<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */
#NOTE: Placeholder function for the Forger system, currently returns true.
function forger() {
  return true;
}


/* TRACE
 * */
#NOTE: Traces and resolves each part of a given path, tagging them as file ('item') or folder ('repo') along with their existence.
function forger_trace( String $source_path, ?Callable $callback = null ) {
  $source_path = str_replace('/', DIRECTORY_SEPARATOR, $source_path);
  $pathParts = explode(DIRECTORY_SEPARATOR, $source_path);
  
  $targets = [];
  $current = '';

  foreach ($pathParts as $part) {
    $current .= ($current === '' ? '' : DIRECTORY_SEPARATOR) . $part;
    if (!empty($current)) {
      $stack['target'] = $current;
      $stack['ready'] = (file_exists($current));

      $ext = pathinfo($current, PATHINFO_EXTENSION);
      $basename = pathinfo($current, PATHINFO_BASENAME);
      $isHidden = substr($basename, 0, 1) === '.';
      $stack['type'] = (!$ext || $isHidden) ? 'repo' : 'item';

      if ($callback) {
        $stack = $callback($stack);
      }

      $targets[] = $stack;
    }
  }
  
  aether_arcane('Forger.entity.forger_trace');
  return $targets;
}

#NOTE: Recursively traces a given path and all its children, returning structured info for both files and directories.
function forger_trace_recursive(string $path): array {
  $result = [];

  if (!file_exists($path)) return $result;

  // Folder dulu
  if (is_dir($path)) {
    $result[] = [
      'target' => $path,
      'ready'  => true,
      'type'   => 'repo',
    ];

    $items = scandir($path);
    foreach ($items as $item) {
      if ($item === '.' || $item === '..') continue;

      $full_path = $path . DIRECTORY_SEPARATOR . $item;
      $result = array_merge($result, forger_trace_recursive($full_path));
    }
  } else {
    // File
    $result[] = [
      'target' => $path,
      'ready'  => true,
      'type'   => 'item',
    ];
  }

  return $result;
}




/* SCAN
 * */
#NOTE: Scans a directory for items, applies a callback to each, and returns the result list.
function forger_scan( String $source_path, ?Callable $callback ) {
  $return = [];
  foreach (glob( $source_path . '/*' ) as $item) {
    $reitem = pathinfo($item);
    $reitem['target'] = $source_path . DIRECTORY_SEPARATOR . $reitem['basename'];
    if ($callback) {
      $reitem = $callback( (object) $reitem );
    }
    $return[] = $reitem;
  }

  aether_arcane('Forger.entity.forger_scan');
  return $return;
}



/* FIX
 * */
#NOTE: Ensures all given paths exist by creating missing directories or files based on their type.
function forger_fix( Array $source_path ) {
  foreach ($source_path as $source) {
    $state = (isset($source['ready'])) ? $source['ready'] : file_exists($source['target']);
    if ($state === false) {
      if ($source['type'] === 'repo') {
        if (!file_exists($source['target'])) {
          @mkdir($source['target'], 0755, true);
        }
      }
      if ($source['type'] === 'item') {
        if (!file_exists($source['target'])) {
          @touch($source['target']);
          chmod($source['target'], 0644);
        }
      }
    }
  }

  aether_arcane('Forger.entity.forger_fix');
}


/* MOVE
 * */
#NOTE: Moves or clones items and repositories to their target paths, ensuring destination structure exists beforehand.
function forger_move(Array $source_path) {
  foreach ($source_path as $source) {
    $type   = $source['type'] ?? null;
    $from   = $source['from'] ?? null;
    $target = $source['target'] ?? null;
    
    $traces = forger_trace($target);
    foreach ($traces as $trace) {
      if ($trace['type'] === 'repo') {
        forger_repo($trace['target']);
      }
    }

    if ($type=='item') {
      if ($from && $target) {
        // Pastikan folder tujuan ada dulu
        $targetDir = dirname($target);
        if (!file_exists($targetDir)) {
          mkdir($targetDir, 0755, true);
        }
  
        // Pindahkan item ke target
        if (file_exists($from)) {
          copy($from, $target);
        }
      }
    }
    if ($type=='repo') {
      forger_clone($from, $target);
    }
  }

  aether_arcane('Forger.entity.forger_move');
}


/* SORT
 * */
#NOTE: Sorts traced paths by type priority and path length, placing prioritized types first and longer paths earlier.
function forger_sort(array $trace, string $priority = 'item'): array {
  usort($trace, function($a, $b) use ($priority) {
    if ($a['type'] === $b['type']) {
      // Kalau sama-sama 'item' atau 'repo', urut berdasarkan panjang path (panjang duluan)
      return strlen($b['target']) - strlen($a['target']);
    }

    // Urutkan sesuai prioritas type
    return ($a['type'] === $priority) ? -1 : 1;
  });

  return array_values($trace); // Reindex biar rapi
}


