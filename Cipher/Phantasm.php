<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Cipher;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.0;
  
  public $main = 'Cipher';

  public $link = [
    ['Aether', 'ether:essence:entity', 1.9],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'CIPHER',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'CIPHER_RUNIC_LIST',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'CIPHER_RUNIC',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'CIPHER',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_id',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_hash',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_base64',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_runic',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_runic_encode',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_runic_decode',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'id( String $prefix = \'\', $entropy = false )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'base64( String $text, Int $isDecode = 0 )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'runic( String $text, Bool $isDecode = false )',
      'note' => '',
    ],
  ];

  public function awakening() {}
  
}