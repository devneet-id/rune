<?php

/*
 * RUNTIME
 * The runtimes of The Liberation of Forte, kept in @liberation/.
 *   @liberation/<runtime>@<version>/   e.g. php@8.3.36/ (php / php.exe, ext/, php.ini)
 *   @liberation/liberation.json        { updated, <runtime>: { version, os, arch, source, … } }
 * The runtimes Rune knows (runtime_catalog) are found by version; any other one is taken from a link.
 */

require_once __DIR__ . '/../reliquary/relic.php';

#NOTE: Where PHP runtimes are taken from (a runite may define RUNTIME_PHP_WINDOWS / RUNTIME_PHP_STATIC before begin(), e.g. for tests).
function runtime_php_source( String $os ) {
  if ($os === 'windows') {
    return rtrim(defined('RUNTIME_PHP_WINDOWS') ? RUNTIME_PHP_WINDOWS : 'https://windows.php.net/downloads/releases', '/');
  }
  return rtrim(defined('RUNTIME_PHP_STATIC') ? RUNTIME_PHP_STATIC : 'https://dl.static-php.dev/static-php-cli/common', '/');
}

#NOTE: The runtimes Rune knows how to find by version (for now: PHP). Others come from a link (--runtime-url).
function runtime_catalog() {
  return [
    'php'=> [
      'note'=> 'PHP — Windows from windows.php.net (sha256 checked), Linux and macOS from static-php',
      'example'=> 'php@8.3',
    ],
  ];
}

#NOTE: The system a runtime is taken for: [os, arch] — windows|linux|macos and x86_64|aarch64 (default: this machine).
function runtime_target( String $os = '', String $arch = '' ) {
  $os = strtolower($os ?: PHP_OS_FAMILY);
  $os = ($os === 'darwin' || $os === 'mac' || $os === 'macos') ? 'macos' : (str_starts_with($os, 'win') ? 'windows' : 'linux');
  $arch = strtolower($arch ?: php_uname('m'));
  $arch = in_array($arch, ['aarch64', 'arm64']) ? 'aarch64' : 'x86_64';
  return [$os, $arch];
}

