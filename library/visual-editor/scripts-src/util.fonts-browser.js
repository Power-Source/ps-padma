(function($) {

/*!
jQuery quicksearch 
*/
(function($,window,document,undefined){$.fn.quicksearch=function(target,opt){var timeout,cache,rowcache,jq_results,val="",e=this,options=$.extend({delay:100,selector:null,stripeRows:null,loader:null,noResults:"",matchedResultsCount:0,bind:"keyup",onBefore:function(){return},onAfter:function(){return},show:function(){this.style.display=""},hide:function(){this.style.display="none"},prepareQuery:function(val){return val.toLowerCase().split(" ")},testQuery:function(query,txt,_row){for(var i=0;i<query.length;i+=
1)if(txt.indexOf(query[i])===-1)return false;return true}},opt);this.go=function(){var i=0,numMatchedRows=0,noresults=true,query=options.prepareQuery(val),val_empty=val.replace(" ","").length===0;for(var i=0,len=rowcache.length;i<len;i++)if(val_empty||options.testQuery(query,cache[i],rowcache[i])){options.show.apply(rowcache[i]);noresults=false;numMatchedRows++}else options.hide.apply(rowcache[i]);if(noresults)this.results(false);else{this.results(true);this.stripe()}this.matchedResultsCount=numMatchedRows;
this.loader(false);options.onAfter();return this};this.search=function(submittedVal){val=submittedVal;e.trigger()};this.currentMatchedResults=function(){return this.matchedResultsCount};this.stripe=function(){if(typeof options.stripeRows==="object"&&options.stripeRows!==null){var joined=options.stripeRows.join(" ");var stripeRows_length=options.stripeRows.length;jq_results.not(":hidden").each(function(i){$(this).removeClass(joined).addClass(options.stripeRows[i%stripeRows_length])})}return this};
this.strip_html=function(input){var output=input.replace(new RegExp("<[^<]+>","g"),"");output=$.trim(output.toLowerCase());return output};this.results=function(bool){if(typeof options.noResults==="string"&&options.noResults!=="")if(bool)$(options.noResults).hide();else $(options.noResults).show();return this};this.loader=function(bool){if(typeof options.loader==="string"&&options.loader!=="")bool?$(options.loader).show():$(options.loader).hide();return this};this.cache=function(){jq_results=$(target);
if(typeof options.noResults==="string"&&options.noResults!=="")jq_results=jq_results.not(options.noResults);var t=typeof options.selector==="string"?jq_results.find(options.selector):$(target).not(options.noResults);cache=t.map(function(){return e.strip_html(this.innerHTML)});rowcache=jq_results.map(function(){return this});val=val||this.val()||"";return true};this.trigger=function(){this.loader(true);options.onBefore();window.clearTimeout(timeout);timeout=window.setTimeout(function(){e.go()},
options.delay);return this};this.cache();this.results(true);this.stripe();this.loader(false);return this.each(function(){$(this).bind(options.bind,function(){val=$(this).val();e.trigger()})})}})(jQuery,this,document);



/* Fonts input object */
	function fontBrowserObj(browser) {

		if ( typeof _ != 'function' ) {
			_ = Padma._;
		}

		this.browser = browser;

		this.propertyInput = browser.parents('.property-font-family-select');

		this.hiddenInput = this.propertyInput.find('input.property-hidden-input');

		this.setup = function() {

			var self = this;

			this.browser.find('.tab-content').each(function() {

				var fontsList = $(this).find('.fonts-list ul');

				var scrollWebFontLoaderDebounced = _.debounce(function() {
					self.scrollWebFontLoader(fontsList);
				}, 100);

				fontsList.bind('scroll', scrollWebFontLoaderDebounced);

				self.initQuickSearch($(this));
				self.initPreview($(this));
				self.initSorting($(this));

				
				fontsList.delegate('.use-font', 'click', function() {

					var li 					= $(this).parents('li').first();

					/* Determine value to save to DB */
					var webfontProvider 	= $(this).parents('.tab-content').data('font-webfont-provider');
					var fontID 				= li.data('value');

					var fontName 			= $(this).siblings('.font-family').text();
					var fontFamily 			= fontID;
				var fontVariants 		= li.data('variants');
				
				// Normalize variants - handle both array and string formats
				var normalizedVariants = [];
				if (_.isArray(fontVariants)) {
					normalizedVariants = fontVariants;
				} else if (_.isString(fontVariants)) {
					var variantStr = $.trim(fontVariants);
					if (variantStr.charAt(0) === '[' && variantStr.charAt(variantStr.length - 1) === ']') {
						variantStr = variantStr.substring(1, variantStr.length - 1);
					}
					normalizedVariants = _.chain(variantStr.split(','))
						.map(function(item) { return $.trim(item.replace(/^['\"]|['\"]$/g, '')); })
						.filter(function(item) { return item.length > 0; })
						.value();
				}
				
				var variantsStr = '';
				if ( normalizedVariants && normalizedVariants.length > 0 && _.indexOf(normalizedVariants, 'regular') === -1 )
					variantsStr = '|' + normalizedVariants.join(',');

				var value = webfontProvider != false ? webfontProvider + '|' + fontID + ':' + normalizedVariants.join(',') : fontID;

					/*	Change iframe	*/
					var selector = $(self.propertyInput.find('input.property-hidden-input')[0]).attr('element_selector');
					var item = $i(selector);


					item.css('font-family', fontFamily);
					
					/* Change selected font */
					self.browser.find('.selected-font').removeClass('selected-font');
					li.addClass('selected-font');

					/* Close font panel */
					fontBrowserClose({
						data: {
							fontBrowser: self.browser
						}
					});
					
					/* Save value */
					dataHandleDesignEditorInput({hiddenInput: self.hiddenInput, value: value, stack: fontFamily});

				});

			});

			// Check if jQuery UI tabs widget is available before calling
			if (typeof this.browser.tabs === 'function') {
				this.browser.tabs({
					selected: 0,
					activate: function(event, ui) {

						var $newPanel = $(ui.newPanel);

						if ( $newPanel.data('fonts-loaded') )
							return;

						self.retrieveRemoteFonts($newPanel, 'popularity', true, true);

					}
				});
			}

			this.changeToSelectedFontProviderTab();

		}

		this.retrieveRemoteFonts = function(context, sortBy, resetTransient, firstLoad) {

			if ( !context.data('font-load-with-ajax') )
				return;

			var self = this;
			var cacheKey = 'padma_fonts_cache_' + context.data('font-webfont-provider') + '_' + (sortBy || 'popularity');
			var cachedFonts = localStorage.getItem(cacheKey);

			// Check for cached fonts (valid for 1 hour)
			if (cachedFonts && !resetTransient) {
				var cacheData = JSON.parse(cachedFonts);
				if (cacheData.timestamp && (Date.now() - cacheData.timestamp) < 3600000) { // 1 hour
					self.renderFontsList(context, cacheData.html, firstLoad);
					return;
				}
			}

			createCog(context.find('.fonts-loading'), true);

			context.find('.fonts-list ul').fadeOut(300);
			context.find('.fonts-loading').fadeIn(300);

			/* Lock search until it has finished loading */
			context.find('.fonts-filter').attr('disabled', 'disabled');

			$.post(Padma.ajaxURL, {
				security: Padma.security,
				action: 'padma_visual_editor',
				method: 'fonts_list',
				sortby: sortBy,
				provider: context.data('font-webfont-provider')
			}, function(response) {

				// Cache the response in localStorage
				try {
					localStorage.setItem(cacheKey, JSON.stringify({
						html: response,
						timestamp: Date.now()
					}));
				} catch (e) {
					// localStorage might be full or disabled, continue anyway
				}

				self.renderFontsList(context, response, firstLoad);

			});

		};

		this.renderFontsList = function(context, response, firstLoad) {

			var self = this;

			context.find('.fonts-loading').fadeOut(300);
			context.find('ul').hide().html(response).fadeIn(300, function() {

				/* Force fonts to load before user scrolls */
				self.scrollWebFontLoader(context.find('ul'));

			});

			/* Refresh quick search cache */
			context.find('.fonts-filter').val('');
			context.data('quicksearch').cache();

			/* Allow quick search again */
			context.find('.fonts-filter').removeAttr('disabled');

			/* Scroll to selected item if first time loading tab otherwise scroll to top */
			if ( typeof firstLoad != 'undefined' && firstLoad && self.hiddenInput.val().match(/\|/g) ) {

				var selectedFont = context.find('li[data-value="' + self.hiddenInput.val().split('|')[1] + '"]');

				if ( selectedFont.length ) {

					selectedFont.addClass('selected-font');
					context.find('.fonts-list ul').scrollTop(selectedFont.position().top);

				}

			} else {

				context.find('.fonts-list ul').scrollTop(0);

			}

			context.data('fonts-loaded', true);

		};

		this.scrollWebFontLoader = function(fontList) {

			var fontListContainer = $(fontList.parents('.tab-content').get(0));
			var fontListProvider = fontListContainer.data('font-webfont-provider');

			if ( fontList.parents('.font-provider-tab-content.ui-tabs-hide').length || !fontListProvider )
				return;

			var fontsToLoad = [];

			var normalizeVariants = function(variants) {
				var normalized = [];
				
				// Handle various input formats
				if (_.isArray(variants)) {
					normalized = variants;
				} else if (_.isString(variants)) {
					var trimmed = $.trim(variants);

					if (!trimmed.length) {
						return [];
					}

					// Remove brackets if present (from JSON encoding)
					if (trimmed.charAt(0) === '[' && trimmed.charAt(trimmed.length - 1) === ']') {
						trimmed = trimmed.substring(1, trimmed.length - 1);
					}

					// Split by comma and clean up each item
					normalized = _.chain(trimmed.split(','))
						.map(function(item) { 
							return $.trim(item.replace(/^['\"]|['\"]$/g, ''));
						})
						.filter(function(item) { return item.length > 0; })
						.value();
				}
				
				// Normalize all variants to standard format
				return _.map(normalized, mapVariant);
			};

			var mapVariant = function(variantName) {
				variantName = String(variantName).toLowerCase().trim();
				
				// Normalize italic variants to short format
				variantName = variantName.replace(/(\d+)italic/g, '$1i');
				variantName = variantName.replace(/^italic$/, '400i');
				
				// Normalize "regular" to "400"
				variantName = variantName.replace(/^regular$/, '400');
				
				// Normalize "normal" to "400" as well
				variantName = variantName.replace(/^normal$/, '400');

				return variantName;
			};

			/* Load visible fonts + 20 fonts buffer for smooth scrolling */
				var viewportTop = fontList.scrollTop();
				var viewportBottom = viewportTop + fontList.outerHeight();
				var buffer = 1000; // Load fonts 1000px above and below viewport

				var allFonts = fontList.find('li');
				var loadCount = 0;
				var maxInitialLoad = Math.min(50, allFonts.length); // Load first 50 fonts or all if less

				allFonts.each(function() {

					var fontTop = $(this).position().top + fontList.scrollTop();
					var fontBottom = fontTop + $(this).outerHeight();

					if ( $(this).data('loadedFont') )
						return;

					// For initial load, load first N fonts; for scroll, load visible + buffer
					if ( loadCount < maxInitialLoad || (fontTop <= (viewportBottom + buffer) && fontBottom >= (viewportTop - buffer)) ) {
						fontsToLoad.push($(this));
						loadCount++;
					}

				});

			/* Load fonts via WebFont API */
				if ( fontsToLoad.length ) {

					var familyParts = [];

					_.each(fontsToLoad, function(fontNode) {
						fontNode = $(fontNode);
						fontNode.data('loadedFont', true);

						var fontName = String(fontNode.data('value') || '').replace(/\s+/g, '+');
						if (!fontName.length) {
							return;
						}

						var variants = normalizeVariants(fontNode.data('variants'));
						var variantsQueryString = _.chain(variants)
							.map(mapVariant)
							.filter(function(item) { return item.length > 0; })
							.value()
							.join(',');

						familyParts.push(fontName + (variantsQueryString ? ':' + variantsQueryString : ''));
					});

					if (familyParts.length) {
						// Batch fonts to avoid oversized URLs (max ~2000 chars per request)
						var basePath = 'https://eimen.net/fonts/css.php?display=swap&family=';
						var maxUrlLength = 2048;
						var fontBatches = [];
						var currentBatch = [];
						var currentLength = basePath.length;

						_.each(familyParts, function(fontPart) {
							var encodedPart = encodeURIComponent(fontPart);
							var separator = currentBatch.length > 0 ? '%7C' : ''; // %7C = |
							var addedLength = separator.length + encodedPart.length;

							if (currentLength + addedLength > maxUrlLength && currentBatch.length > 0) {
								// Start a new batch
								fontBatches.push(currentBatch);
								currentBatch = [fontPart];
								currentLength = basePath.length + encodedPart.length;
							} else {
								currentBatch.push(fontPart);
								currentLength += addedLength;
							}
						});

						if (currentBatch.length > 0) {
							fontBatches.push(currentBatch);
						}

						// Load each batch
						_.each(fontBatches, function(batch, batchIndex) {
							var batchFamilyString = batch.join('|');
							$('<link>')
								.attr('type', 'text/css')
								.attr('rel', 'stylesheet')
								.attr('href', basePath + encodeURIComponent(batchFamilyString))
								.appendTo('head')
								.on('load', function() {

									// On last batch, show all fonts
									if (batchIndex === fontBatches.length - 1) {
										_.each(fontsToLoad, function(fontNode) {
											fontNode = $(fontNode);
											fontNode.find('span.font-family, span.font-preview-text').show().css('opacity', 1);
										});
									}

								})
								.on('error', function() {
									console.error('Font batch ' + (batchIndex + 1) + ' failed to load');
									// Still try to show fonts even if CSS load fails
									if (batchIndex === fontBatches.length - 1) {
										_.each(fontsToLoad, function(fontNode) {
											fontNode = $(fontNode);
											fontNode.find('span.font-family, span.font-preview-text').show().css('opacity', 1);
										});
									}
								});
						});
					}

				}

		}

		this.initQuickSearch = function(context) {

			var id = context.attr('id');

			var quicksearch = context.find('.fonts-filter').quicksearch('#' + id + ' .fonts-list ul li', {
				delay: 750,
				noResults: '#' + id + ' .fonts-list .fonts-noresults',
				loader: '#' + id + ' .fonts-list .fonts-loading',
				bind: 'keyup',
				onBefore: function() {
					context.find('.fonts-list ul').fadeOut(100);
				},
				onAfter: function() {
					/* Force fonts to be loaded */
					context.find('.fonts-list ul')
						.trigger('scroll')
						.fadeIn(100);
				},
				prepareQuery: function (val) {
				    return new RegExp(val, "i");
				},
			    testQuery: function (query, txt, _row) {
			        return query.test(jQuery.trim(txt.replace('the quick brown fox jumps over the lazy dog.', '')));
			    }
			});


			/* Attach quicksearch object to element that way the cache can be refreshed */
			context.data('quicksearch', quicksearch);

		}

		this.initPreview = function(context) {

			var self = this;

			/* fonts preview overlay */
			previewHtml = $('<div class="font-preview-overlay" style="display:none;">' +
					'<span class="close-preview"></span>' +
					'<header>' +
						'<h4></h4>' +
						'<p><i class="icon-edit">&nbsp;</i><strong>klicke irgendwo</strong> in den Vorschautext, um ihn zu bearbeiten und deinen eigenen Text einzufuegen</p>' +
					'</header>' +
					'<div class="editable allow-backspace-key" contenteditable="true"></div>' +
					'<footer>' +
						'<div class="tools">' +
							'<span title="Vorschautext zuruecksetzen" class="reset-preview"></span>' +
							'<span title="Vorschaugroesse verkleinern" class="size-down"></span>' +
							'<span title="Vorschaugroesse vergroessern" class="size-up"></span>' +
							'<span title="Diese Schriftart verwenden" class="use-font"></span>' +
						'</div>' +
					'</footer>' +
				'</div>');

			context.find('.fonts-list').after(previewHtml);

		    /* preview functions */
		    this.defaultPreviewText = 'Franz jagt im komplett verwahrlosten Taxi quer durch Bayern.';
		    this.defaultPreviewSize = '24px';

		    this.previewResize = function(preview, resizeBy) {

		    	var editable = preview.find('.editable');

				var originalSize = editable.css('font-size');
				var newSize = parseFloat(originalSize, 10) * resizeBy;

				editable.css('font-size', newSize);

				localStorage.fontPreviewSize = editable.css('font-size');

			}
		    
		    this.previewLoadFromStorage = function(preview) {

		    	var editable = preview.find('.editable');

		    	/* set preview text */
		    	if ( !localStorage.getItem('fontPreviewText') ) {
				 	editable.html(self.defaultPreviewText);
				} else {
					editable.html(localStorage.fontPreviewText);
				}

				/* set font size */
				if ( localStorage.getItem('fontPreviewSize') ) {
					editable.css('font-size', localStorage.fontPreviewSize);
				}

		    }

		    this.previewSaveText = function() {

		    	localStorage.fontPreviewText = $(this).text();

			}

			this.previewReset = function(preview) {

				preview.find('.editable').html(self.defaultPreviewText);
				preview.find('.editable').css('font-size', self.defaultPreviewSize);

				localStorage.fontPreviewText = self.defaultPreviewText;
				localStorage.fontPreviewSize = self.defaultPreviewSize;

			}

			/* Bind the preview buttons to the preview can be opened */
			context.find('.fonts-list ul').delegate('li .preview-font', 'click', function() {

				var fontID = $(this).parents('li').data('value');
				var fontFamily = '"' + $(this).parent().find('.font-family').text().trim() + '"';
				var fontPreview = $(this).parents('.fonts-list').siblings('.font-preview-overlay');

				fontPreview.data('font-value', fontID);
				fontPreview.data('font-name', $(this).parent().find('.font-family').text());
				fontPreview.data('font-variants', $(this).parents('li').data('variants'));

				fontPreview.fadeIn(750);
				fontPreview.css('font-family', fontFamily);
				fontPreview.find('h4').html($(this).parent().find('.font-family').text() + ' <span>(Vorschau)</span>');

				self.previewLoadFromStorage(fontPreview);

			});

			/* increase */
			context.find('.font-preview-overlay .size-up').on('click', function() {
				self.previewResize($(this).parents('.font-preview-overlay'), 1.1);
			});

			/* decrease */
			context.find('.font-preview-overlay .size-down').on('click', function() {
				self.previewResize($(this).parents('.font-preview-overlay'), 0.9);
			});

			/* reset preview text */
			context.find('.font-preview-overlay .reset-preview').on('click', function() {
		    	self.previewReset($(this).parents('.font-preview-overlay'));
		    });

		    /* close preview */
		    context.find('.font-preview-overlay .close-preview').on('click', function() {
		    	$(this).parents('.font-preview-overlay').fadeOut(750);
		    });

		    /* save changes to local storage */
			context.find('.font-preview-overlay .editable').on('blur', this.previewSaveText);

			/* bind use font button */
			context.find('.font-preview-overlay .use-font').on('click', function() {

				/* Determine value to save to DB */
				var webfontProvider = $(this).parents('.tab-content').data('font-webfont-provider');
				var fontID 			= $(this).parents('.font-preview-overlay').data('font-value');
				var fontName 		= $(this).parents('.font-preview-overlay').data('font-name');
				var fontFamily 		= $(this).parents('.font-preview-overlay').css('font-family');
				var variants 		= $(this).parents('.font-preview-overlay').data('font-variants');

				var variantsStr 	= '';

				if ( variants && variants.indexOf('regular') === -1 )
					variantsStr = '|' + variants.join(',');

				var value = webfontProvider != false ? webfontProvider + '|' + fontID + variantsStr : fontID;

				/* Change readout */
				var fontNameReadout = self.propertyInput.find('.font-name');

				fontNameReadout.css('font-family', fontFamily);
				fontNameReadout.text(fontName);

				/* Change selected font */
				self.browser.find('.selected-font').removeClass('selected-font');
				self.browser.find('li[data-value="' + fontID + '"]').addClass('selected-font');

				/* Close font panel */
				fontBrowserClose({
					data: {
						fontBrowser: self.browser
					}
				});

				/* Save value */
				dataHandleDesignEditorInput({hiddenInput: self.hiddenInput, value: value, stack: fontFamily});

			});

		}

		this.initSorting = function(context) {

			var self = this;

			context.find('.fonts-search select').on('change', function() {

				$('.fonts-noresults').hide();
				var sortBy = $(this).val();
				self.retrieveRemoteFonts($(this).parents('.tab-content'), sortBy, true);
		        
			});

		}

		this.changeToSelectedFontProviderTab = function() {

			var value = this.hiddenInput.val();

			/* Traditional font  */
			if ( !value || !value.match(/\|/g) ) {

				var tab = this.browser.find('#traditional-fonts');
				var selectedFont = tab.find('li[data-value="' + value + '"]');

				if ( selectedFont.length ) {

					selectedFont.addClass('selected-font');

					/* For some reason the selectedFont element isn't visible immediately so the position is wrong thus the use of timeout */
					setTimeout(function() {
						tab.find('.fonts-list ul').scrollTop(selectedFont.position().top);
					}, 100);

				}

			/* Web Font */
			} else {

				var fragments = value.split('|');

				selectTab(+ fragments[0] + '-fonts', this.browser);

			}

		}

	}
/* End fonts input object */

/* Opening and closing */
	fontBrowserOpen = function(event) {

		var fontBrowser = $(this).siblings('.font-browser');	
		var inputContainerOffset = $(this).parents('.design-editor-property-font-family').offset();
		
		fontBrowser.css({
			top: inputContainerOffset.top - fontBrowser.outerHeight(true),
			left: inputContainerOffset.left
		});

		/* Check that font browser isn't bleeding over right--if it is, fix it */
		var fontBrowserLeftOffsetRaw = fontBrowser.css('left');
		if ( typeof fontBrowserLeftOffsetRaw !== 'string' ) {
			fontBrowserLeftOffsetRaw = '0';
		}

		var fontBrowserLeftOffset = parseInt(fontBrowserLeftOffsetRaw.replace('px', ''), 10);
		if ( isNaN(fontBrowserLeftOffset) ) {
			fontBrowserLeftOffset = 0;
		}
		var fontBrowserRightPos = fontBrowserLeftOffset + fontBrowser.outerWidth(true);

		if ( fontBrowserRightPos > $(window).width() ) {

			var fontBrowserRightOverflow = $(window).width() - fontBrowserRightPos;

			fontBrowser.css({
				left: fontBrowserLeftOffset + fontBrowserRightOverflow - 20
			});

		}
		
		/* Keep the sub tabs content container from scrolling */
		$('div.sub-tabs-content-container').css('overflow-y', 'hidden');
		
		/* Setup browser */
			if ( fontBrowser.data('setup') !== true ) {

				fontBrowser.data('obj', new fontBrowserObj(fontBrowser));
				fontBrowser.data('obj').setup();

				fontBrowser.data('setup', true);

			}

		/* Show browser */
			if ( fontBrowser.data('visible') !== true ) {
			
				/* Show the font browser */
				fontBrowser.fadeIn(150);
				fontBrowser.data('visible', true);
			
				/* Bind the document close */
				$(document).bind('mousedown', {fontBrowser: fontBrowser}, fontBrowserClose);
				Padma.iframe.contents().bind('mousedown', {fontBrowser: fontBrowser}, fontBrowserClose);
				
				$(window).bind('resize', {fontBrowser: fontBrowser}, fontBrowserClose);
			
		/* Hide browser */
			} else {
				
				/* Hide the font browser */
				fontBrowser.fadeOut(150);
				fontBrowser.data('visible', false);
				
				/* Allow sub tabs content container to scroll again */
				$('div.sub-tabs-content-container').css('overflow-y', 'auto');

				/* Remove the events */
				$(document).unbind('mousedown', fontBrowserClose);
				Padma.iframe.contents().unbind('mousedown', fontBrowserClose);
				
				$(window).unbind('resize', fontBrowserClose);
				
			}

	}

	fontBrowserClose = function(event) {
				
		/* Do not trigger this if they're clicking the same button that they used to open the multi-select */
		if ( $(event.target).parents('.design-editor-property-font-family').length === 1 )
			return;
		
		var fontBrowser = event.data.fontBrowser;
		
		/* Hide the font browser */
		fontBrowser.fadeOut(150);
		fontBrowser.data('visible', false);
		
		/* Allow sub tabs content container to scroll again */
		$('div.sub-tabs-content-container').css('overflow-y', 'auto');
		
		/* Remove the events */
		$(document).unbind('mousedown', fontBrowserClose);
		Padma.iframe.contents().unbind('mousedown', fontBrowserClose);
		
		$(window).unbind('resize', fontBrowserClose);
		
	}
/* End opening and closing functions */

/* Web Font Quick load for loading just one font */
	webFontQuickLoad = function(font) {

		/* Not a web font */
		if ( !font.match(/\|/g) )
			return;

		var fragments 		= font.split('|');
		var fontOriginal 	= font;
		var provider 		= fragments[0];
		var font 			= fragments[1];
		var variants 		= '';

		if ( typeof fragments[2] != 'undefined' && fragments[2] )
			var variants = ':' + fragments[2];

		var args = {
			fontactive: function(fontFamily, fontDescription) {
				jQuery("span.font-name[data-webfont-value='" + fontOriginal + "']").animate({opacity: 1});
			}
		};

		args[provider] = {
			families: [font + variants]
		};

		return WebFont.load(args);

	}
/* End quick load */


})(jQuery);