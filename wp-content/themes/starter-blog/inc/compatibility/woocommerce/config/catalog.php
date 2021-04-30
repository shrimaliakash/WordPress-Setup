<?php

class StarterBlog_WC_Products {
	function __construct() {
		add_filter( 'starterblog/customizer/config', array( $this, 'config' ), 100 );
	}

	function config( $configs ) {
		$section = 'woocommerce_product_catalog';

		$configs[] = array(
			'name'    => 'woocommerce_catalog_tablet_columns',
			'type'    => 'text',
			'section' => $section,
			'label'   => __( 'Products per row on tablet', 'starter-blog' ),
		);
		$configs[] = array(
			'name'    => 'woocommerce_catalog_mobile_columns',
			'type'    => 'text',
			'section' => $section,
			'default' => 1,
			'label'   => __( 'Products per row on mobile', 'starter-blog' ),
		);

		return $configs;
	}
}

new StarterBlog_WC_Products();
