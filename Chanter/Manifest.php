<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Chanter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function begin() {
    // bindrune
    $bindrunes = glob(\Rune\Ethereal::$bindrune . "/chanter/*");
    foreach ($bindrunes as $app) {
      if (file_exists($app . "/cast.php")) {
        require_once $app . "/cast.php";
      }
    }
  }

  #NOTE: Prepares and executes a spell casting based on provided arguments or from file.
  public static function end() {
    global $CHANTER_ARG;
    global $CHANTER_ARG_CAST;
    global $CHANTER_ARG_SPELL;
    
    chanter_arg_extract();
    $spell = chanter_spell_chain();
    $cast = $CHANTER_ARG_CAST . ' ' . $spell;

    if ($CHANTER_ARG == AETHER_FILE) {
      $run = chanter_cast_get('rune');
    }else {
      $run = chanter_cast_get($cast);
    }

    if (chanter_spell_get('zero-trust')) {
      aether_zero_trust(true);
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
  public static function cast( String $arg, ?Callable $execute = NULL, String $echo = '' ) {
    // set cast
    if (empty($execute)) {
      $return = chanter_cast_get($arg);
    }else {
      chanter_cast_set($arg, $execute);
      $return = self::class;
    }

    // set echo
    if (!empty($echo)) {
      chanter_echo_set($arg, $echo);
    }

    aether_arcane("Chanter.manifest.cast");
    return $return;
  }

  #NOTE: Gets or sets a spell definition by key and returns the result.
  public static function spell( String $key, Mixed $value = NULL, Mixed $fail = false ) {
    if (empty($value)) {
      $return = chanter_spell_get($key);
    }else {
      chanter_spell_set($key, $value);
      $return = true;
    }

    aether_arcane("Chanter.manifest.cast");
    return $return;
  }

  #NOTE: Outputs the given text and returns it
  public static function echo( String $arg, String $note = '' ) {
    if (!empty($note)) {
      $return = chanter_echo_set($arg, $note);
    }else {
      $return = chanter_echo_get($arg);
    }

    aether_arcane("Chanter.manifest.echo");
    return $return;
  }

}