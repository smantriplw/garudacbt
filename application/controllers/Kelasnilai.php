<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Kelasnilai extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto WVGzV;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto Oms8S;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        Oms8S:
        WVGzV:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Dropdown_model", "dropdown");
        $this->load->model("Kelas_model", "kelas");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto rQ0RI;
        }
        $data = json_encode($data);
        rQ0RI:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Rekapitulasi Nilai Siswa", "subjudul" => "Nilai dalam satu semester", "setting" => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $data["mapel"] = $this->dropdown->getAllMapel();
            $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("kelas/nilai/data");
            $this->load->view("_templates/dashboard/_footer");
            goto SXN7H;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $nguru[$guru->id_guru] = $guru->nama_guru;
        $data["guru"] = $guru;
        $data["id_guru"] = $guru->id_guru;
        $mapel_guru = $this->kelas->getGuruMapelKelas($guru->id_guru, $tp->id_tp, $smt->id_smt);
        $mapel = json_decode(json_encode(unserialize($mapel_guru->mapel_kelas)));
        $arrMapel = [];
        $arrKelas = [];
        if (!($mapel != null)) {
            goto qHuxt;
        }
        foreach ($mapel as $m) {
            $arrMapel[$m->id_mapel] = $m->nama_mapel;
            foreach ($m->kelas_mapel as $kls) {
                $arrKelas[$m->id_mapel][] = ["id_kelas" => $kls->kelas, "nama_kelas" => $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $kls->kelas)];
            }
        }
        qHuxt:
        $arrId = [];
        if (!($mapel != null)) {
            goto ZUusC;
        }
        foreach ($mapel[0]->kelas_mapel as $id_mapel) {
            array_push($arrId, $id_mapel->kelas);
        }
        ZUusC:
        $data["mapel"] = $arrMapel;
        $data["arrkelas"] = $arrKelas;
        $data["kelas"] = count($arrId) > 0 ? $this->dropdown->getAllKelasByArrayId($tp->id_tp, $smt->id_smt, $arrId) : [];
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("kelas/nilai/data");
        $this->load->view("members/guru/templates/footer");
        SXN7H:
    }
    public function loadNilaiMapel()
    {
        $kelas = $this->input->get("kelas");
        $mapel = $this->input->get("mapel");
        $tahun = $this->input->get("tahun");
        $smt = $this->input->get("smt");
        $stahun = $this->input->get("stahun");
        $siswa = $this->kelas->getKelasSiswa($kelas, $tahun, $smt);
        if ($smt == "1") {
            $arrBulan = ["07", "08", "09", "10", "11", "12"];
            goto HiOGX;
        }
        $arrBulan = ["01", "02", "03", "04", "05", "06"];
        HiOGX:
        $namaBulan = ['', "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];
        $namaHari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        $infos = $this->kelas->getJadwalMapelByMapel($kelas, $mapel, $tahun, $smt);
        $log_siswa = $this->kelas->getRekapMateriSemester($kelas);
        $jadwal_per_bulan = [];
        $jadwal_materi = [];
        $log_materi = [];
        $cols = 0;
        foreach ($arrBulan as $bulan) {
            foreach ($infos as $info) {
                $jadwal_per_bulan[$info->id_hari][$info->jam_ke] = $info;
                $dates = $this->total_hari($info->id_hari, $bulan, $stahun);
                $mtr = null;
                $tgs = null;
                foreach ($dates as $date) {
                    $d = explode("-", $date);
                    $b = $d[1];
                    $t = $d[2];
                    $jj = $this->kelas->getAllMateriByTgl($kelas, $date, [$mapel]);
                    $mtr = isset($jj[$mapel]) && isset($jj[$mapel][$info->jam_ke]) && isset($jj[$mapel][$info->jam_ke][1]) ? $jj[$mapel][$info->jam_ke][1] : null;
                    $tgs = isset($jj[$mapel]) && isset($jj[$mapel][$info->jam_ke]) && isset($jj[$mapel][$info->jam_ke][2]) ? $jj[$mapel][$info->jam_ke][2] : null;
                    $jadwal_materi[$b][$t][$info->jam_ke][1] = $mtr;
                    $jadwal_materi[$b][$t][$info->jam_ke][2] = $tgs;
                    $cols++;
                }
            }
        }
        $log = [];
        if (count($siswa) > 0 && count($jadwal_per_bulan) > 0) {
            foreach ($siswa as $s) {
                $log[$s->id_siswa] = ["nama" => $s->nama, "nis" => $s->nis, "kelas" => $s->nama_kelas, "nilai_materi" => isset($log_siswa[1][$s->id_siswa]) ? $log_siswa[1][$s->id_siswa] : [], "nilai_tugas" => isset($log_siswa[2][$s->id_siswa]) ? $log_siswa[2][$s->id_siswa] : []];
            }
            $data = ["log" => $log, "materi" => $jadwal_materi, "bulans" => $arrBulan, "mapels" => $jadwal_per_bulan, "nilai" => $log_siswa, "cols" => $cols];
            goto xqr1u;
        }
        $data["mapels"] = [];
        xqr1u:
        $this->output_json($data);
    }
    function total_hari($id_day, $bulan, $taun)
    {
        $days = 0;
        $dates = [];
        $total_days = cal_days_in_month(CAL_GREGORIAN, $bulan, $taun);
        $idday = $id_day == "7" ? 0 : $id_day;
        $i = 1;
        JcGAa:
        if (!($i < $total_days)) {
            return $dates;
        }
        if (!(date("N", strtotime($taun . "-" . $bulan . "-" . $i)) == $idday)) {
            goto wZBR5;
        }
        $days++;
        array_push($dates, date("Y-m-d", strtotime($taun . "-" . $bulan . "-" . $i)));
        wZBR5:
        $i++;
        goto JcGAa;
    }
}
