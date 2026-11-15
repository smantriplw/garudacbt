# Pengembangan lokal

Panduan menjalankan GarudaCBT-Fork di komputer sendiri, termasuk import database,
upload file, dan index performa. Semua perintah di bawah dijalankan dari root
repository kecuali disebutkan lain.

## Kebutuhan

- PHP 8.2 dengan ekstensi `mysqli`, `pdo_mysql`, `gd`, `zip`, `intl`, `calendar`,
  `exif`, `bcmath`, `mbstring`, `dom`/`xml`, dan `fileinfo`.
- MariaDB 10.4+ (dump contoh dibuat dengan MariaDB 11.4).
- `vendor/` sudah tersedia di repository; `composer install` hanya perlu bila
  folder tersebut dihapus.
- Docker (opsional, tetapi paling cepat untuk menyamakan environment).

## Menjalankan dengan Docker

Stack sudah disertakan di repository: `docker-compose.yml` plus konfigurasi di
`docker/`. Isinya tiga service:

| service | image | keterangan |
|---|---|---|
| `web` | `nginx:1.27-alpine` (~48 MB) | port `8099`, menyajikan aset statis dan meneruskan PHP ke `app` |
| `app` | build dari `docker/php/Dockerfile` (~98 MB) | php-fpm 8.2 Alpine, ekstensi mysqli/gd/zip/intl/opcache |
| `db` | `mariadb:11.4` | port `3307`, database `garuda_cbt` |

```bash
docker compose up -d --build
```

Aplikasi tersedia di <http://127.0.0.1:8099>.

Yang perlu diketahui:

- **Kode di-bind mount**, jadi perubahan file langsung terpakai tanpa rebuild.
  Yang perlu `--build` hanya bila `docker/` berubah.
- **`application/config/config.php` dibuat otomatis** dari
  `config.example.php` saat container pertama kali start (skema `http`, session
  di `/tmp`). File itu di-gitignore; hapus dan restart bila ingin dibuat ulang.
