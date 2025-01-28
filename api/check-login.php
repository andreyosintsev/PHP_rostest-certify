<?php
    require('../wp-load.php');

    date_default_timezone_set('Europe/Samara');

    $logFileName = '../logs/accesslogs/login-'.date("Y_m_d_H-i-s").'.txt';
    $logFile = fopen($logFileName, "w");

    writeLog('==========CHECK LOGIN=========', $logFile);

    $id = isset($_GET['p_id']) ? $_GET['p_id'] : null;

    if (!$id) {
        writeLog('ERROR! POST ID NOT SENT', $logFile);
        http_response_code(404);
        echo json_encode(['error' => 'checkAjaxLogin - error']);
        fclose($logFile);
        exit();
    }

    writeLog('POST ID: ' . $id, $logFile);
    writeLog('POST: ' . esc_html(get_the_title($id)), $logFile);

    session_start();
    $authResult = isset($_SESSION['auth']) ? $_SESSION['auth'] : false;

    writeLog('AUTHRESULT: ' . ($authResult ? 'true' : 'false'), $logFile);

    $valid = isActualDates(getCertValidity($id));
    $validStr = ($valid) ? 'active' : 'outdated';

    writeLog('IS VALID: ' . ($valid) ? 'active' : 'outdated', $logFile);

    if ($authResult) {
        $result = [ 'auth' => true ];
        writeLog('DOWNLOADING: ' . $id, $logFile);
    } else {
        $result = [ 'auth' => false ];
        writeLog('FORBIDDEN: '.$id, $logFile);
    }

    writeLog('ENCODED JSON: '.json_encode($result), $logFile);

    fclose($logFile);
    echo json_encode($result);