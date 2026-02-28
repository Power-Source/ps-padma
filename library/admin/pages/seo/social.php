<?php
/**
 * Padma SEO Social Media Modul
 * Verwaltet Open Graph und Twitter Card Einstellungen
 */

?>

<div class="big-tab" id="tab-social-content">
	
	<div class="postbox-container padma-postbox-container">
		<div class="postbox padma-admin-options-group">

			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text"><?php _e('Open Graph & Twitter Cards', 'padma'); ?></span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>

			<h2 class="hndle"><span><?php _e('Social Media Integration', 'padma'); ?></span></h2>

			<div class="inside">
				<p class="description"><?php _e('Open Graph und Twitter Card Meta-Tags werden automatisch für alle Posts und Seiten generiert. Du kannst sie in der SEO-Metabox jedes einzelnen Posts überschreiben.', 'padma'); ?></p>

				<div class="hr"></div>

				<?php
				$form = array(
					array(
						'id' => 'social-default-image',
						'type' => 'image',
						'label' => __('Standard Social Media Bild', 'padma'),
						'default' => '',
						'description' => __('Dieses Bild wird verwendet, wenn kein spezifisches Bild für einen Post festgelegt wurde. Empfohlene Größe: 1200×630 Pixel.', 'padma')
					),

					array(
						'id' => 'social-twitter-username',
						'type' => 'text',
						'label' => __('Twitter Username', 'padma'),
						'default' => '',
						'description' => __('Dein Twitter Handle (z.B. @username) für Twitter Cards. Wird als "Author" gekennzeichnet.', 'padma')
					),

					array(
						'type' => 'checkbox',
						'label' => __('Social Media Features', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'social-enable-og',
								'label' => __('Open Graph Tags aktivieren (Facebook, LinkedIn)', 'padma'),
								'checked' => PadmaOption::get('social-enable-og', 'general', true)
							),
							array(
								'id' => 'social-enable-twitter',
								'label' => __('Twitter Card Tags aktivieren', 'padma'),
								'checked' => PadmaOption::get('social-enable-twitter', 'general', true)
							)
						),
						'description' => __('Open Graph und Twitter Cards sorgen für optimale Darstellung beim Teilen auf Social Media.', 'padma')
					)
				);

				PadmaAdminInputs::admin_field_generate($form);
				?>
			</div>
		</div>
	</div>

</div><!-- #tab-social-content -->
