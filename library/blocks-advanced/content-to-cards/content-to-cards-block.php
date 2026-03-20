<?php
/**
 * Content to Cards Blocks
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
 * Content to Cards Block
 */
class PadmaVisualElementsBlockContentToCards extends \PadmaBlockAPI {

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
		$this->id            = 've-content-to-cards';
		$this->name          = __( 'Content to Cards', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockContentToCardsOptions';
		$this->description   = __( 'Allows you to display expandable posts . ', 'padma' );
		$this->categories    = array( 'box', 'content', 'dynamic-content' );
	}

	/**
	 * Init
	 */
	public function init() {
		// Shortcodes are registered in library/shortcode-functions/register.php
		return true; // Uses custom card rendering
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => '.ve-card-posts-container',
				'name'     => __( 'Container', 'padma' ),
				'selector' => '.ve-card-posts-container',
			)
		);

		$this->register_block_element(
			array(
				'id'       => '.ve-card-posts-container-item',
				'name'     => __( 'item', 'padma' ),
				'selector' => '.ve-card-posts-container li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => '.content-wrapper',
				'name'     => __( 'Content', 'padma' ),
				'selector' => '.ve-card-posts-container .content-wrapper',
			)
		);

		$this->register_block_element(
			array(
				'id'       => '.cd-title',
				'name'     => __( 'Title', 'padma' ),
				'selector' => '.ve-card-posts-container .cd-title h2',
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
	public static function dynamic_css( $block_id, $block ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		$posts = \PadmaQuery::get_posts( $block );

		$css = '';

		foreach ( $posts as $key => $post ) {

			$image = get_the_post_thumbnail_url( $post->ID );
			$css  .= '.single-post.post-id-' . $post->ID . ' .cd-title::before { background-image: url(' . $image . '); }';
		}

		$css .= '.ve-card-posts-container .cd-title{ min-height: 220px; height: auto; }';
		return $css;

	}

	/**
	 * Dynamic JS - Asset loading for VE context
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Block Object.
	 * @return string
	 */
	public static function dynamic_js( $block_id, $block ) {

		if ( ! $block ) {
			$block = \PadmaBlocksData::get_block( $block_id );
		}

		$path = padma_url() . '/library/blocks-advanced/content-to-cards/';

		// Direct asset injection for VE iframe context
		// This runs even when enqueue_action is blocked in VE
		return "
		(function() {
			var basePath = '" . $path . "';
			
			function ensureStyle(href) {
				var links = document.getElementsByTagName('link');
				for (var i = 0; i < links.length; i++) {
					if (links[i].href === href) return;
				}
				var link = document.createElement('link');
				link.rel = 'stylesheet';
				link.href = href;
				document.head.appendChild(link);
			}
			
			function ensureScript(src) {
				var scripts = document.getElementsByTagName('script');
				for (var i = 0; i < scripts.length; i++) {
					if (scripts[i].src === src) return;
				}
				var script = document.createElement('script');
				script.src = src;
				document.head.appendChild(script);
			}
			
			// Load assets
			ensureStyle(basePath + 'content-to-cards.css');
			ensureScript(basePath + 'content-to-cards.js');
		})();
		";
	}

	/**
	 * Padma Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$scroll_text = parent::get_setting( $block, 'scroll-text', 'Scroll down' );

		$posts = \PadmaQuery::get_posts( $block );

		$html  = '<button class="cd-nav-trigger"><span aria-hidden="true" class="cd-icon"></span></button>';
		$html .= '<div class="ve-card-posts-container">';
		$html .= '<ul>';

		foreach ( $posts as $key => $post ) {

			$id     = $post->ID;
			$image  = get_the_post_thumbnail_url( $post->ID );
			$desc   = $post->post_excerpt;
			$title  = $post->post_title;
			$url    = get_post_permalink( $post->ID );
			$date   = date( 'M d, Y', strtotime( $post->post_date ) );
			$author = get_the_author_meta( 'display_name', $post->post_author );

			$post_link = get_permalink( $id );

			$html .= '<li class="single-post post-id-' . $id . '">';
			$html .= '<div class="cd-title">';
			$html .= '<h2>' . $title . '</h2>';
			$html .= '</div>';
			$html .= '<div class="ve-card-post-info">';
			$html .= '<button class="ve-card-scroll">' . $scroll_text . '</button>';
			$html .= '<div class="content-wrapper">';
			$html .= $post->post_content;
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</li>';

		}

		$html .= '</ul>';
		$html .= '</div>';

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

		$path = padma_url() . '/library/blocks-advanced/content-to-cards/';

		// CSS.
		wp_enqueue_style( 'padma-ve-content-to-cards', $path . 'content-to-cards.css', array(), PADMA_VERSION );

		/* JS */
		wp_enqueue_script( 'padma-ve-content-to-cards', $path . 'content-to-cards.js', array( 'jquery' ), PADMA_VERSION, true );
	}

}
