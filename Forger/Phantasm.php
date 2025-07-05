<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Forger;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.13';
  
  public $main = 'Forger';

  public $user = 'Anwar Achilles';

  public $note = 'Built for structured file and directory operations such as tracing, scanning, fixing, moving, and cloning. Enables content analysis and automated organization through dynamic, logic-driven routines.';

  public $link = [
    ['Aether', 'essence:entity', '1.16'],
    ['Crafter', 'ether:essence', '1.7'],
  ];

  public $node = [
    [
      'type' => 'essence',
      'call' => 'FORGER',
      'note' => 'main essence',
    ],
    [
      'type' => 'entity',
      'call' => 'forger',
      'note' => 'Placeholder function for the Forger system, currently returns true.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_trace',
      'note' => 'Traces and resolves each part of a given path, tagging them as file ("item") or folder ("repo") along with their existence.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_trace_recursive',
      'note' => 'Recursively traces a given path and all its children, returning structured info for both files and directories.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_scan',
      'note' => 'Scans a directory for items, applies a callback to each, and returns the result list.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_fix',
      'note' => 'Ensures all given paths exist by creating missing directories or files based on their type.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_move',
      'note' => 'Moves or clones items and repositories to their target paths, ensuring destination structure exists beforehand.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_sort',
      'note' => 'Sorts traced paths by type priority and path length, placing prioritized types first and longer paths earlier.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_clean',
      'note' => 'Cleans a file or directory; if forced as repo, recursively traces and removes all contained files and folders.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_repo',
      'note' => 'Ensures the repository path exists, fixes missing parts, and optionally scans items with a callback.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_item',
      'note' => 'Ensures the file exists, optionally writes content to it, and returns its contents.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_info',
      'note' => 'Retrieves detailed information about a file or directory, including type, basename, name, extension, and path.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_clone',
      'note' => 'Recursively clones a directory and its contents to a target location, creating folders as needed and copying files.',
    ],
    [
      'type' => 'entity',
      'call' => 'forger_observer',
      'note' => 'Observes a directory recursively and returns the latest modification timestamp among all files.',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => 'Optional lifecycle method for internal post-arise logic.',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_end()',
      'note' => 'Special hook for aether-based awakening phase, executed at the end of the crafter process.',
    ],
    [
      'type' => 'manifest',
      'call' => 'end()',
      'note' => 'Final phase of the class lifecycle, called after all manifest components are registered and ready.',
    ],
    [
      'type' => 'manifest',
      'call' => 'trace( String $source_path )',
      'note' => 'Traces and resolves each part of a given path, tagging them as file (item) or folder (repo) along with their existence.',
    ],
    [
      'type' => 'manifest',
      'call' => 'scan( String $source_path, $callback )',
      'note' => 'Scans a directory for items, applies a callback to each, and returns the result list.',
    ],
    [
      'type' => 'manifest',
      'call' => 'info( String $source_path )',
      'note' => 'Retrieves detailed information about a file or directory, including type, basename, name, extension, and path.',
    ],
    [
      'type' => 'manifest',
      'call' => 'fix( Array $source_path )',
      'note' => 'Ensures all given paths exist by creating missing directories or files based on their type.',
    ],
    [
      'type' => 'manifest',
      'call' => 'clean( String $source_path, bool $force = false )',
      'note' => 'Cleans a file or directory; if forced as repo, recursively traces and removes all contained files and folders.',
    ],
    [
      'type' => 'manifest',
      'call' => 'move( Array $source_path )',
      'note' => 'Moves or clones items and repositories to their target paths, ensuring destination structure exists beforehand.',
    ],
    [
      'type' => 'manifest',
      'call' => 'repo( String $source_path, ?Callable $callback )',
      'note' => 'Ensures the repository path exists, fixes missing parts, and optionally scans items with a callback.',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $source_path, $content = false )',
      'note' => 'Ensures the file exists, optionally writes content to it, and returns its contents.',
    ],
    [
      'type' => 'manifest',
      'call' => 'clone( string $from, string $to )',
      'note' => 'Recursively clones a directory and its contents to a target location, creating folders as needed and copying files.',
    ],
    [
      'type' => 'manifest',
      'call' => 'observer( string $path )',
      'note' => 'Observes a directory recursively and returns the latest modification timestamp among all files.',
    ],
  ];

  public function awakening() {}
  
}