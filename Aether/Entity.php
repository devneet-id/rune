<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */
#NOTE: Convert a number of bytes into a human-readable file size (e.g. KB, MB)
function aether_formatFileSize($size, $precision = 2) {
  if ($size <= 0) return '0 B';

  static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  $base = floor(log($size, 1024));
  $base = min($base, count($units) - 1); // Biar nggak keluar dari array

  $scaledSize = $size / pow(1024, $base);
  aether_arcane('Aether.entity.aether_formatFileSize');
  return sprintf("%.{$precision}f%s", $scaledSize, $units[$base]);
}
#NOTE: Measure elapsed time since $AETHER_STOPWATCH was started (in seconds)
function aether_stopwatch() {
  global $AETHER_STOPWATCH;
  $output = microtime(true) - $AETHER_STOPWATCH;
  aether_arcane('Aether.entity.aether_stopwatch');
  return $output;
}
#NOTE: Get current and peak memory usage in bytes
function aether_memoryusage() {
  $output = [memory_get_usage(), memory_get_peak_usage()];
  aether_arcane("Aether.entity.aether_memoryusage");
  return $output;
}

function aether_zero_trust( Mixed $state = '' ) {
  global $AETHER_ZERO_TRUST;
  if (!is_bool($state)) {
    $AETHER_ZERO_TRUST = $state;
  }else {
    return $AETHER_ZERO_TRUST;
  }
}
#NOTE: Exit the program with execution time and memory usage summary (supports pretty output with whisper)
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
    whisper_echo("\n{{COLOR-SECONDARY}}{{ICON-INFO}}EXIT: {$icon_execute}Execute={$end}s, {$icon_memory}Memory=$usage - ^$peak");
  }else {
    print("\n\nRUNE: Execute={$end}s, Memory=$usage - ^$peak");
  }

  if ($force) {
    aether_arcane_reset();
    exit; die;
  }
}

#NOTE: Check if a given function (entity) exists in the current environment
function aether_has_entity( $function ) {
  return (function_exists($function)) ? true : false;
}
#NOTE: Check if a constant (ether) is defined in the runtime
function aether_has_ether( $rune ) {
  return (defined($rune)) ? true : false;
}
#NOTE: Check if a global variable (essence) is set and exists
function aether_has_essence( $rune ) {
  return (isset($GLOBALS[$rune])) ? true : false;
}

#NOTE: Dump and display debug information with colorized formatting, then exit
function aether_dd( $data, $force=false ) {
  ob_start();
  var_dump($data);
  $x = ob_get_clean();

  $translate = [
    '{head-divider}'=> '{{color-danger}} :: {{color-end}}',
    '{tab}'=> '{{color-secondary}}│ {{color-end}}',
    '{key}'=> '{{color-secondary}}♦ {{color-end}}{{color-default}}',
    '{type}'=> '{{color-danger}}::{{color-secondary}}',
    '{end}'=> '{{color-danger}} » {{color-end}}',
  ];
  $trace = debug_backtrace();
  $infile = $trace[0]['file'];
  $inline = $trace[0]['line'];

  $x = "{{bg-danger}} {$inline} {{bg-end}} {$infile}\n\n{{text-secondary}}".$x;
  $x = "{{text-secondary}}///////////////////////////////////{{text-end}}\n D I A G N O S T I C \n".$x;
  $x = str_ireplace("=>\n", "=>", $x);
  $x = preg_replace("/{\n\s*}/", "{}", $x);
  $x = preg_replace('/^(\s*)\["(.+?)"\]=>/m', '$1{key}$2{type}', $x);
  $x = preg_replace('/^(\s*)\[(.+?)\]=>/m', '$1{key}$2{type}', $x);
  for ($i = 0; $i < 10; $i++) {
    $x = str_replace('{type} ', '{type}', $x);
  }
  $x = str_replace(') "', '){end}"', $x);
  $x = str_replace(') {', '){end}{', $x);
  $x = str_replace('  ', '{tab}', $x);
  foreach ($translate as $key => $value) {
    $x = str_ireplace($key, $value, $x);
  }
  
  if ($force) {
    whisper_clear_force();
  }
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

#NOTE: Get all unique arised runes from ether, essence, and entity
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
#NOTE: Track execution events with timestamp and elapsed time (arcane trace logging)
function aether_arcane(String $text, String $value = '') {
  if (aether_zero_trust()) return;

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

    $count = count($AETHER_ARCANE);
    $last1 = $count > 0 ? $AETHER_ARCANE[$count - 1][2] : null;
    $last2 = $count > 1 ? $AETHER_ARCANE[$count - 2][2] : null;

    // Tolak jika $text sama dengan salah satu dari dua terakhir
    if ($text !== $last1 && $text !== $last2) {
      $process();
    }
  }
}
function aether_arcane_old(String $text, String $value = '') {
  if (aether_zero_trust()) return;
  
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
#NOTE: Reset all arcane trace logs
function aether_arcane_reset() {
  global $AETHER_ARCANE;

  $AETHER_ARCANE = [];
  return true;
}
#NOTE: Enable arcane trace logging
function aether_arcane_enable() {
  global $AETHER_ARCANE_STATE;

  $AETHER_ARCANE_STATE = true;
  return true;
}
#NOTE: Disable arcane trace logging
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