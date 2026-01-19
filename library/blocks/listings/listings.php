<?php

class PadmaListingsBlock extends PadmaBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	static $block = null;
	public $categories;


	function __construct(){

		$this->id = 'listings';
		$this->name = __('Archive','padma');
		$this->options_class = 'PadmaListingsBlockOptions';
		$this->description = __('Liste Deine Beiträge, benutzerdefinierten Beitragstypen, Kategorien, Schlagwörter, benutzerdefinierten Taxonomien, Autoren, Seiten und Kommentare auf.','padma');		
		$this->categories = array('core','content');
	}


	function init() {

		require_once 'block-options.php';
		require_once PADMA_LIBRARY_DIR . '/blocks/listings/content-display.php';		
	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'list-items',
			'name' => __('Listen Container','padma'),
			'selector' => 'ul.list-items'
		));

		$this->register_block_element(array(
			'id' => 'list-item',
			'name' => __('Listeneintrag','padma'),
			'selector' => 'ul.list-items li'
		));

		$this->register_block_element(array(
			'id' => 'list-item-link',
			'name' => __('Listeneintrag Link','padma'),
			'selector' => 'ul.list-items li a'
		));

	}

	function content($block) {

		$listing_block_display = new PadmaListingBlockDisplay($block);
		$listing_block_display->display();

	}

}
