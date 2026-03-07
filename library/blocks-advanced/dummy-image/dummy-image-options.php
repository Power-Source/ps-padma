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
			'general' => __( 'Allgemein', 'padma-advanced' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'width'  => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma-advanced' ),
					'tooltip' => __( 'Bildbreite', 'padma-advanced' ),
					'default' => 500,
				),

				'height' => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma-advanced' ),
					'tooltip' => __( 'Bildhöhe', 'padma-advanced' ),
					'default' => 300,
				),
				'theme'  => array(
					'name'    => 'theme ',
					'type'    => 'select',
					'label'   => __( 'Thema ', 'padma-advanced' ),
					'default' => 'any',
					'options' => array(
						'any'       => __( 'Beliebig', 'padma-advanced' ),
						'abstract'  => __( 'Abstrakt', 'padma-advanced' ),
						'animals'   => __( 'Tiere', 'padma-advanced' ),
						'business'  => __( 'Geschäft', 'padma-advanced' ),
						'cats'      => __( 'Katzen', 'padma-advanced' ),
						'city'      => __( 'Stadt', 'padma-advanced' ),
						'food'      => __( 'Essen', 'padma-advanced' ),
						'nightlife' => __( 'Nachtleben', 'padma-advanced' ),
						'fashion'   => __( 'Mode', 'padma-advanced' ),
						'people'    => __( 'Menschen', 'padma-advanced' ),
						'nature'    => __( 'Natur', 'padma-advanced' ),
						'sports'    => __( 'Sport', 'padma-advanced' ),
						'technics'  => __( 'Technik', 'padma-advanced' ),
						'transport' => __( 'Transport', 'padma-advanced' ),

					),
					'tooltip' => 'Wähle das Thema für dieses Bild',
				),
			),
		);
	}
}
