<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined("BASEPATH") or exit("No direct script access allowed");
class Dashboard_model extends CI_Model
{
    public function getSetting()
    {
        static $cached = null;
        if ($cached === null) {
            $cached = $this->db->get("setting")->row();
        }
        return $cached;
    }
    public function getRunningText()
    {
        return $this->db->get("running_text")->result();
    }
    public function total($table, $where = null)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($table);
    }
    public function hapus($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }
    public function getProfileAdmin($id_user)
    {
        $this->db->select("b.*");
        $this->db->from("users a");
        $this->db->join("users_profile b", "a.id=b.id_user", "left");
        $this->db->where("a.id", $id_user);
        $query = $this->db->get()->row();
        return $query;
    }
    public function totalWaliKelas($id_tp, $id_smt)
    {
        $this->db->where("id_jabatan", "4");
        $this->db->where("id_tp", $id_tp);
        $this->db->where("id_smt", $id_smt);
        $query = $this->db->get("jabatan_guru")->num_rows();
        return $query;
    }
    public function totalSiswaKelas($id_kelas, $id_tp, $id_smt)
    {
        $this->db->select("a.id_siswa");
        $this->db->from("kelas_siswa a");
        $this->db->where("a.id_tp", $id_tp);
        $this->db->where("a.id_smt", $id_smt);
        $this->db->where("a.id_kelas", $id_kelas);
        $query = $this->db->get()->num_rows();
        return $query;
    }

    public function progressJadwal(array $ids)
    {
        $ret = [];
        if (count($ids) === 0) {
            return $ret;
        }
        $rows = $this->db->select("id_jadwal, COUNT(DISTINCT id_siswa) AS siswa", false)
            ->from("cbt_soal_siswa")
            ->where_in("id_jadwal", $ids)
            ->group_by("id_jadwal")
            ->get()->result();
        foreach ($rows as $row) {
            $ret[(int) $row->id_jadwal] = (int) $row->siswa;
        }
        return $ret;
    }

    public function soalPerJenis()
    {
        $rows = $this->db->select("jenis, COUNT(*) AS jumlah", false)
            ->from("cbt_soal")
            ->group_by("jenis")
            ->order_by("jenis", "ASC")
            ->get()->result();
        $ret = [];
        foreach ($rows as $row) {
            $ret[(int) $row->jenis] = (int) $row->jumlah;
        }
        return $ret;
    }

    public function bankPerMapel($limit = 8)
    {
        return $this->db->select("m.nama_mapel, COUNT(b.id_bank) AS jumlah", false)
            ->from("cbt_bank_soal b")
            ->join("master_mapel m", "m.id_mapel=b.bank_mapel_id", "left")
            ->group_by("b.bank_mapel_id, m.nama_mapel")
            ->order_by("jumlah", "DESC")
            ->limit($limit)
            ->get()->result();
    }

    public function trafficHarian($days = 7)
    {
        $days = max(1, (int) $days);
        $peta = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $peta[date("Y-m-d", strtotime("-" . $i . " days"))] = [
                "login" => 0, "logout" => 0, "paksa" => 0, "mulai" => 0, "selesai" => 0,
            ];
        }
        $mulai = array_key_first($peta) . " 00:00:00";

        $akses = $this->db->query(
            "SELECT DATE(log_time) AS tgl, log_desc, COUNT(*) AS jumlah
             FROM log
             WHERE log_time >= ?
               AND (log_desc = 'Login' OR log_desc = 'Logout' OR log_desc LIKE 'Keluar paksa%')
             GROUP BY DATE(log_time), log_desc",
            [$mulai]
        )->result();
        foreach ($akses as $row) {
            $tgl = (string) $row->tgl;
            if (!isset($peta[$tgl])) {
                continue;
            }
            $desc = strtolower(trim((string) $row->log_desc));
            if ($desc === "login") {
                $peta[$tgl]["login"] += (int) $row->jumlah;
            } elseif ($desc === "logout") {
                $peta[$tgl]["logout"] += (int) $row->jumlah;
            } elseif (strpos($desc, "keluar paksa") === 0) {
                $peta[$tgl]["paksa"] += (int) $row->jumlah;
            }
        }

        $ujian = $this->db->query(
            "SELECT DATE(log_time) AS tgl, log_type, COUNT(*) AS jumlah
             FROM log_ujian
             WHERE log_time >= ? AND log_type IN (1, 2)
             GROUP BY DATE(log_time), log_type",
            [$mulai]
        )->result();
        foreach ($ujian as $row) {
            $tgl = (string) $row->tgl;
            if (!isset($peta[$tgl])) {
                continue;
            }
            if ((int) $row->log_type === 1) {
                $peta[$tgl]["mulai"] += (int) $row->jumlah;
            } elseif ((int) $row->log_type === 2) {
                $peta[$tgl]["selesai"] += (int) $row->jumlah;
            }
        }

        $ret = [
            "labels" => [], "login" => [], "logout" => [], "paksa" => [], "mulai" => [], "selesai" => [],
        ];
        foreach ($peta as $tgl => $data) {
            $ret["labels"][] = date("d/m", strtotime($tgl));
            foreach (["login", "logout", "paksa", "mulai", "selesai"] as $key) {
                $ret[$key][] = $data[$key];
            }
        }
        $ret["hari_ini"] = end($peta) ?: [
            "login" => 0, "logout" => 0, "paksa" => 0, "mulai" => 0, "selesai" => 0,
        ];
        return $ret;
    }
    public function totalPengawas()
    {
        $this->db->select("*");
        $this->db->where("id_jadwal !=", "a:0:{}");
        $query = $this->db->get("cbt_pengawas")->num_rows();
        return $query;
    }
    public function totalJadwal()
    {
        $this->db->select("*");
        $query = $this->db->get("cbt_jadwal")->num_rows();
        return $query;
    }
    public function getDataTahun()
    {
        $this->datatables->select("id_tp, tahun, active");
        $this->datatables->from("master_tp");
        return $this->datatables->generate();
    }
    public function getTahun()
    {
        $this->db->order_by("tahun", "ASC");
        $result = $this->db->get("master_tp")->result();
        return $result;
    }
    public function getTahunById($id)
    {
        $result = $this->db->get_where("master_tp", "id_tp=" . $id)->row();
        return $result;
    }
    public function getTahunByTahun($tahun)
    {
        $result = $this->db->get_where("master_tp", "tahun=\"" . $tahun . "\"")->row();
        return $result;
    }
    public function getTahunActive()
    {
        static $cached = null;
        if ($cached === null) {
            $this->db->select("id_tp, tahun");
            $this->db->from("master_tp");
            $this->db->where("active", 1);
            $cached = $this->db->get()->row();
        }
        return $cached;
    }
    public function getSemester()
    {
        $this->db->order_by("smt", "ASC");
        $result = $this->db->get("master_smt")->result();
        return $result;
    }
    public function getSemesterById($id)
    {
        $result = $this->db->get_where("master_smt", "id_smt=" . $id)->row();
        return $result;
    }
    public function getSemesterByNama($nama_smt)
    {
        $result = $this->db->get_where("master_smt", "nama_smt=\"" . $nama_smt . "\"")->row();
        return $result;
    }
    public function getSemesterActive()
    {
        static $cached = null;
        if ($cached === null) {
            $this->db->select("id_smt, nama_smt, smt");
            $this->db->from("master_smt");
            $this->db->where("active", 1);
            $cached = $this->db->get()->row();
        }
        return $cached;
    }
    public function getDataGuruByUserId($id_user, $id_tp, $id_smt)
    {
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("a.id_guru, a.nama_guru, a.nip, a.id_user, a.foto, b.id_jabatan, b.id_kelas as wali_kelas, f.level_id, g.level");
        $this->db->from("master_guru a");
        $this->db->join("jabatan_guru b", "a.id_guru=b.id_guru AND b.id_tp=" . $id_tp . " AND b.id_smt=" . $id_smt, "left");
        $this->db->join("level_guru e", "b.id_jabatan=e.id_level", "left");
        $this->db->join("master_kelas f", "a.id_guru=f.guru_id AND f.id_tp=" . $id_tp . " AND f.id_smt=" . $id_smt, "left");
        $this->db->join("level_kelas g", "f.level_id=g.id_level", "left");
        $this->db->where("a.id_user", $id_user);
        $query = $this->db->get()->row();
        return $query;
    }
    public function getDataGuruById($id_guru, $id_tp, $id_smt)
    {
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("a.id_guru, a.nama_guru, a.nip, a.id_user, a.foto, b.id_jabatan, b.id_kelas as wali_kelas, f.level_id, g.level");
        $this->db->from("master_guru a");
        $this->db->join("jabatan_guru b", "a.id_guru=b.id_guru AND b.id_tp=" . $id_tp . " AND b.id_smt=" . $id_smt, "left");
        $this->db->join("level_guru e", "b.id_jabatan=e.id_level", "left");
        $this->db->join("master_kelas f", "a.id_guru=f.guru_id AND f.id_tp=" . $id_tp . " AND f.id_smt=" . $id_smt, "left");
        $this->db->join("level_kelas g", "f.level_id=g.id_level", "left");
        $this->db->where("a.id_guru", $id_guru);
        $query = $this->db->get()->row();
        return $query;
    }
    public function getListGuruByUserId($id_tp, $id_smt)
    {
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("a.id_guru, a.nama_guru, a.id_user, a.foto, b.id_jabatan, b.id_kelas as wali_kelas, f.level_id, g.level");
        $this->db->from("master_guru a");
        $this->db->join("jabatan_guru b", "a.id_guru=b.id_guru AND b.id_tp=" . $id_tp . " AND b.id_smt=" . $id_smt, "left");
        $this->db->join("level_guru e", "b.id_jabatan=e.id_level", "left");
        $this->db->join("master_kelas f", "a.id_guru=f.guru_id AND f.id_tp=" . $id_tp . " AND f.id_smt=" . $id_smt, "left");
        $this->db->join("level_kelas g", "f.level_id=g.id_level", "left");
        $query = $this->db->get()->result();
        $rest = [];
        foreach ($query as $guru) {
            $rest[$guru->id_guru] = $guru;
        }
        return $rest;
    }
    public function getDetailGuruByUserId($id_user, $id_tp, $id_smt)
    {
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("*");
        $this->db->from("master_guru a");
        $this->db->join("jabatan_guru b", "a.id_guru=b.id_guru AND b.id_tp=" . $id_tp . " AND b.id_smt=" . $id_smt, "left");
        $this->db->join("level_guru e", "b.id_jabatan=e.id_level", "left");
        $this->db->join("master_kelas f", "a.id_guru=f.guru_id AND f.id_tp=" . $id_tp . " AND f.id_smt=" . $id_smt, "left");
        $this->db->where("a.id_user", $id_user);
        $query = $this->db->get()->row();
        return $query;
    }
    public function getKelasByMapel($id_mapel = null)
    {
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("*");
        $this->db->from("master_kelas");
        $this->db->join("master_mapel b", "a.mapel_id=b.id_mapel", "left");
        $this->db->join("level_guru d", "a.level_id=d.id_level", "left");
        $query = $this->db->get()->row();
        return $query;
    }
    public function get_where($table, $pk, $id, $join = null, $order = null)
    {
        $this->db->select("*");
        $this->db->from($table);
        $this->db->where($pk, $id);
        if ($join !== null) {
            foreach ($join as $table => $field) {
                $this->db->join($table, $field);
            }
        }
        if ($order !== null) {
            foreach ($order as $field => $sort) {
                $this->db->order_by($field, $sort);
            }
        }
        $query = $this->db->get();
        return $query;
    }
    public function create($table, $data)
    {
        $insert = $this->db->insert($table, $data);
        return $insert;
    }
    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->update($table, $data, array($pk => $id));
            return $insert;
        }
        $insert = $this->db->update_batch($table, $data, $pk);
        return $insert;
    }
    public function getDataSiswa($username, $id_tp, $id_smt)
    {
        $this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("*");
        $this->db->from("master_siswa a");
        $this->db->join("kelas_siswa b", "a.id_siswa=b.id_siswa AND b.id_tp=" . $id_tp . " AND b.id_smt=" . $id_smt, "left");
        $this->db->join("master_kelas c", "b.id_kelas=c.id_kelas AND c.id_tp=" . $id_tp . " AND c.id_smt=" . $id_smt, "left");
        $this->db->join("cbt_sesi_siswa d", "a.id_siswa=d.siswa_id", "left");
        $this->db->where("username", $username);
        $query = $this->db->get()->row();
        return $query;
    }
    public function loadPengumuman($id_for)
    {
        $this->db->select("a.*, b.nama_guru, b.foto");
        $this->db->from("pengumuman a");
        $this->db->join("master_guru b", "a.dari=b.id_guru", "left");
        $this->db->where("kepada", $id_for);
        $query = $this->db->get()->result();
        return $query;
    }
    public function loadJadwalHariIni($id_tp, $id_smt, $id_kelas = null, $id_hari = null)
    {
        $this->db->select("*");
        $this->db->from("kelas_jadwal_mapel a");
        $this->db->join("master_mapel b", "b.id_mapel=a.id_mapel", "left");
        $this->db->where("a.id_tp", $id_tp);
        $this->db->where("a.id_smt", $id_smt);
        if ($id_kelas != null) {
            $this->db->where("a.id_kelas", $id_kelas);
        }
        if ($id_hari != null) {
            $this->db->where("a.id_hari", $id_hari);
        }
        $query = $this->db->get()->result();
        return $query;
    }
    public function getJadwalKbm($id_tp, $id_smt, $id_kelas = null)
    {
        $this->db->select("*");
        $this->db->from("kelas_jadwal_kbm");
        $this->db->where("id_tp", $id_tp);
        $this->db->where("id_smt", $id_smt);
        if ($id_kelas != null) {
            $this->db->where("id_kelas", $id_kelas);
            $query = $this->db->get()->row();
            return $query;
        }
        $query = $this->db->get()->result();
        return $query;
    }
}
