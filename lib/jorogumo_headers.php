<?php
class JorogumoHeaders {

  public static function enqueueCSS() {
    wp_enqueue_style('jorogumoStyle', plugins_url( "/Jorogumo/lib/css/jorogumo.css"));
  }

}

?>