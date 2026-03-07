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
			'general' => __( 'Allgemein', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'URL', 'padma' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL der Dailymotion Seite mit Video', 'padma' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'padma' ),
					'default' => 600,
					'tooltip' => __( 'Video Breite', 'padma' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'padma' ),
					'default' => 400,
					'tooltip' => __( 'Video Höhe', 'padma' ),
				),

				'responsive' => array(
					'name'    => 'responsive',
					'type'    => 'select',
					'label'   => __( 'Responsiv', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Breite und Höhe Parameter ignorieren und Player responsiv machen', 'padma' ),
				),

				'autoplay'   => array(
					'name'    => 'autoplay',
					'type'    => 'select',
					'label'   => __( 'Autoplay', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Video automatisch abspielen wenn die Seite geladen wird. Bitte beachte, dass in modernen Browsern die Autoplay-Option nur funktioniert, wenn die Stumm-Option aktiviert ist', 'padma' ),
				),

				'background' => array(
					'name'    => 'background',
					'type'    => 'text',
					'label'   => __( 'Hintergrund', 'padma' ),
					'default' => '#FFC300',
					'tooltip' => __( 'HTML (HEX) Farbe des Hintergrunds der Steuerelemente', 'padma' ),
				),

				'foreground' => array(
					'name'    => 'foreground',
					'type'    => 'text',
					'label'   => __( 'Vordergrund', 'padma' ),
					'default' => '#F7FFFD',
					'tooltip' => __( 'HTML (HEX) Farbe des Vordergrunds der Steuerelemente', 'padma' ),
				),

				'highlight'  => array(
					'name'    => 'highlight',
					'type'    => 'text',
					'label'   => __( 'Hervorhebung', 'padma' ),
					'default' => '#171D1B',
					'tooltip' => __( 'HTML (HEX) Farbe der Hervorhebungen der Steuerelemente', 'padma' ),
				),

				'logo'       => array(
					'name'    => 'logo',
					'type'    => 'select',
					'label'   => __( 'Logo', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Erlaubt das Dailymotion Logo zu verstecken oder anzuzeigen', 'padma' ),
				),

				'quality'    => array(
					'name'    => 'quality',
					'type'    => 'select',
					'label'   => __( 'Qualität', 'padma' ),
					'default' => '380',
					'options' => array(
						'240'  => '240',
						'380'  => '380',
						'480'  => '480',
						'720'  => '720',
						'1080' => '1080',
					),
					'tooltip' => __( 'Bestimmt die Qualität die standardmäßig abgespielt werden soll falls verfügbar', 'padma' ),
				),

				'related'    => array(
					'name'    => 'related',
					'type'    => 'select',
					'label'   => __( 'Verwandte', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Verwandte Videos am Ende des Videos anzeigen', 'padma' ),
				),

				'info'       => array(
					'name'    => 'info',
					'type'    => 'select',
					'label'   => __( 'Info', 'padma' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'padma' ),
						'no'  => __( 'Nein', 'padma' ),
					),
					'tooltip' => __( 'Video Infos (Titel/Autor) auf dem Startbildschirm anzeigen', 'padma' ),
				),
			),
		);
	}
}
