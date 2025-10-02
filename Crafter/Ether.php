<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

#NOTE: Flag to enable or indicate the Crafter system is active.
define('CRAFTER', true);

#NOTE: Defines available weaver templates for different language sets and output types (plain, class, function), used during bundling.
define('CRAFTER_WEAVER', [
  [
    ['html','css','js','php'],
    'plain', 1,
    __DIR__ . '/weaver/plain--html-css-js-php.txt',
  ],
  [
    ['html','css','js','php'],
    'class', 2,
    __DIR__ . '/weaver/class--html-css-js-php.txt',
  ],
  [
    ['html','css','js','php'],
    'function', 2,
    __DIR__ . '/weaver/function--html-css-js-php.txt',
  ],
  [
    ['html','css','js'],
    'plain', 1,
    __DIR__ . '/weaver/plain--html-css-js.txt',
  ],
  [
    ['css'],
    'plain', 1,
    __DIR__ . '/weaver/plain--css.txt',
  ],
  [
    ['js'],
    'plain', 1,
    __DIR__ . '/weaver/plain--js.txt',
  ],
  [
    ['php'],
    'plain', 1,
    __DIR__ . '/weaver/plain--php.txt',
  ],
]);

#NOTE: Maps cluster types to variable placeholders used during template binding.
define('CRAFTER_VARIABLE', [
  'head.html'=> 'HTML-HEAD',
  'html'=> 'HTML',
  'css'=> 'CSS',
  'js'=> 'JS',
  'php'=> 'PHP',
]);

#NOTE: Defines cleaning rules for each language type to remove unwanted wrappers or tags before bundling.
define('CRAFTER_CLEANING', [
  'head.html'=> [
    '<!DOCTYPE>', '<!DOCTYPE html>',
    '<html>', '<html lang="en">', '</html>',
    '<head>', '</head>',
    '<body>', '</body>',
    '<style>', '</style>',
  ],
  'html'=> [
    '<!DOCTYPE>', '<!DOCTYPE html>',
    '<html>', '<html lang="en">', '</html>',
    '<head>', '</head>',
    '<body>', '</body>',
    '<style>', '</style>',
  ], 
  'css'=> [
    '<style>', '</style>',
  ],
  'js'=> [
    '<script>', '</script>',
  ],
  'php'=> [
    '<?php', '<?=', '?>',
  ],
]);



/* RESETER
 * todo reset essence to default */
#NOTE: Default seed configuration used when resetting the Crafter state.
define('CRAFTER_RESET_SEED', [
  'TYPE'=> 'class',
  'LANGUAGE'=> ['html', 'css', 'js', 'php'],
  'MINIFIED'=> [],
  'CHARSET'=> 'UTF-8',
  'ENCRYPTION'=> 'base64',
  'LINT'=> false,
  'REPO'=> AETHER_REPO . '/',
]);

#NOTE: Default empty structure for CRAFTER_SPARK used during reset.
define('CRAFTER_RESET_SPARK', [
  'item' => '',
  'seed' => [],
  'shard' => [],
]);

#NOTE: Default spark state, marking the crafter as not ready after reset.
define('CRAFTER_RESET_SPARK_STATE', false);

#NOTE: Default empty cluster groups for each language type after reset.
define('CRAFTER_RESET_SPARK_CLUSTER', [
  'head.html'=> [],
  'html'=> [],
  'css'=> [],
  'js'=> [],
  'php'=> [],
]);