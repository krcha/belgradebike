<?php
/**
 * Template for displaying search forms in coup
 * code taken from Twenty Seventeen
 *
 * @package WordPress
 * @subpackage coup
 * @since 1.0
 * @version 1.0
 */
?>

<?php $unique_id =  uniqid( 'search-form-' ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr($unique_id); ?>">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'coup-shop' ); ?></span>
	</label>
	<input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'coup-shop' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<input type="submit" class="search-submit" value="<?php echo esc_html__( 'Search', 'coup-shop' ); ?>" />
</form>
