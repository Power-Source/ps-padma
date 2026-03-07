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
class PadmaVisualElementsBlockYoutubeOptions extends \PadmaBlockOptionsAPI {

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

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'URL', 'padma' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL der YouTube Seite mit Video. Beispiel: http://youtube.com/watch?v=XXXXXX', 'padma' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma' ),
					'default' => 600,
					'tooltip' => __( 'Breite', 'padma' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma' ),
					'default' => 400,
					'tooltip' => __( 'Höhe', 'padma' ),
				),

				'responsive' => array(
					'name'    => 'responsive',
					'type'    => 'select',
					'label'   => __( 'Responsiv', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Breite und Höhe Parameter ignorieren und Player responsiv machen', 'padma' ),
				),

				'autoplay'   => array(
					'name'    => 'autoplay',
					'type'    => 'select',
					'label'   => __( 'Autoplay', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Video automatisch abspielen wenn die Seite geladen wird. Bitte beachte, dass in modernen Browsern die Autoplay-Option nur funktioniert, wenn die Stumm-Option aktiviert ist', 'padma' ),
				),

				'mute'       => array(
					'name'    => 'mute',
					'type'    => 'select',
					'label'   => __( 'Stumm', 'padma' ),
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Player stumm schalten', 'padma' ),
				),
			),
		);
	}

}
