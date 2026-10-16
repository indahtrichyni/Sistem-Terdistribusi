# Tugas Pertemuan 7: Name Service dan Service Discovery

Tugas ini merupakan implementasi sederhana dari konsep Name Service dan Service Discovery pada Sistem Terdistribusi. Proyek ini membuktikan bagaimana sebuah Client dapat menghubungi target Service menggunakan nama logis yang stabil tanpa perlu mengetahui alamat IP dan Port fisik yang bisa berubah sewaktu-waktu.

## Anggota Kelompok
1. Indah Tricahyani - 240209501078 (Mengordinasikan pembagian tugas dan konfigurasi services.json, gateway.php, dan course.php)
2. Aulia Aqilah - 240209501038 (Mengeksekusi pengujian di Postman dan mengambil tiga tangkapan layar skenario uji (valid, gagal, dan ubah port) sebagai bukti)
3. Mutia Inayah - 230209552025 (Menyusun file dokumentasi README.md secara terstruktur lengkap dengan pembuatan diagram alur lookup.)
4. Arjun - 240209502022 (Bertugas menyatukan seluruh berkas pengumpulan (zip), memastikan kesiapan tugas)

---------------

## Cara Menjalankan Service

Karena tugas ini menggunakan arsitektur terdistribusi, kita perlu menjalankan Gateway dan Target Service secara terpisah seolah-olah berada di dua server yang berbeda. Pastikan terminal CMD sudah terbuka dan diarahkan ke dalam folder proyek ini.

1. Langkah 1: Jalankan Course Service (Target Service)
Buka Terminal 1, arahkan ke folder proyek, dan jalankan perintah berikut:

php -S 127.0.0.1:8103 course.php

(Biarkan terminal ini tetap terbuka dan berjalan)


2. Langkah 2: Jalankan API Gateway
Buka Terminal 2 (jendela baru), arahkan ke folder proyek, dan jalankan perintah berikut:

php -S 127.0.0.1:8101 gateway.php

(Biarkan terminal ini tetap terbuka dan berjalan)

---------------

## Skenario Pengujian (menggunakan Postman)

### 1. Uji Nama Valid (Skenario Berhasil)
1) Tujuan: Membuktikan Gateway bisa menemukan alamat berdasarkan nama di registry.
2) Langkah: Buka Postman, set method ke `GET`, lalu masukkan URL:  
    `http://127.0.0.1:8101/?service=course`
3) Ekspektasi: Mendapat status `200 OK` dengan respons JSON dari Course Service yang menampilkan `"port_aktif": 8103`.

### 2. Uji Nama Tidak Ditemukan (Error 500)
1) Tujuan: Membuktikan penanganan *error* jika nama logis tidak terdaftar.
2) Langkah: Di Postman, ubah nilai parameter service menjadi nama yang salah, misalnya:  
    `http://127.0.0.1:8101/?service=payment`
3) Ekspektasi: Mendapat status `500 Internal Server Error` dengan pesan bahwa binding untuk nama service tersebut tidak ditemukan di registry.

### 3. Uji Pemindahan Port (Service Discovery)
1) Tujuan: Membuktikan klien tetap bisa terhubung meski *port* fisik service berubah, tanpa mengubah source code klien.
2) Langkah:
    1. Buka Terminal 1, tekan `Ctrl+C` untuk mematikan *Course Service*.
    2. Nyalakan kembali *Course Service* di port baru (**8203**):  
       `php -S 127.0.0.1:8203 course.php`
    3. Buka file `services.json`, ubah alamat course menjadi `"http://127.0.0.1:8203"`, lalu *Save*.
    4. Buka Postman, gunakan URL yang **sama persis** seperti skenario pertama:  
       `http://127.0.0.1:8101/?service=course`
3) Ekspektasi: Mendapat status `200 OK` dan nilai `"port_aktif"` berubah menjadi `8203`.

---------------

## Diagram Alur Lookup

Berikut adalah representasi alur *service lookup* ketika klien memanggil API menggunakan parameter `service=course`.

```text
┌──────────────────────┐
│   Client (Postman)   │
└──────────┬───────────┘
           │
           │ Request: GET /?service=course
           ▼
┌──────────────────────┐
│     API Gateway      │
└──────────┬───────────┘
           │
           │ (1) Lookup nama "course"
           ▼
┌──────────────────────┐
│ Registry (services.json)│
└──────────┬───────────┘
           │
           │ (2) Return alamat service
           │     http://127.0.0.1:8103
           ▼
┌──────────────────────┐
│     API Gateway      │
└──────────┬───────────┘
           │
           │ (3) Meneruskan request
           ▼
┌──────────────────────┐
│   Course Service     │
│   Port: 8103         │
└──────────┬───────────┘
           │
           │ (4) Response
           ▼
┌──────────────────────┐
│     API Gateway      │
└──────────┬───────────┘
           │
           │ (5) Mengembalikan response
           ▼
┌──────────────────────┐
│   Client (Postman)   │
└──────────────────────┘
```

### Penjelasan Singkat

Klien, seperti Postman, tidak perlu mengetahui alamat dan port langsung dari *Course Service*. Klien cukup mengirimkan request ke API Gateway dengan menyertakan nama logis service, yaitu `course`.

Selanjutnya, API Gateway bertindak sebagai **resolver** dengan membaca file registry `services.json` untuk menemukan alamat tujuan, yaitu `http://127.0.0.1:8103`. Setelah alamat ditemukan, Gateway meneruskan request ke Course Service, menerima response, lalu mengembalikannya kepada klien.

Dengan mekanisme ini, klien cukup berkomunikasi melalui API Gateway tanpa harus mengetahui lokasi setiap service secara langsung.
