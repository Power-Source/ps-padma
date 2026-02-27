<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockLottieFiles extends \PadmaBlockAPI {
	
	public $id 				= 'lottiefiles';
	public $name 			= 'Lottie Files';
	public $options_class 	= 'Padma_Advanced\\PadmaVisualElementsBlockLottieFilesOptions';
	public $description 		= 'Display Lottie animations from https://lottiefiles.com/featured';
	public $categories 		= array('animations','media');
	
	function __construct(){

	}

	
	public function init() {
		add_filter('upload_mimes', array(__CLASS__, 'add_json_upload_mime'));
		add_filter('wp_check_filetype_and_ext', array(__CLASS__, 'allow_json_filetype'), 10, 4);
		
		// Filter out lottie.min.js enqueue in VE context
		if ( self::is_in_ve_context() ) {
			// Completely block enqueue of lottie script in VE
			add_filter('script_loader_src', array(__CLASS__, 'filter_lottie_script'), 10, 2);
		}
	}
	
	/**
	 * Robust check if we're in VE context
	 */
	public static function is_in_ve_context() {
		// Check if we're in iframe
		if ( padma_get('ve-iframe') && class_exists('\\PadmaCapabilities') && \PadmaCapabilities::can_user_visually_edit() ) {
			return true;
		}
		// Check global flag
		if ( defined('PADMA_VISUAL_EDITOR_CONTEXT') && PADMA_VISUAL_EDITOR_CONTEXT ) {
			return true;
		}
		// Check if this is admin page (VE is admin-based)
		if ( is_admin() && class_exists('\\PadmaRoute') && \PadmaRoute::is_visual_editor() ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Filter to remove lottie.min.js from being enqueued in VE
	 */
	public static function filter_lottie_script( $src, $handle ) {
		if ( $handle === 'padma-lottiefiles' ) {
			error_log('[ DEBUG LOTTIE FILTER] Blocking lottie.min.js enqueue in VE context');
			return false;
		}
		return $src;
	}
	
	public function setup_elements() {
	}


	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		$css = 'lottie-player{width: 100%;}';

		return $css;
		
	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		/*

		debug($block);
		
		$origin = 'path';
		$loop = (isset($block['settings']['loop'])) ? $block['settings']['loop'] : 'true';

		if( isset($block['settings']['source']) && $block['settings']['source'] == 'url' ){
			
			$source = trim($block['settings']['animation-url']);		
		
		}elseif ( isset($block['settings']['source']) && $block['settings']['source'] == 'file' ) {

			$source = trim($block['settings']['animation-file']);
			
		}else{
			$source =  padma_url() . '/library/blocks-advanced/lottiefiles/json/156-star-blast.json';
		}


		$container = 'svgContainer-' . $block['id'];

		$js = "jQuery(document).ready(function() {";		
		$js .= "var svgContainer = document.getElementById('".$container."');";
		$js .= "var animItem = bodymovin.loadAnimation({";
		$js .= "	wrapper: svgContainer,";
		$js .= "	animType: 'svg',";
		$js .= "	loop: ".$loop.",";
		$js .= "	" . $origin . ": '". $source ."'";
		$js .= "});";
		$js .= "});";

		return $js;*/
		
	}
	
	public function content($block) {

		$origin = 'path';
		
		$loop = ( isset($block['settings']['loop']) ) ? $block['settings']['loop'] : 'loop';
		if( $loop == 'yes' ){
			$loop = 'loop';
		}else{
			$loop = ' ';
		}
		
		$controls = ( isset($block['settings']['controls']) ) ? $block['settings']['controls'] : '';
		$autoplay = ( isset($block['settings']['autoplay']) ) ? $block['settings']['autoplay'] : 'autoplay';
		

		if( isset($block['settings']['source']) && $block['settings']['source'] == 'url' ){
			
			$source = trim($block['settings']['animation-url']);		
		
		}elseif ( isset($block['settings']['source']) && $block['settings']['source'] == 'file' ) {

			$source = trim($block['settings']['animation-file']);
			
		}else{
			$source =  padma_url() . '/library/blocks-advanced/lottiefiles/json/156-star-blast.json';
		}

		$html = '<lottie-player ';
		$html .= 'src="' . $source . '" ';
		$html .= $loop . ' ';
		$html .= $controls . ' ';
		$html .= $autoplay . ' ';
        //style="width: 400px; --lottie-player-seeker-track-color: #ff3300; --lottie-player-seeker-thumb-color: #ffcc00;"                
      	$html .= '></lottie-player>';
      	echo $html;
	}
	
	public static function enqueue_action($block_id, $block = false) {

		$in_visual_editor = ( function_exists('padma_get') && ( padma_get('ve-iframe') || padma_get('visual-editor-open') ) )
			|| ( class_exists('\\PadmaRoute') && ( \PadmaRoute::is_visual_editor() || \PadmaRoute::is_visual_editor_iframe() ) );

		error_log('[LOTTIE DEBUG] enqueue_action called. in_visual_editor=' . var_export($in_visual_editor, true) . ' | ve-iframe=' . var_export(padma_get('ve-iframe'), true) . ' | visual-editor=' . var_export(padma_get('visual-editor-open'), true));

		if ( $in_visual_editor ) {
			error_log('[LOTTIE DEBUG] Skipping enqueue - detected VE context');
			return;
		}
		
		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);
				
		$path = padma_url() . '/library/blocks-advanced/lottiefiles/';

		error_log('[LOTTIE DEBUG] Enqueueing lottie.min.js from: ' . $path . 'js/lottie.min.js');

		/* JS */		
		wp_enqueue_script( 'padma-lottiefiles', $path . 'js/lottie.min.js', array(), PADMA_VERSION, true );
	}

	public static function add_json_upload_mime($mimes) {
		$mimes['json'] = 'application/json';
		return $mimes;
	}

	public static function allow_json_filetype($types, $file, $filename, $mimes) {
		if ( false !== strpos($filename, '.json') ) {
			$types['ext'] = 'json';
			$types['type'] = 'text/json';
		}

		return $types;
	}
}

