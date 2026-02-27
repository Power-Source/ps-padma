define(['jquery'], function($) {

	/**
	 * Vanilla implementation of custom mouse events (replaces jQuery UI mouse widget)
	 * Provides mouseStart, mouseDrag, mouseStop, and mouseCapture events
	 */
	function CustomMouseImpl(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = $.extend({
			mouseStart: function(e) { return true; },
			mouseDrag: function(e) {},
			mouseStop: function(e) {},
			mouseCapture: function(e) { return true; }
		}, options || {});

		this._init();
	}

	CustomMouseImpl.prototype._init = function() {
		const self = this;
		let isMouseDown = false;
		let startX = 0;
		let startY = 0;

		const handleMouseDown = (e) => {
			// Check if event should be captured
			if (!self.options.mouseCapture.call(self.element, e)) {
				return true;
			}

			isMouseDown = true;
			startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
			startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;

			// Trigger mouseStart callback
			const startResult = self.options.mouseStart.call(self.element, e);
			if (startResult === false) {
				isMouseDown = false;
				return false;
			}

			const onMouseMove = function(moveEvent) {
				if (!isMouseDown) return;

				// Trigger mouseDrag callback
				self.options.mouseDrag.call(self.element, moveEvent);
			};

			const onMouseUp = function(upEvent) {
				isMouseDown = false;
				document.removeEventListener('mousemove', onMouseMove);
				document.removeEventListener('touchmove', onMouseMove);
				document.removeEventListener('mouseup', onMouseUp);
				document.removeEventListener('touchend', onMouseUp);

				// Trigger mouseStop callback
				self.options.mouseStop.call(self.element, upEvent);
			};

			document.addEventListener('mousemove', onMouseMove);
			document.addEventListener('touchmove', onMouseMove, { passive: false });
			document.addEventListener('mouseup', onMouseUp);
			document.addEventListener('touchend', onMouseUp, { passive: true });

			e.preventDefault();
			return false;
		};

		this.$element.on('mousedown.custommouse', handleMouseDown);
		// Use native addEventListener for touchstart to support passive: false
		if (this.element.addEventListener) {
			this._touchStartHandler = handleMouseDown;
			this.element.addEventListener('touchstart', this._touchStartHandler, { passive: false });
		}
	};

	CustomMouseImpl.prototype.destroy = function() {
		this.$element.off('.custommouse');
		if (this._touchStartHandler && this.element.removeEventListener) {
			this.element.removeEventListener('touchstart', this._touchStartHandler);
		}
	};

	$.fn.custommouse = function(options) {
		return this.each(function() {
			var $el = $(this);
			var instance = $el.data('custommouse');

			if (options === 'destroy') {
				if (instance) {
					instance.destroy();
					$el.removeData('custommouse');
				}
				return;
			}

			if (instance) {
				instance.destroy();
				$el.removeData('custommouse');
			}

			instance = new CustomMouseImpl(this, options || {});
			$el.data('custommouse', instance);
		});
	};

	// Export for direct use
	window.CustomMouseImpl = CustomMouseImpl;

	return {
		CustomMouseImpl: CustomMouseImpl,
		mouse: CustomMouseImpl // Provide as 'mouse' for compatibility
	};
});