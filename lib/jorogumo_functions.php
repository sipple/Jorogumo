<?php


if( ! function_exists('joro_show_feature_posts') ) :
/**
 * Returns a festivity date filter select box based on the festival's start and end dates
 */
function joro_show_feature_posts($search = 'recent', $order = 'date', $numberposts = 3) {
  $posts_results = joro_find_posts($search, $order, $numberposts);
  echo joro_feature_posts($posts_results);
}
endif;

if( ! function_exists('joro_show_list_posts') ) :
/**
 * Returns a festivity date filter select box based on the festival's start and end dates
 */
function joro_show_list_posts($search = 'recent', $order = 'date', $numberposts = 3) {
  $posts_results = joro_find_posts($search, $order, $numberposts);
  echo joro_list_posts($posts_results);
}
endif;

if ( ! function_exists( 'joro_recent_posts_func' ) ) :
/**
 * Shortcode to display feature or list views of recent or random
 * posts. Can pull all, by current post category, or by current post
 * tags.
 */

function joro_func($atts) {
  extract( shortcode_atts( array(
 		'style' => 'feature',
 		'search' => 'recent',
    'order' => 'date',
    'numberposts' => 3,
    'title' => 'Recent Posts'
 	), $atts ) );

  $posts_results = joro_find_posts($search, $order, $numberposts);

  if($style == "list") {
    return joro_list_posts($posts_results);
  }
  else {
    return joro_feature_posts($posts_results, $title);
  }

}

add_shortcode( 'joro', 'joro_func' );

endif;

function joro_find_posts($search_type, $search_order, $numberposts) {
  $args = array(
      'numberposts'     => $numberposts,
      'offset'          => 0,
      'orderby'         => 'post_date',
      'order'           => 'DESC',
      'post_type'       => 'post',
      'post_status'     => 'publish',
      'suppress_filters' => false );

  if ($search_type == 'category') {
    $category_ids = joro_get_post_categories();
    $args = array_merge($args, array( 'category' => implode(',', $category_ids)));
  }
  elseif ($search_type == "tag") {
    $tag_ids = joro_get_post_tags();
    $args = array_merge($args, array( 'tag__in' => implode(',', $tag_ids)));
  }

  if ($search_order == "random") {
    $args = array_merge($args, array( 'orderby' => 'rand' ));
  }
  add_filter( 'posts_where', 'joro_restrict_by_date' );
  $post_results = get_posts($args);
  remove_filter( 'posts_where', 'joro_restrict_by_date' );

  return $post_results;

}

function joro_get_post_categories() {
  $categories = get_the_category($post->ID);
  $category_ids = array();
  foreach ($categories as $category) {
    array_push($category_ids, $category->cat_ID);
  }

  return $category_ids;
}

function joro_get_post_tags() {
  $tags = get_the_tags();
  $tag_ids = array();
  foreach($tags as $tag) {
    array_push($tag_ids, $tag->term_id);
  }
  return $tag_ids;
}

function joro_feature_posts($posts) {

  $feature_html = '<div class="row-fluid"><ul class="thumbnails">%1$s</ul></div>';

  $post_divs = "";

  foreach($posts as $feature_post) {
    $image_url = joro_post_image_url($feature_post->ID);
    $post_div_html = '<li class="span4"><div class="thumbnail"><a class="joro-post-link" href="%1$s"><img class="joro-post-image img-rounded" src="%2$s" /></a><div class="joro-post-title"><a class="joro-post-link" href="%3$s" >%4$s</a><br/>%5$s</div></div></li>';
    $post_divs .= sprintf($post_div_html, get_permalink($feature_post->ID), $image_url, get_permalink($feature_post->ID), $feature_post->post_title, mysql2date('F j, Y', $feature_post->post_date) );

  }

  return sprintf($feature_html, $post_divs);

}

function joro_list_posts($posts) {

  $list_html = '<ul class="joro-post-list">%s</ul>';
  $post_divs = "";

  foreach ($posts as $list_post) {
    $post_div_html = '<li class="joro-listed-post"><span class="joro-post-title"><a class="joro-post-title-link" href="%1$s" title="%2$s">%2$s</a></span></li>';
    $post_divs .= sprintf($post_div_html, get_permalink($list_post->ID), $list_post->post_title, $list_post->post_title);
  }

  return sprintf($list_html, $post_divs);

}

function joro_post_image_url ($post_id)
{
  $args = array(
  'numberposts' => 1,
  'order'=> 'ASC',
  'post_mime_type' => 'image',
  'post_parent' => $post_id,
  'post_status' => null,
  'post_type' => 'attachment'
  );

  $attachments = get_children( $args );
  $image_url = "";
  if ($attachments) {
    foreach($attachments as $attachment) {
      $image_url =  wp_get_attachment_image_src( $attachment->ID, 'thumbnail');

    }
  }
  return $image_url[0];
}

function joro_restrict_by_date($where = '') {
  global $wpdb;
  $earliest_date = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d"), date("Y") - 2));
  return $where .= $wpdb->prepare( " AND post_date >= %s ", $earliest_date);
}

?>