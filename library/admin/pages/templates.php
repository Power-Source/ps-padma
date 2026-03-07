<div id="export-template" style="display: none;">
	<h3><?php _e('Vorlage exportieren','padma'); ?></h3>
	<p><?php _e('Fülle die folgenden Informationen aus, um alle Design-Einstellungen, Layouts und Blöcke als Padma-Vorlage zu exportieren. Diese Informationen werden in der exportierten Datei gespeichert und beim Import wieder verwendet.','padma'); ?></p>

	<form id="export-template-form">
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row"><label for="template-export-name"><?php _e('Vorlagenname','padma'); ?></label></th>
				<td><input id="template-export-name" type="text" name="skin-export-info[name]" class="regular-text" /></td>
			</tr>

			<?php
			$current_user = wp_get_current_user();
			?>

			<tr valign="top">
				<th scope="row"><label for="template-export-author"><?php _e('Autor/in','padma'); ?></label></th>
				<td><input id="template-export-author" type="text" name="skin-export-info[author]" class="regular-text" value="<?php echo $current_user->display_name; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="template-export-version"><?php _e('Version','padma'); ?></label></th>
				<td><input id="template-export-version" type="text" name="skin-export-info[version]" placeholder="z.B. 1.0" class="medium-text" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="template-export-description"><?php _e('Beschreibung','padma'); ?></label></th>
				<td><textarea id="template-export-description" name="skin-export-info[description]" class="large-text" rows="3" placeholder="<?php _e('Kurze Beschreibung deiner Vorlage','padma'); ?>"></textarea></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="template-export-image"><?php _e('Vorlagenbild','padma'); ?></label></th>
				<td>
					<button id="template-export-image-button" class="button-secondary">
						<span class="wp-media-buttons-icon"></span>
					<?php _e('Bild auswählen','padma'); ?>
					</button>
					<input id="template-export-image" type="hidden" name="skin-export-info[image-url]" class="medium-text" />
					<img src="" id="template-export-image-preview" style="display: none; max-width: 200px; margin-top: 10px;" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="template-export-doc-url"><?php _e('Dokumentations-Link','padma'); ?></label></th>
				<td><input id="template-export-doc-url" type="url" name="skin-export-info[documentation-url]" class="regular-text" placeholder="https://beispiel.de/dokumentation" /></td>
			</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="export-template-submit" class="button button-primary" value="<?php _e('Vorlage exportieren','padma'); ?>">
		</p>
	</form>
</div>

<!-- Modal für Vorlagen-Metadaten bearbeiten -->
<div id="edit-template-meta" style="display: none;">
	<h3><?php _e('Vorlage bearbeiten','padma'); ?></h3>
	<form id="edit-template-meta-form">
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row"><label for="edit-template-name"><?php _e('Vorlagenname','padma'); ?></label></th>
				<td><input id="edit-template-name" type="text" name="name" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="edit-template-author"><?php _e('Autor/in','padma'); ?></label></th>
				<td><input id="edit-template-author" type="text" name="author" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="edit-template-version"><?php _e('Version','padma'); ?></label></th>
				<td><input id="edit-template-version" type="text" name="version" placeholder="z.B. 1.0" class="medium-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="edit-template-description"><?php _e('Beschreibung','padma'); ?></label></th>
				<td><textarea id="edit-template-description" name="description" class="large-text" rows="3"></textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="edit-template-doc-url"><?php _e('Dokumentations-Link','padma'); ?></label></th>
				<td><input id="edit-template-doc-url" type="url" name="documentation-url" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label><?php _e('Vorlagenbild','padma'); ?></label></th>
				<td>
					<button id="edit-template-image-button" type="button" class="button-secondary">
						<span class="wp-media-buttons-icon"></span>
					<?php _e('Bild auswählen','padma'); ?>
					</button>
					<input id="edit-template-image" type="hidden" name="image-url" class="medium-text" />
					<img src="" id="edit-template-image-preview" style="display: none; max-width: 200px; margin-top: 10px;" />
				</td>
			</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="edit-template-meta-submit" class="button button-primary" value="<?php _e('Speichern','padma'); ?>">
		</p>
	</form>
</div>

<h2><?php _e('Padma Vorlagen','padma'); ?>
	<a href="#" class="add-new-h2" id="install-template"><?php _e('Vorlage installieren','padma'); ?></a>
	<a href="#export-template" class="add-new-h2 ps-modal-trigger" data-modal-target="export-template"><?php _e('Aktuelle Vorlage exportieren','padma'); ?></a>
</h2>

<div id="padma-admin-notifications"></div>

