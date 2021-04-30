<?php
if ( is_admin() || is_customize_preview() ) {

	add_filter( 'starterblog/customizer/config', 'starterblog_pro_upsell', 9999 );

	function starterblog_pro_upsell( $configs ) {

		if ( class_exists( 'StarterBlog_Pro' ) ) {
			return $configs;
		}

		$configs[] = array(
			'name'          => 'starterblog-pro',
			'type'          => 'section',
			'section_class' => 'StarterBlog_WP_Customize_Section_Pro',
			'priority'      => 0,
			'pro_text'      => __( 'More features! Try Starter Blog Pro!', 'starter-blog' ),
			'pro_url'       => 'https://fancywp.com/downloads/starter-blog-pro/?utm_source=theme_dashboard&utm_medium=links&utm_campaign=customizer_top',
		);

		$configs[] = array(
			'name'          => 'header_settings_pro',
			'panel'         => 'header_settings',
			'type'          => 'section',
			'section_class' => 'StarterBlog_WP_Customize_Section_Pro',
			'priority'      => 99999,
			'title'         => __( 'Header options in Starter Blog Pro', 'starter-blog' ),
			'teaser'        => true,
			'pro_url'       => 'https://fancywp.com/downloads/starter-blog-pro/?utm_source=theme_dashboard&utm_medium=links&utm_campaign=customizer_header_side',
			'features'      => array(
				__( 'Header Sticky', 'starter-blog' ),
				__( 'Header Transparent', 'starter-blog' ),
				__( 'More HTML Items', 'starter-blog' ),
				__( 'Secondary Menu', 'starter-blog' ),
				__( 'Icon Box', 'starter-blog' ),
				__( 'Contact Info', 'starter-blog' ),
				__( 'And more header settings', 'starter-blog' ),
			),
		);

		$configs[] = array(
			'name'          => 'footer_settings_pro',
			'panel'         => 'footer_settings',
			'type'          => 'section',
			'priority'      => 99999,
			'section_class' => 'StarterBlog_WP_Customize_Section_Pro',
			'title'         => __( 'More Footer options in Starter Blog Pro', 'starter-blog' ),
			'pro_url'       => 'https://fancywp.com/downloads/starter-blog-pro/?utm_source=theme_dashboard&utm_medium=links&utm_campaign=customizer_footer_side',
			'teaser'        => true,
			'features'      => array(
				__( 'Footer Top Row', 'starter-blog' ),
				__( 'Horizontal Menu Item', 'starter-blog' ),
				__( 'More HTML Items', 'starter-blog' ),
				__( 'Icon Box Item', 'starter-blog' ),
				__( 'Contact Info Item', 'starter-blog' ),
				__( 'Payment Methods Item', 'starter-blog' ),
			),
		);

		return $configs;
	}
}
