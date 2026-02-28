<?php
/**
 * Box Shortcode Function (extracted from psource-shortcodes)
 * 
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render box component
 * 
 * @param array $args Box configuration
 * @param string $content Box content
 * @return string HTML box markup
 */
function padma_render_box( $args = array(), $content = '' ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'title'       => __( 'This is box title', 'padma-advanced' ),
		'style'       => 'default',
		'box_color'   => '#333333',
		'title_color' => '#FFFFFF',
		'radius'      => '3',
		'class'       => ''
	) );
	
	// Sanitize inputs
	$args['title']       = sanitize_text_field( $args['title'] );
	$args['style']       = sanitize_text_field( $args['style'] );
	$args['box_color']   = padma_sanitize_color( $args['box_color'] );
	$args['title_color'] = padma_sanitize_color( $args['title_color'] );
	$args['radius']      = is_numeric( $args['radius'] ) ? intval( $args['radius'] ) : 0;
	$args['class']       = sanitize_text_field( $args['class'] );
	$content              = wp_kses_post( $content );
	
	// Calculate inner radius
	$inner_radius = $args['radius'] > 2 ? $args['radius'] - 2 : 0;
	
	// Build border color (darker version of box color)
	$border_color = padma_hex_shift( $args['box_color'], 'darker', 20 );
	
	// Build HTML
	$html = sprintf(
		'<div class="su-box su-box-style-%s%s" style="border-color:%s;border-radius:%spx"><div class="su-box-title" style="background-color:%s;color:%s;border-top-left-radius:%spx;border-top-right-radius:%spx">%s</div><div class="su-box-content su-clearfix" style="border-bottom-left-radius:%spx;border-bottom-right-radius:%spx">%s</div></div>',
		esc_attr( $args['style'] ),
		padma_ecssc( $args ),
		esc_attr( $border_color ),
		intval( $args['radius'] ),
		esc_attr( $args['box_color'] ),
		esc_attr( $args['title_color'] ),
		intval( $inner_radius ),
		intval( $inner_radius ),
		esc_html( $args['title'] ),
		intval( $inner_radius ),
		intval( $inner_radius ),
		do_shortcode( $content )
	);
	
	return $html;
}
