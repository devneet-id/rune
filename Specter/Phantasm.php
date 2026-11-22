<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Specter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.13';
  
  public $main = 'Specter';

  public $user = 'Anwar Achilles';

  public $note = 'Enables out-of-process operations through soul-like abstractions—designed for managing complex, asynchronous, or decoupled executions by temporarily separating logic from the main flow while maintaining active control.';

  public $link = [
    ['Keeper', 'ether', '1.19'],
    ['Aether', 'ether:entity', '1.22'],
    ['Weaver', 'entity', '1.10'],
    ['Forger', 'entity', '1.17'],
    ['Whisper', 'entity', '1.16'],
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
      'note' => 'Sets a soul value and persists it through the keeper, under one lock (several processes may write souls at once).',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_get( String $name )',
      'note' => 'Retrieves a soul value (false when it is not there) and refreshes the in-memory state; it only reads.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_save( $name, $value = null )',
      'note' => 'Loads soul data from keeper; with a value (any, 0 and false too) it also writes it, under one lock.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_soul_remove( String $name )',
      'note' => 'Removes a soul value from both memory and keeper storage, under one lock.',
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
      'type' => 'entity',
      'call' => 'specter_detach( String $arg, Array $options = [] )',
      'note' => 'Starts a command detached from the runite (it goes on when the runite ends), by title: its PID, or false when that title is still alive.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_process_save( String $arg, String $title, Int $pid, Bool $alive )',
      'note' => 'Keeps a process in cast.json (title, PID, alive), under one lock.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_process_find( String|Int $target )',
      'note' => 'Finds a kept process by title or PID: [arg, entry] — the alive one first — or null.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_pid_alive( Int $pid )',
      'note' => 'Whether a PID runs on this machine (a zombie counts as ended).',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_alive( String|Int $target )',
      'note' => 'Whether a process kept by title (or a PID) is alive; cast.json follows what is found.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_kill( String|Int $target )',
      'note' => 'Ends a process kept by title (or a PID), with what it started: true when it was alive and is ended, false when there was nothing to end.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_loop( Callable $tick, Int $sleep = 100, ?Callable $stop = null, Float $beat = 5, String $title = "loop" )',
      'note' => 'Runs a tick again and again with a pause of $sleep ms between (no loop without a pause) until stop() is true, the tick returns false or a glitch is written; writes a heartbeat soul beat:<title> every $beat seconds; returns the number of ticks.',
    ],
    [
      'type' => 'entity',
      'call' => 'specter_run( String $arg, Float $timeout = 900, String $log = "", ?Callable $onBeat = null, Float $beat = 5 )',
      'note' => 'Runs a command and waits for it: its output (stdout and stderr) goes to $log as it comes, onBeat(seconds) is called every $beat seconds, and past $timeout seconds it is stopped; returns ["code", "timeout", "time"].',
    ],
    [
      'type' => 'manifest',
      'call' => 'end()',
      'note' => 'Ends the part of Specter in the flow (nothing to close yet).',
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
      'call' => 'soul( String $name, Mixed ...$value )',
      'note' => 'Store or retrieve a named soul value (persistent memory): one argument reads; a second one writes any value (0, "" and false too); null removes it',
    ],
    [
      'type' => 'manifest',
      'call' => 'cast( String $arg, Array $options = [] )',
      'note' => 'Register or run a terminal command cast with optional behavior settings; with "detach" => true it outlives the runite and returns its PID (false when its title is still alive)',
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
    [
      'type' => 'manifest',
      'call' => 'alive( String|Int $target )',
      'note' => 'Whether a detached process (by title or PID) is alive',
    ],
    [
      'type' => 'manifest',
      'call' => 'kill( String|Int $target )',
      'note' => 'Ends a detached process (by title or PID) and what it started; false when nothing was alive',
    ],
    [
      'type' => 'manifest',
      'call' => 'loop( Callable $tick, Int $sleep = 100, ?Callable $stop = null, Float $beat = 5, String $title = "loop" )',
      'note' => 'Runs a tick with a pause between (ms) until stop() or a false tick, with a heartbeat soul beat:<title> every $beat seconds; returns the ticks',
    ],
    [
      'type' => 'manifest',
      'call' => 'run( String $arg, Float $timeout = 900, String $log = "", ?Callable $onBeat = null, Float $beat = 5 )',
      'note' => 'Runs a child command with a timeout (s), its output streamed to a log and onBeat every $beat seconds; returns ["code", "timeout", "time"]',
    ],
  ];

  public function awakening() {}
  
}