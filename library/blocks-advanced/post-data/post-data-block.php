<?php
/**
 * Post Data Blocks
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
 * Post Data Block
 */
class PadmaVisualElementsBlockPostData extends \PadmaBlockAPI {

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
		$this->id            = 've-postdata';
		$this->name          = __( 'Post Data', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockPostDataOptions';
		$this->description   = __( 'Allows to display various post fields, including post title, post content, modified date etc . ', 'padma' );
		$this->categories    = array( 'content' );
	}

	    /**
     * Register the session_start() at the earliest point in WordPress.
     */
    public function register_session_start() {
        // Stelle sicher, dass session_start() nur aufgerufen wird, wenn noch keine Sitzung aktiv ist
        if ( session_status() !== PHP_SESSION_ACTIVE ) {
            session_start();
        }
    }

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return function_exists( 'padma_render_post' ) || function_exists( 'padma_render_meta' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'content',
				'name'     => __( 'Content', 'padma' ),
				'selector' => '.ve-postdata',			
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'text',
				'name'     => __( 'Text', 'padma' ),
				'selector' => '.ve-postdata p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h1',
				'name'     => __( 'Content h1', 'padma' ),
				'selector' => '.ve-postdata h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h2',
				'name'     => __( 'Content h2', 'padma' ),
				'selector' => '.ve-postdata h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h3',
				'name'     => __( 'Content h3', 'padma' ),
				'selector' => '.ve-postdata h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h4',
				'name'     => __( 'Content h4', 'padma' ),
				'selector' => '.ve-postdata h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h5',
				'name'     => __( 'Content h5', 'padma' ),
				'selector' => '.ve-postdata h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h6',
				'name'     => __( 'Content h6', 'padma' ),
				'selector' => '.ve-postdata h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-li',
				'name'     => __( 'Content li', 'padma' ),
				'selector' => '.ve-postdata li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-a',
				'name'     => __( 'Content link', 'padma' ),
				'selector' => '.ve-postdata a',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-image',
				'name'     => __( 'Content image', 'padma' ),
				'selector' => '.ve-postdata image',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-figure',
				'name'     => __( 'Content figure', 'padma' ),
				'selector' => '.ve-postdata figure',
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

		global $post;

		$session_id = 've-postdata-post-id-' . $block['id'];

		if ( ! isset( $_SESSION[ $session_id ] ) && empty( $_SESSION[ $session_id ] ) ) {
			if ( $post->ID && is_null( $_SESSION[ $session_id ] ) ) {
				$_SESSION[ 've-postdata-post-id-' . $block['id'] ] = $post->ID;
			}
		}

		$field   = trim( parent::get_setting( $block, 'field', 'post_title' ) );
		$default = parent::get_setting( $block, 'default' );
		$before  = parent::get_setting( $block, 'before' );
		$after   = parent::get_setting( $block, 'after' );
		$post_id = ( parent::get_setting( $block, 'post-id' ) ) ? parent::get_setting( $block, 'post-id' ) : $post->ID;

		if ( ! $post_id && ! is_null( $_SESSION[ 've-postdata-post-id-' . $block['id'] ] ) ) {
			$post_id = $_SESSION[ 've-postdata-post-id-' . $block['id'] ];
		}

		$shortcode = '[su_post field="' . $field . '" post_id="' . $post_id . '"]';

		$html = '<div class="ve-postdata">' . do_shortcode( $shortcode ) . '</div>';

		echo $html;

	}
}
