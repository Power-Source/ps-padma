<?php
/**
 * ExcerptsPlus Block - Erweiterte Beitrags-Anzeige mit Filtern und Layouts
 *
 * @package Padma_Advanced
 * @subpackage ExcerptsPlus
 * @version 1.0.0
 */

namespace Padma_Advanced;

if ( ! defined( 'ABSPATH' ) ) exit;

// Module laden
require_once dirname(__FILE__) . '/classes/Helpers.php';
require_once dirname(__FILE__) . '/classes/ImageProcessor.php';
require_once dirname(__FILE__) . '/classes/MetaHandler.php';
require_once dirname(__FILE__) . '/classes/QueryBuilder.php';

// Legacy-Dateien laden (temporär - bis Renderer-Modul fertig)
require_once dirname(__FILE__) . '/excerpts-plus-display-legacy.php';
require_once dirname(__FILE__) . '/excerpts-plus-functions-legacy.php';

/**
 * Legacy Compatibility Layer
 * 
 * Globale Klasse für Backward-Kompatibilität mit altem Code
 */
class EPFunctions {
	
	/**
	 * Prüft ob Post scheduled ist
	 */
	public static function is_scheduled( $post ) {
		return ExcerptsPlus\Helpers::is_scheduled( $post );
	}
	
	/**
	 * Holt Custom Field Wert
	 */
	public static function get_custom_field( $fieldname, $post_id ) {
		return ExcerptsPlus\Helpers::get_custom_field( $fieldname, $post_id );
	}
	
	/**
	 * Baut Custom Fields HTML
	 */
	public static function build_custom_fields( $block_id, $post_id, $fields_array ) {
		return ExcerptsPlus\MetaHandler::build_custom_fields( $block_id, $post_id, $fields_array );
	}
	
	/**
	 * Löscht Post-Image-Cache
	 */
	public static function clear_post_image_cache( $post_id ) {
		ExcerptsPlus\ImageProcessor::clear_post_cache( $post_id );
	}
	
	/**
	 * Löscht kompletten Image-Cache
	 */
	public static function clear_image_cache() {
		ExcerptsPlus\ImageProcessor::clear_cache();
	}
}

// Legacy Wrapper-Funktionen für globale Nutzung
function ep_is_scheduled( $post ) {
	return EPFunctions::is_scheduled( $post );
}

function ep_get_custom_field( $fieldname, $post_id ) {
	return EPFunctions::get_custom_field( $fieldname, $post_id );
}

function ep_build_custom_fields( $block_id, $post_id, $fields_array ) {
	return EPFunctions::build_custom_fields( $block_id, $post_id, $fields_array );
}

function ep_clear_post_image_cache( $post_id ) {
	EPFunctions::clear_post_image_cache( $post_id );
}

function ep_clear_image_cache() {
	EPFunctions::clear_image_cache();
}

/**
 * ExcerptsPlus Block Klasse
 *
 * Erweiterte Post-Anzeige mit Filtern, Layouts, Sliders und mehr
 */
class PadmaVisualElementsBlockExcerptsPlus extends \PadmaBlockAPI {

	public $id = 'excerpts-plus';
	public $name = 'ExcerptsPlus';
	public $options_class = 'PadmaVisualElementsBlockExcerptsPlusOptions';
	public $description = 'Erweiterte Beitrags-Anzeige mit umfangreichen Filter- und Layout-Optionen, Slider-Unterstützung und Custom Fields.';
	public $categories = array('dynamic-content', 'content');
	protected $show_content_in_grid = true;

	/**
	 * Initialisierung
	 */
	public function __construct() {
		// Image Processor initialisieren
		ExcerptsPlus\ImageProcessor::init();
	}

	/**
	 * Enqueue Scripts & Styles
	 */
	public static function enqueue_action( $block_id, $block ) {
		// Delegiere an Legacy-Klasse (Original-Funktionalität erhalten)
		if ( class_exists( '\PadmaExcerptsPBlock' ) ) {
			\PadmaExcerptsPBlock::enqueue_action( $block_id, $block );
		}
	}

