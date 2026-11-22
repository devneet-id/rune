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
| `survive-glitches-and-exit` | how a runite with Keeper awake meets a PHP warning and what it tells the shell: probe runites in `.proof/`, each cast in its own process — a normal end exits 0, `@mkdir` survives without a glitch, an unsilenced warning glitches and exits non-zero; `Keeper::tolerate()` passes a level (kept in the arcane) or throws it | Keeper, Aether |
| `cast-beyond-the-process` | Specter runs a small php job in another process: hidden and non-blocking the runite goes on at once while the job finishes on its own; hidden and blocking it waits; casts are kept in `cast.json`; souls (`Specter::soul`) written in one process are read in another | Specter, Keeper |
| `engrave-with-sentinel` | Sentinel writes runes into a probe runite in `.proof/` and takes them out: insert a rune, refuse an unknown one; remove refuses an unknown rune or one not in the runite; a rune with a flow (Keeper) gets `begin()` / `end()` at the `#sentinel-flow-*` markers, an older runite without them gets a warning | Sentinel (runite), Forger |
| `keep-the-contract` | reads the origin runes as files and Phantasms and checks the Laws readers rely on: marks VOID/BETA/EXIST, no legacy hooks, the Grimoire header, composer links, the Liberation words — changes nothing | Aether, Forger |
| `read-spells-and-flags` | how Chanter reads a spell: a bare flag is `true`, `--x=1` is the value `"1"`, `--x=0` is `"0"`, `+` stays `+`; a value is read as given (a `%2F` on the command line stays, `%20` in a cast written in code is a space); `chanter_spell_has()`; a cast-in-cast hands flags and values on unchanged | Chanter |
| `speak-to-machines` | output read by a machine: `$AETHER_FOOTER = false;` drops the footer, `$WHISPER_COLOR = false;` (or `NO_COLOR`) gives plain text; together, a JSON cast prints exactly the JSON | Aether, Whisper |
| `awaken-a-runite` | Awakening writes the blank runite into a fresh folder (answering "n"): it runs, carries the `#sentinel-flow-*` markers, and its `.gitignore` hides `.echoes/` and `vendor/` (sentinel --gitignore through Specter) | Awakening, Sentinel, Specter |
| `evolve-a-rune` | The Evolution of Eidos in a probe runite: `sentinel --runic-create` shapes a rune in `@evolution/` (no legacy `_arise()`), refuses a taken or origin name; insert it, `--phantasm-repair` it, read it in the Grimoire; `--runic-delete` removes the folder and takes it out of the runite | Sentinel (runite), Forger |
| `seal-and-open-a-relic` | a relic round trip between two probe runites: seal one with its shard (`reliquary --runite-relic`, `--runite-inspect`), open it in another folder (`--relic-runite`, asks first, `--yes`); the shard travels, the memory does not; a broken file is refused | Reliquary (runite), Keeper, Forger |
| `read-the-arcane` | the arcane of a run: `arcane.txt`, one copy per run in `arcanes/`, the states of each step, `grimoire --arcane`, and `--zero-trust` writing no step | Keeper, Aether |
| `speak-without-weaver` | Weaver is a complement to Whisper (soft bond): a probe runite without Weaver still speaks in colour with icons and labels (lower and upper case), returns text, prints the footer, and plain text works; a user's own bond (`{{name}}`) needs Weaver | Whisper, Weaver |
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

## Status
On Rune 1.21.139 (devneet-id/rune#31, #29): **22 scenarios, 287/287 checks** (`php cast scenario --here`, Linux). Node coverage **73/299 (24%)** (`php cast coverage` in `scenario/coverage`).

Not covered yet: Minister (needs a familiar: URL + token), The Federation of Fides and The Liberation of Forte (they reach the network: a reliquary on GitHub, a PHP runtime download) — explored by hand in `exploration/`.

History: batch 1 recorded 105/113 and 19% coverage on an older Rune; every finding of it is fixed (devneet-id/rune#38, #40, #42, #44).
