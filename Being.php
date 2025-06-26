<?php

namespace Rune;

class Being {

  public static $origin = '';

  public static $energy = 0;

  public static function monarch() {
    // rebase autoloader composer
    $loader = new \Composer\Autoload\ClassLoader();

    // replace the origin
    self::$origin = getcwd() . '/@monarch/';

    // if not exists repo
    if (!file_exists(self::$origin)) {
      // create repo
      mkdir(self::$origin, 0777, true);
    }
    // if not exits item
    if (!file_exists(self::$origin . '/README.md')) {
      file_put_contents(self::$origin . '/README.md', 'Welcome Back monarch.');
    }

    // add bindrune
    $loader->addPsr4('Rune\\', self::$origin);

    // register
    $loader->register(true);

    // define to system
    define('IS_MONARCH', true);
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