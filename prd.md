Ini adalah projek kosong laravek inertia vue untuk mengembangkan aplikasi dengan prd di bawah

---

# Product Requirements Document (PRD) - Heart Risk Prediction System

## 1. Project Overview

Aplikasi ini adalah sistem pendukung keputusan (Decision Support System) untuk memprediksi risiko penyakit jantung berdasarkan data riwayat kesehatan pasien. Sistem ini menggunakan **Algoritma Naive Bayes** sebagai mesin klasifikasi utama.

**Tech Stack:**

* **Backend:** Laravel 11
* **Frontend:** Vue.js 3 (Composition API)
* **Bridge:** Inertia.js
* **Starter Kit:** Laravel Breeze (Sudah terpasang)
* **Database:** MySQL

---

## 2. Core Objectives

1. 
**Klasifikasi Risiko**: Memprediksi apakah pasien masuk kategori "Beresiko" atau "Tidak Beresiko" penyakit jantung.


2. 
**Manajemen Dataset**: Mengelola data latih pasien untuk dasar perhitungan probabilitas.


3. 
**Evaluasi Model**: Menghitung kinerja sistem menggunakan metrik Akurasi, Presisi, dan Recall.



---

## 3. Database Schema (Must Follow)

AI harus mengikuti struktur kolom berikut untuk konsistensi algoritma:

**Table: `patients_datasets**`
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt | Primary Key |
| `age` | Integer | Usia pasien  |
| `gender` | Enum | 'L' (Laki-laki), 'P' (Perempuan)  |
| `blood_pressure` | Integer | Nilai Tekanan Darah  |
| `cholesterol` | Integer | Kadar Kolesterol  |
| `blood_sugar` | Integer | Kadar Gula Darah  |
| `risk_result` | Boolean | Target: 1 (Beresiko), 0 (Tidak Beresiko)  |
| `is_training` | Boolean | Flag: True untuk data latih, False untuk data uji  |

---

## 4. Naive Bayes Logic (Backend Instruction)

AI dilarang keras menggunakan library eksternal untuk prediksi. Logika harus dibangun di dalam `App\Services\NaiveBayesService.php` dengan rumus:

$$P(C|X) = \frac{P(X|C) \times P(C)}{P(X)}$$



**Tahapan Kode:**

1. 
**Hitung Probabilitas Prior $P(C)$**: Hitung jumlah data "Beresiko" dan "Tidak Beresiko" dibagi total data.


2. 
**Hitung Likelihood $P(X|C)$**: Hitung probabilitas tiap atribut input (usia, kolesterol, dll) terhadap masing-masing kelas target.


3. **Handle Data Kontinu**: Gunakan distribusi Gaussian jika atribut berupa angka berkelanjutan (seperti usia/tekanan darah).
4. 
**Prediksi**: Kalikan semua probabilitas dan ambil nilai tertinggi sebagai hasil klasifikasi.



---

## 5. Workflow & Modules

### A. Admin - Dataset Management

* 
**Import Feature**: Mampu mengunggah dataset kesehatan pasien (format CSV/Excel).


* 
**Preprocessing**: Sistem harus mengecek nilai null atau tidak konsisten sebelum data disimpan.


* 
**Data Splitting**: Fitur untuk membagi data menjadi Data Latih (Training) dan Data Uji (Testing).



### B. Medical Personnel - Prediction Form

* Form input Vue.js yang mengirim data ke Laravel via Inertia.
* Menampilkan hasil prediksi secara real-time di Dashboard tanpa refresh halaman.

### C. Evaluation Dashboard

* Menampilkan **Confusion Matrix**.


* Menghitung dan menampilkan **Accuracy, Precision, dan Recall** secara otomatis dari data uji.



---

## 6. Implementation Constraints (Avoid Hallucination)

* 
**No Medical Diagnosis**: UI harus mencantumkan catatan bahwa ini adalah "Informasi Pendukung", bukan diagnosa medis final.


* 
**Independence Assumption**: Ingat bahwa Naive Bayes menganggap setiap atribut bersifat independen (tidak saling mempengaruhi).


* **Data Security**: Karena ini data kesehatan pasien, gunakan Middleware Laravel untuk memastikan hanya user yang login yang bisa akses.

---

