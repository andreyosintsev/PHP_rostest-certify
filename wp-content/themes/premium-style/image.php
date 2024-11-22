<?php
/**
 * image.php
 *
 * The template for displaying image.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php get_header(); ?>
<div class="onecolumn">
  <div id="content" class="left">
    <?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="">
			<h1 class="entry-title"> 
				<?php the_title(); ?>
			</h1>
			<div class="entry-meta">
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( 'Published <span class="entry-date"><time class="entry-date" datetime="%1$s" pubdate>%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'gopiplustheme' ),
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_url( wp_get_attachment_url() ),
						$metadata['width'],
						$metadata['height'],
						esc_url( get_permalink( $post->post_parent ) ),
						esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
						get_the_title( $post->post_parent )
					);
				?>
				<?php edit_post_link( __( 'Edit', 'gopiplustheme' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
			<div class="clear"></div>
		</div>
		<!-- start post nav box -->
		<nav class="nav-single-top">
		  <span class="nav-previous">
			<?php previous_image_link( false, __( ' &laquo;&laquo; Previous Image', 'gopiplustheme' ) ); ?>
		  </span> 
		  <span class="nav-next">
			<?php next_image_link( false, __( 'Next Image &raquo;&raquo; ', 'gopiplustheme' ) ); ?>
		  </span> 
		</nav>
		<!-- start content box -->
		<div class="entry-content">
		<div class="entry-attachment">
			<div id="image-attachment-anchor" class="attachment">
				<?php
					/**
					 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
					 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
					 */
					$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
					foreach ( $attachments as $k => $attachment ) 
					{
						if ( $attachment->ID == $post->ID )
						break;
					}
					$k++;
					
					if ( count( $attachments ) > 1 )  // If there is more than 1 attachment in a gallery
					{
						if ( isset( $attachments[ $k ] ) )
						{
							$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID ); // get the URL of the next image attachment
						}
						else
						{	
							$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );  // or get the URL of the first image attachment
						}
					} 
					else 
					{
						$next_attachment_url = wp_get_attachment_url(); // or, if there's only 1 image, get the URL of the image
					}
				?>
				<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
					$attachment_size = apply_filters( 'premiumstyle_attachment_size', array( 1200, 1200 ) ); // Filterable image size.
					echo wp_get_attachment_image( $post->ID, $attachment_size );
				?></a>
			</div>
			<?php if ( ! empty( $post->post_excerpt ) ) : ?>
			<div class="entry-caption">
				<?php the_excerpt(); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gopiplustheme' ), 'after' => '</div>' ) ); ?>
	</div>
    <?php comments_template(); ?>
    <?php endwhile; ?>
  </div>
</div>
<?php get_footer(); ?>