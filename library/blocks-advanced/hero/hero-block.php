<?php
/**
 * Hero Block
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

class PadmaVisualElementsBlockHero extends \PadmaBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;

	public function __construct() {
		$this->id            = 'visual-elements-hero';
		$this->name          = __( 'Hero', 'padma' );
		$this->options_class = 'Padma_Advanced\PadmaVisualElementsBlockHeroOptions';
		$this->description   = __( 'Großer Intro-Bereich mit Hintergrundbild, Text und Call-to-Action.', 'padma' );
		$this->categories    = array( 'content' );
	}

	public function init() {
		return function_exists( 'padma_render_hero' );
	}

	public function setup_elements() {
		$this->register_block_element(array(
			'id'       => 'hero',
			'name'     => __( 'Hero', 'padma' ),
			'selector' => '.su-hero',
			'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'sizes', 'overflow', 'advanced', 'transition', 'outlines', 'filter' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-media',
			'parent'   => 'hero',
			'name'     => __( 'Hero Hintergrund', 'padma' ),
			'selector' => '.su-hero-media',
			'properties' => array( 'background', 'filter', 'transition', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-overlay',
			'parent'   => 'hero',
			'name'     => __( 'Hero Overlay', 'padma' ),
			'selector' => '.su-hero-overlay',
			'properties' => array( 'background', 'filter', 'transition', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-content-wrap',
			'parent'   => 'hero',
			'name'     => __( 'Hero Inhaltsbereich', 'padma' ),
			'selector' => '.su-hero-content',
			'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'fonts', 'advanced', 'transition', 'outlines' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-subtitle',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Hero Untertitel', 'padma' ),
			'selector' => '.su-hero-subtitle',
			'properties' => array( 'fonts', 'padding', 'background', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-title',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Hero Titel', 'padma' ),
			'selector' => '.su-hero-title',
			'properties' => array( 'fonts', 'padding', 'background', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-text',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Hero Text', 'padma' ),
			'selector' => '.su-hero-text',
			'properties' => array( 'fonts', 'padding', 'background', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-text-links',
			'parent'   => 'hero-text',
			'name'     => __( 'Hero Text Links', 'padma' ),
			'selector' => '.su-hero-text a',
			'properties' => array( 'fonts', 'background', 'advanced' ),
			'states'   => array(
				'Hover'   => '.su-hero-text a:hover',
				'Clicked' => '.su-hero-text a:active',
			),
		));

		$this->register_block_element(array(
			'id'       => 'hero-actions',
			'parent'   => 'hero-content-wrap',
			'name'     => __( 'Hero Aktionen', 'padma' ),
			'selector' => '.su-hero-actions',
			'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-button',
			'parent'   => 'hero-actions',
			'name'     => __( 'Hero Button', 'padma' ),
			'selector' => '.su-hero-actions .su-button',
			'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'fonts', 'advanced', 'transition', 'outlines', 'filter' ),
			'states'   => array(
				'Hover'   => '.su-hero-actions .su-button:hover',
				'Clicked' => '.su-hero-actions .su-button:active',
			),
		));

		$this->register_block_element(array(
			'id'       => 'hero-button-text',
			'parent'   => 'hero-button',
			'name'     => __( 'Hero Button Text', 'padma' ),
			'selector' => '.su-hero-actions .su-button span',
			'properties' => array( 'fonts', 'padding', 'background', 'advanced' ),
			'states'   => array(
				'Hover'   => '.su-hero-actions .su-button:hover span',
				'Clicked' => '.su-hero-actions .su-button:active span',
			),
		));

		$this->register_block_element(array(
			'id'       => 'hero-button-description',
			'parent'   => 'hero-button-text',
			'name'     => __( 'Hero Button Beschreibung', 'padma' ),
			'selector' => '.su-hero-actions .su-button span small',
			'properties' => array( 'fonts', 'padding', 'background', 'advanced' ),
		));

		$this->register_block_element(array(
			'id'       => 'hero-button-icon',
			'parent'   => 'hero-button-text',
			'name'     => __( 'Hero Button Icon', 'padma' ),
			'selector' => '.su-hero-actions .su-button span i, .su-hero-actions .su-button span img',
			'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'sizes', 'filter', 'advanced' ),
		));
	}

	public function content( $block ) {
		$args = array(
			'title'             => parent::get_setting( $block, 'title' ),
			'subtitle'          => parent::get_setting( $block, 'subtitle' ),
			'background_image'  => parent::get_setting( $block, 'background_image' ),
			'button_text'       => parent::get_setting( $block, 'button_text' ),
			'button_url'        => parent::get_setting( $block, 'button_url' ),
			'button_target'     => parent::get_setting( $block, 'button_target', 'self' ),
			'use_inline_styles' => false,
			'use_button_style'  => false,
		);

		$content = parent::get_setting( $block, 'content' );

		echo padma_render_hero( $args, $content );
	}
}