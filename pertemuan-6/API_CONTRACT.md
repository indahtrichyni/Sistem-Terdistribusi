# Kontrak API — Mini-LMS Submission/File Service

Base URL lokal: `http://127.0.0.1:8000`

## 1. Membuat submission

`POST /api/submissions`

Header: `Content-Type: application/json`

Request body:
```json
{
  "assignment_id": 501,
  "student_id": 101,
  "filename": "tugas-mini-lms.zip",
  "file_size": 248000,
  "content_type": "application/zip"
}
```

Field wajib:
- `assignment_id` (integer): ID assignment yang harus terdaftar di `data/assignments.json`.
- `student_id` (integer atau string nonkosong): ID mahasiswa.
- `filename` (string): nama file asli, bukan path.
- `file_size` (integer positif): ukuran file dalam byte.
- `content_type` (string): MIME type file.

Respons sukses: `201 Created`
```json
{
  "data": {
    "submission_id": 9001,
    "assignment_id": 501,
    "student_id": 101,
    "file_id": "f-<unik>",
    "filename": "tugas-mini-lms.zip",
    "file_size": 248000,
    "content_type": "application/zip",
    "internal_location": "storage/<nama-internal>",
    "status": "submitted",
    "created_at": "2026-10-15T..."
  }
}
```

Error yang mungkin:
- `400 Bad Request`: JSON tidak valid, field wajib hilang/tidak valid.
- `404 Not Found`: `assignment_id` tidak terdaftar.
- `413 Content Too Large`: ukuran metadata melewati batas prototipe 10 MiB.
- `415 Unsupported Media Type`: request bukan JSON.

## 2. Mengambil submission

`GET /api/submissions/{submission_id}`

- `200 OK`: metadata submission ditemukan.
- `404 Not Found`: submission tidak ditemukan.

Contoh respons:
```json
{
  "data": {
    "submission_id": 9001,
    "assignment_id": 501,
    "student_id": 101,
    "file_id": "f-<unik>",
    "filename": "tugas-mini-lms.zip",
    "file_size": 248000,
    "content_type": "application/zip",
    "internal_location": "storage/<nama-internal>",
    "status": "submitted",
    "created_at": "2026-10-15T..."
  }
}
```

## 3. Mengambil metadata file

`GET /api/files/{file_id}`

- `200 OK`: metadata file ditemukan.
- `404 Not Found`: file ID tidak ditemukan.

## Model penyimpanan dan batas prototipe

Prototipe ini menyimpan **metadata** sebagai JSON di `data/submissions.json`; belum mengunggah byte file asli. `file_id` adalah identifier stabil untuk client. `internal_location` merupakan lokasi internal yang direncanakan untuk penyimpanan file dan tidak dipakai client untuk mengakses filesystem secara langsung. Nama internal dibuat oleh service, bukan diambil dari path yang dikirim client. Pada implementasi produksi, tambahkan upload multipart, pemeriksaan isi/MIME sebenarnya, autentikasi/otorisasi, dan penyimpanan file yang sesuai.
