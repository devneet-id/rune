<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Specter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.3;
  
  public $main = 'Specter';

  public $user = 'Anwar Achilles';

  public $note = 'Enables out-of-process operations through soul-like abstractions—designed for managing complex, asynchronous, or decoupled executions by temporarily separating logic from the main flow while maintaining active control.';

  public $link = [
    ['Keeper', 'manifest', 1.6],
    ['Aether', 'manifest:essence', 1.9],
    ['Weaver', 'manifest', 1.2],
    ['Chanter', 'manifest', 1.5],
    ['Cipher', 'manifest', 0.2],
    ['Crafter', 'manifest', 1.2],
    ['Forger', 'essence:entity:manifest', 1.6],
    ['Whisper', 'manifest:ether', 1.5],
  ];


  public $node = [
    [
      'type' => 'ether',
      'call' => 'ETHER',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_ECHOES_SOUL',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_ECHOES_CAST',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_CAST_DEFAULT',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_CAST_ARG_DEFAULT',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_SEER_OPTION',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_ITEMS',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_STATS',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_SOUL',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_CAST',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_folder',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_set',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_get',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_save',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_remove',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_cast_set',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_cast_get',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_cast_save',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_seer_set',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_exit',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_setEchoes()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_awaken()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'awaken()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'observer( $repo, $callback )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'devserver( $configure )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'soul( String $name, Mixed $value = NULL )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'cast( String $arg, Array $options = [] )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'seer( ?Callable $callback )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'exit( String $arg )',
      'note' => '',
    ],
  ];



  public function awakening() {}
  
}