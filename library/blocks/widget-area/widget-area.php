<?php

class PadmaWidgetAreaBlock extends PadmaBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $html_tag;
	public $attributes;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'widget-area';
		$this->name = __('Widget-Bereich','padma');
		$this->options_class = 'PadmaWidgetAreaBlockOptions';
		$this->html_tag = 'aside';
		$this->attributes = array(
			'itemscope' => '',
			'itemtype' => 'http://schema.org/WPSideBar'
		);
		$this->description = __('Wird typischerweise als Seitenleiste oder zur Unterstützung der Fußzeile verwendet. Der Widget-Bereich zeigt WordPress-Widgets an, die im WordPress-Design-Bereich „Widgets“ verwaltet werden.','padma');
		$this->categories 	= array('core','content');

	}


	public static function init_action($block_id, $block) {

		$widget_area_name = PadmaBlocksData::get_block_name($block) . ' &mdash; ' . __('Layout: ','padma') . PadmaLayout::get_name($block['layout']);

		$widget_area = array(
			'name'			 =>   $widget_area_name,
			'id' 			 =>   'widget-area-' . $block['id'],
			'before_widget'  =>   '<li id="%1$s" class="widget %2$s">' . "\n",
			'after_widget'   =>   '</li>' . "\n",
			'before_title'   =>   '<h3 class="widget-title"><span class="widget-inner">',
			'after_title'    =>   '</span></h3>' . "\n",
		);

		register_sidebar($widget_area);

	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'widget-area',
			'name' => __('Widget-Bereich','padma'),
			'selector' => 'ul.widget-area'
		));

		$this->register_block_element(array(
			'id' => 'widget',
			'name' => __('Widget','padma'),
			'selector' => 'li.widget'
		));

		$this->register_block_element(array(
			'id' => 'widget-title',
			'name' => __('Widget-Titel','padma'),
			'selector' => 'li.widget h3.widget-title'
		));

		$this->register_block_element(array(
			'id' => 'widget-title-inner',
			'name' => __('Widget-Titel-Inhalt','padma'),
			'selector' => 'li.widget .widget-title span'
		));

		$this->register_block_element(array(
			'id' => 'widget-links',
			'name' => __('Widget-Links','padma'),
			'selector' => 'li.widget a',
			'states' => array(
				'Selected' => 'ul li.current_page_item a', 
				'Hover' => 'ul li a:hover', 
				'Clicked' => 'ul li a:active'
			)
		));

		$this->register_block_element(array(
			'id' => 'widget-lists',
			'name' => __('Widget-Listen','padma'),
			'description' => '&lt;UL&gt;',
			'selector' => 'li.widget ul',
			'properties' => array('fonts', 'lists', 'background', 'borders', 'padding', 'corners', 'box-shadow', 'advanced', 'transition', 'outlines'),
		));

			$this->register_block_element(array(
				'id' => 'widget-list-items',
				'name' => __('Widget-Listenelemente','padma'),
				'description' => '&lt;LI&gt;',
				'selector' => 'li.widget ul li'
			));

	}


	function modify_default_widget_title( $title, $instance, $id_base ) {

		if ( isset( $instance['title'] ) )
			return $instance['title'];

		return $title;

	}


	function content($block) {

		/* Use legacy ID */
		$block['id'] = PadmaBlocksData::get_legacy_id( $block );

		echo ( parent::get_setting( $block, 'horizontal-widgets' ) == true ) ? '<ul class="widget-area horizontal-sidebar">' : '<ul class="widget-area">';

			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-' . $block['id']) ) {

				global $sidebars_widgets, $wp_widget_factory;

				$default_widgets = parent::get_setting( $block, 'default-widgets', array() );

				if ( !empty($default_widgets) && empty($sidebars_widgets['widget-area-' . $block['id']]) ) {

					add_filter( 'widget_title', array( $this, 'modify_default_widget_title' ), 10, 3 );

					foreach ( $default_widgets as $default_widget ) {

						/* check if the widget exists and set widget_obj variable */
						if ( !is_object( $widget_obj = padma_get( $default_widget['widget'], $wp_widget_factory->widgets ) ) )
							continue;

						/* Widget Instance Parameters */
						$instance = array();

						if ( !padma_get('show-title', $default_widget, true, true) ) {
							$instance['title'] = false;
						} else if ( $default_widget['title'] != '%default%' ) {
							$instance['title'] = $default_widget['title'];
						} else {
							$instance['title'] = $widget_obj->name;
						}

						/* WooCommerce fixes */
						if ( stripos( $default_widget['widget'], 'WC_' ) !== false ) {

							$instance += array(
								'number'          => 5,
								'count'           => '1',
								'hierarchical'    => true,
								'dropdown'        => '1',
								'show_variations' => true
							);

						}

						the_widget(
							$default_widget['widget'],
							$instance,
							array(
								'widget_id' => 1,// woocommerce fix
								'before_widget' => '<li class="widget ' . $widget_obj->widget_options['classname'] . '">' . "\n",
								'after_widget' => '</li>' . "\n",
								'before_title' => '<h3 class="widget-title"><span class="widget-inner">',
								'after_title' => '</span></h3>' . "\n",
							)
						);

					}

				} else {

					echo '<li class="widget widget-no-widgets">';
						echo '<h3 class="widget-title"><span class="widget-inner">' . __('Keine Widgets!','padma') . '</span></h3>';
						echo sprintf( __('<p>Füge Widgets zu diesem Sidebar im <a href="%s">Widgets-Panel</a> unter Darstellung im WordPress-Admin hinzu.</p>','padma'), admin_url('widgets.php') );
					echo '</li>';

				}

			} 

		echo '</ul>';

	}

}


