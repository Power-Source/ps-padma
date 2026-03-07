<?php

$class_file = __DIR__ . '/powerform.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => padma_url() . '/library/blocks/powerform'
);
padma_register_block('PadmaPowerformBlock', padma_url() . '/library/blocks/powerform', $class_file, $icons);
