<?php
/**
 * Content Slider Block Options
 *
 * @package    Padma_Advanced
 * @subpackage Padma_Advanced/blocks
 * @author     PSOURCE
 */

namespace Padma_Advanced;

/**
 * Options class for Content Slider block
 */
class PadmaContentSliderBlockOptions extends \PadmaBlockOptionsAPI {

	/**
	 * Block tabs for options.
	 *
	 * @var array $tabs
	 */
	public $tabs;

	/**
	 * Inputs for each tab.
	 *
	 * @var array $inputs
	 */
	public $inputs;

	/**
	 * Init block options
	 */
	public function __construct() {

		$this->tabs = array(
			'content-tab' 	=> __( 'Inhalt', 'padma' ),
			'slider-tab' 	=> __( 'Einstellungen', 'padma' ),
		);


		$this->inputs = array(

		'content-tab' => array(
			'post-type' => array(
				'type' => 'select',
				'name' => 'post-type',
				'label' => __( 'Post-Type', 'padma' ),
				'default' => 'post',
				'tooltip' => '',		
				'options' => 'get_post_types()',
				'callback' => ''
			),

			'categories' => array(
				'type' => 'multi-select',
				'name' => 'categories',
				'label' => __( 'Kategorien', 'padma' ),
				'tooltip' => '',
				'options' => 'get_categories()'
			),

			'order-by' => array(
				'type' => 'select',
				'name' => 'order-by',
				'label' => __( 'Sortieren nach', 'padma' ),
				'tooltip' => '',
				'options' => array(
					'date' => __( 'Datum', 'padma' ),
					'title' => __( 'Titel', 'padma' ),
					'rand' => __( 'Zufällig', 'padma' ),
					'comment_count' => __( 'Kommentar-Anzahl', 'padma' ),
					'ID' => 'ID',
					'author' => __( 'Autor', 'padma' ),
					'type' => __( 'Post Type', 'padma' ),
					'menu_order' => __( 'Benutzerdefinierte Reihenfolge', 'padma' )
				)
			),
			
			'order' => array(
				'type' => 'select',
				'name' => 'order',
				'label' => __( 'Reihenfolge', 'padma' ),
				'tooltip' => '',
				'options' => array(
					'desc' => __( 'Absteigend', 'padma' ),
					'asc' => __( 'Aufsteigend', 'padma' ),
				)
			),

			'only-title' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'only-title',
				'label' 	=> __( 'Nur Titel anzeigen', 'padma' ),
				'tooltip' 	=> __( 'Zeigt nur den Titel an', 'padma' ),
				'toggle'    => array(
					'false' => array(
						'hide' => array(
							'#input-link-title'
						)
					),
					'true' => array(
						'show' => array(
							'#input-link-title'
						)
					)
				)
			),

			'link-title' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'link-title',
				'label' 	=> __( 'Titel als Link', 'padma' ),
				'tooltip' 	=> __( 'Macht den Titel zu einem anklickbaren Link', 'padma' ),				
			),

			'only-featured' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'only-featured',
				'label' 	=> __( 'Nur Beitragsbild anzeigen', 'padma' ),
				'tooltip' 	=> __( 'Zeigt nur das Beitragsbild an', 'padma' ),
			),

