<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockPostSliderOptions extends \PadmaBlockOptionsAPI {

	public $tabs = array();
	public $inputs = array();

	function __construct(){

		$this->tabs = array(
			'content-tab' 	=> 'Content'
		);


		$this->inputs = array(

			'content-tab' => array(

				'post-type' => array(
					'type' => 'select',
					'name' => 'post-type',
					'label' => 'Post type',
					'options' => 'get_post_types()',
					'callback' => 'reloadBlockOptions(block.id)',
					'default' => 'post',
					'tooltip' => '',
				),

				'categories' => array(
					'type' => 'multi-select',
					'name' => 'categories',
					'label' => 'Categories',
					'tooltip' => '',
					'options' => 'get_categories()'
				),

				'order-by' => array(
					'type' => 'select',
					'name' => 'order-by',
					'label' => 'Order By',
					'tooltip' => '',
					'options' => array(
						'date' => 'Date',
						'title' => 'Title',
						'rand' => 'Random',
						'comment_count' => 'Comment Count',
						'ID' => 'ID',
						'author' => 'Author',
						'type' => 'Post Type',
						'menu_order' => 'Custom Order'
					)
				),
				
				'order' => array(
					'type' => 'select',
					'name' => 'order',
					'label' => 'Order',
					'tooltip' => '',
					'options' => array(
						'desc' => 'Descending',
						'asc' => 'Ascending',
					)
				),

				'number-of-posts' => array(
					'type' => 'integer',
					'default' => 6,
					'name' => 'number-of-posts',
					'label' => 'Total Items to show',
					'tooltip' => '',				
				),

				'slider-style' => array(
					'type' => 'select',
					'name' => 'slider-style',
					'label' => 'Style',
					'default' => 'style1',
					'options' => array(
						'style1' => 'Style 1',
						'style2' => 'Style 2',
						'style3' => 'Style 3',
						'style4' => 'Style 4',
						'style5' => 'Style 5',
						'style6' => 'Style 6',
					),
					'toggle'    => array(
						'style1' => array(
							'show' => array(
								'#input-content-to-show',
								'#input-custom-length',
								'#input-custom-length-number',
							),
							'hide' => array(
								'#input-read-more-label',
								'#input-focus-effect'
							)
						),
						'style2' => array(
							'show' => array(
								'#input-content-to-show',
								'#input-custom-length',
								'#input-custom-length-number',
							),
							'hide' => array(
								'#input-read-more-label',
								'#input-focus-effect'
							)
						),
						'style3' => array(
							'show' => array(
								'#input-content-to-show',
								'#input-custom-length',
								'#input-custom-length-number',
								'#input-read-more-label',
								'#input-focus-effect'
							),
							'hide' => array(
							)
						),
						'style4' => array(
							'show' => array(
								'#input-content-to-show',
								'#input-custom-length',
								'#input-custom-length-number',
							),
							'hide' => array(
								'#input-focus-effect',
								'#input-read-more-label'
							)
						),
						'style5' => array(
							'show' => array(
								'#input-content-to-show',
								'#input-custom-length',
								'#input-custom-length-number',
								'#input-read-more-label',
								'#input-focus-effect'
							),
							'hide' => array(
							)
						),
						'style6' => array(
							'show' => array(
								'#input-focus-effect'
							),
							'hide' => array(
								'#input-content-to-show',
								'#input-custom-length',
								'#input-custom-length-number',
								'#input-read-more-label',
							)
						),
					)
				),

				'content-to-show' => array(
					'type' => 'select',
					'name' => 'content-to-show',
					'label' => 'Content to show',
					'options' => array(
						'normal' => 'Normal',						
						'excerpts' => __('Show Excerpts','padma-post-slider'),
						'none' => __('Do not show content','padma-post-slider')
					),
					'default' => 'normal',
					'tooltip' => '',
				),

				'custom-length' => array(
					'type' => 'select',
					'name' => 'custom-length',
					'label' => 'Custom length',
					'options' => array(
						'no' => 'No',
						'yes' => 'Yes',
					),
					'default' => 'no',
					'tooltip' => '',
					'toggle'    => array(
						'yes' => array(
							'show' => array(
								'#input-custom-length-number'
							)
						),
						'no' => array(
							'hide' => array(
								'#input-custom-length-number'
							)
						),
					)
				),

				'custom-length-number' => array(
					'name' => 'custom-length-number',
					'type' => 'integer',
					'default' => 15,
					'label' => 'Words to show',
					'tooltip' => '',
				),				

				'auto_play' => array(
					'type' => 'select',
					'name' => 'auto_play',
					'label' => 'Auto play',
					'tooltip' => '',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No',
					)
				),

				'show_items' => array(
					'type' => 'integer',
					'default' => 3,
					'name' => 'show_items',
					'label' => 'Show items',
					'tooltip' => '',				
				),			

				'show_pagination' => array(
					'type' => 'select',		
					'name' => 'show_pagination',
					'label' => 'Show pagination',
					'tooltip' => '',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No',
					)				
				),

				'read-more-label' => array(
					'type' => 'text',		
					'name' => 'read-more-label',
					'label' => 'Read more label',
					'tooltip' => '',
				),

				'focus-effect' => array(
					'type' => 'select',		
					'name' => 'focus-effect',
					'label' => 'Focus effect',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No',
					),
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-focus-effect-color'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-focus-effect-color'
							)
						),
					)
				),

				'focus-effect-color' => array(
					'type' => 'colorpicker',		
					'name' => 'focus-effect-color',
					'label' => 'Focus color',
					'default' => '#3398db',
				),
			),

		);

	}

	function get_categories() {

		if( isset($this->block['settings']['post-type']) )
			return \PadmaQuery::get_categories( $this->block['settings']['post-type'] );
		else
			return \PadmaQuery::get_categories( 'post' );
	}

	function get_tags() {		
		return \PadmaQuery::get_tags();
	}

	function get_post_types() {		
		return \PadmaQuery::get_post_types();
	}

	function get_authors() {		
		return \PadmaQuery::get_authors();	
	}

	function get_taxonomies() {
		return \PadmaQuery::get_taxonomies();
	}

	function get_post_status() {		
		return \PadmaQuery::get_post_status();		
	}
}