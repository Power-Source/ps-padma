<?php
/**
 * Performance-Optimierungs-Konfiguration für Slider-Block
 * Ermöglicht feinere Kontrolle über Caching, Batch-Größen, etc.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance-Konfiguration
 */
class PadmaSliderOptimizationConfig {

    /**
     * Cache-Einstellungen
     */
    public static $cache = array(
        'enable' => true,
        'ttl' => 86400, // 24 Stunden
        'driver' => 'transient', // 'transient' oder 'object-cache'
        'clear_on_attachment_update' => true
    );

    /**
     * Image-Resize-Einstellungen
     */
    public static $image_resize = array(
        'enable_batch' => true,
        'batch_size' => 10, // Max. Bilder pro Batch
        'use_cache' => true,
        'compression_quality' => 85, // JPEG-Qualität
        'generate_webp' => false, // Optional: WebP-Generierung
        'enable_lazy_loading' => true
    );

    /**
     * Uploader-Einstellungen
     */
    public static $uploader = array(
        'enable_ajax' => true,
        'max_file_size' => 5242880, // 5MB
        'allowed_types' => array('image/jpeg', 'image/png', 'image/gif', 'image/webp'),
        'batch_upload' => true,
        'async_metadata' => true, // Asynchrone Metadaten-Generierung
        'progress_tracking' => true
    );

    /**
     * Repeater-Einstellungen
     */
    public static $repeater = array(
        'enable_virtualization' => true,
        'visible_items' => 5, // Anzahl sichtbarer Items beim Virtual Scrolling
        'scroll_buffer' => 2, // Extra Items laden
        'debounce_update' => 500, // ms
        'use_request_animation_frame' => true
    );

    /**
     * Media Library Einstellungen
     */
    public static $media_library = array(
        'pagination' => true,
        'per_page' => 20,
        'caching' => true,
        'cache_ttl' => 3600
    );

    /**
     * Performance Monitoring
     */
    public static $monitoring = array(
        'enable' => true,
        'track_cache_hits' => true,
        'track_resize_time' => true,
        'log_file' => 'padma-performance.log'
    );

    /**
     * Filter um Konfiguration zu überschreiben
     *
     * Beispiel:
     * add_filter('padma_slider_optimization_config', function($config) {
     *     $config['cache']['ttl'] = 3600; // 1 Stunde statt 24h
     *     return $config;
     * });
     */
    public static function get_config() {
        $monitoring_config = self::$monitoring;
        $monitoring_config['enable'] = defined('WP_DEBUG') && WP_DEBUG;
        $monitoring_config['log_file'] = WP_CONTENT_DIR . '/' . $monitoring_config['log_file'];

        $config = array(
            'cache' => self::$cache,
            'image_resize' => self::$image_resize,
            'uploader' => self::$uploader,
            'repeater' => self::$repeater,
            'media_library' => self::$media_library,
            'monitoring' => $monitoring_config
        );

        return apply_filters('padma_slider_optimization_config', $config);
    }

    /**
     * Prüfe ob Caching aktiviert ist
     */
    public static function is_cache_enabled() {
        $config = self::get_config();
        return $config['cache']['enable'];
    }

    /**
     * Prüfe ob Batch-Resize aktiviert ist
     */
    public static function is_batch_resize_enabled() {
        $config = self::get_config();
        return $config['image_resize']['enable_batch'];
    }

    /**
     * Prüfe ob AJAX-Uploader aktiviert ist
     */
    public static function is_ajax_uploader_enabled() {
        $config = self::get_config();
        return $config['uploader']['enable_ajax'];
    }

    /**
     * Prüfe ob Virtual Repeater aktiviert ist
     */
    public static function is_virtual_repeater_enabled() {
        $config = self::get_config();
        return $config['repeater']['enable_virtualization'];
    }

    /**
     * Get Cache TTL
     */
    public static function get_cache_ttl() {
        $config = self::get_config();
        return $config['cache']['ttl'];
    }

