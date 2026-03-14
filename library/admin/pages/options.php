<?php
/**
 * Padma Options Page Content
 */
?>

<div class="notice notice-info" style="margin: 20px 0; padding: 15px; border-left: 4px solid #2271b1;">
	<h3 style="margin-top: 0;"><?php _e('SEO Settings haben jetzt eine eigene Seite!', 'padma'); ?></h3>
	<p><?php echo sprintf(__('Alle SEO-Einstellungen findest du jetzt unter <a href="%s"><strong>Padma > SEO</strong></a>.', 'padma'), admin_url('admin.php?page=padma-seo')); ?></p>
</div>

<h2 class="nav-tab-wrapper big-tabs-tabs">
	<a class="nav-tab" href="#tab-general"><?php _e('General', 'padma'); ?></a>
	<a class="nav-tab" href="#tab-scripts"><?php _e('Scripts/Analytics', 'padma'); ?></a>
	<a class="nav-tab" href="#tab-visual-editor"><?php _e('Visual Editor', 'padma'); ?></a>
	<a class="nav-tab" href="#tab-advanced"><?php _e('Advanced', 'padma'); ?></a>
	<a class="nav-tab" href="#tab-compatibility"><?php _e('Compatibility', 'padma'); ?></a>
	<a class="nav-tab" href="#tab-mobile"><?php _e('Mobile', 'padma'); ?></a>
	<a class="nav-tab" href="#tab-fonts"><?php _e('Fonts', 'padma'); ?></a>
</h2>

<?php do_action('padma_admin_save_message'); ?>
<?php do_action('padma_admin_save_error_message'); ?>

