<?php

namespace Rune;

class Phantasm {

  public $origin = __DIR__;

  public $version = 0.1;
  
  public $main = '?';

  public $user = '?';

  public $note = '?';

  public $mark = 'VOID';

  public $link = [];

  #NOTE: true when the rune has a begin() and an end() the runite must call (Sentinel writes them at #sentinel-flow-begin / -end).
  public $flow = false;

  public $node = [];

}