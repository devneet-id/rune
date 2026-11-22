<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */
#NOTE: main entity
function specter() {
  return true;
}


/* SOUL
 * todo spectering the spark
 * */
#NOTE: Sets a soul value and persists it through the keeper, under one lock (several processes may write souls at once).
function specter_soul_set( String $name, Mixed $value ) {
  global $SPECTER_SOUL;

  $SPECTER_SOUL = keeper_item_update('soul', function( $soul ) use ($name, $value) {
    $soul = is_object($soul) ? $soul : (object) ($soul ?? []);
    $soul->$name = $value;
    return $soul;
  });

  return $value;
}
#NOTE: Retrieves a soul value (false when it is not there) and refreshes the in-memory state; it only reads.
function specter_soul_get( String $name ) {
  global $SPECTER_SOUL;

  $soul = keeper_item_get('soul');
  $SPECTER_SOUL = is_object($soul) ? $soul : (object) ($soul ?? []);

  if (property_exists($SPECTER_SOUL, $name)) {
    $return = $SPECTER_SOUL->$name;
  }else {
    $return = false;
  };

  return $return;
}
#NOTE: Loads soul data from keeper; with a value (any, 0 and false too) it also writes it, under one lock.
function specter_soul_save( $name, $value = null ) {
  global $SPECTER_SOUL;

  if (func_num_args() > 1) {
    specter_soul_set($name, $value);
  }else {
    specter_soul_get($name);
  }
}
#NOTE: Removes a soul value from both memory and keeper storage, under one lock.
function specter_soul_remove( String $name ) {
  global $SPECTER_SOUL;

  $SPECTER_SOUL = keeper_item_update('soul', function( $soul ) use ($name) {
    $soul = is_object($soul) ? $soul : (object) ($soul ?? []);
    unset($soul->$name);
    return $soul;
  });
}


/* CAST
 * todo spectering the cast
 *  */
#NOTE: Executes a command with given options across platforms and logs the cast state.
function specter_cast_set( string $arg, array $options = [] ) {
  $arg = weaver_bind($arg, 'self', PHP_BINARY. ' ' . AETHER_FILE);
  $opt = array_merge(SPECTER_CAST_ARG_DEFAULT, $options);  

  specter_cast_save($arg, true, $opt);

  $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
  if ($isWin) {
    // WINDOWS MODE
    $cmdFlag = $opt['exit'] ? '/C' : '/K';
    $cmd     = "cmd $cmdFlag \"$arg\"";

    if ($opt['blocking']) {
      if ($opt['visible']) {
        shell_exec("start \"{$opt['title']}\" $cmd");
      } else {
        shell_exec($cmd);
      }
    } else {
      if ($opt['visible']) {
        pclose(popen("start \"{$opt['title']}\" $cmd", 'r'));
      } else {
        pclose(popen("start \"RUNE_SPECTER\" /B $cmd", 'r'));
      }
    }

  } else {
    // UNIX / LINUX / MACOS
    $cmd = $opt['exit'] ? "$arg; exit" : "$arg; exec bash";

    // macOS has no gnome-terminal: open a Terminal window in the same folder
    if (PHP_OS_FAMILY === 'Darwin' && $opt['visible']) {
      $script = 'cd ' . escapeshellarg(getcwd()) . '; ' . $arg;
      $script = str_replace(['\\', '"'], ['\\\\', '\\"'], $script);
      shell_exec('osascript -e ' . escapeshellarg('tell application "Terminal" to do script "' . $script . '"') . ' > /dev/null 2>&1');
      return;
    }

    if ($opt['blocking']) {
      if ($opt['visible']) {
        shell_exec("gnome-terminal --title=\"{$opt['title']}\" -- bash -c \"$cmd\"");
      } else {
        shell_exec($cmd);
      }
    } else {
      if ($opt['visible']) {
        shell_exec("gnome-terminal --title=\"{$opt['title']}\" -- bash -c \"$cmd\" > /dev/null 2>&1 &");
      } else {
        // the whole command goes to the background (`a; exit &` would only background `exit`)
        shell_exec('nohup sh -c ' . escapeshellarg($cmd) . ' > /dev/null 2>&1 &');
      }
    }
  }
}
#NOTE: Retrieves cast state by argument from keeper storage.
function specter_cast_get( string $arg ) {
  global $SPECTER_CAST;

  $arg = weaver_bind($arg, 'self', PHP_BINARY. ' ' . AETHER_FILE);

  $cast_keeper = keeper_item_get('cast');
  if (empty($cast_keeper)) {
    keeper_item_set('cast', []);
    $cast_keeper = keeper_item_get('cast');
  }

  $SPECTER_CAST = $cast_keeper;

  if (isset($SPECTER_CAST->$arg)) {
    $return = $SPECTER_CAST->$arg;
  }else {
    $return = false;
  };
  
  return $return;
}
#NOTE: Saves the cast command and its metadata to keeper storage.
function specter_cast_save( $arg, $alive = true, $option = [] ) {
  global $SPECTER_CAST;

  $arg = weaver_bind($arg, 'self', PHP_BINARY. ' ' . AETHER_FILE);

  $cast_keeper = keeper_item_get('cast');
  if (empty($cast_keeper)) {
    keeper_item_set('cast', []);
    $cast_keeper = keeper_item_get('cast');
  }
  if (!is_object($cast_keeper)) {
    $cast_keeper = (object) $cast_keeper;
  }

  $SPECTER_CAST = $cast_keeper;
  
  // if (empty($option)) {
  //   $potion = SPECTER_CAST_ARG_DEFAULT;
  // }

  // $keeper_option = [];
  // if (isset($cast_keeper->$arg)) {
  //   $keeper_option = (array) $cast_keeper->$arg->option;
  //   $option = array_merge($keeper_option, $option);
  // }
  
  $SPECTER_CAST->$arg = array_merge(SPECTER_CAST_DEFAULT, [
    'arg'=> $arg,
    'alive'=> $alive,
    // 'option'=> $option,
  ]);
  
  keeper_item_set('cast', $SPECTER_CAST);
}