			'only-excerpt' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'only-excerpt',
				'label' 	=> __( 'Nur Auszug anzeigen', 'padma' ),
				'tooltip' 	=> __( 'Zeigt nur den Beitrags-Auszug an', 'padma' ),
			),
			'show-link' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'show-link',
				'label' 	=> __( 'Link anzeigen', 'padma' ),
				'tooltip' 	=> __( 'Zeigt einen Link zum vollständigen Beitrag an', 'padma' ),
			),
			'show-link-text' => array(
				'type' 		=> 'text',
				'default' 	=> 'Mehr anzeigen',
				'name' 		=> 'show-link-text',
				'label' 	=> __( 'Link-Text', 'padma' ),
				'tooltip' 	=> __( 'Text für den Link zum vollständigen Beitrag', 'padma' ),
			),
			'image-max-height' => array(
				'type' 		=> 'integer',
				'default' 	=> 400,
				'name' 		=> 'image-max-height',
				'label' 	=> __( 'Maximale Bildhöhe', 'padma' ),
				'tooltip' 	=> __( 'Maximale Höhe der Bilder in Pixel', 'padma' ),
			),
		),
		'slider-tab' => array(

			'items' => array(
				'type' 		=> 'integer',
				'default' 	=> 1,
				'name' 		=> 'items',
				'label' 	=> __( 'Anzahl Elemente', 'padma' ),
				'tooltip' 	=> __( 'Wie viele Elemente sollen gleichzeitig angezeigt werden?', 'padma' ),				
			),

			'margin' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'margin',
				'label' 	=> __( 'Abstand rechts', 'padma' ),
				'tooltip' 	=> __( 'Abstand (margin-right) in Pixel zwischen den Elementen', 'padma' ),
			),

			'loop' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'loop',
				'label' 	=> __( 'Endlos-Schleife', 'padma' ),
				'tooltip' 	=> __( 'Endlos-Schleife aktivieren. Dupliziert erste und letzte Elemente für nahtlose Wiederholung', 'padma' ),
			),

			'center' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'center',
				'label' 	=> __( 'Zentrieren', 'padma' ),
				'tooltip' 	=> __( 'Zentriert das aktive Element. Funktioniert auch bei ungerader Anzahl', 'padma' ),
			),

			'mouse-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'mouse-drag',
				'label' 	=> __( 'Maus-Drag', 'padma' ),
				'tooltip' 	=> __( 'Slider mit der Maus ziehen aktivieren', 'padma' ),
			),

			'touch-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'touch-drag',
				'label' 	=> __( 'Touch-Drag', 'padma' ),
				'tooltip' 	=> __( 'Touch-Gesten auf mobilen Geräten aktivieren', 'padma' ),
			),
			
			'pull-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'pull-drag',
				'label' 	=> __( 'Pull Drag', 'padma' ),
				'tooltip' 	=> __( 'Slider kann bis zum Rand gezogen werden', 'padma' ),
			),

			'free-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'free-drag',
				'label' 	=> __( 'Freies Ziehen', 'padma' ),
				'tooltip' 	=> __( 'Elemente können frei bis zum Rand gezogen werden', 'padma' ),
			),

			'stage-padding' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'stage-padding',
				'label' 	=> __( 'Bühnen-Abstand', 'padma' ),
				'tooltip' 	=> __( 'Abstand links und rechts (sichtbare Nachbar-Elemente)', 'padma' ),
			),

			'merge' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'merge',
				'label' 	=> __( 'Elemente verbinden', 'padma' ),
				'tooltip' 	=> __( 'Elemente verbinden. Sucht nach data-merge="{number}" im Element', 'padma' ),
			),

			'merge-fit' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'merge-fit',
				'label' 	=> __( 'Verbundene Elemente anpassen', 'padma' ),
				'tooltip' 	=> __( 'Passt verbundene Elemente an, wenn Bildschirm kleiner ist', 'padma' ),
			),

			'auto-width' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'auto-width',
				'label' 	=> __( 'Automatische Breite', 'padma' ),
				'tooltip' 	=> __( 'Für Inhalte ohne festes Raster. Nutze width-Style auf divs', 'padma' ),
			),

			'item-width' => array(
				'type' 		=> 'integer',
				'default' 	=> 800,
				'name' 		=> 'item-width',
				'label' 	=> __( 'Element-Breite', 'padma' ),
				'tooltip' 	=> __( 'Breite in Pixel. Benötigt "Automatische Breite"', 'padma' ),
			),
			
			'start-position' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'start-position',
				'label' 	=> __( 'Startposition', 'padma' ),
				'tooltip' 	=> __( 'Bei welchem Slide soll gestartet werden?', 'padma' ),
			),

			'url-hash-listener' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'url-hash-listener',
				'label' 	=> __( 'URL-Hash Listener', 'padma' ),
				'tooltip' 	=> __( 'Reagiert auf URL-Hash-Änderungen. Benötigt data-hash bei Elementen', 'padma' ),
			),

			'nav' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'nav',
				'label' 	=> __( 'Vor/Zurück-Buttons zeigen', 'padma' ),
				'tooltip' 	=> __( 'Navigations-Pfeile anzeigen', 'padma' ),
			),

			'rewind' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'rewind',
				'label' 	=> __( 'Zurückspulen', 'padma' ),
				'tooltip' 	=> __( 'Spring zurück zum Anfang wenn das Ende erreicht ist', 'padma' ),
			),

			'nav-text-next' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nav-text-next',
				'label' 	=> __( '"Weiter"-Text', 'padma' ),
				'tooltip' 	=> __( 'HTML erlaubt', 'padma' ),
			),

			'nav-text-prev' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nav-text-prev',
				'label' 	=> __( '"Zurück"-Text', 'padma' ),
				'tooltip' 	=> __( 'HTML erlaubt', 'padma' ),
			),

			'nav-element' => array(
				'type' 		=> 'text',
				'default' 	=> 'div',
				'name' 		=> 'nav-element',
				'label' 	=> __( 'Nav-Element', 'padma' ),
				'tooltip' 	=> __( 'DOM-Element-Typ für einen einzelnen Navigations-Link', 'padma' ),
			),

			'slide-by' => array(
				'type' 		=> 'integer',
				'default' 	=> 1,
				'name' 		=> 'slide-by',
				'label' 	=> __( 'Slide um X', 'padma' ),
				'tooltip' 	=> __( 'Navigation springt um X Elemente. "page" springt seitenweise', 'padma' ),
			),

			'slide-transition' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'slide-transition',
				'label' 	=> __( 'Slide-Übergang', 'padma' ),
				'tooltip' 	=> __( 'CSS-Übergang für die Bühne, z.B. "linear"', 'padma' ),
			),

			'dots' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'dots',
				'label' 	=> __( 'Punkte', 'padma' ),
				'tooltip' 	=> __( 'Zeige Punkt-Navigation', 'padma' ),
			),

			'dots-each' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'dots-each',
				'label' 	=> __( 'Punkt alle X Elemente', 'padma' ),
				'tooltip' 	=> __( 'Zeige einen Punkt für je X Elemente', 'padma' ),
			),

			'dots-data' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dots-data',
				'label' 	=> __( 'Punkte-Daten', 'padma' ),
				'tooltip' 	=> __( 'Verwende data-dot Inhalt', 'padma' ),
			),

			'lazy-load' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'lazy-load',
				'label' 	=> __( 'Lazy Load', 'padma' ),
				'tooltip' 	=> __( 'Lädt Bilder erst bei Bedarf. Nutze data-src und data-src-retina', 'padma' ),
			),

			'lazy-load-eager' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'lazy-load-eager',
				'label' 	=> __( 'Lazy Load Eager', 'padma' ),
				'tooltip' 	=> __( 'Lädt X Bilder im Voraus (rechts und bei Loop auch links)', 'padma' ),
			),

			'autoplay' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'autoplay',
				'label' 	=> __( 'Autoplay', 'padma' ),
				'tooltip' 	=> __( 'Automatisches Abspielen aktivieren', 'padma' ),
			),

			'autoplay-timeout' => array(
				'type' 		=> 'integer',
				'default' 	=> 5000,
				'name' 		=> 'autoplay-timeout',
				'label' 	=> __( 'Autoplay Timeout', 'padma' ),
				'tooltip' 	=> __( 'Wartezeit in Millisekunden zwischen Slides', 'padma' ),
			),

			'autoplay-hover-pause' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'autoplay-hover-pause',
				'label' 	=> __( 'Pause bei Hover', 'padma' ),
				'tooltip' 	=> __( 'Pausiert bei Maus-Hover', 'padma' ),
			),
			'autoplay-speed' => array(
				'type' 		=> 'integer',
				'default' 	=> 5000,
				'name' 		=> 'autoplay-speed',
				'label' 	=> __( 'Autoplay Geschwindigkeit', 'padma' ),
				'tooltip' 	=> __( 'Geschwindigkeit des Autoplay', 'padma' ),
			),
			/*
			'smartSpeed' => array(
				'type' 		=> 'integer',
				'default' 	=> 250,
				'name' 		=> 'smartSpeed',
				'label' 	=> 'Smart Speed',
				'tooltip' 	=> 'Speed Calculate. More info to come..',
			),

			'fluidSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'fluidSpeed',
				'label' 	=> 'Fluid Speed',
				'tooltip' 	=> 'Speed Calculate. More info to come..',
			),

			'autoplaySpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'autoplaySpeed',
				'label' 	=> 'Autoplay speed.',
				'tooltip' 	=> 'Autoplay speed.',
			),

			'navSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'navSpeed',
				'label' 	=> 'Nav speed.',
				'tooltip' 	=> 'Navigation speed.',
			),

			'dotsSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dotsSpeed',
				'label' 	=> 'Dots speed.',
				'tooltip' 	=> 'Pagination speed.',
			),

			'dragEndSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dragEndSpeed',
				'label' 	=> 'Drag End speed.',
				'tooltip' 	=> 'Drag end speed.',
			),
			*/

			'callbacks' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'callbacks',
				'label' 	=> __( 'Callbacks', 'padma' ),
				'tooltip' 	=> __( 'Callback-Events aktivieren', 'padma' ),
			),

			/*
				To DO
			'responsive' => array(
				'type' 		=> 'object',
				'default' 	=> '',
				'name' 		=> 'responsive',
				'label' 	=> 'Responsive',
				'tooltip' 	=> 'Object containing responsive options. Can be set to false to remove responsive capabilities.',
			),*/

			'responsive-refresh-rate' => array(
				'type' 		=> 'integer',
				'default' 	=> 200,
				'name' 		=> 'responsive-refresh-rate',
				'label' 	=> __( 'Responsive Refresh Rate', 'padma' ),
				'tooltip' 	=> __( 'Aktualisierungsrate für responsive Anpassungen', 'padma' ),
			),
			/*
				To Do
			'responsiveBaseElement' => array(
				'type' 		=> 'DOM element ',
				'default' 	=> 200,
				'name' 		=> 'responsiveBaseElement',
				'label' 	=> 'Responsive Base Element',
				'tooltip' 	=> 'Responsive refresh rate.',
			),
			*/


			'video' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'video',
				'label' 	=> __( 'Video', 'padma' ),
				'tooltip' 	=> __( 'YouTube/Vimeo/Vzaar Videos laden aktivieren', 'padma' ),
			),

			'video-height' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'video-height',
				'label' 	=> __( 'Video-Höhe', 'padma' ),
				'tooltip' 	=> __( 'Höhe für Videos festlegen', 'padma' ),
			),

			'video-width' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'video-width',
				'label' 	=> __( 'Video-Breite', 'padma' ),
				'tooltip' 	=> __( 'Breite für Videos festlegen', 'padma' ),
			),

			'animate-out' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'animate-out',
				'label' 	=> __( 'AnimateOut Class', 'padma' ),
				'tooltip' 	=> __( 'CSS3-Animations-Klasse für Ausblenden', 'padma' ),
			),

			'animate-in' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'animate-in',
				'label' 	=> __( 'AnimateIn Class', 'padma' ),
				'tooltip' 	=> __( 'CSS3-Animations-Klasse für Einblenden', 'padma' ),
			),

			'fallback-easing' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'fallback-easing',
				'label' 	=> __( 'Fallback Easing', 'padma' ),
				'tooltip' 	=> __( 'Easing für CSS2 $.animate', 'padma' ),
			),
			/*

				To Do
			'info' => array(
				'type' 		=> 'function',
				'default' 	=> null,
				'name' 		=> 'info',
				'label' 	=> 'Fallback Easing',
				'tooltip' 	=> 'Callback to retrieve basic information (current item/pages/widths). Info function second parameter is Owl DOM object reference.',
			),
			*/

			'nested-item-selector' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nested-item-selector',
				'label' 	=> __( 'Verschachtelter Item-Selektor', 'padma' ),
				'tooltip' 	=> __( 'Nutze dies wenn owl-items tief verschachtelt sind. Z.B. "youritem" (ohne Punkt)', 'padma' ),
			),

			'item-element' => array(
				'type' 		=> 'text',
				'default' 	=> 'div',
				'name' 		=> 'item-element',
				'label' 	=> __( 'Item-Element', 'padma' ),
				'tooltip' 	=> __( 'DOM-Element-Typ für owl-item', 'padma' ),
			),

			'stage-element' => array(
				'type' 		=> 'text',
				'default' 	=> 'div',
				'name' 		=> 'stage-element',
				'label' 	=> __( 'Bühnen-Element', 'padma' ),
				'tooltip' 	=> __( 'DOM-Element-Typ für owl-stage', 'padma' ),
			),

			'nav-container' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nav-container',
				'label' 	=> __( 'Nav-Container', 'padma' ),
				'tooltip' 	=> __( 'Eigener Container für Navigation', 'padma' ),
			),

			'dots-container' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'dots-container',
				'label' 	=> __( 'Punkte-Container', 'padma' ),
				'tooltip' 	=> __( 'Eigener Container für Punkte-Navigation', 'padma' ),
			),

			'check-visible' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'check-visible',
				'label' 	=> __( 'Sichtbarkeit prüfen', 'padma' ),
				'tooltip' 	=> __( 'Wenn du weißt, dass der Slider immer sichtbar ist, setze dies auf "false" für bessere Performance', 'padma' ),
			),
		),

	);
	}

	function get_categories() {
		
		$category_options 			= array();		
		$categories_select_query 	= get_categories();
		
		foreach ($categories_select_query as $category)
			$category_options[$category->term_id] = $category->name;

		return $category_options;	
	}

	function get_tags() {
		
		$tag_options = array();
		$tags_select_query = get_terms('post_tag');
		foreach ($tags_select_query as $tag)
			$tag_options[$tag->term_id] = $tag->name;
		$tag_options = (count($tag_options) == 0) ? array('text'	 => __( 'Keine Tags verfügbar', 'padma' )) : $tag_options;
		return $tag_options;
	}

	function get_post_types() {
		
		$post_type_options 	= array();
		$post_types 		= get_post_types(false, 'objects'); 
			
		foreach($post_types as $post_type_id => $post_type){
			
			//Make sure the post type is not an excluded post type.
			if(in_array($post_type_id, array('revision', 'nav_menu_item'))) 
				continue;
			
			$post_type_options[$post_type_id] = $post_type->labels->name;
		
		}
		
		return $post_type_options;
	}

	function get_authors() {
		
		$author_options = array();
		
		$authors = get_users(array(
			'orderby' => 'post_count',
			'order' => 'desc',
			'who' => 'authors'
		));
		
		foreach ( $authors as $author )
			$author_options[$author->ID] = $author->display_name;
			
		return $author_options;	
	}

	function get_taxonomies() {

		$taxonomy_options = array( __( '&ndash; Standard: Kategorie &ndash;', 'padma' ) );

		$taxonomy_select_query=get_taxonomies(false, 'objects', 'or');

		
		foreach ($taxonomy_select_query as $taxonomy)
			$taxonomy_options[$taxonomy->name] = $taxonomy->label;
		
		
		return $taxonomy_options;
	}

	function get_post_status() {
		
		return get_post_stati();
		
	}
}