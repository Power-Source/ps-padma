<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockSociable extends \PadmaBlockAPI {
	
	public $id 				= 'sociable';	
	public $name 			= 'Sociable';		
	public $options_class 	= 'Padma_Advanced\\PadmaVisualElementsBlockSociableOptions';	
	public $fixed_height 	= true;	
	public $html_tag 		= 'section';
	public $description 	= 'Display a set of Sociable icons';
	public $categories 		= array('social');

	protected $show_content_in_grid = false;

	
	public function init() {

		add_filter( 'upload_mimes', array($this, 'add_uploader_svg_mime' ));

	}
	
	public function setup_elements() {

		$this->register_block_element(array(
			'id' => 'icons-wrapper',
			'name' => 'Icons Container',
			'selector' => 'ul.sociable-icons '
		));

		$this->register_block_element(array(
			'id' => 'icon',
			'name' => 'Icon Container ',
			'selector' => 'li'
		));

		$this->register_block_element(array(
			'id' => 'icon-first',
			'name' => 'First Icon',
			'selector' => 'li:first-child'
		));

		$this->register_block_element(array(
			'id' => 'icon-last',
			'name' => 'Last Icon',
			'selector' => 'li:last-child'
		));
		
		$this->register_block_element(array(
			'id' => 'image',
			'name' => 'Image',
			'selector' => 'img'
		));

		$this->register_block_element(array(
			'id' => 'image-link',
			'name' => 'Image Link',
			'selector' => 'img a',
			'states' => array(
				'Hover' => 'img a:hover',
				'Clicked' => 'img a:active'
			)
		));

		$this->register_block_element(array(
			'id' => 'icon paragraph',
			'name' => 'Icon paragraph',
			'selector' => 'li p'
		));

		$this->register_block_element(array(
			'id' => 'icon link',
			'name' => 'Icon link',
			'selector' => 'li a'
		));

		$this->register_block_element(array(
			'id' => 'icon span',
			'name' => 'Icon span',
			'selector' => 'li span'
		));

		$this->register_block_element(array(
			'id' => 'icon h1',
			'name' => 'Icon h1',
			'selector' => 'li h1'
		));

		$this->register_block_element(array(
			'id' => 'icon h2',
			'name' => 'Icon h2',
			'selector' => 'li h2'
		));

		$this->register_block_element(array(
			'id' => 'icon h3',
			'name' => 'Icon h3',
			'selector' => 'li h3'
		));

		$this->register_block_element(array(
			'id' => 'icon h4',
			'name' => 'Icon h4',
			'selector' => 'li h4'
		));

		$this->register_block_element(array(
			'id' => 'icon h5',
			'name' => 'Icon h5',
			'selector' => 'li h5'
		));

		$this->register_block_element(array(
			'id' => 'icon h6',
			'name' => 'Icon h6',
			'selector' => 'li h6'
		));
		
	}


	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		$position 		= parent::get_setting($block, 'icons-position', '');
		$orientation 	= parent::get_setting($block, 'orientation', 'vertical');

		$css = '';

		/* Stack vertical add only bottom margin */
	  	if ( $orientation === 'vertical' ) {

	  		$css .= '
	  			#block-' . $block_id . ' ul.sociable-icons li { 
	  				margin-bottom: '. parent::get_setting($block, 'vertical-spacing', '10') .'px
	  			}

	  			#block-' . $block_id . ' ul.sociable-icons li:last-child { 
	  				margin-bottom: 0;
	  			}
	  		';


	  	}

		/* Float horizontal images and add right margin on all but last*/
		if ( $orientation === 'horizontal' ) {

	  		$css .= '
	  			#block-' . $block_id . ' ul.sociable-icons li {
	  			    display: inline-block;
	  				margin-right: '. parent::get_setting($block, 'horizontal-spacing', '10') .'px
	  			}

	  			#block-' . $block_id . ' ul.sociable-icons li:last-child { 
	  				margin-right: 0;
	  			}
	  		';

	  	}


		if ( $position ) {

	    $position_fragments = explode('_', $position);

           $horizontal_position = $position_fragments[1];
           $vertical_position = str_replace('center', 'middle', $position_fragments[0]);

	    $css .= '
	        #block-' . $block_id . ' div.sociable-icons-container {
	            display: table;
	            width: 100%;
	            height: 100%;
	        }

               #block-' . $block_id . ' ul.sociable-icons {
                   display: table-cell;
                   text-align: ' . $horizontal_position . ';
                   vertical-align: ' . $vertical_position . ';
               }
           ';

       }

		return $css;
		
	}
	
	public function content($block) {

		$icon_set 	= \PadmaBlockAPI::get_setting($block, 'icon-set', 'peel-icons');
		$use_svg 	= parent::get_setting($block, 'use-svg', false);
		$svg_width 	= ($use_svg && parent::get_setting($block, 'svg-width')) ? 'width="' . parent::get_setting($block, 'svg-width') . '"' : '';

		if ($icon_set == 'custom') {
			$icons = parent::get_setting($block, 'icons' , array());
		} else {
			$icons = parent::get_setting($block, 'icons'.$icon_set , array());
		}

		$block_width 	= \PadmaBlocksData::get_block_width($block);
		$block_height 	= \PadmaBlocksData::get_block_height($block);			
		$has_icons 		= false;

		foreach ( $icons as $icon ) {

			if ( padma_get('image', $icon) || padma_get('network', $icon) ) {
				$has_icons = true;
				break;
			}

		}

		if ( !$has_icons) {

			echo '<div class="alert alert-yellow"><p>There are no icons to display.</p></div>';
			
			return;

		}

		echo '<div class="sociable-icons-container">';
		echo '<ul class="sociable-icons">';

			$i = 0;
		  	foreach ( $icons as $icon ) {

		  		if ( !padma_get('image', $icon) && !padma_get('network', $icon) )
		  			continue;

		  		if ($icon_set == 'custom') {
		  			$img_url = $icon['image'];
		  		} else {
		  			$img_url = padma_url() . '/library/blocks-advanced/sociable/icons/' . $icon_set . '/' . padma_fix_data_type(padma_get('network', $icon));
		  		}

		  		if(padma_get('icon-size', $icon, false) != ''){
		  			$size 		= padma_get('icon-size', $icon, false);
		  			$img_url 	.= '-' . $size . '.png';
		  		}else{
		  			if ($icon_set != 'custom') {
						$img_url	.= '-64x64.png';
					}
		  		}

		  		$i++;
		  		$output = array(
		  			'image' => array(
		  				'src' => $img_url,
		  				'alt' => padma_fix_data_type(padma_get('image-alt', $icon, false)) ? ' alt="' . padma_fix_data_type(padma_get('image-alt', $icon, false)) . '"' : null,
		  				'title' => padma_fix_data_type(padma_get('image-title', $icon)) ? ' title="' . padma_fix_data_type(padma_get('image-title', $icon)) . '"' : null,
		  			),

		  			'hyperlink' => array(
		  				'href' => padma_fix_data_type(padma_get('link-url', $icon)),
		  				'alt' => padma_fix_data_type(padma_get('link-alt', $icon, false)) ? ' alt="' . padma_fix_data_type(padma_get('link-alt', $icon, false)) . '"' : null,
		  				'target' => padma_fix_data_type(padma_get('link-target', $icon, false)) ? ' target="_blank"' : null,
		  				'rel' => padma_fix_data_type(padma_get('link-rel', $icon, 'noreferrer')) ? padma_get('link-rel', $icon, 'noreferrer') : 'noreferrer',
		  			)
		  		);

		  			echo '<li>';

		  			if( isset( $icon['before-icon'] ) && !empty( $icon['before-icon'] ) ){
		  				echo trim($icon['before-icon']);
		  			}

		  			/* Open hyperlink if user added one for image */
		  			if ( $output['hyperlink']['href'] ){
		  				echo '<a href="' . $output['hyperlink']['href'] . '"' . $output['hyperlink']['target'] . $output['hyperlink']['alt']. 'rel="'.$output['hyperlink']['rel'] .'" ' . '>';
		  			}
		  			
		  			/* Don't forget to display the ACTUAL IMAGE */
		  			echo '<img src="' . $output['image']['src'] . '"' . $output['image']['alt'] . $output['image']['title'] . ' class="img-' . $i . '" ' . $svg_width . ' />';

		  			/* Closing tag for hyperlink */
		  			if ( $output['hyperlink']['href'] ){
		  				echo '</a>';
		  			}


		  			if( isset( $icon['after-icon'] ) && !empty( $icon['after-icon'] ) ){
		  				echo trim($icon['after-icon']);
		  			}

		  			echo '</li>';
		  		
		  	}
	  
	  	echo '</ul>';
		echo '</div>';
		
	}

	public function add_uploader_svg_mime( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public static function enqueue_action($block_id, $block = false) {
			
			/* CSS */
			//wp_enqueue_style('padma-sociable', plugin_dir_url( __FILE__ ) . 'css/sociable.css');

			/* JS */
			//wp_enqueue_script('padma-sociable', plugin_dir_url( __FILE__ ) . 'js/sociable.js', array('jquery'));

		}
	
}