<form method="post">
	<input type="hidden" value="<?php echo wp_create_nonce('padma-admin-nonce'); ?>" name="padma-admin-nonce" id="padma-admin-nonce" />


	<div class="big-tabs-container">

		<div class="big-tab" id="tab-general-content">

			<!-- General -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text">Toggle panel: General</span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span>General</span></h2>

					<?php
					$form = array(
						array(
							'id' => 'favicon',
							'size' => 'large',
							'type' => 'text',
							'label' => 'Favicon URL',
							'value' => PadmaOption::get('favicon'),
							'description' => __('A favicon is the little image that sits next to your address in the favorites menu and on tabs.  If you do not know how to save an image as an icon you can go to <a href="http://www.favicon.cc/" target="_blank">favicon.cc</a> and draw or import an image.', 'padma')
						),

						array(
							'id' => 'feed-url',
							'size' => 'large',
							'type' => 'text',
							'label' => 'Feed URL',
						'description' => __('Wenn du einen Service wie <a href="http://feedburner.google.com/" target="_blank">FeedBurner</a> verwendest, gib die Feed-URL hier ein.', 'padma'),
						'value' => PadmaOption::get('feed-url')
					)
				);

				PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Admin Preferences -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Admin Preferences', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span>Admin Preferences</span></h2>

					<?php
					$form = array(
						array(
							'id' 		=> 'menu-setup',
							'type' 		=> 'radio',
							'label' 	=> __('Standard Admin-Seite', 'padma'),
							'value' 	=> PadmaOption::get('menu-setup', false, 'getting-started'),
							'radios' 	=> array(
								array(
									'value' => 'getting-started',
									'label' => __('Erste Schritte', 'padma')
								),

								array(
									'value' => 'visual-editor',
									'label' => __('Visueller Editor', 'padma')
								),

								array(
									'value' => 'options',
									'label' => __('Optionen', 'padma')
								)
							),
							'description' => __('Wähle die Admin-Seite aus, zu der du weitergeleitet werden möchtest, wenn du im WordPress-Admin auf "Padma" klickst.', 'padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Versionsnummer', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'hide-menu-version-number',
									'label' => __('Padma Versionsnummer im Menü verstecken', 'padma'),
									'checked' => PadmaOption::get('hide-menu-version-number', false, true)
								)
							),
							'description' => sprintf(__('Aktiviere diese Option, wenn das Menü "Padma" statt "Padma %s" anzeigen soll', 'padma'), PADMA_VERSION)
						),
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<!-- SEO Tab removed - now has its own admin page -->
		<!-- All SEO settings moved to Padma > SEO (admin.php?page=padma-seo) -->

		<div class="big-tab" id="tab-scripts-content">

			<!-- Scripts/Analytics -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Scripts/Analytics', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Scripts/Analytics', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'id' => 'header-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Header-Scripte', 'padma'),
							'description' => 'Alles, was du hier eingibst, wird im <code>&lt;head&gt;</code> der Website platziert. Wenn du <a href="http://google.com/analytics" target="_blank">Google Analytics</a> verwendest, füge den Code hier ein. <strong>Verwende hierfür keinen reinen Text!</strong>',
							'allow-tabbing' => true,
							'value' => PadmaOption::get('header-scripts')
						),

						array(
							'id' => 'footer-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Footer-Scripte', 'padma'),
							'description' => __('Alles, was du hier eingibst, wird vor dem <code>&lt;/body&gt;</code>-Tag der Website eingefügt. <strong>Verwende hierfür keinen reinen Text!</strong>', 'padma'),
							'allow-tabbing' => true,
							'value' => PadmaOption::get('footer-scripts')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-visual-editor-content">

			<!-- Visual Editor -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Visueller Editor', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Visueller Editor', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Tooltips', 'padma'),
							'checkboxes' => array(
								array(
									'id'      => 'disable-visual-editor-tooltips',
									'label'   => __('Tooltips im Visuellen Editor deaktivieren', 'padma'),
									'checked' => PadmaOption::get( 'disable-visual-editor-tooltips', false, false ),
								)
							),
							'description' => __('Wenn dir die Tooltips im Visuellen Editor zu aufdringlich sind, kannst du sie hier deaktivieren. Tooltips sind die schwarzen Sprechblasen, die erscheinen, um dir zu helfen, wenn du dir bei einer Option unsicher bist.', 'padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Editor-Style', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-editor-style',
									'label' => __('Editor-Style deaktivieren', 'padma'),
									'checked' => PadmaOption::get('disable-editor-style', false, false)
								)
							),
							'description' => __('Standardmäßig übernimmt Padma alle Einstellungen aus dem Design-Editor und fügt sie dem <a href="http://codex.wordpress.org/TinyMCE" target="_blank">WordPress TinyMCE-Editor</a> hinzu. Mit dieser Option kannst du das verhindern.', 'padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Versteckte Wrapper im Design-Modus anzeigen', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'show-hidden-wrappers-on-design-mode',
									'label' => __('Versteckte Wrapper im Design-Modus anzeigen', 'padma'),
									'checked' => PadmaOption::get('show-hidden-wrappers-on-design-mode', false, false)
								)
							),
							'description' => __('Versteckte Wrapper im Design-Modus anzeigen. Wenn du einen Breakpoint konfiguriert hast, der den Wrapper versteckt, sorgt diese Option dafür, dass der Wrapper im Visuellen Editor sichtbar bleibt und gestylt werden kann.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-advanced-content">

			<!-- Caching &amp; Compression -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Caching &amp; Komprimierung', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Caching &amp; Komprimierung', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Asset-Caching', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-caching',
									'label' => __('Padma-Caching deaktivieren', 'padma'),
									'checked' => PadmaOption::get('disable-caching', false, false)
								)
							),
							'description' => __('Standardmäßig versucht Padma, alle CSS- und JavaScript-Dateien zu cachen. Es kann jedoch seltene Fälle geben, in denen das Deaktivieren des Caches bei bestimmten Problemen hilft.<br /><br /><em><strong>Wichtig:</strong> Das Deaktivieren des Padma-Caches führt zu <strong>längeren Ladezeiten</strong> und <strong>erhöhter Belastung deines Webservers</strong> bei jedem Seitenaufruf.', 'padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Dependency Query-Variablen', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'remove-dependency-query-vars',
									'label' => __('Query-Variablen aus Dependency-URLs entfernen', 'padma'),
									'checked' => PadmaOption::get('remove-dependency-query-vars', false, false)
								)
							),
							'description' => __('Um Browser-Caching zu optimieren, kann Padma WordPress anweisen, keine Query-Variablen bei statischen Assets wie CSS- und JavaScript-Dateien anzuhängen.', 'padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Kompatibilität mit mod_pagespeed', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'compatibility-mod_pagespeed',
									'label' => __('Kompatibilität mit mod_pagespeed', 'padma'),
									'checked' => PadmaOption::get('compatibility-mod_pagespeed', false, false)
								)
							),
							'description' => __('Entfernt id- und media-Attribute aus Stylesheet-Tags, damit pagespeed sie korrekt kombinieren kann. Wenn du mod_pagespeed nicht auf deinem Server verwendest, hat diese Funktion keine Auswirkung.', 'padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('HTTP/2 Server Push', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'http2-server-push',
									'label' => __('HTTP/2 Server Push', 'padma'),
									'checked' => PadmaOption::get('http2-server-push', false, false)
								)
							),
							'description' => __('Ermöglicht es WordPress, einen Link:<...> rel="prefetch" Header für jedes eingebundene Script und Stylesheet zu senden, während WordPress diese in den Seitenquellcode ausgibt. Benötigt einen Webserver mit HTTP/2-Unterstützung. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

		</div>

		<div class="big-tab" id="tab-compatibility-content">

			<!-- Plugin templates -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Plugin-Templates', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Plugin-Templates', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Plugin-Templates', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-plugin-templates',
									'label' 	=> __('Plugin-Templates erlauben', 'padma'),
									'checked' 	=> PadmaOption::get('allow-plugin-templates', false, false)
								)
							),
							'description' => __('Erlaubt das Laden von Plugin-Templates für Custom Post Types anstelle des Padma Layouts', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>


			<!-- Headway -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Headway', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Headway', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Headway-Unterstützung', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'headway-support',
									'label' 	=> __('Headway-Klassen-Unterstützung aktivieren', 'padma'),
									'checked' 	=> PadmaOption::get('headway-support', false, false)
								)
							),
							'description' => __('Wenn aktiviert, versucht Padma alle PHP-Klassen zu unterstützen, die mit Headway zusammenhängen. Dies ermöglicht dir die Verwendung von Blöcken wie Headway Rocket und ähnlichen. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>


			<!-- Bloxtheme -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Bloxtheme', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Bloxtheme', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Bloxtheme-Unterstützung', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'bloxtheme-support',
									'label' 	=> __('Bloxtheme-Klassen-Unterstützung aktivieren', 'padma'),
									'checked' 	=> PadmaOption::get('bloxtheme-support', false, false)
								)
							),
							'description' => __('Wenn aktiviert, versucht Padma alle PHP-Klassen zu unterstützen, die mit Bloxtheme zusammenhängen. Dies ermöglicht dir die Verwendung von Blöcken wie Blox Rocket und ähnlichen. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>


			<!-- Gutenberg -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Gutenberg', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Gutenberg', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Padma-Blöcke in Gutenberg anzeigen', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'padma-blocks-as-gutenberg-blocks',
									'label' 	=> __('Padma-Blöcke als Gutenberg-Blöcke anzeigen', 'padma'),
									'checked' 	=> PadmaOption::get('padma-blocks-as-gutenberg-blocks', false, false)
								)
							),
							'description' => __('Wenn aktiviert, ermöglicht Padma die Verwendung von Padma-Blöcken als Gutenberg-Blöcke. Gehe zu "Block-Optionen > Überall" um dies zu aktivieren. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-mobile-content">

			<!-- Responsive options -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Responsive-Optionen', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Responsive-Optionen', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Mobiles Zoomen erlauben', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-mobile-zooming',
									'label' 	=> __('Mobiles Zoomen erlauben', 'padma'),
									'checked' 	=> PadmaOption::get('allow-mobile-zooming', false, false)
								)
							),
							'description' => __('Fügt das Viewport-Meta-Tag mit Zoom-Berechtigung hinzu, um deinen Besuchern die Möglichkeit zu geben, deine Website mit mobilen Browsern zu zoomen.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-fonts-content">

			<!-- Fonts options -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Fonts', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Fonts', 'padma'); ?></span></h2>

					<?php

					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Google Fonts', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'do-not-use-google-fonts',
									'label' 	=> __('Google Fonts nicht verwenden', 'padma'),
									'checked' 	=> PadmaOption::get('do-not-use-google-fonts', false, false)
								)
							),
							'description' => __('Wenn aktiviert, verwendet Padma keine Google Fonts mehr.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Google Fonts asynchron laden', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'load-google-fonts-asynchronously',
									'label' 	=> __('Google Fonts asynchron laden', 'padma'),
									'checked' 	=> PadmaOption::get('load-google-fonts-asynchronously', false, false)
								)
							),
							'description' => __('Wenn aktiviert, lädt Padma die Schriftarten asynchron, um Render-Blocking zu vermeiden, wenn du Google Fonts verwendest.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-display',
							'type' 	=> 'select',
							'label' => __('Google Fonts Display-Zeitleiste', 'padma'),
							'options' => array(
								'auto' 		=> 'Auto',
								'block' 	=> 'Block',
								'swap' 		=> 'Swap',
								'fallback' 	=> 'Fallback',
								'optional' 	=> 'Optional',
							),
							'value' => PadmaOption::get('google-fonts-display', false, 'swap'),
							'description' => __('Bestimmt, wie eine Schriftart angezeigt wird, basierend darauf, ob und wann sie heruntergeladen und einsatzbereit ist.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-preload',
							'type' 	=> 'checkbox',
							'label' => __('Google Fonts vorladen', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'google-fonts-preload',
									'label' 	=> __('Google Fonts vorladen', 'padma'),
									'checked' 	=> PadmaOption::get('google-fonts-preload', false, false)
								)
							),
							'description' => __('Wenn aktiviert, weist diese Option den Webbrowser an, Google Fonts frühzeitig zu laden', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>




		<div class="hr hr-submit" style="display: none;"></div>

		<p class="submit" style="display: none;">
			<input type="submit" name="padma-submit" value="<?php _e('Save Changes', 'padma'); ?>" class="button-primary padma-save" />
		</p>

</form>