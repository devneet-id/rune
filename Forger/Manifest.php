<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Forger;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {}

  public static function _aether_awaken() {}

  public static function awaken() {}
  
  public static function trace( String $source_path ) {
    $return = forger_trace( $source_path );

    aether_arcane('Forger.manifest.trace');
    return $return;
  }
  
  public static function scan( String $source_path, $callback ) {
    $return = forger_scan($source_path, $callback );

    aether_arcane('Forger.manifest.scan');
    return $return;
  }

  public static function info( String $source_path ) {
    $return = forger_info( $source_path );

    aether_arcane('Forger.manifest.info');
    return $return;
  }
  
  public static function fix( Array $source_path ) {
    $return = forger_fix( $source_path );

    aether_arcane('Forger.manifest.fix');
    return $return;
  }

  public static function clean( String $source_path, bool $force = false ) {
    $return = forger_clean( $source_path, $force );

    aether_arcane('Forger.manifest.clean');
    return $return;
  }

  public static function move( Array $source_path ) {
    $return = forger_move( $source_path );

    aether_arcane('Forger.manifest.move');
    return $return;
  }
  
  public static function repo( String $source_path, ?Callable $callback ) {
    $return = forger_repo( $source_path, $callback );

    aether_arcane('Forger.manifest.repo');
    return $return;
  }
  
  public static function item( String $source_path, $content = false ) {
    $return = forger_item( $source_path, $content );

    aether_arcane('Forger.manifest.item');
    return $return;
  }

  public static function clone( string $from, string $to ) {
    $return = forger_clone( $from, $to );

    aether_arcane('Forger.manifest.clone');
    return $return;
  }

  public static function observer( string $path ) {
    $return = forger_observer( $path );

    aether_arcane('Forger.manifest.observer');
    return $return;
  }

}