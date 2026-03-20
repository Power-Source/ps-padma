<?php
/**
 * Dummy Image Blocks
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
 * Dummy Image Block
 */
class PadmaVisualElementsBlockDummyImage extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-dummy-image';
		$this->name          = __( 'Dummy Bild', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockDummyImageOptions';
		$this->description   = __( 'Ermöglicht das Anzeigen eines Dummy-Bildes. Du kannst das Bildthema und die Größe ändern.', 'padma' );
		$this->categories    = array( 'media' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_dummy_image' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'dummy-image',
				'name'     => __( 'Dummy Bild', 'padma' ),
				'selector' => '.su-dummy-image',
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

		$width  = parent::get_setting( $block, 'width' );
		$height = parent::get_setting( $block, 'height' );
		$theme  = parent::get_setting( $block, 'theme' );

		if ( is_null( $width ) ) {
			$width = 500;
		}

		if ( $width < 10 ) {
			$width = 10;
		}

		if ( $width > 1600 ) {
			$width = 1600;
		}

		if ( is_null( $height ) ) {
			$height = 300;
		}

		if ( $height < 10 ) {
			$height = 10;
		}

		if ( $height > 1600 ) {
			$height = 1600;
		}

		if ( is_null( $theme ) ) {
			$theme = 'any';
		}

		$html = do_shortcode( '[su_dummy_image width="' . $width . '" height="' . $height . '" theme="' . $theme . '" class=""]' );

		// remove inline CSS for color.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}

}
