<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 *
 * Example:
 * function starter() {}
 */

#NOTE: Placeholder function that currently returns true, possibly for future cipher logic.
function chipher() {
  return true;
}

#NOTE: Generates a unique ID using optional prefix and entropy (via uniqid).
function cipher_id( String $prefix = '', $entropy = false ) {
  return uniqid($prefix, $entropy);
}

#NOTE: Creates a fast and lightweight hash of the input text using 'xxh3' algorithm.
function cipher_hash( String $text ) {
  return hash('xxh3', $text);
}

#NOTE: Encodes or decodes Base64 based on $isDecode flag (0 = encode, 1 = decode).
function cipher_base64( String $text, Int $isDecode = 0 ) {
  if ($isDecode) {
    return base64_decode($text);
  } else {
    return base64_encode($text);
  }
}


/* RUNIC */
#NOTE: Main handler for encoding or decoding runic cipher text using helper functions based on $isDecode flag.
function cipher_runic( String $text, Bool $isDecode = false, String $variant = 'default' ) {
  if ($isDecode) {
    $return = cipher_runic_decode($text, $variant);
  } else {
    $return = cipher_runic_encode($text, $variant);
  }

  aether_arcane('Cipher.entity.cipher_runic');
  return $return;
}
#NOTE: Encodes plain text into runic characters based on the selected variant mapping from CIPHER_RUNIC.
function cipher_runic_encode( String $text, String $variant = 'default' ) {
  $variants = CIPHER_RUNIC;
  $latin_to_runic = $variants[$variant];

  $encoded_text = str_replace(array_keys($latin_to_runic), array_values($latin_to_runic), $text);

  aether_arcane('Cipher.entity.cipher_runic_encode');
  return $encoded_text;
}
#NOTE: Decodes runic characters back into plain text by flipping the selected variant mapping from CIPHER_RUNIC.
function cipher_runic_decode( String $text, String $variant = 'default') {
  $variants = CIPHER_RUNIC;
  $runic_to_latin = array_flip($variants[$variant]);

  $decoded_text = str_replace(array_keys($runic_to_latin), array_values($runic_to_latin), $text);

  aether_arcane('Cipher.entity.cipher_runic_decode');
  return $decoded_text;
}