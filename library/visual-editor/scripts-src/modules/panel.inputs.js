define(['jquery', 'vanilla-draggable', 'helper.codeMirror', 'deps/chosen.jquery', 'deps/colorpicker', 'util.image-uploader', 'util.video-uploader', 'util.audio-uploader', 'util.json-uploader'], function($, vanillaDraggable, codeMirror, chosen, colorpicker, imageUploader, videoUploader, audioUploader, jsonUploader) {

	var veI18n = (typeof Padma !== 'undefined' && Padma.i18nVE) ? Padma.i18nVE : {};
	var t = function(key, fallback) {
		return (typeof veI18n[key] === 'string' && veI18n[key].length) ? veI18n[key] : fallback;
	};

	handleInputTogglesInContainer = function(container) {

		container.each(function() {

			var inputs = $(this).find('[id*="input-"]').get();
			$(inputs.reverse()).each(function() {

				handleInputToggle($(this));

			});

		})

	}


	handleInputToggle = function(input, val) {

		if ( !input || !input.length || typeof input.attr('data-toggle') == 'undefined' )
			return;

		var toggle = $.parseJSON(input.attr('data-toggle'));

		var parentElement = '.panel';

		if ( input.parents('.repeater-group').length )
			var parentElement = '.repeater-group';

		if ( typeof val == 'undefined' )
			var val = input.val().toString();

		if ( input.attr('type') == 'checkbox' ) {

			if ( !input.is(':checked') ) {
				val = 'false';
			} else {
				val = 'true';
			}

		}
		

		if ( val && toggle && typeof toggle == 'object' ) {

			if ( toggle.hasOwnProperty(val) ) {

				/* Show */
					if ( typeof toggle[val].show == 'string' ) {

						var toShow = input.parents(parentElement).find(toggle[val].show);

						toShow.show();

						toShow.find('*[data-toggle]').not(input).each(function() {
							handleInputToggle($(this));
						});

					} else if ( typeof toggle[val].show == 'object' ) {

						$.each(toggle[val].show, function(index, value) {

							var toShow = input.parents(parentElement).find(value).show();

							toShow.show();

							toShow.find('*[data-toggle]').not(input).each(function () {
								handleInputToggle($(this));
							});

						});

					}

				/* Hide */				
					if ( typeof toggle[val].hide == 'string' ) {

						var toHide = input.parents(parentElement).find(toggle[val].hide)

						toHide.find('*[data-toggle]').not(input).each(function() {
							handleInputToggleHideAll($(this));
						});

						toHide.hide();

					} else if ( typeof toggle[val].hide == 'object' ) {
						
						$.each(toggle[val].hide, function(index, value) {

							var toHide = input.parents(parentElement).find(value);

							toHide.find('*[data-toggle]').not(input).each(function() {
								handleInputToggleHideAll($(this));
							});

							toHide.hide();							

						});

					}

			} /* end if toggle.hasOwnProperty(val) */


		}/* end if ( val && toggle && typeof toggle == 'object' ) */

	}


	handleInputToggleHideAll = function(input) {

		if ( !input || !input.length || typeof input.attr('data-toggle') == 'undefined' )
			return;

		var toggle = $.parseJSON(input.attr('data-toggle'));

		var parentElement = '.panel';

		if ( input.parents('.input').parent().attr('class') === 'repeater-group' )
			var parentElement = '.repeater-group';

		$.each(toggle, function(value, hideOrShow) {

			if ( typeof hideOrShow.hide == 'undefined' || !hideOrShow.hide || !hideOrShow.hide.length )
				return;

			if ( typeof hideOrShow.hide == 'string' ) {

				var toHide = input.parents(parentElement).find(hideOrShow.hide);
				toHide.hide();

			} else if ( typeof hideOrShow.hide == 'object' ) {

				$.each(hideOrShow.hide, function(index, value) {

					var toHide = input.parents(parentElement).find(value);
					toHide.hide();

				});

			}

		});

	}

	var panelInputs = {
		delegate: function() {

			var context = 'div#panel';

			/* Selects */	
			$(context).delegate('div.input-select select', 'change', function() {
				
				dataHandleInput($(this));

				var input = $(this);
				var val = $(this).val();
				
				handleInputToggle(input, val);
										
			});

			/*	Sliders	*/
			$(context).delegate('div.input-slider input', 'change', function() {			


				dataHandleInput($(this));

				var input = $(this);
				var val = $(this).val();
				
				handleInputToggle(input, val);

			});

			/* Radios */			
			$(context).delegate('div.input-radio input[type="radio"]', 'change click', function() {				
				dataHandleInput($(this));										
			});

			/* Text */
			$(context).delegate('div.input-text input', 'keyup blur', function() {
				
				dataHandleInput($(this));
						
			});
			
			
			/* Textarea */
				$(context).delegate('div.input-textarea textarea', 'keyup blur', function() {
					
					dataHandleInput($(this));
								
				});
				
				$(context).delegate('div.input-textarea span.textarea-open', 'click', function() {
					
					var textareaContainer = $(this).siblings('.textarea-container');
					var textarea = textareaContainer.find('textarea');
					
					var inputContainerOffset = $(this).parents('.input').offset();
					
					textareaContainer.css({
						top: inputContainerOffset.top - textareaContainer.outerHeight(true),
						left: inputContainerOffset.left
					});
					
					/* Keep the sub tabs content container from scrolling */
					$('div.sub-tabs-content-container').css('overflow-y', 'hidden');

					if ( textareaContainer.data('visible') !== true ) {
					
						/* Show the textarea */
						textareaContainer.show();
						textareaContainer.data('visible', true);
					
						/* Put the cursor in the textarea */
						textarea.trigger('focus');
					
						/* Bind the document close */
						$(document).bind('mousedown', {textareaContainer: textareaContainer}, textareaClose);
						Padma.iframe.contents().bind('mousedown', {textareaContainer: textareaContainer}, textareaClose);
					
						$(window).bind('resize', {textareaContainer: textareaContainer}, textareaClose);
					
					} else {
						
						/* Hide the textarea */
						textareaContainer.hide();
						textareaContainer.data('visible', false);
						
						/* Allow sub tabs content container to scroll again */
						$('div.sub-tabs-content-container').css('overflow-y', 'auto');

						/* Remove the events */
						$(document).unbind('mousedown', textareaClose);
						Padma.iframe.contents().unbind('mousedown', textareaClose);
						
						$(window).unbind('resize', textareaClose);
						
					}
					
				});
				
				textareaClose = function(event) {
									
					/* Do not trigger this if they're clicking the same button that they used to open the textarea */
					if ( $(event.target).parents('div.input-textarea div.input-right').length === 1 )
						return;
					
					var textareaContainer = event.data.textareaContainer;
					
					/* Hide the textarea */
					textareaContainer.hide();
					textareaContainer.data('visible', false);
					
					/* Allow sub tabs content container to scroll again */
					$('div.sub-tabs-content-container').css('overflow-y', 'auto');
					
					/* Remove the events */
					$(document).unbind('mousedown', textareaClose);
					Padma.iframe.contents().unbind('mousedown', textareaClose);
					
					$(window).unbind('resize', textareaClose);
					
				}
			

			/* Code Editor */
				$(context).delegate('div.input-code span.code-editor-open', 'click', function() {

					var codeEditorTextarea = $(this).siblings('textarea');

					codeMirror.showEditor(codeEditorTextarea.attr('id'), $(this).data('editor-mode'), codeEditorTextarea.val(), function(editor) {

						codeEditorTextarea.val(editor.getValue());

						dataHandleInput(codeEditorTextarea);

					});

				});


			/* WYSIWYG */
				inputWYSIWYGChange = function(event) {

					dataHandleInput(this.$element, this.code.get());
					
				}

				inputWYSIWYGTextareaChange = function() {

					dataHandleInput($(this));
					
				}

				$(context).delegate('div.input-wysiwyg span.wysiwyg-open', 'click', function() {

					var wysiwygContainer = $(this).siblings('.wysiwyg-container');
					
					var inputContainerOffset = $(this).parents('.input').offset();
					var inputContainerTop = inputContainerOffset.top - wysiwygContainer.outerHeight(true);

					if ( inputContainerTop < 50 ) {
						inputContainerTop = 50;
					}
					
					wysiwygContainer.css({
						top: inputContainerTop,
						left: inputContainerOffset.left
					});
					
					/* Keep the sub tabs content container from scrolling */
					$('div.sub-tabs-content-container').css('overflow-y', 'hidden');

					if ( wysiwygContainer.data('visible') !== true ) {

						/* Show the WYSWIWYG */
						wysiwygContainer.show();
						wysiwygContainer.css('marginLeft', '');
						wysiwygContainer.data('visible', true);

						/* Make sure WYSIWYG doesn't bleed off screen */
							if ( $('div#side-panel-container').length ) {
								var sidePanelRightRaw = $('div#side-panel-container').css('right');
								if ( typeof sidePanelRightRaw !== 'string' ) {
									sidePanelRightRaw = '0';
								}

								var sidePanelRight = parseInt(sidePanelRightRaw.replace('px', ''), 10);
								if ( isNaN(sidePanelRight) ) {
									sidePanelRight = 0;
								}

								var sidePanelWidth = $('div#side-panel-container').outerWidth() - sidePanelRight;
							} else {
								var sidePanelWidth = 0;
							}

							var possibleRightBleedingDifference = $(document).width() - sidePanelWidth - (wysiwygContainer.offset().left + wysiwygContainer.width());

							if ( possibleRightBleedingDifference < 0 ) {
								wysiwygContainer.css('marginLeft', possibleRightBleedingDifference - 30);
							}

							var setupCKEditor = function() {
								
                                var textArea = wysiwygContainer.find('textarea')[0];
                                var editor = CKEDITOR.replace( textArea, {
                                    /*extraPlugins: 'imageuploader',*/
                                    toolbarGroups: [
                                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                                            { name: 'forms', groups: [ 'forms' ] },
                                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                                            { name: 'links', groups: [ 'links' ] },
                                            { name: 'insert', groups: [ 'insert' ] },
                                            { name: 'styles', groups: [ 'styles' ] },
                                            { name: 'colors', groups: [ 'colors' ] },
                                            { name: 'tools', groups: [ 'tools' ] },
                                            { name: 'others', groups: [ 'others' ] },
                                            { name: 'about', groups: [ 'about' ] }
                                    ],

                                    removeButtons: 'Save,Preview,NewPage,Print,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,RemoveFormat,BidiLtr,BidiRtl,Language,Flash,Styles,Font,Maximize,About'
                                });

                                editor.on('change', function() {
                                   editor.updateElement();
                                   dataHandleInput(textArea)
                                });

								wysiwygContainer.data('setupCKEditor', true);

								wysiwygContainer.resizable({
									start: showIframeOverlay,
									stop: hideIframeOverlay
								});

							}

						/* Load ckeditor if it hasn't been before */
							require(['deps/ckeditor/ckeditor'], function() {

								if ( wysiwygContainer.data('setupCKEditor') !== true ) {
									setupCKEditor();
								}

							});

						/* Bind the document close */
							$(document).bind('mousedown', {wysiwygContainer: wysiwygContainer}, wysiwygClose);
							Padma.iframe.contents().bind('mousedown', {wysiwygContainer: wysiwygContainer}, wysiwygClose);

							$(window).bind('resize', {wysiwygContainer: wysiwygContainer}, wysiwygClose);

					} else {
						
						/* Hide the WYSIWYG */
						wysiwygContainer.hide();
						wysiwygContainer.data('visible', false);

						/* Allow sub tabs content container to scroll again */
						$('div.sub-tabs-content-container').css('overflow-y', 'auto');

						/* Remove the events */
						$(document).unbind('mousedown', wysiwygClose);
						Padma.iframe.contents().unbind('mousedown', wysiwygClose);
						
						$(window).unbind('resize', wysiwygClose);
						
					}


				});


				wysiwygClose = function(event) {
									
					/* Do not trigger this if they're clicking the same button that they used to open the textarea */
					if ( 
						$(event.target).parents('div.input-wysiwyg div.input-right').length === 1 
						|| $(event.target).parents('.redactor-dropdown').length === 1
						|| $(event.target).parents('#redactor-modal-box').length === 1
					)
						return;
					
					var wysiwygContainer = event.data.wysiwygContainer;
					
					/* Hide the WYSIWYG */
					wysiwygContainer.hide();
					wysiwygContainer.data('visible', false);
					
					/* Allow sub tabs content container to scroll again */
					$('div.sub-tabs-content-container').css('overflow-y', 'auto');
					
					/* Remove the events */
					$(document).unbind('mousedown', wysiwygClose);
					Padma.iframe.contents().unbind('mousedown', wysiwygClose);
					
					$(window).unbind('resize', wysiwygClose);
					
				}

			
			/* Integer */
			$(context).delegate('div.input-integer input', 'focus', function() {
				
				if ( typeof originalValues !== 'undefined' ) {
					originalValues = undefined;
				}
				
				originalValues = new Object;		
				originalValues[$(this).attr('name')] = $(this).val();
				
			});
			
			$(context).delegate('div.input-integer input', 'keyup blur', function(event) {
				
				value = $(this).val();
				
				if ( event.type == 'keyup' && value == '-' )
					return;
				
				/* Validate the value and make sure it's a number */
				if ( isNaN(value) ) {

					/* Take the nasties out to make sure it's a number */
					value = value.replace(/[^0-9]*/ig, '');

					/* If the value is an empty string, then revert back to the original value */
					if ( value === '' ) {

						var value = originalValues[$(this).attr('name')];

					}
					
					/* Set the value of the input to the sanitized value */
					$(this).val(value);

				}

				/* Remove leading zeroes */
				if ( value.length > 1 && value[0] == 0 ) {

					value = value.replace(/^[0]+/g, '');
					
					/* Set the value of the input to the sanitized value */
					$(this).val(value);

				}
				
				dataHandleInput($(this), value);
						
			});
			
			
			/* Checkboxes */
			$(context).delegate('div.input-checkbox input', 'change', function(event) {
				
				var inputContainer = $(this).parents('.input-checkbox').first();
				var input = inputContainer.find('input');
				var label = inputContainer.find('label');

				var val = input.is(':checked');

				Padma.history.add({
					up: function() {

						input.val(val);
						input.prop('checked', val);

						dataHandleInput(input, val);
						handleInputToggle(input, val);

						input.trigger('blur');

					},
					down: function() {

						var val = !val;

						input.val(val);
						input.prop('checked', val);

						dataHandleInput(input, val);
						handleInputToggle(input, val);

						input.trigger('blur');

					}
				})
				
				allowSaving();

				event.preventDefault();
				event.stopPropagation();

				return false;
				
			});


			/* Multi-select */
			$(context).delegate('div.input-multi-select select', 'click', function() {

				dataHandleInput($(this));
									
			});
			
			$(context).delegate('div.input-multi-select span.multi-select-open', 'click', function() {
				
				var multiSelectContainer = $(this).siblings('.multi-select-container');
				var multiSelect = multiSelectContainer.find('select');
				
				var inputContainerOffset = $(this).parents('.input').offset();
				
				multiSelectContainer.css({
					top: inputContainerOffset.top - multiSelectContainer.outerHeight(true),
					left: inputContainerOffset.left
				});
				
				/* Keep the sub tabs content container from scrolling */
				$('div.sub-tabs-content-container').css('overflow-y', 'hidden');
				
				if ( multiSelectContainer.data('visible') !== true ) {
				
					/* Show the multi-select */
					multiSelectContainer.show();
					multiSelectContainer.data('visible', true);
				
					/* Bind the document close */
					$(document).bind('mousedown', {multiSelectContainer: multiSelectContainer}, multiSelectClose);
					Padma.iframe.contents().bind('mousedown', {multiSelectContainer: multiSelectContainer}, multiSelectClose);
					
					$(window).bind('resize', {multiSelectContainer: multiSelectContainer}, multiSelectClose);
				
				} else {
					
					/* Hide the multi-select */
					multiSelectContainer.hide();
					multiSelectContainer.data('visible', false);
					
					/* Allow sub tabs content container to scroll again */
					$('div.sub-tabs-content-container').css('overflow-y', 'auto');

					/* Remove the events */
					$(document).unbind('mousedown', multiSelectClose);
					Padma.iframe.contents().unbind('mousedown', multiSelectClose);
					
					$(window).unbind('resize', multiSelectClose);
					
				}
				
			});
			
			multiSelectClose = function(event) {
						
				/* Do not trigger this if they're clicking the same button that they used to open the multi-select */
				if ( $(event.target).parents('div.input-multi-select div.input-right').length === 1 )
					return;
				
				var multiSelectContainer = event.data.multiSelectContainer;
				
				/* Hide the multi-select */
				multiSelectContainer.hide();
				multiSelectContainer.data('visible', false);
				
				/* Allow sub tabs content container to scroll again */
				$('div.sub-tabs-content-container').css('overflow-y', 'auto');
				
				/* Remove the events */
				$(document).unbind('mousedown', multiSelectClose);
				Padma.iframe.contents().unbind('mousedown', multiSelectClose);
				
				$(window).unbind('resize', multiSelectClose);
				
			}
			

			/* Image Uploaders */
			$(context).delegate('div.input-image span.button', 'click', function() {
				
				var self = this;
				
				openImageUploader(function(url, filename) {
								
					$(self).siblings('input').val(url);
					$(self).siblings('span.src').show().text(filename);
					$(self).siblings('span.delete-image').show();

					dataHandleInput($(self).siblings('input'), url, {action: 'add'});	
					
				});

			});


			$(context).delegate('div.input-image span.delete-image', 'click', function() {

				if ( !confirm(t('confirmRemoveImage', 'Bist du sicher, dass du dieses Bild entfernen willst?')) ) {
					return false;
				}

				$(this).siblings('.src').hide();
				$(this).hide();

				$(this).siblings('input').val('');

				dataHandleInput($(this).siblings('input'), '', {action: 'delete'});

			});


			/* Audio Uploaders */
			$(context).delegate('div.input-audio span.button', 'click', function() {
				
				var self = this;
				
				openAudioUploader(function(url, filename) {
								
					$(self).siblings('input').val(url);
					$(self).siblings('span.src').show().text(filename);
					$(self).siblings('span.delete-audio').show();

					dataHandleInput($(self).siblings('input'), url, {action: 'add'});	
					
				});

			});
			
			$(context).delegate('div.input-audio span.delete-audio', 'click', function() {

				if ( !confirm(t('confirmRemoveAudio', 'Bist du sicher, dass du dieses Audio entfernen willst?')) ) {
					return false;
				}

				$(this).siblings('.src').hide();
				$(this).hide();

				$(this).siblings('input').val('');

				dataHandleInput($(this).siblings('input'), '', {action: 'delete'});

			});

			/* Video Uploaders */
			$(context).delegate('div.input-video span.button', 'click', function() {
				
				var self = this;
				
				openVideoUploader(function(url, filename) {
								
					$(self).siblings('input').val(url);
					$(self).siblings('span.src').show().text(filename);
					$(self).siblings('span.delete-video').show();

					dataHandleInput($(self).siblings('input'), url, {action: 'add'});	
					
				});

			});
			
			$(context).delegate('div.input-video span.delete-video', 'click', function() {

				if ( !confirm(t('confirmRemoveVideo', 'Bist du sicher, dass du dieses Video entfernen willst?')) ) {
					return false;
				}

				$(this).siblings('.src').hide();
				$(this).hide();

				$(this).siblings('input').val('');

				dataHandleInput($(this).siblings('input'), '', {action: 'delete'});

			});


			/* Repeaters */
				updateRepeaterValues = function(repeater) {

					var values = {};

					repeater.find('div.repeater-group:visible').each(function(index) {

						var groupValues = {};

						$(this).find('select, input, textarea').each(function() {

							var value = $(this).val();

							if ( $(this).is('[type="checkbox"]') && !$(this).is(':checked') ) {
								value = false;
							}

							groupValues[$(this).attr('name')] = value;

						});

						values[index] = groupValues;

					});

					return dataHandleInput(repeater.find('input.repeater-group-input'), values);	

				}

				$(context).delegate('div.repeater .add-group', 'click', function() {
					
					var repeater = $(this).parents('div.repeater');
					var group = $(this).parents('div.repeater-group');
					var groupTemplate = repeater.find('.repeater-group-template');

					/* If the limit is met then don't add a new group */
						if ( repeater.hasClass('limit-met') )
							return;

					/* Clone repeater template */
						var newGroup = groupTemplate.clone().hide().removeClass('repeater-group-template');
						/*					
						var nextCounter = repeater.find('.repeater-group:not(.repeater-group-template)').length + 1;
						
						// update input's attrs
						newGroup.find('textarea').each(function(){
							
							// id
							var input_id = $(this).attr('id') + '-' + nextCounter;
							$(this).attr('id',input_id);

							// name
							var input_name = $(this).attr('name') + '-' + nextCounter;
							$(this).attr('name',input_name);

						});*/
						

						newGroup.insertAfter(group).fadeIn(300);

					/* Remove group single class since there's no longer one group */
						repeater.find('.repeater-group-single').removeClass('repeater-group-single');

					/* Add limit-met class if necessary */
						var repeaterLimit = repeater.data('repeater-limit');

						if ( !isNaN(repeaterLimit) && repeaterLimit >= 1 && repeater.find('div.repeater-group:not(.repeater-group-template):visible').length == repeaterLimit )
							repeater.addClass('limit-met');

					updateRepeaterValues(repeater);
					
				});

				$(context).delegate('div.repeater .remove-group', 'click', function() {

					if ( !confirm('Bist du sicher?') )
						return;
					
					var repeater = $(this).parents('div.repeater');
					var group = $(this).parents('div.repeater-group');
					
					/* Fade out that way history can revert it.  The updatePanelHidden will be based off of if the group is :visible or not */
						group.fadeOut(300, function() {

							/* if there's only one group left, then add the repeater group single class */
								if ( repeater.find('div.repeater-group:visible').length === 1 )
									repeater.find('div.repeater-group:visible').addClass('repeater-group-single');

							/* Remove limit-met class if necessary */
								var repeaterLimit = repeater.data('repeater-limit');

								if ( !isNaN(repeaterLimit) && repeaterLimit >= 1 && repeater.find('div.repeater-group:not(.repeater-group-template):visible').length < repeaterLimit )
									repeater.removeClass('limit-met');

							updateRepeaterValues(repeater);

						});
					
				});


			/* Color Inputs */
			$(context).delegate('div.input-colorpicker div.colorpicker-box', 'click', function() {

				/* Keep the sub tabs content container from scrolling */
				$('div.sub-tabs-content-container').css('overflow-y', 'hidden');	

				/* Set up variables */
				var input = $(this).parent().siblings('input');
				var inputVal = input.val();

				if ( inputVal == 'transparent' )
					inputVal = '00FFFFFF';

				var colorpickerHandleVal = function(color, inst) {

					var colorValue = '#' + color.hex;

					/* If alpha ISN'T 100% then use RGBa */
					if ( color.a != 100 )
						var colorValue = color.rgba;

					input.val(colorValue);
					dataHandleInput(input, colorValue);

					/* Call developer-defined callback */
						var callback = eval(input.attr('data-callback'));

						if ( typeof callback == 'function' ) {

							callback({
								input: input,
								value: color.rgba,
								colorObj: color
							});

						}
					/* End Callback */
				
				}

				$(this).colorpicker({
					realtime: true,
					alpha: true,
					alphaHex: true,
					allowNull: false,
					swatches: (typeof Padma.colorpickerSwatches == 'object' && Padma.colorpickerSwatches.length) ? Padma.colorpickerSwatches : true,
					color: inputVal,
					showAnim: false,
					beforeShow: function(input, inst) {

						/* Add iframe overlay */
						showIframeOverlay();

					},
					onClose: function(color, inst) {

						colorpickerHandleVal(color, inst);

						/* Hide iframe overlay */
						hideIframeOverlay();

						/* Allow sub tabs content container to scroll again */
						$('div.sub-tabs-content-container').css('overflow-y', 'auto');

					},
					onSelect: function(color, inst) {

						colorpickerHandleVal(color, inst);

					},
					onAddSwatch: function(color, swatches) {

						dataSetOption('general', 'colorpicker-swatches', swatches);

					},
					onDeleteSwatch: function(color, swatches) {

						dataSetOption('general', 'colorpicker-swatches', swatches);

					}
				});

				$.colorpicker._showColorpicker($(this));

				setupTooltips();
								
			});


			/* Buttons */
				$(context).delegate('div.input-button span.button', 'click', function() {

					dataHandleInput($(this));

				});


			/* Import Files */
				$(context).delegate('div.input-import-file span.button', 'click', function() {
					
					$(this).siblings('input[type="file"]').trigger('click');
					
				});

				$(context).delegate('div.input-import-file input[type="file"]', 'change', function(event) {
					
					if ( event.target.files[0].name.split('.').slice(-1)[0] != 'json' ) {

						$(this).val(null);
						return alert('Ungueltige Padma-Importdatei. Bitte stelle sicher, dass die Padma-Importdatei eine gueltig formatierte JSON-Datei ist.');

					}

					$(this).siblings('span.src').show().text($(this).val().split(/(\\|\/)/g).pop());
					$(this).siblings('span.delete-file').show();

					dataHandleInput($(this));
					
				});

				$(context).delegate('div.input-import-file .delete-file', 'click', function() {
					
					if ( !confirm('Bist du sicher?') )
						return;

					$(this).fadeOut(100);
					$(this).siblings('span.src').fadeOut(100);

					var fileInput = $(this).siblings('input[type="file"]');
					var callback = eval(fileInput.attr('data-callback'));

					fileInput.val(null);

					dataHandleInput(fileInput);
					
				});


			/* JSON input Files */
				$(context).delegate('div.input-json span.button', 'click', function() {
					
					var self = this;
					
					openJsonUploader(function(url, filename) {
									
						$(self).siblings('input').val(url);
						$(self).siblings('span.src').show().text(filename);
						$(self).siblings('span.delete-json').show();

						dataHandleInput($(self).siblings('input'), url, {action: 'add'});	
						
					});

				});
				
				$(context).delegate('div.input-json span.delete-json', 'click', function() {

					if ( !confirm(t('confirmRemoveJson', 'Bist du sicher, dass du diese JSON-Datei entfernen willst?')) ) {
						return false;
					}

					$(this).siblings('.src').hide();
					$(this).hide();

					$(this).siblings('input').val('');

					dataHandleInput($(this).siblings('input'), '', {action: 'delete'});

				});				

		},

		bind: function(contenxt) {

			if ( typeof context === 'undefined' )
				var context = 'div#panel';

			/* Sliders - Vanilla implementation */
				$('div.input-slider div.input-slider-bar', context).each(function() {
					
					var sliderBar = $(this);
					var sliderContainer = $(this).parents('.input-slider');

					var value = parseInt(sliderContainer.find('input.input-slider-bar-hidden').val());
					var min = parseInt(sliderBar.attr('slider_min'));
					var max = parseInt(sliderBar.attr('slider_max'));
					var interval = parseInt(sliderBar.attr('slider_interval'));

					var sliderInput = sliderBar.siblings('div.input-slider-bar-text').find('.input-slider-bar-input');
					var hiddenInput = sliderContainer.find('input.input-slider-bar-hidden');

					var handleSliderChange = function(newValue) {
						Padma.history.add({
							up: function() {
								sliderInput.val(newValue);
								sliderInput.prev().text(newValue);
								hiddenInput.val(newValue);
								updateSliderPosition(newValue);
								dataHandleInput(hiddenInput, newValue);
							},
							down: function() {
								var originalValue = hiddenInput.data('value-original');
								sliderInput.val(originalValue);
								sliderInput.prev().text(originalValue);
								hiddenInput.val(originalValue);
								updateSliderPosition(originalValue);
								dataHandleInput(hiddenInput, originalValue);
							}
						});
					};

					var updateSliderPosition = function(val) {
						var percent = ((val - min) / (max - min)) * 100;
						sliderBar.css('background-size', percent + '% 100%');
						sliderBar.css('--slider-percent', percent + '%');
						sliderBar.attr('data-slider-value', val);
						value = val;
					};

					// Initialize slider position
					updateSliderPosition(value);

					// Mouse events for clicking on the bar
					sliderBar.on('click', function(e) {
						var rect = this.getBoundingClientRect();
						var percent = (e.clientX - rect.left) / rect.width;
						var newValue = Math.round(min + percent * (max - min) / interval) * interval;
						newValue = Math.max(min, Math.min(max, newValue));

						hiddenInput.data('value-original', newValue);
						sliderInput.val(newValue);
						sliderInput.prev().text(newValue);
						hiddenInput.val(newValue);
						updateSliderPosition(newValue);
						dataHandleInput(hiddenInput, newValue);
						handleSliderChange(newValue);
					});

					// Mouse drag events
					var isDragging = false;
					sliderBar.on('mousedown', function(e) {
						isDragging = true;
						hiddenInput.data('value-original', value);

						var moveHandler = function(moveEvent) {
							if (!isDragging) return;

							var rect = sliderBar[0].getBoundingClientRect();
							var clientX = moveEvent.type.includes('touch') ? moveEvent.touches[0].clientX : moveEvent.clientX;
							var percent = (clientX - rect.left) / rect.width;
							var newValue = Math.round(min + percent * (max - min) / interval) * interval;
							newValue = Math.max(min, Math.min(max, newValue));

							sliderInput.val(newValue);
							sliderInput.prev().text(newValue);
							hiddenInput.val(newValue);
							updateSliderPosition(newValue);
							dataHandleInput(hiddenInput, newValue);
							handleInputToggle(hiddenInput, newValue);
						};

						var endHandler = function() {
							isDragging = false;
							$(document).off('mousemove', moveHandler);
							$(document).off('mouseup', endHandler);
							document.removeEventListener('touchmove', moveHandler);
							document.removeEventListener('touchend', endHandler);
							
							var finalValue = parseInt(hiddenInput.val());
							handleSliderChange(finalValue);
						};

						$(document).on('mousemove', moveHandler);
						$(document).on('mouseup', endHandler);
						document.addEventListener('touchmove', moveHandler, { passive: false });
						document.addEventListener('touchend', endHandler, { passive: true });
					});

					// Handle touchstart separately for passive: false
					if (sliderBar[0] && sliderBar[0].addEventListener) {
						sliderBar[0].addEventListener('touchstart', function(e) {
							isDragging = true;
							hiddenInput.data('value-original', value);

							var moveHandler = function(moveEvent) {
								if (!isDragging) return;

								var rect = sliderBar[0].getBoundingClientRect();
								var clientX = moveEvent.type.includes('touch') ? moveEvent.touches[0].clientX : moveEvent.clientX;
								var percent = (clientX - rect.left) / rect.width;
								var newValue = Math.round(min + percent * (max - min) / interval) * interval;
								newValue = Math.max(min, Math.min(max, newValue));

								sliderInput.val(newValue);
								sliderInput.prev().text(newValue);
								hiddenInput.val(newValue);
								updateSliderPosition(newValue);
								dataHandleInput(hiddenInput, newValue);
								handleInputToggle(hiddenInput, newValue);
							};

							var endHandler = function() {
								isDragging = false;
								$(document).off('mousemove', moveHandler);
								$(document).off('mouseup', endHandler);
								document.removeEventListener('touchmove', moveHandler);
								document.removeEventListener('touchend', endHandler);
								
								var finalValue = parseInt(hiddenInput.val());
								handleSliderChange(finalValue);
							};

							document.addEventListener('touchmove', moveHandler, { passive: false });
							document.addEventListener('touchend', endHandler, { passive: true });
							e.preventDefault();
						}, { passive: false });
					}

					sliderInput.on('keydown', function(event) {

						var key = event.charCode || event.keyCode || 0;
						// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
						// home, end, period, and numpad decimal
						            
						return (
				                key == 8 || /* Backspace */
				                key == 9 ||
				                key == 46 || /* Delete */
				                key == 110 ||
				                //key == 190 || /* Period */
				                (key >= 35 && key <= 40) ||
				                (key >= 48 && key <= 57) ||
				                (key >= 96 && key <= 105));

					});

					sliderInput.on('focus', function(event) {
						hiddenInput.data('value-original', $(this).val());
					});

					sliderInput.on('keyup change', function(event) {

						/* Don't fire this if the enter key is pressed */
						if ( event.which === 13 )
							return;
						
						var newValue = parseInt(this.value);
						if ( newValue <= max && newValue >= min) {

							sliderInput.prev().text(newValue);
							hiddenInput.val(newValue);
							updateSliderPosition(newValue);
							dataHandleInput(hiddenInput, newValue);
							handleSliderChange(newValue);

						}

					});

					sliderInput.on('blur', function(event) {

						var value = this.value;

						if( this.value > max ) {
							value = max;
						} else if( this.value < min ) {
							value = min;
						}

						handleSliderChange(this, value);

					});
					
				});

			/* Chosen Selects */
				$('.select-chosen select', context).chosen({
					width: '200px'
				});

				$('.select-chosen select', context).on('chosen:showing_dropdown', function(evt, params) {

					var $drop = $(params.chosen.dropdown);
					var dropRect = $drop.get(0).getBoundingClientRect();
					var diff = $(window).height() - dropRect.bottom;
					var $results = $drop.find('.chosen-results');

					$drop.css({
						height: '',
						'max-height': ''
					});

					$results.css({
						height: '',
						'max-height': ''
					});

					if ( diff < 0 ) {

						var maxDropHeight = Math.max(120, dropRect.height - Math.abs(diff) - 10);
						var maxResultsHeight = Math.max(80, maxDropHeight - 10);

						$drop.css('max-height', maxDropHeight + 'px');
						$results.css('max-height', maxResultsHeight + 'px');

					}

				});

			/* Repeaters */
				/* Repeater Sortables */
					$('.repeater-sortable', context).sortable({
						items: '.repeater-group',
						containment: 'parent',
						forcePlaceholderSize: true,
						handle: '.sortable-handle',
						stop: function() {
							updateRepeaterValues($(this));
						}
					});

				/* Repeater Limits */
					$('.repeater', context).each(function() {

						var repeaterLimit = $(this).data('repeater-limit');

						if ( !isNaN(repeaterLimit) && repeaterLimit >= 1 && $(this).find('div.repeater-group:not(.repeater-group-template):visible').length >= repeaterLimit )
							$(this).addClass('limit-met');

					});


			/* Tab Index */
				var tabindex = 1;
			    $('input,select,textarea').each(function() {
			        if (this.type != "hidden") {
			            var $input = $(this);
			            $input.attr("tabindex", tabindex);
			            tabindex++;
			        }
			    });
			/* End Tab Index */
		}

	}

	return panelInputs;

});