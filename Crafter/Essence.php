<?php

/*
 * ESSENCE
 * Represents globals and base data for this domain
 */

#NOTE: Main flag to indicate that the Crafter system is active.
$GLOBALS['CRAFTER'] = true;


/* ITEM */
#NOTE: Stores registered crafter items as callable functions.
$GLOBALS['CRAFTER_ITEM'] = [];


/* SEED */
#NOTE: Stores registered crafter items as callable functions.
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
#NOTE: Holds processed shard info from files.
$GLOBALS['CRAFTER_SHARD'] = [];

#NOTE: List of shard file paths to be processed.
$GLOBALS['CRAFTER_SHARD_LIST'] = [];

#NOTE: Injection callbacks associated with shard file paths.
$GLOBALS['CRAFTER_SHARD_INJECTION'] = [];


/* SPARK */
#NOTE: Stores crafting metadata including selected item, seed data, and loaded shards.
$GLOBALS['CRAFTER_SPARK'] = [
  'item' => '',
  'seed' => [],
  'shard' => [],
];

#NOTE: State flags for the crafting process, such as readiness.
$GLOBALS['CRAFTER_SPARK_STATE'] = false;

#NOTE: Clustered content grouped by type, used during bundling (e.g., html, css, js, etc).
$GLOBALS['CRAFTER_SPARK_CLUSTER'] = [
  'head.html'=> [],
  'html'=> [],
  'css'=> [],
  'js'=> [],
  'php'=> [],
];

#NOTE: Final crafted content ready to be written to output.
$GLOBALS['CRAFTER_SPARK_DISTRIBUTE'] = '';