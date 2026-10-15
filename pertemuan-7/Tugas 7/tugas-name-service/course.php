<?php
header('Content-Type: application/json');

// Mengembalikan respons sukses beserta port aktif untuk membuktikan keberhasilan pengujian
echo json_encode([
    'service' => 'course-service',
    'status' => 'Data mata kuliah berhasil diambil',
    'port_aktif' => $_SERVER['SERVER_PORT']
]);