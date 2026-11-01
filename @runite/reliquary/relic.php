<?php

/*
 * RELIC
 * Shared by the runites reliquary and awakening.
 * A relic (.rune, once called artefact) is a runite preserved to travel:
 *   [ᚱᚾ] R E L I C     how it was made (time, version, runic, …) — older relics say A R T E F A C T
 *   [ᚱᚾ] R U N I T E   the runite
 *   [ᚱᚾ] E C H O E S   its rune.json and shard.json
 *   [ᚱᚾ] S H A R D S   the files it carries
 * The first line is only a title; the block names are what is read.
 */

use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Forger\Manifest as Forger;
use Rune\Cipher\Manifest as Cipher;

#NOTE: The reliquary read without federation mode.
const RELIQUARY_MAIN = 'devneet-id/reliquary';

#NOTE: Where raw files of a reliquary are read (a runite may define RELIQUARY_RAW before begin(), e.g. for tests).
function reliquary_raw() {
  return rtrim(defined('RELIQUARY_RAW') ? RELIQUARY_RAW : 'https://raw.githubusercontent.com', '/');
}


/* RELIC FILE */

#NOTE: Parses a relic from its source string: ARTEFACT or RELIC block, runite, echoes and shards. Null when it is not a relic.
function reliquary_relic_parse( String $source ) {
  $source = str_replace(["\r\n", "\r", "\n"], '', trim($source));
  if (strpos($source, '[ᚱᚾ] ') === false) {
    return null;
  }

  $template = explode('[ᚱᚾ] ', $source);
  $format = ['lore'=> $template[0]];
  foreach ($template as $key=>$part) {
    if ($key === 0) {
      continue;
    }
    $part = explode('  ::', $part);
    $head = str_replace(' ', '', $part[0]);
    unset($part[0]);
    $format[$head] = (count($part) > 1) ? array_values($part) : ($part[1] ?? '');
  }

  // the making of the relic: RELIC, or ARTEFACT in older relics
  $meta = $format['RELIC'] ?? ($format['ARTEFACT'] ?? null);
  if ($meta === null || !isset($format['RUNITE'])) {
    return null;
  }
  $relic = [];
  foreach ((array) $meta as $part) {
    $part = explode('=', $part, 2);
    $relic[$part[0]] = $part[1] ?? '';
  }
  unset($format['ARTEFACT']);
  $format['RELIC'] = $relic;
  $runic = $relic['runic'] ?? 'default';

  // runite
  $format['RUNITE'] = Cipher::base64(Cipher::runic((string) $format['RUNITE'], true, $runic), true);

  // echoes
  $echoes = [];
  if (isset($format['ECHOES'])) {
    foreach ((array) $format['ECHOES'] as $echo) {
      $echo = explode('/////', $echo, 2);
      $echoes[$echo[0]] = json_decode(Cipher::base64(Cipher::runic($echo[1] ?? '', true, $runic), true), true);
    }
    $format['ECHOES'] = $echoes;
  }

  // shards
  if (isset($format['SHARDS'])) {
    $shards = [];
    foreach ((array) $format['SHARDS'] as $shard) {
      $shard = explode('/////', $shard, 2);
      $info = $echoes['SHARD'][$shard[0]] ?? null;
      if ($info === null) {
        continue; // a shard without its echo has no target
      }
      $shards[$shard[0]] = [
        'info'=> $info,
        'source'=> Cipher::base64(Cipher::runic($shard[1] ?? '', true, $runic), true),
      ];
    }
    $format['SHARDS'] = $shards;
  }

  return (object) $format;
}

