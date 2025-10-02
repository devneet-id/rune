<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;
use Rune\Keeper\Manifest as Keeper;

// artefact
Chanter::cast(
  arg:'artefact',
  echo: "To handle with shard & artefact.",
  execute: function() {
    Weaver::ether()::essence()::entity();
    Whisper::ether()::essence()::entity();
    Cipher::ether()::essence()::entity();
    Forger::ether()::essence()::entity();
    Keeper::ether()::essence()::entity();

    global $FORGER_KEEPER_SHARD;

    $FORGER_KEEPER_SHARD = false;

    $header = Weaver::item(__DIR__ . '/header.txt');
    $header = Weaver::bind($header, [
      'AETHER-FILE'=> AETHER_FILE,
    ]);

    Whisper::clear();
    Whisper::echo($header);




    $processing__inspect_raw = function($link) {
      $target = str_replace('.rune', '', $link);
      $file = Forger::item($link);
      $file = trim($file);
      $file = str_replace(PHP_EOL, '', $file);
      $file = str_replace("\n", '', $file);
      $template = explode("[ᚱᚾ] ", $file);
      
      $format = [];
      $format['lore'] = $template[0];

      foreach ($template as $key=>$part) {
        if ($key!==0) {
          $part = explode("  ::", $part);
          $head = str_replace(' ', '', $part[0]);
          unset($part[0]);
    
          if (count($part) > 1) {
            $format[$head] = $part;
          }else {
            $format[$head] = $part[1];
          }
        }
      }

      $artefact = [];
      foreach ($format['ARTEFACT'] as $part) {
        $part = explode("=", $part);
        $artefact[$part[0]] = $part[1];
      }
      $format['ARTEFACT'] = $artefact;

      

      // runite
      $runite = $format['RUNITE'];
      $runite = Cipher::base64(Cipher::runic($runite, true, $artefact['runic']), true);
      $format['RUNITE'] = $runite;
      
      // echoes
      if (isset($format['ECHOES'])) {
        $echoes = $format['ECHOES'];
        $reechoes = [];
        foreach ($echoes as $echo) {
          $echo = explode("/////", $echo);
          $echo_id = $echo[0];
          $echo_value = Cipher::base64(Cipher::runic($echo[1], true, $artefact['runic']), true);
          $echo_value = json_decode($echo_value, true);
          $reechoes[$echo_id] = $echo_value;
        }
        $echoes = $reechoes;
        $format['ECHOES'] = $echoes;
      }


      // shards
      if (isset($format['SHARDS'])) {
        $shards = $format['SHARDS'];
        $reshards = [];
        if (!empty($echoes['SHARD'])) {
          foreach ($shards as $shard) {
            $shard = explode("/////", $shard);
            $shard_id = $shard[0];
            $shard_info = $echoes['SHARD'][$shard[0]];
            $shard_source = Cipher::base64(Cipher::runic($shard[1], true, $artefact['runic']), true);
            $reshards[$shard_id] = [
              'info'=> $shard_info,
              'source'=> $shard_source
            ];
          }
        }
        $shards = $reshards;
        $format['SHARDS'] = $shards;
      }

      return (object) $format;
    };




    /* INVOKE
    *  */
    $processing_invoke = function() {
      $runic_list = CIPHER_RUNIC_LIST;
      shuffle($runic_list);
      $runic = $runic_list[0];

      $format = Weaver::item(__DIR__ . '/format.txt');
      $format = Weaver::bind($format, [
        'time'=> time(),
        'system'=> php_uname(),
        'php'=> phpversion(),
        'version'=> AETHER_VERSION,
        'runic'=> $runic,
        'encryption'=> 'base64',
      ]);

      $runite = Forger::item(AETHER_FILE);
      $runite = Cipher::runic(Cipher::base64($runite), false, $runic);
      $format_runite = Weaver::item(__DIR__ . '/format-runite.txt');
      $format_runite = Weaver::bind($format_runite, ['runite'=> $runite]);
      $format .= "\n\n".$format_runite;

      // dd('x');
      if (defined('KEEPER_ECHOES')) {
        if (file_exists(KEEPER_ECHOES)) {
          // create echoes
          if (file_exists(KEEPER_ECHOES . '/' . AETHER_FILE . '.json')) {
            $format_echoes = Weaver::item(__DIR__ . '/format-echoes.txt');
            $stack_echoes = [];
            // get echoes runite
            $rune_echoes = json_encode(json_decode(Forger::item(KEEPER_ECHOES . '/' . AETHER_FILE . '.json')));
            $rune_echoes = Cipher::runic(Cipher::base64($rune_echoes), false, $runic);
            $stack_echoes[] = "RUNITE/////" . $rune_echoes;
            // get echoes shard
            $shard_echoes = json_encode(json_decode(Forger::item(KEEPER_ECHOES . '/shard.json')));
            $shard_echoes = Cipher::runic(Cipher::base64($shard_echoes), false, $runic);
            $stack_echoes[] = "SHARD/////" . $shard_echoes;
            // create echoes
            $echoes = implode("\n  ::", $stack_echoes);
            // aether_dd($echoes);
            $format_echoes = Weaver::bind($format_echoes, ['echoes'=> $echoes]);
            $format .= "\n\n".$format_echoes;
          }
          // create shards
          if (file_exists(KEEPER_ECHOES_SHARD)) {
            $format_shards = Weaver::item(__DIR__ . '/format-shards.txt');
            // $items = Forger::scan(KEEPER_ECHOES_SHARDS, function($item) {
            //   return Forger::item($item->target);
            // });
            $shards_list = json_decode(Forger::item(KEEPER_ECHOES_SHARD));
            $shard_source = [];
            foreach ($shards_list as $ID=>$shard) {
              $shard = Forger::item($shard->target);
              $shard = Cipher::runic(Cipher::base64($shard), false, $runic);
              $shard_source[] = $ID . "/////" . $shard;
            }
            $shards = implode("\n  ::", $shard_source);
            $format_shards = Weaver::bind($format_shards, ['shards'=> $shards]);
            $format .= "\n\n".$format_shards;
          }
        }
      }

      $runefile = AETHER_FILE . '.rune';
      Forger::item($runefile, $format);

      // $format = trim($format);
      // $page = explode("[ᚱᚾ] ", $format);
      // aether_dd($page);

      // $prefix_newPage = "\n- - - - -\n";
      // $prefix_item = "\n";
      // $runefile = AETHER_FILE . '.rune';
      // $template = '';
      // $template .= 'RUNE ARTEFACT - ' . AETHER_VERSION . "\n";
      // $template .= 'created at ' . date('Y-m-d H:i:s') . "\n";
      // $template .= $prefix_newPage;
      // $rune_source = Forger::item(AETHER_FILE);
      // $template .= Cipher::runic(Cipher::base64($rune_source));
      // if (file_exists(AETHER_ECHOES)) {
      //   Forger::fix([ 
      //     ['type'=>'repo', 'target'=>KEEPER_ECHOES_SHARDS]
      //   ]);
      //   $items = Forger::scan(KEEPER_ECHOES_SHARDS, function($item) {
      //     return Forger::item($item->target);
      //   });
      //   if (!empty($items)) {
      //     $template .= $prefix_newPage;
      //     $template .= implode("\n", $items);
      //   }
      // }
      // $template = str_replace("\n\n", "\n", $template);
      // Forger::item($runefile, $template);

      Whisper::clear()::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Artefact successfully invoked.{{nl}}");
    };
    if (Chanter::spell('invoke')) {
      $processing_invoke();
    }


    /* REVOKE
    *  */
    $processing_revoke = function( $link ) use ($processing__inspect_raw) {
      $result = $processing__inspect_raw($link);

      
      // check version
      if (version_compare($result->ARTEFACT['version'], AETHER_VERSION) > 0) {
        return false;
      }
      
      // deploy runite
      Forger::item(AETHER_FILE, $result->RUNITE);

      // deploy shards
      if (isset($result->SHARDS)) {
        foreach ($result->SHARDS as $ID=>$shard) {
          Forger::fix(Forger::trace($shard['info']['target']));
          Forger::item($shard['info']['target'], $shard['source']);
        }
      }


      // $prefix_newPage = "\n- - - - -\n";
      // $prefix_item = "\n";

      // $target = str_replace('.rune', '', $link);
      // $file = Forger::item($link);
      // $part = explode($prefix_newPage, $file);
      
      // if (isset($part[1])) {
      //   $base = Cipher::base64(Cipher::runic($part[1], true), true);
      //   Forger::item($target, $base);
      // }
      
      // $code = (!empty($part[2])) ? explode("\n", $part[2]) : [];
      // foreach ($code as $row) {
      //   keeper_shard_revoke($row);
      // }

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
    
    $processing__inspect = function($link) use ($processing__inspect_raw) {
      $result = $processing__inspect_raw($link);

      Whisper::clear(true);
      $template = Weaver::item(__DIR__.'/inspect.txt');

      // list artefacts
      $casts = '';
      $runes = '';
      $shards = '';
      if (isset($result->ECHOES)) {
        if (isset($result->ECHOES['RUNITE'])) {
          foreach ($result->ECHOES['RUNITE']['CAST'] as $cast) {
            $casts .= "{{text-secondary}} ♦ {{text-default}}$cast[1] \n";
            if (!empty($cast[2])) {
              $casts .= "{{tab}}{{text-secondary}} ≈ $cast[2] \n";
            }
          }
          foreach ($result->ECHOES['RUNITE']['RUNE'] as $rune) {
            $runes .= "{{text-secondary}} ♦ {{text-default}}$rune \n";
          }
        }
        if (isset($result->ECHOES['SHARD'])) {
          foreach ($result->ECHOES['SHARD'] as $ID=>$shard) {
            $shard = (object) $shard;
            $size = aether_formatFileSize(strlen($result->SHARDS[$ID]['source']));
            $shards .= "{{text-secondary}} ♦ {{text-info}}$shard->ext {{text-danger}}$size {{text-default}}$shard->target \n";
          }
        }
      }

      // binding template
      $template = Weaver::bind($template, [
        'lore' => $result->lore,
        'php'=> $result->ARTEFACT['php'],
        'runic'=> $result->ARTEFACT['runic'],
        'encryption'=> $result->ARTEFACT['encryption'],
        'system'=> $result->ARTEFACT['system'],
        'version'=> $result->ARTEFACT['version'],
        'my_version'=> AETHER_VERSION,
        'my_php'=> phpversion(),
        'my_system'=> php_uname(),
        'cast'=> $casts,
        'rune'=> $runes,
        'shard'=> $shards,
        'created_at'=> date('[H:i] d/m/Y', $result->ARTEFACT['time']),
      ]);

      // output
      Whisper::echo($template);

      // Whisper::echo(weaver_wrap_echo)

      // Whisper::clear(true);
      // Whisper::echo("ARTEFACT {{COLOR-DANGER}}::{{COLOR-END}} INSPECT \n");
      // Whisper::echo("\n");
      // Whisper::echo(weaver_wrap_echo($result->head, 50, "{{tab}}"));
      // Whisper::echo("\n\n");
      
      // Whisper::echo("{{tab}} {{color-danger}}::{{color-end}}S T A T");
      // Whisper::echo("\n{{tab}} - Total Size: {{color-success}}$result->size");
      // Whisper::echo("\n{{tab}} - Rune Size: {{color-success}}$result->rune");
      // Whisper::echo("\n\n");

      // Whisper::echo("{{tab}} {{color-danger}}::{{color-end}}I T E M \n");
      // foreach ($result->item as $item) {
      //   $item = (object) $item;
      //   $type = strtoupper($item->type);
      //   $file = str_replace(DIRECTORY_SEPARATOR, '', $item->file);

      //   Whisper::echo("{{tab}} - ");
      //   Whisper::echo("{{color-default}}$type");
      //   Whisper::echo("{{tab}}{{color-success}}$item->size");
      //   Whisper::echo("{{tab}}{{COLOR-SECONDARY}}$file");
      //   Whisper::echo("\n");
      // }
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




    /* SHARD RESULT */
    if (Chanter::spell('shard-list')) {
      $shards = Forger::item(KEEPER_ECHOES_SHARD);
      $shards = json_decode($shards);

      Whisper::clear(true);
      Whisper::echo("{{text-secondary}}///////////////////////////{{text-end}} \n");
      Whisper::echo("{{tab}}{{text-default}}A R T E F A C T \n");
      Whisper::echo("{{text-secondary}}Confirm your shard, this shard list will build in artefact. \n\n");
      Whisper::echo("{{text-secondary}}[NO] [ID] [EXTENSION] [TARGET] \n");
      $no = 1;
      foreach ($shards as $ID=>$shard) {
        Whisper::echo("[$no]{{text-danger}} $ID {{text-info}} $shard->ext {{text-default}}$shard->target \n");
        $no++;
      }
    }

    /* SHARD REMOVE */
    if (Chanter::spell('shard-remove')) {
      Whisper::clear();
      if (Chanter::spell('shard-remove') !== '1') {
        $input = Chanter::spell('shard-remove');
      }else {
        Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}}{{LABEL-INFO}}Insert ID of the shard.{{nl}}");
        $input = Whisper::call('File location: ');
      }
      if ($input) {
        $shards = Forger::item(KEEPER_ECHOES_SHARD);
        $shards = json_decode($shards);
        if (isset($shards->$input)) {
          unset($shards->$input);
          Forger::item(KEEPER_ECHOES_SHARD, json_encode($shards, JSON_PRETTY_PRINT));
          Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Shard successfully removed");
        }
      }
    }

    /* SHARD CLEAN */
    if (Chanter::spell('shard-clean')) {
      Forger::item(KEEPER_ECHOES_SHARD, '{}');
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
  }
);