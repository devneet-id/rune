# CONTIBUTING

Thank you for wanting to contribute!  
Please read this guide before submitting a PR to ensure everything goes smoothly.

---


### How To Contribute
- Fork this repository.
- Make sure you have PHP (8.1 / latest) and Composer (latest) installed.
- Make your changes or fixes directly in your fork.
- Open a Pull Request (PR) from your fork to this repository.
- Follow the coding style used in the repository.
- Do not commit unnecessary or personal files.


---


### Setup Environment
1. Create the main directory locally.
```shell
rune-dev/
├── composer.json
└── cast
```

2. Clone the forked rune repository.
```shell
git clone https://github.com/<USERNAME>/rune
```

3. Prepare the `composer.json` file.
```json
{
  "autoload": {
    "psr-4": {
      "Rune\\": "rune/"
    }
  }
}
```

4. Run `composer dump-autoload`.
```shell
composer dump-autoload
```

5. Prepare the cast.
```php
<?php

require_once __DIR__.'/vendor/autoload.php';

Rune\Ethereal::awakening();
```

6. Summon the rune, then respond with [n].
```shell
php cast
```

7. Prepare the additional rune using the Sentinel.
```php
php cast sentinel --invoke=Forger
php cast sentinel --invoke=Whisper
php cast sentinel --invoke=Cipher
```

8. Enter **Liberation Mode** in the cast file.
```php
require_once __DIR__ . '/vendor/autoload.php';

Rune\Ethereal::liberation();
```

9. Run the `php cast` command again. If it enters liberation mode, you will see **Liberation of Forte** at the entrance.



---

### Contribute to Existing Rune
Before starting, make sure you understand the **Concept Paradigm**, which is:

- Ether _is constant, todo set up rules of this rune_
- Essence _is global variable, todo handling dynamic data while processing_
- Entity _is function, todo create mini process logic to flowing_
- Manifest _is static class, todo easly & fully handle with process logic_
- Phantasm _is class(grimoire), todo notate & track the rune_

Next, create a new feature for the rune **Whisper**.


1. Prepare this codex in the cast, under the section `✦ Main logic lives here`
```php
Chanter::cast(
  arg: 'hello-build',
  execute: function() {
    /* this is Reflection of Whisper */
    
    /*////// Ether [ET] */
      #NOTE: what is?
      define('WHISPER_HELLO', TRUE);
    
    /*////// Essence [ES] */
      #NOTE: what is?
      $GLOBALS['WHISPER_HELLO_MIN'] = 0;
      #NOTE: what is?
      $GLOBALS['WHISPER_HELLO_MAX'] = 5;
    
    /*////// Entity [EN] */
      #NOTE: what is?
      function whisper_hello() {
        // use essence
        global $WHISPER_HELLO_MIN;
        global $WHISPER_HELLO_MAX;

        for ($i=$WHISPER_HELLO_MIN; $i<$WHISPER_HELLO_MAX; $i++) {
          whisper_echo("{{text-success}}hello \n");
        }

        aether_arcane('Whisper.entity.whisper_hello'); // <- make sure this code include
        return true;
      }

    /*////// Manifest [MN] */
    class DevWhisper extends Whisper {
        #NOTE: what is?
        public static function hello() {
          whisper_hello();
        }
      }
    
    /*////// RAW TEST */
      whisper_hello();

    /*////// USE TEST */
      DevWhisper::hello();
  }
);

Chanter::cast(
  arg: 'hello',
  execute: function() {
    Whisper::hello();
  }
);
```
2. Perform the initial test; the second casting will produce an error.
```shell
php cast hello-build
```

Perform the binding (Binding Rune): open the rune folder, find the Whisper directory, place the contents of cast hello-build into their respective sections at the end, and don’t forget to update the #NOTE of each node.

1. Register the Phantasm with the help of the Sentinel.
```shell
php cast sentinel --phantasm-fix-node=Whisper
php cast sentinel --phantasm-fix-note=Whisper
php cast sentinel --phantasm-up=Whisper
```

2. Check the registration in the Grimoire and look for the nodes you added earlier.
```shell
php cast grimoire --rune=Whisper
```

3. Perform the final test to check if everything is working correctly.
```shell
php cast hello
```

After finishing, push the changes to your forked repository, open a PR to the main repository, and also submit the final test as a cast code.
```php
Chanter::cast(
  arg: 'hello',
  execute: function() {
    Whisper::hello();
  }
);
```

It also applies to the flow/logic already existing in the rune. To fix it, the concept is the same: organize the rune’s content into a test cast, add the prefix DEV_ to each node (can use find & replace all in a text editor), then repeat the initial steps.


---


### Contribute new Rune
The concept is simple: check the **@liberation** folder, run `php cast sentinel --create-rune=Ghost`, done. 
(Under Development)


---

### Contribute with Altar
The concept is to maintain the rune using the Altar GUI, detect anomalies in the rune, and report them. 
(Under Development)

---

Thank you for contributing! Follow the steps above carefully, and you’ll help keep devneet/rune growing and improving.