<?php
/*
Template Name: Norms
*
* template-norms.php
*
* Template file for norm (GOSTs and technical regulations selection.
* /gosty/
*
* @link        http://rostest-certify.ru/
*
* @author      Andrei Osintsev
* @copyright   Copyright (c) 2024 asosintsev@yandex.ru
*/
?>
<?php
    $site_url            = site_url();
    $page_url            = get_page_uri();

    $normSearchString    = $_GET['norm'];
    $isAuth              = isset($_SESSION['auth']);
?>
<?php get_header(); ?>
    <?php
        if ($normSearchString == '') {
            get_template_part('partials/norms-all');
        } else {
            $norms = getNormsByName($normSearchString);
            $normCount = count($norms);
            if ($normCount > 0) {
                //Если найдена только одна норма, то выводим ее, иначе выведем список похожих норм
                if ($normCount == 1) {
                    get_template_part('partials/norms-one', null, $norms);
                } else {
                    get_template_part('partials/norms-some', null, $norms);
                }
            } else {
                get_template_part('partials/norms-not-found', null, $norms);
            }
            if (!$isAuth) { ?>
                <div class="content__ad"></div>
            <?php }
        } ?>
        </div>
        <aside class="sidebar">
            <?php get_sidebar(); ?>
        </aside>
    </main>
    </div>
<?php get_footer(); ?>