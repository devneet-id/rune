<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

#NOTE: Main entity
function chanter() {
  return true;
}




/* ARG
 * todo get arguments */

#NOTE: Sets or returns the current CLI argument string
function chanter_arg( String $newArg = '' ) {
  global $CHANTER_ARG;

  if ($newArg !== '') {
    $CHANTER_ARG = $newArg;
  }
  
  aether_arcane("Chanter.entity.chanter_arg");
  return $CHANTER_ARG;
}

#NOTE: Parses CLI arguments into cast parts and spell options
function chanter_arg_extract( String $newArg = '' ) {
  global $CHANTER_ARGS;
  global $CHANTER_ARG_CAST;
  global $CHANTER_ARG_SPELL;
  global $CHANTER_SPELL;

  $CHANTER_ARG_CAST = [];
  $CHANTER_ARG_SPELL = $CHANTER_SPELL;
  
  if (!empty($newArg)) {
    $args = explode(' ', $newArg);
  }else {
    $args = $CHANTER_ARGS;
    unset($args[0]);
  }
  
  foreach ($args as $arg) {
    if (strpos($arg, '--')!==false) {
      if (preg_match('/^--([a-zA-Z0-9_-]+)(?:=(.*))?$/', $arg, $match)) {
        $key = str_replace('--', '', $match[1]);
        $value = (isset($match[2])) ? $match[2] : false;
        $value = (!empty($value)) ? urldecode($value) : true;
        $CHANTER_ARG_SPELL[$key] = urlencode($value);
      }
    }else {
      $CHANTER_ARG_CAST[] = $arg;
    }
  }
  
  $CHANTER_ARG_CAST = trim(implode(' ', $CHANTER_ARG_CAST));
  
  aether_arcane("Chanter.entity.chanter_arg_extract");
}

#NOTE: Checks if a specific spell name exists in the global $CHANTER_ARG_SPELL array and its value is not equal to
function chanter_arg_rebase() {
  global $CHANTER_ARG_CAST;
  global $CHANTER_ARG_SPELL;

  $arg = '';
  $arg .= $CHANTER_ARG_CAST;
  foreach ($CHANTER_ARG_SPELL as $spell_name=>$spell_value) {
    $arg .= ' --'.$spell_name;
    if ($spell_value) {
      $arg .= "='".$spell_value."'";
    }
  }

  return $arg;
}



/* CAST
 * todo cast a chanter
 * */

#NOTE: Gets or registers a cast function based on the given arguments.
function chanter_cast( String $args, ?Callable $callable ) {
  if (is_callable($callable)) {
    $return = chanter_cast_get($args);
  }else {
    chanter_cast_set($args, $callable);
    $return = true;
  }

  aether_arcane("Chanter.manifest.cast");
  return $return;
}

#NOTE: Registers a new cast if it doesn’t already exist in the cast list.
function chanter_cast_set( String $arg, ?Callable $callable ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_CAST;
  global $CHANTER_CAST_LIST;
  global $CHANTER_ECHO;

  chanter_arg_extract( $arg );
  
  if (!in_array($CHANTER_ARG_CAST, $CHANTER_CAST_LIST)) {
    $CHANTER_CAST[$CHANTER_ARG_CAST] = $callable;
    $CHANTER_ECHO[$CHANTER_ARG_CAST] = [$CHANTER_ARG_CAST, $arg, ''];
  }
  
  aether_arcane("Chanter.entity.chanter_set");
}

