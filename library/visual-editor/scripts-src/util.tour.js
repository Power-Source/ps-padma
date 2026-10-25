define(['jquery', 'util.tooltips', 'helper.boxes', 'modules/panel'], function($, tooltips, panel) {
	var veI18n = (typeof Padma !== 'undefined' && Padma.i18nVE) ? Padma.i18nVE : {};
	var t = function(key, fallback) {
		return (typeof veI18n[key] === 'string' && veI18n[key].length) ? veI18n[key] : fallback;
	};

	var resolveStepTarget = function(step) {

		if ( !step )
			return $(window);

		if ( step.target == 'window' )
			return $(window);

		if ( typeof step.target == 'string' ) {
			var domTarget = $(step.target).first();
			return domTarget.length ? domTarget : $(window);
		}

		if ( step.target && step.target.jquery )
			return step.target.length ? step.target.first() : $(window);

		if ( typeof step.iframeTarget == 'string' && typeof $i == 'function' ) {
			var iframeTarget = $i(step.iframeTarget).first();
			return iframeTarget.length ? iframeTarget : $(window);
		}

		return $(window);

	};

	var canStartTourForMode = function() {

		if ( Padma.mode == 'grid' ) {
			return !!(
				$('li#mode-grid').length
				&& $('#layout-selector-select-content').length
				&& $('div#box-grid-manager').length
			);
		}

		if ( Padma.mode == 'design' ) {
			return !!(
				$('#side-panel-top').length
				&& $('#toggle-inspector').length
			);
		}

		return false;

	};

	/* Grid */
	tourStepsGrid = [
		{
			beginning: true,
			title: t('tourWelcomeTitle', 'Willkommen beim PS Padma Visual Editor!'),
			content: t('tourWelcomeContent', '<p>Wenn du den PS Padma Visual Editor zum ersten Mal besuchst, <strong>empfehlen wir dir, diese Tour zu machen, um PS Padma optimal zu nutzen</strong>.</p><p>Wenn du bereits Erfahrung hast oder sofort loslegen möchtest, klicke jederzeit einfach auf den Schließen-Button oben rechts.</p>')
		},

		{
			target: $('li#mode-grid'),
			title: t('tourModeTitle', 'Modus-Auswahl'),
			content: t('tourModeContent', '<p>Der PS Padma Visual Editor ist in zwei Modi unterteilt.</p><p><ul><li><strong>Grid</strong> &ndash; Erstelle deine Layouts</li><li><strong>Design</strong> &ndash; Füge Farben hinzu, passe Schriftarten an und vieles mehr!</li></ul></p>'),
			position: {
				my: 'top left',
				at: 'bottom center'
			}
		},

		{
			target: $('#layout-selector-select-content'),
			title: t('tourLayoutTitle', 'Layout-Auswahl'),
			content: t('tourLayoutContent', '<p>Verwende die Layout-Auswahl, um die Seite, den Beitrag oder das Archiv auszuwählen, die bzw. den du bearbeiten möchtest. Layouts erben Einstellungen, sodass du umfassende Layouts oder einzelne Seiten anpassen kannst.</p>'),
			position: {
				my: 'top center',
				at: 'bottom center'
			}
		},

		{
			target: $('div#box-grid-manager'),
			title: t('tourGridTitle', 'Das PS Padma Grid'),
			content: t('tourGridContent', '<p>Um dein erstes Layout zu erstellen, wähle rechts eine Vorlage, um das Grid vorab zu füllen, oder wähle „Leeres Grid verwenden“, um mit einem leeren Grid zu beginnen. Wenn du eine Vorlage ausgewählt hast, klicke auf „Fertigstellen“.</p>'),
			position: {
				my: 'right top',
				at: 'left center'
			},
			nextHandler: {
				showButton: false,
				clickElement: '#grid-manager-button-preset-use-preset, span.grid-manager-use-empty-grid',
				message: t('tourGridContinue', 'Klicke zum Fortfahren auf <strong>„Fertigstellen“</strong> oder <strong>„Leeres Grid verwenden“</strong>.')
			}
		},

		{
			iframeTarget: 'div.grid-container',
			title: t('tourAddBlocksTitle', 'Blöcke hinzufügen'),
			content: t('tourAddBlocksContent', '<p>Um einen Block hinzuzufügen, klicke und ziehe im Grid. Wenn der Block erscheint, wähle seinen Typ aus. Du kannst ihn jederzeit verschieben oder seine Größe ändern.</p>'),
			position: {
				my: 'right top',
				at: 'left top',
				adjustY: 100
			},
			maxWidth: 280
		},

		{
			iframeTarget: 'div.grid-container',
			title: t('tourEditBlocksTitle', 'Blöcke bearbeiten'),
			content: t('tourEditBlocksContent', '<p>Nachdem du Blöcke hinzugefügt hast, kannst du sie jederzeit verschieben, ihre Größe ändern, sie löschen oder ihre Optionen ändern.</p>'),
			position: {
				my: 'right top',
				at: 'left top',
				adjustY: 100
			},
			maxWidth: 280
		},

		{
			target: $('#save-button-container'),
			title: t('tourSpeichernTitle', 'Speichern'),
			content: t('tourSpeichernContent', '<p>Speichere deine Änderungen mit diesem Button. Du kannst auch <strong>Strg + S</strong> drücken.</p>'),
			position: {
				my: 'top right',
				at: 'bottom center'
			},
			tip: 'top right'
		},

		{
			target: $('li#mode-design a'),
			title: t('tourDesignModeTitle', 'Design-Modus'),
			content: t('tourSwitchDesignContent', '<p>Danke, dass du dabei geblieben bist! Jetzt, da du den Grid-Modus verstanden hast, wechsle zum Design-Modus, um fortzufahren.</p>'),
			position: {
				my: 'top left',
				at: 'bottom center',
				adjustY: 5
			},
			tip: 'top left',
			buttonText: t('tourSwitchDesign', 'Zum Design-Modus wechseln'),
			buttonCallback: function () {

				$.post(Padma.ajaxURL, {
					security: Padma.security,
					action: 'padma_visual_editor',
					method: 'ran_tour',
					mode: 'grid',
					complete: function () {

						Padma.ranTour['grid'] = true;

						/* Advance to Design Editor */
						$('li#mode-design a').trigger('click');
						window.location = $('li#mode-design a').attr('href');

					}
				});

			}
		}
	];

	/* Design */
	tourStepsDesign = [
		{
			beginning: true,
			title: t('tourDesignWelcomeTitle', 'Willkommen beim Padma Design Editor!'),
			content: t('tourDesignWelcomeContent', '<p>Im <strong>Design Editor</strong> kannst du deine Elemente ganz nach deinen Wünschen gestalten: Schriftarten, Farben, Abstände, Rahmen, Schatten und abgerundete Ecken.</p>')
		},

		{
			target: '#side-panel-top',
			title: t('tourElementSelectorTitle', 'Element-Auswahl'),
			content: t('tourElementSelectorContent', '<p>Mit der Element-Auswahl kannst du das Element auswählen, das du bearbeiten möchtest.</p>'),
			position: {
				my: 'right top',
				at: 'left center'
			},
			callback: function () {
				$('li#element-block-header > span.element-name').trigger('click');
			}
		},

		{
			target: '#toggle-inspector',
			title: t('tourInspectorTitle', 'Inspektor'),
			content: t('tourInspectorContent', '<p>Statt die <em>Element-Auswahl</em> zu verwenden, lass den Inspektor die Arbeit für dich erledigen.</p><p><strong>Probiere es aus!</strong> Zeige auf das Element, das du bearbeiten möchtest, und klicke mit der rechten Maustaste darauf, um es auszuwählen.</p>'),
			position: {
				my: 'top right',
				at: 'bottom center',
				adjustX: 10,
				adjustY: 5
			}
		},

		{
			target: 'window',
			title: t('tourFinalTitle', 'Viel Spaß beim Erstellen mit PS Padma ContentBuilder!'),
			content: t('tourFinalContent', '<p>Wir hoffen, dass du PS Padma ContentBuilder als leistungsstarkes und einfach zu bedienendes ClassicPress-Framework empfindest.</p><p>Wenn du Fragen hast, besuche bitte die <a href="https://psource.eimen.net/wiki/die-ps-padma-contentbuilder-community/" target="_blank">Community</a>.</p>'),
			end: true
		}
	];

	return {
		startWhenReady: function(maxAttempts, delayMs) {

			var self = this;

			var attempts = typeof maxAttempts == 'number' ? maxAttempts : 40;
			var delay = typeof delayMs == 'number' ? delayMs : 250;

			if ( canStartTourForMode() ) {
				return self.start();
			}

			var retries = 0;
			var waitInterval = setInterval(function() {
				retries++;

				if ( canStartTourForMode() ) {
					clearInterval(waitInterval);
					if ( !$('.qtip-tour').length )
						self.start();
					return;
				}

				if ( retries >= attempts ) {
					clearInterval(waitInterval);
				}
			}, delay);

		},

		start: function () {

			if ( $('.qtip-tour').length )
				return false;

			if ( Padma.mode == 'grid' ) {

				var steps = tourStepsGrid;

				hidePanel();
				openBox('grid-manager');

			} else if ( Padma.mode == 'design' ) {

				var steps = tourStepsDesign;

				showPanel();

				require(['modules/design/mode-design'], function() {
					showDesignEditor();
				});

				if ( typeof $('div#panel').data('ui-tabs') != 'undefined' )
					selectTab('editor-tab', $('div#panel'));

			} else {

				return;

			}

			$('<div class="black-overlay"></div>')
				.hide()
				.attr('id', 'black-overlay-tour')
				.css('zIndex', 15)
				.appendTo('body')
				.fadeIn(500);

			$(document.body).qtip({
				id: 'tour', // Give it an ID of qtip-tour so we an identify it easily
				content: {
					text: steps[0].content + '<div id="tour-next-container"><span id="tour-next" class="tour-button button button-blue">' + t('tourContinue', 'Tour fortsetzen') + ' <span class="arrow">&rsaquo;</span></span></div>',
					title: {
						text: steps[0].title, // ...and title
						button: t('tourSkip', 'Tour überspringen')
					}
				},
				style: {
					classes: 'qtip-tour',
					tip: {
						width: 18,
						height: 10,
						mimic: 'center',
						offset: 10
					}
				},
				position: {
					my: 'center',
					at: 'center',
					target: $(window), // Also use first steps position target...
					viewport: $(window), // ...and make sure it stays on-screen if possible
					adjust: {
						y: 5,
						method: 'shift shift'
					}
				},
				show: {
					event: false, // Only show when show() is called manually
					ready: true, // Also show on page load,
					effect: function () {
						$(this).fadeIn(500);
					}
				},
				hide: false, // Don't hide unless we call hide()
				events: {
					render: function (event, api) {

						$('#iframe-notice').remove();
						hideIframeOverlay();

						openBox('grid-manager');

						// Grab tooltip element
						var tooltip = api.elements.tooltip;

						// Track the current step in the API
						api.step = 0;

						// Bind custom custom events we can fire to step forward/back
						tooltip.bind('next', function (event) {

							/* For some reason trigger window resizing helps tooltip positioning */
							$(window).trigger('resize');

							// Increase/decrease step depending on the event fired
							api.step += 1;
							api.step = Math.min(steps.length - 1, Math.max(0, api.step));

							// Set new step properties
							currentTourStep = steps[api.step];

							$('div#black-overlay-tour').fadeOut(100, function () {
								$(this).remove();
							});

							//Run the callback if it exists
							if ( typeof currentTourStep.callback === 'function' ) {
								currentTourStep.callback.apply(api);
							}

							currentTourStep.target = resolveStepTarget(currentTourStep);

							api.set('position.target', currentTourStep.target);

							if ( typeof currentTourStep.maxWidth !== 'undefined' && window.innerWidth < 1440 ) {
								$('.qtip-tour').css('maxWidth', currentTourStep.maxWidth);
							} else {
								$('.qtip-tour').css('maxWidth', 350);
							}

							/* Set up button */
							var buttonText = t('tourNext', 'Weiter');

							if ( typeof currentTourStep.buttonText == 'string' )
								var buttonText = currentTourStep.buttonText;

							var hasNextHandlerTarget = false;

							if ( typeof currentTourStep.nextHandler !== 'undefined' && typeof currentTourStep.nextHandler.clickElement == 'string' ) {
								hasNextHandlerTarget = $(currentTourStep.nextHandler.clickElement).length > 0;
							}

							if ( typeof currentTourStep.end !== 'undefined' && currentTourStep.end === true ) {
								var button = '<div id="tour-next-container"><span id="tour-finish" class="tour-button button button-blue">' + t('tourSchließen', 'Tour schließen') + ' <span class="arrow">&rsaquo;</span></div>';
							} else if ( typeof currentTourStep.nextHandler === 'undefined' || currentTourStep.nextHandler.showButton || !hasNextHandlerTarget ) {
								var button = '<div id="tour-next-container"><span id="tour-next" class="tour-button button button-blue">' + buttonText + ' <span class="arrow">&rsaquo;</span></div>';
							} else {
								var button = '<div id="tour-next-container"><p>' + currentTourStep.nextHandler.message + '</p></div>';
							}

							/* Next Handler Callback... Be able to use something other than the button */
							if ( typeof currentTourStep.nextHandler !== 'undefined' && hasNextHandlerTarget ) {

								var nextHandlerCallback = function (event) {

									$('.qtip-tour').triggerHandler('next');
									event.preventDefault();

									$(this).unbind('click', nextHandlerCallback);

								}

								$(currentTourStep.nextHandler.clickElement).on('click', nextHandlerCallback);

							}

							/* Set the Content */
							api.set('content.text', currentTourStep.content + button);
							api.set('content.title', currentTourStep.title);

							if ( typeof currentTourStep.end === 'undefined' ) {

								/* Position */
								if ( typeof currentTourStep.position !== 'undefined' ) {

									api.set('position.my', currentTourStep.position.my);
									api.set('position.at', currentTourStep.position.at);

									/* Offset/Adjust */
									if ( typeof currentTourStep.position.adjustX !== 'undefined' ) {
										api.set('position.adjust.x', currentTourStep.position.adjustX);
									} else {
										api.set('position.adjust.x', 0);
									}

									if ( typeof currentTourStep.position.adjustY !== 'undefined' ) {
										api.set('position.adjust.y', currentTourStep.position.adjustY);
									} else {
										api.set('position.adjust.y', 0);
									}

								} else {

									api.set('position.my', 'top center');
									api.set('position.at', 'bottom center');

								}

								if ( typeof currentTourStep.tip !== 'undefined' )
									api.set('style.tip.corner', currentTourStep.tip);

							} else {

								api.set('position.my', 'center');
								api.set('position.at', 'center');

							}


						});

						/* Tour Button Bindings */
						$('div.qtip-tour').on('click', 'span#tour-next', function (event) {

							/* Callback that fires upon button click... Used for advancing to Design Editor */
							if ( typeof currentTourStep == 'object' && typeof currentTourStep.buttonCallback == 'function' )
								currentTourStep.buttonCallback.call();

							$('.qtip-tour').triggerHandler('next');
							event.preventDefault();

						});

						$('div.qtip-tour').on('click', 'span#tour-finish', function (event) {

							$('.qtip-tour').qtip('hide');

						});


					},

					// Destroy the tooltip after it hides as its no longer needed
					hide: function (event, api) {

						$('div#tour-overlay').remove();

						$('div#black-overlay-tour').fadeOut(100, function () {
							$(this).remove();
						});

						$('.qtip-tour').fadeOut(100, function () {
							$(this).qtip('destroy');
						});

						//Tell the DB that the tour has been ran
						if ( Padma.ranTour[Padma.mode] == false && Padma.ranTour.legacy != true ) {

							$.post(Padma.ajaxURL, {
								security: Padma.security,
								action: 'padma_visual_editor',
								method: 'ran_tour',
								mode: Padma.mode
							});

							Padma.ranTour[Padma.mode] = true;

						}

					}
				}
			});

		}
	}

});