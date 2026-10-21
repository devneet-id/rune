<?php

/*
 * ESSENCE
 * Represents globals and base data for this domain
 *
 * example:
 * $GLOBALS['STARTER'] = '';
 */

#NOTE: Start time of the flow, used for the Execute time.
$GLOBALS['AETHER_STOPWATCH'] = microtime(true);

#NOTE: Start time used to measure arcane steps.
$GLOBALS['AETHER_ARCANE_STOPWATCH'] = microtime(true);

#NOTE: Time of the last arcane step.
$GLOBALS['AETHER_ARCANE_STOPWATCH_STEP'] = microtime(true);


// $GLOBALS['AETHER_PHANTASM'] = [];
// $GLOBALS['AETHER_FAMILIAR'] = false;
// $GLOBALS['AETHER_ARISED'] = [];

/* RUNE */
#NOTE: Runes awakened in this flow.
$GLOBALS['AETHER_RUNE'] = [];

#NOTE: Runes whose ether is awakened.
$GLOBALS['AETHER_RUNE_ETHER'] = [];

#NOTE: Runes whose essence is awakened.
$GLOBALS['AETHER_RUNE_ESSENCE'] = [];

#NOTE: Runes whose entity is awakened.
$GLOBALS['AETHER_RUNE_ENTITY'] = [];

#NOTE: Runes whose manifest is awakened.
$GLOBALS['AETHER_RUNE_MANIFEST'] = [];

#NOTE: Phantasm of the awakened runes.
$GLOBALS['AETHER_RUNE_PHANTASM'] = [];

#NOTE: Zero-trust state (output drained, arcane disabled).
$GLOBALS['AETHER_ZERO_TRUST'] = false;






/* ARCANE
 *  */
#NOTE: Arcane records of this flow.
$GLOBALS['AETHER_ARCANE'] = [];

#NOTE: Whether arcane is recorded (enable/disable).
$GLOBALS['AETHER_ARCANE_STATE'] = true;



