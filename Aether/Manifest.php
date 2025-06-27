<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Aether;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {}

  
  public static function origin( $flags = 0 ) {
    global $AETHER_PHANTASM;

    gc_collect_cycles();
    aether_arcane_reset();
    
    // bindrune
    require_once __DIR__ . '/bindrune/rune.php';
    require_once __DIR__ . '/bindrune/grimoire.php';
    require_once __DIR__ . '/bindrune/sentinel.php';
    require_once __DIR__ . '/bindrune/artefact.php';

    // end
    aether_arcane("Aether.manifest.origin");
  }

  public static function awaken()
  {
    // auto awaken
    $arised = aether_arised();
    foreach ($arised as $manifest) {
      if (method_exists($manifest, '_aether_awaken_before')) {
        $manifest::_aether_awaken_before();
      }
    }
    foreach ($arised as $manifest) {
      if (method_exists($manifest, '_aether_awaken')) {
        $manifest::_aether_awaken();
      }
    }
    foreach ($arised as $manifest) {
      if (method_exists($manifest, '_aether_awaken_after')) {
        $manifest::_aether_awaken_after();
      }
    }

    // end
    aether_arcane("Aether.manifest.awaken");
    // development mode
    // aether_arcane_pretty_print();
    aether_exit(true);
  }

  public static function awakening()
  {
    require_once __DIR__ . '/bindrune/awakening/index.php';
  }

}