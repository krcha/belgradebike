<?php
/**
 * Bundled Item Title.
 * @version 4.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( $title === '' ) return;

?>
<h2 class="bundled_product_title product_title">
	<?php
		echo $title . ( ( $quantity > 1 ) ? ' &times; '. $quantity : '' );
	?>
</h2>
