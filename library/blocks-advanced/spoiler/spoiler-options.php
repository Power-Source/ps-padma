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
class PadmaVisualElementsBlockSpoilerOptions extends \PadmaBlockOptionsAPI {

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

				'spoilers' => array(
					'type'    => 'repeater',
					'name'    => 'spoilers',
					'label'   => __( 'Spoiler', 'padma-advanced' ),
					'tooltip' => __( 'Spoiler mit verstecktem Inhalt', 'padma-advanced' ),
					'inputs'  => array(
						array(
							'type'  => 'text',
							'name'  => 'title',
							'label' => __( 'Titel', 'padma-advanced' ),
						),

						array(
							'type'    => 'select',
							'name'    => 'open',
							'label'   => __( 'Geöffnet', 'padma-advanced' ),
							'options' => array(
								'yes' => __( 'Ja', 'padma-advanced' ),
								'no'  => __( 'Nein', 'padma-advanced' ),
							),
							'default' => 'no',
						),

						array(
							'name'    => 'style',
							'type'    => 'select',
							'label'   => __( 'Stil', 'padma-advanced' ),
							'default' => 'default',
							'options' => array(
								'default' => __( 'Standard', 'padma-advanced' ),
								'fancy'   => __( 'Schick', 'padma-advanced' ),
								'simple'  => __( 'Einfach', 'padma-advanced' ),
							),
							'tooltip' => __( 'Wähle den Stil für diesen Spoiler', 'padma-advanced' ),
						),

						array(
							'name'    => 'icon',
							'type'    => 'select',
							'label'   => __( 'Symbol', 'padma-advanced' ),
							'default' => 'plus',
							'options' => array(
								'plus'           => 'Plus',
								'plus-cicle'     => 'Plus-cicle',
								'plus-square-1'  => 'Plus-square-1',
								'plus-square-2'  => 'Plus-square-2',
								'arrow'          => 'Arrow',
								'arrow-circle-1' => 'Arrow-circle-1',
								'arrow-circle-2' => 'Arrow-circle-1e',
								'chevron'        => 'Chevron',
								'chevron-circle' => 'Chevron-circle',
								'caret'          => 'Caret',
								'caret-square'   => 'Caret-square',
								'folder-1'       => 'Folder-1',
								'folder-2'       => 'Folder-2',
							),
							'tooltip' => __( 'Choose style for this spoiler', 'padma-advanced' ),
						),

						array(
							'type'    => 'text',
							'name'    => 'anchor',
							'label'   => __( 'Anker', 'padma-advanced' ),
							'tooltip' => __( 'Du kannst einen einzigartigen Anker für diesen Tab verwenden, um ihn mit einem Hash in der Seiten-URL aufzurufen. Beispiel: verwende Hallo und navigiere dann zu einer URL wie http://beispiel.de/seiten-url#Hallo. Dieser Tab wird aktiviert und gescrollt.', 'padma-advanced' ),
						),

						array(
							'type'    => 'wysiwyg',
							'name'    => 'content',
							'label'   => __( 'Inhalt', 'padma-advanced' ),
							'default' => null,
						),
					),
				),
				'sortable' => true,
				'limit'    => 100,
			),
		);
	}
}
