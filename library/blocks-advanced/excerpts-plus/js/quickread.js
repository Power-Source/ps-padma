(function(document, window) {
	'use strict';

	function sanitizeContent(content) {
		var blockedElements = content.querySelectorAll('script, style, iframe, object, embed');
		Array.prototype.forEach.call(blockedElements, function(element) {
			element.remove();
		});

		Array.prototype.forEach.call(content.querySelectorAll('*'), function(element) {
			Array.prototype.slice.call(element.attributes).forEach(function(attribute) {
				var name = attribute.name.toLowerCase();
				var value = attribute.value.trim().toLowerCase();
				if (name.indexOf('on') === 0 || ((name === 'href' || name === 'src') && value.indexOf('javascript:') === 0)) {
					element.removeAttribute(attribute.name);
				}
			});
		});

		return content;
	}

	function init() {
		var dialog = document.getElementById('quickread');
		if (!dialog) {
			return;
		}

		var title = dialog.querySelector('.qr-title');
		var content = dialog.querySelector('.qr-content');
		var closeButton = dialog.querySelector('.qr-close');
		var openPostLink = dialog.querySelector('.qr-open-post');

		closeButton.addEventListener('click', function() {
			dialog.close();
		});

		dialog.addEventListener('click', function(event) {
			if (event.target === dialog) {
				dialog.close();
			}
		});

		Array.prototype.forEach.call(document.querySelectorAll('.openquickread'), function(trigger) {
			trigger.addEventListener('click', function(event) {
				event.preventDefault();

				var destination;
				try {
					destination = new URL(trigger.href, window.location.href);
				} catch (error) {
					return;
				}
				if (destination.origin !== window.location.origin) {
					return;
				}

				title.textContent = trigger.getAttribute('alt') || trigger.textContent;
				content.textContent = 'Loading content...';
				openPostLink.href = destination.href;
				dialog.showModal();

				window.fetch(destination.href, { credentials: 'same-origin' })
					.then(function(response) {
						if (!response.ok) {
							throw new Error('Quick Read request failed with status ' + response.status + '.');
						}
						return response.text();
					})
					.then(function(html) {
						var responseDocument = new DOMParser().parseFromString(html, 'text/html');
						var entryContent = responseDocument.querySelector('.entry-content');
						if (!entryContent) {
							throw new Error('Quick Read response does not contain post content.');
						}

						content.replaceChildren(sanitizeContent(entryContent.cloneNode(true)));
						Array.prototype.forEach.call(content.querySelectorAll('.pzsp-container'), function(slideshow) {
							var message = document.createElement('p');
							message.className = 'ep-pzsp-removed';
							message.textContent = 'Open the full post to view slideshow';
							slideshow.replaceChildren(message);
						});
					})
					.catch(function(error) {
						content.textContent = error.message;
					});
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init, { once: true });
	} else {
		init();
	}
})(document, window);
