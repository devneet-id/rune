<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Cipher;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: Post-initialization hook after parent::arise(), for child setup.
  public static function _arise() {}

  #NOTE: Generates unique cipher ID with optional prefix and entropy.
  public static function id( String $prefix = '', $entropy = false ) {
    $return = cipher_id($prefix, $entropy);

    aether_arcane('Cipher.manifest.id');
    return $return;
  }

  #NOTE: Encodes or decodes Base64 text based on the given flag.
  public static function base64( String $text, Int $isDecode = 0 ) {
    $return = cipher_base64($text, $isDecode);

    aether_arcane('Cipher.manifest.base64');
    return $return;
  }

  #NOTE: Encodes or decodes text using runic character mapping.
  public static function runic( String $text, Bool $isDecode = false, String $variant = 'default' ) {
    $return = cipher_runic($text, $isDecode, $variant);

    aether_arcane('Cipher.manifest.runic');
    return $return;
  }

}