<?php

namespace Rune;

class Ethereal {

  public static $origin = '';

  public static $energy = 0;

  public static $bindrune = __DIR__ . '/@bindrune/';

  public static function liberation() {
    // rebase autoloader composer
    $loader = new \Composer\Autoload\ClassLoader();

    // replace the origin: @liberations/ lives next to the runite, wherever it is cast from
    $runite = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');
    self::$origin = (($runite !== false && is_file($runite)) ? dirname($runite) : getcwd()) . '/@liberations/';

    // if not exists repo
    if (!file_exists(self::$origin)) {
      // create repo
      mkdir(self::$origin, 0777, true);
    }
    // if not exits item
    if (!file_exists(self::$origin . '/README.md')) {
      // file_put_contents(self::$origin . '/README.md', file_get_contents(__DIR__ . '/LIBERATION.md'));
      copy(__DIR__ . '/LIBERATION.md', self::$origin . '/README.md');
    }

    // add bindrune
    $loader->addPsr4('Rune\\', self::$origin);

    // register after the origin loader (append): a liberation rune never replaces an origin rune
    $loader->register(false);

    // define to system
    define('LIBERATION', self::$origin);
  }

  public static function awakening() {
    require_once self::$bindrune . '/awakening/cast.php';
  }

  public static function energy() {
    // set bit system
    static $bit = 0;

    // set energy
    self::$energy = $bit < PHP_INT_SIZE * 8 ? (1 << $bit++) : null;
    
    // return energy
    return self::$energy;
  }
  
}