/* SEER
 * todo spectating changed
 *  */
#NOTE: Continuously calls a condition callback with spinner frames until stopped or glitch is detected.
function specter_seer_set(?Callable $condition) {
  $frames = ['-', '\\', '|', '/'];
  $targetSpeed = SPECTER_SEER_OPTION['speed'] / 1000 ?? 0.1;
  $i = 0;

  while (true) {
    gc_enable();
    $start = microtime(true);

    $frame = $frames[$i % count($frames)];
    $stop = $condition($frame);
    $i++;

    $isGlitched = keeper_is_glitch();

    $end = microtime(true);
    $duration = $end - $start;

    $sleepTime = $targetSpeed - $duration;
    if ($sleepTime > 0) {
      usleep((int)($sleepTime * 1_000_000));
    }

    gc_collect_cycles();

    if ($stop || $isGlitched) break;
  }
}

#NOTE: Marks a casted specter command as no longer active.
function specter_exit( String $arg ) {
  specter_cast_save( $arg, false );
}



/* PROCESS
 * long-running processes: detach, alive, kill — one title, one process, kept in cast.json
 *  */
#NOTE: Starts a command detached from the runite (it goes on when the runite ends), by title: its PID, or false when that title is still alive.
function specter_detach( String $arg, Array $options = [] ) {
  $arg = weaver_bind($arg, 'self', PHP_BINARY. ' ' . AETHER_FILE);
  $title = (string) ($options['title'] ?? SPECTER_CAST_ARG_DEFAULT['title']);
  $log = (string) ($options['log'] ?? '');

  if (specter_alive($title)) {
    return false;
  }

  if (PHP_OS_FAMILY === 'Windows') {
    // Start-Process gives the PID back; the window is minimized or hidden
    $style = !empty($options['minimized']) ? 'Minimized' : 'Hidden';
    $line = $arg . ($log !== '' ? ' >> "' . $log . '" 2>&1' : '');
    // /S /C "…": cmd takes off only the outer quotes, so a quoted program with quoted arguments stays whole
    $script = "(Start-Process -FilePath cmd.exe -ArgumentList '/S /C \"" . str_replace("'", "''", $line) . "\"' -WindowStyle $style -PassThru).Id";
    $pid = (int) trim((string) shell_exec('powershell -NoProfile -Command "' . str_replace('"', '\\"', $script) . '"'));
  }else {
    // its own session (setsid, where there is one), so kill() ends the whole group
    $output = ($log !== '') ? escapeshellarg($log) : '/dev/null';
    $setsid = (trim((string) shell_exec('command -v setsid 2>/dev/null')) !== '') ? 'setsid ' : '';
    $pid = (int) trim((string) shell_exec('nohup ' . $setsid . 'sh -c ' . escapeshellarg($arg) . ' >> ' . $output . ' 2>&1 < /dev/null & echo $!'));
  }

  if ($pid <= 0) {
    return false;
  }
  specter_process_save($arg, $title, $pid, true);
  return $pid;
}
#NOTE: Keeps a process in cast.json (title, PID, alive), under one lock.
function specter_process_save( String $arg, String $title, Int $pid, Bool $alive ) {
  global $SPECTER_CAST;

  $SPECTER_CAST = keeper_item_update('cast', function( $cast ) use ($arg, $title, $pid, $alive) {
    $cast = is_object($cast) ? $cast : (object) ($cast ?? []);
    $cast->$arg = array_merge(SPECTER_CAST_DEFAULT, ['arg'=> $arg, 'alive'=> $alive, 'title'=> $title, 'pid'=> $pid]);
    return $cast;
  });
}
#NOTE: Finds a kept process by title or PID: [arg, entry] — the alive one first — or null.
function specter_process_find( String|Int $target ) {
  $found = null;
  foreach ((array) keeper_item_get('cast') as $arg => $entry) {
    $entry = (object) $entry;
    if (empty($entry->pid)) {
      continue;
    }
    $match = is_int($target) ? ((int) $entry->pid === $target) : (($entry->title ?? null) === $target);
    if ($match && ($found === null || !empty($entry->alive))) {
      $found = [(string) $arg, $entry];
    }
  }
  return $found;
}
#NOTE: Whether a PID runs on this machine (a zombie counts as ended).
function specter_pid_alive( Int $pid ) {
  if ($pid <= 0) {
    return false;
  }
  if (PHP_OS_FAMILY === 'Windows') {
    return str_contains((string) shell_exec('tasklist /FI "PID eq ' . $pid . '" /NH 2>NUL'), (string) $pid);
  }
  if (is_readable("/proc/$pid/stat")) {
    $stat = (string) @file_get_contents("/proc/$pid/stat");
    return $stat !== '' && !preg_match('/\)\s+[ZX]\s/', $stat);
  }
  if (function_exists('posix_kill')) {
    return posix_kill($pid, 0);
  }
  exec('kill -0 ' . $pid . ' 2>/dev/null', $output, $code);
  return $code === 0;
}
#NOTE: Whether a process kept by title (or a PID) is alive; cast.json follows what is found.
function specter_alive( String|Int $target ) {
  $found = specter_process_find($target);
  $pid = is_int($target) ? $target : (int) ($found[1]->pid ?? 0);
  $alive = specter_pid_alive($pid);
  if ($found && (bool) ($found[1]->alive ?? false) !== $alive) {
    specter_process_save($found[0], (string) ($found[1]->title ?? ''), $pid, $alive);
  }
  return $alive;
}
#NOTE: Ends a process kept by title (or a PID), with what it started: true when it was alive and is ended, false when there was nothing to end.
function specter_kill( String|Int $target ) {
  $found = specter_process_find($target);
  $pid = is_int($target) ? $target : (int) ($found[1]->pid ?? 0);
  if (!specter_pid_alive($pid)) {
    if ($found && !empty($found[1]->alive)) {
      specter_process_save($found[0], (string) ($found[1]->title ?? ''), $pid, false);
    }
    return false;
  }

  if (PHP_OS_FAMILY === 'Windows') {
    shell_exec('taskkill /PID ' . $pid . ' /T /F 2>NUL');
  }else {
    // the group first (its children too), then the process itself; 15 = TERM, 9 = KILL
    foreach ([15, 9] as $signal) {
      if (function_exists('posix_kill')) {
        @posix_kill(-$pid, $signal) || @posix_kill($pid, $signal);
      }else {
        shell_exec("kill -$signal -- -$pid 2>/dev/null || kill -$signal $pid 2>/dev/null");
      }
      $deadline = microtime(true) + 2;
      while (specter_pid_alive($pid) && microtime(true) < $deadline) {
        usleep(50000);
      }
      if (!specter_pid_alive($pid)) {
        break;
      }
    }
  }

  if ($found) {
    specter_process_save($found[0], (string) ($found[1]->title ?? ''), $pid, false);
  }
  return !specter_pid_alive($pid);
}



