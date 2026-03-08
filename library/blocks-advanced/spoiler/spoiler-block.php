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
		if ( function_exists( 'padma_query_asset' ) ) {
			padma_query_asset( 'css', 'box-shortcodes' );
			padma_query_asset( 'css', 'other-shortcodes' );
			padma_query_asset( 'js', 'other-shortcodes' );
			return;
		}

		if ( function_exists( 'su_query_asset' ) ) {
			su_query_asset( 'css', 'su-box-shortcodes' );
			su_query_asset( 'css', 'su-other-shortcodes' );
			su_query_asset( 'js', 'su-other-shortcodes' );
			return;
		}

		wp_enqueue_style(
			'padma-box-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/box-shortcodes.css',
			array(),
			'1.0'
		);

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
	 * Padma Content Method
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
