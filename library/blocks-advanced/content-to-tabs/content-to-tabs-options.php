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
class PadmaVisualElementsBlockContentToTabsOptions extends \PadmaBlockOptionsAPI {

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
				'vertical'    => array(
					'name'    => 'vertical',
					'label'   => __( 'Vertical', 'padma' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'padma' ),
						'no'  => __( 'No', 'padma' ),
					),
					'tooltip' => __( 'Align tabs vertically', 'padma' ),
				),

				'tabs-class'  => array(
					'name'    => 'tabs-class',
					'type'    => 'text',
					'label'   => __( 'CSS Class', 'padma' ),
					'tooltip' => __( 'Additional CSS class name(s) separated by space(s)', 'padma' ),
				),

				'style'       => array(
					'name'    => 'style',
					'type'    => 'select',
					'label'   => __( 'Style', 'padma' ),
					'default' => 'default',
					'options' => array(
						'default'       => 'Default',
						'carbon'        => 'Carbon',
						'sharp'         => 'Sharp',
						'grid'          => 'Grid',
						'wood'          => 'Wood',
						'fabric'        => 'Fabric',
						'modern-dark'   => 'Modern: Dark',
						'modern-light'  => 'Modern: Light',
						'modern-blue'   => 'Modern: Blue',
						'modern-orange' => 'Modern: Orange',
						'flat-dark'     => 'Flat: Dark',
						'flat-light'    => 'Flat: Light',
						'flat-blue'     => 'Flat: Blue',
						'flat-green'    => 'Flat: Green',
					),
					'tooltip' => __( 'Choose style for this tabs', 'padma' ),
				),

				'active'      => array(
					'name'    => 'active',
					'type'    => 'integer',
					'label'   => __( 'Active (1-100)', 'padma' ),
					'default' => 1,
					'tooltip' => __( 'Select which tab is open by default', 'padma' ),
				),

				'post-link'   => array(
					'type'    => 'checkbox',
					'name'    => 'url',
					'label'   => __( 'Enable post link', 'padma' ),
					'tooltip' => __( 'Link tab to any webpage. Use full URL to turn the tab title into link', 'padma' ),
					'default' => false,
				),
				'item-target' => array(
					'name'    => 'item-target',
					'type'    => 'select',
					'default' => 'self',
					'options' => array(
						'self'  => __( 'Open in same tab', 'padma' ),
						'blank' => __( 'Open in new tab', 'padma' ),
					),
					'label'   => __( 'Target', 'padma' ),
					'tooltip' => __( 'Choose how to open the custom tab link', 'padma' ),
				),

				'item-class'  => array(
					'name'    => 'item-class',
					'type'    => 'text',
					'label'   => __( 'CSS Class for the items', 'padma' ),
					'tooltip' => __( 'Additional CSS class name(s) separated by space(s)', 'padma' ),
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
	 * Allow developers to modify the properties of the class and use functions since doing a property outside of a function will not allow you to.
	 *
	 * @param boolean $args Args.
	 * @return void
	 */
	public function modify_arguments( $args = false ) {}

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
