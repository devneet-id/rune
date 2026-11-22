# DEVNEET · rune — the `dev` branch

**The workshop of the Rune realm.** Every change to Rune is proved here before it is committed. This branch lives in [devneet-id/rune](https://github.com/devneet-id/rune) next to the product, but shares **no history** with it: it never merges into `1.x`.

> Never change Rune without testing it like a user. — [The Workshop](https://github.com/devneet-id/lore/blob/main/realms/rune/acts/4-the-beyond/the-workshop.md)

## One repo, two branches, one flow

| Where | Role |
|---|---|
| [**rune** `1.x`](https://github.com/devneet-id/rune/tree/1.x) | the product: the only branch that is released (Packagist `devneet/rune`) |
| **rune** `dev` (this branch) | the workshop: runites that use Rune like a user, and their trials |
| [**lore**](https://github.com/devneet-id/lore) | DEVNEET's global knowledge: Rune's realm and chronicle |

`1.x` holds only what ships (Law 19). Notes, trials and experiments live here; documentation lives in `lore`.

## Layout

```
rune-dev/              a clone of devneet-id/rune on the dev branch
├── core/              Rune itself: a clone of devneet-id/rune on 1.x or an agent's branch (git-ignored, made by `php cast init`)
├── composer.json      maps Rune\ → core/
├── cast               the workshop runite: init, use, update, scenario
├── scenario/          runites per scenario + their trials (the safety net); proof in <name>/.proof/
└── exploration/       the beyond: evolution, federation, liberation
```

`composer.json` maps `Rune\` to `core/`, so every runite here runs **the Rune in `core/`**, not the Packagist release.

### `scenario/` — the safety net
One runite per scenario, from easy to complex, each with a `test` cast (its **trial**). Together they aim to touch every entity and manifest of the origin runes, measured as Phantasm node coverage. Rune's development is grounded in these scenarios: when one feature changes, **all** trials run, so a side effect on any other rune is caught. See [scenario/README.md](scenario/README.md).

### `exploration/` — the beyond
For the advanced modes, which unlock folders next to the runite and do not fit an ordinary scenario. See [exploration/README.md](exploration/README.md).

## Setup

```bash
git clone --branch dev https://github.com/devneet-id/rune rune-dev
cd rune-dev
php cast init
php cast scenario --here
```

`php cast init` clones Rune into `core/` (on `1.x`) and runs `composer dump-autoload`. It is the only cast that runs before Rune exists, so it is plain PHP. `--branch=<branch>` clones another branch.

| Cast | What it does |
|---|---|
| `php cast init [--branch=<branch>]` | clone Rune into `core/`, write the autoloader |
| `php cast use --branch=<branch>` | switch `core/` to a branch (an agent's); `php cast use` alone goes back to `1.x` |
| `php cast update` | pull this `dev` branch and `core/`, then write the autoloader again |
| `php cast scenario --here` | run every trial (see below) |

**Test an agent's branch** — the agent names its two branches (the fix, on `1.x`; its scenario, on `dev`):

```bash
git switch <agent-dev-branch>
php cast use --branch=<agent-branch>
php cast scenario --here
```

Then go back: `git switch dev`, `php cast use`.

- **`composer dump-autoload`, never `composer install` or `composer update`.** It only writes `vendor/autoload.php`, mapping `Rune\` to `core/`. There is nothing to download.
- `composer.json` has **no `require`** and this branch has **no `composer.lock`** on purpose. A `require "devneet/rune"` would install the Packagist release into `vendor/` as a second source for `Rune\`: a class missing from `core/` would then load silently from the old release, and a trial could pass on code you are not testing.
- If `php cast` fails after a pull, delete `vendor/` and run `php cast update` (or `composer dump-autoload`).
- To test **the released package** (what users get), use another folder with its own `composer.json` and `composer require devneet/rune` — see [The Workshop](https://github.com/devneet-id/lore/blob/main/realms/rune/acts/4-the-beyond/the-workshop.md).

## The cycle of a change

1. **dev** — write or extend a scenario until its trial **fails** and shows the problem. A fix without a trial is a guess.
2. **`core/`** (a branch from `1.x`) — fix or add the feature. Keep files CRLF, `php -l` every changed PHP file, keep the [Laws](https://github.com/devneet-id/lore/blob/main/realms/rune/acts/4-the-beyond/laws.md).
3. **`core/`** — raise the Phantasm version of every rune that changed (`php evolute sentinel --phantasm-repair=<Rune>` or `--phantasm-upgrade=<Rune>` from `exploration/`).
4. **dev** — run **every** trial until green, then check coverage:
   ```bash
   php cast scenario            # each scenario in a new terminal; --here for this terminal
   cd scenario/coverage && php cast coverage
   ```
   Each scenario leaves its proof in `scenario/<name>/.proof/` (report + what it made).
5. **lore** — update the reference, [Known Issues](https://github.com/devneet-id/lore/blob/main/realms/rune/acts/4-the-beyond/known-issues.md) and [Versioning](https://github.com/devneet-id/lore/blob/main/realms/rune/acts/4-the-beyond/versioning.md), then add a [chronicle](https://github.com/devneet-id/lore/tree/main/chronicle) entry.

## Rules of the bench

- Trials back up and restore the data they touch; a glitch fails one check instead of ending the run.
- Every runite without `.php` starts with `#!/usr/bin/env php` on line 1 and `<?php` on line 2.
- `.echoes/` (runtime memory) and `.proof/` (the proof of the last trial) are never committed (see `.gitignore`).
- Mode folders created by `exploration/` (`@evolution/`, `@federation/`, `@liberation/`, `relics/`) stay out of git.
