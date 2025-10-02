<?php

#NOTE: main essence
$GLOBALS['WHISPER'] = true;

#NOTE: Default text variables for whisper formatting (like newline, tab, etc.)
$GLOBALS['WHISPER_VARS'] = [
  'NL'=> PHP_EOL,
  'TAB'=> '  ',
  'BOLD'=> "\033[1m",
  'END'=> "\033[0;37m",
  'PHP'=> pathinfo(PHP_BINARY, PATHINFO_FILENAME),
];

#NOTE: ANSI color codes for CLI output styling used by whisper
$GLOBALS['WHISPER_COLORS'] = [
  'PRIMARY'   => "\033[01;38;5;12m",
  'SECONDARY' => "\033[01;30m",
  'FAILED'    => "\033[01;31m",
  'SUCCESS'   => "\033[01;32m",
  'WARNING'   => "\033[01;38;5;214m",
  'INFO'      => "\033[01;36m",
  'DANGER'    => "\033[01;31m",
  'ERROR'     => "\033[01;31m",
  'DEBUG'     => "\033[01;30m",
  'DEFAULT'   => "\033[0;37m",
  'END'       => "\033[0;37m",
  'BLACK'     => "\033[30m",
];

#NOTE: ANSI background color codes for CLI output styling used by whisper
$GLOBALS['WHISPER_BG_COLORS'] = [
  'PRIMARY'   => "\033[01;48;5;12m",       // Biru
  'SECONDARY' => "\033[01;100m",      // Abu tua
  'FAILED'    => "\033[01;41m",       // Merah
  'SUCCESS'   => "\033[01;42m",       // Hijau
  'WARNING'   => "\033[48;5;214m\033[30m", // Orange (True color)
  'INFO'      => "\033[01;46m",       // Cyan
  'DANGER'    => "\033[01;41m",       // Merah
  'ERROR'     => "\033[01;41m",       // Merah
  'DEBUG'     => "\033[01;100m",      // Abu tua
  'DEFAULT'   => "\033[47m\033[30m",       // Putih
  'END'       => "\033[0m",        // Reset
];

#NOTE: HTML span-based color definitions for web-based whisper output
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

#NOTE: Default icon markers used in whisper output for each message type
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

#NOTE: Default label prefixes for whisper messages
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

#NOTE: Buffer to store whisper output when in drain mode
$GLOBALS['WHISPER_DRAIN'] = [];

#NOTE: State toggle to determine whether whisper is in drain mode
$GLOBALS['WHISPER_DRAIN_STATE'] = false;

#NOTE: global pointer for flash progress
$GLOBAL['CHANTER_FLASH_POINT'] = 0;