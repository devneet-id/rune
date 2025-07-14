# Rune — ᚱᚾ

**Rune** is a lore-driven, modular concept beneath PHP,  
designed not to dictate development — but to respond to it.

Within Rune’s architecture, **Ether** defines truth, **Essence** holds flow, and **Entity** performs behavior — each awakened only when invoked. Every element is crafted to be composable, lazy-loaded, and purpose-driven, making systems naturally scalable and precise.

This makes Rune inherently lightweight and adaptable, aligning perfectly with projects that evolve from concept, not convention. Whether shaping internal tools, backend systems, or modular workflows, Rune empowers developers to sculpt structure from intent, not instruction. ⚡️


## Awakening
Install Rune using Composer:
```bash
composer require devneet/rune dev-main
```
Project structure after installation:
```shell
📦 rune-project/
├── 📁 vendor/           # Composer dependencies (includes Rune)
├── composer.json        # Project metadata & dependencies
└── myapp                # Your custom application / entry point
```
First invocation — awaken Rune from the void:
```php
<?php
/*
 * Act. 0 - Awaken From The Void
 * THE VOID
 *
 * From the silence of nothingness, echoes rise from the void.
 * A journey begins — with runes at your side.
 * 
 * */
require_once __DIR__ . '/vendor/autoload.php';

Rune\Ethereal::awakening();
```
Run the app to begin:
```bash
php myapp
```
Explore the system’s using the Grimoire:
```bash
php myapp grimoire
```

## Evidentiary
native cast use this:
```php
Chanter::cast('evid-native', function () {
	// Generate 1000 dummy users
	$users = [];
	for ($i = 0; $i < 1000; $i++) {
		$users[] = [
			'id'		=> $i,
			'username'	=> "user_$i",
			'email'		=> "user_$i@example.com",
			'score'		=> rand(1, 1000)
		];
	}

	// Print each user using raw echo and color formatting
	foreach ($users as $u) {
		$color = "\033[1;31m";
		$reset = "\033[0m";
		echo "┌" . str_repeat("─", 48) . "\n";
		echo "│ ID       : {$color}" . str_pad($u['id'], 36) . "{$reset}\n";
		echo "│ Username : {$color}" . str_pad($u['username'], 36) . "{$reset}\n";
		echo "│ Email    : {$color}" . str_pad($u['email'], 36) . "{$reset}\n";
		echo "│ Score    : {$color}" . str_pad($u['score'], 36) . "{$reset}\n";
		echo "└" . str_repeat("─", 48) . "\n";
	}
});
```

cast rune handle same output as native use this:
file perform.txt:
```txt
┌────────────────────────────────────────────────
│ ID       : {{text-danger}}{{id}}{{text-end}}               
│ Username : {{text-danger}}{{username}}{{text-end}}   
│ Email    : {{text-danger}}{{email}}{{text-end}}               
│ Score    : {{text-danger}}{{score}}{{text-end}}                                 
└────────────────────────────────────────────────
```
code cast:
```php
Chanter::cast('evid-rune', function () {
	// Generate 1000 dummy users
	$users = [];
	for ($i = 0; $i < 1000; $i++) {
		$users[] = [
			'id'		=> $i,
			'username'	=> "user_$i",
			'email'		=> "user_$i@example.com",
			'score'		=> rand(1, 1000)
		];
	}

	// Load and bind template per user, then output using Whisper
	foreach ($users as $u) {
		$format = Weaver::item(__DIR__.'/perform.txt');
		$format = Weaver::bind($format, [
			'id'		=> $u['id'],
			'username'	=> $u['username'],
			'email'		=> $u['email'],
			'score'		=> $u['score']
		]);
		Whisper::echo($format);
	}
});
```
rune with 2 option, use zero-trust will break rule of arcane and etc:
```shell
php {filename} evid-native
php {filename} evid-rune
php {filename} evid-rune --zero-trust
```

## Covenant
- [x] We never promise a ready-made framework,
but we will never let you start from the void.
- [x] We never expect a single rune to wield impossible power,
but we shape it to be simple, unique, and bound to others in harmony.
- [x] We never aim to become a massive engine that burdens creation,
but we exist to bring a single idea to life — swiftly, cleanly, and with purpose.
- This is the covenant: not to build for you,
but to forge alongside you — from the void to the vision.