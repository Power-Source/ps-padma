<h2 class="nav-tab-wrapper big-tabs-tabs">
	<a class="nav-tab" href="#tab-general"><?php _e('Allgemein','padma'); ?></a>
	<a class="nav-tab" href="#tab-seo"><?php _e('SEO','padma'); ?></a>
	<a class="nav-tab" href="#tab-scripts"><?php _e('Skripte/Analyse','padma'); ?></a>
	<a class="nav-tab" href="#tab-visual-editor"><?php _e('Visueller Editor','padma'); ?></a>
	<a class="nav-tab" href="#tab-advanced"><?php _e('Erweitert','padma'); ?></a>
	<a class="nav-tab" href="#tab-compatibility"><?php _e('Kompatibilität','padma'); ?></a>
	<a class="nav-tab" href="#tab-mobile"><?php _e('Mobil','padma'); ?></a>
	<a class="nav-tab" href="#tab-fonts"><?php _e('Fonts','padma'); ?></a>
</h2>

<?php do_action('padma_admin_save_message'); ?>
<?php do_action('padma_admin_save_error_message'); ?>

<form method="post">
	<input type="hidden" value="<?php echo wp_create_nonce('padma-admin-nonce'); ?>" name="padma-admin-nonce" id="padma-admin-nonce" />


	<div class="big-tabs-container">

		<div class="big-tab" id="tab-general-content">

			<!-- General -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text">Toggle panel: Allgemein</span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span>Allgemein</span></h2>

					<?php
					$form = array(
						array(
							'id' => 'favicon',
							'size' => 'large',
							'type' => 'text',
							'label' => 'Favicon URL',
							'value' => PadmaOption::get('favicon'),
							'description' => __('Ein Favicon ist das kleine Bild, das neben der URL im Favoritenmenü und auf Registerkarten angezeigt wird. Wenn Du nicht weisst, wie Du ein Bild als Symbol speicherst, kannst Du zu <a href="http://www.favicon.cc/" target="_blank">favicon.cc</a> gehen und ein Bild zeichnen oder importieren.','padma')
						),

						array(
							'id' => 'feed-url',
							'size' => 'large',
							'type' => 'text',
							'label' => 'Feed URL',
							'description' => __('Wenn Du einen Dienst wie <a href="http://feedburner.google.com/" target="_blank">FeedBurner</a> verwendest, gib hier die Feed-URL ein.','padma'),
							'value' => PadmaOption::get('feed-url')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Admin Preferences -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Administratoreinstellungen','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span>Administratoreinstellungen</span></h2>

					<?php
					$form = array(
						array(
							'id' 		=> 'menu-setup',
							'type' 		=> 'radio',
							'label' 	=> __('Standard-Administratorseite','padma'),
							'value' 	=> PadmaOption::get('menu-setup', false, 'getting-started'),
							'radios' 	=> array(
								array(
									'value' => 'getting-started',
									'label' => __('Erste Schritte','padma')
								),

								array(
									'value' => 'visual-editor',
									'label' => __('Visueller Editor','padma')
								),

								array(
									'value' => 'options',
									'label' => __('Einstellungen','padma')
								)
							),
							'description' => __('Wähle aus, zu welcher Admin-Seite Du weitergeleitet werden möchtest, wenn Du im WordPress-Admin auf "Padma" klickst.','padma')
						),
						array(
							'type' 	=> 'checkbox',
							'label' => __('Keine Installation empfohlener Plugins','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'do-not-recommend-plugin-installation',
									'label' 	=> __('Hinweis zu empfohlenen Plugins ausblenden','padma'),
									'checked' 	=> PadmaOption::get('do-not-recommend-plugin-installation', false, false)
								)
							),
							'description' => __('Wenn aktiviert, empfiehlt Padma nicht die Installation von empfohlenen Plugins.','padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Versionsnummer','padma'),
							'checkboxes' => array(
								array(
									'id' => 'hide-menu-version-number',
									'label' => __('Padma-Versionsnummer aus dem Menü verbergen','padma'),
									'checked' => PadmaOption::get('hide-menu-version-number', false, true)
								)
							),
							'description' => sprintf( __('Aktivieren diese Option, wenn im Menü "Padma" statt "Padma %s" stehen soll.','padma'), PADMA_VERSION )
						),
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-seo-content">

			<?php
			if ( PadmaSEO::is_disabled() ) {

				switch ( PadmaSEO::plugin_active() ) {

					case 'aioseop':
						echo '<div class="alert alert-yellow"><p>' . __('Padma hat festgestellt, dass Du das All In One SEO Pack-Plugin verwendest. Um Konflikte zu reduzieren und Ressourcen zu sparen, wurde die SEO-Funktionalität von Padma deaktiviert.','padma') . '</p></div>';
					break;

					case 'wpseo':
						echo '<div class="alert alert-yellow"><p>' . __('Padma hat festgestellt, dass Sie das WordPress SEO-Plugin von Yoast verwenden. Um Konflikte zu reduzieren und Ressourcen zu sparen, wurde die SEO-Funktionalität von Padma deaktiviert.','padma') . '</p></div>';
					break;

					default:
						echo '<div class="alert alert-yellow"><p>' . __('Padmas SEO-Funktionalität ist deaktiviert.','padma') . '</p></div>';
						break;

				}

			} else {
			?>

				<h3 class="title" id="seo-templates-title"><?php _e('SEO-Vorlagen','padma'); ?></h3>

				<div id="seo-templates">
					<div id="seo-templates-hidden-inputs">
						<?php
						/* SETUP THE TYPES OF SEO TEMPLATE INPUTS */
						$seo_template_inputs = array(
							'title',
							'description',
							'noindex',
							'nofollow',
							'noarchive',
							'nosnippet',
							'noodp',
							'noydir'
						);

						/* GENERATE HIDDEN INPUTS */
						$seo_options = PadmaOption::get('seo-templates', 'general', array());

						foreach (PadmaSEO::output_layouts_and_defaults() as $page => $defaults) {

							foreach ($seo_template_inputs as $input) {

								$name_attr = 'name="padma-admin-input[seo-templates][' . $page . '][' . $input . ']"';

								$default = isset($defaults[$input]) ? $defaults[$input] : null;

								$page_options = padma_get($page, $seo_options, array());
								$value = padma_get($input, $page_options, $default);

								echo '<input type="hidden" id="seo-' . $page . '-' . $input . '"' . $name_attr . ' value="' . stripslashes(esc_attr($value)) . '" />';

							}

						}
						?>
					</div>

					<div id="seo-templates-header">
						<span><?php _e('Wähle eine Vorlage:','padma'); ?></span>
						<select>
							<option value="index"><?php _e('Blog Index','padma'); ?></option>

							<?php
							if ( get_option('show_on_front') == 'page' )
								echo '<option value="front_page">' . __('Startseite','padma') . '</option>';
							?>

							<optgroup label="Single">
								<?php
								$post_types = get_post_types(array('public' => true), 'objects');

								foreach($post_types as $post_type)
									echo '<option value="single-' . $post_type->name . '">' . $post_type->label . '</option>';
								?>
							</optgroup>

							<optgroup label="Archive">
								<option value="archive-category"><?php _e('Kategorie','padma'); ?></option>
								<option value="archive-search"><?php _e('Suche','padma'); ?></option>
								<option value="archive-date"><?php _e('Datum','padma'); ?></option>
								<option value="archive-author"><?php _e('Autor','padma'); ?></option>
								<option value="archive-post_tag"><?php _e('Beitrags-Tag','padma'); ?></option>
								<option value="archive-post_type"><?php _e('Beitrags-Typ','padma'); ?></option>
								<option value="archive-taxonomy"><?php _e('Taxonomie','padma'); ?></option>
							</optgroup>

							<option value="four04">404</option>

						</select>
					</div><!-- #seo-templates-header -->

					<div id="seo-templates-inputs">

						<?php
						$form = array(
							array(
								'id' => 'title',
								'type' => 'text',
								'size' => 'large',
								'label' => __('Titel','padma'),
								'description' => __('The title is the main text that describes the page. It is the single most important on-page SEO element (behind overall content).  The title appears at the top of the web browser when viewing the page, in browser tabs, search engine results, and external websites.  <strong>Tip:</strong> it is best that the title stays below 70 characters.<br /><br /><a href="http://www.seomoz.org/learn-seo/title-tag" target="_blank">Learn more about Titles &raquo;</a>','padma'),
								'no-submit' => true
							),

							array(
								'id' => 'description',
								'type' => 'paragraph',
								'cols' => 60,
								'rows' => 3,
								'label' => '<code>&lt;meta&gt;</code> ' . __('Description','padma'),
								'description' => __('Meta description tags, while not important to search engine rankings, are extremely important in gaining user click-through from search engine result pages (SERPs). These short paragraphs are your opportunity to advertise content to searchers and let them know exactly what the given page has with regard to what they’re looking for. <strong>Tip:</strong> a good description is around 150 characters.<br /><br /><a href="http://www.seomoz.org/learn-seo/meta-description" target="_blank">Learn more about &lt;meta&gt; Descriptions &raquo;</a>','padma'),
								'no-submit' => true
							)
						);

						PadmaAdminInputs::generate($form);
						?>

						<div class="hr"></div>

						<p><strong><?php _e('You may use the following variables in the Title and Description inputs above:','padma'); ?></strong></p>

						<ul>
							<li><code>%title%</code> &mdash; <?php _e('Will retrieve the title of whatever post, archive, or page is being displayed.','padma'); ?></li>
							<li><code>%sitename%</code> &mdash; <?php echo sprintf( __('Will retrieve the name of the site. This can be set in <a href="%s" target="_blank">Settings &raquo; General</a>.','padma'), admin_url('options-general.php') ); ?></li>

							<li><code>%tagline%</code> &mdash; <?php echo sprintf( __('Will retrieve the tagline/description of the site.  This can be set in <a href="%s" target="_blank">Settings &raquo; General</a>.','padma'), admin_url('options-general.php') ); ?></li>

							<li><code>%meta%</code> &mdash; <?php _e('Used only on taxonomy archives to display the term name.','padma'); ?></li>
						</ul>

						<h3 id="seo-templates-advanced-options-title" class="title title-hr"><?php _e('Advanced Options <span>Show &darr;</span>','padma'); ?></h3>

						<div id="seo-templates-advanced-options">
							<?php
							$form = array(
								array(
									'type' => 'checkbox',
									'label' => __('Page Indexing','padma'),
									'checkboxes' => array(
										array(
											'id' => 'noindex',
											'label' => 'Enable <code>noindex</code>',
											'no-submit' => true
										)
									),
									'description' => __('Index/NoIndex tells the engines whether the page should be crawled and kept in the engines\' index for retrieval. If you check this box to opt for <code>noindex</code>, the page will be excluded from the engines.  <strong>Note:</strong> if you\'re not sure what this does, do not check this box.','padma')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Link Following','padma'),
									'checkboxes' => array(
										array(
											'id' => 'nofollow',
											'label' => 'Enable <code>nofollow</code>',
											'no-submit' => true
										)
									),
									'description' => __('Follow/NoFollow tells the engines whether links on the page should be crawled. If you check this box to employ "nofollow," the engines will disregard the links on the page both for discovery and ranking purposes.  <strong>Note:</strong> if you\'re not sure what this does, do not check this box.','padma')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Page Archiving','padma'),
									'checkboxes' => array(
										array(
											'id' => 'noarchive',
											'label' => __('Enable <code>noarchive</code>','padma'),
											'no-submit' => true
										)
									),
									'description' => __('Noarchive is used to restrict search engines from saving a cached copy of the page. By default, the engines will maintain visible copies of all pages they indexed, accessible to searchers through the "cached" link in the search results.  Check this box to restrict search engines from storing cached copies of this page.','padma')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Effects','padma'),
									'checkboxes' => array(
										array(
											'id' => 'nosnippet',
											'label' => __('Enable <code>nosnippet</code>','padma'),
											'no-submit' => true
										)
									),
									'description' => __('Nosnippet informs the engines that they should refrain from displaying a descriptive block of text next to the page\'s title and URL in the search results.','padma')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Open Directory Project','padma'),
									'checkboxes' => array(
										array(
											'id' => 'noodp',
											'label' => __('Enable <code>NoODP</code>','padma'),
											'no-submit' => true
										)
									),
									'description' => __('NoODP is a specialized tag telling the engines not to grab a descriptive snippet about a page from the Open Directory Project (DMOZ) for display in the search results.','padma')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Yahoo! Directory','padma'),
									'checkboxes' => array(
										array(
											'id' => 'noydir',
											'label' => __('Enable <code>NoYDir</code>','padma'),
											'no-submit' => true
										)
									),
									'description' => __('NoYDir, like NoODP, is specific to Yahoo!, informing that engine not to use the Yahoo! Directory description of a page/site in the search results.','padma')
								)
							);

							PadmaAdminInputs::generate($form);
							?>
						</div><!-- #seo-templates-advanced-options -->

					</div><!-- #seo-templates-inputs -->
				</div><!-- #seo-templates-content -->

				<div id="seo-description" class="alert alert-yellow"><p><?php _e('Unfamiliar with <em>Search Engine Optimization</em>?','padma'); ?>  <a href="http://www.seomoz.org/beginners-guide-to-seo/" target="_blank"><?php _e('Learn More','padma'); ?> &raquo;</a></p></div>


				<!-- Content <code>nofollow</code> Links -->
				<div id="tab-general-content" class="postbox-container padma-postbox-container">		
					<div id="" class="postbox padma-admin-options-group">

						<button type="button" class="handlediv" aria-expanded="false">
							<span class="screen-reader-text"><?php _e('Content <code>nofollow</code> Links','padma'); ?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>


						<h2 class="hndle"><span><?php _e('Content <code>nofollow</code> Links','padma'); ?></span></h2>

						<?php
						$form = array(
							array(
								'type' => 'checkbox',
								'label' => 'Comment Authors\' URL',
								'checkboxes' => array(
									array(
										'id' => 'nofollow-comment-author-url',
										'label' => __('Add nofollow To Comment Authors\' URL','padma'),
										'checked' => PadmaOption::get('nofollow-comment-author-url', 'general', false)
									)
								),
								'description' => __('Adding nofollow to the comment authors\' URLs will tell search engines to not visit their website and to stay on yours. Many bloggers frown upon this, which can sometimes discourage comments. Only enable this if you are 100% sure you know you want to.','padma')
							)
						);

						PadmaAdminInputs::admin_field_generate($form);

						?>
					</div>
				</div>


				<!-- Disable Schema.org support -->
				<div id="tab-general-content" class="postbox-container padma-postbox-container">		
					<div id="" class="postbox padma-admin-options-group">

						<button type="button" class="handlediv" aria-expanded="false">
							<span class="screen-reader-text"><?php _e('Disable Schema.org support','padma'); ?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>


						<h2 class="hndle"><span><?php _e('Disable Schema.org support','padma'); ?></span></h2>

						<?php
						$form = array(
							array(
								'type' => 'checkbox',
								'label' => __('Disable microdata markup','padma'),
								'checkboxes' => array(
									array(
										'id' => 'disable-schema-support',
										'label' => __('Do not add ld+json data','padma'),
										'checked' => PadmaOption::get('disable-schema-support', 'general', false)
									)
								),
								'description' => __('Schema.org is a vocabulary of microdata markup that aims to make it easer for search crawlers to understand what\'s on a webpage.','padma')
							)
						);

						PadmaAdminInputs::admin_field_generate($form);

						?>
					</div>
				</div>				

			<?php
			}
			?>
		</div>

		<div class="big-tab" id="tab-scripts-content">

			<!-- Scripts/Analytics -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Scripts/Analytics','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Scripts/Analytics','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'id' => 'header-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Header Scripts','padma'),
							'description' => 'Anything here will go in the <code>&lt;head&gt;</code> of the website. If you are using <a href="http://google.com/analytics" target="_blank">Google Analytics</a>, paste the code provided here. <strong>Do not place plain text in this!</strong>',
							'allow-tabbing' => true,
							'value' => PadmaOption::get('header-scripts')
						),

						array(
							'id' => 'footer-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Footer Scripts','padma'),
							'description' => __('Anything here will be inserted before the <code>&lt;/body&gt;</code> tag of the website. <strong>Do not place plain text in this!</strong>','padma'),
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
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Visual Editor','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Visual Editor','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Tooltips','padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-visual-editor-tooltips',
									'label' => __('Disable Tooltips in the Visual Editor','padma'),
									'checked' => PadmaOption::get('disable-visual-editor-tooltips', false, false)
								)
							),
							'description' => __('If you ever feel that the tooltips are too invasive in the visual editor, you can disable them here.  Tooltips are the black speech bubbles that appear to assist you when you are not sure what an option is or how it works.','padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Editor Style','padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-editor-style',
									'label' => __('Disable Editor Style','padma'),
									'checked' => PadmaOption::get('disable-editor-style', false, false)
								)
							),
							'description' => __('By default, Padma will take any settings in the Design Editor and add them to <a href="http://codex.wordpress.org/TinyMCE" target="_blank">WordPress\' TinyMCE editor</a> style.  Use this option to prevent that.','padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Show hidden wrappers on design mode','padma'),
							'checkboxes' => array(
								array(
									'id' => 'show-hidden-wrappers-on-design-mode',
									'label' => __('Show hidden wrappers on design mode','padma'),
									'checked' => PadmaOption::get('show-hidden-wrappers-on-design-mode', false, false)
								)
							),
							'description' => __('Show hidden wrappers on design mode. When configuring a breakpoint to hide the wrapper, this option will urge to Visual Editor to remain the wrapper visible to be styled.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-advanced-content">

			<!-- Advanced -->
			<?php if ( !is_multisite() ) : ?>
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Automatic Updates','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>

					<h2 class="hndle"><span><?php _e('Automatic Updates','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Disable Automatic Core Updates','padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-automatic-core-updates',
									'label' => __('Disable Automatic Core Updates','padma'),
									'checked' => PadmaOption::get('disable-automatic-core-updates', false, false)
								)
							),
							'description' => __('By default, Padma will attempt to update automatically, but if this option is checked automatic updates will not happen. This option requires Padma Updater plugin.','padma')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Disable Automatic Plugin Updates','padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-automatic-plugin-updates',
									'label' => __('Disable Automatic Plugin Updates','padma'),
									'checked' => PadmaOption::get('disable-automatic-plugin-updates', false, false)
								)
							),
							'description' => __('By default, Updater plugin will attempt to update Padma Plugins automatically, but if this option is checked automatic updates for plugins will not happen. This option requires Padma Updater plugin.','padma')
						),
					);

					PadmaAdminInputs::admin_field_generate($form);
					?>
				</div>
			</div>
		<?php endif; ?>

			<!-- Caching &amp; Compression -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Caching &amp; Compression','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Caching &amp; Compression','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Asset Caching','padma'),
							'checkboxes' => array(
								array(
									'id' => 'disable-caching',
									'label' => __('Disable Padma Caching','padma'),
									'checked' => PadmaOption::get('disable-caching', false, false)
								)
							),
							'description' => __('By default, Padma will attempt to cache all CSS and JavaScript that it generates.  However, there may be rare circumstances where disabling the cache will help with certain issues.<br /><br /><em><strong>Important:</strong> Disabling the Padma cache will cause an <strong>increase in page load times</strong> and <strong>increase the strain your web server</strong> will undergo on every page load.','padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Dependency Query Variables','padma'),
							'checkboxes' => array(
								array(
									'id' => 'remove-dependency-query-vars',
									'label' => __('Remove Query Variables from Dependency URLs','padma'),
									'checked' => PadmaOption::get('remove-dependency-query-vars', false, false)
								)
							),
							'description' => __('To leverage browser caching, Padma can tell WordPress to not put query variables on static assets such as CSS and JavaScript files.','padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Compatibility with mod_pagespeed','padma'),
							'checkboxes' => array(
								array(
									'id' => 'compatibility-mod_pagespeed',
									'label' => __('Compatibility with mod_pagespeed','padma'),
									'checked' => PadmaOption::get('compatibility-mod_pagespeed', false, false)
								)
							),
							'description' => __('Strips id and media attributes from stylesheet tags, allowing pagespeed to combine them properly. If you are not using mod_pagespeed on your server, this feature will not do anything for you.','padma')
						),

						array(
							'type' => 'checkbox',
							'label' => __('HTTP/2 Server Push','padma'),
							'checkboxes' => array(
								array(
									'id' => 'http2-server-push',
									'label' => __('HTTP/2 Server Push','padma'),
									'checked' => PadmaOption::get('http2-server-push', false, false)
								)
							),
							'description' => __('Enables WordPress to send a Link:<...> rel="prefetch" header for every enqueued script and style as WordPress outputs them into the page source. Requires a web server that supports HTTP/2. <strong>Important:</strong> This feature is Experimental.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Debugging -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Debugging','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Debugging','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Debug Mode','padma'),
							'checkboxes' => array(
								array(
									'id' => 'debug-mode',
									'label' => __('Enable Debug Mode','padma'),
									'checked' => PadmaOption::get('debug-mode', false, false)
								)
							),
							'description' => __('Having Debug Mode enabled will allow the Padma Themes team to access the Visual Editor for support purposes, but <strong>will not allow changes to be saved</strong>.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-compatibility-content">

			<!-- Plugin templates -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Plugin templates','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Plugin templates','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Plugin templates','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-plugin-templates',
									'label' 	=> __('Allow plugin templates','padma'),
									'checked' 	=> PadmaOption::get('allow-plugin-templates', false, false)
								)
							),
							'description' => __('Allow load plugin templates related to Custom Post Types instead Padma Layout','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>


			<!-- Headway -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Headway','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Headway','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Headway support','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'headway-support',
									'label' 	=> __('Enable Headway classes support','padma'),
									'checked' 	=> PadmaOption::get('headway-support', false, false)
								)
							),
							'description' => __('If on, Padma will attempt support all PHP classes related to Headway. This allows to you use blocks like Headway Rocket and similar. <strong>Important:</strong> This feature is Experimental.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>


			<!-- Gutenberg -->
<?php
function is_classicpress() {
    return function_exists( 'classicpress_version' );
}

global $wp_version;
if ( !is_classicpress() && version_compare( $wp_version, '5.0', '>=' ) ) :
?>
    <div id="tab-general-content" class="postbox-container padma-postbox-container">
        <div class="postbox padma-admin-options-group">
            <button type="button" class="handlediv" aria-expanded="false">
                <span class="screen-reader-text"><?php _e('Gutenberg','padma'); ?></span>
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle"><span><?php _e('Gutenberg','padma'); ?></span></h2>
            <?php
            $form = array(
                array(
                    'type' 	=> 'checkbox',
                    'label' => __('Display Padma Blocks in Gutenberg','padma'),
                    'checkboxes' => array(
                        array(
                            'id' 		=> 'padma-blocks-as-gutenberg-blocks',
                            'label' 	=> __('Show Padma Blocks as Gutenberg Blocks','padma'),
                            'checked' 	=> PadmaOption::get('padma-blocks-as-gutenberg-blocks', false, false)
                        )
                    ),
                    'description' => __('If on, Padma will allow to use Padma Blocks as Gutenberg Blocks. Go to "Block Options > Anywhere" to enable it. <strong>Important:</strong> This feature is Experimental.','padma')
                )
            );
            PadmaAdminInputs::admin_field_generate($form);
            ?>
        </div>
    </div>
<?php endif; ?>

		<div class="big-tab" id="tab-mobile-content">

			<!-- Responsive options -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Responsive options','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Responsive options','padma'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Allow mobile zooming','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-mobile-zooming',
									'label' 	=> __('Allow mobile zooming','padma'),
									'checked' 	=> PadmaOption::get('allow-mobile-zooming', false, false)
								)
							),
							'description' => __('Adds the viewport meta tag with zooming permission to give your users the ability to zoom in your website with mobile browsers.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-fonts-content">

			<!-- Fonts options -->
			<div id="tab-general-content" class="postbox-container padma-postbox-container">		
				<div id="" class="postbox padma-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Fonts','padma'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Fonts','padma'); ?></span></h2>

					<?php

					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Google Fonts','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'do-not-use-google-fonts',
									'label' 	=> __('Do not use Google Fonts','padma'),
									'checked' 	=> PadmaOption::get('do-not-use-google-fonts', false, false)
								)
							),
							'description' => __('If checked, Padma will not use Google Fonts.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Load Google Fonts asynchronously','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'load-google-fonts-asynchronously',
									'label' 	=> __('Load Google Fonts asynchronously','padma'),
									'checked' 	=> PadmaOption::get('load-google-fonts-asynchronously', false, false)
								)
							),
							'description' => __('If checked, Padma will load fonts asynchronously to avoid render blocking font when you use google fonts.','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-display',
							'type' 	=> 'select',
							'label' => __('Google Fonts Display Timeline','padma'),
							'options' => array(								
								'auto' 		=> 'Auto',
								'block' 	=> 'Block',
								'swap' 		=> 'Swap',
								'fallback' 	=> 'Fallback',
								'optional' 	=> 'Optional',								
							),
							'value' => PadmaOption::get('google-fonts-display', false, 'swap'),
							'description' => __('Determines how a font face is displayed based on whether and when it is downloaded and ready to u','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-preload',
							'type' 	=> 'checkbox',
							'label' => __('Preload Google Fonts','padma'),
							'checkboxes' => array(
								array(
									'id' 		=> 'google-fonts-preload',
									'label' 	=> __('Preload Google Fonts','padma'),
									'checked' 	=> PadmaOption::get('google-fonts-preload', false, false)
								)
							),
							'description' => __('If on, this option will tell the web browser to fetch Google Fonts early','padma')
						)
					);

					PadmaAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>




	<div class="hr hr-submit" style="display: none;"></div>

	<p class="submit" style="display: none;">
		<input type="submit" name="padma-submit" value="<?php _e('Save Changes','padma'); ?>" class="button-primary padma-save" />
	</p>

</form>
