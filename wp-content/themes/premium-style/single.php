<?php
/**
 * single.php
 *
 * The Template for displaying all single posts.
 *
 * @link        http://rostest-certify.ru/
 *
 * @author      Andrei Osintsev
 * @copyright   Copyright (c) 2024 asosintsev@yandex.ru
 */
?>
<?php
    $site_url   = site_url();
    $isAuth     = isset($_SESSION['auth']);
?>
<?php get_header(); ?>
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
                   href="<?php echo $site_url; ?>/reestr-sertifikatov/"
                   title="Реестр сертификатов">
                    Реестр сертификатов
                </a>
                /
                <?php echo getCertNumber(get_the_ID()); ?>
            </div>
            <h1 class="hero__title">
                Сертификат <?php echo the_title(); ?>
            </h1>
            <div class="hero__manufacturer">
                <?php echo getCleanName(getCertManufacturer(get_the_ID())); ?>
            </div>
        </div>
    </div>
    <main class="main">
        <div class="content">
            <div class="content__ad"></div>
            <div class="certificate">
                <?php
                    $thumbnail = getThumbnail(454, 650, get_the_ID(), false);
                    $thumbnail2 = getThumbnail(454, 650, get_the_ID(), true);
                ?>
                <img class="certificate__image"
                     src="<?php echo $thumbnail;?>"
                     alt="Сертификат на <?php echo the_title(); ?>"
                     title="Скачать сертификат на <?php echo the_title(); ?>">
                <?php
                    if ($thumbnail2 !== '') { ?>
                        <img class="certificate__image"
                             src="<?php echo $thumbnail2;?>"
                             alt="Приложение к сертификату на <?php echo the_title(); ?>"
                             title="Скачать приложение к сертификату на <?php echo the_title(); ?>">
                <?php } ?>
            </div>
            <div class="specs">
                <?php
                    get_template_part('partials/certificates-downloads',null, ['postId' => get_the_ID(), 'position' => 'up']);
                    get_template_part('partials/certificates-specs', null, ['postId' => get_the_ID()] );
                    get_template_part('partials/certificates-downloads',null, ['postId' => get_the_ID(), 'position' => 'bottom']);
                ?>
                <div class="tags specs__tags">
                    <?php
                        $tags = get_the_tags(get_the_ID());
                        foreach ($tags as $tag) { ?>
                            <div class="tags__tag">
                                <a class="tags__link"
                                   href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                                    title="Сертификаты на <?php echo esc_html($tag->name); ?>">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (!$isAuth) { ?>
                <div class="content__ad"></div>
            <?php } ?>
            <?php related_posts(); ?>
        </div>
        <aside class="sidebar">
            <?php get_sidebar(); ?>
        </aside>
    </main>
</div>

<div class="preloader"></div>
<div class="modal-overlay"></div>

<!--ФОРМА ЛОГИНА-->
<div class="modal modal-login">
    <div class="modal__header">
        <div class="modal__title">
            Скачивание сертификата
        </div>
        <div class="modal__subtitle">
            Чтобы скачать сертификат необходимо войти или зарегистрироваться
        </div>
    </div>
    <div class="modal__content">
        <div class="modal__line"></div>
        <div class="modal__group">
            <label class="modal__label">E-mail:</label>
            <input class="modal__text"
                   type="text"
                   name="log-login"
                   size="10"
                   maxlength="100"
                   placeholder="Введите E-mail"
            >
        </div>
        <div class="modal__group">
            <label class="modal__label">Пароль:</label>
            <input class="modal__text"
                   type="text"
                   name="log-login"
                   size="10"
                   maxlength="100"
                   placeholder="Введите пароль"
            >
            <a class="modal__link modal__link_right" href="#" title="Нажмите, если забыли пароль">Забыли пароль?</a>
        </div>
        <button class="button modal__button" type="button">
            Войти и скачать
        </button>
        <div class="modal__subcontent">
            Ещё нет учётной записи? <a class="modal__subcontent-link" href="#" title="Нажмите, чтобы зарегистрироваться">Зарегистрируйтесь!</a>
        </div>
    </div>
    <div class="modal__close" title="Закрыть"></div>
</div>

<!--ФОРМА СБРОСА ПАРОЛЯ-->
<div class="modal modal-reset-password">
    <div class="modal__header">
        <div class="modal__title">
            Забыли пароль?
        </div>
        <div class="modal__subtitle">
            Мы вышлем новый пароль на e-mail, указанный при регистрации
        </div>
    </div>
    <div class="modal__content">
        <div class="modal__line"></div>
        <div class="modal__group">
            <label class="modal__label">E-mail:</label>
            <input class="modal__text"
                   type="text"
                   name="log-login"
                   size="10"
                   maxlength="100"
                   placeholder="Введите E-mail"
            >
        </div>
        <button class="button modal__button" type="button">
            Получить пароль
        </button>
        <div class="modal__subcontent">
            <a class="modal__subcontent-link" href="#" title="Нажмите, чтобы отменить сброс пароля">Отмена</a>
        </div>
    </div>
    <div class="modal__close" title="Закрыть"></div>
</div>

<!--ФОРМА РЕГИСТРАЦИИ-->
<div class="modal modal-reg">
    <div class="modal__header">
        <div class="modal__title">
            Регистрация
        </div>
        <div class="modal__subtitle">
            Создайте новую учётную запись, чтобы скачивать сертификаты соответствия
        </div>
    </div>
    <div class="modal__content">
        <ul class="modal__benefits">
            <li class="modal__benefit">Неограниченное количество скачиваний</li>
            <li class="modal__benefit">Более 3500 сертификатов и деклараций</li>
            <li class="modal__benefit">Изображения документов высокого качества</li>
            <li class="modal__benefit">Регулярное пополнение базы документов</li>
        </ul>
        <div class="modal__line"></div>
        <div class="modal__group">
            <label class="modal__label">E-mail:</label>
            <input class="modal__text"
                   type="text"
                   name="log-login"
                   size="10"
                   maxlength="100"
                   placeholder="Введите E-mail"
            >
        </div>
        <div class="modal__group">
            <label class="modal__label">Придумайте пароль:</label>
            <input class="modal__text"
                   type="text"
                   name="log-login"
                   size="10"
                   maxlength="100"
                   placeholder="Введите пароль"
            >
        </div>
        <div class="modal__group">
            <label class="modal__label">Повторите пароль:</label>
            <input class="modal__text"
                   type="text"
                   name="log-login"
                   size="10"
                   maxlength="100"
                   placeholder="Введите пароль"
            >
        </div>
        <button class="button modal__button" type="button">
            Продолжить
        </button>
        <div class="modal__subcontent">
            Уже есть учётная запись? <a class="modal__subcontent-link" href="#" title="Нажмите, чтобы войти">Войдите!</a>
        </div>
    </div>
    <div class="modal__close" title="Закрыть"></div>
</div>

<!--ФОРМА РЕГИСТРАЦИИ 2-->
<div class="modal modal-reg2">
    <div class="modal__header">
        <div class="modal__title">
            Регистрация
        </div>
        <div class="modal__subtitle">
            Создайте новую учётную запись, чтобы скачивать сертификаты соответствия
        </div>
    </div>
    <div class="modal__content">
        <ul class="modal__benefits">
            <li class="modal__benefit">Неограниченное количество скачиваний</li>
            <li class="modal__benefit">Более 3500 сертификатов и деклараций</li>
            <li class="modal__benefit">Изображения документов высокого качества</li>
            <li class="modal__benefit">Регулярное пополнение базы документов</li>
        </ul>
        <div class="modal__line"></div>
        <div class="modal__group">
            <div class="modal__textline modal__textline_bold">
                Стоимость регистрации
            </div>
            <div class="modal__proposal">
                <div class="modal__price">
                    199 р
                </div>
                <div>
                    Неограниченный срок
                </div>
            </div>
        </div>
        <div class="modal__line"></div>
        <div class="modal__group">
            <div class="modal__textline modal__textline_light">
                Для регистрации необходимо выполнить оплату
                <p class="modal__paragraph">
                    <span class="modal__textline modal__textline_bold">Назначение:</span> Регистрация на сайте rostest-certify.ru
                </p>
                <p class="modal__paragraph">
                    <span class="modal__textline modal__textline_bold">Сумма платежа:</span> 199 рублей
                </p>
            </div>
        </div>
        <div class="modal__group">
            <ul class="modal__payment-options">
                <li class="modal__payment-option modal__payment-option_card">
                </li>
                <li class="modal__payment-option modal__payment-option_yad"></li>
                <li class="modal__payment-option modal__payment-option_mobile"></li>
            </ul>
        </div>
        <div class="modal__subcontent">
            Оплачивая, вы даёте <a class="modal__subcontent-link" href="#" title="Нажмите, для просмотра ">согласие на обработку персональных данных</a>
        </div>
    </div>
    <div class="modal__close" title="Закрыть"></div>
</div>
<?php get_footer(); ?>