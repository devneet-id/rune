<?php


function whisper() {
  return true;
}

/* HELPERS
 *  */
function whisper_clear() {
  echo "\033[2J\033[0;0H";
}
function whisper_clear_force() {
  system('clear');
  system('cls');
  whisper_clear();
}
function whisper_delay( Int $ms ) {
  usleep($ms * 1000);
}


/* EMIT
 * todo set whisper to user 
 * */
function whisper_echo( String $message, Bool $asString = false ) {
  $return = true;

  if ($asString) {
    $return = whisper_echo_get($message);
  }else {
    whisper_echo_set($message);
  }

  aether_arcane('Whisper.entity.whisper_echo');
  return $return;
}

function whisper_echo_get( String $message ) {
  $return = whisper_echo_imbue($message . '{{color-end}}');

  aether_arcane('Whisper.entity.whisper_echo_get');
  return $return;
}

function whisper_echo_set( String $message ) {
  global $WHISPER_DRAIN_STATE;

  $return = whisper_echo_imbue($message . '{{color-end}}');
  
  if ($WHISPER_DRAIN_STATE) {
    whisper_drain_set($return);
  }else {
    print($return);
  }

  aether_arcane('Whisper.entity.whisper_echo_set');
  return true;
}

function whisper_echo_imbue( String $text ) {
  global $WHISPER_VARS;
  global $WHISPER_COLORS;
  global $WHISPER_ICONS;
  global $WHISPER_LABELS;

  // remap
  $maps = $WHISPER_VARS;
  $maps_threedimension = [
    'color'=> $WHISPER_COLORS,
    'icon'=> $WHISPER_ICONS,
    'label'=> $WHISPER_LABELS,
  ];
  foreach ($maps_threedimension as $key=>$row) {
    foreach ($row as $key2 => $value) {
      $maps[ $key.'-'.$key2 ] = $value;
    }
  }

  // set all vars
  // foreach ($maps as $key=>$value) {
  //   $text = str_replace("      ", '{{tab}}', $text);
  // }
  // foreach ($maps as $key=>$value) {
  //   $text = str_replace( strtolower("{{ ".$key." }}"), $value, $text);
  //   $text = str_replace( strtolower("{{".$key."}}"), $value, $text);
  //   $text = str_replace( strtoupper("{{ ".$key." }}"), $value, $text);
  //   $text = str_replace( strtoupper("{{".$key."}}"), $value, $text);
  // }
  $text = weaver_bind_multiple($text, $maps);

  aether_arcane('Whisper.entity.whisper_echo_imbue');
  return $text;
}



/* REAP
 * todo get user whisper
 *  */
function whisper_call( String $prompt ) {
  whisper_echo("{{COLOR-INFO}}{{ICON-INFO}} $prompt");
  $response = trim(fgets(STDIN));

  aether_arcane('Whisper.entity.whisper_call'); 
  return $response;
}


/* LATCH
 *  */
function whisper_drain() {}

function whisper_drain_start() {
  global $WHISPER_DRAIN_STATE;
  $WHISPER_DRAIN_STATE = true;
  return true;
}

function whisper_drain_set( String $message ) {
  global $WHISPER_DRAIN;
  $WHISPER_DRAIN[] = $message;
}

function whisper_drain_get() {
  global $WHISPER_DRAIN;
  return implode('', $WHISPER_DRAIN);
}

function whisper_drain_end() {
  global $WHISPER_DRAIN;
  global $WHISPER_DRAIN_STATE;

  $WHISPER_DRAIN = [];
  $WHISPER_DRAIN_STATE = false;

  return whisper_drain_get();
}


/* IMBUE
 * todo set default variable to text */
