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
 * Content Slider Block - Owl Carousel powered slider for posts
 */
class PadmaContentSliderBlock extends \PadmaBlockAPI {

    public $id 				= 'content-slider-block';    
    public $name 			= 'Content Slider';
    public $options_class 	= 'Padma_Advanced\\PadmaContentSliderBlockOptions';
    public $categories 		= array('content','gallery');

	function init() {
		$this->name = __( 'Content Slider', 'padma' );
	}
    
			
	function setup_elements() {
		
		$this->register_block_element(array(
			'id' => 'slide',
			'name' => 'Slide',
			'selector' => '.owl-item'
		));

		$this->register_block_element(array(
			'id' => 'slide-p',
			'name' => 'Slide Text',
			'selector' => '.owl-item p'
		));

		$this->register_block_element(array(
			'id' => 'slide-a',
			'name' => 'Slide Link',
			'selector' => '.owl-item a'
		));

		$this->register_block_element(array(
			'id' => 'slide-h1',
			'name' => 'Slide H1',
			'selector' => '.owl-item h1'
		));

		$this->register_block_element(array(
			'id' => 'slide-h2',
			'name' => 'Slide H2',
			'selector' => '.owl-item h2'
		));

		$this->register_block_element(array(
			'id' => 'slide-h3',
			'name' => 'Slide H3',
			'selector' => '.owl-item h3'
		));

		$this->register_block_element(array(
			'id' => 'slide-h4',
			'name' => 'Slide H4',
			'selector' => '.owl-item h4'
		));

		$this->register_block_element(array(
			'id' => 'slide-h5',
			'name' => 'Slide H5',
			'selector' => '.owl-item h5'
		));

		$this->register_block_element(array(
			'id' => 'slide-ul',
			'name' => 'Slide UL',
			'selector' => '.owl-item ul'
		));


		$this->register_block_element(array(
			'id' => 'slide-li',
			'name' => 'Slide LI',
			'selector' => '.owl-item li'
		));

		$this->register_block_element(array(
			'id' => 'dots',
			'name' => 'Punkte',
			'selector' => '.owl-dots'
		));

		$this->register_block_element(array(
			'id' => 'dots-item',
			'name' => 'Punkte Item',
			'selector' => '.owl-dots .owl-dot'
		));

		$this->register_block_element(array(
			'id' => 'nav',
			'name' => 'Navigation',
			'selector' => '.owl-nav'
		));

		$this->register_block_element(array(
			'id' => 'nav-item',
			'name' => 'Navigation Item',
			'selector' => '.owl-nav div'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-next',
			'name' => 'Navigation Weiter',
			'selector' => '.owl-nav div.owl-next'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-prev',
			'name' => 'Navigation Zurück',
			'selector' => '.owl-nav div.owl-prev'
		));

		

	}

	public static function enqueue_action($block_id, $block) {
		$theme_url = get_template_directory_uri() . '/library/blocks-advanced/content-slider/';
		
		wp_enqueue_style('padma-content-slider-owl-carousel-css', $theme_url . 'css/owl.carousel.min.css');
		wp_enqueue_style('padma-content-slider-owl-theme-css', $theme_url . 'css/owl.theme.default.min.css');
		//wp_enqueue_style('padma-content-slider-owl-theme-green-css', $theme_url . 'css/owl.theme.green.min.css');
		wp_enqueue_script('padma-content-slider-slider-js', $theme_url . 'js/owl.carousel.min.js', array('jquery'), '1.0', false);
	}

