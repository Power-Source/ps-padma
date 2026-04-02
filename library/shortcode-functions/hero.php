<?php
/**
 * PS Padma: Hero Shortcode Function
 *
 * @package Padma
 * @since 1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'padma_hero_hex_to_rgba' ) ) {
	/**
	 * Convert hex color to rgba string.
	 *
	 * @param string $color   Hex color.
	 * @param float  $opacity Opacity between 0 and 1.
	 * @return string
	 */
	function padma_hero_hex_to_rgba( $color, $opacity ) {
		$color = sanitize_hex_color( $color );
		if ( ! $color ) {
			$color = '#000000';
		}

		$opacity = max( 0, min( 1, floatval( $opacity ) ) );
		$color   = ltrim( $color, '#' );

		if ( 3 === strlen( $color ) ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}

		$red   = hexdec( substr( $color, 0, 2 ) );
		$green = hexdec( substr( $color, 2, 2 ) );
		$blue  = hexdec( substr( $color, 4, 2 ) );

		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $opacity . ')';
	}
}

/**
 * Render hero component.
 *
 * @param array  $args    Hero configuration.
 * @param string $content Hero content.
 * @return string
 */
function padma_render_hero( $args = array(), $content = '' ) {
	$args = wp_parse_args( $args, array(
		'title'             => '',
		'subtitle'          => '',
		'background_image'  => '',
		'background_color'  => '#1f2937',
		'text_color'        => '#ffffff',
		'overlay_color'     => '#000000',
		'overlay_opacity'   => 45,
		'min_height'        => 420,
		'align'             => 'center',
		'valign'            => 'middle',
		'button_text'       => '',
		'button_url'        => '',
		'button_target'     => 'self',
		'button_style'      => 'default',
		'button_background' => '#2D89EF',
		'button_color'      => '#FFFFFF',
		'use_inline_styles' => true,
		'use_button_style'  => true,
		'class'             => '',
	) );

	$args['title']             = wp_kses_post( $args['title'] );
	$args['subtitle']          = wp_kses_post( $args['subtitle'] );
	$args['background_image']  = esc_url_raw( $args['background_image'] );
	$args['background_color']  = padma_sanitize_color( $args['background_color'] );
	$args['text_color']        = padma_sanitize_color( $args['text_color'] );
	$args['overlay_color']     = padma_sanitize_color( $args['overlay_color'] );
	$args['overlay_opacity']   = max( 0, min( 90, intval( $args['overlay_opacity'] ) ) );
	$args['min_height']        = max( 200, intval( $args['min_height'] ) );
	$args['align']             = in_array( $args['align'], array( 'left', 'center', 'right' ), true ) ? $args['align'] : 'center';
	$args['valign']            = in_array( $args['valign'], array( 'top', 'middle', 'bottom' ), true ) ? $args['valign'] : 'middle';
	$args['button_text']       = sanitize_text_field( $args['button_text'] );
	$args['button_url']        = esc_url_raw( $args['button_url'] );
	$args['button_target']     = in_array( $args['button_target'], array( 'blank', '_blank' ), true ) ? 'blank' : 'self';
	$args['button_style']      = sanitize_text_field( $args['button_style'] );
	$args['button_background'] = padma_sanitize_color( $args['button_background'] );
	$args['button_color']      = padma_sanitize_color( $args['button_color'] );
	$args['use_inline_styles'] = ! empty( $args['use_inline_styles'] );
	$args['use_button_style']  = ! empty( $args['use_button_style'] );
	$args['class']             = sanitize_text_field( $args['class'] );
	$content                   = wp_kses_post( $content );

	$wrapper_classes = array(
		'su-hero',
		'su-hero-align-' . $args['align'],
		'su-hero-valign-' . $args['valign'],
	);

	if ( ! empty( $args['class'] ) ) {
		$wrapper_classes[] = $args['class'];
	}
	if ( ! $args['use_inline_styles'] ) {
		$wrapper_classes[] = 'su-hero-designer-mode';
	}

	$overlay_rgba = padma_hero_hex_to_rgba( $args['overlay_color'], $args['overlay_opacity'] / 100 );
	$wrapper_style = $args['use_inline_styles'] ? padma_build_style_attr( array(
		'min-height'       => $args['min_height'] . 'px',
		'background-color' => $args['background_color'],
		'color'            => $args['text_color'],
		'display'          => 'flex',
		'align-items'      => ( 'top' === $args['valign'] ) ? 'flex-start' : ( ( 'bottom' === $args['valign'] ) ? 'flex-end' : 'center' ),
		'justify-content'  => ( 'left' === $args['align'] ) ? 'flex-start' : ( ( 'right' === $args['align'] ) ? 'flex-end' : 'center' ),
		'position'         => 'relative',
		'overflow'         => 'hidden',
	) ) : '';
	if ( $args['use_inline_styles'] ) {
		$media_style = padma_build_style_attr( array(
			'background-image'    => $args['background_image'] ? 'url(' . esc_url( $args['background_image'] ) . ')' : '',
			'background-size'     => 'cover',
			'background-position' => 'center center',
			'position'            => 'absolute',
			'inset'               => '0',
		) );
	} else {
		// Block mode: only inject the dynamic background-image URL inline;
		// structural styles (position, size, etc.) live in hero-shortcodes.css
		// so the Visual Designer can override them without fighting inline specificity.
		$media_style = $args['background_image']
			? 'background-image:url(' . esc_url( $args['background_image'] ) . ')'
			: '';
	}
	$overlay_style = $args['use_inline_styles'] ? padma_build_style_attr( array(
		'background' => $overlay_rgba,
		'position'   => 'absolute',
		'inset'      => '0',
	) ) : '';
	$content_style = $args['use_inline_styles'] ? padma_build_style_attr( array(
		'position'   => 'relative',
		'z-index'    => '2',
		'width'      => '100%',
		'max-width'  => '960px',
		'padding'    => '56px 32px',
		'text-align' => $args['align'],
	) ) : '';

	$button_html = '';
	if ( $args['button_text'] && $args['button_url'] && function_exists( 'padma_render_button' ) ) {
		$button_args = array(
			'url'    => $args['button_url'],
			'target' => $args['button_target'],
		);
		if ( $args['use_button_style'] ) {
			$button_args['style']      = $args['button_style'];
			$button_args['background'] = $args['button_background'];
			$button_args['color']      = $args['button_color'];
		}
		$button_html = '<div class="su-hero-actions">' . padma_render_button( $button_args, $args['button_text'] ) . '</div>';
	}

	$html  = '<section class="' . esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ) . '" style="' . esc_attr( $wrapper_style ) . '">';
	$html .= '<div class="su-hero-media" style="' . esc_attr( $media_style ) . '"></div>';
	$html .= '<div class="su-hero-overlay" style="' . esc_attr( $overlay_style ) . '"></div>';
	$html .= '<div class="su-hero-content" style="' . esc_attr( $content_style ) . '">';

	if ( $args['subtitle'] ) {
		$html .= '<div class="su-hero-subtitle">' . do_shortcode( $args['subtitle'] ) . '</div>';
	}

	if ( $args['title'] ) {
		$html .= '<h2 class="su-hero-title">' . do_shortcode( $args['title'] ) . '</h2>';
	}

	if ( $content ) {
		$html .= '<div class="su-hero-text">' . do_shortcode( $content ) . '</div>';
	}

	$html .= $button_html;
	$html .= '</div>';
	$html .= '</section>';

	return $html;
}