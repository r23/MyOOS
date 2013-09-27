<?php
/**
 * The template for displaying search forms in myoos
 *
 * @package myoos
 */
?>
<div class="widget">
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>
			<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'myoos' ); ?></span>
			<input type="search" class="input-medium search-query" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'myoos' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'myoos' ); ?>">
		</label>
		<button type="submit" class="btn btn-square btn-theme"><?php echo esc_attr_x( 'Search', 'submit button', 'myoos' ); ?></button>
	</form>
</div>
