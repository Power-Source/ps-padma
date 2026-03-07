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
			'general' => __( 'Allgemein', 'padma-advanced' ),
			'image'   => __( 'Bild', 'padma-advanced' ),
			'iframe'  => __( 'Iframe', 'padma-advanced' ),
			'inline'  => __( 'Inline', 'padma-advanced' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'type'  => array(
					'name'    => 'type',
					'type'    => 'select',
					'label'   => __( 'Typ', 'padma-advanced' ),
					'default' => 'image',
					'options' => array(
						'image'  => __( 'Bild', 'padma-advanced' ),
						'iframe' => __( 'Iframe', 'padma-advanced' ),
						'inline' => __( 'Inline', 'padma-advanced' ),
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
					'tooltip' => __( 'Wähle den Typ des Lightbox Fenster Inhalts', 'padma-advanced' ),
				),

				'title' => array(
					'name'    => 'title',
					'type'    => 'text',
					'label'   => __( 'Titel', 'padma-advanced' ),
					'tooltip' => __( 'Text für den Titel', 'padma-advanced' ),
				),
			),

			'image'   => array(
				'image' => array(
					'name'    => 'image',
					'type'    => 'image',
					'label'   => __( 'Bild', 'padma-advanced' ),
					'tooltip' => __( 'Wähle das Bild das angezeigt werden soll', 'padma-advanced' ),
				),
			),

			'iframe'  => array(
				'iframe' => array(
					'name'    => 'iframe',
					'type'    => 'text',
					'label'   => __( 'URL', 'padma-advanced' ),
					'tooltip' => __( 'URL die angezeigt werden soll', 'padma-advanced' ),
				),
			),

			'inline'  => array(
				'inline' => array(
					'name'    => 'inline',
					'type'    => 'wysiwyg',
					'label'   => __( 'Inhalt', 'padma-advanced' ),
					'tooltip' => __( 'Inhalt der angezeigt werden soll', 'padma-advanced' ),
				),
			),
		);
	}
}
