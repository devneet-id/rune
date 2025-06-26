<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function keeper() {
  return true;
}



/* ARCANE
 * todo manipulate arcane 
 * */
function keeper_arcane_process() {
  global $AETHER_ARCANE;
  global $KEEPER_ARCANE;

  $datas = '';
  for ($i=0; $i < count($AETHER_ARCANE); $i++) {
    $item_prev = isset($AETHER_ARCANE[$i-1]) ? $AETHER_ARCANE[$i-1] : NULL;
    $item = $AETHER_ARCANE[$i];

    $datetime = date('Y-m-d H:i:s', $item[0]);
    $stopwatch = number_format($item[1], 4);
    $response = $item[2];
    $value = (isset($item[3])) ? $item[3] : '';

    // stepwatch
    if (!empty($item_prev)) {
      $stepwatch = $item[1] - $item_prev[1];
    }else {
      $stepwatch = $item[1];
    }
    
    // evaluate
    $state = $KEEPER_ARCANE[0][1];
    foreach ($KEEPER_ARCANE as $row) {
      if ($stepwatch >= $row[0]) {
        $state = $row[1];
      }
    }
    
    $stepwatch = number_format($stepwatch, 5);

    $datas .= "[$datetime] - [$stopwatch] - [$stepwatch] - {$response}: - $value - //{$state}" . PHP_EOL;
  }

  $store = forger_item(KEEPER_ECHOES_ARCANE, $datas);
  keeper_arcane_process_store($store);
  return true;
}

function keeper_arcane_process_store( $datas ) {
  forger_item(KEEPER_ECHOES_ARCANES . '/' . date('Y-m-d--H-i-s') . '.txt', $datas);
}

function keeper_arcane_get() {
}



/* ITEM
 * todo keeper manipulate item (JSON)
 *  */
function keeper_item( String $name, $value = '' ) {
  if (empty($value)) {
    $return = keeper_item_get($name);
  }else {
    $return = keeper_item_set($name, $value);
  }

  aether_arcane('Keeper.entity.keeper_item');
  return $return;
}

function keeper_item_set( String $name, $value ) {
  $datas = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  $datas = str_replace("    ", "  ", $datas);
  forger_item(KEEPER_ECHOES . '/' . $name . '.json', $datas);
  
  aether_arcane('Keeper.entity.keeper_item_set');
  return true;
}

function keeper_item_get( String $name ) {
  $datas = forger_item(KEEPER_ECHOES . '/' . $name . '.json');
  $return = json_decode($datas);

  aether_arcane('Keeper.entity.keeper_item_get');
  return $return;
}



/* ECHO
 * todo keeper echoes all of the system
 *  */
function keeper_echo( String $repo, String $name, $value = '' ) {
  if (empty($value)) {
    $return = keeper_echo_get($repo, $name);
  }else {
    $return = keeper_echo_set($repo, $name, $value);
  }

  aether_arcane('Keeper.entity.keeper_echo');
  return $return;
}

function keeper_echo_set( String $repo, String $name, $value ) {
  forger_fix([ 'target'=> $repo, 'type'=> 'repo' ]);
  forger_item($repo . '/' . $name, $value);

  aether_arcane('Keeper.entity.keeper_echo_set');
  return true;
}

function keeper_echo_get( String $repo, String $name ) {
  $datas = forger_item($repo . '/' . $name);
  return $datas;
}


/* SHARDS
 * todo managements file as shards
 *  */
