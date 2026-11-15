<?php

/*
 * RUNTIME
 * The runtimes of The Liberation of Forte, kept in @liberation/ (for now: PHP).
 *   @liberation/php@<version>/   php / php.exe, ext/, php.ini
 *   @liberation/liberation.json  { updated, php: { version, os, arch, extensions, source } }
 * Windows runtimes come from windows.php.net, Linux and macOS ones from static-php (dl.static-php.dev).
 */

require_once __DIR__ . '/../reliquary/relic.php';

#NOTE: Where PHP runtimes are taken from (a runite may define RUNTIME_PHP_WINDOWS / RUNTIME_PHP_STATIC before begin(), e.g. for tests).
function runtime_php_source( String $os ) {
  if ($os === 'windows') {
    return rtrim(defined('RUNTIME_PHP_WINDOWS') ? RUNTIME_PHP_WINDOWS : 'https://windows.php.net/downloads/releases', '/');
  }
  return rtrim(defined('RUNTIME_PHP_STATIC') ? RUNTIME_PHP_STATIC : 'https://dl.static-php.dev/static-php-cli/common', '/');
}

#NOTE: The system a runtime is taken for: [os, arch] — windows|linux|macos and x86_64|aarch64 (default: this machine).
function runtime_target( String $os = '', String $arch = '' ) {
  $os = strtolower($os ?: PHP_OS_FAMILY);
  $os = ($os === 'darwin' || $os === 'mac' || $os === 'macos') ? 'macos' : (str_starts_with($os, 'win') ? 'windows' : 'linux');
  $arch = strtolower($arch ?: php_uname('m'));
  $arch = in_array($arch, ['aarch64', 'arm64']) ? 'aarch64' : 'x86_64';
  return [$os, $arch];
}

#NOTE: The folder of a runtime in @liberation/ (php@8.3.36).
function runtime_path( String $name ) {
  return LIBERATION . $name . '/';
}

#NOTE: Reads liberation.json (empty when there is none).
function runtime_record() {
  $file = LIBERATION . 'liberation.json';
  $data = file_exists($file) ? json_decode((string) file_get_contents($file), true) : null;
  return is_array($data) ? $data : [];
}

