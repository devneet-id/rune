<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Crafter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  protected static $inName = '';

  #NOTE: Optional lifecycle method for internal post-arise logic.
  public static function _arise() {}

  #NOTE: Special hook for aether-based awakening phase, executed at the end of the crafter process.
  public static function _aether_awaken() {}
  
  #NOTE: Final phase of the class lifecycle, called after all manifest components are registered and ready.
  public static function awaken() {}

  #NOTE: Registers or retrieves a crafter item by name; sets the active name if callable is provided.
  public static function item( String $name, ?Callable $callable = NULL ) {
    if ($callable) {
      crafter_item_set($name, $callable);
      self::$inName = $name;
      $return = self::class;
    }else {
      $return = crafter_item_get($name);
    }

    aether_arcane("Crafter.manifest.item");
    return $return;
  }

  #NOTE: Sets or retrieves a value from the crafter seed configuration.
  public static function seed( String $name, Mixed $value = NULL ) {
    if ($value) {
      $return = crafter_seed_set($name, $value);
    }else {
      $return = crafter_seed_get($name);
    }

    aether_arcane("Crafter.manifest.seed");
    return $return;
  }

  #NOTE: Retrieves or registers a shard source with optional injection logic.
  public static function shard( String $source, ?Callable $injection = NULL ) {
    $return = crafter_shard_get($source, $injection);

    aether_arcane("Crafter.manifest.shard");
    return $return;
  }

  #NOTE: Executes the full crafting process using item name or callable, then resets the crafter state and shows crafting summary.
  public static function spark( Mixed $name_or_callable = NULL, ?Callable $injection = NULL ) {
    if (empty($name_or_callable)) {
      $name = self::$inName;
    }else {
      $name = $name_or_callable;
    }

    if (is_callable($name_or_callable)) {
      $injection = $name_or_callable;
    }

    $return = crafter_spark($name, $injection);

    crafter_spark_message();
    crafter_reset();
    
    aether_arcane("Crafter.manifest.spark");
    return $return;
  }

}