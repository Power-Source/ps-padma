<?php
/**
 * PS Padma: Typography & Basic Content Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: heading, divider, spacer, highlight, label, note, dropcap, frame, pullquote, list, private
 */

// ============================================================================
// HEADING
// ============================================================================

function padma_render_heading( $args = array(), $content = '' ) {
	$defaults = array(
		'style'  => 'default',
		'size'   => 13,
		'align'  => 'center',
		'margin' => '20',
		'class'  => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['size'] = intval( $args['size'] );
	$args['margin'] = intval( $args['margin'] );
	
	return '<div class="su-heading su-heading-style-' . esc_attr( $args['style'] ) . ' su-heading-align-' . esc_attr( $args['align'] ) . padma_ecssc( $args ) . '" style="font-size:' . $args['size'] . 'px;margin-bottom:' . $args['margin'] . 'px"><div class="su-heading-inner">' . do_shortcode( $content ) . '</div></div>';
}

// ============================================================================
// DIVIDER
// ============================================================================

function padma_render_divider( $args = array(), $content = '' ) {
	$defaults = array(
		'top'           => 'yes',
		'text'          => esc_html__( 'Go to top', 'ps-padma' ),
		'style'         => 'default',
		'divider_color' => '#999999',
		'link_color'    => '#999999',
		'size'          => '3',
		'margin'        => '15',
		'class'         => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['size'] = intval( $args['size'] );
	$args['margin'] = intval( $args['margin'] );
	
	$top = ( $args['top'] === 'yes' ) ? '<a href="#" style="color:' . esc_attr( $args['link_color'] ) . '">' . sanitize_text_field( $args['text'] ) . '</a>' : '';
	
	return '<div class="su-divider su-divider-style-' . esc_attr( $args['style'] ) . padma_ecssc( $args ) . '" style="margin:' . $args['margin'] . 'px 0;border-width:' . $args['size'] . 'px;border-color:' . esc_attr( $args['divider_color'] ) . '">' . $top . '</div>';
}

// ============================================================================
// SPACER
// ============================================================================

function padma_render_spacer( $args = array(), $content = '' ) {
	$defaults = array(
		'size'  => '20',
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['size'] = intval( $args['size'] );
	
	return '<div class="su-spacer' . padma_ecssc( $args ) . '" style="height:' . $args['size'] . 'px"></div>';
}

// ============================================================================
// HIGHLIGHT
// ============================================================================

function padma_render_highlight( $args = array(), $content = '' ) {
	$defaults = array(
		'background' => '#ddff99',
		'color'      => '#000000',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<span class="su-highlight' . padma_ecssc( $args ) . '" style="background:' . esc_attr( $args['background'] ) . ';color:' . esc_attr( $args['color'] ) . '">&nbsp;' . do_shortcode( $content ) . '&nbsp;</span>';
}

// ============================================================================
// LABEL
// ============================================================================

function padma_render_label( $args = array(), $content = '' ) {
	$defaults = array(
		'type'  => 'default',
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<span class="su-label su-label-type-' . esc_attr( $args['type'] ) . padma_ecssc( $args ) . '">' . do_shortcode( $content ) . '</span>';
}

// ============================================================================
// NOTE
// ============================================================================

function padma_render_note( $args = array(), $content = '' ) {
	$defaults = array(
		'note_color' => '#FFFF66',
		'text_color' => '#333333',
		'radius'     => '3',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['radius'] = intval( $args['radius'] );
	
	$radius = ( $args['radius'] != '0' ) ? 'border-radius:' . $args['radius'] . 'px;-moz-border-radius:' . $args['radius'] . 'px;-webkit-border-radius:' . $args['radius'] . 'px;' : '';
	
	$darker = padma_hex_shift( $args['note_color'], 'darker', 10 );
	$lighter = padma_hex_shift( $args['note_color'], 'lighter', 80 );
	
	return '<div class="su-note' . padma_ecssc( $args ) . '" style="border-color:' . esc_attr( $darker ) . ';' . $radius . '"><div class="su-note-inner su-clearfix" style="background-color:' . esc_attr( $args['note_color'] ) . ';border-color:' . esc_attr( $lighter ) . ';color:' . esc_attr( $args['text_color'] ) . ';' . $radius . '">' . do_shortcode( $content ) . '</div></div>';
}

// ============================================================================
// DROPCAP
// ============================================================================

function padma_render_dropcap( $args = array(), $content = '' ) {
	$defaults = array(
		'style' => 'default',
		'size'  => 3,
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['size'] = intval( $args['size'] );
	
	$em = $args['size'] * 0.5 . 'em';
	
	return '<span class="su-dropcap su-dropcap-style-' . esc_attr( $args['style'] ) . padma_ecssc( $args ) . '" style="font-size:' . esc_attr( $em ) . '">' . do_shortcode( $content ) . '</span>';
}

// ============================================================================
// FRAME
// ============================================================================

function padma_render_frame( $args = array(), $content = '' ) {
	$defaults = array(
		'style' => 'default',
		'align' => 'left',
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<span class="su-frame su-frame-align-' . esc_attr( $args['align'] ) . ' su-frame-style-' . esc_attr( $args['style'] ) . padma_ecssc( $args ) . '"><span class="su-frame-inner">' . do_shortcode( $content ) . '</span></span>';
}

// ============================================================================
// PULLQUOTE
// ============================================================================

function padma_render_pullquote( $args = array(), $content = '' ) {
	$defaults = array(
		'align' => 'left',
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<div class="su-pullquote su-pullquote-align-' . esc_attr( $args['align'] ) . padma_ecssc( $args ) . '">' . do_shortcode( $content ) . '</div>';
}

// ============================================================================
// LIST
// ============================================================================

function padma_render_list( $args = array(), $content = '' ) {
	$defaults = array(
		'icon'       => 'icon: star',
		'icon_color' => '#333',
		'style'      => null,
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Backward compatibility
	if ( $args['style'] !== null ) {
		$style_map = array(
			'star'     => array( 'icon' => 'icon: star', 'color' => '#ffd647' ),
			'arrow'    => array( 'icon' => 'icon: arrow-right', 'color' => '#00d1ce' ),
			'check'    => array( 'icon' => 'icon: check', 'color' => '#17bf20' ),
			'cross'    => array( 'icon' => 'icon: remove', 'color' => '#ff142b' ),
			'thumbs'   => array( 'icon' => 'icon: thumbs-o-up', 'color' => '#8a8a8a' ),
			'link'     => array( 'icon' => 'icon: external-link', 'color' => '#5c5c5c' ),
			'gear'     => array( 'icon' => 'icon: cog', 'color' => '#ccc' ),
			'time'     => array( 'icon' => 'icon: time', 'color' => '#a8a8a8' ),
			'note'     => array( 'icon' => 'icon: edit', 'color' => '#f7d02c' ),
			'plus'     => array( 'icon' => 'icon: plus-sign', 'color' => '#61dc3c' ),
			'guard'    => array( 'icon' => 'icon: shield', 'color' => '#1bbe08' ),
			'event'    => array( 'icon' => 'icon: bullhorn', 'color' => '#ff4c42' ),
			'idea'     => array( 'icon' => 'icon: sun', 'color' => '#ffd880' ),
			'settings' => array( 'icon' => 'icon: cogs', 'color' => '#8a8a8a' ),
			'twitter'  => array( 'icon' => 'icon: twitter-sign', 'color' => '#00ced6' ),
		);
		
		if ( isset( $style_map[ $args['style'] ] ) ) {
			$args['icon'] = $style_map[ $args['style'] ]['icon'];
			$args['icon_color'] = $style_map[ $args['style'] ]['color'];
		}
	}
	
	if ( strpos( $args['icon'], 'icon:' ) !== false ) {
		$icon_name = trim( str_replace( 'icon:', '', $args['icon'] ) );
		$args['icon'] = '<i class="fa fa-' . esc_attr( $icon_name ) . '" style="color:' . esc_attr( $args['icon_color'] ) . '"></i>';
	} else {
		$args['icon'] = '<img src="' . esc_url( $args['icon'] ) . '" alt="" />';
	}
	
	return '<div class="su-list su-list-style-' . esc_attr( $args['style'] ) . padma_ecssc( $args ) . '">' . str_replace( '<li>', '<li>' . $args['icon'] . ' ', do_shortcode( $content ) ) . '</div>';
}

// ============================================================================
// PRIVATE (visible only for admins)
// ============================================================================

function padma_render_private( $args = array(), $content = '' ) {
	$defaults = array(
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( current_user_can( 'publish_posts' ) ) {
		return '<div class="su-private' . padma_ecssc( $args ) . '"><div class="su-private-shell">' . do_shortcode( $content ) . '</div></div>';
	}
	
	return '';
}
