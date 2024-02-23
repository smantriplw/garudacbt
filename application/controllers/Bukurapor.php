<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Bukurapor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto ATcMo;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto JRUq5;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        JRUq5:
        ATcMo:
        $this->load->library(["datatables", "form_validation"]);
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto OrF07;
        }
        $data = json_encode($data);
        OrF07:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Rapor_model", "rapor");
        $this->load->model("Kelas_model", "kelas");
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Master_model", "master");
        if (!$this->db->table_exists("buku_nilai")) {
            goto KO9li;
        }
        $total = $this->dashboard->total("buku_nilai");
        if (!($total > 0)) {
            goto MRcIH;
        }
        $this->restoreNilai();
        MRcIH:
        KO9li:
        $id_tp = $this->input->get("tp", true);
        $id_smt = $this->input->get("smt", true);
        $id_kelas = $this->input->get("kls", true);
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Kumpulan Nilai Rapor", "subjudul" => "Nilai Rapor Siswa", "setting" => $setting];
        $kelases = $this->kelas->getAllKelas();
        $all_kls = [];
        if (!$kelases) {
            goto cxlh6;
        }
        foreach ($kelases as $key => $row) {
            $all_kls[$row->id_tp][$row->id_smt][$row->id_kelas] = $row;
        }
        cxlh6:
        $siswas = [];
        $mapels = [];
        $kelompoks = [];
        $kelas = isset($all_kls[$id_tp]) && isset($all_kls[$id_tp][$id_smt]) && isset($all_kls[$id_tp][$id_smt][$id_kelas]) ? $all_kls[$id_tp][$id_smt][$id_kelas] : null;
        if (!($kelas != null)) {
            goto m9ATZ;
        }
        $jurusan = $this->kelas->getJurusanById($kelas->id_jurusan);
        $kelompoks = $this->master->getKodeKelompokMapel();
        $siswas = $this->rapor->getDetailSiswa($id_kelas, $id_tp, $id_smt);
        $kategori_mapel = $this->master->getKategoriKelompokMapel();
        $arrk = [];
        foreach ($kategori_mapel as $kk => $km) {
            if (in_array($km, $arrk)) {
                goto BS9gC;
            }
            array_push($arrk, $km->kode_kel_mapel);
            BS9gC:
        }
        $mapels = $this->master->getAllStatusMapel(empty($arrk) ? null : $arrk, isset($jurusan->mapel_peminatan) ? $jurusan->mapel_peminatan : null);
        $ekstras = $this->kelas->getKelasEkskul($id_kelas, $id_tp, $id_smt);
        m9ATZ:
        $settingRapor = $this->rapor->getRaporSetting($id_tp, $id_smt);
        $sikap = [];
        $nilai = [];
        $fisik = [];
        $desks = [];
        $absensi = [];
        $mapelEkstra = [];
        $nilaiEkstra = [];
        if ($id_smt === "1") {
            $other = "2";
            goto LmJvp;
        }
        $other = "1";
        LmJvp:
        $nilai_sikap = $this->rapor->getNilaiSikapByKelas($id_kelas, $id_tp, $id_smt);
        $nilai_rapor = $this->rapor->getNilaiRaporByKelas($id_kelas, $id_tp, $id_smt);
        $prestasis = $this->rapor->getPrestasiByKelas($id_kelas, $id_tp, $id_smt);
        $catatans = $this->rapor->getCatatanWaliByKelas($id_kelas, $id_tp, $id_smt);
        foreach ($catatans as $catatan) {
            $catatan->nilai = unserialize($catatan->nilai);
        }
        $i = 0;
        a84qx:
        if (!($i < count($siswas))) {
            $kkm = $this->rapor->getAllKkmRaporAkhir($id_kelas, $id_tp, $id_smt);
            $data["tp"] = $this->dashboard->getTahun();
            $data["tp_active"] = $this->dashboard->getTahunActive();
            $data["smt"] = $this->dashboard->getSemester();
            $data["smt_active"] = $this->dashboard->getSemesterActive();
            $data["tp_selected"] = $id_tp;
            $data["smt_selected"] = $id_smt;
            $data["kls_selected"] = $id_kelas;
            $data["tp_name"] = $id_tp != null ? $this->dashboard->getTahunById($id_tp) : null;
            $data["smt_name"] = $id_smt != null ? $this->dashboard->getSemesterById($id_smt) : null;
            $data["siswas"] = $siswas;
            $data["kelas"] = $kelas != null ? $kelas->nama_kelas : '';
            $data["lvl_kelas"] = $kelas != null ? $kelas->level_id : '';
            $data["mapels"] = $mapels;
            $data["kelompoks"] = $kelompoks;
            $data["sikap"] = $sikap;
            $data["nilai"] = $nilai;
            $data["nilai_rapor"] = $nilai_rapor;
            $data["deskripsi"] = $desks;
            $data["absensi"] = $absensi;
            $data["fisik"] = $fisik;
            $data["nilai_ekstra"] = $nilaiEkstra;
            $data["mapel_ekstra"] = $mapelEkstra;
            $data["kkm"] = $kkm;
            $data["rapor"] = $settingRapor;
            $data["naik"] = $this->rapor->getKenaikanRapor($id_kelas, $id_tp, $id_smt);
            if ($this->ion_auth->is_admin()) {
                $data["guru"] = $kelas == null ? '' : $this->dashboard->getDataGuruById($kelas->id_guru, $id_tp, $id_smt);
                if ($id_tp != null && $id_smt != null) {
                    $data["kelases"] = $this->dropdown->getAllKelas($id_tp, $id_smt);
                    goto kskgB;
                }
                $data["kelases"] = [];
                kskgB:
                $data["jabatan"] = null;
                $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
                $this->load->view("_templates/dashboard/_header", $data);
                $this->load->view("rapor/arsiprapor");
                $this->load->view("_templates/dashboard/_footer");
                goto kmA8R;
            }
            if ($id_tp != null && $id_smt != null) {
                $guru = $this->dashboard->getDataGuruByUserId($user->id, $id_tp, $id_smt);
                $data["guru"] = $guru;
                $data["kelases"] = $this->dropdown->getAllKelasByArrayId($id_tp, $id_smt, $guru->wali_kelas);
                goto GXpDs;
            }
            $tp_active = $this->dashboard->getTahunActive();
            $smt_active = $this->dashboard->getSemesterActive();
            $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp_active->id_tp, $smt_active->id_smt);
            $data["guru"] = $guru;
            $data["kelases"] = [];
            GXpDs:
            $data["jabatan"] = $this->master->getAllJabatanGuru($guru->id_guru);
            $this->load->view("members/guru/templates/header", $data);
            $this->load->view("rapor/arsiprapor");
            $this->load->view("members/guru/templates/footer");
            kmA8R:
            // [PHPDeobfuscator] Implied return
            return;
        }
        $siswa = $siswas[$i];
        $id_siswa = $siswa->id_siswa;
        $dummySikap = ["predikat" => ''];
        if (count($nilai_sikap) > 0) {
            foreach ($nilai_sikap as $nls) {
                if (!($nls->id_siswa == $id_siswa && $nls->jenis == "1")) {
                    goto jaYmW;
                }
                $sikap[$id_siswa][1] = ["deskripsi" => $nls == null ? '' : $nls->deskripsi, "predikat" => $nls == null ? $dummySikap : unserialize($nls->nilai)];
                jaYmW:
                if (!($nls->id_siswa == $id_siswa && $nls->jenis == "2")) {
                    goto ZEZaU;
                }
                $sikap[$id_siswa][2] = ["deskripsi" => $nls == null ? '' : $nls->deskripsi, "predikat" => $nls == null ? $dummySikap : unserialize($nls->nilai)];
                ZEZaU:
            }
            goto Q7eMS;
        }
        $sikap[$id_siswa][1] = ["deskripsi" => '', "predikat" => $dummySikap];
        $sikap[$id_siswa][2] = ["deskripsi" => '', "predikat" => $dummySikap];
        Q7eMS:
        foreach ($mapels as $mapel) {
            $dummyNilai = ["p_deskripsi" => '', "k_rata_rata" => '', "k_deskripsi" => '', "k_predikat" => '', "nilai" => '', "predikat" => ''];
            $key_mapel = array_search($mapel->id_mapel . $id_kelas . $id_siswa . $id_tp . $id_smt, array_column($nilai_rapor, "id_nilai_harian"));
            if (!($key_mapel !== false)) {
                goto xD84J;
            }
            $nr = $nilai_rapor[$key_mapel];
            $nilai[$id_siswa][$mapel->id_mapel] = $nr;
            xD84J:
        }
        $dummyDesks = ["ranking" => '', "rank_deskripsi" => '', "p1" => '', "p1_desk" => '', "p2" => '', "p2_desk" => '', "p3" => '', "p3_desk" => ''];
        $dummyAbsen = ["s" => " - ", "i" => " - ", "a" => " - ", "saran" => ''];
        $desks[$id_siswa] = isset($prestasis[$id_siswa]) ? $prestasis[$id_siswa] : $dummyDesks;
        $absensi[$id_siswa] = isset($catatans[$id_siswa]) ? $catatans[$id_siswa] : ["nilai" => $dummyAbsen];
        $dummyFisik = ["kondisi" => ["telinga" => '', "mata" => '', "gigi" => '', "lain" => ''], "smt" . $id_smt => ["tinggi" => '', "berat" => '', "tp" => $id_tp], "smt" . $other => ["tinggi" => '', "berat" => '', "tp" => $id_tp]];
        $nf = $this->rapor->getFisikKelas($id_kelas, $siswa->id_siswa, $id_tp, $id_smt);
        $nf2 = $this->rapor->getFisikKelas($id_kelas, $siswa->id_siswa, $id_tp, $other);
        $fisik[$siswa->id_siswa] = $nf != null ? ["kondisi" => unserialize($nf->kondisi), "smt" . $nf->id_smt => ["tinggi" => $nf->tinggi, "berat" => $nf->berat], "smt" . $other => ["tinggi" => $nf2 != null ? $nf2->tinggi : '', "berat" => $nf2 != null ? $nf2->berat : '']] : $dummyFisik;
        foreach ($ekstras as $ext) {
            $dummyEkstra = ["deskripsi" => '', "nilai" => '', "predikat" => ''];
            $arrEkstra = json_decode(json_encode(unserialize($ext->ekstra)));
            foreach ($arrEkstra as $ar) {
                $id_ekstra = $ar->ekstra;
                $mapelEkstra[$id_ekstra] = $this->kelas->getEkskulById($id_ekstra);
                if (!($id_ekstra != null)) {
                    goto SORAa;
                }
                $ne = $this->rapor->getEkstraKelas($id_ekstra, $siswa->id_siswa, $id_tp, $id_smt);
                $nilaiEkstra[$id_siswa][$id_ekstra] = $ne == null ? $dummyEkstra : $ne;
                SORAa:
            }
        }
        $i++;
        goto a84qx;
    }
    public function editNilaiRapor()
    {
        $this->load->model("Rapor_model", "rapor");
        $this->load->model("Dashboard_model", "dashboard");
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Buku Induk", "subjudul" => "Buku Induk", "setting" => $setting];
        $arrTp = $this->dashboard->getTahun();
        $arrSmt = $this->dashboard->getSemester();
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $id_siswa = $this->input->get("siswa", true);
        $id_tp = $this->input->get("tp", true);
        $id_smt = $this->input->get("smt", true);
        $mode = $this->input->get("mode", true);
        $data["tp_sel"] = $id_tp != null ? $this->dashboard->getTahunById($id_tp) : null;
        $data["smt_sel"] = $id_smt != null ? $this->dashboard->getSemesterById($id_smt) : null;
        $data["mode"] = $mode;
        $data["id_siswa"] = $id_siswa;
        $data["tp"] = $arrTp;
        $data["tp_active"] = $tp;
        $data["smt"] = $arrSmt;
        $data["smt_active"] = $smt;
        $data["siswa"] = $this->rapor->getDetailSiswaById($id_siswa, $id_tp, $id_smt);
        if ($mode == "1") {
            $data["sikap"] = $this->rapor->getNilaiSikapBySiswa($id_siswa, $id_tp, $id_smt);
            goto EQ3vr;
        }
        if ($mode == "2") {
            $data["pengetahuan"] = $this->rapor->getNilaiSikapBySiswa($id_siswa, $id_tp, $id_smt);
            goto EQ3vr;
        }
        if ($mode == "3") {
            $data["keterampilan"] = $this->rapor->getNilaiSikapBySiswa($id_siswa, $id_tp, $id_smt);
            goto EQ3vr;
        }
        if ($mode == "4") {
            $data["extra"] = $this->rapor->getNilaiSikapBySiswa($id_siswa, $id_tp, $id_smt);
            goto BT0Ew;
        }
        BT0Ew:
        EQ3vr:
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("rapor/editrapor");
            $this->load->view("_templates/dashboard/_footer");
            goto hcDFm;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $data["guru"] = $guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("rapor/editrapor");
        $this->load->view("members/guru/templates/footer");
        hcDFm:
    }
    public function getDataKelas()
    {
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Master_model", "master");
        $id_tp = $this->input->get("tp", true);
        $id_smt = $this->input->get("smt", true);
        $id_kelas = $this->input->get("kls", true);
        $user = $this->ion_auth->user()->row();
        $jabatan_guru = null;
        $this->load->model("Dashboard_model", "dashboard");
        if ($this->ion_auth->is_admin()) {
            $kelass = $this->dropdown->getAllKelas($id_tp, $id_smt);
            goto GI8Hi;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $id_tp, $id_smt);
        $nguru[$guru->id_guru] = $guru->nama_guru;
        $kelass = $this->dropdown->getAllKelasByArrayId($id_tp, $id_smt, [$id_kelas]);
        $jabatan_guru = $this->master->getAllJabatanGuru($guru->id_guru);
        GI8Hi:
        $this->output_json(["kelas" => $kelass, "jabatan" => $jabatan_guru]);
    }
    public function backupNilai()
    {
        $this->load->model("Rapor_model", "rapor");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Master_model", "master");
        $setting = $this->dashboard->getSetting();
        $tps = $this->dashboard->getTahun();
        $smts = $this->dashboard->getSemester();
        $gurus = $this->master->getAllWaliKelas();
        $mapels = $this->master->getAllMapel();
        $all_nilai = [];
        $kelas_ekstra = $this->rapor->getAllEkstra();
        $setting_rapor = $this->rapor->getAllRaporSetting();
        $kkms = $this->rapor->getAllKkm();
        $nilai_rapor = $this->rapor->getAllNilaiRapor();
        $nilai_extra = $this->rapor->getAllNilaiEkstra();
        $nilai_sikap = $this->rapor->getAllNilaiSikap();
        $rapor_fisik = $this->rapor->getAllFisik();
        $nilai_hph = [];
        $nilai_hpts = [];
        $nilai_hpas = [];
        $nilai_nr = [];
        $nilai_ekstra = [];
        foreach ($nilai_rapor as $nilai) {
            $kkm_tunggal = $setting_rapor[$nilai->id_tp][$nilai->id_smt]->kkm_tunggal == "1";
            $kkm_mapel = null;
            $all_kkm = [];
            if (!(isset($kkms[$nilai->id_tp]) && isset($kkms[$nilai->id_tp][$nilai->id_smt]) && isset($kkms[$nilai->id_tp][$nilai->id_smt][$nilai->id_kelas]))) {
                goto SfxFF;
            }
            $all_kkm = $kkms[$nilai->id_tp][$nilai->id_smt][$nilai->id_kelas];
            $kkm_mapel = isset($all_kkm[1]) && isset($all_kkm[1][$nilai->id_mapel]) ? $all_kkm[1][$nilai->id_mapel] : null;
            SfxFF:
            foreach ($mapels as $mapel) {
                if (!($mapel->id_mapel == $nilai->id_mapel)) {
                    goto JALdv;
                }
                $nilai_hph[$nilai->id_siswa][] = ["id_mapel" => $nilai->id_mapel, "mapel" => $nilai->mapel, "kkm" => $kkm_tunggal ? $setting_rapor[$nilai->id_tp][$nilai->id_smt]->kkm : ($kkm_mapel == null ? '' : $kkm_mapel->kkm), "p_nilai" => $nilai->p_rata_rata, "p_pred" => $nilai->p_predikat, "p_desk" => $nilai->p_deskripsi, "k_nilai" => $nilai->k_rata_rata, "k_pred" => $nilai->k_predikat, "k_desk" => $nilai->k_deskripsi];
                $nilai_hpts[$nilai->id_siswa][] = ["id_mapel" => $nilai->id_mapel, "mapel" => $nilai->mapel, "kkm" => $kkm_tunggal ? $setting_rapor[$nilai->id_tp][$nilai->id_smt]->kkm : ($kkm_mapel == null ? '' : $kkm_mapel->kkm), "nilai" => $nilai->nilai_pts, "pred" => $nilai->pts_predikat];
                $nilai_hpas[$nilai->id_siswa][] = ["id_mapel" => $nilai->id_mapel, "mapel" => $nilai->mapel, "kkm" => $kkm_tunggal ? $setting_rapor[$nilai->id_tp][$nilai->id_smt]->kkm : ($kkm_mapel == null ? '' : $kkm_mapel->kkm), "nilai" => $nilai->nilai_pas];
                $nilai_nr[$nilai->id_siswa][] = ["id_mapel" => $nilai->id_mapel, "mapel" => $nilai->mapel, "kkm" => $kkm_tunggal ? $setting_rapor[$nilai->id_tp][$nilai->id_smt]->kkm : ($kkm_mapel == null ? '' : $kkm_mapel->kkm), "nilai" => $nilai->nilai_rapor, "pred" => $nilai->rapor_predikat];
                JALdv:
            }
            $nilai_ekstra = [];
            if (!(isset($nilai_extra[$nilai->id_tp]) && isset($nilai_extra[$nilai->id_tp][$nilai->id_smt]) && isset($nilai_extra[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa]))) {
                goto QGJXs;
            }
            foreach ($nilai_extra[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa] as $ekstra) {
                $kkm_ekstra = '';
                if (!(isset($all_kkm[2]) && isset($all_kkm[2][$ekstra->id_ekstra]))) {
                    goto n0koo;
                }
                $kkm_ekstra = $all_kkm[2][$ekstra->id_ekstra]->kkm;
                n0koo:
                $nilai_ekstra[$nilai->id_siswa][] = ["mapel" => $ekstra->kode_ekstra, "id_ekstra" => $ekstra->id_ekstra, "nama_ekstra" => $ekstra->nama_ekstra, "kkm" => $kkm_tunggal ? $setting_rapor[$nilai->id_tp][$nilai->id_smt]->kkm : $kkm_ekstra, "nilai" => $ekstra->nilai, "pred" => $ekstra->predikat, "desk" => $ekstra->deskripsi];
            }
            QGJXs:
            $spiritual = null;
            $sosial = null;
            if (!(isset($nilai_sikap[$nilai->id_tp]) && isset($nilai_sikap[$nilai->id_tp][$nilai->id_smt]) && isset($nilai_sikap[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa]))) {
                goto xidUg;
            }
            $spiritual = isset($nilai_sikap[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa][1]) ? $nilai_sikap[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa][1] : null;
            $sosial = isset($nilai_sikap[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa][2]) ? $nilai_sikap[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa][2] : null;
            xidUg:
            $fisik = [];
            if (!isset($rapor_fisik[$nilai->id_siswa])) {
                goto jGkVI;
            }
            $fisik[] = $rapor_fisik[$nilai->id_siswa][$nilai->id_tp][$nilai->id_smt];
            jGkVI:
            $all_nilai[$nilai->id_tp][$nilai->id_smt][$nilai->id_siswa] = ["uid" => $nilai->uid, "id_siswa" => $nilai->id_siswa, "tp" => $nilai->tahun, "smt" => $nilai->nama_smt, "kelas" => $nilai->nama_kelas, "level" => $nilai->level_id, "wali_kelas" => $nilai->nama_guru, "jurusan" => $nilai->nama_jurusan, "hph" => serialize(isset($nilai_hph[$nilai->id_siswa]) ? $nilai_hph[$nilai->id_siswa] : []), "hpts" => serialize(isset($nilai_hpts[$nilai->id_siswa]) ? $nilai_hpts[$nilai->id_siswa] : []), "hpas" => serialize(isset($nilai_hpas[$nilai->id_siswa]) ? $nilai_hpas[$nilai->id_siswa] : []), "nilai_rapor" => serialize(isset($nilai_nr[$nilai->id_siswa]) ? $nilai_nr[$nilai->id_siswa] : []), "ekstra" => serialize(isset($nilai_ekstra[$nilai->id_siswa]) ? $nilai_ekstra[$nilai->id_siswa] : ''), "spritual" => $spiritual == null ? serialize([]) : serialize(["desk" => $spiritual->deskripsi, "nilai" => unserialize($spiritual->nilai)["predikat"]]), "sosial" => $sosial == null ? serialize([]) : serialize(["desk" => $sosial->deskripsi, "nilai" => unserialize($sosial->nilai)["predikat"]]), "rank" => serialize(["rank" => $nilai->ranking, "saran" => $nilai->rank_deskripsi]), "prestasi" => serialize([["nilai" => $nilai->p1, "desk" => $nilai->p1_desk], ["nilai" => $nilai->p2, "desk" => $nilai->p2_desk], ["nilai" => $nilai->p3, "desk" => $nilai->p3_desk]]), "absen" => $nilai->absen != null ? $nilai->absen : serialize([]), "saran" => $nilai->saran != null ? $nilai->saran : "-", "fisik" => serialize($fisik), "naik" => $nilai->naik != null ? $nilai->naik : "1", "setting_rapor" => serialize((array) $setting_rapor[$nilai->id_tp][$nilai->id_smt]), "setting_mapel" => serialize((array) $mapels)];
        }
        $insert = [];
        $ids_siswa = [];
        foreach ($tps as $tp) {
            foreach ($smts as $smt) {
                if (!(isset($all_nilai[$tp->id_tp]) && isset($all_nilai[$tp->id_tp][$smt->id_smt]))) {
                    goto eAyfW;
                }
                foreach ($all_nilai[$tp->id_tp][$smt->id_smt] as $nilai) {
                    $ids_siswa[$nilai["id_siswa"]] = $nilai["id_siswa"];
                    if ($this->rapor->exists($nilai["uid"], $nilai["tp"], $nilai["smt"], $nilai["kelas"])) {
                        goto gLRqI;
                    }
                    $insert[] = $nilai;
                    gLRqI:
                }
                eAyfW:
            }
        }
        $this->db->trans_start();
        if (!(count($insert) > 0)) {
            goto ca0F0;
        }
        $this->db->insert_batch("buku_nilai", $insert);
        $this->rapor->deleteNilaiRapor();
        ca0F0:
        $this->db->trans_complete();
        $res["nilai_ekstra"] = $mapels;
        $res["all_nilai"] = $all_nilai;
        $res["insert"] = $insert;
        $res["ids"] = $ids_siswa;
        $this->output_json($res);
    }
    public function restoreNilai()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Rapor_model", "rapor");
        $this->load->model("Kelas_model", "kelas");
        $this->load->model("Master_model", "master");
        $tps = $this->dashboard->getTahun();
        $smts = $this->dashboard->getSemester();
        $gurus = $this->master->getAllWaliKelas();
        $mapels = $this->master->getAllMapel();
        $siswas = $this->rapor->getDataKumpulanRapor();
        $kelass = $this->kelas->getAllKelas();
        $hph = [];
        $hpts = [];
        $hpas = [];
        $nilai_rapor = [];
        $ekstra = [];
        $spritual = [];
        $sosial = [];
        $rank = [];
        $prestasi = [];
        $absen = [];
        $fisik = [];
        foreach ($siswas as $id => $siswa) {
            $index_tp = array_search($siswa->tp, array_column($tps, "tahun"));
            $tp = $tps[$index_tp];
            $index_smt = array_search($siswa->smt, array_column($smts, "nama_smt"));
            $smt = $smts[$index_smt];
            $id_kelas = '';
            foreach ($kelass as $kelas) {
                if (!($kelas->id_tp == $tp->id_tp && $kelas->id_smt == $smt->id_smt && $kelas->nama_kelas == $siswa->kelas)) {
                    goto ARH8k;
                }
                $id_kelas = $kelas->id_kelas;
                ARH8k:
            }
            $hph[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->hph);
            $hpts[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->hpts);
            $hpas[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->hpas);
            $nilai_rapor[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->nilai_rapor);
            $ekstra[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->ekstra);
            $spritual[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->spritual);
            $sosial[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->sosial);
            $rank[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->rank);
            $prestasi[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->prestasi);
            $absen[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = ["nilai" => $siswa->absen, "deskripsi" => $siswa->saran];
            $fisik[$tp->id_tp][$smt->id_smt][$id][$id_kelas] = unserialize($siswa->fisik);
            foreach ($fisik[$tp->id_tp][$smt->id_smt][$id][$id_kelas] as $value) {
                $value->kondisi = unserialize($value->kondisi);
            }
        }
        $hph_insert = [];
        $hpts_insert = [];
        $hpas_insert = [];
        $ekstra_insert = [];
        $spritual_insert = [];
        $sosial_insert = [];
        $rank_insert = [];
        $absen_insert = [];
        $fisik_insert = [];
        foreach ($tps as $tp) {
            foreach ($smts as $smt) {
                if (!(isset($hph[$tp->id_tp]) && isset($hph[$tp->id_tp][$smt->id_smt]))) {
                    goto b5UDH;
                }
                foreach ($hph[$tp->id_tp][$smt->id_smt] as $id => $phs) {
                    foreach ($phs as $kls => $nilai) {
                        foreach ($nilai as $ph) {
                            $p_rata = (int) $ph["p_nilai"];
                            $k_rata = (int) $ph["k_nilai"];
                            $vals = ["id_nilai_harian" => $ph["id_mapel"] . $kls . $id . $tp->id_tp . $smt->id_smt, "id_siswa" => $id, "id_mapel" => $ph["id_mapel"], "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "p_rata_rata" => $p_rata, "p1" => $p_rata + 1, "p2" => $p_rata - 1, "p3" => $p_rata, "p4" => '', "p5" => '', "p6" => '', "p7" => '', "p8" => '', "p_predikat" => $ph["p_pred"], "p_deskripsi" => $ph["p_desk"], "k_rata_rata" => $k_rata, "k1" => $k_rata + 1, "k2" => $k_rata - 1, "k3" => $k_rata, "k4" => '', "k5" => '', "k6" => '', "k7" => '', "k8" => '', "k_predikat" => $ph["k_pred"], "k_deskripsi" => $ph["k_desk"], "jml" => ''];
                            $hph_insert[] = $vals;
                        }
                    }
                }
                b5UDH:
                if (!(isset($hpts[$tp->id_tp]) && isset($hpts[$tp->id_tp][$smt->id_smt]))) {
                    goto VNHdo;
                }
                foreach ($hpts[$tp->id_tp][$smt->id_smt] as $id => $pht) {
                    foreach ($pht as $kls => $nilai) {
                        foreach ($nilai as $ph) {
                            $vals = ["id_nilai_pts" => $ph["id_mapel"] . $kls . $id . $tp->id_tp . $smt->id_smt, "id_siswa" => $id, "id_mapel" => $ph["id_mapel"], "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "nilai" => $ph["nilai"], "predikat" => $ph["pred"]];
                            $hpts_insert[] = $vals;
                        }
                    }
                }
                VNHdo:
                if (!(isset($hpas[$tp->id_tp]) && isset($hpas[$tp->id_tp][$smt->id_smt]))) {
                    goto qfCQT;
                }
                foreach ($hpas[$tp->id_tp][$smt->id_smt] as $id => $pha) {
                    foreach ($pha as $kls => $nilai) {
                        foreach ($nilai as $ph) {
                            $nr = $nilai_rapor[$tp->id_tp][$smt->id_smt][$id][$kls];
                            $index = array_search($ph["id_mapel"], array_column($nr, "id_mapel"));
                            $hnr = $nr[$index];
                            $vals = ["id_nilai_akhir" => $ph["id_mapel"] . $kls . $id . $tp->id_tp . $smt->id_smt, "id_siswa" => $id, "id_mapel" => $ph["id_mapel"], "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "nilai" => $ph["nilai"], "akhir" => $hnr["nilai"], "predikat" => $hnr["pred"]];
                            $hpas_insert[] = $vals;
                        }
                    }
                }
                qfCQT:
                if (!(isset($ekstra[$tp->id_tp]) && isset($ekstra[$tp->id_tp][$smt->id_smt]))) {
                    goto YUCD6;
                }
                foreach ($ekstra[$tp->id_tp][$smt->id_smt] as $id => $pha) {
                    foreach ($pha as $kls => $nilai) {
                        if (!($nilai != '')) {
                            goto eQD81;
                        }
                        foreach ($nilai as $ph) {
                            $vals = ["id_nilai_ekstra" => $ph["id_ekstra"] . $kls . $id . $tp->id_tp . $smt->id_smt, "id_siswa" => $id, "id_ekstra" => $ph["id_ekstra"], "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "nilai" => $ph["nilai"], "predikat" => $ph["pred"], "deskripsi" => $ph["desk"]];
                            $ekstra_insert[] = $vals;
                        }
                        eQD81:
                    }
                }
                YUCD6:
                if (!(isset($spritual[$tp->id_tp]) && isset($spritual[$tp->id_tp][$smt->id_smt]))) {
                    goto nxbWy;
                }
                foreach ($spritual[$tp->id_tp][$smt->id_smt] as $id => $pht) {
                    foreach ($pht as $kls => $nilai) {
                        $vals = ["id_nilai_sikap" => $kls . $id . $tp->id_tp . $smt->id_smt . "1", "id_siswa" => $id, "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "jenis" => "1", "nilai" => serialize(["predikat" => $nilai["nilai"], "sl1" => '', "sl2" => '', "sl3" => '', "mb1" => '', "mb2" => '', "mb3" => '']), "deskripsi" => $nilai["desk"]];
                        $spritual_insert[] = $vals;
                    }
                }
                nxbWy:
                if (!(isset($sosial[$tp->id_tp]) && isset($sosial[$tp->id_tp][$smt->id_smt]))) {
                    goto uHqbF;
                }
                foreach ($sosial[$tp->id_tp][$smt->id_smt] as $id => $pht) {
                    foreach ($pht as $kls => $nilai) {
                        $vals = ["id_nilai_sikap" => $kls . $id . $tp->id_tp . $smt->id_smt . "2", "id_siswa" => $id, "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "jenis" => "2", "nilai" => serialize(["predikat" => $nilai["nilai"], "sl1" => '', "sl2" => '', "sl3" => '', "mb1" => '', "mb2" => '', "mb3" => '']), "deskripsi" => $nilai["desk"]];
                        $sosial_insert[] = $vals;
                    }
                }
                uHqbF:
                if (!(isset($rank[$tp->id_tp]) && isset($rank[$tp->id_tp][$smt->id_smt]))) {
                    goto fo6jY;
                }
                foreach ($rank[$tp->id_tp][$smt->id_smt] as $id => $pht) {
                    foreach ($pht as $kls => $nilai) {
                        $prt = $prestasi[$tp->id_tp][$smt->id_smt][$id][$kls];
                        $vals = ["id_ranking" => $kls . $id . $tp->id_tp . $smt->id_smt, "id_siswa" => $id, "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "ranking" => $nilai["rank"], "deskripsi" => $nilai["saran"], "p1" => $prt[0]["nilai"], "p1_desk" => $prt[0]["desk"], "p2" => $prt[1]["nilai"], "p2_desk" => $prt[1]["desk"], "p3" => $prt[2]["nilai"], "p3_desk" => $prt[2]["desk"]];
                        $rank_insert[] = $vals;
                    }
                }
                fo6jY:
                if (!(isset($absen[$tp->id_tp]) && isset($absen[$tp->id_tp][$smt->id_smt]))) {
                    goto MTKwN;
                }
                foreach ($absen[$tp->id_tp][$smt->id_smt] as $id => $pht) {
                    foreach ($pht as $kls => $nilai) {
                        $vals = ["id_catatan_wali" => $kls . $id . $tp->id_tp . $smt->id_smt, "id_siswa" => $id, "id_kelas" => $kls, "id_tp" => $tp->id_tp, "id_smt" => $smt->id_smt, "nilai" => $nilai["nilai"], "deskripsi" => $nilai["deskripsi"]];
                        $absen_insert[] = $vals;
                    }
                }
                MTKwN:
            }
        }
        $this->db->trans_start();
        $res = 0;
        if (!(count($rank_insert) > 0)) {
            goto jpb4g;
        }
        $res += $this->db->insert_batch("rapor_prestasi", $rank_insert);
        jpb4g:
        if (!(count($absen_insert) > 0)) {
            goto ph799;
        }
        $res += $this->db->insert_batch("rapor_catatan_wali", $absen_insert);
        ph799:
        if (!(count($ekstra_insert) > 0)) {
            goto uKprX;
        }
        $res += $this->db->insert_batch("rapor_nilai_ekstra", $ekstra_insert);
        uKprX:
        if (!(count($hpas_insert) > 0)) {
            goto jk_y0;
        }
        $res += $this->db->insert_batch("rapor_nilai_akhir", $hpas_insert);
        jk_y0:
        if (!(count($hpts_insert) > 0)) {
            goto O7Dzp;
        }
        $res += $this->db->insert_batch("rapor_nilai_pts", $hpts_insert);
        O7Dzp:
        if (!(count($hph_insert) > 0)) {
            goto q6EtD;
        }
        $res += $this->db->insert_batch("rapor_nilai_harian", $hph_insert);
        q6EtD:
        if (!(count($spritual_insert) > 0)) {
            goto YhFpJ;
        }
        $res += $this->db->insert_batch("rapor_nilai_sikap", $spritual_insert);
        YhFpJ:
        if (!(count($sosial_insert) > 0)) {
            goto v79B6;
        }
        $res += $this->db->insert_batch("rapor_nilai_sikap", $sosial_insert);
        v79B6:
        if (!$res) {
            goto NEyqV;
        }
        $this->db->empty_table("buku_nilai");
        NEyqV:
        $this->db->trans_complete();
        return $res;
    }
    public function edit()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Rapor_model", "rapor");
        $kelas = $this->input->get("kelas", true);
        $tahun = $this->input->get("tahun", true);
        $semester = $this->input->get("semester", true);
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Edit Nilai", "subjudul" => "Nilai Rapor Kelas " . $kelas . ", TP:" . $tahun . ", SMT:" . $semester, "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $siswas = $this->rapor->getDataKumpulanRapor($kelas, $tahun, $semester);
        foreach ($siswas as $siswa) {
            $siswa->hph = unserialize($siswa->hph);
            $siswa->hpts = unserialize($siswa->hpts);
            $siswa->hpas = unserialize($siswa->hpas);
            $siswa->nilai_rapor = unserialize($siswa->nilai_rapor);
            $siswa->ekstra = unserialize($siswa->ekstra);
            $siswa->spritual = unserialize($siswa->spritual);
            $siswa->sosial = unserialize($siswa->sosial);
            $siswa->rank = unserialize($siswa->rank);
            $siswa->prestasi = unserialize($siswa->prestasi);
            $siswa->absen = unserialize($siswa->absen);
            $siswa->fisik = unserialize($siswa->fisik);
            foreach ($siswa->fisik as $value) {
                $value->kondisi = unserialize($value->kondisi);
            }
            $siswa->setting_rapor = unserialize($siswa->setting_rapor);
            $siswa->setting_mapel = unserialize($siswa->setting_mapel);
        }
        $data["siswas"] = $siswas;
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("setting/datarapor");
            $this->load->view("_templates/dashboard/_footer");
            goto jftBs;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $nguru[$guru->id_guru] = $guru->nama_guru;
        $data["guru"] = $guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("setting/datarapor");
        $this->load->view("members/guru/templates/footer");
        jftBs:
    }
    public function ledger()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Rapor_model", "rapor");
        $kelas = $this->input->get("kelas", true);
        $tahun = $this->input->get("tahun", true);
        $semester = $this->input->get("semester", true);
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Edit Nilai", "subjudul" => "Nilai Rapor Kelas " . $kelas . ", TP:" . $tahun . ", SMT:" . $semester, "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $siswas = $this->rapor->getDataKumpulanRapor($kelas, $tahun, $semester);
        foreach ($siswas as $siswa) {
            $siswa->hph = unserialize($siswa->hph);
            $siswa->hpts = unserialize($siswa->hpts);
            $siswa->hpas = unserialize($siswa->hpas);
            $siswa->nilai_rapor = unserialize($siswa->nilai_rapor);
            $siswa->ekstra = unserialize($siswa->ekstra);
            $siswa->spritual = unserialize($siswa->spritual);
            $siswa->sosial = unserialize($siswa->sosial);
            $siswa->rank = unserialize($siswa->rank);
            $siswa->prestasi = unserialize($siswa->prestasi);
            $siswa->absen = unserialize($siswa->absen);
            $siswa->fisik = unserialize($siswa->fisik);
            foreach ($siswa->fisik as $value) {
                $value->kondisi = unserialize($value->kondisi);
            }
            $siswa->setting_rapor = unserialize($siswa->setting_rapor);
            $siswa->setting_mapel = unserialize($siswa->setting_mapel);
        }
        $data["siswas"] = $siswas;
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("setting/datarapor");
            $this->load->view("_templates/dashboard/_footer");
            goto mVxE7;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $nguru[$guru->id_guru] = $guru->nama_guru;
        $data["guru"] = $guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("setting/datarapor");
        $this->load->view("members/guru/templates/footer");
        mVxE7:
    }
    public function dkn()
    {
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Rapor_model", "rapor");
        $kelas = $this->input->get("kelas", true);
        $tahun = $this->input->get("tahun", true);
        $semester = $this->input->get("semester", true);
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Edit Nilai", "subjudul" => "Nilai Rapor Kelas " . $kelas . ", TP:" . $tahun . ", SMT:" . $semester, "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $siswas = $this->rapor->getDataKumpulanRapor($kelas, $tahun, $semester);
        foreach ($siswas as $siswa) {
            $siswa->hph = unserialize($siswa->hph);
            $siswa->hpts = unserialize($siswa->hpts);
            $siswa->hpas = unserialize($siswa->hpas);
            $siswa->nilai_rapor = unserialize($siswa->nilai_rapor);
            $siswa->ekstra = unserialize($siswa->ekstra);
            $siswa->spritual = unserialize($siswa->spritual);
            $siswa->sosial = unserialize($siswa->sosial);
            $siswa->rank = unserialize($siswa->rank);
            $siswa->prestasi = unserialize($siswa->prestasi);
            $siswa->absen = unserialize($siswa->absen);
            $siswa->fisik = unserialize($siswa->fisik);
            foreach ($siswa->fisik as $value) {
                $value->kondisi = unserialize($value->kondisi);
            }
            $siswa->setting_rapor = unserialize($siswa->setting_rapor);
            $siswa->setting_mapel = unserialize($siswa->setting_mapel);
        }
        $data["siswas"] = $siswas;
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("setting/datarapor");
            $this->load->view("_templates/dashboard/_footer");
            goto oc9Ov;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $nguru[$guru->id_guru] = $guru->nama_guru;
        $data["guru"] = $guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("setting/datarapor");
        $this->load->view("members/guru/templates/footer");
        oc9Ov:
    }
    function group_by($key, $data)
    {
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val->{$key}][] = $val;
                goto Ax3Cv;
            }
            $result[''][] = $val;
            Ax3Cv:
        }
        return $result;
    }
}
