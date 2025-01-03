<?php
/*
Plugin Name: Users History
Description: To show history of records and downloads
Version: 1.0
Author: Kintaro Oe
License: GPL2 
 
    Copyright 2011 Author Name
 
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License,
    version 2, as published by the Free Software Foundation. 
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

add_action("widgets_init", function () {
    register_widget("Users_History");
});

class Users_History extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => 'Вывод истории действий пользователя',
		);
		parent::__construct( 'users-history', 'Users History', $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}

	
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		//Работает только если пользователь залогинен
				
		if (isset($_SESSION['auth'])) {

			//Получим последние записи, просмотренные пользователем, из wpdb

			//логин пользователя
			$user_email = $_SESSION['login'];

			//Заголовок виджета

	
			$title = $instance['title'];
				if ( ! $title ) {
					$title = 'История просмотров';
				}

			//максимальное количество записей
			$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
				if ( ! $number ) {
					$number = 5;
				}

			global $wpdb;
			echo $args['before_widget'];

			if ($title) echo $args['before_title'] . $title . $args['after_title'];

			$post_ids = $wpdb->get_col("SELECT post_id FROM wp_userhistory WHERE user='$user_email' ORDER BY lasttime DESC LIMIT $number");

			if (!$post_ids) return;
		
			
			foreach ($post_ids as $post_id) {
			?>
				<div class="arch_home">
					<div class="arch_thumbnails_home">
						<?php echo getThumbnail(52, 75, $post_id); ?>
					</div>
					<div class="arch_description_home">
						<a href="<?php the_permalink($post_id) ?>"  title="Скачать сертификат соответствия на <?php echo get_the_title($post_id); ?>">Сертификат соответствия на <?php echo get_the_title($post_id); ?></a>
					</div>
					<div class="clear"></div>
				</div>
			<?php }

			echo $args['after_widget'];
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>">Количество записей</label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

	<?php
	}
}
?>