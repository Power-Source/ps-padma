<?php
/**
 * PS Padma: Post & Content Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: posts, post, meta, user, post_terms, template, feed, subpages, siblings, permalink, dummy_text, dummy_image, animate
 */

// ============================================================================
// META (post metadata)
// ============================================================================

function padma_render_meta( $args = array(), $content = '' ) {
	$defaults = array(
		'key'      => '',
		'default'  => '',
		'before'   => '',
		'after'    => '',
		'post_id'  => '',
		'filter'   => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['post_id'] ) ) {
		$args['post_id'] = get_the_ID();
	}
	
	if ( ! is_numeric( $args['post_id'] ) || $args['post_id'] < 1 ) {
		return '<p class="su-error">' . esc_html__( 'Meta: post ID is incorrect', 'ps-padma' ) . '</p>';
	}
	
	if ( empty( $args['key'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Meta: please specify meta key name', 'ps-padma' ) . '</p>';
	}
	
	$meta = get_post_meta( $args['post_id'], $args['key'], true );
	
	if ( ! $meta ) {
		$meta = $args['default'];
	}
	
	// Apply custom filter
	if ( ! empty( $args['filter'] ) && function_exists( $args['filter'] ) ) {
		$meta = call_user_func( $args['filter'], $meta );
	}
	
	return ( $meta ) ? $args['before'] . $meta . $args['after'] : '';
}

// ============================================================================
// USER (user field)
// ============================================================================

function padma_render_user( $args = array(), $content = '' ) {
	$defaults = array(
		'field'    => 'display_name',
		'default'  => '',
		'before'   => '',
		'after'    => '',
		'user_id'  => '',
		'filter'   => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( $args['field'] === 'user_pass' ) {
		return '<p class="su-error">' . esc_html__( 'User: password field is not allowed', 'ps-padma' ) . '</p>';
	}
	
	if ( empty( $args['user_id'] ) ) {
		$args['user_id'] = get_current_user_id();
	}
	
	if ( ! is_numeric( $args['user_id'] ) || $args['user_id'] < 0 ) {
		return '<p class="su-error">' . esc_html__( 'User: user ID is incorrect', 'ps-padma' ) . '</p>';
	}
	
	$user = get_user_by( 'id', $args['user_id'] );
	$field = $args['field'];
	$user_value = ( $user && isset( $user->data->$field ) ) ? $user->data->$field : $args['default'];
	
	// Apply custom filter
	if ( ! empty( $args['filter'] ) && function_exists( $args['filter'] ) ) {
		$user_value = call_user_func( $args['filter'], $user_value );
	}
	
	return ( $user_value ) ? $args['before'] . $user_value . $args['after'] : '';
}

// ============================================================================
// POST (post field)
// ============================================================================

function padma_render_post( $args = array(), $content = '' ) {
	$defaults = array(
		'field'    => 'post_title',
		'default'  => '',
		'before'   => '',
		'after'    => '',
		'post_id'  => '',
		'filter'   => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['post_id'] ) ) {
		$args['post_id'] = get_the_ID();
	}
	
	if ( ! is_numeric( $args['post_id'] ) || $args['post_id'] < 1 ) {
		return '<p class="su-error">' . esc_html__( 'Post: post ID is incorrect', 'ps-padma' ) . '</p>';
	}
	
	$post = get_post( $args['post_id'] );
	$field = $args['field'];
	$post_value = ( ! empty( $post ) && ! empty( $post->$field ) ) ? $post->$field : $args['default'];
	
	// Apply custom filter
	if ( ! empty( $args['filter'] ) && function_exists( $args['filter'] ) ) {
		$post_value = call_user_func( $args['filter'], $post_value );
	}
	
	return ( $post_value ) ? $args['before'] . $post_value . $args['after'] : '';
}

// ============================================================================
// POSTS (query & display)
// ============================================================================

function padma_render_posts( $args = array(), $content = '' ) {
	$defaults = array(
		'template'            => 'templates/default-loop.php',
		'id'                  => '',
		'posts_per_page'      => get_option( 'posts_per_page' ),
		'post_type'           => 'post',
		'taxonomy'            => 'category',
		'tax_term'            => '',
		'tax_operator'        => 'IN',
		'author'              => '',
		'tag'                 => '',
		'meta_key'            => '',
		'offset'              => 0,
		'order'               => 'DESC',
		'orderby'             => 'date',
		'post_parent'         => '',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 'no'
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Build query args
	$query_args = array(
		'post_type'      => explode( ',', $args['post_type'] ),
		'posts_per_page' => intval( $args['posts_per_page'] ),
		'order'          => $args['order'],
		'orderby'        => $args['orderby'],
		'offset'         => intval( $args['offset'] ),
		'post_status'    => 'publish'
	);
	
	if ( ! empty( $args['id'] ) ) {
		$query_args['post__in'] = array_map( 'intval', explode( ',', $args['id'] ) );
	}
	
	if ( ! empty( $args['author'] ) ) {
		$query_args['author'] = intval( $args['author'] );
	}
	
	if ( ! empty( $args['tag'] ) ) {
		$query_args['tag'] = $args['tag'];
	}
	
	if ( ! empty( $args['meta_key'] ) ) {
		$query_args['meta_key'] = $args['meta_key'];
	}
	
	if ( ! empty( $args['post_parent'] ) ) {
		if ( $args['post_parent'] === 'current' ) {
			global $post;
			$args['post_parent'] = $post->ID;
		}
		$query_args['post_parent'] = intval( $args['post_parent'] );
	}
	
	// Taxonomy query
	if ( ! empty( $args['taxonomy'] ) && ! empty( $args['tax_term'] ) ) {
		$tax_terms = array_map( 'trim', explode( ',', $args['tax_term'] ) );
		$tax_operator = in_array( $args['tax_operator'], array( 'IN', 'NOT IN', 'AND' ) ) ? $args['tax_operator'] : 'IN';
		
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $args['taxonomy'],
				'field'    => is_numeric( $tax_terms[0] ) ? 'id' : 'slug',
				'terms'    => $tax_terms,
				'operator' => $tax_operator
			)
		);
	}
	
	$ignore_sticky = ( $args['ignore_sticky_posts'] === 'yes' ) ? true : false;
	if ( $ignore_sticky ) {
		$query_args['ignore_sticky_posts'] = true;
	}
	
	// Query posts
	global $wp_query;
	$original_query = $wp_query;
	$wp_query = new WP_Query( $query_args );
	
	// Get template
	ob_start();
	
	if ( file_exists( STYLESHEETPATH . '/' . $args['template'] ) ) {
		load_template( STYLESHEETPATH . '/' . $args['template'], false );
	} elseif ( file_exists( TEMPLATEPATH . '/' . $args['template'] ) ) {
		load_template( TEMPLATEPATH . '/' . $args['template'], false );
	} else {
		echo '<p class="su-error">' . esc_html__( 'Posts: template not found', 'ps-padma' ) . '</p>';
	}
	
	$output = ob_get_clean();
	
	// Restore original query
	$wp_query = $original_query;
	wp_reset_postdata();
	
	return $output;
}

