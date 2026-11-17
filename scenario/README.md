# scenario/

Runites that use Rune the way a user would, from easy to complex. Every scenario has its own `test` cast (the **trial**), and together they aim to touch every Phantasm node of the origin runes.

Rune comes from the local `../rune` (through `rune-dev/vendor`). Run each command **inside the scenario folder**. Every runite starts with the `#!/usr/bin/env php` shebang (format rule for `cast` files without `.php`).

| Scenario | What it is | Runes |
|---|---|---|
| `01-greeting` | greet, a wrapped card, flow stats | Aether, Whisper, Weaver, Chanter |
| `02-notes` | notes CRUD kept in `.echoes`, export to a text file | + Forger, Cipher, Keeper |
| `03-files` | a file manager inside `box/`: new, read, rename, copy, delete, backup, restore | Forger, Cipher |
| `04-vault` | secrets sealed with base64 + runic (all 4 variants) | Cipher, Keeper |
| `05-todo` | todo CRUD + an HTML report woven by Weaver, minified, written by Forger | Weaver, Forger, Keeper |
| `06-multicast` | three runites in one folder (`cast`, `ember`, `frost`): relay a text across runites, separate `.echoes` per runite, cast-in-cast chains | Chanter, Keeper |
| `07-nested` | one runite outside, two inside `realm/`: where does each runite keep its `.echoes`, cast from inside vs from the parent folder | Keeper, Forger |
| `09-crafter` | Crafter bundles a folder (html, head, css, js, php, an image, a subfolder) with three recipes while Keeper is awake: only the seed's `LANGUAGE` is read, Forger keeps no shard: every build runs `spark()` then the cast `store`, where the user sets `Keeper::shard()`, `Forger::scan` targets use `/` | Crafter, Forger, Keeper |
| `coverage` | node coverage of all scenarios + runs every trial | Forger, Weaver |

## Run
```bash
cd scenario/01-greeting
php cast                 # main screen with every cast
php cast greet --name=Anwar --loud
php cast test            # trial of this scenario

cd ../coverage
php cast trials          # every trial at once
php cast coverage        # Phantasm nodes used by the scenarios
php cast coverage --rune=Keeper
```

Trials back up and restore their own data (notes, vault, todos, `box/`), so your data stays.

## Trial helper — `trial.php`
| Function | Use |
|---|---|
| `trial('label', $condition)` | one check |
| `trial_cast('greet --name=Anwar')` | run a cast, get its plain output (spaces as `%20`) |
| `trial_capture(fn() => ...)` | run a callable, get its plain Whisper output |
| `trial_safe(fn() => ...)` | true when it ran without a glitch |
| `trial_section('Title')` / `trial_report()` | headings and the summary |

A glitch (exception or PHP warning) fails one check instead of ending the run.

## Status (batch 1)
- Trials: **105/113** (06-multicast 13/13, 07-nested 6/11) — the failures are real findings found by the scenarios:
  - `chanter_cast_has()` always returns false (missing `global $CHANTER_CAST`).
  - `Keeper::echo()` write glitches (`keeper_echo_set` passes one path map to `forger_fix`, which expects a list).
  - `weaver_item($file, [...])` with data glitches ("Array to string conversion"; it calls `weaver_bind` instead of `weaver_bind_multiple`).
  - `07-nested` (6/11): `.echoes` follows the **current folder**, not the runite. `php realm/cast` from the parent writes `.echoes/realm/cast/`; `php cast` inside `realm/` writes `realm/.echoes/cast/` → one runite, two memories (`AETHER_REPO = getcwd()`, `AETHER_FILE = PHP_SELF`).
  - Also seen (no check yet): the glitch screen shows the file under *On Line Number* and the line under *File Location* (labels swapped).
- Coverage: **56/298 nodes (19%)**. Not covered yet: Crafter, Specter, Minister, most ether/essence nodes, arcane & glitch.

Next batches: Crafter (bundle a small site), Specter (soul, seer, cast), Keeper arcane/glitch/shard + artefact round trip, liberation (a rune of our own), then ether/essence checks to reach every node.
