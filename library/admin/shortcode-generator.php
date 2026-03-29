<?php
/**
 * PS Padma: Shortcode Generator
 *
 * Vollständig theme-nativer Shortcode Builder (Media Button im TinyMCE-Editor).
 * Kein psource-shortcodes Plugin erforderlich.
 *
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Padma_Shortcode_Generator {

	public function __construct() {
		add_action( 'media_buttons',                       array( $this, 'button' ), 1000 );
		add_action( 'admin_footer',                        array( $this, 'popup' ) );

		add_action( 'wp_ajax_su_generator_settings',       array( $this, 'ajax_settings' ) );
		add_action( 'wp_ajax_su_generator_preview',        array( $this, 'ajax_preview' ) );
		add_action( 'wp_ajax_su_generator_get_icons',      array( $this, 'ajax_get_icons' ) );
		add_action( 'wp_ajax_su_generator_get_terms',      array( $this, 'ajax_get_terms' ) );
		add_action( 'wp_ajax_su_generator_get_taxonomies', array( $this, 'ajax_get_taxonomies' ) );
	}

	// -------------------------------------------------------------------------
	// Media Button
	// -------------------------------------------------------------------------

	public function button() {
		if ( ! $this->access_check() ) {
			return;
		}

		$icon_url = get_template_directory_uri() . '/assets/images/icon-shortcodes.png';
		$icon     = file_exists( get_template_directory() . '/assets/images/icon-shortcodes.png' )
			? '<img src="' . esc_url( $icon_url ) . '" style="vertical-align:middle;margin-right:3px;" alt="" /> '
			: '';

		echo '<a href="javascript:void(0);" '
			. 'class="su-generator-button button" '
			. 'title="' . esc_attr__( 'PS Padma Blöcke', 'ps-padma' ) . '" '
			. 'data-target="content" '
			. 'data-mfp-src="#su-generator" '
			. 'data-shortcode="">'
			. $icon
			. esc_html__( 'PS Padma Blöcke', 'ps-padma' )
			. '</a>';

		$this->enqueue_assets();
	}

	// -------------------------------------------------------------------------
	// Asset-Enqueue (nur auf Post-Edit-Seiten)
	// -------------------------------------------------------------------------

	private function enqueue_assets() {
		$base  = get_template_directory_uri();
		$css   = $base . '/assets/css/psource-shortcodes/';
		$js    = $base . '/assets/js/psource-shortcodes/';

		// WordPress-eigene Farb-Picker-Bibliothek
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );

		// Medien-Uploader
		wp_enqueue_media();

		// Magnific Popup
		wp_enqueue_style( 'su-magnific-popup', $css . 'magnific-popup.css', array(), PADMA_VERSION );
		wp_enqueue_script( 'su-magnific-popup', $js . 'magnific-popup.js', array( 'jquery' ), PADMA_VERSION, true );

		// SimpleSlider (Range-Felder)
		wp_enqueue_style( 'su-simpleslider', $css . 'simpleslider.css', array(), PADMA_VERSION );
		wp_enqueue_script( 'su-simpleslider', $js . 'simpleslider.js', array( 'jquery' ), PADMA_VERSION, true );

		// Generator-Popup-CSS
		wp_enqueue_style( 'su-generator', $css . 'generator.css', array( 'su-magnific-popup' ), PADMA_VERSION );

		// TinyMCE-Plugin für Shortcodes
		wp_enqueue_script( 'su-tinymce', $js . 'tinymce.js', array( 'jquery' ), PADMA_VERSION, true );

		// Generator-Logik (muss nach jQuery + Popup-Libs geladen werden)
		wp_enqueue_script(
			'su-generator',
			$js . 'generator.js',
			array( 'jquery', 'su-magnific-popup', 'su-simpleslider', 'farbtastic' ),
			PADMA_VERSION,
			true
		);

		// JS-Konfiguration für generator.js
		wp_localize_script( 'su-generator', 'su_generator', array(
			'ajaxurl'             => admin_url( 'admin-ajax.php' ),
			'nonce'               => wp_create_nonce( 'padma_generator_nonce' ),
			'isp_media_title'     => __( 'Bilder aus der Mediathek auswaehlen', 'ps-padma' ),
			'isp_media_insert'    => __( 'Bilder uebernehmen', 'ps-padma' ),
			'upload_title'        => __( 'Medium auswaehlen', 'ps-padma' ),
			'upload_insert'       => __( 'Medium verwenden', 'ps-padma' ),
			'last_used'           => __( 'Zuletzt verwendet', 'ps-padma' ),
			'presets_prompt_msg'  => __( 'Name fuer diese Vorlage eingeben:', 'ps-padma' ),
			'presets_prompt_value'=> __( 'Meine Vorlage', 'ps-padma' ),
		) );
	}

	// -------------------------------------------------------------------------
	// Popup-HTML (admin_footer)
	// -------------------------------------------------------------------------

	public function popup() {
		// Nur ausgeben wenn der Button auf der Seite ist (d.h. wir auf einer Post-Edit-Seite sind)
		if ( ! $this->access_check() ) {
			return;
		}

		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$shortcodes = (array) Padma_Generator_Data::shortcodes();
		$groups     = (array) Padma_Generator_Data::groups();
		?>
		<div id="su-generator-wrap" style="display:none">
			<div id="su-generator">
				<div id="su-generator-header">
					<div id="su-generator-tools">
						<a href="https://cp-psource.github.io/upfront-shortcodes/" target="_blank"><?php esc_html_e( 'Shortcode-Referenz', 'ps-padma' ); ?></a>
					</div>
					<input type="text" name="su_generator_search" id="su-generator-search" value="" placeholder="<?php esc_attr_e( 'Shortcodes suchen…', 'ps-padma' ); ?>" />
					<p id="su-generator-search-pro-tip">
						<strong><?php esc_html_e( 'Tipp:', 'ps-padma' ); ?></strong>
						<?php esc_html_e( 'Enter drücken um markierten Shortcode direkt zu wählen.', 'ps-padma' ); ?>
					</p>
					<div id="su-generator-filter">
						<strong><?php esc_html_e( 'Nach Typ filtern', 'ps-padma' ); ?></strong>
						<?php foreach ( $groups as $group => $label ) : ?>
							<a href="#" data-filter="<?php echo esc_attr( $group ); ?>"><?php echo esc_html( $label ); ?></a>
						<?php endforeach; ?>
					</div>
					<div id="su-generator-choices" class="su-generator-clearfix">
						<?php foreach ( $shortcodes as $name => $shortcode ) :
							$icon_name = isset( $shortcode['icon'] ) ? $shortcode['icon'] : 'puzzle-piece';
							$sc_name   = isset( $shortcode['name'] ) ? $shortcode['name'] : $name;
							$sc_desc   = isset( $shortcode['desc'] ) ? $shortcode['desc'] : '';
							$sc_group  = isset( $shortcode['group'] ) ? $shortcode['group'] : 'other';
							$icon_html = ( strpos( $icon_name, '/' ) !== false )
								? '<img src="' . esc_url( $icon_name ) . '" alt="" />'
								: '<i class="fa fa-' . esc_attr( $icon_name ) . '"></i>';
						?>
							<span
								data-name="<?php echo esc_attr( $sc_name ); ?>"
								data-shortcode="<?php echo esc_attr( $name ); ?>"
								title="<?php echo esc_attr( $sc_desc ); ?>"
								data-desc="<?php echo esc_attr( $sc_desc ); ?>"
								data-group="<?php echo esc_attr( $sc_group ); ?>"
							><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput -- icon_html is pre-escaped ?><?php echo esc_html( $sc_name ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>
				<div id="su-generator-settings"></div>
				<!-- su-generator-url muss auf das Theme-Verzeichnis zeigen (für Icon/CSS-Pfade in generator.js) -->
				<input type="hidden" name="su-generator-selected" id="su-generator-selected" value="<?php echo esc_url( get_template_directory_uri() ); ?>" />
				<input type="hidden" name="su-generator-url"      id="su-generator-url"      value="<?php echo esc_url( get_template_directory_uri() ); ?>" />
				<input type="hidden" name="su-compatibility-mode-prefix" id="su-compatibility-mode-prefix" value="su_" />
				<div id="su-generator-result" style="display:none"></div>
			</div>
		</div>
		<?php
	}

	// -------------------------------------------------------------------------
	// AJAX: Einstellungsformular für einen Shortcode
	// -------------------------------------------------------------------------

	public function ajax_settings() {
		$this->access();

		if ( empty( $_REQUEST['shortcode'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_die( esc_html__( 'Kein Shortcode angegeben.', 'ps-padma' ) );
		}

		$sc_key    = sanitize_key( wp_unslash( $_REQUEST['shortcode'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		$shortcode = Padma_Generator_Data::shortcodes( $sc_key );

		if ( ! $shortcode ) {
			wp_die( esc_html__( 'Shortcode nicht gefunden.', 'ps-padma' ) );
		}

		$actions = array(
			'insert'  => '<a href="javascript:void(0);" class="button button-primary button-large su-generator-insert"><i class="fa fa-check"></i> ' . esc_html__( 'Shortcode einfügen', 'ps-padma' ) . '</a>',
			'preview' => '<a href="javascript:void(0);" class="button button-large su-generator-toggle-preview"><i class="fa fa-eye"></i> ' . esc_html__( 'Live-Vorschau', 'ps-padma' ) . '</a>',
		);

		$sc_name = isset( $shortcode['name'] ) ? $shortcode['name'] : $sc_key;
		$sc_desc = isset( $shortcode['desc'] ) ? $shortcode['desc'] : '';

		// Breadcrumbs
		$return  = '<div id="su-generator-breadcrumbs">';
		$return .= '<a href="javascript:void(0);" class="su-generator-home" title="' . esc_attr__( 'Zur Shortcode-Liste zurückkehren', 'ps-padma' ) . '">' . esc_html__( 'Alle Shortcodes', 'ps-padma' ) . '</a>';
		$return .= ' &rarr; <span>' . esc_html( $sc_name ) . '</span>';
		$return .= ' <small class="alignright">' . esc_html( $sc_desc ) . '</small>';
		$return .= '<div class="su-generator-clear"></div>';
		$return .= '</div>';

		// Optionaler Hinweis
		if ( isset( $shortcode['note'] ) ) {
			$return .= '<div class="su-generator-note"><i class="fa fa-info-circle"></i><div class="su-generator-note-content">' . wp_kses_post( wpautop( $shortcode['note'] ) ) . '</div></div>';
		}

		// Attribute-Felder
		if ( ! empty( $shortcode['atts'] ) ) {
			foreach ( $shortcode['atts'] as $attr_name => $attr_info ) {
				$default    = isset( $attr_info['default'] ) ? (string) $attr_info['default'] : '';
				$attr_label = isset( $attr_info['name'] ) ? $attr_info['name'] : $attr_name;

				// Typ automatisch ableiten wenn nicht gesetzt
				if ( ! isset( $attr_info['type'] ) ) {
					if ( isset( $attr_info['values'] ) && is_array( $attr_info['values'] ) && count( $attr_info['values'] ) ) {
						$attr_info['type'] = 'select';
					} else {
						$attr_info['type'] = 'text';
					}
				}

				$return .= '<div class="su-generator-attr-container" data-default="' . esc_attr( $default ) . '">';
				$return .= '<h5>' . esc_html( $attr_label ) . '</h5>';

				if ( is_callable( array( 'Padma_Generator_Views', $attr_info['type'] ) ) ) {
					$return .= call_user_func( array( 'Padma_Generator_Views', $attr_info['type'] ), $attr_name, $attr_info );
				}

				if ( isset( $attr_info['desc'] ) ) {
					$return .= '<div class="su-generator-attr-desc">' . wp_kses_post( $attr_info['desc'] ) . '</div>';
				}

				$return .= '</div>';
			}
		}

		// Content-Feld (wrap vs. single)
		if ( isset( $shortcode['type'] ) && $shortcode['type'] === 'single' ) {
			$return .= '<input type="hidden" name="su-generator-content" id="su-generator-content" value="false" />';
		} else {
			$content = '';
			if ( isset( $shortcode['content'] ) ) {
				$content = is_array( $shortcode['content'] )
					? $this->get_shortcode_code( $shortcode['content'] )
					: $shortcode['content'];
			}
			$return .= '<div class="su-generator-attr-container">';
			$return .= '<h5>' . esc_html__( 'Inhalt', 'ps-padma' ) . '</h5>';
			$return .= '<textarea name="su-generator-content" id="su-generator-content" rows="5">' . esc_textarea( $content ) . '</textarea>';
			$return .= '</div>';
		}

		$return .= '<div id="su-generator-preview"></div>';
		$return .= '<div class="su-generator-actions su-generator-clearfix">' . implode( ' ', array_values( $actions ) ) . '</div>';

		echo $return; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML is built with wp_kses_post / esc_* internally
		exit;
	}

	// -------------------------------------------------------------------------
	// AJAX: Live-Vorschau
	// -------------------------------------------------------------------------

	public function ajax_preview() {
		$this->access();

		if ( empty( $_POST['shortcode'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			exit;
		}

		// Shortcode sicher verarbeiten: stripslashes für korrekte Anführungszeichen,
		// kein weiteres Escaping da do_shortcode den String selbst verarbeitet
		$shortcode = wp_unslash( $_POST['shortcode'] ); // phpcs:ignore WordPress.Security.NonceVerification

		echo '<h5>' . esc_html__( 'Vorschau', 'ps-padma' ) . '</h5>';
		// do_shortcode erwartet einen vertrauenswürdigen String vom eingeloggten Redakteur
		echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput
		echo '<div style="clear:both"></div>';
		exit;
	}

	// -------------------------------------------------------------------------
	// AJAX: Icons
	// -------------------------------------------------------------------------

	public function ajax_get_icons() {
		$this->access();

		$output = '';
		foreach ( (array) Padma_Generator_Data::icons() as $icon ) {
			$output .= '<i class="fa fa-' . esc_attr( $icon ) . '" title="' . esc_attr( $icon ) . '"></i>';
		}
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput -- pre-escaped above
		exit;
	}

	// -------------------------------------------------------------------------
	// AJAX: Terms
	// -------------------------------------------------------------------------

	public function ajax_get_terms() {
		$this->access();

		$tax      = isset( $_REQUEST['tax'] ) ? sanitize_key( wp_unslash( $_REQUEST['tax'] ) ) : 'category'; // phpcs:ignore WordPress.Security.NonceVerification
		$class    = isset( $_REQUEST['class'] ) ? sanitize_html_class( wp_unslash( $_REQUEST['class'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$multiple = ! empty( $_REQUEST['multiple'] ); // phpcs:ignore WordPress.Security.NonceVerification
		$size     = isset( $_REQUEST['size'] ) ? absint( $_REQUEST['size'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification

		$terms   = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => false ) );
		$options = array();
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		echo $this->build_select( $options, $class, $multiple, $size ); // phpcs:ignore WordPress.Security.EscapeOutput -- pre-escaped in build_select
		exit;
	}

	// -------------------------------------------------------------------------
	// AJAX: Taxonomien
	// -------------------------------------------------------------------------

	public function ajax_get_taxonomies() {
		$this->access();

		$taxes   = get_taxonomies( array(), 'objects' );
		$options = array();
		foreach ( $taxes as $tax ) {
			$options[ $tax->name ] = $tax->label;
		}

		echo $this->build_select( $options ); // phpcs:ignore WordPress.Security.EscapeOutput -- pre-escaped in build_select
		exit;
	}

	// -------------------------------------------------------------------------
	// Hilfsmethoden
	// -------------------------------------------------------------------------

	/**
	 * Baut ein <select>-Element aus einem key=>value-Array
	 */
	private function build_select( array $options, $class = '', $multiple = false, $size = 0 ) {
		$attrs  = '';
		if ( $class    ) $attrs .= ' class="' . esc_attr( $class ) . '"';
		if ( $multiple ) $attrs .= ' multiple="multiple"';
		if ( $size     ) $attrs .= ' size="' . intval( $size ) . '"';

		$html = '<select' . $attrs . '>';
		foreach ( $options as $val => $label ) {
			$html .= '<option value="' . esc_attr( $val ) . '">' . esc_html( $label ) . '</option>';
		}
		$html .= '</select>';
		return $html;
	}

	/**
	 * Erstellt Shortcode-Code aus einem Array von Kinder-Shortcodes
	 * (für Wrap-Shortcodes mit vordefinierten Kinder-Elementen, z.B. Tabs)
	 */
	private function get_shortcode_code( array $args ) {
		$defaults = array(
			'id'     => '',
			'number' => 1,
			'nested' => false,
		);
		$args     = wp_parse_args( $args, $defaults );
		$id       = sanitize_key( $args['id'] );

		if ( ! $id ) {
			return '';
		}

		$data = Padma_Generator_Data::shortcodes( $id );
		if ( ! $data ) {
			return '';
		}

		$atts = '';
		if ( ! empty( $data['atts'] ) ) {
			foreach ( $data['atts'] as $attr_name => $attr_info ) {
				$val = isset( $attr_info['default'] ) ? $attr_info['default'] : '';
				$atts .= ' ' . $attr_name . '="' . esc_attr( $val ) . '"';
			}
		}

		$inner   = isset( $data['content'] ) && ! is_array( $data['content'] ) ? $data['content'] : '';
		$sc_open = '[su_' . $id . $atts . ']';
		$sc_end  = isset( $data['type'] ) && $data['type'] === 'single' ? '' : '[/su_' . $id . ']';

		$pieces = array();
		for ( $i = 1; $i <= (int) $args['number']; $i++ ) {
			$pieces[] = $sc_open . $inner . $sc_end;
		}
		return implode( "\n", $pieces );
	}

	/**
	 * Zugriffsprüfung
	 */
	private function access_check() {
		return current_user_can( 'edit_posts' );
	}

	private function access() {
		if ( ! $this->access_check() ) {
			wp_die( esc_html__( 'Zugriff verweigert.', 'ps-padma' ) );
		}
	}

}
