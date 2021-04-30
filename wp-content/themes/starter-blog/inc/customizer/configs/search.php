<?php
if ( ! function_exists( 'starterblog_customizer_search_config' ) ) {
	function starterblog_customizer_search_config( $configs = array() ) {

		$args = array(
			'name'     => __( 'Search Results', 'starter-blog' ),
			'id'       => 'search_results',
			'selector' => '',
			'cb'       => '',
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
				'name'            => $args['id'] . '_excerpt_type',
				'type'            => 'select',
				'section'         => $level_2_panel,
				'default'         => 'excerpt',
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
				'section'         => $level_2_panel,
				'default'         => 150,
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Excerpt Length', 'starter-blog' ),
				'required'        => array( $args['id'] . '_excerpt_type', '=', 'custom' ),
			),
			array(
				'name'            => $args['id'] . '_excerpt_more',
				'type'            => 'text',
				'section'         => $level_2_panel,
				'default'         => '',
				'selector'        => $args['selector'],
				'render_callback' => $args['cb'],
				'label'           => __( 'Excerpt More', 'starter-blog' ),
			),

		);

		return array_merge( $configs, $config );

	}
}

add_filter( 'starterblog/customizer/config', 'starterblog_customizer_search_config' );
