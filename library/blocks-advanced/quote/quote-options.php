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
class PadmaVisualElementsBlockQuoteOptions extends \PadmaBlockOptionsAPI {

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

				'quote' => array(
					'name'    => 'quote',
					'type'    => 'wysiwyg',
					'label'   => __( 'Zitat', 'padma' ),
					'tooltip' => __( 'Zitat Text', 'padma' ),
				),

				'cite'  => array(
					'name'    => 'cite',
					'type'    => 'text',
					'label'   => __( 'Zitiert', 'padma' ),
					'tooltip' => __( 'Name des Zitatautors', 'padma' ),
				),

				'url'   => array(
					'name'    => 'url',
					'type'    => 'text',
					'label'   => __( 'Autor URL', 'padma' ),
					'tooltip' => __( 'URL des Zitatautors. Leer lassen um Link zu deaktivieren', 'padma' ),
				),
			),
		);
	}
}
