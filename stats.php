<?php require('wp-load.php');?>

<?php get_header(); ?>

	<div id="content" class="left">
		<div class="post">
			<h1 class="entry-title">Сегодня скачали сертификаты</h1>
			<div class="entry entry-content">
				<ul>

<?php
	
	$today = date("Y-m-d");
    $post_time_array = array();
	$count = 0;
	
	$res_posts=$wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='download_lastdate' AND meta_value='$today'", "OBJECT_K");
	foreach($res_posts as $res_post) {
	
		$r_post = $res_post->post_id;
				
		$res_post_times=$wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id='$r_post' AND meta_key='download_lasttime'");
		
		foreach ($res_post_times as $res_post_time) {
			$post_time_array[$res_post_time->meta_value]=$r_post;
		}
	}	

        ksort ($post_time_array);

    	foreach($post_time_array as $p_time=>$p_id) {
    		$post_titles=$wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE ID='$p_id'");
    		
    		foreach ($post_titles as $post_title) {
    						
    			//$post_time_array[$post_time]=$post_title->post_title;
    			//echo $post_title->post_title;
    			echo '<li> '.$p_time.' SMR (+4): <a href="'.get_permalink($p_id).'">'.$post_title->post_title.'</a></li>';
    			
    			$count=$count+1;
    		
    		}
    	}
			
?>
				</ul>
				
				<p>Всего <strong><?php echo $count; ?></strong> наименований загружено</p>
			</div>
		</div>
	</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>