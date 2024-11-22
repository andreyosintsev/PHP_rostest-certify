<?php require('wp-load.php');?>

<?php get_header(); ?>
	<?php
	date_default_timezone_set('Europe/Samara');

	if (isset($_GET['activate'])) {
		$id_activated = $_GET['activate'];
		$today=date("Y-m-d");
		
		//Проверим, а вообще зарегистрировался ли такой ID сегодня
		$today_users=$wpdb->get_results("SELECT ID, paid FROM wp_paidusers WHERE regtime > '$today'", "OBJECT_K");

		foreach ($today_users as $user) {
			if (($id_activated == $user->ID) AND ($user->paid==0)) {

				//Активируем запись
				$res = $wpdb->update('wp_paidusers', array('paid'=>1), array('ID'=>$id_activated));

				/*Отправим письмо с уведомлением пользователю на почту*/

				$res = $wpdb->get_row($wpdb->prepare("SELECT email FROM wp_paidusers WHERE ID = '$user->ID'", $user->ID));

				$to = $res->email;
			
		        $subject = 'Регистрация на сайте rostest-certify';
		    
	            $headers  = "From: support@rostest-certify.ru\r\n";
	            $headers .= "Reply-To: support@rostest-certify.ru\r\n";
	            $headers .= "Date: ".date('D, d M Y H:i:s O')."\r\n";
	            $headers .= "MIME-Version: 1.0\r\n";
	            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
	            $headers .= "Message-ID: ".$user->ID."@rostest-certify.ru\r\n";
		    
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
	        	$message .= 'Для связи с администрацией сайта воспользуйтесь формой http://rostest-certify.ru/o-sajte/';
	        	$message .= '<br />';
	        	$message .= '<br />';
	        	$message .= 'С уважением, администрация сайта rostest-certify.ru';
		        $message .= "</div></html>\r\n";
		        $sent = mail($to, $subject, $message, $headers);
			break;
			}
		}
	}


	?>

	<div id="content" class="left">
		<div class="post">
			<h1 class="entry-title">Активность за <?php echo date("d.m.Y")?></h1>
			<div class="entry entry-content">
    			<div class="panel">
                    <div class="panel_header">
        				<div class="email">E-mail</div>
        				<div class="time">Время</div>
                        <div class="downloads">D</div>
        				<div class="activate"></div>
    				    <div class="clear"></div>
                    </div>
    				<?php 
                        $today=date("Y-m-d");
    					$today_users=$wpdb->get_results("SELECT ID, email, regtime, lasttime, paid, downloads FROM wp_paidusers WHERE lasttime > '$today' ORDER BY lasttime ASC", "OBJECT_K");

    					foreach ($today_users as $user) {
							echo '<div class="email">'.$user->email;
								$user_download=$wpdb->get_results("SELECT post_id FROM wp_userhistory WHERE user = '$user->email' AND downloaded = 1 AND lasttime > '$today'", "OBJECT_K");
							echo '<ul>';
                            	foreach ($user_download as $post) {
                            	   	echo '<li class="post_name"><a href="'.get_permalink($post->post_id).'">'.mb_substr(get_the_title($post->post_id), 0, 100).'</a></li>';
                            	}
                            echo '</ul>';
                            echo '</div>';
    						echo '<div class="time">'.mb_substr($user->lasttime, mb_strlen($user->lasttime)-8, 5).'</div>';
                            echo '<div class="downloads">'.$user->downloads.'</div>';
                            if (strtotime($user->regtime)<strtotime($today)) echo '<div class="activated">R</div>'; else {
    							if ($user->paid==0) echo '<div class="activate_btn"><a href="panel.php?activate='.$user->ID.'">А</a></div>'; else echo '<div class="activate">А</div>';
    						}    						
    					}
    				?>
    			</div>
            </div>
		</div>
	</div>
<?php get_footer(); ?>