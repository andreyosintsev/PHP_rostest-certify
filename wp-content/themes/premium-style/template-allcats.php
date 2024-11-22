<?php
/*
Template Name: All cats
*/
?>
<?php get_header(); ?>
<?php if (isset($_GET['param'])) $metavalue=$_GET['param']; else $metavalue='okp';?>

<div id="content" class="left">
  
	<h1 class="page-title">Поиск сертификатов соответствия по виду продукции</h1>
	<div class="entry">


		<ul>
			<li><a href="?param=okp" title="Найти сертификаты по коду ОКП">Сертификаты по коду ОКП (ОК 005)</a></li>
			<li><a href="?param=tnvedts" title="Найти сертификаты по коду ТН ВЭД ТС">Сертификаты по коду ТН ВЭД ТС</a></li>
		</ul>
		
		<?php
			if ($metavalue=='okp') {
                $par = 37;
        ?>
				<h2 style="margin-top: 30px; margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #ddd;">Сертификаты соответствия по коду ОКП (ОК 005)</h2>
				<p>Представлены сертификаты соответствия на продукцию по коду ОКП (ОК 005) - общероссийского классификатора продукции.</p>
				<p style="margin-bottom: 30px">Сертификаты отсортированы по первым двум цифрам кода ОКП.</p>
                <div id="slider">
		<?php
			} else {
                $par = 38;
        ?>
				<h2 style="margin-top: 30px; margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #ddd;">Сертификаты соответствия по коду ТН ВЭД ТС</h2>
				<p>Представлены сертификаты соответствия на продукцию по коду ТН ВЭД ТС - Единой Товарной номенклатуры внешнеэкономической деятельности Евразийского экономического союза.</p>
				<p style="margin-bottom: 30px">Сертификаты отсортированы по первым двум цифрам кода ТН ВЭД ТС.</p>

				<div id="slider2">
        <?php }

			$args = array(
				'parent'					=> $par,
				'child_of'					=> 0,
				'hide_empty'				=> 0,
				'number'					=> 0,
				'taxonomy'					=> 'category',
				'pad_counts'				=> true,
				'exclude'					=> 1
			);
	 
			$catlist = get_categories($args);
			
			foreach ($catlist as $categories_item) {
				echo '<div class="h2_ wrapped" id="'.$categories_item->term_id.'-sheader">' . $categories_item->cat_name . '<br><span>' . $categories_item->category_description . '</span></div>';
				/*посмотрим вложенные категории*/
			
				$args = array(
					'parent'					=> $categories_item->cat_ID,
					'child_of'					=> 0,
					'hide_empty'				=> 0,
					'number'					=> 0,
					'taxonomy'					=> 'category',
					'pad_counts'				=> true,
					'exclude'					=> 1
				);		
			
				$catlist2 = get_categories($args);
				echo '<div class="scontent" id="'.$categories_item->term_id.'-scontent"><ul>';
				foreach ($catlist2 as $categories_item2) {
					echo '<li><a href="'.get_category_link($categories_item2->term_id).'">' . $categories_item2->cat_name . '</a><br><span>' . $categories_item2->category_description . '</span></li>';
				}
					
				/*закончили смотреть*/
			
				echo '</ul>'."\r\n";
				echo '</div>'."\r\n";
			}
			?>
			</div>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