class PadmaVisualElementsBlockLottieFilesOptions extends \PadmaBlockOptionsAPI {
	
	public $tabs;
	public $sets;
	public $inputs;

	function __construct(){
		
		$this->tabs = array(
			'general' 			=> 'General',
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'source' => array(
					'type' => 'select',
					'name' => 'source',
					'label' => 'Animation Source',
					'default' => 'url',
					'options' => array(
						'' => '',
						'file' => 'File',
						'url' => 'URL',
					),
					'toggle'    => array(
						'file' => array(
							'hide' => array(
								'#input-animation-url'
							),
							'show' => array(
								'#input-animation-file'
							)
						),
						'url' => array(
							'hide' => array(
								'#input-animation-file'
							),
							'show' => array(
								'#input-animation-url'
							)
						),
					)					
				),

				'animation-file' => array(
					'type' => 'json',
					'name' => 'animation-file',
					'label' => 'Animation JSON File',
					'default' => '',					
					'tooltip' => 'Upload the json file',
					'button-label' => __('Select File','padma'),					
				),

				'animation-url' => array(
					'type' => 'text',
					'name' => 'animation-url',
					'label' => 'Animation URL',
					'default' => '',					
					'tooltip' => 'json file URL',
				),

				'loop' => array(
					'type' => 'select',
					'name' => 'loop',
					'label' => 'Loop',
					'default' => 'true',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No',
					),				
				),

				'controls' => array(
					'type' => 'select',
					'name' => 'controls',
					'label' => 'Controls',
					'default' => 'false',
					'options' => array(
						'true' => 'True',
						'false' => 'False',
					),				
				),

				'autoplay' => array(
					'type' => 'select',
					'name' => 'autoplay',
					'label' => 'Autoplay',
					'default' => 'true',
					'options' => array(
						'true' => 'True',
						'false' => 'False',
					),				
				),
			),
		);
	}


	public function modify_arguments($args = false) {
		$this->tab_notices['general'] = sprintf( __('Get animations from  <a href="%s" target="_blank">lottiefiles.com</a>','padma'), 'https://lottiefiles.com/recent' );
	}
	
}