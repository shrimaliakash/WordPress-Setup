<?php
if ( ! function_exists( 'starterblog_customizer_single_related_posts_config' ) ) {
	function starterblog_customizer_single_related_posts_config( $configs = array() ) {

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
				'name'    => $level_2_panel . '_h_related',
				'type'    => 'heading',
				'section' => $level_2_panel,
				'title'   => __( 'Related Posts', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_related_title',
				'type'            => 'text',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'default'         => __( 'Related Posts', 'starter-blog' ),
				'label'           => __( 'Title', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_related_by',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'default'         => 'tag',
				'max'             => 150,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Related posts by', 'starter-blog' ),
				'choices'         => array(
					'cat' => __( 'By categories', 'starter-blog' ),
					'tag' => __( 'By tags', 'starter-blog' ),
				),
			),

			array(
				'name'            => $args['id'] . '_related_col',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'default'         => array(
					'desktop' => 3,
					'tablet'  => 3,
					'mobile'  => 1,
				),
				'max'             => 150,
				'device_settings' => true,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Number columns to show', 'starter-blog' ),
				'choices'         => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			),

			array(
				'name'            => $args['id'] . '_related_number',
				'type'            => 'text',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'default'         => 3,
				'render_callback' => $args['cb'],
				'label'           => __( 'Number posts to show', 'starter-blog' ),
				'description'     => __( 'Enter 0 to disable related posts.', 'starter-blog' ),
			),

			array(
				'name'            => $args['id'] . '_related_img_pos',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'default'         => 'top',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Image Position', 'starter-blog' ),
				'choices'         => array(
					'left'  => __( 'Left', 'starter-blog' ),
					'right' => __( 'Right', 'starter-blog' ),
					'top'   => __( 'Top', 'starter-blog' ),
				),
			),

			array(
				'name'            => $args['id'] . '_related_thumbnail_size',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'default'         => 'medium',
				'label'           => __( 'Thumbnail Size', 'starter-blog' ),
				'choices'         => starterblog_get_all_image_sizes(),
			),

			array(
				'name'            => $args['id'] . '_related_thumbnail_height',
				'type'            => 'slider',
				'section'         => $level_2_panel,
				'selector'        => 'format',
				'unit'            => '%',
				'max'             => '200',
				'default'         => '',
				'label'           => __( 'Custom Thumbnail Height', 'starter-blog' ),
				'device_settings' => true,
				'devices'         => array( 'desktop', 'tablet' ),
				'css_format'      => '.related-post .related-thumbnail a { padding-top: {{value_no_unit}}%; } .related-post .related-thumbnail img { width: 100%;position: absolute; top: 0px; right: 0px; display: block; height: 100%; object-fit: cover; }',
			),

			array(
				'name'            => $args['id'] . '_related_thumbnail_width',
				'type'            => 'slider',
				'section'         => $level_2_panel,
				'selector'        => 'format',
				'unit'            => '%',
				'max'             => '100',
				'default'         => '',
				'label'           => __( 'Custom Thumbnail Width', 'starter-blog' ),
				'device_settings' => true,
				'devices'         => array( 'desktop', 'tablet' ),
				'css_format'      => '.img-pos-left .related-thumbnail, .img-pos-right .related-thumbnail { flex-basis: {{value_no_unit}}%; } .img-pos-left .related-body, .img-pos-right .related-body { flex-basis: calc( 100% - {{value_no_unit}}% ); }',
				'required'        => array( $args['id'] . '_related_img_pos', 'in', array( 'left', 'right' ) ),
			),

			array(
				'name'            => $args['id'] . '_related_orderby',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Order by', 'starter-blog' ),
				'default'         => 'date',
				'choices'         => array(
					'date'          => __( 'Date', 'starter-blog' ),
					'title'         => __( 'Title', 'starter-blog' ),
					'menu_order'    => __( 'Post order', 'starter-blog' ),
					'rand'          => __( 'Random', 'starter-blog' ),
					'comment_count' => __( 'Comment count', 'starter-blog' ),
				),
			),

			array(
				'name'            => $args['id'] . '_related_order',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'default'         => 'desc',
				'label'           => __( 'Order', 'starter-blog' ),
				'choices'         => array(
					'desc' => __( 'Desc', 'starter-blog' ),
					'asc'  => __( 'Asc', 'starter-blog' ),
				),
			),

			array(
				'name'             => $args['id'] . '_related_meta',
				'section'          => $level_2_panel,
				'type'             => 'repeater',
				'label'            => __( 'Related post meta', 'starter-blog' ),
				'live_title_field' => 'title',
				'limit'            => 4,
				'addable'          => false,
				'title_only'       => true,
				'selector'         => $args['selector'],
				'render_callback'  => $args['cb'],
				'default'          => array(
					array(
						'_key'        => 'author',
						'title'       => __( 'Author', 'starter-blog' ),
						'_visibility' => 'hidden',
					),
					array(
						'_key'  => 'date',
						'title' => __( 'Date', 'starter-blog' ),
					),
					array(
						'_key'        => 'categories',
						'title'       => __( 'Categories', 'starter-blog' ),
						'_visibility' => 'hidden',
					),
					array(
						'_key'        => 'comment',
						'title'       => __( 'Comment', 'starter-blog' ),
						'_visibility' => 'hidden',
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
				'name'            => $args['id'] . '_related_excerpt_length',
				'type'            => 'text',
				'section'         => $level_2_panel,
				'selector'        => $args['selector'],
				'default'         => 0,
				'render_callback' => $args['cb'],
				'label'           => __( 'Excerpt length', 'starter-blog' ),
				'description'     => __( 'Custom excerpt length. Enter 0 to hide the excerpt.', 'starter-blog' ),
			),

		);

		return array_merge( $configs, $config );

	}
}

add_filter( 'starterblog/customizer/config', 'starterblog_customizer_single_related_posts_config', 399 );

