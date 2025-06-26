<?php

/*
 * ARISE
 * Represents the main static controller for this domain.
 */

namespace Rune\Keeper;

class Manifest extends \Rune\Manifest {

  protected static $origin = __DIR__;

  public static function _arise() {
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
    ]);
  
    keeper_glitch_boot();
  }
  
  public static function _aether_awaken_after() {
    self::awaken();
  }
  
  public static function awaken() {
    
    // $memory = aether_memoryusage();
    // keeper_item('aether', [
    //   'FILE'=> AETHER_FILE,
    //   'REPO'=> AETHER_REPO,
    //   'VERSION'=> AETHER_VERSION,
    //   'SIZE'=> filesize(AETHER_FILE),
    //   'MEMORY'=> [$memory[0], $memory[1]],
    //   'RUNE'=> aether_arised(),
    // ]);
    
    aether_arcane('Keeper.manifest.awaken');
    keeper_arcane_process();
  }


  public static function echo( String $repo, String $name, $value = '' ) {
  if (empty($value)) {
    $return = keeper_echo_get($repo, $name);
  }else {
    $return = keeper_echo_set($repo, $name, $value);
  }

  aether_arcane('Keeper.manifest.echo');
  return $return;
}
  
  public static function item( String $name, $value = false ) {
    if ($value === false) {
      $return = keeper_item_get($name);
    }else {
      $return = keeper_item_set($name, $value);
    }

    aether_arcane('Keeper.manifest.item');
    return $return;
  }

  public static function shard( Array $file_maps, Bool $is_revoke = false ) {
    if ($is_revoke) {
      keeper_shard_get($file_maps);
    }else {
      keeper_shard_set($file_maps);
    }

    aether_arcane('Keeper.manifest.shard');
    return true;
  }

}