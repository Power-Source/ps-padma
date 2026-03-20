<?php

class PadmaAdmin {

	public static function init() {

		self::setup_hooks();

		Padma::load(array(
			'abstract/api-admin-meta-box',
			'admin/admin-write' => true,
			'admin/admin-pages',
			'admin/api-admin-inputs'
		));

		// Shortcode-Builder initialisieren (theme-native, kein Plugin erforderlich)
		new Padma_Shortcode_Generator();

	}


	public static function setup_hooks() {

		/* Actions */
		add_action('admin_init', array(__CLASS__, 'activation'), 1);
		add_action('admin_init', array(__CLASS__, 'enqueue'));
		add_action('admin_init', array(__CLASS__, 'visual_editor_redirect'), 12);

		add_action('init', array(__CLASS__, 'form_action_save'), 12); // Init runs before admin_menu; admin_menu runs before admin_init
		add_action('init', array(__CLASS__, 'form_action_reset'), 12);
		add_action('init', array(__CLASS__, 'form_action_delete_snapshots'), 12);
		add_action('init', array(__CLASS__, 'form_action_replace_url'), 12);

		add_action('admin_menu', array(__CLASS__, 'add_menus'));

		add_action('padma_admin_save_message', array(__CLASS__, 'save_message'));
		add_action('padma_admin_save_error_message', array(__CLASS__, 'save_error_message'));

		add_action('admin_notices', array(__CLASS__, 'notice_no_widgets_or_menus'));
		add_action('admin_notices', array(__CLASS__, 'theme_install_template_notice'));
        add_action('admin_notices', array(__CLASS__, 'responsive_grid_notice'));

        add_action('wp_ajax_padma_dismiss_admin_notice', array(__CLASS__, 'ajax_dismiss_admin_notice'));
        add_action('wp_ajax_padma_enable_responsive_grid', array(__CLASS__, 'ajax_enable_responsive_grid'));
		add_action('wp_ajax_padma_publish_template_catalog', array(__CLASS__, 'ajax_publish_template_catalog'));
		add_action('wp_ajax_padma_catalog_templates', array(__CLASS__, 'ajax_catalog_templates'));
		add_action('wp_ajax_padma_install_catalog_template', array(__CLASS__, 'ajax_install_catalog_template'));
		add_action('wp_ajax_padma_verify_catalog_membership', array(__CLASS__, 'ajax_verify_catalog_membership'));
		add_filter('tiny_mce_before_init', array(__CLASS__, 'tiny_mce_formats'));

	}


	public static function form_action_save() {

		//Form action for all Padma configuration panels.  Not in function/hook so it can load before everything else.
		if ( !padma_post('padma-submit', false))
			return false;

		if ( !wp_verify_nonce(padma_post('padma-admin-nonce', false), 'padma-admin-nonce') ) {

			global $padma_admin_save_message;
			$padma_admin_save_message = 'Security nonce did not match.';

			return false;

		}

		foreach ( padma_post('padma-admin-input', array()) as $option => $value ) {

			PadmaOption::set($option, $value);

		}

		global $padma_admin_save_message;
		$padma_admin_save_message = 'Settings saved.';

		return true;

	}

