<?php

padma_register_admin_meta_box('PadmaMetaBoxTemplate');
class PadmaMetaBoxTemplate extends PadmaAdminMetaBoxAPI {

	protected $id;	
	protected $name;				
	protected $context;			
	protected $inputs;

	public function __construct(){

		$this->id = 'template';
		$this->name = __( 'Shared Layout', 'padma');
		$this->context = 'side';
		$this->inputs = array(
			'template' => array(
				'id' => 'template',
				'type' => 'select',
				'options' => array(),
				'description' => __('Weise diesem Eintrag ein gemeinsames Layout zu. Gemeinsame Layouts können im Padma Visual Editor hinzugefügt und bearbeitet werden.','padma'),
				'blank-option' => __('&ndash; Kein gemeinsames Layout verwenden &ndash;','padma')
			)
		);

	}

	protected function modify_arguments($post = false) {

		$this->inputs['template']['options'] = PadmaLayout::get_templates();

		$post_type = get_post_type_object( $post->post_type );

		$this->inputs['template']['description'] = str_replace('entry', strtolower($post_type->labels->singular_name), $this->inputs['template']['description']);

	}

}


padma_register_admin_meta_box('PadmaMetaBoxTitleControl');
class PadmaMetaBoxTitleControl extends PadmaAdminMetaBoxAPI {

	protected $id;
	protected $name;
	protected $context;
	protected $inputs;

	public function __construct(){

		$this->id = 'alternate-title';	
		$this->name = 'Title Control';				
		$this->context = 'side';			
		$this->inputs = array(
			'hide-title' => array(
				'id' => 'hide-title',
				'name' => __('Titel ausblenden','padma'),
				'type' => 'select',
				'blank-option' => __('&ndash; Titel nicht ausblenden &ndash;','padma'),
				'options' => array(
					'singular' => __('Auf Einzelansicht ausblenden','padma'),
					'list' => __('In Index und Archiven ausblenden','padma'),
					'both' => __('Auf Einzelansicht, Index und Archiven ausblenden','padma')
				),
				'description' => __('Wähle, ob der Titel für diesen Eintrag ausgeblendet werden soll. Dies kann nützlich sein, wenn du erweiterte Formatierungen in diesem Eintrag hast.','padma'),
			),

			'alternate-title' => array(
				'id' => 'alternate-title',
				'name' => __('Alternativer Titel','padma'),
				'type' => 'text',
				'description' => __('Mit dem alternativen Seitentitel kannst du den Titel überschreiben, der im Inhaltsblock der Seite angezeigt wird. Auf diese Weise kannst du einen kürzeren Seitentitel im Navigationsmenü und <code>&lt;title&gt;</code> haben, aber einen längeren und beschreibenderen Titel im eigentlichen Seiteninhalt.','padma')
			)
		);
	}


}


padma_register_admin_meta_box('PadmaMetaBoxDisplay');
class PadmaMetaBoxDisplay extends PadmaAdminMetaBoxAPI {

	protected $id;	
	protected $name;
	protected $inputs;

	public function __construct(){

		$this->id = 'display';
		$this->name = __('Anzeige','padma');
		$this->inputs = array(
			'css-class' => array(
				'id' => 'css-class',
				'name' => __('Benutzerdefinierte CSS-Klasse(n)','padma'),
				'type' => 'text',
				'description' => __('Wenn du mit <a href="http://www.w3schools.com/css/" target="_blank">CSS</a> vertraut bist und diesen Eintrag durch das Ansprechen einer bestimmten CSS-Klasse (oder Klassen) gestalten möchtest, kannst du sie hier eingeben. Die Klasse wird dem <strong>Container des Eintrags</strong> zusammen mit der <strong>Body-Klasse</strong> hinzugefügt, wenn nur dieser Eintrag angezeigt wird (z. B. Einzelansicht eines Beitrags oder einer Seite). Klassen können durch Leerzeichen und/oder Kommas getrennt werden.','padma')
			)
		);
	}

}


padma_register_admin_meta_box('PadmaMetaBoxPostThumbnail');
class PadmaMetaBoxPostThumbnail extends PadmaAdminMetaBoxAPI {

	protected $id;
	protected $name;
	protected $context;
	protected $priority;
	protected $inputs;

