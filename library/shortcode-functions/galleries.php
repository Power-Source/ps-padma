<?php
/**
 * PS Padma: Gallery Shortcodes
 * Extracted from psource-shortcodes plugin
 * 
 * Contains: slider, carousel, custom_gallery
 */

// ============================================================================
// GET SLIDES FROM SOURCE (Gallery, Category, or IDs)
// ============================================================================

function padma_get_gallery_slides( $args = array() ) {
	$defaults = array(
		'source'  => 'none',
		'limit'   => 20,
		'gallery' => null,
	);
	
	$args = wp_parse_args( $args, $defaults );
	$slides = array();
	
	// Get from attachment IDs (gallery parameter)
	if ( ! empty( $args['gallery'] ) ) {
		$ids = explode( ',', $args['gallery'] );
		
		foreach ( $ids as $id ) {
			$id = intval( trim( $id ) );
			
			if ( ! $id ) {
				continue;
			}
			
			$attachment = get_post( $id );
			
			if ( ! $attachment || $attachment->post_type !== 'attachment' ) {
				continue;
			}
			
			$meta = wp_get_attachment_metadata( $id );
			
			$slide = array(
				'image' => wp_get_attachment_url( $id ),
				'title' => $attachment->post_title,
				'link'  => get_post_meta( $id, 'su_slide_link', true ),
			);
			
			$slides[] = $slide;
			
			if ( count( $slides ) >= intval( $args['limit'] ) ) {
				break;
			}
		}
	}
	
	// Get from post sources (category, tag, etc.)
	elseif ( $args['source'] !== 'none' ) {
		// This would require more complex implementation
		// For now, return empty if source-based query
	}
	
	return $slides;
}

// ============================================================================
// SLIDER
// ============================================================================

function padma_render_slider( $args = array(), $content = '' ) {
	$defaults = array(
		'source'     => 'none',
		'limit'      => 20,
		'gallery'    => null,
		'link'       => 'none',
		'target'     => 'self',
		'width'      => 600,
		'height'     => 300,
		'responsive' => 'yes',
		'title'      => 'yes',
		'centered'   => 'yes',
		'arrows'     => 'yes',
		'pages'      => 'yes',
		'mousewheel' => 'yes',
		'autoplay'   => 3000,
		'speed'      => 600,
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Get slides
	$slides = padma_get_gallery_slides( $args );
	
	if ( empty( $slides ) ) {
		return '<p class="su-error">' . esc_html__( 'Slider: no images found', 'ps-padma' ) . '</p>';
	}
	
	// Enqueue assets
	if ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'su-galleries-shortcodes' );
		su_query_asset( 'js', 'su-galleries-shortcodes' );
	} else {
		wp_enqueue_style( 'padma-galleries-css', get_template_directory_uri() . '/assets/psource-css/galleries-shortcodes.css' );
		wp_enqueue_script( 'padma-galleries-js', get_template_directory_uri() . '/assets/psource-js/galleries-shortcodes.js', array( 'jquery' ) );
	}
	
	$id = uniqid( 'su_slider_' );
	$target = ( $args['target'] === 'yes' || $args['target'] === 'blank' ) ? ' target="_blank"' : '';
	$centered = ( $args['centered'] === 'yes' ) ? ' su-slider-centered' : '';
	$mousewheel = ( $args['mousewheel'] === 'yes' ) ? 'true' : 'false';
	
	$size = ( $args['responsive'] === 'yes' ) ? 'width:100%' : 'width:' . intval( $args['width'] ) . 'px;height:' . intval( $args['height'] ) . 'px';
	
	if ( $args['link'] === 'lightbox' ) {
		$args['class'] .= ' su-lightbox-gallery';
	}
	
	$html = '<div id="' . esc_attr( $id ) . '" class="su-slider' . esc_attr( $centered ) . ' su-slider-pages-' . esc_attr( $args['pages'] ) . ' su-slider-responsive-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '" style="' . $size . '" data-autoplay="' . intval( $args['autoplay'] ) . '" data-speed="' . intval( $args['speed'] ) . '" data-mousewheel="' . esc_attr( $mousewheel ) . '">'
		. '<div class="su-slider-slides">';
	
	// Build slides
	foreach ( $slides as $slide ) {
		$html .= '<div class="su-slider-slide">';
		
		if ( ! empty( $slide['link'] ) ) {
			$html .= '<a href="' . esc_url( $slide['link'] ) . '"' . $target . ' title="' . esc_attr( $slide['title'] ) . '">';
			$html .= '<img src="' . esc_url( $slide['image'] ) . '" alt="' . esc_attr( $slide['title'] ) . '" />';
			if ( $args['title'] === 'yes' && ! empty( $slide['title'] ) ) {
				$html .= '<span class="su-slider-slide-title">' . esc_html( $slide['title'] ) . '</span>';
			}
			$html .= '</a>';
		} else {
			$html .= '<a>';
			$html .= '<img src="' . esc_url( $slide['image'] ) . '" alt="' . esc_attr( $slide['title'] ) . '" />';
			if ( $args['title'] === 'yes' && ! empty( $slide['title'] ) ) {
				$html .= '<span class="su-slider-slide-title">' . esc_html( $slide['title'] ) . '</span>';
			}
			$html .= '</a>';
		}
		
		$html .= '</div>';
	}
	
	$html .= '</div>';
	
	// Navigation
	$html .= '<div class="su-slider-nav">';
	if ( $args['arrows'] === 'yes' ) {
		$html .= '<div class="su-slider-direction"><span class="su-slider-prev"></span><span class="su-slider-next"></span></div>';
	}
	$html .= '<div class="su-slider-pagination"></div>';
	$html .= '</div>';
	
	$html .= '</div>';
	
	return $html;
}

