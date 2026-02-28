<?php
/**
 * Padma SEO Sitemaps Modul
 * Verwaltet XML Sitemap Einstellungen und Anzeige
 */

?>

<div class="big-tab" id="tab-sitemaps-content">
	
	<div class="postbox-container padma-postbox-container">
		<div class="postbox padma-admin-options-group">

			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text"><?php _e('Sitemap Einstellungen', 'padma'); ?></span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>

			<h2 class="hndle"><span><?php _e('XML Sitemap', 'padma'); ?></span></h2>

			<div class="inside">
				<div class="alert alert-blue">
					<p><strong><?php _e('Sitemap URL:', 'padma'); ?></strong> <a href="<?php echo home_url('/sitemap.xml'); ?>" target="_blank"><?php echo home_url('/sitemap.xml'); ?></a></p>
					<p class="description"><?php _e('Deine XML-Sitemap wurde automatisch generiert. Reiche sie in der Google Search Console und Bing Webmaster Tools ein.', 'padma'); ?></p>
				</div>

				<?php
				$form = array(
					array(
						'type' => 'checkbox',
						'label' => __('Sitemap Features', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'sitemap-include-images',
								'label' => __('Bilder in Sitemap einbeziehen', 'padma'),
								'checked' => PadmaOption::get('sitemap-include-images', 'general', true)
							),
							array(
								'id' => 'sitemap-ping-google',
								'label' => __('Google bei Änderungen automatisch benachrichtigen', 'padma'),
								'checked' => PadmaOption::get('sitemap-ping-google', 'general', false)
							)
						),
						'description' => __('Die Sitemap wird automatisch bei jedem Speichern von Posts aktualisiert.', 'padma')
					)
				);

				PadmaAdminInputs::admin_field_generate($form);
				?>
			</div>
		</div>
	</div>

</div><!-- #tab-sitemaps-content -->
