<?php
/**
 * ExcerptsPlus Query Builder
 *
 * @package    Padma_Advanced
 * @subpackage ExcerptsPlus
 */

namespace Padma_Advanced\ExcerptsPlus;

/**
 * Query Builder Klasse
 * 
 * Baut WP_Query-Objekte basierend auf Block-Settings
 */
class QueryBuilder {

	/**
	 * Baue Query für ExcerptsPlus Block
	 * 
	 * @param array $block Block-Daten
	 * @param array $settings Block-Settings
	 * @return \WP_Query Query-Objekt
	 */
	public static function build_query($block, $settings) {
		
		// Post Types vorbereiten
		$post_types = self::prepare_post_types($settings);
		
		// Kategorien vorbereiten
		$categories = self::prepare_categories($settings);
		
		// Post IDs vorbereiten
		$post_ids = self::prepare_post_ids($settings);
		
		// Autor vorbereiten
		$author = self::prepare_author($settings);

		// Basis Query-Optionen
		$query_options = array(
			'offset' => $settings['offset'] ?? 0,
			'category__not_in' => $settings['ep-excategories'] ?? '',
			'author' => $author,
			'post_type' => $post_types,
			'order' => ($settings['order-az'] == 'Descending' || $settings['order-az'] == 'DESC') ? 'DESC' : 'ASC',
			'orderby' => $settings['order-by'] ?? 'date',
			'post_status' => self::get_post_status($settings),
			'posts_per_page' => $settings['number-show'] ?? 10,
		);

		// Stickies
		if (!empty($settings['include-stickies'])) {
			$query_options['ignore_sticky_posts'] = 0;
		} else {
			$query_options['ignore_sticky_posts'] = 1;
		}

		// Kategorien hinzufügen
		if (!empty($categories) && !$settings['include-stickies']) {
			if (!empty($settings['all-include-categories'])) {
				$query_options['category__and'] = $categories;
			} else {
				$query_options['category__in'] = $categories;
			}
		}

		// Post IDs ein-/ausschließen
		if (!empty($post_ids)) {
			if (!empty($settings['exclude-ids']) && empty($settings['show-children'])) {
				$query_options['post__not_in'] = $post_ids;
			} else {
				$query_options['post__in'] = $post_ids;
			}
		}

		// Custom Taxonomies
		$query_options = self::add_taxonomies($query_options, $settings);

		// Tags
		$query_options = self::add_tags($query_options, $settings);

		// Date Range
		$query_options = self::add_date_range($query_options, $settings);

		// Custom Fields Filter
		$query_options = self::add_custom_field_filter($query_options, $settings);

		// Custom WHERE SQL
		if (!empty($settings['ep-custom-where-sql'])) {
			add_filter('posts_join', array(__CLASS__, 'custom_post_meta_join'));
			add_filter('posts_where', array(__CLASS__, 'custom_where_filter'));
			global $ep_where_vars;
			$ep_where_vars = array('custom' => $settings['ep-custom-where-sql']);
		}

		// Custom Sort
		if (!empty($settings['ep-use-custom-sort'])) {
			global $ep_custom_sort_vals;
			$ep_custom_sort_vals = array(
				'key' => $settings['ep-custom-filter-sort-key'] ?? '',
				'order' => $settings['ep-custom-filter-sort-key-order'] ?? 'ASC'
			);
			add_filter('posts_orderby', array(__CLASS__, 'custom_orderby_filter'));
			if (empty($settings['ep-use-custom-filter'])) {
				add_filter('posts_join', array(__CLASS__, 'custom_posts_join'));
			}
		}

		// Pagination
		if (!empty($settings['use-pagination'])) {
			global $paged;
			$query_options['paged'] = $paged;
			
			// WP mag keinen Offset von 0 bei Pagination
			if (isset($query_options['offset']) && $query_options['offset'] == 0) {
				unset($query_options['offset']);
			}
		}

		return new \WP_Query($query_options);
	}

	/**
	 * Bereite Post Types vor
	 * 
	 * @param array $settings Block-Settings
	 * @return array Array mit Post Types
	 */
	private static function prepare_post_types($settings) {
		$selected = $settings['post-type'] ?? 'post';
		
		// Konvertiere alte numerische Werte
		if ($selected === 0 || $selected === '0' || $selected === 'post') {
			return array('post');
		} elseif ($selected === 1 || $selected === '1' || $selected === 'page') {
			return array('page');
		}

		return is_array($selected) ? $selected : array($selected);
	}

