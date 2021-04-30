<?php

class StarterBlog_WC_Colors {
	function __construct() {
		add_filter( 'starterblog/customizer/config', array( $this, 'config' ), 100 );
	}

	function config( $configs ) {
		$section = 'global_styling';

		$configs[] = array(
			'name'    => "{$section}_shop_colors_heading",
			'type'    => 'heading',
			'section' => $section,
			'title'   => __( 'Shop Colors', 'starter-blog' ),
		);

		$configs[] = array(
			'name'        => "{$section}_shop_primary",
			'type'        => 'color',
			'section'     => $section,
			'title'       => __( 'Shop Buttons', 'starter-blog' ),
			'placeholder' => '#0084ff',
			'description' => __( 'Color for add to cart, checkout buttons. Default is Secondary Color.', 'starter-blog' ),
			'css_format'  => apply_filters(
				'starterblog/styling/shop-buttons',
				'
					.woocommerce .button.add_to_cart_button, 
					.woocommerce .button.alt,
					.woocommerce .button.added_to_cart, 
					.woocommerce .button.checkout, 
					.woocommerce .button.product_type_variable,
					.item--wc_cart .cart-icon .cart-qty .starterblog-wc-total-qty
					{
					    background-color: {{value}};
					}'
			),
			'selector'    => 'format',
		);

		$configs[] = array(
			'name'        => "{$section}_shop_rating_stars",
			'type'        => 'color',
			'section'     => $section,
			'title'       => __( 'Rating Stars', 'starter-blog' ),
			'description' => __( 'Color for rating stars, default is Secondary Color.', 'starter-blog' ),
			'placeholder' => '#0084ff',
			'css_format'  => apply_filters(
				'starterblog/styling/shop-rating-stars',
				'
					.comment-form-rating a, 
					.star-rating,
					.comment-form-rating a:hover, 
					.comment-form-rating a:focus, 
					.star-rating:hover, 
					.star-rating:focus
					{
					    color: {{value}};
					}'
			),
			'selector'    => 'format',
		);

		$configs[] = array(
			'name'        => "{$section}_shop_onsale",
			'type'        => 'color',
			'section'     => $section,
			'title'       => __( 'On Sale', 'starter-blog' ),
			'placeholder' => '#77a464',
			'css_format'  => apply_filters(
				'starterblog/styling/shop-onsale',
				'
					span.onsale
					{
					    background-color: {{value}};
					}'
			),
			'selector'    => 'format',
		);

		return $configs;
	}
}

new StarterBlog_WC_Colors();
