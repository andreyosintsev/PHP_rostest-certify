<?php
/*
Template Name: Search by Number
*
* template-search-num.php
*
* Template file for search by number page.
* /naiti-sertifikat-po-nomeru/
*
* @link        http://rostest-certify.ru/
*
* @author      Andrei Osintsev
* @copyright   Copyright (c) 2024 asosintsev@yandex.ru
*/
?>
<?php get_header(); ?>
<?php
    $site_url         = site_url();
    $page_url         = get_page_uri();
    $template_url     = get_template_directory_uri();
    $pageSearchString = $_GET['param'];
    $isAuth           = isset($_SESSION['auth']);
?>
<div class="wrapper">
    <div class="hero">
        <div class="hero__content">
            <div class="breadcrumbs hero__breadcrumbs">
                <a class="breadcrumbs__link"
                   href="<?php echo $site_url; ?>"
                   title="На главную">
                   Главная
                </a>
                /
                <a class="breadcrumbs__link"
                   href="<?php echo $site_url. '/' .$page_url; ?>"
                   title="Поиск по номеру сертификата">
                   Поиск по номеру
                </a>
                <?php
                    if (!empty($pageSearchString)) echo '/ ' .$pageSearchString;
                ?>
            </div>
            <h1 class="hero__title">
                Поиск по номеру сертификата
            </h1>
            <?php get_template_part(
                'partials/search-item',
                null, [
                    'title'                => 'Поиск сертификата по номеру:',
                    'placeholder'          => $pageSearchString,
                    'placeholderDefault'   => 'Например, RU.СЩ04.B02830',
                    'action'               => $site_url. '/' . get_page_uri(),
                    'param'                => 'param'
                    ]
                );
            ?>
        </div>
    </div>
    <main class="main">
        <div class="content">
            <?php if ($pageSearchString == '') { ?>
                <section class="content__foreword">
                    <h2 class="title-section content__title">
                        Поиск по номеру сертификата соответствия
                    </h2>
                    <p>Введите номер сертификата или его часть.</p>
                    <p>Чтобы найти номер сертификата или декларации, см. изображения ниже.</p>
                </section>
                <?php if (!$isAuth) { ?>
                    <div class="content__ad"></div>
                <?php } ?>
                <section class="examples">
                    <h3 class="title-section examples__title">
                        Где найти номер сертификата соответствия
                    </h3>
                    <p>Номер сертификата соответствия выделен цветом на рисунках ниже.</p>
                    <ul class="examples__items">
                        <li class="examples__item">
                            <img class="examples__image" src="<?php echo $template_url; ?>/images/gost_o.jpg" alt="Где найти номер сертификата соответствия ГОСТ Р обязательная сертификация">
                            <p class="examples__description">
                                Номер сертификата соответствия ГОСТ Р обязательная сертификация
                            </p>
                        </li>
                        <li class="examples__item">
                            <img class="examples__image" src="<?php echo $template_url; ?>/images/gost_d.jpg" alt="Где найти номер сертификата соответствия ГОСТ Р добровольная сертификация">
                            <p class="examples__description">
                                Номер сертификата соответствия ГОСТ Р добровольная сертификация
                            </p>
                        </li>
                        <li class="examples__item">
                            <img class="examples__image" src="<?php echo $template_url; ?>/images/ss.jpg" alt="Где найти номер сертификата соответствия Таможенного союза">
                            <p class="examples__description">
                                Номер сертификата соответствия Таможенного союза
                            </p>
                        </li>
                        <li class="examples__item">
                            <img class="examples__image" src="<?php echo $template_url; ?>/images/ds.jpg" alt="Где найти номер декларации соответствия Таможенного союза">
                            <p class="examples__description">
                                Номер декларации соответствия Таможенного союза
                            </p>
                        </li>
                    </ul>
                </section>
            <?php } else {
                $postIds = searchByNum($pageSearchString);
                $postCount = count($postIds);
                if ($postCount > 0) {
            ?>
                    <section class="certificates">
                        <h2 class="title-section certificates__title ">
                            <?php
                                echo declination($postCount, ["Найден", "Найдено", "Найдено"])
                                    . ' ' . $postCount
                                    . ' ' . declination($postCount, ["результат", "результата", "результатов"]);
                            ?>
                        </h2>
                        <p>Внимание: поиск осуществляется по цифрам в номере сертификата или декларации.</p>
                        <div class="certificates__content">
                            <?php
                                foreach($postIds as $postId) {
                                    get_template_part( 'partials/certificates-item', null, ['postId' => $postId] );
                                }
                            ?>
                        </div>

                        <?php if (function_exists ('wp_page_numbers')) wp_page_numbers(); ?>

                    </section>
                    <?php if (!$isAuth) { ?>
                        <div class="content__ad"></div>
                    <?php } ?>
                <?php } else { ?>

                <section class="certificates">
                    <h2 class="title-section certificates__title ">
                        Не удалось найти сертификаты c номером <?php echo $pageSearchString;?>
                    </h2>
                </section>
            <?php } ?>

        <?php } ?>
        </div>
        <aside class="sidebar">
            <?php get_sidebar(); ?>
        </aside>
    </main>
</div>
<?php get_footer(); ?>