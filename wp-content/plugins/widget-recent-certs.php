<?php
/**
Plugin Name: Recent Certificates
Description: Newly added certificates in the base
Version: 1.0
Last-Modified: 2024/12/02
Author: Andrei Osintsev
License: GPL2

Copyright 2024 Andrei Osintsev

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License,
version 2, as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 */

add_action("widgets_init", function () {
    register_widget("Recent_Certificates");
});
class Recent_Certificates extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'recent_certificates',
            'description' => 'Вывод новых сертификатов',
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'recent-certificates', 'Recent Certificates', $widget_ops );
        $this->alt_option_name = 'widget_recent_certificates';
    }

    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $site_url = site_url();
        $manufacturer = '';

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

            $r = new WP_Query( apply_filters( 'widget_posts_args', array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
            ), $instance ) );

        } else {
            //is_single == true

            $manufacturer = getCleanName(getCertManufacturer(get_post()->ID));
            if ($manufacturer !== '') {

                $title = 'Сертификаты "'.$manufacturer.'"';

                $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
                $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
                if ( ! $number ) {
                    $number = 5;
                }
                $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
                $r = new WP_Query( apply_filters( 'widget_posts_args', array(
                    'no_found_rows'         => true,
                    'post_status'           => 'publish',
                    'ignore_sticky_posts'   => true,
                    'posts_per_page'        => $number,
                    'meta_query'            => array(
                                                'manufacturer' => array(
                                                    'key'       => 'param6_manufacturer',
                                                    'value'     => $manufacturer,
                                                    'compare'   => 'LIKE'
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

                $r = new WP_Query( apply_filters( 'widget_posts_args', array(
                    'posts_per_page'      => $number,
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ), $instance ) );

            }
        }

        if (!$r->have_posts()) return;

        echo $args['before_widget'];
        ?>

        <div class="sidebar-certificates">
        <?php
            if ($title) {
        ?>
            <div class="sidebar-certificates__title">
                <?php echo $args['before_title'] . $title . $args['after_title']; ?>
            </div>

        <?php } ?>

        <ul class="sidebar-certificates__items">

            <?php
                foreach ($r->posts as $post) {
            ?>
                    <li class="sidebar-certificates__item">
                        <a class="sidebar-certificates__link"
                            href="<?php the_permalink($post->ID) ?>"
                            title="Скачать сертификат соответствия на <?php echo get_the_title($post); ?>"
                        >
                            <div class="sidebar-certificates__thumb-description">
                                <div class="sidebar-certificates__thumb">
                                    <img
                                            class="sidebar-certificates__image"
                                            src="<?php echo getThumbnail(56, 80, $post->ID); ?>"
                                            title="Сертификат на <?php echo get_the_title($post->ID); ?>"
                                            alt="Скачать сертификат на <?php echo get_the_title($post->ID); ?>"
                                    >
                                    <div class="sidebar-certificates__gradient"></div>
                                </div>
                                <div class="sidebar-certificates__description">
                                    <?php echo mb_ucfirst(get_the_title($post)); ?>
                                </div>
                            </div>
                        </a>
                    </li>

            <?php } ?>

        </ul>

        <?php
            if ($manufacturer !== '') {
        ?>

        <div class="sidebar-certificates__more">
            <a class="sidebar-certificates__more-link"
                href="<?php echo getManufacturerLink($manufacturer); ?>"
                title="Сертификаты соответствия изготовителя <?php echo $manufacturer; ?>"
            >
                Другая продукция этого изготовителя
            </a>
        </div>

        <?php } ?>

    </div>

    <?php echo $args['after_widget'];
    }


    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php _e( 'Title:' ); ?>
            </label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text"
                   value="<?php echo $title; ?>"
            />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>">
                <?php _e( 'Number of posts to show:' ); ?>
            </label>
            <input class="tiny-text"
                   id="<?php echo $this->get_field_id( 'number' ); ?>"
                   name="<?php echo $this->get_field_name( 'number' ); ?>"
                   type="number"
                   step="1"
                   min="1"
                   value="<?php echo $number; ?>"
                   size="3"
            />
        </p>
        <p>
            <input class="checkbox"
                   type="checkbox"<?php checked( $show_date ); ?>
                   id="<?php echo $this->get_field_id( 'show_date' ); ?>"
                   name="<?php echo $this->get_field_name( 'show_date' ); ?>"
            />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
                <?php _e( 'Display post date?' ); ?>
            </label>
        </p>
        <?php
    }
}
