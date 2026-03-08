<?php
/**
 * Content Slider Block - Swiper powered slider
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/blocks
 * @author     PSOURCE
 */

namespace Padma_Advanced;

/**
 * Content Slider Block - Modern Swiper slider for posts
 */
class PadmaContentSliderBlock extends \PadmaBlockAPI {

    public $id 				= 'content-slider-block';    
    public $name 			= 'Content Slider';
    public $options_class 	= 'Padma_Advanced\\PadmaContentSliderBlockOptions';
    public $categories 		= array('content','gallery');

	function init() {
		$this->name = __( 'Content Slider', 'padma' );
	}

	private static function is_visual_editor_context($block = array()) {
		if ( padma_get('ve-iframe') || padma_get('visual-editor-open') || padma_get('visual-editor') )
			return true;

		if ( class_exists('\\PadmaRoute') && ( \PadmaRoute::is_visual_editor() || \PadmaRoute::is_visual_editor_iframe() ) )
			return true;

		if ( !empty($block['ve-live-content-query']) )
			return true;

		return false;
	}

	private static function should_show_navigate_mode($block = array()) {
		return !empty($block['settings']['navigate-mode']);
	}
    
			
	function setup_elements() {
		
		$this->register_block_element(array(
			'id' => 'slide',
			'name' => 'Slide',
			'selector' => '.item'
		));

		$this->register_block_element(array(
			'id' => 'slide-p',
			'name' => 'Slide Text',
			'selector' => '.item p'
		));

		$this->register_block_element(array(
			'id' => 'slide-a',
			'name' => 'Slide Link',
			'selector' => '.item a'
		));

		$this->register_block_element(array(
			'id' => 'slide-h1',
			'name' => 'Slide H1',
			'selector' => '.item h1'
		));

		$this->register_block_element(array(
			'id' => 'slide-h2',
			'name' => 'Slide H2',
			'selector' => '.item h2'
		));

		$this->register_block_element(array(
			'id' => 'slide-h3',
			'name' => 'Slide H3',
			'selector' => '.item h3'
		));

		$this->register_block_element(array(
			'id' => 'slide-h4',
			'name' => 'Slide H4',
			'selector' => '.item h4'
		));

		$this->register_block_element(array(
			'id' => 'slide-h5',
			'name' => 'Slide H5',
			'selector' => '.item h5'
		));

		$this->register_block_element(array(
			'id' => 'slide-ul',
			'name' => 'Slide UL',
			'selector' => '.item ul'
		));


		$this->register_block_element(array(
			'id' => 'slide-li',
			'name' => 'Slide LI',
			'selector' => '.item li'
		));

		$this->register_block_element(array(
			'id' => 'dots',
			'name' => 'Punkte',
			'selector' => '.owl-dots'
		));

		$this->register_block_element(array(
			'id' => 'dots-item',
			'name' => 'Punkte Item',
			'selector' => '.owl-dot'
		));

		$this->register_block_element(array(
			'id' => 'nav',
			'name' => 'Navigation',
			'selector' => '.owl-nav'
		));

		$this->register_block_element(array(
			'id' => 'nav-item',
			'name' => 'Navigation Item',
			'selector' => '.owl-nav button'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-next',
			'name' => 'Navigation Weiter',
			'selector' => '.owl-nav button.owl-next'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-prev',
			'name' => 'Navigation Zurück',
			'selector' => '.owl-nav button.owl-prev'
		));

		

	}

	public static function enqueue_action($block_id, $block) {
		$theme_url = get_template_directory_uri() . '/library/blocks-advanced/content-slider/';
		
		wp_enqueue_style('padma-content-slider-owl-carousel-css', $theme_url . 'css/owl.carousel.min.css');
		wp_enqueue_style('padma-content-slider-owl-theme-css', $theme_url . 'css/owl.theme.default.min.css');
		wp_enqueue_script('padma-content-slider-slider-js', $theme_url . 'js/owl.carousel.min.js', array('jquery'), '1.0', false);

		// Fallback direct injection for VE context
		echo '<link rel="stylesheet" href="' . $theme_url . 'css/owl.carousel.min.css">';
		echo '<link rel="stylesheet" href="' . $theme_url . 'css/owl.theme.default.min.css">';
		echo '<script src="' . $theme_url . 'js/owl.carousel.min.js"></script>';
	}

