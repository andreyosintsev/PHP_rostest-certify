<?php 
	require('../wp-load.php');

	global $wpdb;

    $normId     = $_GET["id"];
    $site_url   = site_url();

    date_default_timezone_set('Europe/Samara');

    $logFileName = '../logs/downloadlogs/download-norm-'.date("Y_m_d_H-i-s").'.txt';
    $logFile = fopen($logFileName, "w");

    writeLog('DOWNLOAD NORM ATTEMPT', $logFile);
    writeLog('', $logFile);

    if (!isset ($normId)) {
        writeLog('DOWNLOAD ERROR: NO ID SUPPLIED, exiting...', $logFile);
        fclose($logFile);
        exit;
    }

    writeLog('DOWNLOAD NORM ID: '. $normId, $logFile);

	//Получим имя файла для норматива $norm_name
	$filename = $wpdb->get_var($wpdb->prepare("SELECT file FROM wp_norms WHERE ID = %d", $normId));

    if (!$filename) {
        writeLog('ERROR: FILE NOT FOUND FOR ID: '. $normId, $logFile);
        echo('<b>Ошибка: файл нормативов для скачивания не найден</b>');
        fclose($logFile);
        exit;
    }

    writeLog('DOWNLOAD FILENAME: '. $filename, $logFile);
    updateNormCount($normId);

	$link = $site_url .'/norms/'. $filename;
    writeLog('DOWNLOAD LINK: '. $link, $logFile);
    writeLog('', $logFile);
    writeLog('DOWNLOAD SUCCESS', $logFile);

    fclose($logFile);
    header("Location: ". $link);