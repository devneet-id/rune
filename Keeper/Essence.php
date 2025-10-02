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

$GLOBALS['KEEPER_SAVING_ACTIVE'] = false;