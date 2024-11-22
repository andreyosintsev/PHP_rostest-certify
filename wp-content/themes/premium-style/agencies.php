<?php
/*
Template Name: Cert Agencies All
*/
?>
<?php get_header(); ?>

<div id="content" class="left">
	<div class="post">
		<h1 class="page-title">Все органы по сертификации</h1>
        <ul style="margin-top:20px;">
            <?php
                $num = 0;
                $agencies = getAllAgenciesNames(1000);
                $agencies = array_unique($agencies);
                asort($agencies);
                foreach ($agencies as $regnum=>$agency) {
                    echo '<p>'.$agency.'</p>';
                    ++$num;
                };
                echo '<br/>Всего <b>'.$num.'</b> организаций';
            ?>
        </ul>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>