<?php
/**
 * PS Padma Theme - SEO Suite Admin Page
 *
 * @package padma
 * @since 2.0.0
 */

PadmaAdmin::show_header(__('Padma SEO Suite', 'padma'));

// Save settings
if (isset($_POST['padma-seo-save'])) {
	check_admin_referer('padma-seo-settings', 'padma-seo-nonce');
	
	// General SEO settings
	if (isset($_POST['disable-theme-seo'])) {
		PadmaOption::set('disable-theme-seo', $_POST['disable-theme-seo']);
	}
	
	if (isset($_POST['enable-sitemaps'])) {
		PadmaOption::set('enable-sitemaps', $_POST['enable-sitemaps']);
		update_option('padma_sitemap_flush', '1'); // Trigger rewrite flush
	}
	
	if (isset($_POST['enable-schema'])) {
		PadmaOption::set('enable-schema', $_POST['enable-schema']);
	}
	
	if (isset($_POST['disable-schema-support'])) {
		PadmaOption::set('disable-schema-support', $_POST['disable-schema-support']);
	}
	
	// Social settings
	if (isset($_POST['facebook-app-id'])) {
		PadmaOption::set('facebook-app-id', sanitize_text_field($_POST['facebook-app-id']));
	}
	
	if (isset($_POST['twitter-username'])) {
		PadmaOption::set('twitter-username', sanitize_text_field($_POST['twitter-username']));
	}
	
	// Contact info for Organization Schema
	if (isset($_POST['org-contact-phone'])) {
		set_theme_mod('padma_contact_phone', sanitize_text_field($_POST['org-contact-phone']));
	}
	
	if (isset($_POST['org-contact-email'])) {
		set_theme_mod('padma_contact_email', sanitize_email($_POST['org-contact-email']));
	}
	
	echo '<div class="updated"><p>' . __('SEO settings saved successfully!', 'padma') . '</p></div>';
}

$sitemap_enabled = PadmaOption::get('enable-sitemaps', 'general', true);
$schema_enabled = !PadmaOption::get('disable-schema-support', 'general', false);
$disable_theme_seo = PadmaOption::get('disable-theme-seo', 'general', false);
$facebook_app_id = PadmaOption::get('facebook-app-id', 'general', '');
$twitter_username = PadmaOption::get('twitter-username', 'general', '');
$org_phone = get_theme_mod('padma_contact_phone', '');
$org_email = get_theme_mod('padma_contact_email', '');

?>

<style>
.padma-seo-page {
	max-width: 1200px;
	margin: 20px 0;
}
.padma-seo-tabs {
	display: flex;
	border-bottom: 2px solid #ddd;
	margin-bottom: 30px;
	gap: 10px;
}
.padma-seo-tab {
	padding: 12px 24px;
	background: #f1f1f1;
	border: none;
	cursor: pointer;
	font-size: 14px;
	font-weight: 600;
	border-radius: 4px 4px 0 0;
	transition: all 0.3s;
}
.padma-seo-tab.active {
	background: #2271b1;
	color: white;
}
.padma-seo-tab-content {
	display: none;
}
.padma-seo-tab-content.active {
	display: block;
}
.padma-seo-box {
	background: white;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 20px;
}
.padma-seo-box h2 {
	margin-top: 0;
	padding-bottom: 10px;
	border-bottom: 1px solid #eee;
}
.padma-seo-field {
	margin-bottom: 20px;
}
.padma-seo-field label {
	display: block;
	font-weight: 600;
	margin-bottom: 8px;
}
.padma-seo-field input[type="text"],
.padma-seo-field input[type="email"],
.padma-seo-field textarea {
	width: 100%;
	max-width: 500px;
	padding: 8px 12px;
	border: 1px solid #ddd;
	border-radius: 4px;
}
.padma-seo-field small {
	display: block;
	margin-top: 5px;
	color: #666;
}
.padma-seo-status {
	display: inline-block;
	padding: 6px 12px;
	border-radius: 4px;
	font-size: 13px;
	font-weight: 600;
}
.padma-seo-status.enabled {
	background: #edfaed;
	color: #1e4620;
}
.padma-seo-status.disabled {
	background: #fbeaea;
	color: #5a1515;
}
.padma-seo-info-box {
	background: #f0f6fc;
	border-left: 4px solid #2271b1;
	padding: 15px;
	margin: 20px 0;
}
.padma-seo-warning-box {
	background: #fcf3e8;
	border-left: 4px solid #f0b849;
	padding: 15px;
	margin: 20px 0;
}
.padma-button-primary {
	background: #2271b1;
	color: white;
	border: none;
	padding: 10px 20px;
	border-radius: 4px;
	cursor: pointer;
	font-size: 14px;
	font-weight: 600;
}
.padma-button-primary:hover {
	background: #135e96;
}
</style>

