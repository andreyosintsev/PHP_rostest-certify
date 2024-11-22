<?php
/*
Template Name: Submit Certify
*/
?>
<?php get_header(); ?>
<div id="content" class="left">
  
	<h1 class="page-title">Добавление нового сертификата соответствия</h1>
	<div class="entry">
		<?php
			error_log('ADDING NEW CERTIFY');
			/*Для начала получим один посты со статусом draft - черновики с изображениями для редактирования*/
			global $post;
			$args = array('post_status'=>'draft', 'numberposts'=>-1);
			$editpost = get_posts($args);
			$anyTasks = false; /*Есть ли хоть одно подходящее задание для редактирования*/

			if ($editpost) {
				foreach ($editpost as $post){ 

					if ($anyTasks) break; /*Уже вывели одно задание всем спасибо*/

					if (!(isEditable($post->ID))) continue; /*пост не подходит, перейдем к следующему*/
					$anyTasks = true;

					setup_postdata($post);

					echo '<p>Заполните форму с сведениями о сертификате и нажмите кнопку <strong>"Отправить"</strong></p>';
					echo '<p>Сведения в форму необходимо вводить так, как они указаны в сертификате на картинке.</p>';
					echo '<p>Для увеличения изображения щелкните по нему.</p>';
			
					/*Запустим условный таймер на редактирование, отпустив на это 15 минут, сохранив начало времени редактирования*/

					update_post_meta($post->ID, "advego_time_start", date("Y-m-d H:i:s"));

					/*Теперь выведем изображение сертификата*/

					?>
						<div class="thumbnails">
							<?php echo thumbnails(); ?>
							<div class="clear"></div>
						</div>

					
					<?php
						/*Теперь выведем форму для заполнения характеристик сертификата*/
					?>

					<form action="/addcert.php" method="post" name="addcertform" id="addform" onsubmit="return check_input()">
						<div class="fields_wrapper">
								<label>№ сертификата</label>
								<input class="fields" id="param1_number" name="param1_number" type="text" placeholder="РОСС RU.ДМ46.Н01443" onfocus="clear_error('param1_number')"/>
								<div class="error" id="bad_param1">Необходимо указать № сертификата</div>
								
								<label>Срок действия</label>
								<input class="fields" id="param2_validity" name="param2_validity" type="text" placeholder="с 13.06.2013 по 12.06.2016" onfocus="clear_error('param2_validity')"/>
								<div class="error" id="bad_param2">Необходимо указать срок действия сертификата</div>

							<div class="fields_width">
								<label>Орган по сертификации (если указан в сертификате)</label>
								<textarea class="fields" id="param3_certification_agency" name="param3_certification_agency" placeholder="рег. № РОСС RU.0001.11ДМ46. ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ 'МЕБЕЛЬТЕСТ-ИВАНОВО+'. ул. 23 Линия, д. 13, г. Иваново, Россия, 153031, тел (4932) 384-070, 385-542, факс (4932) 384-070." onfocus="clear_error('param3_certification_agency')"></textarea>
								<div class="error" id="bad_param3">Необходимо указать сведения об органе сертификации</div>

								<label>Продукция (услуга, работа)</label>
								<textarea class="fields" id="param4_product" name="param4_product" placeholder="ФАНЕРА БЕРЕЗОВАЯ марки ФК. ТУ 13-00255094-50-98. Серийный выпуск." onfocus="clear_error('param4_product')"></textarea>
								<div class="error" id="bad_param4">Необходимо указать наименование продукции (услуги, работы)</div>

								<label>Соответствует требованиям</label>
								<textarea class="fields" id="param5_complies_with" name="param5_complies_with" placeholder="ТУ 13-00255094-50-98" onfocus="clear_error('param5_complies_with')"></textarea>
								<div class="error" id="bad_param5">Необходимо указать каким требованиям удовлетворяет продукция</div>

								<label>Изготовитель</label>
								<textarea class="fields" id="param6_manufacturer" name="param6_manufacturer" placeholder="ЗАО 'Череповецкий фанерно-мебельный комбинат'. Код ОКПО: 00255094. ИНН: 3528006408. Адрес: ул. Проезжая, д. 4, г. Череповец, Вологодская обл., Россия, 162604." onfocus="clear_error('param6_manufacturer')"></textarea>
								<div class="error" id="bad_param6">Необходимо указать сведения о производителе</div>

								<label>Сертификат выдан (если указан в сертификате)</label>
								<textarea class="fields" id="param7_issued" name="param7_issued" placeholder="ЗАО 'Череповецкий фанерно-мебельный комбинат'. Код ОКПО: 00255094. ИНН: 3528006408. Адрес: ул. Проезжая, д. 4, г. Череповец, Вологодская обл., Россия, 162604. Телефон (8202) 29-64-40, факс (8202) 29-25-40." onfocus="clear_error('param7_issued')"></textarea>
								<div class="error" id="bad_param7">Необходимо указать кому выдан сертификат</div>

								<label>На основании</label>
								<textarea class="fields" id="param8_on_the_basis" name="param8_on_the_basis" placeholder="Акт инспекционного контроля № 11 от 14.05.2013 г. Протокол испытаний № 95 от 03.06.2013 г. Протокол санитарно-химических испытаний № 56 от 03.06.2013 г. Испытательная лаборатория мебели ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ 'МЕБЕЛЬТЕСТ-ИВАНОВО+'' рег. № РОСС RU.001.21ДМ40, ул 23 Линия, д. 13, г. Иваново, Россия, 153031. Экспертное заключение № 91 от 01 февраля 2011 г. (Фанера березовая). ФГУЗ 'Центр гигиены и эпидемиологии в Калужской области', ул. Чичерина, д. 1а, г. Калуга, 284010." onfocus="clear_error('param8_on_the_basis')"></textarea>
								<div class="error" id="bad_param8">Необходимо указать на основании чего выдан сертификат</div>

								<label>Дополнительная информация</label>
								<textarea class="fields" id="param9_add_info" name="param9_add_info" placeholder="Схема сертификации: 3." onfocus="clear_error('param9_add_info')"></textarea>
								<div class="error" id="bad_param9">Необходимо указать дополнительную информацию о сертификате или поставить пробел</div>

								<label>Заявитель/декларант (если указан в сертификате)</label>
								<textarea class="fields" id="parama_declarant" name="parama_declarant" placeholder="TPV Electronics (Fujian) Со., Ltd., Адрес: Shangzheng, Yuan Hong Road, Fuqing City, Fujian, China (Китай). Телефон: +86-591-85285555. Факс: +86-591-8528 5447. E-mail: Aaron.yu@tpv-tech.com" onfocus="clear_error('parama_declarant')"></textarea>
								<div class="error" id="bad_parama">Необходимо указать сведения о заявителе/декларанте (только для деклараций)</div>
							</div>
							<label>Ваше имя <strong> в Advego</strong><br />
								Необходимо указать имя в Advego для проверки и оплаты выполненной работы
							</label>
							<input class="fields" id="advego_name" name="advego_name" type="text" placeholder="kintaro_oe" onfocus="clear_error('advego_name')"/>
							<div class="error" id="bad_advego">Необходимо указать имя исполнителя (никнейм, имя профиля) в Advego для получения оплаты</div>
							<input name="id" type="hidden" value="<?php echo $post->ID; ?>" />
						</div>


						<p class="centered">
							<input class="button large" name="submit" type="submit" value="Отправить"/>
						</p>

					</form>

				<?	wp_reset_postdata();} /*foreach*/
				if (!($anyTasks)) {
					echo '<p><strong>Задания для выполнения отсутствуют</strong></p><p>Если вы получили задание на бирже <strong>Advego</strong>, значит новое задание появится в течение 15 минут.</p>';
					error_log('NO TASKS AVAILABLE');
				}
			} else {
				echo '<p><strong>Задания для выполнения отсутствуют</strong></p><p>Если вы получили задание на бирже <strong>Advego</strong>, значит новое задание появится в течение 15 минут.</p>';
				error_log('NO TASKS AVAILABLE');
			}
		?>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>