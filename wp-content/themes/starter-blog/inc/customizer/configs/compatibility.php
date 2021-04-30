<?php
if ( ! function_exists( 'starterblog_customizer_compatibility_config' ) ) {
	/**
	 * Add compatibility panel.
	 *
	 * @param array $configs List customize settings.
	 *
	 * @return array
	 */
	function starterblog_customizer_compatibility_config( $configs ) {

		$panel  = 'compatibility';
		$config = array(
			// Layout panel.
			array(
				'name'     => $panel . '_panel',
				'type'     => 'panel',
				'priority' => 100,
				'title'    => __( 'Compatibility', 'starter-blog' ),
			),

		);

		return array_merge( $configs, $config );
	}
}

add_filter( 'starterblog/customizer/config', 'starterblog_customizer_compatibility_config' );
