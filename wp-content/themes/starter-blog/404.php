<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Starter Blog
 *
 */

get_header(); ?>

<div class="content-inner">
	<?php
	if ( ! starterblog_is_e_theme_location( 'single' ) ) {
		?>
		<section class="error-404 not-found">
			<?php if ( starterblog_is_post_title_display() ) { ?>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'starter-blog' ); ?></h1>
				</header><!-- .page-header -->
			<?php } ?>
			<div class="page-content widget-area">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'starter-blog' ); ?></p>
				<div class="widget">
					<?php get_search_form(); ?>
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->
		<?php
	}
	?>
</div><!-- #.content-inner -->
<?php
get_footer();
