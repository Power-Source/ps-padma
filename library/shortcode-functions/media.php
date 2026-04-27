<?php
/**
 * PS Padma: Media Player Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: youtube, youtube_advanced, vimeo, audio, video, dailymotion, screenr
 */

// ============================================================================
// YOUTUBE
// ============================================================================

function padma_render_youtube( $args = array(), $content = '' ) {
	if ( function_exists( 'padma_query_asset' ) ) {
		padma_query_asset( 'css', 'media-shortcodes' );
	} elseif ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'media-shortcodes' );
	} else {
		wp_enqueue_style(
			'padma-media-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/media-shortcodes.css',
			array(),
			'1.0'
		);
	}

	$defaults = array(
		'url'        => '',
		'width'      => 600,
		'height'     => 400,
		'autoplay'   => 'no',
		'mute'       => 'no',
		'responsive' => 'yes',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'YouTube: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['url'] = esc_url( $args['url'] );
	
	// Extract video ID
	$id = '';
	if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $args['url'], $match ) ) {
		$id = $match[1];
	}
	
	if ( empty( $id ) ) {
		return '<p class="su-error">' . esc_html__( 'YouTube: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['responsive'] = ( $args['responsive'] === 'no' ) ? 'no' : 'yes';
	$args['autoplay']   = ( $args['autoplay'] === 'yes' ) ? 'yes' : 'no';
	$args['mute']       = ( $args['mute'] === 'yes' ) ? 'yes' : 'no';

	$params = array();
	if ( $args['autoplay'] === 'yes' ) {
		$params['autoplay'] = '1';
	}
	if ( $args['mute'] === 'yes' ) {
		$params['mute'] = '1';
	}

	$embed_url = 'https://www.youtube.com/embed/' . $id;
	if ( ! empty( $params ) ) {
		$embed_url .= '?' . http_build_query( $params, '', '&' );
	}
	
	return '<div class="su-youtube su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" src="' . esc_url( $embed_url ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen="true"></iframe>'
		. '</div>';
}

// ============================================================================
// YOUTUBE ADVANCED
// ============================================================================

function padma_render_youtube_advanced( $args = array(), $content = '' ) {
	if ( function_exists( 'padma_query_asset' ) ) {
		padma_query_asset( 'css', 'media-shortcodes' );
	} elseif ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'media-shortcodes' );
	} else {
		wp_enqueue_style(
			'padma-media-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/media-shortcodes.css',
			array(),
			'1.0'
		);
	}

	$defaults = array(
		'url'            => '',
		'width'          => 600,
		'height'         => 400,
		'responsive'     => 'yes',
		'autohide'       => 'alt',
		'autoplay'       => 'no',
		'controls'       => 'yes',
		'fs'             => 'yes',
		'loop'           => 'no',
		'modestbranding' => 'no',
		'playlist'       => '',
		'rel'            => 'yes',
		'showinfo'       => 'yes',
		'theme'          => 'dark',
		'https'          => 'yes',
		'wmode'          => '',
		'playsinline'    => 'no',
		'class'          => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'YouTube Advanced: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['url'] = esc_url( $args['url'] );
	
	// Extract video ID
	$id = '';
	if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $args['url'], $match ) ) {
		$id = $match[1];
	}
	
	if ( empty( $id ) ) {
		return '<p class="su-error">' . esc_html__( 'YouTube Advanced: please specify correct url', 'ps-padma' ) . '</p>';
	}

	$args['responsive'] = ( $args['responsive'] === 'no' ) ? 'no' : 'yes';
	
	// Build params
	$params = array();
	foreach ( array( 'autohide', 'autoplay', 'controls', 'fs', 'loop', 'modestbranding', 'playlist', 'rel', 'showinfo', 'theme', 'wmode', 'playsinline' ) as $param ) {
		$params[$param] = str_replace( array( 'no', 'yes', 'alt' ), array( '0', '1', '2' ), $args[$param] );
	}
	
	// Correct loop
	if ( $params['loop'] === '1' && empty( $params['playlist'] ) ) {
		$params['playlist'] = $id;
	}
	
	$protocol = ( $args['https'] === 'yes' ) ? 'https' : 'http';
	$params_str = http_build_query( $params );
	
	return '<div class="su-youtube su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" src="' . esc_url( $protocol . '://www.youtube.com/embed/' . $id . '?' . $params_str ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen="true"></iframe>'
		. '</div>';
}

// ============================================================================
// VIMEO
// ============================================================================