	public function __construct(){

		$this->id = 'post-thumbnail';		
		$this->name = __('Beitragsbild Position','padma');				
		$this->context = 'side';
		$this->priority = 'low';				
		$this->inputs = array(
			'position' => array(
				'id' => 'position',
				'name' => __('Beitragsbild Position','padma'),
				'type' => 'radio',
				'options' => array(
					'' => __('Standardblock verwenden','padma'),
					'left' => __('Links vom Titel','padma'),
					'right' => __('Rechts vom Titel','padma'),
					'left-content' => __('Links vom Inhalt','padma'),
					'right-content' => __('Rechts vom Inhalt','padma'),
					'above-title' => __('Über dem Titel','padma'),
					'above-content' => __('Über dem Inhalt','padma'),
					'below-content' => __('Unter dem Inhalt','padma')
				),
				'description' => __('Lege die Position des Beitragsbildes für diesen Eintrag fest.','padma'),
				'default' => '',
				'group' => 'post-thumbnail'
			),
		);
	}

}


if ( !PadmaSEO::is_disabled() ){
	padma_register_admin_meta_box('PadmaMetaBoxSEO');
}

class PadmaMetaBoxSEO extends PadmaAdminMetaBoxAPI {

	protected $id;
	protected $name;
	protected $post_type_supports_id;
	protected $priority;
	protected $inputs;

	public function __construct(){

		$this->id = 'seo';		
		$this->name = __('Suchmaschinenoptimierung (SEO)','padma');			
		$this->post_type_supports_id = 'padma-seo';		
		$this->priority = 'high';				

		$this->inputs = array(
			
			'seo-preview' => array(
				'id' => 'seo-preview',
				'type' => 'seo-preview'
			),

			
			'title' => array(
				'id' => 'title',
				'group' => 'seo',
				'name' => __('Titel','padma'),
				'type' => 'text',
				'description' => __('Benutzerdefiniertes <code>&lt;title&gt;</code> Tag','padma')
			),

			'description' => array(
				'id' => 'description',
				'group' => 'seo',
				'name' => __('Beschreibung','padma'),
				'type' => 'textarea',
				'description' => __('Benutzerdefinierte <code>&lt;meta&gt;</code> Beschreibung','padma')
			),
			
			'noindex' => array(
				'id' => 'noindex',
				'group' => 'seo',
				'name' => __('<code>noindex</code> diesen Eintrag.','padma'),
				'type' => 'checkbox',
				'description' => __('Index/NoIndex teilt den Suchmaschinen mit, ob der Eintrag gecrawlt und im Index der Suchmaschinen für die Abrufbarkeit gespeichert werden soll. Wenn du dieses Kästchen aktivierst, um <code>noindex</code> zu wählen, wird der Eintrag von den Suchmaschinen ausgeschlossen.  <strong>Hinweis:</strong> Wenn du nicht sicher bist, was dies bewirkt, aktiviere dieses Kästchen nicht.','padma')
		),

		'nofollow' => array(
			'id' => 'nofollow',
			'group' => 'seo',
			'name' => __('<code>nofollow</code> Links in diesem Eintrag.','padma'),
				'type' => 'checkbox',
				'description' => __('Noarchive wird verwendet, um Suchmaschinen daran zu hindern, eine zwischengespeicherte Kopie des Eintrags zu speichern. Standardmäßig behalten die Suchmaschinen sichtbare Kopien aller indexierten Seiten bei, die über den Link "Zwischengespeichert" in den Suchergebnissen für Suchende zugänglich sind. Aktivieren Sie dieses Kästchen, um Suchmaschinen daran zu hindern, zwischengespeicherte Kopien dieses Eintrags zu speichern.','padma')
			),

			'nosnippet' => array(
				'id' => 'nosnippet',
				'group' => 'seo',
				'name' => __('<code>nosnippet</code> Links in diesem Eintrag.','padma'),
				'type' => 'checkbox',
				'description' => __('Nosnippet teilt den Suchmaschinen mit, dass sie keinen beschreibenden Textblock neben dem Titel und der URL des Eintrags in den Suchergebnissen anzeigen sollen.','padma')
			),

			'noodp' => array(
				'id' => 'noodp',
				'group' => 'seo',
				'name' => __('<code>noodp</code> Links in diesem Eintrag.','padma'),
				'type' => 'checkbox',
				'description' => __('NoODP ist ein spezielles Tag, das den Suchmaschinen mitteilt, dass sie keinen beschreibenden Ausschnitt über eine Seite aus dem Open Directory Project (DMOZ) für die Anzeige in den Suchergebnissen verwenden sollen.','padma')
			),

			'noydir' => array(
				'id' => 'noydir',
				'group' => 'seo',
				'name' => __('<code>noydir</code> Links in diesem Eintrag.','padma'),
				'type' => 'checkbox',
				'description' => __('NoYDir, ähnlich wie NoODP, ist spezifisch für Yahoo! und teilt dieser Suchmaschine mit, dass sie die Yahoo! Directory-Beschreibung einer Seite/eines Standorts nicht in den Suchergebnissen verwenden sollen.','padma')
			),

			'redirect-301' => array(
				'id' => 'redirect-301',
				'group' => 'seo',
				'name' => __('301 Permanent Redirect','padma'),
				'type' => 'text',
				'description' => __('Der 301 Permanent Redirect kann verwendet werden, um einen alten Beitrag oder eine alte Seite an einen neuen oder anderen Ort weiterzuleiten. Wenn du jemals eine Seite verschiebst oder den Permalink einer Seite änderst, verwende dies, um deine Besucher an den neuen Ort weiterzuleiten.<br /><br /><em>Mehr Informationen? Lies mehr über <a href="http://support.google.com/webmasters/bin/answer.py?hl=en&answer=93633" target="_blank">301 Redirects</a>.</em>','padma')
			),

		);
		
	}


