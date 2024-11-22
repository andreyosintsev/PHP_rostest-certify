<?php require('wp-load.php');?>
<?php get_header(); ?>

<div id="content" class="left">

	<h1 class="page-title">Результаты добавления сертификата/декларации</h1>
	<div class="entry">

<?php

    $advego_name=$_POST["advego_name"];

	if (empty($advego_name)) {
		echo '<h2>Не задано имя/ник на фриланс-бирже</h2>';
		echo '<p>Вероятнее всего, вы забыли указать ваше имя/ник. Задание не принято.</p>';
		echo '</div></div>';
		get_sidebar();
		get_footer();

		error_log('Ошибка при добавлении поста. Не указано имя пользователя Advego');
		exit;
	}	

	$advego_product=				$_POST["advego_product"];
	$param1_number=					$_POST["param1_number"];
	$param2_validity=				$_POST["param2_validity"];
	$param3_certification_agency=	$_POST["param3_certification_agency"];
	$param4_product=				$_POST["param4_product"];
	$param5_complies_with=			$_POST["param5_complies_with"];
	$param6_manufacturer=			$_POST["param6_manufacturer"];
	$param7_issued=					$_POST["param7_issued"];
	$param8_on_the_basis=			$_POST["param8_on_the_basis"];
	$param9_add_info=				$_POST["param9_add_info"];
	$parama_declarant=				$_POST["parama_declarant"];
	$paramb_okp=					$_POST["paramb_okp"];
	$advego_link=					$_POST["advego_link"];
	$advego_link2=					$_POST["advego_link2"];
	$img_edited=					$_POST["img_edited"];
	$img_edited2=					$_POST["img_edited2"];

	/*Создадим запись для последующей проверки*/

	$new_title = mb_substr(wp_strip_all_tags($param4_product),0, 100);

	$post_data = array(
  		'post_title'    => mb_strtolower(mb_substr($new_title, 0, 1)).mb_substr($new_title,1,mb_strlen($new_title)),
  		'post_type'		=> 'post',
  		'post_content'  => '',
  		'post_status'   => 'pending',
  		'post_author'   => 1,
  		'post_category' => array(1)
	);

	/* Вставляем запись в базу данных*/
	$id = wp_insert_post($post_data) ;

	update_post_meta($id, "advego_product", $advego_product);

	update_post_meta($id, "advego_link", $advego_link);
	if (!(empty($advego_link2))) update_post_meta($id, "advego_link2", $advego_link2);
	update_post_meta($id, "param1_number", normalizeNumber($param1_number));
	update_post_meta($id, "param2_validity", mb_strtolower($param2_validity));

	if (!(empty($param3_certification_agency)))	update_post_meta($id, "param3_certification_agency", normalizeString($param3_certification_agency, true));

	update_post_meta($id, "param4_product", normalizeString($param4_product));
	update_post_meta($id, "param5_complies_with", normalizeString($param5_complies_with));
	update_post_meta($id, "param6_manufacturer", normalizeString($param6_manufacturer));

	if (!(empty($param7_issued)))	update_post_meta($id, "param7_issued", normalizeString($param7_issued));

	update_post_meta($id, "param8_on_the_basis", normalizeString($param8_on_the_basis, true));
	update_post_meta($id, "param9_add_info", normalizeString($param9_add_info));

	if (!(empty($parama_declarant))) update_post_meta($id, "parama_declarant", normalizeString($parama_declarant));
	update_post_meta($id, "paramb_okp", $paramb_okp);	

	update_post_meta($id, "advego_name", $advego_name);

/*
	$hkey1=rand(0, 9);
	$hkey2=rand(0, 9);
	$hkey3=rand(0, 9);
	$hkey4=rand(0, 9);
	$hkey5=rand(0, 9);

	$hkey=$hkey1.$hkey2.$hkey3.$hkey4.$hkey5;
	
	update_post_meta($id, "advego_hkey", $hkey);

	$advego_length = mb_strlen($param1_number.$param2_validity.$param3_certification_agency.$param4_product.$param5_complies_with.$param6_manufacturer.$param7_issued.$param8_on_the_basis.$param9_add_info.$parama_declarant.$paramb_okp);

	$advego_img = 0;
	*/

	if (!(empty($img_edited))) {
		update_post_meta($id, "img_edited", site_url().'/upload/'.$img_edited);
		update_post_meta($id, "img_download_link", mb_substr($img_edited, 4));

		//Добавка за картинку
	/*	$advego_img = 0.2;*/
	}

	if (!(empty($img_edited2))) {
		update_post_meta($id, "img_edited2", site_url().'/upload/'.$img_edited2);
		update_post_meta($id, "img_download_link2", mb_substr($img_edited2, 4));
	}
/*
	//Коэффициент за необходимость
	$advego_needful = 1;
	
	//Частота запросов
	$freq = $wpdb->get_var($wpdb->prepare("SELECT search_freq FROM wp_search WHERE search_query LIKE '$advego_product'", $advego_product));
	if (($freq>=10) && ($freq<20)) $advego_needful = 2;
	if ($freq>=20) $advego_needful = 3;

	$advego_cost = round(($advego_length/1000*0.2+$advego_img)*$advego_needful, 2);

	update_post_meta($id, "advego_length", $advego_length);
	update_post_meta($id, "advego_cost", $advego_cost);
*/

	echo '<h2>Сертификат/декларация успешно добавлены</h2>';
	echo '<p>Нажав на ссылку ниже, вы можете продолжить добавление сертификатов.</p>';
	echo '&nbsp;';
/*	echo '<p>Длина текста задания: <strong>'.$advego_length.'</strong> знаков с пробелами</p>';
	echo '<p>Доплата за отредактированное изображение: <strong>'.$advego_img.' у.е.</strong></p>';
	echo '<p>Коэффициент за нужность сертификата: <strong>x'.$advego_needful.'</strong></p>';
	echo '<p>Оплата текста задания при цене 0.2 у.е. за 1000 знаков: <strong>('.$advego_length.'/1000 х 0.2 + '.$advego_img.') x '.$advego_needful.' = '.$advego_cost.' у.е.</strong></p>';
	echo '&nbsp;';

	echo '<h3>Скопируйте следующий текст в качестве результата задания Advego:</h3>';
	echo '<hr style="display: block">';
	echo '<ul><li>Продукция: <strong>'.$advego_product.'</strong></li>';
	echo '<li>Контрольное число: <strong>'.$hkey.'</strong></li>';
	echo '<li>Ссылка на исходный сертификат: <strong>'.$advego_link.'</strong></li>';
	if (!(empty($advego_link2))) echo '<li>Ссылка на исходное приложение: <strong>'.$advego_link2.'</strong></li>';
	echo '</ul>';
*/

	echo '<hr style="display: block; margin-top: 10px;">';
	echo '<p><strong><a href="/addnew/">Добавить еще один сертификат/декларацию</a></strong></p>';

	error_log('SUBMITTED CERTIFY BY: '.$advego_name.' HKEY: '.$hkey.' PRODUCT:'.$param4_product);
?>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>