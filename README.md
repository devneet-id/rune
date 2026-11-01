<p align="center">
  <a href="https://packagist.org/packages/devneet/rune"><img src="https://img.shields.io/packagist/dt/devneet/rune" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/devneet/rune"><img src="https://img.shields.io/packagist/v/devneet/rune" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/devneet/rune"><img src="https://img.shields.io/packagist/l/devneet/rune" alt="License"></a>
</p>

<p align="center">
  <a href="https://github.com/devneet-id/rune" target="_blank">
    <img src="https://raw.githubusercontent.com/devneet-id/.github/refs/heads/main/design/mreazenith/rune/v2-card-2.svg" width="300" alt="Logo">
  </a>
</p>

Within Rune's architecture, **Ether** defines truth, **Essence** holds flow, and **Entity** performs behavior - each awakened only when invoked. Every element is crafted to be composable, lazy-loaded, and purpose-driven, making systems naturally scalable and precise. 

This makes Rune inherently lightweight and adaptable, aligning perfectly with projects that evolve from concept, not convention. Whether shaping internal tools, backend systems, or modular workflows, Rune empowers developers to sculpt structure from intent, not instruction.



## Essentials

##### Core System
- Modular Core Structure & Concept (Maintainable)
- Functional Process-Based
- Lazy Loading without Bootstrapping
- Live Documentation replacing Reflection (Phantasm)
- Automatic Minor & Patch Versioning
- PSR-4 Composer Autoloading
- Static Class Service Handler
- Dynamic Output Structure

##### Developer Space
- Stats Information (Main Entrance)
- Dynamic Command List (Main Entrance)
- System Inspection & Read (Grimoire)
- Phased Process Log (Arcane) (Grimoire)
- Execution Result: Timestamp, Memory & Peak Memory
- Fancy Command Line Interface (100% CLI support)
- Save & Load Project as Package (Relic) (Reliquary)
- Project Item as Package (Shard) (Reliquary)
- Share & Find Relics Online (Reliquary & Federation)
- Metadata Storage & Handling `.echoes` (Keeper)
- Zero Trust for Breaking Execution
- Event-Driven & Encapsulation (Chanter)
- Starter Template or Relic (Awakening)
- Command Line UI & Interaction (Chanter & Whisper)
- Text Styling & Reuse (Weaver)
- File/Item & Directory/Repo Handling (Forger)
- Fancy Error Handling (Keeper)
- Process-Focusable (Whisper.drain)
- Multi-Process & Interaction (Specter)
- Open All Possibilities & Potential (Liberation)



## Awakening
Install Rune using Composer:
```bash
composer require devneet/rune
```

Create main file:
```bash
echo "#rune" > cast
```

First invocation awaken Rune from the void:
```php
<?php
/*
 * Act. 0 - The Awakening of Void
 * THE VOID
 *
 * From the silence of nothingness, echoes rise from the void.
 * A journey begins - with runes at your side.
 * 
 * */
require_once __DIR__ . '/vendor/autoload.php';

Rune\Ethereal::awakening();
```

Run the app to begin:
```bash
php cast
```

Explore the system’s using the Grimoire:
```bash
php cast grimoire
```



## Paradigm
At first, humans did not know anything.  
Then how did they start to know things, and learn more about them?  

By looking, writing, and growing, we can finally understand.  

1. Core
- **Rune** = something we don't know yet
- **Ethereal** = a place or thing we don't know

2. Subject
- **Ether** = something that never changes but always has meaning
- **Essence** = something that can change as time goes on
- **Entity** = something that makes other things change
- **Manifest** = a box to connect things so they can work together
- **Phantasm** = a picture in our mind to understand

3. Others
- **Awakening** = starting something, from anywhere
- **Liberation** = being free and making something new
- **Echoes** = sounds from the past
- **Runite** = a rune that becomes a thing
- **Shard** = a small piece of runite
- **Runite** (built-in) = the runites carried by Rune: awakening, rune, grimoire, sentinel, reliquary
- **Grimoire** = a book that feels alive
- **Sentinel** = someone or something that guards
- **Relic** = a runite preserved to travel (`.rune`)
- **Reliquary** = a place where relics are kept
- **Federation** = reliquaries joined together


