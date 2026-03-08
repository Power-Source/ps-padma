<?php
/**
 * Content Slider Block
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/blocks
 * @author     PSOURCE
 */

namespace Padma_Advanced;

/**
 * Content Slider Block - Swiper powered slider for posts
 */
class PadmaContentSliderBlock extends \PadmaBlockAPI {

    public $id 				= 'content-slider-block';    
    public $name 			= 'Content Slider';
    public $options_class 	= 'Padma_Advanced\\PadmaContentSliderBlockOptions';
    public $categories 		= array('content','gallery');
    
			
	function setup_elements() {
		
		$this->register_block_element(array(
			'id' => 'slide',
			'name' => 'Slide',
			'selector' => '.swiper-slide'
		));

		$this->register_block_element(array(
			'id' => 'slide-p',
			'name' => 'Slide text',
			'selector' => '.swiper-slide p'
		));

		$this->register_block_element(array(
			'id' => 'slide-a',
			'name' => 'Slide link',
			'selector' => '.swiper-slide a'
		));

		$this->register_block_element(array(
			'id' => 'slide-h1',
			'name' => 'Slide H1',
			'selector' => '.swiper-slide h1'
		));

		$this->register_block_element(array(
			'id' => 'slide-h2',
			'name' => 'Slide H2',
			'selector' => '.swiper-slide h2'
		));

		$this->register_block_element(array(
			'id' => 'slide-h3',
			'name' => 'Slide H3',
			'selector' => '.swiper-slide h3'
		));

		$this->register_block_element(array(
			'id' => 'slide-h4',
			'name' => 'Slide H4',
			'selector' => '.swiper-slide h4'
		));

		$this->register_block_element(array(
			'id' => 'slide-h5',
			'name' => 'Slide H5',
			'selector' => '.swiper-slide h5'
		));

		$this->register_block_element(array(
			'id' => 'slide-ul',
			'name' => 'Slide UL',
			'selector' => '.swiper-slide ul'
		));


		$this->register_block_element(array(
			'id' => 'slide-li',
			'name' => 'Slide LI',
			'selector' => '.swiper-slide li'
		));

		$this->register_block_element(array(
			'id' => 'dots',
			'name' => 'Dots',
			'selector' => '.swiper-pagination'
		));

		$this->register_block_element(array(
			'id' => 'dots-item',
			'name' => 'Dots Item',
			'selector' => '.swiper-pagination-bullet'
		));

		$this->register_block_element(array(
			'id' => 'nav',
			'name' => 'Navigation',
			'selector' => '.swiper-button-next, .swiper-button-prev'
		));

		$this->register_block_element(array(
			'id' => 'nav-item',
			'name' => 'Navigation item',
			'selector' => '.swiper-button-next, .swiper-button-prev'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-next',
			'name' => 'Navigation Next',
			'selector' => '.swiper-button-next'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-prev',
			'name' => 'Navigation Prev',
			'selector' => '.swiper-button-prev'
		));

		

	}

	public static function enqueue_action($block_id, $block) {
		wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
		wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0', false);

		// Fallback direct injection for VE context
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">';
		echo '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>';
	}

