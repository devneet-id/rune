<?php

/*
 * ETHER
 * Represents constants and rules for this domain.
 */

define('CRAFTER', true);

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
]);

define('CRAFTER_VARIABLE', [
  'head.html'=> 'HTML-HEAD',
  'html'=> 'HTML',
  'css'=> 'CSS',
  'js'=> 'JS',
  'php'=> 'PHP',
]);

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
define('CRAFTER_RESET_SEED', [
  'TYPE'=> 'class',
  'LANGUAGE'=> ['html', 'css', 'js', 'php'],
  'MINIFIED'=> [],
  'CHARSET'=> 'UTF-8',
  'ENCRYPTION'=> 'base64',
  'LINT'=> false,
  'REPO'=> AETHER_REPO . '/',
]);

define('CRAFTER_RESET_SPARK', [
  'item' => '',
  'seed' => [],
  'shard' => [],
]);

define('CRAFTER_RESET_SPARK_STATE', [
  'ready' => false,
]);

define('CRAFTER_RESET_SPARK_CLUSTER', [
  'head.html'=> [],
  'html'=> [],
  'css'=> [],
  'js'=> [],
  'php'=> [],
]);