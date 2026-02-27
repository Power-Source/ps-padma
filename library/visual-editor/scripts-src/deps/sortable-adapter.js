/**
 * Sortable.js jQuery Adapter
 * Wraps Sortable.js to provide jQuery-compatible API
 */

define(['jquery', 'Sortable'], function($, SortableCtor) {
	'use strict';

	var SortableClass = SortableCtor || window.Sortable;

	$.fn.sortable = function(options) {
		return this.each(function() {
			var instance = $(this).data('sortable-instance');
			var opts = options || {};

			if (!instance) {
				if (typeof opts === 'string') {
					return;
				}

				if (typeof SortableClass !== 'function') {
					throw new Error('Sortable constructor is unavailable');
				}

				var sortableOptions = {
					animation: 150,
					group: opts.group,
					handle: opts.handle,
					sort: true,
					delay: 0,
					filter: opts.filter,
					exclude: opts.exclude,
					ghostClass: opts.ghostClass || 'ui-sortable-placeholder',
					dragClass: opts.dragClass || 'ui-sortable-dragging',
					forceFallback: false,
					fallbackClass: opts.fallbackClass || 'ui-sortable-fallback',
					direction: opts.axis === 'y' ? 'vertical' : 'horizontal',
					swapThreshold: 1,
					invertSwap: false,
					scrollSensitivity: 30,
					scrollSpeed: 30,
					bubbleScroll: true,
					dataIdAttr: opts.dataIdAttr,
					store: opts.store,
					onStart: opts.start,
					onEnd: opts.stop,
					onChoose: opts.choose,
					onSort: opts.sort,
					onFilter: opts.filter,
					onMove: opts.move,
					onClone: opts.clone,
					onChange: opts.change,
					onUpdate: opts.update || opts.stop,
					onRemove: opts.remove,
					onUnchoose: opts.unchoose,
					setData: opts.setData
				};

				Object.keys(sortableOptions).forEach(function(key) {
					if (sortableOptions[key] === undefined) {
						delete sortableOptions[key];
					}
				});

				if (opts.items) {
					sortableOptions.draggable = opts.items;
				}

				if (opts.containment === 'parent') {
					sortableOptions.onMove = function() {
						return true;
					};
				}

				instance = new SortableClass(this, sortableOptions);
				$(this).data('sortable-instance', instance);
				$(this).data('sortable', instance);
			} else if (typeof opts === 'string') {
				if (opts === 'refresh' || opts === 'refreshPositions') {
					if (typeof instance.option === 'function') {
						instance.option('disabled', instance.option('disabled'));
					}
				} else if (opts === 'destroy') {
					instance.destroy();
					$(this).removeData('sortable-instance');
					$(this).removeData('sortable');
				}
			}
		});
	};

	return SortableClass;
});
