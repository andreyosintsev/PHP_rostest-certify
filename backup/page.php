<?php
/**
 * page.php
 *
 * The template for displaying all pages.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php get_header(); ?>
<div id="content" class="left">
    <?php get_template_part( 'breadcrumbs', get_post_format() ); ?>
    <?php the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h1 class="page-title">
            <?php the_title(); ?>
        </h1>
        <div class="entry entry-content">
            <?php the_content(); ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gopiplustheme' ), 'after' => '</div>' ) ); ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
