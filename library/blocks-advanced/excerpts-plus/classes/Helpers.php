<?php
/**
 * ExcerptsPlus Helper Functions
 *
 * @package    Padma_Advanced
 * @subpackage ExcerptsPlus
 */

namespace Padma_Advanced\ExcerptsPlus;

/**
 * Helper-Klasse für ExcerptsPlus
 * 
 * Enthält allgemeine Hilfsfunktionen für Kategorien, Tags, 
 * Debugging und HTML-Verarbeitung.
 */
class Helpers {

	/**
	 * Hole Liste aller Kategorien
	 * 
	 * @return array Kategorien als key-value Array (ID => Name)
	 */
	public static function get_category_list() {
		$categories_select_query = get_categories();
		$categories_select = array();
		
		foreach ($categories_select_query as $category) {
			$categories_select[$category->cat_ID] = $category->cat_name;
		}

		return $categories_select;
	}

	/**
	 * Hole Liste aller Custom Taxonomies (ohne Standard-Taxonomies)
	 * 
	 * @return array Taxonomies als key-value Array
	 */
	public static function get_tax_list() {
		$ep_tax_list = array();
		$ep_taxonomies = get_taxonomies();
		
		// Entferne Standard-Taxonomies
		$exclude_taxonomies = array(
			'category', 'post_tag', 'nav_menu', 'link_category',
			'post_format', 'slide_set', 'events_categories', 
			'events_tags', 'events_feeds'
		);
		
		foreach ($exclude_taxonomies as $taxonomy) {
			if (isset($ep_taxonomies[$taxonomy])) {
				unset($ep_taxonomies[$taxonomy]);
			}
		}
		
		foreach ($ep_taxonomies as $key => $ep_tax) {
			$ep_customtaxs = get_terms($key);
			foreach ($ep_customtaxs as $ep_customtax) {
				$ep_tax_list[($key . ':' . $ep_customtax->slug)] = $ep_tax . ' : ' . $ep_customtax->name;
			}
		}

		return $ep_tax_list;
	}

	/**
	 * Hole Liste aller Tags
	 * 
	 * @return array Tags als key-value Array (ID => Name)
	 */
	public static function get_tag_list() {
		$ep_tags = get_terms('post_tag');
		$ep_tag_list = array();
		
		foreach ($ep_tags as $ep_tag) {
			$ep_tag_list[$ep_tag->term_id] = $ep_tag->name;
		}

		return $ep_tag_list;
	}

	/**
	 * Extrahiere Links aus HTML-String
	 * 
	 * @param string $str HTML-String
	 * @return array Array mit gefundenen Links
	 */
	public static function extract_links($str) {
		preg_match_all('/(href|src)\=(\"|\')[^\"\'\>]+/i', $str, $media);
		unset($str);
		$str = preg_replace('/(href|src)(\"|\'|\=\"|\=\')(.*)/i', "$3", $media[0]);

		return $str;
	}

	/**
	 * Debug-Ausgabe (nur bei aktiviertem Debug-Mode)
	 * 
	 * @param string $string Debug-Message
	 */
	public static function php_debug($string) {
		if (class_exists('ChromePHP') && $_SERVER['QUERY_STRING'] == 'pzepdebug' . date('d')) {
			$pznow = microtime(true);
			$btr = debug_backtrace();
			$line = $btr[0]['line'];
			$file = basename($btr[0]['file']);
			\ChromePhp::log($file . ':' . $line . ' ' . $string . ': Time since reload: ' . round(($pznow - esc_attr($_SERVER['REQUEST_TIME'])), 2) . 's');
		}
	}

	/**
	 * Debug-Ausgabe für Entwicklung
	 * 
	 * @param mixed $value Zu debuggende Variable
	 */
	public static function debug($value = '') {
		$btr = debug_backtrace();
		$line = $btr[0]['line'];
		$file = basename($btr[0]['file']);
		
		print "<pre>$file:$line</pre>\n";
		
		if (is_array($value)) {
			print "<pre>";
			print_r($value);
			print "</pre>\n";
		} elseif (is_object($value)) {
			var_dump($value);
		} else {
			print("<p>&gt;${value}&lt;</p>");
		}
	}

	/**
	 * Entferne alle HTML-Tags inkl. unsichtbare Inhalte
	 * 
	 * @param string $text HTML-Text
	 * @return string Bereinigter Text ohne HTML
	 */
	public static function strip_html_tags($text) {
		$text = preg_replace(
			array(
				// Entferne unsichtbare Inhalte
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
				// Füge Zeilenumbrüche vor/nach Block-Elementen hinzu
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
			),
			$text
		);

		return strip_tags($text);
	}

	/**
	 * Konvertiere HEX zu RGB
	 * 
	 * @param string $hex Hex-Farbwert (#FFFFFF oder FFF)
	 * @return array RGB-Array mit keys 'r', 'g', 'b'
	 */
	public static function hex_to_rgb($hex) {
		$hex = preg_replace("/#/", "", $hex);
		$color = array();
		
		if (strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$color['g'] = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$color['b'] = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		} elseif (strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}

		return $color;
	}

	/**
	 * Verarbeite PHP-Code in Meta-Content
	 * 
	 * @param string $ep_meta Meta-String mit möglichem PHP-Code
	 * @return string Verarbeiteter String
	 */
	public static function process_meta_php($ep_meta) {
		$content = stripslashes(base64_decode($ep_meta));
		
		// Entferne problematischen MailChimp-Code
		$content = str_replace(
			'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>',
			'',
			$ep_meta
		);

		return padma_parse_php(do_shortcode($ep_meta));
	}

	/**
	 * Prüfe ob Post geplant (future) ist
	 * 
	 * @return bool True wenn Post-Status 'future' ist
	 */
	public static function is_scheduled() {
		return (get_post_status() === 'future');
	}

	/**
	 * Hole Custom Field Wert
	 * 
	 * @param string $ep_cf_name Custom Field Name (mit oder ohne %)
	 * @return mixed Custom Field Wert
	 */
	public static function get_custom_field($ep_cf_name) {
		// Entferne %-Zeichen
		$ep_cf_name = str_replace('%', '', $ep_cf_name);
		return get_post_meta(get_the_ID(), $ep_cf_name, true);
	}
}
