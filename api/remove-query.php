<?php
    require('../wp-load.php');

    date_default_timezone_set('Europe/Samara');

    $logFileName = '../logs/searchlogs/remove-'.date("Y_m_d_H-i-s").'.txt';
    $logFile = fopen($logFileName, "w");

	writeLog('SEARCH QUERY DELETE', $logFile);
    writeLog('', $logFile);

    $searchQuery = $_GET['q'];
    writeLog('QUERY TO DELETE: '. $searchQuery, $logFile);

	if (!isset($searchQuery)) {
        writeLog('CANNOT DELETE QUERY: NO QUERY SUPPLIED...',  $logFile);

        fclose($logFile);
		header("Location: ../task/");
	}

    global $wpdb;

    $res = $wpdb->delete('wp_search', ['search_query' => $searchQuery]);

	if ($res) {
        writeLog ('SUCCESSFULLY DELETED QUERY: '. $searchQuery, $logFile);
	} 
	else {
		writeLog('ERROR: CANNOT DELETE QUERY: '.$searchQuery,  $logFile);
	}

    fclose($logFile);
    header("Location: ../task/");