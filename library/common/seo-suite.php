<?php
/**
 * PS Padma Theme - SEO Suite
 *
 * Extended SEO functionality with keyword analysis, schema generation,
 * sitemaps, and content optimization
 *
 * @package padma
 * @since 2.0.0
 */

class PadmaSEOSuite {

	/**
	 * Initialize SEO Suite
	 */
	public static function init() {

		// Admin hooks
		add_action('add_meta_boxes', array(__CLASS__, 'add_seo_metabox'), 10);
		add_action('save_post', array(__CLASS__, 'save_seo_metabox'), 10);
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts'));

		// Frontend hooks
		add_filter('wp_head', array(__CLASS__, 'render_seo_meta'), 5);
		add_action('wp_head', array(__CLASS__, 'render_structured_data'), 10);
		add_filter('document_title_parts', array(__CLASS__, 'setup_seo_title'), 10);

		// Canonical tags
		add_action('wp_head', array(__CLASS__, 'render_canonical'), 2);

		// Robots meta
		add_action('wp_head', array(__CLASS__, 'render_robots_meta'), 3);

	}

	/**
	 * Add SEO Metabox to Posts
	 */
	public static function add_seo_metabox() {

		$post_types = array('post', 'page');
		$post_types = apply_filters('padma_seo_post_types', $post_types);

		foreach ($post_types as $post_type) {
			add_meta_box(
				'padma-seo-suite',
				__('Padma SEO Suite', 'padma'),
				array(__CLASS__, 'render_seo_metabox'),
				$post_type,
				'normal',
				'high'
			);
		}

	}