	public static function form_action_delete_snapshots() {

		global $wpdb;

		if ( ! padma_post( 'padma-delete-snapshots', false ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( padma_post( 'padma-delete-snapshots-nonce', false ), 'padma-delete-snapshots-nonce' ) ) {

			$GLOBALS['padma_admin_save_message'] = 'Security nonce did not match.';

			return false;

		}

		/* Loop through WordPress options and delete the skin options */
	// Table prefix is escaped by wpdb, using variable interpolation is safe here
	$wpdb->query( "TRUNCATE TABLE " . $wpdb->pu_snapshots );
		return true;

	}

	public static function form_action_replace_url() {

		global $wpdb;

		if ( ! padma_post( 'padma-replace-url', false ) ) {
			return false;
		}
		
		if ( ! wp_verify_nonce( padma_post( 'padma-replace-url-nonce', false ), 'padma-replace-url-nonce' ) ) {

			$GLOBALS['padma_admin_save_message'] = 'Security nonce did not match.';
			return false;

		}

	
		$from = ! empty( padma_post('from')) ? padma_post('from') : '';
		$to = ! empty( padma_post('to')) ? padma_post('to') : '';

		try {
			if( padma_replace_urls( $from, $to ) ){
				$GLOBALS['padma_admin_save_message'] = 'URL successfully replaced.';
				return true;		
			}else{
				return false;
			}

		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
			return false;
		}

	}


	public static function form_action_reset() {

		global $wpdb;

		if ( !defined('PADMA_ALLOW_RESET') || PADMA_ALLOW_RESET !== true )
			return false;

		//Form action for all Padma configuration panels.  Not in function/hook so it can load before everything else.
		if ( !padma_post('reset-padma', false) )
			return false;

		//Verify the nonce so other sites can't maliciously reset a Padma installation.
		if ( !wp_verify_nonce(padma_post('padma-reset-nonce', false), 'padma-reset-nonce') ) {

			$GLOBALS['padma_admin_save_message'] = 'Security nonce did not match.';

			return false;

		}

		/* Loop through WordPress options and delete the skin options */
	$wpdb->query( $wpdb->prepare("DELETE FROM " . $wpdb->options . " WHERE option_name = %s", 'padma') );
	$wpdb->query( $wpdb->prepare("DELETE FROM " . $wpdb->options . " WHERE option_name LIKE %s", $wpdb->esc_like('padma_') . '%') );

	$wpdb->query( $wpdb->prepare("DELETE FROM " . $wpdb->options . " WHERE option_name LIKE %s", $wpdb->esc_like('_transient_pu_') . '%') );

	/* Remove Padma post meta */
	$wpdb->query( $wpdb->prepare("DELETE FROM " . $wpdb->postmeta . " WHERE meta_key LIKE %s", $wpdb->esc_like('_pu_') . '%') );
		wp_cache_flush();

		do_action('padma_global_reset');

		$GLOBALS['padma_admin_save_message'] = 'Padma was successfully reset.';

		//This will hide the reset box if set to true.
		$GLOBALS['padma_reset_success'] = true;

		return true;

	}


	public static function activation() {

		if ( !is_admin() || !padma_get('activated') )
			return false;

		global $pagenow;

		if ( $pagenow !== 'themes.php' )
			return false;

		//Since they may be upgrading and files may change, let's clear the cache
		do_action('padma_activation');

		self::activation_redirect();

	}


	public static function activation_redirect() {

		do_action('padma_activation_redirect');

		//If a child theme has been activated rather than Padma, then don't redirect.
		//Let the child theme developer redirect if they want by using the hook above.
		if ( PADMA_CHILD_THEME_ACTIVE === true )
			return false;

		$parent_menu = self::parent_menu();

		//If header were sent, then don't do the redirect
		if ( headers_sent() )
			return false;

		//We're all good, redirect now
		wp_safe_redirect(admin_url('admin.php?page=padma-' . $parent_menu['id']));
		die();

	}


	public static function visual_editor_redirect() {

		if ( isset($_GET['page']) && strpos($_GET['page'], 'padma-visual-editor') !== false && !headers_sent() )
			wp_safe_redirect(home_url() . '/?visual-editor=true');

	}


	public static function add_admin_separator($position){

		global $menu;

		$menu[$position] = array('', 'read', 'separator-padma', '', 'wp-menu-separator padma-separator');

		ksort($menu);

	}


	public static function add_admin_submenu($name, $id, $callback) {

		$parent_menu = self::parent_menu();

		return add_submenu_page('padma-' . $parent_menu['id'], $name, $name, 'manage_options', $id, $callback);

	}


	public static function add_menus(){

		//If the hide menus constant is set to true, don't hide the menus!
		if (defined('PADMA_HIDE_MENUS') && PADMA_HIDE_MENUS === true)
		 	return false;

		//If user cannot access the admin panels, then don't bother running these functions
		if ( !PadmaCapabilities::can_user_visually_edit() )
			return false;

		$menu_name = ( PadmaOption::get('hide-menu-version-number', false, true) == true ) ? PadmaSettings::get('menu-name') : PadmaSettings::get('menu-name') . ' ' . PADMA_VERSION;

		$icon = (version_compare($GLOBALS['wp_version'], '3.8', '>=') && get_user_option('admin_color') != 'light') ? 'padma-32-grey.png' : 'padma-16.png';
		$icon_url = padma_url() . '/library/admin/images/' . $icon;

		$parent_menu = self::parent_menu();

		self::add_admin_separator(48);

		add_menu_page($parent_menu['name'], $menu_name, 'manage_options', 'padma-' . $parent_menu['id'], $parent_menu['callback'], $icon_url, 49);

			switch ( $parent_menu['id'] ) {

				case 'getting-started':
					self::add_admin_submenu( __('Erste Schritte','padma'), 'padma-getting-started', array('PadmaAdminPages', 'getting_started'));
					self::add_admin_submenu( __('Visual Editor','padma'), 'padma-visual-editor', array('PadmaAdminPages', 'visual_editor'));
					self::add_admin_submenu( __('Templates','padma'), 'padma-templates', array('PadmaAdminPages', 'templates'));
					self::add_admin_submenu( __('Erweiterte Blöcke','padma'), 'padma-advanced-blocks', array('PadmaAdminPages', 'advanced_blocks'));
					self::add_admin_submenu( __('SEO','padma'), 'padma-seo', array('PadmaAdminPages', 'seo'));
					self::add_admin_submenu( __('Optionen','padma'), 'padma-options', array('PadmaAdminPages', 'options'));
					self::add_admin_submenu( __('Tools','padma'), 'padma-tools', array('PadmaAdminPages', 'tools'));
				break;

				case 'visual-editor':
					self::add_admin_submenu( __('Visual Editor','padma'), 'padma-visual-editor', array('PadmaAdminPages', 'visual_editor'));
					self::add_admin_submenu( __('Templates','padma'), 'padma-templates', array('PadmaAdminPages', 'templates'));
					self::add_admin_submenu( __('Erweiterte Blöcke','padma'), 'padma-advanced-blocks', array('PadmaAdminPages', 'advanced_blocks'));
					self::add_admin_submenu( __('SEO','padma'), 'padma-seo', array('PadmaAdminPages', 'seo'));
					self::add_admin_submenu( __('Optionen','padma'), 'padma-options', array('PadmaAdminPages', 'options'));
					self::add_admin_submenu( __('Tools','padma'), 'padma-tools', array('PadmaAdminPages', 'tools'));
				break;

				case 'options':
					self::add_admin_submenu( __('Optionen','padma'), 'padma-options', array('PadmaAdminPages', 'options'));
					self::add_admin_submenu( __('Visual Editor','padma'), 'padma-visual-editor', array('PadmaAdminPages', 'visual_editor'));
					self::add_admin_submenu( __('Templates','padma'), 'padma-templates', array('PadmaAdminPages', 'templates'));
					self::add_admin_submenu( __('Erweiterte Blöcke','padma'), 'padma-advanced-blocks', array('PadmaAdminPages', 'advanced_blocks'));
					self::add_admin_submenu( __('SEO','padma'), 'padma-seo', array('PadmaAdminPages', 'seo'));
					self::add_admin_submenu( __('Tools','padma'), 'padma-tools', array('PadmaAdminPages', 'tools'));
				break;

			}

	}


	public static function parent_menu() {

		$menu_setup = PadmaOption::get('menu-setup', false, 'getting-started');

		/* Figure out the primary page */
		switch ( $menu_setup ) {

			case 'getting-started':
				$parent_menu = array(
					'id' => 'getting-started',
					'name' => 'Erste Schritte',
					'callback' => array('PadmaAdminPages', 'getting_started')
				);
			break;

			case 'options':
				$parent_menu = array(
					'id' => 'options',
					'name' => 'Options',
					'callback' => array('PadmaAdminPages', 'options')
				);
			break;

			default:
				$parent_menu = array(
					'id' => 'visual-editor',
					'name' => 'Visual Editor',
					'callback' => array( 'PadmaAdminPages', 'visual_editor' )
				);
			break;

		}

		return $parent_menu;

	}


	public static function enqueue() {

	// Modal-Styles und -Script für native Modals
	wp_enqueue_style('ps_modal_css', padma_url() . '/library/common/css/modal.css');
	wp_enqueue_script('ps_modal_js', padma_url() . '/library/common/js/modal.js', array(), false, true);
	wp_enqueue_script('ps_modal_init', padma_url() . '/library/common/js/modal-init.js', array('ps_modal_js'), false, true);

		global $pagenow;

		/* Global */
		wp_enqueue_style('padma_admin_global', padma_url() . '/library/admin/css/admin-padma-global.css');
        wp_enqueue_script('padma_admin_js', padma_url() . '/library/admin/js/admin-padma.js', array('jquery'));



		wp_localize_script('padma_admin_js', 'Padma', array(
			'ajaxURL' 			=> admin_url('admin-ajax.php'),				
			'security' 			=> wp_create_nonce('padma-visual-editor-ajax'),				
		));

        /* General Padma admin CSS/JS */
		if (strpos(padma_get('page') ?? '', 'padma') !== false) {

			wp_enqueue_script('padma_jquery_scrollto', padma_url() . '/library/admin/js/jquery.scrollto.js', array('jquery'));
			wp_enqueue_script('padma_jquery_tabby', padma_url() . '/library/admin/js/jquery.tabby.js', array('jquery'));
			wp_enqueue_script('padma_jquery_qtip', padma_url() . '/library/admin/js/jquery.qtip.js', array('jquery'));
            wp_enqueue_script('padma_admin_js', padma_url() . '/library/admin/js/admin-padma.js', array('jquery', 'padma_jquery_qtip'));

            wp_enqueue_style('padma_admin', padma_url() . '/library/admin/css/admin-padma.css');
			wp_enqueue_style('padma_alerts', padma_url() . '/library/media/css/alerts.css');

		}

		/* Templates */
		if ( padma_get('page') == 'padma-templates' ) {

			wp_enqueue_script('padma_knockout', padma_url() . '/library/admin/js/knockout.js', array('jquery'));
			wp_enqueue_script('padma_admin_templates', padma_url() . '/library/admin/js/admin-templates.js', array('jquery'));

			wp_localize_script('padma_admin_templates', 'Padma', array(

				'ajaxURL' 			=> admin_url('admin-ajax.php'),
				'apiURL' 			=> PADMA_API_URL,
				'security' 			=> wp_create_nonce('padma-visual-editor-ajax'),
				'templates' 		=> PadmaTemplates::get_all(),
				'templateActive' 	=> PadmaTemplates::get_active(),
				'catalogIdentity' => self::get_catalog_membership_identity(),
				'viewModels' 		=> array()
			));

			// Thickbox entfernt, native Modal-Lösung wird verwendet
			wp_enqueue_media();

		}

		/* Advanced Blocks */
		if ( padma_get('page') == 'padma-advanced-blocks' ) {

			wp_enqueue_style('padma_admin_advanced_blocks', padma_url() . '/library/admin/css/admin-advanced-blocks.css');
			wp_enqueue_script('padma_admin_advanced_blocks', padma_url() . '/library/admin/js/admin-advanced-blocks.js', array('jquery'), PADMA_VERSION, true);
			wp_localize_script('padma_admin_advanced_blocks', 'padmaAdvancedBlocks', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'activating' => __('Aktiviere...', 'padma'),
			'activated' => __('Aktiviert! Seite wird neu geladen...', 'padma'),
			'error' => __('Fehler bei der Aktivierung', 'padma')
		));

	}

	/* Main Admin Pages - Write */
	if ( padma_get('page') == 'getting-started' ) {

		wp_enqueue_style('padma_admin_write', padma_url() . '/library/admin/css/admin-write.css');
		wp_enqueue_style('padma_alerts', padma_url() . '/library/media/css/alerts.css');
		wp_enqueue_script('padma_admin_write', padma_url() . '/library/admin/js/admin-write.js', array('jquery'));
                $css_src = includes_url('css/') . 'editor.css';
                wp_register_style('tinymce_css', $css_src);
                wp_enqueue_style('tinymce_css');

	}

	/* Post/Page Edit Screens (for Metaboxes) */
	if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {

		wp_enqueue_style('padma_admin_write', padma_url() . '/library/admin/css/admin-write.css');

	}

	/* SEO Suite */
	if ( padma_get('page') == 'padma-seo' ) {

		wp_enqueue_style('padma_admin_seo', padma_url() . '/library/admin/css/admin-seo.css');

	}

	/* Auto Updater */
	if ( $pagenow === 'update-core.php' ) {

		wp_enqueue_style('padma_admin', padma_url() . '/library/admin/css/admin-padma.css');
		wp_enqueue_style('padma_alerts', padma_url() . '/library/media/css/alerts.css');

	}

}