	/**
	 * Init Action - Registriert Hooks
	 */
	public static function init_action() {
		// Delegiere an Legacy-Klasse
		if ( class_exists( '\PadmaExcerptsPBlock' ) ) {
			\PadmaExcerptsPBlock::init_action();
		}
	}

	/**
	 * JavaScript Content (Dialog, etc.)
	 */
	public static function js_content() {
		// Delegiere an Legacy-Klasse
		if ( class_exists( '\PadmaExcerptsPBlock' ) ) {
			\PadmaExcerptsPBlock::js_content();
		}
	}

	/**
	 * Setup Elements für Styling
	 */
	public function setup_elements() {
		// Delegiere an Legacy-Klasse
		if ( class_exists( '\PadmaExcerptsPBlock' ) ) {
			$legacy = new \PadmaExcerptsPBlock();
			$elements = $legacy->setup_elements();
			$this->register_block_element( $elements );
		}
	}

	/**
	 * Content - Main Rendering
	 */
	public function content( $block ) {
		// Delegiere an Legacy-Klasse (Original-Rendering erhalten)
		if ( class_exists( '\PadmaExcerptsPBlock' ) ) {
			$legacy = new \PadmaExcerptsPBlock();
			return $legacy->content( $block );
		}
		return '';
	}

}

/**
 * Shortcode Handler: [excerptsplus id=123]
 */
function eplus_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => null,
		'conditions' => null
	), $atts ) );

	if ( ! $id ) {
		return '';
	}

	$block = \PadmaBlocksData::get_block( $id );
	
	if ( ! $block ) {
		return '';
	}

	// Conditions überschreiben wenn angegeben
	if ( $conditions ) {
		$conditions_array = json_decode( stripslashes( $conditions ), true );
		if ( is_array( $conditions_array ) ) {
			foreach ( $conditions_array as $key => $value ) {
				$block['settings'][$key] = $value;
			}
		}
	}

	// Render Block
	ob_start();
	\PadmaBlocks::display_block( $block );
	return ob_get_clean();
}
add_shortcode( 'excerptsplus', __NAMESPACE__ . '\\eplus_shortcode' );

/**
 * Quick Read JavaScript Code
 */
function ep_quickread_code() {
	if ( class_exists( '\PadmaExcerptsPBlock' ) && method_exists( '\PadmaExcerptsPBlock', 'ep_quickread_code' ) ) {
		\PadmaExcerptsPBlock::ep_quickread_code();
	}
}
add_action( 'wp_footer', __NAMESPACE__ . '\\ep_quickread_code', 99 );

/**
 * Extra Visual Editor CSS
 */
function ep_extra_ve_css() {
	if ( class_exists( '\PadmaExcerptsPBlock' ) && method_exists( '\PadmaExcerptsPBlock', 'ep_extra_ve_css' ) ) {
		\PadmaExcerptsPBlock::ep_extra_ve_css();
	}
}
add_action( 'padma_visual_editor_head', __NAMESPACE__ . '\\ep_extra_ve_css' );

/**
 * Hook System für Custom Query Filter
 */
function ep_add_hook( $block_id ) {
	$hook_name = 'ep_block_' . $block_id;
	if ( has_action( $hook_name ) ) {
		do_action( $hook_name );
	}
}

/**
 * Cache Clearing bei Post-Updates
 */
add_action( 'save_post', function( $post_id ) {
	ExcerptsPlus\ImageProcessor::clear_post_cache( $post_id );
}, 10, 1 );

add_action( 'delete_post', function( $post_id ) {
	ExcerptsPlus\ImageProcessor::clear_post_cache( $post_id );
}, 10, 1 );

add_action( 'switch_theme', function() {
	ExcerptsPlus\ImageProcessor::clear_cache();
});
