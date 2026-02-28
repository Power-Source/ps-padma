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
							'description' => __('If you use any service like <a href="http://feedburner.google.com/" target="_blank">FeedBurner</a>, type the feed URL here.', 'padma'),
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
							'label' 	=> __('Default Admin Page', 'padma'),
							'value' 	=> PadmaOption::get('menu-setup', false, 'getting-started'),
							'radios' 	=> array(
								array(
									'value' => 'getting-started',
									'label' => __('Getting Started', 'padma')
								),

								array(
									'value' => 'visual-editor',
									'label' => __('Visual Editor', 'padma')
								),

								array(
									'value' => 'options',
									'label' => __('Options', 'padma')
								)
							),
							'description' => __('Select which admin page you would like to be directed to when you click on "Padma" in the WordPress Admin.', 'padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Version Number', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'hide-menu-version-number',
									'label' => __('Hide Padma Version Number From Menu', 'padma'),
									'checked' => PadmaOption::get('hide-menu-version-number', false, true)
								)
							),
							'description' => sprintf(__('Check this if you wish to have the Menu say "Padma" instead of "Padma %s"', 'padma'), PADMA_VERSION)
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
							'label' => __('Header Scripts', 'padma'),
							'description' => 'Anything here will go in the <code>&lt;head&gt;</code> of the website. If you are using <a href="http://google.com/analytics" target="_blank">Google Analytics</a>, paste the code provided here. <strong>Do not place plain text in this!</strong>',
							'allow-tabbing' => true,
							'value' => PadmaOption::get('header-scripts')
						),

						array(
							'id' => 'footer-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Footer Scripts', 'padma'),
							'description' => __('Anything here will be inserted before the <code>&lt;/body&gt;</code> tag of the website. <strong>Do not place plain text in this!</strong>', 'padma'),
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
						<span class="screen-reader-text"><?php _e('Visual Editor', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Visual Editor', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Tooltips', 'padma'),
							'checkboxes' => array(
								array(
									'id'      => 'disable-visual-editor-tooltips',
									'label'   => __('Disable Tooltips in the Visual Editor', 'padma'),
									'checked' => PadmaOption::get( 'disable-visual-editor-tooltips', false, false ),
								)
							),
							'description' => __('If you ever feel that the tooltips are too invasive in the visual editor, you can disable them here.  Tooltips are the black speech bubbles that appear to assist you when you are not sure what an option is or how it works.', 'padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Editor Style', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-editor-style',
									'label' => __('Disable Editor Style', 'padma'),
									'checked' => PadmaOption::get('disable-editor-style', false, false)
								)
							),
							'description' => __('By default, Padma will take any settings in the Design Editor and add them to <a href="http://codex.wordpress.org/TinyMCE" target="_blank">WordPress\' TinyMCE editor</a> style.  Use this option to prevent that.', 'padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Show hidden wrappers on design mode', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'show-hidden-wrappers-on-design-mode',
									'label' => __('Show hidden wrappers on design mode', 'padma'),
									'checked' => PadmaOption::get('show-hidden-wrappers-on-design-mode', false, false)
								)
							),
							'description' => __('Show hidden wrappers on design mode. When configuring a breakpoint to hide the wrapper, this option will urge to Visual Editor to remain the wrapper visible to be styled.', 'padma')
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
						<span class="screen-reader-text"><?php _e('Caching &amp; Compression', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Caching &amp; Compression', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Asset Caching', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-caching',
									'label' => __('Disable Padma Caching', 'padma'),
									'checked' => PadmaOption::get('disable-caching', false, false)
								)
							),
							'description' => __('By default, Padma will attempt to cache all CSS and JavaScript that it generates.  However, there may be rare circumstances where disabling the cache will help with certain issues.<br /><br /><em><strong>Important:</strong> Disabling the Padma cache will cause an <strong>increase in page load times</strong> and <strong>increase the strain your web server</strong> will undergo on every page load.', 'padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Dependency Query Variables', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'remove-dependency-query-vars',
									'label' => __('Remove Query Variables from Dependency URLs', 'padma'),
									'checked' => PadmaOption::get('remove-dependency-query-vars', false, false)
								)
							),
							'description' => __('To leverage browser caching, Padma can tell WordPress to not put query variables on static assets such as CSS and JavaScript files.', 'padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Compatibility with mod_pagespeed', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'compatibility-mod_pagespeed',
									'label' => __('Compatibility with mod_pagespeed', 'padma'),
									'checked' => PadmaOption::get('compatibility-mod_pagespeed', false, false)
								)
							),
							'description' => __('Strips id and media attributes from stylesheet tags, allowing pagespeed to combine them properly. If you are not using mod_pagespeed on your server, this feature will not do anything for you.', 'padma')
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
							'description' => __('Enables WordPress to send a Link:<...> rel="prefetch" header for every enqueued script and style as WordPress outputs them into the page source. Requires a web server that supports HTTP/2. <strong>Important:</strong> This feature is Experimental.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Developer -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Developer', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Developer', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Use Padma Developer version', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'use-developer-version',
									'label' => __('Allow install Edge version', 'padma'),
									'checked' => PadmaOption::get('use-developer-version', false, false)
								)
							),
							'description' => __('This option is for developers, use this option only if you know what are you doing. Padma Theme and plugins will upgrade to testing version. <strong>Do NOT use on production sites.</strong> Once active this option will allow you to upgrade your website to the latest version.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Debugging -->
			<div class="postbox-container padma-postbox-container">
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Debugging', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Debugging', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Debug Mode', 'padma'),
							'checkboxes' => array(
								array(
									'id' => 'debug-mode',
									'label' => __('Enable Debug Mode', 'padma'),
									'checked' => PadmaOption::get('debug-mode', false, false)
								)
							),
							'description' => __('Having Debug Mode enabled will allow the Padma Themes team to access the Visual Editor for support purposes, but <strong>will not allow changes to be saved</strong>.', 'padma')
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
						<span class="screen-reader-text"><?php _e('Plugin templates', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Plugin templates', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Plugin templates', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-plugin-templates',
									'label' 	=> __('Allow plugin templates', 'padma'),
									'checked' 	=> PadmaOption::get('allow-plugin-templates', false, false)
								)
							),
							'description' => __('Allow load plugin templates related to Custom Post Types instead Padma Layout', 'padma')
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
							'label' => __('Headway support', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'headway-support',
									'label' 	=> __('Enable Headway classes support', 'padma'),
									'checked' 	=> PadmaOption::get('headway-support', false, false)
								)
							),
							'description' => __('If on, Padma will attempt support all PHP classes related to Headway. This allows to you use blocks like Headway Rocket and similar. <strong>Important:</strong> This feature is Experimental.', 'padma')
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
							'label' => __('Bloxtheme support', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'bloxtheme-support',
									'label' 	=> __('Enable Bloxtheme classes support', 'padma'),
									'checked' 	=> PadmaOption::get('bloxtheme-support', false, false)
								)
							),
							'description' => __('If on, Padma will attempt support all PHP classes related to Bloxtheme. This allows to you use blocks like Blox Rocket and similar. <strong>Important:</strong> This feature is Experimental.', 'padma')
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
							'label' => __('Display Padma Blocks in Gutenberg', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'padma-blocks-as-gutenberg-blocks',
									'label' 	=> __('Show Padma Blocks as Gutenberg Blocks', 'padma'),
									'checked' 	=> PadmaOption::get('padma-blocks-as-gutenberg-blocks', false, false)
								)
							),
							'description' => __('If on, Padma will allow to use Padma Blocks as Gutenberg Blocks. Go to "Block Options > Anywhere" to enable it. <strong>Important:</strong> This feature is Experimental.', 'padma')
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
						<span class="screen-reader-text"><?php _e('Responsive options', 'padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Responsive options', 'padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Allow mobile zooming', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-mobile-zooming',
									'label' 	=> __('Allow mobile zooming', 'padma'),
									'checked' 	=> PadmaOption::get('allow-mobile-zooming', false, false)
								)
							),
							'description' => __('Adds the viewport meta tag with zooming permission to give your users the ability to zoom in your website with mobile browsers.', 'padma')
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
									'label' 	=> __('Do not use Google Fonts', 'padma'),
									'checked' 	=> PadmaOption::get('do-not-use-google-fonts', false, false)
								)
							),
							'description' => __('If checked, Padma will not use Google Fonts.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Load Google Fonts asynchronously', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'load-google-fonts-asynchronously',
									'label' 	=> __('Load Google Fonts asynchronously', 'padma'),
									'checked' 	=> PadmaOption::get('load-google-fonts-asynchronously', false, false)
								)
							),
							'description' => __('If checked, Padma will load fonts asynchronously to avoid render blocking font when you use google fonts.', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-display',
							'type' 	=> 'select',
							'label' => __('Google Fonts Display Timeline', 'padma'),
							'options' => array(
								'auto' 		=> 'Auto',
								'block' 	=> 'Block',
								'swap' 		=> 'Swap',
								'fallback' 	=> 'Fallback',
								'optional' 	=> 'Optional',
							),
							'value' => PadmaOption::get('google-fonts-display', false, 'swap'),
							'description' => __('Determines how a font face is displayed based on whether and when it is downloaded and ready to u', 'padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-preload',
							'type' 	=> 'checkbox',
							'label' => __('Preload Google Fonts', 'padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'google-fonts-preload',
									'label' 	=> __('Preload Google Fonts', 'padma'),
									'checked' 	=> PadmaOption::get('google-fonts-preload', false, false)
								)
							),
							'description' => __('If on, this option will tell the web browser to fetch Google Fonts early', 'padma')
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