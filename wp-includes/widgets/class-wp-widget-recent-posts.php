<?php
/**
 * Widget API: WP_Widget_Recent_Posts class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Recent Posts widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class WP_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => __( 'Your site&#8217;s most recent Posts.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-posts', __( 'Recent Posts' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		//Отображение сертификатов того же производителя на страничке записи
		
		if (!is_single()) {

			//Отображение последних добавленных сертификатов

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			if ( ! $number ) {
				$number = 5;
			}
			$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

			/**
			 * Filters the arguments for the Recent Posts widget.
			 *
			 * @since 3.4.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @see WP_Query::get_posts()
			 *
			 * @param array $args     An array of arguments used to retrieve the recent posts.
			 * @param array $instance Array of settings for the current widget.
			 */
			$r = new WP_Query( apply_filters( 'widget_posts_args', array(
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			), $instance ) );

		} else {
			//is_single => true

			$manufacturer = getManufacturer(get_post_meta(get_post()->ID, 'param6_manufacturer', true), false);
		 	if (!empty($manufacturer)) {
			
				$title = 'Сертификаты "'.$manufacturer.'"';
				$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
				$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
				if ( ! $number ) {
					$number = 5;
				}
				$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
				$r = new WP_Query( apply_filters( 'widget_posts_args', array(
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'posts_per_page' => $number,
					'meta_query' => array(
						'manufacturer' => array(
				           	'key' => 'param6_manufacturer',
				           	'value' => $manufacturer,
				           	'compare' => 'LIKE'
				        )
				    ),
			    	'orderby' => 'manufacturer',
  					'order' => 'ASC'
				), $instance ) );
			} else {

							//Отображение последних добавленных сертификатов

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			if ( ! $number ) {
				$number = 5;
			}
			$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

			/**
			 * Filters the arguments for the Recent Posts widget.
			 *
			 * @since 3.4.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @see WP_Query::get_posts()
			 *
			 * @param array $args     An array of arguments used to retrieve the recent posts.
			 * @param array $instance Array of settings for the current widget.
			 */
			$r = new WP_Query( apply_filters( 'widget_posts_args', array(
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			), $instance ) );

			}
		}
		
		if (!is_null($r)) {
			if ( ! $r->have_posts() ) {
				return;
			}
		
			echo $args['before_widget'];
		
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
				
			foreach ( $r->posts as $post):
				//setup_postdata($post);
			?>
				<div class="arch_home">
					<div class="arch_thumbnails_home">
						<?php echo thumbnails(false, true, 52, 75, $post); ?>
					</div>
					<div class="arch_description_home">
						<a href="<?php the_permalink($post->ID) ?>"  title="Скачать сертификат соответствия на <?php echo get_the_title($post); ?>">Сертификат соответствия на <?php echo get_the_title($post); ?></a>
					</div>
					<div class="clear"></div>
				</div>
			
			<?php 
			endforeach;
			if (!empty($manufacturer)) {
				echo '<div class="widget-recent-after">
						<div class="manufacturer_go">
						<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Сертификаты соответствия изготовителя '.$manufacturer.'">Другие сертификаты '.$manufacturer.'...</a>
						</div>
						</div>';
			};

			echo $args['after_widget'];
		}
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
	}
}
