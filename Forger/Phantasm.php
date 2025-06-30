<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Forger;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.8;
  
  public $main = 'Forger';

  public $user = 'Anwar Achilles';

  public $note = 'Built for structured file and directory operations such as tracing, scanning, fixing, moving, and cloning. Enables content analysis and automated organization through dynamic, logic-driven routines.';

  public $link = [
    ['Aether', 'essence:entity', 1.13],
  ];

  public $node = [
    [
      'type' => 'essence',
      'call' => 'FORGER',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_trace',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_trace_recursive',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_scan',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_fix',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_move',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_sort',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_clean',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_repo',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_item',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_info',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_clone',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_observer',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
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
      'call' => 'trace( String $source_path )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'scan( String $source_path, $callback )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'info( String $source_path )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'fix( Array $source_path )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'clean( String $source_path, bool $force = false )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'move( Array $source_path )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'repo( String $source_path, ?Callable $callback )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $source_path, $content = false )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'clone( string $from, string $to )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'observer( string $path )',
      'note' => '',
    ],
  ];


  public function awakening() {}
  
}