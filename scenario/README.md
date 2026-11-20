# scenario/

Runites that use Rune the way a user would, from easy to complex. Every scenario has its own `test` cast (the **trial**), and together they aim to touch every Phantasm node of the origin runes.

Rune comes from the local `../rune` (through `rune-dev/vendor`). Run a scenario's own casts **inside its folder**; run them all with `php cast scenario` at the root of rune-dev. Every runite starts with the `#!/usr/bin/env php` shebang (format rule for `cast` files without `.php`).

| Scenario | What it is | Runes |
|---|---|---|
| `greet-and-show-flow` | greet, a wrapped card, flow stats | Aether, Whisper, Weaver, Chanter |
| `keep-notes` | notes CRUD kept in `.echoes`, export to a text file | + Forger, Cipher, Keeper |
| `manage-files-in-a-box` | a file manager inside `box/`: new, read, rename, copy, delete, backup, restore | Forger, Cipher |
| `seal-secrets` | secrets sealed with base64 + runic (all 4 variants) | Cipher, Keeper |
| `track-todos-with-report` | todo CRUD + an HTML report woven by Weaver, minified, written by Forger | Weaver, Forger, Keeper |
| `relay-across-runites` | three runites in one folder (`cast`, `ember`, `frost`): relay a text across runites, separate `.echoes` per runite, cast-in-cast chains | Chanter, Keeper |
| `nest-runites` | one runite outside, two inside `realm/`: where does each runite keep its `.echoes`, cast from inside vs from the parent folder | Keeper, Forger |
| `bundle-a-site` | Crafter bundles a folder (html, head, css, js, php, an image, a subfolder) with three recipes while Keeper is awake: only the seed's `LANGUAGE` is read, Forger keeps no shard: every build runs `spark()` then the cast `store`, where the user sets `Keeper::shard()`, `Forger::scan` targets use `/` | Crafter, Forger, Keeper |
| `craft-single-files-minified` | Crafter builds **one file of one language** from a folder of every language — a stylesheet (like exuracss), a script (like exurajs), a php library — with and without `MINIFIED`; the php library is linted and required | Crafter, Weaver, Forger |
| `craft-web-page-by-type` | Crafter bundles **one web page** (head, html, css, js, php) into one php file with each `TYPE` — `plain` (readable), `function` and `class` (sealed in base64, named by the app hash) — then serves it; two sealed bundles side by side, minified inside a sealed page, `ENCRYPTION`, a TYPE without a template | Crafter, Weaver, Forger, Cipher |
| `survive-glitches-and-exit` | how a runite with Keeper awake meets a PHP warning and what it tells the shell: probe runites in `.proof/`, each cast in its own process — a normal end exits 0, `@mkdir` survives without a glitch, an unsilenced warning glitches and exits non-zero | Keeper, Aether |
| `cast-beyond-the-process` | Specter runs a small php job in another process: hidden and non-blocking the runite goes on at once while the job finishes on its own; hidden and blocking it waits; casts are kept in `cast.json` | Specter, Keeper |
| `coverage` | node coverage of all scenarios (a tool, not a scenario) | Forger, Weaver |

## Run
```bash
# every scenario, one by one (from the root of rune-dev)
php cast scenario                     # each in a new terminal (Specter), then read its proof
php cast scenario --here              # all in this terminal (headless, agents)
php cast scenario --only=keep-notes   # one scenario

# one scenario by hand
cd scenario/greet-and-show-flow
php cast                 # main screen with every cast
php cast greet --name=Anwar --loud
php cast test            # trial of this scenario

# coverage
cd scenario/coverage
php cast coverage        # Phantasm nodes used by the scenarios
php cast coverage --rune=Keeper
```

Trials back up and restore their own data (notes, vault, todos, `box/`), so your data stays.

## Proof — `.proof/`
Every scenario keeps the proof of its last trial in `scenario/<name>/.proof/` (git-ignored, like `.echoes/`):

| File | What |
|---|---|
| `report.txt` | every check of the last `php cast test` and the summary |
| `report.json` | the result — `scenario`, `passed`, `total`, `failed`, `rune`, `php`, `time` — read by `php cast scenario` |
| `dist/` … | what the scenario made: Crafter bundles (`bundle-a-site`, `craft-*`), the todo report |

A scenario that makes files writes them with `trial_proof('dist/…')` and empties them with `trial_proof_clean('dist')` at the start of its trial; they stay after the run so they can be opened.

## Trial helper — `trial.php`
| Function | Use |
|---|---|
| `trial('label', $condition)` | one check |
| `trial_cast('greet --name=Anwar')` | run a cast, get its plain output (spaces as `%20`) |
| `trial_capture(fn() => ...)` | run a callable, get its plain Whisper output |
| `trial_safe(fn() => ...)` | true when it ran without a glitch |
| `trial_section('Title')` / `trial_report()` | headings and the summary; `trial_report()` also writes `.proof/report.*` |
| `trial_proof('dist/x')` / `trial_proof_clean('dist')` | a path inside `.proof/` / empty a folder there |

A glitch (exception or PHP warning) fails one check instead of ending the run.

## Status (batch 1)
- Trials: **105/113** (relay-across-runites 13/13, nest-runites 6/11) — the failures are real findings found by the scenarios:
  - `chanter_cast_has()` always returns false (missing `global $CHANTER_CAST`).
  - `Keeper::echo()` write glitches (`keeper_echo_set` passes one path map to `forger_fix`, which expects a list).
  - `weaver_item($file, [...])` with data glitches ("Array to string conversion"; it calls `weaver_bind` instead of `weaver_bind_multiple`).
  - `nest-runites` (6/11): `.echoes` follows the **current folder**, not the runite. `php realm/cast` from the parent writes `.echoes/realm/cast/`; `php cast` inside `realm/` writes `realm/.echoes/cast/` → one runite, two memories (`AETHER_REPO = getcwd()`, `AETHER_FILE = PHP_SELF`).
  - Also seen (no check yet): the glitch screen shows the file under *On Line Number* and the line under *File Location* (labels swapped).
- Coverage: **56/298 nodes (19%)**. Not covered yet: Crafter, Specter, Minister, most ether/essence nodes, arcane & glitch.

Next batches: Crafter (bundle a small site), Specter (soul, seer, cast), Keeper arcane/glitch/shard + artefact round trip, liberation (a rune of our own), then ether/essence checks to reach every node.
