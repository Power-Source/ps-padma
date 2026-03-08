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
		$this->name          = __( 'Tabs', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockTabsOptions';
		$this->description   = __( 'Allows you to create tabbed content with multiple tabs', 'padma' );
		$this->categories    = array( 'box' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_tabs' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'tabs',
				'name'     => __( 'Tabs', 'padma' ),
				'selector' => '.su-tabs',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tabs-nav',
				'name'     => __( 'Tabs Navigation', 'padma' ),
				'selector' => '.su-tabs-nav',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tab-item',
				'name'     => __( 'Tab Item', 'padma' ),
				'selector' => '.su-tabs-nav > span',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tab-item-active',
				'name'     => __( 'Tab Item (Active)', 'padma' ),
				'selector' => '.su-tabs-nav > span.su-tabs-current',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tab-item-inactive',
				'name'     => __( 'Tab Item (Inactive)', 'padma' ),
				'selector' => '.su-tabs-nav > span:not(.su-tabs-current)',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'tabs-panes',
				'name'     => __( 'Tabs Panes', 'padma' ),
				'selector' => '.su-tabs-panes',
			)
		);
	}

	/**
	 * Dynamic_js function - Trigger tab initialization
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Block Object.
	 * @return string
	 */
	public static function dynamic_js( $block_id, $block = false ) {
		return '(function($) {
			if (typeof $ === "undefined") return;
			
			$(document).ready(function() {
				// Trigger tab initialization from other-shortcodes.js manually
				var tabBlocks = $(".block-type-visual-elements-tabs .su-tabs");
				
				if (tabBlocks.length > 0) {
					tabBlocks.each(function() {
						var $this = $(this);
						var active = parseInt($this.data("active")) - 1;
						if (active < 0) active = 0;
						
						// Get tab headers
						var $headers = $this.find(".su-tabs-nav span");
						var $panes = $this.find(".su-tabs-pane");
						
						// Hide all panes
						$panes.hide();
						
						// Remove current class from all headers
						$headers.removeClass("su-tabs-current");
						
						// Activate selected tab
						if (active < $headers.length) {
							$headers.eq(active).addClass("su-tabs-current");
							$panes.eq(active).show();
						}
						
						// Attach click handlers
						$headers.off("click").on("click", function(e) {
							e.preventDefault();
							var $tab = $(this);
							var index = $tab.index();
							var $tabs = $tab.parents(".su-tabs");
							var $allHeaders = $tabs.find(".su-tabs-nav span");
							var $allPanes = $tabs.find(".su-tabs-pane");
							
							// Hide all, show this
							$allPanes.hide();
							$allHeaders.removeClass("su-tabs-current");
							$allHeaders.eq(index).addClass("su-tabs-current");
							$allPanes.eq(index).show();
						});
					});
				}
			});
		})(jQuery);';
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
				'title'    => isset( $params['title'] ) ? $params['title'] : __( 'Tab', 'padma' ),
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

		// Try Padma asset system first
		if ( function_exists( 'padma_query_asset' ) ) {
			padma_query_asset( 'css', 'box-shortcodes' );
			padma_query_asset( 'css', 'other-shortcodes' );
			padma_query_asset( 'js', 'other-shortcodes' );
			return;
		}

		// Try plugin asset system second
		if ( function_exists( 'su_query_asset' ) ) {
			su_query_asset( 'css', 'su-box-shortcodes' );
			su_query_asset( 'css', 'su-other-shortcodes' );
			su_query_asset( 'js', 'su-other-shortcodes' );
			return;
		}

		// Fallback to direct enqueue
		wp_enqueue_style(
			'padma-box-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/box-shortcodes.css',
			array(),
			'1.0'
		);

		wp_enqueue_style(
			'padma-other-shortcodes-css',
			get_template_directory_uri() . '/assets/css/psource-shortcodes/other-shortcodes.css',
			array(),
			'1.0'
		);

		wp_enqueue_script(
			'padma-other-shortcodes-js',
			get_template_directory_uri() . '/assets/js/psource-shortcodes/other-shortcodes.js',
			array( 'jquery' ),
			'1.0',
			true
		);
	}
}
