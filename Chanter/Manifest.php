<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Chanter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: middleware arise
  public static function _arise() {}

  #NOTE: middleware from aether awaken
  public static function _aether_awaken_before() {
    self::final();
  }

  #NOTE: Prepares and executes a spell casting based on provided arguments or from file.
  public static function final() {
    global $CHANTER_ARG;
    global $CHANTER_ARG_CAST;
    global $CHANTER_ARG_SPELL;
    
    chanter_arg_extract();
    $spell = chanter_spell_chain();
    $cast = $CHANTER_ARG_CAST . ' ' . $spell;
    #NOTE: $cast = chanter_arg_rebase();

    if ($CHANTER_ARG == AETHER_FILE) {
      $run = chanter_cast_get('rune');
    }else {
      $run = chanter_cast_get($cast);
    }

    if (chanter_spell_get('zero-trust')) {
      aether_arcane_disable();
      chanter_whisper_drain( $run );
      
      if (aether_has_entity('specter')) {
        specter_exit('php '.chanter_arg());
      }

      aether_exit(true);
    }else {
      $run();
      
      if (aether_has_entity('specter')) {
        specter_exit('php '.chanter_arg());
      }
    }
    

    aether_arcane("Chanter.manifest.awaken");
  }

  #NOTE: Gets or sets a spell cast definition based on input and returns the result.
  public static function cast( String $args, ?Callable $callable = NULL ) {
    if (empty($callable)) {
      $return = chanter_cast_get($args);
    }else {
      chanter_cast_set($args, $callable);
      $return = self::class;
    }

    aether_arcane("Chanter.manifest.cast");
    return $return;
  }
  
  #NOTE: Gets or sets a spell definition by name and returns the result.
  public static function spell( String $name, $values = NULL ) {
    if (empty($values)) {
      $return = chanter_spell_get($name);
    }else {
      chanter_spell_set($name, $values);
      $return = true;
    }

    aether_arcane("Chanter.manifest.cast");
    return $return;
  }

  #NOTE: Outputs the given text and returns it
  public static function echo( String $cast, String $notes = '' ) {
    if (!empty($notes)) {
      $return = chanter_echo_set($cast, $notes);
    }else {
      $return = chanter_echo_get($cast);
    }

    aether_arcane("Chanter.manifest.echo");
    return $return;
  }

}