<?php
	require('../wp-load.php');

	global $post;

	session_start();

	$linkNumber =   $_GET["link"];
	$id =           $_GET["id"];
	$direct =       $_GET["direct"];
	$user =         $_SESSION['login'];

    date_default_timezone_set('Europe/Samara');

    $logFileName = '../logs/downloadlogs/download-'.date("Y_m_d_H-i-s").'.txt';
    $logFile = fopen($logFileName, "w");

    writeLog('DOWNLOAD ATTEMPT: '.get_the_title($id), $logFile);
    writeLog('', $logFile);
    writeLog('link_number: '.$linkNumber, $logFile);
    writeLog('id: '.$id, $logFile);
    writeLog('direct: '.$direct, $logFile);
    writeLog('user: '.$user, $logFile);

	updateDownloadCount($id, $user);

    function sendFile($link) {
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Заголовки для передачи файла
        header('Content-Description: File Transfer');
        header('Content-Type: image/jpeg');
        header('Content-Disposition: attachment; filename=' . basename($link));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($link));

        // Чтение и отправка файла
        readfile($link);
    }
	
	if ($linkNumber == "1" || $linkNumber == "2" ) {
        $link = $linkNumber == "1" ? getCertDownloadLink($id) : getCertDownloadLink2($id);
        $link='../download/'. $link;
        writeLog('DOWNLOAD LINK: ' . $link, $logFile);

        if ($direct == "true") {
            writeLog('DIRECT DOWNLOADING', $logFile);
            sendFile($link);
		} else {
            writeLog('LOCATION DOWNLOADING', $logFile);
			header("Location: ".$link);
		}
        fclose($logFile);
		exit;
	}

    writeLog('LINK NOT SPECIFIED, returning on post page', $logFile);
    fclose($logFile);

    header("Location: ".get_permalink($id));