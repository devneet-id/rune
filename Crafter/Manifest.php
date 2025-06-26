<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Crafter;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  protected static $inName = '';

  public static function _arise() {}

  public static function _aether_awaken() {}
  
  public static function awaken() {}


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

  public static function seed( String $name, Mixed $value = NULL ) {
    if ($value) {
      $return = crafter_seed_set($name, $value);
    }else {
      $return = crafter_seed_get($name);
    }

    aether_arcane("Crafter.manifest.seed");
    return $return;
  }

  public static function shard( String $source, ?Callable $injection = NULL ) {
    $return = crafter_shard_get($source, $injection);

    aether_arcane("Crafter.manifest.shard");
    return $return;
  }
  
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