<?php

class PadmaSliderBlock extends PadmaBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'slider';	
		$this->name = 'Slider';		
		$this->options_class = 'PadmaSliderBlockOptions';	
		$this->fixed_height = false;
		$this->description = __('Create effective responsive image slideshows.','padma');
		$this->categories = array('core','content', 'media');

	}


	public static function enqueue_action($block_id, $block) {

		$images = parent::get_setting($block, 'images', array());
		$valid_images_count = 0;

		foreach ( $images as $image ) {
			if ( !empty($image['image']) ) {
				$valid_images_count++;
			}
		}

		// Load Swiper CSS/JS
		wp_enqueue_style('swiper-slider', padma_url() . '/library/blocks/slider/assets/swiper-bundle.min.css');

		// If there are no images or only 1 image, do not load Swiper JS.
		if ( $valid_images_count <= 1 )
			return false;

		wp_enqueue_script('swiper-slider', padma_url() . '/library/blocks/slider/assets/swiper-bundle.min.js', array());
		
		// Fallback direct injection for VE context
		echo '<link rel="stylesheet" href="' . padma_url() . '/library/blocks/slider/assets/swiper-bundle.min.css">';
		echo '<script src="' . padma_url() . '/library/blocks/slider/assets/swiper-bundle.min.js"></script>';

	}


	public static function dynamic_js($block_id, $block) {

		$images = parent::get_setting($block, 'images', array());
		$valid_images_count = 0;

		foreach ( $images as $image ) {
			if ( !empty($image['image']) ) {
				$valid_images_count++;
			}
		}

		// If only 1 image, skip Swiper init
		if ( $valid_images_count <= 1 )
			return false;

		$animation = parent::get_setting($block, 'animation', 'slide-horizontal');
		$effect = ($animation == 'fade') ? 'fade' : 'slide';
		$direction = ($animation == 'slide-vertical') ? 'vertical' : 'horizontal';
		$autoplay_enabled = parent::get_setting($block, 'slideshow', true);
		$autoplay_delay = (int)parent::get_setting($block, 'animation-timeout', 6) * 1000;
		$animation_speed = (int)parent::get_setting($block, 'animation-speed', 500);
		$show_pagination = parent::get_setting($block, 'show-pager-nav', true);
		$show_nav = parent::get_setting($block, 'show-direction-nav', true);

		return '
(function() {
	var sliderSelector = "#block-' . $block['id'] . ' .swiper";
	var basePath = "' . padma_url() . '/library/blocks/slider/assets/";
	
	function ensureStyle(id, href) {
		if (document.querySelector("link[href=\"" + href + "\"]")) return;
		var link = document.createElement("link");
		link.rel = "stylesheet";
		link.href = href;
		document.head.appendChild(link);
	}
	
	function initSwiper_' . $block['id'] . '() {
		if (!window.Swiper) return false;
		var container = document.querySelector(sliderSelector);
		if (!container) return false;
		if (container.swiper) container.swiper.destroy();
		
		new Swiper(sliderSelector, {
			effect: "' . $effect . '",
			direction: "' . $direction . '",
			speed: ' . $animation_speed . ',
			autoplay: ' . ($autoplay_enabled ? '{delay: ' . $autoplay_delay . '}' : 'false') . ',
			pagination: {
				el: ".swiper-pagination",
				enabled: ' . ($show_pagination ? 'true' : 'false') . ',
				type: "bullets",
				clickable: true
			},
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
				enabled: ' . ($show_nav ? 'true' : 'false') . '
			},
			loop: ' . ($valid_images_count > 1 ? 'true' : 'false') . '
		});
		return true;
	}
	
	function bootSwiper_' . $block['id'] . '() {
		ensureStyle("swiper-css", basePath + "swiper-bundle.min.css");
		if (initSwiper_' . $block['id'] . '()) return;
		if (!window.Swiper) {
			setTimeout(bootSwiper_' . $block['id'] . ', 150);
		}
	}
	
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", bootSwiper_' . $block['id'] . ');
	} else {
		bootSwiper_' . $block['id'] . '();
	}
	setTimeout(bootSwiper_' . $block['id'] . ', 350);
})();
' . "\n";

	}


	function content($block) {

		$images = parent::get_setting($block, 'images', array());

		$block_width = PadmaBlocksData::get_block_width($block);
		$block_height = PadmaBlocksData::get_block_height($block);

		$has_images = false;
		$valid_images_count = 0;

		foreach ( $images as $image ){
			if ( !empty($image['image']) ) {
				$has_images = true;
				$valid_images_count++;
			}
		}

		if ( !$has_images ) {

			echo '<div class="alert alert-yellow"><p>' . __('There are no images to display.','padma') . '</p></div>';

			return;

		}

		// OPTIMIERUNG: Batch-Resize für alle Bilder statt einzeln
		$should_resize = parent::get_setting($block, 'crop-resize-images', true);
		$resized_urls = array();
		
		if ( $should_resize ) {
			$image_urls = array_filter(array_column($images, 'image'));
			
			// Lade Caching-Funktion wenn vorhanden
			if ( function_exists('padma_resize_images_batch') ) {
				$resized_urls = padma_resize_images_batch($image_urls, $block_width, $block_height);
			} else {
				// Fallback zu einzelnem Resize
				foreach ( $image_urls as $url ) {
					$resized_urls[] = padma_resize_image($url, $block_width, $block_height);
				}
			}
		}

		$resized_index = 0;
		$no_slide_class = $valid_images_count === 1 ? ' flexslider-no-slide' : '';

		echo '<div class="swiper">';

			echo '<div class="swiper-wrapper">';


			foreach ( $images as $image ) {

				if ( !$image['image'] )
					continue;

				// OPTIMIERUNG: Nutze pre-resized URLs statt einzelner Aufrufe
				$image_src = $image['image'];
				if ( $should_resize && isset($resized_urls[$resized_index]) ) {
					$image_src = $resized_urls[$resized_index];
					$resized_index++;
				}

				$output = array(
					'image' => array(
						'src' => $image_src,
						'alt' => padma_fix_data_type(padma_get('image-alt', $image)),
						'title' => padma_fix_data_type(padma_get('image-title', $image)),
						'caption' => padma_fix_data_type(padma_get('image-description', $image))
					),

					'hyperlink' => array(
						'href' => padma_fix_data_type(padma_get('image-hyperlink', $image)),
						'target' => padma_fix_data_type(padma_get('image-open-link-in-new-window', $image, false)) ? ' target="_blank"' : null
					)
				);

				echo '<div class="swiper-slide">';

					/* Open hyperlink if user added one for image */
					if ( $output['hyperlink']['href'] )
						echo '<a href="' . $output['hyperlink']['href'] . '"' . $output['hyperlink']['target'] . '>';

					/* Don't forget to display the ACTUAL IMAGE */
					echo '<img src="' . $output['image']['src'] . '" alt="' . $output['image']['alt'] . '" title="' . $output['image']['title'] . '" loading="lazy" />';

					/* Closing tag for hyperlink */
					if ( $output['hyperlink']['href'] )
						echo '</a>';

					/* Caption */
					if ( !empty($output['image']['caption']) )
						echo '<p class="swiper-caption">' . $output['image']['caption'] . '</p>';

				echo '</div>';

			}

			echo '</div>'; // .swiper-wrapper

			echo '<div class="swiper-button-prev"></div>';
			echo '<div class="swiper-button-next"></div>';
			echo '<div class="swiper-pagination"></div>';

		echo '</div>';

	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'slider-container',
			'name' => __('Slider Container','padma'),
			'description' => __('Contains Slides, Navigation, Pagination','padma'),
			'selector' => '.swiper',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'advanced', 'transition', 'outlines')
		));

		$this->register_block_element(array(
			'id' => 'slider-wrapper',
			'name' => __('Slider Wrapper','padma'),
			'description' => __('Contains All Slides','padma'),
			'selector' => '.swiper-wrapper',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'overflow', 'advanced', 'transition', 'outlines')
		));

		$this->register_block_element(array(
			'id' => 'slider-slide',
			'name' => __('Slider Slide','padma'),
			'description' => __('Individual Slide','padma'),
			'selector' => '.swiper-slide',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'advanced', 'transition', 'outlines')
		));

		$this->register_block_element(array(
			'id' => 'slider-caption',
			'name' => __('Slider Caption','padma'),
			'selector' => '.swiper-caption',
			'properties' => array('background', 'padding', 'fonts')
		));

		$this->register_block_element(array(
			'id' => 'slider-direction-nav-buttons',
			'name' => __('Slider Direction Nav Buttons','padma'),
			'description' => __('Prev/Next Navigation Buttons','padma'),
			'selector' => '.swiper-button-prev, .swiper-button-next',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-direction-nav-next',
			'name' => __('Slider Direction Next','padma'),
			'selector' => '.swiper-button-next',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-direction-nav-prev',
			'name' => __('Slider Direction Prev','padma'),
			'selector' => '.swiper-button-prev',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-pagination',
			'name' => __('Slider Pagination','padma'),
			'description' => __('Pagination Dots','padma'),
			'selector' => '.swiper-pagination',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-pagination-bullet',
			'name' => __('Slider Pagination Bullet','padma'),
			'selector' => '.swiper-pagination-bullet',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow'),
			'states' => array(
				'Hover' => '.swiper-pagination-bullet:hover', 
				'Active' => '.swiper-pagination-bullet.swiper-pagination-bullet-active'
			)
		));

	}


}


