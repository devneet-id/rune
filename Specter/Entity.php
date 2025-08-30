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
#NOTE: Sets a soul value and persists it through the keeper.
function specter_soul_set( String $name, Mixed $value ) {
  global $SPECTER_SOUL;
  
  specter_soul_save($name, $value);

  return $value;
}
#NOTE: Retrieves a soul value and refreshes the in-memory state.
function specter_soul_get( String $name ) {
  global $SPECTER_SOUL;

  specter_soul_save($name);
  
  if (isset($SPECTER_SOUL->$name)) {
    $return = $SPECTER_SOUL->$name;
  }else {
    $return = false;
  };
  
  return $return;
}
#NOTE: Loads and syncs soul data from keeper, optionally updates a value.
function specter_soul_save( $name, $value = null ) {
  global $SPECTER_SOUL;
  
  $soul_keeper = keeper_item_get('soul');
  if (empty($soul_keeper)) {
    keeper_item_set('soul', []);
    $soul_keeper = keeper_item_get('soul');
  }
  if (!is_object($soul_keeper)) {
    $soul_keeper = (object) $soul_keeper;
  }

  $SPECTER_SOUL = $soul_keeper;

  if (!empty($value)) {
    $SPECTER_SOUL->$name = $value;
  }

  keeper_item_set('soul', $SPECTER_SOUL);
}
#NOTE: Removes a soul value from both memory and keeper storage.
function specter_soul_remove( String $name ) {
  global $SPECTER_SOUL;
  
  $soul_keeper = keeper_item_get('soul');
  if (empty($soul_keeper)) {
    keeper_item_set('soul', []);
    $soul_keeper = keeper_item_get('soul');
  }
  if (!is_object($soul_keeper)) {
    $soul_keeper = (object) $soul_keeper;
  }

  $SPECTER_SOUL = $soul_keeper;

  if (isset($SPECTER_SOUL->$name)) {
    unset($SPECTER_SOUL->$name);
  }

  keeper_item_set('soul', $SPECTER_SOUL);
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
        shell_exec("$cmd > /dev/null 2>&1 &");
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