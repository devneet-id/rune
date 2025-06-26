<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 *
 * Example:
 * function starter() {}
 */

function chipher() {
  return true;
}


function cipher_id( String $prefix = '', $entropy = false ) {
  return uniqid($prefix, $entropy);
}

function cipher_hash( String $text ) {
  return hash('xxh3', $text);
}

function cipher_base64( String $text, Int $isDecode = 0 ) {
  if ($isDecode) {
    return base64_decode($text);
  } else {
    return base64_encode($text);
  }
}


/* RUNIC */
function cipher_runic( String $text, Bool $isDecode = false ) {
  if ($isDecode) {
    $return = cipher_runic_decode($text);
  } else {
    $return = cipher_runic_encode($text);
  }

  aether_arcane('Cipher.entity.cipher_runic');
  return $return;
}
function cipher_runic_encode( String $text, String $variant = 'default' ) {
  $variants = CIPHER_RUNIC;
  $latin_to_runic = $variants[$variant];

  $encoded_text = str_replace(array_keys($latin_to_runic), array_values($latin_to_runic), $text);

  aether_arcane('Cipher.entity.cipher_runic_encode');
  return $encoded_text;
}
function cipher_runic_decode( String $text, String $variant = 'default') {
  $variants = CIPHER_RUNIC;
  $runic_to_latin = array_flip($variants[$variant]);

  $decoded_text = str_replace(array_keys($runic_to_latin), array_values($runic_to_latin), $text);

  aether_arcane('Cipher.entity.cipher_runic_decode');
  return $decoded_text;
}