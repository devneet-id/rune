<?php

namespace Rune;

class Ethereal {

  public static $origin = '';

  public static $energy = 0;

  #NOTE: The built-in runites of Rune (awakening, rune, grimoire, sentinel, reliquary).
  public static $runite = __DIR__ . '/@runite/';

  #NOTE: Old name of $runite, kept so older code still finds the built-in runites.
  public static $bindrune = __DIR__ . '/@runite/';

  #NOTE: The folder next to the runite, wherever it is cast from.
  public static function home() {
    $runite = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');
    return ($runite !== false && is_file($runite)) ? dirname($runite) : getcwd();
  }

  #NOTE: Opens a mode folder next to the runite (@liberation/, @federation/) with its README.
  public static function mode( String $folder, String $readme ) {
    $path = self::home() . '/' . $folder . '/';
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
    if (!file_exists($path . 'README.md') && file_exists($readme)) {
      copy($readme, $path . 'README.md');
    }
    return $path;
  }

  #NOTE: Liberation mode: runes of your own in @liberation/ (The Liberation of Forte).
  public static function liberation() {
    if (defined('LIBERATION')) {
      return;
    }

    // rebase autoloader composer
    $loader = new \Composer\Autoload\ClassLoader();

    // @liberation/ lives next to the runite, wherever it is cast from
    self::$origin = self::mode('@liberation', self::$runite . 'sentinel/liberation.md');

    // the runes of @liberation/
    $loader->addPsr4('Rune\\', self::$origin);

    // register after the origin loader (append): a liberation rune never replaces an origin rune
    $loader->register(false);

    // define to system
    define('LIBERATION', self::$origin);
  }

  #NOTE: Federation mode: your own reliquary, relics kept in @federation/ (The Federation of Fides).
  public static function federation() {
    if (defined('FEDERATION')) {
      return;
    }
    define('FEDERATION', self::mode('@federation', self::$runite . 'sentinel/federation.md'));
  }

  #NOTE: Awakening: writes the first runite (a blank template or a relic from the reliquary).
  public static function awakening() {
    require_once self::$runite . 'awakening/cast.php';
  }

  #NOTE: Gives the next bit of energy (1, 2, 4, 8, ...).
  public static function energy() {
    // set bit system
    static $bit = 0;

    // set energy
    self::$energy = $bit < PHP_INT_SIZE * 8 ? (1 << $bit++) : null;
    
    // return energy
    return self::$energy;
  }
  
}
