<?php
declare(strict_types=1);

require __DIR__ . '/_config.php';

$data = read_json_file(PADMA_CATALOG_INDEX);

$requestUserId = (int)($_GET['user_id'] ?? 0);
$templates = is_array($data['templates'] ?? null) ? $data['templates'] : [];

$filtered = [];
foreach ($templates as $template) {
    $visibility = (string)($template['visibility'] ?? 'public');
    $ownerUserId = (int)($template['owner_user_id'] ?? 0);

    if ($visibility === 'public' || ($requestUserId > 0 && $ownerUserId === $requestUserId)) {
        $filtered[] = $template;
    }
}

$data['templates'] = $filtered;

json_response([
    'ok' => true,
    'user_id' => $requestUserId,
    'data' => $data,
]);
