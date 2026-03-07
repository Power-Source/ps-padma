<?php
/**
 * Block options class
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

/**
 * Options class for PS Maps block
 */
class PadmaVisualElementsBlockGmapOptions extends \PadmaBlockOptionsAPI {

	/**
	 * Block tabs for options.
	 *
	 * @var array $tabs
	 */
	public $tabs;

	/**
	 * Block sets for options.
	 *
	 * @var array $sets
	 */
	public $sets;

	/**
	 * Inputs for each tab.
	 *
	 * @var array $inputs
	 */
	public $inputs;

	/**
	 * Init block options
	 */
	public function __construct() {

		$this->tabs = array(
			'general'   => __( 'Map Auswahl', 'padma-advanced' ),
			'overrides' => __( 'Anpassungen', 'padma-advanced' ),
		);

		$this->sets = array();

		// Hole alle Maps aus PS Maps für Dropdown
		$maps_options = $this->get_available_maps();

		$this->inputs = array(
			'general' => array(
				'map_id' => array(
					'name'    => 'map_id',
					'label'   => __( 'Map auswählen', 'padma-advanced' ),
					'type'    => 'select',
					'options' => $maps_options,
					'default' => '',
					'tooltip' => __( 'Wähle eine existierende Map aus dem PS Maps Plugin. Erstelle neue Maps unter Einstellungen → PS Maps.', 'padma-advanced' ),
				),

				'help_text' => array(
					'name'    => 'help_text',
					'type'    => 'notice',
					'notice'  => __( '<strong>Hinweis:</strong> Dieses Block nutzt das PS Maps Plugin. Erstelle und verwalte deine Maps im Backend unter <em>Einstellungen → PS Maps</em>. Dort kannst du Marker setzen, Routen planen, KML-Overlays hinzufügen und vieles mehr.', 'padma-advanced' ),
				),
			),

			'overrides' => array(
				'width' => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite (optional)', 'padma-advanced' ),
					'default' => '',
					'tooltip' => __( 'Überschreibt die Standard-Breite der Map. Leer lassen für Standard-Einstellung.', 'padma-advanced' ),
				),

				'height' => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe (optional)', 'padma-advanced' ),
					'default' => '',
					'tooltip' => __( 'Überschreibt die Standard-Höhe der Map. Leer lassen für Standard-Einstellung.', 'padma-advanced' ),
				),

				'zoom' => array(
					'name'    => 'zoom',
					'type'    => 'integer',
					'label'   => __( 'Zoom (optional)', 'padma-advanced' ),
					'default' => 0,
					'tooltip' => __( 'Überschreibt die Zoom-Stufe. Werte von 1 (Welt) bis 21 (Gebäude). 0 = Standard.', 'padma-advanced' ),
				),

				'map_type' => array(
					'name'    => 'map_type',
					'type'    => 'select',
					'label'   => __( 'Kartentyp (optional)', 'padma-advanced' ),
					'default' => '',
					'options' => array(
						''          => __( '-- Standard --', 'padma-advanced' ),
						'ROADMAP'   => __( 'Straßenkarte', 'padma-advanced' ),
						'SATELLITE' => __( 'Satellit', 'padma-advanced' ),
						'HYBRID'    => __( 'Hybrid', 'padma-advanced' ),
						'TERRAIN'   => __( 'Gelände', 'padma-advanced' ),
					),
					'tooltip' => __( 'Überschreibt den Kartentyp. Leer lassen für Standard-Einstellung.', 'padma-advanced' ),
				),
			),
		);
	}

	/**
	 * Hole alle verfügbaren Maps aus PS Maps Plugin
	 *
	 * @return array Map-Optionen für Dropdown
	 */
	private function get_available_maps() {
		$options = array(
			'' => __( '-- Bitte Map wählen --', 'padma-advanced' ),
		);

		// Prüfe ob PS Maps Plugin aktiv ist
		if ( ! class_exists( 'AgmMapModel' ) ) {
			$options[''] = __( 'PS Maps Plugin nicht aktiv', 'padma-advanced' );
			return $options;
		}

		try {
			$model = new \AgmMapModel();
			$maps = $model->get_all_maps();

			if ( is_array( $maps ) && ! empty( $maps ) ) {
				foreach ( $maps as $map ) {
					if ( isset( $map['id'] ) && isset( $map['defaults'] ) && isset( $map['defaults']['name'] ) ) {
						$map_name = $map['defaults']['name'];
						$map_id = $map['id'];
						
						// Zeige auch Marker-Anzahl an
						$marker_count = isset( $map['markers'] ) ? count( $map['markers'] ) : 0;
						$label = sprintf( '%s (ID: %d, %d Marker)', $map_name, $map_id, $marker_count );
						
						$options[ $map_id ] = $label;
					}
				}
			} else {
				$options[''] = __( 'Keine Maps vorhanden - erstelle eine unter Einstellungen → PS Maps', 'padma-advanced' );
			}
		} catch ( \Exception $e ) {
			$options[''] = __( 'Fehler beim Laden der Maps', 'padma-advanced' );
		}

		return $options;
	}
}
