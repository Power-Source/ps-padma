<?php

namespace Padma_Advanced;

class PadmaVisualElementsBlockShortcodeBlockOptions extends \PadmaBlockOptionsAPI {

	public $tabs = array(
		'content-tab' 		=> 'Content',
		'marketpress-tab' 	=> 'MarketPress',
		'powerform-tab' 	=> 'PowerForm',
		'community-tab' 	=> 'PS Community',
		'e-newsletter-tab' 	=> 'E-Newsletter',
	);

	public $inputs = array(

		'content-tab' => array(
			'shortcode-product-type' => array(
				'type' => 'select',
				'name' => 'shortcode-product-type',
				'label' => 'Welches Plugin',
				'options' => array(
					'none' 			=> 'Bitte Plugin auswählen',
					'marketpress' 	=> 'MarketPress',
					'powerform' 	=> 'PowerForm',
					'community' 	=> 'PS Community',
					'e-newsletter' 	=> 'E-Newsletter',
				),
				'toggle' => array(
					'none' => array(
						'show' => array(),
						'hide' => array(
							'#sub-tab-marketpress-tab',
							'#sub-tab-powerform-tab',
							'#sub-tab-community-tab',
							'#sub-tab-e-newsletter-tab',
						),
					),
					'marketpress' => array(
						'show' => array('#sub-tab-marketpress-tab'),
						'hide' => array('#sub-tab-powerform-tab', '#sub-tab-community-tab', '#sub-tab-e-newsletter-tab'),
					),
					'powerform' => array(
						'show' => array('#sub-tab-powerform-tab'),
						'hide' => array('#sub-tab-marketpress-tab', '#sub-tab-community-tab', '#sub-tab-e-newsletter-tab'),
					),
					'community' => array(
						'show' => array('#sub-tab-community-tab'),
						'hide' => array('#sub-tab-marketpress-tab', '#sub-tab-powerform-tab', '#sub-tab-e-newsletter-tab'),
					),
					'e-newsletter' => array(
						'show' => array('#sub-tab-e-newsletter-tab'),
						'hide' => array('#sub-tab-marketpress-tab', '#sub-tab-powerform-tab', '#sub-tab-community-tab'),
					),
				),
				'default' => 'none',
				'tooltip' => 'Wähle das Plugin für die Shortcode-Ausgabe',
			),
		),

		'marketpress-tab' => array(
			'mp-shortcode-type' => array(
				'type' => 'select',
				'name' => 'mp-shortcode-type',
				'label' => 'MarketPress Shortcode',
				'options' => array(
					'none' => 'Shortcode auswählen',
					'mp_list_products' => 'Produkte (Liste)',
					'mp_featured_products' => 'Empfohlene Produkte',
					'mp_popular_products' => 'Beliebte Produkte',
					'mp_product' => 'Einzelnes Produkt',
					'mp_cart' => 'Warenkorb',
					'mp_checkout' => 'Checkout',
				),
				'toggle' => array(
					'none' => array(
						'show' => array(),
						'hide' => array(
							'#sub-tab-marketpress-tab-content #input-mp-number',
							'#sub-tab-marketpress-tab-content #input-mp-product-id',
						),
					),
					'mp_featured_products' => array(
						'show' => array('#sub-tab-marketpress-tab-content #input-mp-number'),
						'hide' => array('#sub-tab-marketpress-tab-content #input-mp-product-id'),
					),
					'mp_popular_products' => array(
						'show' => array('#sub-tab-marketpress-tab-content #input-mp-number'),
						'hide' => array('#sub-tab-marketpress-tab-content #input-mp-product-id'),
					),
					'mp_product' => array(
						'show' => array('#sub-tab-marketpress-tab-content #input-mp-product-id'),
						'hide' => array('#sub-tab-marketpress-tab-content #input-mp-number'),
					),
					'mp_list_products' => array(
						'show' => array(),
						'hide' => array('#sub-tab-marketpress-tab-content #input-mp-number', '#sub-tab-marketpress-tab-content #input-mp-product-id'),
					),
					'mp_cart' => array(
						'show' => array(),
						'hide' => array('#sub-tab-marketpress-tab-content #input-mp-number', '#sub-tab-marketpress-tab-content #input-mp-product-id'),
					),
					'mp_checkout' => array(
						'show' => array(),
						'hide' => array('#sub-tab-marketpress-tab-content #input-mp-number', '#sub-tab-marketpress-tab-content #input-mp-product-id'),
					),
				),
				'default' => 'none',
				'tooltip' => 'Wähle den MarketPress Shortcode',
			),
			'mp-number' => array(
				'type' => 'integer',
				'name' => 'mp-number',
				'label' => 'Anzahl',
				'default' => '5',
				'tooltip' => 'Anzahl der auszugebenden Produkte',
			),
			'mp-product-id' => array(
				'type' => 'integer',
				'name' => 'mp-product-id',
				'label' => 'Produkt-ID',
				'default' => '',
				'tooltip' => 'ID eines einzelnen MarketPress Produkts',
			),
		),

		'powerform-tab' => array(
			'powerform-shortcode' => array(
				'type' => 'select',
				'name' => 'powerform-shortcode',
				'label' => 'Formular',
				'default' => 'none',
				'tooltip' => 'Wähle ein PowerForm-Formular',
			),
		),

		'community-tab' => array(
			'community-shortcode-type' => array(
				'type' => 'select',
				'name' => 'community-shortcode-type',
				'label' => 'PS Community Shortcode',
				'options' => array(
					'none' => 'Shortcode auswählen',
					'cpc-groups' => 'Gruppenliste',
					'cpc-my-groups' => 'Meine Gruppen',
					'cpc-group-single' => 'Einzelne Gruppe',
					'cpc-group-members' => 'Gruppenmitglieder',
					'cpc-group-create' => 'Gruppe erstellen',
					'cpc-group-join-button' => 'Join-Button',
					'cpc-group-leave-button' => 'Leave-Button',
				),
				'toggle' => array(
					'none' => array(
						'show' => array(),
						'hide' => array('#sub-tab-community-tab-content #input-community-group-id'),
					),
					'cpc-group-single' => array(
						'show' => array('#sub-tab-community-tab-content #input-community-group-id'),
						'hide' => array(),
					),
					'cpc-group-members' => array(
						'show' => array('#sub-tab-community-tab-content #input-community-group-id'),
						'hide' => array(),
					),
					'cpc-group-join-button' => array(
						'show' => array('#sub-tab-community-tab-content #input-community-group-id'),
						'hide' => array(),
					),
					'cpc-group-leave-button' => array(
						'show' => array('#sub-tab-community-tab-content #input-community-group-id'),
						'hide' => array(),
					),
					'cpc-groups' => array(
						'show' => array(),
						'hide' => array('#sub-tab-community-tab-content #input-community-group-id'),
					),
					'cpc-my-groups' => array(
						'show' => array(),
						'hide' => array('#sub-tab-community-tab-content #input-community-group-id'),
					),
					'cpc-group-create' => array(
						'show' => array(),
						'hide' => array('#sub-tab-community-tab-content #input-community-group-id'),
					),
				),
				'default' => 'none',
				'tooltip' => 'Wähle den PS Community Shortcode',
			),
			'community-group-id' => array(
				'type' => 'select',
				'name' => 'community-group-id',
				'label' => 'Gruppe',
				'default' => 'none',
				'tooltip' => 'Gruppe für Single/Members/Join/Leave auswählen',
			),
		),

		'e-newsletter-tab' => array(
			'enewsletter-shortcode-type' => array(
				'type' => 'select',
				'name' => 'enewsletter-shortcode-type',
				'label' => 'Newsletter Shortcode',
				'options' => array(
					'none' => 'Shortcode auswählen',
					'subscribe' => 'Anmeldeformular',
					'subscribe-message' => 'Anmeldebestätigung',
					'unsubscribe-message' => 'Abmeldebestätigung',
				),
				'toggle' => array(
					'none' => array(
						'show' => array(),
						'hide' => array(
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-name',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-groups',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-subscribe-to-groups',
						),
					),
					'subscribe' => array(
						'show' => array(
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-name',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-groups',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-subscribe-to-groups',
						),
						'hide' => array(),
					),
					'subscribe-message' => array(
						'show' => array(),
						'hide' => array(
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-name',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-groups',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-subscribe-to-groups',
						),
					),
					'unsubscribe-message' => array(
						'show' => array(),
						'hide' => array(
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-name',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-show-groups',
							'#sub-tab-e-newsletter-tab-content #input-enewsletter-subscribe-to-groups',
						),
					),
				),
				'default' => 'none',
				'tooltip' => 'Wähle den E-Newsletter Shortcode',
			),
			'enewsletter-show-name' => array(
				'type' => 'checkbox',
				'name' => 'enewsletter-show-name',
				'label' => 'Namensfeld anzeigen',
				'default' => false,
				'tooltip' => 'Zusätzlich zum Email-Feld auch ein Namensfeld anzeigen',
			),
			'enewsletter-show-groups' => array(
				'type' => 'checkbox',
				'name' => 'enewsletter-show-groups',
				'label' => 'Gruppenauswahl anzeigen',
				'default' => true,
				'tooltip' => 'Dem User erlauben, Gruppen auszuwählen',
			),
			'enewsletter-subscribe-to-groups' => array(
				'type' => 'multi-select',
				'name' => 'enewsletter-subscribe-to-groups',
				'label' => 'Auto-Subscribe zu Gruppen',
				'default' => array(),
				'tooltip' => 'Automatisch zu diesen Gruppen anmelden (User kann nicht abwählen)',
			),
		),
	);