/* CLEAN
 * */
#NOTE: Cleans a file or directory; if forced as repo, recursively traces and removes all contained files and folders.
function forger_clean( String $source_path, $force_repo = false ) {
  if ($force_repo) {
    // trace
    $trace = forger_trace_recursive( $source_path );
    $trace = forger_sort($trace);
    
    // remove
    foreach ($trace as $target) {
      if (is_dir($target['target'])) {
        rmdir($target['target']);
      }else {
        unlink($target['target']);
      }
    }
  }else {
    if (is_dir($source_path)) {
      rmdir($source_path);
    }else {
      unlink($source_path);
    }
  }
  
  aether_arcane('Forger.entity.forger_clean');
  return true;
}


/* REPO
 * todo working with folder */
#NOTE: Ensures the repository path exists, fixes missing parts, and optionally scans items with a callback.
function forger_repo(string $source_path, bool $isRecursion = false) {
  global $FORGER_KEEPER_SHARD;
  $return = true;

  if (!is_dir($source_path)) {
    $return = mkdir($source_path, 0755, true);
  } else {
    chmod($source_path, 0755);
  }

  if ($isRecursion) {
    forger_fix(forger_trace($source_path));
  }

  if (aether_has_entity('keeper')) {
    if ($FORGER_KEEPER_SHARD) {
      keeper_shard_set_all([$source_path]);
    }
  }

  return $return + aether_arcane('Forger.entity.forger_repo');
}


/* ITEM
 * todo working with file
 *  */
#NOTE: Ensures the file exists, optionally writes content to it, and returns its contents.
function forger_item(string $source_path, mixed $content = false, int $flags = 0) {
  global $FORGER_KEEPER_SHARD;
  $return = false;

  if ($content === false) {
    // Mode baca
    if (file_exists($source_path)) {
      $return = file_get_contents($source_path);
    }
  } else {
    // Mode tulis, hanya jika folder-nya sudah ada
    $dir = dirname($source_path);
    if (is_dir($dir)) {
      if (file_put_contents($source_path, $content, $flags) !== false) {
        $return = true;
      }
    }
  }

  if (aether_has_entity('keeper')) {
    if ($FORGER_KEEPER_SHARD) {
      keeper_shard_set($source_path);
    }
  }

  aether_arcane('Forger.entity.forger_item');
  return $return;
}



/* INFO
 * */
#NOTE: Retrieves detailed information about a file or directory, including type, basename, name, extension, and path.
function forger_info( String $source_path ) {
  if (file_exists($source_path)) {
    $file = pathinfo($source_path);

    $return['target'] = $source_path;
    $return['type'] = (is_dir($source_path)) ? 'repo' : 'item';
    $return['base'] = $file['basename'];
    $return['name'] = $file['filename'];
    if (isset($file['extension'])) {
      $return['ext'] = (isset($file['extension'])) ? $file['extension'] : '';
    }
    $return['path'] = $file['dirname'];
  }else {
    $return = false;
  }

  aether_arcane('Forger.entity.forger_info');
  return (object) $return;
}


/* CLONE
 * */
#NOTE: Recursively clones a directory and its contents to a target location, creating folders as needed and copying files.
function forger_clone( string $from, string $to ): bool {
  if (!is_dir($from)) {
    echo "[x] Folder sumber tidak ditemukan: $from\n";
    return false;
  }

  $items = scandir($from);
  foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $sourcePath = $from . DIRECTORY_SEPARATOR . $item;
    $destPath   = $to . DIRECTORY_SEPARATOR . $item;

    if (is_dir($sourcePath)) {
      // Buat struktur folder dulu
      if (!is_dir($destPath)) {
        mkdir($destPath, 0777, true);
      }
      // Rekursif ke dalam
      forger_clone($sourcePath, $destPath);
    } else {
      // Buat folder tujuan kalau belum ada
      $destDir = dirname($destPath);
      if (!is_dir($destDir)) {
        mkdir($destDir, 0777, true);
      }
      // Copy file-nya
      if (!copy($sourcePath, $destPath)) {
        echo "[!] Gagal copy file: $sourcePath -> $destPath\n";
      }
    }
  }

  aether_arcane('Forger.entity.forger_clone');
  return true;
}


/* Observer */
#NOTE: Observes a directory recursively and returns the latest modification timestamp among all files.
function forger_observer($path) {
  $lastModifiedTime = 0;
  foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $f) {
    if (!$f->isFile()) continue;
    $time = $f->getMTime();
    $lastModifiedTime = max($lastModifiedTime, $time);
  }
  return $lastModifiedTime;
}
