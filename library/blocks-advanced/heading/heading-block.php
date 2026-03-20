<?php

/**
 * Heading Blocks
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
 * Heading Block
 */
class PadmaVisualElementsBlockHeading extends \PadmaBlockAPI {

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
	 * Inline editable fields
	 *
	 * @var array $inline_editable;
	 */
	public $inline_editable;

	/**
	 * Inline editable fields equivalences
	 *
	 * @var array $inline_editable_equivalences
	 */
	public $inline_editable_equivalences;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id            = 'visual-elements-heading';
		$this->name          = __( 'Heading', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockHeadingOptions';
		$this->description   = __( 'Allows you to create styled headings with customisable size and margin . ', 'padma' );
		$this->categories    = array( 'content' );

		$this->inline_editable = array( 'block-title', 'block-subtitle', 'su-heading-inner' );

		$this->inline_editable_equivalences = array( 'su-heading-inner' => 'heading-text' );
	}		

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_heading' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'heading',
				'name'     => 'heading',
				'selector' => '.su-heading',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'su-heading-inner',
				'name'     => 'Text',
				'selector' => '.su-heading-inner',
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

		$style        = parent::get_setting( $block, 'style' );
		$heading_text = parent::get_setting( $block, 'heading-text' );

		if ( ! $style ) {
			$style = 'default';
		}

		$html = do_shortcode( '[su_heading style="' . $style . '"]' . $heading_text . '[/su_heading]' );

		// remove inline CSS for color.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}
}
