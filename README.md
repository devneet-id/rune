# DEVNEET · rune-dev

**The workshop of the Rune realm.** Every change to [rune](https://github.com/devneet-id/rune) is proved here before it is committed there.

> Never change Rune without testing it like a user. — [The Workshop](https://github.com/devneet-id/lore/blob/main/realms/rune/lore/acts/4-the-beyond/the-workshop.md)

## Three repos, one flow

| Repo | Role |
|---|---|
| [**rune**](https://github.com/devneet-id/rune) | the product: the only repo that is developed and released |
| **rune-dev** (this repo) | the workshop: runites that use Rune like a user, and their trials |
| [**lore**](https://github.com/devneet-id/lore) | DEVNEET's global knowledge: Rune's lore, reference and chronicle |

`rune` holds only what ships (Law 19). Notes, trials and experiments live here; documentation lives in `lore`.

## Layout

```
your-folder/
├── rune/          clone of devneet-id/rune (the code under test)
└── rune-dev/      this repo
    ├── composer.json   maps Rune\ → ../rune/
    ├── cast            the workshop runite: `php cast scenario` runs every trial
    ├── scenario/       runites per scenario + their trials (the safety net); proof in <name>/.proof/
    └── exploration/    the beyond: evolution, federation, liberation
```

`composer.json` maps `Rune\` to `../rune/`, so every runite here runs **your local copy** of Rune, not the Packagist release.

### `scenario/` — the safety net
One runite per scenario, from easy to complex, each with a `test` cast (its **trial**). Together they aim to touch every entity and manifest of the origin runes, measured as Phantasm node coverage. Rune's development is grounded in these scenarios: when one feature changes, **all** trials run, so a side effect on any other rune is caught. See [scenario/README.md](scenario/README.md).

### `exploration/` — the beyond
For the advanced modes, which unlock folders next to the runite and do not fit an ordinary scenario. See [exploration/README.md](exploration/README.md).

## Setup

`rune/` and `rune-dev/` sit side by side. rune-dev takes Rune from the folder next to it — **never from Packagist** — so every trial tests the code you are changing.

```bash
git clone https://github.com/devneet-id/rune
git clone https://github.com/devneet-id/rune-dev
cd rune-dev
composer dump-autoload
php cast scenario --here
```

- **Use `composer dump-autoload`, not `composer install` or `composer update`.** It only writes `vendor/autoload.php`, mapping `Rune\` to `../rune/` (see `composer.json`). There is nothing to download.
- `composer.json` has **no `require`** and the repo has **no `composer.lock`** on purpose. A `require "devneet/rune"` would install the Packagist release into `vendor/` as a second source for `Rune\`: a class missing from `../rune` would then load silently from the old release, and a trial could pass on code you are not testing.
- Run `composer dump-autoload` again only when `composer.json` changes. If `php cast` fails after a pull, delete `vendor/` and run it again.
- To test **the released package** (what users get), use another folder with its own `composer.json` and `composer require devneet/rune` — see [The Workshop](https://github.com/devneet-id/lore/blob/main/realms/rune/lore/acts/4-the-beyond/the-workshop.md).

## The cycle of a change

1. **rune-dev** — write or extend a scenario until its trial **fails** and shows the problem. A fix without a trial is a guess.
2. **rune** — fix or add the feature. Keep files CRLF, `php -l` every changed PHP file, keep the [Laws](https://github.com/devneet-id/lore/blob/main/realms/rune/lore/acts/4-the-beyond/laws.md).
3. **rune** — raise the Phantasm version of every rune that changed (`php evolute sentinel --phantasm-repair=<Rune>` or `--phantasm-upgrade=<Rune>` from `exploration/`).
4. **rune-dev** — run **every** trial until green, then check coverage:
   ```bash
   php cast scenario            # each scenario in a new terminal; --here for this terminal
   cd scenario/coverage && php cast coverage
   ```
   Each scenario leaves its proof in `scenario/<name>/.proof/` (report + what it made).
5. **lore** — update the reference, [Known Issues](https://github.com/devneet-id/lore/blob/main/realms/rune/lore/acts/4-the-beyond/known-issues.md) and [Versioning](https://github.com/devneet-id/lore/blob/main/realms/rune/lore/acts/4-the-beyond/versioning.md), then add a [chronicle](https://github.com/devneet-id/lore/tree/main/chronicle) entry.

## Rules of the bench

- Trials back up and restore the data they touch; a glitch fails one check instead of ending the run.
- Every runite without `.php` starts with `#!/usr/bin/env php` on line 1 and `<?php` on line 2.
- `.echoes/` (runtime memory) and `.proof/` (the proof of the last trial) are never committed (see `.gitignore`).
- Mode folders created by `exploration/` (`@evolution/`, `@federation/`, `@liberation/`, `relics/`) stay out of git.
