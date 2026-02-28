<?php
/**
 * Tabs & Tab Shortcode Functions (extracted from psource-shortcodes)
 * 
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render tabs component
 * 
 * @param array $args Tabs configuration
 * @param array $tabs Array of tab items
 * @return string HTML tabs markup
 */
function padma_render_tabs( $args = array(), $tabs = array() ) {
	// Merge with defaults
	$args = wp_parse_args( $args, array(
		'active'   => 1,
		'vertical' => 'no',
		'style'    => 'default',
		'class'    => ''
	) );
	
	if ( empty( $tabs ) ) {
		return '';
	}
	
	// Sanitize inputs
	$args['active']   = max( 1, intval( $args['active'] ) );
	$args['vertical'] = sanitize_text_field( $args['vertical'] );
	$args['style']    = sanitize_text_field( $args['style'] );
	$args['class']    = sanitize_text_field( $args['class'] );
	
	// Ensure active tab index doesn't exceed tabs count
	if ( $args['active'] > count( $tabs ) ) {
		$args['active'] = count( $tabs );
	}
	
	// Add vertical class if needed
	$vertical_class = ( 'yes' === $args['vertical'] ) ? ' su-tabs-vertical' : '';
	
	// Build tab headers and panes
	$tab_headers = array();
	$tab_panes = array();
	
	foreach ( $tabs as $index => $tab ) {
		$tab_num = $index + 1;
		
		// Prepare tab data
		$title    = ! empty( $tab['title'] ) ? sanitize_text_field( $tab['title'] ) : __( 'Tab', 'padma-advanced' ) . ' ' . $tab_num;
		$disabled = ! empty( $tab['disabled'] ) && 'yes' === $tab['disabled'] ? ' su-tabs-disabled' : '';
		$anchor   = ! empty( $tab['anchor'] ) ? ' data-anchor="' . esc_attr( str_replace( array( ' ', '#' ), '', $tab['anchor'] ) ) . '"' : '';
		$url      = ! empty( $tab['url'] ) ? esc_url( $tab['url'] ) : '';
		$url_attr = ' data-url="' . $url . '"';
		$target   = ! empty( $tab['target'] ) ? esc_attr( $tab['target'] ) : 'blank';
		$target_attr = ' data-target="' . $target . '"';
		$content  = ! empty( $tab['content'] ) ? do_shortcode( wp_kses_post( $tab['content'] ) ) : '';
		$tab_class = ! empty( $tab['class'] ) ? esc_attr( $tab['class'] ) : '';
		
		// Build header
		$tab_headers[] = '<span class="' . trim( $tab_class . $disabled ) . '"' . $anchor . $url_attr . $target_attr . '>' . esc_html( $title ) . '</span>';
		
		// Build pane
		$tab_panes[] = '<div class="su-tabs-pane su-clearfix' . ( $tab_class ? ' ' . $tab_class : '' ) . '">' . $content . '</div>';
	}
	
	// Build HTML
	$html = '<div class="su-tabs su-tabs-style-' . $args['style'] . $vertical_class . padma_ecssc( $args ) . '" data-active="' . intval( $args['active'] ) . '">';
	$html .= '<div class="su-tabs-nav">' . implode( '', $tab_headers ) . '</div>';
	$html .= '<div class="su-tabs-panes">' . implode( "\n", $tab_panes ) . '</div>';
	$html .= '</div>';
	
	return $html;
}