	public static function save_message() {

		global $padma_admin_save_message;

		if ( !isset($padma_admin_save_message) || $padma_admin_save_message == false )
			return false;

		echo '<div id="setting-error-settings_updated" class="updated settings-error"><p>' . $padma_admin_save_message . '</p></div>';

	}


	public static function save_error_message() {

		global $padma_admin_save_error_message;

		if ( !isset($padma_admin_save_error_message) || $padma_admin_save_error_message == false )
			return false;

		echo '<div id="setting-error-settings_error" class="error settings-error"><p>' . $padma_admin_save_error_message . '</p></div>';

	}


	public static function notice_no_widgets_or_menus() {

		global $pagenow;

		if ( $pagenow != 'widgets.php' && $pagenow != 'nav-menus.php' )
			return false;

		$grid_mode_url = add_query_arg(array('visual-editor' => 'true', 'visual-editor-mode' => 'grid'), home_url());

		//Show the widgets message if no widget blocks exist.
		if ( $pagenow == 'widgets.php' ) {

			$widget_area_blocks = PadmaBlocksData::get_blocks_by_type('widget-area');

			if ( !empty($widget_area_blocks) )
				return;

			if ( !current_theme_supports('padma-grid') )
				return;

			echo '<div class="updated" style="margin-top: 15px;">
			       <p>Padma has detected that you have no Widget Area blocks.  If you wish to use the WordPress widgets system with Padma, please add a Widget Area block in the <a href="' . $grid_mode_url . '" target="_blank">Visual Editor: Grid</a>.</p>

					<style type="text/css">
						div.error.below-h2 { display: none; }
						div.error.below-h2 + p { display: none; }
					</style>
			    </div>';

		}

		//Show the navigation menus message if no navigation blocks exist.
		if ( $pagenow == 'nav-menus.php' ) {

			$navigation_blocks = PadmaBlocksData::get_blocks_by_type('navigation');

			if ( !empty($navigation_blocks) )
				return;

			if ( !current_theme_supports('padma-grid') )
				return;

			echo '<div class="updated">
			       <p>' . sprintf( __('Padma has detected that you have no Navigation blocks. If you wish to use the WordPress menus system with Padma, please add a Navigation block in the <a href="%s" target="_blank">Visual Editor: Grid</a>.', 'padma'), $grid_mode_url ) . '</p>
			    </div>';

		}

	}


	public static function theme_install_template_notice() {

		global $pagenow;

		if ( $pagenow != 'theme-install.php' )
			return false;

		echo '<div class="error">
				<h3>' . __('Are you trying to install a Padma Template?','padma') . '</h3>
			  	 <p>' . sprintf( __('Please go to <a href="%s">Padma &rsaquo; Templates</a> to install Templates.','padma'), admin_url('admin.php?page=padma-templates') ) . '</p>
			</div>';


	}


    public static function responsive_grid_notice() {

        $dismissed_notices = PadmaOption::get('dismissed-notices', false, array());

        if ( PadmaSkinOption::get('enable-responsive-grid', false, true) || in_array('responsive-grid', $dismissed_notices) ) {
            return false;
        }

        echo '<div id="padma-responsive-grid-notice" data-padma-notice="responsive-grid" class="notice notice-warning is-dismissible" style="padding-top: 0.5em;padding-bottom: 0.5em;">
				<h3 style="margin: 0.5em 0">' . __('Important! Your site is currently not mobile-friendly.','padma') . '</h3>
                <p>' . __('Google now penalizes websites that are not mobile-friendly. Enabling the Responsive Grid will make your website mobile-friendly in most cases.','padma') . '</p>
                <p>' . __('<strong>Please note:</strong> Enabling the responsive grid can cause styling and layout changes for some websites. You can always disable Responsive Grid under the Grid mode in the Visual Editor.','padma') . '</p>
                <p><button class="button-primary">' . __('Enable Responsive Grid','padma') . '</button>&emsp;&emsp;<button class="button-secondary padma-dismiss-notice">' . __('Dismiss','padma') . '</button></p>
			</div>';

    }


	public static function show_header($title = false) {

		echo '<div class="wrap padma-page">';

		if ( $title )
			echo '<h2>' . $title . '</h2>';

	}


	public static function show_footer() {

		echo '</div><!-- #wrapper -->';

	}


	public static function row_action_visual_editor($actions, $item) {

		if ( !PadmaCapabilities::can_user_visually_edit() )
			return $actions;

		/* Post */
		if ( isset($item->post_status) ) {

			if ( $item->post_status != 'publish' )
				return $actions;

			$post_type = get_post_type_object($item->post_type);

			if ( !$post_type->public )
				return $actions;

			$layout_id = 'single' . PadmaLayout::$sep . $item->post_type . PadmaLayout::$sep . $item->ID;

			if ( get_option('show_on_front') === 'page' ) {

				if ( $item->ID == get_option('page_on_front') )
					$layout_id = 'front_page';

				if ( $item->ID == get_option('page_for_posts') )
					$layout_id = 'index';

			}

		/* Category */
		} elseif ( isset($item->term_id) && $item->taxonomy == 'category' ) {

			$layout_id = 'archive' . PadmaLayout::$sep . 'category' . PadmaLayout::$sep . $item->term_id;

		/* Post Tag */
		} elseif ( isset($item->term_id) && $item->taxonomy == 'post_tag' ) {

			$layout_id = 'archive' . PadmaLayout::$sep . 'post_tag' . PadmaLayout::$sep . $item->term_id;

		/* Taxonomy */
		} elseif ( isset($item->term_id) ) {

			$layout_id = 'archive' . PadmaLayout::$sep . 'taxonomy' . PadmaLayout::$sep . $item->taxonomy . PadmaLayout::$sep . $item->term_id;

		}

		$visual_editor_url = home_url('/?visual-editor=true&ve-layout=' . urlencode($layout_id));

		$actions['pu-visual-editor'] = '<a href="' . $visual_editor_url . '" title="' . __('Open in Padma Visual Editor','padma') . '" rel="permalink" target="_blank">' . __('Open in Padma Visual Editor','padma') . '</a>';

		return $actions;

	}


	public static function tiny_mce_buttons($buttons) {

		array_unshift( $buttons, 'styleselect' );
		return $buttons;

	}


