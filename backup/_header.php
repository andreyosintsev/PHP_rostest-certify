<!--rostest-->
<?php
/**
 * header.php
 *
 * This file controls the HTML <head> and top graphical markup (including
 * Navigation) for each page in your theme. Displays all of the <head> 
 * section and everything up till <div class="wrap fullwidth">
 *
 * @link        http://rostest-certify.ru/
 *
 * @author      Andrei Osintsev
 * @copyright   Copyright (c) 2024 asosintsev@yandex.ru
 */
?>
<?php
    if (is_single() || is_archive() || is_category() || is_home() || is_page() || is_search()) session_start();

    $site_url =     site_url();
    $template_url = get_bloginfo('template_url');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name='yandex-verification' content='5034b468f638d5d4' />
	<meta name="yandex-verification" content="b0dd183a09ff4e68" />

	<!--OpenGraph-->
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	
	<?php if (is_single()) {?>
	    <?php
	        $metadata           = get_post_custom($post->ID);
	        $og_number        	= $metadata['param1_number'][0];
	        $og_product      	= $metadata['param4_product'][0];
	        $og_product    		= str_replace(array("\""), "", $og_product);
	        $og_product         = mb_substr($og_product, 0, 107 - mb_strlen($og_number));
	    ?>
	    <meta property="og:title" 		content="Скачать сертификат на <?php echo get_the_title(); ?>" />
	    <meta property="og:description" content="Сертификат соответствия № <?=$og_number;?> на <?=$og_product;?>...">
	    <meta property="og:image" 		content="<?php echo site_url().'/thumbs/'.get_post_meta($post->ID, 'img_thmb', true); ?>">
	    <meta property="og:image:width" content="376">
	    <meta property="og:image:height" content="537">
	    <meta property="og:type"		content="article">
	    <meta property="og:url"			content= "<?php echo get_permalink(); ?>">
	    <meta property="og:locale"		content="ru_RU">
	<?php } ?>
	<!--OpenGraph-->

	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="shortcut icon" href="/rostest-certify.ico" type="image/x-icon">

	<?php wp_head(); ?>
	<script src="<?=$template_url;?>js/jquery.min.js"></script>
	<script type="text/javascript">
		function openbox(id){
			display = document.getElementById(id).style.display;
			if (display === 'none') {
				document.getElementById(id).style.display='block';
			} else {
				document.getElementById(id).style.display='none';
			}
		}
	</script>
	<?php if (is_page('addnew')) { ?>
		<script type="text/javascript" src="<?=$template_url;?>/js/addswitch.js"></script>
		<script type="text/javascript" src="<?=$template_url;?>/js/check.js"></script>
	    <script type="text/javascript" src="<?=$site_url;?>/tesseract/tesseract.js"></script>
	    <script type="text/javascript" src="<?=$template_url;?>/js/ocr.js"></script>
	<?php } ?>

	<?php if (is_page('naiti-sertifikat-po-vidu-produktsii')) { ?>
		<script type="text/javascript" src="<?=$template_url;?>/js/slider.js"></script>
	<?php } ?>

	<?php if (is_page('organy-po-sertifikacii')) { ?>
		<script type="text/javascript" src="<?=$template_url;?>/js/expandinfo.js"></script>
	<?php } ?>

	<?php if (is_single()) { ?>
		<script type="text/javascript" src="<?=$template_url?>/js/register.js"></script>
		<script type="text/javascript" src="<?=$template_url;?>/js/print.js"></script>
		<script type="text/javascript" src="<?=$template_url;?>/js/norightbutton.js"></script>
	<?php }?>

	<!-- Yandex.RTB -->
		<script>window.yaContextCb=window.yaContextCb||[]</script>
		<script src="https://yandex.ru/ads/system/context.js" async></script>

</head>
<body <?php body_class(); 
		if (is_page('naiti-sertifikat-po-vidu-produktsii')) 
	 		if (isset($_GET['param'])) {
	 			if ($_GET['param']=='tnvedts') echo ' onload="slider(\'slider2\',0)"'; else echo ' onload="slider(\'slider\',0)"';
	 		} else echo ' onload="slider(\'slider\',0)"';
		?>
	>
<!-- start website menu -->
<div id="primary-nav">
  	<div class="wrap nav">
		<nav id="site-navigation" class="main-navigation">
			<h3 class="menu-toggle"><span style="font-size: 16px; font-weight: bold; margin-right: 10px;">&#9776;</span><?php _e('Сертификаты соответствия', 'gopiplustheme' ); ?></h3>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
		</nav>
 	</div>
</div>
<!-- end website menu -->
<!-- start website header -->
<div id="page-header" class="wrap header">
	<header id="masthead" class="site-header">
		<!-- start hgroup header -->
		<div class="hgroup">
		<?php if ( get_theme_mod( 'premiumstyle_sitelogo' ) ) { ?>
			<div class="site-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php echo get_theme_mod( 'premiumstyle_sitelogo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
				</a>
			</div>
		<?php } else { ?>
		<?php if (is_home()) {?>
			<h1>
				<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					Сертификаты соответствия
				</a>
			</h1>
		<?php } else {?>
			<div class="site-title">
				<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					Сертификаты соответствия
				</a>
			</div>
		<?php } ?>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		<?php } ?>
		</div>
		<!-- start image header -->
		<?php 
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
		<div class="header-image">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		</a>
		</div>
		<?php endif; ?>
		<div class="clear"></div>
		<?php if ((false) && $_SESSION['auth']) {?>
            <div>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- rostest-certify - объявления -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-4019665621332188"
                         data-ad-slot="7487036260"
                         data-ad-format="link"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        <?php } ?>
	</header>
</div>
<!-- end website header -->
<div class="wrap fullwidth">