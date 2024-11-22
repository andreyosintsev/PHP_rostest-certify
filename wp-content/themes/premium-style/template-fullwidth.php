<?php
/*
Template Name: Full Width
*/
?>
<?php get_header(); ?>
<div class="onecolumn">
  <div id="content" class="left">
  <?php get_template_part( 'breadcrumbs', get_post_format() ); ?>
  <?php the_post(); ?>
  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  	<!-- start post entry -->
    <h1 class="entry-title">
      <?php the_title(); ?>
    </h1>
    <div class="entry-meta"> 
		<span class="left">
			<?php _e('by ', 'gopiplustheme'); ?>
			<?php the_author_posts_link(); ?>
			<span class="meta-sep">|</span>
			<?php _e('posted:', 'gopiplustheme'); ?>
			<span class="meta-date">
				<abbr class="published" title="<?php the_time('g:i a'); ?>">
					<?php the_time(get_option('date_format')); ?>
				</abbr>
			</span> 
		</span> 
		<span class="right">
			<span class="entry-comment">
				<a class="link-comments" href="<?php  comments_link(); ?>"><?php comments_number(__('0 Comment','gopiplustheme'),__('1 Comment'),__('% Comments')); ?></a>
			</span>
		</span>
      <div class="clear"></div>
    </div>
    <!-- start post content -->
    <div class="entry entry-content">
      <?php the_content(); ?>
      <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gopiplustheme' ), 'after' => '</div>' ) ); ?>
    </div>
    <!-- start post tags box -->
	<div class="entry-tags">
		<div class="categories">
			<?php the_category(' '); ?>
		</div>
		<div class="tags">
			<?php the_tags('',' '); ?>
		</div>
	</div>
    <div class="clear"></div>
    <!-- start post author box -->
    <div class="entry-author clear">
      <div class="author-avatar"> <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'premiumstyle_author_bio_avatar_size', 60 ) ); ?> </div>
      <div class="author-description">
        <h3><?php the_author(); ?></h3>
        <?php the_author_meta( 'description' ); ?>
        <div class="author-link"> 
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php printf( esc_attr__( 'View all posts by %s', 'gopiplustheme' ), get_the_author() ); ?>">
				<?php _e( 'View all posts by ', 'gopiplustheme' ); ?><?php the_author(); ?>  &raquo;&raquo;
			</a>
		</div>
      </div>
    </div>
	<div class="clear"></div>
	<!-- start related posts box -->
	<div class="entry-bottom">
      <div class="related-posts">
        <?php premiumstyle_related_posts(); ?>
      </div>
	</div>
	<!-- start post nav box -->
	<nav class="nav-single">
      <span class="nav-previous">
      	<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&laquo;&laquo;', 'Previous post link', 'gopiplustheme' ) . '</span> %title' ); ?>
      </span> 
	  <span class="nav-next">
      	<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&raquo;&raquo;', 'Next post link', 'gopiplustheme' ) . '</span>' ); ?>
      </span> 
	</nav>
  </div>
  <div class="clear"></div>
   <!-- start post comment -->
  <?php comments_template( '', true ); ?>
</div>
</div>
<?php get_footer(); ?>
