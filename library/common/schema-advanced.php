<?php
/**
 * PS Padma Theme - Advanced Schema.org Generator
 *
 * Generates rich structured data for various content types
 *
 * @package padma
 * @since 2.0.0
 */

class PadmaSchemaAdvanced {

	/**
	 * Initialize Advanced Schema Generator
	 */
	public static function init() {

		add_action('wp_head', array(__CLASS__, 'output_breadcrumb_schema'), 15);
		add_action('wp_head', array(__CLASS__, 'output_organization_schema'), 14);

	}

	/**
	 * Generate Breadcrumb Schema
	 */
	public static function output_breadcrumb_schema() {

		if (!is_singular() && !is_archive()) {
			return;
		}

		// Collect breadcrumb items
		$items = array();
		$position = 1;

		// Home
		$items[] = array(
			'@type' => 'ListItem',
			'position' => $position++,
			'name' => __('Home', 'padma'),
			'item' => esc_url(home_url('/')),
		);

		// For single posts
		if (is_singular()) {
			$post = get_queried_object();

			// Category/Taxonomy
			if (is_singular('post')) {
				$categories = get_the_category($post->ID);
				if (!empty($categories)) {
					$cat = $categories[0];
					$items[] = array(
						'@type' => 'ListItem',
						'position' => $position++,
						'name' => $cat->name,
						'item' => esc_url(get_category_link($cat->term_id)),
					);
				}
			}

			// Current page
			$items[] = array(
				'@type' => 'ListItem',
				'position' => $position++,
				'name' => get_the_title($post->ID),
				'item' => esc_url(get_permalink($post->ID)),
			);
		}

		// For archives
		if (is_archive()) {
			if (is_category()) {
				$cat = get_queried_object();
				$items[] = array(
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $cat->name,
					'item' => esc_url(get_category_link($cat->term_id)),
				);
			} elseif (is_tag()) {
				$tag = get_queried_object();
				$items[] = array(
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $tag->name,
					'item' => esc_url(get_tag_link($tag->term_id)),
				);
			}
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => $items,
		);

		echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

	/**
	 * Generate Organization Schema
	 */
	public static function output_organization_schema() {

		if (!is_front_page()) {
			return;
		}

		$logo_id = get_theme_mod('custom_logo');
		$logo_url = '';

		if ($logo_id) {
			$logo = wp_get_attachment_image_src($logo_id, 'full');
			if ($logo) {
				$logo_url = $logo[0];
			}
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => get_bloginfo('name'),
			'url' => esc_url(home_url('/')),
			'description' => get_bloginfo('description'),
		);

		if ($logo_url) {
			$schema['logo'] = array(
				'@type' => 'ImageObject',
				'url' => esc_url($logo_url),
				'width' => 250,
				'height' => 60,
			);
		}

		// Contact information
		$contact_phone = get_theme_mod('padma_contact_phone');
		$contact_email = get_theme_mod('padma_contact_email');

		if ($contact_phone || $contact_email) {
			$schema['contactPoint'] = array(
				'@type' => 'ContactPoint',
				'contactType' => 'Customer Service',
			);

			if ($contact_phone) {
				$schema['contactPoint']['telephone'] = $contact_phone;
			}

			if ($contact_email) {
				$schema['contactPoint']['email'] = $contact_email;
			}
		}

		echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

	/**
	 * Generate FAQ Schema from Post Meta
	 * Usage: Add FAQ items via custom post meta
	 */
	public static function generate_faq_schema($post_id) {

		$faq_items = get_post_meta($post_id, '_padma_faq_items', true);

		if (empty($faq_items) || !is_array($faq_items)) {
			return '';
		}

		$schema_items = array();

		foreach ($faq_items as $item) {
			if (isset($item['question']) && isset($item['answer'])) {
				$schema_items[] = array(
					'@type' => 'Question',
					'name' => sanitize_text_field($item['question']),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text' => wp_kses_post($item['answer']),
					),
				);
			}
		}

		if (empty($schema_items)) {
			return '';
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'FAQPage',
			'mainEntity' => $schema_items,
		);

		return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

	/**
	 * Generate HowTo Schema from Post Meta
	 */
	public static function generate_howto_schema($post_id) {

		$howto_data = get_post_meta($post_id, '_padma_howto_data', true);

		if (empty($howto_data)) {
			return '';
		}

		$steps = array();

		if (isset($howto_data['steps']) && is_array($howto_data['steps'])) {
			foreach ($howto_data['steps'] as $index => $step) {
				$steps[] = array(
					'@type' => 'HowToStep',
					'position' => $index + 1,
					'name' => sanitize_text_field($step['title'] ?? 'Step ' . ($index + 1)),
					'text' => wp_kses_post($step['description'] ?? ''),
				);
			}
		}

		if (empty($steps)) {
			return '';
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'HowTo',
			'name' => get_the_title($post_id),
			'step' => $steps,
		);

		if (isset($howto_data['yield'])) {
			$schema['yield'] = $howto_data['yield'];
		}

		if (isset($howto_data['time'])) {
			$schema['totalTime'] = $howto_data['time'];
		}

		return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

	/**
	 * Generate Review Schema
	 */
	public static function generate_review_schema($post_id, $rating = 5, $review_text = '') {

		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'Review',
			'reviewRating' => array(
				'@type' => 'Rating',
				'ratingValue' => $rating,
				'bestRating' => '5',
				'worstRating' => '1',
			),
			'author' => array(
				'@type' => 'Person',
				'name' => get_the_author_meta('display_name', get_post_field('post_author', $post_id)),
			),
			'reviewBody' => $review_text ?: get_the_excerpt($post_id),
			'datePublished' => get_the_date('Y-m-d', $post_id),
		);

		return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

	/**
	 * Generate Product Schema
	 */
	public static function generate_product_schema($post_id, $product_data = array()) {

		$default_data = array(
			'name' => get_the_title($post_id),
			'description' => get_the_excerpt($post_id),
			'image' => get_the_post_thumbnail_url($post_id, 'full'),
			'price' => 0,
			'currency' => 'EUR',
			'rating' => 5,
			'reviewCount' => 1,
		);

		$product_data = array_merge($default_data, $product_data);

		$schema = array(
			'@context' => 'https://schema.org/',
			'@type' => 'Product',
			'name' => $product_data['name'],
			'description' => $product_data['description'],
			'image' => $product_data['image'],
		);

		if ($product_data['price'] > 0) {
			$schema['offers'] = array(
				'@type' => 'Offer',
				'url' => get_permalink($post_id),
				'priceCurrency' => $product_data['currency'],
				'price' => $product_data['price'],
			);
		}

		if ($product_data['rating'] > 0) {
			$schema['aggregateRating'] = array(
				'@type' => 'AggregateRating',
				'ratingValue' => $product_data['rating'],
				'reviewCount' => $product_data['reviewCount'],
			);
		}

		return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

	/**
	 * Generate Event Schema
	 */
	public static function generate_event_schema($post_id, $event_data = array()) {

		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'Event',
			'name' => get_the_title($post_id),
			'description' => get_the_excerpt($post_id),
			'image' => get_the_post_thumbnail_url($post_id, 'full'),
			'url' => get_permalink($post_id),
		);

		if (isset($event_data['startDate'])) {
			$schema['startDate'] = $event_data['startDate'];
		}

		if (isset($event_data['endDate'])) {
			$schema['endDate'] = $event_data['endDate'];
		}

		if (isset($event_data['location'])) {
			$schema['location'] = array(
				'@type' => 'Place',
				'name' => $event_data['location'],
			);
		}

		return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}

}
