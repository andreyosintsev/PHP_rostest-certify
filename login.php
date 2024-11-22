<?php
    require('wp-load.php');
    global $wpdb;

	if (isset($_POST["log-login"]) && isset($_POST["log-pass"]) ) {
    
    $user_login = $_POST["log-login"];
    $user_pass = $_POST["log-pass"];

    date_default_timezone_set('Europe/Samara');
    
    error_log(' '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('========LOGIN ATTEMPT=========='."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log(date('l jS \of F Y H:i:s')."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');


    error_log('USER TRIES TO LOG IN: '.$user_login.' pass: '.$user_pass."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

    //Ищем пользователя с таким логином и ВЫПОЛНИВШЕГО ОПЛАТУ PAID=1
    $rec = $wpdb->get_row($wpdb->prepare("SELECT email, pass, salt FROM wp_paidusers WHERE email = '$user_login' AND paid=1", $user_login));

    if (!isset($rec)) {
//ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН

        session_start();
        $_SESSION['auth'] = false;

        // Формируем массив для JSON ответа
        $result = array(
            'msg' => 'Учетная запись не найдена. Необходимо зарегистрироваться',
            'auth' => false,
        );

        error_log('USER: '.$user_login.' not found!'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

        // Переводим массив в JSON
        echo json_encode($result);
        return;
    }

//ПОЛЬЗОВАТЕЛЬ НАЙДЕН

    //Проверим пароль md5(пароль+salt)
    if (md5($user_pass.$rec->salt) == ($rec->pass)) {
        //Вот теперь точно наш человек
    
        //Запустим сессию чтобы каждый раз пароль не спрашивать
        session_start();
    	$_SESSION['auth'] = true;
    	$_SESSION['login'] = $user_login;
    	$_SESSION['pass'] = $user_pass;

        //Запишем в WPDB дату и время последнего посещения
        $res = $wpdb->update('wp_paidusers', array('lasttime'=>date('Y-m-d H:i:s')), array('email'=>$user_login));

        error_log('SESSION AFTERSET: '.$_SESSION['auth']."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

        // Формируем массив для JSON ответа
        $result = array(
            'msg' => 'Вход произведен',
            'auth' => true,
        );

        error_log('USER: '.$user_login.' LOGGED ON!'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

        // Переводим массив в JSON
        echo json_encode($result);
        return;
    } else {
        //Пароль неверен
        // Формируем массив для JSON ответа
        session_start();
        $_SESSION['auth'] = false;

        $result = array(
            'msg' => 'Неверный пароль',
            'auth' => false,
        );

        error_log('USER: '.$user_login.' wrong password!'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

        // Переводим массив в JSON
        echo json_encode($result);
        return;
    }
}
?>