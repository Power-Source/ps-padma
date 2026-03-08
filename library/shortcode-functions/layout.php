<?php
/**
 * PS Padma: Layout Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: row, column
 */

// ============================================================================
// ROW
// ============================================================================

function padma_enqueue_layout_shortcode_assets() {
	if ( wp_style_is( 'padma-layout-shortcodes-box', 'enqueued' ) ) {
		return;
	}

	wp_enqueue_style(
		'padma-layout-shortcodes-box',
		get_template_directory_uri() . '/assets/css/psource-shortcodes/box-shortcodes.css',
		array(),
		PADMA_VERSION
	);
}

function padma_render_row( $args = array(), $content = '' ) {
	$defaults = array(
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );

	padma_enqueue_layout_shortcode_assets();
	
	return '<div class="su-row' . padma_ecssc( $args ) . '">' . do_shortcode( $content ) . '</div>';
}

// ============================================================================
// COLUMN
// ============================================================================

function padma_render_column( $args = array(), $content = '' ) {
	$defaults = array(
		'size'   => '1/2',
		'center' => 'no',
		'last'   => null,
		'class'  => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( $args['last'] !== null && $args['last'] == '1' ) {
		$args['class'] .= ' su-column-last';
	}
	
	if ( $args['center'] === 'yes' ) {
		$args['class'] .= ' su-column-centered';
	}
	
	$size_class = str_replace( '/', '-', $args['size'] );

	padma_enqueue_layout_shortcode_assets();
	
	return '<div class="su-column su-column-size-' . esc_attr( $size_class ) . padma_ecssc( $args ) . '"><div class="su-column-inner su-clearfix">' . do_shortcode( $content ) . '</div></div>';
}
