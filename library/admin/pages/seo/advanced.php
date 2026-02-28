<?php
/**
 * Padma SEO Advanced Modul
 * Verwaltet erweiterte SEO Optionen wie nofollow, Canonical URLs und Robots Meta
 */

?>

<div class="big-tab" id="tab-advanced-content">
	
	<!-- nofollow Links -->
	<div class="postbox-container padma-postbox-container">
		<div class="postbox padma-admin-options-group">

			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text"><?php _e('Content nofollow Links', 'padma'); ?></span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>

			<h2 class="hndle"><span><?php _e('nofollow Einstellungen', 'padma'); ?></span></h2>

			<div class="inside">
				<?php
				$form = array(
					array(
						'type' => 'checkbox',
						'label' => __('Kommentar-Autoren URLs', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'nofollow-comment-author-url',
								'label' => __('nofollow zu Kommentar-Autoren-URLs hinzufügen', 'padma'),
								'checked' => PadmaOption::get('nofollow-comment-author-url', 'general', false)
							)
						),
						'description' => __('Mit nofollow auf Kommentar-Links sagst du Suchmaschinen, dass sie den Links nicht folgen sollen. Dies kann Spam reduzieren, aber auch ehrliche Kommentatoren abschrecken. <strong>Nur aktivieren, wenn du sicher bist!</strong>', 'padma')
					)
				);

				PadmaAdminInputs::admin_field_generate($form);
				?>
			</div>
		</div>
	</div>

	<!-- Canonical URLs -->
	<div class="postbox-container padma-postbox-container">
		<div class="postbox padma-admin-options-group">

			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text"><?php _e('Canonical URLs', 'padma'); ?></span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>

			<h2 class="hndle"><span><?php _e('Canonical URLs', 'padma'); ?></span></h2>

			<div class="inside">
				<p class="description"><?php _e('Canonical Tags werden automatisch für alle Seiten generiert, um Duplicate Content zu vermeiden. Du kannst individuelle Canonical URLs in der SEO-Metabox jedes Posts festlegen.', 'padma'); ?></p>
				
				<div class="alert alert-green" style="margin-top: 15px;">
					<p><strong><?php _e('✓ Canonical Tags sind aktiv', 'padma'); ?></strong></p>
					<p><?php _e('Padma fügt automatisch <code>&lt;link rel="canonical"&gt;</code> Tags hinzu.', 'padma'); ?></p>
				</div>
			</div>
		</div>
	</div>

	<!-- Robots Meta -->
	<div class="postbox-container padma-postbox-container">
		<div class="postbox padma-admin-options-group">

			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text"><?php _e('Robots Meta Tags', 'padma'); ?></span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>

			<h2 class="hndle"><span><?php _e('Robots Meta Tags', 'padma'); ?></span></h2>

			<div class="inside">
				<p class="description"><?php _e('Robots Meta Tags steuern, wie Suchmaschinen deine Seiten indexieren und crawlen. Diese können für einzelne Posts in der SEO-Metabox und global pro Template-Typ konfiguriert werden.', 'padma'); ?></p>
				
				<div class="alert alert-yellow" style="margin-top: 15px;">
					<p><strong><?php _e('Hinweis:', 'padma'); ?></strong> <?php _e('Die Template-spezifischen Robots-Einstellungen findest du im Tab "SEO Templates" unter "Erweiterte Optionen".', 'padma'); ?></p>
				</div>
			</div>
		</div>
	</div>

</div><!-- #tab-advanced-content -->
