<?php

$advanced_blocks = array();

if ( class_exists('PadmaBlocks') && isset(PadmaBlocks::$advanced_blocks) && is_array(PadmaBlocks::$advanced_blocks) ) {
	$advanced_blocks = PadmaBlocks::$advanced_blocks;
}

$categories = array();
$items_html = '';

foreach ( $advanced_blocks as $block_name => $block_class ) {

	$block_base_dir = PADMA_LIBRARY_DIR . '/blocks-advanced/' . $block_name;
	$block_file = $block_base_dir . '/' . $block_name . '-block.php';
	$icon_file = $block_base_dir . '/icon.svg';
	$icon_url = file_exists($icon_file) ? padma_url() . '/library/blocks-advanced/' . $block_name . '/icon.svg' : '';

	if ( !file_exists($block_file) ) {
		continue;
	}

	require_once $block_file;

	if ( !class_exists($block_class) ) {
		continue;
	}

	$block = new $block_class();
	$block_categories = isset($block->categories) && is_array($block->categories) ? $block->categories : array();
	$categories = array_merge($categories, $block_categories);

	$filters = '';
	foreach ( $block_categories as $category ) {
		$filters .= ' filter-' . sanitize_html_class($category);
	}

	$items_html .= '<a class="padma-advanced-list-item' . $filters . '">';
	$items_html .= '<span class="padma-advanced-list-item-image">';
	if ( $icon_url ) {
		$items_html .= '<img src="' . esc_url($icon_url) . '" alt="" width="120" height="120">';
	} else {
		$items_html .= '<span class="padma-advanced-list-item-noicon">' . esc_html(strtoupper(substr($block_name, 0, 1))) . '</span>';
	}
	$items_html .= '</span>';
	$items_html .= '<span class="padma-advanced-list-item-title">' . esc_html($block->name) . '</span>';
	$items_html .= '<span class="padma-advanced-list-item-description">' . esc_html($block->description) . '</span>';
	$items_html .= '</a>';
}

$categories = array_unique($categories);
sort($categories);
?>

<h2><?php _e('Erweiterte Blöcke', 'padma'); ?></h2>
<p><?php _e('Diese Blöcke sind direkt im Theme integriert und können im Visual Editor verwendet werden.', 'padma'); ?></p>

<?php
$psource_shortcodes_active = class_exists('PSOURCE_Shortcodes');
$psource_shortcodes_installed = false;
$psource_shortcodes_plugin_file = '';

if ( !$psource_shortcodes_active ) {
	if ( !function_exists('get_plugins') ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();
	foreach ( $all_plugins as $plugin_file => $plugin_data ) {
		if ( stripos($plugin_file, 'psource-shortcodes') !== false || stripos($plugin_data['Name'], 'PSOURCE Shortcodes') !== false ) {
			$psource_shortcodes_installed = true;
			$psource_shortcodes_plugin_file = $plugin_file;
			break;
		}
	}
}

$status_class = $psource_shortcodes_active ? 'status-active' : ($psource_shortcodes_installed ? 'status-inactive' : 'status-missing');
?>
<div class="padma-advanced-status-box <?php echo $status_class; ?>">
	<span class="dashicons <?php echo $psource_shortcodes_active ? 'dashicons-yes-alt' : 'dashicons-warning'; ?>"></span>
	<div class="status-content">
		<?php if ( $psource_shortcodes_active ) : ?>
			<strong><?php _e('PSOURCE Shortcodes ist aktiv', 'padma'); ?></strong>
			<p><?php _e('Alle Shortcode-basierten Blöcke (Accordion, Tabs, Spoiler, etc.) funktionieren vollständig.', 'padma'); ?></p>
		<?php elseif ( $psource_shortcodes_installed ) : ?>
			<strong><?php _e('PSOURCE Shortcodes ist installiert aber nicht aktiv', 'padma'); ?></strong>
			<p><?php _e('Einige Blöcke benötigen das PSOURCE Shortcodes Plugin für volle Funktionalität.', 'padma'); ?></p>
		<?php else : ?>
			<strong><?php _e('PSOURCE Shortcodes ist nicht installiert', 'padma'); ?></strong>
			<p><?php _e('Einige Blöcke benötigen das PSOURCE Shortcodes Plugin für volle Funktionalität. Ohne das Plugin werden Fallback-Hinweise angezeigt.', 'padma'); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $psource_shortcodes_installed && !$psource_shortcodes_active ) : ?>
		<button type="button" class="button button-primary padma-activate-plugin" data-plugin="<?php echo esc_attr($psource_shortcodes_plugin_file); ?>" data-nonce="<?php echo wp_create_nonce('padma_activate_plugin'); ?>">
			<?php _e('Jetzt aktivieren', 'padma'); ?>
		</button>
	<?php endif; ?>
</div>

<ul class="padma-advanced-filter-categories">
	<li><a class="active" data-filter="all"><?php _e('Alle', 'padma'); ?></a></li>
	<?php foreach ( $categories as $category ) : ?>
		<li><a data-filter="filter-<?php echo esc_attr(sanitize_html_class($category)); ?>"><?php echo esc_html($category); ?></a></li>
	<?php endforeach; ?>
</ul>

<div class="padma-advanced-list">
	<?php echo $items_html; ?>
</div>
