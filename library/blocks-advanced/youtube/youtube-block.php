<?php
/**
 * YouTube Blocks
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
 * YouTube Block
 */
class PadmaVisualElementsBlockYoutube extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-youtube';
		$this->name          = 'YouTube';
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockYoutubeOptions';
		$this->description   = __( 'Allows you to insert responsive YouTube videos. You can create playlists using Youtube Advanced block . ', 'padma' );
		$this->categories    = array( 'media' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_youtube' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'youtube',
				'name'     => 'Youtube',
				'selector' => 'div.su-youtube',
			)
		);
	}

	/**
	 * Ensure media shortcode styles are available in frontend and VE iframe.
	 */
	public function enqueue_action( $block_id ) {
		if ( function_exists( 'padma_query_asset' ) ) {
			padma_query_asset( 'css', 'media-shortcodes' );
			return;
		}

		if ( function_exists( 'su_query_asset' ) ) {
			su_query_asset( 'css', 'media-shortcodes' );
			return;
		}

		wp_enqueue_style(
			'padma-media-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/media-shortcodes.css',
			array(),
			'1.0'
		);
	}

	/**
	 * Padma Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$url        = parent::get_setting( $block, 'url' );
		$width      = parent::get_setting( $block, 'width' );
		$height     = parent::get_setting( $block, 'height' );
		$responsive = parent::get_setting( $block, 'responsive' );
		$autoplay   = parent::get_setting( $block, 'autoplay' );
		$mute       = parent::get_setting( $block, 'mute' );

		if ( ! $responsive ) {
			$responsive = 'yes';
		}

		if ( $width < 200 ) {
			$width = 200;
		}

		if ( $width > 1600 ) {
			$width = 1600;
		}

		if ( $height < 200 ) {
			$height = 200;
		}

		if ( $height > 1600 ) {
			$height = 1600;
		}

		if ( ! in_array( $autoplay, array( 'yes', 'no' ), true ) ) {
			$autoplay = 'no';
		}

		if ( ! in_array( $mute, array( 'yes', 'no' ), true ) ) {
			$mute = 'no';
		}

		echo do_shortcode( '[su_youtube url="' . $url . '" responsive="' . $responsive . '" autoplay="' . $autoplay . '" mute="' . $mute . '" width="' . $width . '" height="' . $height . '" ]' );

	}

}
