<?php
/*
Template Name: Register
*/
?>
<?php get_header(); ?>
<div id="content" class="left">
  
	<h1 class="page-title">Реестр сертификатов соответствия</h1>
	<div class="entry">
	  
		<p>Представлен реестр сертификатов ГОСТ Р, Таможенного союза, и Деклараций соответствия Таможенного союза, автоматически создаваемый на основе документов, представленных на данном сайте. При помощи этого реестра можно выполнить проверку сертификатов на подлинность онлайн, путем сравнения реквизитов сертификата с его графическим изображением. Сертификаты также доступны для посмотра и скачивания.</p>
	  
		<div class="register">
			<?php 

            $orderby = $_GET["orderby"];
            $order   = $_GET["order"];

            if (!($orderby=="title" || $orderby=="number")) $orderby = "title";
            if (!($order=="ASC" || $order=="DESC")) $order = "ASC";

			$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
            if ($orderby=="title") 

                $params = array(
    				'posts_per_page' => 20, // количество постов на странице
    				'post_type'      => 'post', // тип постов
    				'paged'          => $current_page, // текущая страница
    				'orderby'		 => $orderby,
    				'order'			 => $order
    			);

            if ($orderby=="number") 
                
                $params = array(
                    'posts_per_page' => 20, // количество постов на странице
                    'post_type'      => 'post', // тип постов
                    'paged'          => $current_page, // текущая страница
                    'orderby'        => 'meta_value',
                    'meta_key'       => 'param1_number',
                    'order'          => $order
                );


			query_posts($params);
			 
			$wp_query->is_archive = true;
			$wp_query->is_home = false;

			if (function_exists ('wp_page_numbers')) wp_page_numbers ();  // функция постраничной навигации

            if (($orderby=="number") && ($order == "ASC")) {
                echo '<div class="td_left tablehead"><a href="/reestr-sertifikatov/?orderby=number&order=DESC">№ сертификата ▼</a></div>';
                echo '<div class="td_middle tablehead"><a href="/reestr-sertifikatov/?orderby=title&order=ASC">Продукция</a></div>';
            }
            if (($orderby=="number") && ($order == "DESC")) {
                echo '<div class="td_left tablehead"><a href="/reestr-sertifikatov/?orderby=number&order=ASC">№ сертификата ▲</a></div>';
                echo '<div class="td_middle tablehead"><a href="/reestr-sertifikatov/?orderby=title&order=ASC">Продукция</a></div>';
            }
            if (($orderby=="title") && ($order == "ASC")) {
                echo '<div class="td_left tablehead"><a href="/reestr-sertifikatov/?orderby=number&order=ASC">№ сертификата</a></div>';
                echo '<div class="td_middle tablehead"><a href="/reestr-sertifikatov/?orderby=title&order=DESC">Продукция ▼</a></div>';
            }
            if (($orderby=="title") && ($order == "DESC")) {
                echo '<div class="td_left tablehead"><a href="/reestr-sertifikatov/?orderby=number&order=ASC">№ сертификата<a></div>';
                echo '<div class="td_middle tablehead"><a href="/reestr-sertifikatov/?orderby=title&order=ASC">Продукция ▲</a></div>';
            }
            ?>

            <div class="td_right tablehead">Действие</div>
            <div class="clear"></div>
			
            <?php
			while(have_posts()): the_post();
				$metadata = get_post_custom($post->ID);
				$number   = $metadata['param1_number'][0];
				$validity = $metadata['param2_validity'][0];
			?>
				<div class="td_left"><?php echo getCountry($number).$number;?></div>

                <?php $manufacturer = getManufacturer(get_post_meta(get_the_ID(), "param6_manufacturer", $single = true), false);
                    if ($manufacturer!='') $manufacturer='<a href="/kompanii/?param='.urlencode($manufacturer).'" title="Другие сертификаты '.$manufacturer.'">'.$manufacturer.'</a>';
                ?>                      
    
				<div class="td_middle"><a href="<?php the_permalink()?>" title="Просмотр сертификата на <?php the_title()?>"><?php echo mb_ucfirst(get_the_title()) ?></a><br><span class="table_man"><?php echo $manufacturer; ?></span></div>
				<div class="td_right"><?php echo $validity;?></div>
				<div class="clear"></div>
			<?php endwhile;
			 
			if (function_exists ('wp_page_numbers')) wp_page_numbers ();  // функция постраничной навигации
			?>
		</div>
	
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>