	/**
	 * Render SEO Metabox Content
	 */
	public static function render_seo_metabox($post) {

		wp_nonce_field('padma_seo_suite_nonce', 'padma_seo_suite_nonce');

		$focus_keyword = get_post_meta($post->ID, '_padma_focus_keyword', true);
		$meta_description = get_post_meta($post->ID, '_padma_meta_description', true);
		$canonical_url = get_post_meta($post->ID, '_padma_canonical_url', true);
		$og_title = get_post_meta($post->ID, '_padma_og_title', true);
		$og_description = get_post_meta($post->ID, '_padma_og_description', true);
		$robots_noindex = get_post_meta($post->ID, '_padma_robots_noindex', true);

		?>
		<div class="padma-seo-suite-metabox">
			<style>
				.padma-seo-field { margin-bottom: 20px; }
				.padma-seo-field label { display: block; margin-bottom: 5px; font-weight: 600; }
				.padma-seo-field textarea,
				.padma-seo-field input[type="text"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
				.padma-seo-score { display: inline-block; padding: 8px 12px; border-radius: 4px; margin: 10px 0; }
				.padma-seo-score.good { background: #edfaed; color: #1e4620; }
				.padma-seo-score.ok { background: #fcf3e8; color: #674e1c; }
				.padma-seo-score.bad { background: #fbeaea; color: #5a1515; }
			</style>

			<!-- Focus Keyword -->
			<div class="padma-seo-field">
				<label for="padma_focus_keyword"><?php _e('Focus Keyword', 'padma'); ?></label>
				<input 
					type="text" 
					id="padma_focus_keyword" 
					name="padma_focus_keyword" 
					value="<?php echo esc_attr($focus_keyword); ?>" 
					placeholder="<?php _e('Enter main keyword for this content', 'padma'); ?>"
				/>
				<small><?php _e('The primary keyword this content should rank for', 'padma'); ?></small>
			</div>

			<!-- Meta Description -->
			<div class="padma-seo-field">
				<label for="padma_meta_description"><?php _e('Meta Description', 'padma'); ?></label>
				<textarea 
					id="padma_meta_description" 
					name="padma_meta_description" 
					rows="3" 
					maxlength="160" 
					placeholder="<?php _e('Optimal: 150-160 characters', 'padma'); ?>"
				><?php echo esc_textarea($meta_description); ?></textarea>
				<small>
					<?php _e('Length:', 'padma'); ?> <span id="meta_desc_count"><?php echo strlen($meta_description); ?></span>/160
				</small>
			</div>

			<!-- OG Title (Social Share) -->
			<div class="padma-seo-field">
				<label for="padma_og_title"><?php _e('Social Media Title', 'padma'); ?></label>
				<input 
					type="text" 
					id="padma_og_title" 
					name="padma_og_title" 
					value="<?php echo esc_attr($og_title); ?>" 
					placeholder="<?php _e('Leave empty to use post title', 'padma'); ?>"
					maxlength="70"
				/>
				<small><?php _e('Used when sharing on Facebook, Twitter, LinkedIn', 'padma'); ?></small>
			</div>

			<!-- OG Description -->
			<div class="padma-seo-field">
				<label for="padma_og_description"><?php _e('Social Media Description', 'padma'); ?></label>
				<textarea 
					id="padma_og_description" 
					name="padma_og_description" 
					rows="3" 
					maxlength="200" 
					placeholder="<?php _e('Optimal: 180-200 characters', 'padma'); ?>"
				><?php echo esc_textarea($og_description); ?></textarea>
				<small><?php _e('Length:', 'padma'); ?> <span id="og_desc_count"><?php echo strlen($og_description); ?></span>/200</small>
			</div>

			<!-- Canonical URL -->
			<div class="padma-seo-field">
				<label for="padma_canonical_url"><?php _e('Canonical URL', 'padma'); ?></label>
				<input 
					type="text" 
					id="padma_canonical_url" 
					name="padma_canonical_url" 
					value="<?php echo esc_attr($canonical_url); ?>" 
					placeholder="<?php echo get_permalink($post->ID); ?>"
				/>
				<small><?php _e('Leave empty for automatic. Specify if this is duplicate content.', 'padma'); ?></small>
			</div>

			<!-- Robots Settings -->
			<div class="padma-seo-field">
				<label>
					<input type="checkbox" name="padma_robots_noindex" value="1" <?php checked($robots_noindex); ?> />
					<?php _e('Prevent search engines from indexing this page', 'padma'); ?>
				</label>
			</div>

			<!-- SEO Score Preview -->
			<div id="padma_seo_score_preview"></div>

			<script>
				(function($) {
					function calculateSEOScore() {
						let score = 0;
						const maxScore = 100;
						const title = $('input[name="post_title"]').val() || '';
						const content = $('.editor-styles-wrapper').text() || '';
						const focusKeyword = $('#padma_focus_keyword').val() || '';
						const metaDesc = $('#padma_meta_description').val() || '';

						// Check focus keyword
						if (focusKeyword.length > 0) {
							score += 10;
							if (title.toLowerCase().includes(focusKeyword.toLowerCase())) score += 15;
							if (content.toLowerCase().includes(focusKeyword.toLowerCase())) score += 15;
						}

						// Check meta description
						if (metaDesc.length > 120 && metaDesc.length <= 160) score += 20;
						else if (metaDesc.length > 0) score += 10;

						// Check title
						if (title.length > 30 && title.length <= 60) score += 15;
						else if (title.length > 0) score += 10;

						// Check content length
						if (content.length > 300) score += 15;

						return Math.min(score, maxScore);
					}

					function updateSEOScore() {
						const score = calculateSEOScore();
						let status = 'bad';
						let statusText = '<?php _e('Needs improvement', 'padma'); ?>';

						if (score >= 70) {
							status = 'good';
							statusText = '<?php _e('Good SEO', 'padma'); ?>';
						} else if (score >= 40) {
							status = 'ok';
							statusText = '<?php _e('Fair SEO', 'padma'); ?>';
						}

						$('#padma_seo_score_preview').html(
							'<div class="padma-seo-score ' + status + '">' +
							'<strong><?php _e('SEO Score:', 'padma'); ?></strong> ' + score + '/100 - ' + statusText +
							'</div>'
						);
					}

					// Update on field changes
					$('#padma_focus_keyword, #padma_meta_description, input[name="post_title"]').on('keyup', updateSEOScore);

					// Initial calculation
					updateSEOScore();

					// Update character counts
					$('#padma_meta_description').on('keyup', function() {
						$('#meta_desc_count').text($(this).val().length);
					});

					$('#padma_og_description').on('keyup', function() {
						$('#og_desc_count').text($(this).val().length);
					});
				})(jQuery);
			</script>
		</div>
		<?php

	}

	/**
	 * Save SEO Metabox Data
	 */
	public static function save_seo_metabox($post_id) {

		// Verify nonce
		if (!isset($_POST['padma_seo_suite_nonce']) || !wp_verify_nonce($_POST['padma_seo_suite_nonce'], 'padma_seo_suite_nonce')) {
			return;
		}

		// Check permissions
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		// Save focus keyword
		if (isset($_POST['padma_focus_keyword'])) {
			update_post_meta($post_id, '_padma_focus_keyword', sanitize_text_field($_POST['padma_focus_keyword']));
		}

		// Save meta description
		if (isset($_POST['padma_meta_description'])) {
			update_post_meta($post_id, '_padma_meta_description', sanitize_textarea_field($_POST['padma_meta_description']));
		}

		// Save OG title
		if (isset($_POST['padma_og_title'])) {
			update_post_meta($post_id, '_padma_og_title', sanitize_text_field($_POST['padma_og_title']));
		}

		// Save OG description
		if (isset($_POST['padma_og_description'])) {
			update_post_meta($post_id, '_padma_og_description', sanitize_textarea_field($_POST['padma_og_description']));
		}

		// Save canonical URL
		if (isset($_POST['padma_canonical_url'])) {
			$canonical = sanitize_url($_POST['padma_canonical_url']);
			update_post_meta($post_id, '_padma_canonical_url', $canonical);
		}

		// Save robots setting
		$robots_noindex = isset($_POST['padma_robots_noindex']) ? 1 : 0;
		update_post_meta($post_id, '_padma_robots_noindex', $robots_noindex);

	}

	/**
	 * Enqueue Admin Scripts
	 */
	public static function enqueue_admin_scripts($hook) {

		if (!in_array($hook, array('post.php', 'post-new.php'))) {
			return;
		}

		wp_enqueue_script('jquery');

	}

	/**
	 * Render Meta Tags on Frontend
	 */
	public static function render_seo_meta() {

		if (!is_singular()) {
			return;
		}

		$post = get_queried_object();

		// Meta description
		$meta_description = get_post_meta($post->ID, '_padma_meta_description', true);
		if ($meta_description) {
			echo '<meta name="description" content="' . esc_attr($meta_description) . '" />' . "\n";
		}

		// OG Tags
		$og_title = get_post_meta($post->ID, '_padma_og_title', true);
		if ($og_title) {
			echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
		} else {
			echo '<meta property="og:title" content="' . esc_attr(get_the_title($post->ID)) . '" />' . "\n";
		}

		$og_description = get_post_meta($post->ID, '_padma_og_description', true);
		if ($og_description) {
			echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
		} else if ($meta_description) {
			echo '<meta property="og:description" content="' . esc_attr($meta_description) . '" />' . "\n";
		}

		// OG Type
		echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '" />' . "\n";

		// OG URL
		echo '<meta property="og:url" content="' . esc_url(get_permalink($post->ID)) . '" />' . "\n";

		// Twitter Card
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr($og_title ?: get_the_title($post->ID)) . '" />' . "\n";

	}

	/**
	 * Render Canonical Tag
	 */
	public static function render_canonical() {

		if (!is_singular()) {
			return;
		}

		$post = get_queried_object();
		$canonical = get_post_meta($post->ID, '_padma_canonical_url', true);

		if (!$canonical) {
			$canonical = get_permalink($post->ID);
		}

		echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";

	}

	/**
	 * Render Robots Meta
	 */
	public static function render_robots_meta() {

		if (!is_singular()) {
			return;
		}

		$post = get_queried_object();
		$noindex = get_post_meta($post->ID, '_padma_robots_noindex', true);

		if ($noindex) {
			echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
		}

	}

	/**
	 * Render Structured Data (Schema.org)
	 */
	public static function render_structured_data() {

		if (!is_singular()) {
			return;
		}

		$post = get_queried_object();

		if (class_exists('PadmaSchema')) {
			echo PadmaSchema::article($post);
		}

	}

	/**
	 * Setup SEO Title Filters
	 */
	public static function setup_seo_title($title_parts) {

		if (is_singular()) {
			$post = get_queried_object();
			$custom_title = PadmaLayoutOption::get(PadmaLayout::get_current(), 'title', null, true, 'seo');

			if ($custom_title) {
				$title_parts['title'] = PadmaSeo::parse_seo_variables($custom_title);
			}
		}

		return $title_parts;

	}

}