	public static function dynamic_css($block_id, $block = false) {
		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		$image_max_height = !empty($block['settings']['image-max-height']) ? intval($block['settings']['image-max-height']) : 400;

		$css = '
			#content-slider-' . $block_id . ' .item {
				display: flex;
				flex-direction: column;
				align-items: center;
			}
			#content-slider-' . $block_id . ' .item img {
				max-width: 100%;
				max-height: ' . $image_max_height . 'px;
				height: auto;
				width: 100%;
				object-fit: contain;
				display: block;
			}
			#content-slider-' . $block_id . ' .item h3,
			#content-slider-' . $block_id . ' .item p,
			#content-slider-' . $block_id . ' .item a {
				margin: 10px 0;
				padding: 0 15px;
				width: 100%;
				box-sizing: border-box;
			}
			#content-slider-' . $block_id . ' .owl-carousel {
				margin-bottom: 0;
			}
			#content-slider-' . $block_id . ' .owl-stage-outer {
				padding-bottom: 0;
			}
		';

		return $css;
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
		$showLinkText		= ( !empty($block['settings']['show-link-text']) ) ? $block['settings']['show-link-text']: __( 'Mehr anzeigen', 'padma' );

		
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

		$content_slider_query = new \WP_Query( $args );

		// Keine Posts gefunden - gib nichts aus
		if ( !$content_slider_query->have_posts() ) {
			return;
		}

		$result = '<div id="content-slider-'.$block['id'].'" class="owl-carousel owl-theme">';

		while ( $content_slider_query->have_posts() ) : $content_slider_query->the_post();			

			if( !empty($block['settings']['item-width']) ){
				$itemTag = '<div class="item" style="width:'.$block['settings']['item-width'].'px">';
			}else{
				$itemTag = '<div class="item">';
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
						get_the_ID(), 
						'large', 
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
						get_the_ID(), 
						'large', 
						array( 
							'class' => "img-responsive",
							'alt' 	=> get_the_title(),
							'title' => get_the_title(),
						)
					);


					$result .= '<h3>'.get_the_title().'</h3>';
					if($onlyShowExcerpt){
						$result .= '<p>'.get_the_excerpt().'</p>';
					}else{
						$result .= '<p>'.wp_trim_words(get_the_excerpt(), 30, '...').'</p>';
					}

					if($showLink){
						$result .= '<a href='.get_the_permalink().'>' . $showLinkText . '</a>';
					}

					$result .= '</div>';
				
				}else{
					$result .= $itemTag;
					if($onlyShowExcerpt){
						$result .= '<p>'.get_the_excerpt().'</p>';
					}else{
						$result .= '<p>'.wp_trim_words(get_the_excerpt(), 30, '...').'</p>';
					}

					if($showLink){
						$result .= '<a href='.get_the_permalink().'>' . $showLinkText . '</a>';
					}
					$result .= '</div>';
				}
			}


		endwhile;
		wp_reset_postdata();

		$result .= '</div>';

		echo $result;
	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		// Settings
		$carouselParams = '';

		// Items
		$carouselParams .= 'items:' . ( !empty($block['settings']['items']) && $block['settings']['items'] > 0 ? $block['settings']['items'] : '1' ) . ', ';

		// Margin
		$carouselParams .= 'margin:' . ( !empty($block['settings']['margin']) ? $block['settings']['margin'] : '0' ) . ', ';

		// Loop
		$carouselParams .= 'loop:' . ( !empty($block['settings']['loop']) ? $block['settings']['loop'] : 'true' ) . ', ';

		// Center
		$carouselParams .= 'center:' . ( !empty($block['settings']['center']) ? $block['settings']['center'] : 'false' ) . ', ';

		// mouse-drag
		$carouselParams .= 'mouseDrag:' . ( !empty($block['settings']['mouse-drag']) ? $block['settings']['mouse-drag'] : 'true' ) . ', ';

		// touch-drag
		$carouselParams .= 'touchDrag:' . ( !empty($block['settings']['touch-drag']) ? $block['settings']['touch-drag'] : 'true' ) . ', ';

		// pull-drag
		$carouselParams .= 'pullDrag:' . ( !empty($block['settings']['pull-drag']) ? $block['settings']['pull-drag'] : 'true' ) . ', ';

		// pull-drag
		$carouselParams .= 'freeDrag:' . ( !empty($block['settings']['free-drag']) ? $block['settings']['free-drag'] : 'false' ) . ', ';

		// stagePadding
		$carouselParams .= 'stagePadding:' . ( !empty($block['settings']['stage-padding']) ? $block['settings']['stage-padding'] : '0' ) . ', ';

		// merge
		$carouselParams .= 'merge:'. ( !empty($block['settings']['merge']) ? $block['settings']['merge']: 'false' ) . ', ';
		
		// mergeFit
		$carouselParams .= 'mergeFit:'. ( !empty($block['settings']['merge-fit']) ? $block['settings']['merge-fit']: 'true' ) . ', ';
		
		// autoWidth
		$carouselParams .= 'autoWidth:'. ( !empty($block['settings']['auto-width']) ? 'true': 'false' ) . ', ';
		
		// startPosition
		$carouselParams .= 'startPosition:'. ( !empty($block['settings']['start-position']) ? $block['settings']['start-position']: '0' ) . ', ';
		
		// startPosition
		$carouselParams .= 'URLhashListener:'. ( !empty($block['settings']['url-hash-listener']) ? $block['settings']['url-hash-listener']: '0' ) . ', ';
		
		// nav
		$carouselParams .= 'nav:'. ( !empty($block['settings']['nav']) ? $block['settings']['nav']: 'true' ) . ', ';

		// rewind
		$carouselParams .= 'rewind:'. ( !empty($block['settings']['rewind']) ? $block['settings']['rewind']: 'true' ) . ', ';

		// navText
		if( !empty($block['settings']['nav-text-next']) || !empty($block['settings']['nav-text-prev']) ){
			
			$navText_next	= ( !empty($block['settings']['nav-text-next']) ) ? $block['settings']['nav-text-next']: '>';
			$navText_prev	= ( !empty($block['settings']['nav-text-prev']) ) ? $block['settings']['nav-text-prev']: '<';
			$carouselParams .= 'navText:["'.$navText_prev.'","'.$navText_next.'"],';
		}else{
			$carouselParams .= 'navText:["Prev","Next"],';			
		}

		// navElement
		$carouselParams .= 'navElement:'. ( !empty($block['settings']['nav-element']) ? $block['settings']['nav-element']: '"div"' ) . ', ';

		// slideBy
		$carouselParams .= 'slideBy:'. ( !empty($block['settings']['slide-by']) ? $block['settings']['slide-by']: '1' ) . ', ';

		// slideTransition
		$carouselParams .= 'slideTransition:'. ( !empty($block['settings']['slide-transition']) ? $block['settings']['slide-transition']: '``' ) . ', ';

		// dots
		$carouselParams .= 'dots:'. (!empty($block['settings']['dots']) ? $block['settings']['dots'] : 'true') . ', ';
		
		// dotsEach
		$carouselParams .= 'dotsEach:'. (!empty($block['settings']['dots-each']) ? $block['settings']['dots-each'] : 'false') . ', ';

		// dotsData
		$carouselParams .= 'dotsData:'. (!empty($block['settings']['dots-data']) ? $block['settings']['dots-data'] : 'false') . ', ';
		
		// lazyLoad
		$carouselParams .= 'lazyLoad:'. (!empty($block['settings']['lazy-load']) ? $block['settings']['lazy-load'] : 'false') . ', ';

		// lazyLoadEager
		$carouselParams .= 'lazyLoadEager:'. (!empty($block['settings']['lazy-load-eager']) ? $block['settings']['lazy-load-eager'] : '0') . ', ';
		
		// autoPlay
		$carouselParams .= 'autoPlay:'. (!empty($block['settings']['autoplay']) ? 'true' : 'false') . ', ';
		
		// autoplayTimeout
		$carouselParams .= 'autoplayTimeout:'. (!empty($block['settings']['autoplay-timeout']) ? $block['settings']['autoplay-timeout'] : '5000') . ', ';
		
		// autoplayHoverPause
		$carouselParams 	.= 'autoplayHoverPause:'. (!empty($block['settings']['autoplay-hover-pause']) ? $block['settings']['autoplay-hover-pause'] : 'false') . ', ';

		// smartSpeed
		$carouselParams 	.= 'smartSpeed:'. (!empty($block['settings']['smart-speed']) ? $block['settings']['smart-speed'] : '250') . ', ';		

		// fluidSpeed
		$carouselParams 	.= 'fluidSpeed:'. (!empty($block['settings']['fluid-speed']) ? $block['settings']['fluid-speed'] : 'Number') . ', ';		

		// autoplaySpeed
		$carouselParams 	.= 'autoplaySpeed:'. (!empty($block['settings']['autoplay-speed']) ? $block['settings']['autoplay-speed'] : 'false') . ', ';

		// navSpeed
		$carouselParams 	.= 'navSpeed:'. (!empty($block['settings']['nav-speed']) ? $block['settings']['nav-speed'] : 'false') . ', ';		

		// dotsSpeed
		$carouselParams 	.= 'dotsSpeed:'. (!empty($block['settings']['dots-speed']) ? $block['settings']['dots-speed'] : 'false') . ', ';		

		// dragEndSpeed
		$carouselParams 	.= 'dragEndSpeed:'. (!empty($block['settings']['dragend-speed']) ? $block['settings']['dragend-speed'] : 'false') . ', ';	

		// callbacks
		$carouselParams 	.= 'callbacks:'. (!empty($block['settings']['callbacks']) ? $block['settings']['callbacks'] : 'false') . ', ';
		
		// Autoplay
		$carouselParams .= 'autoplay:'. ( !empty($block['settings']['autoplay']) ? $block['settings']['autoplay'] : 'false' ) . ', ';
		$carouselParams .= 'autoplayTimeout:'. ( !empty($block['settings']['autoplay-timeout']) ? $block['settings']['autoplay-timeout'] : '5000' ) . ', ';
		$carouselParams .= 'autoplayHoverPause:'. ( !empty($block['settings']['autoplay-hover-pause']) ? $block['settings']['autoplay-hover-pause'] : 'true' ) . ', ';
		
		// responsive
		$carouselParams 	.= 'responsive:{ 0:{ items: 1 }, 768:{ items: ' . ( !empty($block['settings']['items']) ? $block['settings']['items'] : '1' ) . ' } }, ';
				
		// responsiveRefreshRate
		$carouselParams 	.= 'responsiveRefreshRate:'. (!empty($block['settings']['responsive-refresh-rate']) ? $block['settings']['responsive-refresh-rate'] : '200') . ', ';
		
		// responsiveBaseElement
		$carouselParams 	.= 'responsiveBaseElement:'. (!empty($block['settings']['responsive-base-element']) ? $block['settings']['responsive-base-element'] : 'window') . ', ';
		
		// video
		$carouselParams 	.= 'video:'. (!empty($block['settings']['video']) ? $block['settings']['video'] : 'false') . ', ';

		// videoHeight
		$carouselParams 	.= 'videoHeight:'. (!empty($block['settings']['video-height']) ? $block['settings']['video-height'] : 'false') . ', ';
		
		// videoWidth
		$carouselParams 	.= 'videoWidth:'. (!empty($block['settings']['video-width']) ? $block['settings']['video-width'] : 'false') . ', ';

		// animateOut
		$carouselParams 	.= 'animateOut:'. (!empty($block['settings']['animate-out']) ? $block['settings']['animate-out'] : 'false') . ', ';

		// animateIn
		$carouselParams 	.= 'animateIn:'. (!empty($block['settings']['animate-in']) ? $block['settings']['animate-in'] : 'false') . ', ';
		
		// fallbackEasing
		$carouselParams 	.= 'fallbackEasing:'. (!empty($block['settings']['fallback-easing']) ? $block['settings']['fallback-easing'] : '"swing"') . ', ';
		
		// info
		$carouselParams 	.= 'info:'. (!empty($block['settings']['info']) ? $block['settings']['info'] : 'false') . ', ';
		
		// nestedItemSelector
		$carouselParams 	.= 'nestedItemSelector:'. (!empty($block['settings']['nested-item-selector']) ? $block['settings']['nested-item-selector'] : 'false') . ', ';
		
		// itemElement
		$carouselParams 	.= 'itemElement:'. (!empty($block['settings']['item-element']) ? $block['settings']['item-element'] : '"div"') . ', ';
		
		// stageElement
		$carouselParams .= 'stageElement:'. (!empty($block['settings']['stage-element']) ? $block['settings']['stage-element'] : '"div"') . ', ';
		
		// navContainer
		$carouselParams .= 'navContainer:'. (!empty($block['settings']['nav-container']) ? $block['settings']['nav-container'] : 'false') . ', ';
		
		// dotsContainer
		$carouselParams .= 'dotsContainer:'. (!empty($block['settings']['dots-container']) ? $block['settings']['dots-container'] : 'false') . ', ';
		
		// checkVisible
		$carouselParams .= 'checkVisible:'. (!empty($block['settings']['check-visible']) ? $block['settings']['check-visible'] : 'true');
			 
		$js = 'jQuery(document).ready(function($){';
		$js .= 'window.carousel_'.$block['id'].' = jQuery("#content-slider-'.$block['id'].'.owl-carousel").owlCarousel({';
		$js .= $carouselParams;
		$js .= '});});';

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