	public static function dynamic_css($block_id, $block = false) {
		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		$image_max_height = !empty($block['settings']['image-max-height']) ? intval($block['settings']['image-max-height']) : 400;
		$in_visual_editor = self::is_visual_editor_context($block);
		$show_navigate = self::should_show_navigate_mode($block);

		$css = '';
		
		// Im VE: Owl Carousel CSS direkt laden (falls nicht via enqueue verfügbar)
		if ($in_visual_editor) {
			$theme_url = get_template_directory_uri() . '/library/blocks-advanced/content-slider/';
			$css .= '@import url("' . $theme_url . 'css/owl.carousel.min.css");';
			$css .= '@import url("' . $theme_url . 'css/owl.theme.default.min.css");';
		}
		
		$css .= '
			#content-slider-' . $block_id . ' {
				overflow: hidden;
				position: relative;
			}';

		// Im VE ohne Navigate-Mode: zeige nur ersten Slide und deaktiviere Navigation visuell
		if ( $in_visual_editor && !$show_navigate ) {
			$css .= '
			#content-slider-' . $block_id . ' .item:nth-child(n+2) {
				display: none;
			}
			#content-slider-' . $block_id . ' .owl-nav,
			#content-slider-' . $block_id . ' .owl-dots {
				pointer-events: none !important;
				opacity: 0.3 !important;
				position: relative;
			}
			#content-slider-' . $block_id . ' .owl-nav::after {
				content: "Navigation - nur zum Stylen (Navigate Mode aktivieren für Funktion)";
				position: absolute;
				top: -25px;
				left: 50%;
				transform: translateX(-50%);
				background: rgba(255,255,0,0.9);
				padding: 5px 10px;
				color: #000;
				font-size: 11px;
				white-space: nowrap;
				z-index: 1000;
				pointer-events: none;
				border-radius: 3px;
			}';
		}

		$css .= '
			#content-slider-' . $block_id . ' .item {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				padding: 10px;
				box-sizing: border-box;
			}
			#content-slider-' . $block_id . ' .item img {
				max-width: 100%;
				max-height: ' . $image_max_height . 'px;
				height: auto;
				width: 100%;
				object-fit: contain;
				display: block;
			}
			#content-slider-' . $block_id . ' .item h1,
			#content-slider-' . $block_id . ' .item h2,
			#content-slider-' . $block_id . ' .item h3,
			#content-slider-' . $block_id . ' .item h4,
			#content-slider-' . $block_id . ' .item h5,
			#content-slider-' . $block_id . ' .item h6,
			#content-slider-' . $block_id . ' .item p,
			#content-slider-' . $block_id . ' .item a {
				margin: 10px 0;
				padding: 0 15px;
				width: 100%;
				box-sizing: border-box;
			}
			#content-slider-' . $block_id . ' .owl-carousel {
				margin: 0;
				padding: 0;
			}
			#content-slider-' . $block_id . ' .owl-stage-outer {
				padding: 0;
				overflow: visible !important;
			}
			#content-slider-' . $block_id . ' .owl-stage {
				display: flex;
			}
			#content-slider-' . $block_id . ' .owl-nav {
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				width: 100%;
				display: flex;
				justify-content: space-between;
				align-items: center;
				pointer-events: none;
				z-index: 10;
			}
			#content-slider-' . $block_id . ' .owl-nav > * {
				pointer-events: all;
				background: rgba(0,0,0,0.3);
				color: #fff;
				border: none;
				padding: 10px 15px;
				cursor: pointer;
				font-size: 18px;
				z-index: 10;
			}
			#content-slider-' . $block_id . ' .owl-nav > *:hover {
				background: rgba(0,0,0,0.6);
			}
			#content-slider-' . $block_id . ' .owl-dots {
				text-align: center;
				margin-top: 15px;
				display: block;
				line-height: 0;
			}
			#content-slider-' . $block_id . ' .owl-dot {
				height: 12px !important;
				width: 12px !important;
				margin: 0 5px !important;
				background: #ccc !important;
				display: inline-block !important;
				border-radius: 50% !important;
				cursor: pointer !important;
				border: none !important;
				padding: 0 !important;
				vertical-align: middle;
			}
			#content-slider-' . $block_id . ' .owl-dot.active {
				background: #333 !important;
			}
			#content-slider-' . $block_id . ' .owl-dot span {
				display: none;
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

		$slider_classes = 'owl-carousel owl-theme';

		$result = '<div id="content-slider-'.$block['id'].'" class="'.$slider_classes.'">';

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

		// Bestimme ob Interaktionen deaktiviert werden sollen
		$in_visual_editor = self::is_visual_editor_context($block);
		$show_navigate = self::should_show_navigate_mode($block);
		$disable_interactions = $in_visual_editor && !$show_navigate;

		// Bestimme ob Buttons SICHTBAR sind (Settings)
		$show_nav_setting = ( isset($block['settings']['nav']) && $block['settings']['nav'] === 'false' ) ? false : true;
		$show_dots_setting = ( isset($block['settings']['dots']) && $block['settings']['dots'] === 'false' ) ? false : true;

		// Owl Carousel generiert Navigation/Dots selbst - keine manuellen Buttons nötig

		$result .= '</div>';

		echo $result;
	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		// Prüfe ob wir im VE sind und Navigate-Mode deaktiviert
		$in_visual_editor = self::is_visual_editor_context($block);
		$show_navigate = self::should_show_navigate_mode($block);
		$disable_interactions = $in_visual_editor && !$show_navigate;

		// Lese nav/dots Settings
		$show_nav_setting = ( isset($block['settings']['nav']) && $block['settings']['nav'] === 'false' ) ? false : true;
		$show_dots_setting = ( isset($block['settings']['dots']) && $block['settings']['dots'] === 'false' ) ? false : true;

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
		$mouseDrag = $disable_interactions ? 'false' : ( !empty($block['settings']['mouse-drag']) ? $block['settings']['mouse-drag'] : 'true' );
		$carouselParams .= 'mouseDrag:' . $mouseDrag . ', ';

		// touch-drag
		$touchDrag = $disable_interactions ? 'false' : ( !empty($block['settings']['touch-drag']) ? $block['settings']['touch-drag'] : 'true' );
		$carouselParams .= 'touchDrag:' . $touchDrag . ', ';

		// pull-drag
		$pullDrag = $disable_interactions ? 'false' : ( !empty($block['settings']['pull-drag']) ? $block['settings']['pull-drag'] : 'true' );
		$carouselParams .= 'pullDrag:' . $pullDrag . ', ';

		// free-drag
		$freeDrag = $disable_interactions ? 'false' : ( !empty($block['settings']['free-drag']) ? $block['settings']['free-drag'] : 'false' );
		$carouselParams .= 'freeDrag:' . $freeDrag . ', ';

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
		
		// nav - Im VE immer generieren wenn Setting aktiv (für Styling), Interaktivität per CSS gesteuert
		// Im Frontend: nur wenn Setting aktiv
		$show_nav_final = $in_visual_editor ? $show_nav_setting : ($show_nav_setting && !$disable_interactions);
		$carouselParams .= 'nav:' . ( $show_nav_final ? 'true' : 'false' ) . ', ';

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

		// navElement - default button für bessere Kompatibilität
		$carouselParams .= 'navElement:'. ( !empty($block['settings']['nav-element']) ? $block['settings']['nav-element']: '"button"' ) . ', ';

		// slideBy
		$carouselParams .= 'slideBy:'. ( !empty($block['settings']['slide-by']) ? $block['settings']['slide-by']: '1' ) . ', ';

		// slideTransition
		$carouselParams .= 'slideTransition:'. ( !empty($block['settings']['slide-transition']) ? $block['settings']['slide-transition']: '``' ) . ', ';

		// dots - Im VE immer generieren wenn Setting aktiv (für Styling), Interaktivität per CSS gesteuert
		// Im Frontend: nur wenn Setting aktiv
		$show_dots_final = $in_visual_editor ? $show_dots_setting : ($show_dots_setting && !$disable_interactions);
		$carouselParams .= 'dots:' . ( $show_dots_final ? 'true' : 'false' ) . ', ';
		
		// dotsEach
		$carouselParams .= 'dotsEach:'. (!empty($block['settings']['dots-each']) ? $block['settings']['dots-each'] : 'false') . ', ';

		// dotsData
		$carouselParams .= 'dotsData:'. (!empty($block['settings']['dots-data']) ? $block['settings']['dots-data'] : 'false') . ', ';
		
		// lazyLoad
		$carouselParams .= 'lazyLoad:'. (!empty($block['settings']['lazy-load']) ? $block['settings']['lazy-load'] : 'false') . ', ';

		// lazyLoadEager
		$carouselParams .= 'lazyLoadEager:'. (!empty($block['settings']['lazy-load-eager']) ? $block['settings']['lazy-load-eager'] : '0') . ', ';
		
		// autoplay - deaktiviert wenn Interaktionen gesperrt sind (lowercase!)
		$autoPlay = $disable_interactions ? 'false' : ( !empty($block['settings']['autoplay']) ? 'true' : 'false' );
		$carouselParams .= 'autoplay:'. $autoPlay . ', ';
		
		// autoplayTimeout
		$carouselParams .= 'autoplayTimeout:'. (!empty($block['settings']['autoplay-timeout']) ? $block['settings']['autoplay-timeout'] : '5000') . ', ';
		
		// autoplayHoverPause
		$carouselParams .= 'autoplayHoverPause:'. (!empty($block['settings']['autoplay-hover-pause']) ? $block['settings']['autoplay-hover-pause'] : 'false') . ', ';

		// smartSpeed
		$carouselParams .= 'smartSpeed:'. (!empty($block['settings']['smart-speed']) ? $block['settings']['smart-speed'] : '250') . ', ';		

		// fluidSpeed
		$carouselParams .= 'fluidSpeed:'. (!empty($block['settings']['fluid-speed']) ? $block['settings']['fluid-speed'] : 'false') . ', ';		

		// autoplaySpeed
		$carouselParams .= 'autoplaySpeed:'. (!empty($block['settings']['autoplay-speed']) ? $block['settings']['autoplay-speed'] : 'false') . ', ';

		// navSpeed
		$carouselParams .= 'navSpeed:'. (!empty($block['settings']['nav-speed']) ? $block['settings']['nav-speed'] : 'false') . ', ';		

		// dotsSpeed
		$carouselParams .= 'dotsSpeed:'. (!empty($block['settings']['dots-speed']) ? $block['settings']['dots-speed'] : 'false') . ', ';		

		// dragEndSpeed
		$carouselParams .= 'dragEndSpeed:'. (!empty($block['settings']['dragend-speed']) ? $block['settings']['dragend-speed'] : 'false') . ', ';	

		// callbacks
		$carouselParams .= 'callbacks:'. (!empty($block['settings']['callbacks']) ? $block['settings']['callbacks'] : 'false') . ', ';
		
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
		
		// checkVisible
		$carouselParams .= 'checkVisible:'. (!empty($block['settings']['check-visible']) ? $block['settings']['check-visible'] : 'true');
		
		// Theme URL für Script-Loading
		$theme_url = get_template_directory_uri() . '/library/blocks-advanced/content-slider/';
			 
		// Owl Carousel Library ggf. dynamisch nachladen im VE
		$js = '(function() {
			function initCarousel() {
				if(jQuery("#content-slider-'.$block['id'].'" ).length) {
					window.carousel_'.$block['id'].' = jQuery("#content-slider-'.$block['id'].'.owl-carousel").owlCarousel({'.$carouselParams.'});
				}
			}
			
			function loadOwlCarousel() {
				if (typeof jQuery !== "undefined" && typeof jQuery.fn.owlCarousel !== "undefined") {
					initCarousel();
				} else if (typeof jQuery !== "undefined") {
					var script = document.createElement("script");
					script.src = "'.$theme_url.'js/owl.carousel.min.js";
					script.onload = function() {
						initCarousel();
					};
					document.head.appendChild(script);
				}
			}
			
			if(document.readyState === "loading") {
				document.addEventListener("DOMContentLoaded", loadOwlCarousel);
			} else {
				loadOwlCarousel();
			}
		})();';

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