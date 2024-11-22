<?php
/*
Template Name: New Certify
*/
?>
<?php get_header(); ?>
<?php $filename=""; ?>
<?php $filename2=""; ?>
<div id="content" class="left">
  
	<h1 class="page-title">Добавление нового сертификата соответствия</h1>
	<div class="entry">
		<p>Необходимо искать в <a href="https://yandex.ru/images/" title="Яндекс.Картинки">Яндекс.Картинках</a> или в <a href="https://www.google.ru/imghp?hl=ru" title="Google Картинки">Google Картинках</a> изображения сертификатов соответствия Ростест и переписывать содержащуюся в них информацию.</p>

		<h2>Требования к сертификатам</h2>

		<ul>
			<li>Сертификаты нужны на продукцию из <a href="/nuzhnye-sertifikaty/" title="Нужные сертификаты" target="_blank"><strong>перечня необходимых сертификатов</strong> (открыть список)</a>.</li>
			<li>Внешний вид сертификатов должен быть такой, как представлен ниже.

		<table style="margin: 25px 0 0; border: 0 none">
			<tr>
				<td><a href="/about/gostr_o_big.jpg" rel="lightbox"><img src="/about/gostr_o.jpg" alt="Сертификат соответствия ГОСТ Р - обязательная сертификация" title="Сертификат соответствия ГОСТ Р - обязательная сертификация" width="165" height="233"/></a></td>
				<td><a href="/about/gostr_d_big.jpg" rel="lightbox"><img src="/about/gostr_d.jpg" alt="Сертификат соответствия ГОСТ Р - добровольная сертификация" title="Сертификат соответствия ГОСТ Р - добровольная сертификация" width="165" height="232"/></a></td>
				<td><a href="/about/ss_big.jpg" rel="lightbox"><img src="/about/ss.jpg" alt="Сертификат соответствия Таможенного союза" title="Сертификат соответствия Таможенного союза" width="165" height="233"/></a></td>
				<td><a href="/about/ds_big.jpg" rel="lightbox"><img src="/about/ds.jpg" alt="Декларация соответствия Таможенного союза" title="Декларация соответствия Таможенного союза" width="165" height="234"/></a></td>
			</tr>
		</table>
		<p class="undertitle">(нажмите для увеличения)</p></li>

			<li>Размеры изображения сертификата: не менее 1000 пикселей по каждой стороне.</li>
			<li>Изображение должно быть цветным, четким, без водяных знаков.</li>
		</ul>

		<h2>Порядок работы</h2>

		<ol>
		<li>Предварительно с помощью сервиса <strong><a href="/naiti-sertifikat-po-nomeru/" title="Найти сертификат по номеру" target="_blank">Найти сертификат по номеру</a></strong> убедиться, что такого сертификата еще нет в системе.</li>
		<li>Заполните форму сведениями из сертификата и нажмите кнопку <strong>"Отправить"</strong>.</li>
		<li>Сведения в форму необходимо вводить так, как они указаны в сертификате.</li>
		<li>Если у сертификата есть <strong>приложение</strong> - представить ссылку и на него.</li>
		</ol>
		
		<?php
			//Получим 200 строк самых популярных запросов из таблицы запросов
			$arrCerts = $wpdb->get_col("SELECT search_query FROM wp_search ORDER BY search_freq DESC LIMIT 300");

			$i = 0 ;
			foreach ($arrCerts as $word) {
				$search_words = mb_split("[ ,]+", $word, 5);
				$num=0;
				//Поиск по синонимам здесь слишком агрессивный и стирает многое из поисковой базы. Требует доработки. Пока отключен
				//путем переименования $num в $num_syn. Чтобы включить поиск по синонимам, заменить $num_syn на $num
				$num_syn = 0;

				foreach ($search_words as $search_word){
					if (mb_strlen($search_word)>4) $search_word = mb_substr($search_word, 0, mb_strlen($search_word)-1);
					$num=$num + $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$search_word%%'", $search_word));

					//Может быть для этого слова есть синонимы? Поищем по ним.
					//Есть ли синонимы?
					$syn1 = $wpdb->get_row($wpdb->prepare("SELECT word2 FROM wp_synonyms WHERE word1 LIKE '%%$word%%'", $word));
					$syn2 = $wpdb->get_row($wpdb->prepare("SELECT word1 FROM wp_synonyms WHERE word2 LIKE '%%$word%%'", $word));

					if (isset($syn1) && isset($syn2)) $syn = array_merge($syn1, $syn2); 
					else {
						if (isset($syn1) && (!(isset($syn2)))) $syn=$syn1;
						if (isset($syn2) && (!(isset($syn1)))) $syn=$syn2;
					}

					//Поиск по синониму слова запроса
					foreach ($syn as $syn_search){
						$syn_words = mb_split("[ ,]+", $syn_search, 5);

						$num_syn=$num_syn + $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$syn_search%%'", $syn_search));

						foreach ($syn_words as $synonym){
							if (mb_strlen($synonym)<4) continue;

							$synonym = mb_substr($synonym, 0, mb_strlen($synonym)-1);
							
							$num_syn=$num_syn + $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$synonym%%'", $synonym));
						}
					}
				}

				//Если количество похожих статей 2 и более - такой запрос неинтересен, так как есть необходимые статьи для ответа
				
				if ($num>=2) {
					//Удаляем его из таблицы запросов
					$wpdb->delete('wp_search', array('search_query'=>$word));
					//Удаляем его из полученного массива самых популярных строк
					unset($arrCerts[$i]);
				}
				$i++;
			}

			
			asort($arrCerts);
		?>

		<form method="post" enctype="multipart/form-data">
			<div class="fields_wrapper">
				<label>Отредактированное изображение <strong>сертификата</strong> (необязательно, <b>бонус 0.2 у.е. к оплате</b>)</label><br />
				<input type="file" name="file" /><br />
				<label>Отредактированное изображение <strong>приложения к сертификату</strong> (необязательно)</label><br />
				<input type="file" name="file2" /><br />
			</div>
			<p class="centered">
				<input type="submit" value="Загрузить" />
			</p>
		</form>
		
		<?php
		
		// если была произведена отправка формы
		if(isset($_FILES['file'])) {
	    // проверяем, можно ли загружать изображение
			$check = can_upload($_FILES['file']);
			if($check === true){
				// загружаем изображение на сервер
				$filename = make_upload($_FILES['file']);
				echo '<strong>Файл '.$filename.' успешно загружен!</strong>';
				}
				else {
				// выводим сообщение об ошибке
				echo '<div style="color: #d51313;"><b>Сертификат: </b>'.$check.'</div>';
				}
		}

		// если была произведена отправка формы
		if(isset($_FILES['file2'])) {
	    // проверяем, можно ли загружать изображение
			$check = can_upload($_FILES['file2']);
			if($check === true){
				// загружаем изображение на сервер
				$filename2 = make_upload($_FILES['file2']);
				echo '<strong>Файл '.$filename2.' успешно загружен!</strong>';
				}
				else {
				if (!($check=='Ошибка! Вы не выбрали файл'))
				// выводим сообщение об ошибке
				echo '<div style="color: #d51313;"><b>Приложение: </b>'.$check.'</div>';
				}
		} ?>

		<form action="/newcert.php" method="post" name="addcertform" id="addform" onsubmit="return check_newinput()">
			<div class="fields_wrapper">
				<label>Продукция из <b><a href="/nuzhnye-sertifikaty/" title="Перечень необходимых сертификатов" target="_blank">перечня необходимых сертификатов</a></b> на которую предоставлен сертификат</label>
				<select class="fields" id="advego_product" name="advego_product">
				<?php
					foreach ($arrCerts as $i) {
						echo '<option>'.$i;
						echo '</option>';
					}
				?>
				</select>
				<div class="error" id="bad_advego_product">Необходимо указать продукцию из перечня необходимых сертификатов </div>

				<label>Ссылка на <i>исходный</i> файл с изображением <strong>сертификата</strong></label>
				<input class="fields" id="advego_link" name="advego_link" type="text" placeholder="http://site.ru/sertify.jpg" onfocus="clear_newerror('advego_link')"/>
				<div class="error" id="bad_advego_link">Необходимо указать ссылку на файл в Интернете с изображением сертификата </div>

				<label>Ссылка на <i>исходный</i> файл с изображением <strong>приложения</strong> к сертификату</label> (если есть)
				<input class="fields" id="advego_link2" name="advego_link2" type="text" placeholder="http://site.ru/application.jpg" onfocus="clear_newerror('advego_link2')"/>
				<div class="error" id="bad_advego_link2">Необходимо указать ссылку на файл в Интернете с изображением сертификата </div>

				<label>№ сертификата</label>
				<input class="fields" id="param1_number" name="param1_number" type="text" placeholder="РОСС RU.ДМ46.Н01443" onfocus="clear_newerror('param1_number')"/>
				<div class="error" id="bad_param1">Необходимо указать № сертификата</div>
				
				<label>Срок действия</label>
				<input class="fields" id="param2_validity" name="param2_validity" type="text" placeholder="с 13.06.2013 по 12.06.2016" onfocus="clear_newerror('param2_validity')"/>
				<div class="error" id="bad_param2">Необходимо указать срок действия сертификата</div>

				<label>Код ОКП или ТН ВЭД ТС или ОКУН</label>
				<input class="fields" id="paramb_okp" name="paramb_okp" type="text" placeholder="55 1110" onfocus="clear_newerror('paramb_okp')" />
				<div class="error" id="bad_paramb_okp">Необходимо указать или код ОКП, или ТН ВЭД ТС, или ОКУН</div>

				<div class="fields_width">
					<label>Орган по сертификации (если указан в сертификате)</label>
					<textarea class="fields" id="param3_certification_agency" name="param3_certification_agency" placeholder="рег. № РОСС RU.0001.11ДМ46. ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ 'МЕБЕЛЬТЕСТ-ИВАНОВО+'. ул. 23 Линия, д. 13, г. Иваново, Россия, 153031, тел (4932) 384-070, 385-542, факс (4932) 384-070." onfocus="clear_newerror('param3_certification_agency')"></textarea>
					<div class="error" id="bad_param3">Необходимо указать сведения об органе сертификации</div>

					<label>Продукция (услуга, работа)</label>
					<textarea class="fields" id="param4_product" name="param4_product" placeholder="ФАНЕРА БЕРЕЗОВАЯ марки ФК. ТУ 13-00255094-50-98. Серийный выпуск." onfocus="clear_newerror('param4_product')"></textarea>
					<div class="error" id="bad_param4">Необходимо указать наименование продукции (услуги, работы)</div>

					<label>Соответствует требованиям</label>
					<textarea class="fields" id="param5_complies_with" name="param5_complies_with" placeholder="ТУ 13-00255094-50-98" onfocus="clear_newerror('param5_complies_with')"></textarea>
					<div class="error" id="bad_param5">Необходимо указать каким требованиям удовлетворяет продукция</div>

					<label>Изготовитель</label>
					<textarea class="fields" id="param6_manufacturer" name="param6_manufacturer" placeholder="ЗАО 'Череповецкий фанерно-мебельный комбинат'. Код ОКПО: 00255094. ИНН: 3528006408. Адрес: ул. Проезжая, д. 4, г. Череповец, Вологодская обл., Россия, 162604." onfocus="clear_newerror('param6_manufacturer')"></textarea>
					<div class="error" id="bad_param6">Необходимо указать сведения о производителе</div>

					<label>Сертификат выдан (если указан в сертификате)</label>
					<textarea class="fields" id="param7_issued" name="param7_issued" placeholder="ЗАО 'Череповецкий фанерно-мебельный комбинат'. Код ОКПО: 00255094. ИНН: 3528006408. Адрес: ул. Проезжая, д. 4, г. Череповец, Вологодская обл., Россия, 162604. Телефон (8202) 29-64-40, факс (8202) 29-25-40." onfocus="clear_newerror('param7_issued')"></textarea>
					<div class="error" id="bad_param7">Необходимо указать кому выдан сертификат</div>

					<label>На основании</label>
					<textarea class="fields" id="param8_on_the_basis" name="param8_on_the_basis" placeholder="Акт инспекционного контроля № 11 от 14.05.2013 г. Протокол испытаний № 95 от 03.06.2013 г. Протокол санитарно-химических испытаний № 56 от 03.06.2013 г. Испытательная лаборатория мебели ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ 'МЕБЕЛЬТЕСТ-ИВАНОВО+'' рег. № РОСС RU.001.21ДМ40, ул 23 Линия, д. 13, г. Иваново, Россия, 153031. Экспертное заключение № 91 от 01 февраля 2011 г. (Фанера березовая). ФГУЗ 'Центр гигиены и эпидемиологии в Калужской области', ул. Чичерина, д. 1а, г. Калуга, 284010." onfocus="clear_newerror('param8_on_the_basis')"></textarea>
					<div class="error" id="bad_param8">Необходимо указать на основании чего выдан сертификат</div>

					<label>Дополнительная информация</label>
					<textarea class="fields" id="param9_add_info" name="param9_add_info" placeholder="Периодичность инспекционного контроля - 1 раз в год. Схема сертификации: 3." onfocus="clear_newerror('param9_add_info')"></textarea>
					<div class="error" id="bad_param9">Необходимо указать дополнительную информацию о сертификате или поставить пробел</div>

					<label>Заявитель/декларант (если указан в сертификате)</label>
					<textarea class="fields" id="parama_declarant" name="parama_declarant" placeholder="TPV Electronics (Fujian) Со., Ltd., Адрес: Shangzheng, Yuan Hong Road, Fuqing City, Fujian, China (Китай). Телефон: +86-591-85285555. Факс: +86-591-8528 5447. E-mail: Aaron.yu@tpv-tech.com" onfocus="clear_newerror('parama_declarant')"></textarea>
					<div class="error" id="bad_parama">Необходимо указать сведения о заявителе/декларанте (только для деклараций)</div>
				</div>
				
				<label>Ваше имя <strong> в Advego</strong><br />
					Необходимо указать имя в Advego для проверки и оплаты выполненной работы
				</label>
				
				<input class="fields" id="advego_name" name="advego_name" type="text" placeholder="kintaro_oe" onfocus="clear_newerror('advego_name')"/>
				<div class="error" id="bad_advego_name">Необходимо указать имя исполнителя (никнейм, имя профиля) в Advego для получения оплаты</div>

				<input type="hidden" name="img_edited" value="<?php echo $filename; ?>">
				<input type="hidden" name="img_edited2" value="<?php echo $filename2; ?>">
			</div>


			<p class="centered">
				<input class="button large" name="submit" type="submit" value="Отправить"/>
			</p>
		</form>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>