<?php
/**
 * searchform.php
 *
 * The template for displaying search form.
 *
 * @link        http://www.gopiplus.com/
 *
 * @author      www.gopiplus.com
 * @copyright   Copyright (c) 2013 www.gopiplus.com
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="input-text" name="s" id="s"  value="<?php _e('Search this site...','gopiplustheme'); ?>" onfocus="if (this.value == '<?php _e('Search this site...','gopiplustheme'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search this site...','gopiplustheme'); ?>';}" />
	<input id="searchsubmit" type="submit" value="Поиск" />
</form>