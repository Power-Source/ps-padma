<?php
/**
 * Button Blocks
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
 * Button Block
 */
class PadmaVisualElementsBlockButton extends \PadmaBlockAPI {


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
$this->id            = 'visual-elements-button';
$this->name          = __( 'Schaltfläche', 'padma' );
$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockButtonOptions';
$this->description   = __( 'Ermöglicht das Erstellen hochgradig anpassbarer Schaltflächen. Du kannst den Schaltflächenstil, die Farben, die Größe ändern, ein Symbol oder eine Beschreibung hinzufügen.', 'padma' );
$this->categories    = array( 'content' );
}

/**
 * Init
 */
public function init() {
		// Check if native render function is available
		// Fallback to PSOURCE_Shortcodes plugin if available
		return function_exists( 'padma_render_button' );
	}

public function setup_elements() {

$this->register_block_element(
array(
'id'       => 'button',
'name'     => __( 'Schaltfläche', 'padma' ),
'selector' => 'a.su-button',
'states'   => array(
'Hover'   => 'a.su-button:hover',
'Clicked' => 'a.su-button:active',
),
)
);

$this->register_block_element(
array(
'id'       => 'icon',
'name'     => __( 'Symbol', 'padma' ),
'selector' => 'a.su-button span i',
)
);

$this->register_block_element(
array(
'id'       => 'text',
'name'     => __( 'Text', 'padma' ),
'selector' => 'a.su-button span small',
'states'   => array(
'Hover'   => 'a.su-button span small:hover',
'Clicked' => 'a.su-button span small:active',
),
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

$css = '#block-' . $block_id . ' .su-button small{ opacity: 1 }';

return $css;
}

/**
 * Padma Content Method
 *
 * @param object $block Block.
 * @return void
 */
public function content( $block ) {

$text    = parent::get_setting( $block, 'text' );
$url     = parent::get_setting( $block, 'url' );
$target  = parent::get_setting( $block, 'target', 'self' );
$style   = parent::get_setting( $block, 'style', 'default' );
$icon    = parent::get_setting( $block, 'icon' );
$desc    = parent::get_setting( $block, 'desc' );
$onclick = parent::get_setting( $block, 'onclick' );
$rel     = parent::get_setting( $block, 'rel' );
$title   = parent::get_setting( $block, 'title' );

// Prepare icon for rendering
if ( $icon && ! filter_var( $icon, FILTER_VALIDATE_URL ) ) {
	$icon = 'icon:' . $icon;
}

// Render button using native function
$button_args = array(
	'url'     => $url,
	'target'  => $target,
	'style'   => $style,
	'icon'    => $icon,
	'desc'    => $desc,
	'onclick' => $onclick,
	'rel'     => $rel,
	'title'   => $title,
);

$html = padma_render_button( $button_args, $text );

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

/* CSS */
\PadmaCompiler::register_file(
array(
'name'         => 've-button-css',
'format'       => 'css',
'fragments'    => array(
__DIR__ . '/button.css',
),
'dependencies' => array(),
'enqueue'      => true,
)
);
}
}
}
