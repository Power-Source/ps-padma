<?php
/**
 * Padma Theme main function file
 *
 * @since 1.0.0
 * @package Padma
 *
 * - Original by Clay Griffiths - Headway Themes
 * - New files by Maarten Schraven - UNITED 7
 * - Padma by PS Padma Team - PS Padma S.A.
 */

/**
 *
 * Load Padma
 */

/* Prevent direct access to this file */
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	die( 'Please do not access this file directly.' );
}

/* Catalog membership verification defaults (can be overridden in wp-config.php). */
if ( ! defined( 'PADMA_MEMBERSHIP_API_BASE' ) ) {
    define( 'PADMA_MEMBERSHIP_API_BASE', 'https://nerdservice.eimen.net/wp-json/membership2/v1' );
}

if ( ! defined( 'PADMA_MEMBERSHIP_API_PASS_KEY' ) ) {
    define( 'PADMA_MEMBERSHIP_API_PASS_KEY', 'HarvH5Ri4E5QOUBS' );
}

if ( ! defined( 'PADMA_MEMBERSHIP_REQUIRED_ID' ) ) {
    define( 'PADMA_MEMBERSHIP_REQUIRED_ID', 582 );
}

/* Optional: shared secret header for trusted membership auth bypass on nerdservice. */
if ( ! defined( 'PADMA_MEMBERSHIP_AUTH_BYPASS_KEY' ) ) {
    define( 'PADMA_MEMBERSHIP_AUTH_BYPASS_KEY', '' );
}

/* Catalog API defaults (key should be set in wp-config.php or env var). */
if ( ! defined( 'PADMA_CATALOG_API_BASE' ) ) {
    define( 'PADMA_CATALOG_API_BASE', 'https://eimen.net/padma-catalog/api' );
}

if ( ! defined( 'PADMA_CATALOG_API_KEY' ) ) {
    $padma_catalog_key = getenv( 'PADMA_CATALOG_API_KEY' );

    if ( ! is_string( $padma_catalog_key ) || trim( $padma_catalog_key ) === '' ) {
        $local_catalog_config = get_template_directory() . '/catalog-deploy/public_html/padma-catalog/api/_config.php';

        if ( file_exists( $local_catalog_config ) && is_readable( $local_catalog_config ) ) {
            $config_contents = file_get_contents( $local_catalog_config );

            if ( is_string( $config_contents ) ) {
                if ( preg_match( "/const\\s+PADMA_CATALOG_API_KEY\\s*=\\s*'([^']+)'\\s*;/", $config_contents, $matches ) ) {
                    $candidate = trim( (string) ( $matches[1] ?? '' ) );
                    if ( $candidate !== '' && $candidate !== 'BITTE_HIER_LANGEN_RANDOM_KEY_SETZEN' ) {
                        $padma_catalog_key = $candidate;
                    }
                }
            }
        }
    }

    define( 'PADMA_CATALOG_API_KEY', is_string( $padma_catalog_key ) ? trim( $padma_catalog_key ) : '' );
}

/* Make sure PHP 7.0 or newer is installed and WordPress 3.4 or newer is installed. */
require_once get_template_directory() . '/library/common/compatibility-checks.php';

/* Load required packages */
$padma_vendor_autoload = get_template_directory() . '/vendor/autoload.php';
if ( file_exists( $padma_vendor_autoload ) ) {
    require_once $padma_vendor_autoload;
}

/* Load Padma! */
require_once get_template_directory() . '/library/common/functions.php';
require_once get_template_directory() . '/library/common/parse-php.php';
require_once get_template_directory() . '/library/common/settings.php';
require_once get_template_directory() . '/library/loader.php';

// Ensure shortcode generator AJAX actions are always available in admin/ajax requests.
if ( is_admin() ) {
    new Padma_Shortcode_Generator();
}

/* Load Shortcode Functions (extracted from psource-shortcodes) */
require_once get_template_directory() . '/library/shortcode-functions/helpers.php';
require_once get_template_directory() . '/library/shortcode-functions/assets.php';

// Advanced Blocks (VE2 Integration)
require_once get_template_directory() . '/library/shortcode-functions/button.php';
require_once get_template_directory() . '/library/shortcode-functions/hero.php';
require_once get_template_directory() . '/library/shortcode-functions/accordion.php';
require_once get_template_directory() . '/library/shortcode-functions/box.php';
require_once get_template_directory() . '/library/shortcode-functions/quote.php';
require_once get_template_directory() . '/library/shortcode-functions/tabs.php';
require_once get_template_directory() . '/library/shortcode-functions/lightbox.php';

// Standard Shortcodes (Modular)
require_once get_template_directory() . '/library/shortcode-functions/typography.php';
require_once get_template_directory() . '/library/shortcode-functions/media.php';
require_once get_template_directory() . '/library/shortcode-functions/layout.php';
require_once get_template_directory() . '/library/shortcode-functions/interactive.php';
require_once get_template_directory() . '/library/shortcode-functions/galleries.php';
require_once get_template_directory() . '/library/shortcode-functions/posts.php';
require_once get_template_directory() . '/library/shortcode-functions/utility.php';

// Shortcode Registration (with fallback system)
require_once get_template_directory() . '/library/shortcode-functions/register.php';

Padma::init();


/**
 *
 * Plugin templates support
 */

add_filter(
	'template_include',
	function( $template ) {
		return PadmaDisplay::load_plugin_template( $template );
	}
);

// PS Update Manager - Hinweis wenn nicht installiert
add_action( 'admin_notices', function() {
    // Prüfe ob Update Manager aktiv ist
    if ( ! function_exists( 'ps_register_product' ) && current_user_can( 'install_plugins' ) ) {
        $screen = get_current_screen();
        if ( $screen && in_array( $screen->id, array( 'plugins', 'plugins-network' ) ) ) {
            // Prüfe ob bereits installiert aber inaktiv
            $plugin_file = 'ps-update-manager/ps-update-manager.php';
            $all_plugins = get_plugins();
            $is_installed = isset( $all_plugins[ $plugin_file ] );
            
            echo '<div class="notice notice-warning is-dismissible"><p>';
            echo '<strong>PS Chat:</strong> ';
            
            if ( $is_installed ) {
                // Installiert aber inaktiv - Aktivierungs-Link
                $activate_url = wp_nonce_url(
                    admin_url( 'plugins.php?action=activate&plugin=' . urlencode( $plugin_file ) ),
                    'activate-plugin_' . $plugin_file
                );
                echo sprintf(
                    __( 'Aktiviere den <a href="%s">PS Update Manager</a> für automatische Updates von GitHub.', 'psource-chat' ),
                    esc_url( $activate_url )
                );
            } else {
                // Nicht installiert - Download-Link
                echo sprintf(
                    __( 'Installiere den <a href="%s" target="_blank">PS Update Manager</a> für automatische Updates aller PSource Plugins & Themes.', 'psource-chat' ),
                    'https://github.com/Power-Source/ps-update-manager/releases/latest'
                );
            }
            
            echo '</p></div>';
        }
    }
});
