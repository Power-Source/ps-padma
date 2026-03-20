<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockShortcodeBlock extends \PadmaBlockAPI {

    public $id 				= 'shortcode-block';
    public $name 			= 'Plugin Shortcodes';
	public $options_class 	= 'Padma_Advanced\\PadmaVisualElementsBlockShortcodeBlockOptions';
	public $description 	= 'Bindet Shortcodes von MarketPress, PowerForm, PS Community und E-Newsletter ein.';
	public $categories 		= array('content','forms');
    
			
	function setup_elements() {

		// ----------------------------------------------------------------
		// MarketPress
		// ----------------------------------------------------------------

		$this->register_block_element(array(
			'id'       => 'mp-product-list',
			'name'     => __( 'MP Produktliste', 'padma' ),
			'selector' => '.mp_product_list',
		));

		$this->register_block_element(array(
			'id'       => 'mp-product',
			'name'     => __( 'MP Produkt', 'padma' ),
			'selector' => '.mp_product',
		));

		$this->register_block_element(array(
			'id'       => 'mp-product-title',
			'name'     => __( 'MP Produkt Titel', 'padma' ),
			'selector' => '.mp_product_name',
			'states'   => array(
				__( 'Hover', 'padma' ) => '.mp_product_name:hover',
			),
		));

		$this->register_block_element(array(
			'id'       => 'mp-product-price',
			'name'     => __( 'MP Preis', 'padma' ),
			'selector' => '.mp_product_price',
		));

		$this->register_block_element(array(
			'id'       => 'mp-product-image',
			'name'     => __( 'MP Produktbild', 'padma' ),
			'selector' => '.mp_product_img',
		));

		$this->register_block_element(array(
			'id'       => 'mp-buy-button',
			'name'     => __( 'MP Kaufen-Button', 'padma' ),
			'selector' => '.mp_buy_form input[type="submit"]',
			'states'   => array(
				__( 'Hover', 'padma' )   => '.mp_buy_form input[type="submit"]:hover',
				__( 'Aktiv', 'padma' )   => '.mp_buy_form input[type="submit"]:active',
			),
		));

		$this->register_block_element(array(
			'id'       => 'mp-cart',
			'name'     => __( 'MP Warenkorb', 'padma' ),
			'selector' => '#mp_cart',
		));

		// ----------------------------------------------------------------
		// PowerForm
		// ----------------------------------------------------------------

		$this->register_block_element(array(
			'id'       => 'powerform-container',
			'name'     => __( 'PowerForm Container', 'padma' ),
			'selector' => '.powerform-form',
		));

		$this->register_block_element(array(
			'id'       => 'powerform-label',
			'name'     => __( 'PowerForm Label', 'padma' ),
			'selector' => '.powerform-form label',
		));

		$this->register_block_element(array(
			'id'       => 'powerform-input',
			'name'     => __( 'PowerForm Eingabefeld', 'padma' ),
			'selector' => '.powerform-form input[type="text"], .powerform-form input[type="email"]',
			'states'   => array(
				__( 'Fokus', 'padma' ) => '.powerform-form input[type="text"]:focus, .powerform-form input[type="email"]:focus',
			),
		));

		$this->register_block_element(array(
			'id'       => 'powerform-textarea',
			'name'     => __( 'PowerForm Textfeld', 'padma' ),
			'selector' => '.powerform-form textarea',
			'states'   => array(
				__( 'Fokus', 'padma' ) => '.powerform-form textarea:focus',
			),
		));

		$this->register_block_element(array(
			'id'       => 'powerform-submit',
			'name'     => __( 'PowerForm Absenden-Button', 'padma' ),
			'selector' => '.powerform-form input[type="submit"]',
			'states'   => array(
				__( 'Hover', 'padma' ) => '.powerform-form input[type="submit"]:hover',
				__( 'Aktiv', 'padma' ) => '.powerform-form input[type="submit"]:active',
			),
		));

		// ----------------------------------------------------------------
		// PS Community
		// ----------------------------------------------------------------

		$this->register_block_element(array(
			'id'       => 'community-groups',
			'name'     => __( 'Community Gruppenliste', 'padma' ),
			'selector' => '.cpc-groups',
		));

		$this->register_block_element(array(
			'id'       => 'community-group-item',
			'name'     => __( 'Community Gruppen-Eintrag', 'padma' ),
			'selector' => '.cpc-group-item',
		));

		$this->register_block_element(array(
			'id'       => 'community-group-title',
			'name'     => __( 'Community Gruppen-Titel', 'padma' ),
			'selector' => '.cpc-group-title',
			'states'   => array(
				__( 'Hover', 'padma' ) => '.cpc-group-title:hover',
			),
		));

		$this->register_block_element(array(
			'id'       => 'community-join-button',
			'name'     => __( 'Community Beitreten-Button', 'padma' ),
			'selector' => '.cpc-join-button',
			'states'   => array(
				__( 'Hover', 'padma' ) => '.cpc-join-button:hover',
			),
		));

		$this->register_block_element(array(
			'id'       => 'community-leave-button',
			'name'     => __( 'Community Verlassen-Button', 'padma' ),
			'selector' => '.cpc-leave-button',
			'states'   => array(
				__( 'Hover', 'padma' ) => '.cpc-leave-button:hover',
			),
		));

		$this->register_block_element(array(
			'id'       => 'community-members',
			'name'     => __( 'Community Mitgliederliste', 'padma' ),
			'selector' => '.cpc-members',
		));

		$this->register_block_element(array(
			'id'       => 'community-member-item',
			'name'     => __( 'Community Mitglied', 'padma' ),
			'selector' => '.cpc-member-item',
		));

		// ----------------------------------------------------------------
		// E-Newsletter
		// ----------------------------------------------------------------

		$this->register_block_element(array(
			'id'       => 'enewsletter-widget-container',
			'name'     => __( 'Newsletter Widget Container', 'padma' ),
			'selector' => '.e-newsletter-widget',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-message',
			'name'     => __( 'Newsletter Nachricht', 'padma' ),
			'selector' => '.e-newsletter-widget #message',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-form',
			'name'     => __( 'Newsletter Formular', 'padma' ),
			'selector' => '.e-newsletter-widget form',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-label',
			'name'     => __( 'Newsletter Label', 'padma' ),
			'selector' => '.e-newsletter-widget label',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-input-text',
			'name'     => __( 'Newsletter Eingabefeld', 'padma' ),
			'selector' => '.e-newsletter-widget input[type="text"]',
			'states'   => array(
				__( 'Fokus', 'padma' ) => '.e-newsletter-widget input[type="text"]:focus',
			),
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-email-input',
			'name'     => __( 'Newsletter E-Mail-Feld', 'padma' ),
			'selector' => '.e-newsletter-widget #e_newsletter_email',
			'states'   => array(
				__( 'Fokus', 'padma' ) => '.e-newsletter-widget #e_newsletter_email:focus',
			),
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-name-input',
			'name'     => __( 'Newsletter Name-Feld', 'padma' ),
			'selector' => '.e-newsletter-widget #e_newsletter_name',
			'states'   => array(
				__( 'Fokus', 'padma' ) => '.e-newsletter-widget #e_newsletter_name:focus',
			),
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-groups-heading',
			'name'     => __( 'Newsletter Gruppen Überschrift', 'padma' ),
			'selector' => '.e-newsletter-widget h3',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-groups-list',
			'name'     => __( 'Newsletter Gruppen Liste', 'padma' ),
			'selector' => '.e-newsletter-widget .subscribe_groups',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-groups-list-item',
			'name'     => __( 'Newsletter Gruppen Listeneintrag', 'padma' ),
			'selector' => '.e-newsletter-widget .subscribe_groups li',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-checkbox',
			'name'     => __( 'Newsletter Checkbox', 'padma' ),
			'selector' => '.e-newsletter-widget input[type="checkbox"]',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-checkbox-label',
			'name'     => __( 'Newsletter Checkbox Label', 'padma' ),
			'selector' => '.e-newsletter-widget .subscribe_groups label',
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-button',
			'name'     => __( 'Newsletter Button', 'padma' ),
			'selector' => '.e-newsletter-widget input[type="submit"]',
			'states'   => array(
				__( 'Hover', 'padma' )  => '.e-newsletter-widget input[type="submit"]:hover',
				__( 'Aktiv', 'padma' )  => '.e-newsletter-widget input[type="submit"]:active',
			),
		));

		$this->register_block_element(array(
			'id'       => 'enewsletter-link',
			'name'     => __( 'Newsletter Links', 'padma' ),
			'selector' => '.e-newsletter-widget a',
			'states'   => array(
				__( 'Hover', 'padma' ) => '.e-newsletter-widget a:hover',
			),
		));
	}

	function content($block) {

		$shortcodeproduct = parent::get_setting($block, 'shortcode-product-type', 'none');

		if ($shortcodeproduct === 'marketpress') {
			$mp_shortcode  = parent::get_setting($block, 'mp-shortcode-type', 'none');
			$mp_number     = (int) parent::get_setting($block, 'mp-number', 5);
			$mp_product_id = (int) parent::get_setting($block, 'mp-product-id', 0);

			if ( !function_exists('mp_get_setting') ) {
				echo '<p>MarketPress ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $mp_shortcode === 'none' || empty($mp_shortcode) ) {
				echo '<p>Bitte MarketPress Shortcode auswählen.</p>';
				return;
			}

			if ( $mp_shortcode === 'mp_featured_products' || $mp_shortcode === 'mp_popular_products' ) {
				echo do_shortcode( '[' . $mp_shortcode . ' number="' . max(1, $mp_number) . '"]' );
				return;
			}

			if ( $mp_shortcode === 'mp_product' ) {
				if ( $mp_product_id <= 0 ) {
					echo '<p>Bitte eine gültige Produkt-ID für MarketPress eingeben.</p>';
					return;
				}

				echo do_shortcode( '[mp_product product_id="' . $mp_product_id . '"]' );
				return;
			}

			echo do_shortcode( '[' . $mp_shortcode . ']' );
			return;
		}

		if ($shortcodeproduct === 'powerform') {
			$powerform_id = (int) parent::get_setting($block, 'powerform-shortcode', 0);

			if ( !post_type_exists('powerform_forms') ) {
				echo '<p>PowerForm ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $powerform_id <= 0 ) {
				echo '<p>Bitte ein PowerForm-Formular auswählen.</p>';
				return;
			}

			echo do_shortcode( '[powerform_form id="' . $powerform_id . '"]' );
			return;
		}

		if ($shortcodeproduct === 'community') {
			$community_shortcode = parent::get_setting($block, 'community-shortcode-type', 'none');
			$community_group_id  = (int) parent::get_setting($block, 'community-group-id', 0);

			if ( !post_type_exists('cpc_group') ) {
				echo '<p>PS Community ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $community_shortcode === 'none' || empty($community_shortcode) ) {
				echo '<p>Bitte einen PS Community Shortcode auswählen.</p>';
				return;
			}

			$requires_group = in_array($community_shortcode, array(
				'cpc-group-single',
				'cpc-group-members',
				'cpc-group-join-button',
				'cpc-group-leave-button',
			), true);

			if ( $requires_group ) {
				if ( $community_group_id <= 0 ) {
					echo '<p>Bitte eine Gruppe auswählen.</p>';
					return;
				}

				echo do_shortcode( '[' . $community_shortcode . ' group_id="' . $community_group_id . '"]' );
				return;
			}

			echo do_shortcode( '[' . $community_shortcode . ']' );
			return;
		}

		if ($shortcodeproduct === 'e-newsletter') {
			$enewsletter_shortcode_type = parent::get_setting($block, 'enewsletter-shortcode-type', 'none');

			if ( !class_exists('Email_Newsletter') ) {
				echo '<p>E-Newsletter ist nicht installiert oder nicht aktiv.</p>';
				return;
			}

			if ( $enewsletter_shortcode_type === 'none' || empty($enewsletter_shortcode_type) ) {
				echo '<p>Bitte einen E-Newsletter Shortcode auswählen.</p>';
				return;
			}

			// Subscribe-Message Shortcode
			if ( $enewsletter_shortcode_type === 'subscribe-message' ) {
				echo do_shortcode( '[enewsletter_subscribe_message]' );
				return;
			}

			// Unsubscribe-Message Shortcode
			if ( $enewsletter_shortcode_type === 'unsubscribe-message' ) {
				echo do_shortcode( '[enewsletter_unsubscribe_message]' );
				return;
			}

			// Subscribe-Formular mit Optionen
			if ( $enewsletter_shortcode_type === 'subscribe' ) {
				$show_name = parent::get_setting($block, 'enewsletter-show-name', false);
				$show_groups = parent::get_setting($block, 'enewsletter-show-groups', true);
				$subscribe_to_groups = parent::get_setting($block, 'enewsletter-subscribe-to-groups', array());

				// Konvertiere Array zu komma-separiertem String
				$groups_string = '';
				if ( is_array($subscribe_to_groups) && count($subscribe_to_groups) > 0 ) {
					$groups_string = implode(',', $subscribe_to_groups);
				}

				// Baue Shortcode-Attribute
				$atts = array();
				$atts[] = 'show_name="' . ($show_name ? 'true' : 'false') . '"';
				$atts[] = 'show_groups="' . ($show_groups ? 'true' : 'false') . '"';
				
				if ( !empty($groups_string) ) {
					$atts[] = 'subscribe_to_groups="' . esc_attr($groups_string) . '"';
				}

				$shortcode = '[enewsletter_subscribe ' . implode(' ', $atts) . ']';
				echo do_shortcode( $shortcode );
				return;
			}
		}

		echo '<p>Bitte Plugin und Shortcode auswählen.</p>';
	}
	
}