<?php
/**
 * PS Padma: Asset Enquiry System
 * Loads CSS/JS for shortcodes from plugin or local assets
 * 
 * This system allows the theme to work with or without the psource-shortcodes plugin
 */

class PadmaAssets {
	
	/**
	 * Initialize asset enquiry
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_shortcode_assets' ), 999 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_shortcode_assets' ), 999 );
	}
	
	/**
	 * Check if plugin is active and has asset enquiry function
	 */
	public static function has_plugin_assets() {
		return function_exists( 'su_query_asset' );
	}
	
	/**
	 * Get asset URL (from plugin or local)
	 */
	public static function get_asset_url( $type, $handle ) {
		// If plugin is active, use plugin's asset function
		if ( self::has_plugin_assets() ) {
			su_query_asset( $type, $handle );
			return true;
		}
		
		// Otherwise, use local assets
		return self::enqueue_local_asset( $type, $handle );
	}
	
	/**
	 * Enqueue local assets (CSS/JS)
	 */
	public static function enqueue_local_asset( $type, $handle ) {
		$theme_url = get_template_directory_uri();
		$asset_map = self::get_asset_map();
		
		if ( ! isset( $asset_map[ $type ][ $handle ] ) ) {
			return false;
		}
		
		$asset = $asset_map[ $type ][ $handle ];
		$src = $theme_url . '/assets/' . $type . '/psource-shortcodes/' . $asset['file'];
		
		if ( $type === 'css' ) {
			wp_enqueue_style( 'padma-' . $handle, $src, $asset['deps'], '1.0' );
		} else {
			wp_enqueue_script( 'padma-' . $handle, $src, $asset['deps'], '1.0', true );
		}
		
		return true;
	}
	
	/**
	 * Asset mapping: handle => [file, dependencies]
	 */
	public static function get_asset_map() {
		return array(
			'css' => array(
				'animate-css' => array(
					'file' => 'animate.css',
					'deps' => array()
				),
				'box-shortcodes' => array(
					'file' => 'box-shortcodes.css',
					'deps' => array()
				),
				'content-shortcodes' => array(
					'file' => 'content-shortcodes.css',
					'deps' => array()
				),
				'hero-shortcodes' => array(
					'file' => 'hero-shortcodes.css',
					'deps' => array()
				),
				'galleries-shortcodes' => array(
					'file' => 'galleries-shortcodes.css',
					'deps' => array()
				),
				'magnific-popup' => array(
					'file' => 'magnific-popup.css',
					'deps' => array()
				),
				'media-shortcodes' => array(
					'file' => 'media-shortcodes.css',
					'deps' => array()
				),
				'other-shortcodes' => array(
					'file' => 'other-shortcodes.css',
					'deps' => array()
				),
				'owl-carousel' => array(
					'file' => 'owl-carousel.css',
					'deps' => array()
				),
				'players-shortcodes' => array(
					'file' => 'players-shortcodes.css',
					'deps' => array()
				),
				'qtip-css' => array(
					'file' => 'qtip.css',
					'deps' => array()
				),
				'simpleslider' => array(
					'file' => 'simpleslider.css',
					'deps' => array()
				),
				'sunrise' => array(
					'file' => 'sunrise.css',
					'deps' => array()
				),
			),
			'js' => array(
				'swiper' => array(
					'file' => 'swiper.js',
					'deps' => array( 'jquery' )
				),
				'owl-carousel' => array(
					'file' => 'owl-carousel.js',
					'deps' => array( 'jquery' )
				),
				'simpleslider' => array(
					'file' => 'simpleslider.js',
					'deps' => array( 'jquery' )
				),
				'galleries-shortcodes' => array(
					'file' => 'galleries-shortcodes.js',
					'deps' => array( 'jquery', 'swiper' )
				),
				'magnific-popup' => array(
					'file' => 'magnific-popup.js',
					'deps' => array( 'jquery' )
				),
				'qtip-js' => array(
					'file' => 'qtip.js',
					'deps' => array( 'jquery' )
				),
				'jplayer' => array(
					'file' => 'jplayer.js',
					'deps' => array( 'jquery' )
				),
				'players-shortcodes' => array(
					'file' => 'players-shortcodes.js',
					'deps' => array( 'jquery', 'jplayer' )
				),
				'other-shortcodes' => array(
					'file' => 'other-shortcodes.js',
					'deps' => array( 'jquery' )
				),
				'inview' => array(
					'file' => 'jquery.inview.js',
					'deps' => array( 'jquery' )
				),
				'jsrender' => array(
					'file' => 'jsrender.js',
					'deps' => array( 'jquery' )
				),
				'form' => array(
					'file' => 'form.js',
					'deps' => array( 'jquery' )
				),
				'chart' => array(
					'file' => 'chart.js',
					'deps' => array( 'jquery' )
				),
			)
		);
	}
	
	/**
	 * Enqueue all shortcode assets on frontend
	 */
	public static function enqueue_shortcode_assets() {
		// If plugin is active, let it handle assets
		if ( self::has_plugin_assets() ) {
			return;
		}
		
		// Otherwise, enqueue commonly used assets
		// This ensures core functionality works without plugin
		self::enqueue_local_asset( 'css', 'other-shortcodes' );
		self::enqueue_local_asset( 'js', 'other-shortcodes' );
		self::enqueue_local_asset( 'css', 'content-shortcodes' );
		self::enqueue_local_asset( 'css', 'hero-shortcodes' );
		self::enqueue_local_asset( 'css', 'box-shortcodes' );
	}
}

// Initialize on init hook
add_action( 'init', array( 'PadmaAssets', 'init' ) );

/**
 * Enqueue bundled Font Awesome for shortcode output.
 *
 * Some extracted shortcodes render Font Awesome markup directly and cannot
 * rely on the original plugin asset loader being present.
 */
function padma_enqueue_fontawesome_assets() {
	static $enqueued = false;

	if ( $enqueued ) {
		return;
	}

	$fontawesome_url = trailingslashit( padma_url() ) . 'library/blocks-advanced/fontawesome/fontawesome.css';
	wp_enqueue_style( 'padma-ve-fontawesome', $fontawesome_url, array(), PADMA_VERSION, 'all' );

	$enqueued = true;
}

/**
 * Helper function to enqueue assets (similar to plugin API)
 */
function padma_query_asset( $type, $handle ) {
	return PadmaAssets::get_asset_url( $type, $handle );
}
