<?php
/*
Template Name: Debug
*
* template-debug.php
*
* Template file for various debugging.
* /debug/
*
* @link        http://rostest-certify.ru/
*
* @author      Andrei Osintsev
* @copyright   Copyright (c) 2024 asosintsev@yandex.ru
*/
?>
<?php get_header(); ?>
<?php
    $site_url            = site_url();
    $page_url            = get_page_uri();

    $isAuth              = isset($_SESSION['auth']);
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
                   title="Отладка">
                    Отладка
                </a>
            </div>
            <h1 class="hero__title">
                Страница для отладки
            </h1>
        </div>
    </div>
    <main class="main">
        <div class="content">
            <ol>
                <?php
                    $num = 0;
                    $agencies = getAllAgenciesNames();
                    asort($agencies);
                    foreach ($agencies as $regnum => $agency) {
                        echo '<li>'. $regnum .' : '. $agency. '</li>';
                        ++$num;
                    };
                    echo '<br/>Всего <b>'. $num. '</b> организаций';
                ?>
            </ol>
        </div>
        <aside class="sidebar">
            <?php get_sidebar(); ?>
        </aside>
    </main>
</div>
<?php get_footer(); ?>