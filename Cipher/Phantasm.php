<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Cipher;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 0.2;
  
  public $main = 'Cipher';

  public $mark = 'BETA';

  public $link = [
    ['Specter', 'manifest', 1.3],
    ['Crafter', 'manifest', 1.2],
    ['Forger', 'manifest', 1.6],
    ['Keeper', 'manifest', 1.6],
    ['Weaver', 'manifest', 1.2],
    ['Whisper', 'manifest', 1.5],
  ];


  public $node = [
    [
      'type' => 'ether',
      'call' => 'CIPHER',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'CIPHER_ALL_VARIANTS',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'CIPHER_VARIANT',
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
      'call' => 'cipher_encode',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'cipher_decode',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_arises($x="")',
      'note' => '',
    ],
  ];




  public function awakening() {}
  
}