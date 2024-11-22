<?php
    require('wp-load.php');
    global $wpdb;

    session_start();

	if (isset($_POST["reg-login"]) && isset($_POST["reg-pass"]) ) {
    
    $user_login = $_POST["reg-login"];
    $user_pass = $_POST["reg-pass"];


    date_default_timezone_set('Europe/Samara');

    error_log(' '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('========REGISTER USER=========='."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log(date('l jS \of F Y H:i:s')."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

    error_log('LOGIN: '.$user_login."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('ENTERED PASS: '.$user_pass."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

    //Проверим, может быть уже такой пользователь существует?
    $rec = $wpdb->get_row($wpdb->prepare("SELECT email, pass, salt, paid FROM wp_paidusers WHERE email = '$user_login'", $user_login));

    if (isset($rec)) {
//ПОЛЬЗОВАТЕЛЬ НАЙДЕН - если он оплачен PAID = 1 это ошибка, надо выйти из регистрации и сообщить пользователю.
//если он не выполнял оплату и PAID = 0 - надо удалить учетную запись и создать нового пользователя.

        switch ($rec->paid) {
            case 1: 
                // Формируем массив для JSON ответа
                $result = array(
                    'msg' => 'Учетная запись с таким E-mail уже существует',
                    'auth' => false,
                );

                error_log('USER: '.$user_login.' found! Exiting...'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

                //Переводим массив в JSON
                echo json_encode($result);
                return;
                break;
            case 0:
                //Удалим этого неоплаченного пользователя и перейдем к регистрации
                $wpdb->delete( 'wp_paidusers', array('email' => $user_login), array('%s'));
                break;
        }

    }

//ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН - выполняем регистрацию

    //Сформируем соль $salt

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $salt = $randomString;

    //Зашифруем пароль md5(пароль+salt) $encoded_pass

    $encoded_pass = md5($user_pass.$salt);

    error_log('PASS: '.$user_pass."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('SALT: '.$salt."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('MD5: '.$encoded_pass."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

    //Внесем сведения в базу данных $wpdb

    $wpdb->insert('wp_paidusers', array( 'email' => $user_login, 'pass' => $encoded_pass, 'salt' => $salt, 'regtime' => date('Y-m-d H:i:s'), 'lasttime' => date('Y-m-d H:i:s'), 'paid' => 0, 'price' => $_SESSION['price'], 'downloads' => 0), array( '%s', '%s', '%s', '%s', '%s', '%f', '%d' ));

    error_log('REGISTERED ID: '.$wpdb->insert_id."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('SUGGESTED PRICE: '.$_SESSION['price'].' р.'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

    // Формируем массив для JSON ответа
    $result = array(
        'msg' => 'Регистрация выполнена',
        'auth' => true,
        'id' => $wpdb->insert_id,
    );

    // Переводим массив в JSON
    echo json_encode($result);
    return;
}
?>