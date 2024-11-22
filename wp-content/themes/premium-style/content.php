<?php
/**
 * content.php
 *
 * The template for displaying home content.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<!-- start loop entry -->
<div id="post-<?php the_ID(); ?>" class="loop-entry <?php if ( is_sticky() ) echo "sticky"; ?>">
	<h2 class="entry-title"> 
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'gopiplustheme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
		<?php the_title(); ?>
	</a>
	</h2>
	<div class="entry-summary">
		<div class="excerpt-thumb">
			<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'gopiplustheme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<?php the_post_thumbnail('excerpt-thumbnail', 'class=alignleft'); ?>
			</a>
			<?php endif;?>
		</div>
		<?php the_excerpt(); ?>
		<?php
		$get_the_title = get_the_title();
		if(trim($get_the_title) == "")
		{
			?>
			<div class="notitle">
				<a href="<?php the_permalink(); ?>">View Post</a>
			</div>
			<?php
		}
		?>
	</div>
	<div class="clear"></div>
	<div class="entry-tags"> 
		<div class="categories">
			<?php the_category(' '); ?>
		</div>
		<div class="tags">
			<?php the_tags('',' '); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>