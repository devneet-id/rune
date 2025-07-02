<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

#NOTE: main ether
define('SPECTER', true);

#NOTE: Path to the file where Specter soul data (state/configuration) is stored.
define('SPECTER_ECHOES_SOUL', KEEPER_ECHOES.'/soul.json');

#NOTE: Path to the file where Specter cast data (execution history/config) is stored.
define('SPECTER_ECHOES_CAST', KEEPER_ECHOES.'/cast.json');

#NOTE: Default structure for a Specter cast, indicating execution state and options.
define('SPECTER_CAST_DEFAULT', [
  'arg'=> '',
  'alive'=> false,
  'option'=> false,
]);

#NOTE: Default command-line options for Specter cast executions.
define('SPECTER_CAST_ARG_DEFAULT', [
  'blocking' => false,
  'visible'  => true,
  'exit'     => true,
  'title'    => 'SPECTER_CAST'
]);

#NOTE: Default configuration for Specter Seer, mainly controlling loop speed and delays.
define('SPECTER_SEER_OPTION', [
  'speed' => 100,
  'delay' => null,
  'infinite' => null
]);