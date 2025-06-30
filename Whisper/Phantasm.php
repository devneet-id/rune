<?php

namespace Rune\Whisper;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = 1.8;
  
  public $main = 'Whisper';

  public $user = 'Anwar Achilles';

  public $note = 'Handles user-facing output through styled messages, prompts, and echoes—focused on delivering clear, customizable system feedback and visual communication.';

  public $link = [
    ['Aether', 'essence:entity', 1.13],
    ['Weaver', 'entity', 1.4],
  ];

  
  public $node = [
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
      'call' => 'emit( String $message, Bool $asString = false )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'reap( String $text )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'drain( ?Callable $callable, Array $option = [] )',
      'note' => '',
    ],
    [
      'type' => 'manifest',
      'call' => 'clear( Bool $force = false )',
      'note' => '',
    ],
    [
      'type' => 'ether',
      'call' => 'WHISPER',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => '$WHISPER',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => '$WHISPER_VARS',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => '$WHISPER_COLORS',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => '$WHISPER_ICONS',
      'note' => '',
    ],
    [
      'type' => 'essence',
      'call' => '$WHISPER_LABELS',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper()',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_clear()',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_clear_force()',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_delay( Int $ms )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo( String $message, Bool $asString = false )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo_get( String $message )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo_set( String $message )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo_imbue( String $text )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_call( String $prompt )',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_drain( callable $callback, array $option )',
      'note' => '',
    ],
  ];

  public function awakening() {}
  
}