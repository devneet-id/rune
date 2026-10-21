<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Aether;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.17';
  
  public $main = 'Aether';

  public $user = 'Anwar Achilles';

  public $note = 'Represents the foundational flow that permeates all components—serves as the invisible layer connecting, powering, and synchronizing every part of the system.';

  public $link = [
    ['Chanter', 'entity', '1.13'],
    ['Whisper', 'entity', '1.13'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'AETHER',
      'note' => 'Flag that the Aether flow is active.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_FILE',
      'note' => 'The runite being cast (the file run by PHP, e.g. cast).',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_REPO',
      'note' => 'The working folder of the runite (getcwd).',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_VERSION',
      'note' => 'Version of the Rune core.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_COPYRIGHT',
      'note' => 'Copyright line placed in crafted and artefact output.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_RUNE_LOCATION',
      'note' => 'Folder of the origin runes.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_SELF_WEAVER',
      'note' => 'Folder of Aether"s own weaver templates.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_LOGS',
      'note' => 'Legacy logs folder inside .echoes (kept for old runites).',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES',
      'note' => 'The .echoes folder of the working folder.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES_RUNE',
      'note' => 'Legacy echoes folder of the runite (Keeper now keeps the echoes).',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES_INFORMATION',
      'note' => 'Legacy information file of the runite echoes.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES_ARCANE',
      'note' => 'Legacy arcane file of the runite echoes.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES_ARCANES',
      'note' => 'Legacy arcanes file of the runite echoes.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_ECHOES_ARTEFACT',
      'note' => 'Legacy artefact folder of the runite echoes.',
    ],
    [
      'type' => 'ether',
      'call' => 'AETHER_PHP_VERSION',
      'note' => 'PHP version the Rune core was built with.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_STOPWATCH',
      'note' => 'Start time of the flow, used for the Execute time.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_ARCANE_STOPWATCH',
      'note' => 'Start time used to measure arcane steps.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_ARCANE_STOPWATCH_STEP',
      'note' => 'Time of the last arcane step.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_RUNE',
      'note' => 'Runes awakened in this flow.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_RUNE_ETHER',
      'note' => 'Runes whose ether is awakened.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_RUNE_ESSENCE',
      'note' => 'Runes whose essence is awakened.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_RUNE_ENTITY',
      'note' => 'Runes whose entity is awakened.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_RUNE_MANIFEST',
      'note' => 'Runes whose manifest is awakened.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_RUNE_PHANTASM',
      'note' => 'Phantasm of the awakened runes.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_ZERO_TRUST',
      'note' => 'Zero-trust state (output drained, arcane disabled).',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_ARCANE',
      'note' => 'Arcane records of this flow.',
    ],
    [
      'type' => 'essence',
      'call' => 'AETHER_ARCANE_STATE',
      'note' => 'Whether arcane is recorded (enable/disable).',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_formatFileSize($size, $precision = 2)',
      'note' => 'Convert a number of bytes into a human-readable file size (e.g. KB, MB)',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_stopwatch()',
      'note' => 'Measure elapsed time since $AETHER_STOPWATCH was started (in seconds)',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_memoryusage()',
      'note' => 'Get current and peak memory usage in bytes',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_zero_trust( Mixed $state = "" )',
      'note' => 'Gets or sets the zero-trust state of the flow.',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_exit( $force = false )',
      'note' => 'Exit the program with execution time and memory usage summary (supports pretty output with whisper)',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_has_entity( $function )',
      'note' => 'Check if a given function (entity) exists in the current environment',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_has_ether( $rune )',
      'note' => 'Check if a constant (ether) is defined in the runtime',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_has_essence( $rune )',
      'note' => 'Check if a global variable (essence) is set and exists',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_dd( $data, $force=false )',
      'note' => 'Dump and display debug information with colorized formatting, then exit',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_whisper( $text )',
      'note' => 'Prints text without Whisper (style tokens removed).',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_whisper_echo( $text )',
      'note' => 'Echoes through Whisper when awakened, otherwise prints plain text.',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arised()',
      'note' => 'Get all unique arised runes from ether, essence, and entity',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arcane(String $text, String $value = "")',
      'note' => 'Track execution events with timestamp and elapsed time (arcane trace logging)',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arcane_old(String $text, String $value = "")',
      'note' => 'Old arcane recorder (kept for reference).',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arcane_reset()',
      'note' => 'Reset all arcane trace logs',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arcane_enable()',
      'note' => 'Enable arcane trace logging',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arcane_disable()',
      'note' => 'Disable arcane trace logging',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_arcane_pretty_print()',
      'note' => 'Prints the arcane records of this flow (development).',
    ],
    [
      'type' => 'entity',
      'call' => 'aether_list_runes()',
      'note' => 'Lists origin runes plus @liberations runes in liberation mode.',
    ],
    [
      'type' => 'manifest',
      'call' => 'begin( $flags = 0 )',
      'note' => 'Begins the Aether flow: cleans memory and resets arcane.',
    ],
    [
      'type' => 'manifest',
      'call' => 'end()',
      'note' => 'Ends the Aether flow and exits with the version footer.',
    ],
    [
      'type' => 'manifest',
      'call' => 'dd( $data, $force=false  )',
      'note' => 'Dumps data and stops the flow.',
    ],
    [
      'type' => 'manifest',
      'call' => 'exit( $force = false )',
      'note' => 'Exits the flow now with the version footer.',
    ],
  ];

  public function awakening() {}
  
}