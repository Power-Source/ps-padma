<?php
// WordPress Media Uploader for Padma Visual Editor
// Simple implementation that recreates the old media-upload.php functionality

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load WordPress
if (!function_exists('wp_enqueue_script')) {
    require_once('../../../../../../wp-load.php');
}

// Remove MarketPress floating cart and other frontend hooks that shouldn't be in the media uploader
remove_all_actions('wp_footer');
remove_all_actions('wp_head');

// Re-add only essential WordPress hooks
add_action('wp_head', 'wp_enqueue_scripts', 1);
add_action('wp_head', 'wp_print_styles', 8);
add_action('wp_head', 'wp_print_head_scripts', 9);
add_action('wp_footer', 'wp_print_footer_scripts', 20);

// Remove any MarketPress hooks specifically
if (function_exists('remove_action')) {
    remove_action('wp_footer', 'mp_cart_widget');
    remove_action('wp_head', 'mp_cart_scripts');
    remove_action('wp_footer', 'mp_floating_cart');
    remove_action('wp_enqueue_scripts', 'mp_enqueue_scripts');
    remove_action('wp_head', 'mp_head_scripts');
    remove_action('wp_footer', 'mp_footer_scripts');
}

// Remove any theme hooks that might add unwanted content
remove_all_actions('padma_head');
remove_all_actions('padma_body_open');
remove_all_actions('padma_body_close');
remove_all_actions('padma_footer');

// Get media type from URL
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'image';
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'upload';

// Simple tabs implementation
$tabs = array(
    'upload' => __('Dateien hochladen', 'padma'),
    'url' => __('Von URL', 'padma'),
    'library' => __('Mediathek', 'padma')
);

