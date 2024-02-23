<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Kelasabsensiharian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto PF8ku;
        }
        if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("guru"))) {
            goto dRJdm;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        dRJdm:
        PF8ku:
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
            goto XG9b4;
        }
        $data = json_encode($data);
        XG9b4:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = ["user" => $user, "judul" => "Kehadiran Harian Siswa", "subjudul" => "Data Kehadiran Siswa", "setting" => $this->dashboard->getSetting()];
        $tp = $this->master->getTahunActive();
        $smt = $this->master->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $data["kelas"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["mapel"] = $this->dropdown->getAllMapel();
        if ($this->ion_auth->is_admin()) {
            $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
            $data["guru"] = $this->dropdown->getAllGuru();
            $this->load->view("_templates/dashboard/_header", $data);
            $this->load->view("kelas/absenharian/data");
            $this->load->view("_templates/dashboard/_footer");
            goto AWWr6;
        }
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        $nguru[$guru->id_guru] = $guru->nama_guru;
        $data["guru"] = $guru;
        $data["id_guru"] = $guru->id_guru;
        $this->load->view("members/guru/templates/header", $data);
        $this->load->view("kelas/absenharian/data");
        $this->load->view("members/guru/templates/footer");
        AWWr6:
    }
    public function loadAbsensi()
    {
        $id_kelas = $this->input->post("kelas", true);
        $tahun = $this->input->post("thn", true);
        $bulan = $this->input->post("bln", true);
        $tanggal = $this->input->post("tgl", true);
        $hari = $this->input->post("hari", true);
        $id_tp = $this->master->getTahunActive()->id_tp;
        $id_smt = $this->master->getSemesterActive()->id_smt;
        $bulan = str_pad($bulan, 2, "0", STR_PAD_LEFT);
        $tanggal = str_pad($tanggal, 2, "0", STR_PAD_LEFT);
        $info = $this->dashboard->getJadwalKbm($id_tp, $id_smt, $id_kelas);
        if ($info != null) {
            $istirahat = unserialize($info->istirahat);
            goto LQF0c;
        }
        $istirahat = [];
        LQF0c:
        $jadwal = $this->dashboard->loadJadwalHariIni($id_tp, $id_smt, $id_kelas, $hari);
        $arrIdMapel = [];
        foreach ($jadwal as $jd) {
            array_push($arrIdMapel, $jd->id_mapel);
        }
        $jadwal_materi = [];
        if (!(count($arrIdMapel) > 0)) {
            goto Kvoxd;
        }
        $jadwal_materi = $this->kelas->getAllMateriByTgl($id_kelas, $tahun . "-" . $bulan . "-" . $tanggal, $arrIdMapel);
        Kvoxd:
        $arrIdKjm = [];
        foreach ($jadwal_materi as $jmtr) {
            foreach ($jmtr as $jam) {
                foreach ($jam as $jns) {
                    array_push($arrIdKjm, $jns->id_kjm);
                }
            }
        }
        $siswa = $this->kelas->getKelasSiswa($id_kelas, $id_tp, $id_smt);
        $log = [];
        if (!($info != null)) {
            goto JauBe;
        }
        foreach ($siswa as $s) {
            $status_materi = [];
            if (!(count($arrIdKjm) > 0)) {
                goto q3loa;
            }
            $status_materi = $this->kelas->getRekapStatusMateri($s->id_siswa, $arrIdKjm);
            q3loa:
            $status = [];
            foreach ($status_materi as $stat) {
                $status[$stat->jam_ke][$stat->id_mapel][$stat->jenis] = $stat;
            }
            $log[$s->id_siswa] = ["nama" => $s->nama, "nis" => $s->nis, "kelas" => $s->nama_kelas, "status" => $status];
        }
        JauBe:
        $this->output_json(array("test" => [$id_kelas, $tahun . "-" . $bulan . "-" . $tanggal, $arrIdMapel], "log" => $log, "info" => $info, "jadwal" => $jadwal, "materi" => $jadwal_materi, "istirahat" => $istirahat));
    }
}
