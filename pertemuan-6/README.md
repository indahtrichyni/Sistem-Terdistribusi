# Mini-LMS — Submission/File Service (Pertemuan 6)

Prototipe PHP sederhana untuk mata kuliah Sistem Terdistribusi yang berfungsi menangani pengumpulan tugas pada sistem Mini-LMS. Layanan ini menerima metadata pengumpulan melalui metode HTTP `POST`, menyimpan metadata dalam format JSON, serta menyediakan metode `GET` untuk membaca data submission dan metadata file.

Prototipe ini berfokus pada pengelolaan metadata file. Isi file fisik belum diunggah ke server, sedangkan lokasi internal file hanya dicatat sebagai rancangan penyimpanan. Dengan demikian, layanan ini menggambarkan pemisahan antara metadata submission dan rancangan lokasi penyimpanan file.

## Identitas Kelompok

| No. | Nama | NIM |
|---:|---|---|
| 1 | Indah Tricahyani | 240209501078 |
| 2 | Aulia Aqilah | 240209501038 |
| 3 | Mutia Inayah | 230209552025 |
| 4 | Arjun | 240209502022 |

## Struktur Folder

```text
pertemuan-6/
├── data/
│   └── assignments.json
├── docs/
│   ├── bukti/
│   ├── screenshots/
│   └── TESTING.md
├── API_CONTRACT.md
├── Mini-LMS.postman_collection.json
├── README.md
├── gen_screenshots.py
├── index.php
└── test_api.sh
```

**Keterangan folder dan file:**

- `data/assignments.json`: menyimpan data awal assignment atau tugas yang tersedia.
- `docs/bukti/`: tempat menyimpan bukti mentah hasil pengujian API, jika tersedia.
- `docs/screenshots/`: tempat menyimpan screenshot atau ilustrasi hasil pengujian.
- `docs/TESTING.md`: berisi dokumentasi skenario dan hasil pengujian.
- `API_CONTRACT.md`: menjelaskan endpoint, metode HTTP, format request dan response, serta kode status API.
- `Mini-LMS.postman_collection.json`: koleksi request API yang dapat diimpor ke Postman.
- `README.md`: dokumentasi utama proyek.
- `gen_screenshots.py`: skrip Python untuk membuat ilustrasi screenshot SVG.
- `index.php`: implementasi layanan PHP yang menangani request dan response API.
- `test_api.sh`: skrip pengujian otomatis menggunakan shell dan curl.

## Kebutuhan Sistem

Untuk menjalankan dan menguji prototipe ini, diperlukan:

- PHP 8.0 atau lebih baru dengan ekstensi JSON.
- Postman untuk melakukan pengujian API secara manual.
- `curl` untuk mengirim request melalui terminal.
- Python 3 untuk membuat ulang ilustrasi screenshot SVG (opsional).
- Bash atau Git Bash untuk menjalankan skrip pengujian otomatis.

## Menjalankan Server

Buka terminal pada direktori `pertemuan-6`, kemudian jalankan perintah berikut:

```bash
php -S 127.0.0.1:8000 index.php
```

Biarkan terminal tetap berjalan selama pengujian berlangsung.

Jika server berhasil dijalankan, layanan dapat diakses melalui alamat:

`http://127.0.0.1:8000`

Alamat tersebut menggunakan server lokal sehingga layanan dapat diakses dari komputer tempat server dijalankan.

## Pengujian API

Pengujian API dapat dilakukan menggunakan Postman atau skrip pengujian otomatis yang disediakan dalam proyek. Pengujian bertujuan memeriksa apakah layanan menerima request, memvalidasi input, dan memberikan status HTTP sesuai dengan kondisi permintaan.

### 1. Menjalankan Pengujian Otomatis

Pada terminal kedua, masuk ke direktori `pertemuan-6`, kemudian jalankan:

```bash
chmod +x test_api.sh
./test_api.sh
```

Perintah tersebut digunakan pada lingkungan yang mendukung shell Unix, seperti Linux atau Git Bash. Pada Windows, pengujian juga dapat dilakukan menggunakan Postman atau perintah `curl`.

### 2. Skenario Pengujian

| No. | Skenario Pengujian | Metode | Status HTTP yang Diharapkan |
|---:|---|---|---|
| 1 | Permintaan submission valid | `POST` | `201 Created` |
| 2 | Assignment tidak ditemukan | `POST` | `404 Not Found` |
| 3 | Input tidak lengkap | `POST` | `400 Bad Request` |
| 4 | Membaca data submission | `GET` | `200 OK` |

