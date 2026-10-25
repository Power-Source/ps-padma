(function(window, document) {
	'use strict';

	function init(selector, options) {
		var container = document.querySelector(selector);
		if (!container || container.dataset.excerptsCarouselReady === 'true') {
			return;
		}

		var slides = Array.prototype.slice.call(container.children);
		if (slides.length < 2) {
			return;
		}

		var currentIndex = 0;
		var timeout = Math.max(Number(options.timeout) || 0, 0);
		var speed = Math.max(Number(options.speed) || 0, 0);
		var timer = null;
		var pager = null;

		container.dataset.excerptsCarouselReady = 'true';
		container.style.position = 'relative';
		if (Number(options.height) > 0) {
			container.style.height = Number(options.height) + 'px';
		}

		slides.forEach(function(slide, index) {
			slide.hidden = index !== 0;
			slide.style.transition = 'opacity ' + speed + 'ms ease';
		});

		function show(index) {
			slides[currentIndex].hidden = true;
			currentIndex = (index + slides.length) % slides.length;
			slides[currentIndex].hidden = false;

			if (pager) {
				Array.prototype.forEach.call(pager.children, function(button, buttonIndex) {
					button.setAttribute('aria-current', buttonIndex === currentIndex ? 'true' : 'false');
				});
			}
		}

		if (options.pagerType !== 'none') {
			pager = document.createElement('div');
			pager.className = options.pagerClass || 'slider-nav-defaults';
			slides.forEach(function(slide, index) {
				var button = document.createElement('button');
				button.type = 'button';
				button.textContent = options.pagerType === 'bullets' ? '•' : String(index + 1);
				button.setAttribute('aria-label', 'Show slide ' + (index + 1));
				button.setAttribute('aria-current', index === 0 ? 'true' : 'false');
				button.addEventListener('click', function() {
					show(index);
				});
				pager.appendChild(button);
			});
			container.insertAdjacentElement('afterend', pager);
		}

		if (timeout > 0) {
			timer = window.setInterval(function() {
				show(currentIndex + 1);
			}, timeout);

			container.addEventListener('mouseenter', function() {
				window.clearInterval(timer);
			});
			container.addEventListener('mouseleave', function() {
				window.clearInterval(timer);
				timer = window.setInterval(function() {
					show(currentIndex + 1);
				}, timeout);
			});
		}
	}

	window.PadmaExcerptsCarousel = {
		init: init
	};
})(window, document);
