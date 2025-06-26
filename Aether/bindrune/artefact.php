<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;
use Rune\Keeper\Manifest as Keeper;

// artefact
Chanter::cast('artefact', function() {
  Forger::entity();
  Cipher::ether();
  Cipher::entity();
  Keeper::ether();

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
    $template .= cipher_encode(cipher_base64($rune_source));
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
      $base = cipher_base64(cipher_decode($part[1]), true);
      Forger::item($target, $base);
    }
    
    $code = (!empty($part[2])) ? explode(PHP_EOL, $part[2]) : [];
    foreach ($code as $row) {
      keeper_shard_revoke($row);
      // $row = json_decode(cipher_base64(cipher_decode($row), true));

      // foreach ($row->items as $item) {
      //   $source = cipher_base64($item->source, true);
      //   Forger::fix(Forger::trace((AETHER_REPO . $item->dirname)));
      //   Forger::item(AETHER_REPO . $item->target, $source);
      // }
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
  // if (Chanter::spell('revoke_option')) {
  //   $link = Chanter::spell('revoke_option');
  //   if ($link) {
  //     $target = str_replace('.rune', '', $link);
  //     $file = Forger::item($link);
  //     $part = explode(PHP_EOL.PHP_EOL, $file);

  //     $base = cipher_base64(cipher_decode($part[1]), true);
  //     Forger::item($target);
  //     Forger::item($target, $base);

  //     $code = explode(PHP_EOL, $part[2]);
  //     foreach ($code as $row) {
  //       $row = json_decode(cipher_base64(cipher_decode($row), true));

  //       foreach ($row->items as $item) {
  //         $source = cipher_base64($item->source, true);
  //         Forger::fix(Forger::trace((AETHER_REPO . $item->dirname);
  //         Forger::item(AETHER_REPO . $item->target);
  //         Forger::item(AETHER_REPO . $item->target, $source);
  //       }
  //     }      
  //   }
  // }




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