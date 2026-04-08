<div id="layout-selector-select">

	<div id="layout-selector-select-content">
		<span id="currently-editing"><?php echo esc_html__( 'Du bearbeitest:', 'padma' ); ?></span><span id="current-layout"><?php echo PadmaLayout::get_current_name(); ?></span>
		<span id="layout-selector-select-content-arrow"></span></div>

	<div id="layout-selector">

		<div id="layout-selector-tabs">
			<ul class="tabs">
				<li><a href="#layout-selector-pages-container"><?php echo esc_html__( 'Seiten', 'padma' ); ?></a></li>
				<li><a href="#layout-selector-templates-container"><?php echo esc_html__( 'Geteilte Layouts', 'padma' ); ?></a></li>
			</ul>
		</div><!-- #layout-selector-tabs -->

		<div id="layout-selector-pages-container">
			<script type="text/html" id="layout-page-template">
				<li class="layout-item" data-bind="css: {
					'has-children': children().length || ajaxChildren,
					'has-ajax-children': ajaxChildren,
					'layout-item-customized': customized,
					'layout-item-template-used': template,
					'layout-selected': id == $root.currentLayout(),
					'layout-item-no-edit': noEdit
				}">
					<span class="layout-has-customized-children tooltip" title="<?php echo esc_attr__( 'Dieses Layout hat angepasste Unterlayouts.', 'padma' ); ?>">&bull;</span>

					<span class="layout layout-page" data-bind="attr: {'data-layout-id': id, 'data-layout-url': url}, css: {'layout-open': $('#layout-selector-pages-search-results').is(':visible')}">
						<strong data-bind="html: name"></strong>

						<span class="status status-post-status" data-bind="visible: postStatus, text: '(' + postStatus + ')'"></span>

						<span class="status status-template" data-bind="attr: {'data-template-id': template},text: templateName;"></span>

						<span class="status status-customized" data-bind="if: customized"><?php echo esc_html__( 'Angepasst', 'padma' ); ?></span>
						<span class="status status-currently-editing" data-bind="if: id == $root.currentLayout()"><?php echo esc_html__( 'Wird gerade bearbeitet', 'padma' ); ?></span>

						<span class="remove-template button layout-selector-button" data-bind="if: template"><?php echo esc_html__( 'Geteiltes Layout entfernen', 'padma' ); ?></span>

						<span class="edit button button-blue layout-selector-button"><?php echo esc_html__( 'Bearbeiten', 'padma' ); ?></span>
						<span class="revert button layout-selector-button tooltip" title="<?php echo esc_attr__( 'Beim Zuruecksetzen werden alle Bloecke aus diesem Layout entfernt&lt;br /&gt;damit es die Bloecke eines uebergeordneten Layouts erbt.', 'padma' ); ?>"><?php echo esc_html__( 'Zuruecksetzen', 'padma' ); ?></span>
					</span>

					<ul data-bind="template: {name: 'layout-page-template', foreach: children()}"></ul>

					<span class="load-more-layouts button layout-selector-button" data-bind="visible: typeof ajaxShowMore != 'undefined' && ajaxShowMore()"><?php echo esc_html__( 'Mehr laden...', 'padma' ); ?></span>
				</li>
			</script>

			<div id="layout-selector-pages" class="layout-selector-content">
				<div class="cog-container" data-bind="visible: searching()">
					<div class="cog-bottom-left"></div>
					<div class="cog-top-right"></div>
				</div>

				<ul data-bind="visible: search().length && !searching(), template: {name: 'layout-page-template', foreach:search()}" id="layout-selector-pages-search-results"></ul>
				<ul data-bind="visible: !search().length && !searching(), template: {name: 'layout-page-template', foreach:pages()}" id="layout-selector-pages-content"></ul>
			</div><!-- div#layout-selector-pages -->

			<div id="layout-search-input-container" class="layout-selector-bottom-input">
				<form>
					<input type="search" placeholder="<?php echo esc_attr__( 'Tippe zum Suchen...', 'padma' ); ?>" value="" id="layout-search-input" pattern=".{3,}" class="allow-enter-key" title="<?php echo esc_attr__( 'Deine Suche muss mindestens 3 Zeichen lang sein.', 'padma' ); ?>" />
					<span class="button" id="layout-search-submit"><?php echo esc_html__( 'Suchen', 'padma' ); ?></span>
				</form>
			</div>
		</div><!-- #layout-selector-pages -->

		<div id="layout-selector-templates-container">
			<script type="text/html" id="layout-shared-template">
				<li class="layout-item" data-bind="css: {
					'layout-selected': id == $root.currentLayout()
				}">
					<span class="layout layout-template" data-bind="attr: {'data-layout-id': id}">
						<strong class="template-name" data-bind="text: name"></strong>

						<span class="delete-template" title="<?php echo esc_attr__( 'Geteiltes Layout loeschen', 'padma' ); ?>"><?php echo esc_html__( 'Loeschen', 'padma' ); ?></span>

						<span class="status status-currently-editing" data-bind="if: id == $root.currentLayout()"><?php echo esc_html__( 'Wird gerade bearbeitet', 'padma' ); ?></span>

						<span class="rename-template button layout-selector-button"><?php echo esc_html__( 'Umbenennen', 'padma' ); ?></span>
						<span class="assign-template button layout-selector-button"><?php echo esc_html__( 'Layout verwenden', 'padma' ); ?></span>
						<span class="edit button button-blue layout-selector-button"><?php echo esc_html__( 'Bearbeiten', 'padma' ); ?></span>
					</span>
				</li>
			</script>


			<div id="layout-selector-templates" class="layout-selector-content">
				<ul data-bind="template: {name: 'layout-shared-template', foreach:shared()}"></ul>
			</div><!-- div#layout-selector-templates -->

			<div id="template-name-input-container" class="layout-selector-bottom-input">
				<input type="text" placeholder="<?php echo esc_attr__( 'Name fuer geteiltes Layout', 'padma' ); ?>" value="" id="template-name-input" />
				<span class="button add-template" id="add-template"><?php echo esc_html__( 'Geteiltes Layout hinzufuegen', 'padma' ); ?></span>
			</div>
		</div><!-- #layout-selector-templates -->
	</div><!-- #layout-selector-container -->
</div><!-- #layout-selector-select -->