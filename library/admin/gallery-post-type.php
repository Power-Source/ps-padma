<?php
/**
 * Gallery Custom Post Type & Taxonomies
 * Registriert den padma_gallery CPT sowie gallery_categories und gallery_tags
 *
 * @package Padma
 * @subpackage Gallery
 */

/* CPT und Taxonomien registrieren */
add_action('init', 'padma_gallery_register_post_type');

function padma_gallery_register_post_type() {
	
    $args = array(
        'public' => true,
        'publicly_queryable' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'labels' => array(
            'name' => _x('Alben', 'padma'),
            'singular_name' => _x('Album', 'padma'),
            'add_new' => _x('Neues Album hinzufügen', 'padma'),
            'add_new_item' => sprintf( __( 'Neues %s hinzufügen', 'padma' ), __( 'Album', 'padma' ) ),
            'edit_item' => sprintf( __( '%s bearbeiten', 'padma' ), __( 'Album', 'padma' ) ),
            'new_item' => sprintf( __( 'Neues %s', 'padma' ), __( 'Album', 'padma' ) ),
            'all_items' => sprintf( __( 'Alle %s', 'padma' ), __( 'Alben', 'padma' ) ),
            'view_item' => sprintf( __( '%s ansehen', 'padma' ), __( 'Album', 'padma' ) ),
            'search_items' => sprintf( __( '%s durchsuchen', 'padma' ), __( 'Alben', 'padma' ) ),
            'not_found' =>  sprintf( __( 'Keine %s gefunden', 'padma' ), __( 'Alben', 'padma' ) ),
            'not_found_in_trash' => sprintf( __( 'Keine %s im Papierkorb gefunden', 'padma' ), __( 'Alben', 'padma' ) ),
            'parent_item_colon' => '',
            'menu_name' => __( 'Galerie', 'padma' )
        ),
        'supports' => array(
            'title',
            'thumbnail'
        ),
        'has_archive' => true,
        'taxonomies' => array('gallery_categories', 'gallery_tags'),
        'menu_position' => 20,
        'rewrite' => array('slug' => 'albums'),
        'menu_icon' => 'dashicons-format-gallery'
    );

    register_post_type('padma_gallery', $args);

    /* Album Categories Taxonomie */
    $labels = array(
        'name'                       => _x('Album-Kategorien', 'padma'),
        'singular_name'              => _x('Album-Kategorie', 'padma'),
        'search_items'               => __('Album-Kategorien durchsuchen', 'padma'),
        'popular_items'              => __('Beliebte Album-Kategorien', 'padma'),
        'all_items'                  => __('Alle Album-Kategorien', 'padma'),
        'parent_item'                => __('Übergeordnete Album-Kategorie', 'padma'),
        'edit_item'                  => __('Album-Kategorie bearbeiten', 'padma'),
        'update_item'                => __('Album-Kategorie aktualisieren', 'padma'),
        'add_new_item'               => _x('Neue Album-Kategorie hinzufügen', 'padma'),
        'new_item_name'              => __('Neue Album-Kategorie', 'padma'),
        'separate_items_with_commas' => __('Album-Kategorien mit Kommas trennen', 'padma'),
        'add_or_remove_items'        => __('Album-Kategorien hinzufügen oder entfernen', 'padma'),
        'choose_from_most_used'      => __('Aus den am häufigsten verwendeten Album-Kategorien wählen', 'padma')
    );
    $args = array(
        'labels'                     => $labels,
        'public'                     => true,
        'hierarchical'               => true,
        'show_ui'                    => true,
        'show_in_nav_menus'          => true,
        'query_var'                  => true,
        'rewrite' 					 => array('slug' => 'album-categories')
    );

    register_taxonomy( 'gallery_categories', 'padma_gallery', $args );

    /* Album Tags Taxonomie */
    $labels = array(
        'name'                       => _x( 'Album-Schlagwörter', 'padma' ),
        'singular_name'              => _x( 'Album-Schlagwort', 'padma' ),
        'search_items'               => __( 'Album-Schlagwörter durchsuchen', 'padma' ),
        'popular_items'              => __( 'Beliebte Album-Schlagwörter', 'padma' ),
        'all_items'                  => __( 'Alle Album-Schlagwörter', 'padma' ),
        'parent_item'                => __( 'Übergeordnetes Album-Schlagwort', 'padma' ),
        'edit_item'                  => __( 'Album-Schlagwort bearbeiten', 'padma' ),
        'update_item'                => __( 'Album-Schlagwort aktualisieren', 'padma' ),
        'add_new_item'               => _x( 'Neues Album-Schlagwort hinzufügen', 'padma' ),
        'new_item_name'              => __( 'Neues Album-Schlagwort', 'padma' ),
        'separate_items_with_commas' => __( 'Album-Schlagwörter mit Kommas trennen', 'padma' ),
        'add_or_remove_items'        => __( 'Album-Schlagwörter hinzufügen oder entfernen', 'padma' ),
        'choose_from_most_used'      => __( 'Aus den am häufigsten verwendeten Album-Schlagwörtern wählen', 'padma' )
    );
    $args = array(
        'labels'                     => $labels,
        'public'                     => true,
        'hierarchical'               => false,
        'show_ui'                    => true,
        'show_in_nav_menus'          => true,
        'query_var'                  => true,
        'rewrite' 					 => array('slug' => 'album-tags')
    );

    register_taxonomy( 'gallery_tags', 'padma_gallery', $args );

    /* Rewrite-Regeln einmalig aktualisieren, damit Album-URLs nicht 404 laufen */
    if ( get_option('padma_gallery_rewrite_flushed') !== '1' ) {
        flush_rewrite_rules(false);
        update_option('padma_gallery_rewrite_flushed', '1');
    }

}


