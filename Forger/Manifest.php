<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Forger;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: Optional lifecycle method for internal post-arise logic.
  public static function _arise() {}

  #NOTE: Special hook for aether-based awakening phase, executed at the end of the crafter process.
  public static function _aether_end() {}

  #NOTE: Final phase of the class lifecycle, called after all manifest components are registered and ready.
  public static function end() {}
  
  #NOTE: Traces and resolves each part of a given path, tagging them as file (item) or folder (repo) along with their existence.
  public static function trace( String $source_path, ?Callable $callback = null ) {
    $return = forger_trace( $source_path, $callback );

    aether_arcane('Forger.manifest.trace');
    return $return;
  }
  
  #NOTE: Scans a directory for items, applies a callback to each, and returns the result list.
  public static function scan( String $source_path, ?Callable $callback = null ) {
    $return = forger_scan($source_path, $callback );

    aether_arcane('Forger.manifest.scan');
    return $return;
  }

  #NOTE: Retrieves detailed information about a file or directory, including type, basename, name, extension, and path.
  public static function info( String $source_path ) {
    $return = forger_info( $source_path );

    aether_arcane('Forger.manifest.info');
    return $return;
  }
  
  #NOTE: Ensures all given paths exist by creating missing directories or files based on their type.
  public static function fix( Array $source_path ) {
    $return = forger_fix( $source_path );

    aether_arcane('Forger.manifest.fix');
    return $return;
  }

  #NOTE: Cleans a file or directory; if forced as repo, recursively traces and removes all contained files and folders.
  public static function clean( String $source_path, bool $force = false ) {
    $return = forger_clean( $source_path, $force );

    aether_arcane('Forger.manifest.clean');
    return $return;
  }

  #NOTE: Moves or clones items and repositories to their target paths, ensuring destination structure exists beforehand.
  public static function move( Array $source_path ) {
    $return = forger_move( $source_path );

    aether_arcane('Forger.manifest.move');
    return $return;
  }
  
  #NOTE: Ensures the repository path exists, fixes missing parts, and optionally scans items with a callback.
  public static function repo( string $source_path, bool $isRecursion = false ) {
    $return = forger_repo( $source_path, $isRecursion );

    aether_arcane('Forger.manifest.repo');
    return $return;
  }
  
  #NOTE: Ensures the file exists, optionally writes content to it, and returns its contents.
  public static function item( string $source_path, Mixed $content = false, int $flags = 0 ) {
    $return = forger_item( $source_path, $content, $flags );

    aether_arcane('Forger.manifest.item');
    return $return;
  }

  #NOTE: Recursively clones a directory and its contents to a target location, creating folders as needed and copying files.
  public static function clone( string $from, string $to ) {
    $return = forger_clone( $from, $to );

    aether_arcane('Forger.manifest.clone');
    return $return;
  }

  #NOTE: Observes a directory recursively and returns the latest modification timestamp among all files.
  public static function observer( string $path ) {
    $return = forger_observer( $path );

    aether_arcane('Forger.manifest.observer');
    return $return;
  }

}