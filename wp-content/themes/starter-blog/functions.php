<?php
/**
 * StarterBlog functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 *
 * @package Starter Blog
 *
 */

/**
 *  Same the hook `the_content`
 *
 * @TODO: do not effect content by plugins
 *
 * 8 WP_Embed:run_shortcode
 * 8 WP_Embed:autoembed
 * 10 wptexturize
 * 10 wpautop
 * 10 shortcode_unautop
 * 10 prepend_attachment
 * 10 wp_filter_content_tags
 * 11 capital_P_dangit
 * 11 do_shortcode
 * 20 convert_smilies
 */
global $wp_embed;
add_filter( 'starterblog_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'starterblog_the_content', array( $wp_embed, 'autoembed' ), 8 );
add_filter( 'starterblog_the_content', 'wptexturize' );
add_filter( 'starterblog_the_content', 'wpautop' );
add_filter( 'starterblog_the_content', 'shortcode_unautop' );
add_filter( 'starterblog_the_content', 'wp_filter_content_tags' );
add_filter( 'starterblog_the_content', 'capital_P_dangit' );
add_filter( 'starterblog_the_content', 'do_shortcode' );
add_filter( 'starterblog_the_content', 'convert_smilies' );


/**
 *  Same the hook `the_content` but not auto P
 *
 * @TODO: do not effect content by plugins
 *
 * 8 WP_Embed:run_shortcode
 * 8 WP_Embed:autoembed
 * 10 wptexturize
 * 10 shortcode_unautop
 * 10 prepend_attachment
 * 10 wp_filter_content_tags
 * 11 capital_P_dangit
 * 11 do_shortcode
 * 20 convert_smilies
 */
add_filter( 'starterblog_the_title', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'starterblog_the_title', array( $wp_embed, 'autoembed' ), 8 );
add_filter( 'starterblog_the_title', 'wptexturize' );
add_filter( 'starterblog_the_title', 'shortcode_unautop' );
add_filter( 'starterblog_the_title', 'wp_filter_content_tags' );
add_filter( 'starterblog_the_title', 'capital_P_dangit' );
add_filter( 'starterblog_the_title', 'do_shortcode' );
add_filter( 'starterblog_the_title', 'convert_smilies' );

// Include the main StarterBlog class.
require_once get_template_directory() . '/inc/class-starterblog.php';

/**
 * Main instance of StarterBlog.
 *
 * Returns the main instance of StarterBlog.
 *
 * @return StarterBlog
 */
function StarterBlog() {
	// phpc:ignore WordPress.NamingConventions.ValidFunctionName.
	return StarterBlog::get_instance();
}

StarterBlog();
