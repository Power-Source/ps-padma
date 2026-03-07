<?php
/**
 * Accordion Blocks
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
 * Accordion Block
 */
class PadmaVisualElementsBlockAccordion extends \PadmaBlockAPI {

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

$this->id            = 'visual-elements-accordion';
$this->name          = __( 'Accordion', 'padma' );
$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockAccordionOptions';
$this->description   = __( 'Allows you to create blocks with hidden content – spoilers (toggles). Hidden content will be shown when block title will be clicked. You can specify different icons or even use different styles for each spoiler . ', 'padma' );
$this->categories    = array( 'box' );

}

/**
 * Init
 */
public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_accordion' );
	}

public function setup_elements() {

$this->register_block_element(
array(
'id'       => 'spoiler',
'name'     => __( 'Spoiler', 'padma' ),
'selector' => '.su-spoiler',
)
);
$this->register_block_element(
array(
'id'       => 'spoiler-title',
'name'     => __( 'Spoiler Title', 'padma' ),
'selector' => '.su-accordion .su-spoiler-title',
)
);
$this->register_block_element(
array(
'id'       => 'spoiler-icon',
'name'     => __( 'Spoiler icon', 'padma' ),
'selector' => '.su-accordion .su-spoiler-icon',
)
);
$this->register_block_element(
array(
'id'       => 'spoiler-content',
'name'     => __( 'Spoiler content', 'padma' ),
'selector' => '.su-accordion .su-spoiler-content',
)
);
$this->register_block_element(
array(
'id'       => 'spoiler-content-p',
'name'     => __( 'Spoiler content paragraph', 'padma' ),
'selector' => '.su-accordion .su-spoiler-content p',
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

$accordion_class = parent::get_setting( $block, 'accordion-class', array() );
if ( empty( $accordion_class ) ) {
$accordion_class = '';
}

$spoilers = parent::get_setting( $block, 'spoilers', array() );

// Prepare accordion arguments
$accordion_args = array(
'class' => $accordion_class,
);

// Prepare spoiler items
$spoiler_items = array();
foreach ( $spoilers as $spoiler => $params ) {
$spoiler_items[] = array(
'title'   => isset( $params['title'] ) ? $params['title'] : __( 'Title', 'padma' ),
'open'    => isset( $params['open'] ) ? $params['open'] : 'no',
'style'   => isset( $params['style'] ) ? $params['style'] : 'default',
'icon'    => isset( $params['icon'] ) ? $params['icon'] : 'plus',
'anchor'  => isset( $params['anchor'] ) ? $params['anchor'] : '',
'class'   => isset( $params['class'] ) ? $params['class'] : '',
'content' => isset( $params['content'] ) ? $params['content'] : '',
);
}

// Render accordion
$html = padma_render_accordion( $accordion_args, $spoiler_items );

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
}

}
