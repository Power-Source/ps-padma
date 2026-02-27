/**
 * Vanilla JS Draggable & Resizable Implementation (replaces jQuery UI)
 * Uses modern touch events and CSS transforms for better performance
 */

(function(window) {
	'use strict';

	// Simple draggable without interact.js dependency
	function SimpleDraggable(element, options) {
		this.element = element;
		this.options = Object.assign({
			handle: null,
			start: null,
			stop: null,
			distance: 0,
			delay: 0
		}, options || {});

		this.init();
	}

	SimpleDraggable.prototype.init = function() {
		const self = this;
		
		// Handle can be: string selector, jQuery object, or DOM element
		let handle = this.options.handle;
		
		if (typeof handle === 'string') {
			// String selector
			handle = this.element.querySelector(handle);
		} else if (handle && handle.jquery) {
			// jQuery object
			handle = handle[0];
		}
		
		// If no handle or invalid, use the element itself
		if (!handle || !handle.nodeType) {
			handle = this.element;
		}

		let isActive = false;
		let startX, startY, offsetX = 0, offsetY = 0;

		const onMouseDown = (e) => {
			// Only allow left mouse button
			if (e.button !== 0 && e.type !== 'touchstart') return;
			
			isActive = true;
			startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
			startY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

			if (self.options.start) self.options.start.call(self.element);

			const onMouseMove = (e) => {
				if (!isActive) return;

				const currentX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
				const currentY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;

				const dx = currentX - startX;
				const dy = currentY - startY;

				offsetX += dx;
				offsetY += dy;

				// Use transform for better performance
				self.element.style.transform = `translate(${offsetX}px, ${offsetY}px)`;

				startX = currentX;
				startY = currentY;
			};

			const onMouseUp = () => {
				isActive = false;
				document.removeEventListener('mousemove', onMouseMove);
				document.removeEventListener('touchmove', onMouseMove);
				document.removeEventListener('mouseup', onMouseUp);
				document.removeEventListener('touchend', onMouseUp);

				if (self.options.stop) self.options.stop.call(self.element);
			};

			document.addEventListener('mousemove', onMouseMove);
			document.addEventListener('touchmove', onMouseMove, { passive: false });
			document.addEventListener('mouseup', onMouseUp);
			document.addEventListener('touchend', onMouseUp);

			e.preventDefault();
		};

		handle.addEventListener('mousedown', onMouseDown);
		handle.addEventListener('touchstart', onMouseDown);
	};

	SimpleDraggable.prototype.destroy = function() {
		// Reset transform
		this.element.style.transform = '';
	};

	// jQuery plugin interface
	if (typeof jQuery !== 'undefined') {
		jQuery.fn.draggable = function(options) {
			return this.each(function() {
				const data = jQuery(this).data('draggable');
				
				if (!data) {
					const instance = new SimpleDraggable(this, options);
					jQuery(this).data('draggable', instance);
				} else if (options === 'destroy') {
					data.destroy();
					jQuery(this).removeData('draggable');
				}
			});
		};

		// Minimal resizable implementation
		jQuery.fn.resizable = function(options) {
			return this.each(function() {
				// For now, resizable is disabled since it's not critical
				// and would require significant CSS/positioning setup
				// Could implement with handle.corner dragging if needed
				console.warn('Resizable not yet implemented - boxes will not be resizable');
			});
		};
	}

	window.SimpleDraggable = SimpleDraggable;
})(window);
