<?php
/*
Plugin Name: Total Certificates
Description: Total quantity of certificates in the base
Version: 1.1
Last-Modified: 2018/11/28
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
    register_widget("Total_Certificates");
});

class Total_Certificates extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => 'Вывод общего количества сертификатов в системе',
		);
		parent::__construct( 'total-certificates', 'Total Certificates', $widget_ops );
		$this->alt_option_name = 'widget_total_certificates';
	}

	
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		//Получим общее количество опубликованных записей WP
				
		$total_count = wp_count_posts();
		$total_published = $total_count->publish;

		//Заголовок виджета

		$title = $instance['title'];
			if ( ! $title ) {
				$title = 'История просмотров';
			}

		//Подпись к числу постов

		$total_subtitle = $instance['total_subtitle'];
		$companies_subtitle = $instance['total_companies'];
		$agencies_subtitle = $instance['total_agencies'];

		//Рисуем виджет
		echo $args['before_widget'];

		if ($title) echo $args['before_title'] . $title . $args['after_title'];?>

		<div class="total_certs"><a href="/reestr-sertifikatov/" title="Реестр сертификатов соответствия"><?php echo $total_published;?></a></div>
		<div class="total_certs_subtitle"><a href="/reestr-sertifikatov/" title="Реестр сертификатов соответствия"><?php echo $total_subtitle;?></a></div>
		<div class="total_flags">
            <?php
                $countries = getAllCountries(12);
                foreach ($countries as $country=>$num) {
                    echo '<div class="total_flag">'.getCountry($country).' - '.$num.'<div style="clear: both;"></div></div>';
                };
            ?>
		</div>
		<div class="clear"></div>

		<?php $companies = count(getAllCompanies());?>
		<?php $agencies = count(getAllAgenciesNum());?>

		
		<div class="total_companies_wrapper">
			<div class="total_companies"><a href="/kompanii/" title="Поиск сертификата по изготовителю"><?php echo $companies;?></a></div>
			<div class="total_companies_subtitle"><a href="/kompanii/" title="Поиск сертификата по изготовителю"><?php echo $companies_subtitle;?></a></div>
		</div>
		
		<div class="total_agencies_wrapper">
			<div class="total_agencies"><a href="/organy-po-sertifikacii/" title="Реестр органов по сертификации"><?php echo $agencies;?></a></div>
			<div class="total_agencies_subtitle"><a href="/organy-po-sertifikacii/" title="Реестр органов по сертификации"><?php echo $agencies_subtitle;?></a></div>
		</div>

		<?php echo $args['after_widget'];
		
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['total_subtitle'] = sanitize_text_field( $new_instance['total_subtitle'] );
		$instance['total_flags'] = sanitize_text_field( $new_instance['total_flags'] );
		$instance['total_companies'] = sanitize_text_field( $new_instance['total_companies'] );
		$instance['total_agencies'] = sanitize_text_field( $new_instance['total_agencies'] );
		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$total_subtitle    = isset( $instance['total_subtitle'] ) ? esc_attr( $instance['total_subtitle'] ) : '';
		$total_companies   = isset( $instance['total_companies'] ) ? esc_attr( $instance['total_companies'] ) : '';
		$total_agencies    = isset( $instance['total_agencies'] ) ? esc_attr( $instance['total_agencies'] ) : '';

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'total_subtitle' ); ?>">Подпись к числу постов</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'total_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'total_subtitle' ); ?>" type="text" value="<?php echo $total_subtitle; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'total_companies' ); ?>">Подпись к числу компаний</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'total_companies' ); ?>" name="<?php echo $this->get_field_name( 'total_companies' ); ?>" type="text" value="<?php echo $total_companies; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'total_agencies' ); ?>">Подпись к числу органов по сертификации</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'total_agencies' ); ?>" name="<?php echo $this->get_field_name( 'total_agencies' ); ?>" type="text" value="<?php echo $total_agencies; ?>" /></p>
	<?php
	}
}
?>