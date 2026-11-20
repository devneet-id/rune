# @liberation — The Liberation of Forte

Hello, and welcome to the liberation of Rune.

This folder opened because your runite calls `Rune\Ethereal::liberation();`.
*Forte* is freedom in ability: your app is no longer bound to what the machine it lands on has installed.
Liberation keeps **portable runtimes** next to your runite — any runtime that runs from its own folder, the way PHP does: PHP, Node, Python… PHP is the first one Rune can find by version.

## ✦ What lives here
- `<runtime>@<version>/` — one portable runtime per folder, e.g. `php@8.3.36/` (`php` / `php.exe`, `ext/`, `php.ini`).
  Take PHP with `php cast sentinel --runtime-install=php@8.3`; any other runtime from a link with `--runtime-url`.
- `liberation.json` — the runtimes in use: one entry each (version, OS, architecture, source…).

## ✦ First steps
1. `php cast sentinel --runtime-install` — the PHP you are running now (`php@<version>` for another one).
2. `php cast sentinel --runtime-list` — the runtimes kept here.
3. `@liberation/php@<version>/php cast` — run your runite with it.

Windows runtimes come from windows.php.net, Linux and macOS ones from static-php (dl.static-php.dev).

*This file is only a greeting — Rune does not read it.*
