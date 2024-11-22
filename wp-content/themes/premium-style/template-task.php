<?php
/*
Template Name: Task
*/
?>
<?php get_header(); ?>
<?php

	date_default_timezone_set('Europe/Samara');
	$period = 'all';

    if (isset ($_GET["period"])) {
    	if ($_GET["period"] == 'month') $period = 'month';
    }
?>
<div id="content" class="left">
  
	<h1 class="page-title">Необходимые сертификаты</h1>
	<div class="entry">
	  
		<p>Для развития сайта необходимы сертификаты соответствия.</p>

		<h3>Вид необходимых сертификатов</h3>
		<p>Нужны только такие сертификаты. Все остальные не нужны.</p>

		<table style="margin: 25px 0 0; border: 0 none">
			<tr>
				<td><a href="/about/gostr_o_big.jpg" rel="lightbox"><img src="/about/gostr_o.jpg" alt="Сертификат соответствия ГОСТ Р - обязательная сертификация" title="Сертификат соответствия ГОСТ Р - обязательная сертификация" width="165" height="233"/></a></td>
				<td><a href="/about/gostr_d_big.jpg" rel="lightbox"><img src="/about/gostr_d.jpg" alt="Сертификат соответствия ГОСТ Р - добровольная сертификация" title="Сертификат соответствия ГОСТ Р - добровольная сертификация" width="165" height="232"/></a></td>
				<td><a href="/about/ss_big.jpg" rel="lightbox"><img src="/about/ss.jpg" alt="Сертификат соответствия Таможенного союза" title="Сертификат соответствия Таможенного союза" width="165" height="233"/></a></td>
				<td><a href="/about/ds_big.jpg" rel="lightbox"><img src="/about/ds.jpg" alt="Декларация соответствия Таможенного союза" title="Декларация соответствия Таможенного союза" width="165" height="234"/></a></td>
			</tr>
		</table>
		<p class="undertitle">(нажмите для увеличения)</p>

		<h3>Перечень необходимых сертификатов</h3>

		<p><b>Важно!</b> Наименование продукции указано в соответствующем разделе сертификата. Сокращения, аббревиатуры и продукция "по-аналогии", а также обобщенные наименования ("ликер" не тоже самое что "алкоголь") не подходят. Допустимы частичное совпадение наименования продукции перечню ("какао-порошок" это тоже "порошок") и словоформы ("окна" это тоже что и "окно").</p>
		<p><b>Важно!</b> Сроки действия сертификата или декларации НЕважны.</p>
		<p><b>Важно!</b> Если рядом с наименованием продукции стоит отметка <b>x2</b> или <b>x3</b>, значит за данный сертификат вы получите <b>оплату в двухкратном</b> или <b>трехкратном размере</b>.</p>

		<p>Для выполнения поиска в системе Яндекс или Google щелкните по ссылке рядом.</p>

		<p><a href="/task?period=all">За всё время</a> | <a href="/task?period=month">За последний месяц</a></p>
		
		<?php
			$is_logged = false;
			if (is_user_logged_in() && current_user_can('administrator')) $is_logged = true;

			//Получим 300 строк самых популярных запросов из таблицы запросов
			//Частота запроса должна быть больше 1, чтобы отсечь случайные запросы

			//Если запрошены запросы за последний месяц

			//Текущая дата минус один месяц
			$date = strtotime('-1 month');
			$date = date('Y-m-d H:i:s', $date);
			
			if ($period == 'month') $arrCerts = $wpdb->get_col("SELECT search_query FROM wp_search WHERE search_freq>1 AND search_date > '$date' ORDER BY search_freq DESC LIMIT 300");
			else

			//Иначе запросы за всё время

			$arrCerts = $wpdb->get_col("SELECT search_query FROM wp_search WHERE search_freq>1 ORDER BY search_freq DESC LIMIT 300");

			error_log('====LIST OF SERTIFICATES====');

			$i = 0 ;
			foreach ($arrCerts as $word) {



				//error_log ('Current Query: '.$word);

				//Проверим, нет ли уже статей, которые подходят под этот запрос, максимум 2 статьи
				//Сначала по всему запросу
				//$num = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$word%%'", $word));
				//error_log ('Full Query Availability: '.$num);

				//теперь по отдельным словам (без последних двух букв, только для слов длиннее 4 букв, иначе слово целиком)
				$search_words = mb_split("[ ,]+", $word, 5);
				$num=0;
//Поиск по синонимам здесь слишком агрессивный и стирает многое из поисковой базы. Требует доработки. Пока отключен
//путем переименования $num в $num_syn. Чтобы включить поиск по синонимам, заменить $num_syn на $num
				$num_syn = 0;

				foreach ($search_words as $search_word){
					if (mb_strlen($search_word)>4) $search_word = mb_substr($search_word, 0, mb_strlen($search_word)-2);
					
					//ИЗ СЛЕДУЮЩЕЙ СТРОЧКИ УБРАНО УСЛОВИЕ О ТОМ, ЧТО ЗАПИСЬ ДОЛЖНА БЫТЬ ОПУБЛИКОВАНА
					//БЫЛО ТАК
					//$num=$num + $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$search_word%%'", $search_word));
					$num=$num + $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'post' AND post_title LIKE '%%$search_word%%'", $search_word));


					
					//Может быть для этого слова есть синонимы? Поищем по ним.
					//Есть ли синонимы?
					$syn1 = $wpdb->get_row($wpdb->prepare("SELECT word2 FROM wp_synonyms WHERE word1 LIKE '%%$word%%'", $word), ARRAY_A);
					$syn2 = $wpdb->get_row($wpdb->prepare("SELECT word1 FROM wp_synonyms WHERE word2 LIKE '%%$word%%'", $word), ARRAY_A);
                    
                    $syn = array();

					if (isset($syn1) && isset($syn2)) $syn = array_merge($syn1, $syn2); 
					else {
						if (isset($syn1) && (!isset($syn2))) $syn=$syn1;
						if (isset($syn2) && (!isset($syn1))) $syn=$syn2;
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

					/*	if (mb_strlen($synonym)>4) $synonym = mb_substr($synonym, 0, mb_strlen($synonym)-2);
						$num=$num + $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$synonym%%'", $synonym));*/
					}

					//error_log ('+By word - '.$search_word.' - Query Availability: '.$num);
					//Теперь по первым 4 буквам слова
					//$search_word = mb_substr($search_word, 0, 4);
					//$num=$num + $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_title LIKE '%%$search_word%%'", $search_word));
					//error_log ('+By 4 letters - '.$search_word.' - Query Availability: '.$num);
				}

				//Если количество похожих статей 2 и более - такой запрос неинтересен, так как есть необходимые статьи для ответа
				
				//ЕСЛИ УЖЕ ЕСТЬ 3 СЕРТИФИКАТА НА ПОДОБНУЮ ПРОДУКЦИЮ
				//ТО ТАКОЙ ЗАПРОС В ТАБЛИЦУ ПОИСКА НЕ ВЫВОДИМ - УЖЕ ДОСТАТОЧНО
								
				if ($num>=3) {
					/*
					02/12/2018 - ОТКЛЮЧИЛИ УДАЛЕНИЕ ЗАПРОСОВ В СВЯЗИ С ВВОДОМ ПОИСКА ПО ОТДЕЛЬНЫМ СЛОВАМ ЗАПРОСА

					error_log ('Query to delete: '.$word);
					error_log ('Total Query Availability: '.$num);
					error_log ('Total Query Synonyms: '.$num_syn);
					error_log ('Perfoming delete query: '.$word);
					error_log ('------------------------------');

					//Удаляем его из таблицы запросов
					$wpdb->delete('wp_search', array('search_query'=>$word));
					*/

					//Удаляем его из полученного массива самых популярных строк
					unset($arrCerts[$i]);
				}
				
				$i++;
			}

			error_log ('====END OF LIST====');

			if ($period=='month') echo '<p><b>За последний месяц</b></p>'; else echo '<p><b>За все время</b></p>'; 
			
			echo '<ul>';
			$currLetter = '';
			asort($arrCerts);

			foreach ($arrCerts as $i) {
					$firstLetter = mb_substr($i, 0, 1);
					if (!($firstLetter==$currLetter)) {
						echo '<p style="margin-left: -35px; font-size: 18px; font-weight: bold; border-bottom: 1px solid #dddddd;">'.$firstLetter.'</p>';
						$currLetter = $firstLetter;
					}
					
					//Частота запросов
					$freq = $wpdb->get_var($wpdb->prepare("SELECT search_freq FROM wp_search WHERE search_query LIKE '$i'", $i));

					echo '<li>'.$i.' ('.$freq.') <a target="_blank" href="https://yandex.ru/images/search?text=сертификат соответствия на '.$i.'&isize=large">найти в Яндексе</a>  <a target="_blank" href="https://www.google.ru/search?q=сертификат соответствия на '.$i.'&newwindow=1&hl=ru&tbm=isch&tbo=u&source=univ&sa=X&ved=0ahUKEwiUoIL7mpvMAhWLBSwKHSgOBxIQsAQIGw&biw=1280&bih=865&tbs=isz:l">найти в Google</a>';
					if ($is_logged) {
						echo '<a href="/remove_query.php?q='.$i.'"> (удалить)</a>';
						echo ' <b>'.$freq.'</b>';
					}
					
					if (($freq>=10) && ($freq<20)) echo '<b> x2 к оплате</b>';
					if ($freq>=20) echo '<b> x3 к оплате</b>';

					echo '</li>';
			}
			echo '</ul>';
		
		?>
	
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>