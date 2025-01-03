<?php
/**
 * 404.php
 *
 * The template for displaying 404 pages (Page Not Found).
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php get_header();?>
<div id="content" class="left">
	<div id="breadcrumbs"> 
		<a href="<?php echo home_url(); ?>"><?php _e('Home','gopiplustheme') ?></a>
		&raquo;&raquo;
		<?php _e('404 Not found','gopiplustheme') ?>
	</div>
	<!-- start post content -->
	<div id="post-404" class="hentry post error404 not-found">
		<h1 class="page-title">
			<?php _e('404! We couldn\'t find the page!','gopiplustheme') ?>
		</h1>
		<div class="entry entry-content">
		<p>
			<?php _e('The page you\'ve requested <strong>can not be displayed</strong>. We are working hard to fix all the missing resources.','gopiplustheme') ?>
		</p>
		<div class="search_by_name">
			<form method="get" id="searchform" action="/">
				<div class="searchform_submit"><input id="searchsubmit" type="submit" value="Поиск"/></div>
				<div class="searchform_text"><input type="text" class="input-text" name="s" id="s"  value="Поиск по названию продукции" onfocus="if (this.value == 'Поиск по названию продукции') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Поиск по названию продукции';}"/></div>
			</form>
			<p class="search_ex">Например, <a href="/?s=%D0%BE%D0%B4%D0%B5%D0%B6%D0%B4%D0%B0+%D0%BC%D1%83%D0%B6%D1%81%D0%BA%D0%B0%D1%8F">одежда мужская</a></p>
		</div>
		
		</div>
	</div>
	<!-- start related posts box -->
	<div class="related-posts">
		<h1>Последние добавленные сертификаты</h1>
		<div class="clear"></div>		
		<ul>
			<?php
			$posts = get_posts('numberposts=5');
			foreach($posts as $post) { ?>
				<div class="arch">
					<div class="arch_thumbnails">
						<?php echo thumbnails(false, true, 75, 107, $post); ?>
					</div>
					<div class="arch_description">
						<h2 class="entry-title arch_title"><a href="<?php the_permalink() ?>"  title="Сертификат на <?php echo get_the_title(); ?>"><?php echo mb_ucfirst(get_the_title()); ?></a></h2>
	                    <div class="arch_manufacturer">
	                        <?php 
	                        	$manufacturer = getCompletedName(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
	                            if ($manufacturer!='') echo '<a href="http://rostest-certify.ru/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
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
			<?php }?>
			<?php wp_reset_query(); ?>
		</ul>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
