<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Starter Blog
 */

get_header(); ?>
	<div class="content-inner">
		<?php
		do_action( 'starterblog/content/before' );
		starterblog_blog_posts_heading();
		starterblog_blog_posts(
			array(
				'_overwrite' => array(
					'media_hide'     => 1,
					'excerpt_type'   => StarterBlog()->get_setting( 'search_results_excerpt_type' ),
					'excerpt_length' => StarterBlog()->get_setting( 'search_results_excerpt_length' ),
					'excerpt_more'   => StarterBlog()->get_setting( 'search_results_excerpt_more' ),
				),
			)
		);
		do_action( 'starterblog/content/after' );
		?>
	</div><!-- #.content-inner -->
<?php
get_footer();
