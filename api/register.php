<?php
    require('../wp-load.php');
    global $wpdb;

    date_default_timezone_set('Europe/Samara');
    $logFileName = '../payments/payments-'.date("Y_m_d").'.txt';
    $logFile = fopen($logFileName, "a");

    writeLog('==========REGISTER USER=========', $logFile);
    writeLog(date('l jS \of F Y H:i:s'), $logFile);

    session_start();

    if (!(isset($_POST["reg-login"]) || isset($_POST["reg-pass"]))) {
        writeLog('ERROR: NO LOGIN OR PASSWORD SUPPLIED. EXITING', $logFile);
        fclose($logFile);

        $result = [
            'msg' => 'Логин или пароль не переданы',
            'auth' => false
        ];
        echo json_encode($result);
        return;
    }

    $userLogin = $_POST["reg-login"];
    $userPass = $_POST["reg-pass"];

    writeLog('LOGIN: '.$userLogin, $logFile);
    writeLog('ENTERED PASSWORD: '.$userPass, $logFile);

    //Проверим, может быть уже такой пользователь существует?
    $rec = $wpdb->get_row($wpdb->prepare("SELECT email, pass, salt, paid FROM wp_paidusers WHERE email = %s", $userLogin));

    if (isset($rec)) {
    //ПОЛЬЗОВАТЕЛЬ НАЙДЕН - если он оплатил регистрацию и PAID = 1 это ошибка, надо выйти из регистрации и сообщить пользователю.
    //Если он оплатил регистрацию и PAID = 0 - надо удалить учетную запись и создать нового пользователя.

        switch ($rec->paid) {
            case 1: 
                writeLog('USER: '. $userLogin. ' found! Exiting...', $logFile);
                fclose($logFile);

                // Формируем массив для JSON ответа
                $result = [
                    'msg' => 'Учетная запись с таким E-mail уже существует',
                    'auth' => false,
                ];

                echo json_encode($result);
                return;
            case 0:
                //Удалим этого неоплаченного пользователя и перейдем к регистрации
                $wpdb->delete( 'wp_paidusers', ['email' => $userLogin], ['%s']);
        }
    }

    //ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН ИЛИ БЫЛ НАЙДЕН НЕОПЛАЧЕННЫЙ ПОЛЬЗОВАТЕЛЬ И БЫЛ УДАЛЁН - выполняем регистрацию

    //Сформируем соль $salt

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $salt = $randomString;

    //Зашифруем пароль $encodedPass = md5(пароль+salt)

    $encodedPass = md5($userPass.$salt);

    writeLog('PASS: '. $userPass, $logFile);
    writeLog('SALT: '. $salt, $logFile);
    writeLog('MD5: '.  $encodedPass, $logFile);

    //Внесем сведения в базу данных $wpdb

    $wpdb->insert('wp_paidusers',
        [   'email' => $userLogin,
            'pass' => $encodedPass,
            'salt' => $salt,
            'regtime' => date('Y-m-d H:i:s'),
            'lasttime' => date('Y-m-d H:i:s'),
            'paid' => 0,
            'price' => $_SESSION['price'],
            'downloads' => 0
        ], [ '%s', '%s', '%s', '%s', '%s', '%f', '%d' ]);

    writeLog('REGISTERED ID: '. $wpdb->insert_id, $logFile);
    writeLog('SUGGESTED PRICE: '. $_SESSION['price'].' р.', $logFile);
    fclose($logFile);

    // Формируем массив для JSON ответа
    $result = [
        'msg' => 'Регистрация выполнена',
        'auth' => true,
        'id' => $wpdb->insert_id
    ];

    echo json_encode($result);
    return;