#NOTE: Returns a registered cast function or a fallback if not found
function chanter_cast_get( String $arg ) {
  global $CHANTER_ARG_CAST;
  global $CHANTER_CAST;
  global $CHANTER_ECHO;
  
  chanter_arg_extract( $arg );

  if (isset($CHANTER_CAST[$CHANTER_ARG_CAST])) {
    $return = $CHANTER_CAST[$CHANTER_ARG_CAST];
  }else {
    $return = function() use ($CHANTER_ARG_CAST, $CHANTER_ECHO) {
      
      if (aether_has_entity('whisper')) {
        whisper_echo("{{color-warning}}{{icon-warning}}{{label-warning}}Chanter cast with '$CHANTER_ARG_CAST' not found. \n");
      }else {
        print("[!] Chanter cast with '$CHANTER_ARG_CAST' not found. \n");
      }
      
      $castFound = '';
      foreach (array_keys($CHANTER_ECHO) as $cast) {
        similar_text($cast, $CHANTER_ARG_CAST, $persen);

        if ($persen >= 70) { #NOTE: ambil yang mirip banget aja, bisa diatur sendiri
          $castFound .= $cast.', ';
          break; #NOTE: cukup ambil satu yang paling relevan dulu
        }
      }
      if (aether_has_entity('whisper')) {
        // whisper_echo("{{color-info}}{$cast}, ");
        whisper_echo("{{color-info}}{{icon-info}}{{label-info}}You mean: $castFound \n");
      }

    };
  };

  aether_arcane("Chanter.entity.chanter_get");
  return $return;
}

#NOTE: Checks if a cast function exists for the given arguments
function chanter_cast_has( String $arg ) {
  global $CHANTER_ARG_CAST;

  chanter_arg_extract( $arg );

  if (isset($CHANTER_CAST[$CHANTER_ARG_CAST])) {
    $return = true;
  }else {
    $return = false;
  };

  aether_arcane("Chanter.entity.chanter_has");
  return $return;
}


/* SPELL
 * todo spell checker */

#NOTE: Gets or sets a spell by name depending on whether values are provided
function chanter_spell( String $name, $values = NULL ) {
  if (empty($values)) {
    $return = chanter_spell_get($name);
  }else {
    chanter_spell_set($name, $values);
    $return = true;
  }
  
  aether_arcane("Chanter.manifest.cast");
  return $return;
}

#NOTE: Registers or updates a spell with the given name and value
function chanter_spell_set( String $name, String $value ) {
  global $CHANTER_SPELL;

  $CHANTER_SPELL[$name] = $value;
  
  aether_arcane("Chanter.entity.chanter_spell_set");
  return true;
}

#NOTE: Retrieves the value of a spell argument if it exists
function chanter_spell_get( String $name ) {
  global $CHANTER_ARG_SPELL;

  if (isset($CHANTER_ARG_SPELL[$name])) {
    $return = urldecode($CHANTER_ARG_SPELL[$name]);
  }else {
    $return = false;
  };

  aether_arcane("Chanter.entity.chanter_spell_get");
  return $return;
}

#NOTE: Builds a full CLI-style spell string from all current spell arguments
function chanter_spell_chain() {
  global $CHANTER_ARG_SPELL;

  $spell = '';
  foreach ($CHANTER_ARG_SPELL as $key=>$value) {
    $spell .= '--'.$key.'='.$value.' ';
  }
  
  $spell = trim($spell);
  aether_arcane("Chanter.entity.chanter_spell_chain");
  return $spell;
}

#NOTE: Missing return statement – function does not return the result.
function chanter_spell_has( String $name ) {
  global $CHANTER_ARG_SPELL;

  if (isset($CHANTER_ARG_SPELL[$name]) && $CHANTER_ARG_SPELL[$name] !== '1') {
    $return = true;
  }else {
    $return = false;
  };
}



/* ECHO
 * todo set echo in chanter */

#NOTE: Adds notes to the echo data if the entry doesn’t exist yet.
function chanter_echo_set( String $arg, String $notes ) {
  global $CHANTER_ECHO;

  if (isset($CHANTER_ECHO[$arg])) {
    $CHANTER_ECHO[$arg][2] = $notes;
  }
  
  aether_arcane("Chanter.entity.chanter_echo_set");
  return true;
}

#NOTE: Retrieves the echo data for a given cast argument
function chanter_echo_get( String $arg ) {
  global $CHANTER_ECHO;

  $return = (isset($CHANTER_ECHO[$arg])) ? $CHANTER_ECHO[$arg] : false;
  
  aether_arcane("Chanter.entity.chanter_echo_get");
  return $return;
}



/* WHISPER LATCH */
function chanter_whisper_drain( $run ) {
  whisper_drain_start();
  $x = $run();
  $return = whisper_drain_get();
  whisper_drain_end();
  whisper_echo($return);
}