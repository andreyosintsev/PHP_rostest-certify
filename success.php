<?php
/*Обработка уведомлений о поступивших платежах.
Цель - активация учетной записи и отправка уведомления
об оплате с указанием данных доступа на e-mail пользователя
*/
	require('wp-load.php');
    global $wpdb;

	$notification_type = $_POST['notification_type'];
	$operation_id = $_POST['operation_id'];
	$amount = $_POST['amount'];
	$withdraw_amount = $_POST['withdraw_amount'];
	$currency = $_POST['currency'];
	$datetime = $_POST['datetime'];
	$sender = $_POST['sender'];
	$codepro = $_POST['codepro'];
	$label = $_POST['label'];
	$sha1_hash = $_POST['sha1_hash'];
	$unaccepted = $_POST['unaccepted'];


	date_default_timezone_set('Europe/Samara');

	error_log(' '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	error_log('===========PAYMENT NOTIFY============'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	error_log(date('l jS \of F Y H:i:s')."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

	/*Проверим подлинность платежа*/
	/*
		notification_type&operation_id&amount&currency&datetime&sender&codepro&notification_secret&label
		p2p-incoming&1234567&300.00&643&2011-07-01T09:00:00.000+04:00&41001XXXXXXXX&false&01234567890ABCDEF01234567890&YM.label.12345
	*/

	error_log('====================================='."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	
	$control = $notification_type.'&'.$operation_id.'&'.$amount.'&'.$currency.'&'.$datetime.'&'.$sender.'&'.$codepro.'&IwgJwyYIFbTxqD1Q1kbpY5Dx&'.$label;
	error_log('$control: '.$control."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

	$control = sha1($control, false);
	error_log('$sha1_hash: '.$sha1_hash."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	error_log('$control-sha1: '.$control."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

	if ($control==$sha1_hash) {

		error_log('SIGNATURE OK. PROCEED'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
		error_log('====================================='."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

		/*Сначала проверим, не повторное ли это уведомление, для чего получим значение paid учетной записи*/

		$rec = $wpdb->get_row($wpdb->prepare("SELECT paid FROM wp_paidusers WHERE ID = '$label'", $label));

		if ($rec->paid!=1) {

			error_log('$notification_type: '.$notification_type."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$operation_id: '.$operation_id."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$amount: '.$amount."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$withdraw_amount: '.$withdraw_amount."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$currency: '.$currency."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$datetime '.$datetime."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$sender: '.$sender."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$codepro: '.$codepro."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$label: '.$label."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$sha1_hash: '.$sha1_hash."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('$unaccepted: '.$unaccepted."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

			/*Если ОК, то поменяем для учетной записи с ID==label статус paid=1*/

			error_log('UPDATING PAIDUSERS'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('ID: '.$label."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('price: '.$withdraw_amount."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			
			$res = $wpdb->update('wp_paidusers', array('paid'=>1, 'price'=>$withdraw_amount), array('ID'=>$label));
			if (!$res) error_log('UPDATING FAILED'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			if ($res==0) error_log('UPDATING COMPLETE 0 ROWS'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			if ($res>0) error_log('UPDATING COMPLETE '.$res.' ROWS'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

			/*Обновим прайс-лист, указав, что для этой цены была успешная регистрация*/

			$pricestr=(string) round($withdraw_amount);

			error_log('UPDATING PRICELIST'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			error_log('Price is converted from '.$withdraw_amount.' to '.$pricestr."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			$rec = $wpdb->get_row($wpdb->prepare("SELECT ID, regs FROM wp_pricelist WHERE price = '$pricestr'", $pricestr));
	    	$res = $wpdb->update('wp_pricelist', array('regs'=>$rec->regs+1), array('ID'=>$rec->ID));
	    	if (!$res) error_log('UPDATING FAILED'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			if ($res==0) error_log('UPDATING COMPLETE 0 ROWS'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
			if ($res>0) error_log('UPDATING COMPLETE '.$res.' ROWS'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

			/*Отправим письмо с уведомлением пользователю на почту*/

			$rec = $wpdb->get_row($wpdb->prepare("SELECT email FROM wp_paidusers WHERE ID = '$label'", $label));

			$to = $rec->email;
			error_log('Mail Notification to User: '.$to."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	        

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

	        if ($sent) error_log('Register Mail Sent '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log'); else error_log('Register Mail Sending ERROR '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	        error_log('REGISTER SUCCESS '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

	    } else {
	    	error_log('$label: '.$label."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	    	error_log('REPEATED NOTIFICATION. Exiting... '."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');
	    };
	} else 	error_log('SIGNATURE BAD. WRONG OR FALSE NOTIFICATION. Exiting...'."\r\n",3,'payments/payments-'.date("Y_m_d").'.log');

	/*И отдадим яндексу ответ 200 ОК*/

	//header("HTTP/1.0 200 OK");
	header('Location: /', true, 200);
?>