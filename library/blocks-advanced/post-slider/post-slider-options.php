<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockPostSliderOptions extends \PadmaBlockOptionsAPI {

	public $tabs = array();
	public $inputs = array();

	function __construct(){

		$this->tabs = array(
			'content-tab' 	=> __( 'Inhalt', 'padma' )
		);


		$this->inputs = array(

			'content-tab' => array(

				'post-type' => array(
					'type' => 'select',
					'name' => 'post-type',
					'label' => __( 'Beitragstyp', 'padma' ),
					'options' => 'get_post_types()',
					'callback' => 'reloadBlockOptions(block.id)',
					'default' => 'post',
					'tooltip' => '',
				),

				'categories' => array(
					'type' => 'multi-select',
					'name' => 'categories',
					'label' => __( 'Kategorien', 'padma' ),
					'tooltip' => '',
					'options' => 'get_categories()'
				),

				'order-by' => array(
					'type' => 'select',
					'name' => 'order-by',
					'label' => __( 'Sortieren nach', 'padma' ),
					'tooltip' => '',
					'options' => array(
						'date' => __( 'Datum', 'padma' ),
						'title' => __( 'Titel', 'padma' ),
						'rand' => __( 'Zufall', 'padma' ),
						'comment_count' => __( 'Anzahl Kommentare', 'padma' ),
						'ID' => 'ID',
						'author' => __( 'Autor', 'padma' ),
						'type' => __( 'Beitragstyp', 'padma' ),
						'menu_order' => __( 'Eigene Reihenfolge', 'padma' )
					)
				),
				
				'order' => array(
					'type' => 'select',
					'name' => 'order',
					'label' => __( 'Reihenfolge', 'padma' ),
					'tooltip' => '',
					'options' => array(
						'desc' => __( 'Absteigend', 'padma' ),
						'asc' => __( 'Aufsteigend', 'padma' ),
					)
				),

				'number-of-posts' => array(
					'type' => 'integer',
					'default' => 6,
					'name' => 'number-of-posts',
					'label' => __( 'So viele Eintraege anzeigen', 'padma' ),
					'tooltip' => '',				
				),

				'slider-style' => array(
					'type' => 'select',
					'name' => 'slider-style',
					'label' => __( 'Stil', 'padma' ),
					'default' => 'style1',
					'options' => array(
						'style1' => __( 'Stil 1', 'padma' ),
						'style2' => __( 'Stil 2', 'padma' ),
						'style3' => __( 'Stil 3', 'padma' ),
						'style4' => __( 'Stil 4', 'padma' ),
						'style5' => __( 'Stil 5', 'padma' ),
						'style6' => __( 'Stil 6', 'padma' ),
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
					'label' => __( 'Was soll gezeigt werden?', 'padma' ),
					'options' => array(
						'normal' => __( 'Kompletter Inhalt', 'padma' ),
						'excerpts' => __( 'Nur Auszuege zeigen', 'padma' ),
						'none' => __( 'Keinen Inhalt zeigen', 'padma' )
					),
					'default' => 'normal',
					'tooltip' => '',
				),

				'custom-length' => array(
					'type' => 'select',
					'name' => 'custom-length',
					'label' => __( 'Eigene Laenge nutzen', 'padma' ),
					'options' => array(
						'no' => __( 'Nein', 'padma' ),
						'yes' => __( 'Ja', 'padma' ),
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
					'label' => __( 'So viele Woerter anzeigen', 'padma' ),
					'tooltip' => '',
				),				

				'auto_play' => array(
					'type' => 'select',
					'name' => 'auto_play',
					'label' => __( 'Automatisch abspielen', 'padma' ),
					'tooltip' => '',
					'options' => array(
						'true' => __( 'Ja', 'padma' ),
						'false' => __( 'Nein', 'padma' ),
					)
				),

				'show_items' => array(
					'type' => 'integer',
					'default' => 3,
					'name' => 'show_items',
					'label' => __( 'So viele Slides gleichzeitig', 'padma' ),
					'tooltip' => '',				
				),			

				'show_pagination' => array(
					'type' => 'select',		
					'name' => 'show_pagination',
					'label' => __( 'Punkte-Navigation anzeigen', 'padma' ),
					'tooltip' => '',
					'options' => array(
						'true' => __( 'Ja', 'padma' ),
						'false' => __( 'Nein', 'padma' ),
					)				
				),

				'read-more-label' => array(
					'type' => 'text',		
					'name' => 'read-more-label',
					'label' => __( 'Text fuer Mehr-lesen-Button', 'padma' ),
					'tooltip' => '',
				),

				'focus-effect' => array(
					'type' => 'select',		
					'name' => 'focus-effect',
					'label' => __( 'Fokus-Effekt nutzen', 'padma' ),
					'options' => array(
						'true' => __( 'Ja', 'padma' ),
						'false' => __( 'Nein', 'padma' ),
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
					'label' => __( 'Fokus-Farbe', 'padma' ),
					'default' => '#3398db',
				),

				'show-direction-nav' => array(
					'type' => 'select',		
					'name' => 'show-direction-nav',
					'label' => __( 'Navigationspfeile anzeigen', 'padma' ),
					'default' => 'true',
					'options' => array(
						'true' => __( 'Ja', 'padma' ),
						'false' => __( 'Nein', 'padma' ),
					)
				),

				'navigate-mode' => array(
					'type' => 'checkbox',
					'default' => false,
					'name' => 'navigate-mode',
					'label' => __( 'Navigations-Modus im Editor', 'padma' ),
					'tooltip' => __( 'Navigiere im visuellen Editor durch die Slides, damit du sie einzeln stylen kannst.', 'padma' ),
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