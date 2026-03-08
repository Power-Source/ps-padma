<?php
/**
 * ExcerptsPlus Image Processor
 *
 * @package    Padma_Advanced
 * @subpackage ExcerptsPlus
 */

namespace Padma_Advanced\ExcerptsPlus;

/**
 * Image Processor Klasse
 * 
 * Verarbeitet, resized und cached Bilder für ExcerptsPlus
 */
class ImageProcessor {

	/**
	 * Cache-URL Prefix
	 */
	private static $cache_url_prefix;

	/**
	 * Cache-Path Prefix
	 */
	private static $cache_path_prefix;

	/**
	 * Initialisiere Cache-Pfade
	 */
	public static function init() {
		$upload_dir = wp_upload_dir();
		
		$cache_url = $upload_dir['baseurl'] . '/cache/padma/eplus';
		$cache_path = $upload_dir['basedir'] . '/cache/padma/eplus';
		
		self::$cache_url_prefix = $cache_url . '/eplus-';
		self::$cache_path_prefix = $cache_path . '/eplus-';
		
		// Prüfe Cache-Verzeichnis
		self::check_cache_directory($cache_path);
	}

	/**
	 * Prüfe und erstelle Cache-Verzeichnis
	 * 
	 * @param string $cache_path Cache-Pfad
	 */
	private static function check_cache_directory($cache_path) {
		if (!is_dir($cache_path)) {
			$upload_dir = wp_upload_dir();
			wp_mkdir_p($upload_dir['basedir'] . '/cache/padma/eplus');
		}
	}

	/**
	 * Verarbeite Bild für Block
	 * 
	 * @param int $block_id Block ID
	 * @param int $post_id Post ID
	 * @param string $image_url Original-Bild URL
	 * @param string $image_path Original-Bild Pfad
	 * @param array $settings Block-Settings
	 * @param array $dimensions Bild-Dimensionen (width, height)
	 * @return string|bool Bild-HTML oder false bei Fehler
	 */
	public static function process_image($block_id, $post_id, $image_url, $image_path, $settings, $dimensions) {
		
		if (!self::$cache_url_prefix) {
			self::init();
		}

		$extension = strtolower(strrchr($image_url, '.'));
		$ep_image_width = $dimensions['width'];
		$image_height = $dimensions['height'];

		// Cache-Dateinamen
		$new_image_url = self::$cache_url_prefix . 'block-' . $block_id . '-post-' . $post_id . '-width' . $ep_image_width . 'px' . $extension;
		$new_image_path = self::$cache_path_prefix . 'block-' . $block_id . '-post-' . $post_id . '-width' . $ep_image_width . 'px' . $extension;

		// Prüfe ob cached Image existiert
		if (file_exists($new_image_path) && !padma_get('ve-iframe')) {
			return '<img class="pzep_image" src="' . $new_image_url . '" alt="' . esc_attr(get_the_title()) . '"/>';
		}

		// Erstelle neues Bild
		return self::create_resized_image(
			$image_path,
			$image_url,
			$new_image_path,
			$new_image_url,
			$ep_image_width,
			$image_height,
			$settings
		);
	}

	/**
	 * Erstelle resized Bild
	 * 
	 * @param string $source_path Quell-Pfad
	 * @param string $source_url Quell-URL
	 * @param string $target_path Ziel-Pfad
	 * @param string $target_url Ziel-URL
	 * @param int $width Breite
	 * @param int $height Höhe
	 * @param array $settings Block-Settings
	 * @return string|bool Bild-HTML oder false
	 */
	private static function create_resized_image($source_path, $source_url, $target_path, $target_url, $width, $height, $settings) {
		
		// Prüfe ob GD verfügbar ist
		if (!extension_loaded('gd') || !function_exists('gd_info')) {
			return '<img src="' . esc_url( get_template_directory_uri() . '/library/blocks-advanced/excerpts-plus/img/missing-gd-placeholder.svg' ) . '" alt="Missing GD Library" width="150" height="50">';
		}

		// Prüfe ob Bild existiert
		$pzep_err_level = error_reporting();
		error_reporting(0);
		
		$use_path = file_exists($source_path);
		if (!$use_path) {
			$use_path = @getimagesize($source_url);
		}
		
		error_reporting($pzep_err_level);

		if (!$use_path) {
			return 'Bild nicht gefunden oder nicht zugänglich: ' . $source_url;
		}

		// Lade Image Resizer
		if (!class_exists('jo_Resize')) {
			require_once dirname(dirname(__FILE__)) . '/includes/jo-resizer/jo_image_resizer.php';
		}

		$resizeObj = new \jo_resize($use_path ? $source_path : $source_url);

		if (!$resizeObj->width) {
			return 'Bild konnte nicht geladen werden: ' . $source_url;
		}

		// Sizing-Type
		$sizing_type = !empty($settings['ep-sizing-type']) ? $settings['ep-sizing-type'] : 'crop';
		
		// Background-Color
		$img_bg_colour = '#FFFFFF';
		if (isset($settings['ep-image-fill']['hex'])) {
			$img_bg_colour = $settings['ep-image-fill']['hex'];
		} elseif (isset($settings['ep-image-fill'])) {
			$img_bg_colour = $settings['ep-image-fill'];
		}

		// Focal Point
		$focal_point = '';
		if (!empty($settings['ep-focal-point-align'])) {
			$focal_point = get_post_meta(get_post_thumbnail_id(), 'pzgp_focal_point', true);
		}

		// Resize Image
		$resizeObj->resizeImage(
			$width,
			$height,
			$sizing_type,
			$settings['ep-vertical-crop-align'] ?? 'center',
			$settings['ep-horizontal-crop-align'] ?? 'center',
			$img_bg_colour,
			true,
			$settings['max-image-dim'] ?? 0,
			$focal_point
		);

		// Colorize wenn gewünscht
		if (!empty($settings['ep-custom-fields-colourise-image'])) {
			$resizeObj->colourize($settings['ep-custom-fields-colourise-image']);
		}

		// Qualität
		$quality = !empty($settings['ep-quality']) ? (int)$settings['ep-quality'] : 70;

		// Speichere Bild
		$resizeObj->saveImage($target_path, $quality);

		if (!file_exists($target_path)) {
			return '<div class="ep-errors"><strong>Image Cache Problem:</strong> Bild ' . $target_url . ' konnte nicht erstellt werden. Bitte Dateiberechtigungen prüfen.</div>';
		}

		return '<img class="pzep_image" src="' . $target_url . '" alt="' . esc_attr(get_the_title()) . '"/>';
	}

