<?php
/*
Template Name: Submit Certify Image
*/
?>
<?php get_header(); ?>
<div id="content" class="left">
  
	<h1 class="page-title">Редактировать изображение сертификата соответствия</h1>
	<div class="entry">
		<?php
			error_log('EDITING CERTIFY IMAGE');
			/*Для начала получим посты со статусом pending - находящиеся на проверке*/
			global $post;
			$args = array('post_status'=>'pending', 'numberposts'=>-1);
			$editpost = get_posts($args);
			$anyTasks = false; /*Есть ли хоть одно подходящее задание для редактирования*/

			if ($editpost) {
				foreach ($editpost as $post){ 

					if ($anyTasks) break; /*Уже вывели одно задание всем спасибо*/
					if (get_post_meta($post->ID, 'img_edited', true)!='') continue; /*пост уже обработан, перейдем к следующему*/

					$anyTasks = true;

					setup_postdata($post);

					echo '<p>Необходимо отредактировать приложенное изображение сертификата.</strong></p>';
					echo '<p>Полученное изображение загрузить обратно на сервер.</p>';
					echo '<hr style="display:block"/ >';
								
					/*Запустим условный таймер на редактирование, отпустив на это 15 минут, сохранив начало времени редактирования*/

					update_post_meta($post->ID, "advego_time_start", date("Y-m-d H:i:s"));

					/*Теперь выведем ссылку на изображение сертификата*/
					?>

					<h2>Изображение для редактирования</h2>
					<p>1. Сохраните изображение</p>

						<div class="img_link" style="margin-bottom: 30px;">
							<?php $link = get_post_meta($post->ID, 'advego_link', true);?>
							<a href="<?php echo $link ?>"><?php echo $link ?></a>
							<div class="clear"></div>
						</div>

					<img src="/imgs/1_saveimage.png" title="Cохранить изображение" alt="Cохранить изображение">

					<hr style="display:block"/ >

					<?php
						/*Теперь выведем кнопку для загрузки отредактированного файла*/
					?>
					<h2>Загрузить изображение</h2>

					<div class="fields_wrapper">
						<form method="post" enctype="multipart/form-data">
						<input type="file" name="file">
						<input type="submit" value="Загрузить"></form>
						</form>
						<?php
    					
    					// если была произведена отправка формы
    					if(isset($_FILES['file'])) {
					    // проверяем, можно ли загружать изображение
	      					$check = can_upload($_FILES['file']);
							if($check === true){
	        					// загружаем изображение на сервер
	        					$filename = make_upload($_FILES['file']);
	        					echo "<strong>Файл <b>".$filename."</b> успешно загружен!</strong>";
	        					//Присваиваем полю img_edited ссылку на загруженный файл
	        					update_post_meta($post->ID, "img_edited", $filename);

	      					}
	      					else{
	        					// выводим сообщение об ошибке
	        					echo "<strong>$check</strong>";  
	      					}
    					} ?>
					</div>

				<?	wp_reset_postdata();} /*foreach*/
				if (!($anyTasks)) {
					echo '<p><strong>Задания foreach для выполнения отсутствуют</strong></p>';
					error_log('NO IMAGES TO EDIT AVAILABLE');
				}
			} else {
					echo '<p><strong>Задания editpost для выполнения отсутствуют</strong></p>';
					error_log('NO IMAGES TO EDIT AVAILABLE');
			}
		?>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>