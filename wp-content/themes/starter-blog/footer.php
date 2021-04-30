<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Starter Blog
 *
 */

?>              <?php do_action( 'starterblog/main/after' ); ?>
			</main><!-- #main -->
			<?php do_action( 'starterblog/sidebars' ); ?>
		</div><!-- #.starterblog-grid -->
	</div><!-- #.starterblog-container -->
</div><!-- #content -->
<?php
/**
 * Hook before site content
 *
 * @since 1.5.0
 */
do_action( 'starterblog/after-site-content' );

do_action( 'starterblog/site-end/before' );
if ( ! starterblog_is_e_theme_location( 'footer' ) ) {
	/**
	 * Site end
	 *
	 * @hooked starterblog_customize_render_footer - 10
	 *
	 * @see starterblog_customize_render_footer
	 */
	do_action( 'starterblog/site-end' );
}
do_action( 'starterblog/site-end/after' );

?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
