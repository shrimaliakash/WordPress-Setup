<?php

/**
 * Class StarterBlog_WC_Single_Product
 *
 * Single product settings
 */
class StarterBlog_WC_Single_Product {
	function __construct() {
		add_filter( 'starterblog/customizer/config', array( $this, 'config' ), 100 );
		if ( is_admin() || is_customize_preview() ) {
			add_filter( 'StarterBlog_Control_Args', array( $this, 'add_product_url' ), 35 );
		}

		add_action( 'wp', array( $this, 'single_product_hooks' ) );
	}

	/**
	 * Add more class if nav showing
	 *
	 * @param array $classes HTML classes.
	 *
	 * @return array
	 */
	function post_class( $classes ) {
		if ( StarterBlog()->get_setting( 'wc_single_product_nav_show' ) ) {
			$classes[] = 'nav-in-title';
		}
		return $classes;
	}

	/**
	 * Get adjacent product
	 *
	 * @param bool   $in_same_term In same term.
	 * @param string $excluded_terms Exlclude terms.
	 * @param bool   $previous Previous.
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return null|string|WP_Post
	 */
	public function get_adjacent_product( $in_same_term = false, $excluded_terms = '', $previous = true, $taxonomy = 'product_cat' ) {
		return get_adjacent_post( $in_same_term, $excluded_terms, $previous, $taxonomy );
	}

	/**
	 * Display prev - next button
	 */
	public function product_prev_next() {
		if ( ! StarterBlog()->get_setting( 'wc_single_product_nav_show' ) ) {
			return;
		}
		$prev_post = $this->get_adjacent_product();
		$next_post = $this->get_adjacent_product( false, '', false );
		if ( $prev_post || $next_post ) {
			?>
			<div class="wc-product-nav">
				<?php if ( $prev_post ) { ?>
					<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" title="<?php the_title_attribute( array( 'post' => $prev_post ) ); ?>" class="prev-link">
						<span class="nav-btn nav-next"><?php echo apply_filters( 'starterblog_nav_prev_icon', '' ); ?></span>
						<?php if ( has_post_thumbnail( $prev_post ) ) { ?>
							<span class="nav-thumbnail">
								<?php
								echo get_the_post_thumbnail( $prev_post, 'woocommerce_thumbnail' );
								?>
							</span>
						<?php } ?>
					</a>
				<?php } ?>
				<?php if ( $next_post ) { ?>
					<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" title="<?php the_title_attribute( array( 'post' => $next_post ) ); ?>" class="next-link">
						<span class="nav-btn nav-next">
						<?php echo apply_filters( 'starterblog_nav_next_icon', '' ); ?>
						</span>
						<?php if ( has_post_thumbnail( $next_post ) ) { ?>
							<span class="nav-thumbnail">
								<?php
								echo get_the_post_thumbnail( $next_post, 'woocommerce_thumbnail' );
								?>
							</span>
						<?php } ?>
					</a>
				<?php } ?>
			</div>
			<?php
		}
	}

	/**
	 * Hooks for single product
	 */
	function single_product_hooks() {
		if ( ! is_product() ) {
			return;
		}

		add_action( 'wc_after_single_product_title', array( $this, 'product_prev_next' ), 2 );
		add_filter( 'post_class', array( $this, 'post_class' ) );

		if ( StarterBlog()->get_setting( 'wc_single_product_tab_hide_description' ) ) {
			add_filter( 'woocommerce_product_description_heading', '__return_false', 999 );
		}

		if ( StarterBlog()->get_setting( 'wc_single_product_tab_hide_attr_heading' ) ) {
			add_filter( 'woocommerce_product_additional_information_heading', '__return_false', 999 );
		}

		$tab_type = StarterBlog()->get_setting( 'wc_single_product_tab' );

		if ( 'section' == $tab_type || 'toggle' == $tab_type ) {
			add_filter( 'woocommerce_product_description_heading', '__return_false', 999 );
			add_filter( 'woocommerce_product_additional_information_heading', '__return_false', 999 );
		}

	}

