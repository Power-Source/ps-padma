<?php

class PadmaCustomCodeBlock extends PadmaBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;


	function __construct(){

		$this->id 				= 'custom-code';	
		$this->name 			= __('Custom Code','padma');
		$this->options_class 	= 'PadmaCustomCodeBlockOptions';
		$this->description 		= __('Fügen Sie in diesen Block benutzerdefinierte HTML-, PHP- oder sogar Shortcodes ein.','padma');
		$this->categories 		= array('core','code');
		$this->inline_editable 	= array('block-title', 'block-subtitle', 'content');

	}


	function content($block) {

		$content = parent::get_setting($block, 'content');

		if ( $content != null )

			echo '<div class="custom-code-content content">'.padma_parse_php(do_shortcode(stripslashes($content))).'</div>';			

		else
			echo '<p class="content">' . __('Es gibt keinen benutzerdefinierten Code anzuzeigen.','padma') .'</p>';

	}


	public function setup_elements() {

		$this->register_block_element(array(
			'id' => 'content',			
			'name' => __('Content','padma'),
			'selector' => '.custom-code-content p',
		));

		$this->register_block_element(array(
			'id' => 'content-h1',
			'name' => __('Content H1','padma'),
			'selector' => '.custom-code-content h1',
		));

		$this->register_block_element(array(
			'id' => 'content-h2',
			'name' => __('Content H2','padma'),
			'selector' => '.custom-code-content h2',
		));

		$this->register_block_element(array(
			'id' => 'content-h3',
			'name' => __('Content H3','padma'),
			'selector' => '.custom-code-content h3',
		));

		$this->register_block_element(array(
			'id' => 'content-h4',
			'name' => __('Content H4','padma'),
			'selector' => '.custom-code-content h4',
		));

		$this->register_block_element(array(
			'id' => 'content-h5',
			'name' => __('Content H5','padma'),
			'selector' => '.custom-code-content h5',
		));

		$this->register_block_element(array(
			'id' => 'content-h6',
			'name' => __('Content H6','padma'),
			'selector' => '.custom-code-content h6',
		));

		$this->register_block_element(array(
			'id' => 'content-p',
			'name' => __('Content p','padma'),
			'selector' => '.custom-code-content span',
		));

		$this->register_block_element(array(
			'id' => 'content-a',
			'name' => __('Content a','padma'),
			'selector' => '.custom-code-content a',
		));

		$this->register_block_element(array(
			'id' => 'content-ul',
			'name' => __('Content ul','padma'),
			'selector' => '.custom-code-content ul',
		));

		$this->register_block_element(array(
			'id' => 'content-ul-li',
			'name' => __('Content ul li','padma'),
			'selector' => '.custom-code-content ul li',
		));
	}

}


class PadmaCustomCodeBlockOptions extends PadmaBlockOptionsAPI {

	public $tabs;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'content' => __('Content','padma')
		);

		$this->inputs = array(
			'content' => array(
				'content' => array(
					'type' 		=> 'code',
					'mode' 		=> 'html',
					'name' 		=> __('content','padma'),
					'label' 	=> __('Content','padma'),
					'default' 	=> null,
					'tooltip' => __('Schreibe hier deinen benutzerdefinierten Code. Um die PHP-Ausführung zu aktivieren, füge bitte define(\'PADMA_DISABLE_PHP_PARSING\', false); zu deiner wp-config.php hinzu.','padma')
				),
			),
		);
	}


	public function modify_arguments( $args = false ) {

		if ( defined('PADMA_DISABLE_PHP_PARSING') && PADMA_DISABLE_PHP_PARSING === true ){

			$this->tab_notices['content'] = __('PHP Parsing ist derzeit deaktiviert. Um die PHP-Ausführung zu aktivieren, füge bitte <br><pre>define(\'PADMA_DISABLE_PHP_PARSING\', false);</pre><br> zu deiner wp-config.php hinzu.','padma');

		}

	}

}