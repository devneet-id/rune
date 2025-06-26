<?php


$GLOBALS['WHISPER'] = true;

$GLOBALS['WHISPER_VARS'] = [
  'NL'=> PHP_EOL,
  'TAB'=> '  ',
  'END'=> "\033[0;37m",
];

$GLOBALS['WHISPER_COLORS'] = [
  'PRIMARY'   => "\033[01;34m",
  'SECONDARY' => "\033[01;30m",
  'FAILED'    => "\033[01;31m",
  'SUCCESS'   => "\033[01;32m",
  'WARNING'   => "\033[38;5;214m",
  'INFO'      => "\033[01;36m",
  'DANGER'    => "\033[01;31m",
  'ERROR'     => "\033[01;31m",
  'DEBUG'     => "\033[01;30m",
  'DEFAULT'   => "\033[0;37m",
  'END'       => "\033[0;37m",
];

$GLOBALS['WHISPER_COLORS_WEBS'] = [
  'PRIMARY'   => '<span style="color: #0000ff">',
  'SECONDARY' => '<span style="color: #808080">',
  'FAILED'    => '<span style="color: #ff0000">',
  'SUCCESS'   => '<span style="color: #00ff00">',
  'WARNING'   => '<span style="color: #ffaf00">',
  'INFO'      => '<span style="color: #00ffff">',
  'DANGER'    => '<span style="color: #ff0000">',
  'ERROR'     => '<span style="color: #ff0000">',
  'DEBUG'     => '<span style="color: #808080">',
  'DEFAULT'   => '<span style="color: #bfbfbf">',
];

$GLOBALS['WHISPER_ICONS'] = [
  'PRIMARY'   => "[~]",
  'SECONDARY' => "[#]",
  'FAILED'    => "[x]",
  'SUCCESS'   => "[√]",
  'WARNING'   => "[!]",
  'INFO'      => "[i]",
  'DANGER'    => "[x]",
  'ERROR'     => "[x]",
  'DEBUG'     => "[^]",
];

$GLOBALS['WHISPER_LABELS'] = [
  'PRIMARY'   => "PRIMARY: ",
  'SECONDARY' => "SECONDARY: ",
  'FAILED'    => "FAILED: ",
  'SUCCESS'   => "SUCCESS: ",
  'WARNING'   => "WARNING: ",
  'INFO'      => "INFO: ",
  'DANGER'    => "DANGER: ",
  'ERROR'     => "ERROR: ",
  'DEBUG'     => "DEBUG: ",
];

$GLOBALS['WHISPER_DRAIN'] = [];

$GLOBALS['WHISPER_DRAIN_STATE'] = false;
