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

Buat folder kerja di luar repository, misalnya `~/garudacbt-docker`, berisi:

`Dockerfile`

```dockerfile
FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev libicu-dev \
        libxml2-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo_mysql gd zip intl calendar exif bcmath \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
```

`docker-compose.yml` (ganti path repository sesuai lokasi Anda)

```yaml
services:
  db:
    image: mariadb:11.4
    environment:
      MARIADB_ROOT_PASSWORD: root
      MARIADB_DATABASE: garuda_cbt
      MARIADB_USER: garuda
      MARIADB_PASSWORD: garuda
    command: ["--sql-mode=NO_AUTO_VALUE_ON_ZERO", "--max_allowed_packet=256M"]
    volumes:
      - db-data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "healthcheck.sh", "--connect", "--innodb_initialized"]
      interval: 5s
      timeout: 5s
      retries: 30
    ports:
      - "127.0.0.1:3307:3306"

  web:
    build: .
    depends_on:
      db:
        condition: service_healthy
    volumes:
      - /path/ke/garudacbt:/var/www/html
      - ./uploads:/var/www/html/uploads
    ports:
      - "127.0.0.1:8099:80"

volumes:
  db-data:
```

Jalankan:

```bash
cd ~/garudacbt-docker
docker compose up -d db          # tunggu sampai healthy
docker compose up -d --build web
```

Aplikasi tersedia di <http://127.0.0.1:8099>.

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
Saat memakai Docker, mount folder tersebut agar tidak menimpa isi repository:

```yaml
- ./uploads:/var/www/html/uploads
```

Struktur minimal yang harus ada: `uploads/foto_siswa`, `uploads/profiles`,
`uploads/settings`, `uploads/bank_soal`, `uploads/materi`, `uploads/import`,
`uploads/temp`.

## Konfigurasi

1. **`application/config/config.php`** tidak disertakan. Salin dari contoh:

   ```bash
   cp application/config/config.example.php application/config/config.php
   ```

   Di lingkungan lokal:
   - ubah `$scheme = 'https';` menjadi `'http'` bila tidak memakai TLS;
   - `$config['sess_save_path']` diisi direktori yang bisa ditulis (contoh
     `/tmp`); nilai `NULL` akan gagal pada image PHP resmi karena
     `/var/lib/php/sessions` tidak ada.

2. **`application/config/database.php`** — sesuaikan `hostname`, `username`,
   `password`, dan `database`. Untuk Docker, host adalah nama service (`db`).

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