	public static function tiny_mce_formats($init_array) {

		$style_formats = array(
			array(
				'title' => 'Alerts',
				'items' => array(
					array(
						'title' => 'Red',
						'block' => 'div',
						'classes' => 'alert alert-red',
						'wrapper' => true
					),

					array(
						'title' => 'Yellow',
						'block' => 'div',
						'classes' => 'alert alert-yellow',
						'wrapper' => true
					),

					array(
						'title' => 'Green',
						'block' => 'div',
						'classes' => 'alert alert-green',
						'wrapper' => true
					),

					array(
						'title' => 'Blue',
						'block' => 'div',
						'classes' => 'alert alert-blue',
						'wrapper' => true
					),

					array(
						'title' => 'Gray',
						'block' => 'div',
						'classes' => 'alert alert-gray',
						'wrapper' => true
					)
				)
			)
		);

		if ( !empty( $init_array['style_formats'] ) ) {

			// json decode wp array
			$jd_orig_array = json_decode( $init_array['style_formats'], true );

			// merge new array with wp array (json encoded)
			$new_merge = json_encode( array_merge( $jd_orig_array, $style_formats ) );

			// populate back into function
			$init_array['style_formats'] = $new_merge;

		} else {

			$init_array['style_formats'] = json_encode($style_formats);

		}

		return $init_array;

	}


    public static function ajax_dismiss_admin_notice() {

        $notice_to_dismiss 		= padma_post('notice-to-dismiss');
        $dismissed_notices 		= PadmaOption::get('dismissed-notices', false, array());
        $dismissed_notices[] 	= $notice_to_dismiss;

        return PadmaOption::set('dismissed-notices', array_unique($dismissed_notices));

    }


    public static function ajax_enable_responsive_grid() {

        return PadmaSkinOption::set('enable-responsive-grid', true);

    }


	private static function get_catalog_membership_identity() {

		$defaults = array(
			'user_id' => 0,
			'remote_login' => '',
			'email' => '',
			'verified' => false,
			'verified_at' => '',
			'membership_ok' => false,
			'auth_source' => '',
			'admin_bypass' => false,
			'message' => '',
		);

		$identity = get_option('padma_catalog_membership_identity', array());

		if ( !is_array($identity) ) {
			$identity = array();
		}

		$identity = array_merge($defaults, $identity);

		$auth_source = sanitize_key((string)($identity['auth_source'] ?? ''));
		$is_verified_remote_identity = (
			$auth_source === 'remote_xmlrpc'
			&& !empty($identity['verified'])
			&& !empty($identity['membership_ok'])
			&& absint($identity['user_id']) > 0
		);

		if ( !$is_verified_remote_identity ) {
			$had_stale_mapping = absint($identity['user_id']) > 0 || !empty($identity['verified']) || !empty($identity['membership_ok']);
			$identity['user_id'] = 0;
			$identity['verified'] = false;
			$identity['verified_at'] = '';
			$identity['membership_ok'] = false;
			$identity['admin_bypass'] = false;
			$identity['auth_source'] = $auth_source;

			if ( $had_stale_mapping && empty($identity['message']) ) {
				$identity['message'] = 'Verbindung bitte neu mit deinen PS-Account-Daten bestaetigen.';
			}
		}

		return $identity;

	}


	private static function get_catalog_remote_user_id() {

		$identity = self::get_catalog_membership_identity();

		if ( empty($identity['verified']) || empty($identity['membership_ok']) ) {
			return 0;
		}

		if ( sanitize_key((string)($identity['auth_source'] ?? '')) !== 'remote_xmlrpc' ) {
			return 0;
		}

		$user_id = isset($identity['user_id']) ? absint($identity['user_id']) : 0;

		return $user_id > 0 ? $user_id : 0;

	}


	private static function get_client_ip() {

		$candidates = array(
			$_SERVER['HTTP_CF_CONNECTING_IP'] ?? '',
			$_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
			$_SERVER['HTTP_X_REAL_IP'] ?? '',
			$_SERVER['REMOTE_ADDR'] ?? '',
		);

		foreach ( $candidates as $candidate ) {
			if ( empty($candidate) ) {
				continue;
			}

			$parts = array_map('trim', explode(',', (string)$candidate));
			$ip = $parts[0] ?? '';

			if ( filter_var($ip, FILTER_VALIDATE_IP) ) {
				return $ip;
			}
		}

		return '0.0.0.0';

	}


	private static function get_verify_rate_limit_key($login) {

		$normalized_login = strtolower(trim((string)$login));
		$ip = self::get_client_ip();

		return 'padma_catalog_verify_' . md5($normalized_login . '|' . $ip);

	}


	private static function check_verify_rate_limit($login) {

		$limit = 5;
		$window = 10 * MINUTE_IN_SECONDS;
		$key = self::get_verify_rate_limit_key($login);
		$data = get_transient($key);

		if ( !is_array($data) ) {
			return array('ok' => true, 'key' => $key, 'limit' => $limit, 'window' => $window);
		}

		$count = isset($data['count']) ? absint($data['count']) : 0;
		$expires = isset($data['expires']) ? absint($data['expires']) : 0;
		$now = time();

		if ( $expires <= $now ) {
			delete_transient($key);
			return array('ok' => true, 'key' => $key, 'limit' => $limit, 'window' => $window);
		}

		if ( $count >= $limit ) {
			return array(
				'ok' => false,
				'retry_after' => max(1, $expires - $now),
				'key' => $key,
			);
		}

		return array('ok' => true, 'key' => $key, 'limit' => $limit, 'window' => $window);

	}


	private static function bump_verify_rate_limit($key, $window) {

		$data = get_transient($key);
		$now = time();

		if ( !is_array($data) || !isset($data['expires']) || absint($data['expires']) <= $now ) {
			$data = array(
				'count' => 1,
				'expires' => $now + absint($window),
			);
		} else {
			$data['count'] = absint($data['count'] ?? 0) + 1;
		}

		set_transient($key, $data, max(60, absint($data['expires']) - $now));

	}


	private static function clear_verify_rate_limit($key) {

		if ( !empty($key) ) {
			delete_transient($key);
		}

	}


	private static function xmlrpc_encode_value($value) {

		if ( is_int($value) ) {
			return '<int>' . $value . '</int>';
		}

		if ( is_bool($value) ) {
			return '<boolean>' . ($value ? '1' : '0') . '</boolean>';
		}

		return '<string>' . esc_html((string)$value) . '</string>';

	}


	private static function xmlrpc_build_request_xml($method, $params = array()) {

		$xml = '<?xml version="1.0"?><methodCall><methodName>' . esc_html((string)$method) . '</methodName><params>';

		foreach ( $params as $param ) {
			$xml .= '<param><value>' . self::xmlrpc_encode_value($param) . '</value></param>';
		}

		$xml .= '</params></methodCall>';

		return $xml;

	}


	private static function xmlrpc_parse_response($xml_body) {

		$xml_body = (string)$xml_body;
		$xml_body = preg_replace('/^\xEF\xBB\xBF/', '', $xml_body);
		$xml_body = trim($xml_body);

		if ( $xml_body === '' ) {
			return array(
				'ok' => false,
				'type' => 'parse',
				'error' => 'Leere XML-RPC Antwort erhalten.',
			);
		}

		$method_response_pos = stripos($xml_body, '<methodResponse');
		if ( $method_response_pos === false ) {
			$snippet = wp_strip_all_tags(substr($xml_body, 0, 220));
			$snippet = preg_replace('/\s+/', ' ', (string)$snippet);

			return array(
				'ok' => false,
				'type' => 'unexpected_content',
				'error' => 'Nerdservice liefert kein gueltiges XML-RPC, sondern unerwarteten Inhalt.',
				'raw_snippet' => trim((string)$snippet),
			);
		}

		if ( $method_response_pos > 0 ) {
			$xml_body = substr($xml_body, $method_response_pos);
		}

		$method_response_end_pos = stripos($xml_body, '</methodResponse>');
		if ( $method_response_end_pos !== false ) {
			$xml_body = substr($xml_body, 0, $method_response_end_pos + strlen('</methodResponse>'));
		}

		if ( !class_exists('IXR_Message') ) {
			require_once ABSPATH . WPINC . '/class-IXR.php';
		}

		$message = new IXR_Message((string)$xml_body);
		if ( !$message->parse() ) {
			$snippet = wp_strip_all_tags(substr($xml_body, 0, 220));
			$snippet = preg_replace('/\s+/', ' ', (string)$snippet);
			return array(
				'ok' => false,
				'type' => 'parse',
				'error' => 'XML-RPC Antwort konnte nicht verarbeitet werden.',
				'raw_snippet' => trim((string)$snippet),
			);
		}

		if ( $message->messageType === 'fault' ) {
			$fault = (isset($message->params[0]) && is_array($message->params[0])) ? $message->params[0] : array();
			return array(
				'ok' => false,
				'type' => 'fault',
				'fault_code' => intval($fault['faultCode'] ?? 0),
				'fault_string' => (string)($fault['faultString'] ?? 'Unbekannter XML-RPC Fehler.'),
			);
		}

		if ( !isset($message->params[0]) ) {
			return array(
				'ok' => false,
				'type' => 'parse',
				'error' => 'XML-RPC Antwort enthaelt keine Daten.',
			);
		}

		return array(
			'ok' => true,
			'response' => $message->params[0],
		);

	}


