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
						<a href="#" class="button button-small edit-template-meta">📝 <?php _e('Bearbeiten','padma'); ?></a>
						<a href="#" class="button button-small download-template">⬇️ <?php _e('Download','padma'); ?></a>
						<a href="#" class="button button-secondary delete-template" data-bind="click: $parent.deleteSkin, visible: (id != $parent.active().id && id != 'base')"><?php _e('Löschen','padma'); ?></a>
						<a class="button button-primary" href="#" data-bind="click: $parent.activateSkin, visible: id != $parent.active().id"><?php _e('Aktivieren','padma'); ?></a>
					</div>

				</div>
			<!-- /ko -->

			<div class="theme add-new-theme" id="add-blank-template">
				<a href="#">
					<div class="theme-screenshot"><span></span></div>
					<h3 class="theme-name">➕ <?php _e('Neue Vorlage','padma'); ?></h3>
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

<script>
// Extended ViewModel für Template Management mit neuen Features
jQuery(document).ready(function($) {

	// Edit Template Metadata Handler
	$(document).on('click', '.edit-template-meta', function(e) {
		e.preventDefault();
		
		const templateData = this.closest('.padma-template');
		const templateId = templateData.getAttribute('data-template-id');
		const templateInfo = {
			id: templateId,
			name: $(templateData).find('.theme-name').text().trim(),
			author: $(templateData).find('.theme-author').text().replace('von ', ''),
			version: $(templateData).find('.theme-name small').text().replace(/[\(\)]/g, ''),
			description: $(templateData).find('.theme-description').text() || '',
			image: $(templateData).find('img').attr('src') || '',
			documentationUrl: ''
		};

		// Show edit modal
		showEditTemplateMeta(templateInfo);
	});

	// Download Template Handler
	$(document).on('click', '.download-template', function(e) {
		e.preventDefault();

		const templateId = this.closest('.padma-template').getAttribute('data-template-id');
		const button = this;
		
		// Disable button while downloading
		$(button).prop('disabled', true).text('⏳ ' + padmaLang.downloading);

		$.ajax({
			url: psAjaxUrl,
			type: 'POST',
			data: {
				action: 'padma_visual_editor_ajax',
				method: 'download_template',
				template_id: templateId,
				_wpnonce: psAjaxNonce
			},
			success: function(response) {
				const result = JSON.parse(response);
				
				if (result.success && result.download_url) {
					// Trigger download
					const link = document.createElement('a');
					link.href = result.download_url;
					link.download = result.filename;
					document.body.appendChild(link);
					link.click();
					document.body.removeChild(link);

					// Clean up file after download
					setTimeout(() => {
						$.ajax({
							url: psAjaxUrl,
							type: 'POST',
							data: {
								action: 'padma_visual_editor_ajax',
								method: 'cleanup_download',
								file_path: result.file_path,
								_wpnonce: psAjaxNonce
							}
						});
					}, 1000);

					showNotification('✅ Vorlage heruntergeladen!', 'success');
				} else {
					showNotification('❌ ' + (result.error || 'Download fehlgeschlagen'), 'error');
				}
			},
			error: function() {
				showNotification('❌ Fehler beim Download', 'error');
			},
			complete: function() {
				$(button).prop('disabled', false).text('⬇️ ' + padmaLang.download);
			}
		});
	});

	// Edit Template Meta Form Handler
	$(document).on('submit', '#edit-template-meta-form', function(e) {
		e.preventDefault();

		const templateId = $(this).data('template-id');
		const metaData = {
			name: $('#edit-template-name').val(),
			author: $('#edit-template-author').val(),
			version: $('#edit-template-version').val(),
			description: $('#edit-template-description').val(),
			'documentation-url': $('#edit-template-doc-url').val(),
			'image-url': $('#edit-template-image').val()
		};

		$.ajax({
			url: psAjaxUrl,
			type: 'POST',
			data: {
				action: 'padma_visual_editor_ajax',
				method: 'update_template_meta',
				template_id: templateId,
				meta_data: JSON.stringify(metaData),
				_wpnonce: psAjaxNonce
			},
			success: function(response) {
				const result = JSON.parse(response);
				
				if (result.success) {
					showNotification('✅ Vorlage aktualisiert', 'success');
					closeModal('edit-template-meta');
					// Reload templates
					location.reload();
				} else {
					showNotification('❌ ' + (result.error || 'Fehler beim Speichern'), 'error');
				}
			},
			error: function() {
				showNotification('❌ Fehler beim Speichern', 'error');
			}
		});
	});

	// Image Picker für Edit Modal
	$(document).on('click', '#edit-template-image-button', function(e) {
		e.preventDefault();
		
		const wp_media_frame = wp.media.frames.file_frame = wp.media({
			title: padmaLang.selectImage,
			button: { text: padmaLang.selectImage },
			multiple: false
		});

		wp_media_frame.on('select', function() {
			const attachment = wp_media_frame.state().get('selection').first().toJSON();
			$('#edit-template-image').val(attachment.url);
			$('#edit-template-image-preview')
				.attr('src', attachment.url)
				.show();
		});

		wp_media_frame.open();
	});

	// Image Picker für Export Modal
	$(document).on('click', '#template-export-image-button', function(e) {
		e.preventDefault();
		
		const wp_media_frame = wp.media.frames.file_frame = wp.media({
			title: padmaLang.selectImage,
			button: { text: padmaLang.selectImage },
			multiple: false
		});

		wp_media_frame.on('select', function() {
			const attachment = wp_media_frame.state().get('selection').first().toJSON();
			$('#template-export-image').val(attachment.url);
			$('#template-export-image-preview')
				.attr('src', attachment.url)
				.show();
		});

		wp_media_frame.open();
	});

	// Notification Helper
	function showNotification(message, type = 'info') {
		const notificationDiv = document.getElementById('padma-admin-notifications');
		if (!notificationDiv) return;

		const notification = document.createElement('div');
		notification.className = `notice notice-${type} is-dismissible`;
		notification.innerHTML = `<p>${message}</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>`;

		notificationDiv.appendChild(notification);

		// Auto-remove after 5 seconds
		setTimeout(() => {
			$(notification).fadeOut(() => notification.remove());
		}, 5000);
	}

	// Modal Helpers
	function showEditTemplateMeta(templateInfo) {
		// Fill form with template data
		$('#edit-template-name').val(templateInfo.name);
		$('#edit-template-author').val(templateInfo.author);
		$('#edit-template-version').val(templateInfo.version);
		$('#edit-template-description').val(templateInfo.description);
		$('#edit-template-image').val(templateInfo.image);
		
		if (templateInfo.image) {
			$('#edit-template-image-preview').attr('src', templateInfo.image).show();
		}

		$('#edit-template-meta-form').data('template-id', templateInfo.id);

		// Show modal
		const modal = document.getElementById('edit-template-meta');
		if (modal) {
			modal.style.display = 'block';
		}
	}

	function closeModal(modalId) {
		const modal = document.getElementById(modalId);
		if (modal) {
			modal.style.display = 'none';
		}
	}

	// Close modals when pressing Escape
	$(document).keydown(function(e) {
		if (e.keyCode === 27) {
			$('#edit-template-meta, #export-template').fadeOut();
		}
	});

	// Language strings
	var padmaLang = {
		downloading: '<?php _e('Wird heruntergeladen...', 'padma'); ?>',
		download: '<?php _e('Download', 'padma'); ?>',
		selectImage: '<?php _e('Bild auswählen', 'padma'); ?>'
	};

});
</script>