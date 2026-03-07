<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockShortcodeBlock extends \PadmaBlockAPI {

    public $id 				= 'shortcode-block';    
    public $name 			= 'Shortcode Generator';
	public $options_class 	= 'Padma_Advanced\\PadmaVisualElementsBlockShortcodeBlockOptions';
	public $categories 		= array('content','forms');
    
			
	function setup_elements() {
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-button',
			'name' => 'WooCommerce Button',
			'selector' => 'a.button.add_to_cart_button.product_type_simple',
			'states' => array(
				'Hover' => 'a.button.add_to_cart_button.product_type_simple:hover',
				'Clicked' => 'a.button.add_to_cart_button.product_type_simple:active'
			)
		));

		$this->register_block_element(array(
    		'id' => '.woocommerce-onsale',
			'name' => 'WooCommerce OnSale',
			'selector' => '.woocommerce span.onsale',
			'states' => array(
				'Hover' => '.woocommerce span.onsale:hover',
				'Clicked' => '.woocommerce span.onsale:active'
			)
		));

		$this->register_block_element(array(
    		'id' => '.woocommerce-image',
			'name' => 'WooCommerce Image',
			'selector' => 'img.attachment-shop_catalog.wp-post-image',
			'states' => array(
				'Hover' => 'img.attachment-shop_catalog.wp-post-image:hover',
				'Clicked' => 'img.attachment-shop_catalog.wp-post-image:active'
			)
		));

		$this->register_block_element(array(
    		'id' => '.woocommerce-product-title',
			'name' => 'WooCommerce Product Title',
			'selector' => '.woocommerce ul.products li.product h3',
			'states' => array(
				'Hover' => '.woocommerce ul.products li.product h3:hover',
				'Clicked' => '.woocommerce ul.products li.product h3:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-stars',
			'name' => 'WooCommerce Star Rating',
			'selector' => '.woocommerce .star-rating',
			'states' => array(
				'Hover' => '.woocommerce .star-rating:hover',
				'Clicked' => '.woocommerce .star-rating:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-ammount',
			'name' => 'WooCommerce Amount',
			'selector' => '.woocommerce span.amount',
			'states' => array(
				'Hover' => '.woocommerce span.amount:hover',
				'Clicked' => '.woocommerce span.amount:active'
			)
		));		
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-price',
			'name' => 'WooCommerce Price',
			'selector' => '.woocommerce span.price',
			'states' => array(
				'Hover' => '.woocommerce span.price:hover',
				'Clicked' => '.woocommerce span.price:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-title',
			'name' => 'WooCommerce Title',
			'selector' => '.woocommerce div.product .product_title',
			'states' => array(
				'Hover' => '.woocommerce div.product .product_title:hover',
				'Clicked' => '.woocommerce div.product .product_title:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-description',
			'name' => 'WooCommerce Description',
			'selector' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]',
			'states' => array(
				'Hover' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]:hover',
				'Clicked' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-quantity',
			'name' => 'WooCommerce Quantity',
			'selector' => '.woocommerce .quantity .qty',
			'states' => array(
				'Hover' => '.woocommerce .quantity .qty:hover',
				'Clicked' => '.woocommerce .quantity .qty:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-cart-button',
			'name' => 'WooCommerce Cart Button',
			'selector' => '.woocommerce div.product form.cart .button',
			'states' => array(
				'Hover' => '.woocommerce div.product form.cart .button:hover',
				'Clicked' => '.woocommerce div.product form.cart .button:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-category',
			'name' => 'WooCommerce Category',
			'selector' => '.woocommerce span.posted_in',
			'states' => array(
				'Hover' => '.woocommerce span.posted_in:hover',
				'Clicked' => '.woocommerce span.posted_in:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-hyperlink',
			'name' => 'WooCommerce Link',
			'selector' => '.woocommerce a',
			'states' => array(
				'Hover' => '.woocommerce a:hover',
				'Clicked' => '.woocommerce a:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-paragraph',
			'name' => 'WooCommerce Body',
			'selector' => '.woocommerce p',
			'states' => array(
				'Hover' => '.woocommerce p:hover',
				'Clicked' => '.woocommerce p:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-active-tab',
			'name' => 'WooCommerce Active Tab',
			'selector' => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			'states' => array(
				'Hover' => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active:hover',
				'Clicked' => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-tab',
			'name' => 'WooCommerce Tabs',
			'selector' => '.woocommerce div.product .woocommerce-tabs ul.tabs li',
			'states' => array(
				'Hover' => '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover',
				'Clicked' => '.woocommerce div.product .woocommerce-tabs ul.tabs li:active'
			)
		));
		
        $this->register_block_element(array(
        	'id' => '.woocommerce-add-to-cart',
			'name' => 'WooCommerce Cart',
			'selector' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
			'states' => array(
				'Hover' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt:hover',
				'Clicked' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => 'form-headings',
			'name' => 'ContactForm7 Headings',
			'description' => 'ContactForm7 Headings',
			'selector' => '.wpcf7 p',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
        
    	$this->register_block_element(array(
			'id' => 'form-text',
			'name' => 'ContactForm7 Text Field',
			'description' => 'ContactForm7 Text Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-text',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-email',
			'name' => 'ContactForm7 Email Field',
			'description' => 'ContactForm7 Email Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-text.wpcf7-email.wpcf7-validates-as-email',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-url',
			'name' => 'ContactForm7 URL Field',
			'description' => 'ContactForm7 URL Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-text.wpcf7-url.wpcf7-validates-as-url',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-number',
			'name' => 'ContactForm7 Number Field',
			'description' => 'ContactForm7 Number Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-number.wpcf7-validates-as-number',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-range',
			'name' => 'ContactForm7 Range Field',
			'description' => 'ContactForm7 Range Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-range.wpcf7-validates-as-number',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-date',
			'name' => 'ContactForm7 Date Field',
			'description' => 'ContactForm7 Date Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-date.wpcf7-validates-as-date',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-textarea',
			'name' => 'ContactForm7 TextArea Field',
			'description' => 'ContactForm7 TextArea Field',
			'selector' => 'textarea.wpcf7-form-control.wpcf7-textarea',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-select',
			'name' => 'ContactForm7 Select Field',
			'description' => 'ContactForm7 Select Field',
			'selector' => 'select.wpcf7-form-control.wpcf7-select',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-radio',
			'name' => 'ContactForm7 Radio Field',
			'description' => 'ContactForm7 Radio Field',
			'selector' => 'span.wpcf7-form-control.wpcf7-radio',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-acceptance',
			'name' => 'ContactForm7 Acceptance Field',
			'description' => 'ContactForm7 Acceptance Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-acceptance',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-quiz-label',
			'name' => 'ContactForm7 Quiz Label',
			'description' => 'ContactForm7 Quiz Label',
			'selector' => 'span.wpcf7-quiz-label',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-quiz-field',
			'name' => 'ContactForm7 Quiz Field',
			'description' => 'ContactForm7 Quiz Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-quiz',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-captcha',
			'name' => 'ContactForm7 Captcha Field',
			'description' => 'ContactForm7 Captcha Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-captchar',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-file',
			'name' => 'ContactForm7 File Field',
			'description' => 'ContactForm7 File Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-file',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'form-submit',
			'name' => 'ContactForm7 Submit Field',
			'description' => 'ContactForm7 Submit Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-submit',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));

		$this->register_block_element(array(
		   'id' => 'gform_title',
		   'name' => 'GForm Title',
		   'selector' => '.gform_title',
		   'states' => array(
		   'Hover' => '.gform_title:hover',
		   'Clicked' => '.gform_title:selected'
		   )
		));

		$this->register_block_element(array(
			'id' => 'gfield_label',
			'name' => 'GForm Label',
			'selector' => '.gfield_label',
			'states' => array(
			'Hover' => '.gfield_label:hover',
			'Clicked' => '.gfield_label:selected'
			)
		));

		$this->register_block_element(array(
		   'id' => 'gfield_sub_label',
		   'name' => 'GForm Sub-Label',
		   'selector' => '.ginput_complex label, .gfield_time_hour label, .gfield_time_minute label, .gfield_time_ampm label, .gfield_date_month label, .gfield_date_day label, .gfield_date_year label, .instruction',
		   'states' => array(
		   'Hover' => '.ginput_complex label, .gfield_time_hour label, .gfield_time_minute label, .gfield_time_ampm label, .gfield_date_month label, .gfield_date_day label, .gfield_date_year label, .instruction:hover',
		   'Clicked' => '.ginput_complex label, .gfield_time_hour label, .gfield_time_minute label, .gfield_time_ampm label, .gfield_date_month label, .gfield_date_day label, .gfield_date_year label, .instruction:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_input',
		   'name' => 'GForm Input',
		   'selector' => '.ginput_complex input[type=text], .ginput_complex input[type=url], .ginput_complex input[type=email], .ginput_complex input[type=tel], .ginput_complex input[type=number], .ginput_complex input[type=password]',
		   'states' => array(
		   'Hover' => '.ginput_complex input[type=text], .ginput_complex input[type=url], .ginput_complex input[type=email], .ginput_complex input[type=tel], .ginput_complex input[type=number], .ginput_complex input[type=password]:hover',
		   'Clicked' => '.ginput_complex input[type=text], .ginput_complex input[type=url], .ginput_complex input[type=email], .ginput_complex input[type=tel], .ginput_complex input[type=number], .ginput_complex input[type=password]:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_input_med',
		   'name' => 'GForm Input Med',
		   'selector' => '.top_label input.medium, .top_label select.medium',
		   'states' => array(
		   'Hover' => '.top_label input.medium, .top_label select.medium:hover',
		   'Clicked' => '.top_label input.medium, .top_label select.medium:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_text_area',
		   'name' => 'GForm Text Area',
		   'selector' => 'textarea.medium',
		   'states' => array(
		   'Hover' => 'textarea.medium:hover',
		   'Clicked' => 'textarea.medium:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_submit',
		   'name' => 'GForm Submit',
		   'selector' => '.gform_footer input.button, .gform_footer input[type=submit], .gform_footer input[type=image],.gform_page_footer .button.gform_next_button, .gform_page_footer .button.gform_button',
		   'states' => array(
		   'Hover' => '.gform_footer input.button, .gform_footer input[type=submit], .gform_footer input[type=image],.gform_page_footer .button.gform_next_button, .gform_page_footer .button.gform_button:hover',
		   'Clicked' => '.gform_footer input.button, .gform_footer input[type=submit], .gform_footer input[type=image],.gform_page_footer .button.gform_next_button, .gform_page_footer .button.gform_button:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_progress_bar_title',
		   'name' => 'GForm Progress Bar Title',
		   'selector' => 'h3.gf_progressbar_title',
		   'states' => array(
		   'Hover' => 'h3.gf_progressbar_title:hover',
		   'Clicked' => 'h3.gf_progressbar_title:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_progress_bar_colour',
		   'name' => 'GForm Progress Bar Colour',
		   'selector' => '.percentbar_blue',
		   'states' => array(
		   'Hover' => '.percentbar_blue:hover',
		   'Clicked' => '.percentbar_blue:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_progress_bar',
		   'name' => 'GForm Progress Bar',
		   'selector' => '.gf_progressbar',
		   'states' => array(
		   'Hover' => '.gf_progressbar:hover',
		   'Clicked' => '.gf_progressbar:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_page_title',
		   'name' => 'GForm Page Title',
		   'selector' => '.gsection .gfield_label, h2.gsection_title, h3.gform_title',
		   'states' => array(
		   'Hover' => '.gsection .gfield_label, h2.gsection_title, h3.gform_title:hover',
		   'Clicked' => '.gsection .gfield_label, h2.gsection_title, h3.gform_title:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_checkbox',
		   'name' => 'GForm Checkbox',
		   'selector' => '.gfield_checkbox li input[type=checkbox],  .gfield_checkbox li input',
		   'states' => array(
		   'Hover' => '.gfield_checkbox li input[type=checkbox],  .gfield_checkbox li input:hover',
		   'Clicked' => '.gfield_checkbox li input[type=checkbox],  .gfield_checkbox li input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_checkbox_label',
		   'name' => 'GForm Checkbox Label',
		   'selector' => '.gfield_checkbox li label',
		   'states' => array(
		   'Hover' => '.gfield_checkbox li label:hover',
		   'Clicked' => '.gfield_checkbox li label:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_radio',
		   'name' => 'GForm Radio',
		   'selector' => '.gfield_radio li input[type=radio],  .gfield_radio li input',
		   'states' => array(
		   'Hover' => '.gfield_radio li input[type=radio],  .gfield_radio li input:hover',
		   'Clicked' => '.gfield_radio li input[type=radio],  .gfield_radio li input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_radio_label',
		   'name' => 'GForm Radio Label',
		   'selector' => '.gfield_radio li label',
		   'states' => array(
		   'Hover' => '.gfield_radio li label:hover',
		   'Clicked' => '.gfield_radio li label:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_form_description',
		   'name' => 'GForm Form Description',
		   'selector' => '.gform_description',
		   'states' => array(
		   'Hover' => '.gform_description:hover',
		   'Clicked' => '.gform_description:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_section',
		   'name' => 'GForm Form Section',
		   'selector' => '.gsection',
		   'states' => array(
		   'Hover' => '.gsection:hover',
		   'Clicked' => '.gsection:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_html',
		   'name' => 'GForm HTML Section',
		   'selector' => '.gfield_html',
		   'states' => array(
		   'Hover' => '.gfield_html:hover',
		   'Clicked' => '.gfield_html:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_upload',
		   'name' => 'GForm Upload',
		   'selector' => '.gfield_list td.gfield_list_cell input',
		   'states' => array(
		   'Hover' => '.gfield_list td.gfield_list_cell input:hover',
		   'Clicked' => '.gfield_list td.gfield_list_cell input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_upload_icon',
		   'name' => 'GForm Upload Icon',
		   'selector' => 'img.add_list_item',
		   'states' => array(
		   'Hover' => 'img.add_list_item:hover',
		   'Clicked' => 'img.add_list_item:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_time',
		   'name' => 'GForm Time',
		   'selector' => '.gfield_time_hour input, .gfield_time_minute input, .gfield_date_month input, .gfield_date_day input, .gfield_date_year input',
		   'states' => array(
		   'Hover' => '.gfield_time_hour input, .gfield_time_minute input, .gfield_date_month input, .gfield_date_day input, .gfield_date_year input:hover',
		   'Clicked' => '.gfield_time_hour input, .gfield_time_minute input, .gfield_date_month input, .gfield_date_day input, .gfield_date_year input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_time_amppm',
		   'name' => 'GForm Time AM/PM Selector',
		   'selector' => '.gfield_time_ampm ',
		   'states' => array(
		   'Hover' => '.gfield_time_ampm :hover',
		   'Clicked' => '.gfield_time_ampm :selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_header',
		   'name' => 'Price Table Header',
		   'selector' => '.rpt_title',
		   'states' => array(
		   'Hover' => '.rpt_title:hover',
		   'Clicked' => '.rpt_title:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_recurrence',
		   'name' => 'Price Table Recurrence',
		   'selector' => '.rpt_recurrence',
		   'states' => array(
		   'Hover' => '.rpt_recurrence:hover',
		   'Clicked' => '.rpt_recurrence:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_currency',
		   'name' => 'Price Table Currency',
		   'selector' => '.rpt_currency',
		   'states' => array(
		   'Hover' => '.rpt_currency:hover',
		   'Clicked' => '.rpt_currency:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_price',
		   'name' => 'Price Table Price',
		   'selector' => '.rpt_price',
		   'states' => array(
		   'Hover' => '.rpt_price:hover',
		   'Clicked' => '.rpt_price:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_subtitle',
		   'name' => 'Price Table Subtitle',
		   'selector' => '.rpt_subtitle',
		   'states' => array(
		   'Hover' => '.rpt_subtitle:hover',
		   'Clicked' => '.rpt_subtitle:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_desc',
		   'name' => 'Price Table Description',
		   'selector' => '.rpt_description',
		   'states' => array(
		   'Hover' => '.rpt_description:hover',
		   'Clicked' => '.rpt_description:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_feature',
		   'name' => 'Price Table Feature',
		   'selector' => '.rpt_feature ',
		   'states' => array(
		   'Hover' => '.rpt_feature :hover',
		   'Clicked' => '.rpt_feature :selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_feature_bg',
		   'name' => 'Price Table Feature Background',
		   'selector' => '.rpt_features ',
		   'states' => array(
		   'Hover' => '.rpt_features :hover',
		   'Clicked' => '.rpt_features :selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_button',
		   'name' => 'Price Table Button',
		   'selector' => '.rpt_foot',
		   'states' => array(
		   'Hover' => '.rpt_foot:hover',
		   'Clicked' => '.rpt_foot:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_price_bg',
		   'name' => 'Price Table Price Background',
		   'selector' => '.rpt_head',
		   'states' => array(
		   'Hover' => '.rpt_head:hover',
		   'Clicked' => '.rpt_head:selected'
		   )
		));    

		$this->register_block_element(array(
		   'id' => 'price_table_recommended_img',
		   'name' => 'Price Table Recommended Image',
		   'selector' => 'img.rpt_recommended',
		   'states' => array(
		   'Hover' => 'img.rpt_recommended:hover',
		   'Clicked' => 'img.rpt_recommended:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_recommended_icon',
		   'name' => 'Price Table Recommended Icon',
		   'selector' => '.rpt_icon',
		   'states' => array(
		   'Hover' => '.rpt_icon:hover',
		   'Clicked' => '.rpt_icon:selected'
		   )
		)); 

		// E-Newsletter Elemente
		$this->register_block_element(array(
			'id' => 'enewsletter-widget-container',
			'name' => 'Newsletter Widget Container',
			'selector' => '.e-newsletter-widget',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-message',
			'name' => 'Newsletter Nachricht',
			'selector' => '.e-newsletter-widget #message',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-form',
			'name' => 'Newsletter Formular',
			'selector' => '.e-newsletter-widget form',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-screen',
			'name' => 'Newsletter Screen',
			'selector' => '.e-newsletter-widget .e-newsletter-widget-screen',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-label',
			'name' => 'Newsletter Label',
			'selector' => '.e-newsletter-widget label',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-input-text',
			'name' => 'Newsletter Eingabefeld',
			'selector' => '.e-newsletter-widget input[type="text"]',
			'states' => array(
				'Focus' => '.e-newsletter-widget input[type="text"]:focus',
			)
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-email-input',
			'name' => 'Newsletter Email-Feld',
			'selector' => '.e-newsletter-widget #e_newsletter_email',
			'states' => array(
				'Focus' => '.e-newsletter-widget #e_newsletter_email:focus',
			)
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-name-input',
			'name' => 'Newsletter Name-Feld',
			'selector' => '.e-newsletter-widget #e_newsletter_name',
			'states' => array(
				'Focus' => '.e-newsletter-widget #e_newsletter_name:focus',
			)
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-groups-heading',
			'name' => 'Newsletter Gruppen Überschrift',
			'selector' => '.e-newsletter-widget h3',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-groups-list',
			'name' => 'Newsletter Gruppen Liste',
			'selector' => '.e-newsletter-widget .subscribe_groups',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-groups-list-item',
			'name' => 'Newsletter Gruppen Listeneintrag',
			'selector' => '.e-newsletter-widget .subscribe_groups li',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-checkbox',
			'name' => 'Newsletter Checkbox',
			'selector' => '.e-newsletter-widget input[type="checkbox"]',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-checkbox-label',
			'name' => 'Newsletter Checkbox Label',
			'selector' => '.e-newsletter-widget .subscribe_groups label',
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-button',
			'name' => 'Newsletter Button',
			'selector' => '.e-newsletter-widget input[type="submit"]',
			'states' => array(
				'Hover' => '.e-newsletter-widget input[type="submit"]:hover',
				'Active' => '.e-newsletter-widget input[type="submit"]:active',
			)
		));

		$this->register_block_element(array(
			'id' => 'enewsletter-link',
			'name' => 'Newsletter Links',
			'selector' => '.e-newsletter-widget a',
			'states' => array(
				'Hover' => '.e-newsletter-widget a:hover',
			)
		));
	}

	function content($block) {

		$shortcodeproduct = parent::get_setting($block, 'shortcode-product-type', 'none');

		if ($shortcodeproduct === 'marketpress') {
			$mp_shortcode  = parent::get_setting($block, 'mp-shortcode-type', 'none');
			$mp_number     = (int) parent::get_setting($block, 'mp-number', 5);
			$mp_product_id = (int) parent::get_setting($block, 'mp-product-id', 0);

			if ( !function_exists('mp_get_setting') ) {
				echo '<p>MarketPress ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $mp_shortcode === 'none' || empty($mp_shortcode) ) {
				echo '<p>Bitte MarketPress Shortcode auswählen.</p>';
				return;
			}

			if ( $mp_shortcode === 'mp_featured_products' || $mp_shortcode === 'mp_popular_products' ) {
				echo do_shortcode( '[' . $mp_shortcode . ' number="' . max(1, $mp_number) . '"]' );
				return;
			}

			if ( $mp_shortcode === 'mp_product' ) {
				if ( $mp_product_id <= 0 ) {
					echo '<p>Bitte eine gültige Produkt-ID für MarketPress eingeben.</p>';
					return;
				}

				echo do_shortcode( '[mp_product product_id="' . $mp_product_id . '"]' );
				return;
			}

			echo do_shortcode( '[' . $mp_shortcode . ']' );
			return;
		}

		if ($shortcodeproduct === 'powerform') {
			$powerform_id = (int) parent::get_setting($block, 'powerform-shortcode', 0);

			if ( !post_type_exists('powerform_forms') ) {
				echo '<p>PowerForm ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $powerform_id <= 0 ) {
				echo '<p>Bitte ein PowerForm-Formular auswählen.</p>';
				return;
			}

			echo do_shortcode( '[powerform_form id="' . $powerform_id . '"]' );
			return;
		}

		if ($shortcodeproduct === 'community') {
			$community_shortcode = parent::get_setting($block, 'community-shortcode-type', 'none');
			$community_group_id  = (int) parent::get_setting($block, 'community-group-id', 0);

			if ( !post_type_exists('cpc_group') ) {
				echo '<p>PS Community ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $community_shortcode === 'none' || empty($community_shortcode) ) {
				echo '<p>Bitte einen PS Community Shortcode auswählen.</p>';
				return;
			}

			$requires_group = in_array($community_shortcode, array(
				'cpc-group-single',
				'cpc-group-members',
				'cpc-group-join-button',
				'cpc-group-leave-button',
			), true);

			if ( $requires_group ) {
				if ( $community_group_id <= 0 ) {
					echo '<p>Bitte eine Gruppe auswählen.</p>';
					return;
				}

				echo do_shortcode( '[' . $community_shortcode . ' group_id="' . $community_group_id . '"]' );
				return;
			}

			echo do_shortcode( '[' . $community_shortcode . ']' );
			return;
		}

		if ($shortcodeproduct === 'e-newsletter') {
			$enewsletter_shortcode_type = parent::get_setting($block, 'enewsletter-shortcode-type', 'none');

			if ( !class_exists('Email_Newsletter') ) {
				echo '<p>E-Newsletter ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $enewsletter_shortcode_type === 'none' || empty($enewsletter_shortcode_type) ) {
				echo '<p>Bitte einen E-Newsletter Shortcode auswählen.</p>';
				return;
			}

			// Subscribe-Message Shortcode
			if ( $enewsletter_shortcode_type === 'subscribe-message' ) {
				echo do_shortcode( '[enewsletter_subscribe_message]' );
				return;
			}

			// Unsubscribe-Message Shortcode
			if ( $enewsletter_shortcode_type === 'unsubscribe-message' ) {
				echo do_shortcode( '[enewsletter_unsubscribe_message]' );
				return;
			}

			// Subscribe-Formular mit Optionen
			if ( $enewsletter_shortcode_type === 'subscribe' ) {
				$show_name = parent::get_setting($block, 'enewsletter-show-name', false);
				$show_groups = parent::get_setting($block, 'enewsletter-show-groups', true);
				$subscribe_to_groups = parent::get_setting($block, 'enewsletter-subscribe-to-groups', array());

				// Konvertiere Array zu komma-separiertem String
				$groups_string = '';
				if ( is_array($subscribe_to_groups) && count($subscribe_to_groups) > 0 ) {
					$groups_string = implode(',', $subscribe_to_groups);
				}

				// Baue Shortcode-Attribute
				$atts = array();
				$atts[] = 'show_name="' . ($show_name ? 'true' : 'false') . '"';
				$atts[] = 'show_groups="' . ($show_groups ? 'true' : 'false') . '"';
				
				if ( !empty($groups_string) ) {
					$atts[] = 'subscribe_to_groups="' . esc_attr($groups_string) . '"';
				}

				$shortcode = '[enewsletter_subscribe ' . implode(' ', $atts) . ']';
				echo do_shortcode( $shortcode );
				return;
			}
		}

		echo '<p>Bitte Plugin und Shortcode auswählen.</p>';
	}
	
}