#NOTE: Makes a relic from this runite (+ its echoes and shards) and returns its source string.
function reliquary_relic_make() {
  $runic_list = CIPHER_RUNIC_LIST;
  shuffle($runic_list);
  $runic = $runic_list[0];

  $format = Weaver::bind(Weaver::item(__DIR__ . '/format.txt'), [
    'time'=> time(),
    // OS, release, version and machine only -- no host name (privacy when sharing relics)
    'system'=> php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('v') . ' ' . php_uname('m'),
    'php-version'=> phpversion(),
    'version'=> aether_version(),
    'runic'=> $runic,
    'encryption'=> 'base64',
  ]);

  $runite = Cipher::runic(Cipher::base64((string) Forger::item(AETHER_FILE)), false, $runic);
  $format .= "\n\n" . Weaver::bind(Weaver::item(__DIR__ . '/format-runite.txt'), ['runite'=> $runite]);

  if (defined('KEEPER_ECHOES') && file_exists(KEEPER_ECHOES)) {
    $rune_echoes_file = KEEPER_ECHOES . '/rune.json';
    if (file_exists($rune_echoes_file) || file_exists(KEEPER_ECHOES_SHARD)) {
      // runite echoes: .echoes/<runite>/rune.json (built now when `rune --resonance` was never run)
      if (file_exists($rune_echoes_file)) {
        $rune_echoes = json_encode(json_decode(Forger::item($rune_echoes_file)));
      }else {
        global $CHANTER_ECHO;
        $memory = aether_memoryusage();
        $rune_echoes = json_encode([
          'FILE'=> AETHER_FILE,
          'VERSION'=> aether_version(),
          'CAST'=> $CHANTER_ECHO,
          'SIZE'=> filesize(AETHER_FILE),
          'MEMORY'=> [$memory[0], $memory[1]],
          'RUNE'=> aether_arised(),
        ]);
      }
      $stack = ['RUNITE/////' . Cipher::runic(Cipher::base64($rune_echoes), false, $runic)];
      $shard_echoes = file_exists(KEEPER_ECHOES_SHARD) ? json_encode(json_decode(Forger::item(KEEPER_ECHOES_SHARD))) : '{}';
      $stack[] = 'SHARD/////' . Cipher::runic(Cipher::base64($shard_echoes), false, $runic);
      $format .= "\n\n" . Weaver::bind(Weaver::item(__DIR__ . '/format-echoes.txt'), ['echoes'=> implode("\n  ::", $stack)]);
    }

    // shards (only what the user listed with Keeper::shard)
    if (file_exists(KEEPER_ECHOES_SHARD)) {
      $shard_source = [];
      foreach ((json_decode((string) Forger::item(KEEPER_ECHOES_SHARD)) ?: []) as $ID=>$shard) {
        $source = Forger::item(keeper_shard_place($shard->target));
        $shard_source[] = $ID . '/////' . Cipher::runic(Cipher::base64((string) $source), false, $runic);
      }
      $format .= "\n\n" . Weaver::bind(Weaver::item(__DIR__ . '/format-shards.txt'), ['shards'=> implode("\n  ::", $shard_source)]);
    }
  }

  return $format;
}

#NOTE: Opens a relic into this runite: the runite is replaced and its shards are written next to it.
function reliquary_relic_deploy( Object $relic ) {
  $version = $relic->RELIC['version'] ?? '0';
  if (version_compare($version, aether_version()) > 0) {
    Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}This relic needs Rune $version, you have " . aether_version() . ".{{nl}}");
    return false;
  }

  // runite ({file} = the name of this runite)
  $runite = str_replace('{file}', basename(AETHER_FILE), (string) $relic->RUNITE);
  // a runite without .php starts with the shebang (editors and ./cast read it as PHP)
  if (strtolower(pathinfo(AETHER_FILE, PATHINFO_EXTENSION)) !== 'php' && substr($runite, 0, 2) !== '#!') {
    $runite = '#!/usr/bin/env php' . (strpos($runite, "\r\n") !== false ? "\r\n" : "\n") . $runite;
  }
  Forger::item(AETHER_REPO . DIRECTORY_SEPARATOR . basename(AETHER_FILE), $runite);

  // shards land next to the runite (an absolute target is kept as it is)
  foreach (($relic->SHARDS ?? []) as $shard) {
    $target = (string) $shard['info']['target'];
    if (!preg_match('#^([A-Za-z]:[\\\\/]|/)#', $target)) {
      $target = AETHER_REPO . DIRECTORY_SEPARATOR . $target;
    }
    // only the folders: a shard without extension (e.g. `cast`) is still a file
    Forger::fix([['type'=> 'repo', 'target'=> dirname($target)]]);
    Forger::item($target, $shard['source']);
  }

  return true;
}


/* RELIQUARY (online) */

