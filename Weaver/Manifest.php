<?php

namespace Rune\Weaver;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: Ends Weaver's part of the flow (nothing to close yet).
  public static function end() {}

  #NOTE: Bind one or multiple variables into a template string
  public static function bind( String $template, $searchOrArray, String $data='' ) {
    if (is_array($searchOrArray)) {
      $return = weaver_bind_multiple($template, $searchOrArray);
    }else {
      $return = weaver_bind($template, $searchOrArray, $data);
    }

    aether_arcane('Weaver.manifest.bind');
    return $return;
  }

  #NOTE: Set or retrieve a key-value bond for template variable storage
  public static function bond( String $key, Mixed $value ) {
    if (isset($value)) {
      $return = weaver_bond_set($key, $value);
    }else {
      $return = weaver_bond_get($key);
    }

    aether_arcane('Weaver.manifest.bond');
    return $return;
  }

  #NOTE: Load a template file into memory and optionally assign it an alias
  public static function item( String $source, String $alias='' ) {
    if (!weaver_item_get($source)) {
      $return = weaver_item_set($source, $alias);
    }else {
      $return = weaver_item_get($source);
    }

    aether_arcane('Weaver.manifest.item');
    return $return;
  }

}