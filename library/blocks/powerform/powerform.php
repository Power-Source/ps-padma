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
			'selector' => '.powerform-ui',
		));

		$this->register_block_element(array(
			'id' => 'form-row',
			'name' => __('Formular-Zeile','padma'),
			'selector' => '.powerform-ui .powerform-row',
		));

		$this->register_block_element(array(
			'id' => 'form-col',
			'name' => __('Formular-Spalte','padma'),
			'selector' => '.powerform-ui .powerform-col',
		));

		$this->register_block_element(array(
			'id' => 'form-field',
			'name' => __('Feld-Container','padma'),
			'selector' => '.powerform-ui .powerform-field',
		));

		$this->register_block_element(array(
			'id' => 'form-label',
			'name' => __('Label','padma'),
			'selector' => '.powerform-ui .powerform-label',
		));

		$this->register_block_element(array(
			'id' => 'form-description',
			'name' => __('Beschreibung','padma'),
			'selector' => '.powerform-ui .powerform-description',
		));

		$this->register_block_element(array(
			'id' => 'form-input',
			'name' => __('Eingabefeld','padma'),
			'selector' => '.powerform-ui .powerform-input',
		));

		$this->register_block_element(array(
			'id' => 'form-select',
			'name' => __('Auswahlfeld','padma'),
			'selector' => '.powerform-ui .powerform-select',
		));

		$this->register_block_element(array(
			'id' => 'form-textarea',
			'name' => __('Textfeld','padma'),
			'selector' => '.powerform-ui .powerform-textarea',
		));

		$this->register_block_element(array(
			'id' => 'form-radio',
			'name' => __('Radio-Buttons','padma'),
			'selector' => '.powerform-ui .powerform-radio',
		));

		$this->register_block_element(array(
			'id' => 'form-checkbox',
			'name' => __('Checkboxen','padma'),
			'selector' => '.powerform-ui .powerform-checkbox',
		));

		$this->register_block_element(array(
			'id' => 'form-button',
			'name' => __('Button','padma'),
			'selector' => '.powerform-ui .powerform-button',
		));

		$this->register_block_element(array(
			'id' => 'form-button-submit',
			'name' => __('Submit-Button','padma'),
			'selector' => '.powerform-ui .powerform-button-submit',
		));

		$this->register_block_element(array(
			'id' => 'form-error-message',
			'name' => __('Fehlermeldung','padma'),
			'selector' => '.powerform-ui .powerform-error-message',
		));

		$this->register_block_element(array(
			'id' => 'form-response-message',
			'name' => __('Erfolgsmeldung','padma'),
			'selector' => '.powerform-ui .powerform-response-message',
		));

		$this->register_block_element(array(
			'id' => 'form-pagination',
			'name' => __('Seitenwechsel-Container','padma'),
			'selector' => '.powerform-ui .powerform-pagination',
		));

		$this->register_block_element(array(
			'id' => 'form-button-text',
			'name' => __('Button-Text','padma'),
			'selector' => '.powerform-ui .powerform-button--text',
		));

		$this->register_block_element(array(
			'id' => 'form-field-with-error',
			'name' => __('Feld mit Fehler','padma'),
			'selector' => '.powerform-ui .powerform-has_error',
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
