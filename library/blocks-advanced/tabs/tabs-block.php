<?php
/**
 * Tabs Block
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
 * Tabs Block
 */
class PadmaVisualElementsBlockTabs extends \PadmaBlockAPI {

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
		$this->id            = 'visual-elements-tabs';
		$this->name          = __( 'Tabs', 'padma-advanced' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockTabsOptions';
		$this->description   = __( 'Allows you to create tabbed content with multiple tabs', 'padma-advanced' );
		$this->categories    = array( 'box' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_tabs' ) || class_exists( 'PSOURCE_Shortcodes' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'tabs',
				'name'     => __( 'Tabs', 'padma-advanced' ),
				'selector' => '.su-tabs',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tabs-nav',
				'name'     => __( 'Tabs Navigation', 'padma-advanced' ),
				'selector' => '.su-tabs-nav',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tabs-panes',
				'name'     => __( 'Tabs Panes', 'padma-advanced' ),
				'selector' => '.su-tabs-panes',
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
		$tabs_list = parent::get_setting( $block, 'tabs', array() );
		$vertical  = parent::get_setting( $block, 'vertical', 'no' );
		$style     = parent::get_setting( $block, 'style', 'default' );

		// Prepare tabs arguments
		$tabs_args = array(
			'vertical' => $vertical,
			'style'    => $style,
		);

		// Prepare tab items
		$tab_items = array();
		foreach ( $tabs_list as $tab => $params ) {
			$tab_items[] = array(
				'title'    => isset( $params['title'] ) ? $params['title'] : __( 'Tab', 'padma-advanced' ),
				'disabled' => isset( $params['disabled'] ) ? $params['disabled'] : 'no',
				'anchor'   => isset( $params['anchor'] ) ? $params['anchor'] : '',
				'url'      => isset( $params['url'] ) ? $params['url'] : '',
				'target'   => isset( $params['target'] ) ? $params['target'] : 'blank',
				'content'  => isset( $params['content'] ) ? $params['content'] : '',
				'class'    => isset( $params['class'] ) ? $params['class'] : '',
			);
		}

		// Render tabs
		$html = padma_render_tabs( $tabs_args, $tab_items );

		// remove inline CSS.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

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
		$style = parent::get_setting( $block, 'style' );
		if ( 'none' !== $style ) {
			\PadmaCompiler::register_file(
				array(
					'name'         => 've-tabs-css',
					'format'       => 'css',
					'fragments'    => array(
						__DIR__ . '/tabs.css',
					),
					'dependencies' => array(),
					'enqueue'      => true,
				)
			);
		}
	}
}
