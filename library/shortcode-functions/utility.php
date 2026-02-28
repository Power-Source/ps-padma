<?php
/**
 * PS Padma: Utility Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: tooltip, feed
 */

// ============================================================================
// TOOLTIP (mit qTip2)
// ============================================================================

function padma_render_tooltip( $args = array(), $content = '' ) {
	$defaults = array(
		'style'        => 'yellow',
		'position'     => 'north',
		'shadow'       => 'no',
		'rounded'      => 'no',
		'size'         => 'default',
		'title'        => '',
		'content'      => __( 'Tooltip text', 'psource-shortcodes' ),
		'behavior'     => 'hover',
		'close'        => 'no',
		'class'        => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Prepare style
	$valid_styles = array( 'light', 'dark', 'green', 'red', 'blue', 'youtube', 'tipsy', 'bootstrap', 'jtools', 'tipped', 'cluetip' );
	$style = in_array( $args['style'], $valid_styles ) ? $args['style'] : 'plain';
	
	// Position mapping: north = top center, south = bottom center, etc.
	$position_map = array(
		'top'    => 'north',
		'right'  => 'east',
		'bottom' => 'south',
		'left'   => 'west'
	);
	
	$position = str_replace( array_keys( $position_map ), array_values( $position_map ), $args['position'] );
	
	// Position configuration for qTip2
	$position_config = array(
		'my' => str_replace( array( 'north', 'east', 'south', 'west' ), array( 'bottom center', 'center left', 'top center', 'center right' ), $position ),
		'at' => str_replace( array( 'north', 'east', 'south', 'west' ), array( 'top center', 'center right', 'bottom center', 'center left' ), $position )
	);
	
	// Prepare classes
	$classes = array( 'su-qtip qtip-' . esc_attr( $style ) );
	$classes[] = 'su-qtip-size-' . esc_attr( $args['size'] );
	if ( $args['shadow'] === 'yes' ) {
		$classes[] = 'qtip-shadow';
	}
	if ( $args['rounded'] === 'yes' ) {
		$classes[] = 'qtip-rounded';
	}
	
	// Enqueue required assets (from plugin OR local)
	if ( function_exists( 'su_query_asset' ) ) {
		// Plugin is active, use its asset system
		su_query_asset( 'css', 'qtip' );
		su_query_asset( 'css', 'su-other-shortcodes' );
		su_query_asset( 'js', 'jquery' );
		su_query_asset( 'js', 'qtip' );
		su_query_asset( 'js', 'su-other-shortcodes' );
	} else {
		// Plugin not active, use WordPress enqueue
		wp_enqueue_style( 'padma-qtip-css', get_template_directory_uri() . '/assets/psource-css/qtip.css' );
		wp_enqueue_style( 'padma-other-shortcodes-css', get_template_directory_uri() . '/assets/psource-css/other-shortcodes.css' );
		wp_enqueue_script( 'padma-qtip-js', get_template_directory_uri() . '/assets/psource-js/qtip.js', array( 'jquery' ) );
		wp_enqueue_script( 'padma-other-shortcodes-js', get_template_directory_uri() . '/assets/psource-js/other-shortcodes.js', array( 'jquery' ) );
	}
	
	return '<span class="su-tooltip' . padma_ecssc( $args ) . '" data-close="' . esc_attr( $args['close'] ) . '" data-behavior="' . esc_attr( $args['behavior'] ) . '" data-my="' . esc_attr( $position_config['my'] ) . '" data-at="' . esc_attr( $position_config['at'] ) . '" data-classes="' . esc_attr( implode( ' ', $classes ) ) . '" data-title="' . esc_attr( $args['title'] ) . '" title="' . esc_attr( $args['content'] ) . '">' . do_shortcode( $content ) . '</span>';
}

// ============================================================================
// FEED (RSS/Atom)
// ============================================================================

function padma_render_feed( $args = array(), $content = '' ) {
	$defaults = array(
		'url'   => get_bloginfo_rss( 'rss2_url' ),
		'limit' => 3,
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Ensure wp_rss is available
	if ( ! function_exists( 'wp_rss' ) ) {
		require_once ABSPATH . WPINC . '/rss.php';
	}
	
	// Start buffering output
	ob_start();
	echo '<div class="su-feed' . padma_ecssc( $args ) . '">';
	
	// Use WordPress RSS function to fetch and display feed
	wp_rss( esc_url( $args['url'] ), intval( $args['limit'] ) );
	
	echo '</div>';
	$return = ob_get_contents();
	ob_end_clean();
	
	return $return;
}