// ============================================================================
// CAROUSEL
// ============================================================================

function padma_render_carousel( $args = array(), $content = '' ) {
	$defaults = array(
		'source'     => 'none',
		'limit'      => 20,
		'gallery'    => null,
		'link'       => 'none',
		'target'     => 'self',
		'width'      => 600,
		'height'     => 200,
		'responsive' => 'yes',
		'items'      => 4,
		'loop'       => 'yes',
		'arrows'     => 'yes',
		'pages'      => 'yes',
		'margin'     => 10,
		'autoplay'   => 'no',
		'speed'      => 600,
		'class'      => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Get slides
	$slides = padma_get_gallery_slides( $args );
	
	if ( empty( $slides ) ) {
		return '<p class="su-error">' . esc_html__( 'Carousel: no images found', 'ps-padma' ) . '</p>';
	}
	
	// Enqueue assets
	if ( function_exists( 'su_query_asset' ) ) {
		su_query_asset( 'css', 'su-galleries-shortcodes' );
		su_query_asset( 'js', 'su-galleries-shortcodes' );
	} else {
		wp_enqueue_style( 'padma-galleries-css', get_template_directory_uri() . '/assets/psource-css/galleries-shortcodes.css' );
		wp_enqueue_script( 'padma-galleries-js', get_template_directory_uri() . '/assets/psource-js/galleries-shortcodes.js', array( 'jquery' ) );
	}
	
	$id = uniqid( 'su_carousel_' );
	$loop = ( $args['loop'] === 'yes' ) ? 'true' : 'false';
	$target = ( $args['target'] === 'yes' || $args['target'] === 'blank' ) ? ' target="_blank"' : '';
	
	$size = ( $args['responsive'] === 'yes' ) ? 'width:100%' : 'width:' . intval( $args['width'] ) . 'px;height:' . intval( $args['height'] ) . 'px';
	
	$html = '<div id="' . esc_attr( $id ) . '" class="su-carousel su-carousel-responsive-' . esc_attr( $args['responsive'] ) . padma_ecssc( $args ) . '" style="' . $size . '" data-items="' . intval( $args['items'] ) . '" data-loop="' . esc_attr( $loop ) . '" data-margin="' . intval( $args['margin'] ) . '" data-autoplay="' . esc_attr( $args['autoplay'] ) . '" data-speed="' . intval( $args['speed'] ) . '">';
	
	// Build slides
	foreach ( $slides as $slide ) {
		$html .= '<div class="su-carousel-slide">';
		
		if ( ! empty( $slide['link'] ) ) {
			$html .= '<a href="' . esc_url( $slide['link'] ) . '"' . $target . ' title="' . esc_attr( $slide['title'] ) . '">';
			$html .= '<img src="' . esc_url( $slide['image'] ) . '" alt="' . esc_attr( $slide['title'] ) . '" />';
			$html .= '</a>';
		} else {
			$html .= '<img src="' . esc_url( $slide['image'] ) . '" alt="' . esc_attr( $slide['title'] ) . '" />';
		}
		
		$html .= '</div>';
	}
	
	// Navigation
	if ( $args['arrows'] === 'yes' ) {
		$html .= '<div class="su-carousel-nav"><span class="su-carousel-prev"></span><span class="su-carousel-next"></span></div>';
	}
	
	$html .= '</div>';
	
	return $html;
}

// ============================================================================
// CUSTOM GALLERY
// ============================================================================

function padma_render_custom_gallery( $args = array(), $content = '' ) {
	$defaults = array(
		'source'   => 'none',
		'limit'    => 20,
		'gallery'  => null,
		'link'     => 'none',
		'width'    => 90,
		'height'   => 90,
		'title'    => 'hover',
		'target'   => 'self',
		'class'    => '',
		'lightbox' => 'no'
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Get slides
	$slides = padma_get_gallery_slides( $args );
	
	if ( empty( $slides ) ) {
		return '<p class="su-error">' . esc_html__( 'Gallery: no images found', 'ps-padma' ) . '</p>';
	}
	
	$target = ( $args['target'] === 'yes' || $args['target'] === 'blank' ) ? ' target="_blank"' : '';
	
	if ( $args['link'] === 'lightbox' || $args['lightbox'] === 'yes' ) {
		$args['class'] .= ' su-lightbox-gallery';
	}
	
	$html = '<div class="su-custom-gallery su-custom-gallery-title-' . esc_attr( $args['title'] ) . padma_ecssc( $args ) . '">';
	
	// Build slides
	foreach ( $slides as $slide ) {
		$title_attr = ! empty( $slide['title'] ) ? ' title="' . esc_attr( $slide['title'] ) . '"' : '';
		
		$html .= '<div class="su-custom-gallery-slide">';
		
		if ( ! empty( $slide['link'] ) ) {
			if ( $args['link'] === 'lightbox' || $args['lightbox'] === 'yes' ) {
				$html .= '<a href="' . esc_url( $slide['image'] ) . '" class="su-lightbox"' . $title_attr . '>';
			} else {
				$html .= '<a href="' . esc_url( $slide['link'] ) . '"' . $target . $title_attr . '>';
			}
			
			$html .= '<img src="' . esc_url( $slide['image'] ) . '" alt="' . esc_attr( $slide['title'] ) . '" width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '" />';
			
			if ( $args['title'] === 'hover' || $args['title'] === 'yes' ) {
				$html .= '<span class="su-custom-gallery-title">' . esc_html( $slide['title'] ) . '</span>';
			}
			
			$html .= '</a>';
		} else {
			$html .= '<img src="' . esc_url( $slide['image'] ) . '" alt="' . esc_attr( $slide['title'] ) . '" width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '"' . $title_attr . ' />';
		}
		
		$html .= '</div>';
	}
	
	$html .= '<div class="su-clear"></div>';
	$html .= '</div>';
	
	return $html;
}
