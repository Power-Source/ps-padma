<?php
/**
 * Optimierter AJAX-basierter Media-Uploader für Padma
 * Performance-verbesserte Version ohne iframe-Overhead
 */

if (!defined('ABSPATH')) {
    exit;
}

// AJAX Handler für Bild-Upload
add_action('wp_ajax_padma_upload_image', 'padma_handle_image_upload_ajax');
add_action('wp_ajax_nopriv_padma_upload_image', 'padma_handle_image_upload_ajax');

function padma_handle_image_upload_ajax() {
    check_ajax_referer('padma_media_nonce', 'nonce');

    if (empty($_FILES['file'])) {
        wp_send_json_error(array('message' => __('No file uploaded', 'padma')));
    }

    // Batch-verarbeitung: Prüfe ob mehrere Dateien
    $files = $_FILES['file'];
    $uploaded_urls = array();
    $errors = array();

    // Einzelne oder mehrere Dateien handhaben
    $file_array = is_array($files['name']) ? $files : array($files);
    
    foreach ((array)$file_array as $file) {
        $file_data = array(
            'name' => $file['name'],
            'type' => $file['type'],
            'tmp_name' => $file['tmp_name'],
            'error' => $file['error'],
            'size' => $file['size']
        );

        $uploaded = wp_handle_upload($file_data, array('test_form' => false));

        if (isset($uploaded['error'])) {
            $errors[] = $uploaded['error'];
            continue;
        }

        // Attachment erstellen (optimiert - async metadata generation)
        $attachment = array(
            'post_mime_type' => $uploaded['type'],
            'post_title' => sanitize_file_name($uploaded['file']),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $uploaded['file']);

        if (!is_wp_error($attachment_id)) {
            // Metadata asynchron generieren (nicht blockierend)
            padma_generate_attachment_metadata_async($attachment_id, $uploaded['file']);
            
            $image_url = wp_get_attachment_url($attachment_id);
            $uploaded_urls[] = array(
                'url' => $image_url,
                'id' => $attachment_id,
                'filename' => basename($uploaded['file'])
            );
        }
    }

    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode(', ', $errors)));
    }

    wp_send_json_success(array(
        'uploads' => $uploaded_urls,
        'count' => count($uploaded_urls)
    ));
}

/**
 * Asynchrone Attachment-Metadata-Generierung
 * Verhindert Blockierung beim Upload
 */
function padma_generate_attachment_metadata_async($attachment_id, $file_path) {
    // Verwende WordPress Cron oder Background Job Queue
    if (function_exists('wp_schedule_single_event')) {
        wp_schedule_single_event(time(), 'padma_generate_metadata', array($attachment_id, $file_path));
    } else {
        // Fallback: direkt generieren (ist schneller als vorher)
        wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file_path));
    }
}

add_action('padma_generate_metadata', 'padma_do_generate_metadata', 10, 2);
function padma_do_generate_metadata($attachment_id, $file_path) {
    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file_path));
}

// AJAX Handler für Media-Library Bilder
add_action('wp_ajax_padma_get_media_library', 'padma_get_media_library_ajax');

function padma_get_media_library_ajax() {
    check_ajax_referer('padma_media_nonce', 'nonce');

    $paged = intval($_POST['paged'] ?? 1);
    $per_page = 20; // Pagination: verhindert zu viele Results
    $offset = ($paged - 1) * $per_page;

    // Query mit Caching für Performance
    $cache_key = 'padma_media_library_page_' . $paged;
    $media = wp_cache_get($cache_key);

    if ($media === false) {
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => $per_page,
            'offset' => $offset,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids' // Nur IDs abrufen (schneller)
        );

        $query = new WP_Query($args);
        $media_ids = $query->posts;
        $total = $query->found_posts;

        $media = array();
        foreach ($media_ids as $attachment_id) {
            $url = wp_get_attachment_image_src($attachment_id, 'thumbnail');
            $media[] = array(
                'id' => $attachment_id,
                'url' => $url[0],
                'full_url' => wp_get_attachment_url($attachment_id),
                'title' => get_the_title($attachment_id)
            );
        }

        wp_cache_set($cache_key, array('media' => $media, 'total' => $total), '', 3600);
    } else {
        $total = $media['total'];
        $media = $media['media'];
    }

    wp_send_json_success(array(
        'media' => $media,
        'total' => $total,
        'paged' => $paged,
        'per_page' => $per_page
    ));
}

// Nonce für AJAX registrieren
add_action('wp_enqueue_scripts', function() {
    wp_localize_script('jquery', 'padmaMediaUploader', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('padma_media_nonce'),
        'maxUploadSize' => wp_max_upload_size()
    ));
});

add_action('admin_init', function() {
    wp_localize_script('jquery', 'padmaMediaUploader', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('padma_media_nonce'),
        'maxUploadSize' => wp_max_upload_size()
    ));
});
