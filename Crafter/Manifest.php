<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Crafter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  protected static $inName = '';

  #NOTE: Registers or retrieves a crafter item by name; sets the active name if callable is provided.
  public static function item(string $name, ?callable $callable = null) {
    if ($callable) {
      crafter_item_set($name, $callable);
      self::$inName = $name;
      $return = self::class;
    } else {
      $return = crafter_item_get($name);
    }

    aether_arcane("Crafter.manifest.item");
    return $return;
  }

  #NOTE: Sets or retrieves a value from the crafter seed configuration.
  public static function seed(string $name, mixed $value = null) {
    if ($value !== null) {
      $return = crafter_seed_set($name, $value);
    } else {
      $return = crafter_seed_get($name);
    }

    aether_arcane("Crafter.manifest.seed");
    return $return;
  }

  #NOTE: Retrieves or registers a shard source with optional injection logic.
  public static function shard(string $source, ?callable $injection = null) {
    $return = crafter_shard_get($source, $injection);

    aether_arcane("Crafter.manifest.shard");
    return $return;
  }

  #NOTE: Executes the full crafting process using item name or callable, then resets the crafter state and shows crafting summary.
  public static function spark(mixed $name_or_callable = null, ?callable $injection = null) {
    $name = empty($name_or_callable) ? self::$inName : $name_or_callable;

    if (is_callable($name_or_callable)) {
      $injection = $name_or_callable;
    }

    $return = crafter_spark($name, $injection);

    aether_arcane("Crafter.manifest.spark");
    return $return;
  }


}