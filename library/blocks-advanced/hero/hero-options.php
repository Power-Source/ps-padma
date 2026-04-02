<?php
/**
 * Hero block options.
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

class PadmaVisualElementsBlockHeroOptions extends \PadmaBlockOptionsAPI {

	public $tabs;
	public $sets;
	public $inputs;

	public function __construct() {
		$this->tabs = array(
			'general' => __( 'Allgemein', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'title' => array(
					'name'    => 'title',
					'type'    => 'text',
					'label'   => __( 'Titel', 'padma' ),
					'default' => __( 'Hero Überschrift', 'padma' ),
					'tooltip' => __( 'Hauptüberschrift des Hero-Bereichs.', 'padma' ),
				),
				'subtitle' => array(
					'name'    => 'subtitle',
					'type'    => 'text',
					'label'   => __( 'Untertitel', 'padma' ),
					'default' => __( 'Kurze Einleitung oder Zusatzzeile', 'padma' ),
					'tooltip' => __( 'Optionale Zeile oberhalb des Hero-Inhalts.', 'padma' ),
				),
				'content' => array(
					'name'    => 'content',
					'type'    => 'textarea',
					'label'   => __( 'Inhalt', 'padma' ),
					'default' => __( 'Kurzer Beschreibungstext für den Hero-Bereich.', 'padma' ),
					'tooltip' => __( 'Beschreibungstext innerhalb des Hero-Bereichs.', 'padma' ),
				),
				'background_image' => array(
					'name'    => 'background_image',
					'type'    => 'image',
					'label'   => __( 'Hintergrundbild', 'padma' ),
					'default' => '',
					'tooltip' => __( 'Bild für den Hero-Hintergrund.', 'padma' ),
				),
				'button_text' => array(
					'name'    => 'button_text',
					'type'    => 'text',
					'label'   => __( 'Button-Text', 'padma' ),
					'default' => __( 'Mehr erfahren', 'padma' ),
					'tooltip' => __( 'Text des optionalen Hero-Buttons.', 'padma' ),
				),
				'button_url' => array(
					'name'    => 'button_url',
					'type'    => 'text',
					'label'   => __( 'Button-URL', 'padma' ),
					'default' => '',
					'tooltip' => __( 'Ziel-URL des Buttons.', 'padma' ),
				),
				'button_target' => array(
					'name'    => 'button_target',
					'type'    => 'select',
					'label'   => __( 'Button-Ziel', 'padma' ),
					'default' => 'self',
					'options' => array(
						'self'  => __( 'Im gleichen Tab öffnen', 'padma' ),
						'blank' => __( 'In neuem Tab öffnen', 'padma' ),
					),
					'tooltip' => __( 'Wie wird der Button-Link geöffnet.', 'padma' ),
				),
			),
		);
	}

	public function modify_arguments( $args = false ) {}
}