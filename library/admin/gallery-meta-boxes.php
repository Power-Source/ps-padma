<?php
/**
 * Gallery Meta Boxes
 * Verwaltet die Admin-Metaboxen für Gallery-Alben
 *
 * @package Padma
 * @subpackage Gallery
 */

/* Metaboxen registrieren */
add_action('init', 'padma_gallery_register_meta_boxes');

function padma_gallery_register_meta_boxes() {

	global $post;

	$gallery_meta_boxes = array();

	$gallery_meta_boxes[] = array(
		'id' => 'display_padma_gallery',
		'title' => __('Album', 'padma'),
		'pages' => array('padma_gallery'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => '',
				'desc' => '',
				'id' => 'padma_gallery_count',
				'type' => 'thumbnail-count',
				'std' => 'no image'
			),
			array(
				'name' => '',
				'desc' => __('Add images which will be used for your album.', 'padma'),
				'id' => 'padma_gallery_image',
				'type' => 'gallery',
				'std' => ''
			)
		)
	);

	$gallery_meta_boxes[] = array(
		'id' => 'display_padma_gallery_options',
		'title' => __('Album Block Options', 'padma'),
		'pages' => array('padma_gallery'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => __('Album Description', 'padma'),
				'desc' => __('Enter your album description.', 'padma'),
				'id' => 'padma_gallery_description',
				'type' => 'wysiwyg',
				'std' => ''
			),
			array(
				'name' => __('Album Caption', 'padma'),
				'desc' => __('Enter your album caption which will be used if you choose to display the albums as thumbnails.', 'padma'),
				'id' => 'padma_gallery_caption',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => __('Custom Readon Link', 'padma'),
				'desc' => __('Enter your custom Readon Link here. Leave it empty if you want to keep the WordPress Readon Link default behaviour.', 'padma'),
				'id' => 'padma_gallery_readon_link',
				'type' => 'text',
				'std' => ''
			)
		)
	);

	foreach ( $gallery_meta_boxes as $gallery_meta_box ) {
		$my_box = new PadmaGalleryMetaBox($gallery_meta_box);
	}

}


/**
 * Metabox-Klasse für Gallery-Alben
 */
class PadmaGalleryMetaBox {

	protected $_gallery_meta_box;

	function __construct($gallery_meta_box) {

		if ( !is_admin() )
			return;

		$this->_gallery_meta_box = $gallery_meta_box;

		add_action('admin_menu', array(&$this, 'add'));
		add_action('save_post', array(&$this, 'save'));
		add_filter( 'attachment_fields_to_edit', array(&$this, 'attachment_fields_edit'), 10, 2);
		add_filter( 'attachment_fields_to_save', array(&$this, 'attachment_fields_save'), 10, 2);

	}

	function add() {

		$this->_gallery_meta_box['context'] = empty($this->_gallery_meta_box['context']) ? 'normal' : $this->_gallery_meta_box['context'];
		$this->_gallery_meta_box['priority'] = empty($this->_gallery_meta_box['priority']) ? 'high' : $this->_gallery_meta_box['priority'];

		foreach ( $this->_gallery_meta_box['pages'] as $page ) {
			add_meta_box($this->_gallery_meta_box['id'], $this->_gallery_meta_box['title'], array(&$this, 'show'), $page, $this->_gallery_meta_box['context'], $this->_gallery_meta_box['priority']);
		}

	}

