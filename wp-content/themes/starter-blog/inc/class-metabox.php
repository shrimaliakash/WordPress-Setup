<?php

if ( is_admin() ) {
	// Calls the class on the post edit screen.
	add_action( 'load-post.php', array( 'StarterBlog_MetaBox', 'get_instance' ) );
	add_action( 'load-post-new.php', array( 'StarterBlog_MetaBox', 'get_instance' ) );
}

/**
 * The Metabox.
 */
class StarterBlog_MetaBox {

	public static $_instance = null;
	/**
	 * @see StarterBlog_Form_Fields
	 * @var StarterBlog_Form_Fields null
	 */
	public $field_builder = null;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			add_action( 'add_meta_boxes', array( self::$_instance, 'add_meta_box' ) );
			add_action( 'save_post', array( self::$_instance, 'save' ) );
			add_action( 'admin_enqueue_scripts', array( self::$_instance, 'scripts' ) );
			require_once get_template_directory() . '/inc/class-metabox-fields.php';
			self::$_instance->field_builder = new StarterBlog_Form_Fields();
			self::$_instance->fields_config();
			do_action( 'starterblog/metabox/init', self::$_instance );

		}

		return self::$_instance;
	}

	/**
	 * Add metabox fields
	 *
	 * @since 1.5.0
	 */
	function fields_config() {

		$this->field_builder->add_tab(
			'layout',
			array(
				'title' => __( 'Layout', 'starter-blog' ),
				'icon'  => 'dashicons dashicons-grid-view',
			)
		);

		$this->field_builder->add_tab(
			'page_header',
			array(
				'title' => __( 'Page Header', 'starter-blog' ),
				'icon'  => 'dashicons dashicons-editor-kitchensink',
			)
		);

		$this->field_builder->add_field(
			array(
				'title'        => __( 'Content Layout', 'starter-blog' ),
				'name'         => 'content_layout',
				'tab'          => 'layout',
				'type'         => 'select',
				'choices'      => array(
					'full-width'     => __( 'Full Width', 'starter-blog' ),
					'full-stretched' => __( 'Full Width - Stretched', 'starter-blog' ),
				),
				'show_default' => true,
			)
		);

		$this->field_builder->add_field(
			array(
				'title'         => __( 'Sidebar', 'starter-blog' ),
				'name'          => 'sidebar',
				'tab'           => 'layout',
				'type'          => 'select',
				'choices'       => starterblog_get_config_sidebar_layouts(),
				'show_default'  => true,
				'default_label' => __( 'Inherit from customize settings', 'starter-blog' ),
			)
		);
		$disable_elements_choices = array(
			'disable_header'        => __( 'Disable Header', 'starter-blog' ),
			'disable_page_title'    => __( 'Disable Title', 'starter-blog' ),
		);

		$disable_elements_choices['disable_header_top'] = __( 'Disable Header Top', 'starter-blog' );
		$disable_elements_choices['disable_header_main'] = __( 'Disable Header Main', 'starter-blog' );
		$disable_elements_choices['disable_header_bottom'] = __( 'Disable Header Bottom', 'starter-blog' );

		if ( class_exists( 'StarterBlog_Pro' ) ) {
			$disable_elements_choices['disable_footer_top'] = __( 'Disable Footer Top', 'starter-blog' );
		}
		$disable_elements_choices['disable_footer_main'] = __( 'Disable Footer Main', 'starter-blog' );
		$disable_elements_choices['disable_footer_bottom'] = __( 'Disable Footer Bottom', 'starter-blog' );
		$this->field_builder->add_field(
			array(
				'title'   => __( 'Disable Elements', 'starter-blog' ),
				'name'    => 'disable_elements',
				'tab'     => 'layout',
				'type'    => 'multiple_checkbox',
				'choices' => $disable_elements_choices,
			)
		);

		$this->field_builder->add_field(
			array(
				'title'   => __( 'Display', 'starter-blog' ),
				'name'    => 'page_header_display',
				'tab'     => 'page_header',
				'type'    => 'select',
				'choices' => array(
					'default'  => __( 'Inherit from customize settings', 'starter-blog' ),
					'normal'   => __( 'Default', 'starter-blog' ),
					'cover'    => __( 'Cover', 'starter-blog' ),
					'titlebar' => __( 'Titlebar', 'starter-blog' ),
					'none'     => __( 'Hide', 'starter-blog' ),
				),
			)
		);

		if ( StarterBlog_Breadcrumb::get_instance()->support_plugins_active() ) {
			$this->field_builder->add_tab(
				'breadcrumb',
				array(
					'title' => __( 'Breadcrumb', 'starter-blog' ),
					'icon'  => 'dashicons dashicons-admin-links',
				)
			);
			$this->field_builder->add_field(
				array(
					'title'   => __( 'Breadcrumb', 'starter-blog' ),
					'tab'     => 'breadcrumb',
					'name'    => 'breadcrumb_display',
					'type'    => 'select',
					'choices' => array(
						'default' => __( 'Inherit from customize settings', 'starter-blog' ),
						'hide'    => __( 'Hide', 'starter-blog' ),
						'show'    => __( 'Show', 'starter-blog' ),
					),
				)
			);
		}

	}

	public function scripts( $hook ) {
		if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
			return;
		}
		$suffix = StarterBlog()->get_asset_suffix();
		wp_enqueue_script( 'starterblog-metabox', esc_url( get_template_directory_uri() ) . '/assets/js/admin/metabox' . $suffix . '.js', array( 'jquery' ), StarterBlog::$version, true );
		wp_enqueue_style( 'starterblog-metabox', esc_url( get_template_directory_uri() ) . '/assets/css/admin/metabox' . $suffix . '.css', false, StarterBlog::$version );
	}

	public function get_support_post_types() {
		$args = array(
			'public' => true,
		);

		$output     = 'names'; // Names or objects, note names is the default.
		$operator   = 'and'; // Can use 'and' or 'or'.
		$post_types = get_post_types( $args, $output, $operator );

		return array_values( $post_types );
	}

	/**
	 * Adds the meta box container.
	 *
	 * @param string $post_type Post Type.
	 */
	public function add_meta_box( $post_type ) {
		// Limit meta box to certain post types.
		$post_types = $this->get_support_post_types();
		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'starterblog_page_settings',
				__( 'Starter Blog Settings', 'starter-blog' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return int|bool
	 */
	public function save( $post_id ) {

		/**
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */
		if ( ! isset( $_POST['starterblog_page_settings_nonce'] ) ) { // Check if our nonce is set.
			return $post_id;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['starterblog_page_settings_nonce'] ) );

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'starterblog_page_settings' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == get_post_type( $post_id ) ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/**
		 * Field Builder
		 *
		 * @since 1.5.0
		 */
		$settings = $this->field_builder->get_submitted_values();

		foreach ( $settings as $key => $value ) {
			if ( ! is_array( $value ) ) {
				$value = wp_kses_post( $value );
			} else {
				$value = array_map( 'wp_kses_post', $value );
			}
			// Update the meta field.
			update_post_meta( $post_id, '_starterblog_' . $key, $value );
		}

	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'starterblog_page_settings', 'starterblog_page_settings_nonce' );
		$values = array();
		foreach ( $this->field_builder->get_all_fields() as $key => $f ) {
			if ( 'multiple_checkbox' == $f['type'] ) {
				foreach ( (array) $f['choices'] as $_key => $label ) {
					$value           = get_post_meta( $post->ID, '_starterblog_' . $_key, true );
					$values[ $_key ] = $value;
				}
			} elseif ( $f['name'] ) {
				$values[ $f['name'] ] = get_post_meta( $post->ID, '_starterblog_' . $f['name'], true );
			}
		}

		$this->field_builder->set_values( $values );
		$this->field_builder->render();

	}
}
