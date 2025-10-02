<?php

namespace Rune;

class Manifest {

  protected static $origin = __DIR__;

  // load entity
  public static function entity() {
    global $AETHER_RUNE_ENTITY;
    
    require_once static::$origin . "/Entity.php";

    $AETHER_RUNE_ENTITY[] = static::class;
    return static::class;
  }

  // load essence
  public static function essence() {
    global $AETHER_RUNE_ESSENCE;
    
    require_once static::$origin . "/Essence.php";

    $AETHER_RUNE_ESSENCE[] = static::class;
    return static::class;
  }

  // load ether
  public static function ether() {
    global $AETHER_RUNE_ETHER;
    
    require_once static::$origin . "/Ether.php";
    
    $AETHER_RUNE_ETHER[] = static::class;
    return static::class;
  }


}