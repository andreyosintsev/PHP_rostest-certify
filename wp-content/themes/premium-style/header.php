<!--rostest-->
<?php
/**
 * header.php
 *
 * This file controls the HTML <head> and top graphical markup (including
 * Navigation) for each page in your theme. Displays all of the <head> 
 * section and everything up till <div class="wrapper">
 *
 * @link        http://rostest-certify.ru/
 *
 * @author      Andrei Osintsev
 * @copyright   Copyright (c) 2024 asosintsev@yandex.ru
 */
?>
<?php
    if (is_single() || is_archive() || is_category() || is_home() || is_page() || is_search()) session_start();


    $site_url       = site_url();
    $template_url   = get_template_directory_uri();

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

        <!--OpenGraph-->
	    <meta property="og:title" 		content="Скачать сертификат на <?php echo get_the_title(); ?>">
	    <meta property="og:description" content="Сертификат соответствия № <?php echo $og_number; ?> на <?php echo $og_product; ?>...">
	    <meta property="og:image" 		content="<?php echo $site_url; ?>/thumbs/<?php get_post_meta($post->ID, 'img_thmb', true); ?>">
	    <meta property="og:image:width" content="376">
	    <meta property="og:image:height" content="537">
	    <meta property="og:type"		content="article">
	    <meta property="og:url"			content= "<?php echo get_permalink(); ?>">
	    <meta property="og:locale"		content="ru_RU">
        <!--/OpenGraph-->

	<?php } ?>


	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="shortcut icon" href="<?php echo $site_url; ?>/favicon.ico" type="image/x-icon">

    <!--Новые стили-->
    <?php if (is_home()) { ?>

        <link rel="stylesheet" href="<?php echo $template_url;?>/css/common/normalize.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/common/consts.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/menu-main.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/menu-mobile.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/button.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/search-item.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/title-more.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/flag.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/certificates-item.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/manufacturers-item.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/agencies-item.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/norms-item.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/sidebar-stats.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/sidebar-countries.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/sidebar-certificates.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/blocks/footer.css">
        <link rel="stylesheet" href="<?php echo $template_url;?>/css/pages/home.css">

    <?php } ?>
    <!--/Новые стили-->

    <!--Cтили и скрипты шаблона-->
	<?php wp_head(); ?>
    <!--/Cтили и скрипты шаблона-->


    <!--Старые скрипты-->
	<?php if (is_page('addnew')) { ?>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/addswitch.js"></script>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/check.js"></script>
	    <script type="text/javascript" src="<?php echo $site_url ?>/tesseract/tesseract.js"></script>
	    <script type="text/javascript" src="<?php echo $template_url; ?>/js/ocr.js"></script>
	<?php } ?>

	<?php if (is_page('naiti-sertifikat-po-vidu-produktsii')) { ?>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/slider.js"></script>
	<?php } ?>

	<?php if (is_page('organy-po-sertifikacii')) { ?>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/expandinfo.js"></script>
	<?php } ?>

	<?php if (is_single()) { ?>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/register.js"></script>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/print.js"></script>
		<script type="text/javascript" src="<?php echo $template_url; ?>/js/norightbutton.js"></script>
	<?php }?>
    <!--/Старые скрипты-->

    <!--Новые скрипты-->
    <script src="<?php echo $template_url; ?>/scripts/menu-mobile.js"></script>
    <!--/Новые скрипты-->

	<!--Yandex.RTB -->
        <script>window.yaContextCb=window.yaContextCb||[]</script>
        <script src="https://yandex.ru/ads/system/context.js" async></script>
    <!--/Yandex.RTB -->
</head>
<body>
<div class="overlay"></div>
<div class="menu-mobile">
    <ul class="menu-mobile__items">
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/naiti-sertifikat-po-nomeru/" title="">Поиск по номеру</a>
        </li>
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/naiti-sertifikat-po-vidu-produktsii/" title="">Продукция</a>
        </li>
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/kompanii/" title="">Изготовители</a>
        </li>
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/reestr-sertifikatov/" title="">Реестр сертификатов</a>
        </li>
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/organy-po-sertifikacii/" title="">Органы по сертификации</a>
        </li>
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/gosty/" title="">ГОСТы</a>
        </li>
        <li class="menu-mobile__item">
            <a class="menu-mobile__link" href="<?php echo $site_url ?>/o-sajte/" title="">О сайте</a>
        </li>
    </ul>
</div>
<header class="nav-top">
    <div class="logo">
        <a class="logo__link" href="home.html" title="<?php bloginfo('name');?>">
            <img class="logo__image" src="<?php echo $template_url; ?>/images/logo.svg" alt="<?php echo $site_url ?>">
        </a>
    </div>
    <div class="search search_collapseable">
        <div class="search__magnifier"></div>
        <form id="searchform" method="get">
            <input class="search__input" placeholder="Поиск сертификата по номеру">
            <input class="search__input search__input-mobile" placeholder="Поиск по номеру">
            <input type="submit" hidden>

            <div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
            <?php
            if ($metavalue=='') $searchstring="Поиск сертификата по номеру"; else $searchstring=$metavalue;
            ?>
            <div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
        </form>

    </div>
    <nav class="menu-main menu-main_collapseable">
        <ul class="menu-main__items">
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/naiti-sertifikat-po-nomeru/" title="Найти сертификат по номеру">
                    Поиск по номеру
                </a>
            </li>
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/naiti-sertifikat-po-vidu-produktsii/" title="Найти сертификат по виду продукции">
                    Продукция
                </a>
            </li>
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/kompanii/" title="Найти сертификат по изготовителю">
                    Изготовители
                </a>
            </li>
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/reestr-sertifikatov/" title="Реестр сертификатов и деклараций соответствия">
                    Реестр сертификатов
                </a>
            </li>
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/organy-po-sertifikacii/" title="Органы по сертификации">
                    Органы
                </a>
            </li>
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/gosty/" title="ГОСТы на материалы, товары, продукцию и услуги">
                    ГОСТы
                </a>
            </li>
            <li class="menu-main__item">
                <a href="<?php echo $site_url ?>/o-sajte/" title="О сайте">
                    О сайте
                </a>
            </li>
        </ul>
        <div class="menu-mobile-search-button">
            <div class="menu-mobile-search-button__button"></div>
        </div>
    </nav>
</header>