<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Starter Blog
 *
 */

get_header(); ?>
<div class="content-inner">
	<?php
	do_action( 'starterblog/content/before' );
	if ( ! starterblog_is_e_theme_location( 'archive' ) ) {
		starterblog_blog_posts_heading();
		starterblog_blog_posts();
	}
	do_action( 'starterblog/content/after' );
	?>
</div><!-- #.content-inner -->
<?php
get_footer();
