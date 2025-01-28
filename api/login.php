<?php
    require('../wp-load.php');
    global $wpdb;

    date_default_timezone_set('Europe/Samara');
    $logFileName = '../payments/payments-'.date("Y_m_d").'.txt';
    $logFile = fopen($logFileName, "a");

    writeLog('==========LOGIN ATTEMPT==========', $logFile);
    writeLog(date('l jS \of F Y H:i:s'), $logFile);

	if (!(isset($_POST["log-login"]) || isset($_POST["log-pass"]))) {
        writeLog('ERROR: NO LOGIN OR PASSWORD SUPPLIED. EXITING', $logFile);
        fclose($logFile);
        $result = [
            'msg' => 'Логин или пароль не указаны',
            'auth' => false,
        ];
    }
    
    $user_login = $_POST["log-login"];
    $user_pass = $_POST["log-pass"];

    writeLog('USER TRIES TO LOG IN: '.$user_login.' pass: '.$user_pass, $logFile);

    //Ищем пользователя с таким логином и ВЫПОЛНИВШЕГО ОПЛАТУ PAID = 1
    $rec = $wpdb->get_row($wpdb->prepare("SELECT email, pass, salt FROM wp_paidusers WHERE email = %s AND paid=1", $user_login));

    if (!isset($rec)) {
    //ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН
        writeLog('USER: '.$user_login.' not found!', $logFile);

        session_start();
        $_SESSION['auth'] = false;

        $result = [
            'msg' => 'Пользователь не найден. Зарегистрируйтесь.',
            'auth' => false,
        ];

        echo json_encode($result);
        writeLog('Exiting', $logFile);
        fclose($logFile);
        return;
    }

    //ПОЛЬЗОВАТЕЛЬ НАЙДЕН
    //Проверим пароль md5(пароль+salt)
    if (md5($user_pass.$rec->salt) == ($rec->pass)) {

        writeLog('USER: '.$user_login.' FOUND!', $logFile);
        //Вот теперь точно наш человек
        //Запустим сессию чтобы каждый раз пароль не спрашивать
        session_start();

    	$_SESSION['auth'] = true;
    	$_SESSION['login'] = $user_login;
    	$_SESSION['pass'] = $user_pass;

        //Запишем в дату и время последнего посещения
        $res = $wpdb->update('wp_paidusers', [ 'lasttime' => date('Y-m-d H:i:s') ], [ 'email' => $user_login ]);

        // Формируем массив для JSON ответа
        $result = [
            'msg' => 'Вход произведен',
            'auth' => true,
        ];

        writeLog('USER: '.$user_login.' LOGGED ON!', $logFile);

        echo json_encode($result);
        fclose($logFile);
        return;
    } else {
        //Пароль неверен
        session_start();
        $_SESSION['auth'] = false;

        $result = [
            'msg' => 'Неверный пароль',
            'auth' => false,
        ];

        writeLog('USER: '.$user_login.' wrong password!', $logFile);
        writeLog('Exiting', $logFile);

        echo json_encode($result);
        fclose($logFile);
        return;
    }