	private static function xmlrpc_post($xmlrpc_url, $method, $params = array()) {

		$xml = self::xmlrpc_build_request_xml($method, $params);
		$headers = array(
			'Content-Type' => 'text/xml',
			'Accept' => 'text/xml',
		);

		$catalog_bypass_key = defined('PADMA_MEMBERSHIP_AUTH_BYPASS_KEY')
			? (string) PADMA_MEMBERSHIP_AUTH_BYPASS_KEY
			: '';

		if ( $catalog_bypass_key !== '' ) {
			$headers['X-Padma-Catalog-Bypass'] = $catalog_bypass_key;
		}

		$response = wp_remote_post($xmlrpc_url, array(
			'timeout' => 20,
			'redirection' => 5,
			'headers' => $headers,
			'body' => $xml,
		));

		if ( is_wp_error($response) ) {
			return array(
				'ok' => false,
				'type' => 'transport',
				'http_status' => 0,
				'error' => $response->get_error_message(),
			);
		}

		$http_status = (int)wp_remote_retrieve_response_code($response);
		$body = (string)wp_remote_retrieve_body($response);

		if ( $http_status < 200 || $http_status >= 300 ) {
			return array(
				'ok' => false,
				'type' => 'transport',
				'http_status' => $http_status,
				'error' => 'HTTP status ' . $http_status,
			);
		}

		$parsed = self::xmlrpc_parse_response($body);

		if ( empty($parsed['ok']) ) {
			$parsed['http_status'] = $http_status;
			return $parsed;
		}

		return array(
			'ok' => true,
			'http_status' => $http_status,
			'response' => $parsed['response'],
		);

	}


	private static function authenticate_membership_account($login, $password) {

		$login = trim((string)$login);
		$password = (string)$password;

		if ( $login === '' || $password === '' ) {
			return array(
				'ok' => false,
				'error' => 'Bitte Benutzername/E-Mail und Passwort eingeben.',
			);
		}

		$xmlrpc_url = defined('PADMA_MEMBERSHIP_AUTH_XMLRPC')
			? (string)PADMA_MEMBERSHIP_AUTH_XMLRPC
			: 'https://nerdservice.eimen.net/xmlrpc.php';

		$attempt_login = $login;
		$users_blogs_request = self::xmlrpc_post($xmlrpc_url, 'wp.getUsersBlogs', array($attempt_login, $password));

		/* If an email was entered, retry once with local-part as username hint. */
		if ( empty($users_blogs_request['ok']) && is_email($login) ) {
			$local_part = strstr($login, '@', true);
			if ( is_string($local_part) && $local_part !== '' ) {
				$attempt_login = $local_part;
				$users_blogs_request = self::xmlrpc_post($xmlrpc_url, 'wp.getUsersBlogs', array($attempt_login, $password));
			}
		}

		if ( empty($users_blogs_request['ok']) ) {
			$request_type = (string)($users_blogs_request['type'] ?? '');
			$fault_code = intval($users_blogs_request['fault_code'] ?? 0);
			$fault_string = (string)($users_blogs_request['fault_string'] ?? $users_blogs_request['error'] ?? '');
			$raw_snippet = (string)($users_blogs_request['raw_snippet'] ?? '');
			$http_status = intval($users_blogs_request['http_status'] ?? 0);
			$error_message = strtolower($fault_string);

			if ( $request_type === 'transport' ) {
				return array(
					'ok' => false,
					'error' => 'Anmeldung von nerdservice blockiert (XML-RPC Transportfehler). Das ist kein lokaler WordPress-Login-Fehler.',
					'error_code' => 'xmlrpc_transport_blocked',
					'xmlrpc_fault_code' => -32300,
					'xmlrpc_fault_message' => $fault_string,
					'xmlrpc_http_status' => $http_status,
				);
			}

			if ( $request_type === 'unexpected_content' || $request_type === 'parse' ) {
				$snippet_lower = strtolower($raw_snippet);
				$is_blocked_ip = (
					strpos($snippet_lower, 'ps security') !== false
					&& (
						strpos($snippet_lower, 'blocked your ip') !== false
						|| strpos($snippet_lower, 'blocked your ip from accessing this website') !== false
					)
				);

				if ( $is_blocked_ip ) {
					$message = 'Nerdservice verweigert den Zugriff von deiner Server-IP (PS Security Blocklist). Die IP muss auf nerdservice in die Allowlist.';

					return array(
						'ok' => false,
						'error' => $message,
						'error_code' => 'xmlrpc_ip_blocked',
						'xmlrpc_fault_code' => -32302,
						'xmlrpc_fault_message' => $fault_string,
						'xmlrpc_http_status' => $http_status,
					);
				}

				$message = 'Nerdservice-Antwort ist kein gueltiges XML-RPC (moegliche Security/Challenge-Interception).';
				if ( !empty($raw_snippet) ) {
					$message .= ' Antwortauszug: ' . $raw_snippet;
				}

				return array(
					'ok' => false,
					'error' => $message,
					'error_code' => 'xmlrpc_unexpected_content',
					'xmlrpc_fault_code' => -32301,
					'xmlrpc_fault_message' => $fault_string,
					'xmlrpc_http_status' => $http_status,
				);
			}

			if ( strpos($error_message, 'xml-rpc') !== false && (strpos($error_message, 'disabled') !== false || strpos($error_message, 'forbidden') !== false || strpos($error_message, 'not available') !== false) ) {
				return array(
					'ok' => false,
					'error' => 'Anmeldung aktuell nicht moeglich: XML-RPC ist auf der Zielseite blockiert. Bitte Admin kontaktieren.',
					'error_code' => 'xmlrpc_blocked',
					'xmlrpc_fault_code' => $fault_code,
					'xmlrpc_fault_message' => $fault_string,
					'xmlrpc_http_status' => $http_status,
				);
			}

			if ( $request_type === 'fault' && $fault_code === 403 ) {
				$invalid_credentials_message = 'Anmeldung fehlgeschlagen. Benutzername/E-Mail oder Passwort sind nicht korrekt.';

				if ( is_email($login) ) {
					$invalid_credentials_message = 'Anmeldung fehlgeschlagen. Auf nerdservice wird fuer XML-RPC der exakte Benutzername benoetigt (kein E-Mail-Alias), plus korrektes Passwort.';
				}

				return array(
					'ok' => false,
					'error' => $invalid_credentials_message,
					'error_code' => 'invalid_credentials',
					'xmlrpc_fault_code' => $fault_code,
					'xmlrpc_fault_message' => $fault_string,
					'xmlrpc_http_status' => $http_status,
				);
			}

			return array(
				'ok' => false,
				'error' => 'Anmeldung fehlgeschlagen. Bitte pruefe die Zugangsdaten deines PS-Accounts auf nerdservice.eimen.net.',
				'xmlrpc_fault_code' => $fault_code,
				'xmlrpc_fault_message' => $fault_string,
				'xmlrpc_http_status' => $http_status,
			);
		}

		$blogs = $users_blogs_request['response'];
		if ( !is_array($blogs) || empty($blogs) ) {
			return array(
				'ok' => false,
				'error' => 'Anmeldung fehlgeschlagen. Kein gueltiger Account gefunden.',
			);
		}

		$blog_id = 1;
		$is_site_admin = false;
		if ( isset($blogs[0]['blogid']) ) {
			$blog_id = absint($blogs[0]['blogid']);
		}

		if ( isset($blogs[0]['isAdmin']) ) {
			$is_site_admin = !empty($blogs[0]['isAdmin']);
		} elseif ( isset($blogs[0]['is_admin']) ) {
			$is_site_admin = !empty($blogs[0]['is_admin']);
		}

		$profile = null;
		$profile_request = self::xmlrpc_post($xmlrpc_url, 'wp.getProfile', array($blog_id, $attempt_login, $password));
		if ( !empty($profile_request['ok']) ) {
			$profile = $profile_request['response'];
		} elseif ( !empty($profile_request['fault_code']) || !empty($profile_request['error']) ) {
			return array(
				'ok' => false,
				'error' => 'Anmeldung erfolgreich, aber Account-Profil konnte nicht geladen werden.',
				'error_code' => 'profile_fetch_failed',
				'xmlrpc_fault_code' => intval($profile_request['fault_code'] ?? -32300),
				'xmlrpc_fault_message' => (string)($profile_request['fault_string'] ?? $profile_request['error'] ?? ''),
				'xmlrpc_http_status' => intval($profile_request['http_status'] ?? 0),
			);
		}

		$user_id = 0;
		$email = '';
		$remote_login = $login;

		if ( is_array($profile) ) {
			if ( isset($profile['user_id']) ) {
				$user_id = absint($profile['user_id']);
			}
			if ( isset($profile['email']) ) {
				$email = sanitize_email((string)$profile['email']);
			}
			if ( isset($profile['username']) ) {
				$remote_login = sanitize_text_field((string)$profile['username']);
			}
		}

		if ( $user_id <= 0 ) {
			return array(
				'ok' => false,
				'error' => 'Account-ID konnte nicht ermittelt werden.',
			);
		}

		if ( empty($email) && is_email($login) ) {
			$email = sanitize_email($login);
		}

		if ( empty($remote_login) ) {
			$remote_login = $attempt_login;
		}

		return array(
			'ok' => true,
			'user_id' => $user_id,
			'email' => $email,
			'remote_login' => $remote_login,
			'is_site_admin' => $is_site_admin,
		);

	}


