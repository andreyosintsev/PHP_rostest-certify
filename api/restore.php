<?php
    require('../wp-load.php');
    global $wpdb;

    date_default_timezone_set('Europe/Samara');
    $logFileName = '../payments/payments-'.date("Y_m_d").'.txt';
    $logFile = fopen($logFileName, "a");

    writeLog('=========PASSWORD RESTORE==========', $logFile);
    writeLog(date('l jS \of F Y H:i:s'), $logFile);

    if (!isset($_POST["restore"])) {
        writeLog('ERROR: NO LOGIN SUPPLIED. EXITING', $logFile);
        fclose($logFile);

        $result = [ 'success' => false ];
        echo json_encode($result);
        return;
    }

    $userLogin = $_POST["restore"];

    writeLog('Username: '.$userLogin, $logFile);

    //Ищем пользователя с таким логином и ВЫПОЛНИВШЕГО ОПЛАТУ PAID=1
    $rec = $wpdb->get_row($wpdb->prepare("SELECT email FROM wp_paidusers WHERE email = %s AND paid=1", $userLogin));

    if (!isset($rec)) {
        //ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН
        writeLog('Username: '. $userLogin. ' not found... Exiting', $logFile);
        fclose($logFile);

        $result = [ 'success' => false ];
        echo json_encode($result);
        return;
    }

    //ПОЛЬЗОВАТЕЛЬ НАЙДЕН

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $newSalt = $randomString;

    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $newPass = $randomString;
    $encodedPass = md5($newPass.$newSalt);

    writeLog('E-MAIL: '. $userLogin, $logFile);
    writeLog('NEW SALT: '. $newSalt, $logFile);
    writeLog('NEW PASS: '. $newPass, $logFile);
    writeLog('ENCODED PASS: '. $encodedPass, $logFile);

    //Сохранить его в БД

    $wpdb->update('wp_paidusers', ['pass' => $encodedPass, 'salt' => $newSalt], ['email' => $userLogin]);

    //Отправить пользователю новый пароль

    $to = $userLogin;
    $subject = 'Восстановление пароля для rostest-certify';

    $headers = "From: restore@rostest-certify.ru\r\n";
    $headers .= "Reply-To: restore@rostest-certify.ru\r\n";
    $headers .= "Date: ".date('D, d M Y H:i:s O')."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $headers .= "Message-ID: restore@rostest-certify.ru\r\n";

    $message = "<html><div>";
    $message .= 'Кто-то (возможно вы) запросил восстановление пароля для сайта <b>rostest-certify.ru</b>.<br /><br />';
    $message .= 'E-mail для входа: '.$userLogin.'<br/>';
    $message .= 'Ваш новый пароль: <b>'.$newPass.'</b>';
    $message .= '<br />';
    $message .= '<br />';
    $message .= 'Теперь вы можете вернуться на сайт и войти в учетную запись используя указанные данные.';
    $message .= '<br />';
    $message .= '<br />';
    $message .= 'Для перехода на сайт нажмите ссылку <a href="//rostest-certify.ru" target="_blank">rostest-certify.ru</a>';
    $message .= '<br />';
    $message .= '<br />';
    $message .= 'Это письмо было сформировано автоматически. Не отвечайте на него!';
    $message .= '<br />';
    $message .= 'Для связи с администрацией сайта воспользуйтесь формой <a href="https://rostest-certify.ru/o-sajte/#feedback" target="_blank">https://rostest-certify.ru/o-sajte/</a>';
    $message .= "</div></html>\r\n";

    $sent = mail($to, $subject, $message, $headers);

    if ($sent) {
        writeLog('Restore Mail Sent', $logFile);;
    } else {
        writeLog('Restore Mail Sending ERROR', $logFile);;
    }

    writeLog('RESTORE SUCCESS', $logFile);;
    fclose($logFile);

    $result = [ 'success' => true ];
    echo json_encode($result);