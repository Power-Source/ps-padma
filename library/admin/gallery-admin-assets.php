<?php
/**
 * Gallery Admin Assets
 * Lädt CSS/JS für Gallery-Admin-Bereich
 *
 * @package Padma
 * @subpackage Gallery
 */

add_action('admin_enqueue_scripts', 'padma_gallery_admin_assets');

function padma_gallery_admin_assets($hook) {

	global $post;

	$gallery_block_url = padma_url() . '/library/blocks/gallery';

	/* Gallery Admin CSS immer laden */
	wp_enqueue_style('padma-gallery-wp-admin-css', $gallery_block_url . '/admin/css/wp-admin.css', false, PADMA_VERSION );

	/* Auf Gallery-Edit-Seiten zusätzliche Assets */
	if ( isset($post->post_type) && $post->post_type == 'padma_gallery' ) {
		wp_enqueue_style('padma-gallery-wp-gallery-css', $gallery_block_url . '/admin/css/wp-gallery.css', false, PADMA_VERSION );
		wp_register_script('padma-gallery-wp-admin-js', $gallery_block_url . '/admin/js/wp-admin.js', array('jquery'), PADMA_VERSION);
		wp_enqueue_script('padma-gallery-wp-admin-js');
	}

	/* Uploader für Media-Editor */
	if ( isset($_GET['padma_media_editor']) && $_GET['padma_media_editor'] == true ) {
		wp_enqueue_style('pur-wp-uploader-css', $gallery_block_url . '/admin/css/wp-uploader.css', false, PADMA_VERSION );
		wp_enqueue_script('pur-wp-uploader-js', $gallery_block_url . '/admin/js/wp-updoader.js', array('jquery','media-upload','thickbox'), PADMA_VERSION);
	}

	/* Attachment Custom Field CSS */
	if ( isset($post->post_type) && $post->post_type == 'attachment' )
		wp_enqueue_style('pur-wp-attachment-css', $gallery_block_url . '/admin/css/wp-attachment.css', false, PADMA_VERSION );

}


/* Admin Head Ausgabe */
add_action('admin_head', 'padma_gallery_admin_head');

function padma_gallery_admin_head() {

	$options = wp_parse_args( wp_get_referer() );

	$output = '<script type="text/javascript">';
		$output .= 'padma_admin_url = "' .  admin_url() . '";';
		if ( isset($options['padma_action']) && $options['padma_action'] === 'done_editing' )
			$output .= 'self.parent.tb_remove();';
	$output .= '</script>';

	if ( isset($options['padma_action']) && $options['padma_action'] === 'done_editing')
		$output .= '<style type="text/css">body { display:none; }</style>';

	echo $output;

}
