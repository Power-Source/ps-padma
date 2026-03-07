<?php

/**
 * PS Maps Block
 *
 * @link       https://github.com/cp-psource/ps-maps
 * @since      1.0.8
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/public
 * @author     PSOURCE <support@psource.io>
 */

namespace Padma_Advanced;

/**
 * PS Maps Block - Integration mit PS Maps Plugin
 */
class PadmaVisualElementsBlockGmap extends \PadmaBlockAPI {

	/**
	 * Block id
	 *
	 * @var string $id
	 */
	public $id;

	/**
	 * Block Name
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 * Options Class
	 *
	 * @var string $options_class
	 */
	public $options_class;

	/**
	 * Block Description
	 *
	 * @var string $description
	 */
	public $description;

	/**
	 * Block Categories
	 *
	 * @var array $categories
	 */
	public $categories;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id            = 'visual-elements-gmap';
		$this->name          = __( 'PS Maps', 'padma-advanced' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockGmapOptions';
		$this->description   = __( 'Zeige Google Maps mit vollständiger Integration in PS Maps Plugin', 'padma-advanced' );
		$this->categories    = array( 'media' );
	}

	/**
	 * Init - Prüft ob PS Maps Plugin aktiv ist
	 */
	public function init() {
		return class_exists( 'AgmMapModel' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'ps-maps',
				'name'     => 'PS Maps',
				'selector' => 'div.agm_google_maps',
			)
		);
	}

	/**
	 * Padma Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		if ( ! class_exists( 'AgmMapModel' ) ) {
			echo '<div class="alert alert-red"><p>' . __( 'PS Maps Plugin ist nicht aktiv. Bitte aktiviere das PS Maps Plugin um Karten anzuzeigen.', 'padma-advanced' ) . '</p></div>';
			return;
		}

		$map_id = parent::get_setting( $block, 'map_id' );
		
		// Wenn keine Map ausgewählt, zeige Hinweis
		if ( ! $map_id || $map_id === '' ) {
			echo '<div class="alert alert-yellow"><p>' . __( 'Bitte wähle eine Map aus oder erstelle eine neue Map im PS Maps Backend.', 'padma-advanced' ) . '</p></div>';
			return;
		}

		// Hole Override-Einstellungen
		$width  = parent::get_setting( $block, 'width' );
		$height = parent::get_setting( $block, 'height' );
		$zoom   = parent::get_setting( $block, 'zoom' );
		$map_type = parent::get_setting( $block, 'map_type' );

		// Baue Shortcode-Parameter
		$shortcode_atts = array( 'id' => $map_id );
		
		if ( ! empty( $width ) ) {
			$shortcode_atts['width'] = intval( $width );
		}
		
		if ( ! empty( $height ) ) {
			$shortcode_atts['height'] = intval( $height );
		}
		
		if ( ! empty( $zoom ) && $zoom > 0 ) {
			$shortcode_atts['zoom'] = intval( $zoom );
		}
		
		if ( ! empty( $map_type ) ) {
			$shortcode_atts['map_type'] = strtoupper( $map_type );
		}

		// Erstelle Shortcode-String
		$shortcode_parts = array();
		foreach ( $shortcode_atts as $key => $value ) {
			$shortcode_parts[] = $key . '="' . esc_attr( $value ) . '"';
		}
		
		// Bestimme welcher Shortcode zu verwenden ist
		$shortcode_tag = 'agm_map';
		if ( class_exists( 'AgmMapModel' ) ) {
			$config = \AgmMapModel::get_config( 'shortcode_map' );
			if ( $config === 'map' ) {
				$shortcode_tag = 'map';
			}
		}
		
		$shortcode = '[' . $shortcode_tag . ' ' . implode( ' ', $shortcode_parts ) . ']';
		
		echo do_shortcode( $shortcode );
	}

}