<!-- Meine Vorlagen Sektion -->
<div id="my-templates-section" style="margin-bottom: 40px;">
	<h3><?php _e('Meine Vorlagen','padma'); ?></h3>
	<div class="theme-browser">
		<div class="themes padma-templates">
			<!-- ko foreach: myTemplates -->
				<div class="theme padma-template" tabindex="0" data-bind="attr: { 'data-template-id': id }, css: { 'active': $parent.active().id == id, 'missing-image': !$data['image-url'] }">

					<div class="theme-screenshot">
						<img src="" alt="" data-bind="attr: { 'src': ($data['image-url'] ? $data['image-url'] : '<?php echo esc_js(padma_url() . '/screenshot.png'); ?>') }" />
					</div>

					<div class="theme-author" data-bind="text: 'von ' + author, visible: author"></div>

					<h3 class="theme-name" id="padma-name">
						<span data-bind="visible: $parent.active().id == id"><?php _e('Aktiv','padma'); ?>: </span><span data-bind="text: name"></span>
						<small data-bind="visible: version, text: ' (' + version + ')'"></small>
					</h3>

					<p class="theme-description" data-bind="text: description, visible: description" style="font-size: 12px; margin: 5px 0;"></p>

					<div class="theme-actions">
						<a href="#edit-template-meta" class="button button-secondary template-sub-action ps-modal-trigger edit-template-meta" data-modal-target="edit-template-meta" data-bind="click: $parent.editTemplateMeta"><?php _e('Bearbeiten','padma'); ?></a>
						<a href="#" class="button button-secondary template-sub-action download-template" data-bind="click: $parent.downloadTemplate"><?php _e('Herunterladen','padma'); ?></a>
						<a href="#" class="button button-secondary delete-template" data-bind="click: $parent.deleteSkin, visible: (id != $parent.active().id && id != 'base')"><?php _e('Löschen','padma'); ?></a>
						<a class="button button-primary" href="#" data-bind="click: $parent.activateSkin, visible: id != $parent.active().id"><?php _e('Aktivieren','padma'); ?></a>
					</div>

				</div>
			<!-- /ko -->

			<div class="theme add-new-theme" id="add-blank-template">
				<a href="#">
					<div class="theme-screenshot"><span></span></div>
					<h3 class="theme-name"><?php _e('Neue Vorlage','padma'); ?></h3>
				</a>
			</div>
		</div>
		<br class="clear">
	</div>
</div>

<!-- Standard Vorlagen Sektion -->
<div class="theme-browser" id="padma-templates-browser">
	<h3><?php _e('Standard-Vorlagen','padma'); ?></h3>
	<div class="themes padma-templates">
		<!-- ko foreach: standardTemplates -->
			<div class="theme padma-template" tabindex="0" aria-describedby="padma-action padma-name" data-bind="attr: { 'data-template-id': id }, css: { 'active': $parent.active().id == id, 'missing-image': !$data['image-url'], 'template-installing': (typeof $data['installing'] != 'undefined' && $data['installing']) }">

				<div class="theme-screenshot">
					<span class="template-loading-indicator" data-bind="visible: (typeof $data['installing'] != 'undefined' && $data['installing'])"></span>
					<img src="" alt="" data-bind="attr: { 'src': ($data['image-url'] ? $data['image-url'] : '<?php echo esc_js(padma_url() . '/screenshot.png'); ?>') }" />
				</div>

				<div class="theme-author" data-bind="text: 'von ' + author, visible: author"></div>

				<h3 class="theme-name" id="padma-name">
					<span data-bind="visible: $parent.active().id == id"><?php _e('Aktiv','padma'); ?>: </span><span data-bind="text: name"></span>
					<small data-bind="visible: version, text: ' (' + version + ')'"></small>
				</h3>

				<p class="theme-description" data-bind="text: description, visible: description" style="font-size: 12px; margin: 5px 0;"></p>

				<div class="theme-actions" data-bind="visible: (typeof $data['installing'] == 'undefined' || !$data['installing'])">
					<a href="#" class="button button-secondary delete-template" data-bind="click: $parent.deleteSkin, visible: (id != $parent.active().id && id != 'base')"><?php _e('Löschen','padma'); ?></a>
					<a class="button button-primary" href="#" data-bind="click: $parent.activateSkin, visible: id != $parent.active().id"><?php _e('Aktivieren','padma'); ?></a>
				</div>

			</div>
		<!-- /ko -->

		<?php 
		if(class_exists('padmaServices')){

			$padmaServices 	= new padmaServices();
			$padmaServices->setToken(get_option('padma_service_token'));
			$data 	= $padmaServices->getDashboardData();

			if( is_array($data->templates) && count($data->templates) > 0){
				echo '<hr class="templates">';
				echo '<h3>' . __('Vorlagen aus deinem Padma Services Account','padma') . '</h3>';

				foreach ($data->templates as $key => $template) {

						$template 	= (array)$template;
						$id 		= $template['id'];
						$name 		= $template['name'];
						$screenshot = $template['image'];

						?>

						<div class="theme padma-template" tabindex="0">

							<div class="theme-screenshot">
								<span class="template-loading-indicator"></span>
								<img src="<?php echo $screenshot; ?>" alt="" />
							</div>

							<h3 class="theme-name" id="padma-name"><span><?php _e('Verfügbar','padma'); ?>: </span><?php echo $name; ?></h3>

							<div class="theme-actions">
								<a class="button button-primary install-cloud-template" id="template-<?php echo $id; ?>" data-token="<?php echo get_option('padma_service_token'); ?>" href="#"><?php _e('Installieren','padma'); ?></a>
							</div>

						</div>

					<?php
				} // foreach

				echo '<hr class="templates">';
			}
		}

		?>
	</div>
	<br class="clear">
</div>

<form id="upload-skin">
	<input type="file" />
</form>