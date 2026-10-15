<?php
header('Content-Type: application/json');

// 1. FUNGSI RESOLVER (Menerjemahkan nama logis ke alamat jaringan)[cite: 76]
function resolveService(string $name): ?string {
    $configFile = __DIR__ . '/services.json';
    if (!file_exists($configFile)) {
        return null;
    }
    
    $json = file_get_contents($configFile);
    $services = json_decode($json, true);
    
    return $services[$name] ?? null;
}

// 2. TANGKAP REQUEST DARI CLIENT (Misal: GET /?service=course)
$targetName = $_GET['service'] ?? '';

if (empty($targetName)) {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter ?service= wajib diisi']);
    exit;
}

// 3. PROSES LOOKUP KE REGISTRY
$targetUrl = resolveService($targetName);

// 4. PENANGANAN ERROR TAHAP 1: Nama Tidak Ditemukan
if ($targetUrl === null) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Konfigurasi Error',
        'message' => "Binding untuk nama service '{$targetName}' tidak ditemukan di registry."
    ]);
    exit;
}

// 5. PENANGANAN ERROR TAHAP 2: Mencoba Koneksi ke Service Target
$context = stream_context_create([
    'http' => ['ignore_errors' => true, 'timeout' => 3] // Timeout 3 detik agar tidak macet
]);

$response = @file_get_contents($targetUrl, false, $context);

// PENANGANAN ERROR TAHAP 3: Service Tidak Merespons / Mati[cite: 79]
if ($response === false) {
    http_response_code(503);
    echo json_encode([
        'error' => 'Service Unavailable',
        'message' => "Lookup berhasil ke {$targetUrl}, namun service tujuan mati atau tidak merespons."
    ]);
    exit;
}

// 6. SUKSES: Kembalikan respons dari service tujuan ke client
http_response_code(200);
echo $response;