<?php

class StarterBlog_Breadcrumb {
	public static $is_transparent = null;
	public static $_instance = null;
	public static $_settings = null;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			add_filter( 'starterblog/customizer/config', array( self::$_instance, 'config' ) );
			add_filter( 'wpseo_breadcrumb_separator', '__return_null' );
			add_filter( 'wpseo_breadcrumb_single_link', array( self::$_instance, 'yoat_seo_link_link' ) );
			if ( ! is_admin() ) {
				add_action( 'wp_head', array( self::$_instance, 'display' ) );
			}
		}

		return self::$_instance;
	}

	function yoat_seo_link_link( $link ) {
		return '<li>' . $link . '</li>';
	}

	public function display() {
		// Display position.
		$display_pos = StarterBlog()->get_setting( 'breadcrumb_display_pos' );
		switch ( $display_pos ) {
			case 'after_header': // Below header.
				if ( function_exists( 'StarterBlog_Pro' ) && StarterBlog_Pro()->is_enabled_module( 'StarterBlog_Pro_Module_Header_Transparent' ) ) {
					if ( has_action( 'starterblog/page-cover/before' ) ) {
						add_action( 'starterblog/page-cover/before', array( self::$_instance, 'render' ), 10 );
					} else {
						add_action( 'starterblog/site-start', array( self::$_instance, 'render' ), 10 );
					}
				} else {
					add_action( 'starterblog/site-start', array( self::$_instance, 'render' ), 15 );
				}
				break;
			case 'before_content':
				add_action( 'starterblog/site-start', array( self::$_instance, 'render' ), 65 );
				break;
			case 'inside':
				add_action( 'starterblog/page-cover/after', array( self::$_instance, 'render' ), 55 );
				add_action( 'starterblog/titlebar/after', array( self::$_instance, 'render' ), 55 );
				break;
			default:
				add_action( 'starterblog/site-start', array( self::$_instance, 'render' ), 55 );
				break;
		}
	}

	/**
	 * Check Support plugin activate
	 *
	 * Current support plugin: breadcrumb-navxt
	 *
	 * @return bool
	 */
	public function support_plugins_active() {
		$activated = false;
		if ( function_exists( 'bcn_display' ) ) {
			$activated = true;
		}

		if ( ! $activated && defined( 'WPSEO_FILE' ) ) {
			$options = get_option( 'wpseo_titles' );
			if ( is_array( $options ) && isset( $options['breadcrumbs-enable'] ) && $options['breadcrumbs-enable'] ) {
				$activated = true;
			}
		}

		return $activated;
	}

	public function config( $configs ) {
		$section  = 'breadcrumb';
		$selector = '#page-breadcrumb';
		$panel    = 'compatibility_panel';
		$config   = array();
		$config[] = array(
			'name'        => $section,
			'type'        => 'section',
			'panel'       => $panel,
			'title'       => __( 'Breadcrumb', 'starter-blog' ),
			'description' => '',
		);

		if ( ! $this->support_plugins_active() ) {
			$desc     = __( 'Starter Blog theme support <a target="_blank" href="https://wordpress.org/plugins/breadcrumb-navxt/">Breadcrumb NavXT</a> or <a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank">Yoast SEO</a> breadcrumb plugin. All settings will be displayed after you installed and activated it.', 'starter-blog' );
			$config[] = array(
				'name'        => "{$section}_display_pos",
				'type'        => 'custom_html',
				'section'     => $section,
				'description' => $desc,
			);

		} else {

			$config[] = array(
				'name'    => "{$section}_display_pos",
				'type'    => 'select',
				'section' => $section,
				'default' => 'below_titlebar',
				'title'   => __( 'Display Position', 'starter-blog' ),
				'choices' => apply_filters(
					'starterblog/breadcrumb/config/positions',
					array(
						'after_header'   => __( 'After header', 'starter-blog' ),
						'inside'         => __( 'Inside cover/titlebar', 'starter-blog' ),
						'before_content' => __( 'Before site main', 'starter-blog' ),
					)
				),
			);

			$display_fields = array(
				array(
					'name'           => 'index',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on index', 'starter-blog' ),
				),
				array(
					'name'           => 'category',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on categories', 'starter-blog' ),
				),
				array(
					'name'           => 'search',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on search', 'starter-blog' ),
				),
				array(
					'name'           => 'archive',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on archive', 'starter-blog' ),
				),
				array(
					'name'           => 'page',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on single page', 'starter-blog' ),
				),
				array(
					'name'           => 'post',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on single post', 'starter-blog' ),
				),
				array(
					'name'           => 'singular',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on singular', 'starter-blog' ),
				),
				array(
					'name'           => 'page_404',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on 404 page', 'starter-blog' ),
				),

			);

			if ( StarterBlog()->is_woocommerce_active() ) {
				$display_fields[] = array(
					'name'           => 'product',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on product page', 'starter-blog' ),
				);
				$display_fields[] = array(
					'name'           => 'product_cat',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on product category', 'starter-blog' ),
				);
				$display_fields[] = array(
					'name'           => 'product_tag',
					'type'           => 'checkbox',
					'checkbox_label' => __( 'Hide on product tag', 'starter-blog' ),
				);

			}

			$config[] = array(
				'name'        => "{$section}_display_pages",
				'type'        => 'modal',
				'section'     => $section,
				'label'       => __( 'Display', 'starter-blog' ),
				'description' => __( 'Settings display for special pages.', 'starter-blog' ),
				'default'     => array(),
				'fields'      => array(
					'tabs'           => array(
						'display' => __( 'Display', 'starter-blog' ),
					),
					'display_fields' => $display_fields,
				),
			);

			$config[] = array(
				'name'        => $section . '_typo',
				'type'        => 'typography',
				'section'     => $section,
				'title'       => __( 'Typography', 'starter-blog' ),
				'description' => __( 'Typography for breadcrumb', 'starter-blog' ),
				'selector'    => "{$selector}",
				'css_format'  => 'typography',
			);

			$config[] = array(
				'name'        => $section . '_styling',
				'type'        => 'styling',
				'section'     => $section,
				'title'       => __( 'Styling', 'starter-blog' ),
				'description' => __( 'Styling for breadcrumb', 'starter-blog' ),
				'selector'    => array(
					'normal'            => "{$selector}, #page-titlebar {$selector}, #page-cover {$selector}",
					'normal_box_shadow' => "{$selector}, #page-titlebar {$selector} .page-breadcrumb-list, #page-cover {$selector} .page-breadcrumb-list",
					'normal_text_color' => "{$selector}, #page-titlebar {$selector} .page-breadcrumb-list, #page-cover {$selector} .page-breadcrumb-list",
					'normal_link_color' => "{$selector} a, #page-titlebar {$selector} .page-breadcrumb-list a, #page-cover {$selector} .page-breadcrumb-list a",
					'hover_link_color'  => "{$selector} a:hover, #page-titlebar {$selector} .page-breadcrumb-list a:hover, #page-cover {$selector} .page-breadcrumb-list a:hover",
				),
				'css_format'  => 'styling',
				'fields'      => array(
					'normal_fields' => array(
						'margin' => false, // Disable for special field.
					),
					'hover_fields'  => array(
						'text_color'     => false,
						'padding'        => false,
						'bg_color'       => false,
						'bg_heading'     => false,
						'bg_cover'       => false,
						'bg_image'       => false,
						'bg_repeat'      => false,
						'border_heading' => false,
						'border_color'   => false,
						'border_radius'  => false,
						'border_width'   => false,
						'border_style'   => false,
						'box_shadow'     => false,
					), // Disable hover tab and all fields inside.
				),
			);
		}

		return array_merge( $configs, $config );
	}

	public function is_showing() {
		if ( ! $this->support_plugins_active() ) {
			return false;
		}

		$display = StarterBlog()->get_setting_tab( 'breadcrumb_display_pages', 'display' );
		$display = wp_parse_args(
			$display,
			array(
				'index'       => '',
				'category'    => '',
				'search'      => '',
				'archive'     => '',
				'page'        => '',
				'post'        => '',
				'singular'    => '',
				'product'     => '',
				'product_cat' => '',
				'product_tag' => '',
				'page_404'    => '',
			)
		);

		$hide = false;

		if ( is_front_page() && is_home() ) { // Index page.
			// Default homepage.
			$hide = $display['index'];
		} elseif ( is_front_page() ) {
			// Static homepage.
			$hide = $display['page'];
		} elseif ( is_home() ) {
			// Blog page.
			$hide = $display['page'];
		} elseif ( is_category() ) {
			// Category.
			$hide = $display['category'];
		} elseif ( is_page() ) {
			// Single page.
			$hide = $display['page'];
		} elseif ( is_single() ) {
			// Single post.
			$hide = $display['post'];
		} elseif ( is_singular() ) {
			// Single custom post type.
			$hide = $display['singular'];
		} elseif ( is_404() ) {
			// Page not found.
			$hide = $display['page_404'];
		} elseif ( is_search() ) {
			// Search result.
			$hide = $display['search'];
		} elseif ( is_archive() ) {
			$hide = $display['archive'];
		}

		// WooCommerce Settings.
		if ( StarterBlog()->is_woocommerce_active() ) {
			if ( is_product() ) {
				$hide = $display['product'];
			} elseif ( is_product_category() ) {
				$hide = $display['product_cat'];
			} elseif ( is_product_tag() ) {
				$hide = $display['product_tag'];
			} elseif ( is_shop() ) {
				$hide = $display['page'];
			}
		}

		if ( StarterBlog()->is_using_post() ) {
			$post_id            = StarterBlog()->get_current_post_id();
			$breadcrumb_display = get_post_meta( $post_id, '_starterblog_breadcrumb_display', true );
			if ( $breadcrumb_display && 'default' != $breadcrumb_display ) {
				if ( 'hide' == $breadcrumb_display ) {
					$hide = 1;
				} else {
					$hide = 0;
				}
			}
		}

		return apply_filters( 'starterblog/breadcrumb/is-showing', ( ! $hide ) );
	}

	/**
	 * Display below header cover
	 *
	 * @return bool|string
	 */
	public function render() {
		if ( ! $this->is_showing() ) {
			return '';
		}
		$list = '';
		if ( function_exists( 'bcn_display_list' ) ) {
			$list = bcn_display_list( true );
		} elseif ( function_exists( 'yoast_breadcrumb' ) ) {
			$list = yoast_breadcrumb( '', '', false );
		}

		if ( $list ) {
			$pos       = sanitize_text_field( StarterBlog()->get_setting( 'breadcrumb_display_pos' ) );
			$layout    = StarterBlog()->get_setting_tab( 'page_header_layout' );
			$classes   = array( 'page-breadcrumb' );
			$classes[] = 'breadcrumb--' . $pos;
			$classes[] = $layout;
			$classes[] = 'text-uppercase text-xsmall link-meta';

			?>
			<div id="page-breadcrumb" class="page-header--item <?php echo esc_attr( join( ' ', $classes ) ); ?>">
				<div class="page-breadcrumb-inner starterblog-container">
					<ul class="page-breadcrumb-list">
						<?php
						// WPCS: XSS OK.
						echo wp_kses_post( $list );
						?>
					</ul>
				</div>
			</div>
			<?php
		}
	}

}

StarterBlog_Breadcrumb::get_instance();
