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

<ul class="padma-advanced-filter-categories">
	<li><a class="active" data-filter="all"><?php _e('Alle', 'padma'); ?></a></li>
	<?php foreach ( $categories as $category ) : ?>
		<li><a data-filter="filter-<?php echo esc_attr(sanitize_html_class($category)); ?>"><?php echo esc_html($category); ?></a></li>
	<?php endforeach; ?>
</ul>

<div class="padma-advanced-list">
	<?php echo $items_html; ?>
</div>
