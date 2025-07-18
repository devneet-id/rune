<?php

namespace Rune\Chanter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.12';
  
  public $main = 'Chanter';

  public $user = 'Anwar Achilles';

  public $note = 'Encapsulates processes and CLI-style operations to maintain immutability—structures input, spell logic, and execution flow into controlled, traceable, and reusable command definitions.';

  public $link = [
    ['Aether', 'essence:entity', '1.16'],
    ['Whisper', 'ether:essence', '1.10'],
    ['Specter', 'ether:essence', '1.7'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'CHANTER',
      'note' => 'Main ether',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER',
      'note' => 'Main essence',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG',
      'note' => 'Stores full CLI argument string as a single line',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARGS',
      'note' => 'Stores full CLI argument string as an array',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG_CAST',
      'note' => 'Holds parsed cast-related arguments from CLI',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG_SPELL',
      'note' => 'Contains full list of separated CLI arguments',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG_LIST',
      'note' => 'Contains full list of separated CLI arguments',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_CAST',
      'note' => 'Stores all available cast definitions',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_CAST_LIST',
      'note' => 'List of registered cast names or keys',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_SPELL',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ECHO',
      'note' => 'Holds text or data to be echoed after cast execution',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter()',
      'note' => 'Main entity',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_arg( String $newArg = "" )',
      'note' => 'Sets or returns the current CLI argument string',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_arg_extract( String $newArg = "" )',
      'note' => 'Parses CLI arguments into cast parts and spell options',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_arg_rebase()',
      'note' => 'Checks if a specific spell name exists in the global $CHANTER_ARG_SPELL array and its value is not equal to',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast( String $args, ?Callable $callable )',
      'note' => 'Gets or registers a cast function based on the given arguments.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast_set( String $arg, ?Callable $callable )',
      'note' => 'Registers a new cast if it doesn’t already exist in the cast list.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast_get( String $arg )',
      'note' => 'Returns a registered cast function or a fallback if not found',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast_has( String $arg )',
      'note' => 'Checks if a cast function exists for the given arguments',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell( String $name, $values = NULL )',
      'note' => 'Gets or sets a spell by name depending on whether values are provided',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_set( String $name, String $value )',
      'note' => 'Registers or updates a spell with the given name and value',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_get( String $name )',
      'note' => 'Retrieves the value of a spell argument if it exists',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_chain()',
      'note' => 'Builds a full CLI-style spell string from all current spell arguments',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_has( String $name )',
      'note' => 'Missing return statement – function does not return the result.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_echo( String $echo )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_echo_set( String $arg, String $notes )',
      'note' => 'Adds notes to the echo data if the entry doesn’t exist yet.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_echo_get( String $arg )',
      'note' => 'Retrieves the echo data for a given cast argument',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_whisper_drain( $run )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_awaken_before()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'end()',
      'note' => 'Prepares and executes a spell casting based on provided arguments or from file.',
    ],
    [
      'type' => 'manifest',
      'call' => 'cast( String $args, ?Callable $callable = NULL )',
      'note' => 'Gets or sets a spell cast definition based on input and returns the result.',
    ],
    [
      'type' => 'manifest',
      'call' => 'spell( String $name, $values = NULL )',
      'note' => 'Gets or sets a spell definition by key and returns the result.',
    ],
    [
      'type' => 'manifest',
      'call' => 'echo( String $cast, String $notes = "" )',
      'note' => 'Outputs the given text and returns it',
    ],
  ];

  public function awakening() {}
  
}