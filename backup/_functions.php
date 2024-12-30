<?php
/**
 * functions.php
 *
 * This file loads the theme functions and definitions.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */

/**
 * SimpleImage foreign image resample class
 */
include('simpleimage.php');

/**
 * Sets up the content width value based on the theme's design.
 *
 */
if ( ! isset( $content_width ) )
{
	$content_width = 1000;
}

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Premium Stylesupports.
 */
function premiumstyle_setup() 
{
	//  Translations can be added to the /languages/ directory.
	load_theme_textdomain( 'gopiplustheme', get_template_directory() . '/languages' );
	
	// This theme styles the visual editor to match the theme style.
	add_editor_style();
	
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'gopiplustheme' ) );
	
	// Custom Background
	add_theme_support( 'custom-background', array('default-color' => 'FFFFFF',) );
	
	// This theme uses a custom image size for featured images, displayed on posts and pages.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); 
	// 200 pixels wide by 150 pixels high, hard crop mode
	add_image_size('excerpt-thumbnail', 200, 150, true); 
}
add_action( 'after_setup_theme', 'premiumstyle_setup' );

/**
 * Enqueues scripts and styles for front end.
 *
 */
function premiumstyle_scripts_styles() 
{
	global $wp_styles;
	global $wp_scripts;

	// Adds JavaScript to pages with the comment form to support sites with threaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
	{
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Loads Premium Stylemain stylesheet.
	wp_enqueue_style( 'premiumstyle-style', get_stylesheet_uri() );
	
	wp_enqueue_script('navigation', get_template_directory_uri().'/js/navigation.js', false, '20131110', true);	

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'premiumstyle-ie', get_template_directory_uri() . '/ie.css', array( 'premiumstyle-style' ), '20131110' );
	$wp_styles->add_data( 'premiumstyle-ie', 'conditional', 'lt IE 9' );
	
	wp_enqueue_script('html5', get_template_directory_uri().'/js/html5.js', false, '20131110', false);
	$wp_scripts->add_data( 'html5', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'premiumstyle_scripts_styles' );

/**
 * Creates a formatted title for the website.
 *
 */
function premiumstyle_wp_title( $title, $sep ) 
{
	global $paged, $page;

	// Skip the title for RSS feed.
	if ( is_feed() )
	{
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
	{
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
	{
		$title = "$title $sep " . sprintf( __( 'Page %s', 'gopiplustheme' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'premiumstyle_wp_title', 10, 2 );

/**
 * Sets up the WordPress core custom header arguments and settings.
 *
 */
function premiumstyle_custom_header() 
{
	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'     => '333333',
		'default-image'          => '',
		// Callbacks for styling the header
		'wp-head-callback'    => 'premiumstyle_header_style',
	);

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'premiumstyle_custom_header' );

/**
 * Display related/recent post in the posts footer.
 *
 */
function premiumstyle_related_posts() 
{
	global $post, $wpdb;
	$related_post_found = false;
	$backup = $post;
	$tags = wp_get_post_tags($post->ID);
	$tagIDs = array();
	if ($tags) 
	{
		$tagcount = count($tags);
		for ($i = 0; $i < $tagcount; $i++) 
		{
			$tagIDs[$i] = $tags[$i]->term_id;
		}
		
		$args = array('tag__in' => $tagIDs, 'post__not_in' => array($post->ID), 'showposts' => 5, 'ignore_sticky_posts' => 1);
		$premiumstyle_query = new WP_Query($args);
		if( $premiumstyle_query->have_posts() ) 
		{ 
			$related_post_found = true; 
			?>
				<h3><?php _e('Related Posts','gopiplustheme'); ?></h3>
				<div class="clear"></div>
				<ul>		
				<?php while ($premiumstyle_query->have_posts()) : $premiumstyle_query->the_post(); ?>
					<li><a class="title" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>				
				<?php endwhile; ?>
				</ul>		
			<?php 
		}
	}
	
	// Show recent posts if no related found in database.
	if(!$related_post_found)
	{ 
		?>
		<h3><?php _e('Recent Posts','gopiplustheme'); ?></h3>
		<div class="clear"></div>		
		<ul>
		<?php
			$args = array( 'posts_per_page' => 5, 'orderby'=> 'post_date', 'order' => 'DESC' );
			$posts = get_posts( $args );
			foreach($posts as $post) 
			{ 
				?>
				<li>
					<a class="title" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
				</li>
				<?php 
			} 
		?>
		</ul>
		<?php 
	}
	wp_reset_postdata();
}

/**
 * Displays navigation to next/previous set of posts when applicable.
 *
 */
function premiumstyle_content_nav( $html_id ) 
{
	global $wp_query;
	$html_id = esc_attr( $html_id );
	if ( $wp_query->max_num_pages > 1 )
	{
		?>
			<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
				<div class="nav-previous alignleft">
					<?php next_posts_link( __( '<span class="meta-nav">&laquo;&laquo;</span> Older posts', 'gopiplustheme' ) ); ?>
				</div>
				<div class="nav-next alignright">
					<?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;&raquo;</span>', 'gopiplustheme' ) ); ?>
				</div>
			</nav>
		<?php 
	}
}

/**
 * Register widgetized area and update sidebar with default widgets
 *
 */
function premiumstyle_widgets_init() 
{
	register_sidebar( array (
		'name' 			=> __( 'Sidebar', 'gopiplustheme' ),
		'id' 			=> 'sidebar',
		'description' 	=> __( 'Sidebar', 'gopiplustheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "<div class='clear'></div></div>",
		'before_title' 	=> '<p class="widget-title">',
		'after_title' 	=> '</p>',
	) );
}
add_action( 'widgets_init', 'premiumstyle_widgets_init' );

/**
 * Adding options page under Appearance menu 
 *
 */
function premiumstyle_theme_menu() 
{  
	add_theme_page( 'Premium Style', 'Premium Style', 'edit_theme_options', 'premiumstyle', 'premiumstyle_display');  
} 
add_action( 'admin_menu', 'premiumstyle_theme_menu' ); 

/**
 * Register widgetized area and update sidebar with default widgets
 *
 */
if (!function_exists("premiumstyle_custom_comment")) 
{
	function premiumstyle_custom_comment($comment, $args, $depth) 
	{
	   $GLOBALS['comment'] = $comment; 
	   ?>       
		<li <?php comment_class(); ?>>
		<a name="comment-<?php comment_ID() ?>"></a>
		<div id="li-comment-<?php comment_ID() ?>" class="comment-container">
			<div class="comment-head">    
				<?php if(get_comment_type() == "comment"){ ?>
					<div class="avatar"><?php premiumstyle_commenter_avatar($args) ?></div>
				<?php } ?>        
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div> 	                          	
			</div>
			<div class="comment-entry"  id="comment-<?php comment_ID(); ?>">
				<span class="arrow"></span>
				<div class="comment-info">
					<div class="left">
						<span class="name"><?php premiumstyle_commenter_link() ?></span>
					</div>
					<div class="right">        
						<span class="date">
							<?php echo get_comment_date(get_option( 'date_format' )) ?> 
							<?php _e('at', 'gopiplustheme'); ?> 
							<?php echo get_comment_time(get_option( 'time_format' )); ?>
						</span>
						<span class="perma">
							<a href="<?php echo get_comment_link(); ?>" title="<?php _e('Direct link to this comment', 'gopiplustheme'); ?>">#</a>
						</span>
						<span class="edit"><?php edit_comment_link(__('Edit', 'gopiplustheme'), '', ''); ?></span>
					</div>
					<div class="clear"></div> 
				</div>	
				<?php comment_text() ?> 
				<?php if ($comment->comment_approved == '0') { ?>
					<p class='unapproved'><?php _e('Your comment is awaiting moderation.', 'gopiplustheme'); ?></p>
				<?php } ?>					
			</div>
		</div>		
	<?php 
	}
}

/**
 * Display pingbacks and trackbacks comment
 *
 */	
function premiumstyle_list_pings($comment, $args, $depth) 
{
	$GLOBALS['comment'] = $comment; 
	?>
	<li id="comment-<?php comment_ID(); ?>">
		<span class="author"><?php comment_author_link(); ?></span> - 
		<span class="date"><?php echo get_comment_date(get_option( 'date_format' )) ?></span>
	<span class="pingcontent"><?php comment_text() ?></span>
	<?php 
} 

/**
 * Display commenter link if link available.
 *
 */	
function premiumstyle_commenter_link() 
{
	$commenter = get_comment_author_link();
	echo $commenter;
}

/**
 * Display commenter avatar in the comment screen.
 *
 */
function premiumstyle_commenter_avatar($args) 
{
	$email 	= get_comment_author_email();
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( "$email",  $args['avatar_size']) );
	echo $avatar;
}

/**
 * Style the header text displayed on the blog.
 *
 */
function premiumstyle_header_style() 
{
	$text_color = get_header_textcolor();

	// If no custom options for text are set.
	if ( $text_color == get_theme_support( 'custom-header', 'default-text-color' ) )
	{
		return;
	}
	// If we get this far, we have custom styles.
	?>
	<style type="text/css" id="premiumstyle-header-css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px 1px 1px 1px); /* IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text, use that.
		else :
	?>
		.site-header h1 a,
		.site-header h2 {
			color: #<?php echo $text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}

/**
 * Premium Style customizer begins
 *
 */
function premiumstyle_customizer( $wp_customize ) 
{
	// Theme customizer text area control
	class PremiumStyle_WP_Theme_Textarea_Control extends WP_Customize_Control 
	{
		public $type = 'textarea';
		public function render_content() 
		{
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}
	
	// Theme customizer text box control
	class PremiumStyle_WP_Theme_Textbox_control extends WP_Customize_Control 
	{
		public $type = 'textarea';
		public function render_content() 
		{
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}
	
	// Social text area customizer
	class PremiumStyle_WP_Theme_Social_control extends WP_Customize_Control 
	{
		public $type = 'textarea';
		public function render_content() 
		{
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="1" style="width:100%;" <?php $this->link(); ?>><?php echo esc_url( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}
	
	// Start upload site logo section
    $wp_customize->add_section( 'premiumstyle_sitelogo_section' , array(
    		'title'       	=> __( 'Logo', 'gopiplustheme' ),
    		'priority'    	=> 10,
    		'description' 	=> 'Upload a logo to replace the default site name and description in the header.',) );
	
	$wp_customize->add_setting( 'premiumstyle_sitelogo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'premiumstyle_sitelogo', array(
			'label'    		=> __( 'Logo', 'gopiplustheme' ),
			'section'  		=> 'premiumstyle_sitelogo_section',
			'settings' 		=> 'premiumstyle_sitelogo',) ) );
	// End upload site logo section
				
	// Start social icons link section
	$wp_customize->add_section('premiumstyle_social_sec' , array(
			'title' 		=> __('Social Icons','gopiplustheme'),
			'priority'  	=> 210,));

	$wp_customize->add_setting('twitter_url', array(
			'default' => 'http://www.twitter.com/', 
			'sanitize_callback' => 'premiumstyle_sanitize',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Social_control($wp_customize, 'twitter_url', array(
			'label' 		=> 'Twitter url',
			'section' 		=> 'premiumstyle_social_sec',
			'settings' 		=> 'twitter_url',)));
			
	$wp_customize->add_setting('premiumstyle_social_activate');
	$wp_customize->add_control('premiumstyle_social_activate', array(
			'type' 			=> 'checkbox', 
			'label' 		=> 'Disable all social icons', 
			'section' 		=> 'premiumstyle_social_sec',));

	$wp_customize->add_setting('facebook_url', array(
			'default' 		=> 'http://www.facebook.com/',
			'sanitize_callback' => 'premiumstyle_sanitize',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Social_control($wp_customize, 'facebook_url', array(
			'label' 		=> 'Facebook url',
			'section' 		=> 'premiumstyle_social_sec',
			'settings' 		=> 'facebook_url',)));

	$wp_customize->add_setting('googleplus_url', array(
			'default' 		=> 'http://plus.google.com/',
			'sanitize_callback' => 'premiumstyle_sanitize',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Social_control($wp_customize, 'googleplus_url', array(
			'label' 		=> 'Google plus url',
			'section' 		=> 'premiumstyle_social_sec',
			'settings' 		=> 'googleplus_url',)));

	$wp_customize->add_setting('youtube_url', array(
			'default' 		=> 'http://www.youtube.com/',
			'sanitize_callback' => 'premiumstyle_sanitize',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Social_control($wp_customize, 'youtube_url', array(
			'label' 		=> 'Youtube url',
			'section' 		=> 'premiumstyle_social_sec', 
			'settings' 		=> 'youtube_url',)));

	$wp_customize->add_setting('rss_url', array(
			'default' 		=> '',
			'sanitize_callback' => 'premiumstyle_sanitize',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Social_control($wp_customize, 'rss_url', array(
			'label' 		=> 'Rss url',
			'section' 		=> 'premiumstyle_social_sec',
			'settings' 		=> 'rss_url',)));
	// End social icons link section
		
	// Start related & author box
	$wp_customize->add_section('premiumstyle_infobox_sec' , array(
			'title' 		=> __('Display Box Setting','gopiplustheme'),
			'priority'    	=> 230,));
	$wp_customize->add_setting('premiumstyle_related_box');
	$wp_customize->add_control('premiumstyle_related_box',array(
			'type' 			=> 'checkbox', 
			'label' 		=> 'Hide related posts box on your posts and pages.',
			'section'		=> 'premiumstyle_infobox_sec',));
	
	$wp_customize->add_setting('premiumstyle_author_box');
	$wp_customize->add_control('premiumstyle_author_box',array(
			'type' 			=> 'checkbox', 
			'label' 		=> 'Hide author information box on your posts and pages.',
			'section' 		=> 'premiumstyle_infobox_sec',));
			
	$wp_customize->add_setting('premiumstyle_thumbnail_box');
	$wp_customize->add_control('premiumstyle_thumbnail_box',array(
			'type' 			=> 'checkbox', 
			'label' 		=> 'Hide thumbnail image on your single view posts and pages.',
			'section' 		=> 'premiumstyle_infobox_sec',));
	// End related & author box
	
	// Start theme footer text
	$wp_customize->add_section('premiumstyle_footer_sec' , array(
			'title' 		=> __('Footer Text','gopiplustheme'),
			'priority'    	=> 240,));
	$wp_customize->add_setting('premiumstyle_footer_l', array(
			'default' 		=> 'Copyright &copy; 2013',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Textbox_control($wp_customize, 'premiumstyle_footer_l', array(
			'label'			=> 'Footer Left',
			'section' 		=> 'premiumstyle_footer_sec',
			'settings' 		=> 'premiumstyle_footer_l',)));
	
	$wp_customize->add_setting('premiumstyle_footer_r', array(
			'default' 		=> 'All rights reserved',));
	$wp_customize->add_control(new PremiumStyle_WP_Theme_Textbox_control($wp_customize, 'premiumstyle_footer_r', array(
			'label' 		=> 'Footer Right',
			'section' 		=> 'premiumstyle_footer_sec',
			'settings' 		=> 'premiumstyle_footer_r',)));
	// End theme footer text
}
add_action('customize_register', 'premiumstyle_customizer');

/**
 * Premium Style sanitize URL, Now Its safe to use in database queries
 *
 */
function premiumstyle_sanitize( $value ) 
{
    $response = esc_url_raw( $value );
    return $response;
}

/**
 * Premium Style admin tips 
 *
 */
function premiumstyle_display() 
{
	define('premiumstyle_link', 'http://www.gopiplus.com/work/2013/11/11/premium-style-wordpress-theme/');
	define('premiumstyle_docs', 'http://www.gopiplus.com/work/2013/11/12/premium-style-wordpress-theme-documentation/');
	?>
	<div class="wrap">
	  <div id="icon-themes" class="icon32"></div>
	  <h2><?php _e( 'Premium Style WordPress Theme', 'gopiplustheme' ); ?></h2>
	  <div class="tool-box">
		<h3 style="color:#009933"><?php _e( 'Thank You for Selecting Premium Style Theme From', 'gopiplustheme' ); ?>
		<a style="color:#009933;text-decoration:none;" href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank">
		<?php _e( 'gopiplus.com', 'gopiplustheme' ); ?></a></h3>
		<h3><?php _e( 'Theme configuration', 'gopiplustheme' ); ?></h3>
			<?php _e( 'Please click customize link to configure your theme.', 'gopiplustheme' ); ?>
		<h3><?php _e( 'Features of this theme', 'gopiplustheme' ); ?></h3>
		<ol>
		  <li><?php _e( 'Free theme', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Highly customizable', 'gopiplustheme' ); ?></li>
		  <li><?php _e( '100% Responsive', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Valid XHTML5 + CSS', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Firefox, IE8+, Chrome and Safari compatible', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'WP 3.6+ compatible and Tested up tp 3.8', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Blog style structure', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Social Icon settings', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Option to enable/disable Author Info Box', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Breadcrumbs links', 'gopiplustheme' ); ?></li>
		  <li><?php _e( 'Free 24x5 email support', 'gopiplustheme' ); ?></li>
		</ol>
		<h3><?php _e( 'Frequently asked questions', 'gopiplustheme' ); ?></h3>
		<ol>
		  <li><a href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank"><?php _e( 'How do I install the theme onto my wordpress blog?', 'gopiplustheme' ); ?></a></li>
		  <li><a href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank"><?php _e( 'How to setup Featured image for post?', 'gopiplustheme' ); ?></a></li>
		  <li><a href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank"><?php _e( 'How to Disable and Enable home page slider?', 'gopiplustheme' ); ?></a></li>
		  <li><a href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank"><?php _e( 'How to configure Social Icon in the theme?', 'gopiplustheme' ); ?></a></li>
		  <li><a href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank"><?php _e( 'How to add favicon?', 'gopiplustheme' ); ?></a></li>
		  <li><a href="<?php _e( premiumstyle_link, 'gopiplustheme' ); ?>" target="_blank"><?php _e( 'How to Enable and Disable AuthorInfo/Related Box in the single view post?', 'gopiplustheme' ); ?></a>
		  </li>
		</ol>
		<h3><?php _e( 'Theme documentation', 'gopiplustheme' ); ?></h3>
		<ol><li><a href="<?php _e( premiumstyle_docs, 'gopiplustheme' ); ?>" target="_blank"><?php _e( premiumstyle_docs, 'gopiplustheme' ); ?></a></li></ol>
	  </div>
	</div>
	<?php
}
?>
<?php
function getSpecs() {
	global $post;
	$metadata = get_post_custom($post->ID);
	
	$number = 				$metadata['param1_number'][0];
	$validity = 			$metadata['param2_validity'][0];
	$certification_agency = $metadata['param3_certification_agency'][0];
	$product = 				$metadata['param4_product'][0];
	$complies_with =		$metadata['param5_complies_with'][0];
	$manufacturer =			$metadata['param6_manufacturer'][0];
	$issued = 				$metadata['param7_issued'][0];
	$on_the_basis = 		$metadata['param8_on_the_basis'][0];
	$add_info = 			$metadata['param9_add_info'][0];
	$declarant =			$metadata['parama_declarant'][0];
	$okp = 					get_the_category($post->ID);
	

	$out='';

	/*
	$out.='<div style="margin: 10px 0;">'.get_or_generate_desc ($post->ID).'</div>'."\r\n";
	$out.='<div class="clear"></div>'."\r\n";
	*/

	if ($number != "") {
		$out.='<div class="td_frst"><b>№:</b></div>'."\r\n".'<div class="td_sec"><span itemprop="productID">'.$number.'</span>'.getCountry($number).'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($validity!="") {
		$out.='<div class="td_frst"><b>Срок действия:</b></div>'."\r\n";
        '<div class="td_green">'.$validity.isActualDates($validity).'</div>'."\r\n";
        if (isActualDates($validity, true))	$out.='<div class="td_sec">'.$validity.isActualDates($validity).'</div>'."\r\n"; else 
        { 
            $out.='<div class="td_green">'.$validity.isActualDates($validity).'<br/>'."\r\n";
            //$out.='<span style="color: #41a641;">Можно скачать бесплатно без регистрации</span>'."\r\n";
            $out.='</div>'."\r\n";
        }
        $out.='<div class="clear"></div>';

	}
	if ($certification_agency!="") {
		$out.='<div class="td_frst"><b>Орган по сертификации:</b></div>'."\r\n".'<div class="td_sec">'.getCertAgencyButton($certification_agency).'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($declarant!="") {
		$out.='<div class="td_frst"><b>Заявитель/декларант:</b></div>'."\r\n".'<div class="td_sec">'.$declarant.'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($product!="") {
		$out.='<div class="td_frst"><b>Продукция (услуга, работа):</b></div>'."\r\n".'<div itemprop="description" class="td_sec"><div class="bolden">'.$product.'</div></div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($okp!="") {
		$out.='<div class="td_frst"><b>Код '.getCategoryType($post->ID).':</b></div>'."\r\n".'<div class="td_sec">'.getCategoryTree('').'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($complies_with!="") {
		$out.='<div class="td_frst"><b>Соответствует требованиям:</b></div>'."\r\n".'<div class="td_sec">'.norm_highlight_block($complies_with).'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($manufacturer!="") {
		$out.='<div class="td_frst"><b>Изготовитель:</b></div>'."\r\n".'<div class="td_sec">'.getManufacturer($manufacturer).'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($issued!="") {
		$out.='<div class="td_frst"><b>Сертификат выдан:</b></div>'."\r\n".'<div class="td_sec">'.$issued.'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($on_the_basis!="") {
		$out.='<div class="td_frst"><b>На основании:</b></div>'."\r\n".'<div class="td_sec">'.$on_the_basis.'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}
	if ($add_info!="") {
		$out.='<div class="td_frst"><b>Дополнительная информация:</b></div>'."\r\n".'<div class="td_sec">'.$add_info.'</div>'."\r\n";
		$out.='<div class="clear"></div>'."\r\n";
	}

	return ($out);
}
?>
<?php
function product(){
	global $post;
	$product = get_post_meta($post->ID, 'param4_product', true);
	return $product;
}
?>
<?php function thumbnail_home($w, $h, $post){
	$title = get_the_title($post->ID);
	
	$out = "";

	$args = 'w='.$w.'&h='.$h.'&crop=false';
	$kama_thumb = kama_thumb_src($args, site_url() . '/thumbs/'. get_thmb($post));
    echo('<!-- KAMA THUMB ' . site_url() . '/thumbs/'. get_thmb($post) . '-->');
	
	$out = $out . '<img src="'.$kama_thumb.'" title="Сертификат на '. $title .'" alt="'. $title .'">';
	$out .= '<div class="shadow_img"></div>';
	return ($out);
}
?>
<?php function thumbnails($down_link = true, $permalink = false, $w, $h, $post, $actual_emph = false){

	$link = get_post_meta($post->ID, "img_download_link", $single = true);
	$link2 = get_post_meta($post->ID, "img_download_link2", $single = true);
	$title = get_the_title($post->ID);
	$is_actual = isActualDates(get_post_meta($post->ID, "param2_validity", true), true);
	if (!$is_actual && $actual_emph) $actual_style='style="-webkit-filter: grayscale(100%); -moz-filter: grayscale(100%); -ms-filter: grayscale(100%); -o-filter: grayscale(100%); filter: grayscale(100%); filter: gray;"'; else $actual_style='';
		
	$out = "";
	
	if ($link!="") {
		if ($link2!="") $out .= '<div class="left_img">'; else $out .= '<div class="center_img">';
		
		if ($permalink) $out.='<a href="'.get_permalink($post).'" title="'.$title.' подробное описание">';
        echo '<!--SITE URL: '. site_url() .'-->';
		if (!isset($w)) $out .= '<img itemprop="image" src="'. site_url() .'/thumbs/'. get_thmb($post).'" title="Сертификат на '. $title .'" alt="Скачать сертификат на '.$title.'" />'; else {
			$args = 'w='.$w.'&h='.$h.'&crop=false';
			$kama_thumb = kama_thumb_src($args, site_url() . '/thumbs/'. get_thmb($post));
			$out .= '<img src="'.$kama_thumb.'" title="Сертификат на '. $title .'" alt="Скачать сертификат на '.$title.'" '.$actual_style.'/>';
		}
		if (!$permalink) {
			$out .= '<div class="shadow_img"></div>';
		}
		
		if ($permalink) $out.='</a>'; 
		if ($down_link) {
			$out .= '<div class="download_link">';
			$out .= '<a href="#" title="Скачать сертификат на '.$title.'" target="_blank" rel="nofollow" onclick="yaCounter32820367.reachGoal(\'dowload-click\'); return true;">Скачать сертификат</a>';
			$out .= '</div>';
			$print_link = mb_substr(get_post_meta($post->ID, "img_thmb", $single = true) ,5);
			$out .= '<div class="print_button" onclick="javascript:CallPrint(\''.$print_link.'\')"><img src="/imgs/zoomin.png" alt="Увеличение и печать сертификата" title="Увеличение и печать сертификата"/></div>';
		}
		$out .= '</div>';
	}

	if ($link2!="") {
		$out .= '<div class="right_img">';
		
		if ($permalink) $out.='<a href="'.get_permalink($post).'" title="'.$title.' подробное описание">';

		if (!isset($w)) $out .= '<img src="'. site_url() .'/thumbs/'. get_thmb2($post).'" title="Приложение к сертификату на '. $title .'" alt="Скачать приложение к сертификату на '.$title.'" />'; else {
			$args = 'w='.$w.'&h='.$h.'&crop=false';
			$kama_thumb = kama_thumb_src($args, site_url() .'/thumbs/'. get_thmb2($post));
			$out .= '<img src="'.$kama_thumb.'" title="Приложение к сертификату на '. $title .'" alt="Скачать приложение к сертификату на '.$title.'" '.$actual_style.' />';
		}
		if (!$permalink) {
			$out .= '<div class="shadow_img"></div>';
		}		

		if ($permalink) $out.='</a>'; 	
		if ($down_link) {
			$out .= '<div class="download_link">';
			$out .='<a href="#" title="Скачать приложение к сертификату на '.$title.'" target="_blank" onclick="ym(32820367,\'reachGoal\',\'appendix-click\'); return true;" rel="nofollow">Скачать приложение</a>';
			$out .= '</div>';
			$print_link2 = mb_substr(get_post_meta($post->ID, "img_thmb2", $single = true) ,5);
			$out .= '<div class="print_button" onclick="javascript:CallPrint(\''.$print_link2.'\')"><img src="/imgs/zoomin.png" alt="Увеличение и печать приложение к сертификату" title="Увеличение и печать приложение к сертификату"/></div>';
		}
		$out .= '</div>';
	}
	
	return ($out);
}
?>
<?php function download_button (){
	global $post;
	$link = get_post_meta($post->ID, "img_download_link", $single = true);
	$link2 = get_post_meta($post->ID, "img_download_link2", $single = true);
	$title = get_the_title($post->ID);

    $metadata = get_post_custom($post->ID);
    $validity = $metadata['param2_validity'][0];
    $valid = isActualDates($validity, true);

    if (!$valid) {
        $btn_class_left='download_link_left'/* btn_green'*/;
        $btn_class_right='download_link_right'/* btn_green'*/;
        $btn_class_center='download_link'/* btn_green'*/;
        $btn_class_left_text='Скачать сертификат на '.$title;
        $btn_class_right_text='Скачать приложение к сертификату на '.$title;
        $btn_class_center_text='Скачать сертификат на '.$title;
        $btn_class_left_title='Скачать сертификат';
        $btn_class_right_title='Скачать приложение';
        $btn_class_center_title='Скачать сертификат';
    } else {
        $btn_class_left='download_link_left';
        $btn_class_right='download_link_right';
        $btn_class_center='download_link';
        $btn_class_left_text='Скачать сертификат на '.$title;
        $btn_class_right_text='Скачать приложение к сертификату на '.$title;
        $btn_class_left_title='Скачать сертификат'; 
        $btn_class_right_title='Скачать приложение'; 
    }

	$out = '';
	
	if ($link!="") {
		if ($link2!="") $out .= '<div class="'.$btn_class_left.'" id="'.$post->ID.'" title="'.$btn_class_left_text.'" onclick="yaCounter32820367.reachGoal(\'dowload-click\'); return true;">'; else $out .= '<div class="'.$btn_class_center.'"  id="'.$post->ID.'" title="'.$btn_class_center_text.'" onclick="yaCounter32820367.reachGoal(\'dowload-click\'); return true;">';
		$out .= $btn_class_left_title;
		$out .= '</div>';
	}

	if ($link2!="") {
		$out .= '<div class="'.$btn_class_right.'"  id="'.$post->ID.'" title="'.$btn_class_right_text.'" onclick="ym(32820367,\'reachGoal\',\'appendix-click\'); return true;">';
		$out .= $btn_class_right_title;
		$out .= '</div>';
	}
	
	$out .= '<div style="clear: both; border-bottom: solid #ddd 1px;"></div>';
	return ($out);
}
?>
<?php function download_count ($link_number, $id, $direct) {

	update_download_count($id);
	error_log('DOWNLOAD      : '.get_the_title($id));
	
	if ($link_number=="1") {
		$thmb="img_thmb";
		$dwnld="img_download_link";
	} else {
		$thmb="img_thmb2";
		$dwnld="img_download_link2";
	}

	if ($direct) {
		$link=get_post_meta($id, $thmb, true);
		$link='download/'.mb_substr($link, 5);

		if (ob_get_level()) {
    		ob_end_clean();
		}
	    // заставляем браузер показать окно сохранения файла
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename=' . basename($link));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($link));
	    // читаем файл и отправляем его пользователю
	    readfile($link);
	} else {
		$link=get_post_meta($id, $dwnld, true);
		header("Location: ".$link);
	}
		
	header("Location: ".get_permalink($id));
}
?>
<?php
// Отключение rss ленты
function fb_disable_feed() {
wp_redirect(get_option('siteurl')); exit;
}

add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);

remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );

?>
<?php
add_action("wp_head", "wp_head_meta_description", 1);
 
function wp_head_meta_description() {
	global $post;
	if( is_single() ) {
		$metadata =           get_post_custom($post->ID);
		$og_number        	= $metadata['param1_number'][0];
        $og_product      	= $metadata['param4_product'][0];
        $og_product    		= str_replace(array("\""), "", $og_product);
        $og_product         = mb_substr($og_product, 0, 107 - mb_strlen($og_number));

        echo '<meta name="description" content="Сертификат соответствия № '.$og_number.' на '. $og_product.'..." />'."\r\n";
	}
	if( is_category() or is_tag()) {
		echo '<meta name="description" content="Скачать сертификаты соответствия на ' . cutStringToWords(esc_attr(mb_strtolower(single_cat_title('',false))), 256) .'">'."\r\n";
	}
	if (is_page('reestr-sertifikatov')) {
		echo '<meta name="description" content="Реестр сертификатов и деклараций соответствия">'."\r\n";
	}
	if (is_page('naiti-sertifikat-po-nomeru')) {
		if (!isset($_GET['param'])) echo '<meta name="description" content="Сертификаты соответствия по номеру сертификата">'."\r\n"; else
		echo '<meta name="description" content="Скачать сертификаты с номером '.$_GET['param'].'">'."\r\n";
	}
	if (is_page('kompanii')) {
		if (!isset($_GET['param'])) echo '<meta name="description" content="Сертификаты соответствия по организациям-изготовителям">'."\r\n"; else
		echo '<meta name="description" content="Скачать сертификаты на продукцию '.$_GET['param'].'">'."\r\n";
	}
	if (is_page('organy-po-sertifikacii')) {
		if (!isset($_GET['param'])) echo '<meta name="description" content="Сертификаты соответствия по органам по сертификации">'."\r\n"; else
		echo '<meta name="description" content="Скачать сертификаты выданные органом по сертификации '.$_GET['param'].'">'."\r\n";
	}
	if (is_page('gosty')) {
		if (!isset($_GET['param'])) echo '<meta name="description" content="ГОСТы, технические регламенты и другие нормативы на материалы, товары, продукцию и услуги">'."\r\n"; else
		echo '<meta name="description" content="Скачать '.$_GET['param'].'">'."\r\n";
	}
	if (is_page('o-sajte')) {
		echo '<meta name="description" content="О сайте rostest-certify.ru, отказ от ответственности и обратная связь">'."\r\n";
	}
	if (is_search()) {
		if (empty($_GET['s'])) echo '<meta name="description" content="Поиск сертификатов соответствия на продукцию">'."\r\n"; else
		echo '<meta name="description" content="Скачать сертификаты соответствия на '.$_GET['s'].'">'."\r\n";
	}
}
?>
<?php
function update_download_count ($id, $user) {
	global $wpdb;

	date_default_timezone_set('Europe/Samara');

	$count = get_post_meta($id, "download_count", true);
	
	if (!($count>0)) {
		add_post_meta($id, "download_count", "0", true); 
		add_post_meta($id, "download_adddate", date("Y-m-d"), true); 			
		add_post_meta($id, "download_addtime", date("H:i"), true);
		$count=0;
	}
		
	update_post_meta($id, "download_count", $count+1);
	update_post_meta($id, "download_lastdate", date("Y-m-d"));
	update_post_meta($id, "download_lasttime", date("H:i"));

	//Обновим для текущего пользователя количество скаченных сертификатов
    $rec = $wpdb->get_row($wpdb->prepare("SELECT ID, email, downloads FROM wp_paidusers WHERE email = '$user'", $user));
    $wpdb->update('wp_paidusers', array('downloads'=>$rec->downloads+1), array('ID'=>$rec->ID));
    //Обновим историю скачиваний в таблице wp_userhistory
    $wpdb_result = $wpdb->update('wp_userhistory', array('downloaded'=>1), array('user'=>$user, 'post_id'=>$id));
    if ($wpdb_result === 0) {
    	$wpdb->insert('wp_userhistory', array( 'user' => $user, 'post_id' => $id, 'lasttime'=>date('Y-m-d H:i:s'), 'downloaded' => 1), array( '%s', '%s', '%s', '%d' ));
    }
}
?>
<?php
function update_norm_count ($norm_id) {
	global $wpdb;

	date_default_timezone_set('Europe/Samara');

	//Получим для данной нормы количество скачиваний

	$download_count = $wpdb->get_var($wpdb->prepare("SELECT download_count FROM wp_norms WHERE ID=$norm_id", $norm_id));

	//Обновим количество скачиваний, а также время и дату последнего скачивания

	$wpdb->update('wp_norms',
		array('download_count'=>$download_count+1, 'download_lastdate'=>date("Y-m-d"), 'download_lasttime'=>date("H:i")),
		array( 'ID' => $norm_id )
	);
}
?>
<?php
function get_or_generate_desc ($id) {
	$desc = get_post_meta($id, "desc", true);
	if (empty ($desc)) {
	
		$out = '<p><strong>';
		$is_cert = true; //true - сертификат, false - декларация, по-умолчанию сертификат
		$is_service = false; // true - услуга, false - товар, по-умолчанию товар
	
        //Определим, перед нами сертификат или декларация
        //Получим номер сертификата
        //Если в номере есть символы С- или C- (латиница) - это сертификат соответствия
        //Если в номере есть символ Д- (латиница) - это декларация соответствия
        //Иначе это сертификат соответствия

        //Упрощаем задачу

        //Если в номере есть символ Д- (латиница) - это декларация соответствия, иначе - сертификат соответствия

        $number = get_post_meta($id, "param1_number", true);

        if (!(mb_strpos($number, 'Д-')===false)) {
            $is_cert = false;
        } else
        {
            $is_cert = true;
        }

        //Определим, на товар или услугу
        $cat=get_the_category($id);
        $cat_parent_name = end(get_ancestors($cat[0]->cat_ID, 'category' ));
        $cat_name = get_cat_name($cat_parent_name).': '.single_cat_title('',0);

        if ($cat_name=='ОКУН: ') $is_service = true; else $is_service = false; 
	
		$seed = rand (0, 1);
		
		if ($is_cert) {
			if (($seed==0) and ($is_cert)) $out .= 'Сертификат соответствия';
			if (($seed==1) and ($is_cert)) $out .= 'Сертификат';
		} else {
			if (($seed==0) and (!($is_cert))) $out .= 'Декларация соответствия';
			if (($seed==1) and (!($is_cert))) $out .= 'Декларация';
		}

		$out.=' на ';

		$title = get_the_title($id);
		if (mb_strtolower(mb_substr($title, 1, 1))==mb_substr($title, 1, 1))
			$out .= mb_strtolower(mb_substr($title, 0, 1)).mb_substr($title, 1).'</strong>';
			else $out .= $title.'</strong>';
		
		$seed = rand (0, 3);
		if ($seed==0) $out .= ', то есть ';
		if ($seed==1) $out .= ' – это ';
		if ($seed==2) $out .= ' есть ';
		if ($seed==3) $out .= ' представляет собой ';
		
		$seed = rand (0, 1);
		if ($seed==0) $out .= 'документ';
		if ($seed==1) $out .= 'официальный документ';
		
		$out .= ', ';
		
		$seed = rand (0, 5);
		if ($seed==0) $out .= 'удостоверяющий ';
		if ($seed==1) $out .= 'подтверждающий ';
		if ($seed==2) $out .= 'указывающий ';
		if ($seed==3) $out .= 'заверяющий ';
		if ($seed==4) $out .= 'знаменующий ';
		if ($seed==5) $out .= 'констатирующий ';
		
		$out .= 'соответствие ';
		
		if ($is_service) {
			$seed = rand (0, 3);
			if ($seed==0) $out .= 'услуги ';
			if ($seed==1) $out .= 'товара или услуги ';
			if ($seed==2) $out .= 'услуги или товара ';
			if ($seed==3) $out .= 'деятельности ';
			
		} else {
			$seed = rand (0, 6);
			if ($seed==0) $out .= 'объекта ';
			if ($seed==1) $out .= 'предмета ';
			if ($seed==2) $out .= 'продукции ';
			if ($seed==3) $out .= 'товара ';
			if ($seed==4) $out .= 'изделия ';
			if ($seed==5) $out .= 'товара или услуги ';
			if ($seed==6) $out .= 'услуги или товара ';
		}
		
		$seed = rand (0, 4);
		if ($seed==0) $out .= 'требованиям ';
		if ($seed==1) $out .= 'положениям ';
		if ($seed==2) $out .= 'разделам ';
		if ($seed==3) $out .= 'показателям ';
		if ($seed==4) $out .= 'параметрам ';
		
		$seed = rand (0, 3);
		if ($seed==0) $out .= 'технических регламентов';
		if ($seed==1) $out .= 'стандартов';
		if ($seed==2) $out .= 'сводов правил';
		if ($seed==3) $out .= 'государственных стандартов';
		
		$out .= ', ';
		
		$seed = rand (0, 1);
		
		$seed = rand (0, 5);
		if ($seed==0) $out .= 'за номером ';
		if ($seed==1) $out .= 'с номером ';
		if ($seed==2) $out .= 'с регистрационным номером ';
		if ($seed==3) $out .= 'имеет номер ';
		if ($seed==4) $out .= 'номер ';
		if ($seed==5) $out .= '№ ';
		
		$number = get_post_meta($id, "param1_number", true);
		$out .= $number;
		
		$seed = rand (0, 3);
		if ($seed==0) $out .= ' и сроком действия ';
		if ($seed==1) $out .= ' со сроком действия ';
		if ($seed==2) $out .= ' имеет срок действия ';
		if ($seed==3) $out .= ' действителен ';
		
		$validity = get_post_meta($id, "param2_validity", true);
		$out .= $validity;
				
		$certification_agency = get_post_meta($id, "param3_certification_agency", true);
		if (!(empty($certification_agency))) {
			
			$out .= '. ';		
			$seed = rand (0, 1);
			if ($is_cert) {
				if (($seed==0) and ($is_cert)) $out .= 'Сертификат соответствия ';
				if (($seed==1) and ($is_cert)) $out .= 'Сертификат ';
			} else {
				if (($seed==0) and (!($is_cert))) $out .= 'Декларация соответствия ';
				if (($seed==1) and (!($is_cert))) $out .= 'Декларация ';
			}
		
			$seed = rand (0, 2);
			if ($is_cert) {
				if (($seed==0) and ($is_cert)) $out .= 'был выдан ';
				if (($seed==1) and ($is_cert)) $out .= 'выдан ';
				if (($seed==2) and ($is_cert)) $out .= 'выданный ';
			} else {
				if (($seed==0) and (!($is_cert))) $out .= 'была выдана ';
				if (($seed==1) and (!($is_cert))) $out .= 'выдана ';
				if (($seed==2) and ($is_cert)) $out .= 'выданная ';
			}
		
			$seed = rand (0, 3);
			if ($seed==0) $out .= 'компанией ';
			if ($seed==1) $out .= 'организацией ';
			if ($seed==2) $out .= 'фирмой ';
			if ($seed==3) $out .= 'предприятием ';
		
			$fs = mb_strpos($certification_agency, '"');
			if ($fs === false) $fs = mb_strpos($certification_agency, '«');
			if ($fs === false) $fs = mb_strpos($certification_agency, '“');
			$ed = mb_strpos($certification_agency, '"', $fs+1);
			if ($ed === false) $ed = mb_strpos($certification_agency, '»', $fs+1);
			if ($ed === false) $ed = mb_strpos($certification_agency, '”', $fs+1);
			
			$out .= mb_substr($certification_agency,$fs,$ed-$fs+1);
		}
		
		$out .= ' на ';
		
		if ($is_service) {
			$seed = rand (0, 1);
			if ($seed==0) $out .= 'услугу';
			if ($seed==1) $out .= 'деятельность';
			
		} else {
			$seed = rand (0, 3);
			if ($seed==0) $out .= 'товар';
			if ($seed==1) $out .= 'продукцию';
			if ($seed==2) $out .= 'изделие';
			if ($seed==3) $out .= 'предмет';
		}
		
		$out .= ', ';
		
		$seed = rand (0, 3);
		if ($seed==0) $out .= 'соответствующий коду ';
		if ($seed==1) $out .= 'который соответствует коду ';
		if ($seed==2) $out .= 'соответствующий классификатору ';
		if ($seed==3) $out .= 'по классификатору ';

		if ($is_cert) {
			$seed = rand (0, 1);
			if ($seed==0) $out .= 'ОКП ';
			if ($seed==1) $out .= 'ОК 005-93 (ОКП) ';
		} else
		if ($is_service) {
			$seed = rand (0, 3);
			if ($seed==0) $out .= 'ТН ВЭД ТС ';
			if ($seed==1) $out .= 'Товарной номенклатуры ВЭД ТС ';
			if ($seed==2) $out .= 'ТН ВЭД Таможенного союза ';
			if ($seed==3) $out .= 'товарной номенклатуры Таможенного союза  ';
		} else {
			$seed = rand (0, 1);
			if ($seed==0) $out .= 'ОКУН ';
			if ($seed==1) $out .= 'ОК 002-93 (ОКУН) ';			
		}
		
		$out .= $cat[0]->cat_name;
		$out .= '.';
		
		$seed = rand (0, 1);
		if ($seed==0) $out .= ' В соответствии с ';
		if ($seed==1) $out .= ' Согласно с ';
		
		$seed = rand (0, 6);
		if ($is_cert) {
			if (($seed==0) and ($is_cert)) $out .= 'выданным ';
			if (($seed==1) and ($is_cert)) $out .= 'настоящим ';
			if (($seed==2) and ($is_cert)) $out .= 'представленным ';
			if (($seed==3) and ($is_cert)) $out .= 'предоставленным ';
			if (($seed==4) and ($is_cert)) $out .= 'оформленным ';
			if (($seed==5) and ($is_cert)) $out .= 'указанным ';
			if (($seed==6) and ($is_cert)) $out .= 'настоящим ';
		} else {
			if (($seed==0) and ($is_cert)) $out .= 'выданной ';
			if (($seed==1) and ($is_cert)) $out .= 'настоящей ';
			if (($seed==2) and ($is_cert)) $out .= 'представленной ';
			if (($seed==3) and ($is_cert)) $out .= 'предоставленной ';
			if (($seed==4) and ($is_cert)) $out .= 'оформленной ';
			if (($seed==5) and ($is_cert)) $out .= 'указанной ';
			if (($seed==6) and ($is_cert)) $out .= 'настоящей ';
		}		
		
		$seed = rand (0, 1);
		if ($is_cert) {
			if (($seed==0) and ($is_cert)) $out .= 'сертификатом соответствия';
			if (($seed==1) and ($is_cert)) $out .= 'сертификатом';
		} else {
			if (($seed==0) and (!($is_cert))) $out .= 'декларацией соответствия';
			if (($seed==1) and (!($is_cert))) $out .= 'декларацией';
		}
		
		$out .= ', ';
		
		$seed = rand (0, 6);
		if ($is_service) {
			if (($seed==0) and ($is_cert)) $out .= 'данная ';
			if (($seed==1) and ($is_cert)) $out .= 'эта ';
			if (($seed==2) and ($is_cert)) $out .= 'настоящая ';
			if (($seed==3) and ($is_cert)) $out .= 'представленная ';
			if (($seed==4) and ($is_cert)) $out .= 'предоставленная ';
			if (($seed==5) and ($is_cert)) $out .= 'вышеупомянутая ';
			if (($seed==6) and ($is_cert)) $out .= 'сертифицированная ';			
		} else {
			if (($seed==0) and ($is_cert)) $out .= 'данный ';
			if (($seed==1) and ($is_cert)) $out .= 'этот ';
			if (($seed==2) and ($is_cert)) $out .= 'настоящий ';
			if (($seed==3) and ($is_cert)) $out .= 'представленный ';
			if (($seed==4) and ($is_cert)) $out .= 'предоставленный ';
			if (($seed==5) and ($is_cert)) $out .= 'вышеупомянутый ';
			if (($seed==6) and ($is_cert)) $out .= 'сертифицированный ';
		}		
		
		if ($is_service) {
			$seed = rand (0, 1);
			if ($seed==0) $out .= 'услуга ';
			if ($seed==1) $out .= 'деятельность ';
		} else {
		   $out .= 'товар ';
		}
		
		$seed = rand (0, 1);
		if ($seed==0) $out .= 'соответствует ';
		if ($seed==1) $out .= 'удовлетворяет ';
		
		$seed = rand (0, 3);
		if ($seed==0) $out .= 'требованиям ';
		if ($seed==1) $out .= 'положениям ';
		if ($seed==2) $out .= 'разделам ';
		if ($seed==3) $out .= 'показателям ';
		
		$complies = get_post_meta($id, "param5_complies_with", true);
		if (mb_substr($complies, -1)=='.') $complies = mb_substr($complies, 0, mb_strlen($complies)-1);
		$out .= $complies;
		$out .= '. ';
		
		if ($is_service) {
			$seed = rand (0, 2);
			if ($seed==0) $out .= 'Услуга предоставляется ';
			if ($seed==1) $out .= 'Услуга оказывается ';
			if ($seed==2) $out .= 'Деятельность осуществляется ';
		} else {
			$seed = rand (0, 9);
			if ($seed==0) $out .= 'Товар был изготовлен ';
			if ($seed==1) $out .= 'Товар изготовлен ';
			if ($seed==2) $out .= 'Товар выпускается ';
			if ($seed==3) $out .= 'Продукция изготовлена ';
			if ($seed==4) $out .= 'Продукция выпущена ';
			if ($seed==5) $out .= 'Изделие изготовлено ';
			if ($seed==6) $out .= 'Изделие произведено ';
			if ($seed==7) $out .= 'Предмет сертификации изготовлен ';
			if ($seed==8) $out .= 'Предмет сертификации был изготовлен ';
			if ($seed==9) $out .= 'Предмет сертификации выпускается ';
		}
		
		$seed = rand (0, 3);
		if ($seed==0) $out .= 'компанией ';
		if ($seed==1) $out .= 'организацией ';
		if ($seed==2) $out .= 'фирмой ';
		if ($seed==3) $out .= 'предприятием ';
		
		$manufacturer = get_post_meta($id, "param6_manufacturer", true);
		$fs = mb_strpos($manufacturer, '"');
		if ($fs === false) $fs = mb_strpos($manufacturer, '«');
		if ($fs === false) $fs = mb_strpos($manufacturer, '“');
		$ed = mb_strpos($manufacturer, '"', $fs+1);
		if ($ed === false) $ed = mb_strpos($manufacturer, '»', $fs+1);
		if ($ed === false) $ed = mb_strpos($manufacturer, '”', $fs+1);
		
		$out .= mb_substr($manufacturer,$fs,$ed-$fs+1);
		
		$issued = get_post_meta($id, "param7_issued", true);
		
		if (!(empty($issued))) {
			$out .= ', ';
			
			$seed = rand (0, 1);
			if ($seed==0) $out .= 'а ';
			if ($seed==1) $out .= 'при этом ';
			
			$seed = rand (0, 1);
			if ($seed==0) $out .= 'выдан - ';
			if ($seed==1) $out .= 'предоставлен - ';
			
			$seed = rand (0, 3);
			if ($seed==0) $out .= 'компании ';
			if ($seed==1) $out .= 'организации ';
			if ($seed==2) $out .= 'фирме ';
			if ($seed==3) $out .= 'предприятию ';
			
			$fs = mb_strpos($issued, '"');
			if ($fs === false) $fs = mb_strpos($issued, '«');
			if ($fs === false) $fs = mb_strpos($issued, '“');
			$ed = mb_strpos($issued, '"', $fs+1);
			if ($ed === false) $ed = mb_strpos($issued, '»', $fs+1);
			if ($ed === false) $ed = mb_strpos($issued, '”', $fs+1);
		
			$out .= mb_substr($issued,$fs,$ed-$fs+1);
		}
		
		$declarant = get_post_meta($id, "parama_declarant", true);
		
		if (!(empty($declarant))) {
			
			$out .= ', сертификация произведена ';
			
			$seed = rand (0, 1);
			if ($seed==0) $out .= ' по заявке ';
			if ($seed==1) $out .= ' по заявлению ';
			
			$seed = rand (0, 3);
			if ($seed==0) $out .= 'компании ';
			if ($seed==1) $out .= 'организации ';
			if ($seed==2) $out .= 'фирмы ';
			if ($seed==3) $out .= 'предприятия ';
			
			$fs = mb_strpos($declarant, '"');
			if ($fs === false) $fs = mb_strpos($declarant, '«');
			if ($fs === false) $fs = mb_strpos($declarant, '“');
			$ed = mb_strpos($declarant, '"', $fs+1);
			if ($ed === false) $ed = mb_strpos($declarant, '»', $fs+1);
			if ($ed === false) $ed = mb_strpos($declarant, '”', $fs+1);
		
			$out .= mb_substr($declarant,$fs,$ed-$fs+1);
		}
		
		$out .= '. ';
		$out .= ' Сертификация ';
		
		$seed = rand (0, 4);
		if ($seed==0) $out .= 'была проведена ';
		if ($seed==1) $out .= 'проведена ';
		if ($seed==2) $out .= 'осуществлена ';
		if ($seed==3) $out .= 'была осуществлена ';
		if ($seed==4) $out .= 'прошла ';
		
		$out .= 'на основании документов: ';
		
		$the_basis = get_post_meta($id, "param8_on_the_basis", true);
		if (mb_substr($the_basis, -1)=='.') $the_basis = mb_substr($the_basis, 0, mb_strlen($the_basis)-1);
		$out .= mb_strtolower(mb_substr($the_basis, 0, 1)).mb_substr($the_basis, 1);
		$out .= '. ';
		
		$seed = rand (0, 2);
		if ($seed==0) $out .= 'Как следует из сертификата';
		if ($seed==1) $out .= 'Кроме того';
		if ($seed==2) $out .= 'Так же';
		
		$out .= ', ';
		
		$add_info = get_post_meta($id, "param9_add_info", true);
		if (mb_substr($add_info, -1)=='.') $add_info = mb_substr($add_info, 0, mb_strlen($add_info)-1);
		$out .= mb_strtolower(mb_substr($add_info, 0, 1)).mb_substr($add_info, 1);
		$out .= '.</p>';
		
		
		add_post_meta($id, "desc", $out, true);
		return $out;
	}
	return $desc;	
}
?>
<?php
function search_by_title ($search='') {
	global $wpdb;

	date_default_timezone_set('Europe/Samara');
	
	$log_file_name = 'searchlog/'.date("Y_m_d_H-i-s").'.txt';
	$log_file = fopen($log_file_name, "w");
	if (!$log_file) error_log ('Cannot create log file: '.$log_file_name); else error_log ('Created searchlog: '.$log_file_name);

	error_log('Entering SEARCH BY TITLE at '.date("Y_m_d_H-i-s"));
	fwrite($log_file, 'Entering SEARCH BY TITLE at '.date("Y_m_d_H-i-s")."\r\n\r\n");

	error_log('search query: '.$search);
	fwrite($log_file, 'search query: '.$search."\r\n\r\n");

	if (empty($search)) return;

	mb_internal_encoding("UTF-8"); 

	$search = stripslashes($search);
	$search = htmlspecialchars($search);
	$search = trim($search);
	$search = mb_strtolower($search);

	$search = mb_substr($search, 0, 255);



	$search_words = mb_split("[ ,]+", $search, 5);

	fwrite($log_file, 'search words: '."\r\n");
	foreach ($search_words as $word) {
		fwrite($log_file, $word.' '."\r\n");
	}

	fwrite($log_file, ' '."\r\n");
	
	/*Логгинг поискового запроса в БД*/

	/*$rec = $wpdb->get_row($wpdb->prepare("SELECT ID, search_freq FROM wp_search WHERE search_query = '%$search%'", $search));*/
	$rec = $wpdb->get_row($wpdb->prepare("SELECT ID, search_freq FROM wp_search WHERE search_query = '$search'", $search));
	
	if (!(isset($rec))) {
		fwrite($log_file, 'SEARCH: '.$search.' FREQ NOT FOUND'."\r\n");
		$sql = $wpdb->insert('wp_search', array('search_query'=>$search, 'search_freq'=>1));
	} else {
		$freq = $rec->search_freq+1;
		$id = $rec->ID;
		fwrite($log_file, 'SEARCH: '.$search.' FREQ: '.$freq.' ID: '.$id."\r\n");
		$sql = $wpdb->update('wp_search', array('search_freq'=>$freq), array('ID'=>$id));
	}

	/*Конец логгинга*/

	
	//Поиск по всей строке запроса целиком
	$sql = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$search%%'", $search));

	$result = array();

	//Максимальный приоритет 1
	foreach ($sql as $s) {
		$result[$s] = 1;
	}

	fwrite($log_file,'FOR FULL STRING: '.$search."\r\n");
	foreach ($result as $value=>$weight) {
		fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
	}

	$word_num = 1;
	//Поиск по отдельным словам запроса
	foreach ($search_words as $word){
		//Трехбуквенные слова вообще не интересуют - пропускаем
		if (mb_strlen($word)<4) continue;

		$word = mb_substr($word, 0, mb_strlen($word)-2);
		
		$sql = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$word%%'", $word));
		foreach ($sql as $s) {
			if (empty($result[$s])) {
				//Первое слово в строке поиска важнее - ему больший приоритет
				if ($word_num == 1)	$result[$s] = 2; else $result[$s] = 3;
			}
		}

		fwrite($log_file,'FOR ALMOST WHOLE WORD: '.$word."\r\n");
		foreach ($result as $value=>$weight) {
			fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
		}

		/*Поиск по первым четырем буквам слова "для массовки" выдачи
		$word = mb_substr($word, 0, 4);

		$sql = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$word%%'", $word));
		foreach ($sql as $s) {
			if (empty($result[$s])) $result[$s] = 4;
		}

		fwrite($log_file,'FOR INITIAL 4 LETTER: '.$word."\r\n");
		foreach ($result as $value=>$weight) {
			fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
		}*/

		//Может быть для этого слова есть синонимы? Поищем по ним.
		//Есть ли синонимы?
		fwrite($log_file, 'SEARCHING FOR SYNONYMS TO: '.$word."\r\n");
		$syn1 = $wpdb->get_row($wpdb->prepare("SELECT word2 FROM wp_synonyms WHERE word1 LIKE '%%$word%%'", $word));
		$syn2 = $wpdb->get_row($wpdb->prepare("SELECT word1 FROM wp_synonyms WHERE word2 LIKE '%%$word%%'", $word));

		if (isset($syn1)) {
			foreach ($syn1 as $value) {
					fwrite($log_file,'SYN1: '.$value."\r\n");
			}
		} else fwrite($log_file,'SYN1 NOT FOUND'."\r\n");

		if (isset($syn2)) {
			foreach ($syn2 as $value) {
					fwrite($log_file,'SYN2: '.$value."\r\n");
			}
		} else fwrite($log_file,'SYN2 NOT FOUND'."\r\n");

		if (isset($syn1) && isset($syn2)) $syn = array_merge($syn1, $syn2); 
		else {
			if (isset($syn1) && (!(isset($syn2)))) $syn=$syn1;
			if (isset($syn2) && (!(isset($syn1)))) $syn=$syn2;
		}

		if (isset($syn)) {
			fwrite($log_file, 'SYNONYMS FOUND!'."\r\n");
			foreach ($syn as $value) {
					fwrite($log_file,'SYNONYM: '.$value."\r\n");
			} 
		} else fwrite($log_file,'SYNONYMS NOT FOUND'."\r\n");		 

		//Поиск по синониму слова запроса
		foreach ($syn as $syn_search){
			$syn_words = mb_split("[ ,]+", $syn_search, 5);

			$sql_syn = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$syn_search%%'", $syn_search));

			foreach ($sql_syn as $s) {
				$result[$s] = 2;
			}

			foreach ($syn_words as $synonym){
				if (mb_strlen($synonym)<4) continue;

				$synonym = mb_substr($synonym, 0, mb_strlen($synonym)-1);
				
				$sql_syn = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$synonym%%'", $synonym));
				foreach ($sql_syn as $s) {
					if (empty($result[$s])) {
						$result[$s] = 3;
					}
				}

				fwrite($log_file,'FOR SYNONYM: '.$synonym."\r\n");
				foreach ($result as $value=>$weight) {
					fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
				}
			}
		}


		$word_num += $word_num;
	}

	asort ($result);

	fwrite($log_file,'SEARCH RESULTS:'."\r\n");

	foreach ($result as $id=>$weight) {
			fwrite($log_file,'WEIGHT: '.$weight.' ID: '.$id.' FOUND: '.get_the_title ($id)."\r\n");
	}

	$result_total = array();

	foreach ($result as $id=>$weight) {
		$result_total[] = $id;
	}

	fwrite($log_file, 'Exiting SEARCH BY TITLE at '.date("Y_m_d_H-i-s").' FOUND '.count($result_total)."\r\n");
	fclose($log_file);

	return $result_total;
}
?>
<?php
function search_by_titleA ($search='') {
	global $wpdb;

	date_default_timezone_set('Europe/Samara');	
	
	//Создание лог-файла поиска
	$log_file_name = 'searchlog/'.date("Y_m_d_H-i-s").'.txt';
	$log_file = fopen($log_file_name, "w");
	

	if (!$log_file) error_log ('A: Cannot create log file: '.$log_file_name); else error_log ('A: Created searchlog: '.$log_file_name);

	error_log('A: Entering SEARCH BY TITLE_A at '.date("Y_m_d_H-i-s"));
	fwrite($log_file, 'A: Entering SEARCH BY TITLE_A at '.date("Y_m_d_H-i-s")."\r\n\r\n");

	error_log('A: search query: '.$search);
	fwrite($log_file, 'A: search query: '.$search."\r\n\r\n");

	if (empty($search)) return;

	mb_internal_encoding("UTF-8"); 

	$search = stripslashes($search);
	$search = htmlspecialchars($search);
	$search = trim($search);
	$search = mb_strtolower($search, 'UTF-8');

	//Ограничим строку поиска 255 символами
	$search = mb_substr($search, 0, 255);

	
	//Разобъем на слова по пробелам и запятым, на 5 частей максимум
	$search_words = mb_split("[ ,]+", $search, 5);

	fwrite($log_file, 'A: Search words: '."\r\n");
	
	//
	foreach ($search_words as $word) {
		//Удалим все слова с количеством букв менее 3
		if (mb_strlen($word)<3) {
			fwrite($log_file, 'A: Word removed: '.$word."\r\n");
			unset($word);
		} else
		fwrite($log_file, 'A: Word added: '.$word."\r\n");
	}

	$search_words = array_values($search_words);

	fwrite($log_file, 'A: Total search words: '.count($search_words)."\r\n");

	fwrite($log_file, ' '."\r\n");
	
	//Логгинг поискового запроса в БД

	$rec = $wpdb->get_row($wpdb->prepare("SELECT ID, search_freq FROM wp_search WHERE search_query = '$search'", $search));
	
	if (!(isset($rec))) {
		fwrite($log_file, 'A: FREQ NOT FOUND'."\r\n");
		$sql = $wpdb->insert('wp_search', array('search_query'=>$search, 'search_freq'=>1));
	} else {
		$freq = $rec->search_freq+1;
		$id = $rec->ID;
		fwrite($log_file, 'A: SEARCH '.$search.' FREQ: '.$freq.' ID: '.$id."\r\n");
		$sql = $wpdb->update('wp_search', array('search_freq'=>$freq), array('ID'=>$id));
	}

	//Конец логгинга
	
	//Берем первое слово, ищем его в таблице постов - выбираем ID и название поста
	$word = current($search_words);
	
	//Обрежем слово для поиска словоформ с конца
	//Если слово 4 символа - не обрезаем, 5 символов - обрезаем 1, 6 символов и более - 2

	if (mb_strlen($word) >= 6) $word = mb_substr($word, 0, mb_strlen($word)-2);
	else if (mb_strlen($word) == 5) $word = mb_substr($word, 0, mb_strlen($word)-1);

	fwrite($log_file, 'A: WORD: '.$word."\r\n");

	//Выполним поиск по таблице с постами
	$sql = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%$word%'", $word));
	
	//Теперь в sql содержится массив объектов, содержащих ID и названия постов, содержащих в названии слово из поиска
	
	//Необходимо просмотреть оставшиеся слова в наборе слов для поиска, и исключить из найденного те варианты,
	//которые не содержат данного слова

	fwrite($log_file, "\r\n");
	fwrite($log_file, 'A: Before Filtering ('.count($sql).'):'."\r\n");

	foreach ($sql as $sq) {
			//Преобразуем все буквы запроса в строчные
			$sq->post_title = mb_strtolower($sq->post_title, 'UTF-8');

			fwrite($log_file, $sq->ID.': '.$sq->post_title."\r\n");
	}

	fwrite($log_file, 'A: Filtering by Word-starting word'."\r\n");

	$sql_filtered_a = array();

	foreach ($sql as $sq) {
			//Отфильтруем все запросы, в которых слово поиска встречается в заголовке записи не с начала слова

			//Получим позицию первой буквы слова поиска
			$f_letter = mb_strpos($sq->post_title, $word);

			//Если слово в 0 позиции, то результат подходит, добавим в массив результатов
			if ($f_letter == 0) {
				fwrite($log_file, 'A: GOOD WORD-STARTING WORD: '.$sq->post_title."\r\n");
				array_push($sql_filtered_a, $sq);
				continue;
			}

			//Получим букву перед первой
			$init_letter = mb_substr($sq->post_title, $f_letter-1, 1);

			//Если перед словом не пробел и не скобка, выбросим этот результат
			if (!(($init_letter==' ') || ($init_letter=='(') || ($init_letter=='"') || ($init_letter=='«') || ($init_letter=='“'))) {
				fwrite($log_file, 'A: NOT A WORD-STARTING WORD: INIT: '.$init_letter.' WORD: '.$sq->post_title."\r\n");
				continue;
			}

			array_push($sql_filtered_a, $sq);
			fwrite($log_file, 'A: GOOD WORD-STARTING WORD: '.$sq->post_title."\r\n");
	}

	$sql = array();
	$sql = $sql_filtered_a;
	
	fwrite($log_file, 'A: AFTER Filtering by Word-starting word ('.count($sql).'):'."\r\n");
		
	$sql_filtered_a = array();

	foreach ($sql as $sq) {
			//Преобразуем все буквы запроса в строчные
			$sq->post_title = mb_strtolower($sq->post_title, 'UTF-8');

			fwrite($log_file, $sq->ID.': '.$sq->post_title."\r\n");
	}

	fwrite($log_file, "\r\n");
	fwrite($log_file, 'A: Filtering by Non-entry words'."\r\n");

	//Переберем оставшиеся слова

	foreach ($search_words as $word) {
		//Обрежем слово для поиска словоформ с конца
		//Если слово 4 символа - не обрезаем, 5 символов - обрезаем 1, 6 символов и более - 2

		if (mb_strlen($word) >= 6) $word = mb_substr($word, 0, mb_strlen($word)-2);
		else if (mb_strlen($word) == 5) $word = mb_substr($word, 0, mb_strlen($word)-1);
		
		fwrite($log_file, "\r\n");
		fwrite($log_file, 'A: WORD: '.$word."\r\n");
		
		//Выбрали слово. Теперь пробежим поля post_title объектов массива $sql, и попробуем найти это слово

		foreach ($sql as $sq) {
			if (mb_strpos($sq->post_title, $word)!==false) { 
				fwrite($log_file, 'GOOD: STRPOST: '.mb_strpos($sq->post_title, $word).' WORD: '.$word.' : SQ :'.$sq->ID.': '.$sq->post_title."\r\n");
				array_push($sql_filtered_a, $sq);
			}
		}
		
		$sql = array();
		$sql = $sql_filtered_a;
		$sql_filtered_a = array();

	}

	fwrite($log_file, "\r\n");
	fwrite($log_file, 'A: After Filtering by Non-entry words ('.count($sql).'):'."\r\n");

	foreach ($sql as $sq) {
			fwrite($log_file, $sq->ID.': '.$sq->post_title."\r\n");
	}


/*
	$word_num = 1;
	
	foreach ($search_words as $word){
	
		if (mb_strlen($word)<4) continue;

		$word = mb_substr($word, 0, mb_strlen($word)-2);
		
		$sql = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$word%%'", $word));
		foreach ($sql as $s) {
			if (empty($result[$s])) {
				//Первое слово в строке поиска важнее - ему больший приоритет
				if ($word_num == 1)	$result[$s] = 2; else $result[$s] = 3;
			}
		}

		fwrite($log_file,'FOR ALMOST WHOLE WORD: '.$word."\r\n");
		foreach ($result as $value=>$weight) {
			fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
		}

		
		$word = mb_substr($word, 0, 4);

		$sql = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$word%%'", $word));
		foreach ($sql as $s) {
			if (empty($result[$s])) $result[$s] = 4;
		}

		fwrite($log_file,'FOR INITIAL 4 LETTER: '.$word."\r\n");
		foreach ($result as $value=>$weight) {
			fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
		}

		//Может быть для этого слова есть синонимы? Поищем по ним.
		//Есть ли синонимы?
		fwrite($log_file, 'SEARCHING FOR SYNONYMS TO: '.$word."\r\n");
		$syn1 = $wpdb->get_row($wpdb->prepare("SELECT word2 FROM wp_synonyms WHERE word1 LIKE '%%$word%%'", $word));
		$syn2 = $wpdb->get_row($wpdb->prepare("SELECT word1 FROM wp_synonyms WHERE word2 LIKE '%%$word%%'", $word));

		if (isset($syn1)) {
			foreach ($syn1 as $value) {
					fwrite($log_file,'SYN1: '.$value."\r\n");
			}
		} else fwrite($log_file,'SYN1 NOT FOUND'."\r\n");

		if (isset($syn2)) {
			foreach ($syn2 as $value) {
					fwrite($log_file,'SYN2: '.$value."\r\n");
			}
		} else fwrite($log_file,'SYN2 NOT FOUND'."\r\n");

		if (isset($syn1) && isset($syn2)) $syn = array_merge($syn1, $syn2); 
		else {
			if (isset($syn1) && (!(isset($syn2)))) $syn=$syn1;
			if (isset($syn2) && (!(isset($syn1)))) $syn=$syn2;
		}

		if (isset($syn)) {
			fwrite($log_file, 'SYNONYMS FOUND!'."\r\n");
			foreach ($syn as $value) {
					fwrite($log_file,'SYNONYM: '.$value."\r\n");
			} 
		} else fwrite($log_file,'SYNONYMS NOT FOUND'."\r\n");		 

		//Поиск по синониму слова запроса
		foreach ($syn as $syn_search){
			$syn_words = mb_split("[ ,]+", $syn_search, 5);

			$sql_syn = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$syn_search%%'", $syn_search));

			foreach ($sql_syn as $s) {
				$result[$s] = 2;
			}

			foreach ($syn_words as $synonym){
				if (mb_strlen($synonym)<4) continue;

				$synonym = mb_substr($synonym, 0, mb_strlen($synonym)-1);
				
				$sql_syn = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$synonym%%'", $synonym));
				foreach ($sql_syn as $s) {
					if (empty($result[$s])) {
						$result[$s] = 3;
					}
				}

				fwrite($log_file,'FOR SYNONYM: '.$synonym."\r\n");
				foreach ($result as $value=>$weight) {
					fwrite($log_file,'ID: '.$value.' WEIGHT: '.$weight."\r\n");
				}
			}
		}


		$word_num += $word_num;
	}
	*/
	//asort ($result);

	fwrite($log_file,'SEARCH RESULTS:'."\r\n");
	$result_total = array();

	foreach ($sql as $sq) {
			array_push($result_total, $sq->ID);
			fwrite($log_file, 'ID: '.$sq->ID.' FOUND: '.get_the_title ($sq->ID)."\r\n");
	}

		
	fwrite($log_file, 'Exiting SEARCH BY TITLE at '.date("Y_m_d_H-i-s").' FOUND '.count($result_total)."\r\n");
	fclose($log_file);

	return $result_total;
}
?>
<?php
//Создание лог-файла поиска
function search_log_create ($name){
    if (!(isset($name)) && empty($search)) return false;
    
    $log_file = fopen($name, "w");
    return $log_file;
}
?>
<?php
//Создание лог-файла поиска
function search_safe ($search){
    if (!(isset($search)) && empty($search)) return '';
    $search = stripslashes($search);
    $search = htmlspecialchars($search);
    $search = trim($search);
    $search = mb_strtolower($search, 'UTF-8');
    //$search = removebadchars($search);

    return $search;
}
?>
<?php
/*
function removebadchars ($search){
    if (!(isset($name)) && empty($search)) return '';
    
    $bad_chars = array('&quot;', 'amp;', '#039;');

    foreach ($bad_chars as $bad) {
	    while (true) {
	    	$pos = mb_strpos($search, $bad);
	    	if ($pos == false) break;
	    	
	    	$start = mb_substr($search, 0, $pos-1);
	    	$ed = mb_substr($search, $pos+mb_strlen($bad));

	    	$search=$start.$ed;
	    }
    }
    return $search;
}
*/
?>
<?php
//Поиск с сохранением в wp_search только существительных из поискового запроса
function search_by_titleB ($search='') {
	global $wpdb;

	//Установка часового поиска и кодировки для логов
	date_default_timezone_set('Europe/Samara');
    mb_internal_encoding("UTF-8"); 
	
	//Создание лог-файла поиска
	$log_file = search_log_create('searchlog/'.date("Y_m_d_H-i-s").'.txt');

	if ($log_file) fwrite($log_file, 'B: Entering SEARCH BY TITLE_B at '.date("Y_m_d_H-i-s")."\r\n\r\n");
    if ($log_file) fwrite($log_file, 'B: Source search query: '.$search."\r\n\r\n");

	if (empty($search)) {
        if ($log_file) fwrite($log_file, 'B: NOTHING TO FIND, EXITING...'."\r\n");
        return;
    }

    //Безопасная передача параметров
    $search = search_safe($search);

	//Удаление ненужных html-сущностей
    //$search = removebadchars ($search);

	//Ограничим строку поиска 255 символами
	$search = mb_substr($search, 0, 255);

	//Разобъем на слова по пробелам и запятым, на 5 частей максимум
	if ($log_file) fwrite($log_file, 'B: STAGE 0: Splitting:'."\r\n\r\n");

	$search_words = mb_split("[ ,]+", $search, 5);

	log_total_search_words($log_file, $search_words);


	//Удалим все слова с количеством букв менее 3
	if ($log_file) fwrite($log_file, 'B: STAGE 1: Removing short (<3):'."\r\n\r\n");

	$search_filtered = array();

	foreach ($search_words as $word) {
		if (mb_strlen($word)<3) {
			if ($log_file) fwrite($log_file, 'B: Word removed (too short): '.$word."\r\n");
		} else {
			array_push($search_filtered, $word);
		}
	}

	$search_words = array();
	$search_words = $search_filtered;
	$search_filtered = array();

	log_total_search_words($log_file, $search_words);

	//Удалим все стоп-слова
	if ($log_file) fwrite($log_file, 'B: STAGE 2: Removing stop-words: '."\r\n\r\n");

	$stop_words = array('№еаэс', '№росс', 'pocc', 'ru.', 'еас', 'декларация', 'названию', 'паспорт', 'сертификат', 'сертификата', 'скачать', 'соответствия');

	foreach ($search_words as $word) {
		if (in_array($word, $stop_words, 0)) {
			if ($log_file) fwrite($log_file, 'B: Word removed (stop word): '.$word."\r\n");
		} else {
			array_push($search_filtered, $word);
		}
	}
	
	$search_words = array();
	$search_words = $search_filtered;
	$search_filtered = array();

	log_total_search_words($log_file, $search_words);

	if (count($search_words)==0) {
		if ($log_file) fwrite($log_file, 'B: NOTHING TO FIND, EXITING...'."\r\n");
		return '';
	}

	//Удалим все стоп-слова
	if ($log_file) fwrite($log_file, 'B: STAGE 3: Saving search freqs to database: '."\r\n\r\n");
	
	//Логгинг поискового запроса в БД
	//Пройдем все слова запроса, отфильтруем прилагательные, и внесем в wp_search сведения только о существительных из запроса

	$endings = array('ой', 'ый', 'ий', 'ая', 'яя', 'ое', 'ее', 'ей', 'ые', 'ие');

	foreach ($search_words as $word) {
		//Определимся, не прилагательное ли у нас
		//Для этого возьмем две последние буквы окончания
		//И посмотрим, не является ли это окончание прилагательного
		//-ой -ый -ий -ая -яя -ое -ее -ые -ие

		$ending = mb_substr ($word, mb_strlen($word)-2);
		if (!(in_array($ending, $endings, 0))) {
			//$word - существительное.
			//Найдем его в таблице wp_search
	
			$rec = $wpdb->get_row($wpdb->prepare("SELECT ID, search_freq FROM wp_search WHERE search_query = '$word'", $word));
				
			if (!(isset($rec))) {
				//Если такое слово не найдено
				if ($log_file) fwrite($log_file, 'B: NOT FOUND'.$word."\r\n");
				
				//Добавим его
				$sql = $wpdb->insert('wp_search', array('search_query'=>$word, 'search_freq'=>1, 'search_date' => date('Y-m-d H:i:s')), array( '%s', '%d', '%s'));
                if ($log_file) fwrite($log_file, 'B: SEARCH FREQ INIT: '.$word."\r\n");
			} else {
				//Если такое слово найдено
				//Увеличим на единицу количество раз, которое его пытались найти, и обновим дату записи
				$freq = $rec->search_freq+1;
				$id = $rec->ID;
				if ($log_file) fwrite($log_file, 'B: FOUND: '.$word.' SEARCH FREQ INCREASED: FREQ: '.$freq.' ID: '.$id."\r\n");
				$sql = $wpdb->update('wp_search', array('search_freq'=>$freq, 'search_date' => date('Y-m-d H:i:s')), array('ID'=>$id), array('%d', '%s'));
			}
		} else {
            if ($log_file) fwrite($log_file, 'B: SEARCH WORD IS ADJECTIVE AND NOT BE LOGGED: '.$word."\r\n");
        }
	}
	

	//Конец фильтров
	fwrite($log_file, "\r\n\r\n");


	//Берем первое слово, ищем его в таблице постов - выбираем ID и название поста
	if ($log_file) fwrite($log_file, 'B: STAGE 4: Fetching results contain the first word: '."\r\n\r\n");

	$word = reset($search_words);
	
	//Обрежем слово для поиска словоформ с конца
	//Если слово 4 символа - не обрезаем, 5 символов - обрезаем 1, 6 символов и более - 2

	if (mb_strlen($word) >= 6) $word = mb_substr($word, 0, mb_strlen($word)-2);
	else if (mb_strlen($word) == 5) $word = mb_substr($word, 0, mb_strlen($word)-1);

	if ($log_file) fwrite($log_file, 'B: WORD: '.$word."\r\n");

	//Выполним поиск по таблице с постами
	$sql = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%$word%'", $word));
	
	//Теперь в sql содержится массив объектов, содержащих ID и названия постов, содержащих в названии слово из поиска
	
	//Необходимо просмотреть оставшиеся слова в наборе слов для поиска, и исключить из найденного те варианты,
	//которые не содержат данного слова

	fwrite($log_file, "\r\n\r\n");

	if ($log_file) fwrite($log_file, 'B: STAGE 5: Removing missing words: '."\r\n");

	if ($log_file) fwrite($log_file, "\r\n");
	if ($log_file) fwrite($log_file, 'B: Before Filtering ('.count($sql).'):'."\r\n");

	foreach ($sql as $sq) {
			//Преобразуем все буквы запроса в строчные
			$sq->post_title = mb_strtolower($sq->post_title, 'UTF-8');

			if ($log_file) fwrite($log_file, $sq->ID.': '.$sq->post_title."\r\n");
	}

	if ($log_file) fwrite($log_file, "\r\n");
	if ($log_file) fwrite($log_file, 'B: Filtering by Word-starting word'."\r\n\r\n");

	$sql_filtered_a = array();

	foreach ($sql as $sq) {
			//Отфильтруем все запросы, в которых слово поиска встречается в заголовке записи не с начала слова

			//Получим позицию первой буквы слова поиска
			$f_letter = mb_strpos($sq->post_title, $word);

			//Если слово в 0 позиции, то результат подходит, добавим в массив результатов
			if ($f_letter == 0) {
				if ($log_file) fwrite($log_file, 'B: GOOD START: '.$sq->post_title."\r\n");
				array_push($sql_filtered_a, $sq);
				continue;
			}

			//Получим букву перед первой
			$init_letter = mb_substr($sq->post_title, $f_letter-1, 1);

			//Если перед словом не пробел и не скобка, выбросим этот результат
			if (!(($init_letter==' ') || ($init_letter=='(') || ($init_letter=='"') || ($init_letter=='«') || ($init_letter=='“'))) {
				if ($log_file) fwrite($log_file, 'B: BAD START: INIT: '.$init_letter.'   WORD: '.$sq->post_title."\r\n");
				continue;
			}

			array_push($sql_filtered_a, $sq);
			if ($log_file) fwrite($log_file, 'B: GOOD START: '.$sq->post_title."\r\n");
	}

	$sql = array();
	$sql = $sql_filtered_a;
	
	if ($log_file) fwrite($log_file, "\r\n");
	if ($log_file) fwrite($log_file, 'B: AFTER Filtering by Word-starting word ('.count($sql).'):'."\r\n");
		
	$sql_filtered_a = array();

	foreach ($sql as $sq) {
			//Преобразуем все буквы запроса в строчные
			$sq->post_title = mb_strtolower($sq->post_title, 'UTF-8');

			if ($log_file) fwrite($log_file, $sq->ID.': '.$sq->post_title."\r\n");
	}

	if ($log_file) fwrite($log_file, "\r\n\r\n");
	if ($log_file) fwrite($log_file, 'B: STAGE 6: Filtering by non-entry words: '."\r\n\r\n");

	//Переберем оставшиеся слова, начиная со второго

	foreach ($search_words as $index => $word) {
		
		//Первый элемент пропускаем
		if ($index < 1)	continue;

		//Обрежем слово для поиска словоформ с конца
		//Если слово 4 символа - не обрезаем, 5 символов - обрезаем 1, 6 символов и более - 2

		if (mb_strlen($word) >= 6) $word = mb_substr($word, 0, mb_strlen($word)-2);
		else if (mb_strlen($word) == 5) $word = mb_substr($word, 0, mb_strlen($word)-1);

		if ($log_file) fwrite($log_file, "\r\n");
		if ($log_file) fwrite($log_file, 'B: WORD: '.$word."\r\n");
		if ($log_file) fwrite($log_file, "\r\n");
		
		//Выбрали слово. Теперь пробежим поля post_title объектов массива $sql, и попробуем найти это слово

		foreach ($sql as $sq) {
			if (mb_strpos($sq->post_title, $word)!==false) { 
				if ($log_file) fwrite($log_file, 'GOOD: STRPOST: '.mb_strpos($sq->post_title, $word).' WORD: '.$word.' : SQ :'.$sq->ID.': '.$sq->post_title."\r\n");
				array_push($sql_filtered_a, $sq);
			} else fwrite($log_file, 'BAD: '.$sq->post_title.' WORD: '.$word.' DOES NOT FOUND'."\r\n");
		}
		
		$sql = array();
		$sql = $sql_filtered_a;
		$sql_filtered_a = array();

	}

	if ($log_file) fwrite($log_file, "\r\n");
	if ($log_file) fwrite($log_file, 'B: After Filtering by Non-entry words ('.count($sql).'):'."\r\n");

	foreach ($sql as $sq) {
			if (!$log_file) fwrite($log_file, $sq->ID.': '.$sq->post_title."\r\n");
	}

	if ($log_file) fwrite($log_file, "\r\n");
	if ($log_file) fwrite($log_file,'SEARCH RESULTS:'."\r\n");
	$result_total = array();

	foreach ($sql as $sq) {
			array_push($result_total, $sq->ID);
			if ($log_file) fwrite($log_file, 'ID: '.$sq->ID.' FOUND: '.get_the_title ($sq->ID)."\r\n");
	}

	if ($log_file) fwrite($log_file, "\r\n\r\n");
	if ($log_file) fwrite($log_file, 'Exiting SEARCH BY TITLE B at '.date("Y_m_d_H-i-s").' FOUND '.count($result_total)."\r\n");
	if ($log_file) fclose($log_file);

	return $result_total;
}
?>
<?php
function isEditable($id) {
	if (empty($id)) return false;

	$advego_name = get_post_meta($id, "advego_name", true);
	if (!(empty($advego_name))) {
		return false;
	}

	$advego_time_start = get_post_meta($id, "advego_time_start", true);
	
	if (empty($advego_time_start)) {
		return true;
	}

	$advego_time_stop = date("Y-m-d H:i:s");

	$latency = (strtotime($advego_time_stop)-strtotime($advego_time_start));
	if ($latency>900) {
		return true;
	}

	return false;
}
?>
<?php
function norm_highlight($complies_with) {
	global $wpdb;

	mb_internal_encoding("UTF-8");

	//Получим все доступные нормативы из таблицы БД
	$norms = $wpdb->get_results("SELECT DISTINCT name, name_full, file FROM wp_norms ORDER BY name ASC");

	//Попробуем найти каждый норматив в строке для разбора
	foreach ($norms as $norm) {

		//Длина названия этой нормы
		$norm_length = mb_strlen($norm->name);

		//Найдем норму в строке для разбора
		$first_letter = mb_strpos($complies_with, $norm->name);

		if (!($first_letter===false)) {
			$complies_with = mb_substr($complies_with, 0, $first_letter).'<a class="norm" href="/norms/'.$norm->file.'" title="'.$norm->name_full.'" target="_blank">'.mb_substr($complies_with, $first_letter, $norm_length).'</a>'.mb_substr($complies_with, $first_letter+$norm_length);
		}
	}

	return $complies_with;
}
?>
<?php
function norm_highlight_block($complies_with) {
	global $wpdb;

	mb_internal_encoding("UTF-8");

	//Получим все доступные нормативы из таблицы БД
	$norms = $wpdb->get_results("SELECT DISTINCT ID, name, name_full, file FROM wp_norms");
	$norms_num = 0;
	$counter = 0;

	$link_block = ''; //Код с блоком ссылок на стандарты

	//Попробуем найти каждый норматив в строке для разбора
	foreach ($norms as $norm) {
		$counter++;

		//Длина названия этой нормы
		$norm_length = mb_strlen($norm->name);

		//Найдем норму в строке для разбора
		$first_letter = mb_strpos($complies_with, $norm->name);

		if (!($first_letter===false)) {
			$complies_with = mb_substr($complies_with, 0, $first_letter).'<span class="norm" title="'.$norm->name_full.'">'.mb_substr($complies_with, $first_letter, $norm_length).'</span>'.mb_substr($complies_with, $first_letter+$norm_length);
			$norms_num++;

			if ($norms_num==1) $link_block = '<div id="norm_block"><div class="norm"><a href="'. site_url() .'/gosty/?param='.urlencode($norm->name).'" title="Скачать '.$norm->name.' - '.$norm->name_full.'"><strong>Скачать '.$norm->name.'</strong> - '.$norm->name_full.'</a></div>';
			else $link_block .= '<div class="norm"><a href="'. site_url() .'/gosty/?param='.urlencode($norm->name).'" title="Скачать '.$norm->name.' - '.$norm->name_full.'"><strong>Скачать '.$norm->name.'</strong> - '.$norm->name_full.'</a></div>';
		}

		if ($counter == count($norms) && ($norms_num>0)) $link_block .= '</div>';
	}

	if ($norms_num>0) {
		$complies_with .= '<div class="norm_download" onclick="document.getElementById(\'norm_block\').style.display=\'block\';">Скачать нормативы</div>';
	}
	
	$out = $complies_with.$link_block;
	return $out;
}
?>
<?php 
function mb_preg_match_all($pattern, $subject, &$matches, $flags = PREG_PATTERN_ORDER, $offset = 0, $encoding = null) {
    if($encoding == null) $encoding = mb_internal_encoding();
    
    $offset = strlen(mb_substr($subject, 0, $offset, $encoding));
    $result = preg_match_all($pattern, $subject, $matches, $flags, $offset);
    
    if ($result && ($flags & PREG_OFFSET_CAPTURE))
        foreach($matches as &$match)
            foreach($match as &$match)
                $match[1] = mb_strlen(substr($subject, 0, $match[1]), $encoding);
    
   	return $result;
}
?>
<?php
function isActualDates($source='', $nohtml=false) {

	date_default_timezone_set('Europe/Samara');
	
	if (empty($source)) {
		if ($nohtml) return false; else return '<span class="validity-false" title="Срок действия сертификата соответствия истек">Срок истек</span>';
	};

	$startDate = '';
	$endDate = '';

	for ($i = 0; $i<(mb_strlen($source)-1); $i++) {
		$curr = mb_substr($source, $i, 1);
		if (($curr=='0') || ($curr=='1') || ($curr=='2') || ($curr=='3') || ($curr=='4') || ($curr=='5') || ($curr=='6') || ($curr=='7') || ($curr=='8') || ($curr=='9')) {
				if ($startDate=='') $startDate = mb_substr($source, $i, 10); else $endDate = mb_substr($source, $i, 10);
				$i = $i+10;
		}
	}

	if ($endDate=='') {
		if ($nohtml) return true; else return '<span class="validity-true" title="Действующий сертификат соответствия">Действующий</span>';
	};

	$currentDate = date('d.m.Y');

	$startDate = strtotime($startDate);
	$endDate = strtotime($endDate);
	$currentDate = strtotime($currentDate);

	if (($currentDate>=$startDate) && ($currentDate<=$endDate)) {
		if ($nohtml) return true; else return '<span class="validity-true" title="Действующий сертификат соответствия">Действующий</span>';
	};

	if ($nohtml) return false; else return '<span class="validity-false" title="Срок действия сертификата соответствия истек">Срок истек</span>';
}
?>
<?php function sortActual($post_ids) {

	$actual = array();
	$others = array();
	
	if (!empty($post_ids))

	foreach ($post_ids as $post_id) {
		$is_actual = isActualDates(get_post_meta($post_id, "param2_validity", true), true);
		if ($is_actual) array_push($actual, $post_id); else array_push($others, $post_id);
	} else return null;

	return array_merge($actual, $others);
}
?>
<?php function sortActualByPosts($posts) {

	$actual = array();
	$others = array();
	foreach ($posts as $post) {
		$is_actual = isActualDates(get_post_meta($post->ID, "param2_validity", true), true);
		if ($is_actual) array_push($actual, $post); else array_push($others, $post);
	};

	return array_merge($actual, $others);
}
?>
<?php
function getCountryCode($source='') {
	if (empty($source)) return '';

	$source = mb_strtolower($source);

	/*Для декларация соответствия*/

	if (!(mb_strpos($source, '-ru')===false)) return 'ru';
	if (!(mb_strpos($source, '-by')===false)) return 'by';
	if (!(mb_strpos($source, '-ua')===false)) return 'ua';
	if (!(mb_strpos($source, '-us')===false)) return 'us';
	if (!(mb_strpos($source, '-de')===false)) return 'de';
	if (!(mb_strpos($source, '-kr')===false)) return 'kr';
	if (!(mb_strpos($source, '-es')===false)) return 'es';
	if (!(mb_strpos($source, '-cn')===false)) return 'cn';
	if (!(mb_strpos($source, '-kz')===false)) return 'kz';
	if (!(mb_strpos($source, '-tr')===false)) return 'tr';
	if (!(mb_strpos($source, '-hk')===false)) return 'hk';
	if (!(mb_strpos($source, '-fi')===false)) return 'fi';
	if (!(mb_strpos($source, '-lv')===false)) return 'lv';
	if (!(mb_strpos($source, '-jp')===false)) return 'jp';
	if (!(mb_strpos($source, '-fr')===false)) return 'fr';
	if (!(mb_strpos($source, '-tw')===false)) return 'tw';
	if (!(mb_strpos($source, '-si')===false)) return 'si';
	if (!(mb_strpos($source, '-sk')===false)) return 'sk';
	if (!(mb_strpos($source, '-pl')===false)) return 'pl';
	if (!(mb_strpos($source, '-it')===false)) return 'it';
	if (!(mb_strpos($source, '-cz')===false)) return 'cz';
	if (!(mb_strpos($source, '-at')===false)) return 'at';
	if (!(mb_strpos($source, '-ch')===false)) return 'ch';
	if (!(mb_strpos($source, '-gb')===false)) return 'gb';
	if (!(mb_strpos($source, '-nl')===false)) return 'nl';
	if (!(mb_strpos($source, '-se')===false)) return 'se';
	if (!(mb_strpos($source, '-li')===false)) return 'li';
	if (!(mb_strpos($source, '-rs')===false)) return 'rs';
	if (!(mb_strpos($source, '-sg')===false)) return 'sg';
	if (!(mb_strpos($source, '-th')===false)) return 'th';
	if (!(mb_strpos($source, '-bg')===false)) return 'bg';
	if (!(mb_strpos($source, '-gr')===false)) return 'gr';
	if (!(mb_strpos($source, '-vn')===false)) return 'vn';
	if (!(mb_strpos($source, '-pt')===false)) return 'pt';
	if (!(mb_strpos($source, '-ee')===false)) return 'ee';
	if (!(mb_strpos($source, '-be')===false)) return 'be';
	if (!(mb_strpos($source, '-dk')===false)) return 'dk';
	if (!(mb_strpos($source, '-ca')===false)) return 'ca';
	if (!(mb_strpos($source, '-mk')===false)) return 'mk';
	if (!(mb_strpos($source, '-my')===false)) return 'my';
	if (!(mb_strpos($source, '-bb')===false)) return 'bb';
	if (!(mb_strpos($source, '-in')===false)) return 'in';
	if (!(mb_strpos($source, '-ma')===false)) return 'ma';
	if (!(mb_strpos($source, '-cy')===false)) return 'cy';
	if (!(mb_strpos($source, '-id')===false)) return 'id';
	if (!(mb_strpos($source, '-mx')===false)) return 'mx';
    if (!(mb_strpos($source, '-bm')===false)) return 'bm';
    if (!(mb_strpos($source, '-ae')===false)) return 'ae';
    if (!(mb_strpos($source, '-md')===false)) return 'md';
    if (!(mb_strpos($source, '-ro')===false)) return 'ro';
    if (!(mb_strpos($source, '-hu')===false)) return 'hu';
    if (!(mb_strpos($source, '-lu')===false)) return 'lu';
    if (!(mb_strpos($source, '-no')===false)) return 'no';
    if (!(mb_strpos($source, '-vg')===false)) return 'vg';


	/*Для сертификатов соответствия*/

	if (!(mb_strpos($source, 'ru')===false)) return 'ru';
	if (!(mb_strpos($source, 'by')===false)) return 'by';
	if (!(mb_strpos($source, 'ua')===false)) return 'ua';
	if (!(mb_strpos($source, 'us')===false)) return 'us';
	if (!(mb_strpos($source, 'de')===false)) return 'de';
	if (!(mb_strpos($source, 'kr')===false)) return 'kr';
	if (!(mb_strpos($source, 'es')===false)) return 'es';
	if (!(mb_strpos($source, 'cn')===false)) return 'cn';
	if (!(mb_strpos($source, 'kz')===false)) return 'kz';
	if (!(mb_strpos($source, 'tr')===false)) return 'tr';
	if (!(mb_strpos($source, 'hk')===false)) return 'hk';
	if (!(mb_strpos($source, 'fi')===false)) return 'fi';
	if (!(mb_strpos($source, 'lv')===false)) return 'lv';
	if (!(mb_strpos($source, 'jp')===false)) return 'jp';
	if (!(mb_strpos($source, 'fr')===false)) return 'fr';
	if (!(mb_strpos($source, 'tw')===false)) return 'tw';
	if (!(mb_strpos($source, 'si')===false)) return 'si';
	if (!(mb_strpos($source, 'sk')===false)) return 'sk';
	if (!(mb_strpos($source, 'pl')===false)) return 'pl';
	if (!(mb_strpos($source, 'it')===false)) return 'it';
	if (!(mb_strpos($source, 'cz')===false)) return 'cz';
	if (!(mb_strpos($source, 'at')===false)) return 'at';
	if (!(mb_strpos($source, 'ch')===false)) return 'ch';
	if (!(mb_strpos($source, 'gb')===false)) return 'gb';
	if (!(mb_strpos($source, 'nl')===false)) return 'nl';
	if (!(mb_strpos($source, 'se')===false)) return 'se';
	if (!(mb_strpos($source, 'li')===false)) return 'li';
	if (!(mb_strpos($source, 'rs')===false)) return 'rs';
	if (!(mb_strpos($source, 'sg')===false)) return 'sg';
	if (!(mb_strpos($source, 'th')===false)) return 'th';
	if (!(mb_strpos($source, 'bg')===false)) return 'bg';
	if (!(mb_strpos($source, 'gr')===false)) return 'gr';
	if (!(mb_strpos($source, 'vn')===false)) return 'vn';
	if (!(mb_strpos($source, 'pt')===false)) return 'pt';
	if (!(mb_strpos($source, 'ee')===false)) return 'ee';
	if (!(mb_strpos($source, 'be')===false)) return 'be';
	if (!(mb_strpos($source, 'dk')===false)) return 'dk';
	if (!(mb_strpos($source, 'ca')===false)) return 'ca';
	if (!(mb_strpos($source, 'mk')===false)) return 'mk';
	if (!(mb_strpos($source, 'my')===false)) return 'my';
	if (!(mb_strpos($source, 'bb')===false)) return 'bb';
	if (!(mb_strpos($source, 'in')===false)) return 'in';
	if (!(mb_strpos($source, 'ma')===false)) return 'ma';
	if (!(mb_strpos($source, 'cy')===false)) return 'cy';
	if (!(mb_strpos($source, 'id')===false)) return 'id';
	if (!(mb_strpos($source, 'mx')===false)) return 'mx';
    if (!(mb_strpos($source, 'bm')===false)) return 'bm';
    if (!(mb_strpos($source, 'ae')===false)) return 'ae';
    if (!(mb_strpos($source, 'md')===false)) return 'md';
    if (!(mb_strpos($source, 'ro')===false)) return 'ro';
    if (!(mb_strpos($source, 'hu')===false)) return 'hu';
    if (!(mb_strpos($source, 'lu')===false)) return 'lu';
    if (!(mb_strpos($source, 'no')===false)) return 'no';
    if (!(mb_strpos($source, 'vg')===false)) return 'vg';

	return '';
}
?>
<?php
function getCountry($source='') {
	if (empty($source)) return '';

	$source = mb_strtolower($source);
	$country = getCountryCode($source);

	switch ($country) {
		case 'ru': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ru.png" alt="RU" title="Произведено в России" /></div>'; break;
		case 'by': return '<div class="flag"><img src="'.site_url().'/imgs/flags/by.png" alt="BY" title="Произведено в Белоруссии" /></div>'; break;
		case 'ua': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ua.png" alt="UA" title="Произведено в Украине" /></div>'; break;
		case 'us': return '<div class="flag"><img src="'.site_url().'/imgs/flags/us.png" alt="US" title="Произведено в США" /></div>'; break;
		case 'de': return '<div class="flag"><img src="'.site_url().'/imgs/flags/de.png" alt="DE" title="Произведено в Германии" /></div>'; break;
		case 'kr': return '<div class="flag"><img src="'.site_url().'/imgs/flags/kr.png" alt="KR" title="Произведено в Республике Корея" /></div>'; break;
		case 'es': return '<div class="flag"><img src="'.site_url().'/imgs/flags/es.png" alt="ES" title="Произведено в Испании" /></div>'; break;
		case 'cn': return '<div class="flag"><img src="'.site_url().'/imgs/flags/cn.png" alt="CN" title="Произведено в Китае" /></div>'; break;
		case 'kz': return '<div class="flag"><img src="'.site_url().'/imgs/flags/kz.png" alt="KZ" title="Произведено в Казахстане" /></div>'; break;
		case 'tr': return '<div class="flag"><img src="'.site_url().'/imgs/flags/tr.png" alt="TR" title="Произведено в Турции" /></div>'; break;
		case 'hk': return '<div class="flag"><img src="'.site_url().'/imgs/flags/hk.png" alt="HK" title="Произведено в Гонконге" /></div>'; break;
		case 'fi': return '<div class="flag"><img src="'.site_url().'/imgs/flags/fi.png" alt="FI" title="Произведено в Финляндии" /></div>'; break;
		case 'lv': return '<div class="flag"><img src="'.site_url().'/imgs/flags/lv.png" alt="LV" title="Произведено в Латвии" /></div>'; break;
		case 'jp': return '<div class="flag"><img src="'.site_url().'/imgs/flags/jp.png" alt="JP" title="Произведено в Японии" /></div>'; break;
		case 'fr': return '<div class="flag"><img src="'.site_url().'/imgs/flags/fr.png" alt="FR" title="Произведено во Франции" /></div>'; break;
		case 'tw': return '<div class="flag"><img src="'.site_url().'/imgs/flags/tw.png" alt="TW" title="Произведено на Тайване" /></div>'; break;
		case 'si': return '<div class="flag"><img src="'.site_url().'/imgs/flags/si.png" alt="SI" title="Произведено в Словении" /></div>'; break;
		case 'sk': return '<div class="flag"><img src="'.site_url().'/imgs/flags/sk.png" alt="SK" title="Произведено в Словакии" /></div>'; break;
		case 'pl': return '<div class="flag"><img src="'.site_url().'/imgs/flags/pl.png" alt="PL" title="Произведено в Польше" /></div>'; break;
		case 'it': return '<div class="flag"><img src="'.site_url().'/imgs/flags/it.png" alt="IT" title="Произведено в Италии" /></div>'; break;
		case 'cz': return '<div class="flag"><img src="'.site_url().'/imgs/flags/cz.png" alt="CZ" title="Произведено в Чехии" /></div>'; break;
		case 'at': return '<div class="flag"><img src="'.site_url().'/imgs/flags/at.png" alt="AT" title="Произведено в Австрии" /></div>'; break;
		case 'ch': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ch.png" alt="CH" title="Произведено в Швейцарии" /></div>'; break;
		case 'gb': return '<div class="flag"><img src="'.site_url().'/imgs/flags/gb.png" alt="GB" title="Произведено в Великобритании" /></div>'; break;
		case 'nl': return '<div class="flag"><img src="'.site_url().'/imgs/flags/nl.png" alt="NL" title="Произведено в Нидерландах" /></div>'; break;
		case 'se': return '<div class="flag"><img src="'.site_url().'/imgs/flags/se.png" alt="SE" title="Произведено в Швеции" /></div>'; break;
		case 'li': return '<div class="flag"><img src="'.site_url().'/imgs/flags/li.png" alt="LI" title="Произведено в Лихтенштейне" /></div>'; break;
		case 'rs': return '<div class="flag"><img src="'.site_url().'/imgs/flags/rs.png" alt="RS" title="Произведено в Сербии" /></div>'; break;
		case 'sg': return '<div class="flag"><img src="'.site_url().'/imgs/flags/sg.png" alt="SG" title="Произведено в Сингапуре" /></div>'; break;
		case 'th': return '<div class="flag"><img src="'.site_url().'/imgs/flags/th.png" alt="TH" title="Произведено в Тайланде" /></div>'; break;
		case 'bg': return '<div class="flag"><img src="'.site_url().'/imgs/flags/bg.png" alt="BG" title="Произведено в Болгарии" /></div>'; break;
		case 'gr': return '<div class="flag"><img src="'.site_url().'/imgs/flags/gr.png" alt="GR" title="Произведено в Греции" /></div>'; break;
		case 'vn': return '<div class="flag"><img src="'.site_url().'/imgs/flags/vn.png" alt="VN" title="Произведено во Вьетнаме" /></div>'; break;
		case 'pt': return '<div class="flag"><img src="'.site_url().'/imgs/flags/pt.png" alt="PT" title="Произведено в Португалии" /></div>'; break;
		case 'ee': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ee.png" alt="EE" title="Произведено в Эстонии" /></div>'; break;
		case 'be': return '<div class="flag"><img src="'.site_url().'/imgs/flags/be.png" alt="BE" title="Произведено в Бельгии" /></div>'; break;
		case 'dk': return '<div class="flag"><img src="'.site_url().'/imgs/flags/dk.png" alt="DK" title="Произведено в Дании" /></div>'; break;
		case 'ca': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ca.png" alt="CA" title="Произведено в Канаде" /></div>'; break;
		case 'mk': return '<div class="flag"><img src="'.site_url().'/imgs/flags/mk.png" alt="MK" title="Произведено в Македонии" /></div>'; break;
		case 'my': return '<div class="flag"><img src="'.site_url().'/imgs/flags/my.png" alt="MY" title="Произведено в Малайзии" /></div>'; break;
		case 'bb': return '<div class="flag"><img src="'.site_url().'/imgs/flags/bb.png" alt="BB" title="Произведено на Барбадосе" /></div>'; break;
		case 'in': return '<div class="flag"><img src="'.site_url().'/imgs/flags/in.png" alt="IN" title="Произведено в Индии" /></div>'; break;
		case 'ma': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ma.png" alt="MA" title="Произведено в Марокко" /></div>'; break;
		case 'cy': return '<div class="flag"><img src="'.site_url().'/imgs/flags/cy.png" alt="CY" title="Произведено на Кипре" /></div>'; break;
		case 'id': return '<div class="flag"><img src="'.site_url().'/imgs/flags/id.png" alt="ID" title="Произведено в Индонезии" /></div>'; break;
		case 'mx': return '<div class="flag"><img src="'.site_url().'/imgs/flags/mx.png" alt="MX" title="Произведено в Мексике" /></div>'; break;
	    case 'bm': return '<div class="flag"><img src="'.site_url().'/imgs/flags/bm.png" alt="BM" title="Произведено на Бермудских островах" /></div>'; break;
	    case 'ae': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ae.png" alt="AE" title="Произведено в ОАЭ" /></div>'; break;
	    case 'md': return '<div class="flag"><img src="'.site_url().'/imgs/flags/md.png" alt="MD" title="Произведено в Республике Молдова" /></div>'; break;
	    case 'ro': return '<div class="flag"><img src="'.site_url().'/imgs/flags/ro.png" alt="RO" title="Произведено в Румынии" /></div>'; break;
	    case 'hu': return '<div class="flag"><img src="'.site_url().'/imgs/flags/hu.png" alt="HU" title="Произведено в Венгрии" /></div>'; break;
	    case 'lu': return '<div class="flag"><img src="'.site_url().'/imgs/flags/lu.png" alt="LU" title="Произведено в Люксембурге" /></div>'; break;
	    case 'no': return '<div class="flag"><img src="'.site_url().'/imgs/flags/no.png" alt="NO" title="Произведено в Норвегии" /></div>'; break;
	    case 'vg': return '<div class="flag"><img src="'.site_url().'/imgs/flags/vg.png" alt="VG" title="Произведено на Британских Виргинских островах" /></div>'; break;
	}
	return '';
}
?>
<?php
function getCategoryTree($category){
	global $post;

	$out = array();

	if (empty($category)) {
		$cat = get_the_category($post->ID);
		$cat_id = $cat[0]->cat_ID;
	} else {
		$cat = $category;
		$cat_id = $cat->cat_ID;
	}
	
	$cat_link = get_category_link($cat_id);
	$cat_name = get_cat_name($cat_id);
	array_push($out, '<div class="bolden"><a itemprop="item" href="'.$cat_link.'" title="Сертификаты на продукцию '.$cat_name.'">'.$cat_name.'<meta itemprop="name" content="'.cutStringToWords($cat_name, 30).'">');

	$ancestors = get_ancestors($cat_id, 'category');

	foreach ($ancestors as $cat => $id) {
		if (($id!=37) && ($id!=316) && ($id!=38)) {

			$cat_link = get_category_link($id);
			$cat_name = get_cat_name($id);

			array_push($out, '<a itemprop="item" href="'.$cat_link.'" title="Сертификаты на продукцию '.$cat_name.'">'.$cat_name.'<meta itemprop="name" content="'.cutStringToWords($cat_name, 30).'">');
		}
	}

	$out = array_reverse($out);
	
	$result = '<div class="cattree" itemscope itemtype="https://schema.org/BreadcrumbList">'."\r\n";
	$num = 1;
	foreach ($out as $outret) {
		$result .='<div class="ancestor" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'.$outret.'<meta itemprop="position" content="'.$num.'"></a></div>'."\r\n";
		$num++;
	}

	$result .= "</div></div>"."\r\n";

	return $result;
}
?>
<?php
function getCategoryType($id){
	global $post;

	$cat = get_the_category($id);
	$cat_id = $cat[0]->cat_ID;

	if (cat_is_ancestor_of(37, $cat_id) or is_category(37)) {
		return 'ОКП';
	}

	if (cat_is_ancestor_of(38, $cat_id) or is_category(38)) {
		return 'ТН ВЭД ТС';
	}

	if (cat_is_ancestor_of(316, $cat_id) or is_category(316)) {
		return 'ОКУН';
	}

	return 'ОКП/ТН ВЭД ТС/ОКУН';
}
?>
<?php
/*
    get_thmb1
    Ранее передавали сам $post, а теперь $postId = $post->ID
 */
function getCertThmb($postId = null) {
    global $post;

    if (!$postId) {
        $postId = $post->ID;
    }

	$thmb = get_post_meta($postId, "img_thmb", $single = true);

	if ($thmb !== '') return $thmb;

	$imgFull = get_post_meta($postId, "img_download_link", $single = true);

	if ($imgFull === '') return '';

	$image = new SimpleImage();

   	$image->load('download/'.$imgFull);s
   	$image->resize(454, 650);
   	$image->save('thumbs/thmb_'.$imgFull);

   	update_post_meta($postId, "img_thmb", 'thmb_'.$imgFull);

   	return 'thmb_'.$imgFull;
}
/*
    Ранее передавали сам $post, а теперь $postId = $post->ID
 */
function getCertThmb2($postId = null) {
    global $post;

    if (!$postId) {
        $postId = $post->ID;
    }

    $thmb = get_post_meta($postId, "img_thmb2", $single = true);

    if ($thmb !== '') return $thmb;

    $imgFull = get_post_meta($postId, "img_download_link", $single = true);

    if ($imgFull === '') return '';

    $image = new SimpleImage();

    $image->load('download/'.$imgFull);s
   	$image->resize(454, 650);
   	$image->save('thumbs/thmb_'.$imgFull);

   	update_post_meta($postId, "img_thmb2", 'thmb_'.$imgFull);

   	return 'thmb_'.$imgFull;
}
?>
<?php
if (!function_exists('mb_ucfirst') && extension_loaded('mbstring'))
{
    /**
     * mb_ucfirst - преобразует первый символ в верхний регистр
     * @param string $str - строка
     * @param string $encoding - кодировка, по-умолчанию UTF-8
     * @return string
     */
    function mb_ucfirst($str, $encoding='UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
               mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}
?>
<?php
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
?>
<?php
function can_upload($file){
	// если имя пустое, значит файл не выбран
    if ($file['name'] == '')
		return 'Ошибка! Вы не выбрали файл';
	
	/* если размер файла 0, значит его не пропустили настройки 
	сервера из-за того, что он слишком большой */
	if ($file['size'] == 0)
		return 'Ошибка! Файл слишком большой';

	
	/* Если MIME типа файла не jpg или progressive-jpg*/
	if (($file['type'] != 'image/jpeg') && ($file['type'] != 'image/pjpeg'))
		return 'Ошибка! Файл - не изображение JPG (FileType), а имеет формат: '.$file['type'];

	$imageinfo = getimagesize($file['tmp_name']);
	/* Если MIME типа файла не jpg или progressive-jpg*/
 	if (($imageinfo['mime'] != 'image/jpeg') && ($imageinfo['mime'] != 'image/pjpeg')) {
  		return "Ошибка! Файл - не изображение JPG (GetImageSize), а имеет формат: ".$imageinfo['mime'];
  	}
	
	// разбиваем имя файла по точке и получаем массив
	$getMime = explode('.', $file['name']);
	// нас интересует последний элемент массива - расширение
	$mime = strtolower(end($getMime));
	// объявим массив допустимых расширений
	$types = array('jpg');
	
	// если расширение не входит в список допустимых - return
	if(!in_array($mime, $types))
		return 'Ошибка! Недопустимый тип файла.';
	
	return true;
}
function make_upload($file) {	
	// формируем уникальное имя картинки: случайное число и name
	$name = mt_rand(1000, 9999) . $file['name'];
	copy($file['tmp_name'], 'upload/' . $name);
	return $name;
  }
function cut_to_ocr($file) {
	if (!(isset($file))) return false;

	//Ресайз изображения до 1500px по горизонтали, если оно другого размера
	//1500px - ширина изображения по дефолту

	$image = new SimpleImage();

   	$image->load('upload/'.$file);
   	$image->resize(1500, 2146, 'y');

	$info = pathinfo($file);
	$filename = basename($file,'.'.$info['extension']);

	mkdir('upload/'.$filename);
   	$image->save('upload/'.$filename.'/'.$filename.'_resized'.'.'.$info['extension']);

	//Нарежем изображение на блоки для распознавания
	//Массив координат для сертификата ГОСТ Р (красный и синий)

   	//№ Сертификата
	$gost_coords[] = array(
		'x1' => 500,
		'y1' => 330,
		'x2' => 1000,
		'y2' => 380
	);

	//Срок действия
	$gost_coords[] = array(
		'x1' => 700,
		'y1' => 385,
		'x2' => 1300,
		'y2' => 430
	);

	//Код ОКП или ТН ВЭД ТС или ОКУН
	$gost_coords[] = array(
		'x1' => 1130,
		'y1' => 770,
		'x2' => 1445,
		'y2' => 1130
	);

	//Орган по сертификации
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 485,
		'x2' => 1445,
		'y2' => 700
	);

	//Продукция
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 700,
		'x2' => 1130,
		'y2' => 920
	);

	//Соответствует требованиям
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 925,
		'x2' => 1130,
		'y2' => 1170
	);

	//Изготовитель
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 1160,
		'x2' => 1445,
		'y2' => 1305
	);

	//Сертификат выдан
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 1300,
		'x2' => 1445,
		'y2' => 1455
	);

	//На основании
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 1455,
		'x2' => 1445,
		'y2' => 1735
	);

	//Дополнительная информация
	$gost_coords[] = array(
		'x1' => 50,
		'y1' => 1735,
		'x2' => 1445,
		'y2' => 1905
	);

	$i = 0;
	foreach ($gost_coords as $coords) {
		$image->load('upload/'.$filename.'/'.$filename.'_resized'.'.'.$info['extension']);
		$image->crop($coords['x1'], $coords['y1'], $coords['x2']-$coords['x1'], $coords['y2']-$coords['y1']);
		$image->save('upload/'.$filename.'/'.$filename.'_gost_r_'.$i.'.'.$info['extension']);

		$i=$i+1;
	}

	$i = 0;
	foreach ($gost_coords as $coords) {
		$image->load('upload/'.$filename.'/'.$filename.'_resized'.'.'.$info['extension']);
		$image->crop($coords['x1'], $coords['y1'], $coords['x2']-$coords['x1'], $coords['y2']-$coords['y1']);
		$image->save('upload/'.$filename.'/'.$filename.'_gost_b_'.$i.'.'.$info['extension']);

		$i=$i+1;
	}


	//Массив координат для сертификата ТН ВЭД ТС

   	//№ Сертификата
	$tnvedts_coords[] = array(
		'x1' => 425,
		'y1' => 200,
		'x2' => 1000,
		'y2' => 265
	);

	//Орган по сертификации
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 310,
		'x2' => 1445,
		'y2' => 520
	);

	//Заявитель
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 515,
		'x2' => 1445,
		'y2' => 685
	);

	//Изготовитель
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 685,
		'x2' => 1445,
		'y2' => 850
	);

	//Продукция (услуга, работа)
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 850,
		'x2' => 1445,
		'y2' => 1085
	);

	//Код ТН ВЭД ТС
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 1085,
		'x2' => 1145,
		'y2' => 1150
	);

	//Соответствует требованиям
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 1150,
		'x2' => 1445,
		'y2' => 1395
	);

	//На основании
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 1395,
		'x2' => 1445,
		'y2' => 1685
	);

	//Дополнительная информация
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 1685,
		'x2' => 1445,
		'y2' => 1835
	);

	//Срок действия
	$tnvedts_coords[] = array(
		'x1' => 60,
		'y1' => 1835,
		'x2' => 1445,
		'y2' => 1905
	);

	$i = 0;


	foreach ($tnvedts_coords as $coords) {
		$image->load('upload/'.$filename.'/'.$filename.'_resized'.'.'.$info['extension']);
		$image->crop($coords['x1'], $coords['y1'], $coords['x2']-$coords['x1'], $coords['y2']-$coords['y1']);
		$image->save('upload/'.$filename.'/'.$filename.'_tnvedts_'.$i.'.'.$info['extension']);

		$i=$i+1;
	}


	return $filename;
}
?>
<?php
function getAllCompanies($num=null) {
	global $wpdb;

	$companies = array();
	$companies_sorted = array();

	/*Получим все возможные значения param6_manufacturer*/

	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param6_manufacturer'");

	/*Очистим название организации от лишней шелухи*/

	foreach($rec as $r){ 
		$manufacturer_clean = getManufacturer($r, false);
		if ($manufacturer_clean!== '') array_push($companies, $manufacturer_clean);
	}

	/*Посчитаем количество каждой организации*/

	foreach ($companies as $company) {
		$companies_sorted[$company]=$companies_sorted[$company]+1;
	}

	/*Отсортируем по убыванию количества повторений*/

	arsort($companies_sorted);

	/*Обрежем количество организаций до требуемого*/
	$companies_sorted = array_slice($companies_sorted, 0, $num);

	/*Отсортируем по алфавиту*/
	ksort($companies_sorted);
	
	return $companies_sorted;
}
?>
<?php
function getAllNotEmphCompanies() {
	global $wpdb;

	$companies = array();

	/*Получим все возможные значения param6_manufacturer*/

	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param6_manufacturer'");

	/*Если не удается выделить название организации - он нам и нужна*/

	foreach($rec as $r){ 
		$manufacturer_clean = getManufacturer($r, false);
		if ($manufacturer_clean=='') array_push($companies, $r);
	}

	/*Отсортируем по алфавиту*/
	asort($companies);

	return $companies;
}
?>
<?php
function getAllNorms($num=null) {
	global $wpdb;

	$norms = array();

	/*Получим все возможные значения ID, name, name_full из таблицы wp_norms, отсортированные по популярности*/

	$rec = $wpdb->get_results("SELECT ID, name, name_full, file FROM wp_norms ORDER BY download_count DESC");

	/*Очистим название организации от лишней шелухи*/

	$norms = $rec;

	/*Обрежем количество нормативов до требуемого*/
	$norms = array_slice($norms, 0, $num);

	return $norms;
}
?>
<?php
function getNormsByName($name) {
	global $wpdb;

	if ($name=='') return false;


	$rec = $wpdb->get_results($wpdb->prepare("SELECT ID, name, name_full, file FROM wp_norms WHERE name LIKE '%$name%'", $name));

	return $rec;
}
?>
<?php
function getAllCountries($num=10) {
	global $wpdb;

	$countries = array();
	$countries_sorted = array();

	/*Получим все возможные значения param1_number*/
	
	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param1_number'");

	/*Получим из номера сертификата страну происхождения*/

	foreach($rec as $r){ 
		$country_clean = getCountryCode($r);
		array_push($countries, $country_clean);
	}

	/*Посчитаем количество каждой страны*/

 
	foreach ($countries as $country) {
		if (!array_key_exists($country, $countries_sorted)) $countries_sorted += [$country => 1]; else $countries_sorted[$country]++;
	}

    /*Отсортируем по убыванию количества повторений*/

	arsort($countries_sorted);

	/*Обрежем количество стран до требуемого*/
	$countries_sorted = array_slice($countries_sorted, 0, $num);

	return $countries_sorted;
}
?>
<?php
function getManufacturer($manufacturer, $outp = true) {
    if (!isset($manufacturer)) return '';

	$fs = -1;
	$ed = -1;

	$fs = mb_strpos($manufacturer, '"');

    if ($fs === false) $fs = mb_strpos($manufacturer, '«');
	if ($fs === false) $fs = mb_strpos($manufacturer, '“');


    $ed = mb_strpos($manufacturer, '"', $fs+1);

	if ($ed === false) $ed = mb_strpos($manufacturer, '»', $fs+1);
	if ($ed === false) $ed = mb_strpos($manufacturer, '”', $fs+1);

	if (($fs === false) || ($ed === false)) {
		if ($outp) return $manufacturer; else return '';
	}

	$manufacturer_clean = mb_substr($manufacturer, $fs + 1, $ed - $fs - 1);

	$symarr = array();

	for ($i = 0; $i < mb_strlen($manufacturer_clean); $i++) {
		$sym = mb_substr($manufacturer_clean, $i, 1);
		if (($sym=='«') || ($sym=='“') || ($sym=='»') || ($sym=='”') || ($sym=='"')) {
			array_push($symarr, $sym);
		}
	}

	if (count($symarr) > 0) {
		end($symarr);
		if (current($symarr)=='«') $manufacturer_clean=$manufacturer_clean.'»';
		if (current($symarr)=='“') $manufacturer_clean=$manufacturer_clean.'”';
		if (current($symarr)=='"') $manufacturer_clean=$manufacturer_clean.'"';
	}

	$out='';
	
	if ($outp) {
        $logo_pic = getManufacturerLogo($manufacturer_clean);
        if ($logo_pic) $sizes = newSizes(site_url().'/logos/'.$logo_pic);
        if ($sizes) $out.='<div class="company_logo" style="background: url('.site_url().'/logos/'.getManufacturerLogo($manufacturer_clean).'); background-size: contain; background-repeat: no-repeat; background-position: top; width: '.$sizes[0].'px ; height: '.$sizes[1].'px;"></div>';
		$out.='<div class="manufacturer_text">';
		$out.=$manufacturer;
		$out.='</div>';
		$out.='<meta itemprop="manufacturer" content="'.$manufacturer_clean.'">';
		$out.='<div style="clear:both"></div>';
		$out.='<a href="'. site_url() .'/kompanii/?param='.urlencode($manufacturer_clean).'"><div class="manufacturer_go">Другая продукция этого изготовителя</div></a>';
	} else $out=$manufacturer_clean;
	return $out;
}
?>
<?php
function getManufacturerLogo($manufacturer, $nohtml=true) {
	global $wpdb;

	if ($manufacturer==='') {
		if ($nohtml) return ''; else return '<!-- no '.$manufacturer.' logo found-->';
	}

	$out = '';

	$rec = $wpdb->get_row($wpdb->prepare("SELECT link FROM wp_logos WHERE name LIKE '$manufacturer'", $manufacturer));

	if (isset($rec->link)) {
		if ($nohtml) $out .= $rec->link; else $out.='<div class="manufacturer_logo"><img src="/logos/'.$rec->link.'" alt="'.$manufacturer.'" title="'.$manufacturer.'" /></div>';
	} else {
		if ($nohtml) return false; else return '<!-- no '.$manufacturer.' logo found-->';
	}

	return $out;
}
?>
<?php
function getManufacturerA($manufacturer) {
	if ($manufacturer==='') return '';

	$fs = -1;
	$en = -1;
	$symarr = array();

	for ($i=0;$i<mb_strlen($manufacturer);$i++) {
		$sym = mb_substr($manufacturer,$i,1);
		if (($sym=='«') || ($sym=='“') || ($sym=='»') || ($sym=='”') || ($sym=='"')) {
			array_push($symarr, $i);
		}
	}

	$count = count($symarr);

	$fs=array_shift($symarr);
	$ed=array_pop($symarr);

	if ($count>2) $manufacturer_clean = mb_substr($manufacturer,$fs+1,$ed-$fs); else $manufacturer_clean = mb_substr($manufacturer,$fs+1,$ed-$fs-1);

	$out='';
	$out.=$manufacturer.'<br>';
	if ($count>=2) $out.='<a href="/kompanii/?param='.urlencode($manufacturer_clean).'"><div class="manufacturer_go">Другая продукция этого изготовителя</div></a>';
	return $out;
}
?>
<?php
function getCertAgency($str) {
	if ($str==='') return false;
	
	$st = mb_strpos($str, 'РОСС RU.');
	
	if ($st!==false) $agency=mb_substr($str, $st, 19);
			
	$st = mb_strpos($str, 'RA.RU.');
	if ($st!==false) $agency= mb_substr($str, $st, 12);

	$st = mb_strpos($str, 'РОСС ВY.');
	if ($st!==false) $agency= mb_substr($str, $st, 19);

	if (empty($agency)) return false;
	
	return $agency;	
}
?>
<?php
function getCertAgencyButton($certification_agency) {
    if (!isset($certification_agency)) return '';

    global $wpdb;

	$agencyName = getManufacturer($certification_agency, false);

	if (!($agencyName==='')) $link='<a href="'. site_url() .'/organy-po-sertifikacii/?param='.urlencode($agencyName).'" title="Сертификаты выданные '.$agencyName.'" ><div class="manufacturer_go">Другие сертификаты выданные органом</div></a>';

	$st = mb_strpos($certification_agency, 'РОСС RU.');
	//echo '<b>ST: '.$st.'</b><br/>';
	if ($st!==false) {
		$pre = mb_substr($certification_agency, 0, $st);
		$agency=mb_substr($certification_agency, $st, 19);
		$aft = mb_substr($certification_agency, $st+19);
		//echo '<b>PRE: '.$pre.'</b><br/>';
		//echo '<b>AGENCY: '.$agency.'</b><br/>';
		//echo '<b>AFT: '.$aft.'</b><br/>';

	}

	$st = mb_strpos($certification_agency, 'РОСС BY.');
	//echo '<b>ST: '.$st.'</b><br/>';
	if ($st!==false) {
		$pre = mb_substr($certification_agency, 0, $st);
		$agency=mb_substr($certification_agency, $st, 19);
		$aft = mb_substr($certification_agency, $st+19);
		//echo '<b>PRE: '.$pre.'</b><br/>';
		//echo '<b>AGENCY: '.$agency.'</b><br/>';
		//echo '<b>AFT: '.$aft.'</b><br/>';

	} 
	
	$st = mb_strpos($certification_agency, 'RA.RU.');
	//echo '<b>ST: '.$st.'</b><br/>';
	if ($st!==false) {
		$pre = mb_substr($certification_agency, 0, $st);
		$agency= mb_substr($certification_agency, $st, 12);
		$aft = mb_substr($certification_agency, $st+12);
		//echo '<b>PRE: '.$pre.'</b><br/>';
		//echo '<b>AGENCY: '.$agency.'</b><br/>';
		//echo '<b>AFT: '.$aft.'</b><br/>';
	} 

	if (!(empty($agency))) {

		$rec = $wpdb->get_row($wpdb->prepare("SELECT link FROM wp_certagency WHERE regnum LIKE '$agency'", $agency));

		if (isset($rec->link)) $link.='<a href="'.$rec->link.'" rel="nofollow" target="_blank" title="Официальный сайт '.$agencyName.'"><div class="manufacturer_go">Перейти на сайт органа по сертификации</div></a>';
	}

	return $pre.'<strong>'.$agency.'</strong>'.$aft.$link;
}
?>
<?php
function getCertAgencyName($regnum) {
	global $wpdb;

	if ($regnum=='') return '';


	$rec = $wpdb->get_row($wpdb->prepare("SELECT meta_value FROM wp_postmeta WHERE meta_key='param3_certification_agency' AND meta_value LIKE '%$regnum%'", $regnum));
	if (isset($rec->meta_value)) $agency = getManufacturer($rec->meta_value, false);	
	return $agency;	
}
?>
<?php
function getAllAgenciesNum($start=0, $num=null) {
	global $wpdb;

	$agencies = array();

	/*Получим все возможные значения param3_certification_agency*/

	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param3_certification_agency'");

	/*Получим регистрационный номер*/

	foreach($rec as $r){ 
		$agency_clean = getCertAgency($r);
		$agencies += [$agency_clean => $r];
		//array_push($agencies, array($agency_clean=>$r));
	}

	/*Удалим повторы*/
	array_unique($agencies);

	/*Выберем требуемый срез массива организаций*/
	if (!is_null($num)) $agencies = array_slice($agencies, $start, $num);

 	return $agencies;
}
?>
<?php
function getAllAgenciesNames($start=0, $num=null) {
	global $wpdb;

	$agenciesNames = array();

	/*Получим все возможные значения param3_certification_agency*/

	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param3_certification_agency'");

	/*Получим регистрационный номер*/

	foreach($rec as $r){ 
		$agencyNum = getCertAgency($r);
	    $agencyName = getManufacturer($r, false);
        $agenciesNames += [$agencyNum => $agencyName];
	}

	/*Удалим повторы*/
	array_unique($agenciesNames);

	/*Выберем требуемый срез массива организаций*/
	if (!is_null($num)) $agenciesNames = array_slice($agenciesNames, $start, $num);

 	return $agenciesNames;
}
?>
<?php
function getAllAgenciesCities($start=0, $num=null) {
	global $wpdb;

	$agenciesCities = array();

	/*Получим все возможные значения param3_certification_agency*/

	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param3_certification_agency'");

	/*Получим регистрационный номер*/

	foreach($rec as $r){ 
		$agencyNum = getCertAgency($r);
	    $agencyCity = getCity($r);
        $agenciesCities += [$agencyNum => $agencyCity];
	}

	/*Удалим повторы*/
	array_unique($agenciesCities);

	/*Выберем требуемый срез массива организаций*/
	if (!is_null($num)) $agenciesCities = array_slice($agenciesCities, $start, $num);

 	return $agenciesCities;
}
?>
<?php
function getAllAgenciesLinks($start=0, $num=null) {
	global $wpdb;

	$agenciesLinks = array();

	/*Получим все возможные значения пар regnum => link*/

	$rec = $wpdb->get_results("SELECT regnum, link FROM wp_certagency");


	foreach($rec as $r){ 
		$agenciesLinks += [$r->regnum => $r->link];
	}

	/*Удалим повторы*/
	array_unique($agenciesLinks);

	/*Выберем требуемый срез массива организаций*/
	if (!is_null($num)) $agenciesLinks = array_slice($agenciesLinks, $start, $num);

 	return $agenciesLinks;
}
?>
<?php
function getCompany($manufacturer) {
	if ($manufacturer==='') return '';

	$fs = mb_strpos($manufacturer, '"');
		if ($fs === false) $fs = mb_strpos($manufacturer, '«');
		if ($fs === false) $fs = mb_strpos($manufacturer, '“');
		//if ($fs === false) $fs=-1;
		if ($fs === false) return $manufacturer;
	
	$ed = mb_strpos($manufacturer, '"', $fs+1);
		if ($ed === false) $ed = mb_strpos($manufacturer, '»', $fs+1);
		if ($ed === false) $ed = mb_strpos($manufacturer, '”', $fs+1);
		if ($ed === false) return $manufacturer; 
		/*
		{
				$point = mb_strpos($manufacturer, '.', $fs+1);
				$space = mb_strpos($manufacturer, ' ', $fs+1);
				if ($point < $space) $ed = $point; else $ed = $space;
		}
		*/

	$manufacturer_clean = mb_substr($manufacturer,$fs+1,$ed-$fs-1);

	$symarr = array();

	for ($i=0;$i<mb_strlen($manufacturer_clean);$i++) {
		$sym = mb_substr($manufacturer_clean,$i,1);
		if (($sym=='«') || ($sym=='“') || ($sym=='»') || ($sym=='”') || ($sym=='"')) {
			array_push($symarr, $sym);
		}
	}

	if (count($symarr)>0) {
		end($symarr);
		if (current($symarr)=='«') $manufacturer_clean=$manufacturer_clean.'»';
		if (current($symarr)=='“') $manufacturer_clean=$manufacturer_clean.'”';
		if (current($symarr)=='"') $manufacturer_clean=$manufacturer_clean.'"';
	}

	return $manufacturer_clean;
}
?>
<?php
function companyTitle( $title, $sep ){
    if (is_page('kompanii')) {
    	if ($_GET['param']=='') $title = 'Найти сертификаты по изготовителю | rostest-certify.ru - Сертификаты соответствия'; else
		$title = 'Скачать сертификаты '.$_GET['param'].' | rostest-certify.ru - Сертификаты соответствия';
	}
    return $title;
}
add_filter( 'wp_title', 'companyTitle', 10, 2);
?>
<?php
function agencyTitle( $title, $sep ){
    if (is_page('organy-po-sertifikacii')) {
    	if ($_GET['param']=='') $title = 'Реестр органов по сертификации | rostest-certify.ru - Сертификаты соответствия'; else
		$title = 'Скачать сертификаты выданные '.$_GET['param'].' | rostest-certify.ru - Сертификаты соответствия';
	}
    return $title;
}
add_filter( 'wp_title', 'agencyTitle', 10, 2);
function normTitle( $title, $sep ){
    if (is_page('gosty')) {
    	if ($_GET['param']=='') $title = 'ГОСТы на материалы, товары, продукцию и услуги | rostest-certify.ru - Сертификаты соответствия'; else
		$title = 'Скачать бесплатно '.$_GET['param'].' - '.current(getNormsByName($_GET['param']))->name_full.' | rostest-certify.ru - Сертификаты соответствия';
	}
    return $title;
}
add_filter( 'wp_title', 'normTitle', 10, 2);
function certNumber( $title, $sep ){
    if (is_page('naiti-sertifikat-po-nomeru')) {
        if ($_GET['param']=='') $title = 'Найти сертификат соответствия по номеру | rostest-certify.ru - Сертификаты соответствия'; else
        $title = 'Скачать сертификаты с номером '.$_GET['param'].' | rostest-certify.ru - Сертификаты соответствия';
    }
    return $title;
}
add_filter( 'wp_title', 'certNumber', 10, 2);
?>
<?php
function getPriceCurrent() {
	global $wpdb;

	//Проверим, какая цена была предложена меньше всего раз?
    $price = $wpdb->get_var("SELECT price FROM wp_pricelist ORDER BY offers ASC");
    
    error_log('PRICE: '.$price);

    if (!$price) $price = 199;

    //Обновим количество совершенных предложений
    $rec = $wpdb->get_row($wpdb->prepare("SELECT ID, offers FROM wp_pricelist WHERE price = '$price'", $price));
    $wpdb->update('wp_pricelist', array('offers'=>$rec->offers+1), array('ID'=>$rec->ID));

   	//Выведем в результаты

    /*DEBUG*/
    $price = 199;  

    return $price;
}
?>
<?php
function getPriceOld($price = 199) {
	//Вычислим цену до скидки
    
    $price_old = round($price*6,-2);

   	//Ее и выведем в результаты
    return $price_old;
}
?>
<?php
add_action( 'template_redirect', function(){
    ob_start( function( $buffer ){
        $buffer = str_replace( array( 'type="text/javascript"', "type='text/javascript'" ), '', $buffer );
        $buffer = str_replace( array( 'type="text/css"', "type='text/css'" ), '', $buffer );
        return $buffer;
    });
});
?>
<?php
function declination($number, $titles) {
	
	$cases = array(2, 0, 1, 1, 1, 2);
	
	return $titles[($number%100>4 && $number%100<20) ? 2 : $cases[min($number%10, 5)]];
	
}
?>
<?php
//Нормализация номера сертификата или декларации соответствия
function normalizeNumber($number) {

    mb_internal_encoding("UTF-8");

    if (empty($number)) return '';

    $charset = mb_detect_encoding($number);

    $number = iconv($charset, "UTF-8", $number);

    $number = mb_strtoupper($number, "UTF-8");

    //Сначала исправим написание РОСС (все большие русские буквы)
    // 1 - означает, что в данной позиции латинская буква, а 0 - русские
    $ross = array('РОСC'/*0001*/,'РОCС'/*0010*/,'РОCC'/*0011*/,'РOСС'/*0100*/,'РOСC'/*0101*/,'РOCС'/*0110*/,'РOCC'/*0111*/,
        'PОСС'/*1000*/,'PОСC'/*1001*/,'PОCС'/*1010*/,'PОCC'/*1011*/,'POСС'/*1100*/,'POСC'/*1101*/,'POCС'/*1110*/,'POCC'/*1111*/);

    //Пробуем найти и заменить подходящий вариант РОСС (все русские)
    $number = str_replace($ross, 'РОСС', $number);


    //Меняем TC на ТС (русская)
    $tc = array('ТC'/*01*/,'TС'/*10*/,'TC'/*11*/);
    $number = str_replace($tc, 'ТС', $number);

    //Меняем C- (английская) на С- (русская)
    $number = str_replace('C-', 'С-', $number);

    //Исправим написание части номера, относящегося к органу по сертификации, на русские буквы
    /*
        РОСС RU.ПТ79.Н00359
        RA.RU.CT08.H00152
        TC RU C-RU.AУ05.B.01317
        TC RU № Д-RU.АЯ54.В.00034
    */

    //Буквы с двучтением ангийский-русский. Далее все буквы английские
    $en = array("A","B","E","K","M","H","O","P","C","T","X");

    //Далее все буквы русские
    $ru = array("А","В","Е","К","М","Н","О","Р","С","Т","Х");
    
    //Разобъем строку на массив по точкам
    $number_ex = explode('.', $number);

    //Найдем элемент $number_exploded длиной 4 символа

    $number_ex_new = array();

    foreach ($number_ex as $num_ex) {
        if (mb_strlen($num_ex)==4) {
            //эта часть номера относится к лаборатории, заменим английские буквы на русские
            $num_ex = str_replace($en, $ru, $num_ex);
        }
        if (mb_strlen($num_ex)==6) {
            //эта часть номера относится к номеру сертификата, заменим английские буквы на русские
            $num_ex = str_replace($en, $ru, $num_ex);
        }
        if (mb_strlen($num_ex)==1) {
            //эта часть номера относится к номеру сертификата, заменим английские буквы на русские
            $num_ex = str_replace($en, $ru, $num_ex);
        }
	    array_push($number_ex_new, $num_ex);
    }

    //Объединим массив в строку

    $number = implode('.', $number_ex_new);

    return $number;
}
?>
<?php
//Нормализация номеров ВСЕХ сертификатов и деклараций соответствия
function normalizeNumbers() {
	global $wpdb;

	/*Получим все возможные значения param1_number*/

	$rec = $wpdb->get_results("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = 'param1_number'");

	echo 'RECS: '.count($rec).'<br />';


	/*Сконвертируем номера всех сертификатов и деклараций*/

	//Количество замен
	$converted = 0;

	//Общее количество сертификатов и деклараций
	$total = 0;

	foreach ($rec as $r) {

		$val = $r->meta_value;
		$val_new = normalizeNumber($val);

		//Если номер был неправильный и сконвертирован
		if ($val!=$val_new) {
			//Перезапись значения в в базе

			//NOT IMPLEMENTED YET
			$wpdb->update('wp_postmeta', array('meta_value'=>$val_new), array('meta_id'=>$r->meta_id));

			echo $r->meta_id.': '.$val.' => '.$val_new.'<br />';

			$converted++;
		}
		$total++;
	}

	echo 'ВСЕГО: '.$total.'<br />';
	echo 'ПРОИЗВЕДЕНО ЗАМЕН: '.$converted.'<br />';
}
?>
<?php
//Нормализация номеров ВСЕХ сертификатов и деклараций соответствия
function normalizeStrings() {
	global $wpdb;

	/*Получим все возможные значения param1_number*/

	$rec = $wpdb->get_results("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = 'param8_on_the_basis'");

	echo 'RECS: '.count($rec).'<br />';


	/*Сконвертируем номера всех сертификатов и деклараций*/

	//Количество замен
	$converted = 0;

	//Общее количество сертификатов и деклараций
	$total = 0;

	foreach ($rec as $r) {

		$val = $r->meta_value;
		$val_new = normalizeString($val);

		//Если номер был неправильный и сконвертирован
		if ($val!=$val_new) {
			//Перезапись значения в в базе

			//NOT IMPLEMENTED YET
			$wpdb->update('wp_postmeta', array('meta_value'=>$val_new), array('meta_id'=>$r->meta_id));
			$val = str_replace('Per', '<b>Per</b>', $val);
			$val = str_replace('per', '<b>per</b>', $val);

			echo $r->meta_id.': '.$val.' => '.$val_new.'<br /><br />';

			$converted++;
		}
		$total++;
	}

	echo 'ВСЕГО: '.$total.'<br />';
	echo 'ПРОИЗВЕДЕНО ЗАМЕН: '.$converted.'<br />';
}
?>
<?php
function normalizeString($str, $per=false) {
	mb_internal_encoding("UTF-8");

    if (empty($str)) return '';

    $charset = mb_detect_encoding($str);
    $str = iconv($charset, "UTF-8", $str);

//Замена переносов строк точкой с пробелом
 	$str = str_replace(array(".\r\n", ".\r", ".\n"), '. ', $str);

//Замена Per на Рег, а per на рег

    if ($per) {
 		$str = str_replace(' Per', ' Рег', $str);
 		$str = str_replace(' per', ' рег', $str);
 	}

 	return $str;
}
?>
<?php 
function getCitiesByAgencies($agencies) {
	global $wpdb;
	$cities = array();
	foreach ($agencies as $regnum=>$agencyName) {
		if (!$agencyName) continue;
		$ag = $wpdb->get_row($wpdb->prepare("SELECT meta_value FROM wp_postmeta WHERE meta_key='param3_certification_agency' AND meta_value LIKE '%$regnum%'", $regnum));
	    if (isset($ag->meta_value)) $cities+=[$regnum => ''/*getCity($ag->meta_value)*/];
	}
	return $cities;
}
?>
<?php
function getCity($str) {

	$cities = array ('Москва', 'Ульяновск', 'Тула', 'Самара', 'Ростов', 'Екатеринбург', 'Белгород', 'Орел', 'Орёл', 'Краснодар', 'Орлов', 'Барнаул', 'Люберцы', 'Оренбург', 'Санкт-Петербург', 'Серпухов', 'Обнинск', 'Королев', 'Королёв', 'Иваново', 'Балабаново', 'Сергиев Посад', 'Кемерово', 'Рязань', 'Курск', 'Калуга', 'Приволжск', 'Махачкала', 'Тамбов', 'Волгоград', 'Красноярск', 'Ижевск', 'Чебоксары', 'Ставрополь', 'Саранск', 'Юбилейный', 'Саров', 'Иркутск', 'Челябинск', 'Омск', 'Уфа', 'Курган', 'Тольятти', 'Нальчик', 'Новосибирск', 'Казань', 'Владивосток', 'Фрязино', 'Тюмень', 'Химки', 'Нижний Новгород', 'Псков', 'Курчатов', 'Смоленск', 'Сыктывкар', 'Саратов', 'Йошкар-Ола', 'Ярославль', 'Энгельс', 'п. Красково',

		'Абаза', 'Абакан', 'Абдулино', 'Абинск', 'Агрыз', 'Адыгейск', 'Азнакаево', 'Азов', 'Ак-Довурак', 'Аксай', 'Акша', 'Алагир', 'Алапаевск', 'Алатырь', 'Алдан', 'Алейск', 'Александров', 'Александровск', 'Александровск-Сахалинский', 'Алексеевка', 'Алексин', 'Алзамай', 'Альметьевск', 'Амурск', 'Анадырь', 'Анапа', 'Ангарск', 'Андреаполь', 'Анжеро-Судженск', 'Анива', 'Апатиты', 'Апрелевка', 'Апшеронск', 'Арамиль', 'Аргун', 'Ардатов', 'Ардон', 'Арзамас', 'Аркадак', 'Армавир', 'Арсеньев', 'Арск', 'Артём', 'Артёмовск', 'Артёмовский', 'Архангельск', 'Асбест', 'Асино', 'Аткарск', 'Ахтубинск', 'Ачинск', 'Аша', 'Бабаево', 'Бабушкин', 'Багратионовск', 'Байкальск', 'Баймак', 'Бакал', 'Баксан', 'Балаково', 'Балахна', 'Балашиха', 'Балашов', 'Балей', 'Балтийск', 'Барабинск', 'Барыш', 'Батайск', 'Беднодемьяновск', 'Бежецк', 'Белая Калитва', 'Белая Холуница', 'Белебей', 'Белёв', 'Белинский', 'Белово', 'Белогорск', 'Белозерск', 'Белокуриха', 'Беломорск', 'Белорецк', 'Белореченск', 'Белоярский', 'Белый', 'Бердск', 'Березники', 'Берёзово', 'Берёзовский', 'Беслан', 'Бийск', 'Бикин', 'Биробиджан', 'Бирск', 'Бирюсинск', 'Благовещенск', 'Благодарный', 'Бобров', 'Богданович', 'Богородицк', 'Богородск', 'Боготол', 'Богучар', 'Бодайбо', 'Бокситогорск', 'Бологое', 'Болотное', 'Болохово', 'Болхов', 'Большой Камень', 'Бор', 'Борзя', 'Борисоглебск', 'Боровичи', 'Боровск', 'Бородино', 'Братск', 'Бронницы', 'Бугульма', 'Бугуруслан', 'Буденновск', 'Бузулук', 'Буинск', 'Буй', 'Буйнакск', 'Булгар', 'Бутурлиновка', 'Валдай', 'Валуйки', 'Варнавино', 'Васильсурск', 'Велиж', 'Великие Луки', 'Великий Новгород', 'Великий Устюг', 'Вельск', 'Венев', 'Верещагино', 'Верея', 'Верхнеуральск', 'Верхний Тагил', 'Верхний Уфалей', 'Верхняя Пышма', 'Верхняя Салда', 'Верхняя Тура', 'Верхотурье', 'Верхоянск', 'Весьегонск', 'Ветлуга', 'Видное', 'Вилюйск', 'Вихоревка', 'Вичуга', 'Владимир', 'Волгодонск', 'Волжск', 'Волжский', 'Вологда', 'Володарск', 'Волоколамск', 'Волхов', 'Волчанск', 'Вольск', 'Воркута', 'Воронеж', 'Ворсма', 'Воскресенск', 'Воткинск', 'Всеволожск', 'Вуктыл', 'Выборг', 'Выкса', 'Высоковск', 'Высоцк', 'Вытегра', 'Вышний Волочек', 'Вяземский', 'Вязники', 'Вязьма', 'Вятские Поляны', 'Гаврилов Посад', 'Гаврилов-Ям', 'Гай', 'Галич', 'Гатчина', 'Гвардейск', 'Гдов', 'Геленджик', 'Георгиевск', 'Глазов', 'Горбатов', 'Горно-Алтайск', 'Горнозаводск', 'Горняк', 'Городец', 'Городище', 'Городовиковск', 'Гороховец', 'Горячий Ключ', 'Грайворон', 'Гремячинск', 'Грязи', 'Грязовец', 'Губаха', 'Губкин', 'Гудермес', 'Гуково', 'Гулькевичи', 'Гурьевск', 'Гусев', 'Гусинозёрск', 'Гусь-Хрустальный', 'Давлеканово', 'Дагестанские Огни', 'Далматово', 'Дальнегорск', 'Дальнереченск', 'Данилов', 'Данков', 'Девяткино', 'Дегтярск', 'Дедовск', 'Демидов', 'Демянск', 'Дербент', 'Десногорск', 'Дзержинск', 'Дзержинский', 'Дивногорск', 'Дигора', 'Диксон', 'Димитровград', 'Дмитриев-Льговский', 'Дмитров', 'Дмитровск-Орловский', 'Дно', 'Добрянка', 'Долгопрудный', 'Долинск', 'Домодедово', 'Донецк', 'Донской', 'Дорогобуж', 'Дрезна', 'Дубна', 'Дубовка', 'Дудинка', 'Духовщина', 'Дюртюли', 'Дятьково', 'Егорьевск', 'Елабуга', 'Елатьма', 'Елец', 'Елизово', 'Ельня', 'Еманжелинск', 'Емва', 'Енисейск', 'Енотаевка', 'Епифань', 'Ершов', 'Ессентуки', 'Ефремов', 'Железноводск', 'Железногорск', 'Железногорск-Илимский', 'Железнодорожный', 'Жердевка', 'Жиганск', 'Жигулевск', 'Жиздра', 'Жирновск', 'Жуковка', 'Жуковский', 'Завитинск', 'Заводоуковск', 'Заволжск', 'Заволжье', 'Задонск', 'Заинск', 'Закаменск', 'Заозёрный', 'Западная Двина', 'Заполярный', 'Зарайск', 'Заринск', 'Звенигово', 'Звенигород', 'Зверево', 'Зеленогорск', 'Зеленоград', 'Зеленоградск', 'Зеленодольск', 'Зеленокумск', 'Зерноград', 'Зея', 'Зима', 'Златоуст', 'Злынка', 'Змеиногорск', 'Зубцов', 'Зуевка', 'Ивангород', 'Ивантеевка', 'Ивдель', 'Игарка', 'Истра', 'Изербаш', 'Изобильный', 'Иланский', 'Инза', 'Инсар', 'Инта', 'Ипатово', 'Ирбит', 'Исилькуль', 'Искитим', 'Ишим', 'Ишимбай', 'Кадников', 'Кадом', 'Кадый', 'Кайеркан', 'Калач', 'Калачинск', 'Калач-на-Дону', 'Калининград', 'Калининск', 'Калтан', 'Калязин', 'Камбарка', 'Каменка', 'Каменногорск', 'Каменск-Уральский', 'Каменск-Шахтинский', 'Камень-на-Оби', 'Камешково', 'Камызяк', 'Камышин', 'Камышлов', 'Канадей', 'Канаш', 'Кандалакша', 'Канск', 'Карабаш', 'Караваново', 'Карасук', 'Карачаевск', 'Карачев', 'Каргат', 'Каргополь', 'Карпинск', 'Карсун', 'Карталы', 'Касимов', 'Касли', 'Каспийск', 'Катав-Ивановск', 'Катайск', 'Качканар', 'Кашин', 'Кашира', 'Кедровый', 'Кемь', 'Кизел', 'Кизилюрт', 'Кизляр', 'Кимовск', 'Кимры', 'Кингисепп', 'Кинель', 'Кинешма', 'Киреевск', 'Киренск', 'Киржач', 'Кириллов', 'Кириши', 'Киров', 'Кировград', 'Кирово-Чепецк', 'Кировск', 'Кирс', 'Кирсанов', 'Кисилевск', 'Кисловодск', 'Климовск', 'Клин', 'Клинцы', 'Ключи', 'Княгинино', 'Ковдор', 'Ковров', 'Ковылкино', 'Когалым', 'Кодинск', 'Козельск', 'Козловка', 'Козьмодемьянск', 'Кола', 'Кологрив', 'Коломна', 'Колпашево', 'Колпино', 'Колывань', 'Кольчугино', 'Комсомольск', 'Комсомольск-на-Амуре', 'Конаково', 'Кондопога', 'Кондрово', 'Константиновск', 'Копейск', 'Кораблино', 'Кореновск', 'Коркино', 'Короча', 'Корсаков', 'Коряжма', 'Костерево', 'Костомукша', 'Кострома', 'Котельниково', 'Котельнич', 'Котлас', 'Котово', 'Котовск', 'Кохма', 'Красавино', 'Красноармейск', 'Красноборск', 'Красновишерск', 'Красногорск', 'Краснозаводск', 'Краснознаменск', 'Краснокаменск', 'Краснокамск', 'Краснослободск', 'Краснотурьинск', 'Красноуральск', 'Красноуфимск', 'Красный Кут', 'Красный Судин', 'Красный Холм', 'Красный Яр', 'Крестцы', 'Кромы', 'Кронштадт', 'Кропоткин', 'Крымск', 'Кстово', 'Кувандык', 'Кувшиново', 'Кудымкар', 'Кузнецк', 'Куйбышев', 'Кулебаки', 'Кумертау', 'Кунгур', 'Купино', 'Курганинск', 'Курильск', 'Куровское', 'Куртамыш', 'Куса', 'Кушва', 'Кызыл', 'Кыштым', 'Кяхта', 'Лабинск', 'Лабытнанги', 'Лагань', 'Ладушкин', 'Лаишево', 'Лакинск', 'Лальск', 'Лангепас', 'Лахденпохья', 'Лебедянь', 'Лениногорск', 'Ленинск', 'Ленинск-Кузнецкий', 'Ленск', 'Лермонтов', 'Лесогорск', 'Лесозаводск', 'Лесосибирск', 'Ливны', 'Ликино-Дулево', 'Липки', 'Лиски', 'Лихославль', 'Лобня', 'Лодейное Поле', 'Ломоносов', 'Лосино-Петровский', 'Луга', 'Луза', 'Лукоянов', 'Лух', 'Луховицы', 'Лысково', 'Лысьва', 'Лыткарино', 'Льгов', 'Любань', 'Любим', 'Людиново', 'Магнитогорск', 'Майкоп', 'Майский', 'Макаров', 'Макарьев', 'Макарьево', 'Макушино', 'Малая Вишера', 'Малгобек', 'Малмыж', 'Малоархангельск', 'Малоярославец', 'Мамадыш', 'Мамоново', 'Мантурово', 'Мариинск', 'Мариинский Посад', 'Мглин', 'Мегион', 'Медвежьегорск', 'Медногорск', 'Медынь', 'Междуреченск', 'Мезень', 'Меленки', 'Мелеуз', 'Менделеевск', 'Мензелинск', 'Мещовск', 'Миасс', 'Микунь', 'Миллерово', 'Минеральные Воды', 'Минусинск', 'Миньяр', 'Мирный', 'Михайлов', 'Михайловка', 'Михайловск', 'Мичуринск', 'Могоча', 'Можайск', 'Можга', 'Моздок', 'Мокшан', 'Мончегорск', 'Морозовск', 'Моршанск', 'Мосальск', 'Мураши', 'Муром', 'Мценск', 'Мыски', 'Мытищи', 'Мышкин', 'Набережные Челны', 'Навашино', 'Наволоки', 'Надым', 'Назарово', 'Назрань', 'Называевск', 'Нариманов', 'Наровчат', 'Наро-Фоминск', 'Нарткала', 'Нарьян-Мар', 'Находка', 'Невель', 'Невельск', 'Невинномысск', 'Невьянск', 'Нелидово', 'Неман', 'Нерехта', 'Нерчинск', 'Нерюнгри', 'Нефтегорск', 'Нефтекамск', 'Нефтекумск', 'Нефтеюганск', 'Нижневартовск', 'Нея', 'Нижнедевицк', 'Нижнекамск', 'Нижнеудинск', 'Нижние Серги', 'Нижний Ломов', 'Нижний Тагил', 'Нижняя Салда', 'Нижняя Тура', 'Николаевск', 'Николаевск-на-Амуре', 'Никольск, Вологодская обл.', 'Никольск, Пензенская обл.', 'Никольское', 'Новая Ладога', 'Новая Ляля', 'Новоалександровск', 'Новоалтайск', 'Новоаннинский', 'Нововоронеж', 'Новодвинск', 'Новозыбков', 'Новокубанск', 'Новокузнецк', 'Новокуйбышевск', 'Новомичуринск', 'Новомосковск', 'Новопавловск', 'Новоржев', 'Новороссийск', 'Новосиль', 'Новосокольники', 'Новотроицк', 'Новоузенск', 'Новоульяновск', 'Новохоперск', 'Новочебоксарск', 'Новочеркасск', 'Новошахтинск', 'Новый Оскол', 'Новый Уренгой', 'Ногинск', 'Нолинск', 'Норильск', 'Ноябрьск', 'Нурлат', 'Нытва', 'Нягань', 'Нязепетровск', 'Няндома', 'Облучье', 'Обоянь', 'Обь', 'Одинцово', 'Одоев', 'Ожерелье', 'Озерск', 'Озёры', 'Октябрьск', 'Октябрьский', 'Окуловка', 'Олекминск', 'Оленегорск', 'Олонец', 'Омутнинск', 'Онега', 'Опочка', 'Орехово-Зуево', 'Орск', 'Оса', 'Осинники', 'Осташков', 'Остров', 'Острогожск', 'Отрадное', 'Отрадный', 'Оха', 'Оханск', 'Охотск', 'Очер', 'Павлово', 'Павловск', 'Павловский Посад', 'Палласовка', 'Партизанск', 'Парфеньево', 'Певек', 'Первомайск', 'Первоуральск', 'Перевоз', 'Перемышль', 'Переславль-Залесский', 'Пермь', 'Пестово', 'Петров Вал', 'Петровск', 'Петровск-Забайкальский', 'Петровское', 'Петродворец', 'Петухово', 'Петушки', 'Печора', 'Печоры', 'Пикалево', 'Пинега', 'Пионерский', 'Питкяранта', 'Плавск', 'Пласт', 'Плёс', 'Повенец', 'Поворино', 'Погар', 'Подольск', 'Подпорожье', 'Покров', 'Полевской', 'Полесск', 'Полысаево', 'Полярный', 'Поронайск', 'Порхов', 'Похвистнево', 'Почеп', 'Починки', 'Починок', 'Пошехонье', 'Правдинск', 'Приморск', 'Приморско-Ахтарск', 'Приозерск', 'Прокопьевск', 'Пролетарск', 'Пронск', 'Протвино', 'Прохладный', 'Пугачев', 'Пудож', 'Пустошка', 'Пучеж', 'Пушкин', 'Пушкино', 'Пущино', 'Пыталово', 'Пятигорск', 'Радужный', 'Райчихинск', 'Раменское', 'Рассказово', 'Ревда', 'Реж', 'Реутов', 'Ржев', 'Родники', 'Рославль', 'Россошь', 'Ростов-на-Дону', 'Рошаль', 'Ртищево', 'Рубцовск', 'Рудня', 'Руза', 'Рузаевка', 'Рыбинск', 'Рыбное', 'Рыльск', 'Ряжск', 'Салават', 'Салаир', 'Салехард', 'Сальск', 'Санчурск', 'Сапожок', 'Сарапул', 'Сасово', 'Сатка', 'Сафоново', 'Саяногорск', 'Саянск', 'Светлогорск', 'Светлоград', 'Светлый', 'Светогорск', 'Свирск', 'Свободный', 'Себеж', 'Северобайкальск', 'Северодвинск', 'Северо-Задонск', 'Североморск', 'Северо-Курильск', 'Североуральск', 'Северск', 'Севск', 'Сегежа', 'Сельцо', 'Семенов', 'Семилуки', 'Семикаракорск', 'Сенгилей', 'Серафимович', 'Сергач', 'Сергиевск', 'Сердобск', 'Серов', 'Сестрорецк', 'Сибай', 'Сковородино', 'Скопин', 'Славгород', 'Славск', 'Славянск-на-Кубани', 'Сланцы', 'Слободской', 'Слюдянка', 'Собинка', 'Советск', 'Советская Гавань', 'Сокол', 'Сокольники', 'Солигалич', 'Соликамск', 'Солнечногорск', 'Сольвычегодск', 'Соль-Илецк', 'Сольцы', 'Сорочинск', 'Сорск', 'Сортавала', 'Сосновка', 'Сосновоборск', 'Сосновый Бор', 'Сосногорск', 'Сочи', 'Спас-Деменск', 'Спас-Клепики', 'Спасск-Рязанский', 'Среднеколымск', 'Среднеуральск', 'Сретенск', 'Старая Русса', 'Старица', 'Стародуб', 'Старый Оскол', 'Стерлитамак', 'Стрежевой', 'Струнино', 'Ступино', 'Суворов', 'Суджа', 'Судиславль', 'Судогда', 'Суздаль', 'Суоярви', 'Сураж', 'Сургут', 'Суровикино', 'Сурск', 'Сусуман', 'Сухиничи', 'Сухой Лог', 'Сходня', 'Сызрань', 'Сысерть', 'Сычевка', 'Тавда', 'Таганрог', 'Тайга', 'Тайшет', 'Талдом', 'Талица', 'Талнах', 'Тара', 'Таруса', 'Татарск', 'Таштагол', 'Тверь', 'Теберда', 'Тейково', 'Темников', 'Темрюк', 'Терек', 'Тетюши', 'Тикси', 'Тим', 'Тимашевск', 'Тихвин', 'Тихорецк', 'Тобольск', 'Тогучин', 'Томари', 'Томмот', 'Томск', 'Топки', 'Торжок', 'Торопец', 'Тосно', 'Тотьма', 'Троицк', 'Трубчевск', 'Туапсе', 'Туймазы', 'Тулун', 'Туран', 'Туринск', 'Туруханск', 'Тутаев', 'Тында', 'Тырныауз', 'Тюкалинск', 'Туапсе', 'Уварово', 'Углегорск', 'Углич', 'Удачный', 'Удомля', 'Ужур', 'Узловая', 'Унеча', 'Урай', 'Урень', 'Уржум', 'Урус-Мартан', 'Урюпинск', 'Усинск', 'Усмань', 'Усолье', 'Усолье-Сибирское', 'Уссурийск', 'Усть-Джегута', 'Усть-Илимск', 'Усть-Катав', 'Усть-Кут', 'Усть-Лабинск', 'Устюжна', 'Ухта', 'Учалы', 'Уяр', 'Фатеж', 'Фокино', 'Фролово', 'Фурманов', 'Хадыженск', 'Харабали', 'Харовск', 'Хасавюрт', 'Хвалынск', 'Хилок', 'Холм', 'Холмогоры', 'Холмск', 'Хотьково', 'Цивильск', 'Цимлянск', 'Чадан', 'Чайковский', 'Чапаевск', 'Чаплыгин', 'Чебаркуль', 'Чекалин', 'Чердынь', 'Черемхово', 'Черепаново', 'Череповец', 'Черкесск', 'Чермоз', 'Черногорск', 'Чернушка', 'Черный Яр', 'Чернь', 'Черняховск', 'Чехов', 'Чистополь', 'Чкаловск', 'Чудово', 'Чулым', 'Чусовой', 'Чухлома', 'Шагонар', 'Шадринск', 'Шали', 'Шарыпово', 'Шарья', 'Шатура', 'Шахтерск', 'Шахты', 'Шахунья', 'Шацк', 'Шебекино', 'Шелехов', 'Шенкурск', 'Шилка', 'Шимановск', 'Шлиссельбург', 'Шумерля', 'Шумиха', 'Шуя', 'Щекино', 'Щелково', 'Щербинка', 'Щигры', 'Щучье', 'Электрогорск', 'Электросталь', 'Электроугли', 'Эртиль', 'Южа', 'Южно-Сухокумск', 'Южноуральск', 'Юрга', 'Юрьевец', 'Юрьев-Польский', 'Юрюзань', 'Юхнов', 'Ядрин', 'Ялуторовск', 'Яранск', 'Ярцево', 'Ясногорск', 'Ясный', 'Яхрома');

    if (empty($str)) return '';

	mb_internal_encoding("UTF-8");

    $charset = mb_detect_encoding($str);
    $str = iconv($charset, "UTF-8", $str);
    $str = mb_strtolower($str, "UTF-8");

    //Обойдем массив городов и попытаемся найти в строке город

    for ($i=0; $i<count($cities);$i++) {
    	$found=mb_strpos($str, mb_strtolower($cities[$i]));
    	if ($found!==false)
    		{
    			return ($cities[$i]);
    			break;
    		}
    }

    return '';
}
?>
<?php function get_home_post_count($on_home) {
	$count = wp_count_posts();
	$count = $count->publish-$on_home;
	
	return $count;
}
?>
<?php function get_norm_count($on_home) {
	global $wpdb;
	$count = $wpdb->get_results("SELECT * FROM wp_norms");
	$count = $wpdb->num_rows-$on_home;

	return $count;
}
?>
<?php function newSizes($image) {
	$info = getimagesize($image);
	if (!$info) {
		$out[0]=100;
		$out[1]=75;
		return ($out);
	}

	$w_old = $info[0];
	$h_old = $info[1];
		
	if ($info) {
		if ($w_old>$h_old) {
			$w = 100;
			$h = round($h_old*$w/$w_old);
			$out[0]=$w;
			$out[1]=$h;
		} else {
			$h = 75;
			$w = round($w_old*$h/$h_old);
			$out[0]=$w;
			$out[1]=$h;
		}
	} else $out=false;

	return $out;
}
?>
<?php function getImageResolution($image) {
	if ($image == '') return '';
	
	$info = getimagesize($image);
	return $info[0].'x'.$info[1];
}
?>
<?php function searchByNum($search) {
	global $wpdb;
    
    //Безопасная передача параметров
    $search = search_safe ($search);
    $search = mb_strtoupper($search, 'UTF-8');

    //Ограничим строку поиска 255 символами
	$search = mb_substr($search, 0, 255);

	//Разобъем на слова по символам кроме цифр
	$search_words = mb_split("[^0-9]", $search);

	//Отсортируем по длине слов

	usort($search_words,'sortByLength');

	//Шаблон запроса

	$sql = "SELECT post_id FROM wp_postmeta WHERE meta_key='param1_number'";

	//Добавляем условий из списка с цифрами из запроса с номером

	foreach($search_words as $word) {
		if (mb_strlen($word) > 0) {
			$sql = $sql." AND meta_value LIKE '%%$word%%'";
		} else break;
	}

	$rec = $wpdb->get_col($sql);

	return $rec;
}

function sortByLength($a,$b){
  if($a == $b) return 0;
  return (mb_strlen($a) > mb_strlen($b) ? -1 : 1);
}
?>
<?php
function getAgencyInfoByName($agencyName) {
	global $wpdb;
	if (!isset ($agencyName)) {
		return '';
	}

	$agencyInfo = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='param3_certification_agency' AND meta_value LIKE '%%$agencyName%%'", $agencyName));
	return $agencyInfo;
}
?>
<?php
function getAgencyInfoByReg($agencyReg) {
	global $wpdb;
	if (!isset ($agencyReg)) {
		return '';
	}

	$agencyInfo = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='param3_certification_agency' AND meta_value LIKE '%%$agencyReg%%'", $agencyReg));
	return $agencyInfo;
}
?>
<?php
function log_total_search_words($log_file, $search_words) {
	fwrite($log_file, 'B: Words to search: ');
		foreach ($search_words as $word) fwrite($log_file, $word.'; ');
	fwrite($log_file, "\r\n");
	fwrite($log_file, 'B: Total search words: '.count($search_words)."\r\n");
	fwrite($log_file, "\r\n\r\n");
};
?>
<?php
	function cutStringToWords($str, $length, $postfix='', $encoding=null) {
	$encoding = $encoding ?: mb_detect_encoding($str);

	$str = mb_eregi_replace('[^a-zа-яё ]', '', $str);
	$str = trim($str);


	if (mb_strlen($str, $encoding) <= $length) {
		return $str;
	}

	while (mb_substr($str, $length, (1)) != ' ') {
	// ДЛЯ ОТЛАДКИ РАСКОМЕНТИРУЙТЕ ЭТО echo $length++." ". mb_substr($str, 0, $length)."»;
	// А ТО ЧТО НИЖЕ $length++ ЗАКОММЕНТИРУЙТЕ
	$length++;
	if (mb_strlen($str, $encoding) <= $length) {
		return $str;
	}
	}

	$tmp = mb_substr($str, 0, $length, $encoding);
	return mb_substr($tmp, 0, mb_strripos($tmp, ' ', 0, $encoding), $encoding) . $postfix;
}
?>
<?php
function getManufacturerCoords($manufacturer) {
	global $wpdb;

	if ($manufacturer==='') return '';
	
	$rec = $wpdb->get_row($wpdb->prepare("SELECT coords FROM wp_logos WHERE name LIKE '$manufacturer'", $manufacturer));

	if (isset($rec->coords)) return $rec->coords; else return '';

}
?>