	public function modify_arguments($args = false){

		if ( function_exists('mp_get_setting') ) {
			$this->inputs['marketpress-tab']['mp-shortcode-type']['options'] = $this->inputs['marketpress-tab']['mp-shortcode-type']['options'];
		} else {
			$this->inputs['marketpress-tab']['mp-shortcode-type']['options'] = array('none' => 'MarketPress ist nicht installiert');
		}

		if ( post_type_exists('powerform_forms') ) {
			$forms = get_posts(array(
				'post_type' => 'powerform_forms',
				'post_status' => array('publish', 'draft'),
				'numberposts' => -1,
			));

			$options = array('none' => 'Formular auswählen');
			foreach ( $forms as $form ) {
				$options[$form->ID] = $form->post_title;
			}
			$this->inputs['powerform-tab']['powerform-shortcode']['options'] = $options;
		} else {
			$this->inputs['powerform-tab']['powerform-shortcode']['options'] = array('none' => 'PowerForm ist nicht installiert');
		}

		if ( post_type_exists('cpc_group') ) {
			$groups = get_posts(array(
				'post_type' => 'cpc_group',
				'post_status' => array('publish', 'draft'),
				'posts_per_page' => -1,
			));

			$options = array('none' => 'Gruppe auswählen');
			foreach ( $groups as $group ) {
				$options[$group->ID] = $group->post_title;
			}
			$this->inputs['community-tab']['community-group-id']['options'] = $options;
		} else {
			$this->inputs['community-tab']['community-group-id']['options'] = array('none' => 'PS Community ist nicht installiert');
		}

		// E-Newsletter Gruppen laden
		if ( class_exists('Email_Newsletter') ) {
			global $email_newsletter;
			
			if ( isset($email_newsletter) && method_exists($email_newsletter, 'get_groups') ) {
				$groups = $email_newsletter->get_groups(0); // 0 = alle Gruppen (inkl. nicht-öffentliche)
				
				$options = array();
				if ( is_array($groups) && count($groups) > 0 ) {
					foreach ( $groups as $group ) {
						$options[$group['group_id']] = $group['group_name'];
					}
				} else {
					$options['none'] = 'Keine Newsletter-Gruppen vorhanden';
				}
				
				$this->inputs['e-newsletter-tab']['enewsletter-subscribe-to-groups']['options'] = $options;
			} else {
				$this->inputs['e-newsletter-tab']['enewsletter-subscribe-to-groups']['options'] = array('none' => 'E-Newsletter nicht korrekt initialisiert');
			}
		} else {
			$this->inputs['e-newsletter-tab']['enewsletter-subscribe-to-groups']['options'] = array('none' => 'E-Newsletter ist nicht installiert');
		}
	}
}
