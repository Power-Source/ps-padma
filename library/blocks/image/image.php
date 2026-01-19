<?php

class PadmaImageBlock extends PadmaBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $html_tag;
	public $attributes;
	public $description;
	public $categories;	
	protected $show_content_in_grid;


	function __construct(){

		$this->id 				= 'image';
		$this->name 			= __('Bild','padma');
		$this->options_class 	= 'PadmaImageBlockOptions';	
		$this->fixed_height 	= true;	
		$this->html_tag 		= 'figure';
		$this->attributes 		= array(
										'itemscope' => '',
										'itemtype' => 'http://schema.org/ImageObject'
									);
		$this->description 	= __('Zeigt ein Bild an','padma');
		$this->categories 		= array('core','media');		
		$this->show_content_in_grid = true;

	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'image',
			'name' => __('Bild','padma'),
			'selector' => 'img',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'sizes', 'advanced', 'transition', 'outlines', 'filter')
		));

		$this->register_block_element(array(
			'id' => 'image-link',
			'name' => __('Bild-Link','padma'),
			'selector' => 'a img',
			'states' => array(
				'Hover' => 'a:hover img',
				'Clicked' => 'a:active img'
			)
		));

	}

	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = PadmaBlocksData::get_block($block_id);

		if ( !$position = parent::get_setting($block, 'image-position') )
			return;

		$position_properties = array(
			'top_left' => 'left: 0; top: 0;',
			'top_center' => 'left: 0; top: 0; right: 0;',
			'top_right' => 'top: 0; right: 0;',

			'center_center' => 'bottom: 0; left: 0; top: 0; right: 0;',
			'center_left' => 'bottom: 0; left: 0; top: 0;',
			'center_right' => 'bottom: 0; top: 0; right: 0;',

			'bottom_left' => 'bottom: 0; left: 0;',
			'bottom_center' => 'bottom: 0; left: 0; right: 0;',
			'bottom_right' => 'bottom: 0;right: 0;'
		);

		$position_fragments = explode('_', $position);
		$position_horizontal = $position_fragments[1];

		$css = '
			#block-' . $block['id'] . ' .block-content { position: relative; text-align: ' . $position_horizontal . '; }
			#block-' . $block['id'] . ' img {
				margin: auto;
			    position: absolute;  
			    ' . padma_get($position, $position_properties) . '
			}
		';

		return $css;

	}

	function content($block) {

		//Display image if there is one
		if ( $image_src = parent::get_setting($block, 'image') ) {

			$url = parent::get_setting($block, 'link-url');
			$alt = parent::get_setting($block, 'image-alt');
			$title = parent::get_setting($block, 'image-title');
			$target = parent::get_setting($block, 'link-target', false) ? $target = 'target="_blank"' : '';
			$rel = parent::get_setting($block, 'link-rel', false) ? $rel = 'noreferrer' : '';

			if ( parent::get_setting($block, 'resize-image', true) ) {

				$block_width = PadmaBlocksData::get_block_width($block);
				$block_height = PadmaBlocksData::get_block_height($block);

				$image_url = padma_resize_image($image_src, $block_width, $block_height);

			} else {

				$image_url = $image_src;

			}

			if ( $image_src = parent::get_setting($block, 'link-image', false) ) {

				echo '<a href="' . $url . '" rel="' . $rel . '" class="image" '.$target.'><img src="' . padma_format_url_ssl($image_url) . '" alt="' . $alt . '" title="' . $title . '" itemprop="contentURL"/></a>';

			} else {

				echo '<img src="' . padma_format_url_ssl($image_url) . '" alt="' . $alt . '" title="' . $title . '" itemprop="contentURL"/>';

			}

		} else {

			echo '<div style="margin: 5px;" class="alert alert-yellow"><p>' . __('Du hast noch kein Bild hinzugefügt. Bitte lade ein Bild hoch und wende es an.','padma') . '</p></div>';
		}

		/* Output position styling for Grid mode */
			if ( padma_get('ve-live-content-query', $block) && padma_post('mode') == 'grid' ) {
				echo '<style type="text/css">';
					echo self::dynamic_css(false, $block);
				echo '</style>';
			}
	}

}