	private static function verify_membership_identity($remote_user_id, $email = '', $is_site_admin = false) {

		$base = defined('PADMA_MEMBERSHIP_API_BASE')
			? untrailingslashit((string)PADMA_MEMBERSHIP_API_BASE)
			: 'https://nerdservice.eimen.net/wp-json/membership2/v1';

		$pass_key = defined('PADMA_MEMBERSHIP_API_PASS_KEY')
			? (string)PADMA_MEMBERSHIP_API_PASS_KEY
			: '';

		$required_membership_id = defined('PADMA_MEMBERSHIP_REQUIRED_ID')
			? absint(PADMA_MEMBERSHIP_REQUIRED_ID)
			: 582;

		if ( empty($pass_key) ) {
			return array(
				'ok' => false,
				'error' => 'PADMA_MEMBERSHIP_API_PASS_KEY ist nicht gesetzt.',
			);
		}

		$member_url = add_query_arg(array(
			'user_id' => absint($remote_user_id),
			'pass_key' => $pass_key,
		), $base . '/member/get');

		$request_headers = array('Accept' => 'application/json');

		$catalog_bypass_key = defined('PADMA_MEMBERSHIP_AUTH_BYPASS_KEY')
			? (string) PADMA_MEMBERSHIP_AUTH_BYPASS_KEY
			: '';

		if ( $catalog_bypass_key !== '' ) {
			$request_headers['X-Padma-Catalog-Bypass'] = $catalog_bypass_key;
		}

		$member_response = wp_remote_get($member_url, array(
			'timeout' => 20,
			'headers' => $request_headers,
		));

		if ( is_wp_error($member_response) ) {
			return array(
				'ok' => false,
				'error' => 'Mitglied konnte nicht geladen werden: ' . $member_response->get_error_message(),
			);
		}

		$member_payload = json_decode(wp_remote_retrieve_body($member_response), true);
		if ( !is_array($member_payload) || !empty($member_payload['code']) ) {
			return array(
				'ok' => false,
				'error' => 'NerdService-Benutzer konnte nicht gefunden werden.',
			);
		}

		$remote_email = isset($member_payload['email']) ? sanitize_email((string)$member_payload['email']) : '';

		$is_admin_bypass = !empty($is_site_admin);
		$roles = $member_payload['roles'] ?? array();
		if ( !is_array($roles) ) {
			$roles = array();
		}

		$caps = $member_payload['caps'] ?? array();
		if ( !is_array($caps) ) {
			$caps = array();
		}

		$capability_keys = array_map('strtolower', array_map('strval', array_keys($caps)));
		$role_values = array_map('strtolower', array_map('strval', $roles));
		$all_role_markers = array_merge($role_values, $capability_keys);

		if ( !empty($member_payload['is_super_admin']) || !empty($member_payload['super_admin']) || !empty($member_payload['is_admin']) ) {
			$is_admin_bypass = true;
		}

		if ( in_array('super_admin', $all_role_markers, true) || in_array('superadmin', $all_role_markers, true) || in_array('administrator', $all_role_markers, true) ) {
			$is_admin_bypass = true;
		}

		if ( !empty($email) && !empty($remote_email) && strtolower($email) !== strtolower($remote_email) ) {
			return array(
				'ok' => false,
				'error' => 'Die angegebene E-Mail passt nicht zur NerdService-Benutzer-ID.',
				'remote_email' => $remote_email,
			);
		}

		$subscription_url = add_query_arg(array(
			'user_id' => absint($remote_user_id),
			'membership_id' => $required_membership_id,
			'pass_key' => $pass_key,
		), $base . '/member/subscription');

		$subscription_response = wp_remote_get($subscription_url, array(
			'timeout' => 20,
			'headers' => $request_headers,
		));

		if ( is_wp_error($subscription_response) ) {
			return array(
				'ok' => false,
				'error' => 'Mitgliedschaft konnte nicht geprueft werden: ' . $subscription_response->get_error_message(),
				'remote_email' => $remote_email,
			);
		}

		$subscription_payload = json_decode(wp_remote_retrieve_body($subscription_response), true);
		$status = strtolower((string)($subscription_payload['member_status'] ?? $subscription_payload['status'] ?? ''));
		$membership_id = isset($subscription_payload['membership_id']) ? absint($subscription_payload['membership_id']) : 0;
		$allowed_statuses = array('active', 'trial', 'testversion');
		$membership_ok = ($membership_id === $required_membership_id) && ($status === '' || in_array($status, $allowed_statuses, true));

		if ( !$membership_ok ) {
			if ( $is_admin_bypass ) {
				return array(
					'ok' => true,
					'admin_bypass' => true,
					'remote_email' => $remote_email,
					'membership_id' => $membership_id,
					'status' => $status,
					'message' => 'Anmeldung erfolgreich. Superadmin/Admin erkannt, Mitgliedschaftscheck wurde fuer diesen Account uebersprungen.',
				);
			}

			return array(
				'ok' => false,
				'error' => 'Anmeldung erfolgreich, aber fuer diesen Account ist keine passende PS-Mitgliedschaft aktiv.',
				'error_code' => 'membership_missing',
				'remote_email' => $remote_email,
				'membership_id' => $membership_id,
				'status' => $status,
			);
		}

		return array(
			'ok' => true,
			'admin_bypass' => false,
			'remote_email' => $remote_email,
			'membership_id' => $membership_id,
			'status' => $status,
		);

	}


