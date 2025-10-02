<?php

namespace Rune\Whisper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;
  
  #NOTE: Optional lifecycle method for internal post-arise logic.
  public static function _arise() {}

  #NOTE: Special hook for aether-based awakening phase, executed at the end of the crafter process.
  public static function _aether_end() {}

  #NOTE: Final phase of the class lifecycle, called after all manifest components are registered and ready.
  public static function end() {}

  #NOTE: Print or return a formatted whisper message with color support
  public static function echo( String $message, Bool $asString = false ) {
    if ($asString) {
      $return = whisper_echo_get($message . '{{color-end}}');
    }else {
      whisper_echo_set($message . '{{color-end}}');
      $return = self::class;
    }

    aether_arcane('Whisper.manifest.echo');
    return $return;
  }

  #NOTE: Prompt user for input through STDIN with a whisper message
  public static function call( String $text ) {
    $result = whisper_call( $text );
    
    aether_arcane('Whisper.manifest.call');
    return $result;
  }

  #NOTE: Start, process, or return a buffered whisper output (drain mode)
  public static function drain( Mixed $state_or_process, Bool $asString = false ) {
    if (is_callable($state_or_process)) {
      whisper_drain_start();
      $state_or_process();
      $return = whisper_drain_get();
      whisper_drain_end();
    }
    if (is_bool($state_or_process)) {
      if ($state_or_process==true) {
        whisper_drain_start();
        $return = true;
      }
      if ($state_or_process==false) {
        $return = whisper_drain_get();
        whisper_drain_end();
      }
    }
    
    if ($asString==false && $state_or_process==false) {
      self::echo($return);
      return true;
    }
    
    aether_arcane('Whisper.manifest.drain');
    return $return;
  }

  #NOTE: Clear terminal screen, optionally forcing it for cross-platform
  public static function clear( Bool $force = false ) {
    if ($force) {
      whisper_clear_force();
      whisper_clear();
    }else {
      whisper_clear();
    }

    aether_arcane('Whisper.manifest.clear');
    return self::class;
  }
}