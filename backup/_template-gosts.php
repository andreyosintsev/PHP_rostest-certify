<?php
/*
Template Name: GOSTs
*/

?>
<?php get_header(); ?>
<link rel="stylesheet" id="yarppRelatedCss-css" href="<?php echo plugins_url(); ?>/yet-another-related-posts-plugin/style/related.css" media='all' />
<?php $metavalue = $_GET['param']; ?>
<?php $noclip = $_GET['noclip']; ?>

<!--template-gosts.php-->
<div id="content" class="left">
	<div class="post">
	<?php
		if ($metavalue=='') {
			echo '<h1 class="page-title">ГОСТы на материалы, товары, продукцию и услуги</h1>';
	?>
			<div id="content-loop" class="layout-content-loop">
            	<?php if ($metavalue=='') echo '<p style="margin-bottom:35px;">Введите номер ГОСТа, технического регламента или его часть</p>';?>
				<div class="search_by_name">
					<form id="searchform" method="get">
						<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
	                    <?php 
	                        if ($metavalue=='') $searchstring="Поиск ГОСТа по номеру"; else $searchstring=$metavalue;
	                    ?>
						<div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
					</form>
					<p class="search_ex">Введите номер ГОСТа или его часть, например <a href="<?php echo site_url(); ?>/gosty/?param=ГОСТ+30547-97">ГОСТ 30547-97</a></p>
				</div>

				<?php if (!(isset($_SESSION['auth']))) { ?>
					<div class="adsense_adapt_home">
						<!-- Yandex.RTB R-A-1572973-2 -->
						<div id="yandex_rtb_R-A-1572973-2"></div>
						<script>window.yaContextCb.push(()=>{
						  Ya.Context.AdvManager.render({
						    renderTo: 'yandex_rtb_R-A-1572973-2',
						    blockId: 'R-A-1572973-2'
						  })
						})</script>
					</div>
				<?php } ?>

				<h2 style="margin-top: 15px; margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #ddd;">Перечень ГОСТов</h2>
                <p style="margin-bottom: 30px;">ГОСТы, технические регламенты и другие нормативные документы по популярности. Для поиска ГОСТа, не представленного в этом перечне, воспользуйтесь поиском.</p>

               	<?php

                $norms = getAllNorms(10);

                foreach ($norms as $norm) { ?>
					<div class="arch">
						<div class="arch_thumbnails_home">
							<img src="<?php echo site_url(); ?>/imgs/pdf_icon3.png" style="border: none;" title="<?php echo $norm->name; ?>">
						</div>
						<div class="arch_description_home" style="padding-top: 7px; padding-bottom: 7px;">
							<h2 class="entry-title arch_title">
								<a href="<?php echo site_url(); ?>/gosty/?param=<?php echo urlencode($norm->name); ?>" title="Скачать <?php echo $norm->name; ?>"><?php echo $norm->name; ?></a>
							</h2>
		                    <div class="arch_manufacturer">
		                        <a href="<?php echo site_url(); ?>/gosty/?param=<?php echo urlencode($norm->name); ?>" title="Скачать <?php echo $norm->name; ?>"><?php echo $norm->name_full; ?></a>
		                    </div>
						</div>
						<div class="clear"></div>
					</div>
				<?php 
				} ?>
                <div class="more_data">
                    <u>И еще <b><?php echo get_norm_count(10);?></b> <?php echo declination(get_norm_count(10), array("нормативный документ", "нормативных документа", "нормативных документов"));?>...</u>
                </div>
			</div>
        <?php }
        else {
           	$norms = getNormsByName($metavalue);

           	//Нашли хоть одну похожую норму
           	if (isset($norms)) {?>

           		<?php 
           		//Если найдена только одна норма, то выводим ее, иначе выведем список похожих норм
           		if (count($norms)==1) { 

           			$norm = current($norms);
           		?>


				    <h1 class="entry-title"><?php echo $norm->name ?></h1>
	            	<div class="arch_norm">
						<?php echo $norm->name_full; ?>
					</div>

				    <!-- start post content -->
				    <div class="entry entry-content">
						<div class="thumbnails">
							<div class="center_norm">
								<?php 
									$scode = '[pdf-embedder url="'. site_url() .'/norms/'.$norm->file.'"]';
									
									echo apply_filters('the_content', $scode);
								?>
							</div>
							<div class="clear"></div>
						</div>

						<div class="clear" style="padding-bottom: 20px;"></div>
						<?php if (!(isset($_SESSION['auth']))) { ?>
							<div class="adsense_adapt_home">
								<?php
									$country = unserialize(file_get_contents('http://ip-api.com/php/'.$_SERVER['REMOTE_ADDR'].'?fields=country'))['country'];
									if ($country != 'Russia') { ?>
										<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4019665621332188"
										     crossorigin="anonymous"></script>
										<!-- rostest-certify - адаптивный -->
										<ins class="adsbygoogle"
										     style="display:block"
										     data-ad-client="ca-pub-4019665621332188"
										     data-ad-slot="7655870617"
										     data-ad-format="auto"
										     data-full-width-responsive="true"></ins>
										<script>
										     (adsbygoogle = window.adsbygoogle || []).push({});
										</script>
								<?php } else {?>
										<!-- Yandex.RTB R-A-1572973-2 -->
										<div id="yandex_rtb_R-A-1572973-2"></div>
										<script>window.yaContextCb.push(()=>{
										  Ya.Context.AdvManager.render({
										    renderTo: 'yandex_rtb_R-A-1572973-2',
										    blockId: 'R-A-1572973-2'
										  })
										})</script>
								<?php } ?>
							</div>
						<?php }
						

						$args = array(
							'posts_per_page' => 50,
							'post_status' => 'publish',
							'meta_query' => array(
								'norms' => array(
					            	'key' => 'param5_complies_with',
					            	'value' => $norm->name,
					            	'compare' => 'LIKE'
					            )
					        ),
					        'orderby' => 'title',
		  					'order' => 'ASC'
			        	);

				
			        	$related_query = get_posts ($args);
						$related_query = sortActualByPosts($related_query);
                        $related_total = count($related_query);
						if (!$noclip) $related_query = array_slice($related_query, 0, 5);                        

						?>
						<div class="yarpp-related">
							<hr style="margin-left:0px; margin-right:0px; margin-bottom: 7px;" />
							<h3 class="hentry">Сертификаты и декларации, соответствующие данному нормативу</h3>
							
							<?php foreach ($related_query as $post) { 
								setup_postdata($post);
							?>
								<div class="arch">
										<div class="arch_thumbnails">
											<?php echo thumbnails(false, true, 113, 162, $post); ?>
										</div>
										<div class="arch_description">
						                    <h2 class="entry-title arch_title">
						                        <a href="<?php the_permalink() ?>"  title="Сертификат на <?php echo get_the_title(); ?>"><?php echo mb_ucfirst(get_the_title()); ?></a>
						                    </h2>
						                    <div class="yarpp_manufacturer">
						                        <?php $manufacturer = getManufacturer(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
						                                if ($manufacturer!='') echo '<a href="'. site_url() .'/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
						                        ?>                      
						                    </div>
						                    <div class="arch_number">
						                        <?php 
						                            $number=get_post_meta(get_the_ID(), "param1_number", $single = true);
						                            echo '№ '.getCountry($number).$number;
						                        ?>
						                    </div>
											<div style="margin: 5px 0 20px 0"><a href="<?php the_permalink(); ?>" title="Скачать <?php echo mb_ucfirst(get_the_title()); ?>">Подробнее/скачать</a></div>
										</div>
										<div class="clear"></div>
									</div>		
							<?php } ?>
							<div class="clear"></div>
                            <?php
                                if (($related_total>5) && !$noclip) {?>
                            <div class="more_data">
                                 <a href="<?php echo site_url(); ?>/gosty/?param=<?php echo urlencode($metavalue);?>&noclip=true">
                                     И еще <b><?php echo $related_total-5;?></b> <?php echo declination($related_total-5, array("сертификат и декларация", "сертификата и декларации", "сертификатов и деклараций"));?>...
                                 </a>
                            </div>
                            <?php }?>
							<hr style="margin-left:0px; margin-right:0px; margin-bottom: 5px;" />
						</div>

						<div class="download">
							<a href="<?php echo site_url(); ?>/download_norm_count.php?id=<?php echo $norm->ID ?>" title="Скачать <?php echo $norm->name; ?> - <?php echo $norm->name_full; ?>" target="_blank">
								<div class="download_link" title="Скачать <?php echo $norm->name;?>" onclick="ym(32820367,'reachGoal','norm-click'); return true;">Скачать <?php echo $norm->name;?></div>
							</a>
							<div class="fixed"></div>
						</div>
					</div>
				<?php  }
				else if (count($norms) > 1) { 
					//Найдено более нормы, выведем список похожих норм.
					?>
					<h1 class="page-title">ГОСТы на материалы, товары, продукцию и услуги</h1>
					<div class="search_by_name">
					<form id="searchform" method="get">
						<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
	                    <?php 
	                        if ($metavalue=='') $searchstring="Поиск ГОСТа по номеру"; else $searchstring=$metavalue;
	                    ?>
						<div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
					</form>
					<p class="search_ex">Введите номер ГОСТа или его часть, например <a href="<?php echo site_url(); ?>/gosty/?param=ГОСТ+30547-97">ГОСТ 30547-97</a></p>
					</div>
					<?php 

						$norms = array_slice($norms, 0, 20);
					?>

					<h1 class="page-title">Результаты поиска: <strong><?php echo $metavalue; ?></strong></h1>

					<?php

					echo '<p style="margin: -5px 0 10px 0;">'.declination($cnt, array("Найден", "Найдено", "Найдено")).' <b>'.count($norms).'</b> '.declination($cnt, array("результат", "результата", "результатов")).'</p>';

					foreach ($norms as $norm) { ?>
						<div class="arch">
							<div class="arch_thumbnails_home">
								<img src="<?php echo site_url(); ?>/imgs/pdf_icon3.png" style="border: none;" title="<?php echo $norm->name; ?>">
							</div>
							<div class="arch_description_home" style="padding-top: 7px; padding-bottom: 7px;">
								<h2 class="entry-title arch_title">
									<a href="<?php echo site_url(); ?>/gosty/?param=<?php echo urlencode($norm->name); ?>" title="Скачать <?php echo $norm->name; ?>"><?php echo $norm->name; ?></a>
								</h2>
			                    <div class="arch_manufacturer">
			                        <a href="<?php echo site_url(); ?>/gosty/?param=<?php echo urlencode($norm->name); ?>" title="Скачать <?php echo $norm->name; ?>"><?php echo $norm->name_full; ?></a>
			                    </div>
							</div>
							<div class="clear"></div>
						</div>
		  			<?php
		  			}
				} else {
					$wp_query->set_404();
                	status_header( 404 );
            	?>
            		<h1 class="page-title">ГОСТы на материалы, товары, продукцию и услуги</h1>
            		<div class="search_by_name">
					<form id="searchform" method="get">
						<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
	                    <?php 
	                        if ($metavalue=='') $searchstring="Поиск ГОСТа по номеру"; else $searchstring=$metavalue;
	                    ?>
						<div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
					</form>
					<p class="search_ex">Введите номер ГОСТа или его часть, например <a href="<?php echo site_url(); ?>/gosty/?param=ГОСТ+30547-97">ГОСТ 30547-97</a></p>
					</div>
					<h1 class="page-title">Результаты поиска: <strong><?php echo $metavalue; ?></strong></h1>
					<p>Не удалось найти нормы <b><?php echo $metavalue; ?></b></p>
				<?php }
			} else { 
				$wp_query->set_404();
                status_header( 404 );
            ?>
            	<h1 class="page-title">ГОСТы на материалы, товары, продукцию и услуги</h1>
            	<div class="search_by_name">
					<form id="searchform" method="get">
						<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
	                    <?php 
	                        if ($metavalue=='') $searchstring="Поиск ГОСТа по номеру"; else $searchstring=$metavalue;
	                    ?>
						<div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
					</form>
					<p class="search_ex">Введите номер ГОСТа или его часть, например <a href="<?php echo site_url(); ?>/gosty/?param=ГОСТ+30547-97">ГОСТ 30547-97</a></p>
				</div>
				<h1 class="page-title">Результаты поиска: <strong><?php echo $metavalue; ?></strong></h1>
				<p>Не удалось найти нормы <b><?php echo $metavalue; ?></b></p>
			<?php }
        } ?>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>