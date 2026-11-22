<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Minister;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: Asks the familiar (AI spirit) and returns its answer
  public static function ask( Array|String $texts ) {
    $return = minister_ask((array) $texts);

    aether_arcane('Minister.manifest.ask');
    return $return;
  }

  #NOTE: Returns every answer collected from the familiar
  public static function say() {
    $return = minister_say();

    aether_arcane('Minister.manifest.say');
    return $return;
  }

}