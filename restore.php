<?php
    require('wp-load.php');
    global $wpdb;

    date_default_timezone_set('Europe/Samara');

    error_log(' '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log('========PASSWORD RESTORE=========='."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
    error_log(date('l jS \of F Y H:i:s')."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

    error_log ('Username: '.$_POST["restore"]."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	
    if (isset($_POST["restore"])) {
        $user_login = $_POST["restore"];

        //Ищем пользователя с таким логином и ВЫПОЛНИВШЕГО ОПЛАТУ PAID=1
        $rec = $wpdb->get_row($wpdb->prepare("SELECT email FROM wp_paidusers WHERE email = '$user_login' AND paid=1", $user_login));
        if (!isset($rec)) {
        //ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН
            error_log ('USER: '.$_POST["restore"].' not found...'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
            $result = array(
                'success' => false,
            );
          
            // Переводим массив в JSON
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
        $new_salt = $randomString;

        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $new_pass = $randomString;
        $encoded_pass = md5($new_pass.$new_salt);

        error_log('E-MAIL: '.$user_login."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
        error_log('NEW SALT: '.$new_salt."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
        error_log('NEW PASS: '.$new_pass."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
        error_log('ENCODED PASS: '.$encoded_pass."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

        //Сохранить его в БД

        $wpdb->update('wp_paidusers', array('pass'=>$encoded_pass, 'salt'=>$new_salt), array('email'=>$user_login));

        //Отправить пользователю новый пароль

        $to = $user_login;
        $subject = 'Восстановление пароля для rostest-certify';
    
        $headers = "From: restore@rostest-certify.ru\r\n";
        $headers .= "Reply-To: restore@rostest-certify.ru\r\n";
        $headers .= "Date: ".date('D, d M Y H:i:s O')."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $headers .= "Message-ID: ".$label."@rostest-certify.ru\r\n";
    
        $message = "<html><div>";
        $message .= 'Кто-то (возможно вы) запросил восстановление пароля для сайта <b>rostest-certify.ru</b>.<br /><br />';
        $message .= 'E-mail для входа: '.$user_login.'<br/>';
        $message .= 'Ваш новый пароль: <b>'.$new_pass.'</b>';
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
        $message .= 'Для связи с администрацией сайта воспользуйтесь формой <a href="//rostest-certify.ru/o-sajte/#feedback" target="_blank">http://rostest-certify.ru/o-sajte/</a>';
        $message .= "</div></html>\r\n";
        
        $sent = mail($to, $subject, $message, $headers);

        if ($sent) error_log('Restore Mail Sent '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log'); else error_log('Restore Mail Sending ERROR '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
        error_log('RESTORE SUCCESS '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

        $result = array(
                'success' => true,
        );

        // Переводим массив в JSON
        echo json_encode($result);
        return;
    }
?>