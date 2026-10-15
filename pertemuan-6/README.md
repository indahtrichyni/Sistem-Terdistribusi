# Mini-LMS — Submission/File Service (Pertemuan 6)

Prototipe PHP sederhana untuk Sistem Terdistribusi. Implementasi ini menerima metadata pengumpulan melalui `POST`, menyimpan metadata ke JSON, dan menyediakan `GET` untuk mencari submission atau metadata file. Sesuai batas praktik pada materi, isi file fisik belum diunggah; lokasi internal hanya dicatat sebagai rancangan penyimpanan.

## Struktur
```text
sistem-terdistribusi/
└── pertemuan-6/
    ├── data/
    │   └── assignments.json
    ├── docs/
    │   ├── bukti/
    │   ├── screenshots/
    │   └── TESTING.md
    ├── .gitignore
    ├── API_CONTRACT.md
    ├── Mini-LMS.postman_collection.json
    ├── README.md
    ├── gen_screenshots.py
    ├── index.php
    └── test_api.sh
```

## Kebutuhan
- PHP 8.0+ dengan ekstensi JSON.
- `curl` untuk menjalankan skrip pengujian (opsional).
- Python 3 untuk membuat ulang ilustrasi bukti SVG (opsional).

## Menjalankan
Dari direktori `pertemuan-6`:
```bash
php -S 127.0.0.1:8000 index.php
```
Biarkan terminal ini tetap berjalan. Buka terminal kedua untuk menjalankan pengujian.

## Pengujian otomatis
```bash
chmod +x test_api.sh
./test_api.sh
```
Skrip memeriksa:
1. Request valid menghasilkan `201`.
2. Assignment yang tidak ada menghasilkan `404`.
3. Input tidak lengkap menghasilkan `400`.
4. GET submission hasil request valid menghasilkan `200`.

Atau coba manual:
```bash
curl -i -X POST http://127.0.0.1:8000/api/submissions \
  -H 'Content-Type: application/json' \
  -d '{"assignment_id":501,"student_id":101,"filename":"tugas.zip","file_size":248000,"content_type":"application/zip"}'
curl -i http://127.0.0.1:8000/api/submissions/9001
```
ID submission sebenarnya dapat berbeda; gunakan `submission_id` yang dikembalikan dari respons POST.

## Identifier, lokasi internal, dan respons
- **`submission_id`** mengidentifikasi satu pengumpulan.
- **`file_id`** (format `f-...`) mengidentifikasi metadata file secara stabil.
- **`internal_location`** adalah lokasi internal yang ditentukan service, bukan path dari client dan tidak dimaksudkan untuk ditampilkan sebagai alamat akses publik.
- Respons menggunakan JSON dengan objek `data` saat berhasil dan `error` saat gagal.

## Bukti pengujian
Berkas SVG di `docs/screenshots/` adalah ilustrasi respons uji yang dapat dibuka di browser. Untuk bukti tangkapan layar aktual, jalankan service dan `test_api.sh`, lalu ambil screenshot terminal/response aktual dan simpan di `docs/bukti/`. Jalankan `python3 gen_screenshots.py` untuk membuat ulang SVG.

## Pembagian kontribusi anggota
Lengkapi sesuai anggota kelompok yang sebenarnya sebelum dikumpulkan:
- **Anggota 1 — [Nama/NIM]:** desain kontrak API dan dokumentasi.
- **Anggota 2 — [Nama/NIM]:** implementasi endpoint PHP dan metadata JSON.
- **Anggota 3 — [Nama/NIM]:** pengujian request valid/gagal dan bukti.
- **Seluruh anggota:** review, integrasi, dan persiapan pengumpulan.

> Ganti placeholder kontribusi dengan nama, NIM, dan pekerjaan nyata setiap anggota. Satu pengumpulan dilakukan untuk satu kelompok.
