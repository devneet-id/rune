<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;
use Rune\Keeper\Manifest as Keeper;

require_once __DIR__ . '/relic.php';

// reliquary: relics (.rune) of this runite, and the reliquaries of Rune
Chanter::cast(
  arg: 'reliquary',
  echo: 'Make, open and share relics (runite ⇄ .rune).',
  execute: function() {
    Weaver::ether()::essence()::entity();
    Whisper::ether()::essence()::entity();
    Cipher::ether()::essence()::entity();
    Forger::ether()::essence()::entity();
    Keeper::ether()::essence()::entity();

    // relics and the federation record are never shards of this runite
    global $FORGER_KEEPER_SHARD;
    $FORGER_KEEPER_SHARD = false;

    // a spell with a value ('' when given without one)
    $value = function( String $spell ) {
      $value = Chanter::spell($spell);
      return ($value === false || $value === '1') ? '' : trim((string) $value);
    };

    // old spells (artefact) answer with the new one
    $renamed = [
      'invoke'=> '--runite-relic',
      'revoke'=> '--relic-runite',
      'inspect'=> '--runite-inspect',
      'shard-list'=> '--shards',
      'online'=> '--relic-runite=<vendor>/<repo>',
    ];
    foreach ($renamed as $old=>$new) {
      if (Chanter::spell($old) !== false) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}--$old is now $new{{nl}}");
        return;
      }
    }

    // main hall of the reliquary
    $spells = ['runite-inspect', 'shard', 'shards', 'shard-remove', 'shard-clean', 'runite-relic', 'relic-runite', 'relic', 'relics', 'initiate', 'discover', 'retrieve'];
    $asked = array_filter($spells, fn($spell) => Chanter::spell($spell) !== false);
    if (!$asked) {
      $header = Weaver::item(__DIR__ . '/header.txt');
      if (defined('FEDERATION')) {
        $header .= Weaver::item(__DIR__ . '/header-federation.txt');
      }
      Whisper::clear();
      Whisper::echo(Weaver::bind($header, [
        'AETHER-FILE'=> AETHER_FILE,
        'RELIQUARY'=> reliquary_main(),
      ]));
      return;
    }


    /* RUNITE */

    // inspect a relic: a local .rune (default: the .rune of this runite)
    $processing__inspect = function( Object $relic ) {
      $casts = '';
      $runes = '';
      $shards = '';
      foreach (($relic->ECHOES['RUNITE']['CAST'] ?? []) as $cast) {
        $casts .= "{{text-secondary}} ♦ {{text-default}}" . ($cast[1] ?? '') . " \n";
        if (!empty($cast[2])) {
          $casts .= "{{tab}}{{text-secondary}} ≈ $cast[2] \n";
        }
      }
      foreach (($relic->ECHOES['RUNITE']['RUNE'] ?? []) as $rune) {
        $runes .= "{{text-secondary}} ♦ {{text-default}}$rune \n";
      }
      foreach (($relic->ECHOES['SHARD'] ?? []) as $ID=>$shard) {
        $shard = (object) $shard;
        $ext = $shard->ext ?? '';
        $size = aether_formatFileSize(strlen($relic->SHARDS[$ID]['source'] ?? ''));
        $shards .= "{{text-secondary}} ♦ {{text-info}}$ext {{text-danger}}$size {{text-default}}$shard->target \n";
      }
      Whisper::clear(true);
      Whisper::echo(Weaver::bind(Weaver::item(__DIR__ . '/inspect.txt'), [
        'lore'=> $relic->lore,
        'relic-php'=> $relic->RELIC['php'] ?? '',
        'runic'=> $relic->RELIC['runic'] ?? '',
        'encryption'=> $relic->RELIC['encryption'] ?? '',
        'system'=> $relic->RELIC['system'] ?? '',
        'version'=> $relic->RELIC['version'] ?? '',
        'my_version'=> aether_version(),
        'my_php'=> phpversion(),
        'my_system'=> php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('v') . ' ' . php_uname('m'),
        'cast'=> $casts,
        'rune'=> $runes,
        'shard'=> $shards,
        'created_at'=> date('[H:i] d/m/Y', (int) ($relic->RELIC['time'] ?? 0)),
      ]));
    };
    if (Chanter::spell('runite-inspect') !== false) {
      $file = $value('runite-inspect') ?: basename(AETHER_FILE) . '.rune';
      $path = keeper_shard_place($file);
      $relic = is_file($path) ? reliquary_relic_parse((string) Forger::item($path)) : null;
      if (!$relic) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}No relic at $file{{nl}}");
        return;
      }
      $processing__inspect($relic);
      return;
    }


    /* SHARD (listed by the runite itself with Keeper::shard) */
    $shards = function() {
      return (defined('KEEPER_ECHOES_SHARD') && file_exists(KEEPER_ECHOES_SHARD)) ? (json_decode((string) Forger::item(KEEPER_ECHOES_SHARD)) ?: new \stdClass) : new \stdClass;
    };

    if (Chanter::spell('shards') !== false) {
      Whisper::clear(true);
      Whisper::echo("{{text-secondary}}///////////////////////////{{text-end}} \n");
      Whisper::echo("{{tab}}{{text-default}}S H A R D S \n");
      Whisper::echo("{{text-secondary}}These files travel in the relic of this runite (Keeper::shard). \n\n");
      Whisper::echo("{{text-secondary}}[NO] [ID] [EXTENSION] [TARGET] \n");
      $no = 1;
      foreach ($shards() as $ID=>$shard) {
        $ext = $shard->ext ?? '';
        Whisper::echo("[$no]{{text-danger}} $ID {{text-info}} $ext {{text-default}}$shard->target \n");
        $no++;
      }
      return;
    }

    if (Chanter::spell('shard') !== false) {
      $ID = $value('shard') ?: trim((string) Whisper::call('Shard ID: '));
      $list = $shards();
      if (!isset($list->$ID)) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}No shard with ID $ID (see --shards){{nl}}");
        return;
      }
      $shard = $list->$ID;
      $path = keeper_shard_place($shard->target);
      $size = is_file($path) ? aether_formatFileSize(filesize($path)) : 'missing';
      Whisper::echo("{{nl}}{{bold}}Shard{{end}} $ID{{nl}}");
      Whisper::echo("{{text-secondary}} ♦ Target    {{text-end}}$shard->target{{nl}}");
      Whisper::echo("{{text-secondary}} ♦ Extension {{text-end}}" . ($shard->ext ?? '') . "{{nl}}");
      Whisper::echo("{{text-secondary}} ♦ Size      {{text-end}}$size{{nl}}");
      return;
    }

    if (Chanter::spell('shard-remove') !== false) {
      $ID = $value('shard-remove') ?: trim((string) Whisper::call('Shard ID: '));
      $list = $shards();
      if (!isset($list->$ID)) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}No shard with ID $ID (see --shards){{nl}}");
        return;
      }
      unset($list->$ID);
      Forger::item(KEEPER_ECHOES_SHARD, json_encode($list, JSON_PRETTY_PRINT));
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Shard $ID removed{{nl}}");
      return;
    }

    if (Chanter::spell('shard-clean') !== false) {
      Forger::item(KEEPER_ECHOES_SHARD, '{}');
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Every shard removed{{nl}}");
      return;
    }


    /* RELIC */

    // this runite to a relic, next to it
    if (Chanter::spell('runite-relic') !== false) {
      $file = basename(AETHER_FILE) . '.rune';
      Forger::item(AETHER_REPO . DIRECTORY_SEPARATOR . $file, reliquary_relic_make());
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Relic made: $file{{nl}}");
      return;
    }

    // a relic to this runite: a local .rune, a kept relic (federation), or online
    if (Chanter::spell('relic-runite') !== false) {
      $source = $value('relic-runite');
      $tag = $value('tag');
      $from = '';
      $body = null;

      if ($source === '' || str_ends_with(strtolower($source), '.rune') || is_file(keeper_shard_place($source))) {
        // a local relic (default: the .rune of this runite)
        $file = $source ?: basename(AETHER_FILE) . '.rune';
        $path = keeper_shard_place($file);
        if (!is_file($path)) {
          Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}No relic at $file{{nl}}");
          return;
        }
        $body = (string) Forger::item($path);
        $from = $file;
      }else {
        $name = strtolower($source);
        // federation: a kept relic in @federation/ first
        $kept = reliquary_relic_kept($name);
        if ($kept && ($tag === '' || $tag === $kept[0])) {
          $body = (string) Forger::item($kept[1]);
          $from = "$name@{$kept[0]} (@federation/)";
        }else {
          $found = reliquary_relic_find($name);
          if (!$found) {
            Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}$name is not in " . reliquary_main() . (defined('FEDERATION') ? ' (try --discover)' : '') . "{{nl}}");
            return;
          }
          $tag = $tag ?: reliquary_relic_latest($found[1]['tags']);
          list($body, $problem) = reliquary_relic_download($found[1], $tag);
          if ($problem) {
            Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}$name@$tag: $problem{{nl}}");
            return;
          }
          reliquary_federation_record([$found[0]=> $found[1]]);
          $from = "$name@$tag";
        }
      }

      $relic = reliquary_relic_parse($body);
      if (!$relic) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}$from is not a relic{{nl}}");
        return;
      }

      // the runite (and its shards) will be replaced
      if (Chanter::spell('yes') === false) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} " . basename(AETHER_FILE) . " will be replaced by $from" . (!empty($relic->SHARDS) ? ' (+ ' . count($relic->SHARDS) . ' shard)' : '') . ".{{nl}}");
        if (strtolower(trim((string) Whisper::call('Continue? [y/n]: '))) !== 'y') {
          Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Nothing changed.{{nl}}");
          return;
        }
      }
      if (reliquary_relic_deploy($relic)) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}$from opened into " . basename(AETHER_FILE) . "{{nl}}");
      }
      return;
    }

    // one relic of the reliquary
    if (Chanter::spell('relic') !== false) {
      $name = strtolower($value('relic') ?: trim((string) Whisper::call('Relic (<vendor>/<repo>): ')));
      $found = reliquary_relic_find($name);
      if (!$found) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}$name is not in " . reliquary_main() . (defined('FEDERATION') ? ' (try --discover)' : '') . "{{nl}}");
        return;
      }
      list($name, $relic) = $found;
      reliquary_federation_record([$name=> $relic]);
      $tags = array_map('strval', array_keys($relic['tags']));
      usort($tags, 'version_compare');
      Whisper::echo("{{nl}}{{bold}}$name{{end}}" . (!empty($relic['note']) ? " {{text-secondary}}— {$relic['note']}{{text-end}}" : '') . "{{nl}}");
      if (!empty($relic['repo'])) {
        Whisper::echo("{{text-secondary}} ♦ Repo   {{text-end}}{$relic['repo']}{{nl}}");
      }
      Whisper::echo("{{text-secondary}} ♦ Runite {{text-end}}" . ($relic['runite'] ?? 'cast') . "{{nl}}");
      foreach (array_reverse($tags) as $tag) {
        $item = $relic['tags'][$tag];
        Whisper::echo("{{text-secondary}} ♦ {{text-success}}$tag{{text-end}} Rune " . ($item['rune'] ?? '?') . (!empty($item['ref']) ? " {{text-secondary}}({$item['ref']}){{text-end}}" : '') . "{{nl}}");
      }
      Whisper::echo("{{nl}}{{text-secondary}}Open it: php " . AETHER_FILE . " reliquary --relic-runite=$name{{text-end}}{{nl}}");
      return;
    }

    // every relic of the reliquary
    if (Chanter::spell('relics') !== false) {
      $main = reliquary_main();
      $list = reliquary_relics($main);
      if ($list === null) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}$main can not be read (no relic.json){{nl}}");
        return;
      }
      reliquary_federation_record($list);
      Whisper::echo("{{nl}}{{bold}}Reliquary{{end}} $main{{nl}}{{nl}}");
      foreach ($list as $name=>$relic) {
        $tags = array_map('strval', array_keys($relic['tags']));
        usort($tags, 'version_compare');
        Whisper::echo(" {{text-default}}$name {{text-success}}" . implode(', ', $tags) . "{{text-end}}" . (!empty($relic['note']) ? " {{text-secondary}}— {$relic['note']}{{text-end}}" : '') . "{{nl}}");
      }
      Whisper::echo("{{nl}}" . count($list) . " relic(s){{nl}}");
      return;
    }


    /* FEDERATION (federation mode only) */
    if (!defined('FEDERATION')) {
      Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This spell opens with federation: php " . AETHER_FILE . " sentinel --federation{{nl}}");
      return;
    }

    // use your own reliquary
    if (Chanter::spell('initiate') !== false) {
      $repo = trim($value('repo') ?: $value('initiate') ?: trim((string) Whisper::call('Your reliquary (<user>/reliquary): ')), '/');
      if (!preg_match('#^[A-Za-z0-9][A-Za-z0-9-]*/[A-Za-z0-9._-]+$#', $repo)) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}Give your reliquary: --repo=<user>/reliquary{{nl}}");
        return;
      }
      if (reliquary_relics($repo) === null) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} $repo has no relic.json (yet), it is set anyway.{{nl}}");
      }
      $data = reliquary_federation();
      $data['reliquary']['main'] = $repo;
      reliquary_federation_save($data);
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Reliquary in use: $repo{{nl}}");
      return;
    }

    // every relic on the chain of reliquaries
    if (Chanter::spell('discover') !== false) {
      $main = reliquary_main();
      Whisper::echo("{{nl}}{{bold}}Discover{{end}} $main{{nl}}{{nl}}");
      $list = [];
      // from your reliquary, and from devneet-id/reliquary as the root (nexus.json only maps the forks below)
      foreach (reliquary_chain(array_unique([$main, RELIQUARY_MAIN])) as $node) {
        $relics = reliquary_relics($node['repo'], $node['branch']);
        if ($relics === null) {
          Whisper::echo("{{text-secondary}} ◇ {$node['repo']} · no relic.json{{text-end}}{{nl}}");
          continue;
        }
        Whisper::echo(" {{text-info}}◆{{text-end}} {$node['repo']} · " . count($relics) . " relic(s){{nl}}");
        $list = array_merge($list, $relics);
      }
      $data = reliquary_federation();
      $data['reliquary']['main'] = $data['reliquary']['main'] ?? $main;
      $data['reliquary']['list'] = $list;
      reliquary_federation_save($data);
      Whisper::echo("{{nl}}{{COLOR-SUCCESS}}federation.json{{COLOR-END}}: " . count($list) . " relic(s){{nl}}");
      return;
    }

    // keep a relic in @federation/
    if (Chanter::spell('retrieve') !== false) {
      $name = strtolower($value('retrieve') ?: trim((string) Whisper::call('Relic (<vendor>/<repo>): ')));
      $found = reliquary_relic_find($name);
      if (!$found) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}$name is not in " . reliquary_main() . " (try --discover){{nl}}");
        return;
      }
      list($name, $relic) = $found;
      $tag = $value('tag') ?: reliquary_relic_latest($relic['tags']);
      list($body, $problem) = reliquary_relic_download($relic, $tag);
      if ($problem) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}$name@$tag: $problem{{nl}}");
        return;
      }
      $path = reliquary_relic_path($name, $tag);
      Forger::fix([['type'=> 'repo', 'target'=> dirname($path)]]);
      Forger::item($path, $body);
      reliquary_federation_record([$name=> $relic]);
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}}{{LABEL-SUCCESS}}Kept @federation/$name@$tag.rune{{nl}}");
      Whisper::echo("{{text-secondary}}Open it: php " . AETHER_FILE . " reliquary --relic-runite=$name{{text-end}}{{nl}}");
      return;
    }
  }
);

// artefact is now reliquary (hidden from the main hall)
Chanter::cast(
  arg: 'artefact',
  execute: function() {
    Whisper::ether()::essence()::entity();
    $renamed = [
      'invoke'=> 'reliquary --runite-relic',
      'revoke'=> 'reliquary --relic-runite',
      'inspect'=> 'reliquary --runite-inspect',
      'shard-list'=> 'reliquary --shards',
      'shard-remove'=> 'reliquary --shard-remove',
      'shard-clean'=> 'reliquary --shard-clean',
      'online'=> 'reliquary --relic-runite=<vendor>/<repo>',
    ];
    foreach ($renamed as $old=>$new) {
      if (Chanter::spell($old) !== false) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}artefact --$old is now $new{{nl}}");
        return;
      }
    }
    Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}artefact is now reliquary: php " . AETHER_FILE . " reliquary{{nl}}");
  }
);
