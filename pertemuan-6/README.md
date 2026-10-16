# Prototipe Submission/File Service (Pertemuan 6)

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
├── index.php
└── test_api.sh
```

### Keterangan Folder dan File

- `data/`: folder untuk menyimpan data yang digunakan oleh layanan, jika tersedia.
- `docs/bukti/`: folder untuk menyimpan bukti asli hasil pengujian API, seperti hasil pengujian dari Postman atau terminal.
- `docs/screenshots/`: folder untuk menyimpan screenshot hasil pengujian API sebagai bukti dokumentasi.
- `docs/TESTING.md`: berisi dokumentasi skenario pengujian, langkah pengujian, hasil yang diharapkan, dan hasil aktual.
- `API_CONTRACT.md`: menjelaskan kontrak API, meliputi endpoint, metode HTTP, format request dan response, serta kode status HTTP.
- `Mini-LMS.postman_collection.json`: berisi koleksi request API yang dapat diimpor ke Postman untuk mempermudah pengujian endpoint.
- `README.md`: dokumentasi utama proyek yang menjelaskan gambaran umum, struktur folder, cara menjalankan layanan, penggunaan API, dan cara melakukan pengujian.
- `index.php`: file utama yang berisi implementasi layanan PHP untuk menangani request, memproses data, melakukan validasi, dan mengirimkan response API.
- `test_api.sh`: skrip shell untuk menjalankan pengujian API secara otomatis menggunakan `curl`.

## Kebutuhan Sistem

Untuk menjalankan dan menguji prototipe ini, diperlukan:

- PHP 8.0 atau lebih baru dengan ekstensi JSON.
- Postman untuk melakukan pengujian API secara manual.
- `curl` untuk mengirim request melalui terminal.
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

Status pada tabel merupakan hasil yang diharapkan untuk setiap skenario.

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

Proyek Mini-LMS Submission/File Service merupakan prototipe layanan berbasis HTTP yang menerapkan konsep Sistem Terdistribusi melalui penggunaan metode POST dan GET. Metode POST digunakan untuk mengirimkan metadata pengumpulan tugas, sedangkan metode GET digunakan untuk mengambil kembali data submission dan metadata file yang tersimpan dalam format JSON. Layanan ini juga menerapkan validasi input serta memberikan respons HTTP sesuai dengan kondisi permintaan.

Prototipe ini menunjukkan pemisahan antara metadata submission dan isi file fisik melalui penggunaan submission_id, file_id, dan internal_location. Namun, implementasi saat ini masih terbatas pada penyimpanan metadata dan belum mendukung unggahan isi file secara langsung. Pengujian melalui Postman dan terminal digunakan untuk memeriksa respons terhadap permintaan valid, assignment yang tidak ditemukan, input tidak lengkap, serta pengambilan data submission. Dengan demikian, prototipe ini dapat menjadi dasar pengembangan layanan pengumpulan tugas yang lebih lengkap, dengan penambahan fitur unggah file, keamanan akses, dan penyimpanan file yang sesungguhnya.
