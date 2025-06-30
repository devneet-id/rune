<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function aether_formatFileSize($size, $precision = 2) {
  if ($size <= 0) return '0 B';

  static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  $base = floor(log($size, 1024));
  $base = min($base, count($units) - 1); // Biar nggak keluar dari array

  $scaledSize = $size / pow(1024, $base);
  aether_arcane('Aether.entity.aether_formatFileSize');
  return sprintf("%.{$precision}f%s", $scaledSize, $units[$base]);
}

function aether_stopwatch() {
  global $AETHER_STOPWATCH;
  $output = microtime(true) - $AETHER_STOPWATCH;
  aether_arcane('Aether.entity.aether_stopwatch');
  return $output;
}


function aether_memoryusage() {
  $output = [memory_get_usage(), memory_get_peak_usage()];
  aether_arcane("Aether.entity.aether_memoryusage");
  return $output;
}


function aether_exit( $force = false ) {
  $memory = aether_memoryusage();
  $stopwatch = aether_stopwatch();
  
  $end = number_format($stopwatch, 4);
  $usage = aether_formatFileSize($memory[0]);
  $peak = aether_formatFileSize($memory[1]);

  
  if (aether_has_entity('whisper')) {
    $icon_execute = "{{color-danger}}ϟ{{color-secondary}}";
    $icon_memory = "{{color-primary}}Ξ{{color-secondary}}";
    
    if (aether_has_entity('chanter')) {
      if (chanter_spell('zero-trust')) {
        $icon_execute = "{{color-warning}}ϟ{{color-secondary}}";
        $icon_memory = "{{color-danger}}Ξ{{color-secondary}}";
      }
    }

    $total_rune = count(aether_arised());
    // aether_dd($arised);
    whisper_echo("\n{{COLOR-SECONDARY}}{{ICON-INFO}}EXIT: {$icon_execute}Execute={$end}s, {$icon_memory}Memory=$usage - ^$peak");
  }else {
    print("\n\nRUNE: Execute={$end}s, Memory=$usage - ^$peak");
  }

  if ($force) {
    aether_arcane_reset();
    exit; die;
  }
}




function aether_has_entity( $function ) {
  return (function_exists($function)) ? true : false;
}
function aether_has_ether( $rune ) {
  return (defined($rune)) ? true : false;
}
function aether_has_essence( $rune ) {
  return (isset($GLOBALS[$rune])) ? true : false;
}





function aether_log( String $text ) {
  // if (aether_has_entity('forger')) {
  //   forger_folder(AETHER_REPO . '/.echoes');
  //   forger_folder(AETHER_LOGS);
  //   aether_arcane($text);
  //   $text = '[' . date('Y-m-d H:i:s') . '] ' . $text;
  //   $last = (forger_file(AETHER_LOGS.date('Y-m-d').'.txt')) ?: '';
  //   $data = $last . PHP_EOL . $text;
  //   $data = str_replace(PHP_EOL.PHP_EOL, PHP_EOL, $data);
  //   forger_set(AETHER_LOGS.date('Y-m-d').'.txt', $data);
  // }
}

function aether_log_clear() {
  // if (aether_has_entity('forger')) {
  //   forger_folder(AETHER_REPO . '/.echoes');
  //   forger_folder(AETHER_LOGS);
  //   forger_set(AETHER_LOGS.date('Y-m-d').'.txt', '');
  // }
}

function aether_dd($data) { 
  // print(PHP_EOL.'AETHER DUBGGING :: START'.PHP_EOL);
  ob_start();
  var_dump($data);
  $x = ob_get_clean();

  $timestamp = number_format(aether_stopwatch(), 4) . 'ms';
  $x = '{{color-primary}}AETHER DEBUGGING ('.$timestamp.') {{color-danger}}::{{color-end}} {{color-secondary}}'.$x;
  $x = str_replace(' ["', ' {{color-danger}}::{{color-end}}{{color-info}}', $x);
  $x = str_replace('"]=>', '{{color-end}}=>', $x);
  $x = str_replace("=>\n", " =>", $x);
  for ($i = 0; $i < 10; $i++) {
    $x = str_replace('=>  ', '=> ', $x);
  }
  $x = str_replace(" => ", " {{color-danger}}-{{color-end}} {{color-secondary}}", $x);
  $x = str_replace(') "', '){{color-end}} "', $x);
  $x = str_replace(') {', '){{color-end}} {', $x);
  $x = str_replace('  ', '{{color-secondary}}:{{color-end}} ', $x);
  
  whisper_clear();
  whisper_echo($x);
  die;
}







