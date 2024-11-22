<?php 
	require('wp-load.php');

	global $wpdb;
	$norm_id=$_GET["id"];

	update_norm_count($norm_id);
	error_log('DOWNLOAD NORM      : '.$norm_id);

	//Получим имя файла для норматива $norm_name
	$filename = $wpdb->get_var($wpdb->prepare("SELECT file FROM wp_norms WHERE ID=$norm_id", $norm_id));
	
	$link='norms/'.$filename;
    header("Location: ".$link);
?>