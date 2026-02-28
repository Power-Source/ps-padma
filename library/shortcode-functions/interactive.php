<?php
/**
 * PS Padma: Interactive & Utility Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: members, guests, expand, service, qrcode, scheduler, menu, document, gmap, table, tooltip, animate
 */

// ============================================================================
// MEMBERS (requires login)
// ============================================================================

function padma_render_members( $args = array(), $content = '' ) {
	$defaults = array(
		'message'    => esc_html__( 'This content is for registered users only. Please %login%.', 'ps-padma' ),
		'color'      => '#ffcc00',
		'login_text' => esc_html__( 'login', 'ps-padma' ),
		'login_url'  => '',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['login_url'] ) ) {
		$args['login_url'] = wp_login_url();
	}
	
	// Check feed
	if ( is_feed() ) {
		return '';
	}
	
	// Check authorization
	if ( ! is_user_logged_in() ) {
		$login = '<a href="' . esc_url( $args['login_url'] ) . '">' . esc_html( $args['login_text'] ) . '</a>';
		$darker = padma_hex_shift( $args['color'], 'darker', 20 );
		$lighter = padma_hex_shift( $args['color'], 'lighter', 50 );
		$text_color = padma_hex_shift( $args['color'], 'darker', 60 );
		
		return '<div class="su-members' . padma_ecssc( $args ) . '" style="background-color:' . esc_attr( $lighter ) . ';border-color:' . esc_attr( $darker ) . ';color:' . esc_attr( $text_color ) . '">' . str_replace( '%login%', $login, sanitize_text_field( $args['message'] ) ) . '</div>';
	}
	
	return do_shortcode( $content );
}

// ============================================================================
// GUESTS (visible only for non-logged users)
// ============================================================================

