



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
                   title="Поиск ГОСТа по номеру">
                    Поиск ГОСТа по номеру
                </a>
                <?php
                if (!empty($normSearchString)) echo '/ ' .$normSearchString;
                ?>
            </div>
            <h1 class="hero__title">
                ГОСТы на материалы, товары, продукцию и услуги
            </h1>
            <?php get_template_part(
                'partials/search-item',
                null, [
                    'title'                => 'Поиск ГОСТа по номеру:',
                    'placeholder'          => $normSearchString,
                    'placeholderDefault'   => 'Например, ГОСТ 30547-97',
                    'action'               => $site_url. '/' . get_page_uri(),
                    'param'                => 'norm'
                ]
            );
            ?>
        </div>
    </div>
    <main class="main">
        <div class="content">
            <?php if (!$isAuth) { ?>
            <div class="content__ad"></div>
            <?php } ?>
            <section class="norms">
                <h2 class="title-section norms__title">
                    Перечень ГОСТов и ТР
                </h2>
                <p>ГОСТы, технические регламенты и другие нормативные документы по популярности.</p>
                <p>Для поиска ГОСТа, не представленного в этом перечне, воспользуйтесь поиском.</p>
                <div class="norms__content">
                    <?php
                    $norms = getAllNorms(10);

                    foreach ($norms as $norm) { ?>
                        <div class="norms-item">
                            <a class="norms-item__link"
                               href="<?php echo site_url(); ?>/gosty/?param=<?php echo urlencode($norm->name); ?>"
                               title="Скачать <?php echo $norm->name; ?>">
                                <div class="norms-item__title">
                                    <?php echo $norm->name; ?>
                                </div>
                            </a>
                            <div class="norms-item__description">
                                <?php echo $norm->name_full; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
            <section class="certificates">
                <h2 class="title-section certificates__title">
                    Найдено 5 результатов
                </h2>
                <div class="norms-data certificates__norm">
                    <div class="norms-data__title">
                        ГОСТ Р 51317.3.3-2008
                    </div>
                    <div class="norms-data__description">
                        Совместимость технических средств электромагнитная. Ограничение изменений напряжения, колебаний напряжения и фликера в низковольтных системах электроснабжения общего назначения. Технические средства с потребряемым током не более 16 А (в одной фазе), подключаемые к электрической сети при несоблюдении определенных условий подключения. Нормы и методы испытаний
                    </div>
                    <div class="norms-data__actions">
                        <button class="button norms-data__action" id="button-view-gost" type="button">
                            Смотреть ГОСТ
                        </button>
                        <button class="button norms-data__action button_outlined" type="button">
                            Скачать ГОСТ
                        </button>
                    </div>
                </div>
                <div class="certificates__content">
                    <div class="certificates-item">
                        <div class="certificates-item__link-thumb">
                            <a class="certificates-item__link" href="#">
                                <img class="certificates-item__thumb" src="./images/certificate_thumb.jpg" title="Сертификат на морозильные лари, морозильные шкафы (камеры) вертикального типа, холодильники «Affendiler», «Delvento», «DeLonghi», «Galatec», «GoldStar», «GRESSEL», «hi», «ICQN», «Jacky’s», «Leran», «LuxDorf, «novex», «Optima», «Samtron», «Schaub Lorenz», «Sennixwell», «Simfer», «Willmark»" alt="морозильные лари, морозильные шкафы (камеры) вертикального типа, холодильники «Affendiler», «Delvento», «DeLonghi», «Galatec», «GoldStar», «GRESSEL», «hi», «ICQN», «Jacky’s», «Leran», «LuxDorf, «novex», «Optima», «Samtron», «Schaub Lorenz», «Sennixwell», «Simfer», «Willmark»">
                                <div class="certificates-item__gradient"></div>
                            </a>
                        </div>
                        <div class="certificates-item__description">
                            <div class="certificates-item__title">
                                <a href="#">
                                    Электрическая энергия, поставляемая потребителям от распределительных электрических сетей (центров питания)
                                </a>
                            </div>
                            <div class="certificates-item__manufacturer">
                                <a href="">
                                    Самарский подшипниковый завод
                                </a>
                            </div>
                            <div class="certificates-item__number">
                                            <span class="flag certificates-item__flag">
                                                <img class="flag__image" src="./images/flags/ru.png" alt="RU" title="Произведено в России">
                                            </span>
                                № РОСС RU.АА49.В00022
                            </div>
                        </div>
                        <div class="certificates-item__category-more">
                            <div class="certificates-item__category">
                                <a href="#">64 - Обувь, гетры и аналогичные изделия; их детали</a>
                                <div class="ancestor"><a href="#">6402 - Прочая обувь с подошвой и с верхом из резины или пластмассы</a>
                                    <div class="ancestor"><a href="#">6402 99 910 0 - Прочая, с длиной стельки менее 24 см</a></div>
                                </div>
                            </div>
                            <div class="certificates-item__more">
                                <a href="https://rostest-certificates.ru/sredstva-individualnoj-zashhity-nog-obuv-specialnaya-kozhanaya-v-tom-chisle-uteplennaya-dlya-zashhity-ot-ximicheskix-faktorov-mexanicheskix-vozdejstvij-polubotinki-s-perforaciej-polubotinki-boti/" title="Электрическая энергия, поставляемая потребителям от распределительных электрических сетей (центров питания)">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="certificates-item">
                        <div class="certificates-item__link-thumb">
                            <a class="certificates-item__link" href="#">
                                <img class="certificates-item__thumb" src="./images/certificate_thumb.jpg" title="Сертификат на морозильные лари, морозильные шкафы (камеры) вертикального типа, холодильники «Affendiler», «Delvento», «DeLonghi», «Galatec», «GoldStar», «GRESSEL», «hi», «ICQN», «Jacky’s», «Leran», «LuxDorf, «novex», «Optima», «Samtron», «Schaub Lorenz», «Sennixwell», «Simfer», «Willmark»" alt="морозильные лари, морозильные шкафы (камеры) вертикального типа, холодильники «Affendiler», «Delvento», «DeLonghi», «Galatec», «GoldStar», «GRESSEL», «hi», «ICQN», «Jacky’s», «Leran», «LuxDorf, «novex», «Optima», «Samtron», «Schaub Lorenz», «Sennixwell», «Simfer», «Willmark»">
                                <div class="certificates-item__gradient"></div>
                            </a>
                        </div>
                        <div class="certificates-item__description">
                            <div class="certificates-item__title">
                                <a href="#">
                                    Электрическая энергия, поставляемая потребителям от распределительных электрических сетей (центров питания)
                                </a>
                            </div>
                            <div class="certificates-item__manufacturer">
                                <a href="">
                                    Самарский подшипниковый завод
                                </a>
                            </div>
                            <div class="certificates-item__number">
                                            <span class="flag certificates-item__flag">
                                                <img class="flag__image" src="./images/flags/ru.png" alt="RU" title="Произведено в России">
                                            </span>
                                № РОСС RU.АА49.В00022
                            </div>
                        </div>
                        <div class="certificates-item__category-more">
                            <div class="certificates-item__category">
                                <a href="#">64 - Обувь, гетры и аналогичные изделия; их детали</a>
                                <div class="ancestor"><a href="#">6402 - Прочая обувь с подошвой и с верхом из резины или пластмассы</a>
                                    <div class="ancestor"><a href="#">6402 99 910 0 - Прочая, с длиной стельки менее 24 см</a></div>
                                </div>
                            </div>
                            <div class="certificates-item__more">
                                <a href="https://rostest-certificates.ru/sredstva-individualnoj-zashhity-nog-obuv-specialnaya-kozhanaya-v-tom-chisle-uteplennaya-dlya-zashhity-ot-ximicheskix-faktorov-mexanicheskix-vozdejstvij-polubotinki-s-perforaciej-polubotinki-boti/" title="Электрическая энергия, поставляемая потребителям от распределительных электрических сетей (центров питания)">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="certificates-item">
                        <div class="certificates-item__link-thumb">
                            <a class="certificates-item__link" href="#">
                                <img class="certificates-item__thumb" src="./images/certificate_thumb.jpg" title="Сертификат на морозильные лари, морозильные шкафы (камеры) вертикального типа, холодильники «Affendiler», «Delvento», «DeLonghi», «Galatec», «GoldStar», «GRESSEL», «hi», «ICQN», «Jacky’s», «Leran», «LuxDorf, «novex», «Optima», «Samtron», «Schaub Lorenz», «Sennixwell», «Simfer», «Willmark»" alt="морозильные лари, морозильные шкафы (камеры) вертикального типа, холодильники «Affendiler», «Delvento», «DeLonghi», «Galatec», «GoldStar», «GRESSEL», «hi», «ICQN», «Jacky’s», «Leran», «LuxDorf, «novex», «Optima», «Samtron», «Schaub Lorenz», «Sennixwell», «Simfer», «Willmark»">
                                <div class="certificates-item__gradient"></div>
                            </a>
                        </div>
                        <div class="certificates-item__description">
                            <div class="certificates-item__title">
                                <a href="#">
                                    Электрическая энергия, поставляемая потребителям от распределительных электрических сетей (центров питания)
                                </a>
                            </div>
                            <div class="certificates-item__manufacturer">
                                <a href="">
                                    Самарский подшипниковый завод
                                </a>
                            </div>
                            <div class="certificates-item__number">
                                            <span class="flag certificates-item__flag">
                                                <img class="flag__image" src="./images/flags/ru.png" alt="RU" title="Произведено в России">
                                            </span>
                                № РОСС RU.АА49.В00022
                            </div>
                        </div>
                        <div class="certificates-item__category-more">
                            <div class="certificates-item__category">
                                <a href="#">64 - Обувь, гетры и аналогичные изделия; их детали</a>
                                <div class="ancestor"><a href="#">6402 - Прочая обувь с подошвой и с верхом из резины или пластмассы</a>
                                    <div class="ancestor"><a href="#">6402 99 910 0 - Прочая, с длиной стельки менее 24 см</a></div>
                                </div>
                            </div>
                            <div class="certificates-item__more">
                                <a href="https://rostest-certificates.ru/sredstva-individualnoj-zashhity-nog-obuv-specialnaya-kozhanaya-v-tom-chisle-uteplennaya-dlya-zashhity-ot-ximicheskix-faktorov-mexanicheskix-vozdejstvij-polubotinki-s-perforaciej-polubotinki-boti/" title="Электрическая энергия, поставляемая потребителям от распределительных электрических сетей (центров питания)">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="norms__nav" id='wp_page_numbers'>
                <ul>
                    <li class="page_info">Стр. 1 из 176</li>
                    <li class="active_page">
                        <a href="https://rostest-certify.ru/reestr-sertifikatov/">1</a>
                    </li>
                    <li>
                        <a href="https://rostest-certify.ru/reestr-sertifikatov/page/2/">2</a>
                    </li>
                    <li>
                        <a href="https://rostest-certify.ru/reestr-sertifikatov/page/3/">3</a>
                    </li>
                    <li>
                        <a href="https://rostest-certify.ru/reestr-sertifikatov/page/2/">&gt;</a>
                    </li>
                </ul>
            </div>
            <?php } else { ?>
            <section class="certificates">
                <h2 class="title-section certificates__title ">
                    <?php
                    $wp_query->set_404();
                    status_header( 404 );
                    ?>
                    Не удалось найти нормативные документы <?php echo $normSearchString; ?>
                </h2>
            </section>
            <?php } ?>
            <?php } ?>
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