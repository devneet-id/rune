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




    // parse an artefact from its source string (a local file or a fetched one)
    $processing__parse = function($file) {
      $file = trim($file);
      // strip every line ending (CRLF/LF/CR) so artefacts parse on any OS
      $file = str_replace(["\r\n", "\r", "\n"], '', $file);
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

    // read an artefact: its source string when given (--online), else the local file
    $processing__inspect_raw = function($link, $source = null) use ($processing__parse) {
      return $processing__parse($source ?? Forger::item($link));
    };


    /* ONLINE (--online)
    *  The artefact comes straight from the internet:
    *    --revoke=https://.../cast.rune --online   a direct link to a .rune
    *  It is read in memory and never saved to disk.
    *  */
    $processing__download = function($url) {
      if (stripos($url, 'https://') === 0 && !extension_loaded('openssl') && !extension_loaded('curl')) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}PHP can not open https: enable openssl (or curl) in php.ini.{{nl}}");
        return false;
      }
      if (ini_get('allow_url_fopen')) {
        $context = stream_context_create(['http'=> [
          'timeout'=> 30,
          'user_agent'=> 'rune-artefact',
          'follow_location'=> 1,
          'ignore_errors'=> true,
        ]]);
        $body = @file_get_contents($url, false, $context);
        $status = 0;
        foreach ($http_response_header ?? [] as $line) {
          if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $match)) {
            $status = (int) $match[1]; // the last status wins (after redirects)
          }
        }
        if ($body === false || ($status && ($status < 200 || $status > 299))) {
          return false;
        }
        return $body;
      }
      if (function_exists('curl_init')) {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
          CURLOPT_RETURNTRANSFER=> true,
          CURLOPT_FOLLOWLOCATION=> true,
          CURLOPT_TIMEOUT=> 30,
          CURLOPT_USERAGENT=> 'rune-artefact',
        ]);
        $body = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);
        return ($body === false || $status < 200 || $status > 299) ? false : $body;
      }
      Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}PHP can not go online: enable allow_url_fopen (or curl) in php.ini.{{nl}}");
      return false;
    };

    // returns the artefact source string (nothing is written to disk), or false
    $processing__online = function($source) use ($processing__download) {
      $source = trim($source);
      if (!preg_match('#^https?://#i', $source)) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}--online needs a link to a .rune: https://.../cast.rune{{nl}}");
        return false;
      }

      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Fetching $source{{nl}}");
      $body = $processing__download($source);
      if ($body === false) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}The artefact can not be fetched: $source{{nl}}");
        return false;
      }
      if (strpos($body, '[ᚱᚾ]') === false) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}This is not an artefact: $source{{nl}}");
        return false;
      }

      return $body;
    };

    // read --revoke / --inspect: a local file, or (with --online) a link
    $processing__source = function($link, $run) use ($processing__online) {
      if (Chanter::spell('online')) {
        $source = $processing__online($link);
        if ($source !== false) {
          $run($link, $source);
        }
        return;
      }
      if (preg_match('#^https?://#i', $link)) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This is a link, add --online to fetch it.{{nl}}");
        return;
      }
      $run($link);
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
        // OS, release, version and machine only -- no host name (privacy when sharing artefacts)
        'system'=> php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('v') . ' ' . php_uname('m'),
        'php-version'=> phpversion(),
        'version'=> aether_version(),
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
          // create echoes (runite echoes live in .echoes/<runite>/rune.json, written by `rune --resonance`)
          $rune_echoes_file = KEEPER_ECHOES . '/rune.json';
          if (file_exists($rune_echoes_file) || file_exists(KEEPER_ECHOES_SHARD)) {
            $format_echoes = Weaver::item(__DIR__ . '/format-echoes.txt');
            $stack_echoes = [];
            // get echoes runite (built now when `rune --resonance` was never run, so shards are not lost)
            if (file_exists($rune_echoes_file)) {
              $rune_echoes = json_encode(json_decode(Forger::item($rune_echoes_file)));
            }else {
              global $CHANTER_ECHO;
              $memory = aether_memoryusage();
              $rune_echoes = json_encode([
                'FILE'=> AETHER_FILE,
                'VERSION'=> aether_version(),
                'CAST'=> $CHANTER_ECHO,
                'SIZE'=> filesize(AETHER_FILE),
                'MEMORY'=> [$memory[0], $memory[1]],
                'RUNE'=> aether_arised(),
              ]);
            }
            $rune_echoes = Cipher::runic(Cipher::base64($rune_echoes), false, $runic);
            $stack_echoes[] = "RUNITE/////" . $rune_echoes;
            // get echoes shard
            $shard_echoes = file_exists(KEEPER_ECHOES_SHARD) ? json_encode(json_decode(Forger::item(KEEPER_ECHOES_SHARD))) : '{}';
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
            $shards_list = json_decode((string) Forger::item(KEEPER_ECHOES_SHARD)) ?: []; // an empty shard.json carries no shard
            $shard_source = [];
            foreach ($shards_list as $ID=>$shard) {
              $shard = Forger::item(keeper_shard_place($shard->target));
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
    $processing_revoke = function( $link, $source = null ) use ($processing__inspect_raw) {
      $result = $processing__inspect_raw($link, $source);

      
      // check version
      if (version_compare($result->ARTEFACT['version'], aether_version()) > 0) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This artefact needs Rune {$result->ARTEFACT['version']}, you have " . aether_version() . ".{{nl}}");
        return false;
      }
      
      // deploy runite
      Forger::item(AETHER_FILE, $result->RUNITE);

      // deploy shards
      if (isset($result->SHARDS)) {
        foreach ($result->SHARDS as $ID=>$shard) {
          // shards land next to the runite (an absolute target is kept as it is)
          $target = $shard['info']['target'];
          if (!preg_match('#^([A-Za-z]:[\\\\/]|/)#', $target)) {
            $target = AETHER_REPO . DIRECTORY_SEPARATOR . $target;
          }
          Forger::fix(Forger::trace($target));
          Forger::item($target, $shard['source']);
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
        $link = Whisper::call(Chanter::spell('online') ? 'Name or link: ' : 'File location: ');
      }
      if ($link) {
        $processing__source($link, $processing_revoke);
      }
    }


    /* INSPECT
    * */
    
    $processing__inspect = function($link, $source = null) use ($processing__inspect_raw) {
      $result = $processing__inspect_raw($link, $source);

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
        'artefact-php'=> $result->ARTEFACT['php'],
        'runic'=> $result->ARTEFACT['runic'],
        'encryption'=> $result->ARTEFACT['encryption'],
        'system'=> $result->ARTEFACT['system'],
        'version'=> $result->ARTEFACT['version'],
        'my_version'=> aether_version(),
        'my_php'=> phpversion(),
        'my_system'=> php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('v') . ' ' . php_uname('m'),
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
        $link = Whisper::call(Chanter::spell('online') ? 'Name or link: ' : 'File location: ');
      }
      if ($link) {
        $processing__source($link, $processing__inspect);
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
  }
);