<div class="padma-seo-page">
	
	<!-- Tabs -->
	<div class="padma-seo-tabs">
		<button class="padma-seo-tab active" data-tab="general"><?php _e('General', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="sitemaps"><?php _e('XML Sitemaps', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="schema"><?php _e('Schema.org', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="social"><?php _e('Social Media', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="advanced"><?php _e('Advanced', 'padma'); ?></button>
	</div>

	<form method="post" action="">
		<?php wp_nonce_field('padma-seo-settings', 'padma-seo-nonce'); ?>

		<!-- General Tab -->
		<div class="padma-seo-tab-content active" data-tab-content="general">
			
			<div class="padma-seo-box">
				<h2><?php _e('SEO Features', 'padma'); ?></h2>
				
				<div class="padma-seo-info-box">
					<strong><?php _e('Padma SEO Suite Status:', 'padma'); ?></strong>
					<span class="padma-seo-status <?php echo $disable_theme_seo ? 'disabled' : 'enabled'; ?>">
						<?php echo $disable_theme_seo ? __('Deaktiviert', 'padma') : __('Aktiviert', 'padma'); ?>
					</span>
				</div>

				<div class="padma-seo-field">
					<label>
						<input type="checkbox" name="disable-theme-seo" value="1" <?php checked($disable_theme_seo); ?> />
						<?php _e('Disable Padma SEO Suite', 'padma'); ?>
					</label>
					<small><?php _e('Check this if you are using a third-party SEO plugin like Yoast or RankMath', 'padma'); ?></small>
				</div>
			</div>

			<div class="padma-seo-box">
				<h2><?php _e('Active Features', 'padma'); ?></h2>
				
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><?php _e('Focus Keyword Analysis per Post', 'padma'); ?></li>
					<li><?php _e('Meta Title & Description Editor', 'padma'); ?></li>
					<li><?php _e('Real-time SEO Score Calculation', 'padma'); ?></li>
					<li><?php _e('Canonical URL Management', 'padma'); ?></li>
					<li><?php _e('Robots Meta Control (noindex/nofollow)', 'padma'); ?></li>
					<li><?php _e('Open Graph Tags (Facebook, LinkedIn)', 'padma'); ?></li>
					<li><?php _e('Twitter Card Tags', 'padma'); ?></li>
					<li><?php _e('XML Sitemaps (Auto-generated)', 'padma'); ?></li>
					<li><?php _e('Structured Data (Schema.org)', 'padma'); ?></li>
					<li><?php _e('Breadcrumb Schema', 'padma'); ?></li>
				</ul>
			</div>

		</div>

		<!-- Sitemaps Tab -->
		<div class="padma-seo-tab-content" data-tab-content="sitemaps">
			
			<div class="padma-seo-box">
				<h2><?php _e('XML Sitemaps', 'padma'); ?></h2>
				
				<div class="padma-seo-field">
					<label>
						<input type="checkbox" name="enable-sitemaps" value="1" <?php checked($sitemap_enabled); ?> />
						<?php _e('Enable XML Sitemaps', 'padma'); ?>
					</label>
					<small><?php _e('Automatically generates XML sitemaps for search engines', 'padma'); ?></small>
				</div>

				<?php if ($sitemap_enabled) : ?>
					<div class="padma-seo-info-box">
						<strong><?php _e('Your Sitemap URL:', 'padma'); ?></strong><br>
						<a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>" target="_blank">
							<?php echo esc_url(home_url('/sitemap.xml')); ?>
						</a>
						<br><br>
						<small><?php _e('Submit this URL to Google Search Console and Bing Webmaster Tools', 'padma'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($sitemap_enabled) : ?>
				<div class="padma-seo-box">
					<h2><?php _e('Sitemap Content', 'padma'); ?></h2>
					
					<p><?php _e('The following content types are automatically included in your sitemap:', 'padma'); ?></p>
					
					<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
						<li><?php _e('Posts', 'padma'); ?></li>
						<li><?php _e('Pages', 'padma'); ?></li>
						<li><?php _e('Custom Post Types (if public)', 'padma'); ?></li>
						<li><?php _e('Categories', 'padma'); ?></li>
						<li><?php _e('Tags', 'padma'); ?></li>
					</ul>
					
					<p><small><?php _e('Posts with "noindex" enabled will be automatically excluded', 'padma'); ?></small></p>
				</div>
			<?php endif; ?>

		</div>

		<!-- Schema Tab -->
		<div class="padma-seo-tab-content" data-tab-content="schema">
			
			<div class="padma-seo-box">
				<h2><?php _e('Structured Data (Schema.org)', 'padma'); ?></h2>
				
				<div class="padma-seo-field">
					<label>
						<input type="checkbox" name="disable-schema-support" value="1" <?php checked(!$schema_enabled); ?> />
						<?php _e('Disable Schema.org Markup', 'padma'); ?>
					</label>
					<small><?php _e('Schema.org helps search engines understand your content better', 'padma'); ?></small>
				</div>
			</div>

			<?php if ($schema_enabled) : ?>
				<div class="padma-seo-box">
					<h2><?php _e('Available Schema Types', 'padma'); ?></h2>
					
					<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
						<li><strong><?php _e('Article Schema', 'padma'); ?></strong> - <?php _e('Automatically added to posts', 'padma'); ?></li>
						<li><strong><?php _e('Breadcrumb Schema', 'padma'); ?></strong> - <?php _e('Automatically added to all pages', 'padma'); ?></li>
						<li><strong><?php _e('Organization Schema', 'padma'); ?></strong> - <?php _e('Added to homepage', 'padma'); ?></li>
						<li><strong><?php _e('FAQ Schema', 'padma'); ?></strong> - <?php _e('Available via custom post meta', 'padma'); ?></li>
						<li><strong><?php _e('HowTo Schema', 'padma'); ?></strong> - <?php _e('Available via custom post meta', 'padma'); ?></li>
						<li><strong><?php _e('Product Schema', 'padma'); ?></strong> - <?php _e('For e-commerce content', 'padma'); ?></li>
						<li><strong><?php _e('Event Schema', 'padma'); ?></strong> - <?php _e('For event pages', 'padma'); ?></li>
					</ul>
				</div>

				<div class="padma-seo-box">
					<h2><?php _e('Organization Information', 'padma'); ?></h2>
					
					<div class="padma-seo-field">
						<label><?php _e('Contact Phone', 'padma'); ?></label>
						<input type="text" name="org-contact-phone" value="<?php echo esc_attr($org_phone); ?>" placeholder="+49 123 456789" />
						<small><?php _e('Used in Organization Schema on the homepage', 'padma'); ?></small>
					</div>

					<div class="padma-seo-field">
						<label><?php _e('Contact Email', 'padma'); ?></label>
						<input type="email" name="org-contact-email" value="<?php echo esc_attr($org_email); ?>" placeholder="info@example.com" />
						<small><?php _e('Used in Organization Schema on the homepage', 'padma'); ?></small>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<!-- Social Media Tab -->
		<div class="padma-seo-tab-content" data-tab-content="social">
			
			<div class="padma-seo-box">
				<h2><?php _e('Social Media Settings', 'padma'); ?></h2>
				
				<div class="padma-seo-field">
					<label><?php _e('Facebook App ID', 'padma'); ?></label>
					<input type="text" name="facebook-app-id" value="<?php echo esc_attr($facebook_app_id); ?>" placeholder="123456789012345" />
					<small><?php _e('Optional. Required for Facebook Insights.', 'padma'); ?> <a href="https://developers.facebook.com/apps/" target="_blank"><?php _e('Get your App ID', 'padma'); ?></a></small>
				</div>

				<div class="padma-seo-field">
					<label><?php _e('Twitter Username', 'padma'); ?></label>
					<input type="text" name="twitter-username" value="<?php echo esc_attr($twitter_username); ?>" placeholder="@yourusername" />
					<small><?php _e('Your Twitter/X username (with or without @)', 'padma'); ?></small>
				</div>
			</div>

			<div class="padma-seo-box">
				<h2><?php _e('Automatic Open Graph Tags', 'padma'); ?></h2>
				
				<p><?php _e('The following Open Graph tags are automatically added to all posts and pages:', 'padma'); ?></p>
				
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><code>og:title</code> - <?php _e('Post/Page title', 'padma'); ?></li>
					<li><code>og:description</code> - <?php _e('Meta description or excerpt', 'padma'); ?></li>
					<li><code>og:type</code> - <?php _e('article or website', 'padma'); ?></li>
					<li><code>og:url</code> - <?php _e('Canonical URL', 'padma'); ?></li>
					<li><code>og:image</code> - <?php _e('Featured image', 'padma'); ?></li>
				</ul>
				
				<p><small><?php _e('You can override these on each post via the Padma SEO metabox', 'padma'); ?></small></p>
			</div>

		</div>

		<!-- Advanced Tab -->
		<div class="padma-seo-tab-content" data-tab-content="advanced">
			
			<div class="padma-seo-box">
				<h2><?php _e('SEO Suite Documentation', 'padma'); ?></h2>
				
				<h3><?php _e('Per-Post SEO Settings', 'padma'); ?></h3>
				<p><?php _e('When editing any post or page, scroll down to the "Padma SEO Suite" metabox to configure:', 'padma'); ?></p>
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><?php _e('Focus Keyword', 'padma'); ?></li>
					<li><?php _e('Meta Description', 'padma'); ?></li>
					<li><?php _e('Social Media Title & Description', 'padma'); ?></li>
					<li><?php _e('Canonical URL', 'padma'); ?></li>
					<li><?php _e('Robots Settings', 'padma'); ?></li>
				</ul>

				<h3><?php _e('Testing Your Setup', 'padma'); ?></h3>
				<p><?php _e('Use these tools to validate your SEO implementation:', 'padma'); ?></p>
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><a href="https://search.google.com/test/rich-results" target="_blank"><?php _e('Google Rich Results Test', 'padma'); ?></a></li>
					<li><a href="https://developers.facebook.com/tools/debug/" target="_blank"><?php _e('Facebook Sharing Debugger', 'padma'); ?></a></li>
					<li><a href="https://cards-dev.twitter.com/validator" target="_blank"><?php _e('Twitter Card Validator', 'padma'); ?></a></li>
					<li><a href="https://search.google.com/search-console" target="_blank"><?php _e('Google Search Console', 'padma'); ?></a></li>
				</ul>
			</div>

			<div class="padma-seo-warning-box">
				<strong><?php _e('Important Notes:', 'padma'); ?></strong>
				<ul style="list-style: disc; margin-left: 25px; margin-top: 10px; line-height: 1.8;">
					<li><?php _e('If you install a third-party SEO plugin, be sure to disable Padma SEO Suite to avoid conflicts', 'padma'); ?></li>
					<li><?php _e('After enabling/disabling sitemaps, you may need to flush permalinks (Settings > Permalinks > Save)', 'padma'); ?></li>
					<li><?php _e('Schema.org markup requires the Spatie library (vendor folder must exist)', 'padma'); ?></li>
				</ul>
			</div>

		</div>

		<p>
			<button type="submit" name="padma-seo-save" class="padma-button-primary">
				<?php _e('Save SEO Settings', 'padma'); ?>
			</button>
		</p>

	</form>

</div>

<script>
jQuery(document).ready(function($) {
	// Tab switching
	$('.padma-seo-tab').on('click', function() {
		var tab = $(this).data('tab');
		
		$('.padma-seo-tab').removeClass('active');
		$(this).addClass('active');
		
		$('.padma-seo-tab-content').removeClass('active');
		$('[data-tab-content="' + tab + '"]').addClass('active');
	});
});
</script>

<?php

PadmaAdmin::show_footer();
