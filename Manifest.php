<?php

namespace Rune;

class Manifest {

  protected static $origin = __DIR__;

  protected static $mana = 0;
  
  // intialize
  public static function arise() {
    self::ether();
    self::essence();
    self::entity();

    if (method_exists(static::class, '_arise')) {
      $name = str_replace('Rune\\', '', static::class);
      $name = str_replace('\\Manifest', '', $name);

      static::_arise();

      aether_arcane($name.'.manifest.arise');
    }    
  }

  // load entity
  public static function entity() {
    global $AETHER_RUNE_ENTITY;
    
    require_once static::$origin . "/Entity.php";

    $AETHER_RUNE_ENTITY[] = static::class;
  }

  // load essence
  public static function essence() {
    global $AETHER_RUNE_ESSENCE;
    
    require_once static::$origin . "/Essence.php";

    $AETHER_RUNE_ESSENCE[] = static::class;
  }

  // load ether
  public static function ether() {
    global $AETHER_RUNE_ETHER;
    
    require_once static::$origin . "/Ether.php";
    
    $AETHER_RUNE_ETHER[] = static::class;
  }

  public static function mana() {
    static $bit = 0; // ← ini penting!
    self::$mana = $bit < PHP_INT_SIZE * 8 ? (1 << $bit++) : null;
    return self::$mana;
  }


}