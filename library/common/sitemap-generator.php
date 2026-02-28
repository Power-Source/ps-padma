<?php
/**
 * PS Padma Theme - XML Sitemap Generator
 *
 * Auto-generates XML sitemaps for SEO
 *
 * @package padma
 * @since 2.0.0
 */

class PadmaSitemapGenerator {

	const SITEMAP_REWRITE = 'padma_sitemap';
	const SITEMAP_INDEX = 'sitemap_index.xml';
	const POSTS_PER_SITEMAP = 50000;

	/**
	 * Initialize Sitemap Generator
	 */
	public static function init() {

		// Add rewrite rules
		add_action('init', array(__CLASS__, 'add_rewrite_rules'));

		// Handle sitemap requests
		add_action('template_redirect', array(__CLASS__, 'handle_sitemap_request'));

		// Flush rewrite rules on activation
		add_action('padma_setup', array(__CLASS__, 'flush_rewrite_rules'));

	}

	/**
	 * Add URL Rewrite Rules
	 */
	public static function add_rewrite_rules() {

		add_rewrite_rule(
			'sitemap\.xml$',
			'index.php?' . self::SITEMAP_REWRITE . '=index',
			'top'
		);

		add_rewrite_rule(
			'sitemap-([a-z]+)-([0-9]+)\.xml$',
			'index.php?' . self::SITEMAP_REWRITE . '=$matches[1]&sitemap_page=$matches[2]',
			'top'
		);

		// Add query vars
		add_filter('query_vars', array(__CLASS__, 'add_query_vars'));

	}

	/**
	 * Add Custom Query Vars
	 */
	public static function add_query_vars($vars) {

		$vars[] = self::SITEMAP_REWRITE;
		$vars[] = 'sitemap_page';

		return $vars;

	}

	/**
	 * Flush Rewrite Rules
	 */
	public static function flush_rewrite_rules() {

		if (get_option('padma_sitemap_flush')) {
			delete_option('padma_sitemap_flush');
			flush_rewrite_rules();
		}

	}

	/**
	 * Handle Sitemap Requests
	 */
	public static function handle_sitemap_request() {

		$sitemap_type = get_query_var(self::SITEMAP_REWRITE);

		if (!$sitemap_type) {
			return;
		}

		// Disable caching for dynamic content
		if (!defined('DONOTCACHEPAGE')) {
			define('DONOTCACHEPAGE', true);
		}

		status_header(200);
		header('Content-Type: application/xml; charset=UTF-8');
		header('Cache-Control: public, max-age=86400');

		if ($sitemap_type === 'index') {
			self::render_sitemap_index();
		} else {
			self::render_sitemap($sitemap_type, get_query_var('sitemap_page', 1));
		}

		exit();

	}

	/**
	 * Render Sitemap Index
	 */
	private static function render_sitemap_index() {

		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

		// Get post types with SEO enabled
		$post_types = get_post_types(array('public' => true), 'objects');
		$exclude = array('attachment');

		$total_posts = 0;

		foreach ($post_types as $post_type) {

			if (in_array($post_type->name, $exclude)) {
				continue;
			}

			$count = wp_count_posts($post_type->name);
			$published = $count->publish;

			if ($published == 0) {
				continue;
			}

			$pages = ceil($published / self::POSTS_PER_SITEMAP);

			for ($i = 1; $i <= $pages; $i++) {
				echo "\t" . '<sitemap>' . "\n";
				echo "\t\t" . '<loc>' . esc_url(home_url("/sitemap-{$post_type->name}-{$i}.xml")) . '</loc>' . "\n";
				echo "\t\t" . '<lastmod>' . date('c', current_time('timestamp')) . '</lastmod>' . "\n";
				echo "\t" . '</sitemap>' . "\n";
			}

		}

		// Add taxonomy sitemaps
		$taxonomies = get_taxonomies(array('public' => true), 'objects');

		foreach ($taxonomies as $taxonomy) {

			if (in_array($taxonomy->name, array('category', 'post_tag'))) {

				$terms = get_terms(array(
					'taxonomy' => $taxonomy->name,
					'hide_empty' => true,
					'number' => 1,
				));

				if (!empty($terms)) {
					echo "\t" . '<sitemap>' . "\n";
					echo "\t\t" . '<loc>' . esc_url(home_url("/sitemap-{$taxonomy->name}-1.xml")) . '</loc>' . "\n";
					echo "\t\t" . '<lastmod>' . date('c', current_time('timestamp')) . '</lastmod>' . "\n";
					echo "\t" . '</sitemap>' . "\n";
				}

			}

		}

		echo '</sitemapindex>' . "\n";

	}

