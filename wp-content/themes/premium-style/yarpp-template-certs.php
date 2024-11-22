<?php /*
Example template
Author: mitcho (Michael Yoshitaka Erlewine)
*/
?>
<?php if ($related_query->have_posts()):?>
<hr style="margin-left:0px; margin-right:0px; margin-bottom: 7px;" />
<h3 class="hentry">Сертификаты и декларации на подобную продукцию</h3>
	<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
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
                                if ($manufacturer!='') echo '<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
                        ?>                      
                    </div>
                    <div class="arch_number">
                        <?php 
                            $number=get_post_meta(get_the_ID(), "param1_number", $single = true);
                            echo '№ '.getCountry($number).$number;
                        ?>
                    </div>
					<div style="margin: 5px 0 20px 0"><a href="<?php the_permalink(); ?>" title="<?php echo mb_ucfirst(get_the_title()); ?> подробное описание">Подробнее/скачать</a></div>
				</div>
				<div class="clear"></div>
			</div>		
	<?php endwhile; ?>
	<div class="clear"></div>
	<hr style="margin-left:0px; margin-right:0px; margin-bottom: 5px;" />

<?php endif; ?>
