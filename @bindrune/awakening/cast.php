<?php

// RUNE:INITIALIZE
use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;

// RUNE:INSTANCE
Aether::ether()::essence()::entity();
Chanter::ether()::essence()::entity();
Weaver::ether()::essence()::entity();
Whisper::ether()::essence()::entity();
Forger::ether()::essence()::entity();
Cipher::ether()::essence()::entity();


// RUNE:AWAKENING
Aether::begin();
Chanter::begin();

Chanter::cast('awakening', function() {
  Whisper::clear();
  Whisper::echo("{{text-secondary}}///////////////////////////// \n");
  Whisper::echo(" A W A K E N I N G \n");
  Whisper::echo("{{bg-danger}} awaken from the void {{bg-end}}\n\n");


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
  $processing__inspect_raw_old = function($link) {
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
  $processing_revoke = function( $from, $to ) use ($processing__inspect_raw) {
    $result = $processing__inspect_raw($from);

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
        
        $target = AETHER_REPO . DIRECTORY_SEPARATOR . $shard['info']['target'];

        $isHidden = (substr(basename($target), 0, 1) === '.');
        
        if ($isHidden) {
            $path = $target;
            $tmp = $path . '.tmp';
            if (is_dir($path)) {
                rmdir($path);
            }
            file_put_contents($tmp, $shard['source']);
            rename($tmp, $path);
        } else {
            Forger::item($target, $shard['source']);
        }
      }
    }

    return true;
  };

  // check minimum requirement
  if (!version_compare(PHP_VERSION, AETHER_PHP_VERSION, '>=')) {
    Whisper::echo('{{COLOR-ERROR}}{{ICON-ERROR}} Need PHP version '.AETHER_PHP_VERSION.' or higher required.');
    exit;
  }

  
  // start stages
  // sleep(1);
  
  // without kit
  // Whisper::clear();
  Whisper::echo("{{color-secondary}}Will you begin from the first step, \n");
  Whisper::echo("{{color-secondary}}or from the strange artefact? \n\n");
  if (Whisper::call('Begin anew, or use an artefact [y/n]: ') !== 'y') {
    $revoking = $processing_revoke(
      __DIR__ . '/src/Blank+Default.2025-10-01.Anwar+Achilles.rune',
      AETHER_REPO.'/'.AETHER_FILE
    );
    if ($revoking) {
      Whisper::echo("{{COLOR-SUCCESS}}Awakening successfully. \n");
      Aether::exit(true);
    }
  }
  
  // choosing
  // Whisper::clear();
  Whisper::echo("\n{{text-secondary}}///////////////////////////// \n");
  Whisper::echo("{{text-secondary}}Artefact with runite ready to explore: \n");
  // Whisper::echo("{{bg-danger}} # {{end}} A R T E F A C T \n");
  $list = [];
  Whisper::echo("{{text-secondary}} ID TITLE \n\n");
  foreach (glob(__DIR__.'/src/*') as $i=>$row) {
    $info = Forger::info($row);
    if (is_file($row)) {
      $profile = explode('.', urldecode($info->name));
      $ID = $i + 1;
      $title = strtoupper($profile[0]);
      $date = (isset($profile[1])) ? $profile[1] : 'unknown';
      $author = (isset($profile[2])) ? $profile[2] : 'unknown';
      $extension = (isset($profile[3])) ? str_replace(',', ', ', $profile[3]) : 'native';

      $list[$ID] = $row;

      Whisper::echo("[{{text-primary}}$ID{{end}}] {{text-default}}$title \n");
      Whisper::echo("{{text-secondary}} :: $date{{end}} @{{text-danger}}$author{{end}} ");
      Whisper::echo("[{{text-warning}}$extension{{end}}] \n\n");
      // Whisper::echo("[{{text-danger}}$ID{{end}}] $title {{text-secondary}}$date{{end}} {{text-danger}}$author{{end}} {{nl}}");

      // aether_dd($name);

      // $data = explode('--', basename($row));
      // $ID = strtoupper($data[0]);
      // $name = str_replace('.rune', '', str_replace('-', ' ', $data[1]));
      // $default = ($ID=='D') ? ' <- current' : '';
      // Whisper::echo("[$ID] $name {{COLOR-SUCCESS}}$default{{COLOR-DEFAULT}} {{nl}}");
      // $list[$ID] = $row;
    }
  }
  
  // processing
  $selected = Whisper::call('Where would you like to continue your journey? [ID]: ');
  // aether_dd($list[$selected]);
  // aether_exit(true);
  if ($selected) {
    $selected = (empty($selected)) ? 'd' : $selected;
    
    if (!isset($list[$selected])) {
      Whisper::echo('{{COLOR-ERROR}}{{ICON-ERROR}} Template not found');
      exit;
    }
    
    $target = $list[$selected];
    $revoking = $processing_revoke(
      $target,
      AETHER_REPO.'/'.AETHER_FILE
    );
    if ($revoking) {
      $selfFile = AETHER_FILE;
      Whisper::echo("{{text-success}}{{icon-success}} Awakening successfully. \n");
      Whisper::echo("{{text-info}}{{icon-info}}{{end}}{{text-secondary}} Checkout your runite with '{{text-danger}}php $selfFile run{{end}}'. \n");
      
      Aether::exit(true);
    }
  }
});

// Chanter::cast('start', function() {
//   Specter::open("{{SELF}} deploy", [
//     'visible'=> false,
//   ]);

//   Whisper::drain(function($loader) {
//     Whisper::echo("{{COLOR-DANGER}}[$loader] A W A K E N I N G ");
//   }, [
//     'speed' => 100,
//     'infinite' => function() {
//       return Specter::get("{{SELF}} deploy");
//     }
//   ]);
// });

// Chanter::cast('deploy', function() {
//   for ($index = 1; $index <= 3; $index++) {
//     sleep(1);
//     if ($index == 3) {
//       Specter::close("{{SELF}} deploy");
//       exit;
//     }
//   }
// });

// Chanter::cast('tester', function() {
//   file_put_contents(__DIR__.'/trash.txt', AETHER_FILE);
//   exit;
// });

// if (isset($CHANTER_ARGS[1])) {
// }else {}

Chanter::cast('awakening')();