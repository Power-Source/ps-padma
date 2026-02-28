<?php
/**
 * Lightbox Shortcode Functions (extracted from psource-shortcodes)
 * 
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render lightbox trigger component
 * 
 * @param array $args Lightbox configuration
 * @param string $content Lightbox trigger text/link content
 * @return string HTML lightbox trigger markup
 */
function padma_render_lightbox( $args = array(), $content = '' ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'src'   => false,
		'type'  => 'iframe',
		'class' => ''
	) );
	
	// Sanitize inputs
	$args['src']   = esc_url( $args['src'] );
	$args['type']  = sanitize_text_field( $args['type'] );
	$args['class'] = sanitize_text_field( $args['class'] );
	$content       = wp_kses_post( $content );
	
	if ( ! $args['src'] ) {
		return '';
	}
	
	// Enqueue assets
	if ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'magnific-popup' );
		su_query_asset( 'js', 'magnific-popup' );
		su_query_asset( 'js', 'jquery' );
	} else {
		wp_enqueue_style( 'padma-magnific-popup-css', get_template_directory_uri() . '/assets/psource-css/magnific-popup.css' );
		wp_enqueue_script( 'padma-magnific-popup-js', get_template_directory_uri() . '/assets/psource-js/magnific-popup.js', array( 'jquery' ) );
	}
	
	// Build HTML
	$html = '<span class="su-lightbox' . padma_ecssc( $args ) . '" data-mfp-src="' . esc_attr( $args['src'] ) . '" data-mfp-type="' . esc_attr( $args['type'] ) . '">' . do_shortcode( $content ) . '</span>';
	
	return $html;
}

/**
 * Render lightbox content container (hidden content that pops up)
 * 
 * @param array $args Lightbox content configuration
 * @param string $content Lightbox content HTML
 * @return string HTML lightbox content markup
 */
function padma_render_lightbox_content( $args = array(), $content = '' ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'id'         => '',
		'width'      => '50%',
		'margin'     => '40',
		'padding'    => '40',
		'text_align' => 'center',
		'background' => '#FFFFFF',
		'color'      => '#333333',
		'shadow'     => '0px 0px 15px #333333',
		'class'      => ''
	) );
	
	// Sanitize inputs
	$args['id']         = sanitize_html_class( $args['id'] );
	$args['width']      = sanitize_text_field( $args['width'] );
	$args['margin']     = intval( $args['margin'] );
	$args['padding']    = intval( $args['padding'] );
	$args['text_align'] = sanitize_text_field( $args['text_align'] );
	$args['background'] = padma_sanitize_color( $args['background'] );
	$args['color']      = padma_sanitize_color( $args['color'] );
	$args['shadow']     = sanitize_text_field( $args['shadow'] );
	$args['class']      = sanitize_text_field( $args['class'] );
	$content            = wp_kses_post( $content );
	
	if ( ! $args['id'] ) {
		return '';
	}
	
	// Remove # from ID if present
	$args['id'] = trim( $args['id'], '#' );
	
	// Build style attribute
	$style = sprintf(
		'display:none;width:%s;margin-top:%spx;margin-bottom:%spx;padding:%spx;background-color:%s;color:%s;box-shadow:%s;text-align:%s',
		esc_attr( $args['width'] ),
		intval( $args['margin'] ),
		intval( $args['margin'] ),
		intval( $args['padding'] ),
		esc_attr( $args['background'] ),
		esc_attr( $args['color'] ),
		esc_attr( $args['shadow'] ),
		esc_attr( $args['text_align'] )
	);
	
	// Build HTML
	$html = '<div class="su-lightbox-content' . padma_ecssc( $args ) . '" id="' . esc_attr( $args['id'] ) . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
	
	return $html;
}