class PadmaImageBlockOptions extends PadmaBlockOptionsAPI {


	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);
		
		$this->tabs = array(
			'general' => 'General'
		);

		$this->inputs = array(
			'general' => array(

				'image-heading' => array(
					'name' => 'image-heading',
					'type' => 'heading',
					'label' => __('Bild hinzufügen','padma')
				),

				'image' => array(
					'type' => 'image',
					'name' => 'image',
					'label' => __('Bild','padma'),
					'default' => null
				),

				'resize-image' => array(
					'name' => 'resize-image',
					'label' => __('Bild automatisch skalieren','padma'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du möchtest, dass Padma das Bild automatisch auf die Blockabmessungen skaliert und zuschneidet, lasse diese Option aktiviert.<br /><br /><em><strong>Wichtig:</strong> Damit das Bild skaliert und zugeschnitten werden kann, muss es <strong>Vom Computer</strong> hochgeladen werden. <strong>Nicht</strong> <strong>Von URL</strong>.</em>','padma'),
					'default' => true
				),

				'image-title' => array(
					'name' => 'image-title',
					'label' => 'Bildtitel',
					'type' => 'text',
					'tooltip' => __('Dies wird als „Titel“-Attribut für das Bild verwendet. Das Titel-Attribut ist für die Suchmaschinenoptimierung (SEO) von Vorteil und ermöglicht es Besuchern, mit der Maus über das Bild zu fahren und mehr darüber zu erfahren.','padma'),
				),

				'image-alt' => array(
					'name' => 'image-alt',
					'label' => 'Alternativer Bildtext',
					'type' => 'text',
					'tooltip' => __('Dies wird als „alt“-Attribut für das Bild verwendet. Das alt-Attribut ist <em>sehr</em> vorteilhaft für die Suchmaschinenoptimierung (SEO) und für die allgemeine Zugänglichkeit.','padma'),
				),

				'link-heading' => array(
					'name' => 'link-heading',
					'type' => 'heading',
					'label' => __('Bild verlinken','padma')
				),

				'link-image' => array(
					'name' => 'link-image',
					'label' => __('Bild verlinken?','padma'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du möchtest, dass das Bild mit einer URL verlinkt wird, aktiviere diese Einstellung. Du musst http:// zuerst hinzufügen','padma'),
					'default' => false,
					'toggle' => array(
						'true' => array(
							'show' => array(
								'#input-link-url',
								'#input-link-target',
								'#input-link-rel'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-link-url',
								'#input-link-target',
								'#input-link-rel'
							)
						)
					)
				),

				'link-url' => array(
					'name' => 'link-url',
					'label' => __('Bild-URL verlinken?','padma'),
					'type' => 'text',
					'tooltip' => __('Gib die URL ein, zu der das Bild verlinken soll','padma')
				),

				'link-target' => array(
					'name' => 'link-target',
					'label' => __('In einem neuen Fenster öffnen?','padma'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du möchtest, dass der Link in einem neuen Fenster geöffnet wird, aktiviere diese Option','padma'),
					'default' => false,
				),

				'link-rel' => array(
					'name' => 'link-rel',
					'label' => 'Rel',
					'type' => 'text',
					'tooltip' => 'Hier kannst Du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: noreferrer, noopener, nofollow, lightbox',
					'default' => 'noreferrer',
				),

				'position-heading' => array(
					'name' => 'position-heading',
					'type' => 'heading',
					'label' => __('Bild positionieren','padma')
				),

				'image-position' => array(
					'name' => 'image-position',
					'label' => __('Bildposition innerhalb des Containers','padma'),
					'type' => 'select',
					'tooltip' => __('Du kannst dieses Bild in Bezug auf den Block mit den bereitgestellten Positionen positionieren','padma'),
					'default' => 'none',
					'options' => array(
						'' => 'Keine',
						'top_left' => __('Oben Links','padma'),
						'top_center' => __('Oben Mitte','padma'),
						'top_right' => __('Oben Rechts','padma'),
						'center_left' => __('Mitte Links','padma'),
						'center_center' => __('Mitte Mitte','padma'),
						'center_right' => __('Mitte Rechts','padma'),
						'bottom_left' => __('Unten Links','padma'),
						'bottom_center' => __('Unten Mitte','padma'),
						'bottom_right' => __('Unten Rechts','padma')
					)
				)

			)
		);
	}

}