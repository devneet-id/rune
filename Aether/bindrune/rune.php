<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Whisper\Manifest as Whisper;
use Rune\Weaver\Manifest as Weaver;

// base/default
Chanter::cast('rune', function() {
  global $AETHER_RUNE_ETHER;
  global $AETHER_RUNE_ESSENCE;
  global $AETHER_RUNE_ENTITY;
  global $CHANTER_ARGS;
  global $CHANTER_ECHO;
  
  foreach ($CHANTER_ECHO as $key => $echo) {
    $text = '{{color-info}} ∙ {{color-secondary}}php ' . AETHER_FILE . '{{color-end}} ' . $echo[1];
    if (!empty($echo[2])) {
      $text .= '{{nl}}{{tab}}{{tab}}{{color-secondary}}::: ' . $echo[2] . '{{color-end}}{{nl}}';
    }
    $list_chanter[] = $text;
  }

  $base_cast = [];
  for ($i=0; $i<4; $i++) {
    $base_cast[] = $list_chanter[$i];
    unset($list_chanter[$i]);
  }
  
  $keeper_cast = [$base_cast, $list_chanter];

  $base_cast = implode(PHP_EOL, $base_cast);
  $registered_cast = implode(PHP_EOL, $list_chanter);
  
  $echoes_state = '{{color-secondary}}';
  $echoes_check = '{{color-success}}√{{color-secondary}}';
  if (aether_has_entity('keeper')) {
    if (file_exists(KEEPER_ECHOES.'/rune.json')) {
      $echoes_state .= $echoes_check."Rune, ";
    }
    if (file_exists(KEEPER_ECHOES_ARCANE)) {
      $echoes_state .= $echoes_check."Arcane, ";
    }
    if (file_exists(KEEPER_ECHOES_GLITCH)) {
      $echoes_state .= $echoes_check."Glitch, ";
    }
    if (file_exists(KEEPER_ECHOES_SHARDS)) {
      $echoes_state .= $echoes_check."Shard, ";
    }
    if (file_exists(KEEPER_ECHOES.'/grimoire.json')) {
      $echoes_state .= $echoes_check."Grimoire, ";
    }
    if (file_exists(KEEPER_ECHOES.'/cast.json')) {
      $echoes_state .= $echoes_check."Cast, ";
    }
    if (file_exists(KEEPER_ECHOES.'/Soul.json')) {
      $echoes_state .= $echoes_check."Soul, ";
    }
  }

  $header = Weaver::item(__DIR__ . '/weaver/rune-header.txt');
  $header = Weaver::bind($header, [
    'FILE'=> AETHER_FILE,
    'REPO'=> AETHER_REPO,
    'VERSION'=> AETHER_VERSION,
    'SIZE'=> aether_formatFileSize(filesize(AETHER_FILE)),
    'ECHOES'=> $echoes_state,
    'BASE-CAST'=> $base_cast,
    'REGISTERED-CAST'=> $registered_cast,
    'TOTAL-RUNE'=> count(aether_arised()),
    'RUNE-ETHER'=> count($AETHER_RUNE_ETHER),
    'RUNE-ESSENCE'=> count($AETHER_RUNE_ESSENCE),
    'RUNE-ENTITY'=> count($AETHER_RUNE_ENTITY),
  ]);

  if (aether_has_entity('chanter')) {
    if (Chanter::spell('resonance')) {
      if (aether_has_entity('keeper')) {
        $memory = aether_memoryusage();
        keeper_item('rune', [
          'FILE'=> AETHER_FILE,
          'REPO'=> AETHER_REPO,
          'VERSION'=> AETHER_VERSION,
          'CAST'=> $CHANTER_ECHO,
          'SIZE'=> filesize(AETHER_FILE),
          'MEMORY'=> [$memory[0], $memory[1]],
          'RUNE'=> aether_arised(),
        ]);
      }
    }
  }
  
  if (aether_has_entity('whisper')) {
    Whisper::clear()::echo($header);
  }else {
    aether_whisper($header);
  }
});