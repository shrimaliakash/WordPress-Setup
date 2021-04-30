<?php

class StarterBlog_Page_Header {
	public $name = null;
	public $description = null;
	static $is_transparent = null;
	static $_instance = null;
	static $_settings = null;

	function __construct() {
		add_filter( 'starterblog/customizer/config', array( $this, 'config' ) );
		if ( ! is_admin() ) {
			add_action( 'starterblog_is_post_title_display', array( $this, 'display_page_title' ), 35 );
			add_action( 'starterblog/site-start', array( $this, 'render' ), 35 );
			add_action( 'wp', array( $this, 'wp' ), 85 );
		}
		self::$_instance = $this;
	}

	function wp() {
		$this->get_settings();
	}

	static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function config( $configs = array() ) {
		$section      = 'page_header';
		$name         = 'page_header';
		$choices      = array(
			'default'  => __( 'Default', 'starter-blog' ),
			'cover'    => __( 'Cover', 'starter-blog' ),
			'titlebar' => __( 'Titlebar', 'starter-blog' ),
			'none'     => __( 'Hide', 'starter-blog' ),
		);
		$render_cb_el = array( $this, 'render' );

		$display_fields = array(
			array(
				'name'        => 'page',
				'type'        => 'select',
				'label'       => __( 'Display on single page', 'starter-blog' ),
				'description' => __( 'Apply when viewing single page', 'starter-blog' ),
				'default'     => 'titlebar',
				'choices'     => $choices,
			),
			array(
				'name'        => 'post',
				'type'        => 'select',
				'label'       => __( 'Display on single post', 'starter-blog' ),
				'description' => __( 'Apply when viewing single post', 'starter-blog' ),
				'default'     => '',
				'choices'     => $choices,
			),

			array(
				'name'        => 'category',
				'type'        => 'select',
				'label'       => __( 'Display on categories', 'starter-blog' ),
				'description' => __( 'Apply when viewing a category page', 'starter-blog' ),
				'default'     => '',
				'choices'     => $choices,
			),
			array(
				'name'        => 'index',
				'type'        => 'select',
				'label'       => __( 'Display on index', 'starter-blog' ),
				'description' => __( 'Apply when your homepage displays as latest posts', 'starter-blog' ),
				'default'     => '',
				'choices'     => $choices,
			),
			array(
				'name'        => 'search',
				'type'        => 'select',
				'label'       => __( 'Display on search', 'starter-blog' ),
				'description' => __( 'Apply when viewing search results page', 'starter-blog' ),
				'default'     => '',
				'choices'     => $choices,
			),
			array(
				'name'        => 'archive',
				'type'        => 'select',
				'label'       => __( 'Display on archive', 'starter-blog' ),
				'description' => __( 'Apply when viewing archive pages, e.g. Tag, Author, Date, Custom Post Type or Custom Taxonomy', 'starter-blog' ),
				'default'     => '',
				'choices'     => $choices,
			),
			array(
				'name'        => 'page_404',
				'type'        => 'select',
				'label'       => __( 'Display on 404 page', 'starter-blog' ),
				'description' => __( 'Apply when the page not found', 'starter-blog' ),
				'default'     => '',
				'choices'     => $choices,
			),

		);

		$title_fields = array(
			array(
				'name'        => 'index',
				'type'        => 'text',
				'label'       => __( 'Title for index page', 'starter-blog' ),
				'description' => __( 'Apply when your homepage displays as latest posts', 'starter-blog' ),
				'default'     => '',
			),
			array(
				'name'        => 'post',
				'type'        => 'text',
				'label'       => __( 'Title for single post', 'starter-blog' ),
				'description' => __( 'Apply when viewing single post', 'starter-blog' ),
				'default'     => '',
			),
			array(
				'name'        => 'page_404',
				'type'        => 'text',
				'label'       => __( 'Title for 404 page', 'starter-blog' ),
				'description' => __( 'Apply when the page not found', 'starter-blog' ),
				'default'     => '',
			),
		);

		$tagline_fields = array(
			array(
				'name'        => 'index',
				'type'        => 'textarea',
				'label'       => __( 'Tagline for index page', 'starter-blog' ),
				'description' => __( 'Apply when your homepage displays as latest posts', 'starter-blog' ),
				'default'     => '',
			),
			array(
				'name'        => 'post',
				'type'        => 'textarea',
				'label'       => __( 'Tagline for single post', 'starter-blog' ),
				'description' => __( 'Apply when viewing single post', 'starter-blog' ),
				'default'     => '',
			),
			array(
				'name'        => 'page_404',
				'type'        => 'textarea',
				'label'       => __( 'Tagline for 404 page', 'starter-blog' ),
				'description' => __( 'Apply when the page not found', 'starter-blog' ),
				'default'     => '',
			),
		);

		$post_types = StarterBlog()->get_post_types( false );
		if ( count( $post_types ) > 0 ) {
			foreach ( $post_types as $pt => $label ) {
				$display_fields[] = array(
					'name'        => $pt,
					'type'        => 'select',
					'label'       => sprintf( __( 'Display on %s page', 'starter-blog' ), $label['singular_name'] ),
					'description' => sprintf( __( 'Apply when viewing single %s', 'starter-blog' ), $label['singular_name'] ),
					'default'     => '',
					'choices'     => $choices,
				);

				$taxonomy_filter_args = [
					'show_in_nav_menus' => true,
				];

				$taxonomy_filter_args['object_type'] = [ $pt ];
				$taxonomies                          = get_taxonomies( $taxonomy_filter_args, 'objects' );
				$options                             = array();

				foreach ( $taxonomies as $taxonomy => $object ) {
					$options[ $taxonomy ] = $object->label;
					$display_fields[]     = array(
						'name'        => $taxonomy,
						'type'        => 'select',
						'label'       => sprintf( __( 'Display on %1$s %2$s', 'starter-blog' ), $label['singular_name'], $object->labels->singular_name ),
						'description' => sprintf( __( 'Apply when viewing %1$s %2$s', 'starter-blog' ), $label['singular_name'], $object->labels->singular_name ),
						'default'     => '',
						'choices'     => $choices,
					);
				}

				$title_fields[] = array(
					'name'        => $pt,
					'type'        => 'text',
					'label'       => sprintf( __( 'Title for %s', 'starter-blog' ), $label['singular_name'] ),
					'description' => sprintf( __( 'Apply when viewing single %s', 'starter-blog' ), $label['singular_name'] ),
					'default'     => '',
				);

				$tagline_fields[] = array(
					'name'        => $pt,
					'type'        => 'textarea',
					'label'       => sprintf( __( 'Tagline for %s', 'starter-blog' ), $label['singular_name'] ),
					'description' => sprintf( __( 'Apply when viewing single %s', 'starter-blog' ), $label['singular_name'] ),
					'default'     => '',
				);
			}
		}

		$config = array(
			array(
				'name'  => $section,
				'type'  => 'section',
				'panel' => 'layout_panel',
				'title' => __( 'Page Header', 'starter-blog' ),
			),

			array(
				'name'       => $section . '_layout',
				'type'       => 'select',
				'section'    => $section,
				'title'      => __( 'Layout', 'starter-blog' ),
				'selector'   => '.page-header--item',
				'css_format' => 'html_class',
				'default'    => '',
				'choices'    => array(
					''                      => __( 'Default', 'starter-blog' ),
					'layout-full-contained' => __( 'Full width - Contained', 'starter-blog' ),
					'layout-fullwidth'      => __( 'Full Width', 'starter-blog' ),
					'layout-contained'      => __( 'Contained', 'starter-blog' ),
				),
			),

			array(
				'name'    => "{$name}_display_h",
				'type'    => 'heading',
				'section' => $section,
				'title'   => __( 'Display Settings', 'starter-blog' ),
			),

			array(
				'name'        => "{$name}_display",
				'type'        => 'modal',
				'section'     => $section,
				'label'       => __( 'Display', 'starter-blog' ),
				'description' => __( 'Settings display for special pages.', 'starter-blog' ),
				'default'     => array(
					'display' => array(
						'page'     => 'titlebar',
						'archive'  => 'titlebar',
						'category' => 'titlebar',
					),
				),
				'fields'      => array(
					'tabs'            => array(
						'display'  => __( 'Display', 'starter-blog' ),
						'advanced' => __( 'Advanced', 'starter-blog' ),
					),
					'display_fields'  => $display_fields,
					'advanced_fields' => array(
						array(
							'name'        => 'post_bg',
							'type'        => 'select',
							'label'       => __( 'Post Header Background Cover', 'starter-blog' ),
							'description' => __( 'Apply when viewing single post and page header setting displays as cover.', 'starter-blog' ),
							'default'     => '',
							'choices'     => array(
								'default'   => __( 'Default', 'starter-blog' ),
								'blog_page' => __( 'Use featured image from blog page', 'starter-blog' ),
								'featured'  => __( 'Use featured image of current post', 'starter-blog' ),
							),
						),
						array(
							'name'    => 'post_title_tagline',
							'type'    => 'select',
							'label'   => __( 'Single Post Title & Tagline', 'starter-blog' ),
							'default' => '',
							'choices' => array(
								'default'   => __( 'Default', 'starter-blog' ),
								'blog_page' => __( 'Use title & tagline from blog page', 'starter-blog' ),
								'current'   => __( 'Use title & tagline of current post', 'starter-blog' ),
							),
						),
					),
				),
			),

			array(
				'name'            => "{$name}_title_tagline",
				'type'            => 'modal',
				'section'         => $section,
				'label'           => __( 'Title & Tagline', 'starter-blog' ),
				'description'     => __( 'Title & tagline for special pages.', 'starter-blog' ),
				'default'         => array(),
				'fields'          => array(
					'tabs'            => array(
						'titles'   => __( 'Title', 'starter-blog' ),
						'taglines' => __( 'Tagline', 'starter-blog' ),
					),
					'titles_fields'   => $title_fields,
					'taglines_fields' => $tagline_fields,
				),
				'selector'        => '#page-titlebar, #page-cover',
				'render_callback' => $render_cb_el,
			),

			array(
				'name'            => $name . '_show_archive_prefix',
				'type'            => 'checkbox',
				'section'         => $section,
				'title'           => __( 'Archive Prefix', 'starter-blog' ),
				'description'     => __( 'Enable or disable archive prefix on category, date, tag page.', 'starter-blog' ),
				'checkbox_label'  => __( 'Enable', 'starter-blog' ),
				'default'         => 1,
				'selector'        => '#page-titlebar, #page-cover',
				'render_callback' => $render_cb_el,
			),

		);

		$configs = array_merge( $configs, $config );
		$configs = array_merge( $configs, $this->config_cover() );
		$configs = array_merge( $configs, $this->config_titlebar() );

		return $configs;
	}

