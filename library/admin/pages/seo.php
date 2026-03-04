<?php
/**
 * PS Padma Theme - SEO Suite Admin Page
 *
 * @package padma
 * @since 2.0.0
 */

PadmaAdmin::show_header(__('PS Padma SEO Suite', 'padma'));

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
	
	echo '<div class="updated"><p>' . __('SEO Einstellungen erfolgreich gespeichert!', 'padma') . '</p></div>';
}

$sitemap_enabled = PadmaOption::get('enable-sitemaps', 'general', true);
$schema_enabled = !PadmaOption::get('disable-schema-support', 'general', false);
$disable_theme_seo = PadmaOption::get('disable-theme-seo', 'general', false);
$facebook_app_id = PadmaOption::get('facebook-app-id', 'general', '');
$twitter_username = PadmaOption::get('twitter-username', 'general', '');
$org_phone = get_theme_mod('padma_contact_phone', '');
$org_email = get_theme_mod('padma_contact_email', '');

?>

<div class="padma-seo-page">
	
	<!-- Tabs -->
	<div class="padma-seo-tabs">
		<button class="padma-seo-tab active" data-tab="general"><?php _e('Allgemein', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="sitemaps"><?php _e('XML Sitemaps', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="schema"><?php _e('Schema.org', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="social"><?php _e('Social Media', 'padma'); ?></button>
		<button class="padma-seo-tab" data-tab="advanced"><?php _e('Erweitert', 'padma'); ?></button>
	</div>

	<form method="post" action="">
		<?php wp_nonce_field('padma-seo-settings', 'padma-seo-nonce'); ?>

		<!-- General Tab -->
		<div class="padma-seo-tab-content active" data-tab-content="general">
			
			<div class="padma-seo-box">
				<h2><?php _e('SEO Features', 'padma'); ?></h2>
				
				<div class="padma-seo-info-box">
					<strong><?php _e('PS Padma SEO Suite Status:', 'padma'); ?></strong>
					<span class="padma-seo-status <?php echo $disable_theme_seo ? 'disabled' : 'enabled'; ?>">
						<?php echo $disable_theme_seo ? __('Deaktiviert', 'padma') : __('Aktiviert', 'padma'); ?>
					</span>
				</div>

				<div class="padma-seo-field">
					<label>
						<input type="checkbox" name="disable-theme-seo" value="1" <?php checked($disable_theme_seo); ?> />
						<?php _e('Deaktiviere PS Padma SEO Suite', 'padma'); ?>
					</label>
					<small><?php _e('Aktiviere diese Option, wenn Du ein Drittanbieter-SEO-Plugin wie Yoast oder RankMath verwenden möchtest', 'padma'); ?></small>
				</div>
			</div>

			<div class="padma-seo-box">
				<h2><?php _e('Aktive Funktionen', 'padma'); ?></h2>
				
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><?php _e('Focus Keyword Analyse pro Beitrag', 'padma'); ?></li>
					<li><?php _e('Meta-Titel & Beschreibung Editor', 'padma'); ?></li>
					<li><?php _e('Echtzeit SEO Score Berechnung', 'padma'); ?></li>
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
						<?php _e('Aktiviere XML Sitemaps', 'padma'); ?>
					</label>
					<small><?php _e('Generiert automatisch XML-Sitemaps für Suchmaschinen', 'padma'); ?></small>
				</div>

				<?php if ($sitemap_enabled) : ?>
					<div class="padma-seo-info-box">
						<strong><?php _e('Deine Sitemap URL:', 'padma'); ?></strong><br>
						<a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>" target="_blank">
							<?php echo esc_url(home_url('/sitemap.xml')); ?>
						</a>
						<br><br>
						<small><?php _e('Reiche diese URL bei der Google Search Console und den Bing Webmaster Tools ein', 'padma'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($sitemap_enabled) : ?>
				<div class="padma-seo-box">
					<h2><?php _e('Sitemap Inhalt', 'padma'); ?></h2>
					
					<p><?php _e('Die folgenden Inhaltstypen werden automatisch in Deine Sitemap aufgenommen:', 'padma'); ?></p>
					
					<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
						<li><?php _e('Beiträge', 'padma'); ?></li>
						<li><?php _e('Seiten', 'padma'); ?></li>
						<li><?php _e('Benutzerdefinierte Beitragstypen (wenn öffentlich)', 'padma'); ?></li>
						<li><?php _e('Kategorien', 'padma'); ?></li>
						<li><?php _e('Schlagwörter', 'padma'); ?></li>
					</ul>
					
					<p><small><?php _e('Beiträge mit aktivierter "noindex"-Option werden automatisch ausgeschlossen', 'padma'); ?></small></p>
				</div>
			<?php endif; ?>

		</div>

		<!-- Schema Tab -->
		<div class="padma-seo-tab-content" data-tab-content="schema">
			
			<div class="padma-seo-box">
				<h2><?php _e('Strukturierte Daten (Schema.org)', 'padma'); ?></h2>
				
				<div class="padma-seo-field">
					<label>
						<input type="checkbox" name="disable-schema-support" value="1" <?php checked(!$schema_enabled); ?> />
						<?php _e('Schema.org Markup deaktivieren', 'padma'); ?>
					</label>
					<small><?php _e('Schema.org hilft Suchmaschinen, Deine Inhalte besser zu verstehen', 'padma'); ?></small>
				</div>
			</div>

			<?php if ($schema_enabled) : ?>
				<div class="padma-seo-box">
					<h2><?php _e('Verfügbare Schema-Typen', 'padma'); ?></h2>
					
					<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
						<li><strong><?php _e('Article Schema', 'padma'); ?></strong> - <?php _e('Automatisch zu Beiträgen hinzugefügt', 'padma'); ?></li>
						<li><strong><?php _e('Breadcrumb Schema', 'padma'); ?></strong> - <?php _e('Automatisch zu allen Seiten hinzugefügt', 'padma'); ?></li>
						<li><strong><?php _e('Organization Schema', 'padma'); ?></strong> - <?php _e('Zur Startseite hinzugefügt', 'padma'); ?></li>
						<li><strong><?php _e('FAQ Schema', 'padma'); ?></strong> - <?php _e('Über benutzerdefinierte Beitragstypen verfügbar', 'padma'); ?></li>
						<li><strong><?php _e('HowTo Schema', 'padma'); ?></strong> - <?php _e('Über benutzerdefinierte Beitragstypen verfügbar', 'padma'); ?></li>
						<li><strong><?php _e('Product Schema', 'padma'); ?></strong> - <?php _e('Für E-Commerce-Inhalte', 'padma'); ?></li>
						<li><strong><?php _e('Event Schema', 'padma'); ?></strong> - <?php _e('Für Veranstaltungsseiten', 'padma'); ?></li>
					</ul>
				</div>

				<div class="padma-seo-box">
					<h2><?php _e('Organisationsinformationen', 'padma'); ?></h2>
					
					<div class="padma-seo-field">
						<label><?php _e('Kontakttelefon', 'padma'); ?></label>
						<input type="text" name="org-contact-phone" value="<?php echo esc_attr($org_phone); ?>" placeholder="+49 123 456789" />
						<small><?php _e('Wird im Organization Schema auf der Startseite verwendet', 'padma'); ?></small>
					</div>

					<div class="padma-seo-field">
						<label><?php _e('Kontakt-E-Mail', 'padma'); ?></label>
						<input type="email" name="org-contact-email" value="<?php echo esc_attr($org_email); ?>" placeholder="info@example.com" />
						<small><?php _e('Wird im Organization Schema auf der Startseite verwendet', 'padma'); ?></small>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<!-- Social Media Tab -->
		<div class="padma-seo-tab-content" data-tab-content="social">
			
			<div class="padma-seo-box">
				<h2><?php _e('Social Media Einstellungen', 'padma'); ?></h2>
				
				<div class="padma-seo-field">
					<label><?php _e('Facebook App ID', 'padma'); ?></label>
					<input type="text" name="facebook-app-id" value="<?php echo esc_attr($facebook_app_id); ?>" placeholder="123456789012345" />
					<small><?php _e('Optional. Benötigt für Facebook Insights.', 'padma'); ?> <a href="https://developers.facebook.com/apps/" target="_blank"><?php _e('Get your App ID', 'padma'); ?></a></small>
				</div>

				<div class="padma-seo-field">
					<label><?php _e('Twitter Username', 'padma'); ?></label>
					<input type="text" name="twitter-username" value="<?php echo esc_attr($twitter_username); ?>" placeholder="@yourusername" />
					<small><?php _e('Dein Twitter/X-Benutzername (mit oder ohne @)', 'padma'); ?></small>
				</div>
			</div>

			<div class="padma-seo-box">
				<h2><?php _e('Automatische Open Graph Tags', 'padma'); ?></h2>
				
				<p><?php _e('Die folgenden Open Graph Tags werden automatisch zu allen Beiträgen und Seiten hinzugefügt:', 'padma'); ?></p>
				
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><code>og:title</code> - <?php _e('Beitrags-/Seitentitel', 'padma'); ?></li>
					<li><code>og:description</code> - <?php _e('Meta-Beschreibung oder Auszug', 'padma'); ?></li>
					<li><code>og:type</code> - <?php _e('Artikel oder Webseite', 'padma'); ?></li>
					<li><code>og:url</code> - <?php _e('Kanonciale URL', 'padma'); ?></li>
					<li><code>og:image</code> - <?php _e('Beitragsbild', 'padma'); ?></li>
				</ul>
				
				<p><small><?php _e('Du kannst diese auf jedem Beitrag über das Padma SEO-Metabox überschreiben', 'padma'); ?></small></p>
			</div>

		</div>

		<!-- Advanced Tab -->
		<div class="padma-seo-tab-content" data-tab-content="advanced">
			
			<div class="padma-seo-box">
				<h2><?php _e('SEO Suite Dokumentation', 'padma'); ?></h2>
				
				<h3><?php _e('Pro-Beitrag SEO Einstellungen', 'padma'); ?></h3>
				<p><?php _e('Beim Bearbeiten eines Beitrags oder einer Seite, scrolle nach unten zum "Padma SEO Suite" Metabox, um Folgendes zu konfigurieren:', 'padma'); ?></p>
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><?php _e('Fokus-Keyword', 'padma'); ?></li>
					<li><?php _e('Meta-Beschreibung', 'padma'); ?></li>
					<li><?php _e('Social Media Titel & Beschreibung', 'padma'); ?></li>
					<li><?php _e('Kanonciale URL', 'padma'); ?></li>
					<li><?php _e('Robots Einstellungen', 'padma'); ?></li>
				</ul>

				<h3><?php _e('Testen Deiner Einrichtung', 'padma'); ?></h3>
				<p><?php _e('Verwende diese Tools, um Deine SEO-Implementierung zu überprüfen:', 'padma'); ?></p>
				<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
					<li><a href="https://search.google.com/test/rich-results" target="_blank"><?php _e('Google Rich Results Test', 'padma'); ?></a></li>
					<li><a href="https://developers.facebook.com/tools/debug/" target="_blank"><?php _e('Facebook Sharing Debugger', 'padma'); ?></a></li>
					<li><a href="https://cards-dev.twitter.com/validator" target="_blank"><?php _e('Twitter Card Validator', 'padma'); ?></a></li>
					<li><a href="https://search.google.com/search-console" target="_blank"><?php _e('Google Search Console', 'padma'); ?></a></li>
				</ul>
			</div>

			<div class="padma-seo-warning-box">
				<strong><?php _e('Wichtige Hinweise:', 'padma'); ?></strong>
				<ul style="list-style: disc; margin-left: 25px; margin-top: 10px; line-height: 1.8;">
					<li><?php _e('Wenn Du ein Drittanbieter-SEO-Plugin installierst, stelle sicher, dass Du die Padma SEO Suite deaktivierst, um Konflikte zu vermeiden', 'padma'); ?></li>
					<li><?php _e('Nach dem Aktivieren/Deaktivieren von Sitemaps musst Du möglicherweise die Permalinks aktualisieren (Einstellungen > Permalinks > Speichern)', 'padma'); ?></li>
					<li><?php _e('Schema.org-Markup erfordert die Spatie-Bibliothek (der Vendor-Ordner muss vorhanden sein)', 'padma'); ?></li>
				</ul>
			</div>

		</div>

		<p>
			<button type="submit" name="padma-seo-save" class="padma-button-primary">
				<?php _e('SEO Einstellungen speichern', 'padma'); ?>
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
