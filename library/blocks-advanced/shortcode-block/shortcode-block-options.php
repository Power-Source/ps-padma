<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockShortcodeBlockOptions extends \PadmaBlockOptionsAPI {

	public $tabs = array(
		'content-tab' 	=> 'Content',
		'woo-tab' 		=> 'WooCommerce',
		'cf7-tab' 		=> 'Contact Form 7',
		'gravity-tab' 	=> 'Gravity Forms',
		'price-tab' 	=> 'Pricing Table',
	);


	public $inputs = array(

		'content-tab' => array(
			'shortcode-product-type' => array(
				'type' => 'select',
				'name' => 'shortcode-product-type',
				'label' => 'Which Product',
				'options' => array(
					'none' 		=> 'Choose your product',
					'woo' 		=> 'WooCommerce',
					'cf7' 		=> 'Contact Form 7',
					'gravity' 	=> 'Gravity Forms',
					'price' 	=> 'Price Tables'
				),
			'toggle' => array(
				'none' => array(
					'show' => array (),
					'hide' => array(
						'#sub-tab-woo-tab',
						'#sub-tab-cf7-tab',
						'#sub-tab-gravity-tab',
						'#sub-tab-price-tab',
					),

				),
				'price' => array(
					'hide' => array (
						'#sub-tab-cf7-tab',
						'#sub-tab-gravity-tab',
						'#sub-tab-woo-tab',
					),
					'show' => array(
						'#sub-tab-price-tab',
					),
				),
				'woo' => array(
					'hide' => array (
						'#sub-tab-price-tab',
						'#sub-tab-cf7-tab',
						'#sub-tab-gravity-tab',
					),
					'show' => array(
						'#sub-tab-woo-tab',
					),
				),
				'cf7' => array(
					'show' => array (
						'#sub-tab-cf7-tab',
					),
					'hide' => array(
						'#sub-tab-woo-tab',
						'#sub-tab-gravity-tab',
						'#sub-tab-price-tab',
					),
				),
				'gravity' => array(
					'show' => array (
						'#sub-tab-gravity-tab',
					),
					'hide' => array(
						'#sub-tab-woo-tab',
						'#sub-tab-cf7-tab',
						'#sub-tab-price-tab',
					),
				),
			),
			'default' => '',
			'tooltip' => '',
		),
		),
		'woo-tab' => array(
			'wc-shortcode-type' => array(
				'type' => 'select',
				'name' => 'wc-shortcode-type',
				'label' => 'WooCommerce Content',
				'options' => array(
					'' => 'Choose your content',
					'recent_products' => 'Recent Products',
					'featured_products' => 'Featured Products',
					'product_category' => 'Product Category',
					'sale_products' => 'Sale Products',
					'best_selling_products' => 'Best Selling Products',
					'top_rated_products' => 'Top Rated Products'
				),
				'toggle' => array(
					'none' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'product_category' => array(
						'show' => '#sub-tab-woo-tab-content #input-wc-category',
						'hide' => array(),
					),
					'recent_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'featured_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'sale_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'best_selling_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'top_rated_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
				),
				'default' => '',
				'tooltip' => 'Choose your WooCommerce content to display',
			),
			'wc-category' => array(
				'type' => 'select',
				'name' => 'wc-category',
				'label' => 'Category Slug',
				'default' => '',
				'tooltip' => 'Choose your category to display.<br />Tip: You can find the category slug in the WooCommerce Category Admin panel'
			),
			'wc-product-count' => array(
				'type' => 'integer',
				'name' => 'wc-product-count',
				'label' => 'Product Qty',
				'default' => '12',
				'tooltip' => 'Choose how many products to display per page.'
			),
			'wc-column-count' => array(
				'type' => 'integer',
				'name' => 'wc-column-count',
				'label' => 'Columns',
				'default' => '4',
				'tooltip' => 'Choose how many columns.'
			),
			'wc-order-by' => array (
				'type' => 'select',
				'name' => 'wc-order-by',
				'label' => 'Order by',
				'default' => 'menu_order',
				'options' => array (
					'menu-order' => 'Menu Order',
					'title' => 'Title',
					'date' => 'Date',
					'rand' => 'Random',
					'id' => 'ID'
				),
			),
			'wc-order' => array (
				'type' => 'select',
				'name' => 'wc-order',
				'label' => 'Order',
				'default' => 'asc',
				'options' => array (
					'asc' => 'Ascending',
					'desc' => 'Descending'
				),
			),
		),
		'cf7-tab' => array(
			'contactform7-shortcode' => array(
				'type' => 'select',
				'name' => 'contactform7-shortcode',
				'label' => 'Form Name',
				'default' => ' ',
				'tooltip' => 'Choose the name of your form',
			),
		),
		'gravity-tab' => array(
			'gravityform-shortcode' => array(
				'type' => 'select',
				'name' => 'gravityform-shortcode',
				'label' => 'Form Name',
				'default' => ' ',
				'tooltip' => 'Choose the name of your form',
			),
			'gravityform-title' => array(
				'type' => 'checkbox',
				'name' => 'gravityform-title',
				'label' => 'Display Form Title',
				'default' => True,
				'tooltip' => 'Choose to have your forms title display.'
			),
			'gravityform-description' => array(
				'type' => 'checkbox',
				'name' => 'gravityform-description',
				'label' => 'Display Form Description',
				'default' => True,
				'tooltip' => 'Choose to have your forms description display.'
			),
			'gravityform-ajax' => array(
				'type' => 'checkbox',
				'name' => 'gravityform-ajax',
				'label' => 'Use Ajax for Form Submissions?',
				'default' => false,
				'tooltip' => 'Choose to have your forms submitted by Ajax.'
			),
		),
		'price-tab' => array(
			'price-heading' => array(
				'type' => 'heading',
				'name' => 'price-heading',
				'label' => 'You can download Responsive Price Table from the <a href="https://wordpress.org/plugins/dk-pricr-responsive-pricing-table/" target="_blank">WordPress repository</a>',
			),
			'price-shortcode' => array(
				'type' => 'select',
				'name' => 'price-shortcode',
				'label' => 'Pricing Tables',
				'default' => ' ',
				'tooltip' => 'Choose the name of your tables',
			),
		),
	);
	
	public function modify_arguments($args = false){
		
		$wcatTerms 	= get_terms('product_cat',array('hide_empty'=>false));
		$options 	= array( 'none' => 'Choose Your Category');
		
		foreach($wcatTerms as $wcatTerm) {
			$options[$wcatTerm->slug] = $wcatTerm->name;
		}
		
		$this->inputs['woo-tab']['wc-category']['options'] = $options;

		if (class_exists('WPCF7_ContactForm')) {
			
			$forms 	= WPCF7_ContactForm::find();
			$options = array( 'none' => 'Choose Your Form');

			foreach ( $forms as $form ) {
				$options[$form->title] = $form->title;
			}
			
			$this->inputs['cf7-tab']['contactform7-shortcode']['options'] = $options;
		
		}else{
			$this->inputs['cf7-tab']['contactform7-shortcode']['options'] = array('none'=>'Contact Form 7 is not installed');
		}

		if (class_exists('GFAPI')) {

			$forms 	= RGFormsModel::get_forms();
			$options = array('none' => 'Choose Your Form');
		
			foreach ( $forms as $form ) {
				$options[$form->id] = $form->title;
			}

			$this->inputs['gravity-tab']['gravityform-shortcode']['options'] = $options;
		
		} else {
			$this->inputs['gravity-tab']['gravityform-shortcode']['options'] = array('none'=>'Gravity Forms is not installed');
		}

		if (function_exists('create_rpt_pricing_table_type')) {
			
			$args 		= array( 'post_type' => 'rpt_pricing_table', 'posts_per_page' => -1 );
			$myposts 	= get_posts( $args );
			$options 	= array('none' => 'Choose Your Price Table');

			foreach ( $myposts as $post ) { 
				$options[$post->post_name] = $post->post_title;
			}
			
			$this->inputs['price-tab']['price-shortcode']['options'] = $options;

		}else{
			$this->inputs['price-tab']['price-shortcode']['options'] = array('none'=>'Responsive Price Table is not installed');
		}
	}
}