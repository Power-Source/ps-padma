<?php

class PadmaCompatibilityShortcodes {

	public static function init() {

		add_action('init', array(__CLASS__, 'register_fallback_shortcodes'), 99);
		add_action('admin_notices', array(__CLASS__, 'admin_notice_missing_shortcodes'));

	}


	public static function admin_notice_missing_shortcodes() {

		if ( !is_admin() || class_exists('PSOURCE_Shortcodes') )
			return;

		if ( !current_user_can('install_plugins') )
			return;

		$page = padma_get('page', false);

		if ( !$page || strpos($page, 'padma') !== 0 )
			return;

		echo '<div class="notice notice-warning"><p>';
		echo '<strong>' . esc_html__('Padma Hinweis:', 'padma') . '</strong> ';
		echo esc_html__('Für mehrere Erweiterte Blöcke wird PSOURCE Shortcodes benötigt (z. B. Tabs, Accordion, Spoiler, Quote, Vimeo, YouTube).', 'padma');
		echo '</p></div>';

	}


	public static function register_fallback_shortcodes() {

		if ( class_exists('PSOURCE_Shortcodes') )
			return;

		$shortcodes = array(
			'su_accordion',
			'su_spoiler',
			'su_tabs',
			'su_tab',
			'su_quote',
			'su_vimeo',
			'su_youtube',
			'su_spacer',
			'su_post'
		);

		foreach ( $shortcodes as $shortcode ) {

			if ( !shortcode_exists($shortcode) )
				add_shortcode($shortcode, array(__CLASS__, 'render_fallback_shortcode'));

		}

	}


	public static function render_fallback_shortcode($atts, $content = null, $tag = '') {

		if ( !is_user_logged_in() || !current_user_can('edit_theme_options') )
			return '';

		if ( $tag === 'su_tab' )
			return do_shortcode((string) $content);

		$notice = '<div style="padding:10px 12px;margin:10px 0;border-left:4px solid #dba617;background:#fff8e5;color:#3c434a;font-size:13px;line-height:1.4;">';
		$notice .= esc_html(sprintf(__('Block-Inhalt benötigt das Plugin PSOURCE Shortcodes (%s).', 'padma'), $tag));
		$notice .= '</div>';

		if ( in_array($tag, array('su_accordion', 'su_tabs'), true) && !empty($content) )
			return $notice . do_shortcode((string) $content);

		return $notice;

	}

}
