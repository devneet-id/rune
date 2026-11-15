# @liberation — The Liberation of Forte

Hello, and welcome to the liberation of Rune.

This folder opened because your runite calls `Rune\Ethereal::liberation();`.
*Forte* is freedom in ability: your app is no longer bound to the PHP of the machine it lands on.
Liberation keeps the runtimes your app runs on — first of all PHP.

## ✦ What lives here
- `php@<version>/` — a PHP runtime (`php` / `php.exe`, `ext/`, `php.ini`).
  Take one with `php cast sentinel --runtime-install=php@8.3`.
- `liberation.json` — the runtime in use: version, OS, architecture, extensions.

## ✦ First steps
1. `php cast sentinel --runtime-install` — the PHP you are running now (`php@<version>` for another one).
2. `php cast sentinel --runtime-list` — the runtimes kept here.
3. `@liberation/php@<version>/php cast` — run your runite with it.

Windows runtimes come from windows.php.net, Linux and macOS ones from static-php (dl.static-php.dev).

*This file is only a greeting — Rune does not read it.*
