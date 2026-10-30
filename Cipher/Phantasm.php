<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Cipher;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.6';
  
  public $main = 'Cipher';

  public $user = 'Anwar Achilles';

  public $note = 'Keeps secrets and identities of the system—unique ids, fast hashes, base64 and runic cipher that turns text into runes and back, used to seal artefacts.';

  public $link = [
    ['Aether', 'entity', '1.18'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'CIPHER',
      'note' => 'Acts as a flag to indicate that the CIPHER system is enabled.',
    ],
    [
      'type' => 'ether',
      'call' => 'CIPHER_RUNIC_LIST',
      'note' => 'Contains a list of supported runic cipher variants for encoding/decoding',
    ],
    [
      'type' => 'ether',
      'call' => 'CIPHER_RUNIC',
      'note' => 'Holds the character mapping tables for each runic cipher variant (e.g., default, elder futhark, etc).',
    ],
    [
      'type' => 'essence',
      'call' => 'CIPHER',
      'note' => 'main essence',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher()',
      'note' => 'Placeholder function that currently returns true, possibly for future cipher logic.',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_id( String $prefix = "", $entropy = false )',
      'note' => 'Generates a unique ID using optional prefix and entropy (via uniqid).',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_hash( String $text )',
      'note' => 'Creates a fast and lightweight hash of the input text using "xxh3" algorithm.',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_base64( String $text, Int $isDecode = 0 )',
      'note' => 'Encodes or decodes Base64 based on $isDecode flag (0 = encode, 1 = decode).',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_runic( String $text, Bool $isDecode = false, String $variant = "default" )',
      'note' => 'Main handler for encoding or decoding runic cipher text using helper functions based on $isDecode flag.',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_runic_encode( String $text, String $variant = "default" )',
      'note' => 'Encodes plain text into runic characters based on the selected variant mapping from CIPHER_RUNIC.',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_runic_decode( String $text, String $variant = "default")',
      'note' => 'Decodes runic characters back into plain text by flipping the selected variant mapping from CIPHER_RUNIC.',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => 'Post-initialization hook after parent::arise(), for child setup.',
    ],
    [
      'type' => 'manifest',
      'call' => 'id( String $prefix = "", $entropy = false )',
      'note' => 'Generates unique cipher ID with optional prefix and entropy.',
    ],
    [
      'type' => 'manifest',
      'call' => 'base64( String $text, Int $isDecode = 0 )',
      'note' => 'Encodes or decodes Base64 text based on the given flag.',
    ],
    [
      'type' => 'manifest',
      'call' => 'runic( String $text, Bool $isDecode = false, String $variant = "default" )',
      'note' => 'Encodes or decodes text using runic character mapping.',
    ],
  ];

  public function awakening() {}
  
}