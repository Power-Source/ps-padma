<?php

/**
 * Lightbox Blocks
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
 * Lightbox Block
 */
class PadmaVisualElementsBlockLightbox extends \PadmaBlockAPI {

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
		$this->id            = 've-lightbox';
		$this->name          = __( 'Lightbox', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockLightboxOptions';	
		$this->description   = __( 'Allows you to display various elements in a pop-up window. You can display an image, a web page, or any HTML content . ', 'padma' );
		$this->categories    = array( 'content' );

		$this->inline_editable = array( 'block-title', 'block-subtitle', 'su-lightbox' );	

		$this->inline_editable_equivalences = array( 'su-lightbox' => 'title' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_lightbox' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => __( 'Title', 'padma' ),
				'selector' => '.su-lightbox',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => __( 'Title', 'padma' ),
				'selector' => '.su-lightbox',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content',
				'name'     => __( 'Content', 'padma' ),
				'selector' => 'div.su-lightbox-content',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-text',
				'name'     => __( 'Content text', 'padma' ),
				'selector' => '.su-lightbox-content p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h1',
				'name'     => __( 'Content h1', 'padma' ),
				'selector' => '.su-lightbox-content h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h2',
				'name'     => __( 'Content h2', 'padma' ),
				'selector' => '.su-lightbox-content h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h3',
				'name'     => __( 'Content h3', 'padma' ),
				'selector' => '.su-lightbox-content h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h4',
				'name'     => __( 'Content h4', 'padma' ),
				'selector' => '.su-lightbox-content h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h5',
				'name'     => __( 'Content h5', 'padma' ),
				'selector' => '.su-lightbox-content h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h6',
				'name'     => __( 'Content h6', 'padma' ),
				'selector' => '.su-lightbox-content h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-li',
				'name'     => __( 'Content li', 'padma' ),
				'selector' => '.su-lightbox-content li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-a',
				'name'     => __( 'Content link', 'padma' ),
				'selector' => '.su-lightbox-content a',
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

		$type   = parent::get_setting( $block, 'type', 'image' );
		$title  = parent::get_setting( $block, 'title' );
		$image  = parent::get_setting( $block, 'image' );
		$iframe = parent::get_setting( $block, 'iframe' );
		$inline = parent::get_setting( $block, 'inline' );

		$lightbox_args = array(
			'type' => $type,
		);

		// Set source based on type
		switch ( $type ) {
			case 'image':
				$lightbox_args['src'] = $image;
				break;
			case 'iframe':
				$lightbox_args['src'] = $iframe;
				break;
			case 'inline':
				$lightbox_args['src'] = '#' . $block['id'];
				break;
			default:
				$lightbox_args['src'] = $image;
		}

		// Render lightbox trigger
		$html = padma_render_lightbox( $lightbox_args, esc_html( $title ) );

		// If type is inline, also render the content container
		if ( 'inline' === $type ) {
			$content_args = array(
				'id' => $block['id'],
			);
			$html .= padma_render_lightbox_content( $content_args, $inline );
		}

		echo $html;

	}

	/**
	 * Register styles and scripts
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Is Block?.
	 * @return void
	 */
	public static function enqueue_action( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		$css_file = PADMA_LIBRARY_DIR . '/blocks-advanced/lightbox/lightbox.css';

		/* CSS */
		\PadmaCompiler::register_file(
			array(
				'name'         => 've-lightbox-css',
				'format'       => 'css',
				'fragments'    => array(
					$css_file,
				),
				'dependencies' => array(),
				'enqueue'      => true,
			)
		);
	}
}
