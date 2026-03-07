<?php
/**
 * Block options class
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

/**
 * Options class for block
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
			'general' => __( 'Allgemein', 'padma-advanced' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'address'    => array(
					'name'    => 'address',
					'label'   => __( 'Adresse', 'padma-advanced' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'Adresse für den Marker. Du kannst sie in jeder Sprache eingeben', 'padma-advanced' ),
				),

				'responsive' => array(
					'name'    => 'responsive',
					'type'    => 'select',
					'label'   => __( 'Responsiv', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Breite und Höhe Parameter ignorieren und Karte responsiv machen', 'padma-advanced' ),
				),

				'zoom'       => array(
					'name'    => 'zoom',
					'type'    => 'integer',
					'label'   => __( 'Zoom', 'padma-advanced' ),
					'default' => 0,
					'tooltip' => __( 'Zoom legt die Anfangs-Zoomstufe der Karte fest. Akzeptierte Werte reichen von 1 (die ganze Welt) bis 21 (einzelne Gebäude). Verwende 0 (Null) um die Zoomstufe automatisch je nach angezeigtem Objekt festzulegen', 'padma-advanced' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma-advanced' ),
					'default' => 600,
					'tooltip' => __( 'Kartenbreite', 'padma-advanced' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma-advanced' ),
					'default' => 400,
					'tooltip' => __( 'Kartenhöhe', 'padma-advanced' ),
				),
			),
		);
	}
}
