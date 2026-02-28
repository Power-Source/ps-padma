<?php
/**
 * Shortcode Helper Functions
 * 
 * Extracted utility functions from psource-shortcodes plugin
 * Used by native button, box, accordion and other blocks
 *
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shift hex color lighter or darker
 * 
 * @param string $supplied_hex Hex color value
 * @param string $shift_method 'lighter' or 'darker'
 * @param int $percentage Percentage to shift (0-100)
 * @return string Shifted hex color
 */
function padma_hex_shift( $supplied_hex, $shift_method, $percentage = 50 ) {
	$shifted_hex_value = null;
	$valid_shift_option = false;
	$current_set = 1;
	$RGB_values = array();
	$valid_shift_up_args = array( 'up', '+', 'lighter', '>' );
	$valid_shift_down_args = array( 'down', '-', 'darker', '<' );
	$shift_method = strtolower( trim( $shift_method ) );
	
	// Check Factor
	if ( ! is_numeric( $percentage ) || ( $percentage = (int) $percentage ) < 0 || $percentage > 100 ) {
		$percentage = 50;
	}
	
	// Check shift method
	foreach ( array( $valid_shift_down_args, $valid_shift_up_args ) as $options ) {
		foreach ( $options as $method ) {
			if ( $method === $shift_method ) {
				$valid_shift_option = ! $valid_shift_option;
				$shift_method = ( 1 === $current_set ) ? '+' : '-';
				break 2;
			}
		}
		++$current_set;
	}
	
	if ( ! $valid_shift_option ) {
		return $supplied_hex;
	}
	
	// Check Hex string
	$supplied_hex = str_replace( '#', '', trim( $supplied_hex ) );
	
	switch ( strlen( $supplied_hex ) ) {
		case 3:
			if ( preg_match( '/^([0-9a-f])([0-9a-f])([0-9a-f])/i', $supplied_hex ) ) {
				$supplied_hex = preg_replace( '/^([0-9a-f])([0-9a-f])([0-9a-f])/i', '\\1\\1\\2\\2\\3\\3', $supplied_hex );
			} else {
				return '#' . $supplied_hex;
			}
			break;
		case 6:
			if ( ! preg_match( '/^[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}$/i', $supplied_hex ) ) {
				return '#' . $supplied_hex;
			}
			break;
		default:
			return $supplied_hex;
	}
	
	// Start shifting
	$RGB_values['R'] = hexdec( $supplied_hex[0] . $supplied_hex[1] );
	$RGB_values['G'] = hexdec( $supplied_hex[2] . $supplied_hex[3] );
	$RGB_values['B'] = hexdec( $supplied_hex[4] . $supplied_hex[5] );
	
	foreach ( $RGB_values as $c => $v ) {
		switch ( $shift_method ) {
			case '-':
				$amount = round( ( ( 255 - $v ) / 100 ) * $percentage ) + $v;
				break;
			case '+':
				$amount = $v - round( ( $v / 100 ) * $percentage );
				break;
			default:
				$amount = $v;
		}
		$decimal_to_hex = dechex( $amount );
		$shifted_hex_value .= ( strlen( $decimal_to_hex ) < 2 ) ? '0' . $decimal_to_hex : $decimal_to_hex;
	}
	
	return '#' . $shifted_hex_value;
}

/**
 * Extract and escape class attribute from array
 * 
 * @param array $atts Array with 'class' key
 * @return string Class attribute value with leading space
 */
function padma_ecssc( $atts ) {
	return ( ! empty( $atts['class'] ) ) ? ' ' . trim( $atts['class'] ) : '';
}

/**
 * Sanitize shortcode attribute
 * 
 * @param mixed $value Value to sanitize
 * @return mixed Sanitized value
 */
function padma_scattr( $value ) {
	if ( is_string( $value ) ) {
		return wp_kses_post( $value );
	}
	return $value;
}

/**
 * Render icon (Font Awesome or image)
 * 
 * @param string $icon Icon identifier ('icon:star', '/path/to/image.png', etc)
 * @param int $size Icon size in pixels
 * @param string $color Icon color
 * @param string $style Additional CSS style
 * @return string HTML icon tag
 */
function padma_get_icon( $icon = '', $size = '', $color = '', $style = '' ) {
	if ( ! $icon ) {
		return '';
	}
	
	// Add trailing ; to the style param
	if ( $style ) {
		$style = rtrim( $style, ';' ) . ';';
	}
	
	// Font Awesome icon
	if ( strpos( $icon, 'icon:' ) !== false ) {
		// Add size
		if ( $size ) {
			$style .= 'font-size:' . intval( $size ) . 'px;';
		}
		// Add color
		if ( $color ) {
			$style .= 'color:' . sanitize_hex_color( $color ) . ';';
		}
		// Return icon
		return '<i class="fa fa-' . trim( str_replace( 'icon:', '', $icon ) ) . '" style="' . $style . '"></i>';
	} 
	// Image icon
	elseif ( strpos( $icon, '/' ) !== false || strpos( $icon, 'http' ) !== false ) {
		// Add size
		if ( $size ) {
			$style .= 'width:' . intval( $size ) . 'px;height:' . intval( $size ) . 'px;';
		}
		// Return icon
		return '<img src="' . esc_url( $icon ) . '" alt="" style="' . $style . '" />';
	}
	
	return '';
}

/**
 * Sanitize hex color
 * 
 * @param string $color Color value
 * @return string Sanitized hex color
 */
function padma_sanitize_color( $color ) {
	if ( empty( $color ) ) {
		return '#000000';
	}
	
	// Check for named colors or rgb
	if ( function_exists( 'sanitize_hex_color' ) ) {
		$sanitized = sanitize_hex_color( $color );
		if ( $sanitized ) {
			return $sanitized;
		}
	}
	
	// Fallback: ensure it starts with #
	if ( strpos( $color, '#' ) === false && stripos( $color, 'rgb' ) === false ) {
		$color = '#' . $color;
	}
	
	return $color;
}

/**
 * Get CSS style string from array of rules
 * 
 * @param array $rules CSS rules (key => value pairs)
 * @return string CSS style attribute value
 */
function padma_build_style_attr( $rules = array() ) {
	if ( empty( $rules ) ) {
		return '';
	}
	
	$styles = array();
	foreach ( $rules as $property => $value ) {
		if ( ! empty( $value ) ) {
			$styles[] = sanitize_text_field( $property ) . ':' . sanitize_text_field( $value );
		}
	}
	
	return implode( ';', $styles );
}
