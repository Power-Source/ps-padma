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
class PadmaVisualElementsBlockDummyTextOptions extends \PadmaBlockOptionsAPI {

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
				'what'   => array(
					'name'    => 'what',
					'type'    => 'select',
					'label'   => __( 'Was', 'padma-advanced' ),
					'tooltip' => __( 'Was generiert werden soll', 'padma-advanced' ),
					'default' => 'paras',
					'options' => array(
						'paras' => __( 'Absätze', 'padma-advanced' ),
						'words' => __( 'Wörter', 'padma-advanced' ),
						'bytes' => __( 'Bytes', 'padma-advanced' ),
					),
				),

				'amount' => array(
					'name'    => 'amount',
					'type'    => 'integer',
					'label'   => __( 'Menge', 'padma-advanced' ),
					'tooltip' => __( 'Wie viele Elemente (Absätze oder Wörter) generiert werden sollen. Minimale Anzahl Wörter ist 5', 'padma-advanced' ),
					'default' => 1,
				),

				'cache'  => array(
					'name'    => 'cache ',
					'type'    => 'select',
					'label'   => __( 'Cache ', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
				),
			),
		);
	}

}
