<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockPostSlider extends \PadmaBlockAPI {

    public $id 				= 'post-slider-block';    
    public $name 			= 'Post Slider';
	public $options_class 	= 'Padma_Advanced\\PadmaVisualElementsBlockPostSliderOptions';
    public $categories 		= array('content','gallery');
    private $authors;

    function __construct(){
	    $this->authors = \PadmaQuery::get_authors();
    }
        			
	function setup_elements() {
		$this->register_block_element(array(
			'id' => 'slide',
			'name' => 'Slide',
			'selector' => '.owl-item'
		));

		$this->register_block_element(array(
			'id' => 'slide-p',
			'name' => 'Slide text',
			'selector' => '.owl-item p'
		));

		$this->register_block_element(array(
			'id' => 'slide-a',
			'name' => 'Slide link',
			'selector' => '.owl-item a'
		));

		$this->register_block_element(array(
			'id' => 'slide-a-button',
			'name' => 'Slide Read more button',
			'selector' => '.owl-item a.button'
		));

		$this->register_block_element(array(
			'id' => 'slide-h1',
			'name' => 'Slide H1',
			'selector' => '.owl-item h1'
		));

		$this->register_block_element(array(
			'id' => 'slide-h2',
			'name' => 'Slide H2',
			'selector' => '.owl-item h2'
		));

		$this->register_block_element(array(
			'id' => 'slide-h3',
			'name' => 'Slide H3',
			'selector' => '.owl-item h3'
		));

		$this->register_block_element(array(
			'id' => 'slide-h4',
			'name' => 'Slide H4',
			'selector' => '.owl-item h4'
		));

		$this->register_block_element(array(
			'id' => 'slide-h5',
			'name' => 'Slide H5',
			'selector' => '.owl-item h5'
		));

		$this->register_block_element(array(
			'id' => 'slide-ul',
			'name' => 'Slide UL',
			'selector' => '.owl-item ul'
		));


		$this->register_block_element(array(
			'id' => 'slide-li',
			'name' => 'Slide LI',
			'selector' => '.owl-item li'
		));

		$this->register_block_element(array(
			'id' => 'dots',
			'name' => 'Dots',
			'selector' => '.owl-dots'
		));

		$this->register_block_element(array(
			'id' => 'dots-item',
			'name' => 'Dots Item',
			'selector' => '.owl-dots .owl-dot'
		));

		/**
		 *
		 * Style 1
		 *
		 */
			$this->register_block_element(array(
				'id' => 'padma-post-slider-area-style1',
				'name' => 'Style 1 Area',
				'selector' => '.padma-post-slider-area-style1'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1',
				'name' => 'Style 1 Carousel',
				'selector' => '.carousel-style1'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item',
				'name' => 'Style 1 Item',
				'selector' => '.carousel-style1-item',
				'states' => array(
					'Hover' => '.carousel-style1-item:hover', 
					'Clicked' => '.carousel-style1-item:active'
				)
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-image',
				'name' => 'Style 1 Image',
				'selector' => '.carousel-style1-item-image'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-category',
				'name' => 'Style 1 Categories',
				'selector' => '.carousel-style1-item-category'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-category-a',
				'name' => 'Style 1 Single Categorie',
				'selector' => '.carousel-style1-item-category a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-reviews',
				'name' => 'Style 1 Content container',
				'selector' => '.carousel-style1-item-reviews'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-title',
				'name' => 'Style 1 Title',
				'selector' => '.carousel-style1-item-title'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-title-a',
				'name' => 'Style 1 Title Link',
				'selector' => '.carousel-style1-item-title a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-description',
				'name' => 'Style 1 Content',
				'selector' => '.carousel-style1-item-description'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-author',
				'name' => 'Style 1 Author',
				'selector' => '.carousel-style1-item-author'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-author-a',
				'name' => 'Style 1 Author Link',
				'selector' => '.carousel-style1-item-author a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style1-item-author-icon',
				'name' => 'Style 1 Author Icon',
				'selector' => '.carousel-style1-item-author i'
			));

		/**
		 *
		 * Style 2
		 *
		 */
			$this->register_block_element(array(
				'id' => 'padma-post-slider-area-style2',
				'name' => 'Style 2 Area',
				'selector' => '.padma-post-slider-area-style2'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2',
				'name' => 'Style 2 Carousel',
				'selector' => '.carousel-style2'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item',
				'name' => 'Style 2 Item',
				'selector' => '.carousel-style2-item',
				'states' => array(
					'Hover' => '.carousel-style2-item:hover', 
					'Clicked' => '.carousel-style2-item:active'
				)
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item-image',
				'name' => 'Style 2 Image',
				'selector' => '.carousel-style2-item-image'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item-meta',
				'name' => 'Style 2 Meta',
				'selector' => '.carousel-style2-item-meta'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item-meta-item',
				'name' => 'Style 2 Meta Item',
				'selector' => '.carousel-style2-item-meta li'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item-date',
				'name' => 'Style 2 Date',
				'selector' => '.carousel-style2-item-date'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item-author',
				'name' => 'Style 2 Author',
				'selector' => '.carousel-style2-item-author'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style2-item-description',
				'name' => 'Style 2 Content',
				'selector' => '.carousel-style2-item-description'
			));

		/**
		 *
		 * Style 3
		 *
		 */
			$this->register_block_element(array(
				'id' => 'padma-post-slider-area-style3',
				'name' => 'Style 3 Area',
				'selector' => '.padma-post-slider-area-style3'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3',
				'name' => 'Style 3 Carousel',
				'selector' => '.carousel-style3'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item',
				'name' => 'Style 3 Item',
				'selector' => '.carousel-style3-item',
				'states' => array(
					'Hover' => '.carousel-style3-item:hover', 
					'Clicked' => '.carousel-style3-item:active'
				)
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-image-link',
				'name' => 'Style 3 Image Link',
				'selector' => '.carousel-style3-item-image a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-image',
				'name' => 'Style 3 Image',
				'selector' => '.carousel-style3-item-image a img'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-title',
				'name' => 'Style 3 Title',
				'selector' => 'h5.carousel-style3-item-title'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-title-a',
				'name' => 'Style 3 Title Link',
				'selector' => 'h5.carousel-style3-item-title a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-meta',
				'name' => 'Style 3 Meta',
				'selector' => '.carousel-style3-item-meta'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-meta-item',
				'name' => 'Style 3 Meta Item',
				'selector' => '.carousel-style3-item-meta li'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-date',
				'name' => 'Style 3 Date',
				'selector' => '.carousel-style3-item-date'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-author',
				'name' => 'Style 3 Author',
				'selector' => '.carousel-style3-item-author'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-button',
				'name' => 'Style 3 Button',
				'selector' => 'a.carousel-style3-item-button'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style3-item-description',
				'name' => 'Style 3 Content',
				'selector' => '.carousel-style3-item-description'
			));
		
		/**
		 *
		 * Style 4
		 *
		 */
			$this->register_block_element(array(
				'id' => 'padma-post-slider-area-style4',
				'name' => 'Style 4 Area',
				'selector' => '.padma-post-slider-area-style4'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style4',
				'name' => 'Style 4 Carousel',
				'selector' => '.carousel-style4'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style4-item-description',
				'name' => 'Style 4 Content',
				'selector' => '.carousel-style4-item-description'
			));
		
		/**
		 *
		 * Style 5
		 *
		 */
			$this->register_block_element(array(
				'id' => 'padma-post-slider-area-style5',
				'name' => 'Style 5 Area',
				'selector' => '.padma-post-slider-area-style5'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5',
				'name' => 'Style 5 Carousel',
				'selector' => '.carousel-style5'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5-item',
				'name' => 'Style 5 Item',
				'selector' => '.carousel-style5-item',
				'states' => array(
					'Hover' => '.carousel-style5-item:hover', 
					'Clicked' => '.carousel-style5-item:active'
				)
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5-item-image-link',
				'name' => 'Style 5 Image Link',
				'selector' => '.carousel-style5-item-image a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5-item-image',
				'name' => 'Style 5 Image',
				'selector' => '.carousel-style5-item-image a img'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5-item-title',
				'name' => 'Style 5 Title',
				'selector' => '.carousel-style5-item-title'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5-item-title-a',
				'name' => 'Style 5 Title Link',
				'selector' => '.carousel-style5-item-title a'
			));			
			$this->register_block_element(array(
				'id' => 'carousel-style5-item-button',
				'name' => 'Style 5 Button',
				'selector' => '.carousel-style5-item-button'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style5-item-description',
				'name' => 'Style 5 Content',
				'selector' => '.carousel-style5-item-description'
			));

		/**
		 *
		 * Style 6
		 *
		 */
			$this->register_block_element(array(
				'id' => 'padma-post-slider-area-style6',
				'name' => 'Style 6 Area',
				'selector' => '.padma-post-slider-area-style6'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style6',
				'name' => 'Style 6 Carousel',
				'selector' => '.carousel-style6'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style6-item',
				'name' => 'Style 6 Item',
				'selector' => '.carousel-style6-item',
				'states' => array(
					'Hover' => '.carousel-style6-item:hover', 
					'Clicked' => '.carousel-style6-item:active'
				)
			));
			$this->register_block_element(array(
				'id' => 'carousel-style6-item-image-link',
				'name' => 'Style 6 Image Link',
				'selector' => '.carousel-style6-item-image a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style6-item-title',
				'name' => 'Style 6 Title',
				'selector' => '.carousel-style6-item-title'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style6-item-title-a',
				'name' => 'Style 6 Title Link',
				'selector' => '.carousel-style6-item-title a'
			));
			$this->register_block_element(array(
				'id' => 'carousel-style6-item-description',
				'name' => 'Style 6 Content',
				'selector' => '.carousel-style6-item-description'
			));
				
	}

	public static function enqueue_action($block_id, $block) {

		$path = padma_url() . '/library/blocks-advanced/post-slider/';

		wp_enqueue_style('padma-post-slider-transitions-css', $path . 'css/owl.transitions.css', array(), PADMA_VERSION);
		wp_enqueue_style('padma-post-slider-slider-css', $path . 'css/owl.carousel.css', array(), PADMA_VERSION);
		wp_enqueue_style('padma-post-slider-awesome-css', $path . 'css/font-awesome.css', array(), PADMA_VERSION);
		wp_enqueue_style('padma-post-slider-theme-css', $path . 'css/owl.theme.css', array(), PADMA_VERSION);
		wp_enqueue_script('padma-post-slider-slider-js', $path . 'js/owl.carousel.js', array('jquery'), PADMA_VERSION, false);
	}

	function content($block) {
		
		$post_type 			= ( !empty($block['settings']['post-type']) ) ? $block['settings']['post-type']: 'post';		
		$slider_style 		= ( !empty($block['settings']['slider-style']) ) ? $block['settings']['slider-style']: 'style1';
		$order_by 			= ( !empty($block['settings']['order-by']) ) ? $block['settings']['order-by']: 'date';
		$order 				= ( !empty($block['settings']['order']) ) ? $block['settings']['order']: 'desc';
		$categories 		= ( !empty($block['settings']['categories']) ) ? $block['settings']['categories']: array();	

		$posts = \PadmaQuery::get_posts($block);

		switch ($slider_style) {

			case 'style1':
				echo $this->render_style1($block,$posts);
				break;
			
			case 'style2':
				echo $this->render_style2($block,$posts);
				break;

			case 'style3':
				echo $this->render_style3($block,$posts);
				break;
			
			case 'style4':
				echo $this->render_style4($block,$posts);
				break;

			case 'style5':
				echo $this->render_style5($block,$posts);
				break;
			
			case 'style6':
				echo $this->render_style6($block,$posts);
				break;
			
			default:
				echo $this->render_style1($block,$posts);
				break;
		}

	}

	private function prepare_content($block,$post){
		
		$content_to_show 		= ( !empty($block['settings']['content-to-show']) ) ? $block['settings']['content-to-show']: 'normal';		
		$custom_length 			= ( !empty($block['settings']['custom-length']) ) ? $block['settings']['custom-length']: 'no';
		$custom_length_number 	= ( !empty($block['settings']['custom-length-number']) ) ? $block['settings']['custom-length-number']: 15;

		$content = '';
		$show_content = true;
		
		if( $content_to_show == 'normal' ){
			
			$content = $post->post_content;

		}elseif ( $content_to_show == 'excerpts' ) {

			$content = $post->post_excerpt;

			if( strlen($content) == 0 ){
				$content = $post->post_content;
			}

		}elseif ( $content_to_show == 'none' ) {
			return false;
		}

		if( $custom_length === 'yes' ){
			$content = wp_trim_words( do_shortcode($content), $custom_length_number );
		}else{
			$content = do_shortcode($content);
		}

		return $content;
	}

	private function render_style1($block,$posts){
		
		$html = '';
		$html .='<div class="padma-post-slider-area-style1 post_slider_'.$block['id'] .' padma-post-slider-area'.$block['id'].'">';
		$html .='<div id="tppost-main-slider-'.$block['id'].'" class="carousel-style1 owl-carousel tppost-main-slider">';
		foreach ($posts as $key => $post) {

			$content = $this->prepare_content($block,$post);

			$html .= '<div class="carousel-style1-item pps_single_slider_items-'.$block['id'].' pps_single_slider_items">';
				

				// Featured image
				$html .= '<div class="carousel-style1-item-image pps_single_slider_items_post_images-'.$block['id'].'">';
				if ( has_post_thumbnail( $post->ID ) ) {

					$html .= '<div class="tps-slider-thumb">';
					$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'">'.get_the_post_thumbnail( $post->ID, 'post-slider-thumb', array( 'class' => "img-responsive" ) ).'</a>';
					$html .= '</div>';

				}

				// Categories
				$html .='<div class="carousel-style1-item-category pps_single_slider_items_category-'.$block['id'].'">';
				$cats = get_the_category( $post->ID );
				foreach ( $cats as $cat ){
					$html .='<a href="'.get_category_link($cat->cat_ID).'">'.$cat->name.'</a>';
					
				}							
				$html .='</div>';
				$html .='</div>';



				// Content and more
				$html .= '<div class="carousel-style1-item-reviews pps_single_slider_item_reviews pps_single_slider_item_reviews-'.$block['id'].'">';
				
					// Title
					$html .= '<h3 class="carousel-style1-item-title pps_single_slider_item_post_title-'.$block['id'].'"><a href="'.esc_url(get_the_permalink($post->ID)).'">'.esc_attr(get_the_title($post->ID)).'</a></h3>';

					// Content
					if( $content !== false ){
						$html .= '<div class="carousel-style1-item-description pps_single_slider_item_description pps_single_slider_item_description-'.$block['id'].'">'.$content.'</div>';						
					}

					// Author
					$author = $this->authors[ $post->post_author ];
					$html .= '<div class="carousel-style1-item-author pps_single_slider_admin_description pps_single_slider_admin_description-'.$block['id'].'">';
					$html .= '<span><i class="fa fa-user"></i> <a href="'.get_author_posts_url( $post->post_author, get_the_author_meta( 'user_nicename' ) ).'">'.$author.'</a></span></div>';
					

			$html .= '</div></div>';

		}
		$html .='</div></div><div class="clearfix"></div>';
	
		return $html;
	}

	private function render_style2($block,$posts){

		$html = '';
		$html .='<div class="padma-post-slider-area-style2 post_slider_'.$block['id'] .' padma-post-slider-area'.$block['id'].'">';
		$html .='<div id="tppost-main-slider-'.$block['id'].'" class="carousel-style2 owl-carousel tppost-main-slider">';
		foreach ($posts as $key => $post) {

			$content = $this->prepare_content($block,$post);
			
			$html .= '<div class="post_slider_'.$block['id'].'_style_two carousel-style2-item ">';					  
				

				// Featured image
				$html .= '<div class="post_slider_'.$block['id'].'_style_img carousel-style2-item-image">';
				if ( has_post_thumbnail( $post->ID ) ) {

					$html .= '<div class="tps-slider-thumb-style2">';
					$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'">'.get_the_post_thumbnail( $post->ID, 'post-slider-thumb', array( 'class' => "img-responsive" ) ).'</a>';
					$html .= '</div>';

				}
				$html .= '</div>';

				$html .= '<h5 class="post_slider_'.$block['id'].'_style_title carousel-style2-item-title">
							<a href="'.esc_url(get_the_permalink( $post->ID )).'">'.esc_attr(get_the_title( $post->ID )).'</a>
						</h5>';


				// Author
				$author = $this->authors[ $post->post_author ];
				$html .= '<ul class="post_slider_'.$block['id'].'_style_bar carousel-style2-item-meta">
							<li class="post_slider_'.$block['id'].'_style_post_date">
							<i class="fa fa-calendar"></i><span class="carousel-style2-item-date">'.get_the_date( 'Y-m-d', $post->ID ).'</span></li>
							<li class="post_slider_'.$block['id'].'_style_post_author">
							<i class="fa fa-user"></i>
							<a class="carousel-style2-item-author" href="'.get_author_posts_url( $post->post_author, get_the_author_meta( 'user_nicename' ) ).'">'.$author.'</a></li>
						</ul>';

				if( $content !== false ) {					
					$html .= '<div class="carousel-style2-item-description">'.$content.'</div>';
				}
				
				$html .= '</div>';					
		}
		$html .='</div></div><div class="clearfix"></div>';
	
		return $html;
	}

	private function render_style3($block,$posts){
				
		$read_more_label = ( !empty($block['settings']['read-more-label']) ) ? $block['settings']['read-more-label']: 'More';
		
		$html = '';
		$html .='<div class="padma-post-slider-area-style3 post_slider_'.$block['id'] .' padma-post-slider-area'.$block['id'].'">';
		$html .='<div id="tppost-main-slider-'.$block['id'].'" class="carousel-style3 owl-carousel tppost-main-slider">';
	
		foreach ($posts as $key => $post) {

			$content = $this->prepare_content($block,$post);

			$html .= '<div class="post_slider_'.$block['id'].'_style3 carousel-style3-item ">';
				
				// Featured image
				$html .= '<div class="post_slider_'.$block['id'].'_style3_img  carousel-style3-item-image">';
				if ( has_post_thumbnail( $post->ID ) ) {
					$html .= '<div class="tps-slider-thumb tps-slider-thumb-style3">';
					$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'">'.get_the_post_thumbnail( $post->ID, 'post-slider-thumb', array( 'class' => "img-responsive" ) ).'</a>';
					$html .= '</div>';
				}
				$html .='</div>';

				// Title
				$html .='<h5 class="post_slider_'.$block['id'].'_style3_title carousel-style3-item-title"><a href="'.esc_url(get_the_permalink( $post->ID )).'">'.esc_attr(get_the_title( $post->ID )).'</a></h5>';

				$author = $this->authors[ $post->post_author ];
				$html .= '<ul class="post_slider_'.$block['id'].'_style3_bars carousel-style3-item-meta">
						<li class="post_slider_'.$block['id'].'_style3_dates"><span class="carousel-style3-item-date">'.get_the_date('Y-m-d',  $post->ID ).'</span></li>
						<li class="post_slider_'.$block['id'].'_style3_autors"><a class="carousel-style3-item-author" href="'.get_author_posts_url( $post->ID, get_the_author_meta( 'user_nicename' ) ).'">'.$author.'</a></li>
					</ul>';

				if( $content !== false ){
					$html .= '<div class="carousel-style3-item-description">'.$content.'</div>';

				}

				$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'" class="post_slider_'.$block['id'].'_style3_p_readmores button carousel-style3-item-button">'.$read_more_label.'</a>';

				$html .= '</div>';
				
		}
		$html .='</div></div><div class="clearfix"></div>';
	
		return $html;
	}

	private function render_style4($block,$posts){

		$html = '';
		$html .='<div class="padma-post-slider-area-style4 post_slider_'.$block['id'] .' padma-post-slider-area'.$block['id'].'">';
		$html .='<div id="tppost-main-slider-'.$block['id'].'" class="carousel-style4 owl-carousel tppost-main-slider">';
	
		foreach ($posts as $key => $post) {

			$content = $this->prepare_content($block,$post);

			$clases = 'presentacion';
			$cats = get_the_category( $post->ID );
			foreach ( $cats as $cat ){
				$clases .= ' ' . $cat->name;
			}

			if( $content !== false )
				$html .= '<div class="carousel-style4-item-description '.$clases.'">'.$content.'</div>';

				
		}
		$html .='</div></div><div class="clearfix"></div>';
	
		return $html;
	}

	private function render_style5($block,$posts){

		$read_more_label = ( !empty($block['settings']['read-more-label']) ) ? $block['settings']['read-more-label']: 'More';		

		$html = '';
		$html .='<div class="padma-post-slider-area-style5 post_slider_'.$block['id'] .' padma-post-slider-area'.$block['id'].'">';
		$html .='<div id="tppost-main-slider-'.$block['id'].'" class="carousel-style5 owl-carousel tppost-main-slider">';
	
		foreach ($posts as $key => $post) {

			$content = $this->prepare_content($block,$post);

			$html .= '<div class="post_slider_'.$block['id'].'_style5 carousel-style5-item ">';
				
				// Featured image
				$html .= '<div class="post_slider_'.$block['id'].'_style5_img  carousel-style5-item-image">';
				if ( has_post_thumbnail( $post->ID ) ) {

					$postIMG = get_the_post_thumbnail_url( $post->ID );

					$html .= '<div class="tps-slider-thumb tps-slider-thumb-style5">';
					$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'" style="background-image:url('.$postIMG.')"></a>';
					$html .= '</div>';
				}
				$html .='</div>';

				// Title
				$html .='<h5 class="post_slider_'.$block['id'].'_style5_title carousel-style5-item-title"><a href="'.esc_url(get_the_permalink( $post->ID )).'">'.esc_attr(get_the_title( $post->ID )).'</a></h5>';
				
				
				if( $content !== false ){
					$html .= '<div class="tps-slider-post-content_style5 carousel-style5-item-description">'.$content.'</div>';
				}

				// Button
				$html .= '<div class="tps-slider-post-link_style5">';
				$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'" class="post_slider_'.$block['id'].'_style5_p_readmores button carousel-style5-item-button">'.$read_more_label.'</a>';
				$html .= '</div>';

			$html .= '</div>';
				
		}
		$html .='</div></div><div class="clearfix"></div>';
	
		return $html;
	}

	private function render_style6($block,$posts){
				
		$html = '';
		$html .='<div class="padma-post-slider-area-style6 post_slider_'.$block['id'] .' padma-post-slider-area'.$block['id'].'">';
		$html .='<div id="tppost-main-slider-'.$block['id'].'" class="carousel-style6 owl-carousel tppost-main-slider">';
	
		foreach ($posts as $key => $post) {

			$html .= '<div class="post_slider_'.$block['id'].'_style6 carousel-style6-item ">';
				
				// Featured image
				$html .= '<div class="post_slider_'.$block['id'].'_style6_img  carousel-style6-item-image">';
				if ( has_post_thumbnail( $post->ID ) ) {

					$postIMG = get_the_post_thumbnail_url( $post->ID );

					$html .= '<div class="tps-slider-thumb tps-slider-thumb-style6">';
					$html .= '<a href="'.esc_url(get_the_permalink( $post->ID )).'" style="background-image:url('.$postIMG.'); background-size: cover;"></a>';
					$html .= '</div>';
				}
				$html .='</div>';

				// Title
				$html .='<h5 class="post_slider_'.$block['id'].'_style6_title carousel-style6-item-title"><a href="'.esc_url(get_the_permalink( $post->ID )).'">'.esc_attr(get_the_title( $post->ID )).'</a></h5>';

			$html .= '</div>';
				
		}
		$html .='</div></div><div class="clearfix"></div>';
	
		return $html;
	}

	public static function dynamic_js($block_id, $block = false) {	

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		$auto_play 		 = ( !empty($block['settings']['auto_play']) ) ? $block['settings']['auto_play']: 'true';
		$show_items 	 = ( !empty($block['settings']['show_items']) ) ? $block['settings']['show_items']: 3;
		$show_pagination = ( !empty($block['settings']['show_pagination']) ) ? $block['settings']['show_pagination']: 'true';

		return 'jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$block['id'].'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,'.$show_items.'],
					itemsDesktopSmall : [979,'.$show_items.'],
					itemsTablet : [979,'.$show_items.'],
					itemsTabletSmall : [979,'.$show_items.'],
					itemsMobile : [979,'.$show_items.'],
					navigation : false,
					navigationText : ["‹","›"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});';
	}

	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = \PadmaBlocksData::get_block($block_id);

		$style 				= ( !empty($block['settings']['slider-style']) ) ? $block['settings']['slider-style']: 'style1';
		$focus_effect 		= ( !empty($block['settings']['focus-effect']) ) ? $block['settings']['focus-effect']: 'true';
		$focus_effect_color 	= ( !empty($block['settings']['focus-effect-color']) ) ? $block['settings']['focus-effect-color']: '#3398db';

		$css = '.post_slider_'.$block['id'] . ' .tps-slider-thumb img{ 
			width: 100%;
			height: 100%;
	    	object-fit: contain;
    	}';
		switch ($style) {

			case 'style1':
					$css .= '.pps_single_slider_items-'.$block['id'].' {
						border-bottom: medium none;
						box-shadow: none;
						margin: 0 10px;
						transition: all 0.4s ease-in-out 0s;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_post_images-'.$block['id'].'{
						position: relative;
						overflow: hidden;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_post_images-'.$block['id'].':before{
						content: "";
						width: 100%;
						height: 100%;
						position: absolute;
						top: 0;
						left: 0;
						background: rgba(0, 0, 0, 0);
						transition: all 0.4s linear 0s;
					}
					.pps_single_slider_items-'.$block['id'].':hover .pps_single_slider_items_post_images-'.$block['id'].':before{
						background: rgba(0, 0, 0, 0.6);
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_post_images-'.$block['id'].' img{
						width: 100%;
						height: auto;
					}
					.pps_single_slider_items-'.$block['id'].' img {
					  border-radius: 0;
					  box-shadow: none;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_category-'.$block['id'].' {
						width: 100%;
						font-size: 16px;
						color: #fff;
						line-height: 11px;
						text-align: center;
						text-transform: capitalize;
						padding: 11px 0;
						background: #ff9412;
						position: absolute;
						bottom: 0;
						left: -100%;
						transition: all 0.5s ease-in-out 0s;
					}
					.pps_single_slider_items-'.$block['id'].':hover .pps_single_slider_items_category-'.$block['id'].'{
						left: 0;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_item_reviews-'.$block['id'].'{
						padding: 20px 20px;
						background: #fff;
						position: relative;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_item_post_title-'.$block['id'].'{
						margin: 0;
					}
					.pps_single_slider_item_reviews-'.$block['id'].' h3.pps_single_slider_item_post_title-'.$block['id'].' {
					  font-size: 15px;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_item_post_title-'.$block['id'].' a{
						border-bottom: medium none;
						color: #ff9412;
						display: inline-block;
						font-size: 15px;
						font-weight: normal;
						letter-spacing: 2px;
						margin-bottom: 25px;
						text-decoration: none;
						transition: all 0.3s linear 0s;
						box-shadow: none;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_item_post_title-'.$block['id'].' a:hover{
						text-decoration: none;
						color: #555;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_item_description-'.$block['id'].'{
						font-size: 15px;
						color: #555;
						line-height: 26px;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_category-'.$block['id'].' > a {
					  border: medium none;
					  box-shadow: none;
					  color: #000;
					  margin-right: 8px;
					  text-decoration: none;
					}
					.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_category-'.$block['id'].' > a:hover {
					  color: #fff;
					}
					.pps_single_slider_item_reviews-'.$block['id'].' .pps_single_slider_admin_description-'.$block['id'].'{
						margin-top: 20px;
					}
					.pps_single_slider_admin_description-'.$block['id'].' span{
						display: inline-block;
						font-size: 14px;
					}
					.pps_single_slider_admin_description-'.$block['id'].' span i{
						margin-right: 5px;
						color: #999;
					}
					.pps_single_slider_admin_description-'.$block['id'].' span a{
						color: #999;
						text-transform: uppercase;
					}
					.pps_single_slider_admin_description-'.$block['id'].' span a:hover{
						text-decoration: none;
						color: #ff9412;
					}
					.pps_single_slider_admin_description-'.$block['id'].' span.comments{
						float: right;
					}
					@media only screen and (max-width: 359px) {
						.pps_single_slider_items-'.$block['id'].' .pps_single_slider_items_category-'.$block['id'].'{ font-size: 13px; }
					}';
				break;
			
			case 'style2':
				$css .= '.post_slider_'.$block['id'].'_style_two{
						padding: 0 15px;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_img{
						position: relative;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_img > a{
						display:block;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_img img{
						border-radius: 0;
						box-shadow: none;
						height: auto;
						width: 100%;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_img:hover:before{
						content: "";
						position: absolute;
						width: 100%;
						height:100%;
						background-color: rgba(220, 0, 90, 0.6);
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_img:hover:after{
						opacity: 1;
						transform: scale(1);
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_title{
						margin-bottom: 10px;
						margin-top: 10px;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_title > a{
						color:#222;
						display: block;
						font-size: 17px;
						font-weight: 600;
						text-transform: uppercase;
						text-decoration:none;
						border-bottom:none;
						box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_title > a:hover{
						text-decoration: none;
						color:#dc005a;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_bar{
						padding: 0;
						list-style: none;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_bar > li{
						display: inline-block;
						margin: 0 15px 0 0;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_date,
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_author,
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_author > a{
						color:#8f8f8f;
						font-size: 12px;
						margin-right: 16px;
						text-transform: uppercase;
						font-style: italic;
						text-decoration:none;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_date > i,
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_author > i{
						margin-right: 5px;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_author > a:hover{
						color:#dc005a;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_description{
						color:#8f8f8f;
						font-size: 14px;
						line-height: 24px;
						padding-top: 5px;
					}
					.post_slider_'.$block['id'].'_style_two .post_slider_'.$block['id'].'_style_post_description:before{
						content: "";
						display: block;
						border-top: 4px solid #dc005a;
						padding-bottom: 12px;
						width: 50px;
					}';
				break;

			case 'style3':
				$css .= '.post_slider_'.$block['id'].'_style3{
						border: 1px solid #eee;
						padding: 20px;
						margin: 0 15px;
						position: relative;
					}
					.post_slider_'.$block['id'].'_style3:before{
						content: "";
						border-top:1px solid transparent;
						position: absolute;
						top:0;
						left:0;
						width: 100%;
						transition:all 0.3s ease-in-out 0s;
					}';

				if( $focus_effect == 'true' ){
					$css .= '
						.post_slider_'.$block['id'].'_style3:hover:before{
							border-top: 1px solid '.$focus_effect_color.';
						}
						.post_slider_'.$block['id'].'_style3:hover{
							border-top: 1px solid '.$focus_effect_color.';
						}';
				}

				$css .= '					
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_img > img{
						width: 100%;
						height:auto;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_title > a{
						font-size: 20px;
						text-transform: capitalize;
						color:#333;
						transition:all 0.3s ease-in-out 0s;
						text-decoration:none;
						border-bottom:none;
						box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_title > a:hover{
						text-decoration: none;
						color:#3398db;
						text-decoration:none;
					}
					.tps-slider-thumb-style3 a img {
					  border-radius: 0;
					  box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_bars{
						padding: 0;
						list-style: none;
						overflow: hidden;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_bars > li{
						border-right: 1px solid #999;
						display: inline-block;
						float: left;
						margin: 0;
						padding: 0 10px;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_bars > li:first-child{
						padding: 0 10px 0 0;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_bars > li:last-child{
						border: 0px none;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_dates,
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_autors,
					.post_slider_'.$block['id'].'_style3 .comment{
						color:#3398db;
						text-transform: uppercase;
						font-size: 11px;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_autors > a,
					.post_slider_'.$block['id'].'_style3 .comment > a,
					.post_slider_'.$block['id'].'_style3 .comment > i{
						color:#999;
						transition:all 0.3s ease-in-out 0s;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_autors > a:hover,
					.post_slider_'.$block['id'].'_style3 .comment > a:hover{
						text-decoration: none;
						color:#333;
					}
					.post_slider_'.$block['id'].'_style3 .comment > i{
						margin-right: 8px;
						font-size: 15px;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_p_description{
						line-height: 1.7;
						color:#666;
						font-size: 13px;
						margin-bottom: 20px;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_p_readmores{
						display: inline-block;
						padding: 10px 35px;
						background: #3398db;
						color: #ffffff;
						border-radius: 5px;
						font-size: 15px;
						font-weight: 900;
						letter-spacing: 1px;
						line-height: 20px;
						margin-bottom: 5px;
						text-transform: uppercase;
						transition:all 0.3s ease-in-out 0s;
						text-decoration:none;
					}
					.post_slider_'.$block['id'].'_style3 .post_slider_'.$block['id'].'_style3_p_readmores:hover{
						text-decoration: none;
						color:#fff;
						background: #333;
					}
					@media only screen and (max-width: 360px) {
						.post_slider_'.$block['id'].'_style3_bars > li:last-child{
							margin-top: 8px;
							padding: 0;
						}
					}';
				break;
			
			case 'style4':
				$css .= '.post_slider_'.$block['id'].'_style_four{
						padding: 0 15px;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_img{
						position: relative;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_img > a{
						display:block;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_img img{
						border-radius: 0;
						box-shadow: none;
						height: auto;
						width: 100%;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_img:hover:before{
						content: "";
						position: absolute;
						width: 100%;
						height:100%;
						background-color: rgba(220, 0, 90, 0.6);
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_img:hover:after{
						opacity: 1;
						transform: scale(1);
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_title{
						margin-bottom: 10px;
						margin-top: 10px;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_title > a{
						color:#222;
						display: block;
						font-size: 17px;
						font-weight: 600;
						text-transform: uppercase;
						text-decoration:none;
						border-bottom:none;
						box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_title > a:hover{
						text-decoration: none;
						color:#dc005a;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_bar{
						padding: 0;
						list-style: none;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_bar > li{
						display: inline-block;
						margin: 0 15px 0 0;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_date,
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_author,
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_author > a{
						color:#8f8f8f;
						font-size: 12px;
						margin-right: 16px;
						text-transform: uppercase;
						font-style: italic;
						text-decoration:none;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_date > i,
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_author > i{
						margin-right: 5px;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_author > a:hover{
						color:#dc005a;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_description{
						color:#8f8f8f;
						font-size: 14px;
						line-height: 24px;
						padding-top: 5px;
					}
					.post_slider_'.$block['id'].'_style_four .post_slider_'.$block['id'].'_style_post_description:before{
						content: "";
						display: block;
						border-top: 4px solid #dc005a;
						padding-bottom: 12px;
						width: 50px;
					}';
				break;

			case 'style5':
				$css .= '.post_slider_'.$block['id'].'_style5{
						border: 1px solid #eee;
						padding: 0px;
						margin: 0 15px;
						position: relative;
					}
					.post_slider_'.$block['id'].'_style5:before{
						content: "";
						border-top:1px solid transparent;
						position: absolute;
						top:0;
						left:0;
						width: 100%;
						transition:all 0.3s ease-in-out 0s;
					}';


				if( $focus_effect == 'true' ){
					$css .= '
						.post_slider_'.$block['id'].'_style5:hover:before{
							border-top: 1px solid '.$focus_effect_color.';
						}
						.post_slider_'.$block['id'].'_style5:hover{
							border-top: 1px solid '.$focus_effect_color.';
						}';
				}
				$css .= '
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_img > img{
						width: 100%;
						height:auto;						
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_title > a{
						font-size: 20px;
						text-transform: capitalize;
						color:#333;
						transition:all 0.3s ease-in-out 0s;
						text-decoration:none;
						border-bottom:none;
						box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_title > a:hover{
						text-decoration: none;
						color:#3398db;
						text-decoration:none;
					}
					.tps-slider-thumb-style3 a img {
					  border-radius: 0;
					  box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_bars{
						padding: 0;
						list-style: none;
						overflow: hidden;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_bars > li{
						border-right: 1px solid #999;
						display: inline-block;
						float: left;
						margin: 0;
						padding: 0 10px;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_bars > li:first-child{
						padding: 0 10px 0 0;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_bars > li:last-child{
						border: 0px none;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_dates,
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_autors,
					.post_slider_'.$block['id'].'_style5 .comment{
						color:#3398db;
						text-transform: uppercase;
						font-size: 11px;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_autors > a,
					.post_slider_'.$block['id'].'_style5 .comment > a,
					.post_slider_'.$block['id'].'_style5 .comment > i{
						color:#999;
						transition:all 0.3s ease-in-out 0s;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_autors > a:hover,
					.post_slider_'.$block['id'].'_style5 .comment > a:hover{
						text-decoration: none;
						color:#333;
					}
					.post_slider_'.$block['id'].'_style5 .comment > i{
						margin-right: 8px;
						font-size: 15px;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_p_description{
						line-height: 1.7;
						color:#666;
						font-size: 13px;
						margin-bottom: 20px;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_p_readmores{
						display: inline-block;
						padding: 10px 35px;
						background: #3398db;
						color: #ffffff;
						border-radius: 5px;
						font-size: 15px;
						font-weight: 900;
						letter-spacing: 1px;
						line-height: 20px;
						margin-bottom: 5px;
						text-transform: uppercase;
						transition:all 0.3s ease-in-out 0s;
						text-decoration:none;
					}
					.post_slider_'.$block['id'].'_style5 .post_slider_'.$block['id'].'_style5_p_readmores:hover{
						text-decoration: none;
						color:#fff;
						background: #333;
					}
					.tps-slider-thumb-style5{
					}
					.tps-slider-thumb-style5 a{
						display: block;
						width: 100%;
						height: 235px;
						background-repeat: no-repeat;
						overflow: hidden;
						border-bottom: 4px solid #48c7e7;
						margin-bottom: 35px;
						background-size: cover;
						background-position: center;
					}
					.post_slider_'.$block['id'].'_style5_title{
						text-align: center;
	    				margin-bottom: 20px;
					}
					.tps-slider-post-content_style5{
						margin: auto;
						padding-left: 15%;
						padding-right: 15%;
						margin-bottom: 15px;
						text-align: center;
						border-bottom: 1px solid #eee;
						padding-bottom: 15px;
						min-height: 90px;
					}
					.tps-slider-post-link_style5{
						text-align: center;
					}
					div.owl-item > div.post_slider_'.$block['id'].'_style5 div.tps-slider-post-link_style5 > a.post_slider_'.$block['id'].'_style5_p_readmores{
						text-align: center;
						background: transparent;
						font-size: 16px !important;
						color: #48c7e7;
					}
					.owl-theme .owl-controls .owl-buttons div{
						color: #000;
						display: inline-block;
						zoom: 1;
						margin: 5px;
						padding: 3px 10px;
						font-size: 25px;
						-webkit-border-radius: 30px;
						-moz-border-radius: 30px;
						border-radius: 0;
						background: #fafafa;
						filter: Alpha(Opacity=50);
						opacity: 0.5;
						border: 1px solid #eee;
						margin-top: 50px;
					}
					@media only screen and (max-width: 360px) {
						.post_slider_'.$block['id'].'_style5_bars > li:last-child{
							margin-top: 8px;
							padding: 0;
						}
					}';
				break;
			
			case 'style6':
				$css .= '.post_slider_'.$block['id'].'_style6{
						border: 0;
						padding: 0px;
						margin: auto;
						position: relative;
						max-width: 190px;
					}
					.post_slider_'.$block['id'].'_style6:before{
						content: "";
						border-top:1px solid transparent;
						position: absolute;
						top:0;
						left:0;
						width: 100%;
						transition:all 0.3s ease-in-out 0s;
					}';

				if( $focus_effect == 'true' ){
					$css .= '
						.post_slider_'.$block['id'].'_style6:hover:before{
							border-top: 1px solid '.$focus_effect_color.';
						}
						.post_slider_'.$block['id'].'_style6:hover{
							border-top: 1px solid '.$focus_effect_color.';
						}';
				}
				$css .= '
					
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_img > img{
						width: 100%;
						height:auto;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_title > a{
						font-size: 15px;
						text-transform: capitalize;
						color:#333;
						transition:all 0.3s ease-in-out 0s;
						text-decoration:none;
						border-bottom:none;
						box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_title > a:hover{
						text-decoration: none;
						color:#3398db;
						text-decoration:none;
					}
					.tps-slider-thumb-style3 a img {
					  border-radius: 0;
					  box-shadow: none;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_bars{
						padding: 0;
						list-style: none;
						overflow: hidden;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_bars > li{
						border-right: 1px solid #999;
						display: inline-block;
						float: left;
						margin: 0;
						padding: 0 10px;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_bars > li:first-child{
						padding: 0 10px 0 0;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_bars > li:last-child{
						border: 0px none;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_dates,
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_autors,
					.post_slider_'.$block['id'].'_style6 .comment{
						color:#3398db;
						text-transform: uppercase;
						font-size: 11px;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_autors > a,
					.post_slider_'.$block['id'].'_style6 .comment > a,
					.post_slider_'.$block['id'].'_style6 .comment > i{
						color:#999;
						transition:all 0.3s ease-in-out 0s;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_autors > a:hover,
					.post_slider_'.$block['id'].'_style6 .comment > a:hover{
						text-decoration: none;
						color:#333;
					}
					.post_slider_'.$block['id'].'_style6 .comment > i{
						margin-right: 8px;
						font-size: 15px;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_p_description{
						line-height: 1.7;
						color:#666;
						font-size: 13px;
						margin-bottom: 20px;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_p_readmores{
						display: inline-block;
						padding: 10px 35px;
						background: #3398db;
						color: #ffffff;
						border-radius: 5px;
						font-size: 15px;
						font-weight: 900;
						letter-spacing: 1px;
						line-height: 20px;
						margin-bottom: 5px;
						text-transform: uppercase;
						transition:all 0.3s ease-in-out 0s;
						text-decoration:none;
					}
					.post_slider_'.$block['id'].'_style6 .post_slider_'.$block['id'].'_style6_p_readmores:hover{
						text-decoration: none;
						color:#fff;
						background: #333;
					}
					.tps-slider-thumb-style6{
						min-height: 250px;
					}
					.tps-slider-thumb-style6 a{
						display: block;
						width: 100%;
						height: 250px;
						background-repeat: no-repeat;
						overflow: hidden;
						margin-bottom: 35px;
						background-position: center center;

					}
					.post_slider_'.$block['id'].'_style6_title{
						text-align: center;
	    				margin-bottom: 20px;
					}
					.tps-slider-post-content_style6{
						margin: auto;
						padding-left: 15%;
						padding-right: 15%;
						margin-bottom: 15px;
						text-align: center;
						border-bottom: 1px solid #eee;
						padding-bottom: 15px;
						min-height: 90px;
					}
					.tps-slider-post-link_style6{
						text-align: center;
					}
					div.owl-item > div.post_slider_'.$block['id'].'_style6 div.tps-slider-post-link_style6 > a.post_slider_'.$block['id'].'_style6_p_readmores{
						text-align: center;
						background: transparent;
						font-size: 16px !important;
						color: #48c7e7;
					}
					.padma-post-slider-area'.$block['id'].'{
						padding-right: 100px;
						padding-left: 100px;
					}
					.owl-theme .owl-controls .owl-buttons div{
						color: #000;
						display: inline-block;
						zoom: 1;
						margin: 5px;
						padding: 3px 10px;
						font-size: 25px;
						-webkit-border-radius: 30px;
						-moz-border-radius: 30px;
						border-radius: 0;
						background: #fafafa;
						filter: Alpha(Opacity=50);
						opacity: 0.5;
						border: 1px solid #eee;
						margin-top: 50px;
					}
					@media only screen and (max-width: 360px) {
						.post_slider_'.$block['id'].'_style6_bars > li:last-child{
							margin-top: 8px;
							padding: 0;
						}
					}';
				break;
			
			default:
				# code...
				break;
		}
		

		return $css;
	}
	
}