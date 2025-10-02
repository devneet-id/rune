<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Keeper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  #NOTE: Special hook for aether-based awakening phase, executed at the start of the crafter process.
  public static function begin( $flags = 0 ) {
    forger_fix([
      [
        'target'=> KEEPER_ECHOES,
        'type'=> 'repo'
      ],
      [
        'target'=> KEEPER_ECHOES_ARCANES,
        'type'=> 'repo'
      ],
      [
        'target'=> KEEPER_ECHOES_SHARDS,
        'type'=> 'repo'
      ],
      [
        'target'=> KEEPER_ECHOES_ARCANE,
        'type'=> 'item'
      ],
      [
        'target'=> KEEPER_ECHOES_SHARD,
        'type'=> 'item'
      ],
    ]);

    keeper_glitch_boot();
  }
  
  #NOTE: Final phase of the class lifecycle, called after all manifest components are registered and ready.
  public static function end() {
    aether_arcane('Keeper.manifest.end');

    keeper_arcane_process();
  }


  #NOTE: Reads or writes a raw file inside the given repo depending on value.
  public static function echo( String $repo, String $name, $value = '' ) {
    if (empty($value)) {
      $return = keeper_echo_get($repo, $name);
    }else {
      $return = keeper_echo_set($repo, $name, $value);
    }

    aether_arcane('Keeper.manifest.echo');
    return $return;
  }
  
  #NOTE: Gets or sets a structured keeper item in JSON format.
  public static function item( String $name, $value = false ) {
    if ($value === false) {
      $return = keeper_item_get($name);
    }else {
      $return = keeper_item_set($name, $value);
    }

    aether_arcane('Keeper.manifest.item');
    return $return;
  }

  #NOTE: Saves rune shard(s) from string or array source.
  public static function shard(Array|String $source): Bool
  {
    if (is_array($source)) {
      keeper_shard_set_all($source);
    } elseif (is_string($source)) {
      keeper_shard_set($source);
    } else {
      throw new InvalidArgumentException("Parameter \$source must be string or array of string.");
    }

    aether_arcane('Keeper.manifest.shard');
    return true;
  }


}