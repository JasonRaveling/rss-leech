<?php
// PBS Newshour
// Dont forget to change the function name and title

add_action( 'widgets_init', 'rssleech_newshour_register' );
function rssleech_newshour_register() {
	register_widget( "RSS_Leech_Newshour" );
}

class RSS_Leech_Newshour extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'pbs_leech_newshour', // Base ID
			__( "Leech PBS Newshour", 'rss_leech' ), // Name
			array( 'description' => __( "Grab PBS Newshour feed.", 'rss_leech' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		// cache this - we're hammering the remote server
		// at each page render otherwise, angering it
		$html = file_get_contents( "http://www.pbs.org/newshour/topic/nation/feed/" );
		$limit = 5;

		$headlines = lptv_parsexpath( $html, "//item//title" );
		$thumbnails = lptv_parsexpath( $html, "//img//@src" );
		$links = lptv_parsexpath( $html, "//item//guid" );

		?>
		<ul class="rss-leech-list">
		  <?php
		  for($x=0; $x <= $limit; $x++) { ?>
		    <a class="rss-leech-link rss-leech-clearfix" target="_blank" href="<?php echo $links[$x]; ?>"><li>
		      <div class="rss-leech-img" style="background-image: url('<?php echo $thumbnails[$x]; ?>')">
		      </div>
		      <span class="rss-leech-headline"><?php echo htmlspecialchars($headlines[$x], ENT_SUBSTITUTE); ?></span>
		    </li>
		  </a>
		  <?php } ?>
		</ul>
		<?php

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'PBS Newshour', 'rss_leech' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

		<?php // Coming soon
		/*<label for="<?php echo $this->get_field_id( 'feed-url' ); ?>"><?php _e( 'Feed URL:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'feed-url' ); ?>" name="<?php echo $this->get_field_name( 'feed-url' ); ?>" type="text" value="<?php echo esc_attr( $feed ); ?>">
		*/?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}
