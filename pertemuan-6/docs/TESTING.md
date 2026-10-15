# Catatan Pengujian

## Prasyarat
Jalankan server dari folder `pertemuan-6`:
```bash
php -S 127.0.0.1:8000 index.php
```

Pada terminal lain:
```bash
./test_api.sh
```

## Skenario dan hasil yang diharapkan

| Skenario | Request | Hasil |
|---|---|---|
| Permintaan valid | POST dengan seluruh field wajib dan `assignment_id: 501` | `201 Created`, berisi `submission_id` dan `file_id` |
| Assignment tidak ditemukan | POST dengan `assignment_id: 99999` | `404 Not Found` |
| Input tidak lengkap | POST tanpa `student_id` atau field wajib lainnya | `400 Bad Request` |
| Baca kembali | GET ID submission yang dibuat | `200 OK` dan metadata JSON |

## Catatan bukti
SVG di folder `screenshots` merupakan ilustrasi respons yang diharapkan, bukan tangkapan layar browser/terminal langsung. Untuk bukti final, simpan tangkapan layar aktual setelah menjalankan tes ke `docs/bukti/`. Catat waktu, perintah, status HTTP, dan response body yang terlihat.
