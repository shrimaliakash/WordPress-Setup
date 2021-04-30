<?php

/**
 * WooCommerce Cart config
 *
 * Class StarterBlog_WC_Cart.
 *
 * @since 1.5.0
 */
class StarterBlog_WC_Cart {
	public function __construct() {
		add_filter( 'starterblog/customizer/config', array( $this, 'config' ), 100 );
		if ( is_admin() || is_customize_preview() ) {
			add_filter( 'StarterBlog_Control_Args', array( $this, 'add_cart_url' ), 35 );
		}

		add_action( 'wp', array( $this, 'cart_hooks' ) );
	}

	public function cart_hooks() {
		if ( ! is_cart() ) {
			return;
		}

		$hide_cross_sell = StarterBlog()->get_setting( 'wc_cart_page_hide_cross_sells' );
		if ( $hide_cross_sell ) {
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			remove_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );
		}

	}

	public function add_cart_url( $args ) {
		$args['section_urls']['wc_cart_page'] = get_permalink( wc_get_page_id( 'cart' ) );

		return $args;
	}

	public function config( $configs ) {
		$section = 'wc_cart_page';

		$configs[] = array(
			'name'  => $section,
			'type'  => 'section',
			'panel' => 'woocommerce',
			'title' => __( 'Cart', 'starter-blog' ),
		);

		$configs[] = array(
			'name'           => "{$section}_hide_cross_sells",
			'type'           => 'checkbox',
			'default'        => 1,
			'section'        => $section,
			'checkbox_label' => __( 'Hide cross-sells', 'starter-blog' ),
		);

		return $configs;
	}
}

new StarterBlog_WC_Cart();
