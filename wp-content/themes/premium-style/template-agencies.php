<?php
/*
Template Name: Agencies
*/
?>
<?php get_header(); ?>
<?php $metavalue = $_GET['param']; ?>

<div id="content" class="left">
	<div class="post">
  
		<?php if ($metavalue=='') echo '<h1 class="page-title">Реестр органов по сертификации</h1>';?>
		<div id="content-loop" class="layout-content-loop">

		<?php
			if (!isset($metavalue)) {
		?>
		  
				<p>Представлен реестр органов по сертификации и испытательных лабораторий (центров) Таможенного союза, осуществляющих оценку соответствия продукции, включенной в Единый перечень продукции и требованиям Технических регламентов Таможенного союза.</p>
			  
				<div class="register">

					<?php
						$orderby = $_GET["orderby"];
			            $order   = $_GET["order"];
			            $st 	 = $_GET["st"];
			            $len   	 = $_GET["len"];

			            if (!($orderby=="title" || $orderby=="number" || $orderby=="city" )) $orderby = "number";
			            if (!($order=="ASC" || $order=="DESC")) $order = "ASC";
			            if (!(($st) && is_numeric($st) && ($st>=0))) $st=0;
			            if (!(($len) && is_numeric($len) && ($len>=0))) $len=25;
			        ?>

		            <div id='wp_page_numbers'>
					<ul><li class="page_info">Стр. 1 из <?php echo (intdiv(count(getAllAgenciesNum()),$len)+1); ?></li>
						<?php
							//echo 'Agencies: '.(intdiv(count(getAllAgenciesNum()),$len)+1);
							$i = 0;
						  	while ($i<count(getAllAgenciesNum())) {
						  		if ($i==$st) $out = '<li class="active_page"><a href="' . site_url() .'/organy-po-sertifikacii/?orderby='.$orderby.'&order='.$order.'&st='.$i.'">'.intdiv($i+$len,$len).'</a></li>'; else
							  		$out = '<li><a href="'. site_url() .'/organy-po-sertifikacii/?orderby='.$orderby.'&order='.$order.'&st='.$i.'">'.intdiv($i+$len,$len).'</a></li>';
						  		echo $out;
						  		$i+=$len;
							}
						?>
					</ul>
					<div style='float: none; clear: both;'></div>
					</div>

					<?php
		           		if (($orderby=="number") && ($order == "ASC")) {?>
			                <div class="td_left td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=number&order=DESC">Рег. № ▼</a>
                            </div>
			                <div class="td_middle td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=title&order=ASC">Наименование органа по сертификации</a>
                            </div>
			                <div class="td_right td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=city&order=ASC">Город</a>
                            </div>
			            <?php }
		            	if (($orderby=="number") && ($order == "DESC")) {?>
			                <div class="td_left td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=number&order=ASC">Рег. № ▲</a>
                            </div>
			                <div class="td_middle td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=title&order=ASC">Наименование органа по сертификации</a>
                            </div>
			                <div class="td_right td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=city&order=ASC">Город</a>
                            </div>
		            	<?php }
		            	if (($orderby=="title") && ($order == "ASC")) {?>
		                	<div class="td_left td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=number&order=ASC">Рег. №</a>
                            </div>
		                	<div class="td_middle td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=title&order=DESC">Наименование органа по сертификации ▼</a>
                            </div>
		                	<div class="td_right td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=city&order=ASC">Город</a>
                            </div>
		            	<?php }
		            	if (($orderby=="title") && ($order == "DESC")) {?>
			                <div class="td_left td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=number&order=ASC">Рег. №<a>
                            </div>
		    	            <div class="td_middle td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=title&order=ASC">Наименование органа по сертификации ▲</a>
                            </div>
		    	            <div class="td_right td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=city&order=ASC">Город</a>
                            </div>
		        	    <?php }
		        	    if (($orderby=="city") && ($order == "ASC")) {?>
		                	<div class="td_left td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=number&order=ASC">Рег. №</a>
                            </div>
		                	<div class="td_middle td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=title&order=DESC">Наименование органа по сертификации</a>
                            </div>
		                	<div class="td_right td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=city&order=DESC">Город ▼</a>
                            </div>
		            	<?php }
		            	if (($orderby=="city") && ($order == "DESC")) {?>
			                <div class="td_left td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=number&order=ASC">Рег. №<a>
                            </div>
		    	            <div class="td_middle td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=title&order=DESC">Наименование органа по сертификации</a>
                            </div>
		    	            <div class="td_right td_agencies tablehead">
                                <a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?orderby=city&order=ASC">Город ▲</a>
                            </div>
		    	        <?php }
		            ?>


		            <div class="clear"></div>

		            <?php
		                $agenciesNums = getAllAgenciesNum();
		                $agenciesNames = getAllAgenciesNames();
		                $agenciesCities = getAllAgenciesCities();
		                $agenciesLinks = getAllAgenciesLinks();

		                if (($orderby=="number") && ($order == "ASC")) {
		                	ksort($agenciesNums);
		                } 

		                if (($orderby=="number") && ($order == "DESC")) {
		                	krsort($agenciesNums);
		                }

		                if (($orderby=="title") && ($order == "ASC")) {
							asort($agenciesNames);
		                }

		                if (($orderby=="title") && ($order == "DESC")) {
		                	arsort($agenciesNames);
		                }

		                if (($orderby=="city") && ($order == "ASC")) {
		                	asort($agenciesCities);
		                }

		                if (($orderby=="city") && ($order == "DESC")) {
		                	arsort($agenciesCities);
		                }

						$num=0;

						if ($orderby=="number") {
							//echo 'num: '.$num.' st: '.$st.' len: '.$len."\r\n";

			                foreach ($agenciesNums as $regnum=>$agency) {
			                	if ($agenciesNames[$regnum]=='') continue;
			                	if ($num < $st) {
			                		++$num;
			                		continue;
			                	}
			                	if ($num >= $st + $len) break;
			            ?>
			                
								<div class="td_left td_agencies"><?php echo $regnum;?>
									<br><span class="table_man expandinfo" data-reg="<?php echo $regnum?>" title="Просмотреть полные сведения об органе по сертификации">Просмотреть сведения</span>
								</div>
				                <div class="td_middle td_agencies"><a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?param=<?php echo urlencode($agenciesNames[$regnum]) ?>" title="Сертификаты выданные <?php echo $agenciesNames[$regnum] ?>"><?php echo $agenciesNames[$regnum] ?></a>
			          			    <?php
				        	            if (array_key_exists($regnum, $agenciesLinks)) 
				        	            {
				        	        		$domain=parse_url($agenciesLinks[$regnum], PHP_URL_HOST);

				        	            	echo '<br><span class="table_man"><a href="'.$agenciesLinks[$regnum].'" title="Перейти на официальный сайт '.$agenciesNames[$regnum].'" target="_blank" rel="nofollow">'.$domain.'</a></span>';
				        	        	}
			            			?>
				                </div>
				                <div class="td_right td_agencies"><?php	echo $agenciesCities[$regnum];?></div>
				            	<div class="clear"></div>
				            	<div class="td_agencies_info" data-reg="<?php echo $regnum;?>">
				                	<p style="padding: 5px; border-bottom: 1px solid #ccc; background: #f3f3f3;"><span style="font-weight: bold;">Орган по сертификации:</span> <?php echo getAgencyInfoByReg($regnum);?></p>
				                </div>
			           		<?php 
			           			++$num;
			           		}	
			           	} 

						if ($orderby=="title") {

			                foreach ($agenciesNames as $regnum=>$agencyName) {
			                	if (!$agencyName) continue;
			                	if ($num<$st) {
			                		++$num;
			                		continue;
			                	}
			                	if ($num>=$st+$len) break;
			            ?>
			                
								<div class="td_left td_agencies"><?php echo $regnum;?>
									<br><span class="table_man expandinfo" data-reg="<?php echo $regnum?>" title="Просмотреть полные сведения об органе по сертификации">Просмотреть сведения</span>
								</div>
				                <div class="td_middle td_agencies"><a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?param=<?php echo urlencode($agencyName) ?>" title="Сертификаты выданные <?php echo $agencyName ?>"><?php echo $agencyName ?></a>
			          			    <?php
				        	            if (array_key_exists($regnum, $agenciesLinks)) 
				        	            {
				        	        		$domain=parse_url($agenciesLinks[$regnum], PHP_URL_HOST);

				        	            	echo '<br><span class="table_man"><a href="'.$agenciesLinks[$regnum].'" title="Перейти на официальный сайт '.$agenciesNames[$regnum].'" target="_blank" rel="nofollow">'.$domain.'</a></span>';
				        	        	}
			            			?>
				                </div>
				                <div class="td_right td_agencies"><?php	echo $agenciesCities[$regnum];?></div>
				                <div class="clear"></div>
				                <div class="td_agencies_info" data-reg="<?php echo $regnum?>">
				                	<p style="padding: 5px; border-bottom: 1px solid #ccc; background: #f3f3f3;"><span style="font-weight: bold;">Орган по сертификации:</span> <?php echo getAgencyInfoByReg($regnum);?></p>
				                </div>
			           		<?php 
			           			++$num;
			           		}	
			           	} 

						if ($orderby=="city") {
			           		foreach ($agenciesCities as $regnum=>$cityName) {
			           			if ($num<$st) {
			                		++$num;
			                		continue;
			                	}
			                	if ($num>=$st+$len) break;			                	
			            	?>
			                
								<div class="td_left td_agencies"><?php echo $regnum;?>
									<br><span class="table_man expandinfo" data-reg="<?php echo $regnum?>" title="Просмотреть полные сведения об органе по сертификации">Просмотреть сведения</span>
								</div>
				                <div class="td_middle td_agencies"><a href="<?php echo site_url(); ?>/organy-po-sertifikacii/?param=<?php echo urlencode($agenciesNames[$regnum]) ?>" title="Сертификаты выданные <?php echo $agenciesNames[$regnum] ?>"><?php echo $agenciesNames[$regnum] ?></a>
			          			    <?php
				        	            if (array_key_exists($regnum, $agenciesLinks)) 
				        	            {
				        	        		$domain=parse_url($agenciesLinks[$regnum], PHP_URL_HOST);

				        	            	echo '<br><span class="table_man"><a href="'.$agenciesLinks[$regnum].'" title="Перейти на официальный сайт '.$agenciesNames[$regnum].'" target="_blank" rel="nofollow">'.$domain.'</a></span>';
				        	        	}
			            			?>
				                </div>
				                <div class="td_right td_agencies"><?php	echo $agenciesCities[$regnum];?></div>
				            	<div class="clear"></div>
				            	<div class="td_agencies_info" data-reg="<?php echo $regnum?>">
				                	<p style="padding: 5px; border-bottom: 1px solid #ccc; background: #f3f3f3;"><span style="font-weight: bold;">Орган по сертификации:</span> <?php echo getAgencyInfoByReg($regnum);?></p>
				                </div>
			           		<?php 
			           			++$num;
			           		}	

			           	}?>
				</div>

				<div id='wp_page_numbers'>
					<ul><li class="page_info">Стр. 1 из <?php echo (intdiv(count(getAllAgenciesNum()),$len)+1); ?></li>
						<?php
							//echo 'Agencies: '.(intdiv(count(getAllAgenciesNum()),$len)+1);
							$i=0;
						  	while ($i<count(getAllAgenciesNum())) {
						  		if ($i==$st) $out = '<li class="active_page"><a href="' . site_url() .'/organy-po-sertifikacii/?orderby='.$orderby.'&order='.$order.'&st='.$i.'">'.intdiv($i+$len,$len).'</a></li>'; else
							  		$out = '<li><a href="' . site_url() .'/organy-po-sertifikacii/?orderby='.$orderby.'&order='.$order.'&st='.$i.'">'.intdiv($i+$len,$len).'</a></li>';
						  		echo $out;
						  		$i+=$len;
							}
						?>
					</ul>
					<div style='float: none; clear: both;'></div>
				</div>
			<?php } else {

				
				$args = array(
					'posts_per_page' => 10,
					'post_status' => 'publish',
					'paged' =>get_query_var('paged'),
					'meta_query' => array(
						'agency' => array(
			            	'key' => 'param3_certification_agency',
			            	'value' => $metavalue,
			            	'compare' => 'LIKE'
			            )
			        ),
			        'orderby' => array( 'agency' => 'ASC',  'title' => 'ASC' )
  					/*'order' => 'ASC'*/
	        	);

                $agency_prev = '';

                ?>

                <h1 class="page-title">Сертификаты выданные <strong><?php echo $metavalue; ?></strong></h1>

                <?php
				
				global $wp_query;

				$save_wpq = $wp_query;
				
				$wp_query = new WP_Query ($args);

				if ($wp_query->found_posts>0) {
			         ?>
				 

					<?php
                        $cnt = $wp_query->found_posts;
                        echo '<p style="margin: -5px 0 10px 0;">'.declination($cnt, array("Найден", "Найдено", "Найдено")).' <b>'.$cnt.'</b> '.declination($cnt, array("результат", "результата", "результатов")).'</p>';
						$num=0;
						while ( $wp_query->have_posts() ) {
							$wp_query->the_post();
                            $agency = getCompany(get_post_meta($post->ID, "param3_certification_agency", $single = true));

                            if ($agency!=$agency_prev) echo '<p style="padding: 5px; border-bottom: 1px solid #ccc; background: #f3f3f3;"><span style="font-weight: bold;">Орган по сертификации: </span>'.get_post_meta($post->ID, "param3_certification_agency", $single = true).'</p>';
                            $agency_prev = $agency;
                            
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
                        <?php $num=$num+1;?>
                        <?php if (($num==2) && (!(isset($_SESSION['auth'])))) { ?>
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
                        <?php }?>
                    <?php  }?>
                    <div class="clear"></div>
                    <?php if (function_exists ('wp_page_numbers')) wp_page_numbers ();  // функция постраничной навигации

						wp_reset_postdata();
						$wp_query = $save_wpq;
			
				} else {
					$wp_query->set_404();
                    status_header( 404 );
					echo '<p>Не удалось найти сертификаты выданные <b>'.$metavalue.'</b></p>';
				}?>
            <?php };?>
		</div>	
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
