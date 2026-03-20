<?php
/**
 * PS Padma: Shortcode Generator Data
 *
 * UI-Metadaten aller Theme-Shortcodes für den Shortcode-Builder.
 * Enthält Gruppen, Feld-Typen, Select-Optionen, Slider-Ranges und Defaults.
 *
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Padma_Generator_Data {

	/**
	 * Shortcode-Gruppen für den Filter-Tab im Builder
	 */
	public static function groups() {
		return apply_filters( 'padma/generator/groups', array(
			'all'     => __( 'Alle', 'ps-padma' ),
			'content' => __( 'Inhalt', 'ps-padma' ),
			'box'     => __( 'Box', 'ps-padma' ),
			'media'   => __( 'Medien', 'ps-padma' ),
			'gallery' => __( 'Galerie', 'ps-padma' ),
			'other'   => __( 'Sonstige', 'ps-padma' ),
		) );
	}

	/**
	 * Alle Shortcodes mit UI-Metadaten
	 *
	 * type: single | wrap
	 * group: content | box | media | gallery | other
	 * atts: Attribut-Definitionen (type, values, default, min, max, step, name, desc)
	 *   type: text | select | bool | color | slider | icon | upload | textarea | extra_css_class
	 *
	 * @param string|false $shortcode Einzelner Shortcode-Name oder false für alle
	 * @return array
	 */
	public static function shortcodes( $shortcode = false ) {

		$data = apply_filters( 'padma/generator/shortcodes', array(

			// ----------------------------------------------------------------
			// HEADING
			// ----------------------------------------------------------------
			'heading' => array(
				'name'    => __( 'Überschrift', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'content',
				'icon'    => 'h-square',
				'desc'    => __( 'Gestaltete Überschrift', 'ps-padma' ),
				'content' => __( 'Überschrift', 'ps-padma' ),
				'atts'    => array(
					'style' => array(
						'type'    => 'select',
						'values'  => array( 'default' => __( 'Standard', 'ps-padma' ) ),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil der Überschrift auswählen', 'ps-padma' ),
					),
					'size' => array(
						'type'    => 'slider',
						'min'     => 7,
						'max'     => 48,
						'step'    => 1,
						'default' => 13,
						'name'    => __( 'Größe', 'ps-padma' ),
						'desc'    => __( 'Schriftgröße in Pixeln', 'ps-padma' ),
					),
					'align' => array(
						'type'    => 'select',
						'values'  => array(
							'left'   => __( 'Links', 'ps-padma' ),
							'center' => __( 'Mitte', 'ps-padma' ),
							'right'  => __( 'Rechts', 'ps-padma' ),
						),
						'default' => 'center',
						'name'    => __( 'Ausrichtung', 'ps-padma' ),
						'desc'    => __( 'Textausrichtung der Überschrift', 'ps-padma' ),
					),
					'margin' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 200,
						'step'    => 10,
						'default' => 20,
						'name'    => __( 'Abstand unten', 'ps-padma' ),
						'desc'    => __( 'Unterer Außenabstand in Pixeln', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// DIVIDER
			// ----------------------------------------------------------------
			'divider' => array(
				'name'  => __( 'Trennlinie', 'ps-padma' ),
				'type'  => 'single',
				'group' => 'content',
				'icon'  => 'ellipsis-h',
				'desc'  => __( 'Trennlinie mit optionalem Nach-Oben-Link', 'ps-padma' ),
				'atts'  => array(
					'top' => array(
						'type'    => 'bool',
						'default' => 'yes',
						'name'    => __( 'Nach-Oben-Link anzeigen', 'ps-padma' ),
						'desc'    => __( 'Link zum Seitenanfang einblenden', 'ps-padma' ),
					),
					'text' => array(
						'default' => __( 'Nach oben', 'ps-padma' ),
						'name'    => __( 'Linktext', 'ps-padma' ),
						'desc'    => __( 'Text für den Nach-Oben-Link', 'ps-padma' ),
					),
					'style' => array(
						'type'    => 'select',
						'values'  => array(
							'default' => __( 'Standard', 'ps-padma' ),
							'dotted'  => __( 'Gepunktet', 'ps-padma' ),
							'dashed'  => __( 'Gestrichelt', 'ps-padma' ),
							'double'  => __( 'Doppelt', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil der Trennlinie', 'ps-padma' ),
					),
					'divider_color' => array(
						'type'    => 'color',
						'default' => '#999999',
						'name'    => __( 'Linienfarbe', 'ps-padma' ),
						'desc'    => __( 'Farbe der Trennlinie', 'ps-padma' ),
					),
					'link_color' => array(
						'type'    => 'color',
						'default' => '#999999',
						'name'    => __( 'Linkfarbe', 'ps-padma' ),
						'desc'    => __( 'Farbe des Nach-Oben-Links', 'ps-padma' ),
					),
					'size' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 40,
						'step'    => 1,
						'default' => 3,
						'name'    => __( 'Stärke', 'ps-padma' ),
						'desc'    => __( 'Linienstärke in Pixeln', 'ps-padma' ),
					),
					'margin' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 200,
						'step'    => 5,
						'default' => 15,
						'name'    => __( 'Abstand', 'ps-padma' ),
						'desc'    => __( 'Außenabstand oben und unten in Pixeln', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// SPACER
			// ----------------------------------------------------------------
			'spacer' => array(
				'name'  => __( 'Abstand', 'ps-padma' ),
				'type'  => 'single',
				'group' => 'content',
				'icon'  => 'arrows-v',
				'desc'  => __( 'Leerer Abstandshalter mit einstellbarer Höhe', 'ps-padma' ),
				'atts'  => array(
					'size' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 800,
						'step'    => 10,
						'default' => 20,
						'name'    => __( 'Höhe', 'ps-padma' ),
						'desc'    => __( 'Höhe des Abstands in Pixeln', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// HIGHLIGHT
			// ----------------------------------------------------------------
			'highlight' => array(
				'name'    => __( 'Hervorhebung', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'content',
				'icon'    => 'pencil',
				'desc'    => __( 'Farbig hervorgehobener Text', 'ps-padma' ),
				'content' => __( 'Hervorgehobener Text', 'ps-padma' ),
				'atts'    => array(
					'background' => array(
						'type'    => 'color',
						'default' => '#DDFF99',
						'name'    => __( 'Hintergrundfarbe', 'ps-padma' ),
						'desc'    => __( 'Hintergrundfarbe der Hervorhebung', 'ps-padma' ),
					),
					'color' => array(
						'type'    => 'color',
						'default' => '#000000',
						'name'    => __( 'Textfarbe', 'ps-padma' ),
						'desc'    => __( 'Schriftfarbe der Hervorhebung', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// LABEL
			// ----------------------------------------------------------------
			'label' => array(
				'name'    => __( 'Label', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'content',
				'icon'    => 'tag',
				'desc'    => __( 'Gestaltetes Label/Badge', 'ps-padma' ),
				'content' => __( 'Label-Text', 'ps-padma' ),
				'atts'    => array(
					'type' => array(
						'type'    => 'select',
						'values'  => array(
							'default'   => __( 'Standard', 'ps-padma' ),
							'success'   => __( 'Erfolg', 'ps-padma' ),
							'warning'   => __( 'Warnung', 'ps-padma' ),
							'important' => __( 'Wichtig', 'ps-padma' ),
							'black'     => __( 'Schwarz', 'ps-padma' ),
							'info'      => __( 'Info', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Typ', 'ps-padma' ),
						'desc'    => __( 'Stil des Labels', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// DROPCAP
			// ----------------------------------------------------------------
			'dropcap' => array(
				'name'    => __( 'Initialbuchstabe', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'content',
				'icon'    => 'bold',
				'desc'    => __( 'Großer Anfangsbuchstabe', 'ps-padma' ),
				'content' => 'D',
				'atts'    => array(
					'style' => array(
						'type'    => 'select',
						'values'  => array(
							'default' => __( 'Standard', 'ps-padma' ),
							'flat'    => __( 'Flat', 'ps-padma' ),
							'light'   => __( 'Hell', 'ps-padma' ),
							'simple'  => __( 'Einfach', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil des Initialbuchstabens', 'ps-padma' ),
					),
					'size' => array(
						'type'    => 'slider',
						'min'     => 1,
						'max'     => 5,
						'step'    => 1,
						'default' => 3,
						'name'    => __( 'Größe', 'ps-padma' ),
						'desc'    => __( 'Größe des Initialbuchstabens', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// LIST
			// ----------------------------------------------------------------
			'list' => array(
				'name'    => __( 'Liste', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'content',
				'icon'    => 'list-ol',
				'desc'    => __( 'Gestaltete Liste mit Icon', 'ps-padma' ),
				'content' => "<ul>\n<li>Listenelement</li>\n<li>Listenelement</li>\n<li>Listenelement</li>\n</ul>",
				'atts'    => array(
					'icon' => array(
						'type'    => 'icon',
						'default' => '',
						'name'    => __( 'Icon', 'ps-padma' ),
						'desc'    => __( 'Icon für die Liste auswählen', 'ps-padma' ),
					),
					'icon_color' => array(
						'type'    => 'color',
						'default' => '#333333',
						'name'    => __( 'Icon-Farbe', 'ps-padma' ),
						'desc'    => __( 'Farbe des Icons', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// BUTTON
			// ----------------------------------------------------------------
			'button' => array(
				'name'    => __( 'Button', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'content',
				'icon'    => 'heart',
				'desc'    => __( 'Gestalteter Button/Link', 'ps-padma' ),
				'content' => __( 'Button-Text', 'ps-padma' ),
				'atts'    => array(
					'url' => array(
						'default' => '',
						'name'    => __( 'Link-URL', 'ps-padma' ),
						'desc'    => __( 'Ziel-URL des Buttons', 'ps-padma' ),
					),
					'target' => array(
						'type'    => 'select',
						'values'  => array(
							'self'  => __( 'Gleiches Tab', 'ps-padma' ),
							'blank' => __( 'Neues Tab', 'ps-padma' ),
						),
						'default' => 'self',
						'name'    => __( 'Ziel', 'ps-padma' ),
						'desc'    => __( 'Wie wird der Link geöffnet', 'ps-padma' ),
					),
					'style' => array(
						'type'    => 'select',
						'values'  => array(
							'default' => __( 'Standard', 'ps-padma' ),
							'flat'    => __( 'Flat', 'ps-padma' ),
							'ghost'   => __( 'Ghost', 'ps-padma' ),
							'soft'    => __( 'Soft', 'ps-padma' ),
							'glass'   => __( 'Glass', 'ps-padma' ),
							'bubbles' => __( 'Bubbles', 'ps-padma' ),
							'noise'   => __( 'Noise', 'ps-padma' ),
							'stroked' => __( 'Stroked', 'ps-padma' ),
							'3d'      => __( '3D', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Hintergrundstil des Buttons', 'ps-padma' ),
					),
					'background' => array(
						'type'    => 'color',
						'default' => '#2D89EF',
						'name'    => __( 'Hintergrundfarbe', 'ps-padma' ),
						'desc'    => __( 'Hintergrundfarbe des Buttons', 'ps-padma' ),
					),
					'color' => array(
						'type'    => 'color',
						'default' => '#FFFFFF',
						'name'    => __( 'Textfarbe', 'ps-padma' ),
						'desc'    => __( 'Schriftfarbe des Buttons', 'ps-padma' ),
					),
					'size' => array(
						'type'    => 'slider',
						'min'     => 1,
						'max'     => 20,
						'step'    => 1,
						'default' => 3,
						'name'    => __( 'Größe', 'ps-padma' ),
						'desc'    => __( 'Button-Größe', 'ps-padma' ),
					),
					'wide' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Volle Breite', 'ps-padma' ),
						'desc'    => __( 'Button auf volle Breite (100%) ausdehnen', 'ps-padma' ),
					),
					'center' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Zentriert', 'ps-padma' ),
						'desc'    => __( 'Button horizontal zentrieren', 'ps-padma' ),
					),
					'radius' => array(
						'type'    => 'select',
						'values'  => array(
							'auto'  => __( 'Automatisch', 'ps-padma' ),
							'round' => __( 'Rund', 'ps-padma' ),
							'0'     => __( 'Eckig', 'ps-padma' ),
							'5'     => '5px',
							'10'    => '10px',
							'20'    => '20px',
						),
						'default' => 'auto',
						'name'    => __( 'Eckenradius', 'ps-padma' ),
						'desc'    => __( 'Abrundung der Button-Ecken', 'ps-padma' ),
					),
					'icon' => array(
						'type'    => 'icon',
						'default' => '',
						'name'    => __( 'Icon', 'ps-padma' ),
						'desc'    => __( 'Icon im Button', 'ps-padma' ),
					),
					'icon_color' => array(
						'type'    => 'color',
						'default' => '#FFFFFF',
						'name'    => __( 'Icon-Farbe', 'ps-padma' ),
						'desc'    => __( 'Farbe des Icons', 'ps-padma' ),
					),
					'desc' => array(
						'default' => '',
						'name'    => __( 'Beschreibung', 'ps-padma' ),
						'desc'    => __( 'Kleine Beschreibung unter dem Button-Text', 'ps-padma' ),
					),
					'title' => array(
						'default' => '',
						'name'    => __( 'Title-Attribut', 'ps-padma' ),
						'desc'    => __( 'Wert für das title-Attribut des Links', 'ps-padma' ),
					),
					'rel' => array(
						'default' => '',
						'name'    => __( 'Rel-Attribut', 'ps-padma' ),
						'desc'    => __( 'Wert für das rel-Attribut, z.B. <b%value>nofollow</b>', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// QUOTE
			// ----------------------------------------------------------------
			'quote' => array(
				'name'    => __( 'Zitat', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'quote-right',
				'desc'    => __( 'Blockzitat-Alternative', 'ps-padma' ),
				'content' => __( 'Zitattext', 'ps-padma' ),
				'atts'    => array(
					'style' => array(
						'type'    => 'select',
						'values'  => array( 'default' => __( 'Standard', 'ps-padma' ) ),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil des Zitats', 'ps-padma' ),
					),
					'cite' => array(
						'default' => '',
						'name'    => __( 'Quellenangabe', 'ps-padma' ),
						'desc'    => __( 'Name des Zitatautors', 'ps-padma' ),
					),
					'url' => array(
						'default' => '',
						'name'    => __( 'Quellen-URL', 'ps-padma' ),
						'desc'    => __( 'Link zur Quelle (leer lassen für keinen Link)', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// PULLQUOTE
			// ----------------------------------------------------------------
			'pullquote' => array(
				'name'    => __( 'Pullquote', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'quote-left',
				'desc'    => __( 'Herausgezogenes Zitat (floated)', 'ps-padma' ),
				'content' => __( 'Pullquote-Text', 'ps-padma' ),
				'atts'    => array(
					'align' => array(
						'type'    => 'select',
						'values'  => array(
							'left'  => __( 'Links', 'ps-padma' ),
							'right' => __( 'Rechts', 'ps-padma' ),
						),
						'default' => 'left',
						'name'    => __( 'Ausrichtung', 'ps-padma' ),
						'desc'    => __( 'Float-Ausrichtung des Pullquotes', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// NOTE (Colored Box)
			// ----------------------------------------------------------------
			'note' => array(
				'name'    => __( 'Notiz', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'list-alt',
				'desc'    => __( 'Farbige Notizbox', 'ps-padma' ),
				'content' => __( 'Notiztext', 'ps-padma' ),
				'atts'    => array(
					'note_color' => array(
						'type'    => 'color',
						'default' => '#FFFF66',
						'name'    => __( 'Hintergrundfarbe', 'ps-padma' ),
						'desc'    => __( 'Hintergrundfarbe der Notizbox', 'ps-padma' ),
					),
					'text_color' => array(
						'type'    => 'color',
						'default' => '#333333',
						'name'    => __( 'Textfarbe', 'ps-padma' ),
						'desc'    => __( 'Schriftfarbe der Notizbox', 'ps-padma' ),
					),
					'radius' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 20,
						'step'    => 1,
						'default' => 3,
						'name'    => __( 'Eckenradius', 'ps-padma' ),
						'desc'    => __( 'Abrundung der Box-Ecken in Pixeln', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// BOX (Colored Box with Title)
			// ----------------------------------------------------------------
			'box' => array(
				'name'    => __( 'Box', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'list-alt',
				'desc'    => __( 'Farbige Box mit Titelleiste', 'ps-padma' ),
				'content' => __( 'Box-Inhalt', 'ps-padma' ),
				'atts'    => array(
					'title' => array(
						'default' => __( 'Box-Titel', 'ps-padma' ),
						'name'    => __( 'Titel', 'ps-padma' ),
						'desc'    => __( 'Text für die Titelleiste', 'ps-padma' ),
					),
					'style' => array(
						'type'    => 'select',
						'values'  => array(
							'default' => __( 'Standard', 'ps-padma' ),
							'soft'    => __( 'Soft', 'ps-padma' ),
							'glass'   => __( 'Glass', 'ps-padma' ),
							'bubbles' => __( 'Bubbles', 'ps-padma' ),
							'noise'   => __( 'Noise', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil-Preset der Box', 'ps-padma' ),
					),
					'box_color' => array(
						'type'    => 'color',
						'default' => '#333333',
						'name'    => __( 'Farbe', 'ps-padma' ),
						'desc'    => __( 'Farbe der Titelleiste und des Rahmens', 'ps-padma' ),
					),
					'title_color' => array(
						'type'    => 'color',
						'default' => '#FFFFFF',
						'name'    => __( 'Titelfarbe', 'ps-padma' ),
						'desc'    => __( 'Schriftfarbe der Titelleiste', 'ps-padma' ),
					),
					'radius' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 20,
						'step'    => 1,
						'default' => 3,
						'name'    => __( 'Eckenradius', 'ps-padma' ),
						'desc'    => __( 'Abrundung der Box-Ecken in Pixeln', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// SERVICE
			// ----------------------------------------------------------------
			'service' => array(
				'name'    => __( 'Service-Box', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'check-square-o',
				'desc'    => __( 'Service-Box mit Titel und Icon', 'ps-padma' ),
				'content' => __( 'Service-Beschreibung', 'ps-padma' ),
				'atts'    => array(
					'title' => array(
						'default' => __( 'Service-Titel', 'ps-padma' ),
						'name'    => __( 'Titel', 'ps-padma' ),
						'desc'    => __( 'Name des Services', 'ps-padma' ),
					),
					'icon' => array(
						'type'    => 'icon',
						'default' => '',
						'name'    => __( 'Icon', 'ps-padma' ),
						'desc'    => __( 'Icon für die Service-Box', 'ps-padma' ),
					),
					'icon_color' => array(
						'type'    => 'color',
						'default' => '#333333',
						'name'    => __( 'Icon-Farbe', 'ps-padma' ),
						'desc'    => __( 'Farbe des Icons', 'ps-padma' ),
					),
					'size' => array(
						'type'    => 'slider',
						'min'     => 10,
						'max'     => 128,
						'step'    => 2,
						'default' => 32,
						'name'    => __( 'Icon-Größe', 'ps-padma' ),
						'desc'    => __( 'Icon-Größe in Pixeln', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// EXPAND
			// ----------------------------------------------------------------
			'expand' => array(
				'name'    => __( 'Ausklappen', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'sort-amount-asc',
				'desc'    => __( 'Ausklappbarer Textblock', 'ps-padma' ),
				'content' => __( 'Dieser Text kann ausgeklappt werden', 'ps-padma' ),
				'atts'    => array(
					'more_text' => array(
						'default' => __( 'Mehr anzeigen', 'ps-padma' ),
						'name'    => __( 'Mehr-Text', 'ps-padma' ),
						'desc'    => __( 'Text des Mehr-Links', 'ps-padma' ),
					),
					'less_text' => array(
						'default' => __( 'Weniger anzeigen', 'ps-padma' ),
						'name'    => __( 'Weniger-Text', 'ps-padma' ),
						'desc'    => __( 'Text des Weniger-Links', 'ps-padma' ),
					),
					'height' => array(
						'type'    => 'slider',
						'min'     => 0,
						'max'     => 1000,
						'step'    => 10,
						'default' => 100,
						'name'    => __( 'Höhe (eingeklappt)', 'ps-padma' ),
						'desc'    => __( 'Höhe im eingeklappten Zustand in Pixeln', 'ps-padma' ),
					),
					'hide_less' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Weniger-Link verstecken', 'ps-padma' ),
						'desc'    => __( 'Weniger-Link nach dem Ausklappen ausblenden', 'ps-padma' ),
					),
					'text_color' => array(
						'type'    => 'color',
						'default' => '#333333',
						'name'    => __( 'Textfarbe', 'ps-padma' ),
						'desc'    => __( 'Schriftfarbe des Textblocks', 'ps-padma' ),
					),
					'link_color' => array(
						'type'    => 'color',
						'default' => '#0088FF',
						'name'    => __( 'Link-Farbe', 'ps-padma' ),
						'desc'    => __( 'Farbe der Mehr/Weniger-Links', 'ps-padma' ),
					),
					'link_style' => array(
						'type'    => 'select',
						'values'  => array(
							'default'    => __( 'Standard', 'ps-padma' ),
							'underlined' => __( 'Unterstrichen', 'ps-padma' ),
							'dotted'     => __( 'Gepunktet', 'ps-padma' ),
							'dashed'     => __( 'Gestrichelt', 'ps-padma' ),
							'button'     => __( 'Button', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Link-Stil', 'ps-padma' ),
						'desc'    => __( 'Stil der Mehr/Weniger-Links', 'ps-padma' ),
					),
					'link_align' => array(
						'type'    => 'select',
						'values'  => array(
							'left'   => __( 'Links', 'ps-padma' ),
							'center' => __( 'Mitte', 'ps-padma' ),
							'right'  => __( 'Rechts', 'ps-padma' ),
						),
						'default' => 'left',
						'name'    => __( 'Link-Ausrichtung', 'ps-padma' ),
						'desc'    => __( 'Ausrichtung der Mehr/Weniger-Links', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// SPOILER
			// ----------------------------------------------------------------
			'spoiler' => array(
				'name'    => __( 'Spoiler', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'list-ul',
				'desc'    => __( 'Ausklappbarer Bereich mit verstecktem Inhalt', 'ps-padma' ),
				'content' => __( 'Versteckter Inhalt', 'ps-padma' ),
				'atts'    => array(
					'title' => array(
						'default' => __( 'Spoiler-Titel', 'ps-padma' ),
						'name'    => __( 'Titel', 'ps-padma' ),
						'desc'    => __( 'Text der Spoiler-Titelleiste', 'ps-padma' ),
					),
					'open' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Geöffnet', 'ps-padma' ),
						'desc'    => __( 'Spoiler standardmäßig geöffnet anzeigen', 'ps-padma' ),
					),
					'style' => array(
						'type'    => 'select',
						'values'  => array(
							'default' => __( 'Standard', 'ps-padma' ),
							'fancy'   => __( 'Fancy', 'ps-padma' ),
							'simple'  => __( 'Einfach', 'ps-padma' ),
						),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil des Spoilers', 'ps-padma' ),
					),
					'icon' => array(
						'type'    => 'select',
						'values'  => array(
							'plus'           => __( 'Plus', 'ps-padma' ),
							'plus-circle'    => __( 'Plus (Kreis)', 'ps-padma' ),
							'plus-square-1'  => __( 'Plus (Quadrat 1)', 'ps-padma' ),
							'plus-square-2'  => __( 'Plus (Quadrat 2)', 'ps-padma' ),
							'arrow'          => __( 'Pfeil', 'ps-padma' ),
							'arrow-circle-1' => __( 'Pfeil (Kreis 1)', 'ps-padma' ),
							'arrow-circle-2' => __( 'Pfeil (Kreis 2)', 'ps-padma' ),
							'chevron'        => __( 'Chevron', 'ps-padma' ),
							'chevron-circle' => __( 'Chevron (Kreis)', 'ps-padma' ),
							'caret'          => __( 'Caret', 'ps-padma' ),
							'caret-square'   => __( 'Caret (Quadrat)', 'ps-padma' ),
							'folder-1'       => __( 'Ordner 1', 'ps-padma' ),
							'folder-2'       => __( 'Ordner 2', 'ps-padma' ),
						),
						'default' => 'plus',
						'name'    => __( 'Icon', 'ps-padma' ),
						'desc'    => __( 'Icon für den Spoiler-Toggle', 'ps-padma' ),
					),
					'anchor' => array(
						'default' => '',
						'name'    => __( 'Anker', 'ps-padma' ),
						'desc'    => __( 'Eindeutiger Anker, um diesen Spoiler per URL-Hash zu öffnen', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// ACCORDION
			// ----------------------------------------------------------------
			'accordion' => array(
				'name'    => __( 'Akkordeon', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'list',
				'desc'    => __( 'Akkordeon aus mehreren Spoilern', 'ps-padma' ),
				'content' => '[su_spoiler title="' . __( 'Spoiler 1', 'ps-padma' ) . '"]' . __( 'Inhalt', 'ps-padma' ) . '[/su_spoiler]' . "\n" . '[su_spoiler title="' . __( 'Spoiler 2', 'ps-padma' ) . '"]' . __( 'Inhalt', 'ps-padma' ) . '[/su_spoiler]',
				'atts'    => array(
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// TABS
			// ----------------------------------------------------------------
			'tabs' => array(
				'name'    => __( 'Tabs', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'list-alt',
				'desc'    => __( 'Tab-Container', 'ps-padma' ),
				'content' => '[su_tab title="' . __( 'Tab 1', 'ps-padma' ) . '"]' . __( 'Inhalt Tab 1', 'ps-padma' ) . '[/su_tab]' . "\n" . '[su_tab title="' . __( 'Tab 2', 'ps-padma' ) . '"]' . __( 'Inhalt Tab 2', 'ps-padma' ) . '[/su_tab]',
				'atts'    => array(
					'style' => array(
						'type'    => 'select',
						'values'  => array( 'default' => __( 'Standard', 'ps-padma' ) ),
						'default' => 'default',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Stil der Tabs', 'ps-padma' ),
					),
					'active' => array(
						'type'    => 'slider',
						'min'     => 1,
						'max'     => 100,
						'step'    => 1,
						'default' => 1,
						'name'    => __( 'Aktiver Tab', 'ps-padma' ),
						'desc'    => __( 'Welcher Tab ist standardmäßig geöffnet', 'ps-padma' ),
					),
					'vertical' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Vertikal', 'ps-padma' ),
						'desc'    => __( 'Tabs vertikal ausrichten', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// TAB
			// ----------------------------------------------------------------
			'tab' => array(
				'name'    => __( 'Tab', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'list-alt',
				'desc'    => __( 'Einzelner Tab (innerhalb von Tabs)', 'ps-padma' ),
				'content' => __( 'Tab-Inhalt', 'ps-padma' ),
				'atts'    => array(
					'title' => array(
						'default' => __( 'Tab-Name', 'ps-padma' ),
						'name'    => __( 'Titel', 'ps-padma' ),
						'desc'    => __( 'Beschriftung des Tab-Reiters', 'ps-padma' ),
					),
					'disabled' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Deaktiviert', 'ps-padma' ),
						'desc'    => __( 'Tab deaktivieren', 'ps-padma' ),
					),
					'anchor' => array(
						'default' => '',
						'name'    => __( 'Anker', 'ps-padma' ),
						'desc'    => __( 'Eindeutiger Anker zum direkten Verlinken des Tabs', 'ps-padma' ),
					),
					'url' => array(
						'default' => '',
						'name'    => __( 'URL', 'ps-padma' ),
						'desc'    => __( 'Tab als Link verwenden (externe URL)', 'ps-padma' ),
					),
					'target' => array(
						'type'    => 'select',
						'values'  => array(
							'self'  => __( 'Gleiches Tab', 'ps-padma' ),
							'blank' => __( 'Neues Tab', 'ps-padma' ),
						),
						'default' => 'blank',
						'name'    => __( 'Link-Ziel', 'ps-padma' ),
						'desc'    => __( 'Wie wird der Tab-Link geöffnet', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// ROW
			// ----------------------------------------------------------------
			'row' => array(
				'name'    => __( 'Spalten-Container', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'columns',
				'desc'    => __( 'Container für flexible Spalten', 'ps-padma' ),
				'content' => '[su_column size="1/2"]' . __( 'Inhalt links', 'ps-padma' ) . '[/su_column]' . "\n" . '[su_column size="1/2"]' . __( 'Inhalt rechts', 'ps-padma' ) . '[/su_column]',
				'atts'    => array(
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// COLUMN
			// ----------------------------------------------------------------
			'column' => array(
				'name'    => __( 'Spalte', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'box',
				'icon'    => 'columns',
				'desc'    => __( 'Einzelne flexible Spalte (innerhalb von Spalten-Container)', 'ps-padma' ),
				'content' => __( 'Spalten-Inhalt', 'ps-padma' ),
				'atts'    => array(
					'size' => array(
						'type'    => 'select',
						'values'  => array(
							'1/1' => __( 'Volle Breite', 'ps-padma' ),
							'1/2' => __( 'Halbe Breite', 'ps-padma' ),
							'1/3' => __( 'Ein Drittel', 'ps-padma' ),
							'2/3' => __( 'Zwei Drittel', 'ps-padma' ),
							'1/4' => __( 'Ein Viertel', 'ps-padma' ),
							'3/4' => __( 'Drei Viertel', 'ps-padma' ),
							'1/5' => __( 'Ein Fünftel', 'ps-padma' ),
							'2/5' => __( 'Zwei Fünftel', 'ps-padma' ),
							'3/5' => __( 'Drei Fünftel', 'ps-padma' ),
							'4/5' => __( 'Vier Fünftel', 'ps-padma' ),
							'1/6' => __( 'Ein Sechstel', 'ps-padma' ),
							'5/6' => __( 'Fünf Sechstel', 'ps-padma' ),
						),
						'default' => '1/2',
						'name'    => __( 'Breite', 'ps-padma' ),
						'desc'    => __( 'Spaltenbreite relativ zur Zeile', 'ps-padma' ),
					),
					'center' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Zentriert', 'ps-padma' ),
						'desc'    => __( 'Spalte auf der Seite zentrieren', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// LIGHTBOX
			// ----------------------------------------------------------------
			'lightbox' => array(
				'name'    => __( 'Lightbox', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'gallery',
				'icon'    => 'search-plus',
				'desc'    => __( 'Lightbox-Trigger für Bild, Video oder HTML', 'ps-padma' ),
				'content' => __( 'Hier klicken', 'ps-padma' ),
				'atts'    => array(
					'type' => array(
						'type'    => 'select',
						'values'  => array(
							'iframe' => __( 'iFrame', 'ps-padma' ),
							'image'  => __( 'Bild', 'ps-padma' ),
							'inline' => __( 'Inline (HTML)', 'ps-padma' ),
						),
						'default' => 'iframe',
						'name'    => __( 'Inhaltstyp', 'ps-padma' ),
						'desc'    => __( 'Art des Lightbox-Inhalts', 'ps-padma' ),
					),
					'src' => array(
						'default' => '',
						'name'    => __( 'Quelle', 'ps-padma' ),
						'desc'    => __( 'URL (Bild/iFrame) oder CSS-Selektor (#id) für Inline-Inhalt', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// YOUTUBE
			// ----------------------------------------------------------------
			'youtube' => array(
				'name'  => __( 'YouTube', 'ps-padma' ),
				'type'  => 'single',
				'group' => 'media',
				'icon'  => 'youtube-play',
				'desc'  => __( 'YouTube-Video einbetten', 'ps-padma' ),
				'atts'  => array(
					'url' => array(
						'default' => '',
						'name'    => __( 'Video-URL', 'ps-padma' ),
						'desc'    => __( 'YouTube-URL des Videos', 'ps-padma' ),
					),
					'width' => array(
						'type'    => 'slider',
						'min'     => 100,
						'max'     => 1200,
						'step'    => 10,
						'default' => 600,
						'name'    => __( 'Breite', 'ps-padma' ),
						'desc'    => __( 'Breite in Pixeln (bei responsiv ignoriert)', 'ps-padma' ),
					),
					'height' => array(
						'type'    => 'slider',
						'min'     => 100,
						'max'     => 800,
						'step'    => 10,
						'default' => 400,
						'name'    => __( 'Höhe', 'ps-padma' ),
						'desc'    => __( 'Höhe in Pixeln (bei responsiv ignoriert)', 'ps-padma' ),
					),
					'autoplay' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Autoplay', 'ps-padma' ),
						'desc'    => __( 'Video automatisch starten', 'ps-padma' ),
					),
					'mute' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Stummschalten', 'ps-padma' ),
						'desc'    => __( 'Video stummschalten', 'ps-padma' ),
					),
					'responsive' => array(
						'type'    => 'bool',
						'default' => 'yes',
						'name'    => __( 'Responsiv', 'ps-padma' ),
						'desc'    => __( 'Video responsiv skalieren', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// VIMEO
			// ----------------------------------------------------------------
			'vimeo' => array(
				'name'  => __( 'Vimeo', 'ps-padma' ),
				'type'  => 'single',
				'group' => 'media',
				'icon'  => 'vimeo-square',
				'desc'  => __( 'Vimeo-Video einbetten', 'ps-padma' ),
				'atts'  => array(
					'url' => array(
						'default' => '',
						'name'    => __( 'Video-URL', 'ps-padma' ),
						'desc'    => __( 'Vimeo-URL des Videos', 'ps-padma' ),
					),
					'width' => array(
						'type'    => 'slider',
						'min'     => 100,
						'max'     => 1200,
						'step'    => 10,
						'default' => 600,
						'name'    => __( 'Breite', 'ps-padma' ),
						'desc'    => __( 'Breite in Pixeln', 'ps-padma' ),
					),
					'height' => array(
						'type'    => 'slider',
						'min'     => 100,
						'max'     => 800,
						'step'    => 10,
						'default' => 400,
						'name'    => __( 'Höhe', 'ps-padma' ),
						'desc'    => __( 'Höhe in Pixeln', 'ps-padma' ),
					),
					'autoplay' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Autoplay', 'ps-padma' ),
						'desc'    => __( 'Video automatisch starten', 'ps-padma' ),
					),
					'responsive' => array(
						'type'    => 'bool',
						'default' => 'yes',
						'name'    => __( 'Responsiv', 'ps-padma' ),
						'desc'    => __( 'Video responsiv skalieren', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// MEMBERS
			// ----------------------------------------------------------------
			'members' => array(
				'name'    => __( 'Nur für Mitglieder', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'other',
				'icon'    => 'lock',
				'desc'    => __( 'Inhalt nur für eingeloggte Nutzer', 'ps-padma' ),
				'content' => __( 'Geschützter Inhalt', 'ps-padma' ),
				'atts'    => array(
					'message' => array(
						'type'    => 'textarea',
						'default' => __( 'Dieser Inhalt ist nur für registrierte Nutzer. Bitte %login%.', 'ps-padma' ),
						'name'    => __( 'Hinweistext', 'ps-padma' ),
						'desc'    => __( 'Nachricht für nicht eingeloggte Nutzer. %login% wird durch den Login-Link ersetzt.', 'ps-padma' ),
					),
					'color' => array(
						'type'    => 'color',
						'default' => '#ffcc00',
						'name'    => __( 'Farbe', 'ps-padma' ),
						'desc'    => __( 'Hintergrundfarbe der Hinweisbox', 'ps-padma' ),
					),
					'login_text' => array(
						'default' => __( 'einloggen', 'ps-padma' ),
						'name'    => __( 'Login-Linktext', 'ps-padma' ),
						'desc'    => __( 'Text des Login-Links im Hinweis', 'ps-padma' ),
					),
					'login_url' => array(
						'default' => '',
						'name'    => __( 'Login-URL', 'ps-padma' ),
						'desc'    => __( 'Manuelle Login-URL (leer = WordPress Standard)', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// GUESTS
			// ----------------------------------------------------------------
			'guests' => array(
				'name'    => __( 'Nur für Gäste', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'other',
				'icon'    => 'eye',
				'desc'    => __( 'Inhalt nur für nicht eingeloggte Nutzer', 'ps-padma' ),
				'content' => __( 'Inhalt für Gäste', 'ps-padma' ),
				'atts'    => array(
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

			// ----------------------------------------------------------------
			// EXPAND (Show More)
			// ----------------------------------------------------------------
			'tooltip' => array(
				'name'    => __( 'Tooltip', 'ps-padma' ),
				'type'    => 'wrap',
				'group'   => 'other',
				'icon'    => 'info-circle',
				'desc'    => __( 'Tooltip beim Hover/Klick', 'ps-padma' ),
				'content' => __( 'Text mit Tooltip', 'ps-padma' ),
				'atts'    => array(
					'content' => array(
						'type'    => 'textarea',
						'default' => __( 'Tooltip-Text', 'ps-padma' ),
						'name'    => __( 'Tooltip-Text', 'ps-padma' ),
						'desc'    => __( 'Der anzuzeigende Tooltip-Text', 'ps-padma' ),
					),
					'title' => array(
						'default' => '',
						'name'    => __( 'Titel', 'ps-padma' ),
						'desc'    => __( 'Optionaler Tooltip-Titel', 'ps-padma' ),
					),
					'style' => array(
						'type'    => 'select',
						'values'  => array(
							'yellow'    => __( 'Gelb', 'ps-padma' ),
							'light'     => __( 'Hell', 'ps-padma' ),
							'dark'      => __( 'Dunkel', 'ps-padma' ),
							'green'     => __( 'Grün', 'ps-padma' ),
							'red'       => __( 'Rot', 'ps-padma' ),
							'blue'      => __( 'Blau', 'ps-padma' ),
							'bootstrap' => __( 'Bootstrap', 'ps-padma' ),
						),
						'default' => 'yellow',
						'name'    => __( 'Stil', 'ps-padma' ),
						'desc'    => __( 'Farbschema des Tooltips', 'ps-padma' ),
					),
					'position' => array(
						'type'    => 'select',
						'values'  => array(
							'north' => __( 'Oben', 'ps-padma' ),
							'south' => __( 'Unten', 'ps-padma' ),
							'east'  => __( 'Rechts', 'ps-padma' ),
							'west'  => __( 'Links', 'ps-padma' ),
						),
						'default' => 'north',
						'name'    => __( 'Position', 'ps-padma' ),
						'desc'    => __( 'Wo erscheint der Tooltip', 'ps-padma' ),
					),
					'behavior' => array(
						'type'    => 'select',
						'values'  => array(
							'hover' => __( 'Bei Hover', 'ps-padma' ),
							'click' => __( 'Bei Klick', 'ps-padma' ),
						),
						'default' => 'hover',
						'name'    => __( 'Verhalten', 'ps-padma' ),
						'desc'    => __( 'Wann wird der Tooltip angezeigt', 'ps-padma' ),
					),
					'close' => array(
						'type'    => 'bool',
						'default' => 'no',
						'name'    => __( 'Schließen-Button', 'ps-padma' ),
						'desc'    => __( 'Schließen-Button im Tooltip anzeigen', 'ps-padma' ),
					),
					'class' => array(
						'type'    => 'extra_css_class',
						'default' => '',
						'name'    => __( 'Zusätzliche CSS-Klasse', 'ps-padma' ),
						'desc'    => __( 'Weitere CSS-Klassen, mit Leerzeichen getrennt', 'ps-padma' ),
					),
				),
			),

		) ); // end apply_filters

		if ( $shortcode ) {
			return isset( $data[ $shortcode ] ) ? $data[ $shortcode ] : false;
		}

		return $data;
	}

	/**
	 * Font-Awesome-Icons für den Icon-Picker (Auswahl, kein AJAX benötigt)
	 */
	public static function icons() {
		return apply_filters( 'padma/generator/icons', array(
			'address-book', 'adjust', 'ambulance', 'anchor', 'archive', 'arrows', 'arrows-h', 'arrows-v',
			'asterisk', 'at', 'balance-scale', 'ban', 'bar-chart', 'barcode', 'bars', 'bed', 'beer',
			'bell', 'bell-o', 'bell-slash', 'bicycle', 'bolt', 'bomb', 'book', 'bookmark', 'briefcase',
			'bug', 'building', 'bullhorn', 'bullseye', 'bus', 'calculator', 'calendar', 'camera',
			'car', 'cart-arrow-down', 'cart-plus', 'certificate', 'check', 'check-circle',
			'check-square', 'child', 'circle', 'clock-o', 'cloud', 'cloud-download', 'cloud-upload',
			'code', 'coffee', 'cog', 'cogs', 'comment', 'comments', 'compass', 'copyright',
			'credit-card', 'cube', 'cubes', 'cutlery', 'database', 'desktop', 'diamond', 'download',
			'edit', 'envelope', 'envelope-o', 'eraser', 'exchange', 'exclamation', 'exclamation-circle',
			'exclamation-triangle', 'external-link', 'eye', 'eye-slash', 'fax', 'female', 'fighter-jet',
			'file', 'file-o', 'file-pdf-o', 'film', 'filter', 'fire', 'flag', 'flask', 'folder',
			'folder-open', 'frown-o', 'gamepad', 'gift', 'glass', 'globe', 'graduation-cap', 'group',
			'hand-o-right', 'hand-peace-o', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat',
			'history', 'home', 'hourglass', 'image', 'inbox', 'industry', 'info', 'info-circle',
			'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'lemon-o', 'lightbulb-o', 'link',
			'list', 'list-alt', 'list-ol', 'list-ul', 'location-arrow', 'lock', 'magic', 'magnet',
			'male', 'map', 'map-marker', 'microphone', 'minus', 'minus-circle', 'mobile', 'money',
			'moon-o', 'motorcycle', 'music', 'newspaper-o', 'paint-brush', 'paper-plane', 'paw',
			'pencil', 'pencil-square', 'percent', 'phone', 'phone-square', 'picture-o', 'pie-chart',
			'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'power-off', 'print',
			'puzzle-piece', 'question', 'question-circle', 'quote-left', 'quote-right', 'random',
			'recycle', 'refresh', 'reply', 'retweet', 'road', 'rocket', 'rss', 'search',
			'search-minus', 'search-plus', 'send', 'server', 'share', 'shield', 'ship',
			'shopping-cart', 'sign-in', 'sign-out', 'signal', 'sitemap', 'sliders', 'smile-o',
			'sort', 'sort-asc', 'sort-desc', 'space-shuttle', 'spinner', 'square', 'star', 'star-o',
			'sticky-note', 'suitcase', 'sun-o', 'tablet', 'tachometer', 'tag', 'tags', 'tasks',
			'terminal', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up',
			'ticket', 'times', 'times-circle', 'tint', 'toggle-off', 'toggle-on', 'trademark',
			'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tv', 'umbrella', 'university',
			'unlock', 'upload', 'user', 'user-plus', 'user-secret', 'users', 'video-camera',
			'volume-down', 'volume-off', 'volume-up', 'warning', 'wheelchair', 'wifi', 'wrench',
			'youtube-play', 'vimeo-square', 'twitter', 'facebook', 'github', 'instagram',
			'linkedin', 'pinterest', 'reddit', 'skype', 'slack', 'soundcloud', 'spotify',
			'stack-overflow', 'telegram', 'tumblr', 'twitch', 'whatsapp', 'wordpress', 'xing',
		) );
	}

}
