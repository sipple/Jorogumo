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
    'numberposts' => 3,
    'title' => 'Recent Posts'
 	), $atts ) );

  $posts_results = find_posts($search, $order, $numberposts);

  if($style == "list") {
    return joro_list_posts($posts_results);
  }
  else {
    return joro_feature_posts($posts_results, $title);
  }

}

add_shortcode( 'joro', 'joro_func' );

endif;

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

  return get_posts($args);

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

function joro_feature_posts($posts, $title) {

  $feature_html = '<div class="row-fluid"><h2>%1$s</h2><ul class="thumbnails">%2$s</ul></div>';

  $post_divs = "";

  foreach($posts as $feature_post) {
    $image_url = joro_post_image_url($feature_post->ID);
    $post_div_html = '<li class="span4"><div class="thumbnail"><a class="joro-post-link" href="%1$s"><img class="joro-post-image img-rounded" src="%2$s" /></a><div class="joro-post-title"><a class="joro-post-link" href="%3$s" >%4$s</a><br/>%5$s</div></div></li>';
    $post_divs .= sprintf($post_div_html, get_permalink($feature_post->ID), $image_url, get_permalink($feature_post->ID), $feature_post->post_title, mysql2date('F j, Y', $feature_post->post_date) );

  }

  return sprintf($feature_html,$title, $post_divs);

}

function joro_list_posts($posts) {

  $list_html = '<div class="joro-post-list">%s</div>';
  $post_divs = "";

  foreach ($posts as $list_post) {
    $post_div_html = '<div class="joro-listed-post"><span class="joro-post-title"><a class="joro-post-title-link" href="%1$s" title="%2$s">%2$s</a></span></div>';
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

?>