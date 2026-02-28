<?php
/**
 * Box Block
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
 * Box Block
 */
class PadmaVisualElementsBlockBox extends \PadmaBlockAPI {

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
$this->id            = 'visual-elements-box';
$this->name          = __( 'Box', 'padma-advanced' );
$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockBoxOptions';
$this->description   = __( 'Allows you to create customizable boxes with title and custom colors', 'padma-advanced' );
$this->categories    = array( 'box' );
}

/**
 * Init
 */
public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_box' ) || class_exists( 'PSOURCE_Shortcodes' );
	}

public function setup_elements() {
$this->register_block_element(
array(
'id'       => 'box',
'name'     => __( 'Box', 'padma-advanced' ),
'selector' => '.su-box',
)
);
$this->register_block_element(
array(
'id'       => 'box-title',
'name'     => __( 'Box Title', 'padma-advanced' ),
'selector' => '.su-box-title',
)
);
$this->register_block_element(
array(
'id'       => 'box-content',
'name'     => __( 'Box Content', 'padma-advanced' ),
'selector' => '.su-box-content',
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
$title   = parent::get_setting( $block, 'title' );
$style   = parent::get_setting( $block, 'style', 'default' );
$content = parent::get_setting( $block, 'content' );

// Render box using native function
$box_args = array(
	'title' => $title,
	'style' => $style,
	);
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
'name'         => 've-box-css',
'format'       => 'css',
'fragments'    => array(
__DIR__ . '/box.css',
),
'dependencies' => array(),
'enqueue'      => true,
)
);
}
}
}