	/**
	 * Bereite Kategorien vor
	 * 
	 * @param array $settings Block-Settings
	 * @return array|string Kategorien
	 */
	private static function prepare_categories($settings) {
		$post_type = $settings['post-type'] ?? 'post';
		$categories = $settings['categories'] ?? '';

		// Keine Kategorien für Pages
		if ($post_type === 'page' || $post_type === '1' || $post_type === 1) {
			return '';
		}

		// Alle Kategorien
		if ($categories === 'all' || $categories === array('all')) {
			return '';
		}

		// URL Override
		if (!empty($_GET['catid'])) {
			return esc_html($_GET['catid']);
		}

		return $categories;
	}

	/**
	 * Bereite Post IDs vor
	 * 
	 * @param array $settings Block-Settings
	 * @return array|null Array mit Post IDs oder null
	 */
	private static function prepare_post_ids($settings) {
		// Children anzeigen
		if (!empty($settings['show-children']) && !empty($settings['post-ids'])) {
			$mypages = get_pages('child_of=' . $settings['post-ids'] . '&sort_column=post_name&sort_order=asc');
			$pageids = array();
			foreach ($mypages as $page) {
				$pageids[] = $page->ID;
			}
			return $pageids;
		}

		// Normale Post IDs
		if (!empty($settings['post-ids'])) {
			return is_array($settings['post-ids']) ? 
				$settings['post-ids'] : 
				explode(',', $settings['post-ids']);
		}

		return null;
	}

	/**
	 * Bereite Autor vor
	 * 
	 * @param array $settings Block-Settings
	 * @return string|int Autor ID(s)
	 */
	private static function prepare_author($settings) {
		// Autor aus Settings
		if (is_array($settings['author'] ?? null)) {
			return implode(',', $settings['author']);
		}
		
		return $settings['author'] ?? '';
	}

	/**
	 * Hole Post Status
	 * 
	 * @param array $settings Block-Settings
	 * @return array Post Status Array
	 */
	private static function get_post_status($settings) {
		if (!empty($settings['post-inc-scheduled']) && $settings['post-inc-scheduled'] == '1') {
			return array('publish', 'private', 'future');
		}
		return null;
	}

	/**
	 * Füge Taxonomies zur Query hinzu
	 * 
	 * @param array $query_options Query-Optionen
	 * @param array $settings Block-Settings
	 * @return array Erweiterte Query-Optionen
	 */
	private static function add_taxonomies($query_options, $settings) {
		if (empty($settings['ep-taxonomies'])) {
			return $query_options;
		}

		$tax_query = array();
		
		foreach ($settings['ep-taxonomies'] as $ep_tax) {
			$tax_parts = explode(':', $ep_tax);
			if (count($tax_parts) == 2) {
				$tax_query[] = array(
					'taxonomy' => $tax_parts[0],
					'field' => 'slug',
					'terms' => array($tax_parts[1]),
				);
			}
		}

		if (!empty($tax_query)) {
			$query_options['tax_query'] = array(
				'relation' => $settings['ep-taxonomies-operator'] ?? 'AND'
			);
			$query_options['tax_query'] = array_merge(
				array('relation' => $settings['ep-taxonomies-operator'] ?? 'AND'),
				$tax_query
			);
		}

		return $query_options;
	}

	/**
	 * Füge Tags zur Query hinzu
	 * 
	 * @param array $query_options Query-Optionen
	 * @param array $settings Block-Settings
	 * @return array Erweiterte Query-Optionen
	 */
	private static function add_tags($query_options, $settings) {
		if (!empty($settings['ep-tags'])) {
			$query_options['tag__in'] = $settings['ep-tags'];
		}
		
		if (!empty($settings['exclude-tags'])) {
			$query_options['tag__not_in'] = $settings['exclude-tags'];
		}

		return $query_options;
	}

	/**
	 * Füge Date Range zur Query hinzu
	 * 
	 * @param array $query_options Query-Optionen
	 * @param array $settings Block-Settings
	 * @return array Erweiterte Query-Optionen
	 */
	private static function add_date_range($query_options, $settings) {
		if (empty($settings['ep-days-to-show']) || $settings['ep-days-to-show'] < 0 || strtolower($settings['ep-days-to-show']) == 'all') {
			return $query_options;
		}

		// WordPress 3.7+ hat date_query Support
		if (version_compare(get_bloginfo('version'), '3.7.0', 'ge')) {
			$ep_timezone = get_option('timezone_string');
			
			if (!empty($settings['ep-use-timezone']) && !empty($ep_timezone)) {
				$ep_the_date = new \DateTime($settings['ep-date-to-end'], new \DateTimeZone($ep_timezone));
			} else {
				$ep_the_date = new \DateTime($settings['ep-date-to-end']);
			}

			$ep_first_date = $ep_the_date->format('Y-m-d');
			$ep_last_date = $ep_the_date->add(new \DateInterval('P' . $settings['ep-days-to-show'] . 'D'))->format('Y-m-d');
			
			$query_options['date_query'] = array(
				array(
					'after' => $ep_first_date,
					'before' => $ep_last_date,
					'inclusive' => true,
				)
			);
		} else {
			// Für ältere WP-Versionen: Filter verwenden
			global $ep_where_vars;
			$ep_where_vars = array(
				'days' => $settings['ep-days-to-show'],
				'end_date' => $settings['ep-date-to-end'],
				'use_timezone' => $settings['ep-use-timezone'] ?? false
			);
			add_filter('posts_where', array(__CLASS__, 'date_range_filter'));
		}

		return $query_options;
	}