// ============================================================================
// TEMPLATE (load template part)
// ============================================================================

function padma_render_template( $args = array(), $content = '' ) {
	$defaults = array(
		'name' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['name'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Template: please specify template name', 'ps-padma' ) . '</p>';
	}
	
	ob_start();
	get_template_part( str_replace( '.php', '', $args['name'] ) );
	$output = ob_get_clean();
	
	return $output;
}

// ============================================================================
// SUBPAGES
// ============================================================================

function padma_render_subpages( $args = array(), $content = '' ) {
	$defaults = array(
		'depth' => 1,
		'p'     => '',
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	$child_of = ! empty( $args['p'] ) ? intval( $args['p'] ) : get_the_ID();
	
	$pages = wp_list_pages( array(
		'title_li' => '',
		'echo'     => 0,
		'child_of' => $child_of,
		'depth'    => intval( $args['depth'] )
	) );
	
	return ( $pages ) ? '<ul class="su-subpages' . padma_ecssc( $args ) . '">' . $pages . '</ul>' : '';
}

// ============================================================================
// SIBLINGS
// ============================================================================

function padma_render_siblings( $args = array(), $content = '' ) {
	$defaults = array(
		'depth' => 1,
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	global $post;
	
	if ( empty( $post ) ) {
		return '';
	}
	
	$pages = wp_list_pages( array(
		'title_li' => '',
		'echo'     => 0,
		'child_of' => $post->post_parent,
		'depth'    => intval( $args['depth'] ),
		'exclude'  => $post->ID
	) );
	
	return ( $pages ) ? '<ul class="su-siblings' . padma_ecssc( $args ) . '">' . $pages . '</ul>' : '';
}

// ============================================================================
// PERMALINK
// ============================================================================

function padma_render_permalink( $args = array(), $content = '' ) {
	$defaults = array(
		'id'     => 1,
		'target' => 'self',
		'class'  => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['id'] = intval( $args['id'] );
	
	$text = ! empty( $content ) ? $content : get_the_title( $args['id'] );
	$url = get_permalink( $args['id'] );
	
	if ( ! $url ) {
		return '';
	}
	
	return '<a href="' . esc_url( $url ) . '" class="' . padma_ecssc( $args ) . '" title="' . esc_attr( $text ) . '" target="_' . esc_attr( $args['target'] ) . '">' . esc_html( $text ) . '</a>';
}

// ============================================================================
// ANIMATE
// ============================================================================

function padma_render_animate( $args = array(), $content = '' ) {
	$defaults = array(
		'type'     => 'bounceIn',
		'duration' => 1,
		'delay'    => 0,
		'inline'   => 'no',
		'class'    => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['duration'] = intval( $args['duration'] );
	$args['delay'] = intval( $args['delay'] );
	
	$tag = ( $args['inline'] === 'yes' ) ? 'span' : 'div';
	
	$time = '-webkit-animation-duration:' . $args['duration'] . 's;-webkit-animation-delay:' . $args['delay'] . 's;animation-duration:' . $args['duration'] . 's;animation-delay:' . $args['delay'] . 's;';
	
	return '<' . $tag . ' class="su-animate' . padma_ecssc( $args ) . '" style="visibility:hidden;' . $time . '" data-animation="' . esc_attr( $args['type'] ) . '" data-duration="' . $args['duration'] . '" data-delay="' . $args['delay'] . '">' . do_shortcode( $content ) . '</' . $tag . '>';
}

// ============================================================================
// DUMMY TEXT & DUMMY IMAGE (deprecated but included)
// ============================================================================

function padma_render_dummy_text( $args = array(), $content = '' ) {
	$defaults = array(
		'amount' => 1,
		'what'   => 'paras',
		'class'  => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<div class="su-dummy-text' . padma_ecssc( $args ) . '"><p>' . esc_html__( 'Placeholder text. Use real content for production.', 'ps-padma' ) . '</p></div>';
}

function padma_render_dummy_image( $args = array(), $content = '' ) {
	$defaults = array(
		'width'  => 500,
		'height' => 300,
		'theme'  => 'any',
		'class'  => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 ' . intval( $args['width'] ) . ' ' . intval( $args['height'] ) . '\'%3E%3Crect fill=\'%23ccc\' width=\'' . intval( $args['width'] ) . '\' height=\'' . intval( $args['height'] ) . '\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%23999\'%3EPlaceholder%3C/text%3E%3C/svg%3E" alt="' . esc_attr__( 'Dummy image', 'ps-padma' ) . '" width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" class="su-dummy-image' . padma_ecssc( $args ) . '" />';
}
