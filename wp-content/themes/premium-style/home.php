<?php
/**
 * home.php
 *
 * The home template file.
 * 
 * @link        http://www.gopiplus.com/
 * @Demo        http://www.gopiplus.com/work/2013/11/11/premium-style-wordpress-theme/
 * 
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 * 
 */
?>
<?php get_header(); ?>
<div id="content" class="left">
	<!-- start layout content loop -->
	<div id="content-loop" class="layout-content-loop">	
		<div class="home-foreword">
			<p>На сайте представлены сертификаты соответствия ГОСТ Р и Таможенного союза, а также Декларации соответствия Таможенного союза на различные материалы, товары, продукцию и услуги.</p> 

			<p>Скачать сертификаты соответствия на продукцию и декларации можно в высоком разрешении. Информация представлена в виде изображений оригинальных документов и их копий. Вместе с изображениями представлена текстовая информация, содержащаяся в сертификатах.</p>
		</div>


		<?php if (!(isset($_SESSION['auth']))) { ?>
			<div class="adsense_adapt_home">
				<!-- Yandex.RTB R-A-1572973-3 -->
				<div id="yandex_rtb_R-A-1572973-3"></div>
				<script>window.yaContextCb.push(()=>{
				  Ya.Context.AdvManager.render({
				    renderTo: 'yandex_rtb_R-A-1572973-3',
				    blockId: 'R-A-1572973-3'
				  })
				})</script>
			</div>
		<?php } ?>

		<div class="search_by_name">
			<form method="get" id="searchform" action="/">
				<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
				<div class="searchform_text"><input type="text" class="input-text" name="s" id="s" placeholder="Поиск по названию продукции"/></div>
			</form>
			<p class="search_ex">Например, <a href="/?s=%D0%BE%D0%B4%D0%B5%D0%B6%D0%B4%D0%B0+%D0%BC%D1%83%D0%B6%D1%81%D0%BA%D0%B0%D1%8F">одежда мужская</a></p>
		</div>

		<h2>Новые сертификаты</h2>
		<?php
			global $query_string;
			$postslist = get_posts($query_string.'&numberposts=3&order=DSC&orderby=date');
			$num=0;?>
			
			<div class="home_items">
			<?php
			foreach ($postslist as $post) :
				$num = $num + 1;
				setup_postdata($post);
				$title = get_the_title(); $up_title = mb_strtoupper(mb_substr($title, 0, 1)).mb_substr($title,1,mb_strlen($title));
				if ($num == 3) {?>
					<div class="home_item_wrap home_last">
				<?php } else {?>
					<div class="home_item_wrap">
				<?php } ?>
					<div class="info">
						<div class="date">
						<?php 
                            $number=get_post_meta(get_the_ID(), "param1_number", $single = true);
                            echo '№ '.getCountry($number).$number;
                        ?>
                    	</div>
					</div>
					<div class="specs_cover_home">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?> скачать бесплатно"><?php echo thumbnail_home(302, 433, $post); ?></a>
					</div>
					<div class="specs_title">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $up_title; ?> скачать бесплатно"><?php echo $up_title; ?></a>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
			<div class="clear" style="padding-bottom: 20px;"></div>

		<a href="/reestr-sertifikatov/" title="Реестр сертификатов соответствия">
			<h2 style="margin: 20px 0 10px;">Сертификаты и декларации соответствия</h2>
		</a>
		<?php
			global $query_string;
			$postslist = get_posts($query_string.'&numberposts=200&order=DSC&orderby=meta_value_num&meta_key=download_count');
			$postslist = sortActualByPosts($postslist);
			$postslist = array_slice($postslist, 0, 5);
			foreach ($postslist as $post) :
				setup_postdata($post);
			?>
				<div class="arch_home">
					<div class="arch_thumbnails_home">
						<?php echo thumbnails(false, true, 52, 75, $post); ?>
					</div>
					<div class="arch_description_home">
						<a href="<?php the_permalink() ?>"  title="Скачать сертификат соответствия на <?php echo get_the_title(); ?>">Сертификат соответствия на <?php echo get_the_title(); ?></a>
					</div>
					<div class="clear"></div>
				</div>	
			<?php endforeach; 
			wp_reset_postdata();?>
		<div class="clear"></div>
		<div class="more_data">
			<a href="/reestr-sertifikatov/">И еще <b><?php echo get_home_post_count(5);?></b> <?php echo declination(get_home_post_count(5), array("сертификат и декларация", "сертификата и декларации", "сертификатов и деклараций"));?>...</a>
		</div>

		<a href="http://pozhsert.ru/" title="Пожарные сертификаты скачать бесплатно">
			<h2 style="margin: 20px 0 10px;">Сертификаты и декларации пожарной безопасности</h2>
		</a>
				<div class="arch_home">
					<div class="arch_thumbnails_home">
						<div class="center_img"><a href="http://pozhsert.ru/gorelki-gazovye-promyshlennye-gip-20-gip-30-gip-40-gip-50-gip-60-gip-70-gip-80-gip-100-gip-150-gip-200-gip-300-gip-400-gip-500/" title="горелки газовые промышленные ГИП-20, ГИП-30, ГИП-40, ГИП-50, ГИП-60, ГИП-70, ГИП-80, ГИП-100, ГИП-150, ГИП-200, ГИП-300, ГИП-400, ГИП-500 подробное описание">
                                <img src="<?php echo site_url(); ?>/imgs/pozhsert/thmb_TP0615454.jpg" title="Пожарный сертификат на горелки газовые промышленные ГИП-20, ГИП-30, ГИП-40, ГИП-50, ГИП-60, ГИП-70, ГИП-80, ГИП-100, ГИП-150, ГИП-200, ГИП-300, ГИП-400, ГИП-500" alt="Скачать пожарный сертификат на горелки газовые промышленные ГИП-20, ГИП-30, ГИП-40, ГИП-50, ГИП-60, ГИП-70, ГИП-80, ГИП-100, ГИП-150, ГИП-200, ГИП-300, ГИП-400, ГИП-500" />
                            </a>
                        </div>
                    </div>
					<div class="arch_description_home">
						<a href="http://pozhsert.ru/gorelki-gazovye-promyshlennye-gip-20-gip-30-gip-40-gip-50-gip-60-gip-70-gip-80-gip-100-gip-150-gip-200-gip-300-gip-400-gip-500/"  title="Скачать пожарные сертификат на горелки газовые промышленные ГИП-20, ГИП-30, ГИП-40, ГИП-50, ГИП-60, ГИП-70, ГИП-80, ГИП-100, ГИП-150, ГИП-200, ГИП-300, ГИП-400, ГИП-500">
                            Пожарный сертификат на горелки газовые промышленные ГИП-20, ГИП-30, ГИП-40, ГИП-50, ГИП-60, ГИП-70, ГИП-80, ГИП-100, ГИП-150, ГИП-200, ГИП-300, ГИП-400, ГИП-500
                        </a>
					</div>
					<div class="clear"></div>
				</div>	
                <div class="arch_home">
					<div class="arch_thumbnails_home">
						<div class="center_img">
                            <a href="http://pozhsert.ru/ognebiozashhitnyj-sostav-dlya-drevesiny-ekodom-ognebio-kedr-ognebio/" title="огнебиозащитный состав для древесины «Экодом ОгнеБио», «Кедр ОгнеБио» подробное описание">
                                <img src="<?php echo site_url(); ?>/imgs/pozhsert/thmb_0001399.jpg" title="Пожарный сертификат на огнебиозащитный состав для древесины «Экодом ОгнеБио», «Кедр ОгнеБио»" alt="Скачать пожарный сертификат на огнебиозащитный состав для древесины «Экодом ОгнеБио», «Кедр ОгнеБио»" />
                            </a>
                        </div>
                    </div>
					<div class="arch_description_home">
						<a href="http://pozhsert.ru/ognebiozashhitnyj-sostav-dlya-drevesiny-ekodom-ognebio-kedr-ognebio/" title="Скачать пожарные сертификат на огнебиозащитный состав для древесины «Экодом ОгнеБио», «Кедр ОгнеБио»">Пожарный сертификат на огнебиозащитный состав для древесины «Экодом ОгнеБио», «Кедр ОгнеБио»
                        </a>
					</div>
					<div class="clear"></div>
				</div>	
				<div class="arch_home">
					<div class="arch_thumbnails_home">
						<div class="center_img">
                            <a href="http://pozhsert.ru/lak-akrilovyj-vodno-dispersionnyj-tonirovannyj-dlya-naruzhnyx-i-vnutrennix-rabot-lak-parketnyj-akrilovyj-vlp-1-lak-dlya-saun-parade-l30/" title="лак акриловый водно-дисперсионный тонированный для наружных и внутренних работ, лак паркетный акриловый ВЛП-1, лак для саун PARADE L30 подробное описание">
                                <img src="<?php echo site_url(); ?>/imgs/pozhsert/thmb_0005597.jpg" title="Пожарный сертификат на лак акриловый водно-дисперсионный тонированный для наружных и внутренних работ, лак паркетный акриловый ВЛП-1, лак для саун PARADE L30" alt="Скачать пожарный сертификат на лак акриловый водно-дисперсионный тонированный для наружных и внутренних работ, лак паркетный акриловый ВЛП-1, лак для саун PARADE L30" />
                            </a>
                        </div>
                    </div>
					<div class="arch_description_home">
						<a href="http://pozhsert.ru/lak-akrilovyj-vodno-dispersionnyj-tonirovannyj-dlya-naruzhnyx-i-vnutrennix-rabot-lak-parketnyj-akrilovyj-vlp-1-lak-dlya-saun-parade-l30/"  title="Скачать пожарные сертификат на лак акриловый водно-дисперсионный тонированный для наружных и внутренних работ, лак паркетный акриловый ВЛП-1, лак для саун PARADE L30">
                            Пожарный сертификат на лак акриловый водно-дисперсионный тонированный для наружных и внутренних работ, лак паркетный акриловый ВЛП-1, лак для саун PARADE L30
                        </a>
					</div>
					<div class="clear"></div>
				</div>	
				<div class="arch_home">
					<div class="arch_thumbnails_home">
						<div class="center_img">
                            <a href="http://pozhsert.ru/kraski-vodno-dispersionnye-torgovyx-marok-white-line-raskras-emmiks-doktor-farbe/" title="краски водно-дисперсионные торговых марок &#171;™White line&#187;, &#171;™РасКрас», &#171;™Эммикс&#187;, &#171;™Doktor Farbe&#187; подробное описание">
                                <img src="<?php echo site_url(); ?>/imgs/pozhsert/thmb_TP0668319.jpg" title="Пожарный сертификат на краски водно-дисперсионные торговых марок &#171;™White line&#187;, &#171;™РасКрас», &#171;™Эммикс&#187;, &#171;™Doktor Farbe&#187;" alt="Скачать пожарный сертификат на краски водно-дисперсионные торговых марок &#171;™White line&#187;, &#171;™РасКрас», &#171;™Эммикс&#187;, &#171;™Doktor Farbe&#187;" />
                            </a>
                        </div>
                    </div>
					<div class="arch_description_home">
						<a href="http://pozhsert.ru/kraski-vodno-dispersionnye-torgovyx-marok-white-line-raskras-emmiks-doktor-farbe/"  title="Скачать пожарные сертификат на краски водно-дисперсионные торговых марок &#171;™White line&#187;, &#171;™РасКрас», &#171;™Эммикс&#187;, &#171;™Doktor Farbe&#187;">Пожарный сертификат на краски водно-дисперсионные торговых марок &#171;™White line&#187;, &#171;™РасКрас», &#171;™Эммикс&#187;, &#171;™Doktor Farbe&#187;</a>
					</div>
					<div class="clear"></div>
				</div>
                <div class="arch_home">
					<div class="arch_thumbnails_home">
						<div class="center_img">
                            <a href="http://pozhsert.ru/linoleum-iz-polivinilxlorida-sportivnye-napolnye-pokrytiya-marok-grabosport-graboflex/" title="линолеум из поливинилхлорида (спортивные напольные покрытия) марок: GraboSport, Graboflex подробное описание">
                                <img src="<?php echo site_url(); ?>/imgs/pozhsert/thmb_TP0647569.jpg" title="Пожарный сертификат на линолеум из поливинилхлорида (спортивные напольные покрытия) марок: GraboSport, Graboflex" alt="Скачать пожарный сертификат на линолеум из поливинилхлорида (спортивные напольные покрытия) марок: GraboSport, Graboflex" />
                            </a>
                        </div>
                    </div>
					<div class="arch_description_home">
						<a href="http://pozhsert.ru/linoleum-iz-polivinilxlorida-sportivnye-napolnye-pokrytiya-marok-grabosport-graboflex/"  title="Скачать пожарные сертификат на линолеум из поливинилхлорида (спортивные напольные покрытия) марок: GraboSport, Graboflex">Пожарный сертификат на линолеум из поливинилхлорида (спортивные напольные покрытия) марок: GraboSport, Graboflex</a>
					</div>
					<div class="clear"></div>
				</div>	
		<div class="clear"></div>

		<div class="more_data">
			<a href="http://pozhsert.ru/reestr-pozharnyh-sertifikatov/">И еще <b>1098</b> сертификатов и деклараций...</a>
		</div>

		<a href="/gosty/" title="ГОСТы на материалы, товары, продукцию и услуги">
			<h2 style="margin: 20px 0 10px;">ГОСТы и технические регламенты</h2>
		</a>
		<?php
            $norms = getAllNorms(5);

            foreach ($norms as $norm) { ?>
				<div class="arch">
					<div class="arch_thumbnails_home">
						<div class="right_img" style="float: right;">
							<img src="<?php echo site_url(); ?>/imgs/pdf_icon3sm.png" style="border: none;" title="<?php echo $norm->name.' - '.$norm->name_full; ?>">
						</div>
					</div>
					<div class="arch_description_home" style="padding-top: 7px; padding-bottom: 7px;">
						<a href="<?php echo site_url(); ?>/gost/?param=<?php echo urlencode($norm->name); ?>" title="Скачать <?php echo $norm->name.' - '.$norm->name_full; ?>"><?php echo $norm->name; ?> - <?php echo $norm->name_full; ?></a>
					</div>
					<div class="clear"></div>
				</div>
			<?php 
			}
		?>
		<div class="more_data">
			<a href="/gosty/">И еще <b><?php echo get_norm_count(5);?></b> <?php echo declination(get_norm_count(5), array("нормативный документ", "нормативных документа", "нормативных документов"));?>...</a>
		</div>
	</div>
</div>
<?php get_sidebar(); ?>
<div class="clear"></div>
<?php get_footer(); ?>