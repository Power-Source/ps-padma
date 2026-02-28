<?php
/**
 * Quote Shortcode Function (extracted from psource-shortcodes)
 * 
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render quote component
 * 
 * @param array $args Quote configuration
 * @param string $content Quote text content
 * @return string HTML quote markup
 */
function padma_render_quote( $args = array(), $content = '' ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'style' => 'default',
		'cite'  => false,
		'url'   => false,
		'class' => ''
	) );
	
	// Sanitize inputs
	$args['style'] = sanitize_text_field( $args['style'] );
	$args['cite']  = sanitize_text_field( $args['cite'] );
	$args['url']   = esc_url( $args['url'] );
	$args['class'] = sanitize_text_field( $args['class'] );
	$content       = wp_kses_post( $content );
	
	// Build cite link or text
	$cite_html = '';
	if ( $args['cite'] ) {
		if ( $args['url'] ) {
			$cite_html = '<a href="' . $args['url'] . '" target="_blank">' . esc_html( $args['cite'] ) . '</a>';
		} else {
			$cite_html = esc_html( $args['cite'] );
		}
		$cite_html = '<span class="su-quote-cite">' . $cite_html . '</span>';
	}
	
	// Prepare classes
	$classes = array( 'su-quote', 'su-quote-style-' . $args['style'] );
	if ( $args['cite'] ) {
		$classes[] = 'su-quote-has-cite';
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}
	
	// Build HTML
	$html = '<div class="' . implode( ' ', array_filter( $classes ) ) . '">';
	$html .= '<div class="su-quote-inner su-clearfix">';
	$html .= do_shortcode( $content );
	$html .= $cite_html;
	$html .= '</div>';
	$html .= '</div>';
	
	return $html;
}