#NOTE: Splits "<runtime>@<version>" (php@8.3.36): [runtime, version] — the runtime is lower case, the version may be empty.
function runtime_name( String $name ) {
  $name = trim($name);
  if (!str_contains($name, '@')) {
    return [strtolower($name), ''];
  }
  [$runtime, $version] = explode('@', $name, 2);
  return [strtolower(trim($runtime)), trim($version)];
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

#NOTE: Writes liberation.json: { updated, <runtime>: {…} }.
function runtime_record_save( Array $data ) {
  unset($data['updated']);
  $data = ['updated'=> gmdate('Y-m-d\TH:i:s\Z')] + $data;
  return file_put_contents(LIBERATION . 'liberation.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
}

#NOTE: The runtimes kept in @liberation/ (folder names, e.g. php@8.3.36, node@20.11.0).
function runtime_list() {
  $list = [];
  foreach (glob(LIBERATION . '*@*', GLOB_ONLYDIR) ?: [] as $dir) {
    $list[] = basename($dir);
  }
  usort($list, function($a, $b) {
    [$name_a, $version_a] = runtime_name($a);
    [$name_b, $version_b] = runtime_name($b);
    return ($name_a === $name_b) ? version_compare($version_a, $version_b) : strcmp($name_a, $name_b);
  });
  return $list;
}

#NOTE: Draws the download bar on one line: a percentage when the size is known, a moving bar when it is not.
function runtime_progress( Int $done, Int $total, Bool $end = false ) {
  static $last = null;
  static $tick = 0;
  $width = 30;
  if ($total > 0) {
    $percent = min(100, (int) floor($done * 100 / $total));
    if ($percent === $last) {
      if ($end) {
        whisper_echo("\n");
        $last = null;
      }
      return;
    }
    $last = $percent;
    $fill = (int) floor($percent * $width / 100);
    $bar = "{{color-warning}}" . str_repeat('/', $fill) . "{{color-secondary}}" . str_repeat('/', $width - $fill);
    $text = str_pad($percent . '%', 4, ' ', STR_PAD_LEFT) . ' ' . aether_formatFileSize($done) . ' / ' . aether_formatFileSize($total);
  } else {
    $tick = ($tick + 1) % $width;
    if ($tick % 3 !== 0 && !$end) {
      return;
    }
    $bar = "{{color-secondary}}" . str_repeat('/', $tick) . "{{color-warning}}///{{color-secondary}}" . str_repeat('/', max(0, $width - $tick - 3));
    $text = aether_formatFileSize($done);
  }
  whisper_echo("\r $bar{{color-end}} $text   ");
  if ($end) {
    whisper_echo("\n");
    $last = null;
  }
}

#NOTE: Downloads a url into a file, drawing the bar while it comes: [true, null] or [false, problem].
function runtime_download( String $url, String $file ) {
  if (stripos($url, 'https://') === 0 && !extension_loaded('openssl') && !extension_loaded('curl')) {
    return [false, 'PHP can not open https: enable openssl (or curl) in php.ini'];
  }
  $handle = fopen($file, 'wb');
  if (!$handle) {
    return [false, "can not write $file"];
  }
  $done = 0;
  $total = 0;

  if (function_exists('curl_init')) {
    $curl = curl_init($url);
    curl_setopt_array($curl, [
      CURLOPT_FILE=> $handle,
      CURLOPT_FOLLOWLOCATION=> true,
      CURLOPT_CONNECTTIMEOUT=> 30,
      CURLOPT_TIMEOUT=> 1800,
      CURLOPT_USERAGENT=> 'rune-sentinel',
      CURLOPT_NOPROGRESS=> false,
      CURLOPT_XFERINFOFUNCTION=> function($curl, $download_total, $download_now) use (&$done, &$total) {
        $done = (int) $download_now;
        $total = (int) $download_total;
        runtime_progress($done, $total);
        return 0;
      },
    ]);
    $ok = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $error = curl_error($curl);
    curl_close($curl);
    fclose($handle);
    runtime_progress($done, $total, true);
    if ($ok === false) {
      return [false, "can not be fetched ($error): $url"];
    }
    return ($status === 200) ? [true, null] : [false, "can not be fetched (HTTP $status): $url"];
  }

  if (ini_get('allow_url_fopen')) {
    $context = stream_context_create(['http'=> ['user_agent'=> 'rune-sentinel', 'follow_location'=> 1, 'ignore_errors'=> true, 'timeout'=> 60]]);
    stream_context_set_params($context, ['notification'=> function($code, $severity, $message, $message_code, $transferred, $max) use (&$done, &$total) {
      if ($code === STREAM_NOTIFY_FILE_SIZE_IS) {
        $total = (int) $max;
      }
      if ($code === STREAM_NOTIFY_PROGRESS) {
        $done = (int) $transferred;
        $total = (int) $max ?: $total;
        runtime_progress($done, $total);
      }
    }]);
    $source = @fopen($url, 'rb', false, $context);
    $status = 0;
    foreach ($http_response_header ?? [] as $line) {
      if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $match)) {
        $status = (int) $match[1];
      }
    }
    if ($source) {
      stream_copy_to_stream($source, $handle);
      fclose($source);
    }
    fclose($handle);
    runtime_progress($done, $total, true);
    return ($source && $status === 200) ? [true, null] : [false, "can not be fetched (HTTP $status): $url"];
  }

  fclose($handle);
  return [false, 'PHP can not go online: enable curl (or allow_url_fopen) in php.ini'];
}

#NOTE: The archive format of a link by its name: zip, tar.gz, tar.bz2, tar — or '' (a single file).
function runtime_format( String $url ) {
  $name = strtolower(parse_url($url, PHP_URL_PATH) ?: $url);
  foreach (['.tar.gz'=> 'tar.gz', '.tgz'=> 'tar.gz', '.tar.bz2'=> 'tar.bz2', '.tar'=> 'tar', '.zip'=> 'zip'] as $end=>$format) {
    if (str_ends_with($name, $end)) {
      return $format;
    }
  }
  return '';
}

#NOTE: Opens an archive into a folder (zip, tar.gz, tar.bz2, tar); a single folder at the top is brought up to the root.
function runtime_open( String $archive, String $path ) {
  if (!class_exists('PharData')) {
    return 'PHP can not open archives here: enable the phar extension';
  }
  try {
    (new PharData($archive))->extractTo($path, null, true);
  } catch (\Throwable $problem) {
    return 'the archive can not be opened: ' . $problem->getMessage();
  }
  // node-v20/…, php-8.3/…: one folder at the top → its content to the root
  $top = array_values(array_diff(scandir($path) ?: [], ['.', '..']));
  if (count($top) === 1 && is_dir($path . $top[0])) {
    $inner = $path . $top[0] . '/';
    foreach (array_diff(scandir($inner) ?: [], ['.', '..']) as $item) {
      rename($inner . $item, $path . $item);
    }
    @rmdir($inner);
  }
  return null;
}

