<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 class Kelasabsensibulanan extends CI_Controller { public function __construct() { goto nGayc; Q9zXj: $this->load->model("\115\x61\163\164\x65\x72\x5f\x6d\157\x64\x65\154", "\155\141\163\x74\x65\162"); goto B25Mt; B25Mt: $this->load->model("\104\x61\x73\x68\142\157\x61\162\144\x5f\155\x6f\144\x65\154", "\x64\x61\x73\x68\142\157\141\x72\x64"); goto Tr3OJ; JNR1O: l5bSR: goto WCCH3; nGayc: parent::__construct(); goto f7GGf; b8JKs: redirect("\141\x75\x74\150"); goto JcP7b; f7GGf: if (!$this->ion_auth->logged_in()) { goto pNJrZ; } goto E2gqU; EVzWN: show_error("\x48\x61\156\171\x61\x20\101\144\155\x69\156\151\163\x74\x72\141\x74\157\162\40\x79\x61\156\x67\x20\x64\x69\x62\145\x72\x69\40\x68\x61\x6b\x20\165\156\164\x75\x6b\x20\155\x65\156\x67\x61\153\x73\x65\163\x20\x68\141\x6c\141\155\x61\x6e\x20\x69\x6e\151\x2c\x20\x3c\x61\40\x68\x72\x65\146\x3d\42" . base_url("\144\141\x73\x68\142\157\141\x72\144") . "\x22\x3e\x4b\145\x6d\142\x61\x6c\x69\x20\x6b\x65\x20\x6d\145\156\x75\x20\x61\167\x61\154\x3c\57\x61\76", 403, "\101\153\x73\145\163\x20\x44\x69\x62\x61\164\141\163\x69"); goto JNR1O; Tr3OJ: $this->load->model("\x44\x72\157\x70\x64\x6f\167\156\137\155\157\144\145\154", "\144\x72\157\x70\144\x6f\x77\x6e"); goto XRqAn; XRqAn: $this->load->model("\x4b\x65\154\x61\163\x5f\155\157\144\145\x6c", "\153\145\154\141\163"); goto YMCla; YMCla: $this->form_validation->set_error_delimiters('', ''); goto IFNGV; DFnNI: pNJrZ: goto b8JKs; tiVAe: $this->load->library(["\144\x61\164\x61\164\141\142\154\x65\163", "\x66\x6f\x72\x6d\137\166\141\x6c\151\x64\x61\164\x69\x6f\156"]); goto Q9zXj; WCCH3: goto qwUPl; goto DFnNI; JcP7b: qwUPl: goto tiVAe; E2gqU: if (!(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("\147\x75\x72\x75"))) { goto l5bSR; } goto EVzWN; IFNGV: } public function output_json($data, $encode = true) { goto x1aRa; ouC_k: pZKfM: goto Kq075; x1aRa: if (!$encode) { goto pZKfM; } goto Nmzu4; Kq075: $this->output->set_content_type("\141\160\x70\x6c\151\x63\x61\x74\x69\157\x6e\57\152\x73\157\156")->set_output($data); goto xuYXl; Nmzu4: $data = json_encode($data); goto ouC_k; xuYXl: } public function index() { goto b7Xuh; p9uaR: $arrMapel = []; goto AuF2Q; Fb4E5: W6Z6b: goto iaIsc; YYZ1a: $data = ["\165\163\x65\x72" => $user, "\x6a\x75\144\x75\x6c" => "\104\x61\146\x74\141\162\40\110\x61\x64\151\162\x20\x42\x75\x6c\x61\156\x61\156", "\x73\x75\142\152\x75\144\x75\154" => "\x44\x61\x66\x74\141\x72\40\x48\x61\x64\x69\x72\40\x42\x75\x6c\x61\156\141\x6e\40\x53\151\x73\167\141", "\x73\145\x74\164\x69\156\147" => $this->dashboard->getSetting()]; goto bZd19; BrRtV: $this->load->view("\155\145\x6d\x62\x65\x72\x73\x2f\147\x75\x72\x75\x2f\x74\x65\x6d\160\154\141\x74\x65\163\57\x66\157\x6f\x74\x65\x72"); goto VFsdr; hPLRg: $mapel_guru = $this->kelas->getGuruMapelKelas($guru->id_guru, $tp->id_tp, $smt->id_smt); goto bq7_Y; FATsz: $data["\x62\x75\154\x61\156"] = $this->dropdown->getBulan(); goto vfDv_; GWE9m: $data["\147\165\162\x75"] = $guru; goto wScS0; zELIb: $this->load->view("\153\x65\x6c\141\x73\x2f\141\142\163\x65\156\x62\165\154\141\x6e\141\x6e\x2f\x64\141\164\x61"); goto xo04N; o3s6T: $data["\x6d\x61\x70\x65\x6c"] = $arrMapel; goto fXvmP; bZd19: $tp = $this->master->getTahunActive(); goto PuXSb; AuF2Q: $arrKelas = []; goto EVkum; hGilY: O3tRD: goto piUM6; VFsdr: goto JzJLs; goto xJIWG; piUM6: dtbAx: goto o3s6T; UFjBK: if (!($mapel != null)) { goto dtbAx; } goto nmnJ9; iaIsc: $arrId = []; goto UFjBK; m08aW: $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt); goto gmMdr; zaj4h: foreach ($mapel as $m) { goto V4X1e; U6aSz: me2OX: goto yiWHT; XjPir: fkO3v: goto U6aSz; uhmh_: foreach ($m->kelas_mapel as $kls) { $arrKelas[$m->id_mapel][] = ["\151\144\x5f\x6b\145\x6c\141\163" => $kls->kelas, "\x6e\141\155\141\x5f\153\145\154\x61\x73" => $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $kls->kelas)]; CXy26: } goto XjPir; V4X1e: $arrMapel[$m->id_mapel] = $m->nama_mapel; goto uhmh_; yiWHT: } goto ccemb; ZcTXs: JzJLs: goto scrqb; ms_Tf: $data["\x74\x70"] = $this->dashboard->getTahun(); goto qJR1k; wScS0: $data["\x69\x64\x5f\x67\165\x72\165"] = $guru->id_guru; goto hPLRg; AJIdT: $this->load->view("\x6b\145\154\x61\x73\57\141\142\163\145\x6e\x62\165\154\141\156\x61\156\x2f\144\x61\x74\141"); goto BrRtV; DJS6W: $data["\153\145\x6c\x61\x73"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt); goto uoAhP; vfDv_: if ($this->ion_auth->is_admin()) { goto qqxgq; } goto m08aW; nmnJ9: foreach ($mapel[0]->kelas_mapel as $id_mapel) { array_push($arrId, $id_mapel->kelas); FXimO: } goto hGilY; PuXSb: $smt = $this->master->getSemesterActive(); goto ms_Tf; xJIWG: qqxgq: goto alpgi; xo04N: $this->load->view("\x5f\164\x65\x6d\160\x6c\x61\164\x65\163\x2f\144\141\x73\x68\x62\x6f\141\x72\x64\x2f\137\x66\x6f\x6f\164\x65\x72"); goto ZcTXs; q_Asu: $data["\x6b\x65\154\141\163"] = count($arrId) > 0 ? $this->dropdown->getAllKelasByArrayId($tp->id_tp, $smt->id_smt, $arrId) : []; goto WnXSo; b7Xuh: $user = $this->ion_auth->user()->row(); goto YYZ1a; heY0A: $data["\155\141\160\x65\154"] = $this->dropdown->getAllMapel(); goto qtsdO; bq7_Y: $mapel = json_decode(json_encode(unserialize($mapel_guru->mapel_kelas))); goto p9uaR; Lte6W: $data["\x73\155\164\137\x61\143\x74\151\166\x65"] = $smt; goto FATsz; gmMdr: $nguru[$guru->id_guru] = $guru->nama_guru; goto GWE9m; qJR1k: $data["\x74\x70\137\141\143\164\151\x76\145"] = $tp; goto wBifC; ccemb: BtKtI: goto Fb4E5; wBifC: $data["\163\155\x74"] = $this->dashboard->getSemester(); goto Lte6W; WnXSo: $this->load->view("\155\x65\155\x62\145\x72\x73\x2f\147\165\162\x75\x2f\164\145\155\160\154\x61\164\145\163\57\x68\x65\141\144\x65\x72", $data); goto AJIdT; fXvmP: $data["\141\162\162\x6b\x65\x6c\x61\163"] = $arrKelas; goto q_Asu; qtsdO: $this->load->view("\x5f\x74\x65\x6d\160\x6c\x61\164\x65\163\x2f\144\x61\x73\x68\142\157\x61\x72\144\57\x5f\150\x65\x61\144\145\x72", $data); goto zELIb; EVkum: if (!($mapel != null)) { goto W6Z6b; } goto zaj4h; alpgi: $data["\160\162\x6f\x66\151\154\145"] = $this->dashboard->getProfileAdmin($user->id); goto DJS6W; uoAhP: $data["\147\165\162\165"] = $this->dropdown->getAllGuru(); goto heY0A; scrqb: } public function loadAbsensiMapel() { goto M0ynk; opNgu: goto pHONT; goto KVd_1; ALJTH: if ($jadwal != null) { goto O72P8; } goto K2c1N; kwhgd: $id_tp = $this->master->getTahunActive()->id_tp; goto oj87O; UDtwc: XGlTk: goto GrhjB; XMbv8: $jadwal_materi[$t] = (array) $this->kelas->getAllMateriByTgl($id_kelas, $tahun . "\55" . $b . "\55" . $t, [$id_mapel]); goto HQDmc; FZ5XW: $materi_perbulan = $this->kelas->getRekapBulananSiswa($id_mapel, $id_kelas, $tahun, $bulan); goto AY5Hj; dTqvT: $jadwal = $this->dashboard->getJadwalKbm($id_tp, $id_smt, $id_kelas); goto ALJTH; V2eyX: pHONT: goto IM5uP; HQDmc: x7tbN: goto eDtxr; IvvYl: $tgl = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); goto A3EW3; a7km6: goto JXn26; goto HZUXf; K2c1N: $this->output_json(["\x6a\x61\144\167\141\154" => $jadwal]); goto opNgu; UmdvY: $jadwal->istirahat = unserialize($jadwal->istirahat); goto IvvYl; xNKjX: $infos = $this->kelas->getJadwalMapelByMapel($id_kelas, $id_mapel, $id_tp, $id_smt); goto IGwQA; XBHB_: $t = $i + 1 < 10 ? "\60" . ($i + 1) : $i + 1; goto pOWCS; IGwQA: foreach ($infos as $info) { goto c1Oa4; LZdAF: g_9CE: goto yc6wU; UkrXa: zhDmF: goto LZdAF; c1Oa4: $dates = $this->total_hari($info->id_hari, $bulan, $tahun); goto v4cTs; v4cTs: foreach ($dates as $date) { goto sX_r9; FGF1Q: pibpa: goto u20ct; sX_r9: $d = explode("\55", $date); goto E7ypF; E7ypF: $mapel_bulan_ini[$d[2]][$info->jam_ke] = $date; goto FGF1Q; u20ct: } goto UkrXa; yc6wU: } goto Q2NMM; A3EW3: $jadwal_materi = []; goto kzFPN; lTtsh: $siswa = $this->kelas->getKelasSiswa($id_kelas, $id_tp, $id_smt); goto AdP1E; Odzbt: $tahun = $this->input->post("\x74\x68\156", true); goto xYGCm; LCLUG: JXn26: goto NgLZP; oj87O: $id_smt = $this->master->getSemesterActive()->id_smt; goto dTqvT; M0ynk: $id_kelas = $this->input->post("\153\x65\154\141\163", true); goto N2S2R; eDtxr: $i++; goto a7km6; Q2NMM: MQKoc: goto paomu; AY5Hj: $log = []; goto lTtsh; N2S2R: $id_mapel = $this->input->post("\x6d\141\160\145\154", true); goto Odzbt; kzFPN: $i = 0; goto LCLUG; NgLZP: if (!($i < $tgl)) { goto tTrrq; } goto XBHB_; HZUXf: tTrrq: goto FZ5XW; GrhjB: $mapel_bulan_ini = []; goto xNKjX; pOWCS: $b = $bulan < 10 ? "\60" . $bulan : $bulan; goto XMbv8; AdP1E: foreach ($siswa as $s) { goto gZ1Wm; tyACh: WtIxB: goto DBckR; bwzMS: bLphU: goto kn1JF; uPY63: goto bLphU; goto tyACh; Y6zgA: $i = 0; goto bwzMS; kn1JF: if (!($i < $tgl)) { goto WtIxB; } goto lnVTi; iOTsN: $arrMateri[1][] = $materi_perbulan != null && isset($materi_perbulan[$s->id_siswa]) && isset($materi_perbulan[$s->id_siswa][1]) && isset($materi_perbulan[$s->id_siswa][1][$tahun . "\55" . $b . "\55" . $t]) ? $materi_perbulan[$s->id_siswa][1][$tahun . "\55" . $b . "\x2d" . $t] : null; goto iy7KR; pIeRU: jg1Kk: goto jJ1tI; xMwcR: $b = $bulan < 10 ? "\60" . $bulan : $bulan; goto iOTsN; P9CmN: $i++; goto uPY63; U8xqr: a662R: goto P9CmN; iy7KR: $arrMateri[2][] = $materi_perbulan != null && isset($materi_perbulan[$s->id_siswa]) && isset($materi_perbulan[$s->id_siswa][2]) && isset($materi_perbulan[$s->id_siswa][2][$tahun . "\55" . $b . "\55" . $t]) ? $materi_perbulan[$s->id_siswa][2][$tahun . "\55" . $b . "\55" . $t] : null; goto U8xqr; DBckR: $log[$s->id_siswa] = ["\156\x61\155\141" => $s->nama, "\156\x69\x73" => $s->nis, "\x6b\145\x6c\x61\163" => $s->nama_kelas, "\x6d\x61\164\145\x72\151" => $arrMateri[1], "\x74\165\x67\141\x73" => $arrMateri[2]]; goto pIeRU; lnVTi: $t = $i + 1 < 10 ? "\60" . ($i + 1) : $i + 1; goto xMwcR; gZ1Wm: $arrMateri = []; goto Y6zgA; jJ1tI: } goto UDtwc; xYGCm: $bulan = $this->input->post("\x62\154\156", true); goto kwhgd; KVd_1: O72P8: goto UmdvY; paomu: $this->output_json(["\x6c\157\x67" => $log, "\152\141\144\x77\141\x6c" => $jadwal, "\x6d\141\x74\145\x72\151" => $jadwal_materi, "\155\141\160\145\x6c\163" => $mapel_bulan_ini]); goto V2eyX; IM5uP: } function total_hari($id_day, $bulan, $taun) { goto wYUXN; Tmhcl: if (!(date("\x4e", strtotime($taun . "\x2d" . $bulan . "\55" . $i)) == $idday)) { goto sLziV; } goto nj0eZ; o_mdi: if (!($i < $total_days)) { goto wvXEl; } goto Tmhcl; PyHx4: Uxrsn: goto o_mdi; hlU0y: $i++; goto SXJzY; SXJzY: goto Uxrsn; goto HqJan; X0eUI: sLziV: goto pSVgu; snzqg: $total_days = cal_days_in_month(CAL_GREGORIAN, $bulan, $taun); goto bLVOa; iZl0d: $i = 1; goto PyHx4; HqJan: wvXEl: goto nUucs; Tw7CI: $dates = []; goto snzqg; nj0eZ: $days++; goto nh6ZG; nUucs: return $dates; goto Iwpwa; pSVgu: yeKwb: goto hlU0y; bLVOa: $idday = $id_day == "\x37" ? 0 : $id_day; goto iZl0d; nh6ZG: array_push($dates, date("\131\55\x6d\55\x64", strtotime($taun . "\x2d" . $bulan . "\55" . $i))); goto X0eUI; wYUXN: $days = 0; goto Tw7CI; Iwpwa: } }
