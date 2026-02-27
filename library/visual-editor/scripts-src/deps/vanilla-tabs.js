/**
 * Vanilla JS Tabs Implementation (jQuery UI compatible subset)
 */

define(['jquery'], function(jQuery) {
	'use strict';

	const $ = jQuery;

	function VanillaTabs(element, options) {
		if (!element) return;

		this.element = element;
		this.$element = $(element);
		this.tabList = null;
		this.tabItems = [];
		this.tabLinks = [];
		this.tabPanels = [];
		this.options = Object.assign({
			active: 0,
			collapsible: false,
			event: 'click',
			disabled: [],
			activate: null
		}, options || {});

		this.init();
	}

	VanillaTabs.prototype.getTabListElement = function() {
		return this.element.querySelector('#panel-top')
			|| this.element.querySelector('[role="tablist"]')
			|| this.element.querySelector('ul');
	};

	VanillaTabs.prototype.bindEvents = function() {
		if (!this.tabList) return;

		if (this._boundTabList && this._boundTabList !== this.tabList) {
			$(this._boundTabList).off('.vanillaTabs');
		}

		this._boundTabList = this.tabList;
		const self = this;

		$(this.tabList).off('.vanillaTabs');

		$(this.tabList).on('click.vanillaTabs', 'a[href^="#"]', function(e) {
			e.preventDefault();
			const index = self.tabLinks.indexOf(this);
			if (index >= 0) {
				self.activate(index);
			}
		});

		$(this.tabList).on('keydown.vanillaTabs', 'a[href^="#"]', function(e) {
			const currentIndex = self.tabLinks.indexOf(this);
			if (currentIndex < 0 || !self.tabLinks.length) return;

			let targetIndex = -1;
			if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
				targetIndex = (currentIndex + 1) % self.tabLinks.length;
			} else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
				targetIndex = (currentIndex - 1 + self.tabLinks.length) % self.tabLinks.length;
			} else if (e.key === 'Home') {
				targetIndex = 0;
			} else if (e.key === 'End') {
				targetIndex = self.tabLinks.length - 1;
			}

			if (targetIndex >= 0) {
				e.preventDefault();
				self.tabLinks[targetIndex].focus();
				self.activate(targetIndex);
			}
		});
	};

	VanillaTabs.prototype.rebuild = function() {
		this.tabItems = [];
		this.tabLinks = [];
		this.tabPanels = [];

		this.tabList = this.getTabListElement();
		if (!this.tabList) return;

		const tabs = this.element;
		tabs.classList.add('ui-tabs', 'ui-widget', 'ui-widget-content', 'ui-corner-all');
		this.tabList.classList.add('ui-tabs-nav', 'ui-helper-reset', 'ui-helper-clearfix', 'ui-widget-header', 'ui-corner-all');
		this.tabList.setAttribute('role', 'tablist');

		const items = Array.from(this.tabList.children).filter((child) => child && child.tagName && child.tagName.toLowerCase() === 'li');
		const links = items
			.map((item) => item.querySelector('a[href^="#"]'))
			.filter(Boolean);

		for (let i = 0; i < links.length; i++) {
			const item = items[i];
			const link = links[i];
			const href = link.getAttribute('href') || '';
			const panelId = href.charAt(0) === '#' ? href.substring(1) : '';
			const panel = panelId ? this.element.querySelector('#' + panelId) : null;

			item.classList.add('ui-state-default', 'ui-corner-top');
			item.setAttribute('role', 'tab');
			item.setAttribute('aria-controls', panelId);
			item.setAttribute('aria-selected', 'false');
			item.setAttribute('tabindex', '-1');

			link.classList.add('ui-tabs-anchor');
			link.setAttribute('role', 'tab');
			link.setAttribute('aria-controls', panelId);
			link.setAttribute('aria-selected', 'false');
			link.setAttribute('tabindex', '0');

			if (panel) {
				panel.classList.add('ui-tabs-panel');
				panel.setAttribute('role', 'tabpanel');
				panel.id = panelId;
			}

			this.tabItems.push(item);
			this.tabLinks.push(link);
			this.tabPanels.push(panel || null);
		}

		this.bindEvents();
	};

	VanillaTabs.prototype.init = function() {
		this.rebuild();
		this.activate(this.options.active, true);
	};

	VanillaTabs.prototype.activate = function(index, suppressCallback) {
		if (!Array.isArray(this.tabLinks) || this.tabLinks.length === 0) return;

		const disabled = Array.isArray(this.options.disabled) ? this.options.disabled : [];
		if (index < 0 || index >= this.tabLinks.length) return;
		if (disabled.includes(index)) return;

		const previousIndex = this.tabLinks.findIndex((link) => link.getAttribute('aria-selected') === 'true');

		for (let i = 0; i < this.tabLinks.length; i++) {
			this.tabLinks[i].setAttribute('aria-selected', 'false');
			if (this.tabItems[i]) {
				this.tabItems[i].setAttribute('aria-selected', 'false');
				this.tabItems[i].setAttribute('tabindex', '-1');
				this.tabItems[i].classList.remove('ui-tabs-active', 'ui-state-active');
			}
			if (this.tabPanels[i]) {
				this.tabPanels[i].style.display = 'none';
				this.tabPanels[i].classList.add('ui-tabs-hide');
				this.tabPanels[i].classList.remove('ui-tabs-active');
			}
		}

		this.tabLinks[index].setAttribute('aria-selected', 'true');
		if (this.tabItems[index]) {
			this.tabItems[index].setAttribute('aria-selected', 'true');
			this.tabItems[index].setAttribute('tabindex', '0');
			this.tabItems[index].classList.add('ui-tabs-active', 'ui-state-active');
		}
		if (this.tabPanels[index]) {
			this.tabPanels[index].style.display = 'block';
			this.tabPanels[index].classList.remove('ui-tabs-hide');
			this.tabPanels[index].classList.add('ui-tabs-active');
		}

		this.options.active = index;

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
		const currentActive = typeof this.options.active === 'number' ? this.options.active : 0;
		this.rebuild();
		this.activate(currentActive, true);
	};

	VanillaTabs.prototype.option = function(name, value) {
		if (value === undefined) {
			return this.options[name];
		}

		if (name === 'active') {
			this.activate(value);
			return;
		}

		this.options[name] = value;
	};

	VanillaTabs.prototype.destroy = function() {
		if (this._boundTabList) {
			$(this._boundTabList).off('.vanillaTabs');
		}
		this.$element.removeData('tabs');
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
					instance.destroy();
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
