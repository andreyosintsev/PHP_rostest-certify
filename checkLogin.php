<?php
    require('wp-load.php');

    date_default_timezone_set('Europe/Samara');
    
    $log_file_name = 'accesslogs/login-'.date("Y_m_d_H-i-s").'.txt';
    $log_file = fopen($log_file_name, "w");
    
    fwrite($log_file, 'LOGIN at '.date("Y_m_d_H-i-s")."\r\n\r\n");

    if (isset($_GET['p_id'])) $id = $_GET['p_id']; else $id="not sent";    
    
    fwrite($log_file, 'POST ID: '.$id."\r\n");
    fwrite($log_file, 'POST: '.esc_html(get_the_title($id))."\r\n");

    session_start();
    error_log('AUTHRESULT: '.$_SESSION['auth']);

    $metadata = get_post_custom($id);
    $validity = $metadata['param2_validity'][0];
    $valid = isActualDates($validity, true);

    $valid_str = ($valid) ? 'active' : 'outdated';

    fwrite($log_file, 'VALIDITY: '.$validity."\r\n");
    fwrite($log_file, 'IS VALID: '.$valid_str."\r\n");

    if ($_SESSION['auth']/* || !$valid*/) {
        $result = array(
            'auth' => true,
        );
        fwrite($log_file, 'DOWNLOADING: '.$id."\r\n");
        fclose($log_file);

    } else {
    /*Иначе перейти ко входу на сайт*/
        $result = array(
            'auth' => false,
        );
        fwrite($log_file, 'FORBIDDEN: '.$id."\r\n");
        fclose($log_file);
    }

    echo json_encode($result);
    return;
?>