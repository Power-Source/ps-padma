<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockSociableOptions extends \PadmaBlockOptionsAPI {
	
	public $tabs = array(
		'general' 			=> 'General',
		'custom-icons-set' 	=> 'Custom Icons'
	);

	public $sets = array(
		'custom' => array(
						'hide' => array(
							'li[id*="-set"]:not(#sub-tab-custom-icons-set)'
						),
						'show' => array(
							'li#sub-tab-custom-icons-set'
						)
					),
		'filled-outline-by-roundicons' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-filled-outline-by-roundicons-set)'
											),
											'show' => array(
												'li#sub-tab-filled-outline-by-roundicons-set'
											)
										),
		'flat-by-pixan' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-flat-by-pixan-set)'
											),
											'show' => array(
												'li#sub-tab-flat-by-pixan-set'
											)
										),
		'glyph-by-betterwork' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-glyph-by-betterwork-set)'
											),
											'show' => array(
												'li#sub-tab-glyph-by-betterwork-set'
											)
										),
		'handdrawn-by-side-project' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-handdrawn-by-side-project-set)'
											),
											'show' => array(
												'li#sub-tab-handdrawn-by-side-project-set'
											)
										),
		'outline-by-roundicons' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-outline-by-roundicons-set)'
											),
											'show' => array(
												'li#sub-tab-outline-by-roundicons-set'
											)
										),

		'capsocial-square-flat-by-litvin' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-capsocial-square-flat-by-litvin-set)'
											),
											'show' => array(
												'li#sub-tab-capsocial-square-flat-by-litvin-set'
											)
										),
	);

	public $inputs = array(
		'general' => array(

			'icon-set' => array(
				'type' => 'select',
				'name' => 'icon-set',
				'label' => 'Icon Set',
				'default' => 'peel-icons',
				'options' => 'get_icon_sets()',
				'tooltip' => 'Select custom to add your own icons or select one of these sets',
				'toggle'  => array(),
				'callback' => 'reloadBlockOptions()'
				
			),

			'layout-heading' => array(
				'name' => 'layout-heading',
				'type' => 'heading',
				'label' => 'Layout',
				'tooltip' => 'Set the position of all icons in the block and the orientation before you add your icons.'
			),

			'icons-position' => array(
				'name' => 'icons-position',
				'label' => 'Position icons inside container',
				'type' => 'select',
				'tooltip' => 'You can position the Sociable icons in relation to the block using the positions provided',
				'default' => 'none',
				'options' => array(
					'' => 'None',
					'top_left' => 'Top Left',
					'top_center' => 'Top Center',
					'top_right' => 'Top Right',
					'center_left' => 'Center Left',
					'center_center' => 'Center Center',
					'center_right' => 'Center Right',
					'bottom_left' => 'Bottom Left',
					'bottom_center' => 'Bottom Center',
					'bottom_right' => 'Bottom Right'
				)
			),

			'orientation' => array(
				'type' => 'select',
				'name' => 'orientation',
				'label' => 'Orientation',
				'tooltip' => '',
				'options' => array(
					'vertical' => 'Vertical',
					'horizontal' => 'Horizontal'
				),
				'toggle'    => array(
					'vertical' => array(
						'show' => array(
							'#input-vertical-spacing'
						),
						'hide' => array(
							'#input-horizontal-spacing'
						),
					),
					'horizontal' => array(
						'show' => array(
							'#input-horizontal-spacing'
						),
						'hide' => array(
							'#input-vertical-spacing'
						),
					)
				),
				'tooltip' => 'Display articles on top of each other (vertical) or side by side as a grid (horizontal)'
			),

			'horizontal-spacing' => array(
				'type' => 'text',
				'name' => 'horizontal-spacing',
				'label' => 'Horizontal Spacing',
				'default' => '10',
				'unit' => 'px',
				'tooltip' => 'Set the px horizontal spacing between the icons.'
			),

			'vertical-spacing' => array(
				'type' => 'text',
				'name' => 'vertical-spacing',
				'label' => 'Vertical Spacing',
				'default' => '10',
				'unit' => 'px',
				'tooltip' => 'Set the px vertical spacing between the icons.'
			),

			'svg-heading' => array(
				'name' => 'svg-heading',
				'type' => 'heading',
				'label' => 'SVG Images',
				'tooltip' => 'Allows you to upload SVG Images. Many icons come with SVG versions of the icons. Using an SVG means it is easier to size the icons. With images like .png and .gif you need to manually size them in a graphics program.'
			),

			'use-svg' => array(
				'name' => 'use-svg',
				'label' => 'Use SVG?',
				'type' => 'checkbox',
				'tooltip' => 'If you would like to upload SVG images check this option',
				'default' => false,
				'toggle'    => array(
					'true' => array(
						'show' => array(
							'#input-svg-width',
							'#input-svg-height'
						)
					),
					'false' => array(
						'hide' => array(
							'#input-svg-width',
							'#input-svg-height'
						)
					)
				),
			),

			'svg-width' => array(
				'type' => 'text',
				'name' => 'svg-width',
				'label' => 'SVG Image Width',
				'tooltip' => 'Set the width of all SVG\'s in the block. This also controls the width with a 1:1 ratio'
			)

		),

		'custom-icons-set' => array(
			'icons' => array(
				'type' => 'repeater',
				'name' => 'icons',
				'label' => 'Icons',
				'inputs' => array(
					array(
						'type' => 'image',
						'name' => 'image',
						'label' => 'Image',
						'default' => null
					),

					array(
						'type' => 'text',
						'name' => 'image-title',
						'label' => '"title"',
						'tooltip' => 'This will be used as the "title" attribute for the image.  The title attribute is beneficial for SEO (Search Engine Optimization) and will allow your visitors to move their mouse over the image and read about it.'
					),

					array(
						'type' => 'text',
						'name' => 'image-alt',
						'label' => '"alt"',
						'tooltip' => 'This will be used as the "alt" attribute for the image.  The alt attribute is <em>hugely</em> beneficial for SEO (Search Engine Optimization) and for general accessibility.'
					),

					array(
						'name' => 'link-heading',
						'type' => 'heading',
						'label' => 'Link Image'
					),

					array(
						'name' => 'link-url',
						'label' => 'Link URL?',
						'type' => 'text',
						'tooltip' => 'Set the URL for the image to link to'
					),

					array(
						'name' => 'link-alt',
						'label' => '"alt"',
						'type' => 'text',
						'tooltip' => 'Set alternative text for the link'
					),

					array(
						'name' => 'link-target',
						'label' => 'New window?',
						'type' => 'checkbox',
						'tooltip' => 'If you would like to open the link in a new window check this option',
						'default' => false,
					),

					array(
						'name' => 'link-rel',
						'label' => 'Rel',
						'type'	=> 'text',
						'tooltip' => 'Here you can add value for the rel attribute. Example values: noreferrer, noopener, nofollow, lightbox',
						'default' => 'noreferrer',
					),

					array(
						'name' => 'before-icon',
						'label' => 'Before icon',
						'type'	=> 'wysiwyg',
						'tooltip' => 'Add content before the icon',
					),

					array(
						'name' => 'after-icon',
						'label' => 'After icon',
						'type'	=> 'wysiwyg',
						'tooltip' => 'Add content after the icon',
					),

				),
				'tooltip' => 'Upload the images that you would like to add to the image block.',
				'sortable' => true,
				'limit' => false
			),
		),
	);


	public function modify_arguments($args = false) {

		foreach ( self::get_icon_sets() as $icon_set => $icon_set_name ) {

			if ( $icon_set == 'custom' )
				continue;

			$this->inputs['general']['icon-set']['toggle'] = $this->sets;

			$this->tabs[$icon_set . '-set'] 	= ucwords(str_replace('-', ' ', $icon_set));
			$this->inputs[$icon_set . '-set'] 	= array(

				'icons'.$icon_set => array(
					'type' => 'repeater',
					'name' => 'icons' . $icon_set,
					'label' => 'Icons',
					'inputs' => array(
						array(
							'type' => 'select',
							'name' => 'network',
							'label' => 'Network',
							'default' => null,
							'options' => self::get_icons( $icon_set )
						),

						array(
							'type' => 'select',
							'name' => 'icon-size',
							'label' => 'Icon size',
							'options' => array(
								'64x64' 	=> '64 x 64',
								'128x128' => '128 x 128',
								'256x256' => '256 x 256',
								'512x512' => '512 x 512',
							)
						),

						array(
							'type' => 'text',
							'name' => 'image-title',
							'label' => '"title"',
							'tooltip' => 'This will be used as the "title" attribute for the image.  The title attribute is beneficial for SEO (Search Engine Optimization) and will allow your visitors to move their mouse over the image and read about it.'
						),

						array(
							'type' => 'text',
							'name' => 'image-alt',
							'label' => '"alt"',
							'tooltip' => 'This will be used as the "alt" attribute for the image.  The alt attribute is <em>hugely</em> beneficial for SEO (Search Engine Optimization) and for general accessibility.'
						),

						array(
							'name' => 'link-heading',
							'type' => 'heading',
							'label' => 'Link Image'
						),

						array(
							'name' => 'link-url',
							'label' => 'Link URL?',
							'type' => 'text',
							'tooltip' => 'Set the URL for the image to link to'
						),

						array(
							'name' => 'link-alt',
							'label' => '"alt"',
							'type' => 'text',
							'tooltip' => 'Set alternative text for the link'
						),

						array(
							'name' => 'link-target',
							'label' => 'New window?',
							'type' => 'checkbox',
							'tooltip' => 'If you would like to open the link in a new window check this option',
							'default' => false,
						),

						array(
							'name' => 'link-rel',
							'label' => 'Rel',
							'type'	=> 'text',
							'tooltip' => 'Here you can add value for the rel attribute. Example values: noreferrer, noopener, nofollow, lightbox',
							'default' => 'noreferrer',
						),

						array(
							'name' => 'before-icon',
							'label' => 'Before icon',
							'type'	=> 'wysiwyg',
							'tooltip' => 'Add content before the icon',
						),

						array(
							'name' => 'after-icon',
							'label' => 'After icon',
							'type'	=> 'wysiwyg',
							'tooltip' => 'Add content after the icon',
						),
						/*
						array(
							'name' => 'icon-preview',
							'label' => 'Icon preview',
							'type' => 'checkbox',
						),*/

						/*
						array(
							'name' 		=> 'author',
							'label' 	=> 'About the author <p style="margin-left: 20px;margin-top: 10px;">'.self::get_author_data( $icon_set ).'</p>',
							'type' 		=> 'heading',
							'tooltip' 	=> 'Information about the author of the icon set',
						)*/

					),
					'tooltip' => 'Upload the images that you would like to add to the image block.',
					'sortable' => true,
					'limit' => false
				)
			);

		}

	}

	public static function get_author_data($icon_set ){

		if ( $icon_set != 'custom' ) {

			$path 		= PADMA_LIBRARY_DIR . '/blocks-advanced/sociable/icons/' . $icon_set . '/author.txt';
			return 	file_get_contents($path);

		}

	}

	public static function get_icon_sets() {

		$path 			= PADMA_LIBRARY_DIR . '/blocks-advanced/sociable/icons/';
		$results 		= scandir($path);
		$icons_options 	= array();

		foreach ($results as $result) {

		    if ( $result === '.' || $result === '..' || $result === '.DS_Store') {
			    continue;
		    }

		    if ( is_dir($path . '/' . $result) ) {
		        $icons_options[$result] = ucwords(str_replace('-', ' ', $result));
		    }

		}

		$icons_options['custom'] = 'Custom Icons';

		return $icons_options;

	}

	public static function get_icons( $icon_set ) {

		if ( $icon_set != 'custom' ) {

			$path 		= PADMA_LIBRARY_DIR . '/blocks-advanced/sociable/icons/' . $icon_set . '/';
			$results 	= scandir($path);
			$icons 		= array();

			foreach ($results as $result) {


		    	if ($result === '.' or $result === '..' or $result === '.DS_Store' or $result === 'author.txt') continue;

			    if (!is_dir($path . '/' . $result)) {

					$icon_network = str_replace('512x512', '', $result);
					$icon_network = str_replace('256x256', '', $icon_network);
					$icon_network = str_replace('128x128', '', $icon_network);
					$icon_network = str_replace('64x64', '', $icon_network);
					$icon_network = preg_replace("/\\.[^.\\s]{3,4}$/", "", $icon_network);
					$icon_network = rtrim($icon_network,'-');

			        $icons[$icon_network] = $icon_network;

			    }
			}

			return $icons;

		}
	}
	
}