<?php
/**
 * Block options class
 *
 * @package Padma_Advanced
 */

namespace Padma_Advanced;

/**
 * Options class for block
 */
class PadmaVisualElementsBlockDailymotionOptions extends \PadmaBlockOptionsAPI {

	/**
	 * Block tabs for options.
	 *
	 * @var array $tabs
	 */
	public $tabs;

	/**
	 * Block sets for options.
	 *
	 * @var array $sets
	 */
	public $sets;

	/**
	 * Inputs for each tab.
	 *
	 * @var array $inputs
	 */
	public $inputs;

	/**
	 * Init block options
	 */
	public function __construct() {
		$this->tabs = array(
			'general' => __( 'Allgemein', 'padma-advanced' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'URL', 'padma-advanced' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL der Dailymotion Seite mit Video', 'padma-advanced' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma-advanced' ),
					'default' => 600,
					'tooltip' => __( 'Video Breite', 'padma-advanced' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma-advanced' ),
					'default' => 400,
					'tooltip' => __( 'Video Höhe', 'padma-advanced' ),
				),

				'responsive' => array(
					'name'    => 'responsive',
					'type'    => 'select',
					'label'   => __( 'Responsiv', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Breite und Höhe Parameter ignorieren und Player responsiv machen', 'padma-advanced' ),
				),

				'autoplay'   => array(
					'name'    => 'autoplay',
					'type'    => 'select',
					'label'   => __( 'Autoplay', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Video automatisch abspielen wenn die Seite geladen wird. Bitte beachte, dass in modernen Browsern die Autoplay-Option nur funktioniert, wenn die Stumm-Option aktiviert ist', 'padma-advanced' ),
				),

				'background' => array(
					'name'    => 'background',
					'type'    => 'text',
					'label'   => __( 'Hintergrund', 'padma-advanced' ),
					'default' => '#FFC300',
					'tooltip' => __( 'HTML (HEX) Farbe des Hintergrunds der Steuerelemente', 'padma-advanced' ),
				),

				'foreground' => array(
					'name'    => 'foreground',
					'type'    => 'text',
					'label'   => __( 'Vordergrund', 'padma-advanced' ),
					'default' => '#F7FFFD',
					'tooltip' => __( 'HTML (HEX) Farbe des Vordergrunds der Steuerelemente', 'padma-advanced' ),
				),

				'highlight'  => array(
					'name'    => 'highlight',
					'type'    => 'text',
					'label'   => __( 'Hervorhebung', 'padma-advanced' ),
					'default' => '#171D1B',
					'tooltip' => __( 'HTML (HEX) Farbe der Hervorhebungen der Steuerelemente', 'padma-advanced' ),
				),

				'logo'       => array(
					'name'    => 'logo',
					'type'    => 'select',
					'label'   => __( 'Logo', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Erlaubt das Dailymotion Logo zu verstecken oder anzuzeigen', 'padma-advanced' ),
				),

				'quality'    => array(
					'name'    => 'quality',
					'type'    => 'select',
					'label'   => __( 'Qualität', 'padma-advanced' ),
					'default' => '380',
					'options' => array(
						'240'  => '240',
						'380'  => '380',
						'480'  => '480',
						'720'  => '720',
						'1080' => '1080',
					),
					'tooltip' => __( 'Bestimmt die Qualität die standardmäßig abgespielt werden soll falls verfügbar', 'padma-advanced' ),
				),

				'related'    => array(
					'name'    => 'related',
					'type'    => 'select',
					'label'   => __( 'Verwandte', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Verwandte Videos am Ende des Videos anzeigen', 'padma-advanced' ),
				),

				'info'       => array(
					'name'    => 'info',
					'type'    => 'select',
					'label'   => __( 'Info', 'padma-advanced' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma-advanced' ),
						'no'  => __( 'Nein', 'padma-advanced' ),
					),
					'tooltip' => __( 'Video Infos (Titel/Autor) auf dem Startbildschirm anzeigen', 'padma-advanced' ),
				),
			),
		);
	}
}
