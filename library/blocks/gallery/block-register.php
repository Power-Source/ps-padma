<?php

require_once __DIR__ . '/depreciate.php';
require_once __DIR__ . '/block-styling.php';
require_once __DIR__ . '/block-options.php';
require_once __DIR__ . '/gallery-display.php';

$class_file = __DIR__ . '/gallery.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => padma_url() . '/library/blocks/gallery'
);

padma_register_block('PadmaGalleryBlock', padma_url() . '/library/blocks/gallery', $class_file, $icons);
