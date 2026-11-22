<?php

/*
 * ESSENCE
 * Represents globals and base data for this domain
 */
#NOTE: main essence
$GLOBALS['KEEPER'] = true;

#NOTE: Defines stepwatch thresholds for classifying performance stages in arcane processing (e.g., BURST to OVERCLOCK).
$GLOBALS['KEEPER_ARCANE'] = [
  [0.0000, 'BURST'],
  [0.0001, 'FLASH'],
  [0.0010, 'FLOW'],
  [2.000, 'FALL'],           
  [5.500, 'BREAK'],
  [8.500, 'COLLAPSE'],
  [10.000, 'OVERCLOCK'],
];

#NOTE: Whether Keeper is saving echoes in this flow.
$GLOBALS['KEEPER_SAVING_ACTIVE'] = false;

#NOTE: The warnings tolerated in this flow (levels: warning, notice, deprecated), or 'throw' to turn them into an ErrorException.
$GLOBALS['KEEPER_TOLERATE'] = [];

#NOTE: Which items are read and written under a file lock (flock): false (none, the default), true (all), or a list of item names; a runite sets it for items shared by several processes.
$GLOBALS['KEEPER_LOCK'] = false;

#NOTE: Seconds to wait for the lock of an item; past it, a glitch names the item.
$GLOBALS['KEEPER_LOCK_WAIT'] = 5;
