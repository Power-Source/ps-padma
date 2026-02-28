<?php
class PadmaSchema {

	protected static $rendered_article_schema = array();

	function __construct(){

	}

	public static function init() {

		if(PadmaOption::get('disable-schema-support'))
			return;

	}


	/**
	 *
	 * Article data
	 *
	 */
	public static function article($post){

		if(is_null($post))
			return;

		if ( ! is_object($post) || empty($post->ID) )
			return;

		if ( isset(self::$rendered_article_schema[$post->ID]) )
			return '';

		self::$rendered_article_schema[$post->ID] = true;

		/**
		 *
		 * Author
		 *
		 */		
		$author = trim(get_the_author_meta('first_name', $post->post_author) . ' ' . get_the_author_meta('last_name', $post->post_author));

		if(trim($author) == '')
			$author = get_the_author_meta('display_name', $post->post_author);

		if(trim($author) == '')
			$author = get_the_author_meta('nickname', $post->post_author);

		if(trim($author) == '')
			$author = get_the_author_meta('user_nicename', $post->post_author);


		/**
		 *
		 * Site Image
		 *
		 */
		$blog_id = (is_multisite()) ? get_current_blog_id(): 0;
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$site_image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		if( $site_image !== false){
			$site_image = $site_image[0];
		}


		/**
		 *
		 * Article Image
		 *
		 */		
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		if( $image !== false){
			$image = $image[0];
		}

		if($site_image && !$image){
			$image = $site_image;	
		}

		$article = array(
			'@context' => 'https://schema.org',
			'@type' => 'Article',
			'mainEntityOfPage' => get_permalink($post->ID),
			'headline' => $post->post_title,
			'author' => array(
				'@type' => 'Person',
				'name' => $author,
				'url' => get_author_posts_url($post->post_author),
			),
			'publisher' => array(
				'@type' => 'Organization',
				'name' => get_bloginfo('name'),
				'url' => site_url(),
			),
		);

		if($site_image){
			$article['publisher']['logo'] = array(
				'@type' => 'ImageObject',
				'url' => $site_image,
			);
		}

		if($image)
			$article['image'] = $image;


		/**
		 *
		 * Check dates
		 * https://www.facebook.com/groups/padmaunlimitedEN/permalink/688903958279742/
		 */

		if( padma_validateDate($post->post_date) )
			$article['dateCreated'] = get_date_from_gmt(get_gmt_from_date($post->post_date), 'c');

		if( padma_validateDate($post->post_date) )
			$article['datePublished'] = get_date_from_gmt(get_gmt_from_date($post->post_date), 'c');

		if( padma_validateDate($post->post_modified) )
			$article['dateModified'] = get_date_from_gmt(get_gmt_from_date($post->post_modified), 'c');


		return '<script type="application/ld+json">' . wp_json_encode($article, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

	}
}