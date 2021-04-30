<?php

/**
 * Alias of class StarterBlog_Post_Entry
 *
 * @return StarterBlog_Post_Entry
 */
function StarterBlog_Post_Entry() {
	return StarterBlog_Post_Entry::get_instance();
}

/**
 * Filter to search results
 *
 * @TOTO: do not apply for WooCommerce results page.
 *
 * @param array $classes
 *
 * @return array
 */
function starterblog_post_classes( $classes ) {
	if ( is_search() && get_query_var( 'post_type' ) != 'product' ) {
		return array( 'entry', 'hentry', 'search-article' );
	}

	return $classes;
}

add_filter( 'post_class', 'starterblog_post_classes', 999 );


if ( ! function_exists( 'starterblog_blog_posts_heading' ) ) {
	function starterblog_blog_posts_heading() {
		if ( starterblog_is_post_title_display() ) {
			if ( is_search() ) {
				?>
				<header class="blog-posts-heading">
					<h1 class="page-title">
						<?php
						printf( // WPCS: XSS ok.
							__( 'Search Results for: %s', 'starter-blog' ),
							'<span>' . get_search_query() . '</span>'
						);
						?>
					</h1>
				</header>
				<?php
			} elseif ( is_archive() ) {
				?>
				<header class="page-header blog-posts-heading">
					<?php
					the_archive_title( '<h1 class="page-title h3">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</header><!-- .page-header -->
				<?php
			} elseif ( starterblog_is_post_title_display() && ! ( is_front_page() && is_home() ) ) {
				?>
				<header class="blog-posts-heading">
					<h1 class="page-title"><?php echo get_the_title( starterblog_get_support_meta_id() ); ?></h1>
				</header>
				<?php
			}
		}
	}
}


if ( ! function_exists( 'starterblog_blog_posts' ) ) {
	/**
	 * Display blog posts layout
	 *
	 * @param array $args
	 */
	function starterblog_blog_posts( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'el_id'  => 'blog-posts',
				'prefix' => 'blog_post',
			)
		);

		$render_class = apply_filters( 'starterblog/blog/render_callback', 'StarterBlog_Posts_Layout' );

		echo '<div id="' . esc_attr( $args['el_id'] ) . '">';
		if ( have_posts() ) {
			if ( class_exists( $render_class ) ) {
				$l = new $render_class();
				if ( method_exists( $l, 'render' ) ) {
					call_user_func_array( array( $l, 'render' ), array( $args ) );
				}
			}
		} else {
			get_template_part( 'template-parts/content', 'none' );
		};
		echo '</div>';
	}
}

if ( ! function_exists( 'starterblog_single_post' ) ) {
	function starterblog_single_post() {
		the_post();
		$fields = StarterBlog()->get_setting( 'single_blog_post_items' );
		$args   = array(
			'meta_sep'       => StarterBlog()->get_setting( 'single_blog_post_meta_sep' ),
			'meta_config'    => StarterBlog()->get_setting( 'single_blog_post_meta_config' ),
			'author_avatar'  => StarterBlog()->get_setting( 'single_blog_post_author_avatar' ),
			'avatar_size'    => 32,
			'thumbnail_size' => StarterBlog()->get_setting( 'single_blog_post_thumbnail_size' ),
		);

		$size = StarterBlog()->get_setting( 'single_blog_post_avatar_size' );
		if ( is_array( $size ) && isset( $size['value'] ) ) {
			$args['avatar_size'] = absint( $size['value'] );
		}
		StarterBlog_Post_Entry()->set_config( $args );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-single' ); ?>>
			<?php StarterBlog_Post_Entry()->build_fields( $fields ); ?>
		</article>
		<?php

	}
}

