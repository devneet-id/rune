<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */
define('ETHER', true);


define('SPECTER_ECHOES_SOUL', KEEPER_ECHOES.'/soul.json');

define('SPECTER_ECHOES_CAST', KEEPER_ECHOES.'/cast.json');




define('SPECTER_CAST_DEFAULT', [
  'arg'=> '',
  'alive'=> false,
  'option'=> false,
]);

define('SPECTER_CAST_ARG_DEFAULT', [
  'blocking' => false,
  'visible'  => true,
  'exit'     => true,
  'title'    => 'SPECTER_CAST'
]);

define('SPECTER_SEER_OPTION', [
  'speed' => 100,
  'delay' => null,
  'infinite' => null
]);