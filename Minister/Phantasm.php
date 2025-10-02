<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Minister;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 0.2;
  
  public $main = 'Minister';

  public $mark = 'DEVELOPMENT';

  public $link = [
    ['Aether', 'essence', '1.14'],
  ];

  public $node = [
    [
      'type' => 'manifest | entity | essence | ether',
      'call' => 'starter( $x )',
      'note' => '',
    ],
  ];

  public function awakening() {}
  
}