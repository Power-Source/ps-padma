define(['jquery', 'underscore'], function($, _) {

	var uploaderI18n = (typeof Padma !== 'undefined' && Padma.i18nUploader) ? Padma.i18nUploader : {};
	var t = function(key, fallback) {
		return uploaderI18n[key] || fallback;
	};

	openVideoUploader = function(callback) {

		// Check if WordPress Media Library is available
		if (typeof wp !== 'undefined' && wp.media) {
			
			// Initialize WordPress Media Library directly
			var mediaUploader = wp.media({
				title: t('selectOrUploadVideo', 'Video auswaehlen oder hochladen'),
				button: {
					text: t('useVideo', 'Video verwenden')
				},
				multiple: false,
				library: {
					type: 'video'
				}
			});
			
			// Handle media selection
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				callback(attachment.url, attachment.filename);
			});
			
			// Open the media library
			mediaUploader.open();
			
		} else {
			// Fallback to iframe method if wp.media is not available
			openVideoUploaderIframe(callback);
		}

	}

	// Fallback iframe method
	openVideoUploaderIframe = function(callback) {

		// Store callback globally for the iframe to access
		window.videoUploaderCallback = callback;

		if ( !boxExists('input-video') ) {

			if ( isNaN(Padma.viewModels.layoutSelector.currentLayout()) )
				iframePostID = 0;

			var settings = {
				id: 'input-video',
				title: t('selectVideo', 'Video auswaehlen'),
				description: t('uploadOrSelectVideo', 'Video hochladen oder auswaehlen'),
				src: Padma.homeURL + '/?padma-trigger=media-uploader',
				load: function() {
					console.log('Video uploader iframe loaded');
				},
				width: $(window).width() - 200,
				height: $(window).height() - 200,
				center: true,
				draggable: false,
				deleteWhenClosed: true,
				blackOverlay: true
			};

			var box = createBox(settings);

			$('#box-input-video').css({
				width: 'auto',
				height: 'auto',
				top: '70px',
				left: '70px',
				right: '70px',
				bottom: '70px',
				margin: 0
			});

		}

		openBox('input-video');

	}

});
