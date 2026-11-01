<?php

// RUNE:INITIALIZE
use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;

// RUNE:INSTANCE
Aether::ether()::essence()::entity();
Chanter::ether()::essence()::entity();
Weaver::ether()::essence()::entity();
Whisper::ether()::essence()::entity();
Forger::ether()::essence()::entity();
Cipher::ether()::essence()::entity();

// relics are read and opened by the reliquary
require_once __DIR__ . '/../reliquary/relic.php';


// RUNE:AWAKENING
Aether::begin();
Chanter::begin();

// awakening: the first runite, a blank template or a relic of devneet-id/reliquary
Chanter::cast('awakening', function() {
  // the runite is never a shard
  global $FORGER_KEEPER_SHARD;
  $FORGER_KEEPER_SHARD = false;

  Whisper::clear();
  Whisper::echo("{{text-secondary}}///////////////////////////// \n");
  Whisper::echo(" A W A K E N I N G \n");
  Whisper::echo("{{bg-danger}} The Awakening of Void {{bg-end}}\n\n");

  // check minimum requirement
  if (!version_compare(PHP_VERSION, AETHER_PHP_VERSION, '>=')) {
    Whisper::echo('{{COLOR-ERROR}}{{ICON-ERROR}} Need PHP version '.AETHER_PHP_VERSION.' or higher required.');
    exit;
  }

  // the end of the awakening
  $awakened = function() {
    $selfFile = AETHER_FILE;
    Whisper::echo("{{text-success}}{{icon-success}} Awakening successfully. \n");
    Whisper::echo("{{text-info}}{{icon-info}}{{end}}{{text-secondary}} Checkout your runite with '{{text-danger}}php $selfFile{{end}}'. \n");
    Aether::exit(true);
  };

  // the blank template ({file} = the name of this runite)
  $blank = function() use ($awakened) {
    $relic = (object) [
      'RELIC'=> ['version'=> '0'],
      'RUNITE'=> Forger::item(__DIR__ . '/blank.txt'),
      'SHARDS'=> [],
    ];
    if (reliquary_relic_deploy($relic)) {
      $awakened();
    }
  };

  Whisper::echo("{{color-secondary}}Will you begin from the void, \n");
  Whisper::echo("{{color-secondary}}or from a relic of the reliquary? \n\n");
  if (strtolower(trim((string) Whisper::call('Use a relic from ' . RELIQUARY_MAIN . ' [y/n]: '))) !== 'y') {
    $blank();
    return;
  }

  // the relics of devneet-id/reliquary
  $relics = reliquary_relics(RELIQUARY_MAIN);
  if (!$relics) {
    Whisper::echo("\n{{COLOR-WARNING}}{{ICON-WARNING}} " . RELIQUARY_MAIN . ($relics === null ? " can not be reached." : " has no relic yet.") . " \n");
    if (strtolower(trim((string) Whisper::call('Begin from the void (blank template) [y/n]: '))) === 'y') {
      $blank();
    }
    return;
  }

  // choosing
  Whisper::echo("\n{{text-secondary}}///////////////////////////// \n");
  Whisper::echo("{{text-secondary}}Relics ready to explore: \n\n");
  $list = [];
  foreach ($relics as $name=>$relic) {
    $ID = count($list) + 1;
    $list[$ID] = $name;
    $tag = reliquary_relic_latest($relic['tags']);
    $rune = $relic['tags'][$tag]['rune'] ?? '?';
    Whisper::echo("[{{text-primary}}$ID{{end}}] {{text-default}}$name {{text-secondary}}@$tag{{end}} [{{text-warning}}rune $rune{{end}}] \n");
    if (!empty($relic['note'])) {
      Whisper::echo("{{text-secondary}} :: $relic[note]{{end}} \n");
    }
    Whisper::echo("\n");
  }

  $selected = trim((string) Whisper::call('Where would you like to continue your journey? [ID]: '));
  if (!isset($list[$selected])) {
    Whisper::echo('{{COLOR-ERROR}}{{ICON-ERROR}} Relic not found');
    exit;
  }

  // download the newest version and open it into this runite
  $name = $list[$selected];
  $tag = reliquary_relic_latest($relics[$name]['tags']);
  list($body, $problem) = reliquary_relic_download($relics[$name], $tag);
  $relic = $body ? reliquary_relic_parse($body) : null;
  if (!$relic) {
    Whisper::echo("{{COLOR-ERROR}}{{ICON-ERROR}} $name@$tag: " . ($problem ?: 'not a relic') . " \n");
    if (strtolower(trim((string) Whisper::call('Begin from the void (blank template) [y/n]: '))) === 'y') {
      $blank();
    }
    return;
  }
  if (reliquary_relic_deploy($relic)) {
    $awakened();
  }
});

Chanter::cast('awakening')();
