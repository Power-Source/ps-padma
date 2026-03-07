<?php

/**
 * FontAwesome Icon Blocks
 *
 * @link       https://padmaunlimited.com
 * @since      1.0.0
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/public
 * @author     Padma Team <support@padmaunlimited.com>
 */

namespace Padma_Advanced;

/**
 * FontAwesome Icon Block
 */
class PadmaVisualElementsFontAwesomeBlock extends \PadmaBlockAPI {

	/**
	 * Block id
	 *
	 * @var string $id
	 */
	public $id;

	/**
	 * Block Name
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 * Options Class
	 *
	 * @var string $options_class
	 */
	public $options_class;

	/**
	 * Block Description
	 *
	 * @var string $description
	 */
	public $description;

	/**
	 * Block Categories
	 *
	 * @var array $categories
	 */
	public $categories;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id            = 'visual-elements-fontawesome';
		$this->name          = __( 'FontAwesome Icon', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsFontAwesomeBlockOptions';
		$this->description   = __( 'Add FontAwesome to the visual editor . ', 'padma' );
		$this->categories    = array( 'content' );
	}

	/**
	 * Init
	 */
	public function init() {
		add_action( 'padma_visual_editor_styles', array( __CLASS__, 'fontawesome_admin_styles' ) );
		add_action( 'padma_visual_editor_scripts', array( __CLASS__, 'fontawesome_admin_scripts' ), 10 );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'block-content',
				'name'     => __( 'Block content', 'padma' ),
				'selector' => '.block-content',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'icon',
				'name'     => __( 'Icon', 'padma' ),
				'selector' => '.block-content i',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon',
				'name'     => __( 'Before Icon', 'padma' ),
				'selector' => '.before-icon',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-a',
				'name'     => __( 'Before Icon Link', 'padma' ),
				'selector' => '.before-icon a',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-p',
				'name'     => __( 'Before Icon Text', 'padma' ),
				'selector' => '.before-icon p',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-span',
				'name'     => __( 'Before Icon Span', 'padma' ),
				'selector' => '.before-icon span',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h1',
				'name'     => __( 'Before Icon H1', 'padma' ),
				'selector' => '.before-icon h1',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h2',
				'name'     => __( 'Before Icon H2', 'padma' ),
				'selector' => '.before-icon h2',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h3',
				'name'     => __( 'Before Icon H3', 'padma' ),
				'selector' => '.before-icon h3',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h1',
				'name'     => __( 'Before Icon H1', 'padma' ),
				'selector' => '.before-icon h1',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h4',
				'name'     => __( 'Before Icon H4', 'padma' ),
				'selector' => '.before-icon h4',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h5',
				'name'     => __( 'Before Icon H5', 'padma' ),
				'selector' => '.before-icon h5',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-h6',
				'name'     => __( 'Before Icon H6', 'padma' ),
				'selector' => '.before-icon h6',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-ul',
				'name'     => __( 'Before Icon List', 'padma' ),
				'selector' => '.before-icon ul',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-ol',
				'name'     => __( 'Before Icon List', 'padma' ),
				'selector' => '.before-icon ol',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon-li',
				'name'     => __( 'Before Icon List item', 'padma' ),
				'selector' => '.before-icon li',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon',
				'name'     => __( 'After Icon', 'padma' ),
				'selector' => '.after-icon',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-a',
				'name'     => __( 'After Icon Link', 'padma' ),
				'selector' => '.after-icon a',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-p',
				'name'     => __( 'After Icon Text', 'padma' ),
				'selector' => '.after-icon p',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-span',
				'name'     => __( 'After Icon Span', 'padma' ),
				'selector' => '.after-icon span',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h1',
				'name'     => __( 'After Icon H1', 'padma' ),
				'selector' => '.after-icon h1',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h2',
				'name'     => __( 'After Icon H2', 'padma' ),
				'selector' => '.after-icon h2',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h3',
				'name'     => __( 'After Icon H3', 'padma' ),
				'selector' => '.after-icon h3',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h1',
				'name'     => __( 'After Icon H1', 'padma' ),
				'selector' => '.after-icon h1',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h4',
				'name'     => __( 'After Icon H4', 'padma' ),
				'selector' => '.after-icon h4',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h5',
				'name'     => __( 'After Icon H5', 'padma' ),
				'selector' => '.after-icon h5',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-h6',
				'name'     => __( 'After Icon H6', 'padma' ),
				'selector' => '.after-icon h6',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-ul',
				'name'     => __( 'After Icon List', 'padma' ),
				'selector' => '.after-icon ul',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-ol',
				'name'     => __( 'After Icon List', 'padma' ),
				'selector' => '.after-icon ol',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon-li',
				'name'     => __( 'After Icon List item', 'padma' ),
				'selector' => '.after-icon li',
			)
		);
	}

	/**
	 * Padma Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$icon        = parent::get_setting( $block, 'fa-icon' );
		$before_icon = parent::get_setting( $block, 'before-icon' );
		$after_icon  = parent::get_setting( $block, 'after-icon' );
		$url         = parent::get_setting( $block, 'url' );

		if ( ! empty( $icon ) ) {

			if ( ! empty( $before_icon ) ) {
				echo '<div class="before-icon">';
				echo $before_icon;
				echo '</div>';
			}

			$icon = explode( '_', $icon );

			if ( ! empty( $url ) ) {
				echo sprintf( '<a href="%s"><i class="%s %s"></i></a>', $url, $icon[0], $icon[1] );
			} else {
				echo sprintf( '<i class="%s %s"></i>', $icon[0], $icon[1] );
			}

			if ( ! empty( $after_icon ) ) {
				echo '<div class="after-icon">';
				echo $after_icon;
				echo '</div>';
			}
		}

	}


	/**
	 * Register styles and scripts
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Is Block?.
	 * @return void
	 */
	public static function enqueue_action( $block_id, $block = false ) {

		/* CSS */
		$path = padma_url() . '/library/blocks-advanced/fontawesome/';
		wp_enqueue_style( 'padma-ve-fontawesome', $path . 'fontawesome.css', array(), PADMA_VERSION, 'all' );

		// Webfont-Pfade mit absoluten URLs korrigieren
		$webfonts_path = $path . 'webfonts/';
		$font_faces = "
		@font-face {
			font-family: 'Font Awesome 5 Brands';
			font-style: normal;
			font-weight: normal;
			font-display: auto;
			src: url('{$webfonts_path}fa-brands-400.eot');
			src: url('{$webfonts_path}fa-brands-400.eot?#iefix') format('embedded-opentype'),
				url('{$webfonts_path}fa-brands-400.woff2') format('woff2'),
				url('{$webfonts_path}fa-brands-400.woff') format('woff'),
				url('{$webfonts_path}fa-brands-400.ttf') format('truetype'),
				url('{$webfonts_path}fa-brands-400.svg#fontawesome') format('svg');
		}
		@font-face {
			font-family: 'Font Awesome 5 Free';
			font-style: normal;
			font-weight: 400;
			font-display: auto;
			src: url('{$webfonts_path}fa-regular-400.eot');
			src: url('{$webfonts_path}fa-regular-400.eot?#iefix') format('embedded-opentype'),
				url('{$webfonts_path}fa-regular-400.woff2') format('woff2'),
				url('{$webfonts_path}fa-regular-400.woff') format('woff'),
				url('{$webfonts_path}fa-regular-400.ttf') format('truetype'),
				url('{$webfonts_path}fa-regular-400.svg#fontawesome') format('svg');
		}
		@font-face {
			font-family: 'Font Awesome 5 Free';
			font-style: normal;
			font-weight: 900;
			font-display: auto;
			src: url('{$webfonts_path}fa-solid-900.eot');
			src: url('{$webfonts_path}fa-solid-900.eot?#iefix') format('embedded-opentype'),
				url('{$webfonts_path}fa-solid-900.woff2') format('woff2'),
				url('{$webfonts_path}fa-solid-900.woff') format('woff'),
				url('{$webfonts_path}fa-solid-900.ttf') format('truetype'),
				url('{$webfonts_path}fa-solid-900.svg#fontawesome') format('svg');
		}
		";
		wp_add_inline_style( 'padma-ve-fontawesome', $font_faces );

	}

	/**
	 * Admin styles
	 *
	 * @return void
	 */
	public static function fontawesome_admin_styles() {

		$path = padma_url() . '/library/blocks-advanced/fontawesome/';
		wp_register_style( 'padma-ve-fontawesome', $path . 'fontawesome.css', false, PADMA_VERSION, 'all' );
		wp_enqueue_style( 'padma-ve-fontawesome' );

		// Webfont-Pfade mit absoluten URLs korrigieren
		$webfonts_path = $path . 'webfonts/';
		$font_faces = "
		@font-face {
			font-family: 'Font Awesome 5 Brands';
			font-style: normal;
			font-weight: normal;
			font-display: auto;
			src: url('{$webfonts_path}fa-brands-400.eot');
			src: url('{$webfonts_path}fa-brands-400.eot?#iefix') format('embedded-opentype'),
				url('{$webfonts_path}fa-brands-400.woff2') format('woff2'),
				url('{$webfonts_path}fa-brands-400.woff') format('woff'),
				url('{$webfonts_path}fa-brands-400.ttf') format('truetype'),
				url('{$webfonts_path}fa-brands-400.svg#fontawesome') format('svg');
		}
		@font-face {
			font-family: 'Font Awesome 5 Free';
			font-style: normal;
			font-weight: 400;
			font-display: auto;
			src: url('{$webfonts_path}fa-regular-400.eot');
			src: url('{$webfonts_path}fa-regular-400.eot?#iefix') format('embedded-opentype'),
				url('{$webfonts_path}fa-regular-400.woff2') format('woff2'),
				url('{$webfonts_path}fa-regular-400.woff') format('woff'),
				url('{$webfonts_path}fa-regular-400.ttf') format('truetype'),
				url('{$webfonts_path}fa-regular-400.svg#fontawesome') format('svg');
		}
		@font-face {
			font-family: 'Font Awesome 5 Free';
			font-style: normal;
			font-weight: 900;
			font-display: auto;
			src: url('{$webfonts_path}fa-solid-900.eot');
			src: url('{$webfonts_path}fa-solid-900.eot?#iefix') format('embedded-opentype'),
				url('{$webfonts_path}fa-solid-900.woff2') format('woff2'),
				url('{$webfonts_path}fa-solid-900.woff') format('woff'),
				url('{$webfonts_path}fa-solid-900.ttf') format('truetype'),
				url('{$webfonts_path}fa-solid-900.svg#fontawesome') format('svg');
		}
		";
		wp_add_inline_style( 'padma-ve-fontawesome', $font_faces );

	}

	/**
	 * Admin scripts
	 *
	 * @return void
	 */
	public static function fontawesome_admin_scripts() {

		$path = padma_url() . '/library/blocks-advanced/fontawesome/';
		wp_register_script( 'padma_fontawesome_script', $path . 'visual-elements-fontawesome.js', array( 'jquery' ), PADMA_VERSION, true );
		wp_enqueue_script( 'padma_fontawesome_script' );

	}

}
