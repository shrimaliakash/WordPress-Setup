<?php
if ( ! function_exists( 'starterblog_customizer_single_blog_config' ) ) {
	function starterblog_customizer_single_blog_config( $configs = array() ) {

		$args = array(
			'name'     => __( 'Single Blog Post', 'starter-blog' ),
			'id'       => 'single_blog_post',
			'selector' => '.entry.entry-single',
			'cb'       => 'starterblog_single_post',
		);

		$top_panel     = 'blog_panel';
		$level_2_panel = 'section_' . $args['id'];

		$config = array(
			array(
				'name'  => $level_2_panel,
				'type'  => 'section',
				'panel' => $top_panel,
				'title' => $args['name'],
			),

			array(
				'name'       => $args['id'] . '_content_width',
				'section'    => $level_2_panel,
				'type'       => 'slider',
				'max'        => 1200,
				'label'      => __( 'Content Max Width', 'starter-blog' ),
				'selector'   => '.single-post .content-inner',
				'css_format' => 'max-width: {{value}};',
			),

			array(
				'name'             => $args['id'] . '_items',
				'section'          => $level_2_panel,
				'type'             => 'repeater',
				'title'            => __( 'Items Display', 'starter-blog' ),
				'live_title_field' => 'title',
				'addable'          => false,
				'title_only'       => true,
				'selector'         => $args['selector'],
				'render_callback'  => $args['cb'],
				'default'          => array(
					array(
						'_visibility' => '',
						'_key'        => 'title',
						'title'       => __( 'Title', 'starter-blog' ),
					),
					array(
						'_key'        => 'meta',
						'_visibility' => '',
						'title'       => __( 'Meta', 'starter-blog' ),
					),
					array(
						'_key'        => 'thumbnail',
						'_visibility' => '',
						'title'       => __( 'Thumbnail', 'starter-blog' ),
					),
					array(
						'_key'        => 'content',
						'_visibility' => '',
						'title'       => __( 'Content', 'starter-blog' ),
					),
					array(
						'_key'        => 'categories',
						'_visibility' => 'hidden',
						'title'       => __( 'Categories', 'starter-blog' ),
					),
					array(
						'_key'        => 'tags',
						'_visibility' => '',
						'title'       => __( 'Tags', 'starter-blog' ),
					),
					array(
						'_key'        => 'author_bio',
						'_visibility' => 'hidden',
						'title'       => __( 'Author Biography', 'starter-blog' ),
					),
					array(
						'_key'        => 'navigation',
						'_visibility' => '',
						'title'       => __( 'Post Navigation', 'starter-blog' ),
					),

					array(
						'_key'        => 'related',
						'_visibility' => 'hidden',
						'title'       => __( 'Related Posts', 'starter-blog' ),
					),

					array(
						'_key'        => 'comment_form',
						'_visibility' => '',
						'title'       => __( 'Comment Form', 'starter-blog' ),
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
				'name'            => $args['id'] . '_thumbnail_size',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'default'         => 'large',
				'label'           => __( 'Thumbnail Size', 'starter-blog' ),
				'choices'         => starterblog_get_all_image_sizes(),
			),

			array(
				'name'    => $level_2_panel . '_h_meta',
				'type'    => 'heading',
				'section' => $level_2_panel,
				'title'   => __( 'Meta Settings', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_meta_sep',
				'section'         => $level_2_panel,
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
				'section'          => $level_2_panel,
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
				'section'         => $level_2_panel,
				'default'         => 0,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'checkbox_label'  => __( 'Show author avatar', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_avatar_size',
				'type'            => 'slider',
				'section'         => $level_2_panel,
				'default'         => 32,
				'max'             => 150,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Avatar Size', 'starter-blog' ),
				'required'        => array( $args['id'] . '_author_avatar', '==', '1' ),
			),

		);

		return array_merge( $configs, $config );

	}
}

add_filter( 'starterblog/customizer/config', 'starterblog_customizer_single_blog_config' );