// Handle file upload
if (isset($_POST['html-upload']) && !empty($_FILES)) {
    $uploaded_file = wp_handle_upload($_FILES['async-upload'], array('test_form' => false));
    if (isset($uploaded_file['file'])) {
        $attachment = array(
            'post_mime_type' => $uploaded_file['type'],
            'post_title' => sanitize_file_name($uploaded_file['file']),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attachment_id = wp_insert_attachment($attachment, $uploaded_file['file']);
        if (!is_wp_error($attachment_id)) {
            wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']));
            $image_url = wp_get_attachment_url($attachment_id);
            ?>
            <script>
                if (window.parent && window.parent.imageUploaderCallback) {
                    window.parent.imageUploaderCallback('<?php echo esc_js($image_url); ?>', '<?php echo esc_js(basename($uploaded_file['file'])); ?>');
                }
                if (window.parent && window.parent.closeBox) {
                    window.parent.closeBox('input-image', true);
                }
            </script>
            <?php
            exit;
        }
    }
}

// Handle URL submission
if (isset($_POST['url-upload']) && !empty($_POST['src'])) {
    $url = esc_url_raw($_POST['src']);
    $title = sanitize_text_field($_POST['title'] ?? '');
    $description = sanitize_textarea_field($_POST['description'] ?? '');
    $alt = sanitize_text_field($_POST['alt'] ?? '');
    $alignment = sanitize_text_field($_POST['alignment'] ?? 'none');
    $size = sanitize_text_field($_POST['size'] ?? 'full');
    $link_to = sanitize_text_field($_POST['link_to'] ?? 'none');
    $custom_url = esc_url_raw($_POST['custom_url'] ?? '');
    
    $filename = basename($url);
    if (empty($title)) {
        $title = $filename;
    }
    
    // Build the HTML based on the options
    $html = '';
    $img_attrs = array();
    
    if (!empty($alt)) {
        $img_attrs[] = 'alt="' . esc_attr($alt) . '"';
    }
    
    if ($alignment !== 'none') {
        $img_attrs[] = 'class="align' . esc_attr($alignment) . '"';
    }
    
    $img_tag = '<img src="' . esc_url($url) . '" ' . implode(' ', $img_attrs) . ' />';
    
    // Handle linking
    if ($link_to === 'file') {
        $html = '<a href="' . esc_url($url) . '">' . $img_tag . '</a>';
    } elseif ($link_to === 'custom' && !empty($custom_url)) {
        $html = '<a href="' . esc_url($custom_url) . '">' . $img_tag . '</a>';
    } else {
        $html = $img_tag;
    }
    
    ?>
    <script>
        if (window.parent && window.parent.imageUploaderCallback) {
            window.parent.imageUploaderCallback('<?php echo esc_js($url); ?>', '<?php echo esc_js($filename); ?>', {
                html: '<?php echo esc_js($html); ?>',
                title: '<?php echo esc_js($title); ?>',
                description: '<?php echo esc_js($description); ?>',
                alt: '<?php echo esc_js($alt); ?>',
                alignment: '<?php echo esc_js($alignment); ?>',
                size: '<?php echo esc_js($size); ?>',
                link_to: '<?php echo esc_js($link_to); ?>',
                custom_url: '<?php echo esc_js($custom_url); ?>'
            });
        }
        if (window.parent && window.parent.closeBox) {
            window.parent.closeBox('input-image', true);
        }
    </script>
    <?php
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php esc_html_e('Medien hochladen', 'padma'); ?></title>
    <?php wp_head(); ?>
    <style>
        body {
            background: #f1f1f1;
            padding: 0;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .media-upload-tabs {
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 0;
            margin: 0;
        }
        .media-upload-tabs ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        .media-upload-tabs li {
            margin: 0;
        }
        .media-upload-tabs a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #666;
            border-right: 1px solid #ddd;
        }
        .media-upload-tabs a:hover, .media-upload-tabs a.current {
            background: #f9f9f9;
            color: #333;
        }
        .media-upload-form {
            background: white;
            padding: 20px;
            margin: 0;
            min-height: 400px;
        }
        .media-upload-form h3 {
            margin-top: 0;
            color: #333;
        }
        .media-upload-form input[type="file"] {
            margin: 10px 0;
            padding: 8px;
            width: 100%;
            max-width: 300px;
        }
        .media-upload-form input[type="url"] {
            margin: 10px 0;
            padding: 8px;
            width: 100%;
            max-width: 400px;
        }
        .media-upload-form input[type="submit"] {
            background: #0073aa;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
        }
        .media-upload-form input[type="submit"]:hover {
            background: #005a87;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .form-table th {
            width: 200px;
            padding: 15px 10px 15px 0;
            text-align: left;
            vertical-align: top;
            font-weight: 600;
            color: #333;
        }
        .form-table td {
            padding: 15px 10px;
            vertical-align: top;
        }
        .form-table .description {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            margin-bottom: 0;
        }
        .form-table select {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .form-table textarea {
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 8px;
            font-family: inherit;
            resize: vertical;
        }
        .form-table input[type="text"],
        .form-table input[type="url"] {
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 8px;
        }
        .submit {
            text-align: left;
            padding: 20px 0 0 0;
        }
        .button-primary {
            background: #0073aa !important;
            color: white !important;
            border: none !important;
            padding: 10px 20px !important;
            border-radius: 3px !important;
            cursor: pointer !important;
            font-size: 14px !important;
        }
        .button-primary:hover {
            background: #005a87 !important;
        }
        .media-library-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        .media-item {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            background: white;
            border-radius: 3px;
        }
        .media-item:hover {
            background: #f9f9f9;
            border-color: #0073aa;
        }
        .media-item img {
            max-width: 100%;
            height: 100px;
            object-fit: cover;
        }
        .media-item span {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }
        /* Hide MarketPress cart and other unwanted elements */
        .mp_cart_widget_content,
        .mp_cart_widget,
        .marketpress-cart,
        .mp-cart,
        .floating-cart,
        .cart-widget,
        .woocommerce-cart,
        .cart-contents,
        .cart-icon,
        .header-cart,
        .mini-cart,
        .widget_shopping_cart,
        .shopping-cart-widget {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
</head>
<body>
    <div class="media-upload-tabs">
        <ul>
            <?php foreach ($tabs as $tab_key => $tab_name): ?>
                <li><a href="?padma-trigger=media-uploader&tab=<?php echo $tab_key; ?>" class="<?php echo $tab === $tab_key ? 'current' : ''; ?>"><?php echo $tab_name; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="media-upload-form">
        <?php if ($tab === 'upload'): ?>
            <h3><?php _e('Dateien hochladen', 'padma'); ?></h3>
            <form method="post" enctype="multipart/form-data">
                <p>
                    <input type="file" name="async-upload" accept="<?php echo $type === 'image' ? 'image/*' : ($type === 'video' ? 'video/*' : ($type === 'audio' ? 'audio/*' : '*/*')); ?>" />
                </p>
                <p>
                    <input type="submit" name="html-upload" value="<?php _e('Hochladen', 'padma'); ?>" />
                </p>
            </form>
            
        <?php elseif ($tab === 'url'): ?>
            <h3><?php _e('Von URL', 'padma'); ?></h3>
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="src"><?php _e('URL:', 'padma'); ?></label></th>
                        <td>
                            <input type="url" name="src" id="src" placeholder="<?php _e('Gib hier die URL ein', 'padma'); ?>" style="width: 100%; max-width: 400px;" required />
                            <p class="description"><?php _e('Gib die URL der Mediendatei ein, die Du verwenden willst.', 'padma'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="title"><?php _e('Titel:', 'padma'); ?></label></th>
                        <td>
                            <input type="text" name="title" id="title" style="width: 100%; max-width: 400px;" />
                            <p class="description"><?php _e('Titel fuer die Mediendatei (optional).', 'padma'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="description"><?php _e('Beschreibung:', 'padma'); ?></label></th>
                        <td>
                            <textarea name="description" id="description" rows="3" style="width: 100%; max-width: 400px;"></textarea>
                            <p class="description"><?php _e('Beschreibung fuer die Mediendatei (optional).', 'padma'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="alt"><?php _e('Alt-Text:', 'padma'); ?></label></th>
                        <td>
                            <input type="text" name="alt" id="alt" style="width: 100%; max-width: 400px;" />
                            <p class="description"><?php _e('Alt-Text fuer Bilder (empfohlen fuer Barrierefreiheit).', 'padma'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="alignment"><?php _e('Ausrichtung:', 'padma'); ?></label></th>
                        <td>
                            <select name="alignment" id="alignment">
                                <option value="none"><?php _e('Keine', 'padma'); ?></option>
                                <option value="left"><?php _e('Links', 'padma'); ?></option>
                                <option value="center"><?php _e('Zentriert', 'padma'); ?></option>
                                <option value="right"><?php _e('Rechts', 'padma'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="size"><?php _e('Groesse:', 'padma'); ?></label></th>
                        <td>
                            <select name="size" id="size">
                                <option value="full"><?php _e('Originalgroesse', 'padma'); ?></option>
                                <option value="large"><?php _e('Gross', 'padma'); ?></option>
                                <option value="medium"><?php _e('Mittel', 'padma'); ?></option>
                                <option value="thumbnail"><?php _e('Vorschaubild', 'padma'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="link_to"><?php _e('Verlinken auf:', 'padma'); ?></label></th>
                        <td>
                            <select name="link_to" id="link_to">
                                <option value="none"><?php _e('Nichts', 'padma'); ?></option>
                                <option value="file"><?php _e('Mediendatei', 'padma'); ?></option>
                                <option value="custom"><?php _e('Eigene URL', 'padma'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr id="custom_url_row" style="display: none;">
                        <th scope="row"><label for="custom_url"><?php _e('Eigene URL:', 'padma'); ?></label></th>
                        <td>
                            <input type="url" name="custom_url" id="custom_url" style="width: 100%; max-width: 400px;" />
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="url-upload" value="<?php _e('In Beitrag einfuegen', 'padma'); ?>" class="button-primary" />
                </p>
            </form>
            
            <script>
                document.getElementById('link_to').addEventListener('change', function() {
                    var customRow = document.getElementById('custom_url_row');
                    if (this.value === 'custom') {
                        customRow.style.display = 'table-row';
                    } else {
                        customRow.style.display = 'none';
                    }
                });
            </script>
            
        <?php elseif ($tab === 'library'): ?>
            <h3><?php _e('Mediathek', 'padma'); ?></h3>
            <div class="media-library-grid">
                <?php
                $attachments = get_posts(array(
                    'post_type' => 'attachment',
                    'post_mime_type' => $type,
                    'post_status' => 'any',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                foreach ($attachments as $attachment):
                    $url = wp_get_attachment_url($attachment->ID);
                    $filename = basename($url);
                    if ($type === 'image') {
                        $thumb = wp_get_attachment_image_src($attachment->ID, 'thumbnail');
                        $preview = $thumb ? $thumb[0] : $url;
                    } else {
                        $preview = includes_url('images/media/default.png');
                    }
                ?>
                    <div class="media-item" onclick="selectMedia('<?php echo esc_js($url); ?>', '<?php echo esc_js($filename); ?>')">
                        <img src="<?php echo esc_url($preview); ?>" alt="<?php echo esc_attr($filename); ?>">
                        <span><?php echo esc_html($filename); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function selectMedia(url, filename) {
            if (window.parent && window.parent.imageUploaderCallback) {
                window.parent.imageUploaderCallback(url, filename);
            }
            if (window.parent && window.parent.closeBox) {
                window.parent.closeBox('input-image', true);
            }
        }
        
        // Hide MarketPress cart and other unwanted elements after page load
        document.addEventListener('DOMContentLoaded', function() {
            // Hide MarketPress floating cart
            var cartElements = document.querySelectorAll('.mp-cart-widget, .mp-floating-cart, .mp-cart-float, .marketpress-cart, .mp-cart, .floating-cart, .cart-widget, .woocommerce-cart, .cart-contents, .cart-icon, .header-cart, .mini-cart, .widget_shopping_cart, .shopping-cart-widget, .mp_cart_widget_content, .mp_cart_widget');
            cartElements.forEach(function(element) {
                element.style.display = 'none';
                element.style.visibility = 'hidden';
                element.remove(); // Remove completely
            });
            
            // Also check for any elements with IDs that might be cart-related
            var cartIds = ['mp-cart', 'floating-cart', 'cart-widget', 'marketpress-cart'];
            cartIds.forEach(function(id) {
                var element = document.getElementById(id);
                if (element) {
                    element.style.display = 'none';
                    element.remove();
                }
            });
        });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>
