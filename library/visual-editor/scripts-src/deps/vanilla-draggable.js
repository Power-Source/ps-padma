/**
 * Vanilla JS Draggable & Resizable Implementation (replaces jQuery UI)
 * Uses modern touch events and CSS transforms for better performance
 */

define(['jquery'], function(jQuery) {

(function(window, jQuery) {
	'use strict';

	/**
	 * Simple widget factory without jQuery UI dependency
	 * Creates a constructor function that can be instantiated on jQuery elements
	 */
	function createWidget(name, baseClass, proto) {
		const parts = name.split('.');
		const namespace = parts[0];
		const widgetName = parts[1];

		if (typeof jQuery !== 'undefined') {
			jQuery.fn[widgetName] = function(options) {
				return this.each(function() {
					const $this = jQuery(this);
					let instance = $this.data(widgetName);

					if (!instance) {
						// Create new instance
						const widget = Object.create(baseClass.prototype || {});
						widget.element = this;
						widget.$element = $this;
						widget.options = jQuery.extend(true, {}, proto.options, options);

						// Merge in prototype methods
						for (const key in proto) {
							if (key !== 'options') {
								widget[key] = proto[key];
							}
						}

						if (widget._create) {
							widget._create();
						}

						$this.data(widgetName, widget);
						instance = widget;
					} else if (options === 'destroy') {
						if (instance._destroy) {
							instance._destroy();
						}
						$this.removeData(widgetName);
						return;
					} else if (typeof options === 'object') {
						// Update options
						jQuery.extend(instance.options, options);
					}

					return this;
				});
			};
		}

		return jQuery.fn[widgetName];
	}

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
		const doc = this.element.ownerDocument || document;
		
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
				doc.removeEventListener('mousemove', onMouseMove);
				doc.removeEventListener('touchmove', onMouseMove);
				doc.removeEventListener('mouseup', onMouseUp);
				doc.removeEventListener('touchend', onMouseUp);

				if (self.options.stop) self.options.stop.call(self.element);
			};

			doc.addEventListener('mousemove', onMouseMove);
			doc.addEventListener('touchmove', onMouseMove, { passive: false });
			doc.addEventListener('mouseup', onMouseUp);
			doc.addEventListener('touchend', onMouseUp, { passive: true });

			e.preventDefault();
		};

		handle.addEventListener('mousedown', onMouseDown);
		handle.addEventListener('touchstart', onMouseDown, { passive: false });
	};

	SimpleDraggable.prototype.destroy = function() {
		// Reset transform
		this.element.style.transform = '';
	};

	// Resizable implementation using vanilla JS
	function SimpleResizable(element, options) {
		this.element = element;
		this.options = Object.assign({
			handles: 'e, s, se', // Default handles: east, south, southeast
			minWidth: 10,
			minHeight: 10,
			start: null,
			resize: null,
			stop: null
		}, options || {});

		this.init();
	}

	SimpleResizable.prototype.init = function() {
		const self = this;
		const element = this.element;
		const opts = this.options;
		const doc = element.ownerDocument || document;

		// Ensure element is positioned
		if (window.getComputedStyle(element).position === 'static') {
			element.style.position = 'relative';
		}

		// Store original dimensions
		let startWidth, startHeight, startX, startY;

		const createHandle = (position) => {
			const handle = document.createElement('div');
			handle.className = `ui-resizable-handle ui-resizable-${position}`;
			handle.style.cssText = `
				position: absolute;
				user-select: none;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
			`;

			switch(position) {
				case 'e': // East (right)
					handle.style.cssText += `
						right: 0;
						top: 0;
						bottom: 0;
						width: 8px;
						cursor: ew-resize;
					`;
					break;
				case 's': // South (bottom)
					handle.style.cssText += `
						bottom: 0;
						left: 0;
						right: 0;
						height: 8px;
						cursor: ns-resize;
					`;
					break;
				case 'se': // Southeast (corner)
					handle.style.cssText += `
						bottom: 0;
						right: 0;
						width: 12px;
						height: 12px;
						cursor: nwse-resize;
					`;
					break;
				case 'w': // West (left)
					handle.style.cssText += `
						left: 0;
						top: 0;
						bottom: 0;
						width: 8px;
						cursor: ew-resize;
					`;
					break;
				case 'n': // North (top)
					handle.style.cssText += `
						top: 0;
						left: 0;
						right: 0;
						height: 8px;
						cursor: ns-resize;
					`;
					break;
				case 'ne': // Northeast (corner)
					handle.style.cssText += `
						top: 0;
						right: 0;
						width: 12px;
						height: 12px;
						cursor: nesw-resize;
					`;
					break;
				case 'sw': // Southwest (corner)
					handle.style.cssText += `
						bottom: 0;
						left: 0;
						width: 12px;
						height: 12px;
						cursor: nesw-resize;
					`;
					break;
				case 'nw': // Northwest (corner)
					handle.style.cssText += `
						top: 0;
						left: 0;
						width: 12px;
						height: 12px;
						cursor: nwse-resize;
					`;
					break;
			}

			handle.addEventListener('mousedown', startResize);
			handle.addEventListener('touchstart', startResize, { passive: false });
			return handle;
		};

		const startResize = (e) => {
			startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
			startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
			startWidth = element.offsetWidth;
			startHeight = element.offsetHeight;
			const startLeft = element.offsetLeft;
			const startTop = element.offsetTop;

			const makeUi = function() {
				return {
					element: jQuery(element),
					helper: jQuery(element),
					size: {
						width: element.offsetWidth,
						height: element.offsetHeight
					},
					position: {
						left: element.offsetLeft,
						top: element.offsetTop
					}
				};
			};

			const handleClass = Array.from(e.target.classList || []).find(cls => cls.indexOf('ui-resizable-') === 0 && cls !== 'ui-resizable-handle');
			const position = handleClass ? handleClass.replace('ui-resizable-', '') : 'se';

			const gridX = Array.isArray(opts.grid) ? parseInt(opts.grid[0], 10) || 1 : 1;
			const gridY = Array.isArray(opts.grid) ? parseInt(opts.grid[1], 10) || 1 : 1;
			const maxWidth = typeof opts.maxWidth === 'number' ? opts.maxWidth : Number.POSITIVE_INFINITY;
			const maxHeight = typeof opts.maxHeight === 'number' ? opts.maxHeight : Number.POSITIVE_INFINITY;

			if (opts.start) {
				opts.start.call(element, e, makeUi());
			}

			const onMouseMove = (moveEvent) => {
				const currentX = moveEvent.type === 'touchmove' ? moveEvent.touches[0].clientX : moveEvent.clientX;
				const currentY = moveEvent.type === 'touchmove' ? moveEvent.touches[0].clientY : moveEvent.clientY;

				const deltaX = currentX - startX;
				const deltaY = currentY - startY;

				let newWidth = startWidth;
				let newHeight = startHeight;
				let newLeft = startLeft;
				let newTop = startTop;

				// Handle different resize positions
				if (position.includes('e')) {
					newWidth = startWidth + deltaX;
				} else if (position.includes('w')) {
					newWidth = startWidth - deltaX;
					newLeft = startLeft + deltaX;
				}

				if (position.includes('s')) {
					newHeight = startHeight + deltaY;
				} else if (position.includes('n')) {
					newHeight = startHeight - deltaY;
					newTop = startTop + deltaY;
				}

				newWidth = Math.max(opts.minWidth, Math.min(maxWidth, newWidth));
				newHeight = Math.max(opts.minHeight, Math.min(maxHeight, newHeight));

				if (position.includes('w')) {
					newLeft = startLeft + (startWidth - newWidth);
				}

				if (position.includes('n')) {
					newTop = startTop + (startHeight - newHeight);
				}

				if (gridX > 1) {
					newWidth = Math.round(newWidth / gridX) * gridX;
					if (position.includes('w')) {
						newLeft = startLeft + (startWidth - newWidth);
					}
				}

				if (gridY > 1) {
					newHeight = Math.round(newHeight / gridY) * gridY;
					if (position.includes('n')) {
						newTop = startTop + (startHeight - newHeight);
					}
				}

				element.style.width = newWidth + 'px';
				element.style.height = newHeight + 'px';
				if (position.includes('w')) {
					element.style.left = newLeft + 'px';
				}
				if (position.includes('n')) {
					element.style.top = newTop + 'px';
				}

				if (opts.resize) {
					opts.resize.call(element, moveEvent, makeUi());
				}
			};

			const onMouseUp = (upEvent) => {
				doc.removeEventListener('mousemove', onMouseMove);
				doc.removeEventListener('touchmove', onMouseMove);
				doc.removeEventListener('mouseup', onMouseUp);
				doc.removeEventListener('touchend', onMouseUp);

				if (opts.stop) {
					opts.stop.call(element, upEvent, makeUi());
				}
			};

			doc.addEventListener('mousemove', onMouseMove);
			doc.addEventListener('touchmove', onMouseMove, { passive: false });
			doc.addEventListener('mouseup', onMouseUp);
			doc.addEventListener('touchend', onMouseUp, { passive: true });

			e.preventDefault();
		};

		const handles = opts.handles ? opts.handles.split(',').map(h => h.trim()) : ['se'];
		handles.forEach(position => {
			element.appendChild(createHandle(position));
		});

		// Mark as resizable
		element.classList.add('ui-resizable');
	};

	SimpleResizable.prototype.destroy = function() {
		// Remove handles
		const handles = this.element.querySelectorAll('.ui-resizable-handle');
		handles.forEach(handle => handle.remove());
		this.element.classList.remove('ui-resizable');
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

		// Resizable implementation
		jQuery.fn.resizable = function(options) {
			return this.each(function() {
				const data = jQuery(this).data('resizable');
				
				if (!data) {
					const instance = new SimpleResizable(this, options);
					jQuery(this).data('resizable', instance);
				} else if (options === 'destroy') {
					data.destroy();
					jQuery(this).removeData('resizable');
				}
			});
		};

		// Create a minimal $.ui namespace for compatibility
		if (jQuery.ui === undefined) {
			jQuery.ui = {};
		}

		// Make CustomMouseImpl available as $.ui.mouse
		function VanillaMouseBase(element, options) {
			// Don't override if element is already a jQuery object from widget factory
			if (element && element.jquery) {
				this.element = element;
				this.$element = element;
			} else {
				this.element = jQuery(element);
				this.$element = this.element;
			}
			this.options = jQuery.extend(true, {}, this.options, options);
			this._mouseInit();
		}

		VanillaMouseBase.prototype = {
			options: {
				delay: 0,
				distance: 0,
				cancel: ':input,option'
			},
			_getOwnerDocument: function() {
				if (this.el && this.el.ownerDocument) {
					return this.el.ownerDocument;
				}

				if (this.element && this.element.jquery && this.element[0] && this.element[0].ownerDocument) {
					return this.element[0].ownerDocument;
				}

				if (this.element && this.element.ownerDocument) {
					return this.element.ownerDocument;
				}

				return document;
			},
			_getPointerCoords: function(event) {
				const original = event && event.originalEvent ? event.originalEvent : event;
				const touch = original && original.touches && original.touches.length ? original.touches[0] : (original && original.changedTouches && original.changedTouches.length ? original.changedTouches[0] : null);

				if (touch) {
					return { x: touch.pageX, y: touch.pageY };
				}

				return {
					x: event.pageX,
					y: event.pageY
				};
			},
			_mouseInit: function() {
				// Initialize mouse tracking
				const self = this;
				
				this.$element.on('mousedown.ui-mouse touchstart.ui-mouse', function(e) {
					return self._mouseDown(e);
				});
			},
			_mouseDown: function(event) {
				// Bail if we're already tracking a mouse
				if (this._mouseStarted) {
					return true;
				}

				// Set up tracking state
				this._mouseDownEvent = event;
				this._mouseDownPosition = this._getPointerCoords(event);

				const self = this;
				const doc = this._getOwnerDocument();
				const $doc = jQuery(doc);

				// Check distance threshold
				const checkDistance = (moveEvent) => {
					const point = self._getPointerCoords(moveEvent);
					const distance = Math.sqrt(
						Math.pow(point.x - self._mouseDownPosition.x, 2) +
						Math.pow(point.y - self._mouseDownPosition.y, 2)
					);
					return distance >= (self.options.distance || 0);
				};

				// Start drag tracking on mousemove
				const onMouseMove = (moveEvent) => {
					if (!self._mouseStarted && checkDistance(moveEvent)) {
						self._mouseStarted = (self._mouseStart(self._mouseDownEvent, moveEvent) !== false);
						if (self._mouseStarted) {
							self._mouseDrag(moveEvent);
						}
					} else if (self._mouseStarted) {
						self._mouseDrag(moveEvent);
					}
				};

				const onMouseUp = (upEvent) => {
					$doc.off('mousemove.ui-mouse', onMouseMove);
					$doc.off('touchmove.ui-mouse', onMouseMove);
					$doc.off('mouseup.ui-mouse', onMouseUp);
					$doc.off('touchend.ui-mouse', onMouseUp);

					if (self._mouseStarted) {
						self._mouseStarted = false;
						self._mouseStop(upEvent);
					}

					self._mouseDownEvent = null;
				};

				// Register only on the element's document (iFrame or main)
				$doc.on('mousemove.ui-mouse touchmove.ui-mouse', onMouseMove);
				$doc.on('mouseup.ui-mouse touchend.ui-mouse', onMouseUp);

				return false;
			},
			_mouseStart: function(event) {
				// Can be overridden by subclasses
				return true;
			},
			_mouseDrag: function(event) {
				// Can be overridden by subclasses
			},
			_mouseStop: function(event) {
				// Can be overridden by subclasses
			},
			_mouseDestroy: function() {
				this.$element.off('.ui-mouse');
				const doc = this._getOwnerDocument();
				jQuery(doc).off('.ui-mouse');
			}
		};

		jQuery.ui.mouse = VanillaMouseBase;

		// Provide a $.widget-like factory
		jQuery.widget = function(name, Base, proto) {
			const parts = name.split('.');
			const namespace = parts[0] || 'ui';
			const widgetName = parts[1] || parts[0];
			const dataKey = namespace + '-' + widgetName;

			// Create constructor
			const widget = function(element, options) {
				this.element = jQuery(element);
				this.$element = this.element;
				this.el = element;
				this.options = jQuery.extend(true, {}, this.options, options);
				
				// Call base class constructor if needed, passing jQuery object
				if (Base && Base !== Object) {
					Base.call(this, this.element, options);
				}
				
				this._create();
			};

			// Set up prototype chain
			if (Base) {
				widget.prototype = Object.create(Base.prototype);
			}

			// Merge in proto methods
			jQuery.extend(widget.prototype, proto);

			if (typeof widget.prototype._on !== 'function') {
				widget.prototype._on = function(element, handlers) {
					const target = element ? jQuery(element) : this.element;
					for (const eventName in handlers) {
						if (typeof handlers[eventName] === 'string' && typeof this[handlers[eventName]] === 'function') {
							target.on(eventName, this[handlers[eventName]].bind(this));
						} else if (typeof handlers[eventName] === 'function') {
							target.on(eventName, handlers[eventName].bind(this));
						}
					}
				};
			}

			if (typeof widget.prototype._trigger !== 'function') {
				widget.prototype._trigger = function(type, event, data) {
					const callback = this.options && this.options[type];
					if (typeof callback === 'function') {
						callback.call(this.element[0], event, data);
					}
					this.element.trigger(type, data);
					return true;
				};
			}

			// Provide jQuery plugin
			jQuery.fn[widgetName] = function(options) {
				return this.each(function() {
					const $this = jQuery(this);
					let instance = $this.data(widgetName) || $this.data(dataKey);

					if (!instance) {
						instance = new widget(this, options);
						$this.data(widgetName, instance);
						$this.data(dataKey, instance);
					} else if (options === 'destroy') {
						if (instance._destroy) {
							instance._destroy();
						}
						$this.removeData(widgetName);
						$this.removeData(dataKey);
					} else if (typeof options === 'object') {
						jQuery.extend(instance.options, options);
					} else if (typeof options === 'string' && typeof instance[options] === 'function') {
						instance[options].apply(instance, Array.prototype.slice.call(arguments, 1));
					}

					return this;
				});
			};
		};

		// Simple helper for event binding in widgets
		jQuery.prototype._on = function(element, handlers) {
			const $el = jQuery(element || this.element);
			for (const event in handlers) {
				if (typeof handlers[event] === 'string') {
					const method = this[handlers[event]];
					if (typeof method === 'function') {
						$el.on(event, method.bind(this));
					}
				} else if (typeof handlers[event] === 'function') {
					$el.on(event, handlers[event].bind(this));
				}
			}
			return this;
		};

		// disableSelection helper
		jQuery.fn.disableSelection = function() {
			return this.css({
				'user-select': 'none',
				'-webkit-user-select': 'none',
				'-moz-user-select': 'none',
				'-ms-user-select': 'none'
			});
		};
	}

	window.SimpleDraggable = SimpleDraggable;
	window.SimpleResizable = SimpleResizable;
	window.createWidget = createWidget;
})(window, jQuery);

return {
	SimpleDraggable: window.SimpleDraggable,
	SimpleResizable: window.SimpleResizable,
	createWidget: window.createWidget
};
});
