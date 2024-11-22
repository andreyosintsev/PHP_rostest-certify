<?php
/*
Template Name: New Cert or Declar
*/
?>
<?php get_header(); ?>
<?php $filename=""; ?>
<?php $filename2=""; ?>
<div id="content" class="left">
  
	<h1 class="page-title">Добавление нового сертификата или декларации</h1>
	<div class="entry">
		<h2>Порядок работы</h2>

		<ol>
			<li>Выберите файл с изображением сертификата или декларации и приложения (при наличии)</li>
			<li>Нажмите кнопку <strong>"Загрузить"</strong></li>
			<li>Выберите вид документа</li>
			<li>Заполните форму сведениями из сертификата или декларации и нажмите кнопку <strong>"Отправить"</strong></li>
			<li>Данные в форму необходимо вводить так же, как они указаны в документе.</li>
		</ol>

		<form method="post" enctype="multipart/form-data" style="margin-bottom: 10px;">
			<div class="fields_wrapper">
				<p>Изображение <strong>сертификата или декларации</strong></p>
				<input type="file" name="file" /><br />
				<p>Изображение <strong>приложения</strong> (при наличии)</p>
				<input type="file" name="file2" /><br />
			</div>
			<input type="submit" value="Загрузить" />
		</form>

		<div style="width: 100%; height: 1px; margin: 10px 0; border-bottom: 1px #aaa solid;"></div>
		
		<?php
		
		// Если была произведена отправка формы
        // задано ли имя файла сертификата
		if (isset($_FILES['file'])) {
	    
        // проверяем, можно ли загружать изображение
			$check = can_upload($_FILES['file']);
			
            if ($check === true) {
				
                // загружаем изображение на сервер
				$filename = make_upload($_FILES['file']);
				echo '<div style="color: #4cbf4c;">Файл '.$_FILES['file']['name'].' успешно загружен!</div>';

                // задано ли имя файла приложения
                if (isset($_FILES['file2'])) {
                    
                    // проверяем, можно ли загружать изображение
                    $check = can_upload($_FILES['file2']);
                    
                    if ($check === true) {
                        // загружаем изображение на сервер
                        $filename2 = make_upload($_FILES['file2']);
                        echo '<div style="color: #4cbf4c;">Файл '.$_FILES['file2']['name'].' успешно загружен!</div>';
                    }
                }

                $info = pathinfo($filename);

                //Нарежем исходное изображение для подготовки к распознаванию
                cut_to_ocr ($filename);
            ?>

		<h3 style="margin-top: 30px;">Теперь выберите вид документа</h3>

		<div class="newcert_selecttype">			
			<div class="newcert_selectitem" id="addcert1"><img style="border-width: 2px !important; border-color: rgb(47, 112, 255);" src="/about/gostr_o.jpg" alt="Сертификат соответствия ГОСТ Р - обязательная сертификация" title="Сертификат соответствия ГОСТ Р - обязательная сертификация" width="165" height="233"/></div>
			<div class="newcert_selectitem" id="addcert2"><img src="/about/gostr_d.jpg" alt="Сертификат соответствия ГОСТ Р - добровольная сертификация" title="Сертификат соответствия ГОСТ Р - добровольная сертификация" width="165" height="232"/></div>
			<div class="newcert_selectitem" id="addcert3"><img src="/about/ss.jpg" alt="Сертификат соответствия Таможенного союза" title="Сертификат соответствия Таможенного союза" width="165" height="233"/></div>
			<div class="newcert_selectitem" id="addcert4"><img src="/about/ds.jpg" alt="Декларация соответствия Таможенного союза" title="Декларация соответствия Таможенного союза" width="165" height="234"/></div>
			<div class="clear"></div>
		</div>

		<h3 style="margin-top: 30px;">Заполните сведения о документе</h3>

        <!--Форма для обязательных сертификатов ГОСТ Р-->
		<form action="/newcert.php" method="post" name="addcertform1" id="addform1" onsubmit="return check_input('form1')">
			<p>Сертификат соответствия ГОСТ Р - обязательная сертификация (красный)</p>
			<span class="formtips">Показать/скрыть подсказку</span>
            <div class="fields_wrapper">
				<img class="formtip" src="/about/gostr-o-header.png">
				<label>№ сертификата</label>
				<img class="formtip" src="/about/gostr-o-param1_number.png">
                <img class="formactual" id="gost_r_0" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_0.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param1_number');">
				<input class="fields" id="form1param1_number" name="param1_number" type="text" form="addform1" placeholder="РОСС RU.НО02.В01904" onfocus="clear_error(this.id)" value=""/>
				<div class="error" id="form1param1_numberbad">Необходимо указать № сертификата</div>
				
				<label>Срок действия</label>
				<img class="formtip" src="/about/gostr-o-param2_validity.png">
                <img class="formactual" id="gost_r_1" src="
                    <?php
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_1.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param2_validity');">
				<input class="fields" id="form1param2_validity" name="param2_validity" type="text" form="addform1" placeholder="с 03.05.2013 по 29.05.2016" onfocus="clear_error(this.id)"/>
				<div class="error" id="form1param2_validitybad">Необходимо указать срок действия сертификата</div>

				<label>Код ОКП или ТН ВЭД ТС или ОКУН</label>
				<img class="formtip" src="/about/gostr-o-okp.png">
                <img class="formactual" id="gost_r_2" style="width: auto;" src="
                    <?php
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_2.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1paramb_okp');">
				<input class="fields" id="form1paramb_okp" name="paramb_okp" type="text" form="addform1" placeholder="22 4811" onfocus="clear_error(this.id)" />
				<div class="error" id="form1paramb_okpbad">Необходимо указать или код ОКП, или ТН ВЭД ТС, или ОКУН</div>

				<label>Орган по сертификации</label>
				<img class="formtip" src="/about/gostr-o-param3_certification_agency.png">
                <img class="formactual" id="gost_r_3" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_3.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param3_certification_agency');">
				<div class="fields_width">
					<textarea class="fields" id="form1param3_certification_agency" name="param3_certification_agency" form="addform1" placeholder="РОСС RU.0001.11НО02. Научно-технический фонд &laquo;СЕРТИФИКАЦИОННЫЙ ЦЕНТР &laquo;КОНТСТАНД&raquo;. 123060, г. Москва, ул. Маршала Рыбалко, д. 8, тел (499) 194-83-80." onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param3_certification_agencybad">Необходимо указать сведения об органе сертификации</div>

				<label>Продукция (услуга, работа)</label>
				<img class="formtip" src="/about/gostr-o-param4_product.png">
                <img class="formactual" id="gost_r_4" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_4.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param4_product');">
				<div class="fields_width">
					<textarea class="fields" id="form1param4_product" name="param4_product" form="addform1" placeholder="ТРУБЫ НАПОРНЫЕ ИЗ ПОЛИЭТИЛЕНА ПЭ80, ПЭ100, SDR 6; 7,4; 9; 11; 13,6; 17; 17,6; 21; 26; 33; 41 ДИАМЕТРОМ ОТ 20 ДО 1200 ММ. ГОСТ 18599-2001 с изм. № 1. Серийный выпуск." onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param4_productbad">Необходимо указать наименование продукции (услуги, работы)"></div>

				<label>Соответствует требованиям</label>
				<img class="formtip" src="/about/gostr-o-param5_complies_with.png">
                <img class="formactual" id="gost_r_5" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_5.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param5_complies_with');">
				<div class="fields_width">
					<textarea class="fields" id="form1param5_complies_with" name="param5_complies_with" form="addform1" placeholder="ГОСТ 18599-2001 с изм. № 1 (табл. 5, п. 5.1)." onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param5_complies_withbad">Необходимо указать каким требованиям удовлетворяет продукция</div>

				<label>Изготовитель</label>
				<img class="formtip" src="/about/gostr-o-param6_manufacturer.png">
                <img class="formactual" id="gost_r_6" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_6.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param6_manufacturer');">
				<div class="fields_width">
					<textarea class="fields" id="form1param6_manufacturer" name="param6_manufacturer" form="addform1" placeholder="ООО &laquo;МирТрубПласт&raquo;. ИНН: 2112390338. 429544, Республика Чувашия, Моргаушский район, село Большой Сундырь, ул. Советская, д. 23." onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param6_manufacturerbad">Необходимо указать сведения о производителе</div>

				<label>Сертификат выдан</label>
				<img class="formtip" src="/about/gostr-o-param7_issued.png">
                <img class="formactual" id="gost_r_7" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_7.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param7_issued');">
				<div class="fields_width">
					<textarea class="fields" id="form1param7_issued" name="param7_issued" form="addform1" placeholder="ООО &laquo;МирТрубПласт&raquo;. 429544, Республика Чувашия, Моргаушский район, село Большой Сундырь, ул. Советская, д. 23. Тел. (83541) 6-93-43, факс (83541) 6-91-19." onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param7_issuedbad">Необходимо указать кому выдан сертификат</div>

				<label>На основании</label>
				<img class="formtip" src="/about/gostr-o-param8_on_the_basis.png">
                <img class="formactual" id="gost_r_8" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_8.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param8_on_the_basis');">
				<div class="fields_width">
					<textarea class="fields" id="form1param8_on_the_basis" name="param8_on_the_basis" form="addform1" placeholder="Протокола сертификационных испытаний № 652/13-КС от 28.05.2013 ИЛ Научно-технического фонда &laquo;СЕРТИФИКАЦИОННЫЙ ЦЕНТР &laquo;КОНТСТАНД&raquo;, регистрационный номер в Госреестре РОСС RU.0001.21АЮ45. 443070, г. Самара, ул. Песчаная, д. 1" onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param8_on_the_basisbad">Необходимо указать на основании чего выдан сертификат</div>

				<label>Дополнительная информация</label>
				<img class="formtip" src="/about/gostr-o-param9_add_info.png">
                <img class="formactual" id="gost_r_9" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_r_9.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form1param9_add_info');">
				<div class="fields_width">
					<textarea class="fields" id="form1param9_add_info" name="param9_add_info" form="addform1" placeholder="Знак соответствия по ГОСТ Р 50460 наносится на изделие и/или в товаросопроводительную документацию. Схема сертификации: 3." onfocus="clear_error(this.id)"></textarea>
				</div>
				<div class="error" id="form1param9_add_infobad">Необходимо указать дополнительную информацию о сертификате или поставить пробел</div>

				<img class="formtip" src="/about/gostr-o-footer.png">

				<div style="width: 100%; height: 1px; margin: 10px 0; border-bottom: 1px #aaa solid;"></div>

				<label>Ваше имя/ник на <strong>фриланс-бирже</strong><br />
					Необходимо указать имя/ник для идентификации исполнителя и оплаты выполненной работы
				</label>
				
				<input class="fields" id="form1advego_name" name="advego_name" type="text" form="addform1" placeholder="vi_lenin" onfocus="clear_error(this.id)"/>
				<div class="error" id="form1advego_namebad">Необходимо указать имя/ник исполнителя на фриланс-бирже для получения оплаты</div>

				<input type="hidden" name="img_edited" form="addform1" value="<?php echo $filename; ?>">
				<input type="hidden" name="img_edited2" form="addform1" value="<?php echo $filename2; ?>">
			</div>


			<p class="centered">
				<input style="margin-top: 10px;" type="submit" form="addform1" value="Отправить"/>
			</p>
		</form>
		
        <!--Форма для добровольных сертификатов ГОСТ Р-->
        <form action="/newcert.php" method="post" name="addcertform2" id="addform2" onsubmit="return check_input('form2')">
            <p>Сертификат соответствия ГОСТ Р - добровольная сертификация (синий)</p>
            <span class="formtips">Показать/скрыть подсказку</span>
            <div class="fields_wrapper">
                <img class="formtip" src="/about/gostr-r-header.png">
                
                <label>№ сертификата</label>
                <img class="formtip" src="/about/gostr-r-param1_number.png">
                <img class="formactual" id="gost_b_0" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_0.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param1_number');">
                <input class="fields" id="form2param1_number" name="param1_number" type="text" form="addform2" placeholder="РОСС RU.СТ08.Н00068" onfocus="clear_error(this.id)"/>
                <div class="error" id="form2param1_numberbad">Необходимо указать № сертификата</div>
                
                <label>Срок действия</label>
                <img class="formtip" src="/about/gostr-r-param2_validity.png">
                <img class="formactual" id="gost_b_1" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_1.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param2_validity');">
                <input class="fields" id="form2param2_validity" name="param2_validity" type="text" form="addform2" placeholder="с 09.06.2015 по 09.06.2018" onfocus="clear_error(this.id)"/>
                <div class="error" id="form2param2_validitybad">Необходимо указать срок действия сертификата</div>

                <label>Код ОКП или ТН ВЭД ТС или ОКУН</label>
                <img class="formtip" src="/about/gostr-r-okp.png">
                <img class="formactual" style="width: auto;" id="gost_b_2" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_2.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2paramb_okp');">
                <input class="fields" id="form2paramb_okp" name="paramb_okp" type="text" form="addform2" placeholder="73 9940" onfocus="clear_error(this.id)" />
                <div class="error" id="form2paramb_okpbad">Необходимо указать или код ОКП, или ТН ВЭД ТС, или ОКУН</div>

                <label>Орган по сертификации</label>
                <img class="formtip" src="/about/gostr-r-param3_certification_agency.png">
                <img class="formactual" id="gost_b_3" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_3.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param3_certification_agency');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param3_certification_agency" name="param3_certification_agency" form="addform2" placeholder="рег. № RA.RU.11СТ08. Московского областного Общественного Учреждения &laquo;РЕГИОНАЛЬНЫЙ СЕРТИФИКАЦИОННЫЙ ЦЕНТР &laquo;ОПЫТНОЕ&raquo;. 143913, Россия, Московская область, г. Балашиха, мкр. им. Гагарина, д. 6, пом. 1, тел 8 (495) 585-58-18, 8 (498) 600-75-16, факс 8 (495) 585-58-18, E-mail: info-opitnoe@mail.ru." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param3_certification_agencybad">Необходимо указать сведения об органе сертификации</div>

                <label>Продукция (услуга, работа)</label>
                <img class="formtip" src="/about/gostr-r-param4_product.png">
                <img class="formactual" id="gost_b_4" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_4.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param4_product');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param4_product" name="param4_product" form="addform2" placeholder="Дверь металлическая модели ДМ Н-10В. Выпускается по ТУ 5262-017-50140568-2009. Серийный выпуск." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param4_productbad">Необходимо указать наименование продукции (услуги, работы)</div>

                <label>Соответствует требованиям</label>
                <img class="formtip" src="/about/gostr-r-param5_complies_with.png">
                <img class="formactual" id="gost_b_5" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_5.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param5_complies_with');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param5_complies_with" name="param5_complies_with" form="addform2" placeholder="ГОСТ Р 51113-1997, ГОСТ Р 51224-1998 и ГОСТ Р 51072-2005 класс устойчивости ко взлому - II (второй)." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param5_complies_withbad">Необходимо указать каким требованиям удовлетворяет продукция</div>

                <label>Изготовитель</label>
                <img class="formtip" src="/about/gostr-r-param6_manufacturer.png">
                <img class="formactual" id="gost_b_6" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_6.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param6_manufacturer');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param6_manufacturer" name="param6_manufacturer" form="addform2" placeholder="ООО &laquo;НЕМАН&raquo;. ИНН: 4401042862. Адрес: 156009, г. Кострома, ул. Льняная, д. 8 &laquo;А&raquo;." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param6_manufacturerbad">Необходимо указать сведения о производителе</div>

                <label>Сертификат выдан</label>
                <img class="formtip" src="/about/gostr-r-param7_issued.png">
                <img class="formactual" id="gost_b_7" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_7.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param7_issued');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param7_issued" name="param7_issued" form="addform2" placeholder="ООО &laquo;НЕМАН&raquo;. ОКПО: 50140568, ИНН: 4401042862. Адрес: 156009, г. Кострома, ул. Льняная, д. 8 &laquo;А&raquo; ." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param7_issuedbad">Необходимо указать кому выдан сертификат</div>

                <label>На основании</label>
                <img class="formtip" src="/about/gostr-r-param8_on_the_basis.png">
                <img class="formactual" id="gost_b_8" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_8.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param8_on_the_basis');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param8_on_the_basis" name="param8_on_the_basis" form="addform2" placeholder="Протоколы № 507/ИЦ-15 от 3.06.2015 ИЦ &laquo;Опытное&raquo;, аттестат аккредитации № РОСС RU.0001.21АВ74 от 14.10.2011 г. Акт проверки состояния производства продукции, выпускаемой Обществом с ограниченной ответственностью ООО &laquo;НЕМАН&raquo;." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param8_on_the_basisbad">Необходимо указать на основании чего выдан сертификат</div>

                <label>Дополнительная информация</label>
                <img class="formtip" src="/about/gostr-r-param9_add_info.png">
                <img class="formactual" id="gost_b_9" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_gost_b_9.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form2param9_add_info');">
                <div class="fields_width">
                    <textarea class="fields" id="form2param9_add_info" name="param9_add_info" form="addform2" placeholder="Схема сертификации № 3а на сопроводительной технической документации." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form2param9_add_infobad">Необходимо указать дополнительную информацию о сертификате или поставить пробел</div>

                <img class="formtip" src="/about/gostr-r-footer.png">
                
                <div style="width: 100%; height: 1px; margin: 10px 0; border-bottom: 1px #aaa solid;"></div>

                <label>Ваше имя/ник на <strong>фриланс-бирже</strong><br />
                    Необходимо указать имя/ник для идентификации исполнителя и оплаты выполненной работы
                </label>
                
                <input class="fields" id="form2advego_name" name="advego_name" type="text" form="addform2" placeholder="vi_lenin" onfocus="clear_error(this.id)"/>
                <div class="error" id="form2advego_namebad">Необходимо указать имя/ник исполнителя на фриланс-бирже для получения оплаты</div>

                <input type="hidden" name="img_edited" form="addform2" value="<?php echo $filename; ?>">
                <input type="hidden" name="img_edited2" form="addform2" value="<?php echo $filename2; ?>">
            </div>


            <p class="centered">
                <input class="add_submit" type="submit" form="addform2" value="Отправить"/>
            </p>
        </form>

<!--Форма для сертификатов Таможенного союза-->
        <form action="/newcert.php" method="post" name="addcertform3" id="addform3" onsubmit="return check_input('form3')">
            <p>Сертификат соответствия Таможенного союза</p>
            <span class="formtips">Показать/скрыть подсказку</span>
            <div class="fields_wrapper">
                <img class="formtip" src="/about/ss-header.png">
                
                <label>№ сертификата</label>
                <img class="formtip" src="/about/ss-param1_number.png">
                <img class="formactual" id="tnvedts_0" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_0.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param1_number');">
                <input class="fields" id="form3param1_number" name="param1_number" type="text" form="addform3" placeholder="ТС BY/112 02.01. 002 00671" onfocus="clear_error(this.id)"/>
                <div class="error" id="form3param1_numberbad">Необходимо указать № сертификата</div>

                <label>Орган по сертификации</label>
                <img class="formtip" src="/about/ss-param3_certification_agency.png">
                <img class="formactual" id="tnvedts_1" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_1.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param3_certification_agency');">
                <div class="fields_width">
                    <textarea class="fields" id="form3param3_certification_agency" name="param3_certification_agency" form="addform3" placeholder="Научно-производственное республиканское унитарное предприятие &laquo;Белорусский государственный институт стандартизации и сертификации&raquo;; место нахождения: Республика Беларусь, 220113, г. Минск, ул. Мележа, 3, к. 406; фактический адрес: Республика Беларусь, 220113, г. Минск, ул. Мележа, 3; тел.: (+375 17) 237 14 21; факс: (+375 17) 262 16 23; e-mail: belgiss@mail.belpak.by; аттестат аккредитации: BY/112 002.03 от 03.06.1993, Республиканское унитарное предприятие &laquo;Белорусский государственный центр аккредитации&raquo;" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3param3_certification_agencybad">Необходимо указать сведения об органе сертификации</div>

                <label>Заявитель</label>
                <img class="formtip" src="/about/ss-parama_declarant.png">
                <img class="formactual" id="tnvedts_2" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_2.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3parama_declarant');">
                <div class="fields_width">
                    <textarea class="fields" id="form3parama_declarant" name="parama_declarant" form="addform3" placeholder="Представительство общества с ограниченной ответственностью &laquo;Самсунг Электроникс Рус Компани&raquo; (Российская Федерация) в Республике Беларусь; сведения о регистрации: разрешение Министерства иностранных дел Республики Беларусь № 5086 от 12.08.2010; место нахождения: Республика Беларусь, 220123, г. Минск, ул. В. Хоружей, 25, корпус 3, комната 1001; фактический адрес: Республика Беларусь, 220123, г. Минск, ул. В. Хоружей, 25, корпус 3, комната 1001; тел.: 290-72-65; факс: 290-72-65; e-mail: w.serc@samsung.com" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3parama_declarantbad">Необходимо указать сведения о заявителе/декларанте</div>

                <label>Изготовитель</label>
                <img class="formtip" src="/about/ss-param6_manufacturer.png">
                <img class="formactual" id="tnvedts_3" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_3.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param6_manufacturer');">
                <div class="fields_width">
                    <textarea class="fields" id="form3param6_manufacturer" name="param6_manufacturer" form="addform3" placeholder="SAMSUNG ELECTRONICS СО., LTD. Республика Корея, (Maetan-dong) 129, Samsung-ro, Yeongtong-gu, Suwon-si, Gyeonggi-do 443-742, Republic of Korea; заводы-изготовители указаны в приложении (бланк BY 0019972)" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3param6_manufacturerbad">Необходимо указать сведения о производителе</div>

                <label>Продукция (услуга, работа)</label>
                <img class="formtip" src="/about/ss-param4_product.png">
                <img class="formactual" id="tnvedts_4" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_4.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param4_product');">
                <div class="fields_width">
                    <textarea class="fields" id="form3param4_product" name="param4_product" form="addform3" placeholder="Микроволновые печи торговой марки &laquo;SAMSUNG&raquo;, модели указаны в приложении (бланк BY 0019972), серийный выпуск" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3param4_productbad">Необходимо указать наименование продукции (услуги, работы)</div>

                <label>Код ТН ВЭД ТС</label>
                <img class="formtip" src="/about/ss-okp.png">
                <img class="formactual" id="tnvedts_5" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_5.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3paramb_okp');">
                <input class="fields" id="form3paramb_okp" name="paramb_okp" type="text" form="addform3" placeholder="8516 50 000 0" onfocus="clear_error(this.id)" />
                <div class="error" id="form3paramb_okpbad">Необходимо указать код ТН ВЭД ТС</div>

                <label>Соответствует требованиям</label>
                <img class="formtip" src="/about/ss-param5_complies_with.png">
                <img class="formactual" id="tnvedts_6" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_6.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param5_complies_with');">
                <div class="fields_width">
                    <textarea class="fields" id="form3param5_complies_with" name="param5_complies_with" form="addform3" placeholder="ТР ТС 004/2011 &laquo;О безопасности низковольтного оборудования&raquo;, СТБ IEC 60335-1-2008, СТБ IEC 60335-2-25-2012, СТБ IEC 60335-2-9-2008, СТБ EN 50366-2007; ТР ТС 020/2011 &laquo;Электромагнитная совместимость технических средств&raquo;, ГОСТ Р 51318.11-2006 (СИСПР 11:2004), ГОСТ Р 51317.3.2-2006 (МЭК 61000-3-2:2005), ГОСТ Р 51317.3.3-2008 (МЭК 61000-3-3:2005), ГОСТ Р 51318.14.1-2006 (СИСПР 14-1:2005), ГОСТ Р 51318.14.2-2006 (СИСПР 14-2:2001)" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3param5_complies_withbad">Необходимо указать каким требованиям удовлетворяет продукция</div>

                <label>На основании</label>
                <img class="formtip" src="/about/ss-param8_on_the_basis.png">
                <img class="formactual" id="tnvedts_7" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_7.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param8_on_the_basis');">
                <div class="fields_width">
                    <textarea class="fields" id="form3param8_on_the_basis" name="param8_on_the_basis" form="addform3" placeholder="Акт от 15.11.2013; протоколы испытаний Испытательного центра БелГИСС, Республика Беларусь, аттестат аккредитации BY/112 02.1.0.0085 от 01.09.1995 (срок действия с 02.10.2009 по 02.10.2014), протоколы №№ 20618 ЭБ от 04.04.2012, 20615-1 ЭБ от 05.04.2012, 21830 ЭБ от 28.11.2012, 21825-1 ЭБ от 28.11.2012. 22388 ЭБ от 05.04.2013, 20659 ЭМС от 05.04.2012, 21826 ЭМС от 28.11.2012" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3param8_on_the_basisbad">Необходимо указать на основании чего выдан сертификат</div>
                
                <label>Дополнительная информация</label>
                <img class="formtip" src="/about/ss-param9_add_info.png">
                <img class="formactual" id="tnvedts_8" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_8.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param9_add_info');">
                <div class="fields_width">
                    <textarea class="fields" id="form3param9_add_info" name="param9_add_info" form="addform3" placeholder="Срок службы продукции 7 лет." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form3param9_add_infobad">Необходимо указать дополнительную информацию о сертификате или поставить пробел</div>

                <label>Срок действия</label>
                <img class="formtip" src="/about/ss-param2_validity.png">
                <img class="formactual" id="tnvedts_9" src="
                    <?php                                                      
                        echo '/upload/'.$info['filename'].'/'.$info['filename'].'_tnvedts_9.'.$info['extension'];
                    ?>
                " onclick="ocr(this.id, 'form3param2_validity');">
                <input class="fields" id="form3param2_validity" name="param2_validity" type="text" form="addform3" placeholder="с 18.03.2014 по 15.04.2018 включительно" onfocus="clear_error(this.id)"/>
                <div class="error" id="form3param2_validitybad">Необходимо указать срок действия сертификата</div>

                <img class="formtip" src="/about/ss-footer.png">

                <div style="width: 100%; height: 1px; margin: 10px 0; border-bottom: 1px #aaa solid;"></div>

                <label>Ваше имя/ник на <strong>фриланс-бирже</strong><br />
                    Необходимо указать имя/ник для идентификации исполнителя и оплаты выполненной работы
                </label>
                
                <input class="fields" id="form3advego_name" name="advego_name" type="text" form="addform3" placeholder="vi_lenin" onfocus="clear_error(this.id)"/>
                <div class="error" id="form3advego_namebad">Необходимо указать имя/ник исполнителя на фриланс-бирже для получения оплаты</div>

                <input type="hidden" name="img_edited" form="addform3" value="<?php echo $filename; ?>">
                <input type="hidden" name="img_edited2" form="addform3" value="<?php echo $filename2; ?>">
            </div>


            <p class="centered">
                <input class="add_submit" type="submit" form="addform3" value="Отправить"/>
            </p>
        </form>

<!--Форма для Деклараций соответствия-->
        <form action="/newcert.php" method="post" name="addcertform4" id="addform4" onsubmit="return check_input('form4')">
            <p>Декларация соответствия</p>
            <span class="formtips">Показать/скрыть подсказку</span>
            <div class="fields_wrapper">
                <img class="formtip" src="/about/ds-header.png">
                
                <label>Заявитель</label>
                <img class="formtip" src="/about/ds-parama_declarant.png">
                <div class="fields_width">
                    <textarea class="fields" id="form4parama_declarant" name="parama_declarant" form="addform4" placeholder="Общество с ограниченной ответственностью &laquo;Мастер Мармелада&raquo;, ОГРН: 1156952013140. Зарегистрировано Межрайонной инспекцией Федеральной налоговой службы №12 по Тверской области 19 июня 2015 г. Адрес: 170041, Россия, Тверская область, город Тверь, улица Михаила Румянцева, дом 44. Фактический адрес: 170041, Россия, Тверская область, город Тверь, улица Михаила Румянцева, дом 44, Телефон: 89066527731, E-mail: mironovasvu@mail.ru в лице генерального директора Мироновой Светланы Владимировны" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form4parama_declarantbad">Необходимо указать сведения о заявителе/декларанте</div>

                <label>Продукция (услуга, работа)</label>
                <img class="formtip" src="/about/ds-param4_product.png">
                <div class="fields_width">
                    <textarea class="fields" id="form4param4_product" name="param4_product" form="addform4" placeholder="Мармелад желейный формовой: &laquo;С вареньем из апельсина&raquo;; &laquo;С вареньем из лимона и имбиря&raquo;; &laquo;С вареньем из черники&raquo;; &laquo;С вареньем из брусники&raquo;; &laquo;С вареньем из клубники&raquo;; &laquo;С вареньем из малины&raquo;; &laquo;С вареньем из вишни&raquo;; &laquo;С вареньем из яблок и корицы&raquo;; &laquo;С вареньем из киви&raquo;; &laquo;С вареньем из крыжовника&raquo;; &laquo;С вареньем из черной смородины&raquo;." onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form4param4_productbad">Необходимо указать наименование продукции (услуги, работы)</div>

                <label>Изготовитель</label>
                <img class="formtip" src="/about/ds-param6_manufacturer.png">
                <div class="fields_width">
                    <textarea class="fields" id="form4param6_manufacturer" name="param6_manufacturer" form="addform4" placeholder="Общество с ограниченной ответственностью &laquo;Мастер Мармелада&raquo;. Адрес: 170041, Россия, Тверская область, город Тверь, улица Михаила Румянцева, дом 44. Фактический адрес: 170041, Россия, Тверская область, город Тверь, улица Михаила Румянцева, дом 44. ОГРН: 1156952013140, телефон: 89066527731, E-mail: mironovasvu@mail.ru" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form4param6_manufacturerbad">Необходимо указать сведения о производителе</div>

                <label>Код ТН ВЭД ТС</label>
                <img class="formtip" src="/about/ds-okp.png">
                <input class="fields" id="form4paramb_okp" name="paramb_okp" type="text" form="addform4" placeholder="1704 90 650 0" onfocus="clear_error(this.id)" />
                <div class="error" id="form4paramb_okpbad">Необходимо указать код ТН ВЭД ТС</div>

                <label>Соответствует требованиям</label>
                <img class="formtip" src="/about/ds-param5_complies_with.png">
                <div class="fields_width">
                    <textarea class="fields" id="form4param5_complies_with" name="param5_complies_with" form="addform4" placeholder="ТР ТС 021/2011 &laquo;О безопасности пищевой продукции&raquo;; ТР ТС 022/2011 &laquo;Пищевая продукция в части ее маркировки&raquo;" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form4param5_complies_withbad">Необходимо указать каким требованиям удовлетворяет продукция</div>

                <label>На основании</label>
                <img class="formtip" src="/about/ds-param8_on_the_basis.png">
                <div class="fields_width">
                    <textarea class="fields" id="form4param8_on_the_basis" name="param8_on_the_basis" form="addform4" placeholder="Протоколов испытаний № 4104-4105 от 15.08.2016 Тверской независимый испытательный центр ООО &laquo;Тверьтест&raquo;, Аттестат аккредитации № RA.RU.21ПУ24 от 27.07.2015г, адрес: г. Тверь, ул. Плеханова, д. 51" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form4param8_on_the_basisbad">Необходимо указать на основании чего выдана декларация</div>
                
                <label>Дополнительная информация</label>
                <img class="formtip" src="/about/ds-param9_add_info.png">
                <div class="fields_width">
                    <textarea class="fields" id="form4param9_add_info" name="param9_add_info" form="addform4" placeholder="Срок годности мармелада 6 месяцев при температуре хранения 10-20 град. С и относительной влажности воздуха 75-85%. Продукция маркируется единым знаком обращения на рынке Евразийского экономического союза" onfocus="clear_error(this.id)"></textarea>
                </div>
                <div class="error" id="form4param9_add_infobad">Необходимо указать дополнительную информацию о декларации или поставить пробел</div>

                <label>№ декларации</label>
                <img class="formtip" src="/about/ds-param1_number.png">
                <input class="fields" id="form4param1_number" name="param1_number" type="text" form="addform4" placeholder="ТС N RU Д-RU.АЕ67.В01835" onfocus="clear_error(this.id)"/>
                <div class="error" id="form4param1_numberbad">Необходимо указать № декларации</div>

                <label>Срок действия</label>
                <img class="formtip" src="/about/ds-param2_validity.png">
                <input class="fields" id="form4param2_validity" name="param2_validity" type="text" form="addform4" placeholder="с 18.08.2016 по 18.08.2019 включительно" onfocus="clear_error(this.id)"/>
                <div class="error" id="form4param2_validitybad">Необходимо указать срок действия декларации</div>

                <div style="width: 100%; height: 1px; margin: 10px 0; border-bottom: 1px #aaa solid;"></div>

                <label>Ваше имя/ник на <strong>фриланс-бирже</strong><br />
                    Необходимо указать имя/ник для идентификации исполнителя и оплаты выполненной работы
                </label>
                
                <input class="fields" id="form4advego_name" name="advego_name" type="text" form="addform4" placeholder="vi_lenin" onfocus="clear_error(this.id)"/>
                <div class="error" id="form4advego_namebad">Необходимо указать имя/ник исполнителя на фриланс-бирже для получения оплаты</div>

                <input type="hidden" name="img_edited" form="addform4" value="<?php echo $filename; ?>">
                <input type="hidden" name="img_edited2" form="addform4" value="<?php echo $filename2; ?>">
            </div>


            <p class="centered">
                <input class="add_submit" type="submit" form="addform4" value="Отправить"/>
            </p>
        </form>


		<?php }	else {
			// выводим сообщение об ошибке
			echo '<div style="color: #d51313;"><b>Сертификат: </b>'.$check.'</div>';
			}
		}?>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>