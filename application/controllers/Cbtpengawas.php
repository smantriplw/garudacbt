<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtpengawas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect("auth");
            goto lAzCo;
        }
        if ($this->ion_auth->is_admin()) {
            goto rLook;
        }
        show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href=\"" . base_url("dashboard") . "\">Kembali ke menu awal</a>", 403, "Akses Terlarang");
        rLook:
        lAzCo:
        $this->load->library(["datatables", "form_validation"]);
        $this->load->model("Master_model", "master");
        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Cbt_model", "cbt");
        $this->load->model("Dropdown_model", "dropdown");
        $this->form_validation->set_error_delimiters('', '');
    }
    public function output_json($data, $encode = true)
    {
        if (!$encode) {
            goto K0Xzv;
        }
        $data = json_encode($data);
        K0Xzv:
        $this->output->set_content_type("application/json")->set_output($data);
    }
    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $setting = $this->dashboard->getSetting();
        $data = ["user" => $user, "judul" => "Atur Pengawas", "subjudul" => "Pengawas Ujian/Ulangan", "setting" => $setting];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $tp;
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $smt;
        $kelass = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
        $data["kelas"] = $kelass;
        $data["profile"] = $this->dashboard->getProfileAdmin($user->id);
        $data["gurus"] = $this->dropdown->getAllGuru();
        $id_jenis = $this->cbt->getDistinctJenisJadwal($tp->id_tp, $smt->id_smt);
        $ids = [];
        if (!(count($id_jenis) > 0)) {
            goto monCj;
        }
        foreach ($id_jenis as $jenis) {
            array_push($ids, $jenis->id_jenis);
        }
        monCj:
        if (count($ids) > 0) {
            $data["jenis"] = $this->cbt->getAllJenisUjianByArrJenis($ids);
            goto T4cV4;
        }
        $data["jenis"] = ['' => "belum ada jadwal ujian"];
        T4cV4:
        $jenis_selected = $this->input->get("jenis", true);
        $data["jenis_selected"] = $jenis_selected;
        $tglJadwals = [];
        if (!($jenis_selected != null)) {
            goto bRIEv;
        }
        $tglJadwals = $this->cbt->getAllJadwalByJenis($jenis_selected, $tp->id_tp, $smt->id_smt);
        foreach ($tglJadwals as $tgl => $jadwalss) {
            foreach ($jadwalss as $mpl => $jadwals) {
                foreach ($jadwals as $jadwal) {
                    $jadwal->bank_kelas = unserialize($jadwal->bank_kelas);
                    foreach ($jadwal->bank_kelas as $kb) {
                        if (!($kb["kelas_id"] != '')) {
                            goto wcEfT;
                        }
                        $klss = $this->cbt->getKelasUjian($kb["kelas_id"]);
                        $jadwal->peserta[] = $klss;
                        wcEfT:
                    }
                }
            }
        }
        bRIEv:
        $data["tgl_jadwals"] = $tglJadwals;
        $data["ruang"] = $this->dropdown->getAllRuang();
        $data["sesi"] = $this->dropdown->getAllSesi();
        $data["ruang_sesi"] = $this->cbt->getRuangSesi($tp->id_tp, $smt->id_smt);
        $data["ruangs"] = $this->cbt->getDistinctRuang($tp->id_tp, $smt->id_smt, []);
        $data["pengawas"] = $this->cbt->getAllPengawas($tp->id_tp, $smt->id_smt);
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("cbt/pengawas/data");
        $this->load->view("_templates/dashboard/_footer");
    }
    public function savePengawas()
    {
        $input = json_decode($this->input->post("data", true));
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $id_tp = $tp->id_tp;
        $id_smt = $smt->id_smt;
        $updated = 0;
        foreach ($input as $d) {
            $ruang = $d->ruang;
            $sesi = $d->sesi;
            $jadwal = $d->jadwal;
            $id_pengawas = $id_tp . $id_smt . $jadwal . $ruang . $sesi;
            $dataInsert = ["id_pengawas" => $id_pengawas, "id_jadwal" => $jadwal, "id_tp" => $id_tp, "id_smt" => $id_smt, "id_ruang" => $ruang, "id_sesi" => $sesi, "id_guru" => implode(",", $d->guru)];
            $update = $this->db->replace("cbt_pengawas", $dataInsert);
            if (!$update) {
                goto tB_n_;
            }
            $updated++;
            tB_n_:
        }
        $data["error"] = "--";
        $data["status"] = $updated;
        $this->output_json($data);
    }
}
