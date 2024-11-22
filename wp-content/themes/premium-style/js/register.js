var d_id = '';
var d_link = '';

$(function(){
    /*Нажатие кнопки ВОЙТИ*/
    $("#log-proceed").click(function(event){
        $error = false;
        if ($('input[name="log-login"]').val()=='') {
            $('input[name="log-login"]').css('border-color','#FF0000');
            $('#log-login-error label').text('Необходимо указать e-mail');

            $error = true;
        };
        if ($('input[name="log-pass"]').val()=='') {
            $('input[name="log-pass"]').css('border-color','#FF0000');
            $('#log-pass-error label').text('Необходимо ввести пароль');
          
            $error = true;
        };
        
        if (!$error) {
            doAjaxLogin('log-ajax', '../login.php', window.d_link, window.d_id);
            return false; 
        };
    });

    /*Нажатие кнопки ЗАРЕГИСТРИРОВАТЬСЯ*/
    $("#reg-proceed").click(function(event){
        $error = false;
        if ($('input[name="reg-login"]').val()=='') {
            $('input[name="reg-login"]').css('border-color','#FF0000');
            $('#reg-login-error label').text('Необходимо указать e-mail');

            $error = true;
        };
        var pattern = /.+@.+\..+/i;
        if (!pattern.test($('input[name="reg-login"]').val())) {
            $('input[name="reg-login"]').css('border-color','#FF0000');
            $('#reg-login-error label').text('Адрес e-mail указан неверно');

            $error = true;
        };
        if ($('input[name="reg-pass"]').val()=='') {
            $('input[name="reg-pass"]').css('border-color','#FF0000');
            $('#reg-pass-error label').text('Необходимо ввести пароль');
          
            $error = true;
        };
        if ($('input[name="reg-pass-confirm"]').val()=='') {
            $('input[name="reg-pass-confirm"]').css('border-color','#FF0000');
            $('#reg-pass-confirm-error label').text('Необходимо еще раз ввести пароль');
          
            $error = true;
        };
        if ($('input[name="reg-pass-confirm"]').val()!=$('input[name="reg-pass"]').val()) {
            $('input[name="reg-pass-confirm"]').css('border-color','#FF0000');
            $('#reg-pass-confirm-error label').text('Введенные пароли не совпадают');
          
            $error = true;
        };        
        
        if (!$error) {
            /*yaCounter32820367.reachGoal('reg-click');*/
            doAjaxRegister('reg-ajax', 'reg-ajax', '../register.php');
            return false; 
        };
    });

    /*Нажатие кнопки СКАЧАТЬ СЕРТИФИКАТ или СКАЧАТЬ ПРИЛОЖЕНИЕ*/
    $('.download_link_left, .download_link').click(function(event) {
        /*Проверка, залогинен ли пользователь*/
        d_link = '1';
        d_id = event.target.id;
       	checkAjaxLogin('../checkLogin.php', '1', event.target.id);
    });
    $('.download_link_right').click(function(event) {
        /*Проверка, залогинен ли пользователь*/
        d_link = '2';
        d_id = event.target.id;
       	checkAjaxLogin('../checkLogin.php', '2', event.target.id);
    });

    /*Закрытие модального окна*/
   $('.reg-close').click(function() {
            $(document).on('click', function(event) {
                $('.fixed-overlay, .log-modal, .log-modal-container').css('display', 'none');
                $('.fixed-overlay, .reg-modal, .reg-modal-container').css('display', 'none');
                $('.reg-shadower').css('display', 'block');
                $('.reg-line').css('display', 'block');
                $('.log-hint').css('display', 'block');
        		$('.log-shadower').css('display', 'block');
                $(document).unbind('click');
                $('#wait').hide();                
                $(document.body).css('overflow', '');
                event.stopPropagation();
            });
    });

    /*Сброс ошибок формы входа*/
    $("#log-login-input").click(function(){
        $('input[name="log-login"]').css('border-color','#333333');
        $('#log-login-error label').text('');

    });
    $("#log-pass-input").click(function(){
        $('input[name="log-pass"]').css('border-color','#333333');
        $('#log-pass-error label').text('');
    });
    $("#restore-login-input").click(function(){
        $('input[name="restore"]').css('border-color','#333333');
        $('#restore-pass-error label').text('');
    });

    /*Сброс ошибок формы регистрации*/
    $("#reg-login-input").click(function(){
        $('input[name="reg-login"]').css('border-color','#333333');
        $('#reg-login-error label').text('');

    });
    $("#reg-pass-input").click(function(){
        $('input[name="reg-pass"]').css('border-color','#333333');
        $('#reg-pass-error label').text('');
    });
    $("#reg-pass-input-confirm").click(function(){
        $('input[name="reg-pass-confirm"]').css('border-color','#333333');
        $('#reg-pass-confirm-error label').text('');
    });

    /*Нажатие ссылки ЗАБЫЛИ ПАРОЛЬ?*/
    $("#log-restore-email").click(function(){
        $('#restore-ajax').show();
        //$('.log-shadower').css('opacity', '0.1');
        //$('.log-shadower').css('pointer-events', 'none');
        //$('.log-modal-container').css('height', '380px');
        $('.log-hint').css('display', 'none');
        $('.reg-line').css('display', 'none');
        $('.log-shadower').css('display', 'none');
    });

    /*Нажатие кнопки ОТПРАВИТЬ ПАРОЛЬ НА E-MAIL*/
    $("#restore-proceed").click(function(event){
        $error = false;
        if ($('input[name="restore"]').val()=='') {
            $('input[name="restore"]').css('border-color','#FF0000');
            $('#restore-pass-error label').text('Необходимо указать e-mail');

            $error = true;
        };
        
        if (!$error) {
            restoreAjaxPass('restore-ajax', '../restore.php');
            return false; 
        };
    });

    /*Нажатие кнопки ЗАРЕГИСТРИРОВАТЬ НОВУЮ УЧЕТНУЮ ЗАПИСЬ*/
    $("#log-register").click(function(event){
        $('.fixed-overlay, .log-modal, .log-modal-container').css('display', 'none');
        //Вывести окно регистрации
        ShowRegister();
    });

});

