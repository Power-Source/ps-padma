<?php
/**
 * Spacer Blocks
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
 * Spacer Block
 */
class PadmaVisualElementsBlockSpacer extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-spacer';
		$this->name          = __( 'Spacer', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockSpacerOptions';
		$this->description   = __( 'Will help you to create an empty space between elements on a page . ', 'padma' );
		$this->categories    = array( 'content' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_spacer' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'spacer',
				'name'     => __( 'spacer', 'padma' ),
				'selector' => 'div.su-spacer',
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

		$size = parent::get_setting( $block, 'size' );

		if ( ! $size || $size < 0 || $size > 800 ) {
			$size = 20;
		}

		echo do_shortcode( '[su_spacer size="' . $size . '"]' );

	}
}
