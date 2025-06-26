<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Minister;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method

  public static function _arise() {
    global $MINISTER_SPIRIT;

    if (keeper_has('familiar.json')) {
      $MINISTER_SPIRIT = true;  
    }
  }

  public static function test() {
    
  }

}