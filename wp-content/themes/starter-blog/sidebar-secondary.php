<?php
/**
 * The secondary sidebar for 3 columns layout.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Starter Blog
 */

$sidebar_id = apply_filters( 'starterblog/sidebar-id', 'sidebar-2', 'secondary' );
if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}
?>
<aside id="sidebar-secondary" <?php starterblog_sidebar_secondary_class(); ?>>
	<div class="sidebar-secondary-inner sidebar-inner widget-area">
		<?php
		do_action( 'starterblog/sidebar-secondary/before' );
		dynamic_sidebar( $sidebar_id );
		do_action( 'starterblog/sidebar-secondary/after' );
		?>
	</div>
</aside><!-- #sidebar-secondary -->
