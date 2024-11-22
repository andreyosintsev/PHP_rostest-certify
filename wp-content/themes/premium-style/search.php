<?php
/**
 * search.php
 *
 * The template for displaying Search Results pages.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php get_header(); ?>
<div id="content" class="left">
	<div class="search_by_name">
		<form method="get" id="searchform" action="/">
			<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
			<?php 
				$searchstring=get_search_query();
				if ($searchstring=='') $searchstring="Поиск сертификата по названию продукции";
			?>
			<div class="searchform_text"><input type="text" class="input-text" name="s" id="s"  value="<?php echo $searchstring; ?>" onfocus="if (this.value == 'Поиск сертификата по названию продукции') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Поиск сертификата по названию продукции';}"/></div>
		</form>
		<p class="search_ex">Например, <a href="/?s=%D0%BE%D0%B4%D0%B5%D0%B6%D0%B4%D0%B0+%D0%BC%D1%83%D0%B6%D1%81%D0%BA%D0%B0%D1%8F">одежда мужская</a></p>
	</div>

	<?php
	//	Если вместо наименования продукции ввели номер сертификата.
	$searchstring = get_search_query();
	$searchlen = mb_strlen($searchstring);
	if ($searchlen>=5) {
		$searchstr = mb_substr($searchstring, $searchlen-5);
		$searchstr2 = mb_substr($searchstring, $searchlen-8, 5);

		if (is_numeric($searchstr) || is_numeric($searchstr2)) {
			header('Location: /naiti-sertifikat-po-nomeru/?param='.$searchstring);
			exit();
		}
	}

	?>


  	<h1 class="page-title">Результаты поиска: <strong><?php echo get_search_query(); ?></strong></h1>
  	<div id="content-loop" class="layout-content-loop">
	  	<?php error_log('SEARCH BY NAME: '.get_search_query());
	   		$post_ids = search_by_titleB(get_search_query());

	   		$post_ids = sortActual($post_ids);	//Сначала действующие сертификаты, потом все остальные	
	   		if ($post_ids) {
	   			$cnt = count($post_ids);
                echo '<p style="margin: -5px 0 10px 0;">'.declination($cnt, array("Найден", "Найдено", "Найдено")).' <b>'.$cnt.'</b> '.declination($cnt, array("результат", "результата", "результатов")).'</p>';
 				$num=0;
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
		                        <?php $manufacturer = getManufacturer(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
		                                if ($manufacturer!='') echo '<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
		                        ?>                      
		                    </div>
		                    <div class="arch_number">
		                        <?php 
		                            $number=get_post_meta(get_the_ID(), "param1_number", $single = true);
		                            echo '№ '.getCountry($number).$number;
		                        ?>
		                    </div>
							<div style="margin: 5px 0 20px 0"><a href="<?php the_permalink() ?>" title="<?php echo mb_ucfirst(get_the_title()); ?> подробное описание">Подробное описание</a></div>
						</div>
						<div class="clear"></div>
					</div>
				<?php $num=$num+1;
					if (($num==2) && (!(isset($_SESSION['auth'])))) { ?>
                        <div class="adsense_adapt_home">
                            <!--new ad code-->
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
	<?php } else echo '<p>Не удалось найти сертификаты по запросу <b>'.get_search_query().'</b></p>';?>

	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>