class PadmaWidgetAreaBlockOptions extends PadmaBlockOptionsAPI {


	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'widget-area-content' => __('Content','padma'),
			'widget-layout' => __('Widget Layout','padma')
		);


		$this->inputs = array(
			'widget-layout' => array(
				'horizontal-widgets' => array(
					'type' => 'checkbox',
					'name' => 'horizontal-widgets',
					'label' => __('Horizontale Widgets','padma'),
					'default' => false,
					'tooltip' => __('Anstatt von Widgets vertikal anzuzeigen, kannst Du sie horizontal anzeigen lassen. Dies ist besonders nützlich für widgetisierte Fußzeilen.','padma')
				)
			)
		);
	}


	function modify_arguments($args = false) {

		global $sidebars_widgets;

		$sidebar_id = 'widget-area-' . $args['block_id'];

		$this->tab_notices['widget-area-content'] =  sprintf( 
			__('Um Widgets zu diesem Widget-Bereich hinzuzufügen, gehe zu <a href="%s" target="_blank">WordPress Admin &raquo; Darstellung &raquo; Widgets</a> und füge die Widgets zu <em>%s &mdash; Layout: %s</em> hinzu.','padma'), 
			admin_url('widgets.php'), 
			PadmaBlocksData::get_block_name( $args['block'] ), 
			PadmaLayout::get_name( $args['layoutID'] ) 
		);

		/* don't show the default widgets options if it is not going to serve any purpose */
		if ( empty($sidebars_widgets[$sidebar_id]) ) :

			$this->tabs = array_merge( $this->tabs, array( 'widget-default' => 'Default Widgets' ) );

			$this->inputs['widget-default']['default-widgets'] = array(
				'type' => 'repeater',
				'name' => 'default-widgets',
				'label' => __('Standard Widgets','padma'),
				'tooltip' => __('Weise diesem Widget-Bereich Standard-Widgets zu..','padma'),
				'inputs' => array(
					array(
						'type' => 'select',
						'name' => 'widget',
						'label' => __('Widget','padma'),
						'default' => array( 'pages' ),
						'options' => 'get_widgets()',
					),
					array(
						'type'    => 'checkbox',
						'name'    => 'show-title',
						'label'   => __('Titel anzeigen','padma'),
						'default' => true,
						'toggle' => array(
							'true' => array(
								'show' => '#input-title'
							),
							'false' => array(
								'hide' => '#input-title'
							)
						)
					),
					array(
						'type' => 'text',
						'name' => 'title',
						'label' => __('Titel','padma'),
						'default' => '%default%',
						'tootlip' => __('Dies wird der Titel sein, der über dem Widget angezeigt wird. Wenn du den Standard für den Widget-Typ verwenden möchtest, gib bitte <em>%default%</em> ein.','padma')
					)
				),
				'sortable' => true
			);

		endif;

	}


	function get_widgets() {

		global $wp_widget_factory;

		if ( !isset($wp_widget_factory->widgets) )
			return;

		$options = array();

		foreach ( $wp_widget_factory->widgets as $class => $widgets )
			$options[$class] = $widgets->name; 

		return array_merge( array( '' => __('Make a Selection','padma') ), $options);

	}

}