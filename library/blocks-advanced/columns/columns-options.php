<?php
/**
 * Block options class
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

/**
 * Options class for Columns block
 */
class PadmaVisualElementsBlockColumnsOptions extends \PadmaBlockOptionsAPI {

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
				'columns' => array(
					'type'     => 'repeater',
					'name'     => 'columns',
					'label'    => __( 'Spalten', 'padma' ),
					'tooltip'  => __( 'Inhalt für deine Spalten', 'padma' ),
					'inputs'   => array(
						array(
							'name'    => 'size',
							'label'   => __( 'Größe', 'padma' ),
							'type'    => 'select',
							'default' => 'one-half',
							'options' => array(
								'full-width'   => __( 'Volle Breite 1/1', 'padma' ),
								'one-half'     => __( 'Eine Hälfte 1/2', 'padma' ),
								'one-third'    => __( 'Ein Drittel 1/3', 'padma' ),
								'two-third'    => __( 'Zwei Drittel 2/3', 'padma' ),
								'one-fourth'   => __( 'Ein Viertel 1/4', 'padma' ),
								'three-fourth' => __( 'Drei Viertel 3/4', 'padma' ),
								'one-fifth'    => __( 'Ein Fünftel 1/5', 'padma' ),
								'two-fifth'    => __( 'Zwei Fünftel 2/5', 'padma' ),
								'three-fifth'  => __( 'Drei Fünftel 3/5', 'padma' ),
								'four-fifth'   => __( 'Vier Fünftel 4/5', 'padma' ),
								'one-sixth'    => __( 'Ein Sechstel 1/6', 'padma' ),
								'five-sixth'   => __( 'Fünf Sechstel 5/6', 'padma' ),
							),
							'tooltip' => __( 'Wähle die Spaltenbreite. Diese Breite wird abhängig von der Seitenbreite berechnet', 'padma' ),
						),

						array(
							'type'    => 'select',
							'name'    => 'center',
							'label'   => __( 'Zentriert', 'padma' ),
							'options' => array(
								'yes' => __( 'Ja', 'padma' ),
								'no'  => __( 'Nein', 'padma' ),
							),
							'default' => 'no',
							'tooltip' => __( 'Ist diese Spalte auf der Seite zentriert', 'padma' ),
						),

						array(
							'type'    => 'text',
							'name'    => 'class',
							'label'   => __( 'Klasse', 'padma' ),
							'tooltip' => __( 'Zusätzliche CSS Klassennamen getrennt durch Leerzeichen', 'padma' ),
						),

						array(
							'type'    => 'wysiwyg',
							'name'    => 'content',
							'label'   => __( 'Inhalt', 'padma' ),
							'default' => null,
						),
					),
					'sortable' => true,
					'limit'    => 100,
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
