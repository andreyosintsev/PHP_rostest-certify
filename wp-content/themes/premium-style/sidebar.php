<?php
/**
 * search.php
 *
 * The template sidebar containing the main widget area.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<div id="sidebar" class="right">
	<?php if ( is_active_sidebar('sidebar') ) :  ?>
		<?php dynamic_sidebar('sidebar'); ?>
	<?php endif; ?>

	<div class="clear"></div>
</div>