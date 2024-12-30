<?php
/**
 * archive.php
 *
 * Template file for categories, tags e.t.c.
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
    $isAuth              = isset($_SESSION['auth']);
    $breadcrumbName      = 'Рубрики';
    $breadcrumbLink      = '#';
    $currentCategoryName = '';

    if (is_tag()) {
        $breadcrumbName = 'Теги';
    } elseif (is_category())  {
        $cat = get_category(get_query_var('cat'));

        if ($cat && !is_wp_error($cat)) {
            $currentCategoryName = $cat->cat_name;
            $ancestors = get_ancestors($cat->cat_ID, 'category');

            if (!empty($ancestors)) {
                $breadcrumbName = get_cat_name(end($ancestors));

                switch ($breadcrumbName) {
                    case 'ОКП':
                        $breadcrumbLink = $site_url .'/naiti-sertifikat-po-vidu-produktsii?classificator=okp';
                        break;
                    case 'ТН ВЭД ТС':
                        $breadcrumbLink = $site_url .'/naiti-sertifikat-po-vidu-produktsii?classificator=tnvedts';
                        break;
                    default:
                        $breadcrumbLink = get_category_link($cat);
                }
            } else {
                $catParentName = $cat->name;
                $breadcrumbLink = get_category_link($cat);
            }
        }
    }
?>
<div class="wrapper">
        <div class="hero">
            <div class="hero__content">
                <div class="breadcrumbs hero__breadcrumbs">
                    <a class="breadcrumbs__link"
                       href="<?php echo $site_url; ?>"
                       title="На главную">
                       Главная</a>
                    /
                    <a class="breadcrumbs__link"
                       href="<?php echo $breadcrumbLink; ?>"
                       title="Классификатор <?php echo $breadcrumbName; ?>">
                       <?php echo $breadcrumbName; ?></a>
                    /
                    <?php echo mb_ucfirst($currentCategoryName); ?>
                </div>
                <h1 class="hero__title">
                    Сертификаты на <?php
                        if (is_tag()) { echo  $currentCategoryName; }
                        elseif (is_category()) { echo mb_lcfirst(splitStringByDash($currentCategoryName)[1]); }
                    ?>
                </h1>
            </div>
        </div>
        <main class="main">
            <div class="content">
                <section class="certificates">
                    <?php
                        global $query_string;
                        query_posts($query_string . "&orderby=title&order=ASC");
                        global $wp_query;

                        $postCount = $wp_query->found_posts;
                        if (have_posts()) {
                    ?>
                        <h2 class="title-section certificates__title ">
                            <?php
                                echo declination($postCount, ["Найден", "Найдено", "Найдено"])
                                    . ' ' . $postCount
                                    . ' ' . declination($postCount, ["результат", "результата", "результатов"]);
                            ?>
                        </h2>
                        <div class="certificates__content">
                            <?php
                                $num = 0;
                                while (have_posts()) : the_post();
                                    get_template_part( 'partials/certificates-item', null, ['postId' => get_the_ID()] );

                                    if (++$num == 2 && !$isAuth) { ?>
                                        <div class="content__ad"></div>
                                    <?php }
                                endwhile;
                            ?>
                        </div>
                        <?php if (function_exists ('wp_page_numbers')) wp_page_numbers (); ?>
                    <?php } else { ?>
                        <section class="certificates">
                            <h2 class="title-section certificates__title ">
                                Не удалось найти сертификаты на <?php
                                    if (is_tag()) { echo  $currentCategoryName; }
                                    elseif (is_category()) { echo mb_lcfirst(splitStringByDash($currentCategoryName)[1]); }
                                ?>
                            </h2>
                        </section>
                    <?php } ?>
                </section>
                <?php if (!$isAuth) { ?>
                    <div class="content__ad"></div>
                <?php } ?>
            </div>
            <aside class="sidebar">
                <?php get_sidebar(); ?>
            </aside>
        </main>
    </div>
<?php get_footer(); ?>