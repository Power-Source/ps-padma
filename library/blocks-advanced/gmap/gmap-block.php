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
		
		// Prüfe ob Quick-Create verwendet wird (nur wenn keine map_id vorhanden)
		$create_address = parent::get_setting( $block, 'create_address' );
		
		if ( empty( $map_id ) && ! empty( $create_address ) ) {
			// Quick-Create: Erstelle neue Map aus Adresse (nur wenn keine existierende Map ausgewählt)
			$created_map_id = $this->create_map_from_address( $block, $create_address );
			
			if ( $created_map_id ) {
				// Verwende die neu erstellte Map
				$map_id = $created_map_id;
			} else {
				// Geocodierung fehlgeschlagen
				echo '<div class="alert alert-red"><p>' . __( 'Die Adresse konnte nicht geocodiert werden. Bitte überprüfe die Adresse und versuche es erneut.', 'padma-advanced' ) . '</p></div>';
				return;
			}
		}
		
		// Wenn keine Map ausgewählt oder erstellt, zeige Hinweis
		if ( ! $map_id || $map_id === '' ) {
			echo '<div class="alert alert-yellow"><p>' . __( 'Bitte wähle eine Map aus oder erstelle eine neue Map im Tab "Neue Map erstellen".', 'padma-advanced' ) . '</p></div>';
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

	/**
	 * Erstellt eine neue Map aus Adresse mit PS Maps autocreate_map
	 *
	 * @param object $block Block-Einstellungen
	 * @param string $address Adresse für die Map
	 * @return int|false Map-ID bei Erfolg, false bei Fehler
	 */
	private function create_map_from_address( $block, $address ) {
		if ( ! class_exists( 'AgmMapModel' ) ) {
			return false;
		}

		$model = new \AgmMapModel();
		
		// Hole optionale Create-Parameter
		$map_name = parent::get_setting( $block, 'create_map_name' );
		$zoom = parent::get_setting( $block, 'create_zoom' );
		$map_type = parent::get_setting( $block, 'create_map_type' );
		
		// Setze Defaults
		if ( empty( $zoom ) ) {
			$zoom = 15; // Standard Zoom für Straßenniveau
		}
		if ( empty( $map_type ) ) {
			$map_type = 'ROADMAP';
		}
		
		// Erstelle Map mit PS Maps autocreate_map
		// Parameter: $post_id, $lat, $lon, $address, $associated_post_id, $args
		$args = array(
			'show_posts' => false,
		);
		
		// autocreate_map wird lat/lon selbst aus address geocodieren
		$map_id = $model->autocreate_map( 
			null,       // post_id - nicht benötigt für standalone maps
			null,       // lat - wird aus address geocodiert
			null,       // lon - wird aus address geocodiert
			$address,   // address
			null,       // associated_post_id
			$args       // zusätzliche args
		);
		
		if ( ! $map_id ) {
			return false; // Geocodierung fehlgeschlagen
		}
		
		// Hole die erstellte Map und aktualisiere sie mit custom Parametern
		$map = $model->get_map( $map_id );
		
		if ( $map && is_array( $map ) ) {
			// Aktualisiere Map-Name wenn angegeben
			if ( ! empty( $map_name ) ) {
				$map['title'] = sanitize_text_field( $map_name );
			}
			
			// Aktualisiere Zoom
			$map['zoom'] = intval( $zoom );
			
			// Aktualisiere Map-Type
			$map['map_type'] = strtoupper( $map_type );
			
			// Speichere aktualisierte Map
			$model->save_map( $map );
		}
		
		return $map_id;
	}

}
