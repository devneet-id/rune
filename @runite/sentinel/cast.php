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
  arg: 'sentinel',
  echo: 'Help you maintain your runite as an assistant.',
  execute: function() {
    Weaver::ether()::essence()::entity();
    Whisper::ether()::essence()::entity();
    Cipher::ether()::essence()::entity();
    Forger::ether()::essence()::entity();
    Keeper::ether()::essence()::entity();
    Crafter::ether()::essence()::entity();
    Specter::ether()::essence()::entity();

    // a spell with a value ('' when given without one)
    $value = function( String $spell ) {
      $value = Chanter::spell($spell);
      return ($value === false || $value === '1') ? '' : trim((string) $value);
    };

    // the rune name as it is written (Ledger, MyAether), whatever case is given
    $runic_name = function( $name ) {
      $name = trim((string) $name);
      foreach (aether_list_runes() as $rune) {
        if (strcasecmp($rune, $name) === 0) {
          return $rune;
        }
      }
      return preg_match('/^[A-Z][A-Za-z0-9]*$/', $name) ? $name : ucfirst(strtolower($name));
    };

    // ask the rune name when the spell has no value
    $ask_rune = function( String $spell ) use ($value) {
      $name = $value($spell);
      if ($name === '') {
        Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Available rune: " . implode(', ', aether_list_runes()) . "{{nl}}");
        $name = trim((string) Whisper::call('Give us the rune name: '));
      }
      return $name;
    };

    // old spells answer with the new one
    $renamed = [
      'invoke'=> '--runite-insert',
      'revoke'=> '--runite-remove',
      'create-rune'=> '--runic-create',
      'remove-rune'=> '--runic-delete',
      'phantasm-up'=> '--phantasm-upgrade',
      'phantasm-fix-node'=> '--phantasm-repair --mode=node',
      'phantasm-fix-note'=> '--phantasm-repair --mode=note',
      'phantasm-fix-link-all'=> '--phantasm-repair --mode=link --all',
      'phantasm-fix-link'=> '--phantasm-repair --mode=link',
      'phantasm-fix'=> '--phantasm-repair',
    ];
    foreach ($renamed as $old=>$new) {
      if (Chanter::spell($old) !== false) {
        Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}}{{LABEL-WARNING}}--$old is now $new{{nl}}");
        return;
      }
    }

    // main hall of the sentinel
    $spells = ['altar', 'inspect', 'gitignore', 'evolution', 'federation', 'liberation', 'runite-insert', 'runite-remove', 'runic-create', 'runic-delete', 'phantasm-upgrade', 'phantasm-repair', 'runtime-install', 'runtime-url', 'runtime-list', 'runtime-use', 'runtime-remove', 'runtime-launcher', 'test'];
    $asked = array_filter($spells, fn($spell) => Chanter::spell($spell) !== false);
    if (!$asked) {
      $template = Weaver::item(__DIR__ . '/header.txt');
      if (defined('EVOLUTION')) {
        $template .= Weaver::item(__DIR__ . '/header-evolution.txt');
      }
      if (defined('LIBERATION')) {
        $template .= Weaver::item(__DIR__ . '/header-liberation.txt');
      }
      $template = Weaver::bind($template, [
        'AETHER-FILE'=> AETHER_FILE,
      ]);
      if (aether_has_entity('whisper')) {
        whisper_clear();
        Whisper::echo($template);
      }else {
        aether_whisper($template);
      }
      return;
    }

    // spell of testing
    if (Chanter::spell('test')) {
      Whisper::clear(true);
      Whisper::echo("\n S E N T I N E L {{color-danger}}::{{color-end}} INVOKE");
      Whisper::echo("\n{{color-success}}{{icon-success}} Successfully Invoked");
    }

    // the runite file and its line ending
    $runite_path = AETHER_REPO . '/' . basename(AETHER_FILE);


    /* ALTAR (hidden)
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

    /* INSPECT (hidden, still in development)
    * */
    if (Chanter::spell('inspect')) {
      Whisper::clear()::echo('{{COLOR-DANGER}} Under Development {{nl}}');
    }


    /* HELP: GITIGNORE
    * a marked block in the .gitignore next to the runite (the lines of the user are kept)
    * */
    if (Chanter::spell('gitignore')) {
      $path = AETHER_REPO . '/.gitignore';
      $original = file_exists($path) ? (string) file_get_contents($path) : '';
      $eol = (strpos($original, "\r\n") !== false) ? "\r\n" : "\n";
      $block = implode($eol, ['# rune (sentinel)', 'vendor/', '.echoes/', '# /rune']) . $eol;
      $pattern = '/# rune \(sentinel\)\R.*?# \/rune(\R|$)/s';
      if (preg_match($pattern, $original)) {
        $content = preg_replace($pattern, str_replace('$', '\$', $block), $original, 1);
        $done = 'updated';
      }else {
        $content = ($original === '' ? '' : rtrim($original, "\r\n") . $eol . $eol) . $block;
        $done = 'written';
      }
      file_put_contents($path, $content);
      Whisper::clear();
      Whisper::echo("{{color-danger}}::{{color-end}}S E N T I N E L {{nl}}");
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} The rune block of .gitignore is $done (vendor/, .echoes/) {{nl}}");
    }


    /* UNLOCK: EVOLUTION, FEDERATION & LIBERATION
    * writes the mode line under the `require` line of the runite (already there = only told)
    * */
    $modes = [
      'evolution'=> ['caption'=> 'The Evolution of Eidos', 'folder'=> '@evolution', 'readme'=> 'evolution.md'],
      'federation'=> ['caption'=> 'The Federation of Fides', 'folder'=> '@federation', 'readme'=> 'federation.md'],
      'liberation'=> ['caption'=> 'The Liberation of Forte', 'folder'=> '@liberation', 'readme'=> 'liberation.md'],
    ];
    $processing__unlock = function( String $mode ) use ($runite_path) {
      $runite = (string) Forger::item($runite_path);
      $eol = (strpos($runite, "\r\n") !== false) ? "\r\n" : "\n";
      $line = "Rune\\Ethereal::$mode();";

      // already unlocked
      if (preg_match('/^[ \t]*\\\\?Rune\\\\Ethereal::' . $mode . '\(\s*\)\s*;/m', $runite)) {
        return 'unlocked';
      }

      // the `require ... autoload.php` line
      if (!preg_match('/^[^\r\n]*require(_once)?[^\r\n]*autoload\.php[^\r\n]*/m', $runite, $require, PREG_OFFSET_CAPTURE)) {
        return false;
      }
      $at = $require[0][1] + strlen($require[0][0]);

      // after the mode lines already written there (side by side)
      if (preg_match('/\G((\r?\n)+[ \t]*\\\\?Rune\\\\Ethereal::[a-z]+\(\s*\)\s*;[^\r\n]*)+/', $runite, $lines, 0, $at)) {
        $runite = substr($runite, 0, $at + strlen($lines[0])) . $eol . $line . substr($runite, $at + strlen($lines[0]));
      }else {
        $runite = substr($runite, 0, $at) . $eol . $eol . $line . substr($runite, $at);
      }
      Forger::item($runite_path, $runite);
      return 'written';
    };
    foreach ($modes as $mode=>$about) {
      if (Chanter::spell($mode)) {
        Whisper::clear();
        Whisper::echo("{{color-danger}}::{{color-end}}S E N T I N E L {{nl}}");
        $done = $processing__unlock($mode);
        if ($done === 'unlocked') {
          Whisper::echo("{{COLOR-INFO}}{{ICON-INFO}} $about[caption] is already unlocked in " . AETHER_FILE . " {{nl}}");
        }else if ($done === 'written') {
          \Rune\Ethereal::mode($about['folder'], __DIR__ . '/' . $about['readme']);
          Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} $about[caption] is unlocked: Rune\\Ethereal::$mode(); {{nl}}");
          Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Read $about[folder]/README.md {{nl}}");
        }else {
          Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} No `require ... autoload.php` line found in " . AETHER_FILE . ", write Rune\\Ethereal::$mode(); under it by hand. {{nl}}");
        }
      }
    }


    /* RUNITE: INSERT
    * insert the manifest & awaken of a rune to the runite
    *  */
    $processing_invoke = function($target) use ($runic_name) {
      (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);

      $target = trim((string) $target);
      if ($target) {
        $target_parts = explode('.', $target);
        $manifest = $runic_name($target_parts[0]);
        $methods = array_map('strtolower', array_slice($target_parts, 1));

        // only insert runes that exist (origin runes, plus @evolution/ runes in evolution mode)
        if (!in_array($manifest, aether_list_runes())) {
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
    if (Chanter::spell('runite-insert')) {
      (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);
      Whisper::clear();
      $input = $ask_rune('runite-insert');
      if ($input) {
        Whisper::echo("{{color-danger}}::{{color-end}}S E N T I N E L {{nl}}");
        if ($processing_invoke($input)) {
          Whisper::echo("{{color-success}}{{icon-success}} Inserted '$input' to " . AETHER_FILE . " {{nl}}");
        }else {
          Whisper::echo("{{color-danger}}{{icon-warning}} Failed to insert '$input' {{nl}}");
          Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Available rune: " . implode(', ', aether_list_runes()) . "{{nl}}");
        }
      }
    }


    /* RUNITE: REMOVE
    * remove the manifest & awaken of a rune from the runite
    *  */
    $processing_revoke = function($target) use ($runic_name) {
      $target = trim((string) $target);
      if ($target) {
        if (strpos($target, '.') !== false) {
          $parts = explode('.', $target);
          $manifest = $runic_name($parts[0]);
          unset($parts[0]);
          $arise = array_values(array_map('strtolower', $parts));
        } else {
          $manifest = $runic_name($target);
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
    if (Chanter::spell('runite-remove')) {
      (aether_has_entity('forger')) ?: die(PHP_EOL.'[!]WARNING: Required Forger:entity'.PHP_EOL);
      Whisper::clear();
      $input = $ask_rune('runite-remove');
      if ($input) {
        Whisper::echo("{{color-danger}}::{{color-end}}S E N T I N E L {{nl}}");
        if ($processing_revoke($input)) {
          Whisper::echo("{{color-success}}{{icon-success}} Removed '$input' from " . AETHER_FILE . " {{nl}}");
        }else {
          Whisper::echo("{{color-danger}}{{icon-warning}} Failed to remove '$input' {{nl}}");
        }
      }
    }


    /*
    * EVOLUTION (evolution mode only: these spells write runes in @evolution/)
    * */
    foreach (['runic-create', 'runic-delete', 'phantasm-upgrade', 'phantasm-repair'] as $evolution_spell) {
      if (Chanter::spell($evolution_spell) && !defined('EVOLUTION')) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} --$evolution_spell needs The Evolution of Eidos: php " . AETHER_FILE . " sentinel --evolution {{nl}}");
        return;
      }
    }

    /* RUNIC CREATE */
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
    if (Chanter::spell('runic-create')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runic Create\n");

      $name = $value('runic-create');
      if ($name === '') {
        $name = trim((string) Whisper::call('Give us the rune name: '));
      }
      $name = preg_match('/^[A-Z][A-Za-z0-9]*$/', $name) ? $name : ucfirst(strtolower($name));
      $repo = EVOLUTION . $name . '/';

      // an origin rune can not be replaced by an evolution rune
      if ($name === '' || in_array(strtolower($name), array_map('strtolower', AETHER_RUNES))) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} '$name' is an origin rune, use another name (e.g. My$name). {{nl}}");
        return;
      }
      if (file_exists($repo)) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune already exists: @evolution/$name {{nl}}");
        return;
      }

      $processing__create_rune($name, $repo);
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Rune created: @evolution/$name {{nl}}");
    }

    /* RUNIC DELETE */
    $processing__remove_rune = function($name, $repo) {
      if (file_exists($repo)) {
        Forger::clean($repo, true);
        return true;
      }else {
        return false;
      }
    };
    if (Chanter::spell('runic-delete')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runic Delete\n");

      $name = $value('runic-delete');
      if ($name === '') {
        Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Evolution rune: " . implode(', ', aether_list_evolution()) . "{{nl}}");
        $name = trim((string) Whisper::call('Give us the rune name: '));
      }
      $name = $runic_name($name);
      $repo = EVOLUTION . $name . '/';

      if ($name !== '' && $processing__remove_rune($name, $repo)) {
        // also remove it from the runite, so no `use`/awaken line points to a deleted rune
        $processing_revoke($name);
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Rune deleted: @evolution/$name {{nl}}");
      }else {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune not found: @evolution/$name {{nl}}");
      }
    }


    /* PHANTASM: the rune must exist (no fatal error on a wrong name) */
    $processing__phantasm_exists = function($name) {
      if ($name === '' || !class_exists('Rune\\' . $name . '\\Phantasm')) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune not found: $name {{nl}}");
        return false;
      }
      return true;
    };

    /* PHANTASM UPGRADE: raise the minor version */
    /* PHANTASM: keep the file's own line ending (the origin runes are CRLF) */
    $keep_eol = function($path, $content) {
      $original = file_exists($path) ? (string) Forger::item($path) : '';
      $content = str_replace("\r\n", "\n", $content);
      $content = preg_replace("/\n{3,}/", "\n\n", $content); // never more than one empty line
      return (strpos($original, "\r\n") !== false) ? str_replace("\n", "\r\n", $content) : $content;
    };

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
    /* PHANTASM FIX LINK */
    $processing__phantasm_fix_link = function ($name) use ($keep_eol) {
      $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
      $phantasm = new $phantasm_class();

      $rune_list = aether_list_runes();

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
    if (Chanter::spell('phantasm-upgrade')) {
      Whisper::clear(true);
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Upgrade \n");
      $name = $runic_name($ask_rune('phantasm-upgrade'));
      if (!$processing__phantasm_exists($name)) {
        // nothing to raise
      }else if ($processing__phantasm_up($name)) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Phantasm upgraded: $name {{nl}}");
      }else {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Phantasm not upgraded: $name {{nl}}");
      }
    }


    /* PHANTASM REPAIR
    * --phantasm-repair[=<Rune>] [--mode=node|note|link] [--all]
    * no --mode = node, link and note (the version is raised when something changed)
    * --all = every rune
    *  */
    if (Chanter::spell('phantasm-repair')) {
      Whisper::clear(true);
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Phantasm Repair \n");

      $mode = strtolower($value('mode'));
      if ($mode !== '' && !in_array($mode, ['node', 'note', 'link'])) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} --mode is node, note or link {{nl}}");
        return;
      }

      $names = Chanter::spell('all') ? aether_list_runes() : [$runic_name($ask_rune('phantasm-repair'))];
      foreach ($names as $name) {
        if (!$processing__phantasm_exists($name)) {
          continue;
        }
        $phantasm_class = 'Rune\\' . $name . '\\Phantasm';
        $phantasm = new $phantasm_class();
        if (empty($phantasm->origin)) {
          Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Phantasm has no origin: $name {{nl}}");
          continue;
        }

        // one part only
        if ($mode === 'node') {
          $done = $processing__phantasm_fix_node($name) !== false;
        }else if ($mode === 'note') {
          $done = $processing__phantasm_fix_note($name);
        }else if ($mode === 'link') {
          $done = $processing__phantasm_fix_link($name);
        }
        if ($mode !== '') {
          Whisper::echo($done
            ? "{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Phantasm $mode repaired: $name {{nl}}"
            : "{{COLOR-WARNING}}{{ICON-WARNING}} Phantasm $mode not repaired: $name {{nl}}");
          continue;
        }

        // all three: remember the Phantasm before, the version is raised only when something changed
        $phantasm_path = $phantasm->origin . '/Phantasm.php';
        $phantasm_before = file_exists($phantasm_path) ? Forger::item($phantasm_path) : null;

        $nodeFixed = $processing__phantasm_fix_node($name);
        Whisper::echo(($nodeFixed !== false)
          ? "{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Node repaired: $name {{nl}}"
          : "{{COLOR-WARNING}}{{ICON-WARNING}} Node not repaired: $name {{nl}}");

        $linkFixed = $processing__phantasm_fix_link($name);
        Whisper::echo($linkFixed
          ? "{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Link repaired: $name {{nl}}"
          : "{{COLOR-WARNING}}{{ICON-WARNING}} Link not repaired: $name {{nl}}");

        $noteFixed = $processing__phantasm_fix_note($name, ($nodeFixed !== false) ? $nodeFixed : null);
        Whisper::echo($noteFixed
          ? "{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Note repaired: $name {{nl}}"
          : "{{COLOR-WARNING}}{{ICON-WARNING}} Note not repaired: $name {{nl}}");

        if ($phantasm_before !== null && Forger::item($phantasm_path) === $phantasm_before) {
          Whisper::echo("{{COLOR-INFO}}{{ICON-INFO}} Nothing changed, version kept: $name {{nl}}");
        }else if ($processing__phantasm_up($name)) {
          Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Phantasm upgraded: $name {{nl}}");
        }else {
          Whisper::echo("{{COLOR-WARNING}}{{ICON-WARNING}} Phantasm not upgraded: $name {{nl}}");
        }
      }
    }


    /*
    * LIBERATION (liberation mode only: the runtimes in @liberation/)
    * --runtime-install[=php@<version>] [--os=windows|linux|macos] [--arch=x86_64|aarch64] [--from=local]
    * --runtime-url=<archive url> --name=<runtime>@<version> [--sha256=<hash>]
    * --runtime-list · --runtime-use=<runtime>@<version> · --runtime-remove=<runtime>@<version> · --runtime-launcher
    * */
    $runtime_spells = ['runtime-install', 'runtime-url', 'runtime-list', 'runtime-use', 'runtime-remove', 'runtime-launcher'];
    foreach ($runtime_spells as $liberation_spell) {
      if (Chanter::spell($liberation_spell) && !defined('LIBERATION')) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} --$liberation_spell needs The Liberation of Forte: php " . AETHER_FILE . " sentinel --liberation {{nl}}");
        return;
      }
    }
    if (defined('LIBERATION') && array_filter($runtime_spells, fn($spell) => Chanter::spell($spell) !== false)) {
      require_once __DIR__ . '/runtime.php';
    }

    // the end of an install: where it is, and how to run with it
    $runtime_kept = function( String $name, String $path ) {
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Runtime kept: @liberation/$name/ {{nl}}");
      if (str_starts_with($name, 'php@')) {
        $binary = file_exists($path . 'php.exe') ? 'php.exe' : 'php';
        Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Run with it: @liberation/$name/$binary " . basename(AETHER_FILE) . " · or write a launcher: --runtime-launcher {{nl}}");
      }
    };

    /* RUNTIME INSTALL: a runtime Rune knows (php), by version — or the PHP running now (--from=local) */
    if (Chanter::spell('runtime-install')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runtime Install\n");

      if (strtolower($value('from')) === 'local') {
        [$path, $problem] = runtime_php_local();
        if (!$path) {
          Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} $problem {{nl}}");
          return;
        }
        $runtime_kept(basename(rtrim($path, '/')), $path);
        return;
      }

      // php@8.3 · php@8.3.14 · 8.3 — empty: the PHP running now
      $asked = $value('runtime-install');
      [$runtime, $version] = str_contains($asked, '@') ? runtime_name($asked) : ['php', $asked];
      if (!isset(runtime_catalog()[$runtime])) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Rune does not know '$runtime' yet: take it from a link with --runtime-url=<archive url> --name=$runtime@<version> {{nl}}");
        return;
      }
      $version = $version !== '' ? $version : PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
      [$os, $arch] = runtime_target($value('os'), $value('arch'));

      [$build, $problem] = runtime_php_find($version, $os, $arch);
      if (!$build) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} php@$version: $problem {{nl}}");
        return;
      }
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} php@{$build['version']} for $os $arch: {$build['url']} {{nl}}");
      [$path, $problem] = runtime_install('php', $build['version'], $build, $os, $arch);
      if (!$path) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} php@{$build['version']}: $problem {{nl}}");
        return;
      }
      $runtime_kept('php@' . $build['version'], $path);
    }

    /* RUNTIME URL: any runtime, from a link to its archive (zip, tar.gz, tar.bz2, tar) or a single file */
    if (Chanter::spell('runtime-url')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runtime URL\n");
      $url = $value('runtime-url');
      [$runtime, $version] = runtime_name($value('name'));
      if (!preg_match('#^https?://#i', $url) || $runtime === '' || $version === '' || !preg_match('/^[a-z0-9._-]+$/', $runtime) || !preg_match('/^[A-Za-z0-9._-]+$/', $version)) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Give the link and the name: --runtime-url=<https://…/archive.zip> --name=<runtime>@<version> (--sha256=<hash>) {{nl}}");
        return;
      }
      [$os, $arch] = runtime_target($value('os'), $value('arch'));
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} $runtime@$version for $os $arch: $url {{nl}}");
      [$path, $problem] = runtime_install($runtime, $version, ['url'=> $url, 'sha256'=> $value('sha256')], $os, $arch);
      if (!$path) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} $runtime@$version: $problem {{nl}}");
        return;
      }
      $runtime_kept("$runtime@$version", $path);
    }

    /* RUNTIME LIST: the runtimes Rune knows, and the ones kept in @liberation/ */
    if (Chanter::spell('runtime-list')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runtime List\n");
      Whisper::echo("{{text-secondary}}Known by Rune (--runtime-install=<runtime>@<version>):{{nl}}");
      foreach (runtime_catalog() as $runtime=>$about) {
        Whisper::echo("{{text-secondary}} ◆ {{text-default}}$runtime {{text-secondary}}— $about[note] (e.g. $about[example]){{nl}}");
      }
      Whisper::echo("{{text-secondary}} ◆ {{text-default}}any other {{text-secondary}}— from a link: --runtime-url=<archive url> --name=<runtime>@<version>{{nl}}{{nl}}");

      $record = runtime_record();
      $list = runtime_list();
      Whisper::echo("{{text-secondary}}Kept in @liberation/:{{nl}}");
      foreach ($list as $name) {
        [$runtime, $version] = runtime_name($name);
        $use = $record[$runtime] ?? [];
        $mark = (($use['version'] ?? '') === $version) ? " {{text-success}}in use ({$use['os']} {$use['arch']}){{text-end}}" : '';
        Whisper::echo("{{text-secondary}} ♦ {{text-default}}$name$mark{{nl}}");
      }
      Whisper::echo(count($list) . " runtime(s) in @liberation/{{nl}}");
    }

    /* RUNTIME USE: which kept runtime is the one in use (liberation.json, the launcher, the relic) */
    if (Chanter::spell('runtime-use')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runtime Use\n");
      $name = $value('runtime-use');
      $name = str_contains($name, '@') ? $name : 'php@' . $name;
      [$record, $problem] = runtime_use($name);
      if (!$record) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} $problem {{nl}}");
        return;
      }
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} In use: $name ({$record['os']} {$record['arch']}) {{nl}}");
    }

    /* RUNTIME REMOVE */
    if (Chanter::spell('runtime-remove')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runtime Remove\n");
      $name = $value('runtime-remove');
      if ($name === '') {
        Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Runtime: " . implode(', ', runtime_list()) . "{{nl}}");
        $name = trim((string) Whisper::call('Give us the runtime (<runtime>@<version>): '));
      }
      $name = str_contains($name, '@') ? $name : 'php@' . $name;
      if (runtime_remove($name)) {
        Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Runtime removed: @liberation/$name {{nl}}");
      }else {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} Runtime not found: @liberation/$name {{nl}}");
      }
    }

    /* RUNTIME LAUNCHER: <runite>.bat (Windows) or <runite>.sh next to the runite, running it with the PHP in use */
    if (Chanter::spell('runtime-launcher')) {
      Whisper::clear();
      Whisper::echo("SENTINEL {{color-danger}}::{{color-end}} Runtime Launcher\n");
      [$file, $problem] = runtime_launcher(AETHER_FILE);
      if (!$file) {
        Whisper::echo("{{COLOR-DANGER}}{{ICON-WARNING}} $problem {{nl}}");
        return;
      }
      Whisper::echo("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} Launcher written: " . basename($file) . " {{nl}}");
      Whisper::echo("{{COLOR-SECONDARY}}{{ICON-INFO}} Run it: " . (str_ends_with($file, '.bat') ? basename($file) : './' . basename($file)) . " <cast> {{nl}}");
    }
  }
);
