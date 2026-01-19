<?php

class PadmaContentBlock extends PadmaBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'content';	
		$this->name = __('Content','padma');
		$this->options_class = 'PadmaContentBlockOptions';
		$this->description 	= __('Main content area to show the current page\'s content or the latest posts.  This is considered the "Loop" in other themes.','padma');
		$this->categories = array('core','content');

	}		


	function init() {

		/* Load dependencies */
		require_once PADMA_LIBRARY_DIR . '/blocks/content/content-display.php';

		/* Set up the comments template */
		add_filter('comments_template', array(__CLASS__, 'add_blank_comments_template'), 5);

		/* Set up editor style */
		add_filter('mce_css', array(__CLASS__, 'add_editor_style'));

		/* Add .comment class to all pingbacks */
		add_filter('comment_class', array(__CLASS__, 'add_comment_class_to_all_types'));

	}


	public static function add_blank_comments_template() {

		return PADMA_LIBRARY_DIR . '/blocks/content/comments-template.php';

	}


	public static function add_comment_class_to_all_types($classes) {

		if ( !is_array($classes) )
			$classes = implode(' ', trim($classes));

		$classes[] = 'comment';

		return array_filter(array_unique($classes));

	}


	public static function add_editor_style($css) {

		if ( PadmaOption::get('disable-editor-style', false, false) )
			return $css;

		if ( !current_theme_supports('editor-style') )
			return $css;

		if ( !current_theme_supports('padma-design-editor') )
			return $css;

		PadmaCompiler::register_file(array(
			'name' => 'editor-style',
			'format' => 'css',
			'fragments' => array(
				'padma_content_block_editor_style'
			),
			'dependencies' => array(PADMA_LIBRARY_DIR . '/blocks/content/editor-style.php'),
			'enqueue' => false
		));

		return $css . ',' . PadmaCompiler::get_url('editor-style');

	}

	public static function dynamic_css($block_id, $block) {

		$css = '';

		$featured_image_as_background = parent::get_setting( $block, 'featured-image-as-background', false);
		$overlay = parent::get_setting($block, 'featured-image-as-background-overlay');
		$overlay = parent::get_setting($block, 'featured-image-as-background-overlay');
		$overlay_hover = parent::get_setting($block, 'featured-image-as-background-overlay-hover', 'transparent');

		if( !empty( $overlay ) && $featured_image_as_background ){

			$css .= '#block-' . $block_id . ' article{';
			$css .= 'position: relative;';
			$css .= '}';

			$css .= '#block-' . $block_id . ' *{';
			$css .= 'position: relative;';
			$css .= 'z-index: 2;';	
			$css .= '}';

			$css .= '#block-' . $block_id . ' article:before{';
			$css .= 'content: " ";';
			$css .= 'background-color: ' . $overlay . ';';	
			$css .= 'position: absolute;';
			$css .= 'top: 0;';
			$css .= 'bottom: 0;';
			$css .= 'left: 0;';
			$css .= 'right: 0;';
			$css .= 'z-index: 1;';
			$css .= '}';
			$css .= '#block-' . $block_id . ' article:hover:before{';
			$css .= 'background-color: ' . $overlay_hover . ';';
			$css .= '}';
		}

		if ( parent::get_setting($block, 'enable-column-layout') ) {

			$gutter_width = parent::get_setting($block, 'post-gutter-width', '20');

			if ( PadmaResponsiveGrid::is_enabled() ) {
				$css .= '@media only screen and (min-width: ' . PadmaBlocksData::get_block_width($block) . 'px) {';
			}

				$css .= '#block-' . $block_id . ' .loop .entry-row .hentry {';

					$css .= 'margin-left: ' . self::width_as_percentage($gutter_width, $block) . '%;';
					$css .= 'width: ' . self::width_as_percentage(self::get_column_width($block), $block) . '%;';

				$css .= '}';


			if ( PadmaResponsiveGrid::is_enabled() ) {
				$css .= '}';
			}

		}

		return $css . "\n";


	}

	static function get_column_width($block) {

		$block_width = PadmaBlocksData::get_block_width($block);

		$columns = parent::get_setting($block, 'posts-per-row', '2');
		$gutter_width = parent::get_setting($block, 'post-gutter-width', '20');

		$total_gutter = $gutter_width * ($columns-1);

		$columns_width = (($block_width - $total_gutter) / $columns);

		return $columns_width; 
	}

	/* To make the layout responsive
	 * Works out a percentage value equivalent of the px value 
	 * using common responsive formula: target_width / container_width * 100
	 */	
	static function width_as_percentage($target = '', $block) {
		$block_width = PadmaBlocksData::get_block_width($block);

		if ($block_width > 0 )
			return ($target / $block_width)*100;

		return false;
	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'article',
			'name' => __('Article','padma'),
			'selector' => 'article',			
		));

		/* Classic Editor */
			$this->register_block_element(array(
				'id' => 'entry-container-hentry',
				'name' => __('Entry Container','padma'),
				'selector' => '.hentry',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'transform', 'advanced', 'transition', 'outlines', 'filter')
			));

				$this->register_block_element(array(
					'id' => 'page-container',
					'name' => __('Page Entry Container','padma'),
					'parent' => 'entry-container-hentry',
					'selector' => '.type-page'
				));

				$this->register_block_element(array(
					'id' => 'entry-container',
					'name' => __('Post Entry Container','padma'),
					'parent' => 'entry-container-hentry',
					'selector' => '.type-post'
				));


			$this->register_block_element(array(
				'id' => 'entry-row',
				'name' => __('Entry Row','padma'),
				'selector' => '.entry-row'
			));

			$this->register_block_element(array(
				'id' => 'title',
				'name' => __('Title','padma'),
				'selector' => '.entry-title',
				'states' => array(
					'Hover' => '.entry-title:hover', 
					'Clicked' => '.entry-title:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'archive-title',
				'name' => __('Archive Title','padma'),
				'selector' => '.archive-title'
			));

			$this->register_block_element(array(
				'id' => 'entry-content',
				'name' => __('Body Text','padma'),
				'description' => __('All text including &lt;p&gt; elements','padma'),
				'selector' => 'div.entry-content, div.entry-content p'
			));

			$this->register_block_element(array(
				'id' => 'entry-content-hyperlinks',
				'name' => __('Body Hyperlinks','padma'),
				'selector' => 'div.entry-content a',				
				'states' => array(
					'Hover' => 'div.entry-content a:hover', 
					'Clicked' => 'div.entry-content a:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'entry-content-images',
				'name' => __('Images','padma'),
				'selector' => 'div.entry-content img',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'sizes', 'filter')
			));

			$this->register_block_element( array(
				'id'         => 'entry-content-image-captions',
				'name'       => __('Image Captions','padma'),
				'selector'   => 'div.entry-content .wp-caption',
				'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'animation' )
			) );

				$this->register_block_element( array(
					'id'       => 'entry-content-image-caption-image',
					'parent'   => 'entry-content-image-captions',
					'name'     => __('Images in Captions','padma'),
					'selector' => 'div.entry-content .wp-caption img',
					'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'filter' )
				) );

				$this->register_block_element( array(
					'id'         => 'entry-content-image-caption-text',
					'parent'     => 'entry-content-image-captions',
					'name'       => __('Caption Text','padma'),
					'selector'   => 'div.entry-content .wp-caption .wp-caption-text'
				) );

			$this->register_block_element(array(
				'id' => 'entry-meta',
				'name' => __('Meta','padma'),
				'selector' => 'div.entry-meta'
			));

				$this->register_block_element(array(
					'id' => 'entry-meta-above',
					'name' => __('Meta Above Content','padma'),
					'selector' => 'div.entry-meta-above',
					'parent' => 'entry-meta'
				));			

				$this->register_block_element(array(
					'id' => 'entry-meta-below',
					'name' => __('Meta Below Content','padma'),
					'selector' => 'footer.entry-utility-below',
					'parent' => 'entry-meta'
				));			

				$this->register_block_element(array(
					'id' => 'entry-meta-links',
					'name' => __('Meta Hyperlinks','padma'),
					'selector' => 'div.entry-meta a, footer.entry-meta a',
					'parent' => 'entry-meta',					
					'states' => array(
					'Hover' => 'div.entry-meta a:hover, footer.entry-meta a:hover', 
					'Clicked' => 'div.entry-meta a:active, footer.entry-meta a:active'
				)
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-author',
					'name' => __('Author Avatar Image','padma'),
					'selector' => '.avatar',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher',
					'name' => __('Publisher Logo container','padma'),
					'selector' => '.publisher-img',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-image-container',
					'name' => __('Publisher Logo image container','padma'),
					'selector' => '.publisher-img .logo',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-image-link',
					'name' => __('Publisher Logo link','padma'),
					'selector' => '.publisher-img .logo a',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-image-file',
					'name' => __('Publisher Logo image','padma'),
					'selector' => '.publisher-img .logo a img',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-meta',
					'name' => __('Publisher Logo meta data','padma'),
					'selector' => '.publisher-img meta',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-date',
					'name' => __('Post Entry Date','padma'),
					'parent' => 'entry-meta',
					'selector' => '.entry-date'
				));

			$this->register_block_element(array(
				'id' => 'heading',
				'name' => __('Heading','padma'),
				'selector' => 'div.entry-content h3, div.entry-content h2, div.entry-content h1'
			));

				$this->register_block_element(array(
					'id' => 'heading-h1',
					'parent' => 'heading',
					'name' => 'H1',
					'selector' => 'div.entry-content h1',
					'parent' => 'heading'
				));

				$this->register_block_element(array(
					'id' => 'heading-h2',
					'parent' => 'heading',
					'name' => 'H2',
					'selector' => 'div.entry-content h2'
				));

				$this->register_block_element(array(
					'id' => 'heading-h3',
					'parent' => 'heading',
					'name' => 'H3',
					'selector' => 'div.entry-content h3'
				));

			$this->register_block_element(array(
				'id' => 'sub-heading',
				'name' => __('Sub Heading','padma'),
				'selector' => 'div.entry-content h4, div.entry-content h5'
			));

				$this->register_block_element(array(
					'id' => 'sub-heading-h4',
					'parent' => 'sub-heading',
					'name' => 'H4',
					'selector' => 'div.entry-content h4'
				));

				$this->register_block_element(array(
					'id' => 'sub-heading-h5',
					'parent' => 'sub-heading',
					'name' => 'H5',
					'selector' => 'div.entry-content h5'
				));

				$this->register_block_element(array(
					'id' => 'content-ul-lists',
					'name' => __('Unordered Lists','padma'),
					'description' => '&lt;UL&gt;',
					'selector' => 'div.entry-content ul',
				));

				$this->register_block_element(array(
					'id' => 'content-ul-list-item',
					'name' => __('Unordered List Items','padma'),
					'description' => '&lt;LI&gt;',
					'selector' => 'div.entry-content ul li',					
				));

				$this->register_block_element(array(
					'id' => 'content-ol-lists',
					'name' => __('Ordered Lists','padma'),
					'description' => '&lt;OL&gt;',
					'selector' => 'div.entry-content ol',					
				));

				$this->register_block_element(array(
					'id' => 'content-list-item',
					'name' => __('Ordered List Items','padma'),
					'description' => '&lt;LI&gt;',
					'selector' => 'div.entry-content ol li',					
				));

			$this->register_block_element(array(
				'id' => 'post-thumbnail-contanier',
				'name' => __('Featured Image Container','padma'),
				'selector' => '.block-type-content a.post-thumbnail',				
			));

			$this->register_block_element(array(
				'id' => 'post-thumbnail',
				'name' => __('Featured Image','padma'),
				'selector' => '.block-type-content a.post-thumbnail img',				
			));

			$this->register_block_element(array(
				'id' => 'more-link',
				'name' => __('Continue Reading Button','padma'),
				'selector' => 'div.entry-content a.more-link',
				'states' => array(
					'Hover' => 'div.entry-content a.more-link:hover',
					'Clicked' => 'div.entry-content a.more-link:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'loop-navigation-link',
				'name' => __('Loop Navigation Button','padma'),
				'selector' => 'div.loop-navigation div.nav-previous a, div.loop-navigation div.nav-next a',
				'states' => array(
					'Hover' => 'div.loop-navigation div.nav-previous a:hover, div.loop-navigation div.nav-next a:hover',
					'Clicked' => 'div.loop-navigation div.nav-previous a:active, div.loop-navigation div.nav-next a:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'comments-wrapper',
				'name' => __('Comments','padma'),
				'selector' => 'div#comments'
			));

			$this->register_block_element(array(
				'id' => 'comments-area',
				'name' => __('Comments Area','padma'),
				'selector' => 'ol.commentlist',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow'),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comments-area-headings',
				'name' => __('Comments Area Headings','padma'),
				'selector' => 'div#comments h3',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-container',
				'name' => __('Comment Container','padma'),
				'selector' => 'li.comment',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation'),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comments-textarea',
				'name' => __('Add Comment Textarea','padma'),
				'selector' => '#comment',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-author',
				'name' => __('Comment Author','padma'),
				'selector' => 'li.comment .comment-author',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-meta',
				'name' => __('Comment Meta','padma'),
				'selector' => 'li.comment .comment-meta',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-meta-count',
				'name' => __('Comment Meta Count','padma'),
				'selector' => 'a.entry-comments',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-body',
				'name' => __('Comment Body','padma'),
				'selector' => 'li.comment .comment-body p',
				'properties' => array('fonts'),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-reply-link',
				'name' => __('Comment Reply Link','padma'),
				'selector' => 'a.comment-reply-link',
				'states' => array(
					'Hover' => 'a.comment-reply-link:hover',
					'Clicked' => 'a.comment-reply-link:active'
				),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-form-input-label',
				'name' => __('Comment Form Input Label','padma'),
				'selector' => 'div#respond label',
				'properties' => array('fonts'),
				'parent' => 'comments-wrapper'
			));

		/* End Classic Container */


		/*	Gutenberg */

			$this->register_block_element(array(
				'id' => 'gutenberg-audio-block',
				'name' => __('Gutenberg audio block','padma'),
				'selector' => '.wp-block-audio',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-video-block',
				'name' => __('Gutenberg video block','padma'),
				'selector' => '.wp-block-video',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-file-block',
				'name' => __('Gutenberg file block','padma'),
				'selector' => '.wp-block-file',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-image-block',
				'name' => __('Gutenberg image block','padma'),
				'selector' => '.wp-block-image',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-gallery-block',
				'name' => __('Gutenberg gallery block','padma'),
				'selector' => '.wp-block-gallery',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-gallery-block-item',
				'name' => __('Gutenberg gallery item','padma'),
				'selector' => '.wp-block-gallery-item',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-cover-block',
				'name' => __('Gutenberg cover block','padma'),
				'selector' => '.wp-block-cover',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-code-block',
				'name' => __('Gutenberg code block','padma'),
				'selector' => '.wp-block-code',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-preformatted-block',
				'name' => __('Gutenberg preformatted block','padma'),
				'selector' => '.wp-block-preformatted',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-pullquote-block',
				'name' => __('Gutenberg pullquote block','padma'),
				'selector' => '.wp-block-pullquote',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-table-block',
				'name' => __('Gutenberg table block','padma'),
				'selector' => '.wp-block-table',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-button-block',
				'name' => __('Gutenberg button block','padma'),
				'selector' => '.wp-block-button',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-columns-block',
				'name' => __('Gutenberg columns block','padma'),
				'selector' => '.wp-block-columns',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-media-text-block',
				'name' => __('Gutenberg media-text block','padma'),
				'selector' => '.wp-block-media-text',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-separator-block',
				'name' => __('Gutenberg separator block','padma'),
				'selector' => '.wp-block-separator',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-archives-block',
				'name' => __('Gutenberg archives block','padma'),
				'selector' => '.wp-block-archives',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-categories-block',
				'name' => __('Gutenberg categories block','padma'),
				'selector' => '.wp-block-categories',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-categories-block',
				'name' => __('Gutenberg categories block','padma'),
				'selector' => '.wp-block-categories .cat-item',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-latest-comments-block',
				'name' => __('Gutenberg latest-comments block','padma'),
				'selector' => '.wp-block-latest-comments',
			));			

			$this->register_block_element(array(
				'id' => 'gutenberg-categories-block',
				'name' => __('Gutenberg categories block','padma'),
				'selector' => '.wp-block-categories',
			));			

			$this->register_block_element(array(
				'id' => 'gutenberg-embed-block',
				'name' => __('Gutenberg embed block','padma'),
				'selector' => '.wp-block-embed',
			));			

		/*	End Gutenberg */


		/**
		 *
		 * Custom Fields
		 *
		 */
		$this->register_block_element(array(
			'id' => 'custom-fields',
			'name' => __('Custom Fields Container','padma'),
			'selector' => '.custom-fields',			
		));
		$this->register_block_element(array(
			'id' => 'custom-fields-group',
			'name' => __('Custom Fields Group','padma'),
			'selector' => '.custom-fields-group',
		));

			$this->register_block_element(array(
				'id' => 'custom-fields-div',
				'name' => __('Custom Fields Image','padma'),
				'selector' => '.custom-fields image',			
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-div',
				'name' => __('Custom Fields Div','padma'),
				'selector' => '.custom-fields div',			
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-p',
				'name' => __('Custom Fields text','padma'),
				'selector' => '.custom-fields p',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-a',
				'name' => __('Custom Fields Link','padma'),
				'selector' => '.custom-fields a',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h1',
				'name' => __('Custom Fields H1','padma'),
				'selector' => '.custom-fields h1',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h2',
				'name' => __('Custom Fields H2','padma'),
				'selector' => '.custom-fields h2',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h3',
				'name' => __('Custom Fields H3','padma'),
				'selector' => '.custom-fields h3',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h4',
				'name' => __('Custom Fields H4','padma'),
				'selector' => '.custom-fields h4',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h5',
				'name' => __('Custom Fields H5','padma'),
				'selector' => '.custom-fields h5',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h6',
				'name' => __('Custom Fields H6','padma'),
				'selector' => '.custom-fields h6',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-span',
				'name' => __('Custom Fields span','padma'),
				'selector' => '.custom-fields span',
			));

	}


	function content($block) {

		$block['custom-fields'] = $this->get_custom_fields($block);
		$content_block_display = new PadmaContentBlockDisplay($block);
		$content_block_display->display();

	}

	function get_custom_fields($block){

		$custom_fields_show = $custom_fields_label = $custom_fields_position = array();

		foreach ($block['settings'] as $name => $value) {

			$data = explode('-', $name);
			$post_type = (!empty($data[3])) ? $data[3]: null;

			if(is_null($post_type))
				continue;

			$custom_field = $name;
			$custom_field = str_replace('custom-field-show-' . $post_type . '-', '' , $custom_field);
			$custom_field = str_replace('custom-field-position-' . $post_type . '-', '' , $custom_field);
			$custom_field = str_replace('custom-field-label-' . $post_type . '-', '' , $custom_field);

			if ( strpos($name, 'custom-field-show') !== false ){				
				if($value){
					$custom_fields_show[$post_type][$custom_field] = $value;
				}				
			}

			if ( strpos($name, 'custom-field-position') !== false )
				$custom_fields_position[$post_type][$custom_field] = $value;

			if ( strpos($name, 'custom-field-label') !== false )
				$custom_fields_label[$post_type][$custom_field] = $value;

		}

		$data = array();

		foreach ($custom_fields_position as $post_type => $custom_fields) {
			foreach ($custom_fields as $field_name => $position) {
				if($custom_fields_show[$post_type][$field_name]){
					$label = $custom_fields_label[$post_type][$field_name];
					$data[$position][$post_type][$field_name] = $label;					
				}
			}
		}

		return $data;
	}


}


class PadmaContentBlockOptions extends PadmaBlockOptionsAPI {


	public $tab_notices;
	public $tabs;	
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tab_notices = array(
			'mode' => __('Der Content Block ist äußerst vielseitig.  Wenn der Standardmodus ausgewählt ist, verhält sich das Element wie erwartet. Wenn Du es beispielsweise auf einer Seite hinzufügst, wird deren Inhalt angezeigt. Fügee es im Blog-Übersichtslayout hinzu, werden die Beiträge wie in einer normalen Blogvorlage aufgelistet, und füge es in einem Kategorielayout hinzu, werden die Beiträge dieser Kategorie angezeigt. Um die Anzeige des Inhaltsblocks anzupassen, ändere den Modus auf „Benutzerdefinierte Abfrage“ und verwendest die Einstellungen im Tab „Abfragefilter“.','padma'),

			'query-setup' => __('Für mehr Kontrolle über Abfragen und deren Anzeige funktioniert Padma perfekt mit <a href="http://pluginbuddy.com/purchase/loopbuddy/" target="_blank">LoopBuddy</a>.','padma'),

			'meta' => __('
				<p>Die Metadaten eines Beitrags sind die Informationen, die unterhalb des Beitragstitels und des Beitragsinhalts angezeigt werden. Standardmäßig enthalten sie Informationen zum Autor des Beitrags, den Kategorien und Kommentaren.</p>
				<p><strong>Verfügbare Variablen:</strong></p>
				<p>%date% &bull; %modified_date% &bull; %time% &bull; %comments% &bull; %comments_no_link% &bull; %respond% &bull; %author% &bull; %author_no_link% &bull; %categories% &bull; %tags% &bull; %publisher% &bull; %publisher_img% &bull; %publisher_no_img%</p>
			','padma')
		);


		$this->tabs = array(
			'mode' 				=> __('Modus','padma'),
			'query-filters' 	=> __('Abfragefilter','padma'),
			'display' 			=> __('Anzeige','padma'),
			'custom-fields'		=> __('Benutzerdefinierte Felder','padma'),
			'meta' 				=> __('Metadaten','padma'),		
			'comments' 			=> __('Kommentare','padma'),
			'post-thumbnails' 	=> __('Featured Bilder','padma')
		);


		$this->inputs = array(

			'mode' => array(
				'mode' => array(
					'type' => 'select',
					'name' => 'mode',
					'label' => __('Abfragemodus','padma'),
					'tooltip' => '',
					'options' => array(
						'default' => __('Standardverhalten','padma'),
						'custom-query' => __('Benutzerdefinierte Abfrage','padma')
					),
					'toggle'    => array(
						'custom-query' => array(
							'show' => array(
								'li#sub-tab-query-filters'
							)
						),
						'default' => array(
							'hide' => array(
								'li#sub-tab-query-filters'
							)
						)
					)
				)
			),

			'query-filters' => array(
				'page-fetch-query-heading' => array(
					'name' => 'page-fetch--query-heading',
					'type' => 'heading',
					'label' => __('Seite abrufen','padma')
				),

				'fetch-page-content' => array(
					'type' => 'select',
					'name' => 'fetch-page-content',
					'label' => __('Seiteninhalt abrufen','padma'),
					'tooltip' => __('Abfrageoptionen haben keine Auswirkung, wenn Du Dich entschieden hast, eine Seite abzurufen','padma'),
					'options' => 'get_pages()'
				),

				'custom-query-heading' => array(
					'name' => 'custom-query-heading',
					'type' => 'heading',
					'label' => __('Abfragefilter','padma'),
					'tooltip' => __('Abfrageoptionen haben keine Auswirkung, wenn Du Dich entschieden hast, eine Seite abzurufen','padma')
				),

				'categories' => array(
					'type' => 'multi-select',
					'name' => 'categories',
					'label' => __('Kategorien','padma'),
					'tooltip' => '',
					'options' => 'get_categories()'
				),

				'categories-mode' => array(
					'type' => 'select',
					'name' => 'categories-mode',
					'label' => __('Kategorien Modus','padma'),
					'tooltip' => '',
					'options' => array(
						'include' => __('Einbeziehen','padma'),
						'exclude' => __('Ausschließen','padma')
					)
				),

				'enable-tags' => array(
					'type' => 'checkbox',
					'name' => 'tags-filter',
					'label' => __('Tags Filter','padma'),
					'tooltip' => __('Aktiviere dies, um den Tags-Filter anzuzeigen.','padma'),
					'default' => false,
					'toggle'    => array(
						'false' => array(
							'hide' => array(
								'#input-tags'
							)
						),
						'true' => array(
							'show' => array(
								'#input-tags'
							)
						)
					)
				),
				'tags' => array(
					'type' => 'multi-select',
					'name' => 'tags',
					'label' => __('Tags','padma'),
					'tooltip' => '',
					'options' => 'get_tags()'
				),


				'post-type' => array(
					'type' => 'multi-select',
					'name' => 'post-type',
					'label' => __('Beitragstyp','padma'),
					'tooltip' => '',
					'options' => 'get_post_types()',
					'callback' => 'reloadBlockOptions(block.id)'
				),

				'post-status' => array(
					'type' => 'multi-select',
					'name' => 'post-status',
					'label' => __('Beitragsstatus','padma'),
					'tooltip' => '',
					'options' => 'get_post_status()'
				),

				'author' => array(
					'type' => 'multi-select',
					'name' => 'author',
					'label' => __('Autor','padma'),
					'tooltip' => '',
					'options' => 'get_authors()'
				),

				'number-of-posts' => array(
					'type' => 'integer',
					'name' => 'number-of-posts',
					'label' => __('Anzahl der Beiträge','padma'),
					'tooltip' => '',
					'default' => 10
				),

				'offset' => array(
					'type' => 'integer',
					'name' => 'offset',
					'label' => __('Offset','padma'),
					'tooltip' => __('Der Offset ist die Anzahl der Einträge oder Beiträge, die Du überspringen möchtest. Wenn der Offset 1 ist, wird der erste Beitrag übersprungen.','padma'),
					'default' => 0
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
						'ID' => 'ID',
						'author' => __('Autor','padma'),
						'type' => __('Beitragstyp','padma'),
						'menu_order' => __('Benutzerdefinierte Reihenfolge','padma')
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
				),
				'byid-include' => array(
					'type' => 'text',
					'name' => 'byid-include',
					'label' => __('Inkludiert nach ID','padma'),
					'tooltip' => __('Sowohl bei "Inkludiert nach ID" als auch bei "Ausschließen nach ID" verwendest Du eine durch Kommas getrennte Liste von IDs Deines Beitragstyps.','padma')
					),

				'byid-exclude' => array(
					'type' => 'text',
					'name' => 'byid-exclude',
					'label' => __('Ausschließen nach ID','padma'),
					'tooltip' => __('Sowohl bei "Inkludiert nach ID" als auch bei "Ausschließen nach ID" verwendest Du eine durch Kommas getrennte Liste von IDs Deines Beitragstyps.','padma')
				)
			),

			'display' => array(
				'read-more-text' => array(
					'type' => 'text',
					'label' => __('Weiterlesen Text','padma'),
					'name' => 'read-more-text',
					'default' => __('Weiterlesen','padma'),
					'tooltip' => __('Falls Auszüge angezeigt werden oder ein vorgestellter Beitrag mit dem WordPress "Weiterlesen" Shortcode gekürzt wird, wird dieser Text nach dem Auszug oder gekürzten Inhalt angezeigt.','padma')
				),

				'show-titles' => array(
					'type' => 'checkbox',
					'name' => 'show-titles',
					'label' => __('Titel anzeigen','padma'),
					'default' => true,
					'tooltip' => __('Wenn Du nur den Inhalt und die Metadaten des Eintrags anzeigen möchtest, kannst Du mit dieser Option die Titel des Eintrags (Beitrag oder Seite) ausblenden.','padma')
				),

				'link-titles'  => array(
					'type' => 'checkbox',
					'name' => 'link-titles',
					'label' => __('Titel verlinken?','padma'),
					'default' => true,
					'tooltip' => __('Wenn Du den Link zu den Beitrags-/Seitentiteln deaktivieren möchtest, deaktiviere diese Option.','padma')
				),

				'show-archive-title'  => array(
					'type' => 'checkbox',
					'name' => 'show-archive-title',
					'label' => __('Archiv-Titel anzeigen?','padma'),
					'default' => true,
					'tooltip' => __('Wenn Du den Seitentitel in Archiv-Layouts (z.B. Kategorie, Schlagwort, etc.) ausschalten möchtest, deaktiviere diese Option.','padma')
				),

				'show-archive-title-type' => array(
					'type' => 'select',
					'name' => 'show-archive-title-type',
					'default' => 'normal',
					'options' => array(
						'normal' => 'Normal',
						'only-archive-name' => __('Nur Archivname','padma'),
						'show-custom-archive-title' => __('Benutzerdefinierter Titel','padma'),
					),
					'label' => __('Archiv-Titel Typ','padma'),
					'tooltip' => __('Zeige normalen Titel, nur Archiv (Kategorie, Schlagwort, etc.) oder benutzerdefinierten Archiv-Titel an','padma'),
					'toggle' => array(
						'normal' => array(
							'hide' => array(
								'#input-custom-archive-title',							
							)
						),
						'only-archive-name' => array(
							'hide' => array(
								'#input-custom-archive-title',
							)
						),
						'show-custom-archive-title' => array(
							'show' => array(
								'#input-custom-archive-title',
							)
						),
					)


				),

				'custom-archive-title'  => array(
					'type' => 'text',
					'name' => 'custom-archive-title',
					'label' => __('Benutzerdefinierter Archiv-Titel','padma'),
					'tooltip' => __('Verwende einen benutzerdefinierten Titel für das Archiv. Verwende %archive% für Kategorie, Schlagwort, etc.: Beispiel "Kategorie-Archiv: %archive%"','padma')				
				),

				'show-readmore' => array(
					'type' => 'checkbox',
					'name' => 'show-readmore',
					'label' => __('Weiterlesen anzeigen','padma'),
					'default' => true,
					'tooltip' => __('Zeige oder verstecke den "Weiterlesen" Text/Knopf.','padma')
				),

				'entry-content-display' => array(
					'type' => 'select',
					'name' => 'entry-content-display',
					'label' => __('Eintragsinhalt anzeigen','padma'),
					'tooltip' => __('Der Eintragsinhalt ist der eigentliche Text des Eintrags. Dies ist das, was Du im Rich-Text-Bereich eingibst, wenn Du einen Eintrag (Beitrag oder Seite) erstellst. Wenn auf "Normal" gesetzt, bestimmt Padma, ob vollständige Einträge oder Auszüge basierend auf der Einstellung <em>Featured Posts</em> und der angezeigten Seite angezeigt werden.<br /><br /><strong>Tipp:</strong> Setze dies auf <em>Eintragsinhalt ausblenden</em>, um eine einfache Auflistung von Beiträgen zu erstellen.','padma'),
					'default' => 'normal',
					'options' => array(
						'normal' => 'Normal',
						'full-entries' => __('Vollständige Einträge anzeigen','padma'),
						'excerpts' => __('Auszüge anzeigen','padma'),
						'hide' => __('Eintragsinhalt ausblenden','padma')
					),
					'toggle' => array(
						'normal' => array(
							'show' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						),
						'excerpts' => array(
							'show' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						),
						'full-entries' => array(
							'hide' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						),
						'hide' => array(
							'hide' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						)
					)
				),

				'show-entry' => array(
					'type' => 'checkbox',
					'name' => 'show-entry',
					'label' => __('Eintrag anzeigen','padma'),
					'default' => true,
					'tooltip' => __('Standardmäßig werden die Einträge immer angezeigt. Es kann jedoch Fälle geben, in denen Du den Eintragsinhalt in einem Inhaltsblock anzeigen möchtest, aber die Kommentare in einem anderen. Mit dieser Option kannst Du das tun.','padma')
				),

				'comments-visibility' => array(
					'type' => 'select',
					'name' => 'comments-visibility',
					'label' => __('Kommentare anzeigen','padma'),
					'default' => 'auto',
					'options' => array(
						'auto' => 'Automatisch',
						'hide' => __('Kommentare immer ausblenden','padma'),
						'show' => __('Kommentare immer anzeigen','padma')
					),
					'tooltip' => __('Falls auf Automatisch gesetzt, werden die Kommentare nur auf einzelnen Beitragsseiten angezeigt. Es kann jedoch Zeiten geben, in denen Du die Kommentarsichtbarkeit erzwingen möchtest, um Kommentare auf Seiten zuzulassen. Oder Du kannst die Kommentare ausblenden, wenn Du sie überhaupt nicht sehen möchtest.<br /><br /><strong>Tipp:</strong> Erstelle einzigartige Layouts, indem Du diese Option in Verbindung mit der Option Eintrag anzeigen verwendest, um den Eintragsinhalt in einem Inhaltsblock anzuzeigen und die Kommentare in einem anderen Inhaltsblock anzuzeigen.','padma')
				),

				'featured-posts' => array(
					'type' => 'integer',
					'name' => 'featured-posts',
					'label' => __('Hervorgehobene Beiträge','padma'),
					'default' => 1,
					'tooltip' => __('Hervorgehobene Beiträge sind Beiträge, in denen der gesamte Inhalt angezeigt wird, sofern dies nicht durch den WordPress-Tag „Mehr“ eingeschränkt wird. Nach der Anzeige der hervorgehobenen Beiträge wird automatisch auf die Darstellung von gekürzten Auszügen umgeschaltet.','padma')
				),

				'paginate' => array(
					'type' => 'checkbox',
					'name' => 'paginate',
					'label' => __('Ältere/Neuere Beiträge Navigation anzeigen','padma'),
					'tooltip' => __('In Archiv-Layouts: Zeige Links am Ende der Schleife an, damit der Besucher ältere oder neuere Beiträge ansehen kann.','padma'),
					'default' => true
				),

				'show-single-post-navigation' => array(
					'type' => 'checkbox',
					'name' => 'show-single-post-navigation',
					'label' => __('Einzelbeitragsnavigation anzeigen','padma'),
					'default' => true,
					'tooltip' => __('Standardmäßig zeigt Padma Links zu den vorherigen und nächsten Beiträgen unter einem Eintrag an, wenn jeweils nur ein Eintrag angezeigt wird. Du kannst diese Links mit dieser Option ausblenden.','padma'),
					'toggle' => array(

						'true' => array(
							'show' => '#input-show-single-post-navigation-enable-tax'
							),
						'false' => array(
							'hide' => array(
							'#input-show-single-post-navigation-enable-tax',
							'#input-show-single-post-navigation-tax'
							)
						)
					),

				),

				'show-single-post-navigation-enable-tax' => array(
					'type' => 'checkbox',
					'name' => 'show-single-post-navigation-enable-tax',
					'label' => __('Einzelbeitragsnavigation: Gleiche Taxonomie?','padma'),
					'default' => false,
					'tooltip' => __('Wenn die Einzelbeitragsnavigation aktiviert ist, zeigt WordPress/Padma standardmäßig Links zum nächsten und vorherigen Beitrag in chronologischer Reihenfolge an. Wenn du möchtest, dass die nächsten/vorherigen Beiträge nur auf Beiträge in derselben Taxonomie wie der aktuelle Beitrag verlinken, aktiviere diese Option.','padma'),
					'toggle' => array(

						'true' => array(
							'show' => '#input-show-single-post-navigation-tax'
							),
						'false' => array(
							'hide' => '#input-show-single-post-navigation-tax'
							)
					),
				),

				'show-single-post-navigation-tax' => array(
					'type' => 'select',
					'name' => 'show-single-post-navigation-tax',
					'label' => __('Einzelbeitragsnavigation Taxonomie','padma'),
					'default' => 'category',
					'tooltip' => __('Wenn du die Option "Gleiche Taxonomie" für die Einzelbeitragsnavigation aktiviert hast, kannst du hier auswählen, auf welche Taxonomie sie angewendet werden soll. Standardmäßig wird sie auf die Kategorie-Taxonomie angewendet.','padma'),
					'options' => 'get_taxonomies()'
				),

				'show-edit-link' => array(
					'type' => 'checkbox',
					'name' => 'show-edit-link',
					'label' => __('Bearbeitungslink anzeigen','padma'),
					'default' => true,
					'tooltip' => __('Der Bearbeitungslink ist ein praktischer Link, der neben dem Beitragstitel angezeigt wird. Er führt dich direkt zum WordPress-Adminbereich, um den Eintrag zu bearbeiten.','padma')
				),

				'custom-excerpts-heading' => array(
					'name' => 'custom-excerpts-heading',
					'type' => 'heading',
					'label' => __('Benutzerdefinierte Auszüge','padma')
				),

				'custom-excerpts' => array(
					'type' => 'checkbox',
					'name' => 'custom-excerpts',
					'label' => __('Benutzerdefinierte Auszugslänge','padma'),
					'default' => false,
					'tooltip' => __('Standardmäßig sind die Auszüge auf 55 Wörter eingestellt. Dies kann zu lang sein und erfordert normalerweise einen PHP-Hook, um geändert zu werden. Stattdessen kannst du hier eine benutzerdefinierte Anzahl angeben, indem du die gewünschte Wortanzahl festlegst.','padma'),
					'toggle' => array(
						'true' => array(
							'show' => '#input-excerpts-length'
							),
						'false' => array(
							'hide' => '#input-excerpts-length'
							)
					),
				),

				'excerpts-length' => array(
					'type' => 'integer',
					'name' => 'excerpts-length',
					'label' => __('Benutzerdefinierte Auszugslänge','padma'),
					'default' => '55',
					'tooltip' => __('Steuere die Länge des Auszugs. Standardmäßig sind sie auf 55 Wörter eingestellt. Mit dieser Einstellung kannst du die Länge nach Belieben verkürzen oder verlängern, was sehr praktisch ist, um das Aussehen deiner Archivseiten anzupassen.','padma')
				),

				'column-layout-heading' => array(
					'name' => 'column-layout-heading',
					'type' => 'heading',
					'label' => __('Spaltenlayout','padma')
				),

				'enable-column-layout' => array(
					'type' => 'checkbox',
					'name' => 'enable-column-layout',
					'label' => __('Spaltenlayout aktivieren','padma'),
					'default' => false,
					'tooltip' => __('Aktiviere diese Option, um Artikel nebeneinander als Spalten anzuzeigen.','padma'),
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-posts-per-row',
								'#input-post-gutter-width',
								'#input-post-bottom-margin'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-posts-per-row',
								'#input-post-gutter-width',
								'#input-post-bottom-margin'
							)
						)
					)
				),

				'posts-per-row' => array(
					'type' => 'slider',
					'name' => 'posts-per-row',
					'label' => 'Posts Per Row',
					'slider-min' => 1,
					'slider-max' => 10,
					'slider-interval' => 1,
					'tooltip' => '',
					'default' => 2,
					'tooltip' => __('Wie viele Beiträge pro Reihe angezeigt werden sollen.','padma'),
					'callback' => ''
				),

				'post-gutter-width' => array(
					'type' => 'slider',
					'name' => 'post-gutter-width', 
					'label' => 'Abstand zwischen den Spalten',
					'slider-min' => 0,
					'slider-max' => 100,
					'slider-interval' => 1,
					'default' => 15,
					'unit' => 'px',
					'tooltip' => __('Der horizontale Abstand zwischen den Beiträgen.','padma')
				)
			),

			'custom-fields' => array(),

			'meta' => array(
				'show-entry-meta-post-types' => array(
					'type' => 'multi-select',
					'name' => 'show-entry-meta-post-types',
					'label' => __('Entry Meta Anzeige (Pro Beitragstyp)','padma'),
					'tooltip' => __('Wähle aus, für welche Beitragstypen die Entry Meta angezeigt werden soll.','padma'),
					'options' => 'get_post_types()',
					'default' => array('post')
				),

				'entry-meta-above' => array(
					'type' => 'textarea',
					'label' => __('Meta über dem Inhalt','padma'),
					'name' => 'entry-meta-above',
					'default' => __('Posted am %date% von %author% &bull; %comments%','padma')
				),

				'entry-utility-below' => array(
					'type' => 'textarea',
					'label' => __('Meta unter dem Inhalt','padma'),
					'name' => 'entry-utility-below',
					'default' => __('Abgelegt unter: %categories%','padma')
				),

				'date-format' => array(
					'type' => 'select',
					'name' => 'date-format',
					'label' => __('Datumsformat','padma')
				),

				'time-format' => array(
					'type' => 'select',
					'name' => 'time-format',
					'label' => __('Zeitformat','padma')
				),

				'comments-meta-heading' => array(
					'name' => 'comments-meta-heading',
					'type' => 'heading',
					'label' => __('Kommentare Meta','padma')
				),

					'comment-format' => array(
						'type' => 'text',
						'label' => __('Kommentarformat &ndash; Mehr als 1 Kommentar','padma'),
						'name' => 'comment-format',
						'default' => '%num% Kommentare',
						'tooltip' => __('Steuert, was die Variablen %comments% und %comments_no_link% im Entry Meta ausgeben, wenn es <strong>mehr als 1 Kommentar</strong> zum Beitrag gibt.','padma')
					),

					'comment-format-1' => array(
						'type' => 'text',
						'label' => __('Kommentarformat &ndash; 1 Kommentar','padma'),
						'name' => 'comment-format-1',
						'default' => '%num% Kommentar',
						'tooltip' => __('Steuert, was die Variablen %comments% und %comments_no_link% im Entry Meta ausgeben, wenn es <strong>genau 1 Kommentar</strong> zum Beitrag gibt.','padma')
					),

					'comment-format-0' => array(
						'type' => 'text',
						'label' => __('Kommentarformat &ndash; 0 Kommentare','padma'),
						'name' => 'comment-format-0',
						'default' => '%num% Kommentare',
						'tooltip' => __('Steuert, was die Variablen %comments% und %comments_no_link% im Entry Meta ausgeben, wenn es <strong>0 Kommentare</strong> zum Beitrag gibt.','padma')

					),

					'respond-format' => array(
						'type' => 'text',
						'label' => __('Antwortformat','padma'),
						'name' => 'respond-format',
						'default' => __('Hinterlasse einen Kommentar!','padma'),
						'tooltip' => __('Steuert, was die Variable %respond% im Entry Meta ausgibt.','padma')
					)
			),

			'comments' => array(
				'comments-area' => array(
					'name' => 'comments-area',
					'type' => 'heading',
					'label' => __('Kommentare Bereich Überschrift','padma')
				),

					'comments-area-heading' => array(
						'type' => 'text',
						'label' => __('Kommentare Bereich Überschrift Format','padma'),
						'name' => 'comments-area-heading',
						'default' => '%responses% to <em>%title%</em>',
						'tooltip' => __('Überschrift über allen Kommentaren.
						<br />
						<br /><strong>Verfügbare Variablen:</strong>
						<ul>
							<li>%responses%</li>
							<li>%title%</li>
						</ul>','padma')
					),

					'comments-area-heading-responses-number' => array(
						'type' => 'text',
						'label' => __('Antwort Format &ndash; Mehr als 1 Kommentar','padma'),
						'name' => 'comments-area-heading-responses-number',
						'default' => '%num% Responses',
						'tooltip' => __('Steuert, was die Variable %responses% in der Kommentare Bereich Überschrift ausgibt, wenn es <strong>mehr als 1 Kommentar</strong> zum Beitrag gibt.','padma')
					),

					'comments-area-heading-responses-number-1' => array(
						'type' => 'text',
						'label' => __('Antwort Format &ndash; 1 Kommentar','padma'),
						'name' => 'comments-area-heading-responses-number-1',
						'default' => __('Eine Antwort','padma'),
						'tooltip' => __('Steuert, was die Variable %responses% in der Kommentare Bereich Überschrift ausgibt, wenn es <strong>genau 1 Kommentar</strong> zum Beitrag gibt.','padma')
					),

				'reply-area-heading' => array(
					'name' => 'reply-area-heading',
					'type' => 'heading',
					'label' => __('Antwort Bereich','padma')
				),

					'leave-reply' => array(
						'type' => 'text',
						'label' => __('Kommentar Formular Titel','padma'),
						'name' => 'leave-reply',
						'default' => __('Hinterlasse eine Antwort','padma'),
						'tooltip' => __('Dies ist der Text, der über dem Kommentarformular angezeigt wird.','padma')
					),

					'leave-reply-to' => array(
						'type' => 'text',
						'label' => __('Kommentar Formular Titel beim Antworten','padma'),
						'name' => 'leave-reply-to',
						'default' => __('Hinterlasse eine Antwort an %s','padma'),
						'tooltip' => __('Der Titel des Kommentarformulars beim Antworten auf einen Kommentar.','padma')
					),

					'cancel-reply-link' => array(
						'type' => 'text',
						'label' => __('Abbrechen Antwort Text','padma'),
						'name' => 'cancel-reply-link',
						'default' => __('Antwort abbrechen','padma'),
						'tooltip' => __('Der Text für die Schaltfläche "Antwort abbrechen".','padma')
					),

					'label-submit-text' => array(
						'type' => 'text',
						'label' => __('Absenden Text','padma'),
						'name' => 'label-submit-text',
						'default' => __('Kommentar absenden','padma'),
						'tooltip' => __('Der Text für die Absenden-Schaltfläche.','padma')
					)
			),

			'post-thumbnails' => array(

				'show-post-thumbnails' => array(
					'type' => 'checkbox',
					'name' => 'show-post-thumbnails',
					'label' => __('Beitragsbild anzeigen','padma'),
					'default' => true,
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-featured-image-as-background'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-featured-image-as-background'
							)
						)
					)
				),

				'featured-image-as-background' => array(
					'type' => 'checkbox',
					'name' => 'featured-image-as-background',
					'label' => __('Beitragsbild als Hintergrund verwenden','padma'),
					'default' => false,
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-featured-image-as-background-overlay',
							)
						),
						'false' => array(
							'hide' => array(
								'#input-featured-image-as-background-overlay'
							)
						),
					),
				),

				'featured-image-as-background-overlay' => array(
					'type' => 'colorpicker',
					'name' => 'featured-image-as-background-overlay',
					'label' => __('Beitragsbild Overlay','padma'),
					'default' => '00000003',
				),

				'featured-image-as-background-overlay-hover' => array(
					'type' => 'colorpicker',
					'name' => 'featured-image-as-background-overlay-hover',
					'label' => __('Beitragsbild Overlay Hover','padma'),
					'default' => '00000000',
				),

				'post-thumbnails-link' => array(
					'type' => 'select',
					'name' => 'post-thumbnails-link',
					'label' => __('Link Beitragsbild','padma'),
					'default' => 'entry',
					'options' => array(
						'entry' => __('Entry (Default)','padma'),
						'media' => __('Anhangsseite','padma'),
						'none' => __('Keine','padma'),
						'custom' => __('Benutzerdefiniert','padma'),
					),
					'toggle'    => array(
						'custom' => array(
							'show' => array(
								'#input-post-thumbnails-custom-link',
								'#input-post-thumbnails-link-new-tab'
							)
						),
						'entry' => array(
							'show' => array(								
								'#input-post-thumbnails-link-new-tab'
							),
							'hide' => array(
								'#input-post-thumbnails-custom-link'
							)
						),
						'media' => array(
							'show' => array(								
								'#input-post-thumbnails-link-new-tab'
							),
							'hide' => array(
								'#input-post-thumbnails-custom-link'
							)
						),
						'none' => array(
							'hide' => array(
								'#input-post-thumbnails-custom-link',
								'#input-post-thumbnails-link-new-tab'
							)
						),
					),
					'tooltip' => __('Standardmäßig erstellt Padma einen Link um das Beitragsbild, der zurück zum Beitrag führt. Wählen Sie keinen Link oder einen Link zur Anhangsseite des Bildes.','padma')
				),

				'post-thumbnails-custom-link' => array(
					'type' => 'text',
					'label' => __('Benutzerdefinierter Link','padma'),
					'name' => 'post-thumbnails-custom-link',
					'default' => '',
				),

				'post-thumbnails-link-new-tab' => array(
					'type' => 'checkbox',
					'label' => __('In neuem Tab öffnen','padma'),
					'name' => 'post-thumbnails-link-new-tab',
					'default' => '',
				),

				'post-thumbnail-position' => array(
					'type' => 'select',
					'name' => 'post-thumbnail-position',
					'label' => __('Bildposition','padma'),
					'default' => 'left',
					'options' => array(
						'left' => __('Links vom Titel','padma'),
						'right' => __('Rechts vom Titel','padma'),
						'left-content' => __('Links vom Inhalt','padma'),
						'right-content' => __('Rechts vom Inhalt','padma'),
						'above-title' => __('Über dem Titel','padma'),
						'above-content' => __('Über dem Inhalt','padma'),
						'below-content' => __('Unter dem Inhalt','padma')
					)
				),

				'use-entry-thumbnail-position' => array(
					'type' => 'checkbox',
					'name' => 'use-entry-thumbnail-position',
					'label' => __('Verwende pro Beitrag festgelegte Bildpositionen','padma'),
					'default' => true,
					'tooltip' => __('Im ClassicPress Schreibbereich gibt es eine Padma-Metabox, mit der Du die Position des Beitragsbildes für den gerade bearbeiteten Beitrag ändern kannst.<br /><br />Standardmäßig verwendet der Block diesen Wert, aber Du kannst diese Option deaktivieren, damit immer die Bildposition des Blocks verwendet wird.','padma')
				),

				'thumbnail-sizing-heading' => array(
					'name' => 'thumbnail-sizing-heading',
					'type' => 'heading',
					'label' => __('Größe des Beitragsbildes','padma')
				),

					'post-thumbnail-size' => array(
						'type' => 'slider',
						'name' => 'post-thumbnail-size',
						'label' => __('Größe des Beitragsbildes (Links/Rechts)','padma'),
						'default' => 125,
						'slider-min' => 20,
						'slider-max' => 400,
						'slider-interval' => 1,
						'tooltip' => __('Passe die Größe der Beitragsbilder an. Dies wird sowohl für die Breite als auch für die Höhe der Bilder verwendet.','padma'),
						'unit' => 'px'
					),

					'post-thumbnail-height-ratio' => array(
						'type' => 'slider',
						'name' => 'post-thumbnail-height-ratio',
						'label' => __('Höhenverhältnis des Beitragsbildes (Über dem Titel/Inhalt)','padma'),
						'default' => 35,
						'slider-min' => 10,
						'slider-max' => 200,
						'slider-interval' => 5,
						'tooltip' => __('Passe die Höhe der Beitragsbilder an, wenn diese über dem Titel oder dem Inhalt positioniert sind. Dieser Wert bestimmt, wie viel Prozent der Bildhöhe im Verhältnis zur Blockbreite liegt.<br /><br />Beispiel: Bei einer Blockbreite von 500 Pixeln und einem Seitenverhältnis von 50 % beträgt die Größe des Beitragsbildes 500 x 250 Pixel.','padma'),
						'unit' => '%'
					),

					'crop-post-thumbnails' => array(
						'type' => 'checkbox',
						'name' => 'crop-post-thumbnails',
						'label' => __('Beitragsbilder zuschneiden','padma'),
						'default' => true
					)
			)

		);
	}


	function modify_arguments($args = false) {

		global $pluginbuddy_loopbuddy;

		if ( class_exists('pluginbuddy_loopbuddy') && isset($pluginbuddy_loopbuddy) ) {

			//Remove the old tabs
			unset($this->tabs['mode']);
			unset($this->tabs['meta']);
			unset($this->tabs['display']);
			unset($this->tabs['query-filters']);
			unset($this->tabs['post-thumbnails']);

			unset($this->inputs['mode']);
			unset($this->inputs['meta']);
			unset($this->inputs['display']);
			unset($this->inputs['query-filters']);
			unset($this->inputs['post-thumbnails']);

			//Add in new tabs
			$this->tabs['loopbuddy'] = 'LoopBuddy';

			$this->inputs['loopbuddy'] = array(
				'loopbuddy-query' => array(
					'type' => 'select',
					'name' => 'loopbuddy-query',
					'label' => __('LoopBuddy Query','padma'),
					'options' => 'get_loopbuddy_queries()',
					'tooltip' => __('Select a LoopBuddy query to the right.  Queries determine what content (posts, pages, etc) will be displayed.  You can modify/add queries in the WordPress admin under LoopBuddy.','padma'),
					'default' => ''
				),

				'loopbuddy-layout' => array(
					'type' => 'select',
					'name' => 'loopbuddy-layout',
					'label' => __('LoopBuddy Layout','padma'),
					'options' => 'get_loopbuddy_layouts()',
					'tooltip' => __('Select a LoopBuddy layout to the right.  Layouts determine how the query will be displayed.  This includes the order of the content in relation to the title, meta, and so on.  You can modify/add layouts in the WordPress admin under LoopBuddy.','padma'),
					'default' => ''
				)
			);

			$this->tab_notices = array(
				'loopbuddy' => sprintf( __('<strong>Auch wenn wir hier die Optionen haben, ein LoopBuddy-Layout und eine Abfrage auszuwählen, empfehlen wir, LoopBuddy über sein <a href="%s" target="_blank">Optionsfeld</a> zu konfigurieren.</strong><br /><br />Die untenstehenden Optionen sind nützlicher, wenn Du zwei Inhaltsblöcke in einem Layout verwendest und diese separat konfigurieren möchtest.  <strong>Hinweis:</strong> Du MUSST eine Abfrage ausgewählt haben, um auch ein LoopBuddy-Layout auswählen zu können.','padma'), admin_url('admin.php?page=pluginbuddy_loopbuddy') )
			);

			return;

		}

		if ( class_exists('SWP_Query') ) {

			$this->inputs['display']['swp-heading'] = array(
					'name'  => 'swp-heading',
					'type'  => 'heading',
					'label' => 'SearchWP'
			);

			$this->inputs['display']['swp-engine'] = array(
				'type'    => 'select',
				'name'    => 'swp-engine',
				'label'   => __('SearchWP Engine','padma'),
				'options' => 'get_swp_engines()',
				'tooltip' => __('Wenn Du die Ergebnisse einer ergänzten SearchWP-Suchmaschine anzeigen möchtest, wähle bitte hier die Suchmaschine aus.','padma'),
				'default' => ''
			);

		}

		$this->inputs['meta']['date-format']['options'] = array(
			'wordpress-default' => 'ClassicPress Standard',
			'F j, Y' => date('F j, Y'),
			'm/d/y' => date('m/d/y'),
			'd/m/y' => date('d/m/y'),
			'M j' => date('M j'),
			'M j, Y' => date('M j, Y'),
			'F j' => date('F j'),
			'F jS' => date('F jS'),
			'F jS, Y' => date('F jS, Y')
		);

		$this->inputs['meta']['time-format']['options'] = array(
			'wordpress-default' => 'ClassicPress Standard',
			'g:i A' => date('g:i A'),
			'g:i A T' => date('g:i A T'),
			'g:i:s A' => date('g:i:s A'),
			'G:i' => date('G:i'),
			'G:i T' => date('G:i T')
		);


		/**
		 *
		 * Custom Fields support
		 *
		 */


		$post_types = $custom_fields = array();

		if( !empty($this->block['settings']['mode']) && $this->block['settings']['mode'] == 'custom-query' ){

			if( isset($this->block['settings']['post-type']) )
				$post_types = $this->block['settings']['post-type'];
			else
				$post_types = array('post');

		}else{
			$post_types = get_post_types();
		}


		$custom_fields = PadmaQuery::get_meta($post_types);		

		if(count($custom_fields)==0){

			if($this->block['settings']['mode'] == 'custom-query')
				$this->tab_notices['custom-fields'] = __('Der ausgewählte Beitragstyp verfügt über keine benutzerdefinierten Felder.','padma');
			else
				$this->tab_notices['custom-fields'] = __('Es sind keine benutzerdefinierten Felder vorhanden.','padma');

		}else{

			$inputs = array();

			foreach ($custom_fields as $post_type => $fields) {

				$heading = 'custom-fields-'.$post_type.'-heading';

				$inputs[$heading] = array(
					'name' => $heading,
					'type' => 'heading',
					'label' => 'Custom Fields for: "' . $post_type . '".'
				);

				foreach ($fields as $field_name => $posts_total) {

					// Custom field name
					$name = 'custom-field-show-' . $post_type . '-' . $field_name;

					// Custom field position
					$label = 'custom-field-label-' . $post_type . '-' . $field_name;					

					// Custom field position
					$position = 'custom-field-position-' . $post_type . '-' . $field_name;

					// Custom field input
					$inputs[$name] = array(
						'type' => 'checkbox',
						'name' => $name,
						'label' => 'Show "' . $field_name .'"',
						'tooltip' => 'Check this to allow show ' . $field_name,
						'default' => false,
						'toggle'    => array(
							'false' => array(
								'hide' => array(
									'#input-' . $position,
									'#input-' . $label
								)
							),
							'true' => array(
								'show' => array(
									'#input-' . $position,
									'#input-' . $label
								)
							)
						)
					);					

					// Custom field label input
					$inputs[$label] = array(
						'type' => 'text',
						'name' => $label,
						'label' => '"'.$field_name .'" label',
						'default' => '',
					);

					// Custom field position input
					$inputs[$position] = array(
						'type' => 'select',
						'name' => $position,
						'label' => '"'.$field_name .'" position',
						'default' => 'below',
						'options' => array(
							'above' => 'Above',
							'after-title' => 'After Title',
							'below' => 'Below'
						)
					);

				}
			}

			$this->inputs['custom-fields'] = $inputs;

		}

	}


	function get_pages() {

		$page_options = array( __('&ndash; Nicht abrufen &ndash;','padma') );

		$page_select_query = get_pages();

		foreach ($page_select_query as $page)
			$page_options[$page->ID] = $page->post_title;

		return $page_options;

	}


	function get_categories() {

		if( isset($this->block['settings']['post-type']) )
			return PadmaQuery::get_categories($this->block['settings']['post-type']);
		else
			return array();

	}

	function get_tags() {

		$tag_options = array();
		$tags_select_query = get_terms('post_tag');
		foreach ($tags_select_query as $tag)
			$tag_options[$tag->term_id] = $tag->name;
		$tag_options = (count($tag_options) == 0) ? array('text'	 => __('Keine Schlagwörter verfügbar','padma') ) : $tag_options;
		return $tag_options;

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


	function get_post_types() {

		$post_type_options = array();

		$post_types = get_post_types(false, 'objects'); 

		foreach($post_types as $post_type_id => $post_type){

			//Make sure the post type is not an excluded post type.
			if(in_array($post_type_id, array('revision', 'nav_menu_item'))) 
				continue;

			$post_type_options[$post_type_id] = $post_type->labels->name;

		}

		return $post_type_options;

	}

	function get_taxonomies() {

		$taxonomy_options = array('&ndash; Default: Category &ndash;');

		$taxonomy_select_query=get_taxonomies(false, 'objects', 'or');


		foreach ($taxonomy_select_query as $taxonomy)
			$taxonomy_options[$taxonomy->name] = $taxonomy->label;


		return $taxonomy_options;

	}

	function get_post_status() {

		return get_post_stati();

	}


	function get_swp_engines() {

		$options = array( __('&ndash; Wähle eine Suchmaschine &ndash;','padma') );

		if ( !function_exists('SWP') ) {
			return $options;
		}

		$searcbtp = SWP();

		if ( !is_array( $searcbtp->settings['engines']) ) {
			return $options;
		}

		foreach ( $searcbtp->settings['engines'] as $engine => $engine_settings ) {

			if ( empty( $engine_settings['searcbtp_engine_label'] ) ) {
				continue;
			}

			$options[$engine] = $engine_settings['searcbtp_engine_label'];

		}

		return $options;

	}


	function get_loopbuddy_queries() {

		$loopbuddy_options = get_option('pluginbuddy_loopbuddy');

		$queries = array(
			'' => __('&ndash; Standardabfrage verwenden &ndash;','padma')
		);

		foreach ( $loopbuddy_options['queries'] as $query_id => $query ) {

			$queries[$query_id] = $query['title'];

		}

		return $queries;

	}


	function get_loopbuddy_layouts() {

		$loopbuddy_options = get_option('pluginbuddy_loopbuddy');

		$layouts = array(
			'' => __('&ndash; Standardlayout verwenden &ndash;','padma')
		);

		foreach ( $loopbuddy_options['layouts'] as $layout_id => $layout ) {

			$layouts[$layout_id] = $layout['title'];

		}

		return $layouts;

	}
}
