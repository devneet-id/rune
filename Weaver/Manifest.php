<?php

namespace Rune\Weaver;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {}

  public static function _aether_awaken() {}

  public static function awaken() {}

  public static function bind( String $template, $searchOrArray, String $data='' ) {

    if (is_array($searchOrArray)) {
      $return = weaver_bind_multiple($template, $searchOrArray);
    }else {
      $return = weaver_bind($template, $searchOrArray, $data);
    }

    aether_arcane('Weaver.manifest.bind');
    return $return;
  }

  public static function item( $source ) {
    $return = weaver_item($source);

    aether_arcane('Weaver.manifest.item');
    return $return;
  }

  // public static function load( $source ) {
  //   return file_get_contents($source);
  // }

  // public static function bind( $template, $search, $data ) {
  //   $search = strtoupper($search);
  //   $parse = str_replace("{{ ".$search." }}", $data, $template);
  //   $parse = str_replace("{{".$search."}}", $data, $template);
  //   return $parse;
  // }

  // public static function bindAll( $template, $list) {
  //   foreach ($list as $key => $value) {
  //     $template = self::bind($template, $key, $value);
  //   }
  //   return $template;
  // }

}