	public static function ajax_verify_catalog_membership() {

		check_ajax_referer('padma-visual-editor-ajax', 'security');

		if ( !current_user_can('manage_options') && (!class_exists('PadmaCapabilities') || !PadmaCapabilities::can_user('padma_visual_editor')) ) {
			wp_send_json_error(array('error' => 'Du hast keine Berechtigung fuer diese Aktion.'), 403);
		}

		$login = sanitize_text_field((string)padma_post('login'));
		$password = (string)padma_post('password');
		$rate = self::check_verify_rate_limit($login);

		if ( empty($rate['ok']) ) {
			wp_send_json_error(array(
				'error' => 'Zu viele Login-Versuche. Bitte in ' . absint($rate['retry_after']) . ' Sekunden erneut versuchen.',
			), 429);
		}

		$account = self::authenticate_membership_account($login, $password);
		if ( empty($account['ok']) ) {
			self::bump_verify_rate_limit((string)$rate['key'], absint($rate['window']));

			$identity = self::get_catalog_membership_identity();
			$identity['user_id'] = 0;
			$identity['verified'] = false;
			$identity['verified_at'] = '';
			$identity['membership_ok'] = false;
			$identity['admin_bypass'] = false;
			$identity['auth_source'] = 'remote_xmlrpc';
			$identity['message'] = (string)($account['error'] ?? 'Anmeldung fehlgeschlagen.');
			update_option('padma_catalog_membership_identity', $identity, false);

			wp_send_json_error(array(
				'error' => (string)($account['error'] ?? 'Anmeldung fehlgeschlagen.'),
				'identity' => $identity,
				'xmlrpc_fault_code' => isset($account['xmlrpc_fault_code']) ? intval($account['xmlrpc_fault_code']) : 0,
				'xmlrpc_fault_message' => isset($account['xmlrpc_fault_message']) ? sanitize_text_field((string)$account['xmlrpc_fault_message']) : '',
				'xmlrpc_http_status' => isset($account['xmlrpc_http_status']) ? intval($account['xmlrpc_http_status']) : 0,
				'error_code' => isset($account['error_code']) ? sanitize_key((string)$account['error_code']) : '',
			), 403);
		}

		self::clear_verify_rate_limit((string)$rate['key']);

		$remote_user_id = absint($account['user_id']);
		$email = sanitize_email((string)($account['email'] ?? ''));
		$remote_login = sanitize_text_field((string)($account['remote_login'] ?? $login));

		$verification = self::verify_membership_identity($remote_user_id, $email, !empty($account['is_site_admin']));
		$identity = self::get_catalog_membership_identity();
		$identity['user_id'] = $remote_user_id;
		$identity['remote_login'] = $remote_login;
		$identity['email'] = $email;
		$identity['verified'] = !empty($verification['ok']);
		$identity['verified_at'] = !empty($verification['ok']) ? current_time('mysql') : '';
		$identity['membership_ok'] = !empty($verification['ok']);
		$identity['admin_bypass'] = !empty($verification['admin_bypass']);
		$identity['auth_source'] = 'remote_xmlrpc';
		$identity['message'] = !empty($verification['ok'])
			? (!empty($verification['admin_bypass'])
				? 'Verbindung erfolgreich (Superadmin/Admin-Bypass aktiv).'
				: 'Verbindung erfolgreich und PS-Mitgliedschaft verifiziert.')
			: (string)($verification['error'] ?? 'Mitgliedschaft konnte nicht verifiziert werden.');

		update_option('padma_catalog_membership_identity', $identity, false);

		if ( empty($verification['ok']) ) {
			$setup_url = 'https://nerdservice.eimen.net/memberships/';
			$error_message = $identity['message'];
			if ( ($verification['error_code'] ?? '') === 'membership_missing' ) {
				$error_message .= ' Mitgliedschaft einrichten: ' . $setup_url;
			}

			wp_send_json_error(array(
				'error' => $error_message,
				'identity' => $identity,
				'verification' => $verification,
				'membership_setup_url' => $setup_url,
			), 400);
		}

		wp_send_json_success(array(
			'identity' => $identity,
			'verification' => $verification,
		));

	}


	public static function ajax_publish_template_catalog() {

		check_ajax_referer('padma-visual-editor-ajax', 'security');

		if ( !current_user_can('manage_options') && (!class_exists('PadmaCapabilities') || !PadmaCapabilities::can_user('padma_visual_editor')) ) {
			wp_send_json_error(array('error' => 'Du hast keine Berechtigung fuer diese Aktion.'), 403);
		}

		$template_id = sanitize_text_field((string)padma_post('template_id'));
		$file_path = (string)padma_post('file_path');
		$visibility = sanitize_key((string)padma_post('visibility'));

		if ( $visibility !== 'public' && $visibility !== 'private' ) {
			$visibility = 'private';
		}

		if ( empty($template_id) || empty($file_path) ) {
			wp_send_json_error(array('error' => 'Ungueltige Anfrage. Template oder Datei fehlt.'), 400);
		}

		$real_file_path = realpath($file_path);
		if ( !$real_file_path || !is_readable($real_file_path) || !is_file($real_file_path) ) {
			wp_send_json_error(array('error' => 'Exportierte Vorlagen-Datei konnte nicht gelesen werden.'), 400);
		}

		$catalog_base = defined('PADMA_CATALOG_API_BASE') ? untrailingslashit(PADMA_CATALOG_API_BASE) : 'https://eimen.net/padma-catalog/api';
		$catalog_api_key = defined('PADMA_CATALOG_API_KEY') ? (string)PADMA_CATALOG_API_KEY : '';

		if ( empty($catalog_api_key) ) {
			wp_send_json_error(array('error' => 'PADMA_CATALOG_API_KEY ist nicht gesetzt.'), 500);
		}

		if ( !function_exists('curl_init') ) {
			wp_send_json_error(array('error' => 'cURL ist auf dem Server nicht verfuegbar.'), 500);
		}

		$remote_user_id = self::get_catalog_remote_user_id();
		if ( $remote_user_id <= 0 ) {
			wp_send_json_error(array('error' => 'Bitte zuerst deine NerdService-Benutzerdaten speichern und die Mitgliedschaft pruefen.'), 400);
		}

		$upload_url = $catalog_base . '/upload.php';
		$upload_ch = curl_init($upload_url);
		$upload_fields = array(
			'template_zip' => new CURLFile($real_file_path, 'application/zip', basename($real_file_path)),
			'user_id' => $remote_user_id,
			'visibility' => $visibility,
		);

		curl_setopt_array($upload_ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $upload_fields,
			CURLOPT_HTTPHEADER => array(
				'X-PADMA-API-KEY: ' . $catalog_api_key,
				'Accept: application/json',
			),
			CURLOPT_TIMEOUT => 30,
		));

		$upload_response_raw = curl_exec($upload_ch);
		$upload_http_code = (int)curl_getinfo($upload_ch, CURLINFO_HTTP_CODE);
		$upload_curl_error = curl_error($upload_ch);
		curl_close($upload_ch);

		if ( $upload_response_raw === false || !empty($upload_curl_error) ) {
			wp_send_json_error(array('error' => 'Upload an Katalog fehlgeschlagen: ' . $upload_curl_error), 500);
		}

		$upload_response = json_decode($upload_response_raw, true);
		if ( !is_array($upload_response) || empty($upload_response['ok']) || empty($upload_response['upload_id']) || $upload_http_code >= 400 ) {
			$upload_error = is_array($upload_response) && !empty($upload_response['error']) ? $upload_response['error'] : 'Upload wurde vom Katalog abgelehnt.';
			wp_send_json_error(array('error' => $upload_error), 400);
		}

