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
class PadmaVisualElementsBlockDummyImageOptions extends \PadmaBlockOptionsAPI {

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
			'general' => __( 'Allgemein', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'width'  => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma' ),
					'tooltip' => __( 'Bildbreite', 'padma' ),
					'default' => 500,
				),

				'height' => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma' ),
					'tooltip' => __( 'Bildhöhe', 'padma' ),
					'default' => 300,
				),
				'theme'  => array(
					'name'    => 'theme ',
					'type'    => 'select',
					'label'   => __( 'Thema ', 'padma' ),
					'default' => 'any',
					'options' => array(
						'any'       => __( 'Beliebig', 'padma' ),
						'abstract'  => __( 'Abstrakt', 'padma' ),
						'animals'   => __( 'Tiere', 'padma' ),
						'business'  => __( 'Geschäft', 'padma' ),
						'cats'      => __( 'Katzen', 'padma' ),
						'city'      => __( 'Stadt', 'padma' ),
						'food'      => __( 'Essen', 'padma' ),
						'nightlife' => __( 'Nachtleben', 'padma' ),
						'fashion'   => __( 'Mode', 'padma' ),
						'people'    => __( 'Menschen', 'padma' ),
						'nature'    => __( 'Natur', 'padma' ),
						'sports'    => __( 'Sport', 'padma' ),
						'technics'  => __( 'Technik', 'padma' ),
						'transport' => __( 'Transport', 'padma' ),

					),
					'tooltip' => 'Wähle das Thema für dieses Bild',
				),
			),
		);
	}
}