	protected function input_seo_preview() {

		global $post;

		$date = get_the_time('M j, Y') ? get_the_time('M j, Y') : mktime('M j, Y');
		$date_text = ( $post->post_type == 'post' ) ? $date . ' ... ' : null;

		echo '<h4 id="seo-preview-title">Search Engine Result Preview</h4>';

			echo '<div id="padma-seo-preview">';

				echo '<h4 title="Klicken zum Bearbeiten">' . get_bloginfo('name') . '</h4>';
				echo '<p id="seo-preview-description" title="Klicken zum Bearbeiten">' . $date_text . '<span id="text"></span></p>';

				echo '<p id="seo-preview-bottom"><span id="seo-preview-url">' . str_replace('http://', '', home_url()) . '</span> - <span>Zwischengespeichert</span> - <span>Ähnlich</span></p>';
		
			echo '</div>';

		echo '<small id="seo-preview-disclaimer">' . __('Denke daran, dies ist nur eine vorhergesagte Vorschau des Suchmaschinenergebnisses. Es gibt keine Garantie, dass es genau so aussehen wird. Es wird jedoch ähnlich aussehen.','padma') . '</small>';

	}


	protected function input_text_with_counter($input) {

		echo '
			<tr class="label">
				<th valign="top" scope="row">
					<label for="' . $input['attr-id'] . '">' . $input['name'] . '</label>
				</th>
			</tr>

			<tr>
				<td>
					<input type="text" value="' . esc_attr($input['value']) . '" id="' . $input['attr-id'] . '" name="' . $input['attr-name'] . '" />
				</td>
			</tr>

			<tr class="character-counter">
				<td>
					<span>130</span><div class="character-counter-box"><div class="character-counter-inside"></div></div>
				</td>
			</tr>
		';

	}


	protected function modify_arguments($post = false) {

		//Do not use this box if the page being edited is the front page since they can edit the setting in the configuration.
		if ( get_option('page_on_front') == padma_get('post') && get_option('show_on_front') == 'page' ) {

			$this->info = sprintf( __('<strong>Konfiguriere die SEO-Einstellungen für diese Seite (Startseite) im PS Padma Search Engine Optimization-Einstellungsbereich unter <a href="%s" target="_blank">Padma &raquo; Konfiguration</a>.</strong>','padma'), admin_url('admin.php?page=padma-options#tab-seo') );

			$this->inputs = array();

			return;

		}

		//Setup the defaults for the title and checkboxes
		$current_screen = get_current_screen();
		$seo_templates_query = PadmaOption::get('seo-templates', 'general', PadmaSEO::output_layouts_and_defaults());
		$seo_templates = padma_get('single-' . $current_screen->id, $seo_templates_query, array());

		$title_template = str_replace(array('%sitename%', '%SITENAME%'), get_bloginfo('name'), padma_get('title', $seo_templates));

		echo '<input type="hidden" id="title-seo-template" value="' . $title_template . '" />';

		$this->inputs['noindex']['default'] = padma_get('noindex', $seo_templates);
		$this->inputs['nofollow']['default'] = padma_get('nofollow', $seo_templates);
		$this->inputs['noarchive']['default'] = padma_get('noarchive', $seo_templates);
		$this->inputs['nosnippet']['default'] = padma_get('nosnippet', $seo_templates);
		$this->inputs['noodp']['default'] = padma_get('noodp', $seo_templates);
		$this->inputs['noydir']['default'] = padma_get('noydir', $seo_templates);


	}

}