<?php

class PadmaBlockOptionsAPI extends PadmaVisualEditorPanelAPI {


	public $block_type_object;
	public $block 		= false;
	public $block_id 	= false;


	public function __construct($block_type_object) {

		/* Accept the block type as an argument that way its properties are available for use in this class */
		$this->block_type_object = $block_type_object;

	}


	public function register() {

		return true;

	}


	public function display($block, $layout) {

		//Set block properties
		$this->block = $block;

		//Args for modify_arguments and block_content
		$args = array(
			'block' => $this->block,
			'blockID' => $this->block['id'],
			'layoutID' => $this->block['layout'],

			/* Backwards Compatibility */
			'block_id' => $this->block['id']
		);

		//Allow developers to modify the properties of the class and use functions since doing a property 
		//outside of a function will not allow you to.
		$this->modify_arguments($args);

		//Add the standard block tabs
		$this->add_standard_block_config();
		$this->add_standard_block_import_export();

		if ( PadmaResponsiveGrid::is_enabled() )
			$this->add_standard_block_responsive();

		$this->add_anywhere_tab($args);

		//Display it
		$this->panel_content($args);

	}

	public function add_anywhere_tab($args){

		if ( !isset($this->tabs) )
			$this->tabs = array();

		//Add the tab
		$this->tabs['anywhere'] = 'Shortcode';
		$shortcode_txt = "[padma-block id='" . $args['block']['id'] ."']";

		$this->tab_notices['anywhere'] = __('<strong>Verwende diesen Block überall</strong><p>Um diesen Block in deinen Beitrag oder deine Seite einzufügen, verwende diesen Shortcode:<p>','padma').'<input class="shortcode-anywhere" value="'.$shortcode_txt.'">';

		if(PadmaOption::get('padma-blocks-as-gutenberg-blocks')){
			$this->inputs['anywhere']['show-as-gutenberg-block'] = array(
					'name' => 'show-as-gutenberg-block',
					'type' => 'checkbox',
					'label' => __('Als Gutenberg-Block anzeigen','padma'),
					'default' => false
				);
		}

	}