function padma_render_guests( $args = array(), $content = '' ) {
	$defaults = array(
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( ! is_user_logged_in() ) {
		return '<div class="su-guests' . padma_ecssc( $args ) . '">' . do_shortcode( $content ) . '</div>';
	}
	
	return '';
}

// ============================================================================
// EXPAND / SHOW MORE
// ============================================================================

function padma_render_expand( $args = array(), $content = '' ) {
	$defaults = array(
		'more_text'  => esc_html__( 'Show more', 'ps-padma' ),
		'less_text'  => esc_html__( 'Show less', 'ps-padma' ),
		'height'     => '100',
		'hide_less'  => 'no',
		'text_color' => '#333333',
		'link_color' => '#0088FF',
		'link_style' => 'default',
		'link_align' => 'left',
		'more_icon'  => '',
		'less_icon'  => '',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['height'] = intval( $args['height'] );
	
	// Prepare less link
	$less = ( $args['hide_less'] !== 'yes' ) ? '<div class="su-expand-link su-expand-link-less" style="text-align:' . esc_attr( $args['link_align'] ) . '"><a href="javascript:;" style="color:' . esc_attr( $args['link_color'] ) . ';border-color:' . esc_attr( $args['link_color'] ) . '"><span style="border-color:' . esc_attr( $args['link_color'] ) . '">' . esc_html( $args['less_text'] ) . '</span></a></div>' : '';
	
	return '<div class="su-expand su-expand-collapsed su-expand-link-style-' . esc_attr( $args['link_style'] ) . padma_ecssc( $args ) . '" data-height="' . $args['height'] . '">'
		. '<div class="su-expand-content" style="color:' . esc_attr( $args['text_color'] ) . ';max-height:' . $args['height'] . 'px;overflow:hidden">' . do_shortcode( $content ) . '</div>'
		. '<div class="su-expand-link su-expand-link-more" style="text-align:' . esc_attr( $args['link_align'] ) . '"><a href="javascript:;" style="color:' . esc_attr( $args['link_color'] ) . ';border-color:' . esc_attr( $args['link_color'] ) . '"><span style="border-color:' . esc_attr( $args['link_color'] ) . '">' . esc_html( $args['more_text'] ) . '</span></a></div>'
		. $less
		. '</div>';
}

// ============================================================================
// SERVICE BOX
// ============================================================================

function padma_render_service( $args = array(), $content = '' ) {
	$defaults = array(
		'title'       => esc_html__( 'Service title', 'ps-padma' ),
		'icon'        => '',
		'icon_color'  => '#333',
		'size'        => 32,
		'class'       => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	$args['size'] = intval( $args['size'] );
	
	// RTL support
	$rtl = ( is_rtl() ) ? 'right' : 'left';
	
	// Built-in icon
	if ( strpos( $args['icon'], 'icon:' ) !== false ) {
		$icon_name = trim( str_replace( 'icon:', '', $args['icon'] ) );
		$args['icon'] = '<i class="fa fa-' . esc_attr( $icon_name ) . '" style="font-size:' . $args['size'] . 'px;color:' . esc_attr( $args['icon_color'] ) . '"></i>';
	} else {
		$args['icon'] = '<img src="' . esc_url( $args['icon'] ) . '" width="' . $args['size'] . '" height="' . $args['size'] . '" alt="' . esc_attr( $args['title'] ) . '" />';
	}
	
	return '<div class="su-service' . padma_ecssc( $args ) . '"><div class="su-service-title" style="padding-' . $rtl . ':' . round( $args['size'] + 14 ) . 'px;min-height:' . $args['size'] . 'px;line-height:' . $args['size'] . 'px">' . $args['icon'] . ' ' . esc_html( $args['title'] ) . '</div><div class="su-service-content su-clearfix" style="padding-' . $rtl . ':' . round( $args['size'] + 14 ) . 'px">' . do_shortcode( $content ) . '</div></div>';
}

// ============================================================================
// MENU SHORTCODE
// ============================================================================

function padma_render_menu( $args = array(), $content = '' ) {
	$defaults = array(
		'name'  => '',
		'class' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['name'] ) ) {
		return '';
	}
	
	$menu_args = array(
		'echo'        => false,
		'menu'        => $args['name'],
		'container'   => false,
		'fallback_cb' => '__return_false',
		'items_wrap'  => '<ul id="%1$s" class="%2$s' . padma_ecssc( $args ) . '">%3$s</ul>'
	);
	
	return wp_nav_menu( $menu_args );
}

// ============================================================================
// DOCUMENT (Google Docs Viewer)
// ============================================================================

function padma_render_document( $args = array(), $content = '' ) {
	$defaults = array(
		'url'        => '',
		'width'      => 600,
		'height'     => 400,
		'responsive' => 'yes',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Document: please specify url', 'ps-padma' ) . '</p>';
	}
	
	return '<div class="su-document su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe src="//docs.google.com/viewer?embedded=true&url=' . esc_url( $args['url'] ) . '" width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" class="su-document"></iframe>'
		. '</div>';
}

// ============================================================================
// GOOGLE MAP
// ============================================================================

function padma_render_gmap( $args = array(), $content = '' ) {
	$defaults = array(
		'width'      => 600,
		'height'     => 400,
		'responsive' => 'yes',
		'address'    => 'New York',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	return '<div class="su-gmap su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" src="//maps.google.com/maps?q=' . urlencode( sanitize_text_field( $args['address'] ) ) . '&amp;output=embed"></iframe>'
		. '</div>';
}

// ============================================================================
// TABLE
// ============================================================================

function padma_render_table( $args = array(), $content = '' ) {
	$defaults = array(
		'url'        => '',
		'responsive' => false,
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	$responsive_class = ( $args['responsive'] ) ? ' su-table-responsive' : '';
	
	$table_content = ( ! empty( $args['url'] ) ) ? wp_remote_fopen( esc_url( $args['url'] ) ) : do_shortcode( $content );
	
	return '<div class="su-table' . esc_attr( $responsive_class ) . padma_ecssc( $args ) . '">' . $table_content . '</div>';
}

// ============================================================================
// QR CODE
// ============================================================================

function padma_render_qrcode( $args = array(), $content = '' ) {
	$defaults = array(
		'data'       => '',
		'title'      => '',
		'size'       => 200,
		'margin'     => 0,
		'align'      => 'none',
		'link'       => '',
		'target'     => 'blank',
		'color'      => '#000000',
		'background' => '#ffffff',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['data'] ) ) {
		return '<p class="su-error">' . esc_html__( 'QR code: please specify the data', 'ps-padma' ) . '</p>';
	}
	
	$args['size'] = intval( $args['size'] );
	$args['margin'] = intval( $args['margin'] );
	
	// Convert hex to RGB for QR API
	$color_rgb = str_replace( '#', '', $args['color'] );
	$bg_rgb = str_replace( '#', '', $args['background'] );
	
	$href = ( ! empty( $args['link'] ) ) ? ' href="' . esc_url( $args['link'] ) . '"' : '';
	
	if ( ! empty( $args['link'] ) ) {
		$args['class'] .= ' su-qrcode-clickable';
	}
	
	return '<span class="su-qrcode su-qrcode-align-' . esc_attr( $args['align'] ) . padma_ecssc( $args ) . '">'
		. '<a' . $href . ' target="_' . esc_attr( $args['target'] ) . '" title="' . esc_attr( $args['title'] ) . '">'
			. '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode( $args['data'] ) . '&size=' . $args['size'] . 'x' . $args['size'] . '&format=png&margin=' . $args['margin'] . '&color=' . esc_attr( $color_rgb ) . '&bgcolor=' . esc_attr( $bg_rgb ) . '" alt="' . esc_attr( $args['title'] ) . '" />'
		. '</a>'
		. '</span>';
}

// ============================================================================
// SCHEDULER (show/hide based on date/time)
// ============================================================================

function padma_render_scheduler( $args = array(), $content = '' ) {
	$defaults = array(
		'time'       => 'all',
		'days_week'  => 'all',
		'days_month' => 'all',
		'months'     => 'all',
		'years'      => 'all',
		'alt'        => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Check time
	if ( $args['time'] !== 'all' ) {
		$now = current_time( 'timestamp', 0 );
		$args['time'] = preg_replace( "/[^0-9-,:]/", '', $args['time'] );
		
		foreach ( explode( ',', $args['time'] ) as $range ) {
			if ( strpos( $range, '-' ) === false ) {
				return $args['alt'];
			}
			
			$time = explode( '-', $range );
			if ( strpos( $time[0], ':' ) === false ) $time[0] .= ':00';
			if ( strpos( $time[1], ':' ) === false ) $time[1] .= ':00';
			
			$time[0] = strtotime( $time[0] );
			$time[1] = strtotime( $time[1] );
			
			if ( $now < $time[0] || $now > $time[1] ) {
				return $args['alt'];
			}
		}
	}
	
	// Check day of week
	if ( $args['days_week'] !== 'all' ) {
		$today = date( 'w', current_time( 'timestamp', 0 ) );
		$args['days_week'] = preg_replace( "/[^0-9-,]/", '', $args['days_week'] );
		$days = padma_range_parse( $args['days_week'] );
		
		if ( ! in_array( $today, $days ) ) {
			return $args['alt'];
		}
	}
	
	// Check day of month
	if ( $args['days_month'] !== 'all' ) {
		$today = date( 'j', current_time( 'timestamp', 0 ) );
		$args['days_month'] = preg_replace( "/[^0-9-,]/", '', $args['days_month'] );
		$days = padma_range_parse( $args['days_month'] );
		
		if ( ! in_array( $today, $days ) ) {
			return $args['alt'];
		}
	}
	
	// Check month
	if ( $args['months'] !== 'all' ) {
		$now = date( 'n', current_time( 'timestamp', 0 ) );
		$args['months'] = preg_replace( "/[^0-9-,]/", '', $args['months'] );
		$months = padma_range_parse( $args['months'] );
		
		if ( ! in_array( $now, $months ) ) {
			return $args['alt'];
		}
	}
	
	// Check year
	if ( $args['years'] !== 'all' ) {
		$now = date( 'Y', current_time( 'timestamp', 0 ) );
		$args['years'] = preg_replace( "/[^0-9-,]/", '', $args['years'] );
		$years = padma_range_parse( $args['years'] );
		
		if ( ! in_array( $now, $years ) ) {
			return $args['alt'];
		}
	}
	
	// All checks passed
	return do_shortcode( $content );
}

// ============================================================================
// Helper: Range Parser (for scheduler)
// ============================================================================

function padma_range_parse( $range_str ) {
	$result = array();
	
	foreach ( explode( ',', $range_str ) as $item ) {
		$item = trim( $item );
		
		if ( strpos( $item, '-' ) !== false ) {
			$parts = explode( '-', $item );
			$start = intval( trim( $parts[0] ) );
			$end = intval( trim( $parts[1] ) );
			
			for ( $i = $start; $i <= $end; $i++ ) {
				$result[] = $i;
			}
		} else {
			$result[] = intval( $item );
		}
	}
	
	return array_unique( $result );
}