	function config_titlebar() {

		$section      = 'page_header';
		$render_cb_el = array( $this, 'render' );
		$selector     = '#page-titlebar';
		$name         = 'titlebar';
		$config       = array(

			array(
				'name'    => "{$name}_styling_h_tb",
				'type'    => 'heading',
				'section' => 'page_header',
				'title'   => __( 'Titlebar Settings', 'starter-blog' ),
			),

			array(
				'name'           => $name . '_show_title',
				'type'           => 'checkbox',
				'section'        => $section,
				'label'          => __( 'Show Title', 'starter-blog' ),
				'description'    => __( 'Title is pull from post title, archive title.', 'starter-blog' ),
				'checkbox_label' => __( 'Enable', 'starter-blog' ),
				'default'        => 1,
			),

			array(
				'name'           => $name . '_show_tagline',
				'type'           => 'checkbox',
				'section'        => $section,
				'label'          => __( 'Show Tagline', 'starter-blog' ),
				'description'    => __( 'Tagline is pull from post excerpt, archive description.', 'starter-blog' ),
				'checkbox_label' => __( 'Enable', 'starter-blog' ),
				'default'        => 1,
			),
			array(
				'name'       => $name . '_title_color',
				'type'       => 'color',
				'section'         => $section,
				'label'      => __( 'Title Color', 'starter-blog' ),
				'selector'   => "$selector .titlebar-title",
				'css_format' => 'color: {{value}};',
			),

			array(
				'name'       => $name . '_tagline_color',
				'type'       => 'color',
				'section'         => $section,
				'label'      => __( 'Tagline Color', 'starter-blog' ),
				'selector'   => "$selector .titlebar-tagline",
				'css_format' => 'color: {{value}};',
			),

			array(
				'name'            => "{$name}_align",
				'type'            => 'text_align_no_justify',
				'section'         => $section,
				'device_settings' => true,
				'selector'        => "$selector",
				'css_format'      => 'text-align: {{value}};',
				'title'           => __( 'Text Align', 'starter-blog' ),
			),

		);

		$config = apply_filters( 'starterblog/titlebar/config', $config, $this );

		return $config;
	}