#NOTE: Writes liberation.json: { updated, php: {…} }.
function runtime_record_save( Array $data ) {
  unset($data['updated']);
  $data = ['updated'=> gmdate('Y-m-d\TH:i:s\Z')] + $data;
  return file_put_contents(LIBERATION . 'liberation.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
}

#NOTE: The runtimes kept in @liberation/ (folder names, e.g. php@8.3.36).
function runtime_list() {
  $list = [];
  foreach (glob(LIBERATION . 'php@*', GLOB_ONLYDIR) ?: [] as $dir) {
    $list[] = basename($dir);
  }
  usort($list, fn($a, $b) => version_compare(substr($a, 4), substr($b, 4)));
  return $list;
}

#NOTE: Finds a PHP build online: [build, null] or [null, problem]; build = { version, url, sha256, format }.
function runtime_php_find( String $version, String $os, String $arch ) {
  $version = trim($version);
  if (!preg_match('/^\d+\.\d+(\.\d+)?$/', $version)) {
    return [null, "'$version' is not a PHP version (8.3 or 8.3.14)"];
  }
  $source = runtime_php_source($os);

  // Windows: releases.json of windows.php.net (newest of each branch, with sha256), older ones from archives/
  if ($os === 'windows') {
    if ($arch !== 'x86_64') {
      return [null, 'windows.php.net has no PHP for Windows on ARM'];
    }
    [$major, $minor] = explode('.', $version);
    $branch = "$major.$minor";
    $vs = version_compare($branch, '8.4', '>=') ? 'vs17' : (version_compare($branch, '8.0', '>=') ? 'vs16' : 'vc15');
    $releases = reliquary_fetch_json("$source/releases.json");
    $release = $releases[$branch] ?? null;
    $latest = $release ? preg_replace('/-\d+$/', '', (string) ($release['version'] ?? '')) : '';
    if ($release && ($version === $branch || $version === $latest)) {
      $zip = $release["nts-$vs-x64"]['zip'] ?? null;
      if (!$zip) {
        return [null, "no NTS x64 build of PHP $branch in releases.json"];
      }
      return [['version'=> $latest, 'url'=> "$source/" . $zip['path'], 'sha256'=> $zip['sha256'] ?? '', 'format'=> 'zip'], null];
    }
    if ($version === $branch) {
      return [null, $releases === null ? "$source/releases.json can not be read" : "PHP $branch is not in releases.json, give the full version (php@$branch.<patch>)"];
    }
    return [['version'=> $version, 'url'=> "$source/archives/php-$version-nts-Win32-$vs-x64.zip", 'sha256'=> '', 'format'=> 'zip'], null];
  }

  // Linux and macOS: a static PHP cli of static-php, by its full version
  if (substr_count($version, '.') === 1) {
    if (str_starts_with(PHP_VERSION, "$version.")) {
      $version = preg_replace('/[^0-9.].*$/', '', PHP_VERSION);
    } else {
      return [null, "give the full version for $os (php@$version.<patch>)"];
    }
  }
  return [['version'=> $version, 'url'=> "$source/php-$version-cli-$os-$arch.tar.gz", 'sha256'=> '', 'format'=> 'tar.gz'], null];
}

#NOTE: Downloads and opens a PHP build into @liberation/php@<version>/: [path, null] or [null, problem].
function runtime_php_install( Array $build, String $os, String $arch ) {
  if (!class_exists('PharData')) {
    return [null, 'PHP can not open archives here: enable the phar extension'];
  }
  $name = 'php@' . $build['version'];
  $path = runtime_path($name);
  if (is_dir($path)) {
    return [null, "$name is already in @liberation/ (remove it first: --runtime-remove=$name)"];
  }

  // download and check the seal
  list($status, $body) = reliquary_fetch($build['url'], 600);
  if ($status !== 200 || $body === '') {
    return [null, "can not be fetched (HTTP $status): {$build['url']}"];
  }
  if (!empty($build['sha256']) && !hash_equals(strtolower($build['sha256']), hash('sha256', $body))) {
    return [null, 'the seal is broken (sha256 does not match)'];
  }

  // open the archive (zip or tar.gz) into the runtime folder
  $archive = LIBERATION . '.' . $name . '.' . $build['format'];
  file_put_contents($archive, $body);
  unset($body);
  try {
    mkdir($path, 0755, true);
    (new PharData($archive))->extractTo($path, null, true);
  } catch (\Throwable $problem) {
    @unlink($archive);
    forger_clean($path, true);
    @rmdir($path);
    return [null, 'the archive can not be opened: ' . $problem->getMessage()];
  }
  @unlink($archive);

  // a static build may sit in one folder inside the archive: bring the binary to the root
  $binary = $path . ($os === 'windows' ? 'php.exe' : 'php');
  if (!file_exists($binary)) {
    foreach (glob($path . '*/' . basename($binary)) ?: [] as $found) {
      rename($found, $binary);
      break;
    }
  }
  if (!file_exists($binary)) {
    forger_clean($path, true);
    @rmdir($path);
    return [null, 'no ' . basename($binary) . ' in the archive'];
  }
  if ($os !== 'windows') {
    @chmod($binary, 0755);
  }

  // Windows: a small php.ini with the extensions Rune reaches for (static builds carry theirs inside)
  $extensions = [];
  if ($os === 'windows') {
    foreach (['curl', 'fileinfo', 'mbstring', 'openssl', 'zip'] as $extension) {
      if (file_exists($path . "ext/php_$extension.dll")) {
        $extensions[] = $extension;
      }
    }
    $ini = "; written by Rune (sentinel --runtime-install)\n"
      . "extension_dir = \"ext\"\n"
      . implode('', array_map(fn($extension) => "extension=$extension\n", $extensions))
      . "date.timezone = UTC\n";
    if (!file_exists($path . 'php.ini')) {
      file_put_contents($path . 'php.ini', str_replace("\n", "\r\n", $ini));
    }
  }

  // the runtime in use
  $record = runtime_record();
  $record['php'] = [
    'version'=> $build['version'],
    'os'=> $os,
    'arch'=> $arch,
    'extensions'=> $extensions,
    'source'=> $build['url'],
  ];
  runtime_record_save($record);

  return [$path, null];
}

#NOTE: Removes a runtime from @liberation/ (and from liberation.json when it was the one in use).
function runtime_remove( String $name ) {
  $path = runtime_path($name);
  if ($name === '' || !preg_match('/^php@\d+\.\d+\.\d+$/', $name) || !is_dir($path)) {
    return false;
  }
  forger_clean($path, true);
  @rmdir($path);
  $record = runtime_record();
  if (('php@' . ($record['php']['version'] ?? '')) === $name) {
    unset($record['php']);
    runtime_record_save($record);
  }
  return !is_dir($path);
}
