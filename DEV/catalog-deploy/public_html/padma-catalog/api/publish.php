<?php
declare(strict_types=1);

require __DIR__ . '/_config.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(['ok' => false, 'error' => 'method_not_allowed'], 405);
}

require_api_key();

if (!class_exists('ZipArchive')) {
    json_response(['ok' => false, 'error' => 'ziparchive_missing'], 500);
}

$uploadId = trim((string)($_POST['upload_id'] ?? ''));
if ($uploadId === '' || !preg_match('/^[a-f0-9]{24}$/', $uploadId)) {
    json_response(['ok' => false, 'error' => 'invalid_upload_id'], 400);
}

$zipPath = PADMA_CATALOG_UPLOADS . '/' . $uploadId . '.zip';
if (!is_file($zipPath)) {
    json_response(['ok' => false, 'error' => 'upload_not_found'], 404);
}

$uploadMetaPath = upload_meta_path($uploadId);
$uploadMeta = read_json_file($uploadMetaPath);
$ownerUserId = (int)($uploadMeta['user_id'] ?? 0);
$visibility = sanitize_visibility((string)($uploadMeta['visibility'] ?? 'private'));

if ($ownerUserId <= 0) {
    json_response(['ok' => false, 'error' => 'upload_meta_missing'], 400);
}

require_membership_access($ownerUserId);

$extractDir = PADMA_CATALOG_TMP . '/' . $uploadId;
ensure_dir($extractDir);

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    json_response(['ok' => false, 'error' => 'zip_open_failed'], 400);
}

if (!$zip->extractTo($extractDir)) {
    $zip->close();
    json_response(['ok' => false, 'error' => 'zip_extract_failed'], 400);
}
$zip->close();

$manifestPath = $extractDir . '/manifest.json';
$legacyManifestPath = $extractDir . '/template-manifest.json';

if (!is_file($manifestPath) && !is_file($legacyManifestPath)) {
    json_response(['ok' => false, 'error' => 'manifest_missing'], 400);
}

$manifest = is_file($manifestPath) ? read_json_file($manifestPath) : read_json_file($legacyManifestPath);

$name = trim((string)($manifest['name'] ?? ''));
$slug = sanitize_slug((string)($manifest['slug'] ?? ''));

if ($slug === '' && $name !== '') {
    $slug = sanitize_slug($name);
}

$version = sanitize_version((string)($manifest['version'] ?? ''));
if ($version === '') {
    $version = '1.0.0';
}
$channel = strtolower(trim((string)($manifest['channel'] ?? 'stable')));
$channel = in_array($channel, ['stable', 'beta'], true) ? $channel : 'stable';

if ($slug === '' || $name === '') {
    json_response(['ok' => false, 'error' => 'manifest_invalid', 'required' => ['slug', 'name', 'version']], 400);
}

ensure_dir(PADMA_CATALOG_PUBLIC_TEMPLATES);
$slugDir = PADMA_CATALOG_PUBLIC_TEMPLATES . '/' . $slug;
ensure_dir($slugDir);

$publicZip = $slugDir . '/' . $version . '.zip';
if (!copy($zipPath, $publicZip)) {
    json_response(['ok' => false, 'error' => 'publish_copy_failed'], 500);
}

$checksum = hash_file('sha256', $publicZip) ?: '';
$downloadUrl = '/padma-catalog/templates/' . $slug . '/' . $version . '.zip';

$index = read_json_file(PADMA_CATALOG_INDEX);
if (!isset($index['templates']) || !is_array($index['templates'])) {
    $index['templates'] = [];
}

$templateFound = false;
foreach ($index['templates'] as &$template) {
    if (($template['slug'] ?? '') !== $slug) {
        continue;
    }

    $templateFound = true;
    $template['name'] = $name;
    $template['owner_user_id'] = $ownerUserId;
    $template['visibility'] = $visibility;
    $template['updated_at'] = now_utc_iso8601();

    if (!isset($template['versions']) || !is_array($template['versions'])) {
        $template['versions'] = [];
    }

    $versionUpdated = false;
    foreach ($template['versions'] as &$v) {
        if (($v['version'] ?? '') === $version) {
            $v['channel'] = $channel;
            $v['checksum'] = $checksum;
            $v['download_url'] = $downloadUrl;
            $v['owner_user_id'] = $ownerUserId;
            $v['visibility'] = $visibility;
            $v['published_at'] = now_utc_iso8601();
            $versionUpdated = true;
            break;
        }
    }
    unset($v);

    if (!$versionUpdated) {
        $template['versions'][] = [
            'version' => $version,
            'channel' => $channel,
            'checksum' => $checksum,
            'download_url' => $downloadUrl,
            'owner_user_id' => $ownerUserId,
            'visibility' => $visibility,
            'published_at' => now_utc_iso8601(),
        ];
    }

    usort($template['versions'], static function(array $a, array $b): int {
        return version_compare((string)$b['version'], (string)$a['version']);
    });

    $template['latest'] = $template['versions'][0]['version'] ?? $version;
    break;
}
unset($template);

if (!$templateFound) {
    $index['templates'][] = [
        'slug' => $slug,
        'name' => $name,
        'latest' => $version,
        'owner_user_id' => $ownerUserId,
        'visibility' => $visibility,
        'updated_at' => now_utc_iso8601(),
        'versions' => [
            [
                'version' => $version,
                'channel' => $channel,
                'checksum' => $checksum,
                'download_url' => $downloadUrl,
                'owner_user_id' => $ownerUserId,
                'visibility' => $visibility,
                'published_at' => now_utc_iso8601(),
            ],
        ],
    ];
}

$index['generated_at'] = now_utc_iso8601();
write_json_file(PADMA_CATALOG_INDEX, $index);

$stable = [
    'channel' => 'stable',
    'generated_at' => now_utc_iso8601(),
    'templates' => [],
];

foreach (($index['templates'] ?? []) as $template) {
    $stableVersion = null;
    foreach (($template['versions'] ?? []) as $v) {
        if (($v['channel'] ?? 'stable') === 'stable') {
            $stableVersion = $v;
            break;
        }
    }

    if ($stableVersion === null) {
        continue;
    }

    if (($template['visibility'] ?? 'public') !== 'public') {
        continue;
    }

    $stable['templates'][] = [
        'slug' => $template['slug'] ?? '',
        'name' => $template['name'] ?? '',
        'version' => $stableVersion['version'] ?? '',
        'checksum' => $stableVersion['checksum'] ?? '',
        'download_url' => $stableVersion['download_url'] ?? '',
    ];
}

write_json_file(PADMA_CATALOG_STABLE, $stable);

json_response([
    'ok' => true,
    'slug' => $slug,
    'name' => $name,
    'version' => $version,
    'owner_user_id' => $ownerUserId,
    'visibility' => $visibility,
    'channel' => $channel,
    'checksum' => $checksum,
    'download_url' => $downloadUrl,
]);
