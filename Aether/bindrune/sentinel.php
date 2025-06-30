<?php

use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Keeper\Manifest as Keeper;

use Rune\Crafter\Manifest as Crafter;
use Rune\Forger\Manifest as Forger;
use Rune\Specter\Manifest as Specter;

// sentinel
Chanter::cast('sentinel', function() {

  // prepare cast template
  $template = Weaver::item(__DIR__ . '/weaver/sentinel-header.txt');
  $template .= Weaver::item(__DIR__.'/weaver/sentinel-cast-avaliable.txt');
  if (defined('BEING_MONARCH')) {
    $template .= Weaver::item(__DIR__.'/weaver/sentinel-cast-monarch.txt');
  }
  $template = Weaver::bind($template, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);
  
  // output the template
  if (aether_has_entity('whisper')) {
    whisper_clear();
    Whisper::echo($template);
  }else {
    aether_whisper($template);
  }

  // spell of testing
  if (Chanter::spell('test')) {
    Whisper::clear(true);
    Whisper::echo("\n S E N T I N E L {{color-danger}}::{{color-end}} INVOKE");
    Whisper::echo("\n{{color-success}}{{icon-success}} Successfully Invoked");
  }

  // todo get all rune
  $avalaible_rune = function() {
    $result = [];
    foreach (glob(AETHER_RUNE_LOCATION . '/*') as $rune) {
      if (is_dir($rune)) {
        $result[] = basename($rune);
      }
    }
    // external rune
    if (file_exists(AETHER_REPO . '/@monarch')) {
      foreach (glob(AETHER_REPO . '/@monarch/*') as $rune) {
        if (is_dir($rune)) {
          $result[] = basename($rune);
        }
      }
    }
    return $result;
  };

  /* ALTAR
   * */
  $processing__altar = function() {
    if (!file_exists(AETHER_REPO . '/altar.php')) {
      Forger::move([
        ['type'=>'item', 'from'=> __DIR__ . '/weaver/sentinel-altar.txt', 'target'=> AETHER_REPO . '/altar.php']
      ]);
    }
    Specter::devserver([
      'port'=> 8100,
      'file'=> AETHER_REPO . '/altar.php',
    ]);
  };
  if (Chanter::spell('altar')) {
    $processing__altar();
  }

  /* INSPECT
   * */
  if (Chanter::spell('inspect')) {
    $active_runes = aether_arised();

    Whisper::clear()::echo('{{COLOR-DANGER}} Under Development {{nl}}');
  }

  /* INVOKE
   * todo invoke manifest & arise to rune
   *  */
  $processing_invoke = function($target) {
    (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

    $target = ucfirst(strtolower($target));
    if ($target) {
      if (strpos($target, '.') !== false) {
        $target = explode('.', $target);
        $manifest = $target[0];
        unset($target[0]);
        $arise = $target;
        $state_arise = 'multi';
      }else {
        $manifest = $target;
        $state_arise = 'single';
      }
      $rune = Forger::item(AETHER_REPO . '/'. AETHER_FILE);
      
      $prefix_manifest = '#sentinel-manifest';
      $prefix_arise = '#sentinel-arise';
      $codex_manifest = "use Rune\\{$manifest}\\Manifest as {$manifest};";
      // check codex manifest
      if (strpos($rune, $codex_manifest) !== false) {
        $rune = str_replace($codex_manifest.PHP_EOL, '', $rune);
      }
      $rune = str_replace($prefix_manifest, $codex_manifest.PHP_EOL.$prefix_manifest, $rune);
      
      // arising
      if ($state_arise=='single') {
        $codex_arise = "{$manifest}::arise();";
        // check codex arise
        if (strpos($rune, $codex_arise) !== false) {
          $rune = str_replace($codex_arise.PHP_EOL, '', $rune);
        }
        $rune = str_replace($prefix_arise, $codex_arise.PHP_EOL.$prefix_arise, $rune);
      }else {
        if (strpos($rune, "{$manifest}::arise();") !== false) {
          $rune = str_replace("{$manifest}::arise();".PHP_EOL, '', $rune);
        }
        $codex_arise_list = [
          'ether'=> "{$manifest}::ether();",
          'essence'=> "{$manifest}::essence();",
          'entity'=> "{$manifest}::entity();",
        ];
        $codex_arise_select = [];
        foreach ($arise as $ID) {
          $codex_arise_select[] = $codex_arise_list[$ID];
        }
        $codex_arise = '';
        foreach ($arise as $ID) {
          $codex_arise .= $codex_arise_list[$ID].PHP_EOL;
        }
        $rune = str_replace($prefix_arise, trim($codex_arise.PHP_EOL).PHP_EOL.$prefix_arise, $rune);
      }

      
      Forger::item(AETHER_REPO . '/'. AETHER_FILE, $rune);
      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('invoke')) {
    Whisper::clear();
    if (Chanter::spell('invoke') !== '1') {
      $input = Chanter::spell('invoke');
    }else {
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune()) . "{{nl}}");
      $input = Whisper::call('Give us the rune name: ');
    }
    if ($input) {
      Whisper::echo("{{color-danger}}::{{color-end}}S E N T I N E L {{nl}}");
      if ($processing_invoke($input)) {
        Whisper::echo("{{color-success}}{{icon-success}} Successfully do Invoked with '$manifest' {{nl}}");
      }else {
        Whisper::echo("{{color-danger}}{{icon-warning}} Failed do Invoked with '$manifest' {{nl}}");
      }
    }
  }


  /* REVOKE
   * todo revoke manifest & arise in rune
   *  */
  $processing_revoke = function($target) {
    $target = ucfirst(strtolower($target));
    if ($target) {
      if (strpos($target, '.') !== false) {
        $target = explode('.', $target);
        $manifest = $target[0];
        unset($target[0]);
        $arise = $target;
        $state_arise = 'multi';
      }else {
        $manifest = $target;
        $state_arise = 'single';
      }
      $rune = Forger::item(AETHER_REPO . '/'. AETHER_FILE);
      $prefix_manifest = '#sentinel-manifest';
      $prefix_arise = '#sentinel-arise';
      $codex_manifest = "use Rune\\{$manifest}\\Manifest as {$manifest};";
      $codex_arise = "{$manifest}::arise();";

      // delete arise
      if (strpos($rune, $codex_arise) !== false) {
        $rune = str_replace($codex_arise.PHP_EOL, '', $rune);
      }

      // arising
      if ($state_arise=='multi') {
        $codex_arise_list = [
          'ether'=> "{$manifest}::ether();",
          'essence'=> "{$manifest}::essence();",
          'entity'=> "{$manifest}::entity();",
        ];
        $codex_arise_select = [];
        foreach ($arise as $ID) {
          $codex_arise_select[] = $codex_arise_list[$ID];
        }
        foreach ($codex_arise_select as $codex_arise) {
          if (strpos($rune, $codex_arise) !== false) {
            $rune = str_replace($codex_arise.PHP_EOL, '', $rune);
          }
        }
      }


      // arise check
      $check_arise_list = [
        'ether'=> "{$manifest}::ether();",
        'essence'=> "{$manifest}::essence();",
        'entity'=> "{$manifest}::entity();",
      ];
      $check_arise_list_state = 3;
      foreach ($check_arise_list as $ID => $codex_arise) {
        if (!strpos($rune, $codex_arise) !== false) {
          $check_arise_list_state -= 1;
        }
      }
      if ($check_arise_list_state == 0) {
        // delete manifest
        if (strpos($rune, $codex_manifest) !== false) {
          $rune = str_replace($codex_manifest.PHP_EOL, '', $rune);
        }
      }
      
      Forger::item(AETHER_REPO . '/'. AETHER_FILE, $rune);
      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('revoke')) {
    (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

    Whisper::clear();
    if (Chanter::spell('revoke') !== '1') {
      $input = Chanter::spell('revoke');
    }else {
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune())."{{nl}}");
      $input = Whisper::call('Give us the rune name: ');
    }
    if ($input) {
      Whisper::echo("{{color-danger}}::{{color-end}}S E N T I N E L {{nl}}");
      if ( $processing_revoke($input) ) {
        Whisper::echo("{{color-success}}{{icon-success}} Successfully do Revoke with '$manifest' {{nl}}");
      }else {
        Whisper::echo("{{color-danger}}{{icon-warning}} Failed do Revoke with '$manifest' {{nl}}");
      }
    }
  }


  /* CODEX
   * todo sentinel generate codex
   *  */
  if (Chanter::spell('codex')) {
    Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon.. {{nl}}");
  }


  /* CHRONICLE
   * todo sentinel give you back to selected chronicle
   *  */
  if (Chanter::spell('cronicle')) {
    Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Under Development, feature will be added soon.. {{nl}}");
  }
  




  /* 
   * FOR RUNE BUILDER (PRIVATE)
   * */

  /* CREATE RUNE */
  $processing__create_rune = function($name, $repo) {
    Forger::fix(Forger::trace($repo));

    // importing
    $phantasm = Weaver::item( __DIR__ . '/weaver/sentinel-rune--phantasm.txt');
    $manifest = Weaver::item( __DIR__ . '/weaver/sentinel-rune--manifest.txt');
    $ether = Weaver::item( __DIR__ . '/weaver/sentinel-rune--ether.txt');
    $essence = Weaver::item( __DIR__ . '/weaver/sentinel-rune--essence.txt');
    $entity = Weaver::item( __DIR__ . '/weaver/sentinel-rune--entity.txt');
  
    // weaving
    $vars = [
      'name-capital'=> ucfirst($name),
      'name-lower'=> strtolower($name),
      'name-upper'=> strtoupper($name),
    ];
    $phantasm = Weaver::bind($phantasm, $vars);
    $manifest = Weaver::bind($manifest, $vars);
    $ether = Weaver::bind($ether, $vars);
    $essence = Weaver::bind($essence, $vars);
    $entity = Weaver::bind($entity, $vars);
    
    // fixing
    forger_fix([
      ['type'=>'item', 'target'=> $repo . '/Phantasm.php'],
      ['type'=>'item', 'target'=> $repo . '/Manifest.php'],
      ['type'=>'item', 'target'=> $repo . '/Ether.php'],
      ['type'=>'item', 'target'=> $repo . '/Essence.php'],
      ['type'=>'item', 'target'=> $repo . '/Entity.php'],
    ]);
  
    // insert
    forger_item($repo . '/Phantasm.php', $phantasm);
    forger_item($repo . '/Manifest.php', $manifest);
    forger_item($repo . '/Ether.php', $ether);
    forger_item($repo . '/Essence.php', $essence);
    forger_item($repo . '/Entity.php', $entity);
  };
  if (Chanter::spell('create-rune')) {
    // check bindrune
    if (!file_exists(AETHER_REPO . '/@monarch/')) {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} You are not eligible!! {{nl}}");
      die();
    }

    // header
    Whisper::clear();
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Create Rune\n");

    // run spell and call
    if (Chanter::spell('create-rune') !== '1') {
      $name = Chanter::spell('create-rune');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    
    // set data
    $name = ucfirst(strtolower($name));
    $repo = AETHER_REPO . '/@monarch/' . $name . '/';

    // processing
    $processing__create_rune($name, $repo);

    Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success create rune: $name {{nl}}");
  }


  /* REMOVE RUNE */
  $processing__remove_rune = function($name, $repo) {
    if (file_exists($repo)) {
      Forger::clean($repo, true);
      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('remove-rune')) {
    // check bindrune
    if (!file_exists(AETHER_REPO . '/@monarch/')) {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} You are not eligible!! {{nl}}");
      die();
    }

    // header
    Whisper::clear();
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Remove Rune\n");

    // run spell and call
    if (Chanter::spell('remove-rune') !== '1') {
      $name = Chanter::spell('remove-rune');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    
    // set data
    $name = ucfirst(strtolower($name));
    $repo = AETHER_REPO . '/@monarch/' . $name . '/';

    // processing
    if ($processing__remove_rune($name, $repo) ) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success create rune: $name {{nl}}");
    }else {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune not found: $name {{nl}}");
    }
  }








  /* PHANTASM UP */
  $processing__phantasm_up = function($name) {
    $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
    $phantasm = new $phantasm_class();

    if (isset($phantasm->origin)) {
      $path = $phantasm->origin . '/Phantasm.php';
      $read_phantasm = Forger::item($path);

      preg_match('/public\s+\$version\s*=\s*([\d.]+)/i', $read_phantasm, $matches);
      $current_version = isset($matches[1]) ? $matches[1] : '1.0';

      $parts = explode('.', $current_version);
      $major = isset($parts[0]) ? (int)$parts[0] : 1;
      $minor = isset($parts[1]) ? (int)$parts[1] : 0;
      $minor += 1;
      $new_version = $major . '.' . $minor;

      $updated = preg_replace('/(public\s+\$version\s*=\s*)[\d.]+/i', '${1}' . $new_version, $read_phantasm);

      Forger::item($path, $updated);

      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('phantasm-up')) {
    // header
    Whisper::clear(true);
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Up \n");
    // run spell and call
    if (Chanter::spell('phantasm-up') !== '1') {
      $name = Chanter::spell('phantasm-up');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    // set data
    $name = ucfirst(strtolower($name));
    // processing
    if ( $processing__phantasm_up($name) ) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
    }else {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
    }
  }

  /* PHANTASM FIX NODE */
  $processing__phantasm_fix_node = function($name) {
    $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
    $phantasm = new $phantasm_class();

    if (isset($phantasm->origin)) {
      
      $read_ether = Forger::item($phantasm->origin . '/Ether.php');
      preg_match_all("/define\(\s*['\"]([^'\"]+)['\"]\s*,/", $read_ether, $matches);
      $find_ether = $matches[1];

      $read_essence = Forger::item($phantasm->origin . '/Essence.php');
      preg_match_all("/\\\$GLOBALS\\[['\"]([^'\"]+)['\"]\\]/", $read_essence, $matches);
      $find_essence = $matches[1];

      $name_entity = strtoupper($name);
      $read_entity = Forger::item($phantasm->origin . '/Entity.php');
      preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(([^)]*)\)/i', $read_entity, $matches);
      $find_entity = array_filter(array_map(function ($func, $params) use ($name_entity) {
          if (stripos($func, $name_entity) !== false) {
              return $func . '(' . $params . ')';
          }
          return null;
      }, $matches[1], $matches[2]));

      $read_manifest = Forger::item($phantasm->origin . '/Manifest.php');
      preg_match_all('/\bstatic\s+function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(([^)]*)\)/i', $read_manifest, $matches);
      $find_manifest = array_map(function ($name, $params) {
          return $name . '(' . $params . ')';
      }, $matches[1], $matches[2]);



      $read_phantasm = Forger::item($phantasm->origin . '/Phantasm.php');
      $templates_phantasm = preg_replace('/(public\s\$node\s*=\s*)(\[[\s\S]*?\]);/', '{{node}}', $read_phantasm);
      
      $node = [
        'ether' => $find_ether,
        'essence' => $find_essence,
        'entity' => $find_entity,
        'manifest' => $find_manifest,
      ];
      $maps = [];
      foreach ($node as $key => $row) {
        foreach ($row as $value) {
          $maps[] = [
            'type' => $key,
            'call' => str_replace("'", '"', $value),
            'note' => '',
          ];
        }
      }
      
      $templates = 'public $node = [' . PHP_EOL;
      foreach ($maps as $row) {
        $call = str_replace("'", '"', $row['call']);
        $templates .= "    [" . PHP_EOL;
        $templates .= "      'type' => '$row[type]'," . PHP_EOL;
        $templates .= "      'call' => '$call'," . PHP_EOL;
        $templates .= "      'note' => '$row[note]'," . PHP_EOL;
        $templates .= "    ]," . PHP_EOL;
      }
      $templates .= '  ];' . PHP_EOL;
      
      $template = weaver_bind($templates_phantasm, 'node', $templates);
      
      Forger::item($phantasm->origin . '/Phantasm.php', str_ireplace(PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL, $template));

      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('phantasm-fix-node')) {
    // header
    Whisper::clear(true);
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Fix Node \n");
    // run spell and call
    if (Chanter::spell('phantasm-fix-node') !== '1') {
      $name = Chanter::spell('phantasm-fix-node');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    // set data
    $name = ucfirst(strtolower($name));
    // processing
    if ( $processing__phantasm_fix_node($name) ) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
    }else {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
    }
  }

  /* PHANTASM FIX LINK */
  $processing__phantasm_fix_link = function ($name) use ($avalaible_rune) {
    $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
    $phantasm = new $phantasm_class();

    $rune_list = $avalaible_rune();

    $rune_finds = [
      $phantasm->origin.'/Ether.php',
      $phantasm->origin.'/Essence.php',
      $phantasm->origin.'/Entity.php',
      $phantasm->origin.'/Manifest.php',
    ];
    
    $founds = [];
    foreach ($rune_finds as $rf) {
      $content = Forger::item($rf);
      foreach ($rune_list as $rune) {
        $phantasm_class_select = 'Rune\\' . $rune . '\\Phantasm';
        $phantasm_select = new $phantasm_class_select();

        if ($phantasm_select->main !== $phantasm->main) {
          $found_state = false;
          $rune_line = [];

          foreach ($phantasm_select->node as $item) {
            $type = $item['type'];
            $pattern = preg_quote($item['call'], '/');

            if ($type === 'entity') {
              $match = preg_match("/\\b$pattern\\s*\\(/i", $content);
            } elseif ($type === 'manifest') {
              $match = preg_match("/\\b$phantasm_select->main::\\s*$pattern\\b/i", $content);
            } else {
              $match = preg_match("/\\b$pattern\\b/i", $content);
            }

            if ($match) {
              $found_state = true;
              $rune_line[] = $type;
            }
          }

          if ($found_state && !isset($founds[$phantasm_select->main])) {
            $founds[$phantasm_select->main] = [
              $phantasm_select->main,
              implode(':', array_unique($rune_line)),
              $phantasm_select->version,
            ];
          }
        }
      }
    }
    
    $read_phantasm = Forger::item($phantasm->origin . '/Phantasm.php');
    $templates_phantasm_link = preg_replace('/(public\s\$link\s*=\s*)(\[[\s\S]*?\]);/', '{{TARGET}}', $read_phantasm);
    $templates = 'public $link = [' . PHP_EOL;
    foreach ($founds as $row) {
      $templates .= "    ['$row[0]', '$row[1]', $row[2]]," . PHP_EOL;
    }
    $templates .= '  ];' . PHP_EOL;
    
    $template = weaver_bind($templates_phantasm_link, 'TARGET', $templates);
    
    Forger::item($phantasm->origin . '/Phantasm.php', str_ireplace(PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL, $template));

    return true;
  };
  if (Chanter::spell('phantasm-fix-link')) {
    // header
    Whisper::clear(true);
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Fix Node \n");
    // run spell and call
    if (Chanter::spell('phantasm-fix-link') !== '1') {
      $name = Chanter::spell('phantasm-fix-link');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    // set data
    $name = ucfirst(strtolower($name));
    // processing
    if ( $processing__phantasm_fix_link($name) ) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
    }else {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
    }
  }
  
  /* PHANTASM FIX NOTE */
  $processing__phantasm_fix_note = function ($name) {
    $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
    $phantasm = new $phantasm_class();

    $selected = '';
    foreach (aether_arised() as $manifest) {
      $basename = str_replace('Rune\\', '', $manifest);
      $basename = str_replace('\\Manifest', '', $basename);
      
      if ($basename == $name) {
        $selected = str_replace('Manifest', 'Phantasm', $manifest);
      }
    }

    $phantasm = new $selected();
    if (isset($phantasm->origin)) {
      $sourcing = '';
      $sourcing .= Forger::item($phantasm->origin . '/Ether.php');
      $sourcing .= Forger::item($phantasm->origin . '/Essence.php');
      $sourcing .= Forger::item($phantasm->origin . '/Entity.php');
      $sourcing .= Forger::item($phantasm->origin . '/Manifest.php');
      
      $renote = [];
      $source = $sourcing; // source code penuh

      foreach ($phantasm->node as $node) {
          $type = $node['type'];
          $call = $node['call'];

          // Ambil hanya nama fungsinya aja (sebelum tanda kurung)
          if (preg_match('/^([a-zA-Z0-9_]+)\s*\(/', $call, $fnMatch)) {
              $funcName = $fnMatch[1];
          } else {
              $funcName = $call; // fallback
          }

          $escapedName = preg_quote($funcName, '/');

          // Pola regex tergantung type
          if ($type === 'manifest') {
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*function\s+' . $escapedName . '\b/i';
          } elseif ($type === 'entity') {
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*function\s+' . $escapedName . '\b/i';
          } elseif ($type === 'ether') {
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*const\s+' . $escapedName . '\b/i';
          } elseif ($type === 'essence') {
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*define\s*\(\s*[\'"]' . $escapedName . '[\'"]/i';
          } else {
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*' . $escapedName . '\b/i';
          }

          preg_match($pattern, $source, $matches);

          $note = $matches[1] ?? 'no note';
          $node['note'] = $note;
          $renote[] = $node;
      }

      $read_phantasm = Forger::item($phantasm->origin . '/Phantasm.php');
      $templates_phantasm = preg_replace('/(public\s\$node\s*=\s*)(\[[\s\S]*?\]);/', '{{node}}', $read_phantasm);
      $templates = 'public $node = [' . PHP_EOL;
      foreach ($renote as $row) {
        $call = str_replace("'", '"', $row['call']);
        $templates .= "    [" . PHP_EOL;
        $templates .= "      'type' => '$row[type]'," . PHP_EOL;
        $templates .= "      'call' => '$call'," . PHP_EOL;
        $templates .= "      'note' => '$row[note]'," . PHP_EOL;
        $templates .= "    ]," . PHP_EOL;
      }
      $templates .= '  ];' . PHP_EOL;
      $template = weaver_bind($templates_phantasm, 'node', $templates);

      Forger::item($phantasm->origin . '/Phantasm.php', str_ireplace(PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL, $template));

      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('phantasm-fix-note')) {
    // header
    Whisper::clear(true);
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Fix Note \n");
    // run spell and call
    if (Chanter::spell('phantasm-fix-note') !== '1') {
      $name = Chanter::spell('phantasm-fix-note');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    // set data
    $name = ucfirst(strtolower($name));
    // processing
    if ( $processing__phantasm_fix_note($name) ) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
    }else {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
    }
  }

  /* PHANTASM FIX */
  if (Chanter::spell('phantasm-fix')) {
    // header
    Whisper::clear(true);
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Fix Auto \n");
    // run spell and call
    if (Chanter::spell('phantasm-fix') !== '1') {
      $name = Chanter::spell('phantasm-fix');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    // set data
    $name = ucfirst(strtolower($name));
    // fixing link
    $linkFixed = $processing__phantasm_fix_link($name);
    if ($linkFixed) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Link fixed for phantasm: $name {{nl}}");
    } else {
      Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix link for phantasm: $name {{nl}}");
    }

    // fixing node
    $nodeFixed = $processing__phantasm_fix_node($name);
    if ($nodeFixed) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Node fixed for phantasm: $name {{nl}}");
    } else {
      Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix node for phantasm: $name {{nl}}");
    }

    // fixing note
    $noteFixed = $processing__phantasm_fix_note($name);
    if ($noteFixed) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Note fixed for phantasm: $name {{nl}}");
    } else {
      Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix note for phantasm: $name {{nl}}");
    }

    // update phantasm
    $updatePhantasm = $processing__phantasm_up($name);
    if ($updatePhantasm) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Update phantasm: $name {{nl}}");
    } else {
      Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to update phantasm: $name {{nl}}");
    }
  }

  

});