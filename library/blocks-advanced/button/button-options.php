<?php
/**
 * Block options class
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

/**
 * Options class for Button block
 */
class PadmaVisualElementsBlockButtonOptions extends \PadmaBlockOptionsAPI {

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

				'text'    => array(
					'name'    => 'text',
					'type'    => 'text',
					'label'   => __( 'Text', 'padma-advanced' ),
					'tooltip' => __( 'Button Text Inhalt', 'padma-advanced' ),
				),

				'url'     => array(
					'name'    => 'url',
					'type'    => 'text',
					'label'   => __( 'URL', 'padma-advanced' ),
					'tooltip' => __( 'Button Link', 'padma-advanced' ),
				),

				'target'  => array(
					'name'    => 'target',
					'type'    => 'select',
					'label'   => __( 'Ziel', 'padma-advanced' ),
					'default' => 'self',
					'options' => array(
						'self'  => __( 'Im gleichen Tab öffnen', 'padma-advanced' ),
						'blank' => __( 'In neuem Tab öffnen', 'padma-advanced' ),
					),
					'tooltip' => __( 'Button Link Ziel', 'padma-advanced' ),
				),

				'style'   => array(
					'name'    => 'style',
					'label'   => __( 'Stil', 'padma-advanced' ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'none'    => 'none',
						'default' => 'Default',
						'flat'    => 'Flat',
						'ghost'   => 'Ghost',
						'soft'    => 'Soft',
						'glass'   => 'Glass',
						'bubbles' => 'Bubbles',
						'noise'   => 'Noise',
						'stroked' => 'Stroked',
						'3d'      => '3D',
					),
					'tooltip' => __( 'Button Hintergrund Stil Voreinstellung', 'padma-advanced' ),
				),

				'icon'    => array(
					'name'    => 'icon',
					'label'   => __( 'Symbol', 'padma-advanced' ),
					'type'    => 'text',
					'tooltip' => __( 'Du kannst ein eigenes Symbol für diesen Button hochladen oder ein eingebautes Symbol wählen. FontAwesome Symbol Name oder Symbol Bild URL. Beispiele: "star", http://beispiel.de/icon.png', 'padma-advanced' ),
				),

				'desc'    => array(
					'name'    => 'desc',
					'label'   => __( 'Beschreibung', 'padma-advanced' ),
					'type'    => 'text',
					'tooltip' => __( 'Kleine Beschreibung unter dem Button Text. Diese Option ist inkompatibel mit Symbol.', 'padma-advanced' ),
				),

				'onclick' => array(
					'name'    => 'onclick',
					'label'   => __( 'onClick', 'padma-advanced' ),
					'type'    => 'text',
					'tooltip' => __( 'Erweiterter JavaScript Code für onClick Aktion.', 'padma-advanced' ),
				),

				'rel'     => array(
					'name'    => 'rel',
					'label'   => __( 'Rel', 'padma-advanced' ),
					'type'    => 'text',
					'tooltip' => __( 'Hier kannst du einen Wert für das rel Attribut hinzufügen. Beispielwerte: nofollow, lightbox', 'padma-advanced' ),
				),

				'title'   => array(
					'name'    => 'title',
					'label'   => __( 'Titel', 'padma-advanced' ),
					'type'    => 'text',
					'tooltip' => __( 'Hier kannst du einen Wert für das title Attribut hinzufügen', 'padma-advanced' ),
				),
			),
		);
	}


	/**
	 * Allow developers to modify the properties of the class and use functions since doing a property outside of a function will not allow you to.
	 *
	 * @param boolean $args Args.
	 * @return void
	 */
	public function modify_arguments( $args = false ) {}

}