    /**
     * Get Batch Size für Uploads
     */
    public static function get_batch_size() {
        $config = self::get_config();
        return $config['image_resize']['batch_size'];
    }

    /**
     * Logging Funktion für Performance-Monitoring
     */
    public static function log($message, $type = 'info') {
        $config = self::get_config();
        
        if (!$config['monitoring']['enable']) {
            return;
        }

        $log_file = $config['monitoring']['log_file'];
        $timestamp = current_time('mysql');
        $log_line = "[$timestamp] [$type] $message\n";

        error_log($log_line, 3, $log_file);
    }

    /**
     * Track Cache Hit
     */
    public static function track_cache_hit() {
        if (!self::get_config()['monitoring']['track_cache_hits']) {
            return;
        }

        $hits = wp_cache_get('padma_cache_hits', 'cache_stats') ?: 0;
        wp_cache_set('padma_cache_hits', $hits + 1, 'cache_stats');
    }

    /**
     * Track Cache Miss
     */
    public static function track_cache_miss() {
        if (!self::get_config()['monitoring']['track_cache_hits']) {
            return;
        }

        $misses = wp_cache_get('padma_cache_misses', 'cache_stats') ?: 0;
        wp_cache_set('padma_cache_misses', $misses + 1, 'cache_stats');
    }

    /**
     * Get Cache Stats
     */
    public static function get_cache_stats() {
        return array(
            'hits' => wp_cache_get('padma_cache_hits', 'cache_stats') ?: 0,
            'misses' => wp_cache_get('padma_cache_misses', 'cache_stats') ?: 0,
            'hit_rate' => self::calculate_hit_rate()
        );
    }

    /**
     * Calculate Hit Rate
     */
    private static function calculate_hit_rate() {
        $hits = wp_cache_get('padma_cache_hits', 'cache_stats') ?: 0;
        $misses = wp_cache_get('padma_cache_misses', 'cache_stats') ?: 0;
        $total = $hits + $misses;

        if ($total === 0) {
            return 0;
        }

        return round(($hits / $total) * 100, 2);
    }

    /**
     * Reset Stats
     */
    public static function reset_stats() {
        wp_cache_delete('padma_cache_hits', 'cache_stats');
        wp_cache_delete('padma_cache_misses', 'cache_stats');
    }

    /**
     * Display Stats (für Admin-Dashboard)
     */
    public static function display_stats() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $stats = self::get_cache_stats();

        echo '<div class="padma-performance-stats" style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin: 20px 0;">';
        echo '<h3>Slider Block Performance Stats</h3>';
        echo '<p><strong>Cache Hits:</strong> ' . $stats['hits'] . '</p>';
        echo '<p><strong>Cache Misses:</strong> ' . $stats['misses'] . '</p>';
        echo '<p><strong>Hit Rate:</strong> ' . $stats['hit_rate'] . '%</p>';
        echo '<button class="button button-small" onclick="padmaResetStats()">Reset Stats</button>';
        echo '</div>';
    }
}

/**
 * AJAX Handler für Stats-Reset
 */
add_action('wp_ajax_padma_reset_performance_stats', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    check_ajax_referer('padma_nonce', 'nonce');
    PadmaSliderOptimizationConfig::reset_stats();
    wp_send_json_success('Stats reset');
});

/**
 * Admin Bar Menu (für schnellen Zugriff auf Stats)
 */
add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $stats = PadmaSliderOptimizationConfig::get_cache_stats();

    $wp_admin_bar->add_menu(array(
        'id' => 'padma-performance',
        'title' => 'Padma: ' . $stats['hit_rate'] . '% Hit Rate',
        'href' => '#'
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'padma-performance',
        'id' => 'padma-cache-hits',
        'title' => 'Cache Hits: ' . $stats['hits']
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'padma-performance',
        'id' => 'padma-cache-misses',
        'title' => 'Cache Misses: ' . $stats['misses']
    ));

}, 100);