- **`application/config/database.php` di-mount dari `docker/php/database.php`**,
  sehingga file di repository (yang berisi kredensial lokal) tidak tersentuh.
  Nilai diambil dari env `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
- **Kepemilikan file** mengikuti `HOST_UID`/`HOST_GID` (default `1000`). Bila
  `id -u` Anda bukan 1000:

  ```bash
  HOST_UID=$(id -u) HOST_GID=$(id -g) docker compose up -d --build
  ```

- **Port** dapat diganti lewat env: `APP_PORT=8099`, `DB_PORT=3307`,
  `CI_ENV=production` (ubah ke `development` untuk menampilkan error).
- **Backup** memakai binary `mariadb-dump`, `zip`, dan `gzip` yang sudah ada di
  image. Di luar Docker, pasang paket `mariadb-client`/`mysql-client` agar
  backup database besar tidak kehabisan memori (tanpa binary, aplikasi memakai
  dbutil per tabel).
- **Restore** memakai binary `mariadb`/`mysql`. Batas unggah 64 MB
  (`upload_max_filesize`) dan 80 MB (`client_max_body_size`), cukup untuk file
  `.sql.gz` dari database ini; naikkan keduanya bila backup Anda lebih besar.

```bash
APP_PORT=8090 DB_PORT=3308 docker compose up -d
```

Menghentikan stack: `docker compose down` (tambahkan `-v` untuk menghapus
volume database).

## Import database

Dump tidak berisi `CREATE DATABASE`, jadi buat database lebih dulu (sudah dibuat
oleh `MARIADB_DATABASE`) lalu import:

```bash
docker compose exec -T db mariadb -ugaruda -pgaruda garuda_cbt < data.sql
```

Bila memakai MySQL/MariaDB lokal, cukup:

```bash
mysql -u <user> -p garuda_cbt < data.sql
```

## Upload file

File upload (foto siswa, logo, materi, bank soal) berada di `uploads/`.
Compose me-mount seluruh repository (`.:/var/www/html`), termasuk `uploads/`,
jadi file yang diunggah lewat aplikasi langsung terlihat di repository dan
sebaliknya. Service `web` hanya membaca, service `app` menulis.

Struktur minimal yang harus ada: `uploads/foto_siswa`, `uploads/profiles`,
`uploads/settings`, `uploads/bank_soal`, `uploads/materi`, `uploads/import`,
`uploads/temp` (entrypoint membuatnya bila hilang).

## Konfigurasi

1. **`application/config/config.php`** tidak disertakan di repository.
   - Dengan Docker: dibuat otomatis oleh `docker/entrypoint.sh` saat container
     pertama kali start (skema `http`, session di `/tmp`).
   - Tanpa Docker: salin manual, lalu ubah `$scheme = 'https';` menjadi `'http'`
     bila tidak memakai TLS dan isi `$config['sess_save_path']` dengan direktori
     yang bisa ditulis (contoh `/tmp`); nilai `NULL` gagal pada image PHP resmi
     karena `/var/lib/php/sessions` tidak ada.

   ```bash
   cp application/config/config.example.php application/config/config.php
   ```

2. **`application/config/database.php`** — sesuaikan `hostname`, `username`,
   `password`, dan `database`. Dengan Docker file ini **diganti** oleh
   `docker/php/database.php` (host `db`, kredensial dari env compose), sehingga
   nilai lokal Anda tidak berubah.

3. **Dapodik** — URL dan token tidak wajib di `config.php`. Isi dari halaman
   **DAPODIK → Pengaturan Koneksi Dapodik**, lalu tekan **Tes Koneksi**. Nilai
   di `config.php` hanya dipakai bila kolom database masih kosong.

Mengganti password akun admin untuk pengujian:

```bash
php -r 'echo password_hash("PasswordBaru", PASSWORD_BCRYPT), PHP_EOL;'
mysql -u <user> -p garuda_cbt -e "UPDATE users SET password='<hash>' WHERE username='<user>';"
```

## Index performa

`docs/sql/performance-indexes.sql` berisi index opsional. Script ini **tidak**
dijalankan otomatis; jalankan sekali pada database aplikasi:

```bash
mysql -u <user> -p garuda_cbt < docs/sql/performance-indexes.sql
```

Yang paling berdampak pada dataset besar:

- `rapor_nilai_*` — halaman cetak rapor;
- `cbt_soal_siswa (id_bank, id_siswa)` — hitungan peserta pada daftar bank soal
  dan rekap (pada dump contoh query agregasinya turun dari ~0,39 s ke ~0,03 s);
- `cbt_soal (bank_id, jenis, nomor_soal)` — editor soal.

Setelah menjalankan index, ukur ulang dengan `EXPLAIN` pada query yang lambat.

## Menguji perubahan

Tidak ada test suite otomatis di repository ini. Pola yang dipakai saat
mengembangkan:

1. **Harness fake database** — untuk method model/controller yang memakai query
   builder, buat stub kecil (`select/from/where/where_in/get/result/row/update/
   insert`) lalu bandingkan keluaran lama dan baru pada data sintetis.
2. **SQLite** — untuk membandingkan perilaku SQL antar-versi (mis. hitungan
   siswa per kelas), buat skema mini dan jalankan kedua query.
3. **Differential test** — untuk kode yang dihitung ulang (nilai, poin,
   penomoran), jalankan versi lama dan baru pada ribuan input acak dan bandingkan
   hasilnya.
4. **Uji langsung** — jalankan stack Docker, login sebagai admin, lalu akses
   halaman yang berubah. Untuk endpoint JSON, ambil `csrf_token` dari cookie
   `csrf_cookie` dan kirim sebagai field `csrf_token` atau header
   `X-CSRF-TOKEN`.
5. `php -l` pada setiap file PHP yang diubah, dan `node --check` untuk JS inline
   bila diubah.

## Catatan

- `Dbmanager::manage()` membangun dump JSON seluruh tabel untuk view yang sama
  dengan `dbclear`; pada dataset besar ini akan kehabisan memori. Tidak ada menu
  yang menuju ke sana.
- `Dbclear::index` menghapus tabel yang tidak ada di `assets/app/db/database.json`
  dan tidak termasuk daftar `$excludes`. Pastikan file tersebut lengkap sebelum
  membuka halaman **Bersihkan Data**.
- `application/config/config.php` dan `uploads/` biasanya tidak masuk version
  control; jangan commit kredensial dari lingkungan lokal.
