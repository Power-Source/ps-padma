/**
 * Vanilla JS Tabs Implementation (replaces jQuery UI tabs)
 * Modern, lightweight alternative without jQuery UI dependency
 */

(function(window) {
	'use strict';

	function VanillaTabs(element, options) {
		if (!element) return;
		
		this.element = element;
		this.options = Object.assign({
			active: 0,
			collapsible: false,
			event: 'click',
			disabled: []
		}, options || {});

		this.init();
	}

	VanillaTabs.prototype.init = function() {
		const tabs = this.element;
		const tabNames = tabs.querySelector('ul, [role="tablist"]');
		const tabPanels = Array.from(tabs.querySelectorAll('[role="tabpanel"], .ui-tabs-panel'));
		
		if (!tabNames) return;

		const tabLinks = Array.from(tabNames.querySelectorAll('li > a, [role="tab"]'));

		// Setup roles and IDs
		tabs.classList.add('ui-tabs', 'ui-widget', 'ui-widget-content', 'ui-corner-all');
		tabNames.setAttribute('role', 'tablist');
		
		tabLinks.forEach((link, index) => {
			const panel = tabPanels[index];
			const id = link.getAttribute('aria-controls') || `ui-tabs-${index}-${Math.random().toString(36).substr(2, 9)}`;
			
			link.setAttribute('role', 'tab');
			link.setAttribute('tabindex', '0');
			link.setAttribute('aria-controls', id);
			link.setAttribute('aria-selected', index === this.options.active ? 'true' : 'false');
			
			if (panel) {
				panel.setAttribute('role', 'tabpanel');
				panel.setAttribute('aria-labelledby', link.id || id);
				panel.id = id;
				panel.style.display = index === this.options.active ? 'block' : 'none';
			}

			// Click handler
			link.addEventListener(this.options.event, (e) => {
				e.preventDefault();
				this.activate(index);
			});

			// Keyboard navigation
			link.addEventListener('keydown', (e) => {
				let targetIndex = -1;
				if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
					targetIndex = (index + 1) % tabLinks.length;
				} else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
					targetIndex = (index - 1 + tabLinks.length) % tabLinks.length;
				} else if (e.key === 'Home') {
					targetIndex = 0;
				} else if (e.key === 'End') {
					targetIndex = tabLinks.length - 1;
				}
				
				if (targetIndex >= 0) {
					e.preventDefault();
					tabLinks[targetIndex].focus();
					this.activate(targetIndex);
				}
			});
		});

		this.tabLinks = tabLinks;
		this.tabPanels = tabPanels;
		this.activate(this.options.active);
	};

	VanillaTabs.prototype.activate = function(index) {
		if (index < 0 || index >= this.tabLinks.length) return;
		if (this.options.disabled.includes(index)) return;

		// Deactivate all
		this.tabLinks.forEach((link, i) => {
			link.setAttribute('aria-selected', 'false');
			link.classList.remove('ui-tabs-active', 'ui-state-active');
			if (this.tabPanels[i]) {
				this.tabPanels[i].style.display = 'none';
				this.tabPanels[i].classList.remove('ui-tabs-active');
			}
		});

		// Activate selected
		this.tabLinks[index].setAttribute('aria-selected', 'true');
		this.tabLinks[index].classList.add('ui-tabs-active', 'ui-state-active');
		if (this.tabPanels[index]) {
			this.tabPanels[index].style.display = 'block';
			this.tabPanels[index].classList.add('ui-tabs-active');
		}
	};

	VanillaTabs.prototype.refresh = function() {
		// Refresh tab layout (useful after DOM changes)
		const activeIndex = this.tabLinks.findIndex(link => 
			link.getAttribute('aria-selected') === 'true'
		);
		this.activate(activeIndex >= 0 ? activeIndex : this.options.active);
	};

	VanillaTabs.prototype.option = function(name, value) {
		if (value === undefined) {
			return this.options[name];
		}
		if (name === 'active') {
			this.activate(value);
		}
		this.options[name] = value;
	};

	// jQuery plugin interface for backward compatibility
	if (typeof jQuery !== 'undefined') {
		jQuery.fn.tabs = function(options) {
			return this.each(function() {
				const data = jQuery(this).data('tabs');
				
				if (!data) {
					const instance = new VanillaTabs(this, options);
					jQuery(this).data('tabs', instance);
				} else if (typeof options === 'string') {
					// Method call like .tabs('refresh') or .tabs('option', 'active', 2)
					if (options === 'refresh') {
						data.refresh();
					} else if (options === 'option') {
						// Handle .tabs('option', name, value)
						if (arguments.length === 2) {
							return data.option(arguments[1]);
						} else if (arguments.length === 3) {
							data.option(arguments[1], arguments[2]);
						}
					}
				} else if (typeof options === 'object') {
					// Update options
					Object.assign(data.options, options);
					data.refresh();
				}
			});
		};
	}

	window.VanillaTabs = VanillaTabs;
})(window);
