<?php


if ( ! function_exists( 'joro_recent_posts_func' ) ) :
/**
 * Prints HTML with each of the event's performances
 *
 */

function joro_func($atts) {
  extract( shortcode_atts( array(
 		'style' => 'feature',
 		'search' => 'recent',
    'order' => 'date',
    'numberposts' => 5
 	), $atts ) );
}

add_shortcode( 'joro', 'joro_func' );


function find_posts($search_type, $search_order, $numberposts) {

  $args = array(
      'numberposts'     => $numberposts,
      'offset'          => 0,
      'orderby'         => 'post_date',
      'order'           => 'DESC',
      'post_type'       => 'post',
      'post_status'     => 'publish',
      'suppress_filters' => true );

  if ($search_type == 'category') {
    $category_ids = get_post_categories();
    $args = array_merge($args, array( 'category' => implode(',', $category_ids)));
  }

  if ($search_order == "random") {
    $args = array_merge($args, array( 'orderby' => 'random' ));
  }

  return get_posts($args);

}

function get_post_categories() {
  $categories = get_the_category($post->ID);
  $category_ids = array();
  foreach ($categories as $category) {
    array_push($category_ids, $category->cat_ID);
  }

  return $category_ids;
}

function feature_posts($posts) {

}

function list_posts($posts) {

}

endif;

?>