	/**
	 * Render Individual Sitemap
	 */
	private static function render_sitemap($type, $page = 1) {

		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
		echo ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"';
		echo ' xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">' . "\n";

		// Check if it's a post type or taxonomy
		$post_types = get_post_types(array('public' => true));
		$taxonomies = get_taxonomies(array('public' => true));

		if (in_array($type, $post_types)) {
			self::render_posts_sitemap($type, $page);
		} elseif (in_array($type, $taxonomies)) {
			self::render_taxonomy_sitemap($type, $page);
		}

		echo '</urlset>' . "\n";

	}

	/**
	 * Render Posts Sitemap
	 */
	private static function render_posts_sitemap($post_type, $page = 1) {

		$offset = ($page - 1) * self::POSTS_PER_SITEMAP;

		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => self::POSTS_PER_SITEMAP,
			'offset' => $offset,
			'orderby' => 'modified',
			'order' => 'DESC',
			'post_status' => 'publish',
		);

		$posts = get_posts($args);

		foreach ($posts as $post) {

			// Skip if noindex
			if (get_post_meta($post->ID, '_padma_robots_noindex', true)) {
				continue;
			}

			$url = get_permalink($post->ID);
			$last_modified = get_the_modified_date('c', $post->ID);

			echo "\t" . '<url>' . "\n";
			echo "\t\t" . '<loc>' . esc_url($url) . '</loc>' . "\n";
			echo "\t\t" . '<lastmod>' . $last_modified . '</lastmod>' . "\n";
			echo "\t\t" . '<changefreq>weekly</changefreq>' . "\n";
			echo "\t\t" . '<priority>0.8</priority>' . "\n";

			// Add featured image
			if (has_post_thumbnail($post->ID)) {
				$image_url = get_the_post_thumbnail_url($post->ID, 'full');
				if ($image_url) {
					echo "\t\t" . '<image:image>' . "\n";
					echo "\t\t\t" . '<image:loc>' . esc_url($image_url) . '</image:loc>' . "\n";
					echo "\t\t\t" . '<image:title>' . esc_html(get_the_title($post->ID)) . '</image:title>' . "\n";
					echo "\t\t" . '</image:image>' . "\n";
				}
			}

			echo "\t" . '</url>' . "\n";

		}

	}

	/**
	 * Render Taxonomy Sitemap
	 */
	private static function render_taxonomy_sitemap($taxonomy, $page = 1) {

		$offset = ($page - 1) * self::POSTS_PER_SITEMAP;

		$args = array(
			'taxonomy' => $taxonomy,
			'hide_empty' => true,
			'number' => self::POSTS_PER_SITEMAP,
			'offset' => $offset,
		);

		$terms = get_terms($args);

		if (is_wp_error($terms) || empty($terms)) {
			return;
		}

		foreach ($terms as $term) {

			$url = get_term_link($term);

			if (is_wp_error($url)) {
				continue;
			}

			echo "\t" . '<url>' . "\n";
			echo "\t\t" . '<loc>' . esc_url($url) . '</loc>' . "\n";
			echo "\t\t" . '<changefreq>weekly</changefreq>' . "\n";
			echo "\t\t" . '<priority>0.6</priority>' . "\n";
			echo "\t" . '</url>' . "\n";

		}

	}

	/**
	 * Get Sitemap URL
	 */
	public static function get_sitemap_url() {

		return home_url('/sitemap.xml');

	}

}