	function config_cover() {

		$section      = 'page_header';
		$render_cb_el = array( $this, 'render' );
		$selector     = '#page-cover';
		$name         = 'header_cover';
		$config       = array(

			array(
				'name'    => "{$name}_settings_h",
				'type'    => 'heading',
				'section' => $section,
				'title'   => __( 'Cover Settings', 'starter-blog' ),
			),

			array(
				'name'           => $name . '_show_title',
				'type'           => 'checkbox',
				'section'        => $section,
				'label'          => __( 'Show Title', 'starter-blog' ),
				'description'    => __( 'Title is pull from post title, archive title.', 'starter-blog' ),
				'checkbox_label' => __( 'Enable', 'starter-blog' ),
				'default'        => 1,
			),

			array(
				'name'           => $name . '_show_tagline',
				'type'           => 'checkbox',
				'section'        => $section,
				'label'          => __( 'Show Tagline', 'starter-blog' ),
				'description'    => __( 'Tagline is pull from post excerpt, archive description.', 'starter-blog' ),
				'checkbox_label' => __( 'Enable', 'starter-blog' ),
				'default'        => 1,
			),

			array(
				'name'       => $name . '_bg',
				'type'       => 'modal',
				'section'    => $section,
				'title'      => __( 'Color & Background', 'starter-blog' ),
				'selector'   => $selector,
				'css_format' => 'styling', // Styling.
				'default'    => array(
					'normal' => array(
						'bg_image' => array(
							'id'  => '',
							'url' => esc_url( get_template_directory_uri() ) . '/assets/images/default-cover.jpg',
						),
					),
				),
				'fields'     => array(
					'tabs'          => array(
						'normal' => '_',
					),
					'normal_fields' => array(
						array(
							'name'       => 'title_color',
							'type'       => 'color',
							'label'      => __( 'Title Color', 'starter-blog' ),
							'selector'   => "$selector .page-cover-title",
							'css_format' => 'color: {{value}};',
						),

						array(
							'name'       => 'tagline_color',
							'type'       => 'color',
							'label'      => __( 'Tagline Color', 'starter-blog' ),
							'selector'   => "$selector .page-cover-tagline",
							'css_format' => 'color: {{value}};',
						),

						array(
							'name'       => 'bg_image',
							'type'       => 'image',
							'label'      => __( 'Background Image', 'starter-blog' ),
							'selector'   => "$selector",
							'css_format' => 'background-image: url("{{value}}");',
						),
						array(
							'name'       => 'bg_cover',
							'type'       => 'select',
							'choices'    => array(
								''        => __( 'Default', 'starter-blog' ),
								'auto'    => __( 'Auto', 'starter-blog' ),
								'cover'   => __( 'Cover', 'starter-blog' ),
								'contain' => __( 'Contain', 'starter-blog' ),
							),
							'required'   => array( 'bg_image', 'not_empty', '' ),
							'label'      => __( 'Size', 'starter-blog' ),
							'class'      => 'field-half-left',
							'selector'   => "$selector",
							'css_format' => '-webkit-background-size: {{value}}; -moz-background-size: {{value}}; -o-background-size: {{value}}; background-size: {{value}};',
						),
						array(
							'name'       => 'bg_position',
							'type'       => 'select',
							'label'      => __( 'Position', 'starter-blog' ),
							'required'   => array( 'bg_image', 'not_empty', '' ),
							'class'      => 'field-half-right',
							'choices'    => array(
								''              => __( 'Default', 'starter-blog' ),
								'center'        => __( 'Center', 'starter-blog' ),
								'top left'      => __( 'Top Left', 'starter-blog' ),
								'top right'     => __( 'Top Right', 'starter-blog' ),
								'top center'    => __( 'Top Center', 'starter-blog' ),
								'bottom left'   => __( 'Bottom Left', 'starter-blog' ),
								'bottom center' => __( 'Bottom Center', 'starter-blog' ),
								'bottom right'  => __( 'Bottom Right', 'starter-blog' ),
							),
							'selector'   => "$selector",
							'css_format' => 'background-position: {{value}};',
						),
						array(
							'name'       => 'bg_repeat',
							'type'       => 'select',
							'label'      => __( 'Repeat', 'starter-blog' ),
							'class'      => 'field-half-left',
							'required'   => array(
								array( 'bg_image', 'not_empty', '' ),
							),
							'choices'    => array(
								'repeat'    => __( 'Default', 'starter-blog' ),
								'no-repeat' => __( 'No repeat', 'starter-blog' ),
								'repeat-x'  => __( 'Repeat horizontal', 'starter-blog' ),
								'repeat-y'  => __( 'Repeat vertical', 'starter-blog' ),
							),
							'selector'   => "$selector",
							'css_format' => 'background-repeat: {{value}};',
						),

						array(
							'name'       => 'bg_attachment',
							'type'       => 'select',
							'label'      => __( 'Attachment', 'starter-blog' ),
							'class'      => 'field-half-right',
							'required'   => array(
								array( 'bg_image', 'not_empty', '' ),
							),
							'choices'    => array(
								''       => __( 'Default', 'starter-blog' ),
								'scroll' => __( 'Scroll', 'starter-blog' ),
								'fixed'  => __( 'Fixed', 'starter-blog' ),
							),
							'selector'   => "$selector",
							'css_format' => 'background-attachment: {{value}};',
						),

						array(
							'name'            => 'overlay',
							'type'            => 'color',
							'section'         => $section,
							'class'           => 'starterblog--clear',
							'device_settings' => false,
							'selector'        => "$selector:before",
							'label'           => __( 'Cover Overlay', 'starter-blog' ),
							'css_format'      => 'background-color: {{value}};',
						),

					),
					'hover_fields'  => false,
				),
			),

			array(
				'name'            => "{$name}_align",
				'type'            => 'text_align_no_justify',
				'section'         => $section,
				'device_settings' => true,
				'selector'        => "$selector",
				'css_format'      => 'text-align: {{value}};',
				'title'           => __( 'Cover Text Align', 'starter-blog' ),
			),

			array(
				'name'            => "{$name}_height",
				'type'            => 'slider',
				'section'         => $section,
				'device_settings' => true,
				'max'             => 1000,
				'title'           => __( 'Cover Height', 'starter-blog' ),
				'selector'        => "{$selector} .page-cover-inner",
				'css_format'      => 'min-height: {{value}};',
				'default'         => array(
					'desktop' => array(
						'value' => '300',
					),
					'tablet'  => array(
						'value' => '250',
					),
					'mobile'  => array(
						'value' => '200',
					),
				),
			),

			array(
				'name'            => "{$name}_align",
				'type'            => 'text_align_no_justify',
				'section'         => $section,
				'device_settings' => true,
				'selector'        => "$selector",
				'css_format'      => 'text-align: {{value}};',
				'title'           => __( 'Cover Text Align', 'starter-blog' ),
			),

		);
		$config       = apply_filters( 'starterblog/cover/config', $config, $this );

		return $config;
	}

