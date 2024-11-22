<?php require('wp-load.php');?>
<?php get_header(); ?>

<div id="content" class="left">

	<h1 class="page-title">Результаты выполнения задания</h1>
	<div class="entry">

<?php
	
	$id=$_POST["id"];
	if (empty($id)) {
		echo '<h2>Произошла ошибка</h2>';
		echo '<p>Что-то пошло не так. Создатели ресурса недостаточно тщательно написали код, или Интернет глючит, или что-то еще, но задание не принято системой.</p>';
		echo '</div></div>';
		get_sidebar();
		get_footer();

		error_log('Ошибка при добавлении поста. ID не указан');
		exit;
	}

	$editpost = get_post($id);

	$advego_name=$_POST["advego_name"];
	if (empty($advego_name)) {
		echo '<h2>Не задано имя пользователя Advego</h2>';
		echo '<p>Вероятнее всего, вы забыли указать имя пользователя в Advego. Соответственно, задание не принято.</p>';
		echo '</div></div>';
		get_sidebar();
		get_footer();

		/*Сменим статус на черновик*/

		$editpost->post_status = draft;
		wp_update_post($editpost);
		
		error_log('Ошибка при добавлении поста. Не указано имя пользователя Advego');
		exit;
	}	

	/*Сменим статус, чтобы было понятно, что работу можно проверять*/

	$editpost->post_status = pending;
	wp_update_post($editpost);


	$param1_number=$_POST["param1_number"];
	$param2_validity=$_POST["param2_validity"];
	$param3_certification_agency=$_POST["param3_certification_agency"];
	$param4_product=$_POST["param4_product"];
	$param5_complies_with=$_POST["param5_complies_with"];
	$param6_manufacturer=$_POST["param6_manufacturer"];
	$param7_issued=$_POST["param7_issued"];
	$param8_on_the_basis=$_POST["param8_on_the_basis"];
	$param9_add_info=$_POST["param9_add_info"];
	$parama_declarant=$_POST["parama_declarant"];

	update_post_meta($id, "param1_number", $param1_number);
	update_post_meta($id, "param2_validity", $param2_validity);

	if (!(empty($param3_certification_agency)))	update_post_meta($id, "param3_certification_agency", $param3_certification_agency);

	update_post_meta($id, "param4_product", $param4_product);
	update_post_meta($id, "param5_complies_with", $param5_complies_with);
	update_post_meta($id, "param6_manufacturer", $param6_manufacturer);

	if (!(empty($param7_issued)))	update_post_meta($id, "param7_issued", $param7_issued);

	update_post_meta($id, "param8_on_the_basis", $param8_on_the_basis);
	update_post_meta($id, "param9_add_info", $param9_add_info);

	if (!(empty($parama_declarant))) update_post_meta($id, "parama_declarant", $parama_declarant);

	update_post_meta($id, "advego_name", $advego_name);

	$hkey1=rand(0, 9);
	$hkey2=rand(0, 9);
	$hkey3=rand(0, 9);
	$hkey4=rand(0, 9);
	$hkey5=rand(0, 9);

	$hkey=$hkey1.$hkey2.$hkey3.$hkey4.$hkey5;
	
	update_post_meta($id, "advego_hkey", $hkey);

	$advego_length = mb_strlen($param1_number.$param2_validity.$param3_certification_agency.$param4_product.$param5_complies_with.$param6_manufacturer.$param7_issued.$param8_on_the_basis.$param9_add_info.$parama_declarant);
	$advego_cost = round($advego_length/1000*0.15, 2);

	update_post_meta($id, "advego_length", $advego_length);
	update_post_meta($id, "advego_cost", $advego_cost);

	echo '<h2>Задание успешно выполнено</h2>';
	echo '<p>В скором времени модератор проверит выполненное задание, и одобрит оплату в <strong>Advego</strong>.</p>';
	echo '<p>Вам необходимо в качестве выполненного задания вставить в Advego следующее число:</p>';
	echo '<p style="text-align: center; font-size: 20px; font-weight: bold">'.$hkey.'</p>';
	echo '<p>Общая длина выполненного задания: '.$advego_length.' символов с пробелами</p>';
	echo '<p>Оплата работы по 0.15 за 1000 знаков: '.$advego_cost.' у. е.</p>';


	echo '<hr>';
	echo '<p><strong><a href="/certify_add/">Продолжить выполнение заданий</a></strong></p>';

	error_log('SUBMITTED CERTIFY BY: '.$advego_name.' HKEY: '.$hkey.' PRODUCT:'.$param4_product);
?>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>