#NOTE: Finds a PHP build online: [build, null] or [null, problem]; build = { version, url, sha256 }.
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
      return [['version'=> $latest, 'url'=> "$source/" . $zip['path'], 'sha256'=> $zip['sha256'] ?? ''], null];
    }
    if ($version === $branch) {
      return [null, $releases === null ? "$source/releases.json can not be read" : "PHP $branch is not in releases.json, give the full version (php@$branch.<patch>)"];
    }
    return [['version'=> $version, 'url'=> "$source/archives/php-$version-nts-Win32-$vs-x64.zip", 'sha256'=> ''], null];
  }

  // Linux and macOS: a static PHP cli of static-php, by its full version
  if (substr_count($version, '.') === 1) {
    if (str_starts_with(PHP_VERSION, "$version.")) {
      $version = preg_replace('/[^0-9.].*$/', '', PHP_VERSION);
    } else {
      return [null, "give the full version for $os (php@$version.<patch>)"];
    }
  }
  return [['version'=> $version, 'url'=> "$source/php-$version-cli-$os-$arch.tar.gz", 'sha256'=> ''], null];
}

#NOTE: Downloads a runtime and opens it into @liberation/<runtime>@<version>/, then records it: [path, null] or [null, problem].
function runtime_install( String $runtime, String $version, Array $build, String $os, String $arch ) {
  $name = "$runtime@$version";
  $path = runtime_path($name);
  if (is_dir($path)) {
    return [null, "$name is already in @liberation/ (remove it first: --runtime-remove=$name)"];
  }

  // download (with the bar) and check the seal
  $format = runtime_format($build['url']);
  $download = LIBERATION . '.' . $name . ($format ? '.' . $format : '.download');
  [$ok, $problem] = runtime_download($build['url'], $download);
  if (!$ok) {
    @unlink($download);
    return [null, $problem];
  }
  if (!empty($build['sha256']) && !hash_equals(strtolower($build['sha256']), hash_file('sha256', $download))) {
    @unlink($download);
    return [null, 'the seal is broken (sha256 does not match)'];
  }

  // open it: an archive into the folder, a single file as it is
  mkdir($path, 0755, true);
  if ($format) {
    $problem = runtime_open($download, $path);
    @unlink($download);
    if ($problem) {
      forger_clean($path, true);
      @rmdir($path);
      return [null, $problem];
    }
  } else {
    $file = basename(parse_url($build['url'], PHP_URL_PATH) ?: $runtime);
    rename($download, $path . $file);
    if ($os !== 'windows') {
      @chmod($path . $file, 0755);
    }
  }

  $record = ['version'=> $version, 'os'=> $os, 'arch'=> $arch, 'source'=> $build['url']];
  if ($runtime === 'php') {
    $problem = runtime_php_prepare($path, $os, $record);
    if ($problem) {
      forger_clean($path, true);
      @rmdir($path);
      return [null, $problem];
    }
  }

  // the runtime in use
  $data = runtime_record();
  $data[$runtime] = $record;
  runtime_record_save($data);
  return [$path, null];
}

#NOTE: Makes a PHP runtime ready: the binary at the root (executable), and on Windows a small php.ini; fills $record['extensions'].
function runtime_php_prepare( String $path, String $os, Array &$record ) {
  $binary = $path . ($os === 'windows' ? 'php.exe' : 'php');
  if (!file_exists($binary)) {
    foreach (glob($path . '*/' . basename($binary)) ?: [] as $found) {
      rename($found, $binary);
      break;
    }
  }
  if (!file_exists($binary)) {
    return 'no ' . basename($binary) . ' in the archive';
  }
  if ($os !== 'windows') {
    @chmod($binary, 0755);
  }

  // Windows: the extensions Rune reaches for (static builds carry theirs inside)
  $extensions = [];
  if ($os === 'windows') {
    foreach (['curl', 'fileinfo', 'mbstring', 'openssl', 'phar', 'zip'] as $extension) {
      if (file_exists($path . "ext/php_$extension.dll")) {
        $extensions[] = $extension;
      }
    }
    if (!file_exists($path . 'php.ini')) {
      $ini = "; written by Rune (sentinel --runtime-install)\n"
        . "extension_dir = \"ext\"\n"
        . implode('', array_map(fn($extension) => "extension=$extension\n", $extensions))
        . "date.timezone = UTC\n";
      file_put_contents($path . 'php.ini', str_replace("\n", "\r\n", $ini));
    }
  }
  $record['extensions'] = $extensions;
  return null;
}

