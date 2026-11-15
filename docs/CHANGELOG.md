# Changelog

Catatan perubahan GarudaCBT-Fork. Dikelompokkan per area, bukan per urutan
commit. Setiap poin menjelaskan perilaku yang berubah, bukan detail implementasi.

---

## Autentikasi & hak akses

- `Ion_auth_model::in_group()` diperbaiki. Sebelumnya fungsi ini selalu
  mengembalikan `true` untuk semua pengguna, sehingga:
  - admin yang hanya tergabung di grup `admin` tetap dianggap "siswa" dan
    dilempar ke halaman *Akun Suspend* saat membuka dashboard;
  - halaman khusus guru (`cbtjadwal`, `cbtrekap`, `cbtbanksoal`, `datasiswa`,
    dan lainnya) bisa dibuka oleh akun siswa.
- Dashboard sekarang melewati pengecekan data siswa/guru untuk admin, sehingga
  admin selalu masuk ke dashboard admin.
- Halaman pengganti "Akun Suspend" diperbaiki: menjelaskan penyebab sebenarnya
  (belum terdaftar di kelas / belum punya jabatan pada tahun pelajaran aktif),
  menyebutkan akun dan tahun pelajaran/semester, serta typo "arudaCBT" menjadi
  "GarudaCBT".

## Dapodik

- Konfigurasi webservice tidak lagi hanya dari `application/config/config.php`.
  URL dan token disimpan pada baris `setting` (`dapodik_url`, `dapodik_token`)
  dan dapat diubah dari halaman **DAPODIK** tanpa menyentuh file. Nilai di
  `config.php` tetap dipakai sebagai cadangan bila kolom kosong.
- Kolom `dapodik_last_sync` mencatat waktu tarik data terakhir.
- Tombol **Tes Koneksi** memanggil `/WebService/getGtk` dan sekarang gagal bila
  respons bukan JSON webservice, bila `rows` kosong, atau bila HTTP bukan 2xx.
  Pesan menyebutkan Content-Type dan potongan respons agar salah URL dan salah
  token bisa dibedakan.
- Ditambahkan **Pratinjau** untuk GTK dan siswa sebelum data ditulis:
  - ringkasan jumlah data baru / diperbarui / tidak valid;
  - untuk siswa, rincian **per kelas**: rombel, kode, level, jurusan, status
    kelas (ada/dibuat), jumlah siswa baru vs diperbarui, dan peringatan bila
    level/jurusan kelas tersimpan berbeda dengan data Dapodik.
- Penarikan data memakai transaksi dan timeout; kesalahan koneksi dilaporkan
  sebagai pesan, bukan halaman 500.

## Sinkronisasi & import data

- Import siswa (Excel) sekarang **upsert** berdasarkan NISN (fallback NIS):
  data yang sudah ada diperbarui, data baru ditambahkan, dan batch tidak lagi
  ditolak seluruhnya hanya karena satu NISN sudah terdaftar.
- Import guru (Excel/Word) sekarang **upsert** berdasarkan NIP, sehingga tidak
  lagi membuat baris `master_guru` ganda.
- Baris dengan NIS/NISN/NIP tidak valid, atau duplikat di dalam satu file,
  dilaporkan per baris tanpa membatalkan baris lain.
- Sinkronisasi Dapodik tidak lagi menimpa `username`/`password` yang sudah terisi
  saat memperbarui data yang ada; nilai hanya diisi bila masih kosong.
- `buku_induk` dipastikan hanya untuk siswa yang diproses, bukan seluruh tabel.

## Kelas / rombel

- Jumlah siswa di daftar kelas dan di detail kelas sekarang memakai definisi
  yang sama (`kelas_siswa` untuk `id_kelas` + `id_tp` + `id_smt`). Sebelumnya
  daftar menghitung semua baris tanpa filter semester (bisa menggelembung),
  sedangkan detail menyembunyikan siswa tanpa `buku_induk` aktif.
- Rombel dapat dibuat tanpa memilih siswa. Form tidak lagi memaksa pilihan
  siswa, dan `Datakelas::save()` tidak lagi error saat `siswa[]` kosong.

## CBT (ujian)

- Soal **menjodohkan** dapat ditampilkan pada halaman ujian siswa:
  - data soal/answer dinormalisasi (serialized array, serialized object, JSON,
    atau kosong) sehingga tabel selalu terbentuk;
  - jawaban siswa yang tersimpan muncul kembali saat siswa melanjutkan ujian;
  - kunci jawaban tidak lagi ikut dikirim ke browser;
  - soal tanpa data jawaban menampilkan pesan, bukan halaman kosong.
- Ujian tetap bisa dimulai walau salah satu jenis soal kurang dari jumlah yang
  dikonfigurasi. Queue diambil dari soal yang tersedia (dibatasi jumlah
  konfigurasi) dan bobot dibagi sesuai jumlah soal yang benar-benar masuk.

## Bank soal

- Jumlah siswa per bank dihitung dengan satu query `COUNT(DISTINCT id_siswa)`,
  bukan dengan memuat seluruh `cbt_soal_siswa` ke PHP.
- Penyimpanan pilihan soal (centang) memakai satu `UPDATE ... WHERE id_soal IN`,
  bukan satu update per soal.
- Pemeriksaan "soal belum lengkap" dibatasi ke bank yang sedang dibuka.
- Endpoint `cbtbanksoal/data` mengembalikan JSON; sebelumnya mengembalikan teks
  `Array`.

## Database

- Halaman **Bersihkan Data** (`dbclear`) menghitung jumlah baris per tabel
  dengan `SELECT COUNT(*)`, bukan dengan memuat seluruh isi tabel.
- **Backup Semua Data** memakai `mariadb-dump`/`mysqldump` yang menulis langsung
  ke file lalu digzip, bukan `dbutil->backup()` yang menyusun seluruh dump di
  memori. `OPTIMIZE TABLE` yang dijalankan pada setiap backup dihapus. Pada
  dump contoh: sebelumnya gagal (HTTP 500, kehabisan memori setelah ~9 detik),
  sekarang selesai ~6 detik dengan file `.sql.gz` 25 MB. Bila binary tidak
  tersedia, aplikasi memakai dbutil per tabel dan memberi pesan bila gagal.
- Backup file upload memakai `zip -r` bila tersedia, sehingga isi `uploads/`
  tidak dimuat seluruhnya ke memori.

## Performa & memori

- Query rapor memakai batch: `GET` per siswa diganti satu query per kelas dan
  di-cache selama satu request.
- Daftar jadwal ujian dan rekap tidak lagi memuat 1,2 juta baris
  `cbt_soal_siswa` untuk menghitung peserta; dipakai agregasi SQL.
- Import spreadsheet/Word melepas `PhpSpreadsheet`/`PhpWord` dan file sementara
  setelah dipakai.
- `docs/sql/performance-indexes.sql` berisi index opsional untuk tabel rapor,
  CBT, dan bank soal. Lihat [DEVELOPMENT.md](DEVELOPMENT.md#index-performa).

## Skrip SQL

`docs/sql/performance-indexes.sql` — jalankan sekali pada database aplikasi
(atau pada salinan, lalu ukur dengan `EXPLAIN`) untuk mempercepat halaman rapor
dan CBT. Script ini tidak dijalankan otomatis oleh aplikasi.