function checkAjaxLogin(url, link, id) {
    $('#wait').show();
    jQuery.ajax({
        url:      url, //url страницы (checkLogin.php)
        method:   "get", //метод отправки
        dataType: "html", //формат данных
        data: {
            p_id: id
        },
        cache: false,
        success: function(response) { //Данные отправлены успешно
            $('#wait').hide();
            //alert(jQuery.parseJSON(response));
            var result = jQuery.parseJSON(response);
            if (result.auth) {
                //Отдать файл
                DownloadFile('../download_count.php?id='+id+'&link='+link+'&direct=true');
                $(document.body).css('overflow', '');
            } else {
                ShowLogin();
            }
            return;
        },
        error: function(response) { // Данные не отправлены
            $('#wait').hide();

        }
    });
}
function doAjaxLogin(ajax_form, url, link, id) {
    $('#wait').show();
    jQuery.ajax({
        url:      url, //url страницы (login.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: jQuery("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
            var result = jQuery.parseJSON(response);
            if (result.auth) {
                $('#wait').hide();
                //Закрыть модальное окно
                $('.fixed-overlay, .log-modal, .log-modal-container').hide();               
                //Отдать файл
                DownloadFile('../download_count.php?id='+id+'&link='+link+'&direct=true');  
                $(document.body).css('overflow', '');
            } else {
                $('#wait').hide();
                $('#log-pass-error label').text(result.msg);
            }
            return;
        },
        error: function(response) { // Данные не отправлены
            $('#wait').hide();
            $('#log-pass-error').text('Ошибка соединения с сервером');
        }
    });
}
function doAjaxRegister(result_form, ajax_form, url) {
    $('#wait').show();
    jQuery.ajax({
        url:      url, //url страницы (login.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: jQuery("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
            var result = jQuery.parseJSON(response);
            if (result.auth) {
                $('#wait').hide();
                //Регистрация прошла успешно
                //$('.fixed-overlay, .reg-modal, .reg-modal-container').hide();
                //переходим к оплате
                $('.reg-shadower').css('display','none'); 
                doPayment(result.id);
            } else {
                $('#wait').hide();
                //Регистрация не удалась
                $('#reg-login-error label').text(result.msg);
            }
            return;
        },
        error: function(response) { // Данные не отправлены
            $('#wait').hide();
            $('#reg-pass-confirm-error label').text('Ошибка соединения с сервером');
        }
    });
}
function restoreAjaxPass(ajax_form, url) {
    $('#wait').show();
    jQuery.ajax({
        url:      url, //url страницы (restore.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: jQuery("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
            $('#wait').hide();
            //alert(jQuery.parseJSON(response));
            var result = jQuery.parseJSON(response);
            if (result.success) {
                $('#restore-pass-error label').css('color', '#333333');
                $('#restore-pass-error label').text('Новый пароль отправлен на указанный E-mail');
            } else {
                $('#restore-pass-error label').text('Указанный E-mail не зарегистрирован на сайте');
            }
            return;
        },
        error: function(response) { // Данные не отправлены
            $('#wait').hide();
            $('#restore-pass-error label').text('Ошибка соединения с сервером');
        }
    });
}
function ClearErrors() {
    //Очистка ошибок
    $('input[name="log-login"]').val('');
    $('input[name="log-login"]').css('border-color','#333333');
    $('#log-login-error label').text('');

    $('input[name="log-pass"]').val('');
    $('input[name="log-pass"]').css('border-color','#333333');
    $('#log-pass-error label').text('');

    $('input[name="restore"]').val('');
    $('input[name="restore"]').css('border-color','#333333');
    $('#restore-pass-error label').text('');

    //Восстановление размеров модального окна логина
    $('#restore-ajax').hide();
    $('.log-shadower').css('opacity', '1');
    $('.log-shadower').css('pointer-events', 'all');
    $('.log-modal-container').css('height', '');

    //Восстановление размеров модального окна регистрации
    $('input[name="reg-login"]').val('');
    $('input[name="reg-login"]').css('border-color','#333333');
    $('#reg-login-error label').text('');

    $('input[name="reg-pass"]').val('');
    $('input[name="reg-pass"]').css('border-color','#333333');
    $('#reg-pass-error label').text('');
          
    $('input[name="reg-pass-confirm"]').val('');
    $('input[name="reg-pass-confirm"]').css('border-color','#333333');
    $('#reg-pass-confirm-error label').text('');

    $('div.reg-payment').hide();
}
function ShowLogin() {
	//Вывести окно регистрации

    ClearErrors();
            
    $('.fixed-overlay, .log-modal').css('display', 'flex');
    $('.log-modal-container').css('display', 'block');
    $(document.body).css('overflow', 'hidden');
}
function DownloadFile(url) {
    document.location.href = url;
}
function ShowRegister() {
    //Вывести окно регистрации

    ClearErrors();
            
    $('.fixed-overlay, .reg-modal').css('display', 'flex');
    $('.reg-modal-container').css('display', 'block');
}
function closeModals() {
    $(document).on('click', function(event) {
        var select = $('.log-modal-container');
        if ($(event.target).closest(select).length) return;
        $('.fixed-overlay, .log-modal, .log-modal-container').hide();
        var select = $('.reg-modal-container');
        if ($(event.target).closest(select).length) return;
        $('.fixed-overlay, .reg-modal, .reg-modal-container').hide();
        $(document).unbind('click');
        event.stopPropagation();
    });
}
function doPayment(id) {
    $("#yandexCardRefillForm input[name=targets]:hidden").val('Регистрация на сайте rostest-certify.ru (ID = ' + id + ')');
    $("#yandexCardRefillForm input[name=label]:hidden").val(id);
    $("#yandexYadRefillForm input[name=targets]:hidden").val('Регистрация на сайте rostest-certify.ru (ID = ' + id + ')');
    $("#yandexYadRefillForm input[name=label]:hidden").val(id);
    $("#yandexMobileRefillForm input[name=targets]:hidden").val('Регистрация на сайте rostest-certify.ru (ID = ' + id + ')');
    $("#yandexMobileRefillForm input[name=label]:hidden").val(id);
    $('div.reg-payment').show();
}