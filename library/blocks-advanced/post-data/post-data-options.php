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
class PadmaVisualElementsBlockPostDataOptions extends \PadmaBlockOptionsAPI {

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

				'field'   => array(
					'name'    => 'field',
					'type'    => 'select',
					'label'   => __( 'Feld', 'padma-advanced' ),
					'default' => 'post_title',
					'options' => array(
						''                      => '',
						'ID'                    => __( 'Beitrag ID', 'padma-advanced' ),
						'post_author'           => __( 'Beitrag Autor', 'padma-advanced' ),
						'post_date'             => __( 'Beitrag Datum', 'padma-advanced' ),
						'post_date_gmt'         => __( 'Beitrag Datum GMT', 'padma-advanced' ),
						'post_content'          => __( 'Beitrag Inhalt', 'padma-advanced' ),
						'post_title'            => __( 'Beitrag Titel', 'padma-advanced' ),
						'post_excerpt'          => __( 'Beitrag Auszug', 'padma-advanced' ),
						'post_status'           => __( 'Beitrag Status', 'padma-advanced' ),
						'comment_status'        => __( 'Kommentar Status', 'padma-advanced' ),
						'ping_status'           => __( 'Ping Status', 'padma-advanced' ),
						'post_name'             => __( 'Beitrag Name', 'padma-advanced' ),
						'post_modified'         => __( 'Beitrag geändert', 'padma-advanced' ),
						'post_modified_gmt'     => __( 'Beitrag geändert GMT', 'padma-advanced' ),
						'post_content_filtered' => __( 'Gefilterter Beitrag Inhalt', 'padma-advanced' ),
						'post_parent'           => __( 'Beitrag Elternelement', 'padma-advanced' ),
						'guid'                  => 'GUID',
						'menu_order'            => __( 'Menü Reihenfolge', 'padma-advanced' ),
						'post_type'             => __( 'Beitrag Typ', 'padma-advanced' ),
						'post_mime_type'        => __( 'Beitrag Mime Typ', 'padma-advanced' ),
						'comment_count'         => __( 'Kommentar Anzahl', 'padma-advanced' ),
					),
					'tooltip' => __( 'Beitrag Datenfeld Name', 'padma-advanced' ),
				),
				'default' => array(
					'name'    => 'default',
					'type'    => 'text',
					'label'   => __( 'Standard', 'padma-advanced' ),
					'tooltip' => __( 'Dieser Text wird angezeigt wenn keine Daten gefunden werden', 'padma-advanced' ),
				),
				'before'  => array(
					'name'    => 'before',
					'type'    => 'text',
					'label'   => __( 'Davor', 'padma-advanced' ),
					'tooltip' => __( 'Dieser Inhalt wird vor dem Wert angezeigt', 'padma-advanced' ),
				),
				'after'   => array(
					'name'    => 'after',
					'type'    => 'text',
					'label'   => __( 'Danach', 'padma-advanced' ),
					'tooltip' => __( 'Dieser Inhalt wird nach dem Wert angezeigt', 'padma-advanced' ),
				),
				'post-id' => array(
					'name'    => 'post-id',
					'type'    => 'text',
					'label'   => __( 'Beitrag ID', 'padma-advanced' ),
					'tooltip' => __( 'Du kannst eine benutzerdefinierte Beitrag ID angeben. Beitrag Slug ist auch erlaubt. Lasse dieses Feld leer um die ID des aktuellen Beitrags zu verwenden. Die aktuelle Beitrag ID funktioniert möglicherweise nicht im Live-Vorschau-Modus', 'padma-advanced' ),
				),
			),

		);
	}
}
