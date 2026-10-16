# Kontrak API — Mini-LMS Submission/File Service

Base URL lokal: `http://127.0.0.1:8000`

Dokumen ini menjelaskan endpoint, format request, respons, dan kode status HTTP pada layanan Submission/File Mini-LMS.

## 1. Membuat Submission

**Endpoint:** `POST /api/submissions`

**Header:**
```http
Content-Type: application/json
```

**Request Body:**
```json
{
  "assignment_id": 501,
  "student_id": 101,
  "filename": "tugas-mini-lms.zip",
  "file_size": 248000,
  "content_type": "application/zip"
}
```

**Field wajib:**

- `assignment_id` (integer): ID tugas yang harus terdaftar dan aktif di `data/assignments.json`.
- `student_id` (integer atau string nonkosong): ID mahasiswa yang mengumpulkan tugas.
- `filename` (string): nama file asli, bukan path filesystem.
- `file_size` (integer positif): ukuran file dalam byte, maksimal 10 MiB.
- `content_type` (string): tipe MIME file, misalnya `application/zip`.

**Respons sukses: `201 Created`**

Contoh respons:
```json
{
  "data": {
    "submission_id": 9005,
    "assignment_id": 501,
    "student_id": 101,
    "file_id": "f-6da324454bd4",
    "filename": "tugas-mini-lms.zip",
    "file_size": 248000,
    "content_type": "application/zip",
    "internal_location": "storage/f-6da324454bd4.blob",
    "status": "submitted",
    "created_at": "2026-10-15T13:34:25+00:00"
  }
}
```

Nilai pada contoh respons di atas diambil dari hasil pengujian Postman. ID dan waktu dapat berbeda ketika submission baru dibuat.

**Kemungkinan respons error:**

- `400 Bad Request`: JSON tidak valid atau field wajib hilang/tidak valid.
- `404 Not Found`: assignment tidak ditemukan atau tidak aktif.
- `413 Content Too Large`: ukuran yang dikirim melewati batas prototipe 10 MiB, jika kondisi tersebut ditangani oleh implementasi.
- `415 Unsupported Media Type`: request tidak menggunakan format JSON, jika kondisi tersebut ditangani oleh implementasi.

## 2. Mengambil Data Submission

**Endpoint:** `GET /api/submissions/{submission_id}`

Endpoint ini digunakan untuk mengambil metadata submission berdasarkan ID submission.

**Contoh request:**
```http
GET /api/submissions/9005
```

**Respons sukses: `200 OK`**

Jika submission ditemukan, server mengembalikan metadata submission dalam format JSON, termasuk `submission_id`, `assignment_id`, `student_id`, `file_id`, `filename`, `file_size`, `content_type`, `internal_location`, `status`, dan `created_at`.

**Kemungkinan respons error:**

- `404 Not Found`: submission dengan ID yang diminta tidak ditemukan.

## 3. Mengambil Metadata File

**Endpoint:** `GET /api/files/{file_id}`

Endpoint ini digunakan untuk mengambil metadata file berdasarkan `file_id`.

**Contoh request:**
```http
GET /api/files/f-6da324454bd4
```

**Respons sukses: `200 OK`**

Jika file ID ditemukan, server mengembalikan metadata file yang tersimpan pada submission terkait.

**Kemungkinan respons error:**

- `404 Not Found`: file ID tidak ditemukan.

## 4. Model Penyimpanan dan Batas Prototipe

Prototipe ini menyimpan metadata submission dalam format JSON di `data/submissions.json`. Implementasi saat ini belum mengunggah atau menyimpan byte file asli.

`file_id` merupakan identifier unik yang digunakan client untuk merujuk file. Sementara itu, `internal_location` merupakan informasi lokasi internal yang dikelola oleh service dan tidak boleh digunakan client untuk mengakses filesystem secara langsung.

Nama lokasi internal dibuat oleh service, bukan diambil dari path yang dikirim client. Dengan demikian, client hanya berinteraksi menggunakan endpoint API dan identifier yang disediakan.

Pada implementasi produksi, layanan perlu dikembangkan dengan dukungan upload file, validasi isi dan tipe MIME sebenarnya, autentikasi dan otorisasi, serta penyimpanan file yang aman.

## 5. Ringkasan Endpoint

| Metode | Endpoint | Fungsi | Status sukses |
|---|---|---|---|
| POST | `/api/submissions` | Membuat submission baru | `201 Created` |
| GET | `/api/submissions/{submission_id}` | Mengambil metadata submission | `200 OK` |
| GET | `/api/files/{file_id}` | Mengambil metadata file | `200 OK` |

## 6. Ringkasan Pengujian

| Skenario | Metode | Status HTTP |
|---|---|---|
| Permintaan valid | POST | `201 Created` |
| Assignment tidak ditemukan | POST | `404 Not Found` |
| Input tidak lengkap | POST | `400 Bad Request` |
| Membaca submission yang ditemukan | GET | `200 OK` |
| Membaca file ID yang ditemukan | GET | `200 OK` |

Status pada tabel pengujian merupakan hasil yang diharapkan. Hasil aktual perlu dikonfirmasi melalui Postman atau CMD.
