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
class PadmaVisualElementsBlockDividerOptions extends \PadmaBlockOptionsAPI {

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
			'general' => 'Allgemein',
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'top'    => array(
					'name'    => 'top',
					'label'   => __( 'Nach oben', 'padma' ),
					'type'    => 'select',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Link zum Seitenanfang anzeigen oder nicht', 'padma' ),
				),
				'text'   => array(
					'name'    => 'text',
					'type'    => 'text',
					'label'   => __( 'Text', 'padma' ),
					'tooltip' => __( 'Text für den NACH OBEN Link', 'padma' ),
				),
				'style'  => array(
					'name'    => 'style',
					'label'   => __( 'Stil', 'padma' ),
					'type'    => 'select',
					'default' => 'none',
					'options' => array(
						'default' => __( 'Standard', 'padma' ),
						'dotted'  => __( 'Gepunktet', 'padma' ),
						'dashed'  => __( 'Gestrichelt', 'padma' ),
						'double'  => __( 'Doppelt', 'padma' ),
					),
					'tooltip' => __( 'Wähle den Stil für diesen Trenner', 'padma' ),
				),
				'margin' => array(
					'name'    => 'margin',
					'label'   => __( 'Abstand', 'padma' ),
					'type'    => 'integer',
					'tooltip' => __( 'Passe die oberen und unteren Abstände dieses Trenners an (in Pixeln)', 'padma' ),
					'default' => 20,
				),
				'size'   => array(
					'name'    => 'size',
					'label'   => __( 'Größe', 'padma' ),
					'type'    => 'integer',
					'tooltip' => __( 'Höhe des Trenners (in Pixeln)', 'padma' ),
					'default' => 3,
				),
			),
		);
	}
}
