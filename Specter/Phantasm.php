<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Specter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.8';
  
  public $main = 'Specter';

  public $user = 'Anwar Achilles';

  public $note = 'Enables out-of-process operations through soul-like abstractions—designed for managing complex, asynchronous, or decoupled executions by temporarily separating logic from the main flow while maintaining active control.';

  public $link = [
    ['Keeper', 'ether', '1.9'],
    ['Aether', 'ether', '1.15'],
    ['Weaver', 'entity', '1.5'],
    ['Crafter', 'ether:essence', '1.6'],
    ['Forger', 'entity', '1.10'],
    ['Whisper', 'ether:essence', '1.9'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'SPECTER',
      'note' => 'main ether',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_ECHOES_SOUL',
      'note' => 'Path to the file where Specter soul data (state/configuration) is stored.',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_ECHOES_CAST',
      'note' => 'Path to the file where Specter cast data (execution history/config) is stored.',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_CAST_DEFAULT',
      'note' => 'Default structure for a Specter cast, indicating execution state and options.',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_CAST_ARG_DEFAULT',
      'note' => 'Default command-line options for Specter cast executions.',
    ],
    [
      'type' => 'ether',
      'call' => 'SPECTER_SEER_OPTION',
      'note' => 'Default configuration for Specter Seer, mainly controlling loop speed and delays.',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER',
      'note' => 'main essence',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_ITEMS',
      'note' => 'Holds the list of all registered Specter items.',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_STATS',
      'note' => 'Stores runtime statistics for Specter items.',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_SOUL',
      'note' => 'Stores the soul data of the Specter, usually persistent state or configuration.',
    ],
    [
      'type' => 'essence',
      'call' => 'SPECTER_CAST',
      'note' => 'Contains casting records and arguments used when Specter runs external processes.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter()',
      'note' => 'main entity',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_set( String $name, Mixed $value )',
      'note' => 'Sets a soul value and persists it through the keeper.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_get( String $name )',
      'note' => 'Retrieves a soul value and refreshes the in-memory state.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_save( $name, $value = null )',
      'note' => 'Loads and syncs soul data from keeper, optionally updates a value.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_remove( String $name )',
      'note' => 'Removes a soul value from both memory and keeper storage.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_cast_set( string $arg, array $options = [] )',
      'note' => 'Executes a command with given options across platforms and logs the cast state.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_cast_get( string $arg )',
      'note' => 'Retrieves cast state by argument from keeper storage.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_cast_save( $arg, $alive = true, $option = [] )',
      'note' => 'Saves the cast command and its metadata to keeper storage.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_seer_set(?Callable $condition)',
      'note' => 'Continuously calls a condition callback with spinner frames until stopped or glitch is detected.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_exit( String $arg )',
      'note' => 'Marks a casted specter command as no longer active.',
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
      'call' => 'observer( $repo, $callback )',
      'note' => 'Start observing changes in a directory and trigger callback on modification',
    ],
    [
      'type' => 'manifest',
      'call' => 'devserver( $configure )',
      'note' => 'Launch a local PHP development server with configurable host, port, and router',
    ],
    [
      'type' => 'manifest',
      'call' => 'soul( String $name, Mixed $value = NULL )',
      'note' => 'Store or retrieve a named soul value (persistent memory)',
    ],
    [
      'type' => 'manifest',
      'call' => 'cast( String $arg, Array $options = [] )',
      'note' => 'Register or run a terminal command cast with optional behavior settings',
    ],
    [
      'type' => 'manifest',
      'call' => 'seer( ?Callable $callback )',
      'note' => 'Start a visual loop animation until a stop condition or glitch is met',
    ],
    [
      'type' => 'manifest',
      'call' => 'exit( String $arg )',
      'note' => 'Mark a previously casted command as inactive (used to exit the cast lifecycle)',
    ],
  ];

  public function awakening() {}
  
}