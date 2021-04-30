<?php
if ( ! function_exists( 'starterblog_customizer_blog_config' ) ) {
	function starterblog_customizer_blog_config( $args = array() ) {

		$args          = wp_parse_args(
			$args,
			array(
				'name'     => __( 'Blog Posts', 'starter-blog' ),
				'id'       => 'blog_post',
				'selector' => '#blog-posts',
				'cb'       => 'starterblog_blog_posts',
			)
		);
		$top_panel     = 'blog_panel';
		$level_2_panel = 'panel_' . $args['id'];

		$config = array(
			array(
				'name'  => $level_2_panel,
				'type'  => 'panel',
				'panel' => $top_panel,
				'title' => $args['name'],
			),

			array(
				'name'  => $level_2_panel . '_layout',
				'type'  => 'section',
				'panel' => $level_2_panel,
				'title' => __( 'Layout', 'starter-blog' ),
			),

			array(
				'name'             => $args['id'] . '_layout',
				'type'             => 'image_select',
				'section'          => $level_2_panel . '_layout',
				'label'            => __( 'Layout', 'starter-blog' ),
				'default'          => 'blog_column',
				'selector'         => $args['selector'],
				'render_callback'  => $args['cb'],
				'disabled_msg'     => __( 'This option is available in Starter Blog Pro plugin only.', 'starter-blog' ),
				'disabled_pro_msg' => __( 'Please activate module Blog Posts to use this layout.', 'starter-blog' ),
				'choices'          => array(
					'blog_classic' => array(
						'img' => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/blog_classic.svg',
					),
					'blog_column'  => array(
						'img' => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/blog_column.svg',
					),
					'blog_masonry' => array(
						'img'     => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/blog_masonry.svg',
						'disable' => 1,
						'bubble'  => __( 'Pro', 'starter-blog' ),
					),
					'blog_lateral' => array(
						'img'     => esc_url( get_template_directory_uri() ) . '/assets/images/customizer/blog_lateral.svg',
						'disable' => 1,
						'bubble'  => __( 'Pro', 'starter-blog' ),
					),

				),
				'reset_controls'   => array(
					$args['id'] . '_media_ratio',
					$args['id'] . '_media_width',
				),
			),

			array(
				'name'    => $level_2_panel . '_layout_h1',
				'type'    => 'heading',
				'section' => $level_2_panel . '_layout',
				'title'   => __( 'Article Styling', 'starter-blog' ),
			),

			array(
				'name'       => $args['id'] . '_a_item',
				'type'       => 'styling',
				'section'    => $level_2_panel . '_layout',
				'selector'   => array(
					'normal'        => "{$args['selector'] } .entry-inner",
					'hover'         => "{$args['selector'] } .entry-inner:hover",
					'normal_margin' => "{$args['selector'] } .entry-inner",
				),
				'css_format' => 'styling',
				'label'      => __( 'Article Wrapper', 'starter-blog' ),
				'fields'     => array(
					'normal_fields' => array(
						'link_color'    => false, // disable for special field.
						'bg_image'      => false,
						'bg_cover'      => false,
						'bg_position'   => false,
						'bg_repeat'     => false,
						'bg_attachment' => false,
					),
					'hover_fields'  => array(
						'link_color' => false,
					),
				),
			),

			array(
				'name'  => $level_2_panel . '_media',
				'type'  => 'section',
				'panel' => $level_2_panel,
				'title' => __( 'Media', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_media_hide',
				'type'            => 'checkbox',
				'section'         => $level_2_panel . '_media',
				'checkbox_label'  => __( 'Hide Media', 'starter-blog' ),
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
			),

			array(
				'name'            => $args['id'] . '_media_ratio',
				'type'            => 'slider',
				'section'         => $level_2_panel . '_media',
				'label'           => __( 'Media Ratio', 'starter-blog' ),
				'selector'        => "{$args['selector']} .posts-layout .entry .entry-media",
				'css_format'      => 'padding-top: {{value_no_unit}}%;',
				'max'             => 200,
				'min'             => 0,
				'device_settings' => true,
				'unit'            => '%',
				'required'        => array( $args['id'] . '_media_hide', '!=', '1' ),
			),
			array(
				'name'            => $args['id'] . '_media_width',
				'type'            => 'slider',
				'section'         => $level_2_panel . '_media',
				'label'           => __( 'Media Width', 'starter-blog' ),
				'device_settings' => true,
				'devices'         => array( 'desktop', 'tablet' ),
				'max'             => 100,
				'min'             => 20,
				'unit'            => '%',
				'selector'        => "{$args['selector']} .posts-layout .entry-media, {$args['selector']} .posts-layout.layout--blog_classic .entry-media",
				'css_format'      => 'flex-basis: {{value_no_unit}}%; width: {{value_no_unit}}%;',
				'required'        => array( $args['id'] . '_media_hide', '!=', '1' ),
			),

			array(
				'name'       => $args['id'] . '_media_radius',
				'type'       => 'slider',
				'section'    => $level_2_panel . '_media',
				'label'      => __( 'Media Radius', 'starter-blog' ),
				'max'        => 100,
				'min'        => 0,
				'selector'   => "{$args['selector']} .posts-layout .entry-media",
				'css_format' => 'border-radius: {{value}};',
				'required'   => array( $args['id'] . '_media_hide', '!=', '1' ),
			),

			array(
				'name'            => $args['id'] . '_thumbnail_size',
				'type'            => 'select',
				'section'         => $level_2_panel . '_media',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'default'         => 'medium',
				'label'           => __( 'Thumbnail Size', 'starter-blog' ),
				'choices'         => starterblog_get_all_image_sizes(),
				'required'        => array( $args['id'] . '_media_hide', '!=', '1' ),
			),
			array(
				'name'            => $args['id'] . '_hide_thumb_if_empty',
				'type'            => 'checkbox',
				'section'         => $level_2_panel . '_media',
				'default'         => '1',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'checkbox_label'  => __( 'Hide featured image if empty.', 'starter-blog' ),
				'required'        => array( $args['id'] . '_media_hide', '!=', '1' ),
			),

			// Article Excerpt ---------------------------------------------------------------------------------.
			array(
				'name'  => $level_2_panel . '_excerpt',
				'type'  => 'section',
				'panel' => $level_2_panel,
				'title' => __( 'Excerpt', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_excerpt_type',
				'type'            => 'select',
				'section'         => $level_2_panel . '_excerpt',
				'default'         => 'custom',
				'choices'         => array(
					'custom'   => __( 'Custom', 'starter-blog' ),
					'excerpt'  => __( 'Use excerpt metabox', 'starter-blog' ),
					'more_tag' => __( 'Strip excerpt by more tag', 'starter-blog' ),
					'content'  => __( 'Full content', 'starter-blog' ),
				),
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Excerpt Type', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_excerpt_length',
				'type'            => 'number',
				'section'         => $level_2_panel . '_excerpt',
				'default'         => 25,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Excerpt Length', 'starter-blog' ),
				'required'        => array( $args['id'] . '_excerpt_type', '=', 'custom' ),
			),
			array(
				'name'            => $args['id'] . '_excerpt_more',
				'type'            => 'text',
				'section'         => $level_2_panel . '_excerpt',
				'default'         => '',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Excerpt More', 'starter-blog' ),
			),

			array(
				'name'  => $level_2_panel . '_meta',
				'type'  => 'section',
				'panel' => $level_2_panel,
				'title' => __( 'Metas', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_meta_sep',
				'section'         => $level_2_panel . '_meta',
				'type'            => 'text',
				'default'         => '',
				'label'           => __( 'Separator', 'starter-blog' ),
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
			),

			array(
				'name'       => $args['id'] . '_meta_sep_width',
				'section'    => $level_2_panel . '_meta',
				'type'       => 'slider',
				'max'        => 20,
				'label'      => __( 'Separator Width', 'starter-blog' ),
				'selector'   => $args['selector'] . ' .entry-meta .sep',
				'css_format' => 'margin-left: calc( {{value}} / 2 ); margin-right: calc( {{value}} / 2 );',
			),

			array(
				'name'             => $args['id'] . '_meta_config',
				'section'          => $level_2_panel . '_meta',
				'type'             => 'repeater',
				'description'      => __( 'Drag to reorder the meta item.', 'starter-blog' ),
				'live_title_field' => 'title',
				'limit'            => 4,
				'addable'          => false,
				'title_only'       => true,
				'selector'         => $args['selector'],
				'render_callback'  => $args['cb'],
				'default'          => array(
					array(
						'_key'  => 'author',
						'title' => __( 'Author', 'starter-blog' ),
					),
					array(
						'_key'  => 'date',
						'title' => __( 'Date', 'starter-blog' ),
					),
					array(
						'_key'  => 'categories',
						'title' => __( 'Categories', 'starter-blog' ),
					),
					array(
						'_key'  => 'comment',
						'title' => __( 'Comment', 'starter-blog' ),
					),

				),
				'fields'           => array(
					array(
						'name' => '_key',
						'type' => 'hidden',
					),
					array(
						'name'  => 'title',
						'type'  => 'hidden',
						'label' => __( 'Title', 'starter-blog' ),
					),
				),
			),

			array(
				'name'            => $args['id'] . '_author_avatar',
				'type'            => 'checkbox',
				'section'         => $level_2_panel . '_meta',
				'default'         => 0,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'checkbox_label'  => __( 'Show author avatar', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_avatar_size',
				'type'            => 'slider',
				'section'         => $level_2_panel . '_meta',
				'default'         => 32,
				'max'             => 150,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Avatar Size', 'starter-blog' ),
				'required'        => array( $args['id'] . '_author_avatar', '==', '1' ),
			),

			array(
				'name'  => $level_2_panel . '_readmore',
				'type'  => 'section',
				'panel' => $level_2_panel,
				'title' => __( 'Read More', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_more_display',
				'type'            => 'checkbox',
				'default'         => 1,
				'section'         => $level_2_panel . '_readmore',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'checkbox_label'  => __( 'Show Read More Button', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_more_text',
				'type'            => 'text',
				'section'         => $level_2_panel . '_readmore',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Read More Text', 'starter-blog' ),
				'required'        => array( $args['id'] . '_more_display', '==', '1' ),
			),
			array(
				'name'       => $args['id'] . '_more_typography',
				'type'       => 'typography',
				'css_format' => 'typography',
				'section'    => $level_2_panel . '_readmore',
				'selector'   => "{$args['selector'] } .entry-readmore a",
				'label'      => __( 'Typography', 'starter-blog' ),
				'required'   => array( $args['id'] . '_more_display', '==', '1' ),
			),

			array(
				'name'       => $args['id'] . '_more_styling',
				'type'       => 'styling',
				'section'    => $level_2_panel . '_readmore',
				'selector'   => array(
					'normal'        => "{$args['selector'] } .entry-readmore a",
					'hover'         => "{$args['selector'] } .entry-readmore a:hover",
					'normal_margin' => "{$args['selector'] } .entry-readmore",
				),
				'css_format' => 'styling',
				'label'      => __( 'Styling', 'starter-blog' ),
				'fields'     => array(
					'normal_fields' => array(
						'link_color'    => false, // Disable for special field.
						'bg_image'      => false,
						'bg_cover'      => false,
						'bg_position'   => false,
						'bg_repeat'     => false,
						'bg_attachment' => false,
					),
					'hover_fields'  => array(
						'link_color' => false, // Disable for special field.
					),
				),
				'required'   => array( $args['id'] . '_more_display', '==', '1' ),
			),

			array(
				'name'  => $level_2_panel . '_pagination',
				'type'  => 'section',
				'panel' => $level_2_panel,
				'title' => __( 'Pagination', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_pg_show_paging',
				'section'         => $level_2_panel . '_pagination',
				'type'            => 'checkbox',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'default'         => 1,
				'checkbox_label'  => __( 'Show Pagination', 'starter-blog' ),
			),
			array(
				'name'            => $args['id'] . '_pg_show_nav',
				'section'         => $level_2_panel . '_pagination',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'type'            => 'checkbox',
				'default'         => 1,
				'checkbox_label'  => __( 'Show Next, Previous Label', 'starter-blog' ),
				'required'        => array( $args['id'] . '_pg_show_paging', '==', '1' ),
			),
			array(
				'name'            => $args['id'] . '_pg_prev_text',
				'section'         => $level_2_panel . '_pagination',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'type'            => 'text',
				'label'           => __( 'Previous Label', 'starter-blog' ),
				'required'        => array( $args['id'] . '_pg_show_paging', '==', '1' ),
			),
			array(
				'name'            => $args['id'] . '_pg_next_text',
				'section'         => $level_2_panel . '_pagination',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'type'            => 'text',
				'label'           => __( 'Next Label', 'starter-blog' ),
				'required'        => array( $args['id'] . '_pg_show_paging', '==', '1' ),
			),

			array(
				'name'            => $args['id'] . '_pg_mid_size',
				'section'         => $level_2_panel . '_pagination',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'type'            => 'text',
				'default'         => 3,
				'label'           => __( 'How many numbers to either side of the current pages', 'starter-blog' ),
				'required'        => array( $args['id'] . '_pg_show_paging', '==', '1' ),
			),

		);

		return $config;
	}
}


if ( ! function_exists( 'starterblog_customizer_blog_posts_config' ) ) {
	function starterblog_customizer_blog_posts_config( $configs ) {

		$config = array(
			array(
				'name'     => 'blog_panel',
				'type'     => 'panel',
				'priority' => 20,
				'title'    => __( 'Blog', 'starter-blog' ),
			),
		);

		$blog   = starterblog_customizer_blog_config();
		$config = array_merge( $config, $blog );

		return array_merge( $configs, $config );
	}
}

add_filter( 'starterblog/customizer/config', 'starterblog_customizer_blog_posts_config' );
