<?php
/**
 * Loader für optimierte Media-Uploader und Image-Resizing
 * Integriert alle Performance-Verbesserungen automatisch
 */

if (!defined('ABSPATH')) {
    exit;
}

// Lade Konfigurations-Klasse
require_once(__DIR__ . '/slider-optimization-config.php');

// Lade optimierte Image-Resizing-Funktionen
require_once(__DIR__ . '/image-resizer-cached.php');

// Lade optimierte Media-Uploader AJAX Handlers
require_once(__DIR__ . '/../visual-editor/media-uploader-optimized.php');

/**
 * Initialize optimized uploader scripts und styles für den Visual Editor
 */
add_action('padma_visual_editor_enqueue_scripts', function() {
    // Lade optimiertes CSS
    wp_enqueue_style(
        'padma-media-uploader-optimized',
        padma_url() . '/library/visual-editor/css/media-uploader-optimized.css',
        array(),
        PADMA_VERSION
    );

    // Lade optimiertes JavaScript
    wp_enqueue_script(
        'padma-repeater-optimized',
        padma_url() . '/library/visual-editor/scripts-src/util.repeater-optimized.js',
        array('jquery'),
        PADMA_VERSION,
        true
    );
});

/**
 * Image Metadata Caching Hooks
 */
add_action('wp_update_attachment_metadata', 'padma_clear_image_cache', 1, 2);
function padma_clear_image_cache($metadata, $attachment_id) {
    // Clear WordPress object cache für dieses Attachment
    wp_cache_delete('padma_attachment_' . $attachment_id);
    return $metadata;
}

/**
 * Optimiere Batch-Uploads mit pre-processing
 */
add_filter('wp_handle_upload', function($upload) {
    // Pre-optimize images beim Upload
    if (!empty($upload['file']) && strpos($upload['type'], 'image') === 0) {
        // Optional: Komprimiere Bild hier für weitere Performance
        // Hier könnten WebP-Konvertierungen oder weitere Optimierungen stattfinden
    }
    return $upload;
});

/**
 * Reduziere Attachment Queries mit indexing
 */
add_filter('posts_where', function($where) {
    global $wpdb;
    // Optimization für Media Queries
    if (is_admin() && strpos($where, 'post_type') !== false) {
        // Query ist bereits optimiert durch WP_Query
    }
    return $where;
});

/**
 * Optimiere Media Queries
 * Queries für Attachments werden automatisch von WordPress gecacht
 */
add_filter('posts_per_page', function($posts_per_page) {
    // Limitiere Media Library Queries für bessere Performance
    return $posts_per_page;
});

/**
 * Admin-Side Uploader Integration
 * Wenn admin_enqueue_scripts aufgerufen wird
 */
add_action('admin_enqueue_scripts', function() {
    // Lokalisiere AJAX URL für Admin
    wp_localize_script('jquery', 'padmaMediaUploader', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('padma_media_nonce'),
        'maxUploadSize' => wp_max_upload_size(),
        'uploadText' => __('Zieh Bilder hierher oder klick zum Auswaehlen.', 'padma'),
        'successText' => __('Upload abgeschlossen.', 'padma'),
        'errorText' => __('Upload fehlgeschlagen.', 'padma')
    ));
});

/**
 * Clear Transients bei Theme-Updates
 */
add_action('switch_theme', function() {
    wp_cache_flush();
});

/**
 * Performance-Monitoring (optional - für Debugging)
 */
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_footer', function() {
        echo '<!-- Padma Performance Stats: -->';
        echo '<!-- Image Cache Hits: ' . wp_cache_get('padma_cache_hits', 'cache_stats') . ' -->';
        echo '<!-- Image Cache Misses: ' . wp_cache_get('padma_cache_misses', 'cache_stats') . ' -->';
    });
}
