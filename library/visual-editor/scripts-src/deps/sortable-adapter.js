/**
 * Sortable.js jQuery Adapter
 * Wraps Sortable.js to provide jQuery-compatible API
 */

(function(window) {
	'use strict';

	if (typeof jQuery !== 'undefined') {
		jQuery.fn.sortable = function(options) {
			return this.each(function() {
				const data = jQuery(this).data('sortable-instance');
				
				if (!data) {
					if (typeof options === 'string') {
						return; // Method call on non-initialized element
					}

					// Initialize Sortable
					const sortableOptions = {
						animation: 150,
						group: options.group,
						handle: options.handle,
						sort: true,
						delay: 0,
						filter: options.filter,
						exclude: options.exclude,
						ghostClass: options.ghostClass || 'ui-sortable-placeholder',
						dragClass: options.dragClass || 'ui-sortable-dragging',
						forceFallback: false,
						fallbackClass: options.fallbackClass || 'ui-sortable-fallback',
						direction: options.axis === 'y' ? 'vertical' : 'horizontal',
						swapThreshold: 1,
						invertSwap: false,
						scrollSensitivity: 30,
						scrollSpeed: 30,
						bubbleScroll: true,
						dataIdAttr: options.dataIdAttr,
						store: options.store,
						onStart: options.start,
						onEnd: options.stop,
						onChoose: options.choose,
						onSort: options.sort,
						onFilter: options.filter,
						onMove: options.move,
						onClone: options.clone,
						onChange: options.change,
						onUpdate: options.update || options.stop,
						onRemove: options.remove,
						onUnchoose: options.unchoose,
						setData: options.setData
					};

					// Remove undefined options
					Object.keys(sortableOptions).forEach(key => {
						if (sortableOptions[key] === undefined) {
							delete sortableOptions[key];
						}
					});

					// Custom handling for special options
					if (options.items) {
						sortableOptions.draggable = options.items;
					}
					
					if (options.containment === 'parent') {
						sortableOptions.onMove = function(evt) {
							// Implement containment
							return true;
						};
					}

					// Create Sortable instance
					const sortable = new window.Sortable(this, sortableOptions);
					jQuery(this).data('sortable-instance', sortable);
					jQuery(this).data('sortable', sortable); // Both keys for compatibility
					
				} else if (typeof options === 'string') {
					// Method calls
					if (options === 'refresh' || options === 'refreshPositions') {
						// Sortable.js doesn't need explicit refresh
						// but we can trigger a recompute if needed
						data.render();
					} else if (options === 'destroy') {
						data.destroy();
						jQuery(this).removeData('sortable-instance');
						jQuery(this).removeData('sortable');
					} else if (options === 'option') {
						// Handle .sortable('option', optionName, value)
						if (arguments.length === 2) {
							return data.option(arguments[1]);
						} else if (arguments.length === 3) {
							data.option(arguments[1], arguments[2]);
						}
					}
				}
			});
		};
	}

	// Export for use in RequireJS
	if (typeof module !== 'undefined' && module.exports) {
		module.exports = window.Sortable;
	}

})(window);