function keeper_shard( Array $file_maps, Bool $is_revoke = false ) {
  if ($is_revoke) {
    keeper_shard_get($file_maps);
  }else {
    keeper_shard_set($file_maps);
  }
}
function keeper_shard_set( Array $file_maps ) {
  $map = [];
  foreach ($file_maps as $row) {
    $map = array_merge($map, forger_trace_recursive($row));
  }

  $map2 = [];
  foreach ($map as $row) {
    if ($row['type'] == 'item') {
      if ($row['ready'] == true) {
        $file = forger_info($row['target']);
        $map2[$file->base] = $file;
      }
    }
  }

  foreach ($map2 as $row) {
    keeper_shard_invoke($row);
  }

  aether_arcane('Keeper.entity.keeper_shard_set');
  return true;
}
function keeper_shard_invoke( Object $forger_info ) {
  $patch = '';
  $source = '';

  $patch = json_encode($forger_info);
  $source = forger_item($forger_info->target);
  $source = cipher_base64($source);
  
  $file = cipher_base64($patch.PHP_EOL.$source);
  $file = cipher_encode($file);

  // not use
  $rune_name = str_replace('/', '-', $forger_info->target . '.rune');

  forger_item(KEEPER_ECHOES_SHARDS . '/' . cipher_hash($rune_name) . '.rune', $file);

  aether_arcane('Keeper.entity.keeper_shard_invoke');
  return true;
}
function keeper_shard_get( Array $file_maps ) {
  foreach ($file_maps as $name) {
    $file = forger_item(KEEPER_ECHOES_SHARDS . '/' . $name . '.rune');
    keeper_shard_revoke($file);
  }

  aether_arcane('Keeper.entity.keeper_shard_get');
  return true;
}
function keeper_shard_revoke( String $raw_source ) {
  $file = cipher_decode($raw_source);
  $file = cipher_base64($file, true);
  $file = explode(PHP_EOL, $file);
  
  $patch = json_decode($file[0]);
  $source = cipher_base64($file[1], true);

  forger_fix(forger_trace($patch->target));
  
  forger_item($patch->target, $source);

  aether_arcane('Keeper.entity.keeper_shard_revoke');
  return true;
}
function keeper_shard_clean() {
  forger_clean(KEEPER_ECHOES_SHARDS, true);
  forger_repo(KEEPER_ECHOES_SHARDS);

  aether_arcane('Keeper.entity.keeper_shard_clean');
  return true;
}


/* GLITCH
 * todo hidden error end report to keeper
 *  */
function keeper_glitch_boot() {  
  forger_item(KEEPER_ECHOES_GLITCH, '');
  
  $handler = function($type, $message, $file = '', $line = '') {
    $data = (object) [
      'time'    => date('Y-m-d H:i:s'),
      'type'    => strtoupper($type),
      'message' => $message,
      'file'    => $file ?: '-',
      'line'    => $line ?: 0
    ];

    forger_item(KEEPER_ECHOES_GLITCH, "[$data->time] [$data->type] [$data->line] [$data->file] $data->message".PHP_EOL, FILE_APPEND);
    
    whisper_echo("{{COLOR-DANGER}}{{ICON-DANGER}} [$data->time] {{COLOR-WARNING}}//$data->type{{nl}}");
    whisper_echo("{{COLOR-INFO}}($data->line) $data->file{{nl}}");
    whisper_echo("{{COLOR-DEFAULT}}$data->message{{nl}}{{nl}}");
    
    if (aether_has_entity('specter')) {
      specter_exit('php '.chanter_arg());
    }

    aether_exit(true);
  };

  // Tangkap error
  set_error_handler(function($errno, $errstr, $errfile, $errline) use ($handler) {
    $handler('error', $errstr, $errfile, $errline);
    return true;
  });

  // Tangkap exception
  set_exception_handler(function($e) use ($handler) {
    $handler('exception', $e->getMessage(), $e->getFile(), $e->getLine());
  });

  // Tangkap fatal error (shutdown)
  register_shutdown_function(function() use ($handler) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
      $handler('fatal', $error['message'], $error['file'], $error['line']);
    }
  });

  ini_set('display_errors', '0');
  error_reporting(E_ALL);
}

function keeper_glitch_detect() {
  global $KEEPER_GLITCH;

  aether_dd($KEEPER_GLITCH);
}



function keeper_is_glitch() {
  if (filesize(KEEPER_ECHOES_GLITCH)) {
    return true;
  }
  return false;
}