	public function add_standard_block_config() {

		if ( !isset($this->tabs) )
			$this->tabs = array();

		if ( !isset($this->inputs) )
			$this->inputs = array();

		//Add the tab
		$this->tabs['config'] = __('Konfiguration','padma');

		/* Add the inputs */

		$this->inputs['config']['mirror-block'] = array(
			'type' => 'select',
			'name' => 'mirror-block',
			'label' => __('Block spiegeln','padma'),
			'chosen' => true,
			'default' => '',
			'tooltip' => __('Mit dieser Option kannst Du einen Block anweisen, einen anderen Block samt dessen Inhalt zu „spiegeln“. Diese Funktion ist nützlich, wenn Du einen Block – etwa eine Kopfzeile – auf verschiedenen Layouts Deiner Webseite gemeinsam nutzen möchtest. Wähle im Auswahlfeld auf der rechten Seite den Block aus, dessen Inhalt gespiegelt werden soll.','padma'),
			'options' => 'get_blocks_select_options_for_mirroring()',
			'callback' => 'updateBlockMirrorStatus(input, block.id, value);',
			'value' => PadmaBlocksData::is_block_mirrored($this->block)
		);

		$this->inputs['config']['alias'] = array(
			'type' => 'text',
			'name' => 'alias',
			'label' => __('Blockalias','padma'),
			'default' => '',
			'callback' => 'var $block = $i("#block-" + block.id); $block.data("alias", value); updateBlockContentCover($block);',
			'tooltip' => __('Gib einen leicht erkennbaren Namen für den Blockalias ein, und er wird im gesamten Admin-Bereich deiner Webseite verwendet. Wenn du beispielsweise einem Widget-Bereichsblock einen Alias hinzufügst, wird dieser Alias im Widgets-Panel verwendet.','padma'),
		);

		$this->inputs['config']['css-classes'] = array(
			'type' => 'text',
			'name' => 'css-classes',
			'callback' => 'updateBlockCustomClasses(input, block.id, value);',
			'label' => __('Benutzerdefinierte CSS-Klasse(n)','padma'),
			'default' => '',
			'tooltip' => __('Benötigst du mehr Kontrolle? Gib die benutzerdefinierten CSS-Klassenselektoren hier ein, und sie werden dem class-Attribut des Blocks hinzugefügt. <strong>Füge hier KEIN reguläres CSS ein.</strong> Verwende dafür den Live-CSS-Editor.','padma'),
		);

		$this->inputs['config']['css-classes-bubble'] = array(
			'type' => 'checkbox',
			'name' => 'css-classes-bubble',
			'label' => __('<em style="color: #666; font-style: italic;">Erweitert:</em> Benutzerdefinierte CSS-Klasse(n) zur Zeile/Spalte hinzufügen','padma'),
			'default' => '',
			'tooltip' => __('Kopiere alle benutzerdefinierten CSS-Klassen, die diesem Block hinzugefügt wurden, und füge sie dem übergeordneten Zeilen- und Spalten-&lt;section&gt; hinzu.','padma'),
		);

		/* Titles */		
			if ( isset($this->block_type_object->allow_titles) && $this->block_type_object->allow_titles ) {

				$this->inputs['config']['titles-heading'] = array(
					'name' => 'titles-heading',
					'type' => 'heading',
					'label' => __('Blocktitel','padma')
				);

					$this->inputs['config']['block-title'] = array(
						'name' => 'block-title',
						'type' => 'text',
						'label' => __('Blocktitel','padma'),
						'tooltip' => __('Füge einen benutzerdefinierten Titel über dem Blockinhalt hinzu.','padma')
					);

					$this->inputs['config']['block-title-tag'] = array(
						'name' => 'block-title-tag',
						'type' => 'select',
						'options' => array(
							'h1' => 'H1',
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							//'h6' => 'H6',
						),
						'label' => __('Blocktitel-Tag','padma'),
						'tooltip' => __('Benutzerdefiniertes Titel-Tag.','padma')
					);


					$this->inputs['config']['block-subtitle'] = array(
						'name' => 'block-subtitle',
						'type' => 'text',
						'label' => __('Blockuntertitel','padma'),
						'tooltip' => __('Füge einen benutzerdefinierten Untertitel über dem Blockinhalt und unter dem Blocktitel hinzu.','padma')
					);


					$this->inputs['config']['block-subtitle-tag'] = array(
						'name' => 'block-subtitle-tag',
						'type' => 'select',
						'options' => array(
							//'h1' => 'H1',
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							'h6' => 'H6',
						),
						'label' => __('Blockuntertitel-Tag','padma'),
						'tooltip' => __('Benutzerdefiniertes Untertitel-Tag.','padma')
					);

					$this->inputs['config']['block-title-link-check'] = array(
						'name' => 'block-title-link-check',
						'type' => 'checkbox',
						'label' => __('Verlinke Blocktitel?','padma'),
						'tooltip' => __('Wähle, ob der Blocktitel ein Link sein soll oder nicht','padma'),
						'default' => false,
						'toggle' => array(
							'true' => array(
								'show' => array(
									'#input-block-title-link-url',
									'#input-block-title-link-target',
									'#input-block-title-link-rel',
								)
							),
							'false' => array(
								'hide' => array(
									'#input-block-title-link-url',
									'#input-block-title-link-target',
									'#input-block-title-link-rel',
								)
							)
						)
					);

					$this->inputs['config']['block-title-link-url'] = array(
						'name' => 'block-title-link-url',
						'type' => 'text',
						'label' => __('Blocktitel-Link URL','padma'),
						'tooltip' => __('Füge eine URL für den Blocktitel hinzu','padma')
					);

					$this->inputs['config']['block-title-link-target'] = array(
						'name' => 'block-title-link-target',
						'type' => 'checkbox',
						'label' => __('In einem neuen Fenster öffnen?','padma'),
						'tooltip' => __('Wenn du den Link in einem neuen Fenster öffnen möchtest, aktiviere diese Option','padma'),
						'default' => false
					);

					$this->inputs['config']['block-title-link-rel'] = array(
						'name' => 'block-title-link-rel',
						'type'	=> 'text',
							'tooltip' => __('Hier kannst du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: noreferrer, noopener, nofollow, lightbox','padma'),
							'default' => 'noreferrer',
					);

			}
		/* End Titles */

	}

