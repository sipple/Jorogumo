<?php
/*
Plugin Name: Jorogumo
Plugin URI: http://saalonmuyo.com
Description: Tools to keep readers on your site
Version: 0.5
Author: Eric Sipple
Author URI: http://saalonmuyo.com
License: GPL2
*/

/*  Copyright 2012  Eric Sipple  (email : saalon@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

new Jorogumo();

class Jorogumo {

  // set up the initialization hooks for the plugin
  function __construct() {
    require_once "lib/jorogumo_autoloader.php";
    require_once 'lib/jorogumo_functions.php';
    add_action('init', array(__CLASS__, 'enqueueScripts'));
    add_action( 'widgets_init', create_function( '', 'register_widget( "jorogumo_widget" );' ) );
  }

  function enqueueScripts() {
    add_action('wp_print_styles', array(__CLASS__, 'enqueueCSS'));
  }

  public static function enqueueCSS() {
    wp_enqueue_style('jorogumoStyle', plugins_url( "/Jorogumo/lib/css/jorogumo.css"));
    wp_enqueue_style('jorogumoBootstrap', plugins_url( "/Jorogumo/lib/css/bootstrap.css"));
  }

}