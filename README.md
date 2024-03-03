# GarudaCBT-FORK
## Last version: 1.6.0

-------------

Saya [@hansputera (Hanif Dwy Putra S)](https://github.com/hansputera) selaku contributor yang melakukan maintain GarudaCBT (Fork) dengan memodifikasi beberapa kode yang berada di dalam kode orisinil GarudaCBT.
___________

## NOTE:
Aplikasi ini tidak diperjual belikan alias gratis 100% dengan lisensi MIT yang artinya bebas untuk memodifikasi dengan syarat tetap mencantumkan sumber asal.

Kami selaku pengembang aplikasi ini tidak bertanggung-jawab:
- jika ada yang mengharuskan membayar untuk mendapatkan aplikasi ini,
- adanya masalah dari aplikasi yang sudah dimodif atau dirubah fiturnya oleh pihak lain.
______________
- [HOME](https://garudacbt.github.io/cbt)
- [TUTORIAL INSTALL](https://github.com/garudacbt/cbt/wiki)

____
## Download
Harap download aplikasi dari tombol [<> Code] -> Download zip

-----
## VIRTUAL BOX (VHD):
https://github.com/origrata/garudacbt-vdi

----
## Install
* Download Aplikasi dari menu **Code => Download ZIP**
* Extract di folder **htdocs** jika menggunakan XAMPP, atau folder **www** jika mengonakan Laragon
* Lengkapnya sialhkan ke [TUTORIAL INSTALL](https://github.com/garudacbt/cbt/wiki)

[GarudaCBT Fork Note]
* Untuk GarudaCBT-Fork, konfigurasi Dapodik pada file `application/config/config.php` di line paling bawah. Silahkan ganti value dari `DAPODIK_WEBSERVICE_URL` dengan base url server Dapodik sekolah Anda, dan ganti `DAPODIK_WEBSERVICE_TOKEN` dengan token webservice yang telah teregistrasi di server Dapodik Anda.
* Untuk GarudaCBT-Fork, beberapa fitur dari Base-GarudaCBT telah difix, dan mengalami beberapa perubahan kode untuk memperbaiki bug.
* Kode dalam GarudaCBT-Fork sudah tidak diobfuscated seperti kode yang berasal dari sumber aslinya.

----
## Update
* Download Aplikasi dari menu **Code => Download ZIP**
* Extract dan replace semua code di folder aplikasi
* sesuaikan nama database yang digunakan
* backup database untuk berjaga-jaga
* jalankan menu **update** di menu DATABASE => UPDATE
______
### MENU FITUR
1. DATA MASTER
    * Beranda
    * Data Umum
        * Tahun Pelajaran
        * Jurusan
        * Mata Pelajaran
        * Ekstrakurikuler
        * Siswa
        * Kelas / Rombel
        * Guru
    * Data E-Learning
        * Jadwal Pelajaran
        * Materi
        * Tugas
        * Jadwal Materi/Tugas
    * Data Ujian
        * Jenis Ujian
        * Sesi
        * Ruang
        * Atur Ruang dan Sesi
        * Atur Nomor Peserta
        * Bank Soal
        * Jadwal
        * Alokasi Waktu
        * Token
    * Pengumuman

2. PELAKSANAAN
    * Hasil E-Learning
        * Nilai Harian
        * Kehadiran Harian
        * Kehadiran Bulanan
        * Rekap Nilai
    * Pelaksanaan Ujian
        * Cetak
        * Status Siswa
        * Hasil Ujian
        * Analisis Soal
        * Rekap Nilai Ujian

3. RAPOR
    * Setting Rapor
    * Kumpulan Nilai Rapor
    * Buku Induk
    * Alumni

4. PENGATURAN
    * Profile Sekolah
    * User Management
        * Administrator
        * Guru
        * Siswa
    * TARIK DAPODIK - feature by [@hansputera (Hanif Dwy Putra S)](https://github.com/hansputera)
        * TARIK SISWA
        * TARIK GTK
    * Database
        * Backup/Restore
        * Update

6. LOGOUT
______
______
MIT License

Copyright (c) 2020 GarudaCBT

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