	function get_settings() {

		if ( ! is_null( self::$_settings ) ) {
			return self::$_settings;
		}

		$args = array(
			'_page'                      => 'index',
			'display'                    => 'default',
			'title'                      => '',
			'tagline'                    => '',
			'image'                      => '',
			'title_tag'                  => 'h1',
			'force_display_single_title' => '', // Show || or hide.
			'show_title'                 => false, // force show post title.
			'shortcode'                  => false, // force show post title.
			'cover_tagline'              => 1, // Display tagline in cover.
			'titlebar_tagline'           => 1, // Display tagline in titlbar.
		);
		$name = 'page_header';

		$display  = StarterBlog()->get_setting_tab( $name . '_display', 'display' );
		$advanced = StarterBlog()->get_setting_tab( $name . '_display', 'advanced' );

		$titles   = StarterBlog()->get_setting_tab( $name . '_title_tagline', 'titles' );
		$taglines = StarterBlog()->get_setting_tab( $name . '_title_tagline', 'taglines' );

		$args['cover_tagline']    = StarterBlog()->get_setting( 'header_cover_show_tagline' );
		$args['titlebar_tagline'] = StarterBlog()->get_setting( 'titlebar_show_tagline' );

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

		$advanced = wp_parse_args(
			$advanced,
			array(
				'post_bg'            => '',
				'post_title_tagline' => '',
			)
		);

		$titles = wp_parse_args(
			$titles,
			array(
				'index'    => '',
				'post'     => '',
				'product'  => '',
				'page_404' => '',
			)
		);

		$taglines = wp_parse_args(
			$taglines,
			array(
				'index'    => '',
				'post'     => '',
				'product'  => '',
				'page_404' => '',
			)
		);

		$post_thumbnail_id = false;

		$post_id = 0;
		if ( is_front_page() && is_home() ) { // index page.
			// Default homepage.
			$args['display'] = $display['index'];
			$args['title']   = $titles['index'];
			$args['tagline'] = $taglines['index'];
			$args['_page']   = 'index';
		} elseif ( is_front_page() ) {
			// static homepage.
			$args['display'] = $display['page'];
			$post_id         = get_the_ID();
			$args['_page']   = 'page';
		} elseif ( is_home() ) {
			// blog page.
			$args['display'] = $display['page'];
			$post_id         = get_option( 'page_for_posts' );
			$args['_page']   = 'page';
		} elseif ( is_category() ) {
			// category.
			$args['display'] = $display['category'];
			$args['title']   = get_the_archive_title();
			$args['tagline'] = get_the_archive_description();
			$args['_page']   = 'category';
			$post_id         = 0;
		} elseif ( is_page() ) {
			// single page.
			$args['display'] = $display['page'];
			$post_id         = get_the_ID();
			$args['_page']   = 'page';
		} elseif ( is_singular( 'post' ) ) {
			// single post.
			$args['display']   = $display['post'];
			$args['title_tag'] = 'h2';

			// Setup single post bg for cover.
			if ( 'blog_page' == $advanced['post_bg'] ) {
				$post_id           = get_option( 'page_for_posts' );
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			} elseif ( 'featured' == $advanced['post_bg'] ) {
				$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			} else {
				$post_id = get_option( 'page_for_posts' );
				if ( $post_id ) {
					$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
				}
			}

			if ( 'none' != $args['display'] ) {
				if ( 'blog_page' == $advanced['post_title_tagline'] ) {
					$post_id                            = get_option( 'page_for_posts' );
					$args['force_display_single_title'] = 'show';
				} elseif ( 'current' == $advanced['post_title_tagline'] ) {
					$post_id = get_the_ID();
					if ( 'default' != $args['display'] ) {
						$args['force_display_single_title'] = 'hide';
					} else {
						$args['force_display_single_title'] = 'show';
					}
					$args['title_tag'] = 'h1';
				} else {
					$post_id                            = get_option( 'page_for_posts' );
					$args['force_display_single_title'] = 'show';
					if ( ! $post_id ) {
						$args['force_display_single_title'] = 'show';
						if ( $titles['post'] || $taglines['post'] ) {
							$args['title']   = $titles['post'];
							$args['tagline'] = $taglines['post'];
						}
					}
				}
			}

			$args['_page'] = 'post';
		} elseif ( is_singular() ) {
			// single custom post type.
			$post_id   = get_the_ID();
			$post_type = get_post_type();
			if ( isset( $display[ $post_type ] ) ) {
				$args['display'] = $display[ $post_type ];
				$args['_page']   = 'singular_' . $post_type;
			} elseif ( isset( $display['singular'] ) ) {
				$args['display'] = $display['singular'];
				$args['_page']   = 'singular';
			}
		} elseif ( is_404() ) {
			// page not found.
			$args['display'] = $display['page_404'];
			$args['_page']   = '404';
			$args['title']   = $titles['page_404'];
			$args['tagline'] = $taglines['page_404'];
			if ( ! $args['title'] ) {
				$args['title'] = __( "Oops! That page can't be found.", 'starter-blog' );
			}
		} elseif ( is_search() ) {
			// Search result.
			$args['display'] = $display['search'];
			$args['title']   = sprintf( // WPCS: XSS ok.
				/* translators: 1: Search query name */
				__( 'Search Results for: %s', 'starter-blog' ),
				'<span>' . get_search_query() . '</span>'
			);
			$args['tagline'] = '';
			$args['_page']   = 'search';
			$post_id         = 0;
		} elseif ( is_archive() ) {
			$args['display'] = $display['archive'];
			$args['title']   = get_the_archive_title();
			$args['tagline'] = get_the_archive_description();
			$args['_page']   = 'archive';
			$post_id         = 0;
		}

		if ( is_tax() ) {
			$queried_object = get_queried_object();
			if ( isset( $display[ $queried_object->taxonomy ] ) ) {
				$args['display'] = $display['product_tag'];
			}
			if ( isset( $titles[ $queried_object->taxonomy ] ) ) {
				$args['display'] = $titles[ $queried_object->taxonomy ];
			}
			if ( isset( $taglines[ $queried_object->taxonomy ] ) ) {
				$args['tagline'] = $taglines[ $queried_object->taxonomy ];
			}
			$args['_page'] = 'tax_' . $queried_object->taxonomy;
		}

		// WooCommerce Settings.
		if ( StarterBlog()->is_woocommerce_active() ) {
			if ( is_product() ) {
				$post_id         = wc_get_page_id( 'shop' );
				$args['display'] = $display['product'];
				$args['title']   = $titles['product'];
				$args['tagline'] = $taglines['product'];
				$args['_page']   = 'product';
				if ( $args['title'] || $args['tagline'] ) {
					$post_id = 0;
				}
			} elseif ( is_product_category() ) {
				$post_id         = 0;
				$args['display'] = $display['product_cat'];
				$args['title']   = get_the_archive_title();
				$args['tagline'] = get_the_archive_description();
				$args['_page']   = 'product_cat';
			} elseif ( is_product_tag() ) {
				$post_id         = 0;
				$args['display'] = $display['product_tag'];
				$args['title']   = get_the_archive_title();
				$args['tagline'] = get_the_archive_description();
				$args['_page']   = 'product_tag';
			} elseif ( is_shop() && ! is_search() ) {
				$args['display'] = $display['page'];
				$post_id         = wc_get_page_id( 'shop' );
				$args['_page']   = 'shop';
				$args['tagline'] = '';
			}
		}

		if ( $post_id > 0 ) {
			$post = get_post( $post_id );
			if ( $post ) {
				$args['title'] = get_the_title( $post_id );
				if ( $post->post_excerpt ) {
					$args['tagline'] = get_the_excerpt( $post );
				}
				if ( ! $post_thumbnail_id ) {
					$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				}
			}
		}

		if ( ! $args['image'] && $post_thumbnail_id ) {
			$_i = StarterBlog()->get_media( $post_thumbnail_id );
			if ( $_i ) {
				$args['image'] = $_i;
			}
		}

		if ( StarterBlog()->is_using_post() ) {
			$post_id = StarterBlog()->get_current_post_id();

			// If Disable page title.
			$disable = get_post_meta( $post_id, '_starterblog_disable_page_title', true );
			if ( $disable ) {
				$args['force_display_single_title'] = 'hide';
			}

			// If has custom field custom title.
			$post_display = get_post_meta( $post_id, '_starterblog_page_header_display', true );
			if ( $post_display && 'default' != $post_display ) {
				if ( 'normal' == $post_display ) {
					$args['display'] = 'default';
				} else {
					$args['display'] = $post_display;
				}
			}

			// If has custom field custom title.
			$title = get_post_meta( $post_id, '_starterblog_page_header_title', true );
			if ( $title ) {
				$args['title'] = $title;
			}

			// If has custom field custom tagline.
			$tagline = trim( get_post_meta( $post_id, '_starterblog_page_header_tagline', true ) );
			if ( $tagline ) {
				$args['tagline'] = $tagline;
			}

			// If has custom field header media.
			$media = get_post_meta( $post_id, '_starterblog_page_header_image', true );
			if ( ! empty( $media ) ) {
				$image = StarterBlog()->get_media( $media );
				if ( $image ) {
					$args['image'] = $image;
				}
			}

			// Has custom shortcode.
			$args['shortcode'] = trim( get_post_meta( $post_id, '_starterblog_page_header_shortcode', true ) );
			if ( $args['shortcode'] ) {
				$args['display'] = 'shortcode';
			}
		}

		if ( ! $args['display'] ) {
			$args['display'] = 'default';
		}

		self::$_settings = apply_filters( 'starterblog/page-header/get-settings', $args );

		return $args;
	}

