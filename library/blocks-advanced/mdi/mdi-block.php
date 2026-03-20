<?php

/**
 * MaterialDesign Icons Blocks
 *
 * @link       https://power-source.github.io/ps-padma/
 * @since      1.0.0
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/public
 * @author     PSOURCE <support@psource.eimen.net>
 */

namespace Padma_Advanced;

/**
 * MaterialDesign Icon Block
 */
class PadmaMDIBlock extends \PadmaBlockAPI {

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
		$this->id            = 'mdi';
		$this->name          = __( 'MaterialDesign Symbole', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaMDIBlockOptions';
		$this->description   = __( 'Fügt MaterialDesign-Symbole zum visuellen Editor hinzu.', 'padma' );
		$this->categories    = array( 'content' );
	}

	/**
	 * Init
	 */
	public function init() {
		add_action( 'padma_visual_editor_styles', array( __CLASS__, 'mdi_admin_styles' ) );
		add_action( 'padma_visual_editor_scripts', array( __CLASS__, 'mdi_admin_scripts' ), 10 );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'content',
				'name'     => __( 'Block Inhalt', 'padma' ),
				'selector' => '.block-content',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'mdi-wrap',
				'name'     => __( 'MDI Wrapper', 'padma' ),
				'selector' => '.mdi-inline-wrap',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'link',
				'name'     => __( 'Link', 'padma' ),
				'selector' => '.mdi-icon-link',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'icon',
				'name'     => __( 'Symbol', 'padma' ),
				'selector' => 'i.mdi',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'before-icon',
				'name'     => __( 'Vor dem Symbol', 'padma' ),
				'selector' => '.before-icon',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'after-icon',
				'name'     => __( 'Nach dem Symbol', 'padma' ),
				'selector' => '.after-icon',
			)
		);
	}

	/**
	 * Dynamic CSS for inline before/icon/after layout
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Block Object.
	 * @return string
	 */
	public static function dynamic_css( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		return '
		#block-' . $block_id . ' .mdi-inline-wrap {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			flex-wrap: wrap;
		}

		#block-' . $block_id . ' .mdi-inline-wrap .before-icon,
		#block-' . $block_id . ' .mdi-inline-wrap .after-icon {
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}

		#block-' . $block_id . ' .mdi-inline-wrap .before-icon > *,
		#block-' . $block_id . ' .mdi-inline-wrap .after-icon > * {
			display: inline;
			margin: 0;
		}
		';
	}

	/**
	 * Dynamic JS - Asset loading for VE context
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Block Object.
	 * @return string
	 */
	public static function dynamic_js( $block_id, $block ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		$path = padma_url() . '/library/blocks-advanced/mdi/css/materialdesignicons.min.css';

		return "
		(function() {
			var href = '" . $path . "';
			var links = document.getElementsByTagName('link');
			for (var i = 0; i < links.length; i++) {
				if (links[i].href === href) return;
			}
			var link = document.createElement('link');
			link.rel = 'stylesheet';
			link.href = href;
			document.head.appendChild(link);
		})();
		";
	}

	/**
	 * Padma Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$icon        = parent::get_setting( $block, 'mdi-icon' );
		$width       = (int)parent::get_setting( $block, 'mdi-icon-width' );
		$height      = (int)parent::get_setting( $block, 'mdi-icon-height' );
		$before_icon = parent::get_setting( $block, 'before-icon' );
		$after_icon  = parent::get_setting( $block, 'after-icon' );
		$url         = parent::get_setting( $block, 'url' );

		if ( ! empty( $icon ) ) {

			echo '<span class="mdi-inline-wrap">';

			if ( ! empty( $before_icon ) ) {
				echo '<span class="before-icon">';
				echo $before_icon;
				echo '</span>';
			}

			if ( empty( $width ) ) {
				$width = 24;
			}

			if ( empty( $height ) ) {
				$height = 24;
			}

			$icon_name  = sanitize_html_class( $icon );
			$font_size  = (int) min( $width, $height );
			$icon_style = sprintf( 'display:inline-block;width:%dpx;height:%dpx;line-height:%dpx;font-size:%dpx;text-align:center;', (int) $width, (int) $height, (int) $height, (int) $font_size );
			$icon_html  = sprintf( '<i class="mdi mdi-%s" style="%s" aria-hidden="true"></i>', esc_attr( $icon_name ), esc_attr( $icon_style ) );

			if ( ! empty( $url ) ) {
				echo sprintf( '<a href="%s" class="mdi-icon-link">%s</a>', esc_url( $url ), $icon_html );
			} else {
				echo $icon_html;
			}

			if ( ! empty( $after_icon ) ) {
				echo '<span class="after-icon">';
				echo $after_icon;
				echo '</span>';
			}

			echo '</span>';
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
		$mdi_css_path = PADMA_LIBRARY_DIR . '/blocks-advanced/mdi/css/materialdesignicons.min.css';
		$mdi_css_url  = padma_url() . '/library/blocks-advanced/mdi/css/materialdesignicons.min.css';

		if ( file_exists( $mdi_css_path ) ) {
			wp_enqueue_style( 'padma-ve-mdi', $mdi_css_url, array(), PADMA_VERSION, 'all' );
		}
	}

	/**
	 * Admin styles
	 *
	 * @return void
	 */
	public static function mdi_admin_styles() {
		$path = padma_url() . '/library/blocks-advanced/mdi/css/materialdesignicons.min.css';
		wp_register_style( 'padma-ve-mdi', $path, false, PADMA_VERSION, 'all' );
		wp_enqueue_style( 'padma-ve-mdi' );
	}

	/**
	 * Admin scripts
	 *
	 * @return void
	 */
	public static function mdi_admin_scripts() {

		$path = padma_url() . '/library/blocks-advanced/mdi/';
		wp_register_script( 'padma_mdi_script', $path . 'mdi.js', array('jquery'), PADMA_VERSION, true );
		wp_enqueue_script( 'padma_mdi_script' );

	}

}