	function show() {

		global $post;

		$output = '<input type="hidden" name="meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';
		$output .= '<div class="padma-gallery-options">';

		foreach ( $this->_gallery_meta_box['fields'] as $field ) {

			$meta = get_post_meta($post->ID, $field['id'], true);
			$output .= $field['name'] ? '<label for="' . $field['id'] . '">' . $field['name'] . '</label>' : '';

			switch ($field['type']) {

				case 'text':
					$value = $meta ? $meta : $field['std'];
					$output .= '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . esc_attr($value) . '" size="30" />';
					$output .= '<span class="pur-field-description">' . $field['desc'] . '</span>';
				break;

				case 'thumbnail-count':
					$value = $meta != 0 ? $meta : $field['std'];
					$output .= '<input type="hidden" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . esc_attr($meta) . '" size="30" />';
					$output .= '<span class="pur-thumbnail-count"><strong>' . __('Images:', 'padma') . ' </strong><span>' . esc_html($value) . '</span></span>';
				break;

				case 'wysiwyg':
					$meta = html_entity_decode($meta, ENT_QUOTES, 'UTF-8');
					$value = $meta ? $meta : $field['std'];
			        $settings = array(
			        		'wpautop' => false,
			        		'media_buttons' => false,
			        	);
			       	ob_start();
			       		wp_editor( $value, $field['id'], $settings );
			       		$output .= ob_get_contents();
			       	ob_end_clean();
			       	$output .= '<span class="pur-field-description">' . $field['desc'] . '</span>';
				break;

				case 'gallery':
					$thumbnail_count = get_post_meta($post->ID, 'padma_gallery_count', true);
					$thumbnail_option = array(
					    'width' => get_option('thumbnail_size_w'),
					    'height' => get_option('thumbnail_size_h'),
					    'crop' => get_option('thumbnail_crop'),
					);

					$output .= '<input class="pur-upload-image button button-primary" type="button" value="' . esc_attr__('Add image', 'padma') . '" /><span class="drag-notice">' . __('Drag and drop to re-order', 'padma') . '</span>';
					$output .= '<div data-thumb-w="' . $thumbnail_option['width'] . '" data-thumb-h="' . $thumbnail_option['height'] . '" class="pur-thumbnails ui-sortable">';
					$output .= '<input type="hidden" name="' . $field['id'] . '" value="" />';

					if ( $meta ) {
						foreach ( $meta as $attachment => $id ) {
							if ( $id ) {
								$img = wp_get_attachment_image_src( $id, 'full' );
								if ( $img ) {
									$img_src = $img[0];
									$thumb_crop = $thumbnail_option['crop'] == 1 ? true : false;

									if ( function_exists('padma_resize_image') ) {
										$img_src = padma_resize_image($img_src, $thumbnail_option['width'], $thumbnail_option['height'], $thumb_crop);
									}

									$output .= '<div class="pur-thumbnail" style="width: ' . $thumbnail_option['width'] . 'px; height: ' . $thumbnail_option['height'] . 'px">';
										$output .= '<input class="pur-image-value" type="hidden" name="' . $field['id'] . '[]" value="' . esc_attr($id) .'" />';
										$output .= '<div class="pur-image-wrap"><img src="' . esc_url($img_src) . '" /></div>';
										$output .= '<div class="pur-thumbnail-toolbar">';
											$output .= '<ul>
													<li><a href="#" class="pur-drag pur-btn">' . __('Drag', 'padma') . '</a></li>
													<li><a href="#" class="pur-edit pur-btn">' . __('Edit', 'padma') . '</a></li>
													<li><a href="#" class="pur-remove pur-btn">' . __('Remove', 'padma') . '</a></li>
												</ul>';
										$output .= '</div>';
									$output .= '</div>';
								}
							}
						}
					}

					$output .= '<div class="pur-no-thumbnail">
									<p>' . __('You do not have any image selected!', 'padma') . '</p>
									<p><a href="#" class="pur-upload-image browser button button-hero">' . __('Add image', 'padma') . '</a></p>
								</div>';
					$output .= '</div>';
					$output .= '<span class="pur-field-description">' . $field['desc'] . '</span>';
				break;

			}

		}

		$output .= '</div>';
		echo $output;

	}

	function save($post_id) {

		if ( !isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__)) )
			return $post_id;

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;

		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can('edit_page', $post_id) )
				return $post_id;
		} elseif ( !current_user_can('edit_post', $post_id) ) {
			return $post_id;
		}

		foreach ( $this->_gallery_meta_box['fields'] as $field ) {

			$name = $field['id'];
			$old = get_post_meta($post_id, $name, true);
			$new = isset($_POST[$field['id']]) ? $_POST[$field['id']] : '';

			if ( $field['type'] == 'wysiwyg' )
				$new = wpautop($new);

			if ( $new && $new != $old )
				update_post_meta($post_id, $name, $new);
			elseif ( '' == $new && $old )
				delete_post_meta($post_id, $name, $old);

		}

	}

	function attachment_fields_edit($form_fields, $post) {
	    $form_fields['pur-custom-link']['label'] = __( 'Custom Link', 'padma' );
	    $form_fields['pur-custom-link']['value'] = get_post_meta($post->ID, '_padma_custom_link', true);
	    $form_fields['pur-custom-link']['helps'] = __( 'Added by Padma Gallery Block', 'padma' );
	    return $form_fields;
	}

	function attachment_fields_save($post, $attachment) {
	    if ( isset($attachment['pur-custom-link']) )
	        update_post_meta($post['ID'], '_padma_custom_link', $attachment['pur-custom-link']);
	    return $post;
	}

}
