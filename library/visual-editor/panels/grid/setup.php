<?php
class GridSetupPanel extends PadmaVisualEditorPanelAPI {

	public $id = 'setup';
	public $name;
	public $mode = 'grid';	
	public $tabs;	
	public $tab_notices;	
	public $inputs;

	function __construct(){

		$this->tabs = array(
			'grid' => 'Grid',
			'responsive-grid' => __('Responsive Grid','padma')
		);

		$this->name = 'Setup';

		$this->tab_notices = array(

			'grid' => __('<strong>Hinweis:</strong> der Inhalt im Raster oben spiegelt nicht wider, wie Deine Webseite tatsächlich aussieht. Der Inhalt innerhalb der Blöcke dient als allgemeine Referenz, während Du das Layout Deiner Website entwirfst und erstellst.<br /><br />Die untenstehenden Einstellungen sind <strong>global</strong> und werden nicht pro Layout angepasst.','padma'),

			'responsive-grid' => __('Das Padma Responsive Grid ermöglicht es, das leistungsstarke Raster in Padma Base je nach Gerät, von dem der Besucher die Webseite betrachtet, individuell anzupassen. Bitte beachte: Einige Webseiten profitieren davon, das responsive Raster zu aktivieren, während andere dies nicht tun. Als Designer der Webseite liegt es an Dir, dies zu entscheiden. Das responsive Raster kann jederzeit aktiviert oder deaktiviert werden.','padma')
		);

		$this->inputs = array(
			'grid' => array(
				'columns' => array(
					'type' => 'slider',
					'name' => 'columns',
					'label' => __('Standard-Spaltenanzahl','padma'), /* Column count is default only because you can't change it on the fly */
					'default' => 24,
					'tooltip' => __('Die Spaltenanzahl ist die Anzahl der Spalten im Raster. Dies wird durch die grauen Bereiche im Raster dargestellt.<br /><br /><strong>Dies wirkt sich NICHT auf bereits erstellte Wrapper aus. Es betrifft nur Wrapper, die nach der Änderung dieser Einstellung erstellt werden.</strong>','padma'),
					'slider-min' => 6,
					'slider-max' => 24,
					'slider-interval' => 1,
					'callback' => 'Padma.defaultGridColumnCount = value.toString();updateGridWidthInput($(input).parents(".sub-tabs-content"));'
				),

				'column-width' => array(
					'type' => 'slider',
					'name' => 'column-width',
					'label' => __('Globale Spaltenbreite','padma'),
					'default' => 26,
					'tooltip' => __('Die Spaltenbreite ist der Raum innerhalb jeder Spalte. Dies wird durch die grauen Bereiche im Raster dargestellt.','padma'),
					'unit' => 'px',
					'slider-min' => 10,
					'slider-max' => 120,
					'slider-interval' => 1,
					'callback' => 'Padma.globalGridColumnWidth = value.toString();$i("div.wrapper:not(.independent-grid)").each(function() { $(this).padmaGrid("updateGridCSS"); });updateGridWidthInput($(input).parents(".sub-tabs-content"));'
				),

				'gutter-width' => array(
					'type' => 'slider',
					'name' => 'gutter-width',
					'label' => __('Globale Gutter-Breite','padma'),
					'default' => 22,
					'tooltip' => __('Die Gutter-Breite ist der Abstand zwischen den einzelnen Spalten. Dies ist der Abstand zwischen den grauen Bereichen im Raster.','padma'),
					'unit' => 'px',
					'slider-min' => 0,
					'slider-max' => 60,
					'slider-interval' => 1,
					'callback' => 'Padma.globalGridGutterWidth = value.toString();$i("div.wrapper:not(.independent-grid)").each(function() { $(this).padmaGrid("updateGridCSS"); });updateGridWidthInput($(input).parents(".sub-tabs-content"));'
				),

				'grid-width' => array(
					'type' => 'integer',
					'unit' => 'px',
					'default' => 1130,
					'name' => 'grid-width',
					'label' => __('Globale Rasterbreite','padma'),
					'readonly' => true
				)
			),

			'responsive-grid' => array(
				'enable-responsive-grid' => array(
					'type' => 'checkbox',
					'name' => 'enable-responsive-grid',
					'label' => __('Responsive Raster aktivieren','padma'),
					'default' => true,
					'tooltip' => __('Wenn Padmas responsive Raster aktiviert ist, passt sich das Raster automatisch an das Gerät des Besuchers an (Computer, iPhone, iPad usw.). Das Aktivieren des responsiven Rasters kann für einige Webseiten äußerst vorteilhaft sein, für andere jedoch weniger sinnvoll. Wenn das responsive Raster aktiviert ist, hat der Benutzer immer die Möglichkeit, das responsive Raster über einen Link im Footer-Block zu deaktivieren.<br /><br /><strong>Bitte beachte:</strong> Bei aktiviertem responsive Raster können die genauen Pixelbreiten der Blöcke geringfügig von denen abweichen, wenn es <em>deaktiviert</em> ist.','padma')
				),

				'responsive-video-resizing' => array(
					'type' => 'checkbox',
					'name' => 'responsive-video-resizing',
					'label' => __('Responsive Video Resizing','padma'),
					'default' => true,
					'tooltip' => __('Wenn das Responsive Raster aktiviert ist und der Benutzer die Webseite besucht, während YouTube-, Vimeo- oder andere Videos vorhanden sind, werden die Videos nicht richtig skaliert, es sei denn, diese Option ist aktiviert.','padma')
				)
			)
		);

	}
}
padma_register_visual_editor_panel('GridSetupPanel');