<?php

// RUNE:INITIALIZE
use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;

// RUNE:INSTANCE
Aether::arise();
Chanter::arise();
Weaver::arise();
Whisper::arise();
Forger::arise();
Cipher::arise();


// RUNE:AWAKENING
Aether::origin();

Chanter::cast('awakening', function() {
  Whisper::clear();
  Whisper::echo("\n{{tab}}RUNE {{COLOR-DANGER}}::{{color-end}} AWAKENING");
  Whisper::echo("\n{{tab}}{{COLOR-SECONDARY}}awaken the rune from the void...\n");

  $rune = '';
  $processing = function( $rune, $timing ) {
    $target = Forger::item(AETHER_REPO.'/'.AETHER_FILE);
    $runefile = Forger::item(AETHER_REPO.'/'.AETHER_FILE.'.rune', $rune);
    
    aether_dd($target);

    $target = str_replace('use Rune\Aether\Manifest as Aether;', '', $target);
    $target = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $target);
    $target = str_replace('<?php', $rune->act, $target);
    $target = str_replace($act_void, '', $target);
    $target = str_replace('Aether::awakening();', $rune->main, $target);

    Forger::item( AETHER_REPO.'/'.AETHER_FILE, $target);
    foreach ($rune->asset as $asset) {
      Forger::clone($asset['location'], $asset['destination']);
    }

    Whisper::drain(function($loader) {
      Whisper::echo("{{COLOR-DANGER}}[$loader] A W A K E N I N G ");
    },[
      'speed' => 100,
      'delay' => $timing,
    ]);
    Whisper::clear();
    Whisper::clear_force();
    Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} A W A K E N I N G ");
  };
  $processing_revoke = function( $from, $to ) {
    $prefix_newPage = PHP_EOL.'- - - - -'.PHP_EOL;
    $prefix_item = PHP_EOL;

    $target = $to;
    $file = Forger::item($from);
    $part = explode($prefix_newPage, $file);
    
    if (isset($part[1])) {
      $base = cipher_base64(cipher_decode($part[1]), true);
      Forger::item($target, $base);
    }
    
    $code = (!empty($part[2])) ? explode(PHP_EOL, $part[2]) : [];
    foreach ($code as $row) {
      $row = json_decode(cipher_base64(cipher_decode($row), true));

      foreach ($row->items as $item) {
        $source = cipher_base64($item->source, true);
        Forger::fix(Forger::trace((AETHER_REPO . $item->dirname)));
        Forger::item(AETHER_REPO . $item->target, $source);
      }
    }

    Whisper::clear();
    Whisper::echo("\n{{tab}}RUNE {{COLOR-DANGER}}::{{color-end}} AWAKENED {{COLOR-SUCCESS}}{{ICON-SUCCESS}}");
    Whisper::echo("\n{{tab}}{{COLOR-SECONDARY}}Check with command {{color-danger}}php ".AETHER_FILE."\n");
  };

  // check minimum requirement
  if (!version_compare(PHP_VERSION, AETHER_PHP_VERSION, '>=')) {
    Whisper::echo('{{COLOR-ERROR}}{{ICON-ERROR}} Need PHP version '.AETHER_PHP_VERSION.' or higher required.');
    exit;
  }

  
  // start stages
  sleep(1);
  
  // without kit
  Whisper::clear();
  Whisper::echo('you will choose rank D as default, {{nl}}Did you want to choose another rank?{{nl}}');
  if (Whisper::call('Enter your answer [y/n]: ') !== 'y') {
    $processing_revoke(
      __DIR__ . '/rank/d--plain.rune',
      AETHER_REPO.'/'.AETHER_FILE
    );
    aether_exit(true);
  }
  
  
  // choosing
  Whisper::clear();
  Whisper::echo('Choose you want to use. {{nl}}');
  Whisper::echo('');
  $list = [];
  Whisper::echo("{{COLOR-SECONDARY}}[ID] NAME {{nl}}");
  foreach (glob(__DIR__.'/rank/*') as $row) {
    if (is_file($row)) {
      $data = explode('--', basename($row));
      $ID = strtoupper($data[0]);
      $name = str_replace('.rune', '', str_replace('-', ' ', $data[1]));
      $default = ($ID=='D') ? ' <- current' : '';
      Whisper::echo("[$ID] $name {{COLOR-SUCCESS}}$default{{COLOR-DEFAULT}} {{nl}}");
      $list[$ID] = $row;
    }
  }
  
  
  // processing
  $selected = Whisper::call('Enter kit ID: ');
  if ($selected) {
    $selected = (empty($selected)) ? 'd' : $selected;
    
    if (!isset($list[strtoupper($selected)])) {
      Whisper::echo('{{COLOR-ERROR}}{{ICON-ERROR}} Template not found');
      exit;
    }

    
    $target = $list[strtoupper($selected)];
    $processing_revoke(
      $target,
      AETHER_REPO.'/'.AETHER_FILE
    );
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