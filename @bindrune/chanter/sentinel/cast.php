<?php

use Rune\Aether\Manifest as Aether;
use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;
use Rune\Whisper\Manifest as Whisper;
use Rune\Cipher\Manifest as Cipher;
use Rune\Forger\Manifest as Forger;
use Rune\Keeper\Manifest as Keeper;
use Rune\Crafter\Manifest as Crafter;
use Rune\Specter\Manifest as Specter;

// sentinel
Chanter::cast(
  arg:'sentinel',
  echo: "Help you with maintain your runite as assistant.",
  execute: function() {
    Weaver::ether()::essence()::entity();
    Whisper::ether()::essence()::entity();
    Cipher::ether()::essence()::entity();
    Forger::ether()::essence()::entity();
    Keeper::ether()::essence()::entity();
    Crafter::ether()::essence()::entity();
    Specter::ether()::essence()::entity();

    global $FORGER_KEEPER_SHARD;
    $FORGER_KEEPER_SHARD = false;

    // prepare cast template
    $template = Weaver::item(__DIR__ . '/header.txt');
    $template .= Weaver::item(__DIR__.'/cast-avaliable.txt');
    if (defined('LIBERATION')) {
      $template .= Weaver::item(__DIR__.'/cast-liberation.txt');
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
      static $warned = false;
      $result = [];
      foreach (glob(AETHER_RUNE_LOCATION . '/*') as $rune) {
        if (is_dir($rune)) {
          if (basename($rune)!=='@bindrune') {
            $result[] = basename($rune);
          }
        }
      }
      // external rune (a liberation rune never replaces an origin rune)
      if (defined('LIBERATION')) {
        foreach (glob(AETHER_REPO . '/@liberations/*') as $rune) {
          if (is_dir($rune)) {
            if (in_array(basename($rune), $result)) {
              if (!$warned) {
                Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} @liberations/" . basename($rune) . " is skipped, it has the name of an origin rune. Rename it (e.g. MY" . basename($rune) . "). {{nl}}");
              }
              continue;
            }
            $result[] = basename($rune);
          }
        }
        $warned = true;
      }
      return $result;
    };

    /* ALTAR
    * */
    $processing__altar = function() {
      if (!file_exists(AETHER_REPO . '/altar.php')) {
        Forger::move([
          ['type'=>'item', 'from'=> __DIR__ . '/altar.txt', 'target'=> AETHER_REPO . '/altar.php']
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
    $processing_invoke = function($target) use ($avalaible_rune) {
      (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

      $target = ucfirst(strtolower($target));
      if ($target) {
        $target_parts = explode('.', $target);
        $manifest = $target_parts[0];
        $methods = array_slice($target_parts, 1);

        // only invoke runes that exist (origin runes, plus @liberations runes in liberation mode)
        if (!in_array($manifest, $avalaible_rune())) {
          return false;
        }

        $valid_methods = ['ether', 'essence', 'entity'];
        $selected_methods = $methods ? array_values(array_intersect($valid_methods, $methods)) : $valid_methods;

        $codex_arise = $manifest . '::' . implode('()::', $selected_methods) . '();';

        $rune = Forger::item(AETHER_REPO . '/' . basename(AETHER_FILE));
        $eol = (strpos($rune, "\r\n") !== false) ? "\r\n" : "\n"; // keep the runite's own line ending

        $prefix_manifest = '#sentinel-manifest';
        $prefix_arise = '#sentinel-awaken';
        $codex_manifest = "use Rune\\{$manifest}\\Manifest as {$manifest};";

        if (strpos($rune, $codex_manifest) !== false) {
          $rune = str_replace($codex_manifest . $eol, '', $rune);
        }
        $rune = str_replace($prefix_manifest, $codex_manifest . $eol . $prefix_manifest, $rune);

        // Hapus baris arise yang lama untuk manifest ini
        $rune = preg_replace(
          '/' . preg_quote($manifest) . '::(?:ether|essence|entity)(\(\)::)?(?:ether|essence|entity)?(\(\)::)?(?:ether|essence|entity)?\(\);' . $eol . '/',
          '',
          $rune
        );

        $rune = str_replace($prefix_arise, $codex_arise . $eol . $prefix_arise, $rune);
        
        Forger::item(AETHER_REPO . '/' . basename(AETHER_FILE), $rune);
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
          Whisper::echo("{{color-success}}{{icon-success}} Successfully do Invoked with '$input' {{nl}}");
        }else {
          Whisper::echo("{{color-danger}}{{icon-warning}} Failed do Invoked with '$input' {{nl}}");
          Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Avaliable rune: " . implode(', ', $avalaible_rune()) . "{{nl}}");
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
          $parts = explode('.', $target);
          $manifest = $parts[0];
          unset($parts[0]);
          $arise = array_values($parts);
        } else {
          $manifest = $target;
          $arise = 'all';
        }

        $rune = Forger::item(AETHER_REPO . '/' . basename(AETHER_FILE));
        $eol = (strpos($rune, "\r\n") !== false) ? "\r\n" : "\n"; // keep the runite's own line ending
        $prefix_manifest = '#sentinel-manifest';
        $prefix_arise = '#sentinel-awaken';
        $codex_manifest = "use Rune\\{$manifest}\\Manifest as {$manifest};";

        $method_map = [
          'ether' => 'ether',
          'essence' => 'essence',
          'entity' => 'entity'
        ];

        $pattern = '/' . preg_quote($manifest) . '::(?:ether\(\)::|essence\(\)::|entity\(\)::)*(ether|essence|entity)\(\);/';
        preg_match($pattern, $rune, $matches);
        $chain_line = $matches[0] ?? null;

        if ($arise === 'all') {
          if ($chain_line) {
            $rune = str_replace($chain_line . $eol, '', $rune);
          }
          if (strpos($rune, $codex_manifest) !== false) {
            $rune = str_replace($codex_manifest . $eol, '', $rune);
          }
        } else {
          if ($chain_line) {
            $existing_methods = [];
            foreach ($method_map as $key => $method) {
              if (strpos($chain_line, $method . '()') !== false) {
                $existing_methods[] = $key;
              }
            }

            $remaining_methods = array_diff($existing_methods, $arise);

            if (!empty($remaining_methods)) {
              $new_chain = $manifest . '::' . implode('()::', array_map(fn($m) => $method_map[$m], $remaining_methods)) . '();';
              $rune = str_replace($chain_line, $new_chain, $rune);
            } else {
              $rune = str_replace($chain_line . $eol, '', $rune);
              if (strpos($rune, $codex_manifest) !== false) {
                $rune = str_replace($codex_manifest . $eol, '', $rune);
              }
            }
          }
        }
        
        Forger::item(AETHER_REPO . '/' . basename(AETHER_FILE), $rune);
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
          Whisper::echo("{{color-success}}{{icon-success}} Successfully do Revoke with '$input' {{nl}}");
        }else {
          Whisper::echo("{{color-danger}}{{icon-warning}} Failed do Revoke with '$input' {{nl}}");
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
      $phantasm = Weaver::item( __DIR__ . '/rune--phantasm.txt');
      $manifest = Weaver::item( __DIR__ . '/rune--manifest.txt');
      $ether = Weaver::item( __DIR__ . '/rune--ether.txt');
      $essence = Weaver::item( __DIR__ . '/rune--essence.txt');
      $entity = Weaver::item( __DIR__ . '/rune--entity.txt');
    
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
      if (!defined('LIBERATION')) {
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
      $name = ucfirst(strtolower(trim((string) $name)));
      $repo = LIBERATION . $name . '/';

      // an origin rune can not be replaced by a liberation rune
      if ($name === '' || is_dir(AETHER_RUNE_LOCATION . '/' . $name)) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} '$name' is an origin rune, use another name (e.g. MY$name). {{nl}}");
        return;
      }

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
      if (!defined('LIBERATION')) {
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
      $repo = LIBERATION . $name . '/';

      // processing
      if ($processing__remove_rune($name, $repo) ) {
        // also revoke it from the runite, so no `use`/awaken line points to a deleted rune
        $processing_revoke($name);
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success remove rune: $name {{nl}}");
      }else {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune not found: $name {{nl}}");
      }
    }








    /* PHANTASM (liberation mode only: these spells write into a rune's Phantasm) */
    foreach (['phantasm-up', 'phantasm-fix', 'phantasm-fix-node', 'phantasm-fix-note', 'phantasm-fix-link', 'phantasm-fix-link-all'] as $phantasm_spell) {
      if (Chanter::spell($phantasm_spell) && !defined('LIBERATION')) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} You are not eligible!! {{nl}}");
        die();
      }
    }

    /* PHANTASM: keep the file's own line ending (the origin runes are CRLF) */
    $keep_eol = function($path, $content) {
      $original = file_exists($path) ? (string) Forger::item($path) : '';
      $content = str_replace("\r\n", "\n", $content);
      $content = preg_replace("/\n{3,}/", "\n\n", $content); // never more than one empty line
      return (strpos($original, "\r\n") !== false) ? str_replace("\n", "\r\n", $content) : $content;
    };

    /* PHANTASM: the rune must exist (no fatal error on a wrong name) */
    $processing__phantasm_exists = function($name) {
      if ($name === '' || !class_exists('Rune\\' . $name . '\\Phantasm')) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune not found: $name {{nl}}");
        return false;
      }
      return true;
    };

    /* PHANTASM UP */
    $processing__phantasm_up = function($name) {
      $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
      $phantasm = new $phantasm_class();

      if (isset($phantasm->origin)) {
        $path = $phantasm->origin . '/Phantasm.php';
        $read_phantasm = Forger::item($path);

        preg_match('/public\s+\$version\s*=\s*[\'"]?([\d.]+)[\'"]?/i', $read_phantasm, $matches);
        $current_version = isset($matches[1]) ? $matches[1] : '1.0';

        // only <major>.<minor> (whole numbers): anything else would be cut silently
        if (!preg_match('/^\d+\.\d+$/', $current_version)) {
          Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Version '$current_version' of $name is not <major>.<minor>, fix it by hand. {{nl}}");
          return false;
        }

        $parts = explode('.', $current_version);
        $major = isset($parts[0]) ? $parts[0] : '1';
        $minor = isset($parts[1]) ? $parts[1] : '0';

        $minor = str_pad((string)((int)$minor + 1), strlen($minor), '0', STR_PAD_LEFT);
        $new_version = $major . '.' . $minor;

        $updated = preg_replace(
            '/(public\s+\$version\s*=\s*)[\'"]?[\d.]+[\'"]?/i',
            '${1}\'' . $new_version . '\'',
            $read_phantasm
        );

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
      if (!$processing__phantasm_exists($name)) {
        // nothing to raise
      }else if ( $processing__phantasm_up($name) ) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
      }else {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not updated: $name {{nl}}");
      }
    }

    /* PHANTASM FIX NODE */
    $processing__phantasm_fix_node = function($name) use ($keep_eol) {
      $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
      $phantasm = new $phantasm_class();

      if (isset($phantasm->origin)) {
        
        // block comments are skipped (template examples like `define('STARTER', '')` are not nodes)
        $uncomment = function($source) { return preg_replace(['#/\*.*?\*/#s', '#^\s*(//|\#).*$#m'], '', (string) $source); };

        $read_ether = $uncomment(Forger::item($phantasm->origin . '/Ether.php'));
        preg_match_all("/define\(\s*['\"]([^'\"]+)['\"]\s*,/", $read_ether, $matches);
        $find_ether = $matches[1];

        $read_essence = $uncomment(Forger::item($phantasm->origin . '/Essence.php'));
        preg_match_all("/\\\$GLOBALS\\[['\"]([^'\"]+)['\"]\\]/", $read_essence, $matches);
        $find_essence = $matches[1];

        $name_entity = strtoupper($name);
        $read_entity = $uncomment(Forger::item($phantasm->origin . '/Entity.php'));
        preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(([^)]*)\)/i', $read_entity, $matches);
        $find_entity = array_filter(array_map(function ($func, $params) use ($name_entity) {
            if (stripos($func, $name_entity) !== false) {
                return $func . '(' . $params . ')';
            }
            return null;
        }, $matches[1], $matches[2]));

        $read_manifest = $uncomment(Forger::item($phantasm->origin . '/Manifest.php'));
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
          $note = str_replace("'", '"', $row['note']);
          $templates .= "    [" . PHP_EOL;
          $templates .= "      'type' => '$row[type]'," . PHP_EOL;
          $templates .= "      'call' => '$call'," . PHP_EOL;
          $templates .= "      'note' => '$note'," . PHP_EOL;
          $templates .= "    ]," . PHP_EOL;
        }
        $templates .= '  ];' . PHP_EOL;
        
        $template = weaver_bind($templates_phantasm, 'node', $templates);
        
        Forger::item($phantasm->origin . '/Phantasm.php', $keep_eol($phantasm->origin . '/Phantasm.php', str_ireplace(PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL, $template)));

        return $maps; // T15: return the fresh node list so --phantasm-fix can pass it to fix-note
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
      if ( $processing__phantasm_fix_node($name) !== false ) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Success update phantasm: $name {{nl}}");
      }else {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not have origin!! {{nl}}");
      }
    }

    /* PHANTASM FIX LINK */
    $processing__phantasm_fix_link = function ($name) use ($avalaible_rune, $keep_eol) {
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
        $content = preg_replace(['#/\*.*?\*/#s', '#^\s*(//|\#).*$#m'], '', (string) Forger::item($rf)); // skip comments
        foreach ($rune_list as $rune) {
          $phantasm_class_select = 'Rune\\' . $rune . '\\Phantasm';
          $phantasm_select = new $phantasm_class_select();

          if ($phantasm_select->main !== $phantasm->main) {
            $found_state = false;
            $rune_line = [];

            foreach ($phantasm_select->node as $item) {
              $type = $item['type'];
              // match by name only: `keeper_item_get( String $name )` -> `keeper_item_get`
              $call_name = preg_match('/^\s*\$?([A-Za-z_][A-Za-z0-9_]*)/', $item['call'], $call_match) ? $call_match[1] : $item['call'];
              $pattern = preg_quote($call_name, '/');

              if ($type === 'entity') {
                $match = preg_match("/\\b$pattern\\s*\\(/i", $content);
              } elseif ($type === 'manifest') {
                $match = preg_match("/\\b$phantasm_select->main::\\s*$pattern\\s*\\(/i", $content);
              } else {
                // ether (constant) & essence (global) are case sensitive
                $match = preg_match("/\\b$pattern\\b/", $content);
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
        $templates .= "    ['$row[0]', '$row[1]', '$row[2]']," . PHP_EOL;
      }
      $templates .= '  ];' . PHP_EOL;
      
      $template = weaver_bind($templates_phantasm_link, 'TARGET', $templates);
      
      Forger::item($phantasm->origin . '/Phantasm.php', $keep_eol($phantasm->origin . '/Phantasm.php', str_ireplace(PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL, $template)));

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
    $processing__phantasm_fix_note = function ($name, $nodes = null) use ($keep_eol) {
      $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
      $phantasm = new $phantasm_class();

      if (isset($phantasm->origin)) {
        $sourcing = '';
        $sourcing .= Forger::item($phantasm->origin . '/Ether.php');
        $sourcing .= Forger::item($phantasm->origin . '/Essence.php');
        $sourcing .= Forger::item($phantasm->origin . '/Entity.php');
        $sourcing .= Forger::item($phantasm->origin . '/Manifest.php');
        
        $renote = [];
        $source = $sourcing; // source code penuh

        foreach (($nodes ?? $phantasm->node) as $node) { // T15: use fresh nodes when given
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
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*define\s*\(\s*[\'"]' . $escapedName . '[\'"]/i';
            } elseif ($type === 'essence') {
              $pattern = '/#NOTE:\s*(.*?)\s*\n\s*\$GLOBALS\[[\'"]' . $escapedName . '[\'"]\]\s*=\s*/i';
            } else {
              $pattern = '/#NOTE:\s*(.*?)\s*(?:\n\s*)*.*' . $escapedName . '\b/i';
            }

            preg_match($pattern, $source, $matches);

            $note = $matches[1] ?? '';
            $node['note'] = $note;
            $renote[] = $node;
        }

        $read_phantasm = Forger::item($phantasm->origin . '/Phantasm.php');
        $templates_phantasm = preg_replace('/(public\s\$node\s*=\s*)(\[[\s\S]*?\]);/', '{{node}}', $read_phantasm);
        $templates = 'public $node = [' . PHP_EOL;
        foreach ($renote as $row) {
          $call = str_replace("'", '"', $row['call']);
          $note = str_replace("'", '"', $row['note']);
          $templates .= "    [" . PHP_EOL;
          $templates .= "      'type' => '$row[type]'," . PHP_EOL;
          $templates .= "      'call' => '$call'," . PHP_EOL;
          $templates .= "      'note' => '$note'," . PHP_EOL;
          $templates .= "    ]," . PHP_EOL;
        }
        $templates .= '  ];' . PHP_EOL;
        $template = weaver_bind($templates_phantasm, 'node', $templates);

        Forger::item($phantasm->origin . '/Phantasm.php', $keep_eol($phantasm->origin . '/Phantasm.php', str_ireplace(PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL, $template)));

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
      if (!$processing__phantasm_exists($name)) {
        return;
      }
      // remember the Phantasm before fixing: the version is raised only when something changed
      $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
      $phantasm_path = (new $phantasm_class())->origin . '/Phantasm.php';
      $phantasm_before = file_exists($phantasm_path) ? Forger::item($phantasm_path) : null;
      // fixing node
      $nodeFixed = $processing__phantasm_fix_node($name);
      if ($nodeFixed !== false) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Node fixed for phantasm: $name {{nl}}");
      } else {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix node for phantasm: $name {{nl}}");
      }
      // fixing link
      $linkFixed = $processing__phantasm_fix_link($name);
      if ($linkFixed) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Link fixed for phantasm: $name {{nl}}");
      } else {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix link for phantasm: $name {{nl}}");
      }
      // fixing note
      $noteFixed = $processing__phantasm_fix_note($name, ($nodeFixed !== false) ? $nodeFixed : null);
      if ($noteFixed) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Note fixed for phantasm: $name {{nl}}");
      } else {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix note for phantasm: $name {{nl}}");
      }
      // update phantasm (only when node, link or note changed it)
      if ($phantasm_before !== null && Forger::item($phantasm_path) === $phantasm_before) {
        Whisper::echo("{{COLOR-INFO}}{{ICON-INFO}} Nothing changed, version kept: $name {{nl}}");
      } else if ($processing__phantasm_up($name)) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Update phantasm: $name {{nl}}");
      } else {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to update phantasm: $name {{nl}}");
      }
    }

    /* PHANTASM FIX LINK ALL */
    if (Chanter::spell('phantasm-fix-link-all')) {
      // header
      Whisper::clear(true);
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Fix Link All \n");
      // fixing link
      foreach ( $avalaible_rune() as $name) {
        // fixing link
        $linkFixed = $processing__phantasm_fix_link($name);
        if ($linkFixed) {
          Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Link fixed for phantasm: $name {{nl}}");
        } else {
          Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Failed to fix link for phantasm: $name {{nl}}");
        }
      }
    }
  }
);