	function display_page_title( $show ) {
		$args = $this->get_settings();
		if ( ! $args['display'] || 'default' == $args['display'] ) {
			$show = true;
		} elseif ( 'cover' == $args['display'] || 'titlebar' == $args['display'] || 'none' == $args['display'] ) {
			$show = false;
		}
		if ( 'hide' == $args['force_display_single_title'] ) {
			$show = false;
		} elseif ( 'show' == $args['force_display_single_title'] ) {
			$show = true;
		}

		return $show;
	}

	function render_cover( $args = array() ) {
		$args = $this->get_settings();
		extract( $args, EXTR_SKIP ); // phpcs:ignore

		$style = '';
		if ( $args['image'] ) {
			$style = ' style="background-image: url(\'' . esc_url( $args['image'] ) . '\')" ';
		}

		if ( ! $args['title_tag'] ) {
			$args['title_tag'] = 'h2';
		}

		$layout    = StarterBlog()->get_setting_tab( 'page_header_layout' );
		$classes   = array( 'page-header--item page-cover' );
		$classes[] = $layout;

		?>
		<div id="page-cover" class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"<?php echo $style; ?>>
			<div class="page-cover-inner starterblog-container">
				<?php
				do_action( 'starterblog/page-cover/before' );

				if ( StarterBlog()->get_setting( 'header_cover_show_title' ) ) {
					if ( $args['title'] ) {
						// WPCS: XSS ok.
						echo '<' . $args['title_tag'] . ' class="page-cover-title">' . apply_filters( 'starterblog_the_title', wp_kses_post( $args['title'] ) ) . '</' . $args['title_tag'] . '>';
					}
				}
				if ( $args['cover_tagline'] ) {
					if ( $args['tagline'] ) {
						// WPCS: XSS ok.
						echo '<div class="page-cover-tagline-wrapper"><div class="page-cover-tagline">' . apply_filters( 'starterblog_the_title', wp_kses_post( $args['tagline'] ) ) . '</div></div>';
					}
				}

				do_action( 'starterblog/page-cover/after' );
				?>
			</div>
		</div>
		<?php
	}

