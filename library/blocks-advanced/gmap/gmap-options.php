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
			'general'   => __( 'Map Auswahl', 'padma' ),
			'overrides' => __( 'Anpassungen', 'padma' ),
			'create'    => __( 'Neue Map erstellen', 'padma' ),
		);

		$this->sets = array();

		// Hole alle Maps aus PS Maps für Dropdown
		$maps_options = $this->get_available_maps();

		$this->inputs = array(
			'general' => array(
				'map_id' => array(
					'name'    => 'map_id',
					'label'   => __( 'Map auswählen', 'padma' ),
					'type'    => 'select',
					'options' => $maps_options,
					'default' => '',
					'tooltip' => __( 'Wähle eine existierende Map aus dem PS Maps Plugin. Erstelle neue Maps unter Einstellungen → PS Maps.', 'padma' ),
				),

				'help_text' => array(
					'name'    => 'help_text',
					'type'    => 'notice',
					'notice'  => __( '<strong>Hinweis:</strong> Dieses Block nutzt das PS Maps Plugin. Erstelle und verwalte deine Maps im Backend unter <em>Einstellungen → PS Maps</em>. Dort kannst du Marker setzen, Routen planen, KML-Overlays hinzufügen und vieles mehr.', 'padma' ),
				),
			),

			'overrides' => array(
				'width' => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite (optional)', 'padma' ),
					'default' => '',
					'tooltip' => __( 'Überschreibt die Standard-Breite der Map. Leer lassen für Standard-Einstellung.', 'padma' ),
				),

				'height' => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe (optional)', 'padma' ),
					'default' => '',
					'tooltip' => __( 'Überschreibt die Standard-Höhe der Map. Leer lassen für Standard-Einstellung.', 'padma' ),
				),

				'zoom' => array(
					'name'    => 'zoom',
					'type'    => 'integer',
					'label'   => __( 'Zoom (optional)', 'padma' ),
					'default' => 0,
					'tooltip' => __( 'Überschreibt die Zoom-Stufe. Werte von 1 (Welt) bis 21 (Gebäude). 0 = Standard.', 'padma' ),
				),

				'map_type' => array(
					'name'    => 'map_type',
					'type'    => 'select',
					'label'   => __( 'Kartentyp (optional)', 'padma' ),
					'default' => '',
					'options' => array(
						''          => __( '-- Standard --', 'padma' ),
						'ROADMAP'   => __( 'Straßenkarte', 'padma' ),
						'SATELLITE' => __( 'Satellit', 'padma' ),
						'HYBRID'    => __( 'Hybrid', 'padma' ),
						'TERRAIN'   => __( 'Gelände', 'padma' ),
					),
					'tooltip' => __( 'Überschreibt den Kartentyp. Leer lassen für Standard-Einstellung.', 'padma' ),
				),
			),

			'create' => array(
				'create_notice' => array(
					'name'    => 'create_notice',
					'type'    => 'notice',
					'notice'  => __( '<strong>Quick-Create Map:</strong> Gib eine Adresse ein, um schnell eine neue einfache Map zu erstellen. Die Map wird beim ersten Anzeigen der Seite automatisch im PS Maps Plugin angelegt. <br><br><strong>Wichtig:</strong> Die Map wird nur erstellt, wenn <em>keine Map im Tab "Map Auswahl" ausgewählt ist</em>. Wenn du eine existierende Map ausgewählt hast, wird diese verwendet und die Quick-Create-Felder ignoriert.<br><br>Für erweiterte Features (mehrere Marker, Routen, KML, etc.) nutze die Map-Auswahl Tab und erstelle Maps im PS Maps Backend.', 'padma' ),
				),

				'create_address' => array(
					'name'    => 'create_address',
					'type'    => 'text',
					'label'   => __( 'Adresse', 'padma' ),
					'default' => '',
					'tooltip' => __( 'Vollständige Adresse (z.B. "Musterstraße 123, 12345 Musterstadt, Deutschland"). Diese wird automatisch geocodiert.', 'padma' ),
				),

				'create_map_name' => array(
					'name'    => 'create_map_name',
					'type'    => 'text',
					'label'   => __( 'Map-Name (optional)', 'padma' ),
					'default' => '',
					'tooltip' => __( 'Name für die neue Map. Wenn leer, wird die Adresse als Name verwendet.', 'padma' ),
				),

				'create_zoom' => array(
					'name'    => 'create_zoom',
					'type'    => 'integer',
					'label'   => __( 'Zoom-Stufe (optional)', 'padma' ),
					'default' => 15,
					'tooltip' => __( 'Zoom-Stufe für die neue Map. Werte von 1 (Welt) bis 21 (Gebäude). Standard: 15 (Straßenniveau)', 'padma' ),
				),

				'create_map_type' => array(
					'name'    => 'create_map_type',
					'type'    => 'select',
					'label'   => __( 'Kartentyp (optional)', 'padma' ),
					'default' => 'ROADMAP',
					'options' => array(
						'ROADMAP'   => __( 'Straßenkarte', 'padma' ),
						'SATELLITE' => __( 'Satellit', 'padma' ),
						'HYBRID'    => __( 'Hybrid', 'padma' ),
						'TERRAIN'   => __( 'Gelände', 'padma' ),
					),
					'tooltip' => __( 'Kartentyp für die neue Map.', 'padma' ),
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
			'' => __( '-- Bitte Map wählen --', 'padma' ),
		);

		// Prüfe ob PS Maps Plugin aktiv ist
		if ( ! class_exists( 'AgmMapModel' ) ) {
			$options[''] = __( 'PS Maps Plugin nicht aktiv', 'padma' );
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
				$options[''] = __( 'Keine Maps vorhanden - erstelle eine unter Einstellungen → PS Maps', 'padma' );
			}
		} catch ( \Exception $e ) {
			$options[''] = __( 'Fehler beim Laden der Maps', 'padma' );
		}

		return $options;
	}
}