	function content($block) {

		// Content
		$post_type 			= ( !empty($block['settings']['post-type']) ) ? $block['settings']['post-type']: 'post';
		$categories 		= ( !empty($block['settings']['categories']) ) ? $block['settings']['categories']: array();
		$order_by 			= ( !empty($block['settings']['order-by']) ) ? $block['settings']['order-by']: 'date';
		$order 				= ( !empty($block['settings']['order']) ) ? $block['settings']['order']: 'desc';
		$onlyShowTitle 		= ( !empty($block['settings']['only-title']) ) ? true: false;
		$linkTitle 			= ( !empty($block['settings']['link-title']) ) ? true: false;
		$onlyShowFeatured 	= ( !empty($block['settings']['only-featured']) ) ? true: false;
		$onlyShowExcerpt 	= ( !empty($block['settings']['only-excerpt']) ) ? true: false;
		$showLink 			= ( !empty($block['settings']['show-link']) ) ? true: false;
		$showLinkText		= ( !empty($block['settings']['show-link-text']) ) ? $block['settings']['show-link-text']: 'Show more';

		
		global $post;

		$args 	= array ( 
					'post_type' 		=> $post_type,
					//'posts_per_page' 	=> $number,
					'orderby' 			=> $order_by,
					'order' 			=> $order 
				);

		if(count($categories) > 0) {
			$args['tax_query'] = array(
				array(
						'taxonomy' 	=> 'category',
						'field' 	=> 'id',
						'terms' 	=> $categories 
					)
			);
		}

		$content_slider_query = new WP_Query( $args );


		$result = '<div id="content-slider-'.$block['id'].'" class="swiper content-slider-swiper">';
		$result .= '<div class="swiper-wrapper">';

		while ( $content_slider_query->have_posts() ) : $content_slider_query->the_post();

			setup_postdata( $post );			

			if( !empty($block['settings']['item-width']) ){
				$itemTag = '<div class="swiper-slide item" style="width:'.$block['settings']['item-width'].'px">';
			}else{
				$itemTag = '<div class="swiper-slide item">';
			}

			if($onlyShowTitle){
				$result .= $itemTag;
				if($linkTitle){
					$result .= '<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
				}else{
					$result .= '<h3>'.get_the_title().'</h3>';
				}
				$result .= '</div>';
			}else{

				if($onlyShowFeatured && has_post_thumbnail()){
					$result .= $itemTag;
					$result .= get_the_post_thumbnail( 
						$post->ID, 
						'content-slider-thumb', 
						array( 
							'class' => "img-responsive",
							'alt' 	=> get_the_title(),
							'title' => get_the_title(),
						)
					);
					$result .= '</div>';
				
				}elseif (!$onlyShowFeatured && has_post_thumbnail() ) {
					
					$result .= $itemTag;
					$result .= get_the_post_thumbnail( 
						$post->ID, 
						'content-slider-thumb', 
						array( 
							'class' => "img-responsive",
							'alt' 	=> get_the_title(),
							'title' => get_the_title(),
						)
					);


					$result .= '<h3>'.get_the_title().'</h3>';
					if($onlyShowExcerpt){
						$result .= do_shortcode('<p>'.get_the_excerpt().'</p>');
					}else{
						$result .= do_shortcode('<p>'.get_the_content().'</p>');
					}

					if($showLink){
						$result .= '<a href='.get_the_permalink().'>' . $showLinkText . '</a>';
					}

					$result .= '</div>';
				
				}else{
					if($onlyShowExcerpt){
						$result .= $itemTag.do_shortcode('<p>'.get_the_excerpt().'</p>').'</div>';
					}else{
						$result .= $itemTag.do_shortcode('<p>'.get_the_content().'</p>').'</div>';
					}

					if($showLink){
						$result .= '<a href='.get_the_permalink().'>' . $showLinkText . '</a>';
					}
					
				}
			}


		endwhile;
		wp_reset_postdata();
		$result .= '</div>';
		$result .= '<div class="swiper-pagination content-slider-pagination-'.$block['id'].'"></div>';
		$result .= '<div class="swiper-button-prev content-slider-prev-'.$block['id'].'"></div>';
		$result .= '<div class="swiper-button-next content-slider-next-'.$block['id'].'"></div>';
		$result .= '</div>';

		echo $result;
	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = PadmaBlocksData::get_block($block_id);

		$slides_per_view = ( !empty($block['settings']['items']) && intval($block['settings']['items']) > 0 ) ? intval($block['settings']['items']) : 3;
		$space_between = !empty($block['settings']['margin']) ? intval($block['settings']['margin']) : 0;
		$loop = !empty($block['settings']['loop']) ? 'true' : 'false';
		$centered_slides = !empty($block['settings']['center']) ? 'true' : 'false';
		$allow_touch_move = !empty($block['settings']['touch-drag']) ? 'true' : 'false';
		$simulate_touch = !empty($block['settings']['mouse-drag']) ? 'true' : 'false';
		$free_mode = !empty($block['settings']['free-drag']) ? 'true' : 'false';
		$slides_per_group = !empty($block['settings']['slide-by']) ? intval($block['settings']['slide-by']) : 1;
		$initial_slide = !empty($block['settings']['start-position']) ? intval($block['settings']['start-position']) : 0;
		$speed = !empty($block['settings']['smart-speed']) ? intval($block['settings']['smart-speed']) : 250;
		$rewind = !empty($block['settings']['rewind']) ? 'true' : 'false';
		$show_nav = !empty($block['settings']['nav']) ? 'true' : 'false';
		$show_dots = isset($block['settings']['dots']) ? ( !empty($block['settings']['dots']) ? 'true' : 'false' ) : 'true';
		$lazy_load = !empty($block['settings']['lazy-load']) ? 'true' : 'false';
		$autoplay_timeout = !empty($block['settings']['autoplay-timeout']) ? intval($block['settings']['autoplay-timeout']) : 5000;
		$autoplay_pause_on_hover = !empty($block['settings']['autoplay-hover-pause']) ? 'true' : 'false';
		$autoplay = !empty($block['settings']['autoplay'])
			? '{delay: '.$autoplay_timeout.', disableOnInteraction: false, pauseOnMouseEnter: '.$autoplay_pause_on_hover.'}'
			: 'false';

		$js = 'if(document.readyState === "loading") {
			document.addEventListener("DOMContentLoaded", function() {
				if(document.querySelector("#content-slider-'.$block['id'].'")) {
					if(window.carousel_'.$block['id'].' && typeof window.carousel_'.$block['id'].'.destroy === "function") {
						window.carousel_'.$block['id'].'.destroy(true, true);
					}

					window.carousel_'.$block['id'].' = new Swiper("#content-slider-'.$block['id'].'", {
						slidesPerView: '.$slides_per_view.',
						spaceBetween: '.$space_between.',
						loop: '.$loop.' && document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1,
						watchOverflow: true,
						rewind: '.$rewind.',
						centeredSlides: '.$centered_slides.',
						allowTouchMove: document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1 ? '.$allow_touch_move.' : false,
						simulateTouch: document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1 ? '.$simulate_touch.' : false,
						freeMode: '.$free_mode.',
						slidesPerGroup: '.$slides_per_group.',
						initialSlide: '.$initial_slide.',
						speed: '.$speed.',
						autoplay: document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1 ? '.$autoplay.' : false,
						lazy: '.$lazy_load.',
						navigation: ('.$show_nav.' && document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1) ? {
							nextEl: "#content-slider-'.$block['id'].' .content-slider-next-'.$block['id'].'",
							prevEl: "#content-slider-'.$block['id'].' .content-slider-prev-'.$block['id'].'"
						} : false,
						pagination: ('.$show_dots.' && document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1) ? {
							el: "#content-slider-'.$block['id'].' .content-slider-pagination-'.$block['id'].'",
							clickable: true
						} : false
					});
				}
			});
		} else {
			if(document.querySelector("#content-slider-'.$block['id'].'")) {
				if(window.carousel_'.$block['id'].' && typeof window.carousel_'.$block['id'].'.destroy === "function") {
					window.carousel_'.$block['id'].'.destroy(true, true);
				}

				window.carousel_'.$block['id'].' = new Swiper("#content-slider-'.$block['id'].'", {
					slidesPerView: '.$slides_per_view.',
					spaceBetween: '.$space_between.',
					loop: '.$loop.' && document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1,
					watchOverflow: true,
					rewind: '.$rewind.',
					centeredSlides: '.$centered_slides.',
					allowTouchMove: document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1 ? '.$allow_touch_move.' : false,
					simulateTouch: document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1 ? '.$simulate_touch.' : false,
					freeMode: '.$free_mode.',
					slidesPerGroup: '.$slides_per_group.',
					initialSlide: '.$initial_slide.',
					speed: '.$speed.',
					autoplay: document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1 ? '.$autoplay.' : false,
					lazy: '.$lazy_load.',
					navigation: ('.$show_nav.' && document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1) ? {
						nextEl: "#content-slider-'.$block['id'].' .content-slider-next-'.$block['id'].'",
						prevEl: "#content-slider-'.$block['id'].' .content-slider-prev-'.$block['id'].'"
					} : false,
					pagination: ('.$show_dots.' && document.querySelectorAll("#content-slider-'.$block['id'].' .swiper-slide").length > 1) ? {
						el: "#content-slider-'.$block['id'].' .content-slider-pagination-'.$block['id'].'",
						clickable: true
					} : false
				});
			}
		}';

		return $js;
	}

	function custom_excerpt_post($text, $limit = 20){
		$excerpt = explode(' ', $text, $limit);

		if (count($excerpt)>=$limit) {
			
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		
		} else {
			$excerpt = implode(" ",$excerpt);

		}	
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		return $excerpt;
	}
	
}