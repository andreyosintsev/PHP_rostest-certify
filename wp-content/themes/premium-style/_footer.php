<?php
/**
 * footer.php
 *
 * The template for displaying the footer. Contains footer 
 * content and the closing of the html elements.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<div class="clear"></div>
<!--noindex-->
<?php if (is_single()) { ?>
    <!--ФОРМА ЛОГИНА-->
    <div id="wait"></div>
    <div class="fixed-overlay" id="log-popup"></div>
    <div class="log-modal" id="log-modal">
        <div class="log-modal-container">
            <div class="reg-close" title="Закрыть"></div>
            <div class="reg-h1">Скачивание</div>
            <div class="reg-line"></div>
            <p class="log-hint">Чтобы скачать, необходимо войти или зарегистрироваться</p>
            <div class="log-clear"></div>
            <div class="log-shadower">
                <form name="log" id="log-ajax" class="log-form" action="<?php echo get_permalink();?>" method="post">
                    <div class="log-label" id="log-login"><label>E-mail</label></div>
                    <div class="log-input" id="log-login-input"><input type="text" name="log-login" size="10" maxlength="100" placeholder="Введите ваш E-mail"></div>
                    <div class="log-clear"></div>
                    <div class="log-label-error" id="log-login-error"><label></label></div>
                    <div class="log-label" id="log-pass"><label>Пароль</label></div>
                    <div class="log-input" id="log-pass-input"><input type="password" name="log-pass" size="10" maxlength="100" placeholder="Введите ваш пароль"></div>
                    <div class="log-clear"></div>
                    <div class="log-label-error" id="log-pass-error"><label></label></div>
                    <p class="log-restore" id="log-restore-email">Забыли пароль?</p>
                    <div class="log-submit" id="log-proceed"><input type="button" name="submit" value="Войти и скачать" autofocus></div>
                </form>
                <div class="log-register" id="log-register">Зарегистрировать новую учетную запись</div>
            </div>
            <form name="restore" id="restore-ajax" class="restore-form" action="<?php echo get_permalink();?>" method="post">
                <div class="restore-label" id="restore-login"><label>Введите E-mail, на который будет отправлен пароль</label></div>
                <div class="restore-input" id="restore-login-input"><input type="text" name="restore" size="10" maxlength="100" placeholder="Введите ваш e-mail"></div>
                <div class="restore-label-error" id="restore-pass-error"><label></label></div>
                <div class="restore-submit" id="restore-proceed"><input type="button" name="submit" value="Отправить пароль на E-mail"></div>
            </form>
        </div>
    </div>
    <!--/ФОРМА ЛОГИНА-->
    <!--ФОРМА РЕГИСТРАЦИИ-->
    <div class="reg-modal" id="reg-modal">
        <div class="reg-modal-container">
            <div class="reg-close" title="Закрыть"></div>
            <div class="reg-h1">Регистрация</div>
            <div class="reg-line"></div>
            <p class="reg-hint">Регистрация новой учетной записи</p>
            <div class="reg-clear"></div>
            <div class="reg-proposal">
                <p class="reg-proposal-foreword">Зарегистрируйтесь на сайте, чтобы скачивать сертификаты соответствия</p>
                <ul>
                    <?php
                        $total_count = wp_count_posts();
                        $total_published = round ($total_count->publish,-2, PHP_ROUND_HALF_DOWN);
                    ?>
                    <li>Одна регистрация позволяет скачать неограниченное количество сертификатов<li>
                    <li><b>Более <?php echo $total_published;?></b> сертификатов и деклараций<li>
                    <li>Изображения документов высокого качества<li>
                    <li>Регулярное пополнение базы документов<li>
                </ul>
                <p class="reg-proposal-foreword" style="margin-bottom: 0;">Стоимость регистрации</p>
                <div class="reg-proposal-price">
                    <?php
                    if (isset($_SESSION['price'])) {
                        $price = $_SESSION['price'];
                    } else {
                        $price = getPriceCurrent();
                        $_SESSION['price'] = $price;
                    }
                    $price_old = getPriceOld($price);
                    ?>        
                    <!--p class="reg-proposal-pricenew"><?php /*echo*/ $price_old;?> р.</p--><p class="reg-proposal-oldprice" style="font-weight: bold; color: #333333;"><?php echo $price;?> р.</p>
                    <div class="reg-clear"></div>
                    <p class="reg-proposal-till">Неограниченный срок</p>
                </div>      
            </div>
            <div class="reg-payment">
                <div class="reg-payment-title">Для регистрации необходимо выполнить оплату</div>
                <div class="reg-payment-sum"><b>Назначение:</b> Регистрация на сайте rostest-certify.ru</div>
                <div class="reg-payment-sum"><b>Сумма платежа:</b> <?php echo $price;?> рублей</div>
                <div class="reg-payment-meth">Выберите способ оплаты</div>
                <div class="reg-payment-card" onclick="$('form[name=yandexCardRefillForm]').submit();" title="Банковская карта">
                    <form name="yandexCardRefillForm" id="yandexCardRefillForm" method="post" action="https://yoomoney.ru/quickpay/confirm.xml">
                        <input type="hidden" name="receiver" value="41001587087146">
                        <input type="hidden" name="quickpay-form" value="shop">
                        <input type="hidden" name="targets" value="Регистрация на сайте rostest-certify.ru (ID = ERROR)">
                        <input type="hidden" name="paymentType" value="AC">
                        <input type="hidden" name="sum" value="<?php echo $price;?>">
                        <input type="hidden" name="formcomment" value="Регистрация на сайте rostest-certify.ru">
                        <input type="hidden" name="short-dest" value="Регистрация на сайте rostest-certify.ru">
                        <input type="hidden" name="label" value="ERROR">
                        <input type="hidden" name="successURL" value="<?php echo get_permalink();?>">
                    </form>
                </div>
                <div class="reg-payment-yad" onclick="$('form[name=yandexYadRefillForm]').submit();" title="Yoomoney (Яндекс.Деньги)">
                    <form name="yandexYadRefillForm" id="yandexYadRefillForm" method="post" action="https://yoomoney.ru/quickpay/confirm.xml">
                        <input type="hidden" name="receiver" value="41001587087146">
                        <input type="hidden" name="quickpay-form" value="shop">
                        <input type="hidden" name="targets" value="Регистрация на сайте rostest-certify.ru (ID = ERROR)">
                        <input type="hidden" name="paymentType" value="PC">
                        <input type="hidden" name="sum" value="<?php echo $price;?>">
                        <input type="hidden" name="formcomment" value="Регистрация на сайте rostest-certify.ru">
                        <input type="hidden" name="short-dest" value="Регистрация на сайте rostest-certify.ru">
                        <input type="hidden" name="label" value="ERROR">
                        <input type="hidden" name="successURL" value="<?php echo get_permalink();?>">
                    </form>
                </div>
                <div class="reg-payment-mobile" onclick="$('form[name=yandexMobileRefillForm]').submit();" title="Баланс мобильного телефона">
                    <form name="yandexMobileRefillForm" id="yandexMobileRefillForm" method="post" action="https://yoomoney.ru/quickpay/confirm.xml">
                        <input type="hidden" name="receiver" value="41001587087146">
                        <input type="hidden" name="quickpay-form" value="shop">
                        <input type="hidden" name="targets" value="Регистрация на сайте rostest-certify.ru (ID = ERROR)">
                        <input type="hidden" name="paymentType" value="MC">
                        <input type="hidden" name="sum" value="<?php echo $price;?>">
                        <input type="hidden" name="formcomment" value="Регистрация на сайте rostest-certify.ru">
                        <input type="hidden" name="short-dest" value="Регистрация на сайте rostest-certify.ru">
                        <input type="hidden" name="label" value="ERROR">
                        <input type="hidden" name="successURL" value="<?php echo get_permalink();?>">
                    </form>
                </div>
                <div class="reg-clear"></div>
                <div class="reg-payment-agree">Оплачивая, вы даете <a href="/policy-personal.php" target="_blank">согласие на обработку персональных данных</a></div>
            </div>
            <div class="reg-shadower">
                <form name="reg" id="reg-ajax" class="reg-form" action="<?php echo get_permalink();?>" method="post">
                
                    <div class="reg-label" id="reg-login"><label>Ваш E-mail</label></div>
                    <div class="reg-input" id="reg-login-input"><input type="text" name="reg-login" size="10" maxlength="100" placeholder="E-mail для входа в новую учетную запись"></div>
                    <div class="reg-clear"></div>
                    <div class="reg-label-error" id="reg-login-error"><label></label></div>
                    <div class="reg-label" id="reg-pass"><label>Придумайте пароль</label></div>
                    <div class="reg-input" id="reg-pass-input"><input type="password" name="reg-pass" size="10" maxlength="100"></div>
                    <div class="reg-clear"></div>
                    <div class="reg-label-error" id="reg-pass-error"><label></label></div>
                    <div class="reg-label" id="reg-pass-confirm"><label>Повторите пароль</label></div>
                    <div class="reg-input" id="reg-pass-input-confirm"><input type="password" name="reg-pass-confirm" size="10" maxlength="100"></div>
                    <div class="reg-clear"></div>
                    <div class="reg-label-error" id="reg-pass-confirm-error"><label></label></div>
                    <!--div class="reg-submit" id="reg-proceed"><input type="button" name="submit" value="Зарегистрироваться" autofocus></div-->
                    <div class="reg-submit" id="reg-proceed">Зарегистрироваться</div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
    <!--ФОРМА ЛОГИНА-->
<!--/noindex-->
<div id="footer">
	<div class="copyright">
		<div class="footerleft">  
			<?php echo get_theme_mod( 'premiumstyle_footer_l', 'Copyright &copy; 2013-'.date('Y') ).'.'; ?>
            <span>Информация представлена только для ознакомительного использования.</span>
		</div>
		<div class="footerright">
            <!-- Yandex.Metrika informer -->
            <a href="https://metrika.yandex.ru/stat/?id=32820367&amp;from=informer"
            target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/32820367/3_0_FFFFFFFF_F2F2F2FF_0_pageviews"
            style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="32820367" data-lang="ru" /></a>
            <!-- /Yandex.Metrika informer -->

            <!-- Yandex.Metrika counter -->
            <script type="text/javascript" >
               (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
               m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
               (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

               ym(32820367, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
               });
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/32820367" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
		</div>
		<div class="clear"></div>
	</div>
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>