	/**
	 * Lösche Post-Bilder aus Cache
	 * 
	 * @param int $post_id Post ID
	 */
	public static function clear_post_cache($post_id) {
		self::clear_cache('post-' . $post_id);
	}

	/**
	 * Lösche alle Bilder aus Cache
	 * 
	 * @param string $match Optional: nur Dateien mit diesem String löschen
	 */
	public static function clear_cache($match = 'eplus') {
		if (!self::$cache_path_prefix) {
			self::init();
		}

		$cache_path = dirname(self::$cache_path_prefix);
		
		if (!is_dir($cache_path)) {
			return;
		}

		$cache_files = scandir($cache_path);
		
		foreach ($cache_files as $cache_file) {
			if (strpos($cache_file, $match) !== false) {
				$file_path = $cache_path . '/' . $cache_file;
				if (is_file($file_path)) {
					@unlink($file_path);
				}
			}
		}
	}

	/**
	 * Hole Bild-Information
	 * 
	 * @param int $post_id Post ID
	 * @param array $settings Block-Settings
	 * @return array|false Array mit image_url, image_path, caption oder false
	 */
	public static function get_image_info($post_id, $settings) {
		$has_thumbnail = has_post_thumbnail($post_id);
		$ep_wpml_parent_id = null;

		// WPML Unterstützung
		if (defined('ICL_PLUGIN_PATH') && !$has_thumbnail) {
			global $post, $sitepress;
			$ep_wpml_parent_id = icl_object_id($post->ID, $post->post_type, true, $sitepress->get_default_language());
			$has_thumbnail = has_post_thumbnail($ep_wpml_parent_id);
		}

		// Attached Images als Fallback
		if (!$has_thumbnail && !empty($settings['ep-use-attached-images'])) {
			$args = array(
				'post_type' => 'attachment',
				'numberposts' => 1,
				'post_status' => null,
				'post_parent' => $post_id
			);
			$attachments = get_posts($args);
			
			if (!empty($attachments[0]) && wp_attachment_is_image($attachments[0]->ID)) {
				$image_info = wp_get_attachment_image_src($attachments[0]->ID, 'full');
				return array(
					'image_url' => $image_info[0],
					'image_path' => get_attached_file($attachments[0]->ID),
					'caption' => !empty($settings['ep-image-captions']) ? $attachments[0]->post_excerpt : ''
				);
			}
		}

		// Featured Image
		if ($has_thumbnail || ($ep_wpml_parent_id && has_post_thumbnail($ep_wpml_parent_id))) {
			$thumb_id = $has_thumbnail ? get_post_thumbnail_id($post_id) : get_post_thumbnail_id($ep_wpml_parent_id);
			$image_info = \Padma_Advanced\ExcerptsPlus\Helpers::extract_links(
				get_the_post_thumbnail($thumb_id, 'full')
			);

			return array(
				'image_url' => $image_info[0],
				'image_path' => get_attached_file($thumb_id),
				'caption' => !empty($settings['ep-image-captions']) ? get_post_field('post_excerpt', $thumb_id) : ''
			);
		}

		return false;
	}
}

// Initialisiere beim Laden
ImageProcessor::init();
