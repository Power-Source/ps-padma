jQuery(document).ready(function($) {

	var templates = {
		renderCatalogIdentityStatus: function(identity, isError) {
			var $status = $('#padma-catalog-membership-status');
			if (!$status.length) {
				return;
			}

			if (!identity || !identity.user_id) {
				$status.css('color', '#555').text('Nicht verbunden. Bitte Anmeldung einblenden und Verbindung pruefen.');
				return;
			}

			var text = 'Verifizierter Account: ' + (identity.remote_login || ('User #' + identity.user_id));
			if (identity.email) {
				text += ' | E-Mail: ' + identity.email;
			}

			if (identity.membership_ok) {
				text += identity.admin_bypass ? ' | Verbindungsmodus: Superadmin-Bypass' : ' | PS-Mitgliedschaft: OK';
				if (identity.verified_at) {
					text += ' (geprueft: ' + identity.verified_at + ')';
				}
				$status.css('color', '#008a20').text(text);
				return;
			}

			if (identity.message) {
				text += ' | ' + identity.message;
			}

			$status.css('color', isError ? '#b32d2e' : '#555').text(text);
		},

		normalizeSlug: function(value) {
			return (value || '')
				.toString()
				.toLowerCase()
				.replace(/[^a-z0-9-]+/g, '-')
				.replace(/^-+|-+$/g, '');
		},

		compareVersions: function(a, b) {
			var aParts = (a || '0.0.0').toString().split('.')[0] ? (a || '0.0.0').toString().split('.') : ['0', '0', '0'];
			var bParts = (b || '0.0.0').toString().split('.')[0] ? (b || '0.0.0').toString().split('.') : ['0', '0', '0'];

			for (var i = 0; i < 3; i++) {
				var aNum = parseInt((aParts[i] || '0').replace(/[^0-9].*$/, ''), 10);
				var bNum = parseInt((bParts[i] || '0').replace(/[^0-9].*$/, ''), 10);

				aNum = isNaN(aNum) ? 0 : aNum;
				bNum = isNaN(bNum) ? 0 : bNum;

				if (aNum > bNum) return 1;
				if (aNum < bNum) return -1;
			}

			return 0;
		},

		decorateCatalogTemplates: function(catalogTemplates) {
			var localTemplates = (Padma.viewModels.templates && Padma.viewModels.templates.myTemplates)
				? Padma.viewModels.templates.myTemplates()
				: [];

			for (var i = 0; i < catalogTemplates.length; i++) {
				var catalogTemplate = catalogTemplates[i];
				var catalogSlug = templates.normalizeSlug(catalogTemplate.slug || catalogTemplate.name || '');
				var installedVersion = null;

				for (var j = 0; j < localTemplates.length; j++) {
					var localTemplate = localTemplates[j] || {};
					var localSlug = templates.normalizeSlug(localTemplate.id || localTemplate.name || '');

					if (catalogSlug && localSlug === catalogSlug) {
						if (!installedVersion || templates.compareVersions(localTemplate.version, installedVersion) > 0) {
							installedVersion = localTemplate.version || null;
						}
					}
				}

				catalogTemplate.installed_version = installedVersion;
				catalogTemplate.update_available = !!installedVersion && templates.compareVersions(catalogTemplate.latest, installedVersion) > 0;
			}

			return catalogTemplates;
		},

		init: function() {

			templates.bind();
			templates.setupViewModel();
			templates.renderCatalogIdentityStatus(Padma.catalogIdentity || null, false);
			templates.fetchCatalogTemplates();

		},

		setupViewModel: function() {

			var allTemplates = Padma.templates || [];
			var myTemplates = [];
			var standardTemplates = [];

			for (var i = 0; i < allTemplates.length; i++) {
				if (allTemplates[i].id === 'base') {
					standardTemplates.push(allTemplates[i]);
				} else {
					myTemplates.push(allTemplates[i]);
				}
			}

			Padma.viewModels.templates = {
				templates: ko.observableArray(Padma.templates),
				myTemplates: ko.observableArray(myTemplates),
				standardTemplates: ko.observableArray(standardTemplates),
				catalogTemplates: ko.observableArray([]),
				active: ko.observable(Padma.templateActive),
				editTemplateMeta: function() {

					var skin = this;

					$('#edit-template-meta-form').data('template-id', skin.id);
					$('#edit-template-name').val(skin.name || '');
					$('#edit-template-author').val(skin.author || '');
					$('#edit-template-version').val(skin.version || '');
					$('#edit-template-description').val(skin.description || '');
					$('#edit-template-doc-url').val(skin['documentation-url'] || '');
					$('#edit-template-image').val(skin['image-url'] || '');

					if ( skin['image-url'] ) {
						$('#edit-template-image-preview').attr('src', skin['image-url']).show();
					} else {
						$('#edit-template-image-preview').hide();
					}

				},
				downloadTemplate: function() {

					var skin = this;

					if ( !skin.id || skin.id === 'base' )
						return;

					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_visual_editor',
						method: 'download_template',
						template_id: skin.id
					}, function(response) {

						if ( response && response.download_url ) {
							window.location = response.download_url;

							if ( response.file_path ) {
								setTimeout(function() {
									$.post(Padma.ajaxURL, {
										security: Padma.security,
										action: 'padma_visual_editor',
										method: 'cleanup_download',
										file_path: response.file_path
									});
								}, 2000);
							}

						} else {
							showNotification({
								id: 'template-download-error',
								message: (response && response.error) ? response.error : 'Could not download template.',
								closable: true,
								closeTimer: 6000,
								error: true
							});
						}

					}, 'json');

				},
				publishTemplateToCatalogWithVisibility: function(skin, visibility) {

					if ( !skin || !skin.id || skin.id === 'base' ) {
						return;
					}

					showNotification({
						id: 'catalog-publish-start-' + skin.id,
						message: 'Veroeffentliche Vorlage im Katalog (' + visibility + '): ' + skin.name,
						closable: false,
						closeTimer: false
					});

					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_visual_editor',
						method: 'download_template',
						template_id: skin.id
					}, function(downloadResponse) {

						if ( !downloadResponse || !downloadResponse.file_path ) {
							hideNotification('catalog-publish-start-' + skin.id);
							return showNotification({
								id: 'catalog-publish-error-download-' + skin.id,
								message: (downloadResponse && downloadResponse.error) ? downloadResponse.error : 'Template konnte nicht fuer den Katalog exportiert werden.',
								closable: true,
								closeTimer: 8000,
								error: true
							});
						}

						$.post(Padma.ajaxURL, {
							security: Padma.security,
							action: 'padma_publish_template_catalog',
							template_id: skin.id,
							file_path: downloadResponse.file_path,
							visibility: visibility
						}, function(publishResponse) {

							hideNotification('catalog-publish-start-' + skin.id);

							if ( publishResponse && publishResponse.success ) {
								return showNotification({
									id: 'catalog-publish-success-' + skin.id,
									message: skin.name + ' erfolgreich im Katalog veröffentlicht (' + visibility + ').',
									closable: true,
									closeTimer: 6000,
									success: true
								});
							}

							showNotification({
								id: 'catalog-publish-error-' + skin.id,
								message: publishResponse && publishResponse.data && publishResponse.data.error
									? publishResponse.data.error
									: 'Katalog-Veröffentlichung fehlgeschlagen.',
								closable: true,
								closeTimer: 8000,
								error: true
							});

						}, 'json').fail(function(xhr) {

							hideNotification('catalog-publish-start-' + skin.id);

							var publishError = 'Katalog-Veroeffentlichung fehlgeschlagen (Serverfehler).';
							if (xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.error) {
								publishError = xhr.responseJSON.data.error;
							} else if (xhr && xhr.responseText) {
								publishError = 'Katalog-Veroeffentlichung fehlgeschlagen: ' + xhr.responseText.substring(0, 180);
							}

							showNotification({
								id: 'catalog-publish-error-http-' + skin.id,
								message: publishError,
								closable: true,
								closeTimer: 9000,
								error: true
							});

						}).always(function() {

							if ( downloadResponse.file_path ) {
								$.post(Padma.ajaxURL, {
									security: Padma.security,
									action: 'padma_visual_editor',
									method: 'cleanup_download',
									file_path: downloadResponse.file_path
								});
							}

						});

					}, 'json').fail(function() {
						hideNotification('catalog-publish-start-' + skin.id);
						showNotification({
							id: 'catalog-publish-error-request-' + skin.id,
							message: 'Katalog-Veröffentlichung konnte nicht gestartet werden.',
							closable: true,
							closeTimer: 8000,
							error: true
						});
					});

				},
				publishTemplateToCatalogPrivate: function() {

					var skin = this;
					return Padma.viewModels.templates.publishTemplateToCatalogWithVisibility(skin, 'private');

				},
				publishTemplateToCatalogPublic: function() {

					var skin = this;
					return Padma.viewModels.templates.publishTemplateToCatalogWithVisibility(skin, 'public');

				},
				installCatalogTemplate: function() {

					var template = this;

					if ( !template.slug ) {
						return;
					}

					showNotification({
						id: 'catalog-install-' + template.slug,
						message: 'Installiere Katalog-Vorlage: ' + (template.name || template.slug),
						closeTimer: false,
						closable: false
					});

					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_install_catalog_template',
						slug: template.slug,
						version: template.latest || ''
					}, function(response) {

						hideNotification('catalog-install-' + template.slug);

						if ( response && response.success && response.data && response.data.skin ) {

							var installedSkin = $.extend({}, {description: null}, response.data.skin);
							Padma.viewModels.templates.templates.push(installedSkin);
							Padma.viewModels.templates.myTemplates.push(installedSkin);
							templates.fetchCatalogTemplates();

							return showNotification({
								id: 'catalog-install-success-' + template.slug,
								message: (template.name || template.slug) + ' erfolgreich installiert.',
								closeTimer: 6000,
								success: true
							});
						}

						showNotification({
							id: 'catalog-install-error-' + template.slug,
							message: response && response.data && response.data.error ? response.data.error : 'Katalog-Installation fehlgeschlagen.',
							closable: true,
							closeTimer: 8000,
							error: true
						});

					}, 'json').fail(function() {
						hideNotification('catalog-install-' + template.slug);
						showNotification({
							id: 'catalog-install-error-request-' + template.slug,
							message: 'Katalog-Installation konnte nicht gestartet werden.',
							closable: true,
							closeTimer: 8000,
							error: true
						});
					});

				},
				activateSkin: function() {

					var skin = this;

					/* Don't try to activate if it's already activated */
					if ( skin.id == Padma.viewModels.templates.active().id )
						return;

					/* Send AJAX Request to switch skins */
						$.post(Padma.ajaxURL, {
							security: Padma.security,
							action: 'padma_visual_editor',
							method: 'switch_skin',
							skin: skin.id
						}, function(response) {

							/* Set this skin as the activated skin */
							Padma.viewModels.templates.active(skin);

							showNotification({
								id: 'skin-switched',
								message: skin.name + ' activated.',
								closeTimer: 5000,
								success: true
							});

						});

				},
				deleteSkin: function() {

					var skin = this;

					if ( !confirm('Are you sure you want to delete this template?  All design settings, blocks, and layout settings for this template will be deleted.') )
						return;

					/* Send AJAX Request to switch skins */
						$.post(Padma.ajaxURL, {
							security: Padma.security,
							action: 'padma_visual_editor',
							method: 'delete_skin',
							skin: skin.id
						}, function(response) {

							if ( response != 'success' ) {

								return showErrorNotification({
									id: 'unable-to-delete-skin',
									message: 'Unable to delete template.',
								});

							} else {

								showNotification({
									id: 'skin-deleted',
									message: skin.name + ' deleted.',
									closeTimer: 5000,
									success: true
								});

							}

							Padma.viewModels.templates.templates.remove(skin);
							Padma.viewModels.templates.myTemplates.remove(skin);
							Padma.viewModels.templates.standardTemplates.remove(skin);

						});

				}
			}

			$('.padma-templates').each(function() {
				ko.applyBindings(Padma.viewModels.templates, this);
			});

		},

		fetchCatalogTemplates: function() {

			$.post(Padma.ajaxURL, {
				security: Padma.security,
				action: 'padma_catalog_templates'
			}, function(response) {

				if ( response && response.success && response.data && $.isArray(response.data.templates) ) {
					Padma.viewModels.templates.catalogTemplates(templates.decorateCatalogTemplates(response.data.templates));
				}

			}, 'json');

		},

		bind: function() {

			$('#padma-catalog-toggle-form').on('click', function() {
				var $form = $('#padma-catalog-auth-form');
				var isVisible = $form.is(':visible');

				$form.slideToggle(120);
				$(this).text(isVisible ? 'Anmeldung einblenden' : 'Anmeldung ausblenden');

				if (!isVisible) {
					setTimeout(function() {
						$('#padma-catalog-login').trigger('focus');
					}, 140);
				}
			});

			$('#padma-catalog-verify-membership').on('click', function() {
				var login = ($('#padma-catalog-login').val() || '').trim();
				var password = ($('#padma-catalog-password').val() || '').trim();

				if (!login || !password) {
					return showNotification({
						id: 'catalog-identity-verify-invalid',
						message: 'Bitte Benutzername/E-Mail und Passwort eingeben.',
						error: true,
						closable: true,
						closeTimer: 7000
					});
				}

				showNotification({
					id: 'catalog-identity-verify-running',
					message: 'Pruefe Anmeldung und PS-Mitgliedschaft...',
					closable: false,
					closeTimer: false
				});

				$.post(Padma.ajaxURL, {
					security: Padma.security,
					action: 'padma_verify_catalog_membership',
					login: login,
					password: password
				}, function(response) {

					hideNotification('catalog-identity-verify-running');

					if (response && response.success && response.data && response.data.identity) {
						Padma.catalogIdentity = response.data.identity;
						$('#padma-catalog-password').val('');
						templates.renderCatalogIdentityStatus(Padma.catalogIdentity, false);

						return showNotification({
							id: 'catalog-identity-verify-ok',
							message: 'Anmeldung erfolgreich und PS-Mitgliedschaft verifiziert.',
							success: true,
							closeTimer: 6000
						});
					}

					var errorMsg = response && response.data && response.data.error ? response.data.error : 'Mitgliedschaft konnte nicht verifiziert werden.';
					templates.renderCatalogIdentityStatus((response && response.data && response.data.identity) ? response.data.identity : Padma.catalogIdentity, true);

					showNotification({
						id: 'catalog-identity-verify-error',
						message: errorMsg,
						error: true,
						closable: true,
						closeTimer: 9000
					});

				}, 'json').fail(function(xhr) {

					hideNotification('catalog-identity-verify-running');

					var msg = 'Mitgliedschafts-Pruefung fehlgeschlagen.';
					if (xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.error) {
						msg = xhr.responseJSON.data.error;
						if (xhr.responseJSON.data.error_code) {
							msg += ' (Code: ' + xhr.responseJSON.data.error_code + ')';
						}
						if (xhr.responseJSON.data.xmlrpc_fault_code) {
							msg += ' (XML-RPC Fault: ' + xhr.responseJSON.data.xmlrpc_fault_code + ')';
						}
						if (xhr.responseJSON.data.xmlrpc_http_status) {
							msg += ' (HTTP: ' + xhr.responseJSON.data.xmlrpc_http_status + ')';
						}
						if (xhr.responseJSON.data.xmlrpc_fault_message) {
							msg += ' [' + xhr.responseJSON.data.xmlrpc_fault_message + ']';
						}
						if (xhr.responseJSON.data.identity) {
							templates.renderCatalogIdentityStatus(xhr.responseJSON.data.identity, true);
						}
						if (xhr.responseJSON.data.membership_setup_url) {
							$('#padma-catalog-membership-status')
								.css('color', '#b32d2e')
								.html('Anmeldung erfolgreich, aber keine passende Mitgliedschaft gefunden. <a href="' + xhr.responseJSON.data.membership_setup_url + '" target="_blank" rel="noopener noreferrer">Mitgliedschaft jetzt einrichten</a>.');
						}
					}

					showNotification({
						id: 'catalog-identity-verify-request-error',
						message: msg,
						error: true,
						closable: true,
						closeTimer: 9000
					});
				});

			});

			/* Skin Upload button */
			$('#install-template').on('click', function() {

				if ( $(this).is('[disabled]') )
					return;

				$('#upload-skin input[type="file"]').first().trigger('click');

			});


			/*		Install padma services cloud template		*/
			$('.install-cloud-template').on('click',function(){

				var id 		= this.id.split('-')[1];
				var token 	= $(this).data('token');

				$.post(Padma.apiURL + 'template/get-data', {
					token: 		token,
					id: 		id,
					user_agent: 'padma',					
				}, function(response) {

					try {
						var skin = JSON.parse(response.skin);

						if ( skin && typeof skin.name != 'undefined' && typeof skin['data-type'] != 'undefined' ) {

							/* Check to be sure that the JSON file is a layout */
							if ( skin['data-type'] != 'skin' ){
								return alert('Cannot load template. Please insure that the file is a valid Padma Template.');
							}

							/* Deactivate install template button */
							$('#install-template').attr('disabled', 'true');

							showNotification({
								id: 'installing-skin',
								message: 'Installing Template: ' + skin['name'],
								closeTimer: false,
								closable: false
							});

							var tempSkin = {
								description: null,
								name: 'Installing ' + skin['name'] + '...',
								installing: true,
								id: null,
								author: null,
								active: false,
								version: null
							};

							Padma.viewModels.templates.templates.push(tempSkin);
							Padma.viewModels.templates.myTemplates.push(tempSkin);

							installSkin(skin);

						}

					} catch ( e ) {

						return alert('Cannot load template.  Please insure that the file is a valid Padma Template.');

					}

				});

			});

			$('#upload-skin input[type="file"]').on('change', function(event) {

				var skinFile = $(this).get(0).files[0];

				if ( skinFile && typeof skinFile.name != 'undefined' && typeof skinFile.type != 'undefined' ) {

					var skinReader = new FileReader();

					skinReader.onload = function(e) {

						var skinJSON = e.target.result;

						try {

							var skin = JSON.parse(skinJSON);

							/* Check to be sure that the JSON file is a layout */
							if ( skin['data-type'] != 'skin' )
								return alert('Cannot load template.  Please insure that the file is a valid Padma Template.');

							/* Deactivate install template button */
							$('#install-template').attr('disabled', 'true');

							showNotification({
								id: 'installing-skin',
								message: 'Installing Template: ' + skin['name'],
								closeTimer: false,
								closable: false
							});

							var tempSkin = {
								description: null,
								name: 'Installing ' + skin['name'] + '...',
								installing: true,
								id: null,
								author: null,
								active: false,
								version: null
							};

							Padma.viewModels.templates.templates.push(tempSkin);
							Padma.viewModels.templates.myTemplates.push(tempSkin);

							installSkin(skin);

						} catch ( e ) {

							return alert('Cannot load template.  Please insure that the file is a valid Padma Template.');

						}

					}

					$('#upload-skin input[type="file"]').val('');

					skinReader.readAsText(skinFile);

				} else {

					alert('Cannot load template.  Please insure that the file is a valid Padma Template.');

				}

			});


				installSkin = function(skin) {


					if ( typeof skin['image-definitions'] == 'object' && Object.keys(skin['image-definitions']).length ) {

						var numberOfImages = Object.keys(skin['image-definitions']).length;
						var importedImages = {};

						showNotification({
							id: 'skin-importing-images',
							message: 'Importing Images...',
							closeTimer: false,
							closable: false
						});

						var importSkinImage = function(imageID) {

							/* Update notification for image import */
								var imageIDInt = parseInt(imageID.replace('%%', '').replace('IMAGE_REPLACEMENT_', ''));

								updateNotification('skin-importing-images', 'Importing Image (' + imageIDInt + '/' + numberOfImages + ')');

							/* Do the AJAX request to upload the image */
								var imageImportXhr = $.post(Padma.ajaxURL, {
									security: Padma.security,
									action: 'padma_visual_editor',
									method: 'import_image',
									imageID: imageID,
									imageContents: skin['image-definitions'][imageID]
								}, null, 'json')
									.always(function(response) {

										/* Update notification */

										/* Check if error.  If so, fire notification */
											if ( typeof response['url'] == 'undefined' ) {
												var response = 'ERROR';

												showNotification({
													id: 'skin-importing-images-error-' + imageIDInt,
													message: 'Error Importing Image #' + imageIDInt,
													closeTimer: 10000,
													closable: true,
													error: true
												});
											}

										/* Store uploaded image URL */
											importedImages[imageID] = response;

										/* Check if there are more images to upload.  If so, upload them. */
											var nextImageID = '%%IMAGE_REPLACEMENT_' + (parseInt(imageID.replace('%%', '').replace('IMAGE_REPLACEMENT_', '')) + 1) + '%%';

											if ( typeof skin['image-definitions'][nextImageID] != 'undefined' ) {

												importSkinImage(nextImageID);

										/* If not, finalize skin installation */
											} else {

												/* Hide notification since images are uploaded is complete */
												hideNotification('skin-importing-images');

												/* Finalize */
												skin['imported-images'] = importedImages;

												finalizeSkinInstallation(skin);

											}

									});
							/* End doing AJAX request to upload image */

						}

						importSkinImage('%%IMAGE_REPLACEMENT_1%%');

					} else {

						finalizeSkinInstallation(skin);

					}

				}


					finalizeSkinInstallation = function(skin) {

						/* Remove image definitions from skin array since they've already been imported */
						if ( typeof skin['image-definitions'] != 'undefined' )
							delete skin['image-definitions'];

						/* Do AJAX request to install skin */
						return $.post(Padma.ajaxURL, {
							security: Padma.security,
							action: 'padma_visual_editor',
							method: 'install_skin',
							skin: JSON.stringify(skin)
						}).done(function(data) {

							var skin = data;

							if ( typeof skin['error'] !== 'undefined' || typeof skin['name'] == 'undefined' ) {

								if ( typeof skin['error'] == 'undefined' )
									skin['error'] = 'Could not install template.';

								Padma.viewModels.templates.templates.pop();
								Padma.viewModels.templates.myTemplates.pop();
								$('#install-template').removeAttr('disabled');

								return showNotification({
									id: 'skin-not-installed',
									message: 'Error: ' + skin['error'],
									closable: true,
									closeTimer: false,
									error: true
								});

							}

							hideNotification('installing-skin');

							showNotification({
								id: 'skin-installed',
								message: skin['name'] + ' successfully installed.',
								closeTimer: 5000,
								success: true
							});

							/* Pop off the last skin which is going to be the loader */
							Padma.viewModels.templates.templates.pop();
							Padma.viewModels.templates.myTemplates.pop();

							var installedSkin = $.extend({}, {description: null}, skin);
							Padma.viewModels.templates.templates.push(installedSkin);

							if ( installedSkin.id === 'base' ) {
								Padma.viewModels.templates.standardTemplates.push(installedSkin);
							} else {
								Padma.viewModels.templates.myTemplates.push(installedSkin);
							}

							/* Reactive install template button */
							$('#install-template').removeAttr('disabled');

						}).fail(function(data) {

							showNotification({
								id: 'skin-not-installed',
								message: 'Error: Could not install template.',
								closable: true,
								closeTimer: false,
								error: true
							});

						});

					}

		/* Skin Export */
			$('#export-template-submit').on('click', function(event) {

				event.preventDefault();

				var params = {
					'security': Padma.security,
					'action': 'padma_visual_editor',
					'method': 'export_skin',
					'skin-info': $('#export-template-form').serialize()
				}


				var exportURL = Padma.ajaxURL + '?' + $.param(params);
				return window.open(exportURL);


			});

			/* Export Template Image */
			var BTTemplateExportImageFrame;

			$('#template-export-image-button').on('click', function (event) {

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if (BTTemplateExportImageFrame) {
					BTTemplateExportImageFrame.open();
					return;
				}

				// Create the media frame.
				BTTemplateExportImageFrame = wp.media.frames.file_frame = wp.media({
					title: 'Select Image for Template',
					button: {
						text: 'Select Image',
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				BTTemplateExportImageFrame.on('select', function () {
					attachment = BTTemplateExportImageFrame.state().get('selection').first().toJSON();

					$('input#template-export-image').val(attachment.url);

					$('img#template-export-image-preview')
						.attr('src', attachment.url)
						.show();

				});

				BTTemplateExportImageFrame.open();
			});

		/* Add Blank Skin */
			$('#add-blank-template').on('click', function() {

				var skinName = window.prompt('Please enter a name for the new template:' , 'Template Name');

				if ( !skinName || $('#notification-adding-blank-skin').length )
					return;

				/* Perform AJAX request to create the skin and get the ID and name */
					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_visual_editor',
						method: 'add_blank_skin',
						skinName: skinName
					}, function(response) {

						var skinID = response['id'];
						var skinName = response['name'];

						showNotification({
							id: 'added-blank-skin',
							message: skinName + ' successfully added.',
							closeTimer: 5000,
							success: true
						});

						var newTemplate = {
							id: skinID,
							name: skinName,
							version: null,
							author: null,
							description: null
						};

						Padma.viewModels.templates.templates.push(newTemplate);
						Padma.viewModels.templates.myTemplates.push(newTemplate);

					}, 'json');

			});

		/* Edit Template Meta */
			$('#edit-template-meta-form').on('submit', function(event) {

				event.preventDefault();

				var templateID = $('#edit-template-meta-form').data('template-id');

				if ( !templateID )
					return;

				var metaData = {
					name: $('#edit-template-name').val(),
					author: $('#edit-template-author').val(),
					version: $('#edit-template-version').val(),
					description: $('#edit-template-description').val(),
					'documentation-url': $('#edit-template-doc-url').val(),
					'image-url': $('#edit-template-image').val()
				};

				$.post(Padma.ajaxURL, {
					security: Padma.security,
					action: 'padma_visual_editor',
					method: 'update_template_meta',
					template_id: templateID,
					meta_data: JSON.stringify(metaData)
				}, function(response) {

					if ( response && response.success ) {
						window.location.reload();
					} else {
						showNotification({
							id: 'template-update-error',
							message: (response && response.error) ? response.error : 'Could not update template.',
							closable: true,
							closeTimer: 6000,
							error: true
						});
					}

				}, 'json');

			});

			$('#edit-template-meta-submit').on('click', function(event) {

				event.preventDefault();
				$('#edit-template-meta-form').submit();

			});

		/* Edit Template Image */
			var BTTemplateEditImageFrame;

			$('#edit-template-image-button').on('click', function (event) {

				event.preventDefault();

				if (BTTemplateEditImageFrame) {
					BTTemplateEditImageFrame.open();
					return;
				}

				BTTemplateEditImageFrame = wp.media.frames.file_frame = wp.media({
					title: 'Select Image for Template',
					button: {
						text: 'Select Image',
					},
					multiple: false
				});

				BTTemplateEditImageFrame.on('select', function () {
					attachment = BTTemplateEditImageFrame.state().get('selection').first().toJSON();

					$('input#edit-template-image').val(attachment.url);

					$('img#edit-template-image-preview')
						.attr('src', attachment.url)
						.show();

				});

				BTTemplateEditImageFrame.open();
			});

		}
	}

	templates.init();

});