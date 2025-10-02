<?php

#NOTE: main entity
function whisper() {
  return true;
}

/* HELPERS
 *  */
#NOTE: Clear terminal screen using ANSI escape codes
function whisper_clear() {
  echo "\033[2J\033[0;0H";
}
#NOTE: Force clear terminal using system commands (Linux & Windows)
function whisper_clear_force() {
  system('clear');
  system('cls');
  whisper_clear();
}
#NOTE: Delay output for a given number of milliseconds
function whisper_delay( Int $ms ) {
  usleep($ms * 1000);
}


/* EMIT
 * todo set whisper to user 
 * */
#NOTE: Display or retrieve a formatted message with color/icon support
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
#NOTE: Return a string with applied whisper styles (colors, icons, labels)
function whisper_echo_get( String $message ) {
  $return = whisper_echo_imbue($message . '{{color-end}}');

  aether_arcane('Whisper.entity.whisper_echo_get');
  return $return;
}
#NOTE: Print the formatted message or route to drain if enabled
function whisper_echo_set( String $message ) {
  global $WHISPER_DRAIN_STATE;

  $return = whisper_echo_imbue($message . '{{color-end}}');
  
  if ($WHISPER_DRAIN_STATE) {
    whisper_drain_set($return);
  }else {
    // print($return);
    echo $return;
  }

  aether_arcane('Whisper.entity.whisper_echo_set');
  return true;
}
#NOTE: Apply all whisper formatting to the text (colors, icons, labels)
function whisper_echo_imbue( String $text ) {
  global $WHISPER_VARS;
  global $WHISPER_COLORS;
  global $WHISPER_BG_COLORS;
  global $WHISPER_ICONS;
  global $WHISPER_LABELS;

  // remap
  $maps = $WHISPER_VARS;
  $maps_threedimension = [
    'bg'=> $WHISPER_BG_COLORS,
    'text'=> $WHISPER_COLORS,
    'color'=> $WHISPER_COLORS,
    'icon'=> $WHISPER_ICONS,
    'label'=> $WHISPER_LABELS,
  ];
  foreach ($maps_threedimension as $key=>$row) {
    foreach ($row as $key2 => $value) {
      $maps[ $key.'-'.$key2 ] = $value;
    }
  }
  $text = weaver_bind_multiple($text, $maps);
  aether_arcane('Whisper.entity.whisper_echo_imbue');
  return $text;
}



/* CALL
 * todo get user whisper
 *  */
#NOTE: Prompt user for input via terminal with styled message
function whisper_call( String $prompt ) {
  whisper_echo("{{text-warning}}>>>{{text-end}} $prompt");
  $response = trim(fgets(STDIN));

  aether_arcane('Whisper.entity.whisper_call'); 
  return $response;
}


/* DRAIN
 *  */

#NOTE: Start buffering whisper output instead of printing directly
function whisper_drain_start() {
  global $WHISPER_DRAIN_STATE;
  $WHISPER_DRAIN_STATE = true;
  return true;
}
#NOTE: Store a line of whisper output into the drain buffer
function whisper_drain_set( String $message ) {
  global $WHISPER_DRAIN;
  $WHISPER_DRAIN[] = $message;
}
#NOTE: Retrieve all buffered whisper output as a single string
function whisper_drain_get() {
  global $WHISPER_DRAIN;
  return implode('', $WHISPER_DRAIN);
}
#NOTE: Clear the whisper output buffer and disable buffering
function whisper_drain_end() {
  global $WHISPER_DRAIN;
  global $WHISPER_DRAIN_STATE;

  $WHISPER_DRAIN = [];
  $WHISPER_DRAIN_STATE = false;

  return true;
}


/* IMBUE
 * todo set default variable to text */