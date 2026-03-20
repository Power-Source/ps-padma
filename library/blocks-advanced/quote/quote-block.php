<?php
/**
 * Quote Block
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
 * Quote Block
 */
class PadmaVisualElementsBlockQuote extends \PadmaBlockAPI {

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
$this->id            = 'visual-elements-quote';
$this->name          = __( 'Quote', 'padma' );
$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockQuoteOptions';
$this->description   = __( 'Allows you to create customizable quote blocks with author citation', 'padma' );
$this->categories    = array( 'box' );
}

/**
 * Init
 */
public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_quote' );
	}

public function setup_elements() {
$this->register_block_element(
array(
'id'       => 'quote',
'name'     => __( 'Quote', 'padma' ),
'selector' => '.su-quote',
)
);
$this->register_block_element(
array(
'id'       => 'quote-inner',
'name'     => __( 'Quote Inner', 'padma' ),
'selector' => '.su-quote-inner',
)
);
$this->register_block_element(
array(
'id'       => 'quote-cite',
'name'     => __( 'Quote Citation', 'padma' ),
'selector' => '.su-quote-cite',
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
$quote   = parent::get_setting( $block, 'quote' );
$cite    = parent::get_setting( $block, 'cite' );
$url     = parent::get_setting( $block, 'url' );

// Render quote using native function
$quote_args = array(
	'cite' => $cite,
	'url'  => $url,
	);

$html = padma_render_quote( $quote_args, $quote );

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
'name'         => 've-quote-css',
'format'       => 'css',
'fragments'    => array(
__DIR__ . '/quote.css',
),
'dependencies' => array(),
'enqueue'      => true,
)
);
}
}
}
