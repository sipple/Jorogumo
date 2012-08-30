<?php
class Jorogumo_Widget extends WP_Widget {

	public function __construct() {
    parent::__construct(
        'jorogumo_widget', // Base ID
      'Jorogumo', // Name
      array( 'description' => __( 'List Recent (or random) Posts', 'text_domain' ), ) // Args
    );
	}

 	public function form( $instance ) {
		// outputs the options form on admin
     if ( isset( $instance[ 'joro-title' ] ) ) {
   			$title = $instance[ 'joro-title' ];
   		}
   		else {
   			$title = __( 'Recent Posts', 'text_domain' );
   		}

     if ( isset( $instance[ 'joro-number' ] ) ) {
   			$numberposts = $instance[ 'joro-number' ];
   		}
   		else {
   			$numberposts = '5';
   		}

   		?>
   		<p>
   		<label for="<?php echo $this->get_field_id( 'joro-title' ); ?>"><?php _e( 'Title?' ); ?></label>
   		<input class="widefat" id="<?php echo $this->get_field_id( 'joro-title' ); ?>" name="<?php echo $this->get_field_name( 'joro-title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
   		</p>
     <p>
     <label for="<?php echo $this->get_field_id( 'joro-number' ); ?>"><?php _e( 'How many posts?' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'joro-number' ); ?>" name="<?php echo $this->get_field_name( 'joro-number' ); ?>" type="text" value="<?php echo esc_attr( $numberposts ); ?>" />
      </p>
       <p>
       <label for="<?php echo $this->get_field_id( 'joro-filter' ); ?>"><?php _e( 'Filter?' ); ?></label>
       <select class="widefat" id="<?php echo $this->get_field_id( 'joro-filter' ); ?>" name="<?php echo $this->get_field_name( 'joro-filter' ); ?>">
          <option value="recent" <?php if ( 'recent' == $instance[ 'joro-filter' ] ) echo 'selected="selected"'; ?>>All Posts</option>
          <option value="category" <?php if ( 'category' == $instance[ 'joro-filter' ] ) echo 'selected="selected"'; ?>>Current Post's Categories</option>
         <option value="tag" <?php if ( 'tag' == $instance[ 'joro-filter' ] ) echo 'selected="selected"'; ?>>Current Post's Tags</option>
       </select>
       </p>
      <p>
      <label for="<?php echo $this->get_field_id( 'joro-search' ); ?>"><?php _e( 'Recent or Random?' ); ?></label>
      <select class="widefat" id="<?php echo $this->get_field_id( 'joro-search' ); ?>" name="<?php echo $this->get_field_name( 'joro-search' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
         <option value="post_date" <?php if ( 'post_date' == $instance[ 'joro-search' ] ) echo 'selected="selected"'; ?>>Recent Posts</option>
         <option value="random" <?php if ( 'random' == $instance[ 'joro-search' ] ) echo 'selected="selected"'; ?>>Random Posts</option>
      </select>
      </p>
   		<?php
   	}

	public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['joro-title'] = strip_tags( $new_instance['joro-title'] );
    $instance['joro-number'] = strip_tags( $new_instance['joro-number'] );
    $instance['joro-search'] = strip_tags( $new_instance['joro-search'] );
    $instance['joro-filter'] = strip_tags( $new_instance['joro-filter'] );


    return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['joro-title'] );
    $number = $instance['joro-number'];
    $order = $instance['joro-search'];
    $search = $instance['joro-filter'];

    $post_results = joro_find_posts($search, $order, $number, 0);


    echo $before_widget;
    if ( ! empty( $title ) )
      echo $before_title . $title . $after_title;
    echo joro_widget_posts($post_results);
    echo $after_widget;
	}

}
register_widget( 'Jorogumo_Widget' );

?>