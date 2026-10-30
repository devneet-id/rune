<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

#NOTE: Flag that the Aether flow is active.
define('AETHER', true);

#NOTE: The runite being cast, as it was typed (e.g. cast, realm/cast).
define('AETHER_FILE', $_SERVER['PHP_SELF']);

#NOTE: The folder of the runite itself, wherever it is cast from (falls back to getcwd).
define('AETHER_REPO', (($AETHER_RUNITE = realpath($_SERVER['SCRIPT_FILENAME'] ?? '')) !== false && is_file($AETHER_RUNITE)) ? dirname($AETHER_RUNITE) : getcwd());

#NOTE: Version of the Rune core.
define('AETHER_VERSION', '1.11');

#NOTE: Copyright line placed in crafted and artefact output.
define('AETHER_COPYRIGHT', 'RUNE '.AETHER_VERSION.' | Created By @anwarachilles');

#NOTE: Folder of the origin runes.
define('AETHER_RUNE_LOCATION', realpath(__DIR__.'/../'));

#NOTE: Folder of Aether's own weaver templates.
define('AETHER_SELF_WEAVER', __DIR__.'/weaver/');

#NOTE: Legacy logs folder inside .echoes (kept for old runites).
define('AETHER_LOGS', AETHER_REPO.'/.echoes/logs/');

#NOTE: The .echoes folder of the working folder.
define('AETHER_ECHOES', AETHER_REPO.'/.echoes/');

#NOTE: Legacy echoes folder of the runite (Keeper now keeps the echoes).
define('AETHER_ECHOES_RUNE', AETHER_ECHOES . '/' . basename(AETHER_FILE) . '/');

#NOTE: Legacy information file of the runite echoes.
define('AETHER_ECHOES_INFORMATION', AETHER_ECHOES_RUNE.'/information.json');

#NOTE: Legacy arcane file of the runite echoes.
define('AETHER_ECHOES_ARCANE', AETHER_ECHOES_RUNE.'/arcane.txt');

#NOTE: Legacy arcanes file of the runite echoes.
define('AETHER_ECHOES_ARCANES', AETHER_ECHOES_RUNE.'/arcanes.txt');

#NOTE: Legacy artefact folder of the runite echoes.
define('AETHER_ECHOES_ARTEFACT', AETHER_ECHOES_RUNE.'/artefacts/');

#NOTE: PHP version the Rune core was built with.
define('AETHER_PHP_VERSION', '8.1.10');