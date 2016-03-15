<?php
// PBS Newshour
// Dont forget to change the function name and title

add_action( 'widgets_init', 'rssleech_newshour_national_register' );
function rssleech_newshour_national_register() {
	register_widget( "RSS_Leech_Newshour_National" );
}

class RSS_Leech_Newshour_National extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'pbs_leech_newshour', // Base ID
			__( "RSS Leech PBS Newshour National", 'rss_leech' ), // Name
			array( 'description' => __( "Grab PBS Newshour National headline feed.", 'rss_leech' ), ) // Args
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

    if ( ! empty($instance['headline-limit']) ) {
      $headline_limit = $instance['headline-limit'];
    }

		$html = rss_cacher( 'http://www.pbs.org/newshour/topic/nation/feed/', $args['widget_name'], $headline_limit, 3600);

		// These items may be different depending on the RSS feed
		$headlines = rssleech_parsexpath( $html, "//item//title" );
		$thumbnails = rssleech_parsexpath( $html, "//img//@src" );
		$links = rssleech_parsexpath( $html, "//item//guid" );

		?>
		<ul class="rss-leech-list">
		  <?php
		  for($x=0; $x < $headline_limit; ++$x) { ?>
		    <a class="rss-leech-link" target="_blank" href="<?php echo $links[$x]; ?>">
					<li>
						<?php
						if ( $thumbnails[$x] ) {
						?>
						<div class="rss-leech-img" style="background-image: url('<?php echo $thumbnails[$x]; ?>')"></div>
						<?php
						}
						?>
						<span class="rss-leech-headline"><?php echo htmlspecialchars($headlines[$x], ENT_QUOTES | ENT_HTML5); ?></span>
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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'RSS Leech', 'rss_leech' );
    $headline_limit = ! empty( $instance['headline-limit'] ) ? $instance['headline-limit'] : '';
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

    <label for="<?php echo $this->get_field_id( 'headline-limit' ); ?>"><?php _e( 'Number of Headlines:' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'headline-limit' ); ?>" name="<?php echo $this->get_field_name( 'headline-limit' ); ?>">
      <?php
      for ( $x = 1; $x <= 20; ++$x ) {
        echo "<option value='$x' " . selected( $instance['headline-limit'], $x, false ) . ">$x</option>";
      }
      ?>
    </select>
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
		$instance['title'] = ( ! empty($new_instance['title']) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['headline-limit'] = ( ! empty($new_instance['headline-limit']) ) ? strip_tags( $new_instance['headline-limit'] ) : '';

    if ( $instance['headline-limit'] < 1 || 20 < $instance['headline-limit'] ) {
      $instance['headline-limit'] = 5;
    }

		return $instance;
	}

}
