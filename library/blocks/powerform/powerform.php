<?php

class PadmaPowerformBlock extends PadmaBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'powerform';	
		$this->name = 'PowerForm';		
		$this->options_class = 'PadmaPowerformBlockOptions';
		$this->description = __('Zeig ein PowerForm-Formular an','padma');
		$this->categories = array('core','forms');

	}

	public function init() {

		if(!post_type_exists('powerform_forms'))
			return false;

	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'powerform-container',
			'name' => __('Formular-Container','padma'),
			'selector' => '.forminator-ui',
		));

		$this->register_block_element(array(
			'id' => 'form-paragraph',
			'name' => __('Formular-Absatz','padma'),
			'selector' => '.forminator-ui p',
		));

		$this->register_block_element(array(
			'id' => 'form-h1',
			'name' => __('Formular H1','padma'),
			'selector' => '.forminator-ui h1',
		));

		$this->register_block_element(array(
			'id' => 'form-h2',
			'name' => __('Formular H2','padma'),
			'selector' => '.forminator-ui h2',
		));

		$this->register_block_element(array(
			'id' => 'form-h3',
			'name' => __('Formular H3','padma'),
			'selector' => '.forminator-ui h3',
		));

		$this->register_block_element(array(
			'id' => 'form-h4',
			'name' => __('Formular H4','padma'),
			'selector' => '.forminator-ui h4',
		));

		$this->register_block_element(array(
			'id' => 'form-h5',
			'name' => __('Formular H5','padma'),
			'selector' => '.forminator-ui h5',
		));

		$this->register_block_element(array(
			'id' => 'form-h6',
			'name' => __('Formular H6','padma'),
			'selector' => '.forminator-ui h6',
		));

		$this->register_block_element(array(
			'id' => 'form-label',
			'name' => __('Formular-Label','padma'),
			'selector' => '.forminator-ui label',
		));

		$this->register_block_element(array(
			'id' => 'form-input',
			'name' => __('Formular-Eingabefeld','padma'),
			'selector' => '.forminator-ui input',
		));

		$this->register_block_element(array(
			'id' => 'form-select',
			'name' => __('Formular-Auswahl','padma'),
			'selector' => '.forminator-ui select',
		));

		$this->register_block_element(array(
			'id' => 'form-textarea',
			'name' => __('Formular-Textfeld','padma'),
			'selector' => '.forminator-ui textarea',
		));

		$this->register_block_element(array(
			'id' => 'form-button',
			'name' => __('Formular-Button','padma'),
			'selector' => '.forminator-ui button',
		));

		$this->register_block_element(array(
			'id' => 'form-submit',
			'name' => __('Formular-Absenden','padma'),
			'selector' => '.forminator-ui button[type="submit"]',
		));
	}


	public static function dynamic_css($block_id, $block = false) {

	}


	public static function dynamic_js($block_id, $block = false) {

	}

	public function content($block) {

		$form_id = parent::get_setting($block, 'form-id', '');		
		
		if ( empty($form_id) || $form_id === '0' ) {
			echo '<p>' . __('Bitte wähl ein Formular aus.','padma') . '</p>';
			return;
		}

		if ( !post_type_exists('powerform_forms') ) {
			echo '<p>' . __('PowerForm ist nicht installiert oder nicht aktiv.','padma') . '</p>';
			return;
		}

		echo do_shortcode('[powerform_form id="' . absint($form_id) . '"]');
	}

	public static function enqueue_action($block_id, $block = false) {

	}


	function get_form_title($form_id){

		$args = array('post_type' => 'powerform_forms', 'posts_per_page' => -1, );

		return get_post($form_id, OBJECT, 'raw')->post_title;
	}

}


class PadmaPowerformBlockOptions extends PadmaBlockOptionsAPI {

	public $tabs;	
	public $sets;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'Allgemein'
		);

		$this->sets = array(

		);

		$this->inputs = array(
			'general' => array(
				'form-id' => array(
					'type' => 'select',
					'name' => 'form-id',
					'label' => __('Formular auswählen','padma'),
					'options' => 'get_forms()',
					'tooltip' => '',
				),
			)
		);
	}

	public function modify_arguments($args = false) {
	}


	function get_forms() {

		$args = array('post_type' => 'powerform_forms', 'posts_per_page' => -1, 'post_status' => array('publish', 'draft'));
		$forms = array(
			'0' => __('Wähl ein Formular aus','padma')
		);

		if( $data = get_posts($args)){

			foreach($data as $key){
				$forms[$key->ID] = $key->post_title;
			}

			return $forms;
		}

		return $forms;
	}	
}
