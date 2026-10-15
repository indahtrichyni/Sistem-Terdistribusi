<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

const MAX_FILE_SIZE = 10485760; // 10 MiB untuk metadata prototipe
const ASSIGNMENTS_PATH = __DIR__ . '/data/assignments.json';
const SUBMISSIONS_PATH = __DIR__ . '/data/submissions.json';

function respond(int $status, array $body): never {
    http_response_code($status);
    echo json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}
function readJsonFile(string $path, array $default): array {
    if (!is_file($path)) return $default;
    $raw = file_get_contents($path);
    $decoded = json_decode($raw === false ? '' : $raw, true);
    return is_array($decoded) ? $decoded : $default;
}
function saveSubmissions(array $rows): bool {
    $json = json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return $json !== false && file_put_contents(SUBMISSIONS_PATH, $json . PHP_EOL, LOCK_EX) !== false;
}
function jsonBody(): array {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw === false ? '' : $raw, true);
    if (!is_array($decoded)) respond(400, ['error' => ['code' => 'invalid_json', 'message' => 'Body harus berupa JSON object yang valid.']]);
    return $decoded;
}
function findSubmission(array $rows, int $id): ?array {
    foreach ($rows as $row) if ((int)($row['submission_id'] ?? 0) === $id) return $row;
    return null;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$assignments = readJsonFile(ASSIGNMENTS_PATH, ['assignments' => []]);
$rows = readJsonFile(SUBMISSIONS_PATH, []);

if ($uri === '/' && $method === 'GET') {
    respond(200, ['service' => 'Mini-LMS Submission/File Service', 'version' => '1.0', 'endpoints' => [
        'POST /api/submissions', 'GET /api/submissions/{submission_id}', 'GET /api/files/{file_id}'
    ]]);
}

if ($uri === '/api/submissions' && $method === 'POST') {
    $contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
    if ($contentType !== 'application/json') respond(415, ['error' => ['code' => 'unsupported_media_type', 'message' => 'Gunakan Content-Type: application/json.']]);
    $input = jsonBody();
    $required = ['assignment_id', 'student_id', 'filename', 'file_size', 'content_type'];
    $missing = [];
    foreach ($required as $field) if (!array_key_exists($field, $input) || $input[$field] === '' || $input[$field] === null) $missing[] = $field;
    if ($missing) respond(400, ['error' => ['code' => 'input_incomplete', 'message' => 'Field wajib belum lengkap.', 'fields' => $missing]]);
    if (!is_numeric($input['assignment_id']) || !is_numeric($input['file_size']) || (int)$input['file_size'] < 1 ||
        !is_string($input['filename']) || trim($input['filename']) === '' ||
        !is_string($input['content_type']) || trim($input['content_type']) === '' ||
        !(is_string($input['student_id']) || is_int($input['student_id']))) {
        respond(400, ['error' => ['code' => 'invalid_field', 'message' => 'Tipe atau nilai field tidak valid.']]);
    }
    $size = (int)$input['file_size'];
    if ($size > MAX_FILE_SIZE) respond(413, ['error' => ['code' => 'file_too_large', 'message' => 'Ukuran metadata file melewati batas 10 MiB.']]);
    $assignmentId = (int)$input['assignment_id'];
    $found = false;
    foreach (($assignments['assignments'] ?? []) as $assignment) {
        if ((int)($assignment['assignment_id'] ?? 0) === $assignmentId && ($assignment['active'] ?? false) === true) { $found = true; break; }
    }
    if (!$found) respond(404, ['error' => ['code' => 'assignment_not_found', 'message' => 'Assignment tidak ditemukan atau tidak aktif.', 'assignment_id' => $assignmentId]]);
    $nextId = 9001;
    foreach ($rows as $row) $nextId = max($nextId, (int)($row['submission_id'] ?? 9000) + 1);
    $fileId = 'f-' . bin2hex(random_bytes(6));
    $safeName = basename(str_replace('\\', '/', trim($input['filename'])));
    $internalName = $fileId . '.blob';
    $record = [
        'submission_id' => $nextId,
        'assignment_id' => $assignmentId,
        'student_id' => $input['student_id'],
        'file_id' => $fileId,
        'filename' => $safeName,
        'file_size' => $size,
        'content_type' => trim($input['content_type']),
        'internal_location' => 'storage/' . $internalName,
        'status' => 'submitted',
        'created_at' => gmdate('c')
    ];
    $rows[] = $record;
    if (!saveSubmissions($rows)) respond(500, ['error' => ['code' => 'storage_error', 'message' => 'Metadata gagal disimpan.']]);
    respond(201, ['data' => $record]);
}

if (preg_match('#^/api/submissions/([0-9]+)$#', $uri, $m) && $method === 'GET') {
    $record = findSubmission($rows, (int)$m[1]);
    if ($record === null) respond(404, ['error' => ['code' => 'submission_not_found', 'message' => 'Submission tidak ditemukan.']]);
    respond(200, ['data' => $record]);
}
if (preg_match('#^/api/files/([A-Za-z0-9-]+)$#', $uri, $m) && $method === 'GET') {
    foreach ($rows as $record) if (($record['file_id'] ?? '') === $m[1]) respond(200, ['data' => [
        'file_id' => $record['file_id'], 'submission_id' => $record['submission_id'],
        'filename' => $record['filename'], 'file_size' => $record['file_size'],
        'content_type' => $record['content_type'], 'internal_location' => $record['internal_location'],
        'status' => $record['status']
    ]]);
    respond(404, ['error' => ['code' => 'file_not_found', 'message' => 'File ID tidak ditemukan.']]);
}
if (str_starts_with($uri, '/api/')) respond(405, ['error' => ['code' => 'method_or_endpoint_not_allowed', 'message' => 'Endpoint atau metode tidak tersedia.']]);
respond(404, ['error' => ['code' => 'not_found', 'message' => 'Endpoint tidak ditemukan.']]);