	public function add_standard_block_responsive() {

		if ( !isset($this->tabs) )
			$this->tabs = array();

		if ( !isset($this->inputs) )
			$this->inputs = array();

		//Add the tab
		$this->tabs['responsive'] = __('Responsive Kontrolloptionen','padma');

		/* Add the inputs */
		$this->inputs['responsive']['responsive-options'] = array(
			'type' => 'repeater',
			'name' => 'responsive-options',
			'label' => __('Haltepunkte konfigurieren.','padma'),
			'inputs' => array(

				array(
					'type' => 'select',
					'name' => 'blocks-breakpoint',
					'label' => __('Haltepunkt setzen','padma'),
					'options' => array(
						'off' => __('Aus - Kein Haltepunkt','padma'),
						'custom' => __('Benutzerdefinierte Breite','padma'),						
						'1920px' 	=> __('1920px - Sehr große Bildschirme','padma'),
						'1824px' 	=> __('1824px - Große Bildschirme','padma'),
						'1224px' 	=> __('1224px - Desktop und Laptop','padma'),
						'1024px' 	=> __('1024px - Beliebtes Tablet im Querformat','padma'),
						'812px' 	=> __('812px - iPhone X Landscape','padma'),
						'768px' 	=> __('768px - Beliebtes Tablet im Hochformat','padma'),
						'736px' 	=> __('736px - iPhone 6+ & 7+ & 8+ Landscape','padma'),
						'667px' 	=> __('667px - iPhone 6 & 7 & 8 & Android Landscape','padma'),
						'600px' 	=> __('600px - Beliebter Haltepunkt in Padma','padma'),
						'568px' 	=> __('568px - iPhone 5 Landscape','padma'),
						'480px' 	=> __('480px - iPhone 3 & 4 Landscape','padma'),
						'414px' 	=> __('414px - iPhone 6+ & 7+ & 8+ Landscape','padma'),
						'375px' 	=> __('375px - iPhone 6 & 7 & 8 & X & Android Portrait','padma'),
						'320px' 	=> __('320px - iPhone 3 & 4 & 5 & Android Portrait','padma'),
					),
					'toggle' => array(
						'' => array(
							'hide' => array(
								'.input:not(#input-blocks-breakpoint)'
							)
						),
						'off' => array(
							'hide' => array(
								'.input:not(#input-blocks-breakpoint)'
							)
						),
						'custom' => array(
							'show' => array(
								'.input'
							)
						),						
						'1824px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'1224px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'1024px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'768px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'600px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'568px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'480px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'320px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						)
					),
					'tooltip' => __('Wähle eine Bildschirmbreite, damit diese Änderungen wirksam werden.','padma'),
					'default' => ''
				),

				array(
					'type' => 'text',
					'name' => 'max-width',
					'label' => __('Benutzerdefinierte Breite','padma'),
					'default' => ''
				),

				array(
					'type' => 'select',
					'name' => 'breakpoint-min-or-max',
					'label' => __('Min- oder Max-Breite','padma'),
					'options' => array(
						'min' => __('Min-Breite (gilt für Bildschirme, die breiter als der Haltepunkt sind)','padma'),
						'max' => __('Max-Breite (gilt für Bildschirme, die schmaler als der Haltepunkt sind)','padma')
					),
					'default' => 'max'
				),

				array(
					'name' => 'adaptive-heading',
					'type' => 'heading',
					'label' => __('Adaptive Options','padma')
				),

				array(
					'type' => 'checkbox',
					'name' => 'disable-block-height',
					'label' => __('Blockhöhe deaktivieren','padma'),
					'tooltip'=> __('Deaktiviert die Höhe für kleinere Bildschirme, wenn der Block auf kleineren Bildschirmen zu hoch angezeigt wird','padma'),
					'default' => false
				),

				array(
					'type' => 'checkbox',
					'name' => 'mobile-center-elements',
					'label' => __('Versuche, Blockelemente zu zentrieren','padma'),
					'default' => false
				),

				array(
					'type' => 'checkbox',
					'name' => 'griddify-lists',
					'label' => __('Listen im Raster anzeigen','padma'),
					'default' => false,
					'tooltip' => __('Jede Art von Liste, wie Kategorien, neueste Beiträge, sogar Menüs usw. funktionieren auf großen Bildschirmen in der Seitenleiste einwandfrei. Aber auf kleineren Bildschirmen, auf denen die Seitenleiste unter den Inhalt rutscht, können die Listen aufgrund der großen Menge an Leerraum leer aussehen. Dies wird die Listenelemente in 2 Spalten nebeneinander anordnen.','padma')
				),

				array(
					'type' => 'checkbox',
					'name' => 'hide-block',
					'label' => __('Diesen Block ausblenden','padma'),
					'default' => false,
					'tooltip' => __('Dies blendet diesen Block für den festgelegten Haltepunkt aus.','padma')
				)

			),
			'sortable' => true,
			'limit' => false,
			'callback' => ''
		);


