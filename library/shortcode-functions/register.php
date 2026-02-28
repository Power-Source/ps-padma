<?php
/**
 * PS Padma: Register All Shortcodes
 * 
 * Unified registration for all 41 extracted shortcodes with fallback to plugin
 * Shortcodes are registered with 'su_' prefix for compatibility
 */

/**
 * Check if we should use native function or fallback
 */
function padma_get_shortcode_function( $shortcode_name ) {
	$native_function = 'padma_render_' . str_replace( '-', '_', $shortcode_name );
	
	// If native function exists, use it
	if ( function_exists( $native_function ) ) {
		return $native_function;
	}
	
	// If plugin provides the shortcode, return callback to use plugin version
	if ( shortcode_exists( 'su_' . $shortcode_name ) ) {
		// Return a wrapper to use plugin shortcode
		return function( $args, $content = '' ) use ( $shortcode_name ) {
			// Remove our shortcode temporarily to avoid recursion
			remove_shortcode( 'su_' . $shortcode_name );
			
			// Call the plugin version
			$output = do_shortcode( '[su_' . $shortcode_name . ' ' . padma_shortcode_attrs_to_string( $args ) . ']' . $content . '[/su_' . $shortcode_name . ']' );
			
			// Re-register our shortcode
			$function = padma_get_shortcode_function( $shortcode_name );
			if ( is_callable( $function ) ) {
				add_shortcode( 'su_' . $shortcode_name, $function );
			}
			
			return $output;
		};
	}
	
	// Return null if neither available
	return null;
}

/**
 * Convert shortcode attributes to string for re-registration
 */
function padma_shortcode_attrs_to_string( $attrs ) {
	$output = '';
	
	foreach ( $attrs as $key => $value ) {
		if ( is_numeric( $key ) ) {
			continue; // Skip numeric keys
		}
		$output .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}
	
	return $output;
}

/**
 * Register a single shortcode with fallback handling
 */
function padma_register_shortcode( $shortcode_name ) {
	$function = padma_get_shortcode_function( $shortcode_name );
	
	if ( is_callable( $function ) ) {
		add_shortcode( 'su_' . $shortcode_name, $function );
		
		// Also register with padma_ prefix for internal use
		add_shortcode( 'padma_' . $shortcode_name, $function );
		
		return true;
	}
	
	return false;
}

/**
 * Main registration function - called on 'init' hook
 */
function padma_register_all_shortcodes() {
	// ========================================================================
	// TYPOGRAPHY SHORTCODES (11)
	// ========================================================================
	padma_register_shortcode( 'heading' );
	padma_register_shortcode( 'divider' );
	padma_register_shortcode( 'spacer' );
	padma_register_shortcode( 'highlight' );
	padma_register_shortcode( 'label' );
	padma_register_shortcode( 'note' );
	padma_register_shortcode( 'dropcap' );
	padma_register_shortcode( 'frame' );
	padma_register_shortcode( 'pullquote' );
	padma_register_shortcode( 'list' );
	padma_register_shortcode( 'private' );
	
	// ========================================================================
	// MEDIA SHORTCODES (8)
	// ========================================================================
	padma_register_shortcode( 'youtube' );
	padma_register_shortcode( 'youtube-advanced' );
	padma_register_shortcode( 'vimeo' );
	padma_register_shortcode( 'audio' );
	padma_register_shortcode( 'video' );
	padma_register_shortcode( 'dailymotion' );
	padma_register_shortcode( 'screenr' );
	padma_register_shortcode( 'media' );
	
	// ========================================================================
	// LAYOUT SHORTCODES (2)
	// ========================================================================
	padma_register_shortcode( 'row' );
	padma_register_shortcode( 'column' );
	
	// ========================================================================
	// INTERACTIVE SHORTCODES (12)
	// ========================================================================
	padma_register_shortcode( 'members' );
	padma_register_shortcode( 'guests' );
	padma_register_shortcode( 'expand' );
	padma_register_shortcode( 'service' );
	padma_register_shortcode( 'menu' );
	padma_register_shortcode( 'document' );
	padma_register_shortcode( 'gmap' );
	padma_register_shortcode( 'table' );
	padma_register_shortcode( 'qrcode' );
	padma_register_shortcode( 'scheduler' );
	
	// ========================================================================
	// GALLERY SHORTCODES (3)
	// ========================================================================
	padma_register_shortcode( 'slider' );
	padma_register_shortcode( 'carousel' );
	padma_register_shortcode( 'custom-gallery' );
	
	// ========================================================================
	// POST/QUERY SHORTCODES (13)
	// ========================================================================
	padma_register_shortcode( 'meta' );
	padma_register_shortcode( 'user' );
	padma_register_shortcode( 'post' );
	padma_register_shortcode( 'posts' );
	padma_register_shortcode( 'template' );
	padma_register_shortcode( 'subpages' );
	padma_register_shortcode( 'siblings' );
	padma_register_shortcode( 'permalink' );
	padma_register_shortcode( 'animate' );
	padma_register_shortcode( 'dummy-text' );
	padma_register_shortcode( 'dummy-image' );
	
	// ========================================================================
	// PREVIOUSLY EXTRACTED SHORTCODES (9)
	// ========================================================================
	padma_register_shortcode( 'button' );
	padma_register_shortcode( 'accordion' );
	padma_register_shortcode( 'spoiler' );
	padma_register_shortcode( 'box' );
	padma_register_shortcode( 'quote' );
	padma_register_shortcode( 'tabs' );
	padma_register_shortcode( 'lightbox' );
	padma_register_shortcode( 'lightbox-content' );
	
	// ========================================================================
	// UTILITY SHORTCODES (2)
	// ========================================================================
	padma_register_shortcode( 'tooltip' );
	padma_register_shortcode( 'feed' );
	
	/**
	 * Hook for custom shortcode registration
	 */
	do_action( 'padma_register_shortcodes' );
}

/**
 * Register shortcodes on 'init' hook (priority 11 to run after plugins)
 */
add_action( 'init', 'padma_register_all_shortcodes', 11 );

/**
 * Fallback handler for missing shortcodes (displays warning)
 */
function padma_shortcode_fallback_handler( $shortcode_name ) {
	return function( $args, $content = '' ) use ( $shortcode_name ) {
		// In production, return empty or error message
		if ( current_user_can( 'manage_options' ) ) {
			return '<p style="color:red;font-size:11px;padding:5px;background:#ffebee;border:1px solid #f44336;">Shortcode not available: [' . esc_html( $shortcode_name ) . ']</p>';
		}
		return '';
	};
}
