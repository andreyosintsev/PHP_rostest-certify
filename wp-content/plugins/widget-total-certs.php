<?php
/**
Plugin Name: Total Certificates
Description: Total quantity of certificates in the base
Version: 1.1
Last-Modified: 2018/11/28
Author: Andrei Osintsev
License: GPL2 
 
    Copyright 2011 Andrei Osintsev
 
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
        $site_url = site_url();

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
        ?>

        <div class="sidebar-stats">
            <div class="sidebar-stats__title">
                <?php if ($title) echo $args['before_title'] . $title . $args['after_title'];?>
            </div>
            <a class="sidebar-stats__certificates" href="<?php echo $site_url; ?>/reestr-sertifikatov/" title="Реестр сертификатов соответствия">
                <div class="sidebar-stats__certificates-value">
                    <?php echo $total_published;?>
                </div>
                <div class="sidebar-stats__certificates-subtitle">
                    <?php echo $total_subtitle;?>
                </div>
            </a>
            <div class="sidebar-stats__manufacturers-agencies">
                <a class="sidebar-stats__manufacturers" href="<?php echo $site_url; ?>/kompanii/" title="Поиск сертификата по изготовителю">
                    <div class="sidebar-stats__manufacturers-value">
                        <?php echo count(getAllManufacturers()); ?>
                    </div>
                    <div class="sidebar-stats__manufacturers-subtitle">
                        <?php echo $companies_subtitle; ?>
                    </div>
                </a>
                <a class="sidebar-stats__agencies" href="<?php echo $site_url; ?>/organy-po-sertifikacii/" title="Реестр органов по сертификации">
                    <div class="sidebar-stats__agencies-value">
                        <?php echo count(getAllAgenciesNum()); ?>
                    </div>
                    <div class="sidebar-stats__agencies-subtitle">
                        <?php echo $agencies_subtitle; ?>
                    </div>
                </a>
            </div>
        </div>
        <div class="sidebar-countries">
            <div class="sidebar-countries__title">
                Сертификаты по странам
            </div>
            <ul class="sidebar-countries__items">
                <?php
                    $countries = getAllCountries(12);
                    foreach ($countries as $country => $num) {
                ?>
                    <li class="sidebar-countries__item">
                        <span class="flag sidebar-countries__flag">
                            <?php echo getCountryFlag($country); ?>
                        </span>
                        <?php echo $num; ?>
                    </li>
                <?php } ?>
            </ul>
        </div>

<?php }

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