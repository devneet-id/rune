<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 *
 * Example:
 * function starter() {}
 */

function chipher() {}


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

function cipher_encode( String $text, String $variant = 'default' ) {
  $variants = CIPHER_VARIANT;
  $latin_to_runic = $variants[$variant];

  // Encode menggunakan str_replace untuk setiap huruf kecil
  $encoded_text = str_replace(array_keys($latin_to_runic), array_values($latin_to_runic), $text);

  return $encoded_text;
}

function cipher_decode( String $text, String $variant = 'default') {
  $variants = CIPHER_VARIANT;
  $runic_to_latin = array_flip($variants[$variant]);

  // Decode menggunakan str_replace
  $decoded_text = str_replace(array_keys($runic_to_latin), array_values($runic_to_latin), $text);

  return $decoded_text;
}