<?php
/*
Plugin Name: Actual Certificates
Description: A quantity of actual and obsolete certificates in the base
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
    register_widget("Actual_Certificates");
});

class Actual_Certificates extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => 'Вывод количества действующих и просроченных сертификатов в системе',
		);
		parent::__construct( 'actual-certificates', 'Actual Certificates', $widget_ops );
		$this->alt_option_name = 'widget_actual_certificates';
	}

	
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		global $wpdb;

		//Получим сроки действия всех сертификатов в БД
		//Получим все возможные значения param2_validity

		$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param2_validity'");

		//Количество действующих и просроченных сертификатов
		$actual = 0;
		$obsolete = 0;
		
		//Проверим каждый срок на актуальность

		foreach($rec as $r){ 
			if (isActualDates($r, true)) $actual++; else $obsolete++;
		}

		//Заголовок виджета

		$title = $instance['title'];
			if ( ! $title ) {
				$title = 'Действующие/просроченные';
			}

		//Подпись к числу постов

		$total_subtitle = $instance['total_subtitle'];

		//Рисуем виджет
		echo $args['before_widget'];

		if ($title) echo $args['before_title'] . $title . $args['after_title'];?>

		<div class="actual_certs">Действующих: <?php echo $actual;?></div>
		<div class="actual_certs">Просроченных: <?php echo $obsolete;?></div>
		<div class="actual_certs_subtitle"><?php echo $total_subtitle;?></div>

		<?php echo $args['after_widget'];
		
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['actual_subtitle'] = sanitize_text_field( $new_instance['actual_subtitle'] );
		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$actual_subtitle    = isset( $instance['actual_subtitle'] ) ? esc_attr( $instance['actual_subtitle'] ) : '';

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'total_subtitle' ); ?>">Подпись к числу постов</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'actual_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'actual_subtitle' ); ?>" type="text" value="<?php echo $total_subtitle; ?>" /></p>

	<?php
	}
}
?>