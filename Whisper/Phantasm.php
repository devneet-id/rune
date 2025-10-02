<?php

namespace Rune\Whisper;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.12';
  
  public $main = 'Whisper';

  public $user = 'Anwar Achilles, Kuro Kid';

  public $note = 'Handles user-facing output through styled messages, prompts, and echoes—focused on delivering clear, customizable system feedback and visual communication.';

  public $link = [
    ['Aether', 'essence:entity', '1.15'],
    ['Crafter', 'ether:essence', '1.6'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'WHISPER',
      'note' => 'main ether',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER',
      'note' => 'main essence',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_VARS',
      'note' => 'Default text variables for whisper formatting (like newline, tab, etc.)',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_COLORS',
      'note' => 'ANSI color codes for CLI output styling used by whisper',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_BG_COLORS',
      'note' => 'ANSI background color codes for CLI output styling used by whisper',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_COLORS_WEBS',
      'note' => 'HTML span-based color definitions for web-based whisper output',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_ICONS',
      'note' => 'Default icon markers used in whisper output for each message type',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_LABELS',
      'note' => 'Default label prefixes for whisper messages',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_DRAIN',
      'note' => 'Buffer to store whisper output when in drain mode',
    ],
    [
      'type' => 'essence',
      'call' => 'WHISPER_DRAIN_STATE',
      'note' => 'State toggle to determine whether whisper is in drain mode',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper()',
      'note' => 'main entity',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_clear()',
      'note' => 'Clear terminal screen using ANSI escape codes',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_clear_force()',
      'note' => 'Force clear terminal using system commands (Linux & Windows)',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_delay( Int $ms )',
      'note' => 'Delay output for a given number of milliseconds',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo( String $message, Bool $asString = false )',
      'note' => 'Display or retrieve a formatted message with color/icon support',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo_get( String $message )',
      'note' => 'Return a string with applied whisper styles (colors, icons, labels)',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo_set( String $message )',
      'note' => 'Print the formatted message or route to drain if enabled',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_echo_imbue( String $text )',
      'note' => 'Apply all whisper formatting to the text (colors, icons, labels)',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_call( String $prompt )',
      'note' => 'Prompt user for input via terminal with styled message',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_drain_start()',
      'note' => 'Start buffering whisper output instead of printing directly',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_drain_set( String $message )',
      'note' => 'Store a line of whisper output into the drain buffer',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_drain_get()',
      'note' => 'Retrieve all buffered whisper output as a single string',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_drain_end()',
      'note' => 'Clear the whisper output buffer and disable buffering',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_flash_start()',
      'note' => 'init flash, reset pointer, start buffer',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_flash_run()',
      'note' => 'run one flash step, update pointer, flush output',
    ],
    [
      'type' => 'entity',
      'call' => 'whisper_flash_stop()',
      'note' => 'stop flash, end buffer, add newline',
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
      'call' => 'echo( String $message, Bool $asString = false )',
      'note' => 'Print or return a formatted whisper message with color support',
    ],
    [
      'type' => 'manifest',
      'call' => 'call( String $text )',
      'note' => 'Prompt user for input through STDIN with a whisper message',
    ],
    [
      'type' => 'manifest',
      'call' => 'drain( Mixed $state_or_process, Bool $asString = false )',
      'note' => 'Start, process, or return a buffered whisper output (drain mode)',
    ],
    [
      'type' => 'manifest',
      'call' => 'clear( Bool $force = false )',
      'note' => 'Clear terminal screen, optionally forcing it for cross-platform',
    ],
    [
      'type' => 'manifest',
      'call' => 'flash( Callable $execute )',
      'note' => 'wrapper to control flash with callbacks',
    ],
  ];

  public function awakening() {}
  
}