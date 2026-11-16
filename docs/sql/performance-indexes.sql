-- Optional performance indexes for GarudaCBT.
--
-- The application's report pages filter these tables by the same column
-- combinations on every request. Without an index MySQL scans the whole
-- table for each lookup; with one it is a range read.
--
-- Run once against the application database, then verify with EXPLAIN.
-- Remove the `ADD INDEX` lines that already exist, MySQL has no
-- "IF NOT EXISTS" for indexes.
--
--   mysql -u <user> -p <dbname> < docs/sql/performance-indexes.sql

-- Rapor: one row per student x mapel x kelas x term
ALTER TABLE rapor_nilai_harian ADD INDEX idx_harian_kelas (id_mapel, id_kelas, id_tp, id_smt);
ALTER TABLE rapor_nilai_pts    ADD INDEX idx_pts_kelas    (id_mapel, id_kelas, id_tp, id_smt);
ALTER TABLE rapor_nilai_akhir  ADD INDEX idx_akhir_kelas  (id_mapel, id_kelas, id_tp, id_smt);
ALTER TABLE rapor_nilai_ekstra ADD INDEX idx_ekstra_kelas (id_ekstra, id_kelas, id_tp, id_smt);
ALTER TABLE rapor_fisik        ADD INDEX idx_fisik_kelas  (id_kelas, id_tp, id_smt);

-- Roster lookups ("who is in this class this term")
ALTER TABLE kelas_siswa ADD INDEX idx_kelas_siswa (id_kelas, id_tp, id_smt);

-- KIKD / KKM lookups by the composed id
ALTER TABLE rapor_kikd ADD INDEX idx_kikd (id_kikd, id_tp, id_smt);
ALTER TABLE rapor_kkm  ADD INDEX idx_kkm  (id_kkm);
ALTER TABLE rapor_admin_setting ADD INDEX idx_setting (id_tp, id_smt);

-- CBT recap: scores per student per exam
ALTER TABLE cbt_nilai ADD INDEX idx_nilai_jadwal_siswa (id_jadwal, id_siswa);
ALTER TABLE cbt_nilai ADD INDEX idx_nilai_id_siswa (id_siswa);

-- Dashboard traffic: login/logout/force-logout and exam activity per day
ALTER TABLE log ADD INDEX idx_log_time (log_time);
ALTER TABLE log_ujian ADD INDEX idx_log_ujian_time (log_time);

-- Bank soal: list per bank, "is it used" counters, and the question editor
ALTER TABLE cbt_soal ADD INDEX idx_soal_bank_jenis (bank_id, jenis, nomor_soal);
ALTER TABLE cbt_soal ADD INDEX idx_soal_bank_tampil (bank_id, tampilkan);
ALTER TABLE cbt_jadwal ADD INDEX idx_jadwal_bank_status (id_bank, status);
ALTER TABLE cbt_soal_siswa ADD INDEX idx_soal_siswa_bank (id_bank, id_siswa);
