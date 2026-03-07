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
class PadmaVisualElementsBlockPortfolioOptions extends \PadmaBlockOptionsAPI {

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
			'general'       => __( 'General', 'padma' ),
			'query-filters' => __( 'Query Filters', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(

			'general'       => array(

				'columns'                    => array(
					'type'       => 'slider',
					'name'       => 'columns',
					'label'      => __( 'Columns', 'padma' ),
					'tooltip'    => __( 'Amount of portfolio columns . ', 'padma' ),
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

				'show-filter'                => array(
					'name'    => 'show-filter',
					'label'   => __( 'Show filter', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Show filter', 'padma' ),
					'toggle'  => array(
						'yes' => array(
							'show' => array(
								'#input-filter-style',
								'#input-show-all-text',
							),
						),
						'no'  => array(
							'hide' => array(
								'#input-filter-style',
								'#input-show-all-text',
							),
						),
					),
				),

				'only-categories-with-posts' => array(
					'name'    => 'only-categories-with-posts',
					'label'   => __( 'Only show categories with posts', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Select filter style', 'padma' ),
				),

				'filter-style'               => array(
					'name'    => 'filter-style',
					'label'   => __( 'Filter style', 'padma' ),
					'type'    => 'select',
					'default' => 'style-1',
					'options' => array(
						'style-1' => 'Style 1',
						'style-2' => 'Style 2',
						'style-3' => 'Style 3',
						'style-4' => 'Style 4',
					),
					'tooltip' => __( 'Select filter style', 'padma' ),
				),

				'show-all-text'              => array(
					'name'    => 'show-all-text',
					'label'   => __( 'Show All text', 'padma' ),
					'type'    => 'text',
					'default' => 'Show All',
					'tooltip' => __( 'Default text for "Show all" button', 'padma' ),
				),

				'show-margin'                => array(
					'name'    => 'show-margin',
					'label'   => __( 'Show margin', 'padma' ),
					'type'    => 'select',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Show margin', 'padma' ),
				),

				'alternate-content'          => array(
					'name'    => 'alternate-content',
					'label'   => __( 'Alternate content and image', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Alternate content and image', 'padma' ),
				),

				'full-width-image'           => array(
					'name'    => 'full-width-image',
					'label'   => __( 'Show full width image', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Show full width image', 'padma' ),
				),

				'title-overlay'              => array(
					'name'    => 'title-overlay',
					'label'   => __( 'Title overlay', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Show title over the image', 'padma' ),
				),

				'show-open-button'           => array(
					'name'    => 'show-open-button',
					'label'   => __( 'Show open button', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Show open button', 'padma' ),
				),

				'open-button-text'           => array(
					'name'    => 'open-button-text',
					'label'   => __( 'Open button text', 'padma' ),
					'type'    => 'text',
					'default' => 'Open article',
					'tooltip' => __( 'Default text for open article button', 'padma' ),
				),
			),

			'query-filters' => array(

				'categories'      => array(
					'type'    => 'multi-select',
					'name'    => 'categories',
					'label'   => __( 'Categories', 'padma' ),
					'tooltip' => '',
					'options' => 'get_categories()',
				),

				'categories-mode' => array(
					'type'    => 'select',
					'name'    => 'categories-mode',
					'label'   => __( 'Categories Mode', 'padma' ),
					'tooltip' => '',
					'options' => array(
						'include' => __( 'Include', 'padma' ),
						'exclude' => __( 'Exclude', 'padma' ),
					),
				),

				'enable-tags'     => array(
					'type'    => 'checkbox',
					'name'    => 'tags-filter',
					'label'   => __( 'Tags Filter', 'padma' ),
					'tooltip' => __( 'Check this to allow the tags filter show . ', 'padma' ),
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
					'label'   => __( 'Tags', 'padma' ),
					'tooltip' => '',
					'options' => 'get_tags()',
				),

				'post-type'       => array(
					'type'     => 'multi-select',
					'name'     => 'post-type',
					'label'    => __( 'Post Type', 'padma' ),
					'tooltip'  => '',
					'options'  => 'get_post_types()',
					'callback' => 'reloadBlockOptions()',
				),

				'post-status'     => array(
					'type'    => 'multi-select',
					'name'    => 'post-status',
					'label'   => __( 'Post Status', 'padma' ),
					'tooltip' => '',
					'options' => 'get_post_status()',
				),

				'author'          => array(
					'type'    => 'multi-select',
					'name'    => 'author',
					'label'   => __( 'Author', 'padma' ),
					'tooltip' => '',
					'options' => 'get_authors()',
				),

				'number-of-posts' => array(
					'type'    => 'integer',
					'name'    => 'number-of-posts',
					'label'   => __( 'Number of Posts', 'padma' ),
					'tooltip' => '',
					'default' => 10,
				),

				'offset'          => array(
					'type'    => 'integer',
					'name'    => 'offset',
					'label'   => __( 'Offset', 'padma' ),
					'tooltip' => __( 'The offset is the number of entries or posts you would like to skip.  If the offset is 1, then the first post will be skipped . ', 'padma' ),
					'default' => 0,
				),

				'order-by'        => array(
					'type'    => 'select',
					'name'    => 'order-by',
					'label'   => __( 'Order By', 'padma' ),
					'tooltip' => __( 'Order By', 'padma' ),
					'options' => array(
						'date'          => __( 'Date', 'padma' ),
						'title'         => __( 'Title', 'padma' ),
						'rand'          => __( 'Random', 'padma' ),
						'comment_count' => __( 'Comment Count', 'padma' ),
						'ID'            => __( 'ID', 'padma' ),
						'author'        => __( 'Author', 'padma' ),
						'type'          => __( 'Post Type', 'padma' ),
						'menu_order'    => __( 'Custom Order', 'padma' ),
					),
				),

				'order'           => array(
					'type'    => 'select',
					'name'    => 'order',
					'label'   => __( 'Order', 'padma' ),
					'tooltip' => '',
					'options' => array(
						'desc' => __( 'Descending', 'padma' ),
						'asc'  => __( 'Ascending', 'padma' ),
					),
				),

				'byid-include'    => array(
					'type'    => 'text',
					'name'    => 'byid-include',
					'label'   => __( 'Include by ID', 'padma' ),
					'tooltip' => __( 'In both Include and Exclude by ID, you use a comma separated list of IDs of your post type . ', 'padma' ),
				),

				'byid-exclude'    => array(
					'type'    => 'text',
					'name'    => 'byid-exclude',
					'label'   => __( 'Exclude by ID', 'padma' ),
					'tooltip' => __( 'In both Include and Exclude by ID, you use a comma separated list of IDs of your post type . ', 'padma' ),
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
		if ( isset( $this->block['settings']['post-type'] ) ) {
			return \PadmaQuery::get_categories( $this->block['settings']['post-type'] );
		} else {
			return array();
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
