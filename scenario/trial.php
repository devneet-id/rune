<?php

/*
 * TRIAL
 * A tiny test helper shared by every scenario runite.
 *
 *   trial('label', $condition)          -> records a pass or a fail
 *   trial_capture(fn() => ...)           -> runs a callable and returns its Whisper output as plain text
 *   trial_cast('greet --name=Anwar')     -> casts a registered cast and returns its plain output
 *   trial_report()                       -> prints the summary
 */

$GLOBALS['TRIAL'] = ['pass' => 0, 'fail' => 0, 'failed' => []];

function trial( String $label, $condition ) {
  global $TRIAL;

  if ($condition) {
    $TRIAL['pass']++;
    whisper_echo("{{color-success}} ✓ {{color-end}}$label{{nl}}");
  } else {
    $TRIAL['fail']++;
    $TRIAL['failed'][] = $label;
    whisper_echo("{{color-danger}} ✗ $label{{color-end}}{{nl}}");
  }
  return (bool) $condition;
}

function trial_plain( String $text ) {
  return preg_replace('/\x1b\[[0-9;]*[A-Za-z]/', '', $text);
}

// warnings become exceptions inside a trial, so a glitch fails one check instead of ending the run
function trial_guard() {
  set_error_handler(function($no, $message, $file, $line) {
    throw new \ErrorException($message, 0, $no, $file, $line);
  });
}

function trial_capture( Callable $run ) {
  trial_guard();
  try {
    return trial_plain((string) \Rune\Whisper\Manifest::drain($run, true));
  } catch (\Throwable $glitch) {
    whisper_drain_end();
    return 'GLITCH: ' . $glitch->getMessage() . ' (' . basename($glitch->getFile()) . ':' . $glitch->getLine() . ')';
  } finally {
    restore_error_handler();
  }
}

// runs a callable, returns true when it finished without a glitch
function trial_safe( Callable $run ) {
  trial_guard();
  try {
    $run();
    return true;
  } catch (\Throwable $glitch) {
    whisper_echo("{{color-warning}}   glitch: " . $glitch->getMessage() . ' (' . basename($glitch->getFile()) . ':' . $glitch->getLine() . "){{color-end}}{{nl}}");
    return false;
  } finally {
    restore_error_handler();
  }
}

function trial_cast( String $arg ) {
  $out = trial_capture(function() use ($arg) {
    \Rune\Chanter\Manifest::cast($arg)();
  });
  if (str_starts_with($out, 'GLITCH')) {
    whisper_echo("{{color-warning}}   $arg -> $out{{color-end}}{{nl}}");
  }
  return $out;
}

function trial_section( String $title ) {
  whisper_echo("{{nl}}{{color-info}}# $title{{color-end}}{{nl}}");
}

function trial_report() {
  global $TRIAL;

  $total = $TRIAL['pass'] + $TRIAL['fail'];
  if ($TRIAL['fail'] === 0) {
    whisper_echo("{{nl}}{{color-success}}{{icon-success}} TRIAL PASSED {$TRIAL['pass']}/$total{{color-end}}{{nl}}");
  } else {
    whisper_echo("{{nl}}{{color-danger}}{{icon-warning}} TRIAL FAILED {$TRIAL['fail']}/$total: " . implode(', ', $TRIAL['failed']) . "{{color-end}}{{nl}}");
  }
  return $TRIAL['fail'] === 0;
}
