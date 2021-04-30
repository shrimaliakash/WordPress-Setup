<?php
/**
 * StarterBlog builder init
 *
 * @since 1.5.0
 * @package Starter Blog
 */
class StarterBlog_Panel_Builder {
	public function __construct() {
		$this->includes();
		$this->init();
	}

	public function includes() {
		$path = get_template_directory();
		require_once $path . '/inc/panel-builder/class-abstract-layout-frontend.php';
		require_once $path . '/inc/panel-builder/class-builder-panel.php';
		require_once $path . '/inc/panel-builder/class-layout-builder.php';
		require_once $path . '/inc/panel-builder/class-layout-builder-frontend.php';
		require_once $path . '/inc/panel-builder/class-layout-builder-frontend-v2.php';
		require_once $path . '/inc/panel-builder/builder-functions.php';
	}

	private function init() {
		/**
		 * Initial Layout Builder
		 */
		StarterBlog_Customize_Layout_Builder()->init();

		/**
		 * Add Header Content To Frontend
		 */
		add_action( 'starterblog/site-start', 'starterblog_customize_render_header' );
		/**
		 * Add Footer Content To Frontend
		 */
		add_action( 'starterblog/site-end', 'starterblog_customize_render_footer' );
	}

}

new StarterBlog_Panel_Builder();