		$publish_request = wp_remote_post($catalog_base . '/publish.php', array(
			'timeout' => 30,
			'headers' => array(
				'X-PADMA-API-KEY' => $catalog_api_key,
				'Accept' => 'application/json',
			),
			'body' => array(
				'upload_id' => $upload_response['upload_id'],
			),
		));

		if ( is_wp_error($publish_request) ) {
			wp_send_json_error(array('error' => 'Publish an Katalog fehlgeschlagen: ' . $publish_request->get_error_message()), 500);
		}

		$publish_http_code = (int)wp_remote_retrieve_response_code($publish_request);
		$publish_body = wp_remote_retrieve_body($publish_request);
		$publish_response = json_decode($publish_body, true);

		if ( !is_array($publish_response) || empty($publish_response['ok']) || $publish_http_code >= 400 ) {
			$publish_error = is_array($publish_response) && !empty($publish_response['error']) ? $publish_response['error'] : 'Katalog-Publish fehlgeschlagen.';
			wp_send_json_error(array('error' => $publish_error), 400);
		}

		wp_send_json_success(array(
			'template_id' => $template_id,
			'visibility' => $visibility,
			'catalog' => $publish_response,
		));

	}


	public static function ajax_catalog_templates() {

		check_ajax_referer('padma-visual-editor-ajax', 'security');

		if ( !current_user_can('manage_options') && (!class_exists('PadmaCapabilities') || !PadmaCapabilities::can_user('padma_visual_editor')) ) {
			wp_send_json_error(array('error' => 'Du hast keine Berechtigung fuer diese Aktion.'), 403);
		}

		$catalog_base = defined('PADMA_CATALOG_API_BASE') ? untrailingslashit(PADMA_CATALOG_API_BASE) : 'https://eimen.net/padma-catalog/api';
		$remote_user_id = self::get_catalog_remote_user_id();
		$request_url = add_query_arg(array('user_id' => $remote_user_id), $catalog_base . '/templates.php');

		$response = wp_remote_get($request_url, array(
			'timeout' => 20,
			'headers' => array('Accept' => 'application/json'),
		));

		if ( is_wp_error($response) ) {
			wp_send_json_error(array('error' => 'Katalog konnte nicht geladen werden: ' . $response->get_error_message()), 500);
		}

		$payload = json_decode(wp_remote_retrieve_body($response), true);
		$templates = isset($payload['data']['templates']) && is_array($payload['data']['templates']) ? $payload['data']['templates'] : array();

		wp_send_json_success(array('templates' => $templates));

	}


	public static function ajax_install_catalog_template() {

		check_ajax_referer('padma-visual-editor-ajax', 'security');

		if ( !current_user_can('manage_options') && (!class_exists('PadmaCapabilities') || !PadmaCapabilities::can_user('padma_visual_editor')) ) {
			wp_send_json_error(array('error' => 'Du hast keine Berechtigung fuer diese Aktion.'), 403);
		}

		$slug = sanitize_title((string)padma_post('slug'));
		$version = sanitize_text_field((string)padma_post('version'));

		if ( empty($slug) ) {
			wp_send_json_error(array('error' => 'Ungueltiger Template-Slug.'), 400);
		}

		$catalog_base = defined('PADMA_CATALOG_API_BASE') ? untrailingslashit(PADMA_CATALOG_API_BASE) : 'https://eimen.net/padma-catalog/api';
		$remote_user_id = self::get_catalog_remote_user_id();
		$list_url = add_query_arg(array('user_id' => $remote_user_id), $catalog_base . '/templates.php');

		$list_response = wp_remote_get($list_url, array(
			'timeout' => 20,
			'headers' => array('Accept' => 'application/json'),
		));

		if ( is_wp_error($list_response) ) {
			wp_send_json_error(array('error' => 'Katalog-Liste konnte nicht geladen werden.'), 500);
		}

		$list_payload = json_decode(wp_remote_retrieve_body($list_response), true);
		$templates = isset($list_payload['data']['templates']) && is_array($list_payload['data']['templates']) ? $list_payload['data']['templates'] : array();

		$target_template = null;
		foreach ( $templates as $template ) {
			if ( !isset($template['slug']) || $template['slug'] !== $slug ) {
				continue;
			}
			$target_template = $template;
			break;
		}

		if ( !$target_template ) {
			wp_send_json_error(array('error' => 'Template nicht im Katalog gefunden.'), 404);
		}

		$versions = isset($target_template['versions']) && is_array($target_template['versions']) ? $target_template['versions'] : array();
		$target_version = null;

		if ( !empty($version) ) {
			foreach ( $versions as $v ) {
				if ( isset($v['version']) && (string)$v['version'] === $version ) {
					$target_version = $v;
					break;
				}
			}
		}

		if ( !$target_version && !empty($versions) ) {
			$target_version = $versions[0];
		}

		if ( !$target_version || empty($target_version['download_url']) ) {
			wp_send_json_error(array('error' => 'Keine installierbare Version gefunden.'), 404);
		}

		$download_url = (string)$target_version['download_url'];
		if ( strpos($download_url, 'http') !== 0 ) {
			$parts = wp_parse_url($catalog_base);
			$scheme = isset($parts['scheme']) ? $parts['scheme'] : 'https';
			$host = isset($parts['host']) ? $parts['host'] : '';
			$port = isset($parts['port']) ? ':' . $parts['port'] : '';
			$download_url = $scheme . '://' . $host . $port . $download_url;
		}

		$tmp_zip = wp_tempnam('padma-catalog-template.zip');
		if ( !$tmp_zip ) {
			wp_send_json_error(array('error' => 'Temporäre Datei konnte nicht erstellt werden.'), 500);
		}

		$zip_response = wp_remote_get($download_url, array('timeout' => 30));
		if ( is_wp_error($zip_response) ) {
			@unlink($tmp_zip);
			wp_send_json_error(array('error' => 'Template ZIP konnte nicht geladen werden.'), 500);
		}

		$zip_body = wp_remote_retrieve_body($zip_response);
		if ( empty($zip_body) ) {
			@unlink($tmp_zip);
			wp_send_json_error(array('error' => 'Template ZIP ist leer.'), 500);
		}

		file_put_contents($tmp_zip, $zip_body);

		require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		$extract_dir = trailingslashit(PADMA_CACHE_DIR) . 'catalog-install-' . uniqid();
		wp_mkdir_p($extract_dir);

		$archive = new PclZip($tmp_zip);
		$extract_result = $archive->extract(PCLZIP_OPT_PATH, $extract_dir);

		@unlink($tmp_zip);

		if ( $extract_result === 0 ) {
			wp_send_json_error(array('error' => 'ZIP konnte nicht entpackt werden.'), 500);
		}

		$template_data_file = trailingslashit($extract_dir) . 'template-data.json';
		$manifest_file = trailingslashit($extract_dir) . 'template-manifest.json';

		if ( !file_exists($template_data_file) ) {
			wp_send_json_error(array('error' => 'template-data.json fehlt im ZIP.'), 400);
		}

		global $wp_filesystem;
		if ( !$wp_filesystem ) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
		}

		$template_data = json_decode($wp_filesystem->get_contents($template_data_file), true);

		if ( file_exists($manifest_file) ) {
			$manifest = json_decode($wp_filesystem->get_contents($manifest_file), true);
			if ( is_array($manifest) ) {
				foreach ( $manifest as $key => $value ) {
					if ( !isset($template_data[$key]) ) {
						$template_data[$key] = $value;
					}
				}
			}
		}

		Padma::load('data/data-portability');
		$result = PadmaDataPortability::install_skin($template_data);

		if ( is_array($result) && isset($result['error']) ) {
			wp_send_json_error(array('error' => $result['error']), 400);
		}

		wp_send_json_success(array(
			'skin' => $result,
			'slug' => $slug,
			'version' => isset($target_version['version']) ? $target_version['version'] : null,
		));

	}


}
