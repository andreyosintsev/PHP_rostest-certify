<?php
/**
 * index.php
 *
 * The main template file.
 * 
 * @link        http://www.gopiplus.com/
 * @Demo        http://www.gopiplus.com/work/2013/11/11/premium-style-wordpress-theme/
 * 
 * @version     1.6
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */
?>
<?php get_header(); ?>
<div id="content" class="left">
	<!-- start layout content loop -->
	<div id="content-loop" class="layout-content-loop">	
	<?php if ( have_posts() ) : ?>	
		<?php while (have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
		<div class="clear"></div>
			<?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
			<div class="pagination">
			  <div class="newer">
				<?php previous_posts_link(__(' &laquo;&laquo; Newer Entries ', 'gopiplustheme')) ?>
			  </div>
			  <div class="older">
				<?php next_posts_link(__(' Older Entries &raquo;&raquo; ', 'gopiplustheme')) ?>
			  </div>
			  <div class="clear"></div>
			</div>
			<?php } ?>
	<?php else : ?>
		<div id="post" class="loop-entry">
			<h2 class="entry-title"> 
				<?php _e( 'Nothing Found', 'gopiplustheme' ); ?>
			</h2>
			<div class="entry-summary">
				<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'gopiplustheme' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</div>
	<?php endif; ?>	
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>