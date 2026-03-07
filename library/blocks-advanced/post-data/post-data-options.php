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
			'general' => __( 'Allgemein', 'padma' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'field'   => array(
					'name'    => 'field',
					'type'    => 'select',
					'label'   => __( 'Feld', 'padma' ),
					'default' => 'post_title',
					'options' => array(
						''                      => '',
						'ID'                    => __( 'Beitrag ID', 'padma' ),
						'post_author'           => __( 'Beitrag Autor', 'padma' ),
						'post_date'             => __( 'Beitrag Datum', 'padma' ),
						'post_date_gmt'         => __( 'Beitrag Datum GMT', 'padma' ),
						'post_content'          => __( 'Beitrag Inhalt', 'padma' ),
						'post_title'            => __( 'Beitrag Titel', 'padma' ),
						'post_excerpt'          => __( 'Beitrag Auszug', 'padma' ),
						'post_status'           => __( 'Beitrag Status', 'padma' ),
						'comment_status'        => __( 'Kommentar Status', 'padma' ),
						'ping_status'           => __( 'Ping Status', 'padma' ),
						'post_name'             => __( 'Beitrag Name', 'padma' ),
						'post_modified'         => __( 'Beitrag geändert', 'padma' ),
						'post_modified_gmt'     => __( 'Beitrag geändert GMT', 'padma' ),
						'post_content_filtered' => __( 'Gefilterter Beitrag Inhalt', 'padma' ),
						'post_parent'           => __( 'Beitrag Elternelement', 'padma' ),
						'guid'                  => 'GUID',
						'menu_order'            => __( 'Menü Reihenfolge', 'padma' ),
						'post_type'             => __( 'Beitrag Typ', 'padma' ),
						'post_mime_type'        => __( 'Beitrag Mime Typ', 'padma' ),
						'comment_count'         => __( 'Kommentar Anzahl', 'padma' ),
					),
					'tooltip' => __( 'Beitrag Datenfeld Name', 'padma' ),
				),
				'default' => array(
					'name'    => 'default',
					'type'    => 'text',
					'label'   => __( 'Standard', 'padma' ),
					'tooltip' => __( 'Dieser Text wird angezeigt wenn keine Daten gefunden werden', 'padma' ),
				),
				'before'  => array(
					'name'    => 'before',
					'type'    => 'text',
					'label'   => __( 'Davor', 'padma' ),
					'tooltip' => __( 'Dieser Inhalt wird vor dem Wert angezeigt', 'padma' ),
				),
				'after'   => array(
					'name'    => 'after',
					'type'    => 'text',
					'label'   => __( 'Danach', 'padma' ),
					'tooltip' => __( 'Dieser Inhalt wird nach dem Wert angezeigt', 'padma' ),
				),
				'post-id' => array(
					'name'    => 'post-id',
					'type'    => 'text',
					'label'   => __( 'Beitrag ID', 'padma' ),
					'tooltip' => __( 'Du kannst eine benutzerdefinierte Beitrag ID angeben. Beitrag Slug ist auch erlaubt. Lasse dieses Feld leer um die ID des aktuellen Beitrags zu verwenden. Die aktuelle Beitrag ID funktioniert möglicherweise nicht im Live-Vorschau-Modus', 'padma' ),
				),
			),

		);
	}
}
