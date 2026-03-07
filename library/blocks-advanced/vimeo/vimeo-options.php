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
class PadmaVisualElementsBlockVimeoOptions extends \PadmaBlockOptionsAPI {

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

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'URL', 'padma-advanced' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL der Vimeo Seite mit Video. Beispiel: http://vimeo.com/watch?v=XXXXXX', 'padma-advanced' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma-advanced' ),
					'default' => 600,
					'tooltip' => __( 'Breite', 'padma-advanced' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma-advanced' ),
					'default' => 400,
					'tooltip' => __( 'Höhe', 'padma-advanced' ),
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
					'tooltip' => __( 'Breite und Höhe Parameter ignorieren und Player responsiv machen', 'padma-advanced' ),
				),

				'autoplay'   => array(
					'name'    => 'autoplay',
					'type'    => 'select',
					'label'   => __( 'Autoplay', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Video automatisch abspielen wenn die Seite geladen wird.', 'padma-advanced' ),
				),

				'dnt'        => array(
					'name'    => 'dnt',
					'type'    => 'select',
					'label'   => __( 'DNT', 'padma-advanced' ),
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Wenn du diesen Parameter auf JA setzt, wird der Player daran gehindert, Wiedergabesitzungsdaten zu verfolgen. Hat den gleichen Effekt wie das Aktivieren eines Do Not Track Headers in deinem Browser', 'padma-advanced' ),
				),
			),
		);
	}

}

