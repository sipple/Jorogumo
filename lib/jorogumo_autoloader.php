<?php
  // Hack:
  define('AUTOLOADER_INCLUDE_ROOT', realpath(ABSPATH . "/wp-content/plugins/Festivity/"));

  /**
   * This is an autoloader.  It will load things automatically.  Specifically, it'll find classes
   * saved in the lib directory of the Festivity plugin and load them. This code was stolen with
   * permission from Brennen Bearnes.
   */
  class JorogumoAutoloader {

    /**
     * Do the actual business of autoloading.
     *
     * @param string name of class
     */
    public static function Autoload ($class)
    {
      $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class));

      $name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
      $path = "lib/{$name}.php";

      $full_path = AUTOLOADER_INCLUDE_ROOT . '/' . $path;
      if (is_file($full_path)) include $full_path;
    }
  }

  // Register autoloader
  spl_autoload_register(array('JorogumoAutoloader', 'Autoload'));
