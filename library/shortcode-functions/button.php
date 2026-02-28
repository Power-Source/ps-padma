<?php
/**
 * Button Shortcode Function (extracted from psource-shortcodes)
 * 
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render button component
 * 
 * Used by button block instead of [su_button] shortcode
 * Allows full Visual Editor styling integration
 *
 * @param array $args Button configuration
 * @param string $content Button text content
 * @return string HTML button markup
 */
function padma_render_button( $args = array(), $content = '' ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'url'         => home_url(),
		'target'      => 'self',
		'style'       => 'default',
		'background'  => '#2D89EF',
		'color'       => '#FFFFFF',
		'size'        => 3,
		'wide'        => 'no',
		'center'      => 'no',
		'radius'      => 'auto',
		'icon'        => false,
		'icon_color'  => '#FFFFFF',
		'text_shadow' => 'none',
		'desc'        => '',
		'onclick'     => '',
		'rel'         => '',
		'title'       => '',
		'id'          => '',
		'class'       => ''
	) );
	
	// Sanitize inputs
	$args['url']        = esc_url( $args['url'] );
	$args['background'] = padma_sanitize_color( $args['background'] );
	$args['color']      = padma_sanitize_color( $args['color'] );
	$args['icon_color'] = padma_sanitize_color( $args['icon_color'] );
	$args['target']     = in_array( $args['target'], array( 'blank', '_blank' ) ) ? 'blank' : 'self';
	$args['style']      = sanitize_text_field( $args['style'] );
	$args['size']       = max( 1, intval( $args['size'] ) );
	$args['onclick']    = sanitize_text_field( $args['onclick'] );
	$args['rel']        = sanitize_text_field( $args['rel'] );
	$args['title']      = sanitize_text_field( $args['title'] );
	$args['desc']       = wp_kses_post( $args['desc'] );
	$args['id']         = sanitize_key( $args['id'] );
	$args['class']      = sanitize_text_field( $args['class'] );
	
	// Text shadow values
	$shadows = array(
		'none'         => '0 0',
		'top'          => '0 -1px',
		'right'        => '1px 0',
		'bottom'       => '0 1px',
		'left'         => '-1px 0',
		'top-right'    => '1px -1px',
		'top-left'     => '-1px -1px',
		'bottom-right' => '1px 1px',
		'bottom-left'  => '-1px 1px'
	);
	
	// Common styles for button
	$font_size = round( ( $args['size'] + 7 ) * 1.3 );
	$ts_color  = ( $args['text_shadow'] === 'light' ) ? padma_hex_shift( $args['background'], 'lighter', 50 ) : padma_hex_shift( $args['background'], 'darker', 40 );
	$ts_pos    = ( isset( $shadows[ $args['text_shadow'] ] ) ) ? $shadows[ $args['text_shadow'] ] : $shadows['none'];
	
	// Calculate border-radius
	if ( 'auto' === $args['radius'] ) {
		$radius = round( $args['size'] + 2 ) . 'px';
	} elseif ( 'round' === $args['radius'] ) {
		$radius = round( ( ( $args['size'] * 2 ) + 2 ) * 2 + $font_size ) . 'px';
	} elseif ( is_numeric( $args['radius'] ) ) {
		$radius = intval( $args['radius'] ) . 'px';
	} else {
		$radius = '0px';
	}
	
	// CSS rules for <a> tag
	$a_rules = array(
		'color'                 => $args['color'],
		'background-color'      => $args['background'],
		'border-color'          => padma_hex_shift( $args['background'], 'darker', 20 ),
		'border-radius'         => $radius,
		'-moz-border-radius'    => $radius,
		'-webkit-border-radius' => $radius
	);
	
	// CSS rules for <span> tag
	$padding_vertical   = round( ( $args['size'] ) / 2 + 4 );
	$padding_horizontal = round( $args['size'] * 2 + 10 );
	$line_height        = ( $args['icon'] ) ? round( $font_size * 1.5 ) : round( $font_size * 2 );
	
	$span_rules = array(
		'color'                 => $args['color'],
		'padding'               => ( $args['icon'] ) ? $padding_vertical . 'px ' . $padding_horizontal . 'px' : '0px ' . $padding_horizontal . 'px',
		'font-size'             => $font_size . 'px',
		'line-height'           => $line_height . 'px',
		'border-color'          => padma_hex_shift( $args['background'], 'lighter', 30 ),
		'border-radius'         => $radius,
		'-moz-border-radius'    => $radius,
		'-webkit-border-radius' => $radius,
		'text-shadow'           => $ts_pos . ' 1px ' . $ts_color,
		'-moz-text-shadow'      => $ts_pos . ' 1px ' . $ts_color,
		'-webkit-text-shadow'   => $ts_pos . ' 1px ' . $ts_color
	);
	
	// CSS rules for <img> tag
	$img_size = round( $font_size * 1.5 );
	$img_rules = array(
		'width'  => $img_size . 'px',
		'height' => $img_size . 'px'
	);
	
	// CSS rules for <small> tag
	$small_rules = array(
		'padding-bottom' => $padding_vertical . 'px',
		'color'          => $args['color']
	);
	
	// Create style attribute values
	$a_style = padma_build_style_attr( $a_rules );
	$span_style = padma_build_style_attr( $span_rules );
	$img_style = padma_build_style_attr( $img_rules );
	$small_style = padma_build_style_attr( $small_rules );
	
	// Prepare button classes
	$classes = array( 'su-button', 'su-button-style-' . $args['style'] );
	if ( ! empty( $args['class'] ) ) {
		$classes[] = $args['class'];
	}
	if ( 'yes' === $args['wide'] ) {
		$classes[] = 'su-button-wide';
	}
	
	// Prepare icon
	$icon = '';
	if ( $args['icon'] ) {
		$icon = padma_get_icon( $args['icon'], $font_size, $args['icon_color'] );
		if ( ! $icon && filter_var( $args['icon'], FILTER_VALIDATE_URL ) ) {
			$icon = '<img src="' . esc_url( $args['icon'] ) . '" alt="" style="' . $img_style . '" />';
		}
	}
	
	// Prepare <small> with description
	$desc_html = '';
	if ( ! empty( $args['desc'] ) ) {
		$desc_html = '<small style="' . $small_style . '">' . padma_scattr( $args['desc'] ) . '</small>';
	}
	
	// Wrap with div if button centered
	$before = '';
	$after = '';
	if ( 'yes' === $args['center'] ) {
		$before = '<div class="su-button-center">';
		$after = '</div>';
	}
	
	// Replace icon marker in content or append to begin
	if ( strpos( $content, '%icon%' ) !== false ) {
		$content = str_replace( '%icon%', $icon, $content );
		$classes[] = 'su-button-float-icon';
	} else {
		$content = $icon . ' ' . $content;
	}
	
	// Build attributes
	$onclick_attr = $args['onclick'] ? ' onclick="' . esc_attr( $args['onclick'] ) . '"' : '';
	$rel_attr     = $args['rel'] ? ' rel="' . esc_attr( $args['rel'] ) . '"' : '';
	$title_attr   = $args['title'] ? ' title="' . esc_attr( $args['title'] ) . '"' : '';
	$id_attr      = $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : '';
	
	// Build HTML
	$html = $before . '<a href="' . $args['url'] . '" class="' . implode( ' ', array_filter( $classes ) ) . '" style="' . $a_style . '" target="_' . $args['target'] . '"' . $onclick_attr . $rel_attr . $title_attr . $id_attr . '><span style="' . $span_style . '">' . do_shortcode( wp_kses_post( $content ) ) . $desc_html . '</span></a>' . $after;
	
	return $html;
}
