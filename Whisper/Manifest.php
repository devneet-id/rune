<?php

namespace Rune\Whisper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;
  
  public static function _arise() {}

  public static function _aether_awaken() {}

  public static function awaken() {}

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

  public static function call( String $text ) {
    $result = whisper_call( $text );
    
    aether_arcane('Whisper.manifest.drain');
    return $result;
  }

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
    
    if (!$asString) {
      self::echo($return);
    }
    
    aether_arcane('Whisper.manifest.latch');
    return $return;
  }

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