#NOTE: GET a url: [status, body]; status 0 = can not be reached.
function reliquary_fetch( String $url ) {
  if (stripos($url, 'https://') === 0 && !extension_loaded('openssl') && !extension_loaded('curl')) {
    Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}PHP can not open https: enable openssl (or curl) in php.ini.{{nl}}");
    return [0, ''];
  }
  if (function_exists('curl_init')) {
    $curl = curl_init($url);
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER=> true,
      CURLOPT_FOLLOWLOCATION=> true,
      CURLOPT_TIMEOUT=> 30,
      CURLOPT_USERAGENT=> 'rune-reliquary',
    ]);
    $body = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);
    return [$body === false ? 0 : $status, $body === false ? '' : $body];
  }
  if (ini_get('allow_url_fopen')) {
    $context = stream_context_create(['http'=> [
      'timeout'=> 30,
      'user_agent'=> 'rune-reliquary',
      'follow_location'=> 1,
      'ignore_errors'=> true,
    ]]);
    $body = @file_get_contents($url, false, $context);
    $status = 0;
    foreach ($http_response_header ?? [] as $line) {
      if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $match)) {
        $status = (int) $match[1]; // the last status wins (after redirects)
      }
    }
    return [$body === false ? 0 : $status, $body === false ? '' : $body];
  }
  Whisper::echo("{{COLOR-DANGER}}{{ICON-DANGER}}{{LABEL-DANGER}}PHP can not go online: enable curl (or allow_url_fopen) in php.ini.{{nl}}");
  return [0, ''];
}

#NOTE: Reads a json file online, or null.
function reliquary_fetch_json( String $url ) {
  list($status, $body) = reliquary_fetch($url);
  $data = ($status === 200) ? json_decode($body, true) : null;
  return is_array($data) ? $data : null;
}

#NOTE: The federation record: @federation/federation.json (federation mode only).
function reliquary_federation_file() {
  return defined('FEDERATION') ? FEDERATION . 'federation.json' : null;
}

#NOTE: Reads the federation record (empty when there is none).
function reliquary_federation() {
  $file = reliquary_federation_file();
  $data = ($file && file_exists($file)) ? json_decode((string) Forger::item($file), true) : null;
  $data = is_array($data) ? $data : [];
  $data['reliquary'] = (isset($data['reliquary']) && is_array($data['reliquary'])) ? $data['reliquary'] : [];
  $data['reliquary']['list'] = (isset($data['reliquary']['list']) && is_array($data['reliquary']['list'])) ? $data['reliquary']['list'] : [];
  return $data;
}

