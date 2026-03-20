<?php

/**
 * Basic Heading Blocks
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
 * Basic heading block
 */
class PadmaVisualElementsBlockBasicHeading extends \PadmaBlockAPI {

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
	 * Block inline editable fields
	 *
	 * @var array $inline_editable
	 */
	public $inline_editable;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->id              = 've-basic-heading';
		$this->name            = __( 'Basis Überschrift', 'padma' );
		$this->options_class   = 'Padma_Advanced\PadmaVisualElementsBlockBasicHeadingOptions';
		$this->description     = __( 'Eine Überschrift kann als Titel, Abschnittsüberschrift und/oder Unterüberschrift dienen. Du kannst jeder Überschrift eine relative Wichtigkeit zuweisen, von H1 bis H6. Tipp: Suchmaschinen (und Menschen!) verwenden Überschriften, um die wichtigsten Themen und Inhalte Ihrer Inhalte zu bestimmen.', 'padma' );
		$this->categories      = array( 'content', 'basic', 'typography' );
		$this->inline_editable = array( 'block-title', 'block-subtitle', 'basic-heading' );
	}

	/**
	 * Init
	 */
	public function init() {}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h1',
				'name'     => __( 'Überschrift H1', 'padma' ),
				'selector' => 'h1',
				'states'   => array(
					'Hover' => 'h1:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h2',
				'name'     => __( 'Überschrift H2', 'padma' ),
				'selector' => 'h2',
				'states'   => array(
					'Hover' => 'h2:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h3',
				'name'     => __( 'Überschrift H3', 'padma' ),
				'selector' => 'h3',
				'states'   => array(
					'Hover' => 'h3:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h4',
				'name'     => __( 'Überschrift H4', 'padma' ),
				'selector' => 'h4',
				'states'   => array(
					'Hover' => 'h4:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h5',
				'name'     => __( 'Überschrift H5', 'padma' ),
				'selector' => 'h5',
				'states'   => array(
					'Hover' => 'h5:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h6',
				'name'     => __( 'Überschrift H6', 'padma' ),
				'selector' => 'h6',
				'states'   => array(
					'Hover' => 'h6:hover',
				),
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

		$text = parent::get_setting( $block, 'basic-heading' );
		$tag  = parent::get_setting( $block, 'tag', 'h1' );

		echo sprintf( '<%s class="basic-heading" >%s</%s>', $tag, $text, $tag );

	}

	/**
	 * Register styles and scripts
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Is Block?.
	 * @return void
	 */
	public static function enqueue_action( $block_id, $block = false ) {}

}