class PadmaSliderBlockOptions extends PadmaBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'slider-images' => __('Slider Images','padma'),
			'animation' => __('Animation','padma'),
			'ui' => __('User Interface','padma'),
			'advanced' => __('Advanced','padma')
		);

		$this->inputs = array(
			'slider-images' => array(
				'images' => array(
					'type' => 'repeater',
					'name' => 'images',
					'label' => __('Images','padma'),
					'tooltip' => __('Upload the images that you would like to add to the image rotator here.  You can even drag and drop the images to change the order.','padma'),
					'inputs' => array(
						array(
							'type' => 'image',
							'name' => 'image',
							'label' => __('Image','padma'),
							'default' => null
						),

						array(
							'type' => 'text',
							'name' => 'image-hyperlink',
							'label' => __('Hyperlink','padma'),
							'default' => null
						),

						array(
							'type' => 'checkbox',
							'name' => 'image-open-link-in-new-window',
							'label' => __('Open Link in New Window','padma'),
							'default' => false
						),

						array(
							'type' => 'text',
							'name' => 'image-description',
							'label' => __('Caption','padma'),
							'placeholder' => __('Describe the Image','padma'),
							'tooltip' => __('This will be displayed underneath the image.','padma')
						),

						array(
							'type' => 'text',
							'name' => 'image-title',
							'label' => __('"title" Attribute','padma'),
							'tooltip' => __('This will be used as the "title" attribute for the image.  The title attribute is beneficial for SEO (Search Engine Optimization) and will allow your visitors to move their mouse over the image and read about it.','padma')
						),

						array(
							'type' => 'text',
							'name' => 'image-alt',
							'label' => __('"alt" Attribute','padma'),
							'tooltip' => __('This will be used as the "alt" attribute for the image.  The alt attribute is <em>hugely</em> beneficial for SEO (Search Engine Optimization) and for general accessibility.','padma')
						)
					),
					'sortable' => true,
					'limit' => false
				),

				'randomize-order' => array(
					'type' => 'checkbox',
					'name' => 'randomize-order',
					'label' => __('Randomize Image Order','padma'),
					'default' => false
				),

				'image-sizing-header' => array(
					'type' => 'heading',
					'name' => 'image-sizing-header',
					'label' => __('Image Sizing','padma')
				),

					'crop-resize-images' => array(
						'type' => 'checkbox',
						'name' => 'crop-resize-images',
						'label' => __('Crop and Resize Images','padma'),
						'default' => true,
						'tooltip' => __('The Slider block has the ability to automatically resize and crop images to fit in the Slider if the images are too big.  This will improve loading times and make the image fit better in the Slider.<br /><br />If you do not want the Slider block to do this, uncheck this option and the Slider block will insert your original uploaded images into the slider.  <strong>Please note:</strong> Even with this unchecked the images will still be resized with CSS.','padma')
					),

				'content-types-heading' => array(
					'type' => 'heading',
					'name' => 'content-types-heading',
					'label' => __('Other Content Types','padma'),
				),

					'content-types-text' => array(
						'type' => 'notice',
						'name' => 'content-types-text',
						'notice' => __('This Slider block is only capable of displaying images.  If you wish to insert more content such as text, videos, etc., we recommend <a href="http://padmaunlimited.com/go/slidedeck-lite" target="_blank">SlideDeck</a> and <a href="http://padmaunlimited.com/extend/addon/sliderplus/" target="_blank">SliderPlus</a>.','padma')
					)
			),

			'animation' => array(
				'animation' => array(
					'type' => 'select',
					'name' => 'animation',
					'label' => __('Animation','padma'),
					'default' => 'slide-horizontal',
					'options' => array(
						'slide-horizontal' => __('Slide Horizontal','padma'),
						'slide-vertical' => __('Slide Vertical','padma'),
						'fade' => 'Fade'
					)
				),

				'animation-speed' => array(
					'type' => 'slider',
					'name' => 'animation-speed',
					'label' => __('Animation Speed','padma'),
					'default' => 500,
					'slider-min' => 50,
					'slider-max' => 5000,
					'slider-interval' => 10,
					'tooltip' => __('Adjust this to change how long the animation lasts when fading between images.','padma'),
					'unit' => 'ms'
				),

				'slideshow' => array(
					'type' => 'checkbox',
					'name' => 'slideshow',
					'label' => __('Automatic Slide Advancement','padma'),
					'default' => true,
					'tooltip' => __('Act as a slideshow and automatically move to the next slide.','padma')
				),

				'animation-timeout' => array(
					'type' => 'slider',
					'name' => 'animation-timeout',
					'label' => __('Time Between Slides','padma'),
					'default' => 6,
					'slider-min' => 1,
					'slider-max' => 20,
					'slider-interval' => 1,
					'tooltip' => __('This is the amount of time each image will stay visible.','padma'),
					'unit' => 's'
				)
			),

			'ui' => array(
				'show-pager-nav' => array(
					'type' => 'checkbox',
					'name' => 'show-pager-nav',
					'label' => __('Show Pager Navigation','padma'),
					'default' => true,
					'tooltip' => __('Show dots below slider to choose specific slides.','padma')
				),

				'show-direction-nav' => array(
					'type' => 'checkbox',
					'name' => 'show-direction-nav',
					'label' => __('Show Next/Previous Arrows','padma'),
					'default' => true,
					'tooltip' => __('Show arrows to advance to the next/previous slides.','padma')
				)
			),

			'advanced' => array(
				'content-types-text' => array(
					'type' => 'notice',
					'name' => 'content-types-text',
					'notice' => __('This Slider block is only capable of displaying images.  If you wish to insert more content such as text, videos, etc., we recommend <a href="http://padmaunlimited.com/go/slidedeck-lite" target="_blank">SlideDeck</a> and <a href="http://padmaunlimited.com/extend/addon/sliderplus/" target="_blank">SliderPlus</a>.','padma')
				)
			)
		);
	}

}