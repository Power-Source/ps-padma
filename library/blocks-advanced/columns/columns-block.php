<?php
/**
 * Accordion Blocks
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
 * Columns Block
 */
class PadmaVisualElementsBlockColumns extends \PadmaBlockAPI {

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

		$this->id            = 'visual-elements-columns';
		$this->name          = __( 'Spalten', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockColumnsOptions';
		$this->description   = __( 'Hilft den Seiteninhalt in Spalten zu unterteilen.', 'padma' );
		$this->categories    = array( 'box' );

	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_column' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'columns',
				'name'     => __( 'Spalten', 'padma' ),
				'selector' => '.su-row',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'column',
				'parent'   => 'columns',
				'name'     => __( 'Spalte', 'padma' ),
				'selector' => '.su-column',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'column-inner',
				'parent'   => 'column',
				'name'     => __( 'Spalteninhalt', 'padma' ),
				'selector' => '.su-column-inner',
			)
		);
	}

	/**
	 * Dynamic_css function
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Block Object.
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

		$columns   = parent::get_setting( $block, 'columns', array() );
		$shortcode = '[su_row class=""]';
		$size_map  = array(
			'full-width'   => '1/1',
			'one-half'     => '1/2',
			'one-third'    => '1/3',
			'two-third'    => '2/3',
			'one-fourth'   => '1/4',
			'three-fourth' => '3/4',
			'one-fifth'    => '1/5',
			'two-fifth'    => '2/5',
			'three-fifth'  => '3/5',
			'four-fifth'   => '4/5',
			'one-sixth'    => '1/6',
			'five-sixth'   => '5/6',
		);

		foreach ( $columns as $column => $params ) {

			$size    = isset( $params['size'] ) ? $params['size'] : '';
			$center  = isset( $params['center'] ) ? $params['center'] : '';
			$class   = isset( $params['class'] ) ? $params['class'] : '';
			$content = isset( $params['content'] ) ? $params['content'] : '';

			if ( isset( $size_map[ $size ] ) ) {
				$size = $size_map[ $size ];
			}

			if ( empty( $size ) ) {
				$size = '1/2';
			}

			$shortcode .= '[su_column ';
			$shortcode .= 'size="' . $size . '" ';
			$shortcode .= 'center="' . $center . '" ';
			$shortcode .= 'class="' . $class . '" ';
			$shortcode .= ']';
			$shortcode .= $content;
			$shortcode .= '[/su_column]';

		}

		$shortcode .= '[/su_row]';

		echo do_shortcode( $shortcode );

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
