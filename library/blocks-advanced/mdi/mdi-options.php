<?php
/**
 * Block options class
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

class PadmaMDIBlockOptions extends \PadmaBlockOptionsAPI {

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

				'url' => array(
					'name'    => 'url',
					'label'   => 'Link',
					'type'    => 'text',
					'tooltip' => 'Wenn gesetzt, wird das Symbol ein Link sein',
				),

				'before-icon' => array(
					'name'    => 'before-icon',
					'label'   => 'Vor dem Symbol',
					'type'    => 'wysiwyg',
					'tooltip' => 'Inhalt vor dem Symbol hinzufügen',
				),

				'after-icon'  => array(
					'name'    => 'after-icon',
					'label'   => 'Nach dem Symbol',
					'type'    => 'wysiwyg',
					'tooltip' => 'Inhalt nach dem Symbol hinzufügen',
				),
				'filter'      => array(
					'name' => 'filter',
					'type' => 'raw_html',
					'html' => '<div class="mdi-icon-filter-search"><input type="text" id="icon-filter" placeholder="Filter" title="Filter icons"><a class="mdi-icon-filter-reset"><span>x</span></a></div>',
				),
				'mdi-icon-width' => array(
					'name'    => 'mdi-icon-width',
					'type'    => 'integer',
					'label'   => 'Breite',
					'default' => '24',
				),
				'mdi-icon-height' => array(
					'name'    => 'mdi-icon-height',
					'type'    => 'integer',
					'label'   => 'Höhe',
					'default' => '24',
				),
				'mdi-icon'     => array(
					'name'    => 'mdi-icon',
					'type'    => 'radio',
					'label'   => 'Symbol',
					'default' => '',
					'options' => $this->load(),
				),
			),
		);
	}

	/**
	 * Load MDI icons
	 *
	 * @return array
	 */
	public function load() {
		$icons = array();
		$data = (array) json_decode( file_get_contents( __DIR__ . '/meta.json' ) );

		foreach ( $data as $key => $icon ) {
			$icons[ $icon->name ] = sprintf( '<i class="mdi mdi-%s" style="font-size:24px;line-height:24px;"></i>', esc_attr( $icon->name ) );
		}
		return $icons;
	}

}
