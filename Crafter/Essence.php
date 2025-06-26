<?php

/*
 * ESSENCE
 * Represents globals and base data for this domain
 */

$GLOBALS['CRAFTER'] = true;


/* ITEM */
$GLOBALS['CRAFTER_ITEM'] = [];


/* SEED */
$GLOBALS['CRAFTER_SEED'] = [
  'TYPE'=> 'class',
  'LANGUAGE'=> ['html', 'css', 'js', 'php'],
  'MINIFIED'=> [],
  'CHARSET'=> 'UTF-8',
  'ENCRYPTION'=> 'base64',
  'LINT'=> false,
  'REPO'=> AETHER_REPO . '/',
];


/* SHARD */
$GLOBALS['CRAFTER_SHARD'] = [];

$GLOBALS['CRAFTER_SHARD_LIST'] = [];

$GLOBALS['CRAFTER_SHARD_INJECTION'] = [];


/* SPARK */
$GLOBALS['CRAFTER_SPARK'] = [
  'item' => '',
  'seed' => [],
  'shard' => [],
];

$GLOBALS['CRAFTER_SPARK_STATE'] = [
  'ready' => false,
];

$GLOBALS['CRAFTER_SPARK_CLUSTER'] = [
  'head.html'=> [],
  'html'=> [],
  'css'=> [],
  'js'=> [],
  'php'=> [],
];

$GLOBALS['CRAFTER_SPARK_DISTRIBUTE'] = '';