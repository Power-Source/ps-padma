/**
 * Vanilla JS Tabs Implementation (replaces jQuery UI tabs)
 * Modern, lightweight alternative without jQuery UI dependency
 */

define(['jquery'], function(jQuery) {
	'use strict';

	const $ = jQuery;

	function VanillaTabs(element, options) {
		if (!element) return;
		
		this.element = element;
		this.$element = $(element);
		this.options = Object.assign({
			active: 0,
			collapsible: false,
			event: 'click',
			disabled: [],
			activate: null
		}, options || {});

		this.init();
	}

	VanillaTabs.prototype.init = function() {
		const tabs = this.element;
		const tabNames = tabs.querySelector('ul, [role="tablist"]');
		
		if (!tabNames) return;

		const tabItems = Array.from(tabNames.querySelectorAll('li'));
		const tabLinks = Array.from(tabNames.querySelectorAll('li > a, [role="tab"]'));

		// Setup roles and IDs
		tabs.classList.add('ui-tabs', 'ui-widget', 'ui-widget-content', 'ui-corner-all');
		tabNames.setAttribute('role', 'tablist');
		
		tabLinks.forEach((link, index) => {
			let panel = null;
			const href = link.getAttribute('href');
			const hrefId = href && href.indexOf('#') === 0 ? href.substring(1) : null;
			if (hrefId) {
				panel = tabs.querySelector('#' + hrefId);
			}
			if (!panel) {
				const panels = Array.from(tabs.querySelectorAll('[role="tabpanel"], .ui-tabs-panel'));
				panel = panels[index] || null;
			}

			const id = (panel && panel.id) || link.getAttribute('aria-controls') || `ui-tabs-${index}-${Math.random().toString(36).substr(2, 9)}`;
			
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

		this.tabItems = tabItems;
		this.tabLinks = tabLinks;
		this.tabPanels = tabLinks.map((link, idx) => {
			const controls = link.getAttribute('aria-controls');
			const panel = controls ? tabs.querySelector('#' + controls) : null;
			if (panel) return panel;
			const panels = Array.from(tabs.querySelectorAll('[role="tabpanel"], .ui-tabs-panel'));
			return panels[idx] || null;
		});
		this.activate(this.options.active, true);
	};

	VanillaTabs.prototype.activate = function(index, suppressCallback) {
		if (index < 0 || index >= this.tabLinks.length) return;
		if (this.options.disabled.includes(index)) return;

		const previousIndex = this.tabLinks.findIndex(link => link.getAttribute('aria-selected') === 'true');

		// Deactivate all
		this.tabLinks.forEach((link, i) => {
			link.setAttribute('aria-selected', 'false');
			if (this.tabItems[i]) {
				this.tabItems[i].classList.remove('ui-tabs-active', 'ui-state-active');
			}
			if (this.tabPanels[i]) {
				this.tabPanels[i].style.display = 'none';
				this.tabPanels[i].classList.remove('ui-tabs-active');
			}
		});

		// Activate selected
		this.tabLinks[index].setAttribute('aria-selected', 'true');
		if (this.tabItems[index]) {
			this.tabItems[index].classList.add('ui-tabs-active', 'ui-state-active');
		}
		if (this.tabPanels[index]) {
			this.tabPanels[index].style.display = 'block';
			this.tabPanels[index].classList.add('ui-tabs-active');
		}

		if (!suppressCallback && typeof this.options.activate === 'function') {
			this.options.activate.call(this.element, null, {
				newTab: this.tabItems[index] ? $(this.tabItems[index]) : $(this.tabLinks[index]),
				newPanel: this.tabPanels[index] ? $(this.tabPanels[index]) : $(),
				oldTab: previousIndex >= 0 && this.tabItems[previousIndex] ? $(this.tabItems[previousIndex]) : $(),
				oldPanel: previousIndex >= 0 && this.tabPanels[previousIndex] ? $(this.tabPanels[previousIndex]) : $()
			});
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

	$.fn.tabs = function(options) {
		const args = Array.prototype.slice.call(arguments, 1);
		let returnValue = this;

		this.each(function() {
			let instance = $(this).data('tabs');

			if (!instance) {
				if (typeof options === 'string') {
					return;
				}
				instance = new VanillaTabs(this, options);
				$(this).data('tabs', instance);
				return;
			}

			if (typeof options === 'string') {
				if (options === 'refresh') {
					instance.refresh();
				} else if (options === 'option') {
					if (args.length === 1) {
						returnValue = instance.option(args[0]);
					} else if (args.length >= 2) {
						instance.option(args[0], args[1]);
					}
				} else if (options === 'destroy') {
					$(this).removeData('tabs');
				}
			} else if (typeof options === 'object') {
				Object.assign(instance.options, options);
				instance.refresh();
			}
		});

		return returnValue;
	};

	window.VanillaTabs = VanillaTabs;
	return VanillaTabs;
});
