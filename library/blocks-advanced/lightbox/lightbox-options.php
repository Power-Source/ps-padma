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
class PadmaVisualElementsBlockLightboxOptions extends \PadmaBlockOptionsAPI {

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
			'image'   => __( 'Bild', 'padma' ),
			'iframe'  => __( 'Iframe', 'padma' ),
			'inline'  => __( 'Inline', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'type'  => array(
					'name'    => 'type',
					'type'    => 'select',
					'label'   => __( 'Typ', 'padma' ),
					'default' => 'image',
					'options' => array(
						'image'  => __( 'Bild', 'padma' ),
						'iframe' => __( 'Iframe', 'padma' ),
						'inline' => __( 'Inline', 'padma' ),
					),
					'toggle'  => array(
						'image'  => array(
							'show' => array(
								'li#sub-tab-image',
							),
							'hide' => array(
								'li#sub-tab-iframe',
								'li#sub-tab-inline',
							),
						),
						'iframe' => array(
							'show' => array(
								'li#sub-tab-iframe',
							),
							'hide' => array(
								'li#sub-tab-image',
								'li#sub-tab-inline',
							),
						),
						'inline' => array(
							'show' => array(
								'li#sub-tab-inline',
							),
							'hide' => array(
								'li#sub-tab-iframe',
								'li#sub-tab-image',
							),
						),
					),
					'tooltip' => __( 'Wähle den Typ des Lightbox Fenster Inhalts', 'padma' ),
				),

				'title' => array(
					'name'    => 'title',
					'type'    => 'text',
					'label'   => __( 'Titel', 'padma' ),
					'tooltip' => __( 'Text für den Titel', 'padma' ),
				),
			),

			'image'   => array(
				'image' => array(
					'name'    => 'image',
					'type'    => 'image',
					'label'   => __( 'Bild', 'padma' ),
					'tooltip' => __( 'Wähle das Bild das angezeigt werden soll', 'padma' ),
				),
			),

			'iframe'  => array(
				'iframe' => array(
					'name'    => 'iframe',
					'type'    => 'text',
					'label'   => __( 'URL', 'padma' ),
					'tooltip' => __( 'URL die angezeigt werden soll', 'padma' ),
				),
			),

			'inline'  => array(
				'inline' => array(
					'name'    => 'inline',
					'type'    => 'wysiwyg',
					'label'   => __( 'Inhalt', 'padma' ),
					'tooltip' => __( 'Inhalt der angezeigt werden soll', 'padma' ),
				),
			),
		);
	}
}