	function render_titlebar( $args = array() ) {

		$classes   = array( 'page-header--item page-titlebar' );
		$layout    = StarterBlog()->get_setting_tab( 'page_header_layout' );
		$classes[] = $layout;
		?>
		<div id="page-titlebar" class="<?php echo esc_attr( join( ' ', $classes ) ); ?>">
			<div class="page-titlebar-inner starterblog-container">
				<?php
				/**
				 * Hook titlebar before
				 */
				do_action( 'starterblog/titlebar/before' );

				// WPCS: XSS ok.
				if ( StarterBlog()->get_setting( 'titlebar_show_title' ) ) {
					if ( $args['title'] ) {
						echo '<' . $args['title_tag'] . ' class="titlebar-title h4">' . apply_filters( 'starterblog_the_title', wp_kses_post( $args['title'] ) ) . '</' . $args['title_tag'] . '>';
					}
				}
				if ( $args['titlebar_tagline'] ) {
					if ( $args['tagline'] ) {
						// WPCS: XSS ok.
						echo '<div class="titlebar-tagline">' . apply_filters( 'starterblog_the_title', wp_kses_post( $args['tagline'] ) ) . '</div>';
					}
				}
				/**
				 * Hook titlebar after
				 */
				do_action( 'starterblog/titlebar/after' );
				?>
			</div>
		</div>
		<?php
	}

	function render() {
		$args = $this->get_settings();
		if ( 'none' == $args['display'] ) {
			return '';
		}

		switch ( $args['display'] ) {
			case 'cover':
				$this->render_cover( $args );
				break;
			case 'titlebar':
				$this->render_titlebar( $args );
				break;
			case 'shortcode':
				echo '<div class="page-header-shortcode">' . apply_filters( 'starterblog_the_content', $args['shortcode'] ) . '</div>';
				break;
		}

	}

}

StarterBlog_Page_Header::get_instance();
