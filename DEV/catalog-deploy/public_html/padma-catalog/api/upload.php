<?php
declare(strict_types=1);

require __DIR__ . '/_config.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(['ok' => false, 'error' => 'method_not_allowed'], 405);
}

require_api_key();

$userId = read_post_user_id();
require_membership_access($userId);

$visibility = sanitize_visibility((string)($_POST['visibility'] ?? 'private'));

ensure_dir(PADMA_CATALOG_UPLOADS);
ensure_dir(PADMA_CATALOG_TMP);
ensure_dir(PADMA_CATALOG_LOGS);

if (!isset($_FILES['template_zip'])) {
    json_response(['ok' => false, 'error' => 'missing_template_zip'], 400);
}

$file = $_FILES['template_zip'];

if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    json_response(['ok' => false, 'error' => 'upload_failed', 'code' => $file['error'] ?? 'unknown'], 400);
}

$originalName = (string)($file['name'] ?? 'template.zip');
$tmpName = (string)($file['tmp_name'] ?? '');

if ($tmpName === '' || !is_uploaded_file($tmpName)) {
    json_response(['ok' => false, 'error' => 'invalid_upload_tmp'], 400);
}

$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
if ($extension !== 'zip') {
    json_response(['ok' => false, 'error' => 'invalid_file_type', 'message' => 'Only .zip files are allowed'], 400);
}

$uploadId = bin2hex(random_bytes(12));
$targetZip = PADMA_CATALOG_UPLOADS . '/' . $uploadId . '.zip';

if (!move_uploaded_file($tmpName, $targetZip)) {
    json_response(['ok' => false, 'error' => 'move_failed'], 500);
}

write_json_file(upload_meta_path($uploadId), [
    'user_id' => $userId,
    'visibility' => $visibility,
    'uploaded_at' => now_utc_iso8601(),
]);

json_response([
    'ok' => true,
    'upload_id' => $uploadId,
    'user_id' => $userId,
    'visibility' => $visibility,
    'file' => basename($targetZip),
    'next' => 'POST publish.php with upload_id',
]);
