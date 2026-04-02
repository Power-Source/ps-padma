<?php
/**
 * Hero Block
 *
 * @link       https://power-source.github.io/ps-padma/
 * @since      1.1.4
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/public
 * @author     PSOURCE <support@psource.eimen.net>
 */

namespace Padma_Advanced;

/**
 * Hero Block
 */
class PadmaVisualElementsBlockHero extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-hero';
		$this->name          = __( 'Hero-Bereich', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockHeroOptions';
		$this->description   = __( 'Großer Intro-Bereich mit Hintergrundbild, Text und Call-to-Action.', 'padma' );
		$this->categories    = array( 'content' );
	}

	/**
	 * Init
	 */
	public function init() {
		return function_exists( 'padma_render_hero' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element( array(
			'id'       => 'hero',
			'name'     => __( 'Hero-Bereich', 'padma' ),
			'selector' => '.su-hero',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-media',
			'parent'   => 'hero',
			'name'     => __( 'Hintergrundbild', 'padma' ),
			'selector' => '.su-hero-media',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-overlay',
			'parent'   => 'hero',
			'name'     => __( 'Overlay', 'padma' ),
			'selector' => '.su-hero-overlay',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-content-wrap',
			'parent'   => 'hero',
			'name'     => __( 'Inhaltscontainer', 'padma' ),
			'selector' => '.su-hero-content',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-subtitle',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Untertitel', 'padma' ),
			'selector' => '.su-hero-subtitle',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-title',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Titel', 'padma' ),
			'selector' => '.su-hero-title',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-text',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Text', 'padma' ),
			'selector' => '.su-hero-text',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-text-links',
			'parent'   => 'hero-text',
			'name'     => __( 'Textlinks', 'padma' ),
			'selector' => '.su-hero-text a',
			'states'   => array(
				'Hover'   => '.su-hero-text a:hover',
				'Clicked' => '.su-hero-text a:active',
			),
		) );

		$this->register_block_element( array(
			'id'       => 'hero-actions',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Button-Bereich', 'padma' ),
			'selector' => '.su-hero-actions',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-button',
			'parent'   => 'hero-actions',
			'name'     => __( 'Button', 'padma' ),
			'selector' => '.su-hero-actions .su-button',
			'states'   => array(
				'Hover'   => '.su-hero-actions .su-button:hover',
				'Clicked' => '.su-hero-actions .su-button:active',
			),
		) );

		$this->register_block_element( array(
			'id'       => 'hero-button-text',
			'parent'   => 'hero-button',
			'name'     => __( 'Button-Text', 'padma' ),
			'selector' => '.su-hero-actions .su-button span',
			'states'   => array(
				'Hover'   => '.su-hero-actions .su-button:hover span',
				'Clicked' => '.su-hero-actions .su-button:active span',
			),
		) );

		$this->register_block_element( array(
			'id'       => 'hero-button-description',
			'parent'   => 'hero-button-text',
			'name'     => __( 'Button-Beschreibung', 'padma' ),
			'selector' => '.su-hero-actions .su-button span small',
		) );

		$this->register_block_element( array(
			'id'       => 'hero-button-icon',
			'parent'   => 'hero-button-text',
			'name'     => __( 'Button-Symbol', 'padma' ),
			'selector' => '.su-hero-actions .su-button span i, .su-hero-actions .su-button span img',
		) );
	}

	/**
	 * Dynamic CSS function
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block    Block Object.
	 * @return string
	 */
	public static function dynamic_css( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		return '';
	}

	/**
	 * Padma Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$args = array(
			'title'             => parent::get_setting( $block, 'title' ),
			'subtitle'          => parent::get_setting( $block, 'subtitle' ),
			'background_image'  => parent::get_setting( $block, 'background_image' ),
			'button_text'       => parent::get_setting( $block, 'button_text' ),
			'button_url'        => parent::get_setting( $block, 'button_url' ),
			'button_target'     => parent::get_setting( $block, 'button_target', 'self' ),
			'use_inline_styles' => false,
			'use_button_style'  => false,
		);

		$content = parent::get_setting( $block, 'content' );

		echo padma_render_hero( $args, $content );
	}

	/**
	 * Register styles and scripts
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block    Is Block?.
	 * @return void
	 */
	public static function enqueue_action( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		\PadmaCompiler::register_file( array(
			'name'         => 've-hero-css',
			'format'       => 'css',
			'fragments'    => array(
				get_template_directory() . '/assets/css/psource-shortcodes/hero-shortcodes.css',
			),
			'dependencies' => array(),
			'enqueue'      => true,
		) );
	}
}