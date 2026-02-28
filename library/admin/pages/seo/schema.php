<?php
/**
 * Padma SEO Schema.org Modul
 * Verwaltet strukturierte Daten und Schema.org Einstellungen
 */

?>

<div class="big-tab" id="tab-schema-content">
	
	<div class="postbox-container padma-postbox-container">
		<div class="postbox padma-admin-options-group">

			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text"><?php _e('Schema.org Einstellungen', 'padma'); ?></span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>

			<h2 class="hndle"><span><?php _e('Strukturierte Daten (Schema.org)', 'padma'); ?></span></h2>

			<div class="inside">
				<?php
				$form = array(
					array(
						'type' => 'checkbox',
						'label' => __('Schema.org Markup', 'padma'),
						'checkboxes' => array(
							array(
								'id' => 'disable-schema-support',
								'label' => __('Strukturierte Daten DEAKTIVIEREN', 'padma'),
								'checked' => PadmaOption::get('disable-schema-support', 'general', false)
							)
						),
						'description' => __('Schema.org ist ein standardisiertes Vokabular für strukturierte Daten, das Suchmaschinen hilft, Inhalte besser zu verstehen und Rich Snippets anzuzeigen. <strong>Empfehlung:</strong> Aktiviert lassen für bessere Suchergebnisse!', 'padma')
					)
				);

				PadmaAdminInputs::admin_field_generate($form);
				?>

				<div class="hr"></div>

				<h3><?php _e('Aktive Schema-Typen', 'padma'); ?></h3>
				<p class="description"><?php _e('Padma generiert automatisch folgende strukturierte Daten:', 'padma'); ?></p>
				
				<ul style="list-style: disc; margin-left: 25px; line-height: 2; margin-top: 15px;">
					<li><strong>Article Schema</strong> &mdash; <?php _e('Für Blog-Posts und Artikel', 'padma'); ?></li>
					<li><strong>Breadcrumb Schema</strong> &mdash; <?php _e('Navigation und Seitenhierarchie', 'padma'); ?></li>
					<li><strong>Organization Schema</strong> &mdash; <?php _e('Website und Firmeninformationen', 'padma'); ?></li>
					<li><strong>FAQ Schema</strong> &mdash; <?php _e('Fragen und Antworten (via Metabox)', 'padma'); ?></li>
					<li><strong>HowTo Schema</strong> &mdash; <?php _e('Schritt-für-Schritt Anleitungen (via Metabox)', 'padma'); ?></li>
					<li><strong>Review Schema</strong> &mdash; <?php _e('Bewertungen und Rezensionen (via Metabox)', 'padma'); ?></li>
					<li><strong>Product Schema</strong> &mdash; <?php _e('Produkt-Informationen (via Metabox)', 'padma'); ?></li>
					<li><strong>Event Schema</strong> &mdash; <?php _e('Veranstaltungen (via Metabox)', 'padma'); ?></li>
				</ul>

				<div class="alert alert-blue" style="margin-top: 20px;">
					<p><strong><?php _e('Schema Validierung:', 'padma'); ?></strong></p>
					<p><?php _e('Teste deine strukturierten Daten mit dem', 'padma'); ?> <a href="https://search.google.com/test/rich-results" target="_blank">Google Rich Results Test</a> <?php _e('oder dem', 'padma'); ?> <a href="https://validator.schema.org/" target="_blank">Schema.org Validator</a>.</p>
				</div>
			</div>
		</div>
	</div>

</div><!-- #tab-schema-content -->