/* Setze Default-Kategorie falls keine vorhanden */
add_action('publish_padma_gallery', 'padma_gallery_set_default_category');

function padma_gallery_set_default_category($padma_gallery_id) {

	if(!has_term('', 'gallery_categories', $padma_gallery_id)){
		wp_set_object_terms($padma_gallery_id, array('Unkategorisiert'), 'gallery_categories');
	}

}


/* Custom Columns für Gallery Admin-Liste */
add_filter( 'manage_edit-padma_gallery_columns', 'padma_gallery_admin_columns' );

function padma_gallery_admin_columns($columns) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Titel', 'padma' ),
		'images' => __( 'Bilder', 'padma' ),
		'gallery-categories' => __( 'Album-Kategorien', 'padma' ),
		'gallery-tags' => __( 'Album-Schlagwörter', 'padma' ),
		'date' => __( 'Datum', 'padma' )
	);

    return $columns;

}


/* Column-Inhalt ausgeben */
add_action( 'manage_padma_gallery_posts_custom_column', 'padma_gallery_admin_columns_content' );

function padma_gallery_admin_columns_content($column) {

	global $post;

    switch($column) {

    	case 'images' :

    		$album_imgs = get_post_meta( $post->ID, 'padma_gallery_image', true );

			if ( !empty($album_imgs) ) {

				$album_img = array_slice($album_imgs, 0, 4);

			    foreach ( $album_img as $i => $album_img_id ) {

			    	$get_album_img = get_post( $album_img_id );
			    	$album_img_src = wp_get_attachment_image_src( $album_img_id, 'full' );
			    	
			    	if ( $album_img_src && function_exists('padma_resize_image') ) {
			    		$album_img_src = padma_resize_image($album_img_src[0], 40, 40, true);
			    	} elseif ( $album_img_src ) {
			    		$album_img_src = $album_img_src[0];
			    	}

			    	echo '<div class="thumbnail-wrap"><div class="thumbnail"><img src="' . $album_img_src . '" /></div></div>';

			    }

		    $nbr_images = count($album_imgs) . ' Bilder';

		} else {

			$album_img = array();
			$nbr_images = 'kein Bild';

	}

	for ($i = 1; $i <= 4 - count($album_img); $i++) {
		$placeholder_url = padma_url() . '/library/blocks/gallery/admin/images/no-image.png';
	    echo '<div class="thumbnail-wrap"><div class="thumbnail"><img src="' . $placeholder_url . '" ></div></div>';
	}

	echo '<a class="nbr-images" href="' . admin_url('post.php?post=' . $post->ID . '&action=edit') . '">' . $nbr_images . '</span>';
        case 'gallery-tags':
        	$taxonomy_type = 'gallery_tags';
        	$empty         = 'Kein Album-Schlagwort';
        	echo padma_gallery_taxonomy_column_output($taxonomy_type, $empty);
        break;

    }
}


/* Hilfsfunktion für Taxonomy-Column-Ausgabe */
function padma_gallery_taxonomy_column_output($taxonomy_type, $empty) {

	global $post;
	global $typenow;

	$terms = get_the_terms($post->ID, $taxonomy_type);

	if ( $terms ) {
		$out = array();

		foreach ( $terms as $term ) {
			$out[] = '<a href="' . admin_url('edit.php?post_type=' . $typenow . '&' . $taxonomy_type .'=' . $term->slug) . '">' . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy_type, 'display')) . '</a>';
		}

		$return = join( ', ', $out );

	} else {
		$return = $empty;
	}

	return $return;
}
