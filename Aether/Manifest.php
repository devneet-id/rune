<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Aether;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;
  
  #NOTE: Begins the Aether flow: cleans memory and resets arcane.
  public static function begin( $flags = 0 ) {
    global $AETHER_PHANTASM;

    gc_collect_cycles();

    aether_arcane_reset();

    // end
    aether_arcane("Aether.manifest.begin");
  }

  #NOTE: Ends the Aether flow and exits with the version footer.
  public static function end()
  {
    // end
    aether_arcane("Aether.manifest.end");
    // development mode
    // aether_arcane_pretty_print();
    aether_exit(true);
  }

  #NOTE: Dumps data and stops the flow.
  public static function dd( $data, $force=false  )
  {
    aether_dd($data);
  }

  #NOTE: Exits the flow now with the version footer.
  public static function exit( $force = false )
  {
    aether_arcane("Aether.manifest.exit");
    aether_exit(true);
  }

}