/* PHANTASM OF WHISPER */
function aether_whisper( $text ) {
  // if (aether_has_entity('whisper')) {
  //   whisper_echo($text);
  // }else {
  // }
  print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
}
function aether_whisper_echo( $text ) {
  if (aether_has_entity('whisper')) {
    whisper_echo($text);
  }else {
    print(preg_replace('/\{\{.*?\}\}/', '', $text).PHP_EOL);
  }

  aether_arcane("Aether.entity.aether_whisper_echo");
}


function aether_arised() {
  global $AETHER_RUNE_ETHER;
  global $AETHER_RUNE_ESSENCE;
  global $AETHER_RUNE_ENTITY;

  $return = array_merge(
    $AETHER_RUNE_ETHER,
    $AETHER_RUNE_ESSENCE,
    $AETHER_RUNE_ENTITY
  );
  $return = array_unique($return);

  return $return;
}


/* ARCANE
 * need: Forger, Keeper
 *  */
function aether_arcane(String $text, String $value = '') {
  global $AETHER_ARCANE;
  global $AETHER_STOPWATCH;
  global $AETHER_ARCANE_STATE;

  if ($AETHER_ARCANE_STATE) {
    $process = function() use (&$AETHER_ARCANE, &$AETHER_STOPWATCH, &$text, &$value) {
      $now = microtime(true);
      
      $global_stopwatch = $now - $AETHER_STOPWATCH;
    
      $AETHER_ARCANE[] = [
        time(),
        $global_stopwatch,
        $text,
        $value
      ];
    };
  
    if (isset($AETHER_ARCANE[2])) {
      if ($text !== end($AETHER_ARCANE)[2]) {
        $lastkey = array_key_last($AETHER_ARCANE);
        $process();
      }
    }else {
      $process();
    }
  }
}
function aether_arcane_reset() {
  global $AETHER_ARCANE;

  $AETHER_ARCANE = [];
  return true;
}
function aether_arcane_enable() {
  global $AETHER_ARCANE_STATE;

  $AETHER_ARCANE_STATE = true;
  return true;
}
function aether_arcane_disable() {
  global $AETHER_ARCANE_STATE;

  $AETHER_ARCANE_STATE = false;
  return true;
}

function aether_arcane_pretty_print() {
  global $AETHER_ARCANE;

  print(PHP_EOL."ARCANE".PHP_EOL);
  $datas = '';
  foreach ($AETHER_ARCANE as $item) {
    $datetime = date('Y-m-d H:i:s', $item[0]);
    $stopwatch = number_format($item[1], 4);
    $response = $item[2];
    $value = (isset($item[3])) ? $item[3] : '';
    
    if (aether_has_entity('whisper')) {
      if (strpos($item[2], 'manifest') !== false) {
        $state = '{{color-danger}}';
      }else if (strpos($item[2], 'entity') !== false) {
        $state = '{{color-info}}';
      }else {
        $state = '{{color-default}}';
      }
      // whisper_echo("{{color-secondary}}[$datetime] [$stopwatch] {$state}{$response} {{nl}}");
      $datas .= "{{color-secondary}}[$datetime] [$stopwatch] {$state}{$response}: $value {{nl}}";
    }else {
      // print("[$datetime] [$stopwatch] $response" . PHP_EOL);
      $datas .= "[$datetime] [$stopwatch] {$response}: $value " . PHP_EOL;
    }
  }
  if (aether_has_entity('whisper')) {
    whisper_echo($datas);
  }else {
    print($datas);
  }
}






function aether_list_runes() {
  $manifests = [];
  // internal rune
  foreach (glob(AETHER_RUNE_LOCATION . '/*') as $manifest) {
    if (is_dir($manifest)) {
      $manifests[] = basename($manifest);
    }
  }
  // external rune
  if (file_exists(AETHER_REPO . '/.bindrune')) {
    foreach (glob(AETHER_REPO . '/.bindrune/*') as $manifest) {
      if (is_dir($manifest)) {
        $manifests[] = basename($manifest);
      }
    }
  }
  return $manifests;
}