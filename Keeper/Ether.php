<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

#NOTE: main ether
define('KEEPER', true);

#NOTE: Base directory for all Keeper-related output files and logs (next to the runite).
define('KEEPER_ECHOES', AETHER_REPO . '/.echoes/' . basename(AETHER_FILE) . '/');

#NOTE: Path to the main keeper state file.
define('KEEPER_ECHOES_KEEPER', KEEPER_ECHOES.'/keeper.json');

#NOTE: Path to the statistics log file for tracking system data.
define('KEEPER_ECHOES_STATS', KEEPER_ECHOES.'/stats.json');

#NOTE: Path to the active arcane log file containing process entries.
define('KEEPER_ECHOES_ARCANE', KEEPER_ECHOES.'/arcane.txt');

#NOTE: Path to the glitch log file for captured errors and exceptions.
define('KEEPER_ECHOES_GLITCH', KEEPER_ECHOES.'/glitch.txt');

#NOTE: Directory for archived arcane logs.
define('KEEPER_ECHOES_ARCANES', KEEPER_ECHOES.'/arcanes/');

#NOTE: Directory for encoded shard backups.
define('KEEPER_ECHOES_SHARDS', KEEPER_ECHOES.'/shards/');

#NOTE: Shard list of the runite (files carried into the artefact).
define('KEEPER_ECHOES_SHARD', KEEPER_ECHOES.'/shard.json');



#NOTE: Flag bit for Keeper::begin(): save echoes.
define('KEEPER_SAVING_ECHOES', \Rune\Ethereal::energy());

#NOTE: Flag bit for Keeper::begin(): show glitch screen.
define('KEEPER_SHOW_GLITCH', \Rune\Ethereal::energy());