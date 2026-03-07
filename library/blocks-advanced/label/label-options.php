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
class PadmaVisualElementsBlockLabelOptions extends \PadmaBlockOptionsAPI {

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

				'type' => array(
					'name'    => 'type',
					'label'   => __( 'Typ', 'padma' ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'default'   => __( 'Standard', 'padma' ),
						'success'   => __( 'Erfolg', 'padma' ),
						'warning'   => __( 'Warnung', 'padma' ),
						'important' => __( 'Wichtig', 'padma' ),
						'black'     => __( 'Schwarz', 'padma' ),
						'info'      => __( 'Info', 'padma' ),
					),
					'tooltip' => __( 'Stil des Labels', 'padma' ),
				),

				'text' => array(
					'name'  => 'text',
					'type'  => 'text',
					'label' => __( 'Text', 'padma' ),
				),
			),
		);
	}

}
