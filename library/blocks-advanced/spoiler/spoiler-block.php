<?php
/**
 * Spoiler Blocks
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
 * Spoiler Block
 */
class PadmaVisualElementsBlockSpoiler extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-spoiler';	
		$this->name          = __( 'Spoiler', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockSpoilerOptions';
		$this->description   = __( 'Allows you to create blocks with hidden content – spoilers (toggles). Hidden content will be shown when block title will be clicked. You can specify different icons or even use different styles for each spoiler . ', 'padma' );
		$this->categories    = array( 'box' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		// No external plugin dependency needed
		return true;
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'spoiler-item',
				'name'     => __( 'Spoiler Item', 'padma' ),
				'selector' => '.su-spoiler',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler-title',
				'name'     => __( 'Spoiler Titel', 'padma' ),
				'selector' => '.su-spoiler-title',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler-icon',
				'name'     => __( 'Spoiler Icon', 'padma' ),
				'selector' => '.su-spoiler-icon',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler-content',
				'name'     => __( 'Spoiler Inhalt', 'padma' ),
				'selector' => '.su-spoiler-content',
			)
		);
	}

	/**
	 * Ensure spoiler styles and scripts are available in frontend and VE iframe.
	 */
	public function enqueue_action( $block_id ) {
		// Load FontAwesome for icons
		$fontawesome_css_path = PADMA_LIBRARY_DIR . '/blocks-advanced/portfolio/fontawesome.css';
		if ( file_exists( $fontawesome_css_path ) ) {
			$fontawesome_css_url = padma_url() . '/library/blocks-advanced/portfolio/fontawesome.css';
			wp_enqueue_style( 'padma-ve-fontawesome', $fontawesome_css_url, array(), PADMA_VERSION, 'all' );
		}

		// Load box-shortcodes CSS
		wp_enqueue_style(
			'padma-box-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/box-shortcodes.css',
			array(),
			'1.0'
		);

		// Load other-shortcodes CSS and JS
		wp_enqueue_style(
			'padma-other-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/other-shortcodes.css',
			array(),
			'1.0'
		);

		wp_enqueue_script(
			'padma-other-shortcodes-js',
			get_template_directory_uri() . '/assets/js/psource-shortcodes/other-shortcodes.js',
			array( 'jquery' ),
			'1.0',
			true
		);
	}

	/**
	 * Inject spoiler CSS directly for Visual Editor
	 */
	public static function dynamic_css( $block_id, $block = false ) {
		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		$css = '
/* FontAwesome for spoiler icons */
@font-face {
	font-family: "FontAwesome";
	src: url("' . padma_url() . '/library/blocks-advanced/post-slider/fonts/fontawesome-webfont.woff2") format("woff2"),
		 url("' . padma_url() . '/library/blocks-advanced/post-slider/fonts/fontawesome-webfont.woff") format("woff"),
		 url("' . padma_url() . '/library/blocks-advanced/post-slider/fonts/fontawesome-webfont.ttf") format("truetype"),
		 url("' . padma_url() . '/library/blocks-advanced/post-slider/fonts/fontawesome-webfont.eot?#iefix") format("embedded-opentype");
	font-weight: normal;
	font-style: normal;
}

/* Spoiler/Accordion Base Styles */
.su-spoiler { 
	margin-bottom: 1.5em; 
}
.su-spoiler .su-spoiler:last-child { 
	margin-bottom: 0; 
}
.su-accordion { 
	margin-bottom: 1.5em; 
}
.su-accordion .su-spoiler { 
	margin-bottom: 0.5em; 
}
.su-spoiler-title {
	position: relative;
	cursor: pointer;
	min-height: 20px;
	line-height: 20px;
	padding: 7px 7px 7px 34px;
	font-weight: bold;
	font-size: 13px;
}
.su-spoiler-icon {
	position: absolute;
	left: 7px;
	top: 7px;
	display: block;
	width: 20px;
	height: 20px;
	line-height: 21px;
	text-align: center;
	font-size: 14px;
	font-family: FontAwesome;
	font-weight: normal;
	font-style: normal;
	-webkit-font-smoothing: antialiased;
}
.su-spoiler-content {
	padding: 14px;
	-webkit-transition: padding-top .2s;
	-moz-transition: padding-top .2s;
	-o-transition: padding-top .2s;
	transition: padding-top .2s;
}
.su-spoiler.su-spoiler-closed > .su-spoiler-content {
	height: 0;
	margin: 0;
	padding: 0;
	overflow: hidden;
	border: none;
	opacity: 0;
}

/* Spoiler Icons */
.su-spoiler-icon-plus .su-spoiler-icon:before { 
	content: "\\f068"; 
}
.su-spoiler-icon-plus.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f067"; 
}
.su-spoiler-icon-plus-circle .su-spoiler-icon:before { 
	content: "\\f056"; 
}
.su-spoiler-icon-plus-circle.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f055"; 
}
.su-spoiler-icon-plus-square-1 .su-spoiler-icon:before { 
	content: "\\f146"; 
}
.su-spoiler-icon-plus-square-1.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0fe"; 
}
.su-spoiler-icon-plus-square-2 .su-spoiler-icon:before { 
	content: "\\f117"; 
}
.su-spoiler-icon-plus-square-2.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f116"; 
}
.su-spoiler-icon-arrow .su-spoiler-icon:before { 
	content: "\\f063"; 
}
.su-spoiler-icon-arrow.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f061"; 
}
.su-spoiler-icon-arrow-circle-1 .su-spoiler-icon:before { 
	content: "\\f0ab"; 
}
.su-spoiler-icon-arrow-circle-1.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0aa"; 
}
.su-spoiler-icon-arrow-circle-2 .su-spoiler-icon:before { 
	content: "\\f01a"; 
}
.su-spoiler-icon-arrow-circle-2.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f019"; 
}
.su-spoiler-icon-angle .su-spoiler-icon:before { 
	content: "\\f107"; 
}
.su-spoiler-icon-angle.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f105"; 
}
.su-spoiler-icon-angle-circle-down .su-spoiler-icon:before { 
	content: "\\f0a8"; 
}
.su-spoiler-icon-angle-circle-down.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0a9"; 
}
.su-spoiler-icon-angle-circle-right .su-spoiler-icon:before { 
	content: "\\f0da"; 
}
.su-spoiler-icon-angle-circle-right.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0d9"; 
}
.su-spoiler-icon-chevron .su-spoiler-icon:before { 
	content: "\\f078"; 
}
.su-spoiler-icon-chevron.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f077"; 
}
.su-spoiler-icon-chevron-circle-down .su-spoiler-icon:before { 
	content: "\\f13a"; 
}
.su-spoiler-icon-chevron-circle-down.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f139"; 
}
.su-spoiler-icon-chevron-circle-right .su-spoiler-icon:before { 
	content: "\\f138"; 
}
.su-spoiler-icon-chevron-circle-right.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f137"; 
}
.su-spoiler-icon-caret .su-spoiler-icon:before { 
	content: "\\f0d7"; 
}
.su-spoiler-icon-caret.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0d6"; 
}
.su-spoiler-icon-caret-circle-down .su-spoiler-icon:before { 
	content: "\\f0a2"; 
}
.su-spoiler-icon-caret-circle-down.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0a3"; 
}
.su-spoiler-icon-caret-circle-right .su-spoiler-icon:before { 
	content: "\\f0a4"; 
}
.su-spoiler-icon-caret-circle-right.su-spoiler-closed .su-spoiler-icon:before { 
	content: "\\f0a5"; 
}
';

		return $css;
	}

	/**
	 * Dynamic_css placeholder
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$spoilers  = parent::get_setting( $block, 'spoilers', array() );
		$shortcode = '';

		foreach ( $spoilers as $spoiler => $params ) {

			$title   = isset( $params['title'] ) ? $params['title'] : '';
			$open    = isset( $params['open'] ) ? $params['open'] : '';
			$style   = isset( $params['style'] ) ? $params['style'] : '';
			$icon    = isset( $params['icon'] ) ? $params['icon'] : '';
			$anchor  = isset( $params['anchor'] ) ? $params['anchor'] : '';
			$content = isset( $params['content'] ) ? $params['content'] : '';

			if ( is_null( $title ) ) {
				$title = 'Title';
			}

			if ( is_null( $open ) ) {
				$open = 'no';
			}

			if ( is_null( $style ) ) {
				$style = 'default';
			}

			if ( is_null( $icon ) ) {
				$icon = 'plus';
			}

			if ( is_null( $anchor ) ) {
				$anchor = 'none';
			}

			if ( function_exists( 'padma_render_spoiler' ) ) {
				$html = padma_render_spoiler(
					array(
						'title'   => $title,
						'open'    => $open,
						'style'   => $style,
						'icon'    => $icon,
						'anchor'  => $anchor,
						'class'   => '',
						'content' => $content,
					)
				);
			} else {
				$html = do_shortcode( '[su_spoiler title="' . $title . '" open="' . $open . '" style="' . $style . '" icon="' . $icon . '" anchor="' . $anchor . '" class=""]' . $content . '[/su_spoiler]' );
			}

			// remove inline CSS for color.
			$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

			$shortcode .= $html;

		}

		echo $shortcode;	

	}

}
