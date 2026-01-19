<?php

class PadmaBreadcrumbsBlock extends PadmaBlockAPI {


	public $id;
	public $name;
	public $fixed_height;
	public $description;
	public $options_class;
	public $categories;
	// To allow inline editor
	public $inline_editable;


	function __construct(){

		$this->id = 'breadcrumbs';
		$this->name = __('Breadcrumbs','padma');
		$this->fixed_height = true;
		$this->description = __('Breadcrumbs helfen bei der Navigation Deiner Webseite, indem sie eine visuelle Hierarchie dessen anzeigen, wo sich der Besucher befindet.<br /><strong>Beispiel:</strong> Startseite &raquo; Blog &raquo; Beispiel Blog Beitrag','padma');	
		$this->options_class = 'PadmaBreadcrumbsBlockOptions';
		$this->categories 	= array('core','navigation');		
		$this->inline_editable = array('block-title', 'block-subtitle', 'prefix-text', 'separator');
	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'text',
			'name' => __('Text','padma'),
			'selector' => 'p'
		));

		$this->register_block_element(array(
			'id' => 'hyperlinks',
			'name' => __('Hyperlinks','padma'),
			'selector' => 'p a'
		));

		$this->register_block_element(array(
			'id' => 'separators',
			'name' => __('Trennzeichen','padma'),
			'selector' => 'span.sep'
		));

	}


	function content($block) {

		/* If Yoast's breadcrumbs are activated then use them instead */
		if ( function_exists('yoast_breadcrumb') ) {

			$yoast_breadcrumb = yoast_breadcrumb( '<p class="breadcrumbs yoastbreadcrumb">', '</p>' );

			if ( $yoast_breadcrumb ) {

				if ( is_string($yoast_breadcrumb) ) {
					echo $yoast_breadcrumb;
				}

				return;

			}

		}

		wp_reset_query();

		/* Set up variables */
			global $post;

			$breadcrumbs = array();
			$breadcrumbs[home_url()] = __('Home', 'padma');

		/* Handle blogs that aren't set to the homepage */
			if ( get_option('show_on_front') == 'page' && get_option('page_for_posts') !== get_option('page_on_front') ) {

				/* If the blog is set to a page rather than homepage, then don't show that fragment if it's a 404, search, or non-post singular */
				if ( !is_404() && !is_search() && !(is_singular() && get_post_type() != 'post') )
					$breadcrumbs[get_page_link(get_option('page_for_posts'))] = get_the_title(get_option('page_for_posts'));

			}

		/* Single Posts */
			if ( is_single() && get_post_type() == 'post' ) {

				$breadcrumbs[] = get_the_category_list(', ');
				$breadcrumbs[] = get_the_title();

			}

		/* Pages/Custom Post Type */
			else if ( is_singular() && !is_home() && !is_front_page() ) {

				$current_page = array($post);				

				/* Get the parent pages of the current page if they exist */
				if ( isset($current_page[0]->post_parent) )
					while ( $current_page[0]->post_parent )
						array_unshift($current_page, get_post($current_page[0]->post_parent));

				/* Add returned pages to breadcrumbs */
				foreach ( $current_page as $page )
					$breadcrumbs[get_page_link($page->ID)] = $page->post_title;

		/* Categories */	 			
			} else if ( is_category() ) {

				$breadcrumbs[] = single_cat_title('', false);

			}

		/* Searches */
			else if ( is_search() ) {

				$breadcrumbs[] = __('Suchergebnisse für:', 'padma') . ' ' . get_search_query();

			}

		/* Author Archives */
			else if ( is_author() ) {

				$author = get_queried_object();

				$breadcrumbs[] = __('Autor Archiv:', 'padma') . ' ' . $author->display_name;

			}

		/* Tag Archives */
			else if ( is_tag() ) {

				$breadcrumbs[] = __('Tag Archiv:', 'padma') . ' ' . single_tag_title('', false);

			}

		/* Date Archives */
			else if ( is_date() ) {

				$breadcrumbs[] = __('Archiv:', 'padma') . ' ' . get_the_time('F Y'); 

			}

		/* 404's */
			else if ( is_404() ) {

				$breadcrumbs[] = __('Hoppla! Seite nicht gefunden...', 'padma');

			}

		/* Display the breadcrumbs */
			echo '<p class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';

				if ( parent::get_setting($block, 'show-prefix', false) )
					echo '<span class="breadcrumbs-prefix prefix-text">' . parent::get_setting($block, 'prefix-text', __('Du bist hier:', 'padma')) . '</span>&ensp;';

				$breadcrumbs = apply_filters('padma_breadcrumbs', $breadcrumbs);

				$breadcrumbs_length = count($breadcrumbs);
				$breadcrumbs_loop_counter = 0;

				foreach ( $breadcrumbs as $breadcrumb_url => $breadcrumb ) {

					/* Do not show separator before first item */
						if ( $breadcrumbs_loop_counter != 0 )
							echo ' <span class="sep separator">' . parent::get_setting($block, 'separator', '&raquo;') . '</span> ';

					echo '<span typeof="v:Breadcrumb" class="breadcrumb">';

						if ( !is_numeric($breadcrumb_url) && ($breadcrumbs_loop_counter != $breadcrumbs_length - 1) ) {

							echo '<a href="' . $breadcrumb_url . '" rel="v:url" property="v:title">' . $breadcrumb . '</a></span>';

						} else {

							echo $breadcrumb;

						}

					echo '</span>';

					$breadcrumbs_loop_counter++;

				}

			echo '</p>';

	}


}


class PadmaBreadcrumbsBlockOptions extends PadmaBlockOptionsAPI {

	public $tabs;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'General'
		);

		$this->inputs = array(
			'general' => array(
				'show-prefix' => array(
					'name' => 'show-prefix',
					'label' => __('"Du bist hier:" Präfix anzeigen','padma'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du möchtest, dass die Breadcrumbs "Du bist hier:" oder etwas Ähnliches vor der Breadcrumb-Spur anzeigen, dann aktiviere diese Option.','padma'),
					'default' => false
				),

				'prefix-text' => array(
					'name' => 'prefix-text',
					'label' => __('Präfixtext','padma'),
					'type' => 'text',
					'tooltip' => __('Wenn das vorherige Kontrollkästchen aktiviert ist, kannst Du den Präfixtext anpassen.','padma'),
					'default' => __('Du bist hier:','padma')
				),

				'separator' => array(
					'name' => 'separator',
					'label' => __('Trennzeichen','padma'),
					'type' => 'text',
					'tooltip' => __('Dies wird zwischen jedem Breadcrumb angezeigt. Zum Beispiel, wenn das Trennzeichen "&raquo;" ist, wird es als Home &raquo; Seitenname angezeigt.','padma'),
					'default' => '&raquo;'
				)
			)
		);

	}
}