<?php

class PadmaListingsBlockOptions extends PadmaBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'listing-type' => __('Listentyp auswählen','padma'),
			'posts-pages-filters' => __('Beiträge &amp; Seiten Filter','padma'),
			'taxonomy-options' => __('Taxonomieoptionen','padma')
		);

		$this->inputs = array(
			'listing-type' => array(

				'listing-type' => array(
					'type' => 'select',
					'name' => 'listing-type',
					'label' => __('Liste?','padma'),
					'tooltip' => __('Wähle einen Typ der Listenausgabe und konfiguriere ihn dann mit den Optionen links.','padma'),
					'options' => array(
						'taxonomy' => __('Taxonomie (Kategorie, Schlagwort etc)','padma'),
						'content' => __('Beiträge oder Seiten (benutzerdefinierte Beiträge)','padma'),
						'authors' => __('Autoren','padma')
					),
					'default' => 'taxonomy',
					'toggle'    => array(
						'taxonomy' => array(
							'show' => array(
								'#sub-tab-taxonomy-options'
							),
							'hide' => array(
								'#sub-tab-posts-pages-filters'
							)
						),
						'content' => array(
							'hide' => array(
								'#sub-tab-taxonomy-options'
							),
							'show' => array(
								'#sub-tab-posts-pages-filters'
							)
						)
					)
				)

			),

			'taxonomy-options'	=> array(

				'terms-select-taxonomy-heading' => array(
					'name' => 'terms-select-taxonomy-heading',
					'type' => 'heading',
					'label' => __('Taxonomie auswählen','padma')
				),

				'select-taxonomy' => array(
					'label' => __('Taxonomie zum Anzeigen auswählen','padma'),
					'type' => 'select',
					'name' => 'select-taxonomy',
					'options' => 'get_taxonomies()',
					'default' => 'category',
				),

				'terms-options-sorting-heading' => array(
					'name' => 'terms-options-sorting-heading',
					'type' => 'heading',
					'label' => __('Taxonomie sortieren','padma')
				),

				'terms-orderby' => array(
					'type' => 'select',
					'name' => 'terms-orderby',
					'label' => __('Sortieren nach?','padma'),
					'tooltip' => __('Sortiere Begriffe alphabetisch, nach eindeutiger Term-ID oder nach der Anzahl der Elemente in diesem Begriff','padma'),
					'options' => array(
						'none' => __('Keine','padma'),
						'ID' => 'ID',
						'name' => __('Name','padma'),
						'slug' => __('Slug','padma'),
						'count' => __('Anzahl','padma'),
						//'term_group' => 'Term Group'
					),
					'default' => 'name'
				),

				'terms-order' => array(
					'type' => 'select',
					'name' => 'terms-order',
					'label' => __('Sortierreihenfolge?','padma'),
					'tooltip' => __('Sortierreihenfolge für Begriffe (entweder aufsteigend oder absteigend).','padma'),
					'options' => array(
						'DESC' => __('Absteigend','padma'),
						'ASC' => __('Aufsteigend','padma')
					),
					'default' => 'ASC'
				),

				'terms-options-filter-heading' => array(
					'name' => 'terms-options-filter-heading',
					'type' => 'heading',
					'label' => __('Taxonomie filtern','padma')
				),

				'terms-number' => array(
					'type' => 'slider',
					'slider-min' => 0,
					'slider-max' => 30,
					'slider-interval' => 1,
					'name' => 'terms-number',
					'label' => __('Anzahl der Begriffe','padma'),
					'default' => '10',
					'tooltip' => __('Legt die Anzahl der anzuzeigenden Begriffe fest. Standard ist 0 für keine Begrenzung.','padma')
				),

				'terms-child-of' => array(
					'type' => 'select',
					'name' => 'terms-child-of',
					'label' => __('Child von','padma'),
					'options' => 'get_listing_terms()',
					'default' => '',
					'tooltip' => __('Nur Begriffe anzeigen, die Kinder von dem sind, was Du hier angibst.','padma')
				),

				'terms-exclude' => array(
					'type' => 'multi-select',
					'name' => 'terms-exclude',
					'label' => __('Ausschließen','padma'),
					'options' => 'get_listing_terms()',
					'default' => '',
					'tooltip' => __('Schließe einen oder mehrere Begriffe von den Ergebnissen aus.','padma')
				),

				'terms-include' => array(
					'type' => 'multi-select',
					'name' => 'terms-include',
					'label' => __('Einschließen','padma'),
					'options' => 'get_listing_terms()',
					'default' => '',
					'tooltip' => __('Nur bestimmte Begriffe in der Liste einschließen.','padma')
				),

				'terms-slug' => array(
					'name' => 'terms-slug',
					'type' => 'text',
					'label' => 'Slug',
					'tooltip' => __('Gibt Begriffe zurück, deren "Slug" diesem Wert entspricht. Standard ist ein leerer String.','padma')
				),

				'terms-options-display-heading' => array(
					'name' => 'terms-options-display-heading',
					'type' => 'heading',
					'label' => __('Taxonomie anzeigen','padma')
				),

				'terms-hide-empty' => array(
					'type' => 'checkbox',
					'name' => 'terms-hide-empty', 
					'label' => __('Leer ausblenden?','padma'),
					'tooltip' => __('Blendet Begriffe ohne Beiträge aus.','padma'),
					'default' => true
				),

				'terms-hierarchical' => array(
					'type' => 'checkbox',
					'name' => 'terms-hierarchical', 
					'label' => __('Hierarchisch?','padma'),
					'tooltip' => __('Ob Begriffe mit nicht-leeren Nachkommen eingeschlossen werden sollen.','padma'),
					'default' => true
				)

			),

			'posts-pages-filters' => array(

				'number-of-posts' => array(
					'type' => 'integer',
					'name' => 'number-of-posts',
					'label' => __('Anzahl der Beiträge','padma'),
					'tooltip' => '',
					'default' => 5
				),

				'posts-pages-post-type-heading' => array(
					'name' => 'posts-pages-post-type-heading',
					'type' => 'heading',
					'label' => __('Inhalt filtern','padma')
				),

				'post-type' => array(
					'type' => 'select',
					'name' => 'post-type',
					'label' => __('Beitragstyp','padma'),
					'tooltip' => '',
					'options' => 'get_post_types()',
					'toggle'    => array(
						'0' => array(
							'hide' => array(
								'#input-post-taxonomy-filter',
								'#input-terms'
							)
						)
					),
					'callback' => 'reloadBlockOptions()'
				),

				'post-taxonomy-filter' => array(
					'label' => __('Taxonomie zum Filtern auswählen','padma'),
					'type' => 'select',
					'name' => 'post-taxonomy-filter',
					'options' => 'get_taxonomies()',
					'default' => 'category',
					'toggle'    => array(
						'0' => array(
							'hide' => array(
								'#input-terms'
							)
						)
					),
					'callback' => '
						reloadBlockOptions()'
				),

				'terms' => array(
					'type' => 'multi-select',
					'name' => 'terms',
					'tooltip' => ''
				),

				'author' => array(
					'type' => 'multi-select',
					'name' => 'author',
					'label' => __('Autor','padma'),
					'tooltip' => '',
					'options' => 'get_authors()'
				),

				'offset' => array(
					'type' => 'integer',
					'name' => 'offset',
					'label' => __('Offset','padma'),
					'tooltip' => __('Der Offset ist die Anzahl der Einträge oder Beiträge, die Sie überspringen möchten. Wenn der Offset 1 ist, wird der erste Beitrag übersprungen.','padma'),
					'default' => 0
				),

				'posts-pages-sort-heading' => array(
					'name' => 'posts-pages-sort-heading',
					'type' => 'heading',
					'label' => __('Inhalt sortieren','padma')
				),

				'order-by' => array(
					'type' => 'select',
					'name' => 'order-by',
					'label' => __('Sortieren nach','padma'),
					'tooltip' => '',
					'options' => array(
						'date' => __('Datum','padma'),
						'title' => __('Titel','padma'),
						'rand' => __('Zufällig','padma'),
						'comment_count' => __('Anzahl der Kommentare','padma'),
						'ID' => 'ID'
					)
				),

				'order' => array(
					'type' => 'select',
					'name' => 'order',
					'label' => __('Reihenfolge','padma'),
					'tooltip' => '',
					'options' => array(
						'desc' => __('Absteigend','padma'),
						'asc' => __('Aufsteigend','padma'),
					)
				)
			),
		);

	}

	function modify_arguments($args = false) {

		$block = $args['block'];

		/* Content Options */
		$taxomomy = PadmaBlockAPI::get_setting($block, 'post-taxonomy-filter');

		$terms = self::get_listing_terms($taxomomy);
		$label = self::get_taxonomy_label($taxomomy);
		$post_type = PadmaBlockAPI::get_setting($block, 'post-type');
		$taxonomies = self::get_taxonomies($post_type);

		$this->inputs['posts-pages-filters']['terms']['options'] = $terms;
		$this->inputs['posts-pages-filters']['terms']['label'] = $label;
		$this->inputs['posts-pages-filters']['post-taxonomy-filter']['options'] = $taxonomies;

		/* Taxonomy Options */
		$this->inputs['taxonomy-options']['select-taxonomy']['options'] = self::get_taxonomies();

		$taxomomy = PadmaBlockAPI::get_setting($block, 'select-taxonomy');

		$terms = self::get_listing_terms($taxomomy);
		$label = self::get_taxonomy_label($taxomomy);
		$this->inputs['taxonomy-options']['terms']['label'] = $label;
		$this->inputs['taxonomy-options']['terms-child-of']['options'] = $terms;
		$this->inputs['taxonomy-options']['terms-exclude']['options'] = $terms;
		$this->inputs['taxonomy-options']['terms-include']['options'] = $terms;

	}

	function get_taxonomies($post_type='') {

		if (!empty($post_type)) {
			$post_type = array($post_type);
			$args=array(
			  'object_type' => $post_type 
			);
		} else {
			$args = '';
		}

		$output = 'objects';
		$operator = 'and';

		$taxonomy_options = array('&ndash; Nicht filtern &ndash;');

		$taxonomy_select_query=get_taxonomies($args,$output,$operator);

		if  ($taxonomy_select_query) {
		  foreach ($taxonomy_select_query as $taxonomy)
			$taxonomy_options[$taxonomy->name] = $taxonomy->label;
		} 

		return $taxonomy_options;

	}

	function get_listing_terms($taxonomy='category') {

		if ( !$taxonomy )
			$taxonomy = 'category';

		$taxonomy_label = $this->get_taxonomy_label($taxonomy);

		$terms_options = array('&ndash; Wähle '. $taxonomy_label .' &ndash;');

		$terms = get_terms( $taxonomy, 'orderby=id&hide_empty=0' );

		if ( !$terms )
			return;

		foreach ($terms as $term)
			$terms_options[$term->term_id] = $term->name;

		return $terms_options;

	}

	function get_taxonomy_label($taxonomy) {

		if ( !$taxonomy )
			$taxonomy = 'category';

		$args = array(
		  'name' => $taxonomy
		);
		$output = 'objects'; // or objects		
		$taxonomy_select_query=get_taxonomies($args,$output);; 

		if  ($taxonomy_select_query) {
		  foreach ($taxonomy_select_query as $taxonomy)
			return $taxonomy->label;
		} 

	}

	function get_authors() {

		$author_options = array();

		$authors = get_users(array(
			'orderby' => 'post_count',
			'order' => 'desc',
			'capability' => 'authors'
		));

		foreach ( $authors as $author )
			$author_options[$author->ID] = $author->display_name;

		return $author_options;

	}

	function get_pages() {

		$page_options = array('&ndash; Default &ndash;');

		$page_select_query = get_pages();

		foreach ($page_select_query as $page)
			$page_options[$page->ID] = $page->post_title;

		return $page_options;

	}

	function get_post_types() {

		$post_type_options = array('&ndash; Alle Beitragstypen &ndash;');

		$post_types = get_post_types(false, 'objects'); 

		foreach($post_types as $post_type_id => $post_type){

			//Make sure the post type is not an excluded post type.
			if(in_array($post_type_id, array('revision', 'nav_menu_item'))) 
				continue;

			$post_type_options[$post_type_id] = $post_type->labels->name;

		}

		return $post_type_options;

	}

}