	/**
	 * Add url to customize preview when section open
	 *
	 * @param array $args Args to add.
	 *
	 * @return mixed
	 */
	public function add_product_url( $args ) {

		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 1,
				'orderby'        => 'rand',
			)
		);

		$products = $query->get_posts();
		if ( count( $products ) ) {
			$args['section_urls']['wc_single_product'] = get_permalink( $products[0] );
		}

		return $args;
	}

	/**
	 * Customize config
	 *
	 * @param array $configs Config args.
	 *
	 * @return array
	 */
	public function config( $configs ) {
		$section = 'wc_single_product';

		$configs[] = array(
			'name'     => $section,
			'type'     => 'section',
			'panel'    => 'woocommerce',
			'title'    => __( 'Single Product Page', 'starter-blog' ),
			'priority' => 19,
		);

		$configs[] = array(
			'name'    => 'wc_single_layout_h',
			'type'    => 'heading',
			'section' => $section,
			'label'   => __( 'Layout', 'starter-blog' ),
		);

		/*
		$configs[] = array(
			'name'    => 'wc_single_layout',
			'type'    => 'select',
			'section' => $section,
			'default' => 'default',
			'label'   => __( 'Layout', 'starter-blog' ),
			'choices' => array(
				'default'    => __( 'Default', 'starter-blog' ),
				'top-medium' => __( 'Top Gallery Boxed', 'starter-blog' ),
				'top-full'   => __( 'Top Gallery Full Width', 'starter-blog' ),
				'left-grid'  => __( 'Left Gallery Grid', 'starter-blog' ),
			)
		);
		*/

		$configs[] = array(
			'name'             => 'wc_single_layout',
			'type'             => 'image_select',
			'section'          => $section,
			'title'            => __( 'Layout', 'starter-blog' ),
			'default'          => 'default',

			'disabled_msg'     => __( 'This option is available in Starter Blog Pro plugin only.', 'starter-blog' ),
			'disabled_pro_msg' => __( 'Please activate module Single Product Layouts to use this layout.', 'starter-blog' ),

			'choices'          => array(
				'default'    => array(
					'img'   => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/wc-layout-default.svg',
					'label' => __( 'Default', 'starter-blog' ),
				),
				'top-medium' => array(
					'img'     => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/wc-layout-top-medium.svg',
					'label'   => __( 'Top Gallery Boxed', 'starter-blog' ),
					'disable' => 1,
					'bubble'  => __( 'Pro', 'starter-blog' ),
				),
				'top-full'   => array(
					'img'     => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/wc-layout-top-full.svg',
					'label'   => __( 'Top Gallery Full Width', 'starter-blog' ),
					'disable' => 1,
					'bubble'  => __( 'Pro', 'starter-blog' ),
				),
				'left-grid'  => array(
					'img'     => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/wc-layout-left-grid.svg',
					'label'   => __( 'Left Gallery Grid', 'starter-blog' ),
					'disable' => 1,
					'bubble'  => __( 'Pro', 'starter-blog' ),

				),
			),
		);

		$configs[] = array(
			'name'     => "{$section}_nav_heading",
			'type'     => 'heading',
			'section'  => $section,
			'title'    => __( 'Product Navigation', 'starter-blog' ),
			'priority' => 39,
		);

		$configs[] = array(
			'name'           => "{$section}_nav_show",
			'type'           => 'checkbox',
			'default'        => 1,
			'section'        => $section,
			'checkbox_label' => __( 'Show Product Navigation', 'starter-blog' ),
			'priority'       => 39,
		);

		$configs[] = array(
			'name'     => "{$section}_tab_heading",
			'type'     => 'heading',
			'section'  => $section,
			'title'    => __( 'Product Tabs', 'starter-blog' ),
			'priority' => 40,
		);

		$configs[] = array(
			'name'     => "{$section}_tab",
			'type'     => 'select',
			'default'  => 'horizontal',
			'section'  => $section,
			'label'    => __( 'Tab Layout', 'starter-blog' ),
			'choices'  => array(
				'horizontal' => __( 'Horizontal', 'starter-blog' ),
				'vertical'   => __( 'Vertical', 'starter-blog' ),
				'toggle'     => __( 'Toggle', 'starter-blog' ),
				'sections'   => __( 'Sections', 'starter-blog' ),
			),
			'priority' => 45,
		);

		$configs[] = array(
			'name'           => "{$section}_tab_hide_description",
			'type'           => 'checkbox',
			'default'        => 1,
			'section'        => $section,
			'checkbox_label' => __( 'Hide product description heading', 'starter-blog' ),
			'priority'       => 46,
		);

		$configs[] = array(
			'name'           => "{$section}_tab_hide_attr_heading",
			'type'           => 'checkbox',
			'default'        => 1,
			'section'        => $section,
			'checkbox_label' => __( 'Hide product additional information heading', 'starter-blog' ),
			'priority'       => 47,
		);

		$configs[] = array(
			'name'           => "{$section}_tab_hide_review_heading",
			'type'           => 'checkbox',
			'default'        => 0,
			'section'        => $section,
			'checkbox_label' => __( 'Hide product review heading', 'starter-blog' ),
			'selector'       => '.woocommerce-Reviews-title',
			'css_format'     => 'display: none;',
			'priority'       => 48,
		);

		$configs[] = array(
			'name'     => "{$section}_upsell_heading",
			'type'     => 'heading',
			'section'  => $section,
			'title'    => __( 'Upsell Products', 'starter-blog' ),
			'priority' => 60,
		);

		$configs[] = array(
			'name'     => "{$section}_upsell_number",
			'type'     => 'text',
			'default'  => 3,
			'section'  => $section,
			'label'    => __( 'Number of upsell products', 'starter-blog' ),
			'priority' => 65,
		);

		$configs[] = array(
			'name'            => "{$section}_upsell_columns",
			'type'            => 'text',
			'device_settings' => true,
			'section'         => $section,
			'label'           => __( 'Upsell products per row', 'starter-blog' ),
			'priority'        => 66,
		);

		$configs[] = array(
			'name'     => "{$section}_related_heading",
			'type'     => 'heading',
			'section'  => $section,
			'title'    => __( 'Related Products', 'starter-blog' ),
			'priority' => 70,
		);

		$configs[] = array(
			'name'     => "{$section}_related_number",
			'type'     => 'text',
			'default'  => 3,
			'section'  => $section,
			'label'    => __( 'Number of related products', 'starter-blog' ),
			'priority' => 75,
		);

		$configs[] = array(
			'name'            => "{$section}_related_columns",
			'type'            => 'text',
			'device_settings' => true,
			'section'         => $section,
			'label'           => __( 'Related products per row', 'starter-blog' ),
			'priority'        => 76,
		);

		$configs[] = array(
			'name'           => 'wc_single_layout_breadcrumb',
			'type'           => 'checkbox',
			'section'        => $section,
			'default'        => 1,
			'checkbox_label' => __( 'Show shop breadcrumb', 'starter-blog' ),
		);

		return $configs;
	}
}

new StarterBlog_WC_Single_Product();
