<?php
	error_log ('QUERY DELETE ROUTINE');
	require('wp-load.php');

	global $wpdb;

	if (empty($_GET['q'])) {
		error_log ('CANNOT DELETE QUERY: EMPTY');
		header("Location: /task/");
	}

	$search_query = $_GET['q'];
	if (!($wpdb->delete('wp_search', array('search_query'=>$search_query)) == false)) {
		error_log ('QUERY DELETED MANUALLY: '.$search_query);
	} 
	else {
		error_log ('CANNOT DELETE QUERY: '.$search_query);
	}

	header("Location: /task/");
?>