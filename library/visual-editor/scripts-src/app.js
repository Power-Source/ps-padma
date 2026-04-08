var padmaBaseUrl = (typeof Padma !== 'undefined' && Padma.padmaURL && Padma.scriptFolder)
	? (Padma.padmaURL + '/library/visual-editor/' + Padma.scriptFolder)
	: null;

if (!padmaBaseUrl && typeof document !== 'undefined') {
	var scriptNode = document.currentScript;

	if (!scriptNode) {
		var scripts = document.getElementsByTagName('script');
		for (var si = scripts.length - 1; si >= 0; si--) {
			var scriptSrc = scripts[si] && scripts[si].src ? scripts[si].src : '';
			if (scriptSrc.indexOf('/library/visual-editor/') !== -1 && scriptSrc.indexOf('/app.js') !== -1) {
				scriptNode = scripts[si];
				break;
			}
		}
	}

	if (scriptNode && scriptNode.src) {
		padmaBaseUrl = scriptNode.src.split('?')[0].replace(/\/app\.js$/, '');
	}
}

require.config({
	baseUrl: padmaBaseUrl || '',
	waitSeconds: 45,
	paths: {
		knockout: 'deps/knockout',
		underscore: 'deps/underscore',

		/* Modern Replacements for jQuery UI */
		'Sortable': 'deps/Sortable',
		'sortable-adapter': 'deps/sortable-adapter',
		'vanilla-tabs': 'deps/vanilla-tabs',
		'vanilla-draggable': 'deps/vanilla-draggable',

		/* jQuery Plugins */
		qtip: 'deps/jquery.qtip'
	},
	shim: {
	    underscore: {
	      exports: '_'
		},
		'deps/colorpicker': {
			deps: ['jquery']
		}
	}
});

if (typeof requirejs !== 'undefined') {
	requirejs.onError = function(err) {
		if (err && err.requireType === 'timeout') {
			if (window.console && typeof window.console.error === 'function') {
				console.error('[Padma VE] RequireJS timeout.', {
					error: err,
					baseUrl: padmaBaseUrl,
					modules: err.requireModules || []
				});
			}
		}

		throw err;
	};
}

require(['jquery', 'util.loader', 'Sortable', 'sortable-adapter', 'vanilla-tabs', 'vanilla-draggable'], function($) {

	/* Start loading indidcator */
	startTitleActivityIndicator();
	//iframe.showIframeLoadingOverlay();

	/* Parse the JSON in the Padma l10n array */	
	Padma.blockTypeURLs 			= $.parseJSON(Padma.blockTypeURLs.replace(/&quot;/g, '"'));
	Padma.allBlockTypes 			= $.parseJSON(Padma.allBlockTypes.replace(/&quot;/g, '"'));
	Padma.ranTour 					= $.parseJSON(Padma.ranTour.replace(/&quot;/g, '"'));
	Padma.designEditorProperties 	= $.parseJSON(Padma.designEditorProperties.replace(/&quot;/g, '"'));
	Padma.layouts 					= $.parseJSON(Padma.layouts.replace(/&quot;/g, '"'));

	/* Setup modules */
	require(['modules/layout-selector'], function(layoutSelector) {
		layoutSelector.init();
	});

	require(['modules/panel', 'modules/iframe'], function(panel, iframe) {
		panel.init();
		iframe.init();
	});

	require(['modules/menu'], function(menu) {
		menu.init();
	});

	require(['modules/snapshots'], function(snapshots) {
		snapshots.init();
	});

	/* Init tour */
	require(['util.tour'], function (tour) {

		if ( Padma.ranTour[Padma.mode] == false && Padma.ranTour.legacy == false ) {
			if ( typeof tour.startWhenReady == 'function' ) {
				tour.startWhenReady();
			} else {
				tour.start();
			}
		}

	});

	/**
	 *
	 * Load mode switcher
	 *
	 */
	require(['switch.mode'], function(switchMode) {
		switchMode.init();
	});


	/* Load helpers all at once since they're used everywhere */
	require(['helper.data', 'helper.blocks', 'helper.wrappers', 'helper.context-menus', 'helper.notifications', 'helper.boxes', 'helper.history'], function(data, blocks, wrappers, contextMenus, notifications, boxes, history) {
		history.init();
	});


	/**
	 *
	 * Offline check
	 * DISABLED: Causing 403 nonce issues with admin-ajax.php
	 *
	 */
	// require(['util.offline'], function(offline) {
	// 	offline.init();
	// });
	
	

	/* Load in the appropriate modules depending on the mode */
	switch ( Padma.mode ) {

		case 'grid':

			require(['modules/grid/mode-grid', 'modules/iframe', 'modules/layout-selector'], function(modeGrid) {
				
				Padma.instance = modeGrid;

				modeGrid.init();
				waitForIframeLoad(modeGrid.iframeCallback);
			});

		break;

		case 'design':

			require(['modules/design/mode-design', 'util.preview', 'modules/iframe', 'modules/layout-selector'], function(modeDesign, devicePreview) {
			//require(['modules/design/mode-design', 'util.preview', 'modules/content-selector', 'modules/iframe', 'modules/layout-selector'], function(modeDesign, devicePreview, contentSelector) {
				
				/**
				 *
				 * Load Devices Preview 
				 *
				 */
				devicePreview.init();


				Padma.instance = modeDesign;

				modeDesign.init();
				waitForIframeLoad(modeDesign.iframeCallback);

				/*
					- Disable until future release
				
				contentSelector.init();

				if(Padma.viewModels.layoutSelector.currentLayout().search('template') != -1){
					$('#content-selector-select').show();
				}else{
					$('#content-selector-select').hide();
				}
				*/
			});

		break;

	}

	/* After everything is loaded show the Visual Editor */
	$(document).ready(function() {

		$('body').addClass('show-ve');

	});

	$(window).bind('load', function() {

		/* Remove VE loader overlay after we know page has loaded */
		setTimeout(function () {
			$('div#ve-loading-overlay').remove();
		}, 1000);

	});


});