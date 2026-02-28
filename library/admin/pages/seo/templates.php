<?php
/**
 * Padma SEO Templates Modul
 * Verwaltet Title und Meta Description Templates für verschiedene Seitentypen
 */

?>

<div class="big-tab" id="tab-templates-content">
	
	<div class="alert alert-blue" style="margin: 20px 0;">
		<h3 style="margin-top: 0;"><?php _e('SEO Templates', 'padma'); ?></h3>
		<p><?php _e('Definiere Title und Meta Description Templates für verschiedene Seitentypen. Diese werden automatisch angewendet, falls nicht in den einzelnen Posts überschrieben.', 'padma'); ?></p>
	</div>

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
			<label for="seo-template-select" style="font-weight: 600; margin-right: 10px;"><?php _e('Wähle einen Template-Typ:', 'padma'); ?></label>
			<select id="seo-template-select">
				<option value="index"><?php _e('Blog Index', 'padma'); ?></option>

				<?php
				if (get_option('show_on_front') == 'page')
					echo '<option value="front_page">' . __('Front Page', 'padma') . '</option>';
				?>

				<optgroup label="<?php _e('Einzelseiten', 'padma'); ?>">
					<?php
					$post_types = get_post_types(array('public' => true), 'objects');
					foreach ($post_types as $post_type)
						echo '<option value="single-' . $post_type->name . '">' . $post_type->label . '</option>';
					?>
				</optgroup>

				<optgroup label="<?php _e('Archive', 'padma'); ?>">
					<option value="archive-category"><?php _e('Kategorie', 'padma'); ?></option>
					<option value="archive-search"><?php _e('Suche', 'padma'); ?></option>
					<option value="archive-date"><?php _e('Datum', 'padma'); ?></option>
					<option value="archive-author"><?php _e('Autor', 'padma'); ?></option>
					<option value="archive-post_tag"><?php _e('Tag', 'padma'); ?></option>
					<option value="archive-post_type"><?php _e('Post-Typ', 'padma'); ?></option>
					<option value="archive-taxonomy"><?php _e('Taxonomie', 'padma'); ?></option>
				</optgroup>

				<option value="four04">404</option>
			</select>
		</div><!-- #seo-templates-header -->

		<div id="seo-templates-inputs" style="margin-top: 30px;">

			<?php
			$form = array(
				array(
					'id' => 'title',
					'type' => 'text',
					'size' => 'large',
					'label' => __('Title Template', 'padma'),
					'description' => __('Der Title ist das wichtigste On-Page SEO Element. Er erscheint im Browser Tab, in Suchergebnissen und bei Social Media Shares. <strong>Tipp:</strong> Optimal sind unter 70 Zeichen.<br /><br /><a href="http://www.seomoz.org/learn-seo/title-tag" target="_blank">Mehr über Title Tags &raquo;</a>', 'padma'),
					'no-submit' => true
				),

				array(
					'id' => 'description',
					'type' => 'paragraph',
					'cols' => 60,
					'rows' => 3,
					'label' => '<code>&lt;meta&gt;</code> ' . __('Description Template', 'padma'),
					'description' => __('Die Meta Description ist entscheidend für die Click-Through Rate in Suchergebnissen. Sie beschreibt den Inhalt der Seite für Suchende. <strong>Tipp:</strong> Optimal sind ca. 150-160 Zeichen.<br /><br /><a href="http://www.seomoz.org/learn-seo/meta-description" target="_blank">Mehr über Meta Descriptions &raquo;</a>', 'padma'),
					'no-submit' => true
				)
			);

			PadmaAdminInputs::generate($form);
			?>

			<div class="hr"></div>

			<h3><?php _e('Verfügbare Variablen:', 'padma'); ?></h3>
			<p class="description"><?php _e('Diese Platzhalter kannst du in Title und Description verwenden:', 'padma'); ?></p>

			<ul style="list-style: disc; margin-left: 25px; line-height: 2;">
				<li><code>%title%</code> &mdash; <?php _e('Titel des aktuellen Posts, Archivs oder der Seite', 'padma'); ?></li>
				<li><code>%sitename%</code> &mdash; <?php echo sprintf(__('Name der Website (aus <a href="%s" target="_blank">Einstellungen &rsaquo; Allgemein</a>)', 'padma'), admin_url('options-general.php')); ?></li>
				<li><code>%tagline%</code> &mdash; <?php echo sprintf(__('Untertitel der Website (aus <a href="%s" target="_blank">Einstellungen &rsaquo; Allgemein</a>)', 'padma'), admin_url('options-general.php')); ?></li>
				<li><code>%meta%</code> &mdash; <?php _e('Nur für Taxonomie-Archive: zeigt den Term-Namen an', 'padma'); ?></li>
			</ul>

			<h3 id="seo-templates-advanced-options-title" class="title title-hr" style="cursor: pointer; margin-top: 30px;">
				<?php _e('Erweiterte Optionen', 'padma'); ?> <span><?php _e('anzeigen &darr;', 'padma'); ?></span>
			</h3>

			<div id="seo-templates-advanced-options" style="display: none;">
				<?php
				$form = array(
					array(
						'type' => 'checkbox',
						'label' => __('Seiten-Indexierung', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'noindex',
								'label' => 'Enable <code>noindex</code>',
								'no-submit' => true
							)
						),
						'description' => __('Index/NoIndex sagt Suchmaschinen, ob die Seite gecrawlt werden soll. Mit <code>noindex</code> wird die Seite von Suchmaschinen ausgeschlossen. <strong>Hinweis:</strong> Nur aktivieren, wenn du sicher bist!', 'padma')
					),

					array(
						'type' => 'checkbox',
						'label' => __('Link-Verfolgung', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'nofollow',
								'label' => 'Enable <code>nofollow</code>',
								'no-submit' => true
							)
						),
						'description' => __('Follow/NoFollow sagt Suchmaschinen, ob Links auf der Seite verfolgt werden sollen. Mit "nofollow" werden Links ignoriert. <strong>Hinweis:</strong> Nur aktivieren, wenn du sicher bist!', 'padma')
					),

					array(
						'type' => 'checkbox',
						'label' => __('Seiten-Archivierung', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'noarchive',
								'label' => __('<code>noarchive</code> aktivieren', 'padma'),
								'no-submit' => true
							)
						),
						'description' => __('Noarchive verhindert, dass Suchmaschinen eine gecachte Kopie der Seite speichern.', 'padma')
					),

					array(
						'type' => 'checkbox',
						'label' => __('Snippet-Anzeige', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'nosnippet',
								'label' => __('<code>nosnippet</code> aktivieren', 'padma'),
								'no-submit' => true
							)
						),
						'description' => __('Nosnippet verhindert die Anzeige von beschreibenden Textblöcken in Suchergebnissen.', 'padma')
					),

					array(
						'type' => 'checkbox',
						'label' => __('Open Directory Project', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'noodp',
								'label' => __('<code>NoODP</code> aktivieren', 'padma'),
								'no-submit' => true
							)
						),
						'description' => __('NoODP verhindert, dass Suchmaschinen Beschreibungen vom Open Directory Project (DMOZ) verwenden.', 'padma')
					),

					array(
						'type' => 'checkbox',
						'label' => __('Yahoo! Directory', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'noydir',
								'label' => __('<code>NoYDir</code> aktivieren', 'padma'),
								'no-submit' => true
							)
						),
						'description' => __('NoYDir verhindert, dass Yahoo die Yahoo Directory Beschreibung in Suchergebnissen verwendet.', 'padma')
					)
				);

				PadmaAdminInputs::generate($form);
				?>
			</div><!-- #seo-templates-advanced-options -->

		</div><!-- #seo-templates-inputs -->
	</div><!-- #seo-templates -->

	<div class="alert alert-yellow" style="margin-top: 30px;">
		<p><?php _e('Neu bei <em>Search Engine Optimization</em>?', 'padma'); ?> <a href="http://www.seomoz.org/beginners-guide-to-seo/" target="_blank"><?php _e('Lern-Ressourcen', 'padma'); ?> &raquo;</a></p>
	</div>

</div><!-- #tab-templates-content -->
