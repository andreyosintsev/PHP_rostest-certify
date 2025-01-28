<?php
/*Обработка уведомлений о поступивших платежах.
Цель - активация учетной записи и отправка уведомления
об оплате с указанием данных доступа на e-mail пользователя
*/
	require('../wp-load.php');
    global $wpdb;

	$notificationType = $_POST['notification_type'];
	$operationId = $_POST['operation_id'];
	$amount = $_POST['amount'];
	$withdrawAmount = $_POST['withdraw_amount'];
	$currency = $_POST['currency'];
	$datetime = $_POST['datetime'];
	$sender = $_POST['sender'];
	$codepro = $_POST['codepro'];
	$label = $_POST['label'];
	$sha1Hash = $_POST['sha1_hash'];
	$unaccepted = $_POST['unaccepted'];

    date_default_timezone_set('Europe/Samara');
    $logFileName = '../payments/payments-'.date("Y_m_d").'.txt';
    $logFile = fopen($logFileName, "a");

    writeLog('===========PAYMENT NOTIFY============', $logFile);
    writeLog(date('l jS \of F Y H:i:s'), $logFile);

	/*Проверим подлинность платежа*/
	/*
		notification_type&operation_id&amount&currency&datetime&sender&codepro&notification_secret&label
		p2p-incoming&1234567&300.00&643&2011-07-01T09:00:00.000+04:00&41001XXXXXXXX&false&01234567890ABCDEF01234567890&YM.label.12345
	*/

    writeLog('===========PAYMENT NOTIFY============', $logFile);

	$control = $notificationType.'&'.$operationId.'&'.$amount.'&'.$currency.'&'.$datetime.'&'.$sender.'&'.$codepro.'&IwgJwyYIFbTxqD1Q1kbpY5Dx&'.$label;
	writeLog('$control: ', $logFile);

	$control = sha1($control, false);
    writeLog('$sha1_hash: '.$sha1Hash, $logFile);
    writeLog('$control-sha1: '.$control, $logFile);

	if ($control == $sha1Hash) {

        writeLog('SIGNATURE OK. PROCEED', $logFile);
        writeLog('=====================================', $logFile);

		/*Сначала проверим, не повторное ли это уведомление, для чего получим значение paid учетной записи*/

		$rec = $wpdb->get_row($wpdb->prepare("SELECT paid FROM wp_paidusers WHERE ID = %s", $label));

		if ($rec->paid != 1) {
            writeLog('$notification_type: '. $notificationType, $logFile);
            writeLog('$operation_id: '. $operationId, $logFile);
            writeLog('$amount: '. $amount, $logFile);
            writeLog('$withdraw_amount: '. $withdrawAmount, $logFile);
            writeLog('$currency: '. $currency, $logFile);
            writeLog('$datetime: '. $datetime, $logFile);
            writeLog('$sender: '. $sender, $logFile);
            writeLog('$codepro: '. $codepro, $logFile);
            writeLog('$label: '. $label, $logFile);
            writeLog('$sha1_hash: '. $sha1Hash, $logFile);
            writeLog('$sunaccepted: '. $unaccepted, $logFile);

			/*Если ОК, то поменяем для учетной записи с ID==label статус paid=1*/

            writeLog('UPDATING WP_PAIDUSERS', $logFile);
            writeLog('ID: ', $label);
            writeLog('price: ', $withdrawAmount);


			$res = $wpdb->update('wp_paidusers', ['paid' => 1, 'price' => $withdrawAmount], ['ID' => $label]);

            if(!$res) writeLog('UPDATING FAILED', $logFile);
            if($res == 0) writeLog('UPDATING COMPLETE 0 ROWS', $logFile);
            if($res > 0) writeLog('UPDATING COMPLETE '.$res.' ROWS', $logFile);

			/*Обновим прайс-лист, указав, что для этой цены была успешная регистрация*/

			$priceStr=(string) round($withdrawAmount);

            writeLog('UPDATING PRICELIST', $logFile);

            writeLog('Price is converted from '.$withdrawAmount.' to '.$priceStr, $logFile);

            $rec = $wpdb->get_row($wpdb->prepare("SELECT ID, regs FROM wp_pricelist WHERE price = %s", $priceStr));
	    	$res = $wpdb->update('wp_pricelist', [ 'regs'=>$rec->regs+1], ['ID' => $rec->ID]);

            if(!$res) writeLog('UPDATING FAILED', $logFile);
            if($res == 0) writeLog('UPDATING COMPLETE 0 ROWS', $logFile);
            if($res > 0) writeLog('UPDATING COMPLETE '.$res.' ROWS', $logFile);

			/*Отправим письмо с уведомлением пользователю на почту*/

			$rec = $wpdb->get_row($wpdb->prepare("SELECT email FROM wp_paidusers WHERE ID = %s", $label));

			$to = $rec->email;
            writeLog('Mail Notification to User: '. $to, $logFile);
	        
	        $subject = 'Регистрация на сайте rostest-certify';
	    
            $headers = "From: support@rostest-certify.ru\r\n";
            $headers .= "Reply-To: support@rostest-certify.ru\r\n";
            $headers .= "Date: ".date('D, d M Y H:i:s O')."\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $headers .= "Message-ID: ".$label."@rostest-certify.ru\r\n";
	    
	        $message = "<html><div>";
	        $message .= 'Уважаемый пользователь,<br /><br />';
	        $message .= 'поздравляем вас с регистрацией на сайте <b>rostest-certify.ru</b>.';
	        $message .= '<br />';
	        $message .= 'E-mail для входа: <b>'.$to.'</b><br/>';
	        $message .= '<br />';
	        $message .= '<br />';
	        $message .= 'Теперь вы можете вернуться на сайт и войти в учетную запись используя ваш E-mail и пароль.';
	        $message .= '<br />';
	        $message .= '<br />';
	        $message .= '<br />';
	        $message .= '<br />';
	        $message .= '<br />';
	        $message .= 'Это письмо было сформировано автоматически. Не отвечайте на него!';
        	$message .= '<br />';
        	$message .= 'Для связи с администрацией сайта воспользуйтесь формой <a href="//rostest-certify.ru/o-sajte/#feedback" target="_blank">rostest-certify.ru/o-sajte/</a>';
        	$message .= '<br />';
        	$message .= '<br />';
        	$message .= 'С уважением, администрация сайта rostest-certify.ru';
	        $message .= "</div></html>\r\n";
	        $sent = mail($to, $subject, $message, $headers);

	        if ($sent) {
                writeLog('Register Mail Sent', $logFile);
            } else {
                writelog('Register Mail Sending ERROR', $logFile);
            }
	        writeLog('REGISTER SUCCESS', $logFile);

	    } else {
            writeLog('$label: '. $label, $logFile);
            writeLog('REPEATED NOTIFICATION. Exiting...', $logFile);
	    };
	} else 	writeLog('SIGNATURE IS BAD. WRONG OR FALSE NOTIFICATION. Exiting...', $logFile);

    fclose($logFile);

	/*И отдадим яндексу ответ 200 ОК*/

	header('Location: /', true, 200);