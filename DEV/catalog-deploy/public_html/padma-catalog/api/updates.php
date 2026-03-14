<?php
declare(strict_types=1);

require __DIR__ . '/_config.php';

$data = read_json_file(PADMA_CATALOG_STABLE);

json_response([
    'ok' => true,
    'channel' => 'stable',
    'data' => $data,
]);