Status pada tabel merupakan hasil yang diharapkan untuk setiap skenario dan perlu dicocokkan dengan hasil pengujian aktual.

### 3. Contoh Permintaan API

**a. Membuat submission**

```bash
curl -i -X POST http://127.0.0.1:8000/api/submissions \
  -H "Content-Type: application/json" \
  -d '{"assignment_id":501,"student_id":101,"filename":"tugas.zip","file_size":248000,"content_type":"application/zip"}'
```

Request tersebut digunakan untuk mengirim metadata pengumpulan tugas dalam format JSON. Jika data valid dan assignment ditemukan, layanan diharapkan mengembalikan status `201 Created` beserta informasi submission yang berhasil dibuat.

**b. Membaca data submission**

```bash
curl -i http://127.0.0.1:8000/api/submissions/9005
```

Gunakan `submission_id` yang benar-benar diperoleh dari respons request `POST`. Angka `9005` hanya contoh dan perlu diganti jika ID submission yang tersimpan berbeda.

## Identifier dan Format Respons

Layanan menggunakan identifier dan format respons JSON untuk membantu client mengakses data melalui API.

- **`submission_id`**: identifier untuk membedakan setiap data pengumpulan tugas.
- **`file_id`**: identifier untuk membedakan metadata file.
- **`internal_location`**: informasi lokasi internal yang dicatat sebagai rancangan penyimpanan file.
- **`data`**: objek yang berisi informasi hasil permintaan yang berhasil.
- **`error`**: objek yang berisi informasi kesalahan ketika permintaan gagal.

Respons API menggunakan format JSON agar informasi dapat dibaca dan diproses oleh aplikasi lain. Client menggunakan identifier untuk merujuk pada data tanpa harus menentukan lokasi fisik file di server.

Pada prototipe ini, metadata file dikelola oleh layanan, sedangkan isi file fisik belum diunggah ke server.

## Bukti Pengujian
Ringkasan: [`docs/TESTING.md`](docs/TESTING.md)
Skenario yang didokumentasikan meliputi:

1. Permintaan submission valid dengan status yang diharapkan `201 Created`.
2. Assignment tidak ditemukan dengan status yang diharapkan `404 Not Found`.
3. Input tidak lengkap dengan status yang diharapkan `400 Bad Request`.
4. Membaca data submission dengan status yang diharapkan `200 OK`.

## Kontribusi Anggota Kelompok

| No. | Nama | NIM | Kontribusi |
|---:|---|---|---|
| 1 | Indah Tricahyani | 240209501078 | Melakukan pengujian API menggunakan Postman dan terminal, memeriksa skenario permintaan valid maupun gagal, mengevaluasi respons dan status HTTP, menyiapkan bukti hasil pengujian, menyusun catatan pengujian, serta merapikan dokumentasi proyek dan membuat hyperlink ke folder atau file terkait di GitHub. |
| 2 | Aulia Aqilah | 240209501038 | Merancang dan mendokumentasikan kontrak API, meliputi endpoint, metode HTTP, format request dan response, serta ketentuan status HTTP pada `API_CONTRACT.md`. |
| 3 | Mutia Inayah | 230209552025 | Mengimplementasikan dan memeriksa endpoint PHP, mengelola metadata submission dan file dalam format JSON, serta melakukan validasi data yang diterima oleh layanan. |
| 4 | Arjun | 240209502022 | Memberikan pendapat dan masukan terkait skenario pengujian API serta membantu diskusi mengenai pemeriksaan hasil pengujian. |

Seluruh anggota kelompok berkontribusi dalam diskusi, pemeriksaan hasil implementasi, integrasi komponen, dan persiapan pengumpulan proyek.

## Kesimpulan

Proyek Mini-LMS Submission/File Service merupakan prototipe layanan sederhana yang menerapkan komunikasi HTTP dalam Sistem Terdistribusi. Melalui metode `POST` dan `GET`, layanan dapat menerima data submission, memvalidasi permintaan, serta membaca kembali metadata yang tersimpan dalam format JSON.

Prototipe ini juga menunjukkan konsep pemisahan metadata dari isi file fisik. Walaupun file belum benar-benar diunggah ke server, pencatatan identifier dan rancangan lokasi internal menjadi dasar untuk pengembangan layanan penyimpanan file pada tahap selanjutnya.

Pengujian dilakukan untuk memeriksa respons layanan terhadap permintaan valid maupun tidak valid, dengan hasil aktual yang dicatat dalam dokumentasi pengujian.