function padma_render_vimeo( $args = array(), $content = '' ) {
	if ( function_exists( 'padma_query_asset' ) ) {
		padma_query_asset( 'css', 'media-shortcodes' );
	} elseif ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'media-shortcodes' );
	} else {
		wp_enqueue_style(
			'padma-media-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/media-shortcodes.css',
			array(),
			'1.0'
		);
	}

	$defaults = array(
		'url'        => '',
		'width'      => 600,
		'height'     => 400,
		'autoplay'   => 'no',
		'dnt'        => 'no',
		'responsive' => 'yes',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Vimeo: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['url'] = esc_url( $args['url'] );
	
	// Extract video ID
	$id = '';
	if ( preg_match( '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $args['url'], $match ) ) {
		$id = $match[1];
	}
	
	if ( empty( $id ) ) {
		return '<p class="su-error">' . esc_html__( 'Vimeo: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['responsive'] = ( $args['responsive'] === 'no' ) ? 'no' : 'yes';
	$args['autoplay']   = ( $args['autoplay'] === 'yes' ) ? 'yes' : 'no';
	$args['dnt']        = ( $args['dnt'] === 'yes' ) ? 'yes' : 'no';

	$params = array(
		'title'    => '0',
		'byline'   => '0',
		'portrait' => '0',
		'color'    => 'ffffff',
	);

	if ( $args['autoplay'] === 'yes' ) {
		$params['autoplay'] = '1';
	}
	if ( $args['dnt'] === 'yes' ) {
		$params['dnt'] = '1';
	}

	$embed_url = 'https://player.vimeo.com/video/' . $id . '?' . http_build_query( $params, '', '&' );
	
	return '<div class="su-vimeo su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" src="' . esc_url( $embed_url ) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen="true"></iframe>'
		. '</div>';
}

// ============================================================================
// AUDIO
// ============================================================================

function padma_render_audio( $args = array(), $content = '' ) {
	$defaults = array(
		'url'      => '',
		'width'    => 'auto',
		'title'    => '',
		'autoplay' => 'no',
		'loop'     => 'no',
		'class'    => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Audio: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	// Enqueue assets
	if ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'su-players-shortcodes' );
		su_query_asset( 'js', 'jplayer' );
		su_query_asset( 'js', 'su-players-shortcodes' );
	} else {
		wp_enqueue_style( 'padma-players-css', get_template_directory_uri() . '/assets/css/psource-shortcodes/players-shortcodes.css' );
		wp_enqueue_script( 'padma-jplayer-js', get_template_directory_uri() . '/assets/js/psource-shortcodes/jplayer.js', array( 'jquery' ) );
		wp_enqueue_script( 'padma-players-js', get_template_directory_uri() . '/assets/js/psource-shortcodes/players-shortcodes.js', array( 'jquery', 'padma-jplayer-js' ) );
	}
	
	$args['url'] = esc_url( $args['url'] );
	$id = uniqid( 'su_audio_player_' );
	$width = ( $args['width'] !== 'auto' ) ? 'max-width:' . esc_attr( $args['width'] ) : '';
	
	return '<div class="su-audio' . padma_ecssc( $args ) . '" data-id="' . esc_attr( $id ) . '" data-audio="' . esc_attr( $args['url'] ) . '" data-autoplay="' . esc_attr( $args['autoplay'] ) . '" data-loop="' . esc_attr( $args['loop'] ) . '" style="' . $width . '">'
		. '<div id="' . esc_attr( $id ) . '" class="jp-jplayer"></div>'
		. '<div id="' . esc_attr( $id ) . '_container" class="jp-audio">'
			. '<div class="jp-type-single">'
				. '<div class="jp-gui jp-interface">'
					. '<div class="jp-controls"><span class="jp-play"></span><span class="jp-pause"></span><span class="jp-stop"></span><span class="jp-mute"></span><span class="jp-unmute"></span><span class="jp-volume-max"></span></div>'
					. '<div class="jp-progress"><div class="jp-seek-bar"><div class="jp-play-bar"></div></div></div>'
					. '<div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div>'
					. '<div class="jp-current-time"></div>'
					. '<div class="jp-duration"></div>'
				. '</div>'
				. '<div class="jp-title">' . esc_html( $args['title'] ) . '</div>'
			. '</div>'
		. '</div></div>';
}

// ============================================================================
// VIDEO
// ============================================================================

function padma_render_video( $args = array(), $content = '' ) {
	$defaults = array(
		'url'      => '',
		'poster'   => '',
		'title'    => '',
		'width'    => 600,
		'height'   => 300,
		'controls' => 'yes',
		'autoplay' => 'no',
		'loop'     => 'no',
		'class'    => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Video: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['url'] = esc_url( $args['url'] );
	$id = uniqid( 'su_video_player_' );
	$title = ( $args['title'] ) ? '<div class="jp-title">' . esc_html( $args['title'] ) . '</div>' : '';
	
	return '<div style="width:' . intval( $args['width'] ) . 'px">'
		. '<div id="' . esc_attr( $id ) . '" class="su-video jp-video su-video-controls-' . esc_attr( $args['controls'] ) . padma_ecssc( $args ) . '" data-id="' . esc_attr( $id ) . '" data-video="' . esc_attr( $args['url'] ) . '" data-autoplay="' . esc_attr( $args['autoplay'] ) . '" data-loop="' . esc_attr( $args['loop'] ) . '" data-poster="' . esc_attr( $args['poster'] ) . '">'
			. '<div id="' . esc_attr( $id ) . '_player" class="jp-jplayer" style="width:' . intval( $args['width'] ) . 'px;height:' . intval( $args['height'] ) . 'px"></div>'
			. $title
			. '<div class="jp-start jp-play"></div>'
			. '<div class="jp-gui">'
				. '<div class="jp-interface">'
					. '<div class="jp-progress"><div class="jp-seek-bar"><div class="jp-play-bar"></div></div></div>'
					. '<div class="jp-current-time"></div>'
					. '<div class="jp-duration"></div>'
					. '<div class="jp-controls-holder"><span class="jp-play"></span><span class="jp-pause"></span><span class="jp-mute"></span><span class="jp-unmute"></span><span class="jp-full-screen"></span><span class="jp-restore-screen"></span><div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div></div>'
				. '</div>'
			. '</div>'
		. '</div></div>';
}

// ============================================================================
// DAILYMOTION
// ============================================================================

function padma_render_dailymotion( $args = array(), $content = '' ) {
	if ( function_exists( 'padma_query_asset' ) ) {
		padma_query_asset( 'css', 'media-shortcodes' );
	} elseif ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'media-shortcodes' );
	} else {
		wp_enqueue_style(
			'padma-media-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/media-shortcodes.css',
			array(),
			'1.0'
		);
	}

	$defaults = array(
		'url'        => '',
		'width'      => 600,
		'height'     => 400,
		'responsive' => 'yes',
		'autoplay'   => 'no',
		'background' => '#FFC300',
		'foreground' => '#F7FFFD',
		'highlight'  => '#171D1B',
		'logo'       => 'yes',
		'quality'    => '380',
		'related'    => 'yes',
		'info'       => 'yes',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Dailymotion: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['url'] = esc_url( $args['url'] );
	$id = strtok( basename( $args['url'] ), '_' );
	
	if ( empty( $id ) ) {
		return '<p class="su-error">' . esc_html__( 'Dailymotion: please specify correct url', 'ps-padma' ) . '</p>';
	}

	$args['responsive'] = ( $args['responsive'] === 'no' ) ? 'no' : 'yes';
	$args['autoplay']   = ( $args['autoplay'] === 'yes' ) ? 'yes' : 'no';
	$args['logo']       = ( $args['logo'] === 'no' ) ? 'no' : 'yes';
	$args['related']    = ( $args['related'] === 'no' ) ? 'no' : 'yes';
	$args['info']       = ( $args['info'] === 'no' ) ? 'no' : 'yes';
	
	// Build params
	$params = array();
	foreach ( array( 'autoplay', 'background', 'foreground', 'highlight', 'logo', 'quality', 'related', 'info' ) as $param ) {
		$params[] = $param . '=' . str_replace( array( 'yes', 'no', '#' ), array( '1', '0', '' ), $args[$param] );
	}
	
	return '<div class="su-dailymotion su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" src="https://www.dailymotion.com/embed/video/' . esc_attr( $id ) . '?' . implode( '&', $params ) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen="true"></iframe>'
		. '</div>';
}

// ============================================================================
// SCREENR
// ============================================================================

function padma_render_screenr( $args = array(), $content = '' ) {
	if ( function_exists( 'padma_query_asset' ) ) {
		padma_query_asset( 'css', 'media-shortcodes' );
	} elseif ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'media-shortcodes' );
	} else {
		wp_enqueue_style(
			'padma-media-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/media-shortcodes.css',
			array(),
			'1.0'
		);
	}

	$defaults = array(
		'url'        => '',
		'width'      => 600,
		'height'     => 400,
		'responsive' => 'yes',
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['url'] ) ) {
		return '<p class="su-error">' . esc_html__( 'Screenr: please specify correct url', 'ps-padma' ) . '</p>';
	}
	
	$args['url'] = esc_url( $args['url'] );
	
	// Extract video ID
	$id = '';
	if ( preg_match( '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*screenr\.com(?:[\/\w]*\/videos?)?\/([a-zA-Z0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $args['url'], $match ) ) {
		$id = $match[1];
	}
	
	if ( empty( $id ) ) {
		return '<p class="su-error">' . esc_html__( 'Screenr: please specify correct url', 'ps-padma' ) . '</p>';
	}

	$args['responsive'] = ( $args['responsive'] === 'no' ) ? 'no' : 'yes';
	
	return '<div class="su-screenr su-responsive-media-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '">'
		. '<iframe width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" src="' . esc_url( 'https://screenr.com/embed/' . $id ) . '" frameborder="0" allow="fullscreen" allowfullscreen="true"></iframe>'
		. '</div>';
}

// ============================================================================
// MEDIA (smart detection)
// ============================================================================

function padma_render_media( $args = array(), $content = '' ) {
	$defaults = array(
		'url'    => '',
		'width'  => 600,
		'height' => 400,
		'class'  => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Check YouTube
	if ( strpos( $args['url'], 'youtu' ) !== false ) {
		return padma_render_youtube( $args );
	}
	// Check Vimeo
	elseif ( strpos( $args['url'], 'vimeo' ) !== false ) {
		return padma_render_vimeo( $args );
	}
	// Default: image
	else {
		return '<img src="' . esc_url( $args['url'] ) . '" width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" style="max-width:100%" alt="" />';
	}
}