#NOTE: Writes the federation record (federation mode only).
function reliquary_federation_save( Array $data ) {
  $file = reliquary_federation_file();
  if (!$file) {
    return false;
  }
  $data['updated'] = gmdate('Y-m-d\TH:i:s\Z');
  $data = ['updated'=> $data['updated']] + $data;
  // { updated, reliquary: { main, list } }
  $data['reliquary'] = ['main'=> (string) ($data['reliquary']['main'] ?? '')] + $data['reliquary'];
  if (isset($data['reliquary']['list']) && is_array($data['reliquary']['list'])) {
    ksort($data['reliquary']['list']);
    $data['reliquary']['list'] = $data['reliquary']['list'] ?: new \stdClass;
  }
  return Forger::item($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
}

#NOTE: Records relics in federation.json (federation mode only; nothing is written otherwise).
function reliquary_federation_record( Array $list, Bool $replace = false ) {
  if (!defined('FEDERATION')) {
    return false;
  }
  $data = reliquary_federation();
  $data['reliquary']['list'] = $replace ? $list : array_merge($data['reliquary']['list'], $list);
  return reliquary_federation_save($data);
}

#NOTE: The reliquary in use: devneet-id/reliquary, or the main of federation.json in federation mode.
function reliquary_main() {
  if (defined('FEDERATION')) {
    $main = reliquary_federation()['reliquary']['main'] ?? '';
    if (is_string($main) && preg_match('#^[A-Za-z0-9][A-Za-z0-9-]*/[A-Za-z0-9._-]+$#', $main)) {
      return $main;
    }
  }
  return RELIQUARY_MAIN;
}

#NOTE: The relics of one reliquary (its relic.json list), only <owner>/* — or null when it can not be read.
function reliquary_relics( String $repo, String $branch = 'main' ) {
  $record = reliquary_fetch_json(reliquary_raw() . "/$repo/$branch/relic.json");
  if (!$record || !isset($record['list']) || !is_array($record['list'])) {
    return null;
  }
  $owner = explode('/', $repo)[0];
  $list = [];
  foreach ($record['list'] as $name=>$relic) {
    // a fork that has not initiated still holds the names of its parent: they are ignored
    if (strcasecmp(explode('/', (string) $name)[0], $owner) === 0 && is_array($relic) && !empty($relic['tags']) && is_array($relic['tags'])) {
      $list[strtolower((string) $name)] = $relic;
    }
  }
  return $list;
}

#NOTE: Every reliquary on the chain (nexus.json → nexus.json → …), from the given roots; each one read once.
function reliquary_chain( Array $roots ) {
  $raw = reliquary_raw();
  $chain = [];
  $queue = [];
  foreach ($roots as $root) {
    $queue[] = [$root, 'main'];
  }
  while ($queue) {
    list($repo, $branch) = array_shift($queue);
    $key = strtolower($repo);
    if (isset($chain[$key])) {
      continue;
    }
    $chain[$key] = ['repo'=> $repo, 'branch'=> $branch];
    $nexus = reliquary_fetch_json("$raw/$repo/$branch/nexus.json");
    foreach ((($nexus['list'] ?? null) ?: []) as $full_name=>$fork) {
      if (is_array($fork) && !isset($chain[strtolower((string) $full_name)])) {
        $queue[] = [(string) $full_name, (string) ($fork['branch'] ?? 'main')];
      }
    }
  }
  return array_values($chain);
}

#NOTE: Finds one relic: federation.json first (federation mode), then the reliquary in use. [name, relic] or null.
function reliquary_relic_find( String $name ) {
  $name = strtolower(trim($name));
  if (defined('FEDERATION')) {
    $list = reliquary_federation()['reliquary']['list'];
    if (isset($list[$name])) {
      return [$name, $list[$name]];
    }
  }
  $list = reliquary_relics(reliquary_main()) ?? [];
  return isset($list[$name]) ? [$name, $list[$name]] : null;
}

#NOTE: The newest tag of a relic.
function reliquary_relic_latest( Array $tags ) {
  $keys = array_map('strval', array_keys($tags));
  usort($keys, 'version_compare');
  return (string) end($keys);
}

#NOTE: Downloads one version of a relic and checks it: [body, null] or [null, problem].
function reliquary_relic_download( Array $relic, String $tag ) {
  $item = $relic['tags'][$tag] ?? null;
  if (!is_array($item) || empty($item['url'])) {
    return [null, "no version $tag (" . implode(', ', array_keys($relic['tags'] ?? [])) . ')'];
  }
  list($status, $body) = reliquary_fetch((string) $item['url']);
  if ($status !== 200) {
    return [null, "can not be fetched (HTTP $status): {$item['url']}"];
  }
  if (strpos($body, '[ᚱᚾ]') === false) {
    return [null, 'not a relic'];
  }
  if (!empty($item['sha256']) && !hash_equals(strtolower((string) $item['sha256']), hash('sha256', $body))) {
    return [null, 'the seal is broken (sha256 does not match the reliquary)'];
  }
  return [$body, null];
}

#NOTE: Where a retrieved relic is kept: @federation/<vendor>/<repo>@<tag>.rune.
function reliquary_relic_path( String $name, String $tag ) {
  return FEDERATION . strtolower($name) . '@' . $tag . '.rune';
}

#NOTE: The newest retrieved version of a relic in @federation/, or null.
function reliquary_relic_kept( String $name ) {
  if (!defined('FEDERATION')) {
    return null;
  }
  $tags = [];
  foreach (glob(FEDERATION . strtolower($name) . '@*.rune') ?: [] as $file) {
    $tags[substr(basename($file, '.rune'), strrpos(basename($file, '.rune'), '@') + 1)] = $file;
  }
  if (!$tags) {
    return null;
  }
  $latest = reliquary_relic_latest($tags);
  return [$latest, $tags[$latest]];
}
