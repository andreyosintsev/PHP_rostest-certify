<?php
/*
Template Name: Manufacturers All
*/
?>
<?php get_header(); ?>
<div id="content" class="left">
	<div class="post">
		<h1 class="page-title">Все организации-изготовители</h1>
        <ul style="margin-top:20px;">
            <?php
            	global $wpdb;
            	$rec = $wpdb->get_col("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'param6_manufacturer'");

				$companies = array();
				$num = 0;
            	
           		foreach($rec as $r) { 
           			$manufacturer_clean = getManufacturer($r, false);
					if ($manufacturer_clean!== '')	array_push($companies, $manufacturer_clean);
				}

				asort($companies);
				$companies = array_unique($companies);

				echo '<ol>'."\r\n";
				foreach($companies as $company) { 
					++$num;
					echo '<li>'.$num.'. '.$company.'</li>'."\r\n";;
				}
				echo '</ol>'."\r\n";;

                /*$num = 1;
                $companies = getAllCompanies();
                //$companies = array_unique($agencies);
                //asort($companies);
                echo '<ol>';
                foreach ($companies as $comp_name=>$comp_num) {
                    echo '<li>'.$num.'. '.$comp_name.'</li>';
                    ++$num;
                };
                echo '</ol>';
                */
                echo '<br/>Всего <b>'.$num.'</b> организаций-изготовителей';
            ?>
        </ul>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>