<?php
/**
 * single.php
 *
 * The Template for displaying all single posts.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<?php get_header(); ?>

<div id="content" class="left">
  <?php if(false) get_template_part( 'breadcrumbs', get_post_format() ); ?>
  <?php the_post(); ?>

<?php 
//Если пользователь залогинен, запишем просмотр сертификата в wp_userhistory
	if ($_SESSION['auth']) {
		date_default_timezone_set('Europe/Samara');
		$wpdb->insert('wp_userhistory', array( 'user' => $_SESSION['login'], 'post_id' => get_the_ID(), 'lasttime'=>date('Y-m-d H:i:s'), 'downloaded' => 0), array( '%s', '%s', '%s', '%d' ));
	}
?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<div itemscope="" itemtype="http://schema.org/Product">
	    	<!-- start post entry -->
			<h1 itemprop="name" class="entry-title">Сертификат на <?php echo get_the_title(); ?></h1>
		    <div class="arch_manufacturer">
		        <?php $manufacturer = getManufacturer(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
		                if ($manufacturer!='') echo '<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
		        ?>                      
		    </div>
		    <div class="entry-meta"> 
				<span class="left">
					<span class="meta-date">
						<abbr class="published" title="<?php the_date('j F Y', 'Добавлен ', ' года'); ?>">
							№ <?php echo get_post_meta($id, "param1_number", $single = true)?>
						</abbr>
					</span> 
				</span> 
		      <div class="clear"></div>
		    </div>
		    <!-- start post content -->
		    <div class="entry entry-content">
				<?php the_content(); ?>
				<div class="thumbnails">
					<?php echo thumbnails(false, false, null, null, $post); ?>
					<div class="clear"></div>
				</div>

				<div class="clear" style="padding-bottom: 20px;"></div>
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

				<div class="specs">
					<?php echo getSpecs(); ?>
				</div>
				<div class="download">
					<div class="pre_download">Скачать в хорошем качестве <span class=""><?php echo getImageResolution(site_url().'/download/'.get_post_meta($post->ID, "img_download_link", $single = true));?></span>:</div>
					<?php echo download_button(); ?>
				</div>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'gopiplustheme' ), 'after' => '</div>' ) ); ?>
				<div>
		            <?php related_posts(); ?>
		        </div>
		    </div>
		    <!-- start post tags box 
		    <div class="star_rating"-->
		        <?php if (false) echo kk_star_ratings();?>
		    <!--/div-->
			<div class="entry-tags">
				<div class="tags">
					<?php the_tags('',' '); ?>
				</div>
			</div>
		    <div class="clear"></div>
			<nav class="nav-single">
		      <span class="nav-previous">
		      	<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&laquo;&laquo;', 'Previous post link', 'gopiplustheme' ) . '</span> %title' ); ?>
		      </span> 
			  <span class="nav-next">
		      	<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&raquo;&raquo;', 'Next post link', 'gopiplustheme' ) . '</span>' ); ?>
		      </span> 
			</nav>
			<!-- end post entry -->
			<div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
   				<meta itemprop="ratingValue" content="5">
   				<meta itemprop="reviewCount" content="1">
			</div>
			<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer"> 
				<meta itemprop="price" content="0">
				<meta itemprop="priceCurrency" content="RUB">
				<link itemprop="availability" href="http://schema.org/InStock">
			</div>
		</div>
  	</div>
  <div class="clear"></div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>