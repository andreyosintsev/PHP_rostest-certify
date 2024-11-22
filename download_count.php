<? 
	require('wp-load.php');

	/*
	update_post_meta($_GET["id"], "download_count", get_post_meta($_GET["id"], "download_count", true)+1);
	update_post_meta($_GET["id"], "download_lastdate", date("Y-m-d"));
	update_post_meta($_GET["id"], "download_lasttime", date("H:i"));
	*/

	global $post;

	session_start();

	$link_number=$_GET["link"];
	$id=$_GET["id"];
	$direct=$_GET["direct"];
	$user=$_SESSION['login'];

	update_download_count($id, $user);
	error_log('DOWNLOAD      : '.get_the_title($id));
	
	
	if ($link_number=="1") {
		if ($direct=="true") {
			$link=get_post_meta($id, "img_thmb", true);
			$link='download/'.mb_substr($link, 5);

			if (ob_get_level()) {
        		ob_end_clean();
    		}
		    // заставляем браузер показать окно сохранения файла
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename=' . basename($link));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($link));
		    // читаем файл и отправляем его пользователю
		    readfile($link);
		    error_log('DOWNLOAD_LINK: '.$link);
		} else {
			$link=get_post_meta($id, "img_download_link", true);
			header("Location: ".$link);
		}		
		exit;
	}
	
	if ($link_number=="2") {
		if ($direct=="true") {
			$link=get_post_meta($id, "img_thmb2", true);
			$link='download/'.mb_substr($link, 5);

			if (ob_get_level()) {
        		ob_end_clean();
    		}
		    // заставляем браузер показать окно сохранения файла
		    header('Content-Description: File Transfer');
		    header('Content-Type: image/jpeg');
		    header('Content-Disposition: attachment; filename=' . basename($link));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($link));
		    // читаем файл и отправляем его пользователю
		    readfile($link);
		} else {
			$link=get_post_meta($id, "img_download_link2", true);
			header("Location: ".$link);
		}
		exit;
	}
		
	header("Location: ".get_permalink($id));
?>