<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;

// the main hall (the default cast: `php cast`; hidden from its own list)
Chanter::cast(
  arg: 'rune',
  echo: 'The main hall of the runite.',
  execute: function() {
    Weaver::ether()::essence()::entity();
    Whisper::ether()::essence()::entity();
    
    global $AETHER_RUNE_ETHER;
    global $AETHER_RUNE_ESSENCE;
    global $AETHER_RUNE_ENTITY;
    global $CHANTER_ARGS;
    global $CHANTER_ECHO;
    global $FORGER_KEEPER_SHARD;
    $FORGER_KEEPER_SHARD = false;
    
    // the built-in runites of @runite/ are the default casts; rune itself and old names stay hidden
    $hidden = ['rune', 'artefact'];
    $builtin = [];
    foreach (glob(\Rune\Ethereal::$runite . '*', GLOB_ONLYDIR) as $runite) {
      $builtin[] = basename($runite);
    }

    $base_cast = [];
    $list_chanter = [];
    foreach ($CHANTER_ECHO as $key => $echo) {
      if (in_array($key, $hidden)) {
        continue;
      }
      $text = '{{color-secondary}} ♦ {{color-info}}{{php}} ' . AETHER_FILE . '{{color-end}} ' . $echo[1];
      if (!empty($echo[2])) {
        $echoWrapped = weaver_wrap_word($echo[2], 40, '{{tab}}{{tab}}  ', true);
        $text .= '{{nl}}{{tab}}{{tab}}{{text-secondary}}≈ ' . $echoWrapped . '{{color-end}}';
      }
      if (in_array($key, $builtin)) {
        $base_cast[] = $text;
      }else {
        $list_chanter[] = $text;
      }
    }

    $base_cast = implode(PHP_EOL, $base_cast);
    $registered_cast = implode(PHP_EOL, $list_chanter);
    
    $echoes_state = '';
    if (aether_has_entity('keeper')) {
      if (file_exists(KEEPER_ECHOES.'/rune.json')) {
        $echoes_state .= "Rune, ";
      }
      if (file_exists(KEEPER_ECHOES_ARCANE)) {
        $echoes_state .= "Arcane, ";
      }
      if (file_exists(KEEPER_ECHOES_GLITCH)) {
        $echoes_state .= "Glitch, ";
      }
      if (file_exists(KEEPER_ECHOES_SHARDS)) {
        $echoes_state .= "Shards, ";
      }
      if (file_exists(KEEPER_ECHOES.'/grimoire.json')) {
        $echoes_state .= "Grimoire, ";
      }
      if (file_exists(KEEPER_ECHOES.'/cast.json')) {
        $echoes_state .= "Cast, ";
      }
      if (file_exists(KEEPER_ECHOES.'/soul.json')) {
        $echoes_state .= "Soul, ";
      }
    }
    
    // the caption of the highest mode wins: federation > liberation > none
    if (defined('FEDERATION')) {
      $mode_text = 'The Federation of Fides';
    }else if (defined('LIBERATION')) {
      $mode_text = 'The Liberation of Forte';
    }else {
      $mode_text = 'The Awakening of Void';
    }
    
    list($major,,) = explode('.', AETHER_VERSION);

    $header = Weaver::item(__DIR__ . '/header.txt');
    $header = Weaver::bind($header, [
      'FILE'=> AETHER_FILE,
      'REPO'=> AETHER_REPO,
      'VERSION'=> $major,
      'SIZE'=> aether_formatFileSize(filesize(AETHER_FILE)),
      'ECHOES'=> $echoes_state,
      'BASE-CAST'=> $base_cast,
      'REGISTERED-CAST'=> $registered_cast,
      'MODE'=> $mode_text,
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
            'REPO'=> basename(AETHER_REPO),
            'VERSION'=> aether_version(),
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
  }
);