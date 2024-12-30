<?php
/**
 * archive.php
 *
 * The template for displaying Archive pages.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php get_header(); ?>
<div id="content" class="left">
  <?php if (false) get_template_part( 'breadcrumbs', get_post_format() ); ?>
    <h1 class="page-title">
		<?php 
			if(is_tag()) {echo 'Сертификаты на '; echo mb_strtolower(single_tag_title('',0));};
			if(is_category()) {
				$cat = get_category(get_query_var('cat'));
				$cat_parent_name = end(get_ancestors($cat->cat_ID, 'category' ));
				echo 'Сертификаты на продукцию '.get_cat_name($cat_parent_name).' '.single_cat_title('',0);
			};
		?>
	</h1>
		<?php 
			if((is_category()) && (count(get_ancestors($cat->cat_ID, 'category' ))>1)) {
				echo getCategoryTree($cat);
			};
		?>
  	<div id="content-loop" class="layout-content-loop">
	<?php 
		query_posts($query_string . "&orderby=title&order=ASC");
		if ( have_posts() ) {
	 		$num=0;
			while ( have_posts() ) : the_post();
	?>
			<div class="arch">
				<div class="arch_thumbnails">
					<?php echo thumbnails(false, true, 75, 107, $post); ?>
				</div>
				<div class="arch_description">
					<h2 class="entry-title arch_title"><a href="<?php the_permalink() ?>"  title="Сертификат на <?php echo get_the_title(); ?>"><?php echo mb_ucfirst(get_the_title()); ?></a></h2>
                    <div class="arch_manufacturer">
                        <?php 
                        	$manufacturer = getManufacturer(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
                            if ($manufacturer!='') echo '<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
                        ?>                      
                    </div>
                    <div class="arch_number">
                        <?php 
                            $number=get_post_meta(get_the_ID(), "param1_number", $single = true);
                            echo '№ '.getCountryFlag($number).$number;
                        ?>
                    </div>
					<div style="margin: 5px 0 20px 0"><a href="<?php the_permalink() ?>" title="<?php echo mb_ucfirst(get_the_title()); ?> скачать">Подробнее/скачать</a></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php 
				$num=$num+1;
				if (($num==2) && (!(isset($_SESSION['auth'])))) { ?>
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
		<?php endwhile; ?>
		<div class="clear"></div>
			<?php if (function_exists ('wp_page_numbers')) wp_page_numbers (); else { ?>
				<div class="pagination">
					<div class="newer">
						<?php next_posts_link(__(' Older Entries &raquo;&raquo; ', 'gopiplustheme')) ?>
					</div>
				  	<div class="older">
						<?php previous_posts_link(__(' &laquo;&laquo; Newer Entries ', 'gopiplustheme')) ?>
				  	</div>
				  	<div class="clear"></div>
				</div>
			<?php } ?>
	<?php 
		} else echo '<p>По вашему запросу ничего не найдено</p>';
	?>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>