<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Cipher;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  // create next static method

  public static function _arise() {}

  public static function id( String $prefix = '', $entropy = false ) {
    $return = cipher_id($prefix, $entropy);

    aether_arcane('Cipher.manifest.id');
    return $return;
  }

  public static function base64( String $text, Int $isDecode = 0 ) {
    $return = cipher_base64($text, $isDecode);

    aether_arcane('Cipher.manifest.base64');
    return $return;
  }

  public static function runic( String $text, Bool $isDecode = false ) {
    $return = cipher_runic($text, $isDecode);

    aether_arcane('Cipher.manifest.runic');
    return $return;
  }

}