<?php

namespace Rune\Chanter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.6;
  
  public $main = 'Chanter';

  public $user = 'Anwar Achilles';

  public $note = 'Encapsulates processes and CLI-style operations to maintain immutability—structures input, spell logic, and execution flow into controlled, traceable, and reusable command definitions.';

  public $link = [
    ['Specter', 'ether:essence:entity:manifest', 1.3],
    ['Whisper', 'manifest:ether', 1.5],
    ['Aether', 'manifest:ether', 1.9],
    ['Cipher', 'manifest', 0.2],
    ['Crafter', 'manifest', 1.2],
    ['Forger', 'manifest', 1.6],
    ['Keeper', 'manifest', 1.6],
    ['Weaver', 'manifest', 1.2],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'CHANTER',
      'note' => 'Main entity',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER',
      'note' => 'Main entity',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG',
      'note' => 'Sets or returns the current CLI argument string',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG_CAST',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG_SPELL',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ARG_LIST',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_CAST',
      'note' => 'Gets or registers a cast function based on the given arguments.',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_CAST_LIST',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_SPELL',
      'note' => 'Gets or sets a spell by name depending on whether values are provided',
    ],
    [
      'type' => 'essence',
      'call' => 'CHANTER_ECHO',
      'note' => 'Saves an echo message for the current cast if its valid.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter',
      'note' => 'Main entity',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_arg',
      'note' => 'Sets or returns the current CLI argument string',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_arg_extract',
      'note' => 'Parses CLI arguments into cast parts and spell options',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast',
      'note' => 'Gets or registers a cast function based on the given arguments.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast_set',
      'note' => 'Registers a new cast if it doesn’t already exist in the cast list.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast_get',
      'note' => 'Returns a registered cast function or a fallback if not found',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_cast_has',
      'note' => 'Checks if a cast function exists for the given arguments',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell',
      'note' => 'Gets or sets a spell by name depending on whether values are provided',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_set',
      'note' => 'Registers or updates a spell with the given name and value',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_get',
      'note' => 'Retrieves the value of a spell argument if it exists',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_chain',
      'note' => 'Builds a full CLI-style spell string from all current spell arguments',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_spell_has',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_echo',
      'note' => 'Saves an echo message for the current cast if its valid.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_echo_set',
      'note' => 'Adds notes to the echo data if the entry doesn’t exist yet.',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_echo_get',
      'note' => 'Retrieves the echo data for a given cast argument',
    ],
    [
      'type' => 'entity',
      'call' => 'chanter_whisper_drain',
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
      'call' => 'awaken()',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'cast( String $args, ?Callable $callable = NULL )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'spell( String $name, $values = NULL )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'echo( String $text )',
      'note' => '',
    ],
  ];







  public function awakening() {}
  
}