<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Dropdown_model extends CI_Model
{
    public function getBulan()
    {
        $result = $this->db->get("bulan")->result();
        $ret = [];
        if (!$result) {
            goto CEHhN;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_bln] = $row->nama_bln;
        }
        CEHhN:
        return $ret;
    }
    public function getAllSesi()
    {
        $this->db->select("id_sesi, nama_sesi, kode_sesi");
        $result = $this->db->get("cbt_sesi")->result();
        if (!$result) {
            goto Pf8Zs;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_sesi] = $row->nama_sesi;
        }
        Pf8Zs:
        return $ret;
    }
    public function getAllRuang()
    {
        $result = $this->db->get("cbt_ruang")->result();
        $ret = [];
        if (!$result) {
            goto nZNGf;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_ruang] = $row->nama_ruang;
        }
        nZNGf:
        return $ret;
    }
    public function getAllWaktuSesi()
    {
        $result = $this->db->get("cbt_sesi")->result();
        if (!$result) {
            goto cH63o;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_sesi] = ["mulai" => $row->waktu_mulai, "akhir" => $row->waktu_akhir];
        }
        cH63o:
        return $ret;
    }
    public function getDataKelompokMapel()
    {
        $this->db->select("*");
        $this->db->from("master_kelompok_mapel");
        $this->db->order_by("kode_kel_mapel");
        $result = $this->db->get()->result();
        $ret = [];
        foreach ($result as $row) {
            $ret[$row->kode_kel_mapel] = $row->nama_kel_mapel;
        }
        return $ret;
    }
    public function getAllMapel()
    {
        $this->db->select("id_mapel,nama_mapel,urutan_tampil");
        $this->db->order_by("urutan_tampil");
        $this->db->where("status", "1");
        $result = $this->db->get("master_mapel")->result();
        if (!$result) {
            goto i884v;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_mapel] = $row->nama_mapel;
        }
        i884v:
        return $ret;
    }
    public function getAllKodeMapel()
    {
        $this->db->order_by("urutan_tampil");
        $this->db->where("status", "1");
        $result = $this->db->get("master_mapel")->result();
        $ret[''] = "Tidak ada";
        if (!$result) {
            goto BIDk6;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_mapel] = $row->kode;
        }
        BIDk6:
        return $ret;
    }
    public function getAllMapelPeminatan()
    {
        $this->db->select("*");
        $this->db->from("master_kelompok_mapel");
        $this->db->where("kategori <> \"WAJIB\"")->or_where("kategori <> \"PAI (Kemenag)\"");
        $res = $this->db->get("master_mapel")->result();
        $ress = [];
        if (!$res) {
            goto RfM0q;
        }
        foreach ($res as $key => $row) {
            $ress[$row->id_kel_mapel] = $row->kode_kel_mapel;
        }
        RfM0q:
        $ret = [];
        if (!(count($ress) > 0)) {
            goto DxrBX;
        }
        $this->db->where_in("kelompok", $ress);
        $this->db->order_by("urutan_tampil");
        $result = $this->db->get("master_mapel")->result();
        if (!$result) {
            goto wMevi;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_mapel] = $row->nama_mapel;
        }
        wMevi:
        DxrBX:
        return $ret;
    }
    public function getAllLevel($jenjang)
    {
        $levels = [];
        if ($jenjang == "1") {
            $levels = ["1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6"];
            goto W8Urr;
        }
        if ($jenjang == "2") {
            $levels = ["7" => "7", "8" => "8", "9" => "9"];
            goto W8Urr;
        }
        if ($jenjang == "3") {
            $levels = ["10" => "10", "11" => "11", "12" => "12"];
            goto vh7U6;
        }
        vh7U6:
        W8Urr:
        return $levels;
    }
    public function getAllKelas($tp, $smt, $level = null)
    {
        $this->db->select("*");
        $this->db->from("master_kelas");
        $this->db->where("id_tp", $tp);
        $this->db->where("id_smt", $smt);
        $this->db->order_by("level_id", "ASC");
        $this->db->order_by("nama_kelas", "ASC");
        if (!($level != null)) {
            goto t0IDo;
        }
        $this->db->where("level_id" . $level);
        t0IDo:
        $result = $this->db->get()->result();
        $ret = [];
        if ($result) {
            foreach ($result as $key => $row) {
                $ret[$row->id_kelas] = $row->nama_kelas;
            }
            goto BWoNw;
        }
        $ret = [];
        BWoNw:
        return $ret;
    }
    public function getAllKeyKodeKelas($tp, $smt)
    {
        $this->db->select("*");
        $this->db->from("master_kelas");
        $this->db->where("id_tp", $tp);
        $this->db->where("id_smt", $smt);
        $result = $this->db->get()->result();
        if ($result) {
            foreach ($result as $key => $row) {
                $ret[$row->kode_kelas] = $row->nama_kelas;
            }
            goto OwS4T;
        }
        $ret = [];
        OwS4T:
        return $ret;
    }
    public function getAllKodeKelas($tp = null, $smt = null)
    {
        $this->db->select("*");
        $this->db->from("master_kelas");
        if (!($tp != null)) {
            goto Rf6US;
        }
        $this->db->where("id_tp", $tp);
        Rf6US:
        if (!($smt != null)) {
            goto nuq07;
        }
        $this->db->where("id_smt", $smt);
        nuq07:
        $result = $this->db->get()->result();
        if ($result) {
            foreach ($result as $key => $row) {
                $ret[$row->id_kelas] = $row->kode_kelas;
            }
            goto eEMTK;
        }
        $ret = [];
        eEMTK:
        return $ret;
    }
    public function getNamaKelasById($tp, $smt, $id)
    {
        $this->db->select("nama_kelas");
        $this->db->where("id_kelas", $id);
        $this->db->where("id_tp", $tp);
        $this->db->where("id_smt", $smt);
        $result = $this->db->get("master_kelas")->row();
        if ($result != null) {
            return $result->nama_kelas;
        }
        return null;
    }
    public function getAllKelasByArrayId($tp, $smt, $arrId)
    {
        $this->db->select("*");
        $this->db->from("master_kelas");
        $this->db->where("id_tp", $tp);
        $this->db->where_in("id_kelas", $arrId);
        $result = $this->db->get()->result();
        $ret = [];
        if (!$result) {
            goto Xe6Ik;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_kelas] = $row->nama_kelas;
        }
        Xe6Ik:
        return $ret;
    }
    public function getAllEkskul()
    {
        $result = $this->db->get("master_ekstra")->result();
        if (!$result) {
            goto pq72I;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_ekstra] = $row->nama_ekstra;
        }
        pq72I:
        return $ret;
    }
    public function getAllKodeEkskul()
    {
        $result = $this->db->get("master_ekstra")->result();
        if (!$result) {
            goto O7PE4;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_ekstra] = $row->kode_ekstra;
        }
        O7PE4:
        return $ret;
    }
    public function getAllJurusan()
    {
        $result = $this->db->get("master_jurusan")->result();
        if (!$result) {
            goto xA6cM;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_jurusan] = $row->kode_jurusan;
        }
        xA6cM:
        return $ret;
    }
    public function getAllGuru()
    {
        $this->db->select("a.id_guru, a.nama_guru");
        $this->db->from("master_guru a");
        $this->db->join("users e", "a.username=e.username");
        $result = $this->db->get()->result();
        $ret["0"] = "Pilih Guru :";
        if (!$result) {
            goto pIs2i;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_guru] = $row->nama_guru;
        }
        pIs2i:
        return $ret;
    }
    public function getAllLevelGuru()
    {
        $result = $this->db->get("level_guru")->result();
        $ret[''] = "Pilih Jabatan :";
        if (!$result) {
            goto IbUMQ;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_level] = $row->level;
        }
        IbUMQ:
        return $ret;
    }
    public function getAllJenisUjian()
    {
        $result = $this->db->get("cbt_jenis")->result();
        if (!$result) {
            goto dM_hu;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_jenis] = $row->nama_jenis . " (" . $row->kode_jenis . ")";
        }
        dM_hu:
        return $ret;
    }
    public function getAllBankSoal()
    {
        $result = $this->db->get("cbt_bank_soal")->result();
        $ret[''] = "Pilih Bank Soal :";
        if (!$result) {
            goto ACZZw;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_bank] = $row->bank_kode;
        }
        ACZZw:
        return $ret;
    }
    public function getAllJadwal($tp, $smt)
    {
        $this->db->from("cbt_jadwal a");
        $this->db->join("cbt_bank_soal b", "b.id_bank=a.id_bank");
        $this->db->where("a.id_tp", $tp);
        $this->db->where("a.id_smt", $smt);
        $result = $this->db->get()->result();
        $ret = [];
        if (!$result) {
            goto jkIsu;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_jadwal] = $row->bank_kode;
        }
        jkIsu:
        return $ret;
    }
    public function getAllJadwalGuru($tp, $smt, $guru)
    {
        $this->db->from("cbt_jadwal a");
        $this->db->join("cbt_bank_soal b", "b.id_bank=a.id_bank AND b.bank_guru_id=" . $guru);
        $this->db->where("a.id_tp", $tp);
        $this->db->where("a.id_smt", $smt);
        $result = $this->db->get()->result();
        $ret = [];
        if (!$result) {
            goto Fgwzz;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_jadwal] = $row->bank_kode;
        }
        Fgwzz:
        return $ret;
    }
    public function getAllJenisJadwal($tp, $smt, $jenis, $mapel)
    {
        $this->db->from("cbt_jadwal a");
        if ($mapel == "0") {
            $this->db->join("cbt_bank_soal b", "b.id_bank=a.id_bank");
            goto uF3Aa;
        }
        $this->db->join("cbt_bank_soal b", "b.id_bank=a.id_bank AND b.bank_mapel_id=" . $mapel . " ");
        uF3Aa:
        $this->db->where("a.id_tp", $tp);
        $this->db->where("a.id_smt", $smt);
        $this->db->where("a.id_jenis", $jenis);
        $result = $this->db->get()->result();
        $ret = [];
        if (!$result) {
            goto jU138;
        }
        foreach ($result as $key => $row) {
            $ret[$row->id_jadwal] = $row->bank_kode;
        }
        jU138:
        return $ret;
    }
}
