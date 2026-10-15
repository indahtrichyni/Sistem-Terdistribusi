# Catatan Pengujian API Mini-LMS

## 1. Prasyarat

Server PHP dijalankan dari folder `pertemuan-6` menggunakan perintah:

```bash
php -S 127.0.0.1:8000 index.php
```

Pengujian dilakukan menggunakan Postman dan CMD dengan endpoint API Mini-LMS.

## 2. Skenario Pengujian

### 2.1 Permintaan Valid

- **Metode:** POST
- **Endpoint:** `/api/submissions`
- **Hasil:** `201 Created`
- **Keterangan:** Berhasil. API menerima data submission yang lengkap dan mengembalikan informasi submission serta metadata file.

### 2.2 Assignment Tidak Ditemukan

- **Metode:** POST
- **Endpoint:** `/api/submissions`
- **Hasil:** `404 Not Found`
- **Keterangan:** Berhasil. API menolak permintaan karena assignment dengan ID `99999` tidak ditemukan atau tidak aktif.

### 2.3 Input Tidak Lengkap

- **Metode:** POST
- **Endpoint:** `/api/submissions`
- **Hasil:** `400 Bad Request`
- **Keterangan:** Berhasil. API menolak permintaan karena terdapat field wajib yang belum diisi, yaitu `student_id`.

### 2.4 Membaca Data Submission

- **Metode:** GET
- **Endpoint:** `/api/submissions/9005`
- **Perintah CMD:**

```bash
curl -i http://127.0.0.1:8000/api/submissions/9005
```

- **Hasil:** `200 OK`
- **Keterangan:** Berhasil. API dapat mengambil dan menampilkan data submission yang telah tersimpan dalam format JSON.

## 3. Ringkasan Hasil Pengujian
| No. | Skenario | Metode | Status HTTP | Keterangan |
|---|---|---|---|---|
| 1 | Permintaan valid | POST | 201 Created | Berhasil |
| 2 | Assignment tidak ditemukan | POST | 404 Not Found | Berhasil |
| 3 | Input tidak lengkap | POST | 400 Bad Request | Berhasil |
| 4 | Membaca data submission | GET | 200 OK | Berhasil |

## 4. Bukti Pengujian
Bukti pengujian berupa tangkapan layar hasil pengujian aktual dari Postman dan CMD yang memperlihatkan endpoint, metode HTTP, status respons, serta response body.
1. `01-Permintaan-valid
<img width="953" height="503" alt="01-Permintaan valid" src="https://github.com/user-attachments/assets/01bf7703-9ab6-4f97-aa91-e1539c7fcc72" />

2. `02-Assignment-tidak-ditemukan
<img width="959" height="504" alt="02-Assignment tidak ditemukan" src="https://github.com/user-attachments/assets/78a1590a-4bbd-4162-87f7-b164af283a53" />

3. `03-Input-tidak-lengkap
<img width="954" height="496" alt="03-Input tidak lengkap" src="https://github.com/user-attachments/assets/36059249-6d7c-4157-b67e-277505996842" />

4. `04-GET-submission-200
<img width="956" height="500" alt="04-GET-submission-200" src="https://github.com/user-attachments/assets/12a07e01-1b0c-462e-a0bc-c2e84448a6bf" />

## 5. Kesimpulan
Berdasarkan pengujian yang telah dilakukan, API Mini-LMS berhasil menangani permintaan submission yang valid, menolak assignment yang tidak ditemukan, memvalidasi input yang tidak lengkap, serta membaca kembali data submission yang tersimpan. Hasil pengujian menunjukkan bahwa API memberikan respons HTTP sesuai dengan skenario yang diuji.