		if ( PadmaBlocksData::get_block_setting($this->block, 'responsive-block-hiding') ) {

			$this->inputs['responsive']['responsive-block-hiding'] = array(
				'type' => 'multi-select',
				'name' => 'responsive-block-hiding',
				'label' => __('Legacy Responsive Grid Block Ausblenden','padma'),
				'default' => '',
				'tooltip' => __('Wenn Du das responsive Raster aktiviert hast und der Benutzer Deine Webseite auf einem iPhone (oder einem gleichwertigen Gerät) ansieht, kann das Raster aufgrund der vielen Blöcke in einem kleinen Bereich unübersichtlich werden. Wenn Du die auf mobilen Geräten angezeigten Blöcke einschränken möchtest, kannst Du diese Einstellung verwenden, um bestimmte Blöcke für die von Dir gewählten Geräte auszublenden. <strong>Wenn keine Optionen ausgewählt sind, ist das responsive Blockausblenden für diesen Block nicht aktiv.</strong>','padma'),
				'options' => array(
					'smartphones' => 'iPhone/Smartphones',
					'tablets-landscape' => 'iPad/Tablets (Landscape)',
					'tablets-portrait' => 'iPad/Tablets (Portrait)',
					'computers' => __('Laptops & Desktops (Nicht empfohlen)','padma')
				)
			);

		}

	}

	public function add_standard_block_import_export() {

		if ( !isset($this->tabs) )
			$this->tabs = array();

		if ( !isset($this->inputs) )
			$this->inputs = array();

		//Add the tab
		$this->tabs['import-export'] = __('Importieren/Exportieren','padma');

		/* Add the inputs */

		$this->inputs['import-export']['import-heading'] = array(
			'name' => 'import-heading',
			'type' => 'heading',
			'label' => __('Blockeinstellungen importieren','padma')
		);

			$this->inputs['import-export']['block-import-settings-file'] = array(
				'type' => 'import-file',
				'name' => 'block-import-settings-file',
				'button-label' => __('Datei zum Importieren auswählen','padma'),
				'no-save' => true
			);

			$this->inputs['import-export']['block-import-include-options'] = array(
				'type' => 'checkbox',
				'name' => 'block-import-settings-include-options',
				'label' => __('Blockoptionen einbeziehen','padma'),
				'default' => true,
				'no-save' => true
			);

			$this->inputs['import-export']['block-import-include-design'] = array(
				'type' => 'checkbox',
				'name' => 'block-import-settings-include-design',
				'label' => __('Blockdesign einbeziehen','padma'),
				'default' => true,
				'no-save' => true
			);

			$this->inputs['import-export']['block-import-settings'] = array(
				'type' => 'button',
				'name' => 'block-import-settings',
				'button-label' => __('Blockeinstellungen importieren','padma'),
				'no-save' => true,
				'callback' => 'initiateBlockSettingsImport(args);'
			);

		$this->inputs['import-export']['export-heading'] = array(
			'name' => 'export-heading',
			'type' => 'heading',
			'label' => __('Blockeinstellungen exportieren','padma')
		);

			$this->inputs['import-export']['block-export-settings'] = array(
				'type' => 'button',
				'name' => 'block-export-settings',
				'button-label' => __('Exportdatei herunterladen','padma'),
				'no-save' => true,
				'callback' => 'exportBlockSettingsButtonCallback(args);'
			);

	}

	public function get_blocks_select_options_for_mirroring() {

		$block_type = $this->block['type'];	

		$blocks = PadmaBlocksData::get_blocks_by_type($block_type);

		$options = array('' => '&ndash; '. __('Nicht spiegeln','padma') . ' &ndash;');

		//If there are no blocks, then just return the Do Not Mirror option.
		if ( !isset($blocks) || !is_array($blocks) )
			return $options;

		foreach ( $blocks as $block_id => $block ) {

			if ( $this->block['id'] == $block_id ) {
				continue;
			}

			//If the block is mirrored, skip it
			if ( PadmaBlocksData::is_block_mirrored( $block ) ) {
				continue;
			}

			/* Do not show block that's in a mirrored wrapper */
			if ( PadmaWrappersData::is_wrapper_mirrored( PadmaWrappersData::get_wrapper( padma_get( 'wrapper_id', $block ) ) ) ) {
				continue;
			}

			//Create the default name by using the block type and ID
			$default_name = PadmaBlocks::block_type_nice( $block['type'] ) . ' Block';

			//If we can't get a name for the layout, then things probably aren't looking good.  Just skip this block.
			if ( ! ( $layout_name = PadmaLayout::get_name( $block['layout'] ) ) ) {
				continue;
			}

			//Make sure the block exists
			if ( ! PadmaBlocksData::block_exists( $block['id'] ) ) {
				continue;
			}

			$layout_name = PadmaLayout::get_layout_parents_names( $block['layout'] ) . $layout_name;

			if ( ! isset( $options[ $layout_name ] ) ) {
				$options[ $layout_name ] = array();
			}

			$options[ $layout_name ][ $block['id'] ] = padma_get( 'alias', $block['settings'], $default_name );

		}

		return $options;

	}

}