	/**
	 * Füge Custom Field Filter zur Query hinzu
	 * 
	 * @param array $query_options Query-Optionen
	 * @param array $settings Block-Settings
	 * @return array Erweiterte Query-Optionen
	 */
	private static function add_custom_field_filter($query_options, $settings) {
		if (empty($settings['ep-use-custom-filter'])) {
			return $query_options;
		}

		$filter_value = $settings['ep-custom-filter-value'] ?? '';
		$value_type = $settings['ep-custom-filter-value-type'] ?? 'string';

		// Verarbeite Wert basierend auf Typ
		switch ($value_type) {
			case 'date':
				$filter_value = ($filter_value == 'today') ? 
					date('Y-m-d', time()) : 
					date('Y-m-d', strtotime($filter_value));
				break;
			case 'timestamp':
				$filter_value = strtotime($filter_value);
				break;
		}

		$query_options['meta_query'] = array(
			array(
				'key' => $settings['ep-custom-filter-key'] ?? '',
				'value' => $filter_value,
				'type' => $settings['ep-custom-filter-type'] ?? 'CHAR',
				'compare' => $settings['ep-custom-filter-compare'] ?? '='
			)
		);

		return $query_options;
	}

	/**
	 * Filter: Custom Post Meta Join
	 * 
	 * @param string $join Join-Statement
	 * @return string Modifiziertes Join-Statement
	 */
	public static function custom_post_meta_join($join) {
		global $wpdb;
		$join .= " INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)";
		return $join;
	}

	/**
	 * Filter: Custom Posts Join
	 * 
	 * @param string $join Join-Statement
	 * @return string Modifiziertes Join-Statement
	 */
	public static function custom_posts_join($join) {
		global $wpdb;
		$join .= " INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id) ";
		return $join;
	}

	/**
	 * Filter: Custom WHERE
	 * 
	 * @param string $where WHERE-Statement
	 * @return string Modifiziertes WHERE-Statement
	 */
	public static function custom_where_filter($where) {
		global $ep_where_vars, $wpdb;
		if (!empty($ep_where_vars['custom'])) {
			$where .= ' AND ' . $ep_where_vars['custom'];
		}
		return $where;
	}

	/**
	 * Filter: Custom Orderby
	 * 
	 * @param string $orderby Orderby-Statement
	 * @return string Modifiziertes Orderby-Statement
	 */
	public static function custom_orderby_filter($orderby) {
		global $ep_custom_sort_vals, $wpdb;
		return " {$wpdb->postmeta}.meta_value " . $ep_custom_sort_vals['order'] . " ";
	}

	/**
	 * Filter: Date Range
	 * 
	 * @param string $where WHERE-Statement
	 * @return string Modifiziertes WHERE-Statement
	 */
	public static function date_range_filter($where) {
		global $ep_where_vars, $wpdb;

		$ep_timezone = get_option('timezone_string');
		
		if (!empty($ep_where_vars['use_timezone']) && !empty($ep_timezone)) {
			$ep_last_date = new \DateTime($ep_where_vars['end_date'], new \DateTimeZone($ep_timezone));
		} else {
			$ep_last_date = new \DateTime($ep_where_vars['end_date']);
		}

		$ep_days = ($ep_where_vars['days'] == 0) ? 0 : ($ep_where_vars['days'] - 1);
		$ep_first_date = clone $ep_last_date;

		$date_field = !empty($ep_where_vars['use_timezone']) ? 'post_date_gmt' : 'post_date';

		$where .= " AND ({$wpdb->posts}.{$date_field} >= '" . 
			$ep_first_date->sub(new \DateInterval('P' . $ep_days . 'D'))->format('Y-m-d') . 
			"' AND {$wpdb->posts}.{$date_field} < '" . 
			$ep_last_date->add(new \DateInterval('P1D'))->format('Y-m-d') . "')";

		return $where;
	}
}