## Origin Runes
Ten runes live at the origin. Each holds one purpose, and Aether flows through all of them.

| Rune | Purpose |
|---|---|
| **Aether** | The flow beneath everything: paths, stopwatch, memory, arcane, exit and the version footer |
| **Chanter** | The CLI gate: casts (commands), spells (`--options`), echoes (notes) and zero trust |
| **Whisper** | The voice to the user: styled echo, call (input), clear, drain and flash |
| **Weaver** | Text and templates: bind `{{var}}`, bond, wrap and minify |
| **Forger** | Files and folders: trace, scan, fix, move, clone, clean and observe |
| **Cipher** | Secrets: unique id, hash, base64 and the runic cipher |
| **Keeper** | Memory in `.echoes/`: items, shards, arcane and glitch |
| **Crafter** | The builder: item → seed → shard → spark into one distributable file |
| **Specter** | Beyond the main process: cast in another window, soul, seer, observer and devserver |
| **Minister** | Speaks with a familiar (AI spirit) — still in development |

Read any rune from the Grimoire:
```bash
php cast grimoire --runic=Keeper
php cast grimoire --runic-list
```



## Laws of the Rune
- **One rune, one purpose.** A rune is never stretched to do another rune's work.
- **Entity holds the logic.** Entities may call the entities of other runes; this is how runes work together.
- **Manifest is for engraving.** It only forwards to entities, so your runite (`cast`) speaks through `Rune\X\Manifest`.
- **Phantasm is the contract.** It replaces Reflection: version, user, note, mark, link and node are read by the Grimoire.
- **Aether flows everywhere.** Runes are not standalone packages; they awaken together inside one flow.
- **Every change raises the Phantasm version** of the rune that changed.
- **A liberation rune never replaces an origin rune.** Need another version? Create a new rune with its own name (e.g. `MYWeaver`).



## The Journey
1. **Awakening** — `Rune\Ethereal::awakening()` turns an empty `cast` into a runite, from a blank start or a relic of `devneet-id/reliquary`.
2. **Engraving** — write casts in the runite: `Chanter::cast('wind --art', fn() => ..., 'note')`.
3. **Echoes & Arcane** — Keeper remembers in `.echoes/`; Aether records every step as arcane, revealed by `grimoire --arcane`.
4. **Relic** — `php cast reliquary --runite-relic` seals the runite, its echoes and shards into a `.rune` file to share.
5. **Return** — `php cast reliquary --relic-runite` brings a relic back to life in another place (`=<vendor>/<repo>` takes one from the reliquary).

Sentinel guards the runite: `php cast sentinel --runite-insert=Keeper` engraves a rune into it, `--runite-remove` takes it out, `--gitignore` keeps `vendor/` and `.echoes/` out of git.



## Liberation
Liberation opens the gate to create your own runes beside the origin runes.

```bash
php cast sentinel --liberation
```

Sentinel writes `Rune\Ethereal::liberation();` under the `require` line of your runite. Your runes live in `@liberation/` next to it, and Sentinel helps to shape them:
```bash
php cast sentinel --runic-create=Ledger
php cast sentinel --phantasm-repair=Ledger
php cast sentinel --runite-insert=Ledger
```



## Federation
Federation lets you keep your own reliquary (a fork of `devneet-id/reliquary`) and the relics you take from it.

```bash
php cast sentinel --federation
php cast reliquary --initiate --repo=<user>/reliquary
php cast reliquary --discover
php cast reliquary --retrieve=<vendor>/<repo>
```

Relics are kept in `@federation/` next to the runite, and `@federation/federation.json` records what was found. Without federation every relic comes from `devneet-id/reliquary`.



## Versioning
The footer of every cast shows the version of your rune world:
```
Version=1.20.114
```
It is the major version of the core followed by the sum of every Phantasm version (origin runes, plus liberation runes in liberation mode) — so the number grows as the runes grow.



## Contributing

Thank you for considering contributing to the Rune! The contribution guide can be found in the [Rune Contribution](CONTRIBUTING.md).


## Code of Conduct

In order to ensure that the DEVNEET community is welcoming to all, please review and abide by the [Code of Conduct](CODE_OF_CONDUCT.md).


## License

The Rune is open-sourced software licensed under the [MIT license](LICENSE).
