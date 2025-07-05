<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Aether;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;
  
  public static function begin( $flags = 0 ) {
    global $AETHER_PHANTASM;

    gc_collect_cycles();

    aether_arcane_reset();

    // end
    aether_arcane("Aether.manifest.begin");
  }

  public static function end()
  {
    // end
    aether_arcane("Aether.manifest.end");
    // development mode
    // aether_arcane_pretty_print();
    aether_exit(true);
  }

  public static function dd( $data, $force=false  )
  {
    aether_dd($data);
  }

  public static function exit( $force = false )
  {
    aether_arcane("Aether.manifest.exit");
    aether_exit(true);
  }

}