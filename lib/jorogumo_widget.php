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
     if ( isset( $instance[ 'title' ] ) ) {
   			$title = $instance[ 'title' ];
   		}
   		else {
   			$title = __( 'New title', 'text_domain' );
   		}
   		?>
   		<p>
   		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
   		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
   		</p>
   		<?php
   	}

	public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = strip_tags( $new_instance['title'] );

    return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
	}

}
register_widget( 'Jorogumo_Widget' );

?>