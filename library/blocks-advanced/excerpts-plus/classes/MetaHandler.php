<?php
/**
 * ExcerptsPlus Meta Handler
 *
 * @package    Padma_Advanced
 * @subpackage ExcerptsPlus
 */

namespace Padma_Advanced\ExcerptsPlus;

/**
 * Meta Handler Klasse
 * 
 * Verarbeitet Post-Meta, Custom Fields und Meta-Informationen
 */
class MetaHandler {

	/**
	 * Parse Meta-String und ersetze Platzhalter
	 * 
	 * @param array $block Block-Daten
	 * @param array $settings Block-Settings
	 * @param string $meta Meta-String mit Platzhaltern
	 * @return string Verarbeiteter Meta-String
	 */
	public static function parse_meta($block, $settings, $meta) {
		global $post, $authordata;

		$replacements = array();

		// Datum
		if (strpos($meta, '%date%') !== false) {
			$postdate = ($settings['date-format']) ? get_the_time($settings['date-format']) : get_the_date();
			$replacements['%date%'] = '<span class="entry-date published">' . $postdate . '</span>';
		}

		// Zeit
		if (strpos($meta, '%time%') !== false) {
			$posttime = ($settings['time-format']) ? get_the_time($settings['time-format']) : get_the_time();
			$replacements['%time%'] = '<span class="entry-date published">' . $posttime . '</span>';
		}

		// Kommentare
		if (strpos($meta, '%comments%') !== false || strpos($meta, '%comments_no_link%') !== false) {
			$comment_count = (int)get_comments_number($post->ID);
			
			if ($comment_count === 0) {
				$comments_format = stripslashes(\PadmaBlockAPI::get_setting(
					$block, 
					'comment-format-0', 
					$settings['ep-text-comments-nil'] ?? 'Keine Kommentare'
				));
			} elseif ($comment_count == 1) {
				$comments_format = stripslashes(\PadmaBlockAPI::get_setting(
					$block,
					'comment-format-1',
					$settings['ep-text-comments-single'] ?? '1 Kommentar'
				));
			} else {
				$comments_format = stripslashes(\PadmaBlockAPI::get_setting(
					$block,
					'comment-format',
					$settings['ep-text-comments-multiple'] ?? '%num% Kommentare'
				));
			}
			
			$comments = str_replace('%num%', $comment_count, $comments_format);

			if (strpos($meta, '%comments%') !== false) {
				$replacements['%comments%'] = '<a href="' . get_comments_link() . '" title="' . esc_attr(get_the_title()) . ' Kommentare" class="entry-comments">' . $comments . '</a>';
			}
			
			if (strpos($meta, '%comments_no_link%') !== false) {
				$replacements['%comments_no_link%'] = $comments;
			}
		}

		// Antworten-Link
		if (strpos($meta, '%respond%') !== false) {
			$respond_format = stripslashes(\PadmaBlockAPI::get_setting(
				$block,
				'respond-format',
				$settings['ep-text-comments-new'] ?? 'Kommentieren'
			));
			$replacements['%respond%'] = '<a href="' . get_permalink() . '#respond" title="Auf ' . esc_attr(get_the_title()) . ' antworten" class="entry-respond">' . $respond_format . '</a>';
		}

		// Autor
		if (strpos($meta, '%author%') !== false || strpos($meta, '%author_no_link%') !== false) {
			$author_avatar = '';
			if (!empty($settings['show-avatar']) && $settings['image-location'] != 'behind') {
				$author_avatar = get_avatar(get_the_author_meta('ID'), $settings['avatar-size'] ?? 32);
			}
			
			if (strpos($meta, '%author%') !== false) {
				$replacements['%author%'] = '<a class="author-link fn nickname url" href="' . get_author_posts_url($authordata->ID) . '" title="Alle Beiträge von ' . esc_attr($authordata->display_name) . '">' . $author_avatar . $authordata->display_name . '</a>';
			}
			
			if (strpos($meta, '%author_no_link%') !== false) {
				$replacements['%author_no_link%'] = $author_avatar . $authordata->display_name;
			}
		}

		// Kategorien
		if (strpos($meta, '%categories%') !== false) {
			$replacements['%categories%'] = get_the_category_list(', ');
		}

		// Tags
		if (strpos($meta, '%tags%') !== false) {
			$replacements['%tags%'] = (get_the_tags() != null) ? 
				get_the_tag_list('<span class="tag-links"><span>Tags:</span> ', ', ', '</span>') : '';
		}

		// Bearbeiten-Link
		if (strpos($meta, '%edit%') !== false) {
			$edit_format = \PadmaBlockAPI::get_setting($block, 'edit-link-format', ' | %edit_link%');
			$edit_link = '<span class="edit"><a class="post-edit-link" href="' . get_edit_post_link($post->ID) . '">Bearbeiten</a></span>';
			$replacements['%edit%'] = current_user_can('edit_post', $post->ID) ? 
				str_replace('%edit_link%', $edit_link, $edit_format) : '';
		}

		// Permalink
		if (strpos($meta, '%permalink%') !== false) {
			$replacements['%permalink%'] = get_permalink();
		}

		// Titel
		if (strpos($meta, '%title%') !== false) {
			$replacements['%title%'] = get_the_title();
		}

		// Quick Read
		if (strpos($meta, '%quickread%') !== false) {
			$quickread_text = !empty($settings['ep-quick-read-label']) ? 
				$settings['ep-quick-read-label'] : 'Schnelllesen';
			$replacements['%quickread%'] = '<a href="' . get_permalink() . '" class="ep_quickread openquickread" alt="' . esc_attr(get_the_title()) . '" title="Öffne &quot;' . esc_attr(get_the_title()) . '&quot; in Popup">' . $quickread_text . '</a>';
		}

		// Ersetze alle Platzhalter
		$meta = str_replace(array_keys($replacements), array_values($replacements), $meta);

		// Verarbeite Custom Fields
		$meta = self::process_custom_field_placeholders($meta);

		return apply_filters('padma_meta', $meta);
	}

