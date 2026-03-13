define(['jquery', 'util.tooltips', 'helper.boxes', 'modules/panel'], function($, tooltips, panel) {

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
			title: 'Willkommen beim PS Padma Visual Editor!',
			content: '<p>Wenn dies Dein erster Besuch im PS Padma Visual Editor ist, <strong>empfehlen wir, diese Tour zu folgen, um das Beste aus PS Padma herauszuholen</strong>.</p><p>Oder, wenn Du erfahren bist oder sofort loslegen möchtest, klicke einfach jederzeit auf die Schaltfläche zum Schließen oben rechts.</p>'
		},

		{
			target: $('li#mode-grid'),
			title: 'Modus-Auswahl',
			content: '<p>Der PS Padma Visual Editor ist in 2 Modi unterteilt.</p><p><ul><li><strong>Grid</strong> &ndash; Erstelle Deine Layouts</li><li><strong>Design</strong> &ndash; Farben hinzufügen, Schriftarten anpassen und mehr!</li></ul></p>',
			position: {
				my: 'top left',
				at: 'bottom center'
			}
		},

		{
			target: $('#layout-selector-select-content'),
			title: 'Layout-Auswahl',
			content: '<p style="font-size:12px;">Da Du möglicherweise nicht möchtest, dass jede Seite gleich ist, kannst Du den Layout-Selector verwenden, um auszuwählen, welche Seite, welchen Beitrag oder welches Archiv Du bearbeiten möchtest.</p><p style="font-size:12px;">Der Layout-Selector basiert auf Vererbung. Zum Beispiel kannst Du das Layout "Seite" anpassen und alle Seiten folgen diesem Layout. Außerdem kannst Du eine bestimmte Seite anpassen, und sie wird sich von allen anderen Seiten unterscheiden.</p><p style="font-size:12px;">Der Layout-Selector ermöglicht es Dir, so präzise oder breit zu sein, wie Du möchtest. Es liegt ganz bei Dir!</p>',
			position: {
				my: 'top center',
				at: 'bottom center'
			}
		},

		{
			target: $('div#box-grid-manager'),
			title: 'Das PS Padma Grid',
			content: '<p>Jetzt sind wir bereit, mit dem PS Padma Grid zu beginnen. Mit anderen Worten, der gute Teil.</p><p>Um Dein erstes Layout zu erstellen, wähle bitte ein Preset rechts aus, um das Grid vorab zu füllen. Oder Du kannst "Leeres Grid verwenden" auswählen, um mit einem komplett leeren Grid zu beginnen.</p><p>Sobald Du ein Preset ausgewählt hast, klicke auf "Fertigstellen".</p>',
			position: {
				my: 'right top',
				at: 'left center'
			},
			nextHandler: {
				showButton: false,
				clickElement: '#grid-manager-button-preset-use-preset, span.grid-manager-use-empty-grid',
				message: 'Bitte klicke auf <strong>"Fertigstellen"</strong> oder <strong>"Leeres Grid verwenden"</strong>, um fortzufahren.'
			}
		},

		{
			iframeTarget: 'div.grid-container',
			title: 'Blöcke hinzufügen',
			content: '<p>Um einen Block hinzuzufügen, platziere einfach Deine Maus in das Grid und klicke an die Stelle, an der der obere linke Punkt des Blocks sein soll.</p><p>Ziehe Deine Maus und der Block wird erscheinen! Sobald der Block erscheint, kannst Du den Blocktyp auswählen.</p><p>Tipp: Keine Sorge, wenn Du nicht genau bist, Du kannst den Block jederzeit verschieben oder die Größe ändern.</p>',
			position: {
				my: 'right top',
				at: 'left top',
				adjustY: 100
			},
			maxWidth: 280
		},

		{
			iframeTarget: 'div.grid-container',
			title: 'Blöcke bearbeiten',
			content: '\
					<p style="font-size:12px;">Nachdem Du die gewünschten Blöcke zu Deinem Layout hinzugefügt hast, kannst Du den Block jederzeit verschieben, die Größe ändern, löschen oder die Optionen des Blocks ändern.</p>\
					<ul style="font-size:12px;">\
						<li><strong>Blöcke verschieben</strong> &ndash; Klicke und ziehe den Block.  Wenn Du mehrere Blöcke gleichzeitig verschieben möchtest, doppelklicke auf einen Block, um den <em>Massenblock-Auswahlmodus</em> zu aktivieren.</li>\
						<li><strong>Blöcke skalieren</strong> &ndash; Greife den Rand oder die Ecke des Blocks und ziehe Deine Maus.</li>\
						<li><strong>Block-Optionen (z.B. Header-Bild)</strong> &ndash; Fahre mit der Maus über den Block und klicke dann auf das Symbol für Block-Optionen oben rechts.</li>\
						<li><strong>Blöcke löschen</strong> &ndash; Bewege Deine Maus über den gewünschten Block und klicke dann auf das <em>X</em>-Symbol oben rechts.</li>\
					</ul>',
			position: {
				my: 'right top',
				at: 'left top',
				adjustY: 100
			},
			maxWidth: 280
		},

		{
			target: $('#save-button-container'),
			title: 'Speichern',
			content: '<p>Jetzt, da Du hoffentlich einige Änderungen vorgenommen hast, kannst Du diese mit diesem schicken Speichern-Button speichern.</p><p>Für diejenigen unter Euch, die Tastenkombinationen mögen, verwendet <strong>Strg + S</strong>, um zu speichern.</p>',
			position: {
				my: 'top right',
				at: 'bottom center'
			},
			tip: 'top right'
		},

		{
			target: $('li#mode-design a'),
			title: 'Design Modus',
			content: '<p>Danke, dass Du bei uns geblieben bist!</p><p>Jetzt, da Du ein Verständnis für den Grid-Modus hast, hoffen wir, dass Du bei uns bleibst und zum Design-Modus wechselst.</p>',
			position: {
				my: 'top left',
				at: 'bottom center',
				adjustY: 5
			},
			tip: 'top left',
			buttonText: 'Zum Design-Modus wechseln',
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
			title: 'Willkommen im Padma Design Editor!',
			content: "<p>Im <strong>Design Editor</strong> kannst Du Deine Elemente nach Belieben gestalten.</p><p>Ob Schriftarten, Farben, Abstände, Rahmen, Schatten oder abgerundete Ecken, Du kannst den Design Editor verwenden.</p><p>Bleib dran, um mehr zu erfahren!</p>"
		},

		{
			target: '#side-panel-top',
			title: 'Elementauswahl',
			content: '<p>Der Elementauswahl ermöglicht es Dir, auszuwählen, welches Element Du bearbeiten möchtest.</p>',
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
			title: 'Inspector',
			content: "\
					<p>Anstatt den <em>Elementauswahl</em> zu verwenden, lass den Inspector die Arbeit für Dich erledigen.</p>\
					<p><strong>Probier es aus!</strong> Zeige mit der Maus auf das Element, das Du bearbeiten möchtest, und klicke mit der rechten Maustaste darauf, um es auszuwählen!</p>\
				",
			position: {
				my: 'top right',
				at: 'bottom center',
				adjustX: 10,
				adjustY: 5
			}
		},

		{
			target: 'window',
			title: 'Viel Spaß beim Bauen mit PS Padma ContentBuilder!',
			content: '<p>Wir hoffen, dass Du PS Padma ContentBuilder als das leistungsstärkste und benutzerfreundlichste ClassicPress-Framework empfindest.</p><p>Wenn Du Fragen hast, zögere bitte nicht, die <a href="https://psource.eimen.net/wiki/die-ps-padma-contentbuilder-community/" target="_blank">Community</a> zu besuchen.</p>',
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
					text: steps[0].content + '<div id="tour-next-container"><span id="tour-next" class="tour-button button button-blue">Tour fortsetzen <span class="arrow">&rsaquo;</span></span></div>',
					title: {
						text: steps[0].title, // ...and title
						button: 'Tour überspringen'
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
							var buttonText = 'Next';

							if ( typeof currentTourStep.buttonText == 'string' )
								var buttonText = currentTourStep.buttonText;

							var hasNextHandlerTarget = false;

							if ( typeof currentTourStep.nextHandler !== 'undefined' && typeof currentTourStep.nextHandler.clickElement == 'string' ) {
								hasNextHandlerTarget = $(currentTourStep.nextHandler.clickElement).length > 0;
							}

							if ( typeof currentTourStep.end !== 'undefined' && currentTourStep.end === true ) {
								var button = '<div id="tour-next-container"><span id="tour-finish" class="tour-button button button-blue">Tour schließen <span class="arrow">&rsaquo;</span></div>';
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