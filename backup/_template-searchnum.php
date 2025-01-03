<?php
/*
Template Name: Number Search
*/
?>
<?php get_header(); ?>
<?php $metavalue = $_GET['param']; ?>

<div id="content" class="left">
	<div class="post">
		<?php if ($metavalue=='') echo '<h1 class="page-title">Поиск по номеру сертификата</h1>';?>
		<div id="content-loop" class="layout-content-loop">
			<?php if ($metavalue=='') echo '<p style="margin-bottom:35px;">Введите номер сертификата или его часть</p>';?>
			<div class="search_by_name">
				<form id="searchform" method="get">
					<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
					<?php 
						if ($metavalue=='') $searchstring="Поиск сертификата по номеру"; else $searchstring=$metavalue;
					?>
					<div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
				</form>
				<p class="search_ex">Введите номер сертификата, например, <a href="/naiti-sertifikat-po-nomeru/?param=RU.СЩ04.B02830">RU.СЩ04.B02830</a></p>
			</div>

			<?php if ($metavalue=='') {?>

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
			<?php
				echo '<div id="examples">';
				echo '<img src="' . site_url() . '/search/gost_o.jpg" title="Номер сертификата соответствия ГОСТ Р обязательная сертификация"/>';
				echo '<p class="undertitle">Номер сертификата соответствия ГОСТ Р обязательная сертификация</p>';
				echo '<img src="' . site_url() . '/search/gost_d.jpg" title="Номер сертификата соответствия ГОСТ Р добровольная сертификация"/>';
				echo '<p class="undertitle">Номер сертификата соответствия ГОСТ Р добровольная сертификация</p>';
				echo '<img src="' . site_url() . '/search/ss.jpg" title="Номер сертификата соответствия Таможенного союза"/>';
				echo '<p class="undertitle">Номер сертификата соответствия Таможенного союза</p>';
				echo '<img src="' . site_url() . '/search/ds.jpg" title="Номер декларации соответствия Таможенного союза"/>';
				echo '<p class="undertitle">Номер декларации соответствия Таможенного союза</p>';
				echo '</div>';
			};?>
	
<?php
	if (!($metavalue=='')) {
		error_log('SEARCH BY NUM : '.$metavalue);
        $searchvalue = $metavalue;

		?>
  
		<h1 class="page-title">Результаты поиска: <strong><?php echo $searchvalue; ?></strong></h1>
		<p class="search_num_note">Внимание: поиск осуществляется по цифрам в номере сертификата или декларации</p>

		<?php

		$post_ids = searchByNum($searchvalue);

		if (count($post_ids)>0) {

			$num=0;
	   		$cnt = count($post_ids);
            echo '<p style="margin: -5px 0 10px 0;">'.declination($cnt, array("Найден", "Найдено", "Найдено")).' <b>'.$cnt.'</b> '.declination($cnt, array("результат", "результата", "результатов")).'</p>';
			foreach($post_ids as $post_id) {
				$post = get_post($post_id);?>
			
				<div class="arch">
					<div class="arch_thumbnails">
						<?php echo thumbnails(false, true, 75, 107, $post); ?>
					</div>
					<div class="arch_description">
						<h2 class="entry-title arch_title">
							<a href="<?php the_permalink() ?>"  title="Сертификат на <?php echo get_the_title(); ?>"><?php echo mb_ucfirst(get_the_title()); ?></a>
						</h2>
	                    <div class="arch_manufacturer">
	                        <?php $manufacturer = getCompletedName(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
	                                if ($manufacturer!='') echo '<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
	                        ?>                      
	                    </div>
	                    <div class="arch_number">
	                        <?php 
	                            $number=get_post_meta(get_the_ID(), "param1_number", $single = true);
	                            echo '№ '.getCountry($number).$number;
	                        ?>
	                    </div>
						<div style="margin: 5px 0 20px 0"><a href="<?php the_permalink() ?>" title="<?php echo mb_ucfirst(get_the_title()); ?> скачать">Подробнее/скачать</a></div>
					</div>
					<div class="clear"></div>
				</div>
				<?php $num=$num+1;
					if (($num==2) && (!(isset($_SESSION['auth'])))) { ?>
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
				<?php }?>
			<?php }?>
			
			<div class="clear"></div>
			
			<?php if (function_exists ('wp_page_numbers')) wp_page_numbers ();  // функция постраничной навигации

			wp_reset_postdata();
			$wp_query = $save_wpq;

		} else echo '<p>Не удалось найти сертификаты с номером <b>'.$searchvalue.'</b></p>';
	}
?>
  
		</div>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>