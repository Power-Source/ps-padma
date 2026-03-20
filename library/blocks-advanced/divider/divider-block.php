<?php

/**
 * Divider Blocks
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
 * Divider Block
 */
class PadmaVisualElementsBlockDivider extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-divider';
		$this->name          = __( 'Trenner', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockDividerOptions';
		$this->description   = __( 'Ermöglicht das Teilen von Seiteninhalten mit einem gestalteten Trenner. Du kannst Farben anpassen, den „Nach oben“-Link ausblenden und die Größe des Trenners anpassen.', 'padma' );
		$this->categories    = array( 'content' );

		$this->inline_editable              = array( 'block-title', 'block-subtitle', 'su-divider' );
		$this->inline_editable_equivalences = array( 'su-divider' => 'text' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_divider' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'divider',
				'name'     => __( 'Trenner', 'padma' ),
				'selector' => '.su-divider',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'link',
				'name'     => __( 'Link', 'padma' ),
				'selector' => '.su-divider a',
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

		$top    = parent::get_setting( $block, 'top' );
		$text   = parent::get_setting( $block, 'text' );
		$style  = parent::get_setting( $block, 'style' );
		$size   = parent::get_setting( $block, 'size' );
		$margin = parent::get_setting( $block, 'margin' );

		if ( ! $top ) {
			$top = 'yes';
		}

		if ( ! $text ) {
			$text = __( 'Nach oben', 'padma' );
		}

		if ( ! $style ) {
			$style = 'default';
		}

		if ( ! $size || $size < 0 || $size > 40 ) {
			$size = 3;
		}

		if ( ! $margin || $margin < 0 || $margin > 200 ) {
			$margin = 15;
		}

		$html = do_shortcode( '[su_divider top="' . $top . '" text="' . $text . '" style="' . $style . '" size="' . $size . '" margin="' . $margin . '" class="text"]' );

		// remove inline CSS for color.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}
}
