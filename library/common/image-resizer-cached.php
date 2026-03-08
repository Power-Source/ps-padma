<?php
/**
 * Optimierter Image-Resizer mit Caching
 * Verhindert redundante Resize-Operationen
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Optimierte Image-Resize-Funktion mit Caching
 * @param string $url - Original-Bild-URL
 * @param int $width - Zielbreite
 * @param int $height - Zielhöhe
 * @param bool $crop - Zuschneiden
 * @param bool $single - Single oder Array
 * @param bool $upscale - Hochskalieren
 * @return string Resized Image URL
 */
function padma_resize_image_cached(
    $url,
    $width = null,
    $height = null,
    $crop = true,
    $single = true,
    $upscale = true
) {
    if (!$url) {
        return null;
    }

    // Generiere Caching-Key basierend auf URL und Parametern
    $cache_key = 'padma_resize_' . md5($url . $width . $height . $crop . $upscale);
    
    // Versuche aus Cache zu laden
    $cached_result = wp_cache_get($cache_key);
    if ($cached_result !== false) {
        return $cached_result;
    }

    // Lade die Image-Resizer Klasse nur einmal pro Request
    Padma::load('common/image-resizer');
    $PadmaImageResize = PadmaImageResize::getInstance();
    
    $resized_image = $PadmaImageResize->process($url, $width, $height, $crop, false, $upscale);

    if (is_wp_error($resized_image)) {
        return $url . '#' . $resized_image->get_error_code();
    }

    $result = $resized_image['url'];
    
    // Speichere im Cache für 24 Stunden
    wp_cache_set($cache_key, $result, '', 86400);
    
    return $result;
}

/**
 * Batch-Resize für mehrere Bilder
 * Deutlich schneller als einzelne resize() Aufrufe
 * @param array $images - Array von Bild-URLs
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array Resized URLs
 */
function padma_resize_images_batch($images, $width = null, $height = null, $crop = true) {
    if (!is_array($images) || empty($images)) {
        return array();
    }

    // Lade Image-Resizer einmal
    Padma::load('common/image-resizer');
    $PadmaImageResize = PadmaImageResize::getInstance();

    $results = array();
    
    foreach ($images as $image_url) {
        if (empty($image_url)) {
            continue;
        }

        $cache_key = 'padma_resize_' . md5($image_url . $width . $height . $crop);
        $cached = wp_cache_get($cache_key);
        
        if ($cached !== false) {
            $results[] = $cached;
            continue;
        }

        $resized = $PadmaImageResize->process($image_url, $width, $height, $crop, false, true);
        
        if (!is_wp_error($resized)) {
            $url = $resized['url'];
            wp_cache_set($cache_key, $url, '', 86400);
            $results[] = $url;
        } else {
            $results[] = $image_url;
        }
    }

    return $results;
}

/**
 * Konvertiere padma_resize_image() Aufrufe zu gecachten Versionen
 * (Backward Compatibility)
 */
if (!function_exists('padma_resize_image')) {
    function padma_resize_image(
        $url,
        $width = null,
        $height = null,
        $crop = true,
        $single = true,
        $upscale = true
    ) {
        return padma_resize_image_cached($url, $width, $height, $crop, $single, $upscale);
    }
}

/**
 * Cache-Invalidierung bei Attachment-Änderungen
 */
add_action('delete_attachment', function($attachment_id) {
    // Lösche alle Cache-Einträge für dieses Attachment
    $attachment_url = wp_get_attachment_url($attachment_id);
    wp_cache_delete('padma_resize_' . md5($attachment_url . '*'));
});

add_action('wp_update_attachment_metadata', function($metadata, $attachment_id) {
    // Invalidiere Cache bei Metadaten-Updates
    wp_cache_flush();
    return $metadata;
}, 10, 2);
