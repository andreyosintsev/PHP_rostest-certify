<?php
/*
Plugin Name: Total Paid Users
Description: Total quantity of paid users
Version: 1.0
Last-Modified: 2022/12/20
Author: Kintaro Oe
License: GPL2 
 
    Copyright 2022 Kintaro Oe
 
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License,
    version 2, as published by the Free Software Foundation. 
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

add_action("widgets_init", function () {
    register_widget("Total_Users");
});

class Total_Users extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => 'Вывод общего количества пользователей',
		);
		parent::__construct( 'total-users', 'Total Users', $widget_ops );
		$this->alt_option_name = 'widget_total_users';
	}

	
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		//Получим общее количество пользователей, и количество активных (оплативших) пользователей

		global $wpdb;

		$total_users = $wpdb->get_results("SELECT COUNT(ID) FROM `wp_paidusers`", ARRAY_N)[0][0];
		$paid_users = $wpdb->get_results("SELECT COUNT(ID) FROM `wp_paidusers` WHERE `paid` = 1", ARRAY_N)[0][0];

		//Заголовок виджета

		$title = $instance['title'];
			if ( ! $title ) {
				$title = 'Пользователи сайта';
			}

		//Подпись к числу постов

		$total_users_subtitle = $instance['total_users_subtitle'];
		$paid_users_subtitle = $instance['paid_users_subtitle'];

		//Рисуем виджет
		echo $args['before_widget'];

		if ($title) echo $args['before_title'] . $title . $args['after_title'];?>
		
		<div class="total_companies_wrapper">
			<div class="total_users"><?php echo $total_users;?></a></div>
			<div class="total_users_subtitle"><?php echo $total_users_subtitle;?></div>
		</div>
		
		<div class="total_companies_wrapper">
			<div class="paid_users"><?php echo $paid_users;?></a></div>
			<div class="paid_users_subtitle"><?php echo $paid_users_subtitle;?></div>
		</div>

		<?php echo $args['after_widget'];
		
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['total_users_subtitle'] = sanitize_text_field( $new_instance['total_users_subtitle'] );
		$instance['paid_users_subtitle'] = sanitize_text_field( $new_instance['paid_users_subtitle'] );
		return $instance;
	}

	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$total_subtitle  = isset( $instance['total_users_subtitle'] ) ? esc_attr( $instance['total_users_subtitle'] ) : '';
		$paid_subtitle   = isset( $instance['paid_users_subtitle'] ) ? esc_attr( $instance['paid_users_subtitle'] ) : '';

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'total_users_subtitle' ); ?>">Подпись к общему количеству пользоваталей</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'total_users_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'total_users_subtitle' ); ?>" type="text" value="<?php echo $total_users_subtitle; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'paid_users_subtitle' ); ?>">Подпись к количеству активных пользователей</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'paid_users_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'paid_users_subtitle' ); ?>" type="text" value="<?php echo $paid_users_subtitle; ?>" /></p>

	<?php
	}
}
?>