#NOTE: Keeps the PHP that runs now in @liberation/ (no download): Windows copies its whole folder, Linux and macOS the binary.
function runtime_php_local() {
  $version = preg_replace('/[^0-9.].*$/', '', PHP_VERSION);
  $name = "php@$version";
  $path = runtime_path($name);
  if (is_dir($path)) {
    return [null, "$name is already in @liberation/ (remove it first: --runtime-remove=$name)"];
  }
  [$os, $arch] = runtime_target();
  $binary = realpath(PHP_BINARY);
  if (!$binary || !is_file($binary)) {
    return [null, 'the PHP binary that runs now can not be found'];
  }
  mkdir($path, 0755, true);
  if ($os === 'windows') {
    // php.exe, its DLLs, ext/ and php.ini sit in one folder
    $from = dirname($binary) . DIRECTORY_SEPARATOR;
    $items = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($from, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($items as $item) {
      $target = $path . substr($item->getPathname(), strlen($from));
      $item->isDir() ? @mkdir($target, 0755, true) : copy($item->getPathname(), $target);
    }
  } else {
    copy($binary, $path . 'php');
    @chmod($path . 'php', 0755);
  }

  $record = ['version'=> $version, 'os'=> $os, 'arch'=> $arch, 'source'=> 'local', 'extensions'=> get_loaded_extensions()];
  $data = runtime_record();
  $data['php'] = $record;
  runtime_record_save($data);
  return [$path, null];
}

#NOTE: Writes the launcher next to the runite for the PHP in use: <runite>.bat on Windows, <runite>.sh elsewhere: [file, null] or [null, problem].
function runtime_launcher( String $runite ) {
  $php = runtime_record()['php'] ?? null;
  if (!$php || empty($php['version']) || !is_dir(runtime_path('php@' . $php['version']))) {
    return [null, 'no PHP runtime in use: php ' . basename($runite) . ' sentinel --runtime-install'];
  }
  $folder = '@liberation/php@' . $php['version'];
  $name = basename($runite);
  $base = AETHER_REPO . '/' . preg_replace('/\.php$/i', '', $name);
  if (($php['os'] ?? '') === 'windows') {
    $file = $base . '.bat';
    $ini = file_exists(runtime_path('php@' . $php['version']) . 'php.ini') ? " -c \"%~dp0$folder\\php.ini\"" : '';
    $launcher = "@echo off\r\nrem written by Rune (sentinel --runtime-launcher)\r\n\"%~dp0" . str_replace('/', '\\', $folder) . "\\php.exe\"" . str_replace('/', '\\', $ini) . " \"%~dp0$name\" %*\r\n";
  } else {
    $file = $base . '.sh';
    $launcher = "#!/bin/sh\n# written by Rune (sentinel --runtime-launcher)\nDIR=\"\$(cd \"\$(dirname \"\$0\")\" && pwd)\"\nexec \"\$DIR/$folder/php\" \"\$DIR/$name\" \"\$@\"\n";
  }
  if ($file === AETHER_REPO . '/' . $name) {
    return [null, 'the launcher would replace the runite itself'];
  }
  file_put_contents($file, $launcher);
  if (str_ends_with($file, '.sh')) {
    @chmod($file, 0755);
  }
  return [$file, null];
}

#NOTE: Makes a kept runtime the one in use (liberation.json): [record, null] or [null, problem].
function runtime_use( String $name ) {
  [$runtime, $version] = runtime_name($name);
  $path = runtime_path("$runtime@$version");
  if ($runtime === '' || $version === '' || !is_dir($path)) {
    return [null, "$runtime@$version is not in @liberation/"];
  }
  $data = runtime_record();
  [$os, $arch] = runtime_target();
  if ($runtime === 'php') {
    $os = file_exists($path . 'php.exe') ? 'windows' : (($data['php']['os'] ?? '') === 'macos' ? 'macos' : ($os === 'windows' ? 'linux' : $os));
  }
  $record = ['version'=> $version, 'os'=> $os, 'arch'=> $data[$runtime]['arch'] ?? $arch, 'source'=> 'kept'];
  if ($runtime === 'php') {
    $record['extensions'] = [];
    foreach (['curl', 'fileinfo', 'mbstring', 'openssl', 'phar', 'zip'] as $extension) {
      if (file_exists($path . "ext/php_$extension.dll")) {
        $record['extensions'][] = $extension;
      }
    }
  }
  $data[$runtime] = $record;
  runtime_record_save($data);
  return [$record, null];
}

#NOTE: Removes a runtime from @liberation/; when it was the one in use, the newest kept version of that runtime takes its place.
function runtime_remove( String $name ) {
  [$runtime, $version] = runtime_name($name);
  $path = runtime_path("$runtime@$version");
  if ($runtime === '' || $version === '' || !preg_match('/^[a-z0-9._-]+$/', $runtime) || !preg_match('/^[A-Za-z0-9._-]+$/', $version) || !is_dir($path)) {
    return false;
  }
  forger_clean($path, true);
  @rmdir($path);
  $data = runtime_record();
  if (($data[$runtime]['version'] ?? '') === $version) {
    unset($data[$runtime]);
    runtime_record_save($data);
    $left = array_values(array_filter(runtime_list(), fn($kept) => runtime_name($kept)[0] === $runtime));
    if ($left) {
      runtime_use(end($left));
    }
  }
  return !is_dir($path);
}