/* LOOP
 * a runite as a worker: small ticks, a pause between them, a stop, a heartbeat
 *  */
#NOTE: Runs a tick again and again with a pause of $sleep ms between (no loop without a pause) until stop() is true, the tick returns false or a glitch is written; writes a heartbeat soul beat:<title> every $beat seconds; returns the number of ticks.
function specter_loop( Callable $tick, Int $sleep = 100, ?Callable $stop = null, Float $beat = 5, String $title = 'loop' ) {
  $ticks = 0;
  $last = null;
  $heartbeat = function() use (&$ticks, $title) {
    specter_soul_set("beat:$title", ['at'=> time(), 'ticks'=> $ticks, 'pid'=> getmypid()]);
  };

  while (true) {
    $result = $tick($ticks);
    $ticks++;
    if ($last === null || microtime(true) - $last >= $beat) {
      $heartbeat();
      $last = microtime(true);
    }
    if ($result === false || ($stop !== null && $stop($ticks)) || keeper_is_glitch()) {
      break;
    }
    usleep(max(1, $sleep) * 1000);
  }
  $heartbeat();

  aether_arcane('Specter.entity.specter_loop');
  return $ticks;
}



/* RUN
 * a child command with a timeout, a log and a beat
 *  */
#NOTE: Runs a command and waits for it: its output (stdout and stderr) goes to $log as it comes, onBeat(seconds) is called every $beat seconds, and past $timeout seconds it is stopped; returns ['code', 'timeout', 'time'].
function specter_run( String $arg, Float $timeout = 900, String $log = '', ?Callable $onBeat = null, Float $beat = 5 ) {
  $arg = weaver_bind($arg, 'self', PHP_BINARY. ' ' . AETHER_FILE);
  $windows = PHP_OS_FAMILY === 'Windows';
  // one handle for stdout and stderr: they share one position in the log (two handles overwrite each other on Windows)
  $output = fopen(($log !== '') ? $log : ($windows ? 'NUL' : '/dev/null'), 'ab');
  // exec: the command takes the place of the shell, so a timeout stops the command itself
  $process = proc_open($windows ? $arg : 'exec ' . $arg, [0=> ['pipe', 'r'], 1=> $output, 2=> $output], $pipes);
  if (!is_resource($process)) {
    fclose($output);
    return ['code'=> null, 'timeout'=> false, 'time'=> 0.0];
  }
  fclose($pipes[0]);

  $start = microtime(true);
  $last = $start;
  $code = null;
  $timedout = false;
  while (true) {
    $status = proc_get_status($process);
    if (!$status['running']) {
      $code = $status['exitcode'];
      break;
    }
    $now = microtime(true);
    if ($onBeat !== null && $now - $last >= $beat) {
      $onBeat($now - $start);
      $last = $now;
    }
    if ($now - $start >= $timeout) {
      $timedout = true;
      proc_terminate($process, 15);
      $deadline = microtime(true) + 2;
      while (proc_get_status($process)['running'] && microtime(true) < $deadline) {
        usleep(50000);
      }
      if (proc_get_status($process)['running']) {
        proc_terminate($process, 9);
      }
      break;
    }
    usleep(50000);
  }
  proc_close($process);
  fclose($output);

  aether_arcane('Specter.entity.specter_run');
  return ['code'=> $timedout ? null : $code, 'timeout'=> $timedout, 'time'=> microtime(true) - $start];
}