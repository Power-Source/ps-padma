<?php
declare(strict_types=1);

const PADMA_CATALOG_API_KEY = '8f9a7e44e2a9c1b0d6f3a8b2c5d7e1f490ab63cd12ef45a6789bc0de1f234567';
const PADMA_CATALOG_ROOT = __DIR__ . '/..';
const PADMA_CATALOG_INDEX = PADMA_CATALOG_ROOT . '/index/templates.json';
const PADMA_CATALOG_STABLE = PADMA_CATALOG_ROOT . '/index/channels/stable.json';
const PADMA_CATALOG_STORAGE = __DIR__ . '/../../../../padma-catalog-storage';
const PADMA_CATALOG_UPLOADS = PADMA_CATALOG_STORAGE . '/uploads';
const PADMA_CATALOG_TMP = PADMA_CATALOG_STORAGE . '/tmp';
const PADMA_CATALOG_LOGS = PADMA_CATALOG_STORAGE . '/logs';
const PADMA_CATALOG_PUBLIC_TEMPLATES = PADMA_CATALOG_ROOT . '/templates';
const PADMA_MEMBERSHIP_API_BASE = 'https://nerdservice.eimen.net/wp-json/membership2/v1';
const PADMA_MEMBERSHIP_API_PASS_KEY = 'HarvH5Ri4E5QOUBS';
const PADMA_MEMBERSHIP_REQUIRED_ID = 582;

function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function require_api_key(): void {
    $key = $_SERVER['HTTP_X_PADMA_API_KEY'] ?? '';

    if ($key !== PADMA_CATALOG_API_KEY) {
        json_response(['ok' => false, 'error' => 'unauthorized'], 401);
    }
}

function read_json_file(string $path): array {
    if (!is_file($path)) {
        return [];
    }

    $raw = file_get_contents($path);

    if ($raw === false) {
        return [];
    }

    $decoded = json_decode($raw, true);

    return is_array($decoded) ? $decoded : [];
}

function write_json_file(string $path, array $data): void {
    $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    if ($encoded === false) {
        json_response(['ok' => false, 'error' => 'json_encode_failed'], 500);
    }

    $result = file_put_contents($path, $encoded . "\n");

    if ($result === false) {
        json_response(['ok' => false, 'error' => 'json_write_failed'], 500);
    }
}

function ensure_dir(string $path): void {
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        json_response(['ok' => false, 'error' => 'mkdir_failed', 'path' => $path], 500);
    }
}

function sanitize_slug(string $value): string {
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9-]+/', '-', $value) ?? '';
    $value = trim($value, '-');
    return $value;
}

function sanitize_version(string $value): string {
    $value = trim($value);
    return preg_match('/^[0-9]+\.[0-9]+\.[0-9]+(?:-[a-z0-9.-]+)?$/i', $value) ? $value : '';
}

function now_utc_iso8601(): string {
    return gmdate('c');
}

function sanitize_visibility(string $value): string {
    $value = strtolower(trim($value));
    return in_array($value, ['public', 'private'], true) ? $value : 'private';
}

function read_post_user_id(): int {
    $value = $_POST['user_id'] ?? $_GET['user_id'] ?? '';
    $userId = (int)$value;

    if ($userId <= 0) {
        json_response(['ok' => false, 'error' => 'invalid_user_id'], 400);
    }

    return $userId;
}

function http_get_json(string $url): array {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 15,
            'ignore_errors' => true,
            'header' => "Accept: application/json\r\nUser-Agent: PadmaCatalog/1.0\r\n",
        ],
    ]);

    $raw = file_get_contents($url, false, $context);
    if ($raw === false) {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function membership_user_has_required_plan(int $userId): bool {
    $query = http_build_query([
        'membership_id' => PADMA_MEMBERSHIP_REQUIRED_ID,
        'user_id' => $userId,
        'pass_key' => PADMA_MEMBERSHIP_API_PASS_KEY,
    ]);

    $url = PADMA_MEMBERSHIP_API_BASE . '/member/subscription?' . $query;
    $response = http_get_json($url);

    if (($response['code'] ?? '') === 'rest_user_cannot_view') {
        return false;
    }

    if (($response['membership_id'] ?? null) == PADMA_MEMBERSHIP_REQUIRED_ID && (int)($response['user_id'] ?? 0) === $userId) {
        $status = strtolower((string)($response['member_status'] ?? $response['status'] ?? 'active'));
        $allowed = ['active', 'trial', 'testversion'];
        return in_array($status, $allowed, true) || $status === '';
    }

    return false;
}

function require_membership_access(int $userId): void {
    if (!membership_user_has_required_plan($userId)) {
        json_response([
            'ok' => false,
            'error' => 'membership_required',
            'membership_id' => PADMA_MEMBERSHIP_REQUIRED_ID,
        ], 403);
    }
}

function upload_meta_path(string $uploadId): string {
    return PADMA_CATALOG_UPLOADS . '/' . $uploadId . '.json';
}