	/**
	 * Verarbeite Custom Field Platzhalter im Meta-String
	 * 
	 * @param string $meta Meta-String
	 * @return string Verarbeiteter Meta-String
	 */
	private static function process_custom_field_placeholders($meta) {
		$ep_cf_names = get_post_custom_keys();
		
		if (!isset($ep_cf_names)) {
			return $meta;
		}

		foreach ($ep_cf_names as $ep_cf_name) {
			// Überspringe WordPress interne Fields (beginnen mit _)
			if (substr($ep_cf_name, 0, 1) != '_') {
				$placeholder = '%' . $ep_cf_name . '%';
				$ep_cf_data = Helpers::get_custom_field($placeholder);
				
				if (!is_array($ep_cf_data)) {
					$meta = str_replace($placeholder, $ep_cf_data, $meta);
				}
			}
		}

		return $meta;
	}

	/**
	 * Baue Custom Fields HTML
	 * 
	 * @param array $settings Block-Settings
	 * @param int $group Custom Fields Gruppe (1-3)
	 * @return string HTML für Custom Fields
	 */
	public static function build_custom_fields($settings, $group) {
		$ep_custom = '';
		$repeater_key = 'ep-custom-fields-group' . $group . '-repeater';

		if (empty($settings[$repeater_key])) {
			return '';
		}

		foreach ($settings[$repeater_key] as $settings_cf) {
			$field_name = $settings_cf['ep-custom-fields-name'] ?? '';
			$field_value = get_post_meta(get_the_id(), $field_name, true);

			// Überspringe wenn leer
			if (empty($field_value)) {
				continue;
			}

			// Wrapper öffnen
			$wrapper = $settings_cf['ep-custom-fields-wrapper'] ?? 'div';
			if ($wrapper != 'none') {
				$ep_custom .= '<' . $wrapper . ' class="ep_custom_field ep_custom_field_' . sanitize_html_class($field_name) . '">';
			}

			// Prefix Text
			if (!empty($settings_cf['ep-custom-fields-prefix-text'])) {
				$ep_custom .= '<span class="ep_custom_field_prefix_text">' . 
					esc_html($settings_cf['ep-custom-fields-prefix-text']) . '</span>';
			}

			// Prefix Image
			if (!empty($settings_cf['ep-custom-fields-prefix-image'])) {
				$ep_custom .= '<img class="ep_custom_field_prefix_image" src="' . 
					esc_url($settings_cf['ep-custom-fields-prefix-image']) . '" alt=""/>';
			}

			// Field Value mit optional URL
			$field_url_key = $settings_cf['ep-custom-fields-name-url'] ?? 'none';
			
			if ($field_url_key != 'none') {
				$field_url = get_post_meta(get_the_id(), $field_url_key, true);
				
				// E-Mail Handling
				if (strpos($field_url, '@') > 0 && strpos($field_url, 'mailto:') === false) {
					$field_url = 'mailto:' . $field_url;
				}
				
				$ep_custom .= '<a href="' . esc_url($field_url) . '">' . esc_html($field_value) . '</a>';
			} else {
				// Bild
				if (!empty($settings_cf['ep-custom-fields-is-image']) && $settings_cf['ep-custom-fields-is-image'] != 'false') {
					$ep_custom .= '<img src="' . esc_url($field_value) . '" class="ep-cf-image" alt=""/>';
				}
				// Datum
				elseif (!empty($settings_cf['ep-custom-fields-is-date']) && $settings_cf['ep-custom-fields-is-date'] != 'false') {
					$ep_custom .= date_i18n(
						get_option('date_format'),
						strtotime(str_replace(',', ' ', $field_value))
					);
				}
				// Text
				else {
					$ep_custom .= esc_html($field_value);
				}
			}

			// Suffix Text
			if (!empty($settings_cf['ep-custom-fields-suffix-text'])) {
				$ep_custom .= '<span class="ep_custom_field_suffix_text">' . 
					esc_html($settings_cf['ep-custom-fields-suffix-text']) . '</span>';
			}

			// Suffix Image
			if (!empty($settings_cf['ep-custom-fields-suffix-image'])) {
				$ep_custom .= '<img class="ep_custom_field_suffix_image" src="' . 
					esc_url($settings_cf['ep-custom-fields-suffix-image']) . '" alt=""/>';
			}

			// Wrapper schließen
			if ($wrapper != 'none') {
				$ep_custom .= '</' . $wrapper . '>';
			}
		}

		return wpautop($ep_custom);
	}
}
