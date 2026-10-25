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
