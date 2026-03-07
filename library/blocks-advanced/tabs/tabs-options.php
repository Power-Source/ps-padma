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
class PadmaVisualElementsBlockTabsOptions extends \PadmaBlockOptionsAPI {

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

				'active'   => array(
					'name'    => 'active',
					'label'   => __( 'Aktiv', 'padma' ),
					'type'    => 'integer',
					'tooltip' => __( 'Welcher Tab standardmäßig geöffnet ist. Nummer von 1 bis 100.', 'padma' ),
					'default' => 1,
				),

				'vertical' => array(
					'name'    => 'vertical',
					'label'   => __( 'Vertikal', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Tabs vertikal ausrichten', 'padma' ),
				),

				'tabs'     => array(
					'type'     => 'repeater',
					'name'     => 'tabs',
					'label'    => __( 'Tabs', 'padma' ),
					'tooltip'  => __( 'Inhalt für deine Tabs', 'padma' ),
					'sortable' => true,
					'limit'    => 100,
					'inputs'   => array(

						array(
							'type'  => 'text',
							'name'  => 'title',
							'label' => __( 'Titel', 'padma' ),
						),

						array(
							'type'    => 'select',
							'name'    => 'disabled',
							'label'   => __( 'Deaktiviert', 'padma' ),
							'options' => array(
								'yes' => __( 'Ja', 'padma' ),
								'no'  => __( 'Nein', 'padma' ),
							),
							'default' => 'no',
						),

						array(
							'type'    => 'text',
							'name'    => 'anchor',
							'label'   => __( 'Anker', 'padma' ),
							'tooltip' => __( 'Du kannst einen einzigartigen Anker für diesen Tab verwenden, um ihn mit einem Hash in der Seiten-URL aufzurufen. Beispiel: verwende Hallo und navigiere dann zu einer URL wie http://beispiel.de/seiten-url#Hallo. Dieser Tab wird aktiviert und gescrollt.', 'padma' ),
						),

						array(
							'type'    => 'text',
							'name'    => 'url',
							'label'   => __( 'URL', 'padma' ),
							'tooltip' => __( 'Verlinke Tab zu einer beliebigen Webseite. Verwende die vollständige URL um den Tab Titel in einen Link zu verwandeln.', 'padma' ),
						),

						array(
							'name'    => 'target',
							'type'    => 'select',
							'label'   => __( 'Ziel', 'padma' ),
							'default' => 'blank',
							'options' => array(
								'self'  => __( 'Im gleichen Tab öffnen', 'padma' ),
								'blank' => __( 'In neuem Tab öffnen', 'padma' ),
							),
							'tooltip' => __( 'Wähle wie der benutzerdefinierte Tab Link geöffnet wird', 'padma' ),
						),

						array(
							'type'    => 'wysiwyg',
							'name'    => 'content',
							'label'   => __( 'Inhalt', 'padma' ),
							'default' => null,
						),
					),
				),
			),
		);
	}
}
