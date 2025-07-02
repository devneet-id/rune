<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;
use Rune\Keeper\Manifest as Keeper;

// artefact
Chanter::cast('artefact', function() {
  Weaver::arise();
  Whisper::arise();
  Cipher::arise();
  Forger::arise();
  Keeper::arise();

  $header = Weaver::item(__DIR__ . '/weaver/artefact-header.txt');
  $header = Weaver::bind($header, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);

  if (aether_has_entity('whisper')) {
    Whisper::clear();
    Whisper::echo($header);
  }else {
    aether_whisper($header);
  }


  /* INVOKE
   *  */
  $processing_invoke = function() {
    $prefix_newPage = PHP_EOL.'- - - - -'.PHP_EOL;
    $prefix_item = PHP_EOL;

    $runefile = AETHER_FILE . '.rune';
    $template = '';
    $template .= 'RUNE ARTEFACT - ' . AETHER_VERSION . PHP_EOL;
    $template .= 'created at ' . date('Y-m-d H:i:s') . PHP_EOL;
    
    $template .= $prefix_newPage;
    $rune_source = Forger::item(AETHER_FILE);
    $template .= Cipher::runic(Cipher::base64($rune_source));
    // aether_dd($template);
    
    if (file_exists(AETHER_ECHOES)) {
      Forger::fix([ 
        ['type'=>'repo', 'target'=>KEEPER_ECHOES_SHARDS]
      ]);
      $items = Forger::scan(KEEPER_ECHOES_SHARDS, function($item) {
        return Forger::item($item->target);
      });
      if (!empty($items)) {
        $template .= $prefix_newPage;
        $template .= implode(PHP_EOL, $items);
      }
    }

    $template = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $template);
    
    Forger::item($runefile, $template);

    Whisper::clear()::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully invoked.{{nl}}");
  };
  if (Chanter::spell('invoke')) {
    $processing_invoke();
  }


  /* REVOKE
   *  */
  $processing_revoke = function( $link ) {
    $prefix_newPage = PHP_EOL.'- - - - -'.PHP_EOL;
    $prefix_item = PHP_EOL;

    $target = str_replace('.rune', '', $link);
    $file = Forger::item($link);
    $part = explode($prefix_newPage, $file);
    
    if (isset($part[1])) {
      $base = Cipher::base64(Cipher::runic($part[1], true), true);
      Forger::item($target, $base);
    }
    
    $code = (!empty($part[2])) ? explode(PHP_EOL, $part[2]) : [];
    foreach ($code as $row) {
      keeper_shard_revoke($row);
    }

    Whisper::clear()::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully revoked. {{nl}}");
  };
  if (Chanter::spell('revoke')) {
    Whisper::clear();
    if (Chanter::spell('revoke') !== '1') {
      $link = Chanter::spell('revoke');
    }else {
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}You will revoke the artefact, Where the artefact?{{nl}}");
      $link = Whisper::call('File location: ');
    }
    if ($link) {
      $processing_revoke($link);
    }
  }


  /* INSPECT
   * */
  $processing__inspect_raw = function($link) {
    $link = 'rune.php.rune';

    $prefix_newPage = PHP_EOL.'- - - - -'.PHP_EOL;
    $prefix_item = PHP_EOL;

    $target = str_replace('.rune', '', $link);
    $file = Forger::item($link);
    $part = explode($prefix_newPage, $file);

    $result = [];
    
    // head
    $result['head'] = $part[0];

    // rune
    $rune = strlen($part[1]);
    $result['size'] = $rune;
    $result['rune'] = aether_formatFileSize($rune);

    // item
    $item = [];
    if (!empty($part[2])) {
      foreach (explode($prefix_item, $part[2]) as $row) {
        $row = Cipher::base64(Cipher::runic($row, true), true);
        $row = explode($prefix_item, $row);

        $data = json_decode($row[0]);
        $size = strlen($row[1]);

        $result['size'] += $size;
        $item[] = [
          'file'=> $data->target,
          'type'=> $data->ext,
          'size'=> aether_formatFileSize($size)
        ];
      }
    }
    $result['item'] = $item;
    $result['size'] = aether_formatFileSize($result['size']);

    return (object) $result;
  };
  $processing__inspect = function($link) use ($processing__inspect_raw) {
    $result = $processing__inspect_raw($link);

    // aether_dd($result);
    Whisper::clear(true);
    Whisper::echo("ARTEFACT {{COLOR-DANGER}}::{{COLOR-END}} INSPECT \n");
    Whisper::echo("\n");
    Whisper::echo(weaver_wrap_echo($result->head, 50, "{{tab}}"));
    Whisper::echo("\n\n");
    
    Whisper::echo("{{tab}} {{color-danger}}::{{color-end}}S T A T");
    Whisper::echo("\n{{tab}} - Total Size: {{color-success}}$result->size");
    Whisper::echo("\n{{tab}} - Rune Size: {{color-success}}$result->rune");
    Whisper::echo("\n\n");

    Whisper::echo("{{tab}} {{color-danger}}::{{color-end}}I T E M \n");
    foreach ($result->item as $item) {
      $item = (object) $item;
      $type = strtoupper($item->type);
      $file = str_replace(DIRECTORY_SEPARATOR, '', $item->file);

      Whisper::echo("{{tab}} - ");
      Whisper::echo("{{color-default}}$type");
      Whisper::echo("{{tab}}{{color-success}}$item->size");
      Whisper::echo("{{tab}}{{COLOR-SECONDARY}}$file");
      Whisper::echo("\n");
    }
  };
  if (Chanter::spell('inspect')) {
    Whisper::clear();
    if (Chanter::spell('inspect') !== '1') {
      $link = Chanter::spell('inspect');
    }else {
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}You will inspect the artefact, Where the artefact?{{nl}}");
      $link = Whisper::call('File location: ');
    }
    if ($link) {
      $processing__inspect($link);
    }
  }


  /* SHARD CLEAN */
  if (Chanter::spell('shard-clean')) {
    keeper_shard_clean();
    Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Shards successfully cleaned");
  }



  if (Chanter::spell('shards')) {
    $result = [];
    $no = 1;
    Whisper::echo('{{COLOR-INFO}} Your artefact shard is: {{nl}}');
    foreach (glob(AETHER_ECHOES_ARTEFACT . '/*.rune') as $file) {
      $time = filemtime($file);
      $file = pathinfo($file);
      $file['timestamp'] = date('Y-m-d H:i:s', $time);
      Whisper::echo('['.$no.'] ' . $file['basename'] . ' - ' . date('Y-m-d H:i:s', $time) . ' {{nl}}');
      $result[] = $file;
      $no++;
    }
    keeper_set('shards.json', json_encode($result));
  }

  if (Chanter::spell('remove')) {
    $target = AETHER_ECHOES_ARTEFACT . '/' . Chanter::spell('remove_process');
    if (file_exists($target)) {
      unlink($target);
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully removed.");
    }
  }



});