<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function forger() {
  return true;
}


/* TRACE
 * */
function forger_trace( String $source_path ) {
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

      $targets[] = $stack;
    }
  }
  
  aether_arcane('Forger.entity.forger_trace');
  return $targets;
}
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
function forger_scan( String $source_path, ?Callable $callback ) {
  $return = [];
  foreach (glob( $source_path . '/*' ) as $item) {
    $reitem = pathinfo($item);
    $reitem['target'] = $source_path . DIRECTORY_SEPARATOR . $reitem['basename'];
    $return[] = $callback( (object) $reitem );
  }

  aether_arcane('Forger.entity.forger_scan');
  return $return;
}


/* FIX
 * */
function forger_fix( Array $source_path ) {
  foreach ($source_path as $source) {
    $state = (isset($source['ready'])) ? $source['ready'] : file_exists($source['target']);
    if ($state === false) {
      if ($source['type'] === 'repo') {
        if (!file_exists($source['target'])) {
          mkdir($source['target'], 0755, true);
        }
      }
      if ($source['type'] === 'item') {
        if (!file_exists($source['target'])) {
          touch($source['target']);
          chmod($source['target'], 0644);
        }
      }
    }
  }

  aether_arcane('Forger.entity.forger_fix');
}

/* MOVE
 * */
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
function forger_repo( String $source_path, ?Callable $callback = null ) {
  $trace = forger_trace( $source_path );
  forger_fix( $trace );
  
  $return = true;
  
  if (!empty($callback)) {
    $return = forger_scan( $source_path, $callback );
  }

  aether_arcane('Forger.entity.forger_repo');
  return $return;
}


/* ITEM
 * todo working with file
 *  */
function forger_item( String $source_path, $content = false, $flags = 0 ) {
  $trace = forger_trace( $source_path );
  forger_fix( $trace );
  
  if ($content!==false) {
    file_put_contents( $source_path, $content, $flags );
  }

  aether_arcane('Forger.entity.forger_item');
  return file_get_contents( $source_path );
}


/* INFO
 * */
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
function forger_observer($path) {
  $lastModifiedTime = 0;
  foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $f) {
    if (!$f->isFile()) continue;
    $time = $f->getMTime();
    $lastModifiedTime = max($lastModifiedTime, $time);
  }
  return $lastModifiedTime;
}
