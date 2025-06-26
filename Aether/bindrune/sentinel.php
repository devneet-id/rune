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









  /* PHANTASM FIX NODE */
  $processing__phantasm_fix_node = function($name) {
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
      
      $read_ether = Forger::item($phantasm->origin . '/Ether.php');
      preg_match_all("/define\(\s*['\"]([^'\"]+)['\"]\s*,/", $read_ether, $matches);
      $find_ether = $matches[1];

      $read_essence = Forger::item($phantasm->origin . '/Essence.php');
      preg_match_all("/\\\$GLOBALS\\[['\"]([^'\"]+)['\"]\\]/", $read_essence, $matches);
      $find_essence = $matches[1];

      $name_entity = strtoupper($name);
      $read_entity = Forger::item($phantasm->origin . '/Entity.php');
      preg_match_all('/function\s+(.*'.$name_entity.'.*)\s*\([^\)]*\)/i', $read_entity, $matches);
      $find_entity = $matches[1];

      $read_manifest = Forger::item($phantasm->origin . '/Manifest.php');
      preg_match_all('/\bstatic\s+function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(([^)]*)\)/', $read_manifest, $matches);
      // Gabungkan nama function + isi paramnya
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
            'call' => $value,
            'note' => '',
          ];
        }
      }

      // $old_node = $phantasm->node;
      // $combined = array_merge($maps, $old_node);
      // $final = [];
      // foreach ($combined as $item) {
      //   $final[$item['call']] = $item; // key = 'call', auto timpa kalau udah ada
      // }
      // $maps = array_values($final); // Reset index biar rapi
      
      $templates = 'public $node = [' . PHP_EOL;
      foreach ($maps as $row) {
        $templates .= "    [" . PHP_EOL;
        $templates .= "      'type' => '$row[type]'," . PHP_EOL;
        $templates .= "      'call' => '$row[call]'," . PHP_EOL;
        $templates .= "      'note' => '$row[note]'," . PHP_EOL;
        $templates .= "    ]," . PHP_EOL;
      }
      $templates .= '  ];' . PHP_EOL;
      
      $template = weaver_bind($templates_phantasm, 'node', $templates);
      
      Forger::item($phantasm->origin . '/Phantasm.php', $template);

      return true;
    }else {
      return false;
    }
  };
  if (Chanter::spell('phantasm-fix-node')) {
    // header
    Whisper::clear(true);
    Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Remove Rune\n");

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
  $processing__phantasm_fix_link = function ($name) {
    $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
    $phantasm = new $phantasm_class();

    $rune_list = function() {
      $manifests = [];
      // internal rune
      foreach (glob(AETHER_RUNE_LOCATION . '/*') as $manifest) {
        if (is_dir($manifest)) {
          $manifests[] = basename($manifest);
        }
      }
      // external rune
      if (file_exists(AETHER_REPO . '/@monarch')) {
        foreach (glob(AETHER_REPO . '/@monarch/*') as $manifest) {
          if (is_dir($manifest)) {
            $manifests[] = basename($manifest);
          }
        }
      }
      return $manifests;
    };
    

    $rune_finds = [
      $phantasm->origin.'/Ether.php',
      $phantasm->origin.'/Essence.php',
      $phantasm->origin.'/Entity.php',
      $phantasm->origin.'/Manifest.php',
    ];
    
    $founds = [];
    foreach ($rune_finds as $rf) {
      $content = Forger::item($rf);
      foreach ($rune_list() as $rune) {
        $phantasm_class_select = 'Rune\\' . $rune . '\\Phantasm';
        $phantasm_select = new $phantasm_class_select();
        
        if ($phantasm_select->main !== $phantasm->main) {
          $found_state = false;
          $rune_line = [];
          foreach ($phantasm_select->node as $item) {
            $pattern = preg_quote($item['call'], '/');
            if (preg_match("/$pattern/i", $content)) {
              $found_state = true;
              $rune_line[] = $item['type'];
              // $founds[$phantasm_select->main] = $rune_line;
            }
          }
          if ($found_state) {
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
    
    Forger::item($phantasm->origin . '/Phantasm.php', trim($template));
    Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
  };
  if (Chanter::spell('phantasm-fix-link')) {
    if (Chanter::spell('phantasm-fix-link') !== '1') {
      $name = Chanter::spell('phantasm-fix-link');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    $name = ucfirst(strtolower($name));
    
    $processing__phantasm_fix_link($name);
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
        $search = $node['call']; // call kayak: echo( String $text )

        // Escape regex biar aman
        $escapedName = preg_quote($search, '/');

        // Bikin pola regex: cari komentar #NOTE: sebelum deklarasi fungsi yang cocok dengan call
        $pattern = '/#NOTE:\s*(.*?)\s*\n\s*(?:public\s+|private\s+|protected\s+|static\s+|function\s+)?(?:function\s+|const\s+|define\s*\(\s*)?' . $escapedName . '\b/i';

        preg_match($pattern, $source, $matches);

        $note = $matches[1] ?? ''; // isi komentar yang ditemukan
        $node['note'] = $note;
        $renote[] = $node;
      }


      $read_phantasm = Forger::item($phantasm->origin . '/Phantasm.php');
      $templates_phantasm = preg_replace('/(public\s\$node\s*=\s*)(\[[\s\S]*?\]);/', '{{node}}', $read_phantasm);
      $templates = 'public $node = [' . PHP_EOL;
      foreach ($renote as $row) {
        $templates .= "    [" . PHP_EOL;
        $templates .= "      'type' => '$row[type]'," . PHP_EOL;
        $templates .= "      'call' => '$row[call]'," . PHP_EOL;
        $templates .= "      'note' => '$row[note]'," . PHP_EOL;
        $templates .= "    ]," . PHP_EOL;
      }
      $templates .= '  ];' . PHP_EOL;
      $template = weaver_bind($templates_phantasm, 'node', $templates);

      Forger::item($phantasm->origin . '/Phantasm.php', $template);

      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm '$name' {{nl}}");
    }else {
      Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
    }
  };
  if (Chanter::spell('phantasm-fix-note')) {
    if (Chanter::spell('phantasm-fix-note') !== '1') {
      $name = Chanter::spell('phantasm-fix-note');
    }else {
      $name = Whisper::call('Give us the rune name: ');
    }
    $name = ucfirst(strtolower($name));
    
    $processing__phantasm_fix_note($name);
  }




  


});