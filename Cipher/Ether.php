<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

#NOTE: Acts as a flag to indicate that the CIPHER system is enabled.
define('CIPHER', true);

#NOTE: Contains a list of supported runic cipher variants for encoding/decoding
define('CIPHER_RUNIC_LIST', [
  'default', 'elder_futhark', 'younger_futhark', 'anglo_saxon_futhorc'
]);

#NOTE: Holds the character mapping tables for each runic cipher variant (e.g., default, elder futhark, etc).
define('CIPHER_RUNIC', [
  'default'=> [
    'a' => 'ᚨ', 'b' => 'ᛒ', 'c' => 'ᚲ', 'd' => 'ᛞ',
    'e' => 'ᛖ', 'f' => 'ᚠ', 'g' => 'ᚷ', 'h' => 'ᚺ',
    'i' => 'ᛁ', 'k' => 'ᚴ', 'l' => 'ᛚ', 'm' => 'ᛗ',
    'n' => 'ᚾ', 'o' => 'ᛟ', 'p' => 'ᛈ', 'r' => 'ᚱ',
    's' => 'ᛋ', 't' => 'ᛏ', 'u' => 'ᚢ', 'w' => 'ᚹ',
    'y' => 'ᛇ', 'z' => 'ᛉ'
  ],  
  'elder_futhark' => [
    'a' => 'ᚨ',  'b' => 'ᛒ',  'c' => 'ᚲ',  'd' => 'ᛞ',  'e' => 'ᛖ', 
    'f' => 'ᚠ',  'g' => 'ᚷ',  'h' => 'ᚺ',  'i' => 'ᛁ',  'k' => 'ᚴ', 
    'l' => 'ᛚ',  'm' => 'ᛗ',  'n' => 'ᚾ',  'o' => 'ᛟ',  'p' => 'ᛈ', 
    'r' => 'ᚱ',  's' => 'ᛋ',  't' => 'ᛏ',  'u' => 'ᚢ',  'w' => 'ᚹ', 
    'y' => 'ᛃ',  'z' => 'ᛉ'
  ],
  'younger_futhark' => [
    'a' => 'ᚨ',  'b' => 'ᛒ',  'd' => 'ᛞ',  'e' => 'ᛖ',  'f' => 'ᚠ',
    'g' => 'ᚷ',  'h' => 'ᚺ',  'i' => 'ᛁ',  'k' => 'ᚴ',  'l' => 'ᛚ',
    'm' => 'ᛗ',  'n' => 'ᚾ',  'o' => 'ᛟ',  'r' => 'ᚱ',  's' => 'ᛋ',
    't' => 'ᛏ',  'u' => 'ᚢ',  'y' => 'ᛃ'
  ],
  'anglo_saxon_futhorc' => [
    'a' => 'ᚨ',  'b' => 'ᛒ',  'c' => 'ᚲ',  'd' => 'ᛞ',  'e' => 'ᛖ',
    'f' => 'ᚠ',  'g' => 'ᚷ',  'h' => 'ᚺ',  'i' => 'ᛁ',  'k' => 'ᚲ',
    'l' => 'ᛚ',  'm' => 'ᛗ',  'n' => 'ᚾ',  'o' => 'ᛟ',  'p' => 'ᛈ',
    'r' => 'ᚱ',  's' => 'ᛋ',  't' => 'ᛏ',  'u' => 'ᚢ',  'w' => 'ᚹ',
    'y' => 'ᛃ',  'ð' => 'ᚦ',  'æ' => 'ᚫ',  'ǽ' => 'ᚬ'
  ],
]);