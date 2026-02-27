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
            'name' => _x('Albums', 'padma'),
            'singular_name' => _x('Album', 'padma'),
            'add_new' => _x('Add New Album', 'padma'),
            'add_new_item' => sprintf( __( 'Add New %s', 'padma' ), __( 'Album', 'padma' ) ),
            'edit_item' => sprintf( __( 'Edit %s', 'padma' ), __( 'Album', 'padma' ) ),
            'new_item' => sprintf( __( 'New %s', 'padma' ), __( 'Album', 'padma' ) ),
            'all_items' => sprintf( __( 'All %s', 'padma' ), __( 'Albums', 'padma' ) ),
            'view_item' => sprintf( __( 'View %s', 'padma' ), __( 'Album', 'padma' ) ),
            'search_items' => sprintf( __( 'Search %s', 'padma' ), __( 'Albums', 'padma' ) ),
            'not_found' =>  sprintf( __( 'No %s Found', 'padma' ), __( 'Album', 'padma' ) ),
            'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'padma' ), __( 'Album', 'padma' ) ),
            'parent_item_colon' => '',
            'menu_name' => __( 'Gallery', 'padma' )
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
        'name'                       => _x('Album Categories', 'padma'),
        'singular_name'              => _x('Album Category', 'padma'),
        'search_items'               => __('Search Album Categories', 'padma'),
        'popular_items'              => __('Popular Album Categories', 'padma'),
        'all_items'                  => __('All Album Categories', 'padma'),
        'parent_item'                => __('Parent Album Category', 'padma'),
        'edit_item'                  => __('Edit Album Category', 'padma'),
        'update_item'                => __('Update Album Category', 'padma'),
        'add_new_item'               => _x('Add New Album Category', 'padma'),
        'new_item_name'              => __('New Album Category', 'padma'),
        'separate_items_with_commas' => __('Separate Album Categories with commas', 'padma'),
        'add_or_remove_items'        => __('Add or remove Album Categories', 'padma'),
        'choose_from_most_used'      => __('Choose from most used Album Categories', 'padma')
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
        'name'                       => _x( 'Album Tags', 'padma' ),
        'singular_name'              => _x( 'Album Tag', 'padma' ),
        'search_items'               => __( 'Search Album Tags', 'padma' ),
        'popular_items'              => __( 'Popular Album Tags', 'padma' ),
        'all_items'                  => __( 'All Album Tags', 'padma' ),
        'parent_item'                => __( 'Parent Album Tag', 'padma' ),
        'edit_item'                  => __( 'Edit Album Tag', 'padma' ),
        'update_item'                => __( 'Update Album Tag', 'padma' ),
        'add_new_item'               => _x( 'Add New Album Tag', 'padma' ),
        'new_item_name'              => __( 'New Album Tag', 'padma' ),
        'separate_items_with_commas' => __( 'Separate Album Tags with commas', 'padma' ),
        'add_or_remove_items'        => __( 'Add or remove Album Tags', 'padma' ),
        'choose_from_most_used'      => __( 'Choose from most used Album Tags', 'padma' )
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
		wp_set_object_terms($padma_gallery_id, array('Uncategorized'), 'gallery_categories');
	}

}


/* Custom Columns für Gallery Admin-Liste */
add_filter( 'manage_edit-padma_gallery_columns', 'padma_gallery_admin_columns' );

function padma_gallery_admin_columns($columns) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'images' => __( 'Images' ),
		'gallery-categories' => __( 'Album Categories' ),
		'gallery-tags' => __( 'Album Tags' ),
		'date' => __( 'Date' )
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

			    $nbr_images = count($album_imgs) . ' images';

			} else {

				$album_img = array();
				$nbr_images = 'no image';

			}

			for ($i = 1; $i <= 4 - count($album_img); $i++) {
				$placeholder_url = padma_url() . '/library/blocks/gallery/admin/images/no-image.png';
			    echo '<div class="thumbnail-wrap"><div class="thumbnail"><img src="' . $placeholder_url . '" ></div></div>';
			}

			echo '<a class="nbr-images" href="' . admin_url('post.php?post=' . $post->ID . '&action=edit') . '">' . $nbr_images . '</span>';

        break;

        case 'gallery-categories' :
        	$taxonomy_type = 'gallery_categories';
        	$empty         = 'No Album Category';
			echo padma_gallery_taxonomy_column_output($taxonomy_type, $empty);
        break;

        case 'gallery-tags':
        	$taxonomy_type = 'gallery_tags';
        	$empty         = 'No Album Tag';
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
