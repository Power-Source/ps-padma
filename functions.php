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

/* Make sure PHP 7.0 or newer is installed and WordPress 3.4 or newer is installed. */
require_once get_template_directory() . '/library/common/compatibility-checks.php';

/* Load required packages */
require_once get_template_directory() . '/vendor/autoload.php';

/* Load Padma! */
require_once get_template_directory() . '/library/common/functions.php';
require_once get_template_directory() . '/library/common/parse-php.php';
require_once get_template_directory() . '/library/common/settings.php';
require_once get_template_directory() . '/library/loader.php';

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
