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
			'general' => __( 'Allgemein', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'what'   => array(
					'name'    => 'what',
					'type'    => 'select',
					'label'   => __( 'Was', 'padma' ),
					'tooltip' => __( 'Was generiert werden soll', 'padma' ),
					'default' => 'paras',
					'options' => array(
						'paras' => __( 'Absätze', 'padma' ),
						'words' => __( 'Wörter', 'padma' ),
						'bytes' => __( 'Bytes', 'padma' ),
					),
				),

				'amount' => array(
					'name'    => 'amount',
					'type'    => 'integer',
					'label'   => __( 'Menge', 'padma' ),
					'tooltip' => __( 'Wie viele Elemente (Absätze oder Wörter) generiert werden sollen. Minimale Anzahl Wörter ist 5', 'padma' ),
					'default' => 1,
				),

				'cache'  => array(
					'name'    => 'cache ',
					'type'    => 'select',
					'label'   => __( 'Cache ', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
				),
			),
		);
	}

}
