<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Specter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: Ends the part of Specter in the flow (nothing to close yet).
  public static function end() {}

  #NOTE: Start observing changes in a directory and trigger callback on modification
  public static function observer( $repo, $callback ) {
    if (!file_exists($repo)) {
      whisper_echo("{{COLOR-DANGER}}{{ICON-WARNING}}{{label-warning}} Specter Repo not exits!! {{nl}}");
      aether_exit(true);  
    }

    $index = 0;
    $last = forger_observer( $repo );

    whisper_echo("\n SPECTER {{color-danger}}::{{color-end}} OBSERVER");
    whisper_echo("\n {{color-secondary}}Your watch this directory '$repo'");
    whisper_echo("\n {{color-secondary}}Running successfully exit with [{{color-danger}}Ctrl+C{{color-end}}].\n\n");
    self::seer( function($animation) use (&$last, &$index, $repo, $callback) {
      $current = forger_observer($repo);
      
      if ($last !== $current) {
        global $AETHER_STOPWATCH;
        $AETHER_STOPWATCH = microtime(true);
        $callback();
        $index++;
        $last = $current;
      }

      if ($index > 5) {
        whisper_clear(true);
        whisper_echo("\n SPECTER {{color-danger}}::{{color-end}} OBSERVER");
        whisper_echo("\n {{color-success}}{{icon-success}}{{color-secondary}}Successfully clearing your console..");
        whisper_echo("\n {{color-secondary}}Running successfully exit with [{{color-danger}}Ctrl+C{{color-end}}].\n\n");
        $index = 0;
      }
      return false;
    });
  }

  #NOTE: Launch a local PHP development server with configurable host, port, and router
  public static function devserver( $configure ) {
    $config = (object) $configure;
    
    // Default value
    $config->host ??= '127.0.0.1';
    $config->port ??= '8000';
    $config->mode ??= 'private';
    
    // Jika mode public, ubah host ke 0.0.0.0
    if ($config->mode == 'public') {
      $config->host = '0.0.0.0';
    }
    
    // Path ke direktori
    $path = !empty($config->path) ? ' -t ' . escapeshellarg($config->path) : '';
    
    // Jika ada file router (kayak router.php)
    $file = !empty($config->file) ? ' ' . escapeshellarg($config->file) : '';
    
    // Gabungkan semua
    $command = PHP_BINARY . ' -S ' . $config->host . ':' . $config->port . $path . $file;
    
    // whisper
    whisper_echo("\n SPECTER {{color-danger}}::{{color-end}} DEVSERVER");
    whisper_echo("\n {{color-secondary}}Your local development server in http://{$config->host}:{$config->port}");
    whisper_echo("\n {{color-secondary}}Running successfully exit with [{{color-danger}}Ctrl+C{{color-end}}].\n\n");
    
    // Jalankan server
    shell_exec($command);
  }

  #NOTE: Store or retrieve a named soul value (persistent memory): one argument reads; a second one writes any value (0, '' and false too); null removes it
  public static function soul( String $name, Mixed ...$value ) {
    if (count($value) === 0) {
      $return = specter_soul_get($name);
    }else if ($value[0] === null) {
      specter_soul_remove($name);
      $return = null;
    }else {
      specter_soul_set($name, $value[0]);
      $return = $value[0];
    }

    aether_arcane("Specter.manifest.soul");
    return $return;
  }

  #NOTE: Register or run a terminal command cast with optional behavior settings; with 'detach' => true it outlives the runite and returns its PID (false when its title is still alive)
  public static function cast( String $arg, Array $options = [] ) {
    if (!empty($options['detach'])) {
      $return = specter_detach($arg, $options);
    }else if (is_array($options)) {
      specter_cast_set($arg, $options);
      $return = true;
    }else {
      $return = specter_cast_get($arg);
    }

    aether_arcane("Specter.manifest.cast");
    return $return;
  }
  
  #NOTE: Start a visual loop animation until a stop condition or glitch is met
  public static function seer( ?Callable $callback ) {
    specter_seer_set($callback);

    aether_arcane("Specter.manifest.seer");
  }

  #NOTE: Mark a previously casted command as inactive (used to exit the cast lifecycle)
  public static function exit( String $arg ) {
    specter_cast_save( $arg, false );
  }

  #NOTE: Whether a detached process (by title or PID) is alive
  public static function alive( String|Int $target ) {
    $return = specter_alive($target);

    aether_arcane("Specter.manifest.alive");
    return $return;
  }

  #NOTE: Ends a detached process (by title or PID) and what it started; false when nothing was alive
  public static function kill( String|Int $target ) {
    $return = specter_kill($target);

    aether_arcane("Specter.manifest.kill");
    return $return;
  }

  #NOTE: Runs a tick with a pause between (ms) until stop() or a false tick, with a heartbeat soul beat:<title> every $beat seconds; returns the ticks
  public static function loop( Callable $tick, Int $sleep = 100, ?Callable $stop = null, Float $beat = 5, String $title = 'loop' ) {
    $return = specter_loop($tick, $sleep, $stop, $beat, $title);

    aether_arcane("Specter.manifest.loop");
    return $return;
  }

  #NOTE: Runs a child command with a timeout (s), its output streamed to a log and onBeat every $beat seconds; returns ['code', 'timeout', 'time']
  public static function run( String $arg, Float $timeout = 900, String $log = '', ?Callable $onBeat = null, Float $beat = 5 ) {
    $return = specter_run($arg, $timeout, $log, $onBeat, $beat);

    aether_arcane("Specter.manifest.run");
    return $return;
  }

}