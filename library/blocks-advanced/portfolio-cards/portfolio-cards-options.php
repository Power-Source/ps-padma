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
class PadmaVisualElementsBlockPortfolioCardsOptions extends \PadmaBlockOptionsAPI {

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
			'general'       => 'Allgemein',
			'query-filters' => 'Query Filter',
		);

		$this->sets = array();

		$this->inputs = array(

			'general'       => array(

				'columns' => array(
					'type'       => 'slider',
					'name'       => 'columns',
					'label'      => 'Spalten',
					'tooltip'    => 'Anzahl der Portfolio-Spalten',
					'unit'       => null,
					'default'    => 4,
					'slider-min' => 1,
					'slider-max' => 6,
					'toggle'     => array(
						'1' => array(
							'show' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
							'hide' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),

						),
						'2' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'3' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'4' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'5' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'6' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
					),
				),
			),

			'query-filters' => array(

				'categories'      => array(
					'type'    => 'multi-select',
					'name'    => 'categories',
					'label'   => 'Kategorien',
					'tooltip' => 'Wähle Kategorien aus den ausgewählten Post Types',
					'options' => 'get_categories()',
				),

				'categories-mode' => array(
					'type'    => 'select',
					'name'    => 'categories-mode',
					'label'   => 'Kategorien-Modus',
					'tooltip' => 'Sollen die ausgewählten Kategorien eingeschlossen oder ausgeschlossen werden',
					'options' => array(
						'include' => 'Einschließen',
						'exclude' => 'Ausschließen',
					),
				),

				'enable-tags'     => array(
					'type'    => 'checkbox',
					'name'    => 'tags-filter',
					'label'   => 'Tags Filter',
					'tooltip' => 'Aktiviere diese Option, um nach Tags zu filtern',
					'default' => false,
					'toggle'  => array(
						'false' => array(
							'hide' => array(
								'#input-tags',
							),
						),
						'true'  => array(
							'show' => array(
								'#input-tags',
							),
						),
					),
				),

				'tags'            => array(
					'type'    => 'multi-select',
					'name'    => 'tags',
					'label'   => 'Tags',
					'tooltip' => 'Wähle Tags aus, um Beiträge zu filtern',
					'options' => 'get_tags()',
				),

				'post-type'       => array(
					'type'     => 'multi-select',
					'name'     => 'post-type',
					'label'    => 'Post Type',
					'tooltip'  => 'Wähle die Post Types aus, die angezeigt werden sollen',
					'default'  => array('post'),
					'options'  => 'get_post_types()',
					'callback' => 'reloadBlockOptions()',
				),

				'post-status'     => array(
					'type'    => 'multi-select',
					'name'    => 'post-status',
					'label'   => 'Post Status',
					'tooltip' => 'Wähle den Status der Beiträge (veröffentlicht, Entwurf, etc.)',
					'options' => 'get_post_status()',
				),

				'author'          => array(
					'type'    => 'multi-select',
					'name'    => 'author',
					'label'   => 'Autor',
					'tooltip' => 'Filtere Beiträge nach Autor',
					'options' => 'get_authors()',
				),

				'number-of-posts' => array(
					'type'    => 'integer',
					'name'    => 'number-of-posts',
					'label'   => 'Anzahl der Beiträge',
					'tooltip' => 'Wie viele Beiträge sollen angezeigt werden',
					'default' => 10,
				),

				'offset'          => array(
					'type'    => 'integer',
					'name'    => 'offset',
					'label'   => 'Offset',
					'tooltip' => 'Die Anzahl der Beiträge, die übersprungen werden sollen. Bei Offset 1 wird der erste Beitrag übersprungen',
					'default' => 0,
				),

				'order-by'        => array(
					'type'    => 'select',
					'name'    => 'order-by',
					'label'   => 'Sortieren nach',
					'tooltip' => 'Nach welchem Feld sollen die Beiträge sortiert werden',
					'options' => array(
						'date'          => 'Datum',
						'title'         => 'Titel',
						'rand'          => 'Zufällig',
						'comment_count' => 'Kommentar-Anzahl',
						'ID'            => 'ID',
						'author'        => 'Autor',
						'type'          => 'Post Type',
						'menu_order'    => 'Benutzerdefinierte Reihenfolge',
					),
				),

				'order'           => array(
					'type'    => 'select',
					'name'    => 'order',
					'label'   => 'Reihenfolge',
					'tooltip' => 'Aufsteigend oder absteigend sortieren',
					'options' => array(
						'desc' => 'Absteigend',
						'asc'  => 'Aufsteigend',
					),
				),

				'byid-include'    => array(
					'type'    => 'text',
					'name'    => 'byid-include',
					'label'   => 'Einschließen nach ID',
					'tooltip' => 'Komma-getrennte Liste von Post-IDs, die eingeschlossen werden sollen',
				),

				'byid-exclude'    => array(
					'type'    => 'text',
					'name'    => 'byid-exclude',
					'label'   => 'Ausschließen nach ID',
					'tooltip' => 'Komma-getrennte Liste von Post-IDs, die ausgeschlossen werden sollen',
				),
			),
		);
	}

	/**
	 * Get posts categories
	 *
	 * @return array
	 */
	public function get_categories() {
		// Wenn Post Type gesetzt ist, lade kategorien für diese Post Types
		if ( isset( $this->block['settings']['post-type'] ) && !empty( $this->block['settings']['post-type'] ) ) {
			return \PadmaQuery::get_categories( $this->block['settings']['post-type'] );
		} else {
			// Falls kein Post Type gesetzt ist, verwende 'post' als Default
			return \PadmaQuery::get_categories( array('post') );
		}
	}

	/**
	 * Get Tags
	 *
	 * @return array
	 */
	public function get_tags() {
		return \PadmaQuery::get_tags();
	}

	/**
	 * Get Authors
	 *
	 * @return array
	 */
	public function get_authors() {
		return \PadmaQuery::get_authors();
	}

	/**
	 * Get Post types
	 *
	 * @return array
	 */
	public function get_post_types() {
		return \PadmaQuery::get_post_types();
	}

	/**
	 * Get taxonomies
	 *
	 * @return array
	 */
	public function get_taxonomies() {
		return \PadmaQuery::get_taxonomies();
	}

	/**
	 * Get posts status
	 *
	 * @return array
	 */
	public function get_post_status() {
		return \PadmaQuery::get_post_status();
	}
}
