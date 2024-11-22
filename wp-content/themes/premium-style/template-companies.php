<?php
/*
Template Name: Company
*/
?>
<?php get_header(); ?>
<?php $metavalue = $_GET['param']; ?>

<!--template-companies.php-->
<div id="content" class="left">
	<div class="post">
		<?php if ($metavalue=='') echo '<h1 class="page-title">Поиск сертификата соответствия по изготовителю</h1>';?>
		<div id="content-loop" class="layout-content-loop">
            <?php if ($metavalue=='') echo '<p style="margin-bottom:35px;">Введите наименование организации-изготовителя или его часть</p>';?>
			<div class="search_by_name">
				<form id="searchform" method="get">
					<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
                    <?php 
                        if ($metavalue=='') $searchstring="Поиск сертификата по организации-изготовителю"; else $searchstring=$metavalue;
                    ?>
					<div class="searchform_text"><input type="text" name="param" placeholder="<?php echo $searchstring; ?>"/></div>
				</form>
				<p class="search_ex">Введите наименование компании, например <a href="<?php echo site_url(); ?>/kompanii/?param=ТехноНИКОЛЬ">ТехноНИКОЛЬ</a></p>
			</div>

			<?php
				if (!isset($metavalue)) {

					if (!(isset($_SESSION['auth']))) { ?>
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
					<?php }


                    echo '<h2 style="margin-top: 20px; margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #ddd;">Алфавитный указатель</h2>';
                    echo '<p style="margin-bottom: 30px;">Организации-изготовители, для которых на данном сайте представлено наибольшее количество сертификатов и деклараций. Для поиска организации-изготовителя, не представленной в этом перечне, воспользуйтесь поиском.</p>';

                    $companies = getAllCompanies(300);

                    $currLetter = '';
                    $opened = false;

                    foreach ($companies as $company=>$freq) {
                        $firstLetter = mb_substr($company, 0, 1);
                        if (!($firstLetter==$currLetter)) {

                            if ($opened) echo '</div>'; else $opened = true;

                            echo '<p class="companies_title">'.$firstLetter.'</p>'."\r\n";
                            echo '<div class="companies_items">'."\r\n";
                            
                            $currLetter = $firstLetter;
                            $i = 0;
                        }

                        /* Вывод компаний, начинающихся на данную букву */
                        $logo = getManufacturerLogo($company);
                        if ($logo) { ?>
                            <div class="companies_item">
                                <div class="companies_item_logo"
                                     style="background: url(<?php echo site_url(); ?>/logos/<?php echo getManufacturerLogo($company)?>);
                                             background-size: contain;
                                             background-repeat: no-repeat;
                                             background-position: center;">
                                    <a style="display: block; height: 100%;"
                                       href="<?php echo site_url(); ?>/kompanii/?param=<?php echo urlencode($company);?>"
                                       title="Сертификаты <?php echo $company; ?>"
                                    >
                                    </a>
                                </div>
                                <div class="companies_item_title">
                                    <a class="companies_cpny"
                                       href="<?php echo site_url(); ?>/kompanii/?param=<?php echo urlencode($company);?>"
                                       title="Сертификаты <?php echo $company; ?>"
                                    >
                                        <?php echo $company; ?>
                                    </a>
                                </div>
                            </div>
                        <?php
                            $i++;
                        }

                        /* Конец вывода */
                    }
                    echo '</div>';
                } else {

				
				$args = array(
					'posts_per_page' => 10,
					'post_status' => 'publish',
					'paged' => get_query_var('paged'),
					'meta_query' => array(
						'manufacturer' => array(
			            	'key' => 'param6_manufacturer',
			            	'value' => $metavalue,
			            	'compare' => 'LIKE'
			            )
			        ),
			        'orderby' => 'manufacturer',
  					'order' => 'ASC'
	        	);

                $company_prev = '';

                ?>

                <h1 class="page-title">Сертификаты <strong><?php echo $metavalue; ?></strong></h1>

                <?php

                global $wp_query;

				$save_wpq = $wp_query;
				
				$wp_query = new WP_Query ($args);
				if ($wp_query->found_posts>0) {
                        $cnt = $wp_query->found_posts;
                        echo '<p style="margin: -5px 0 10px 0;">'.declination($cnt, array("Найден", "Найдено", "Найдено")).' <b>'.$cnt.'</b> '.declination($cnt, array("результат", "результата", "результатов")).'</p>';
						$num = 0;
						$yamap_container = 0;
						while ( $wp_query->have_posts() ) {
							$wp_query->the_post();
                            $company = getCompany(get_post_meta($post->ID, "param6_manufacturer", $single = true));

                            if ($company!=$company_prev) {
                                $logo_pic = getManufacturerLogo($company);
                                if ($logo_pic) $sizes = newSizes(site_url().'/logos/'.$logo_pic);
                                if ($sizes)
                                    $logo = '<div class="company_logo" style="background: url('.site_url().'/logos/'.getManufacturerLogo($company).'); background-size: contain; background-repeat: no-repeat; background-position: top; width: '.$sizes[0].'px ; height: '.$sizes[1].'px;"></div>'; else $logo='';
                                echo '<div style="padding: 5px 0; border-top: 3px solid #ccc; border-bottom: 3px solid #ccc; background: #fff;">'.$logo.'<span style="font-weight: bold;">Изготовитель: </span>'.get_post_meta($post->ID, "param6_manufacturer", $single = true).'<div style="clear:both;"></div>';
                                unset ($sizes);

								$coords = getManufacturerCoords($company);
								if ($coords !== '') {
                                    $shortcode = '[yamap container="yamap'.$yamap_container.'" center="'.$coords.'" height="250px" zoom="13" type="yandex#map" controls="typeSelector;zoomControl"][yaplacemark coord="'.$coords.'" icon="islands#blueHomeCircleIcon" color="#1e98ff" name="'.$company.'"][/yamap]';
                                    echo '<div style="clear:both;"></div>';
                                    echo '<div id="yamap'.$yamap_container.'"></div>';
                                    echo do_shortcode($shortcode);
                                    $yamap_container++;
                                }
                                echo '<div style="clear:both;"></div></div>';                            }
                            $company_prev = $company;
                            
				?>

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
	                                            if ($manufacturer!='') echo '<a href="'. site_url() .'/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
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
                    <?php  }?>
                    
                    <div class="clear"></div>
                    
                    <?php if (function_exists ('wp_page_numbers')) wp_page_numbers ();  // функция постраничной навигации

						wp_reset_postdata();
						$wp_query = $save_wpq;
			
				} else {
                    $wp_query->set_404();
                    status_header( 404 );
                    echo '<p>Не удалось найти сертификаты компании-изготовителя <b>'.$metavalue.'</b></p>';
                }

                    ?>
            <?php };?>
		</div>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>