<?php
/**
 * Accordion & Spoiler Shortcode Functions (extracted from psource-shortcodes)
 * 
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render accordion component
 * 
 * @param array $args Accordion configuration
 * @param array|string $spoilers Array of spoiler items or shortcode content string
 * @return string HTML accordion markup
 */
function padma_render_accordion( $args = array(), $spoilers = array() ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'class' => ''
	) );
	
	// If $spoilers is a string (from shortcode handler), parse it
	if ( is_string( $spoilers ) ) {
		$spoilers = padma_parse_spoilers_from_content( $spoilers );
	}
	
	if ( empty( $spoilers ) ) {
		return '';
	}
	
	// Ensure $spoilers is an array
	if ( ! is_array( $spoilers ) ) {
		return '';
	}
	
	$html = '<div class="su-accordion' . padma_ecssc( $args ) . '">';
	
	foreach ( $spoilers as $spoiler ) {
		$html .= padma_render_spoiler( $spoiler );
	}
	
	$html .= '</div>';
	
	return $html;
}

/**
 * Render spoiler (accordion item) component
 * 
 * @param array $args Spoiler configuration
 * @return string HTML spoiler markup
 */
function padma_render_spoiler( $args = array() ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'title'  => __( 'Spoiler title', 'padma-advanced' ),
		'open'   => 'no',
		'style'  => 'default',
		'icon'   => 'plus',
		'anchor' => '',
		'class'  => '',
		'content' => ''
	) );
	
	// Sanitize inputs
	$args['title']   = sanitize_text_field( $args['title'] );
	$args['style']   = sanitize_text_field( $args['style'] );
	$args['icon']    = sanitize_text_field( $args['icon'] );
	$args['anchor']  = sanitize_text_field( str_replace( array( ' ', '#' ), '', $args['anchor'] ) );
	$args['class']   = sanitize_text_field( $args['class'] );
	$args['content'] = wp_kses_post( $args['content'] );
	
	// Convert old style values
	$style_map = array(
		'1' => 'default',
		'2' => 'fancy'
	);
	$args['style'] = isset( $style_map[ $args['style'] ] ) ? $style_map[ $args['style'] ] : $args['style'];
	
	// Prepare classes
	$classes = array( 'su-spoiler', 'su-spoiler-style-' . $args['style'], 'su-spoiler-icon-' . $args['icon'] );
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}
	if ( 'yes' !== $args['open'] ) {
		$classes[] = 'su-spoiler-closed';
	}
	
	// Prepare anchor attribute
	$anchor_attr = '';
	if ( $args['anchor'] ) {
		$anchor_attr = ' data-anchor="' . esc_attr( $args['anchor'] ) . '"';
	}
	
	// Build HTML
	$html = '<div class="' . implode( ' ', array_filter( $classes ) ) . '"' . $anchor_attr . '>';
	$html .= '<div class="su-spoiler-title"><span class="su-spoiler-icon"></span>' . esc_html( $args['title'] ) . '</div>';
	$html .= '<div class="su-spoiler-content su-clearfix" style="display:none">' . do_shortcode( $args['content'] ) . '</div>';
	$html .= '</div>';
	
	return $html;
}

/**
 * Parse spoiler items from shortcode content
 * 
 * @param string $content Shortcode content containing [su_spoiler] items
 * @return array Array of parsed spoiler items
 */
function padma_parse_spoilers_from_content( $content ) {
	$spoilers = array();
	
	if ( empty( $content ) || ! is_string( $content ) ) {
		return $spoilers;
	}
	
	// Use a preg_match_all to extract [su_spoiler] tags with their attributes and content
	$pattern = '/\[su_spoiler\s+([^\]]*)\](.*?)\[\/su_spoiler\]/is';
	
	if ( preg_match_all( $pattern, $content, $matches ) ) {
		for ( $i = 0; $i < count( $matches[0] ); $i++ ) {
			$attrs = shortcode_parse_atts( $matches[1][ $i ] );
			
			$spoiler = array(
				'title'   => isset( $attrs['title'] ) ? $attrs['title'] : __( 'Spoiler title', 'padma-advanced' ),
				'content' => isset( $matches[2][ $i ] ) ? $matches[2][ $i ] : '',
				'open'    => isset( $attrs['open'] ) ? $attrs['open'] : 'no',
				'style'   => isset( $attrs['style'] ) ? $attrs['style'] : 'default',
				'icon'    => isset( $attrs['icon'] ) ? $attrs['icon'] : 'plus',
				'anchor'  => isset( $attrs['anchor'] ) ? $attrs['anchor'] : '',
				'class'   => isset( $attrs['class'] ) ? $attrs['class'] : ''
			);
			
			$spoilers[] = $spoiler;
		}
	}
	
	return $spoilers;
}
