<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 defined("\102\x41\x53\105\120\101\x54\110") or exit("\116\x6f\x20\x64\151\x72\145\x63\164\x20\x73\x63\x72\151\160\x74\x20\141\x63\143\x65\163\x73\40\x61\154\x6c\157\167\x65\144"); class Datakelas extends CI_Controller { public function __construct() { goto rHm3O; Ci6y9: $this->load->model("\104\x61\x73\150\142\x6f\141\x72\x64\137\155\x6f\144\145\x6c", "\144\141\x73\x68\142\157\141\x72\x64"); goto G2TM0; Gamuy: $this->form_validation->set_error_delimiters('', ''); goto Z6I3Z; A6Tkj: $this->load->model("\113\x65\154\x61\x73\137\x6d\x6f\144\x65\x6c", "\153\145\154\x61\x73"); goto Ci6y9; RcN54: h0Vzc: goto btpoU; efFHP: RMuwi: goto qMX64; qNQO8: redirect("\x61\165\x74\x68"); goto efFHP; dCjke: show_error("\110\141\156\171\141\x20\101\x64\x6d\151\156\x69\163\x74\x72\x61\164\157\x72\x20\x79\x61\156\x67\40\144\151\x62\x65\x72\x69\40\150\x61\153\40\x75\156\x74\165\153\40\x6d\145\x6e\147\x61\x6b\163\145\163\40\x68\141\154\141\155\x61\x6e\40\x69\156\151\x2c\x20\74\141\x20\150\162\x65\146\75\x22" . base_url("\x64\x61\x73\150\x62\x6f\x61\162\x64") . "\x22\76\x4b\x65\155\142\141\x6c\x69\40\153\145\x20\155\x65\156\x75\40\141\167\x61\x6c\74\x2f\x61\x3e", 403, "\x41\x6b\x73\x65\163\x20\x54\145\162\x6c\141\x72\141\156\147"); goto RcN54; SFsMe: $this->load->model("\122\x61\160\157\162\x5f\155\x6f\x64\145\154", "\x72\141\160\157\x72"); goto Gamuy; NwYnL: ng3tS: goto qNQO8; qMX64: $this->load->library(["\x64\x61\x74\x61\x74\x61\142\x6c\x65\x73", "\146\157\162\x6d\137\166\141\154\151\144\x61\164\151\157\x6e"]); goto A6Tkj; rHm3O: parent::__construct(); goto Ca2Mz; P9sfj: if ($this->ion_auth->is_admin()) { goto h0Vzc; } goto dCjke; RJ6Rz: $this->load->model("\x44\162\157\x70\x64\157\x77\156\137\x6d\157\144\145\x6c", "\x64\162\x6f\160\144\x6f\167\156"); goto SFsMe; G2TM0: $this->load->model("\x4d\x61\x73\164\145\x72\x5f\155\x6f\144\145\x6c", "\155\141\x73\164\x65\162"); goto RJ6Rz; btpoU: goto RMuwi; goto NwYnL; Ca2Mz: if (!$this->ion_auth->logged_in()) { goto ng3tS; } goto P9sfj; Z6I3Z: } public function output_json($data, $encode = true) { goto lL5d4; bhNlW: $data = json_encode($data); goto qetCM; qetCM: ENHd7: goto iVHTX; lL5d4: if (!$encode) { goto ENHd7; } goto bhNlW; iVHTX: $this->output->set_content_type("\x61\x70\x70\154\151\143\141\164\x69\x6f\x6e\x2f\x6a\163\x6f\x6e")->set_output($data); goto pt0tY; pt0tY: } public function index() { goto Owshq; LKQSu: $kelas = $this->kelas->getKelasList($tp->id_tp, $smt->id_smt); goto A36WJ; QZRxW: $this->load->view("\155\x61\163\164\145\x72\57\x6b\x65\x6c\x61\163\57\x64\141\x74\141"); goto zV3Dk; UMt1I: $data["\153\145\154\141\163"] = $kelas; goto Mx0G2; Wa1Cd: $tp = $this->dashboard->getTahunActive(); goto i165Y; V1vK3: $data["\152\165\x72\165\x73\x61\x6e"] = $this->kelas->get_jurusan(); goto FVhix; U9j7G: $kelas = []; goto i1Vrk; i1Vrk: $kelas_lama = []; goto mq5q7; mq5q7: if (!($chek > 0)) { goto OaciC; } goto LKQSu; MV4oi: $data["\163\155\x74\x5f\141\143\164\151\x76\145"] = $smt; goto xXVRl; kBpe4: $data = ["\x75\163\145\162" => $user, "\x6a\165\x64\x75\154" => "\113\x65\154\141\x73", "\x73\x75\x62\152\165\x64\165\x6c" => "\x44\141\164\x61\x20\113\x65\x6c\x61\x73", "\163\x65\164\x74\x69\x6e\x67" => $setting]; goto Wa1Cd; XssQW: $setting = $this->dashboard->getSetting(); goto kBpe4; vn3lx: $data["\x74\x70"] = $this->dashboard->getTahun(); goto P_Nwr; taj5Q: $data["\163\151\x73\167\x61"] = $this->kelas->getAllSiswa($tp->id_tp, $smt->id_smt); goto aU24I; zV3Dk: $this->load->view("\137\x74\x65\x6d\160\x6c\x61\164\x65\x73\x2f\144\141\x73\x68\142\157\x61\162\144\x2f\x5f\x66\157\x6f\x74\145\162"); goto h3IlH; Owshq: $user = $this->ion_auth->user()->row(); goto XssQW; M31x2: $chek = $this->kelas->count_all(); goto U9j7G; i165Y: $smt = $this->dashboard->getSemesterActive(); goto vn3lx; FVhix: $data["\x6c\145\166\145\154"] = $this->kelas->getLevel($setting->jenjang); goto gDf0f; h264X: OaciC: goto UMt1I; EMuwG: $data["\163\155\164"] = $this->dashboard->getSemester(); goto MV4oi; gDf0f: $data["\147\x75\x72\165"] = $this->kelas->get_guru(); goto taj5Q; xXVRl: $data["\x70\162\x6f\146\151\154\x65"] = $this->dashboard->getProfileAdmin($user->id); goto M31x2; A36WJ: $kelas_lama = $this->kelas->getKelasList($tp->id_tp - 1, "\62"); goto h264X; aU24I: $this->load->view("\137\x74\145\x6d\x70\x6c\141\x74\x65\163\57\144\x61\x73\x68\142\x6f\141\162\144\57\x5f\x68\x65\x61\x64\x65\162", $data); goto QZRxW; P_Nwr: $data["\x74\x70\x5f\x61\x63\164\x69\x76\x65"] = $tp; goto EMuwG; Mx0G2: $data["\x6b\145\x6c\x61\x73\137\154\x61\x6d\141"] = $kelas_lama; goto V1vK3; h3IlH: } public function detail($id) { goto oRq_c; w3nC_: $data = ["\x75\163\145\x72" => $user, "\152\165\144\165\x6c" => "\x44\145\164\141\151\154\40\113\x65\x6c\x61\x73", "\x73\x75\x62\x6a\165\x64\165\x6c" => "\104\x65\164\x61\x69\154\40\x4b\145\x6c\x61\163", "\x73\x65\164\164\x69\156\147" => $setting]; goto VHuuU; K3ACX: $data["\147\165\162\165"] = $this->kelas->get_guru(); goto e_580; SoTzT: if ($struktur == null) { goto Rh09s; } goto AWCUn; VkAs1: $this->load->view("\137\164\145\155\x70\x6c\141\164\145\163\x2f\144\141\x73\x68\142\x6f\141\162\x64\x2f\137\x68\145\x61\x64\x65\162", $data); goto Cu_P6; BwhWi: YrD82: goto VkAs1; YU3Y_: $this->load->view("\x5f\x74\x65\x6d\160\154\141\164\x65\163\x2f\144\141\x73\150\x62\x6f\141\x72\144\x2f\137\x66\157\x6f\164\145\162"); goto HC8Oc; BA71M: goto YrD82; goto ULcja; OStPc: $data["\x73\x6d\x74\137\x61\x63\164\151\x76\x65"] = $smt; goto TQlIG; TQlIG: $data["\160\x72\157\x66\151\154\x65"] = $this->dashboard->getProfileAdmin($user->id); goto SPUS0; SPUS0: $data["\153\x65\154\x61\163"] = $this->kelas->get_one($id); goto qLrEP; oRq_c: $user = $this->ion_auth->user()->row(); goto ktuUI; e_580: $data["\x73\x69\x73\167\x61\x73"] = $this->kelas->get_siswa_kelas($id, $tp->id_tp, $smt->id_smt); goto Mqq_X; ULcja: Rh09s: goto ORJlu; acSYv: $data["\164\160\137\141\143\164\x69\166\145"] = $tp; goto oY0f6; VHuuU: $tp = $this->dashboard->getTahunActive(); goto FoxeR; ORJlu: $data["\x73\164\x72\x75\153\x74\x75\x72"] = json_decode(json_encode($this->kelas->dummyStruktur())); goto BwhWi; Cu_P6: $this->load->view("\155\x61\163\164\145\162\57\153\145\x6c\x61\x73\57\144\145\x74\x61\x69\x6c"); goto YU3Y_; Mqq_X: $struktur = $this->kelas->getStrukturKelas($id); goto SoTzT; FoxeR: $smt = $this->dashboard->getSemesterActive(); goto R8jXT; ktuUI: $setting = $this->dashboard->getSetting(); goto w3nC_; oY0f6: $data["\x73\x6d\164"] = $this->dashboard->getSemester(); goto OStPc; AWCUn: $data["\x73\x74\x72\165\x6b\x74\165\162"] = $struktur; goto BA71M; qLrEP: $data["\x6a\165\162\x75\163\141\x6e"] = $this->kelas->get_jurusan(); goto fU1Dn; fU1Dn: $data["\x6c\x65\x76\145\154"] = $this->kelas->getLevel($setting->jenjang); goto K3ACX; R8jXT: $data["\164\x70"] = $this->dashboard->getTahun(); goto acSYv; HC8Oc: } public function add() { goto rnimX; mbHqd: $siswa = $this->kelas->getAllSiswa($tp->id_tp, $smt->id_smt); goto mdjJo; kXDvW: $data["\164\x70\137\x61\143\x74\151\x76\145"] = $tp; goto vqHt6; gj26I: $this->load->view("\155\141\163\164\x65\162\57\x6b\145\x6c\141\163\57\x61\x64\144"); goto BBTSS; PJ1ur: $data = ["\165\163\145\x72" => $user, "\152\x75\x64\x75\154" => "\x4b\x65\154\141\x73", "\163\165\142\x6a\165\144\165\x6c" => "\x54\141\x6d\142\141\x68\40\113\x65\154\141\x73", "\163\x65\164\x74\151\156\x67" => $setting]; goto B5T_1; unpBw: $data["\x67\x75\162\165"] = $this->kelas->get_guru(); goto mbHqd; KNkGS: $data["\x73\x6d\x74\137\x61\x63\x74\151\x76\145"] = $smt; goto dus1m; dus1m: $data["\160\162\157\146\x69\x6c\145"] = $this->dashboard->getProfileAdmin($user->id); goto qy2Rt; aqSFr: $setting = $this->dashboard->getSetting(); goto PJ1ur; mdjJo: $data["\163\151\x73\167\x61"] = $siswa; goto moiSL; BBTSS: $this->load->view("\137\164\x65\155\x70\x6c\x61\164\145\x73\x2f\x64\x61\x73\x68\142\157\x61\162\144\x2f\x5f\x66\157\157\x74\x65\162"); goto GcsA2; d03Yf: $smt = $this->dashboard->getSemesterActive(); goto on9Ox; B5T_1: $tp = $this->dashboard->getTahunActive(); goto d03Yf; vqHt6: $data["\x73\x6d\x74"] = $this->dashboard->getSemester(); goto KNkGS; iFtPY: $data["\x6a\165\162\x75\x73\x61\156"] = $this->kelas->get_jurusan(); goto vF1E8; jYONJ: $this->load->view("\137\164\145\x6d\x70\x6c\141\164\145\x73\57\144\141\163\150\x62\157\141\162\x64\57\x5f\150\145\141\144\x65\162", $data); goto gj26I; moiSL: $data["\163\x69\163\x77\141\x6b\145\x6c\141\163"] = array(); goto jYONJ; qy2Rt: $data["\153\x65\154\141\x73"] = json_decode(json_encode($this->kelas->dummy())); goto iFtPY; rnimX: $user = $this->ion_auth->user()->row(); goto aqSFr; vF1E8: $data["\154\145\166\x65\154"] = $this->kelas->getLevel($setting->jenjang); goto unpBw; on9Ox: $data["\x74\160"] = $this->dashboard->getTahun(); goto kXDvW; GcsA2: } public function edit($id = '') { goto yObH1; eEAjL: $data["\153\x65\154\x61\163"] = $this->kelas->get_one($id); goto xfoh8; DafOU: $data["\163\155\x74\137\x61\143\164\151\x76\145"] = $smt; goto EnBgX; MhvDE: $data = ["\165\x73\x65\162" => $user, "\152\x75\144\x75\x6c" => "\113\x65\x6c\141\163", "\x73\165\x62\x6a\165\144\x75\154" => "\x45\x64\151\x74\40\113\x65\x6c\x61\163", "\x73\145\x74\x74\x69\156\x67" => $setting]; goto RupFt; xfoh8: $data["\152\165\x72\165\x73\x61\156"] = $this->kelas->get_jurusan(); goto DzyqF; sW05y: $data["\164\160\137\141\x63\164\x69\166\x65"] = $tp; goto SSViY; t31fB: $setting = $this->dashboard->getSetting(); goto MhvDE; RupFt: $tp = $this->dashboard->getTahunActive(); goto GtScC; W30KY: $this->load->view("\137\164\x65\x6d\160\x6c\x61\164\145\163\x2f\144\141\163\150\142\x6f\141\x72\144\57\137\146\x6f\x6f\x74\145\162"); goto fTmyw; yObH1: $user = $this->ion_auth->user()->row(); goto t31fB; ZNR9O: $data["\x73\x69\x73\167\x61\x6b\145\154\x61\x73"] = $this->kelas->get_siswa_kelas($id, $tp->id_tp, $smt->id_smt); goto bXNS8; EnBgX: $data["\x70\162\157\x66\151\154\145"] = $this->dashboard->getProfileAdmin($user->id); goto FQ0St; a1IE4: $data["\x67\165\x72\x75"] = $this->kelas->getWaliKelas($tp->id_tp, $smt->id_smt); goto xBL_o; xBL_o: $data["\x73\151\163\x77\x61"] = $this->kelas->getAllSiswa($tp->id_tp, $smt->id_smt); goto ZNR9O; DzyqF: $data["\154\145\166\145\x6c"] = $this->kelas->getLevel($setting->jenjang); goto a1IE4; FQ0St: $data["\151\144\137\x6b\x65\154\x61\163"] = $id; goto eEAjL; PWrtY: $this->load->view("\x6d\141\x73\164\x65\162\x2f\153\x65\x6c\141\x73\57\x61\144\x64"); goto W30KY; GtScC: $smt = $this->dashboard->getSemesterActive(); goto YSS8d; YSS8d: $data["\x74\x70"] = $this->dashboard->getTahun(); goto sW05y; SSViY: $data["\x73\155\164"] = $this->dashboard->getSemester(); goto DafOU; bXNS8: $this->load->view("\137\x74\x65\155\x70\x6c\141\164\145\x73\x2f\x64\x61\x73\150\142\x6f\x61\162\x64\57\x5f\150\x65\141\x64\x65\162", $data); goto PWrtY; fTmyw: } public function save() { goto Htwzd; R1R_a: $new_id_kelas = $id != null && $id != '' ? $id : $id_new; goto g37i6; ELDDD: $guru_id = strip_tags($this->input->post("\147\165\x72\165\137\151\144", TRUE)); goto wF0yB; bx7Gm: i6Qn6: goto ziaf_; OJgH2: $insert[$id_tp . $id_smt . $idsiswa] = ["\x69\x64\x5f\153\x65\154\x61\x73\x5f\163\151\163\167\x61" => $id_tp . $id_smt . $idsiswa, "\x69\x64\137\x74\160" => $id_tp, "\x69\144\137\163\x6d\164" => $id_smt, "\151\144\137\153\145\154\x61\163" => $new_id_kelas, "\x69\144\137\x73\x69\x73\x77\141" => $idsiswa]; goto E9TwW; fblso: Y4vq3: goto c0jJy; kTEyw: $i = 0; goto nb2IC; ExWZY: $config = array(array("\x66\x69\145\154\144" => "\x6e\141\x6d\141\x5f\x6b\145\154\x61\x73", "\154\x61\x62\x65\154" => "\x4e\x61\x6d\x61\x20\113\145\154\141\x73", "\x72\x75\x6c\145\163" => "\x74\162\151\x6d"), array("\x66\x69\x65\x6c\144" => "\153\157\144\x65\x5f\153\x65\x6c\141\x73", "\154\141\142\x65\154" => "\x4b\157\x64\x65\40\113\x65\x6c\x61\163", "\162\x75\154\145\x73" => "\x74\x72\x69\x6d"), array("\x66\151\x65\154\x64" => "\x6a\x75\x72\x75\163\x61\156\137\151\x64", "\x6c\141\x62\x65\x6c" => "\112\x75\x72\165\x73\141\156", "\162\x75\x6c\145\163" => "\164\162\x69\155"), array("\x66\151\x65\x6c\144" => "\154\145\166\145\154\x5f\x69\x64", "\x6c\141\x62\x65\154" => "\x4c\145\x76\x65\154", "\162\x75\x6c\x65\163" => "\x74\162\151\155"), array("\146\x69\145\x6c\x64" => "\147\x75\x72\x75\137\151\x64", "\154\141\x62\145\x6c" => "\x47\165\x72\x75", "\162\165\154\145\x73" => "\164\162\151\x6d"), array("\x66\x69\x65\x6c\144" => "\163\x69\x73\x77\141\x5f\151\144", "\x6c\x61\142\x65\x6c" => "\x53\x69\x73\x77\x61", "\x72\165\x6c\145\163" => "\164\x72\151\x6d")); goto EQtcM; qAQNf: if ($this->form_validation->run() == TRUE) { goto m8N4y; } goto a3VjE; GKObr: $this->form_validation->set_rules($config); goto qAQNf; XUBn9: array_push($siswakelas, ["\151\144" => $id_siswa]); goto PgtER; hhryK: $jumlah = serialize($siswakelas); goto EB1Sd; GKdYV: $insert[$id_tp . $id_smt . $idsiswa]["\151\x64\137\x6b\x65\154\x61\163"] = $new_id_kelas; goto hdwnn; rWHl5: $data["\151\156\x73\x65\x72\x74"] = $insert; goto aOkXv; PAi82: $id_new = $this->db->insert_id(); goto WkByW; cwMHy: $insert = []; goto ihwhy; ucOWE: PKUmI: goto rWHl5; YeAYJ: CGXvE: goto zZEx6; aOkXv: JQlDa: goto bx7Gm; EQtcM: $siswakelas = []; goto kTEyw; ibbfM: goto Pg1iM; goto L5xgE; LcVL2: goto GX1FM; goto C0UjI; nb2IC: GX1FM: goto yU85r; H3I23: rMLFM: goto MqMDD; hSj0J: vYca7: goto EOjxV; E9TwW: goto oV1ja; goto xu3Vx; H36m3: t2W77: goto E38fw; Htwzd: $id = $this->input->post("\x69\x64\137\x6b\x65\x6c\141\x73", true); goto ELDDD; cdoFN: $status = $this->db->update("\155\141\163\164\x65\x72\137\x6b\x65\x6c\141\163", $insert); goto Z0SSY; PDMD9: $this->output_json($data); goto mWlAE; ziaf_: $data["\x73\x69\x73\x77\x61"] = $siswa_inserted; goto NgRwH; Dt6dc: $data["\x73\164\141\x74\165\x73"] = $status; goto PDMD9; zZEx6: foreach ($insert as $ins) { goto VxKNE; VxKNE: if (!$this->db->replace("\x6b\x65\x6c\141\x73\137\163\x69\163\167\x61", $ins)) { goto wnq9l; } goto jYEhh; jYEhh: $siswa_inserted++; goto pdjcd; pdjcd: wnq9l: goto aUP3U; aUP3U: SEH1L: goto PkLwT; PkLwT: } goto ucOWE; EjJ5E: $siswas = $this->input->post("\x73\151\x73\167\x61", true); goto ExWZY; CC7n4: EYWly: goto p7TXH; BsJqs: $updated = false; goto DWhMh; LKCDR: Zxa5N: goto Qd6Oq; pDP7D: $id_new = null; goto VrA4k; ntdUn: if (!($id_siswa != null)) { goto KDc2b; } goto XUBn9; hdwnn: oV1ja: goto t0iE9; aEQxd: $this->db->set("\151\x64\137\x6b\145\x6c\141\x73", $id); goto Le5HG; t0iE9: HIJ4T: goto CC7n4; grzKu: if (!$updated) { goto JQlDa; } goto cwMHy; g37i6: if (!($idsiswa != null)) { goto HIJ4T; } goto F70wu; yU85r: if (!($i <= count($siswas))) { goto gdnBc; } goto YTQhV; aUcfW: $status = $this->db->insert("\155\x61\163\164\145\162\137\153\145\x6c\141\163", $insert); goto PAi82; EB1Sd: $insert = array("\156\141\x6d\141\137\153\145\154\141\x73" => strip_tags($this->input->post("\x6e\141\x6d\x61\x5f\153\x65\154\141\x73", TRUE)), "\x6b\157\144\x65\137\x6b\x65\154\x61\x73" => strip_tags($this->input->post("\153\x6f\x64\145\x5f\x6b\x65\x6c\x61\x73", TRUE)), "\152\x75\x72\165\x73\141\156\137\151\x64" => strip_tags($this->input->post("\152\165\162\165\163\141\x6e\137\x69\144", TRUE)), "\151\x64\x5f\164\x70" => $id_tp, "\x69\144\137\x73\x6d\164" => $id_smt, "\154\145\166\145\x6c\137\151\x64" => strip_tags($this->input->post("\154\145\166\x65\154\x5f\x69\x64", TRUE)), "\x67\x75\162\165\137\x69\x64" => strip_tags($this->input->post("\x67\165\162\x75\137\151\x64", TRUE)), "\x73\151\163\167\x61\x5f\x69\x64" => strip_tags($this->input->post("\163\x69\163\x77\x61\137\x69\144", TRUE)), "\152\x75\155\154\141\150\x5f\x73\x69\x73\x77\141" => $jumlah); goto pDP7D; DWhMh: $siswa_inserted = 0; goto PZV0H; EOjxV: wWWaX: goto H36m3; L5xgE: FKFNe: goto oEhQT; uGOS_: goto zzV9E; goto rEJoY; lbF7K: qQYEW: goto BsJqs; ihwhy: if (!($id != null && $id != '')) { goto t2W77; } goto lIAa3; f4KFR: $updated = $this->db->update("\152\141\x62\x61\x74\x61\x6e\x5f\147\165\x72\x75"); goto grzKu; xu3Vx: jxHPm: goto GKdYV; cZUs3: goto qQYEW; goto H3I23; oEhQT: $this->db->where("\x69\144\x5f\153\145\x6c\141\x73", $id); goto cdoFN; F70wu: if (isset($insert[$id_tp . $id_smt . $idsiswa])) { goto jxHPm; } goto OJgH2; skJH9: $id_smt = $this->master->getSemesterActive()->id_smt; goto EjJ5E; PZV0H: if (!$status) { goto i6Qn6; } goto aEQxd; PgtER: KDc2b: goto LKCDR; Qd6Oq: $i++; goto LcVL2; WkByW: zzV9E: goto cZUs3; NgRwH: $data["\165\160\144\x61\164\x65"] = $updated; goto Dt6dc; wF0yB: $id_tp = $this->master->getTahunActive()->id_tp; goto skJH9; pAnbr: goto Y4vq3; goto YeAYJ; a3VjE: $status = FALSE; goto uGOS_; eUTfz: if (!(count($siswa_kelas) > 0)) { goto wWWaX; } goto ix0NI; p7TXH: $i++; goto pAnbr; c0jJy: if (!($i <= count($siswas))) { goto CGXvE; } goto AYmZm; MqMDD: $this->form_validation->set_rules($config); goto mb2YT; C0UjI: gdnBc: goto hhryK; qP95l: $status = FALSE; goto ibbfM; mb2YT: if ($this->form_validation->run() == TRUE) { goto FKFNe; } goto qP95l; YTQhV: $id_siswa = isset($siswas[$i]) ? $siswas[$i] : null; goto ntdUn; E38fw: $i = 0; goto fblso; ix0NI: foreach ($siswa_kelas as $id_siswa => $sis) { $insert[$id_tp . $id_smt . $id_siswa] = ["\151\x64\x5f\153\x65\154\141\163\137\163\151\163\x77\141" => $id_tp . $id_smt . $id_siswa, "\x69\144\137\164\x70" => $id_tp, "\151\x64\x5f\x73\x6d\164" => $id_smt, "\x69\144\137\x6b\145\154\x61\x73" => 0, "\151\144\x5f\x73\x69\163\x77\x61" => $id_siswa]; dNRGR: } goto hSj0J; lIAa3: $siswa_kelas = $this->kelas->get_status_siswa_kelas($id, $id_tp, $id_smt); goto eUTfz; Le5HG: $this->db->where("\151\144\137\152\x61\x62\x61\x74\x61\156\x5f\x67\x75\162\x75", $guru_id . $id_tp . $id_smt); goto f4KFR; VrA4k: if ($id != null && $id != '') { goto rMLFM; } goto GKObr; rEJoY: m8N4y: goto aUcfW; Z0SSY: Pg1iM: goto lbF7K; AYmZm: $idsiswa = isset($siswas[$i]) ? $siswas[$i] : null; goto R1R_a; mWlAE: } public function update_kelas($id) { goto BQOjj; ikjSl: $i++; goto pAsUZ; eiXsh: uaGdf: goto ikjSl; EpHHF: if (!($id_siswa != null)) { goto vGPZb; } goto ycsjU; t3jC9: $this->db->replace("\x6b\x65\154\141\x73\x5f\163\x69\x73\167\141", $insert); goto Nsfkt; eLibn: Qek7o: goto prb18; H1UGh: $i = 0; goto eLibn; M2IOp: foreach ($siswakelas as $id_siswa => $sis) { goto yUOvO; yUOvO: $insert = ["\x69\x64\x5f\x6b\x65\x6c\x61\163\137\163\x69\163\167\x61" => $id_tp . $id_smt . $id_siswa, "\151\144\137\x74\x70" => $id_tp, "\151\144\137\163\x6d\164" => $id_smt, "\151\144\137\153\145\154\x61\163" => 0, "\151\x64\x5f\x73\x69\x73\x77\x61" => $id_siswa]; goto EYA9Y; ya9tE: UKktR: goto x0KKO; EYA9Y: $this->db->replace("\153\x65\154\x61\x73\x5f\163\151\x73\167\x61", $insert); goto ya9tE; x0KKO: } goto BAiAa; Odsef: NGyQd: goto s0KQs; BQOjj: $id_tp = $this->master->getTahunActive()->id_tp; goto PC2qk; BAiAa: TH1dC: goto Odsef; afFct: $id_siswa = $this->input->post("\163\151\x73\167\x61\x5b" . $i . "\x5d", true); goto EpHHF; ycsjU: $insert = ["\x69\x64\137\x6b\145\154\x61\163\x5f\x73\151\x73\x77\x61" => $id_tp . $id_smt . $id_siswa, "\x69\144\x5f\x74\x70" => $id_tp, "\151\x64\137\x73\155\x74" => $id_smt, "\151\x64\137\153\x65\154\141\163" => $id, "\x69\x64\137\x73\x69\x73\167\141" => $id_siswa]; goto t3jC9; prb18: if (!($i <= $rowsSelect)) { goto bzSiD; } goto afFct; Nsfkt: vGPZb: goto eiXsh; s0KQs: $rowsSelect = count($this->input->post("\163\x69\x73\167\x61", true)); goto H1UGh; CNwLa: $siswakelas = $this->kelas->get_status_siswa_kelas($id, $id_tp, $id_smt); goto x6qKn; PC2qk: $id_smt = $this->master->getSemesterActive()->id_smt; goto CNwLa; x6qKn: if (!(count($siswakelas) > 0)) { goto NGyQd; } goto M2IOp; Q5B0i: bzSiD: goto v5UaR; pAsUZ: goto Qek7o; goto Q5B0i; v5UaR: return $siswakelas; goto n_ozF; n_ozF: } public function manage() { goto AJcH4; JiUCa: $data["\163\x6d\164"] = $this->dashboard->getSemester(); goto eeB8r; eeB8r: $data["\x73\155\164\x5f\x61\143\x74\x69\166\145"] = $smt; goto wBov6; hCGMM: $data["\x74\x70\137\x61\143\164\151\x76\145"] = $tp; goto JiUCa; OIO7d: $data = ["\165\163\x65\162" => $user, "\x6a\165\144\165\154" => "\103\157\x70\171\x20\113\x65\154\x61\163", "\x73\x75\x62\x6a\165\x64\165\x6c" => "\103\x6f\160\171\40\x44\141\164\141\x20\113\145\x6c\x61\x73\40\153\x65\40\123\115\x54\40\x49\x49", "\x73\x65\164\x74\151\156\x67" => $this->dashboard->getSetting()]; goto HUMvm; CGx9e: $data["\x74\x70"] = $this->dashboard->getTahun(); goto hCGMM; HUMvm: $tp = $this->dashboard->getTahunActive(); goto GjCVP; bEqXO: $data["\153\x65\154\141\163\x32"] = $this->dropdown->getAllKelas($tp->id_tp, "\x32"); goto D8uVy; wBov6: $data["\x70\162\x6f\x66\x69\x6c\145"] = $this->dashboard->getProfileAdmin($user->id); goto OCl3r; lThai: $this->load->view("\155\x61\163\164\x65\x72\57\153\x65\154\x61\x73\x2f\x70\145\x72\x73\x65\x6d\145\163\x74\145\162"); goto WZYmx; OCl3r: $data["\x6b\145\154\x61\163"] = $this->dropdown->getAllKelas($tp->id_tp, "\x31"); goto bEqXO; WZYmx: $this->load->view("\137\164\x65\x6d\160\154\141\x74\145\x73\x2f\144\141\x73\150\142\x6f\x61\162\x64\x2f\137\x66\157\157\x74\145\162"); goto bEMOQ; GjCVP: $smt = $this->dashboard->getSemesterActive(); goto CGx9e; D8uVy: $this->load->view("\137\164\145\x6d\x70\x6c\141\164\x65\x73\x2f\x64\141\163\x68\142\x6f\x61\162\x64\x2f\137\150\x65\141\x64\x65\x72", $data); goto lThai; AJcH4: $user = $this->ion_auth->user()->row(); goto OIO7d; bEMOQ: } public function getFromSmt1($kelas) { goto f0RvQ; f0RvQ: $tp = $this->dashboard->getTahunActive(); goto ictDi; ictDi: $data1 = $this->kelas->getKelasSiswa($kelas, $tp->id_tp, "\x31"); goto paawN; UuSbt: C_I4P: goto NdXjN; paawN: $data2 = $this->kelas->getKelasSiswa($kelas, $tp->id_tp, "\62"); goto qiKhc; z50If: $this->output_json(["\163\x6d\164\61" => $data1, "\x73\x6d\164\62" => $ids]); goto xsJvf; JVxv2: if (!(count($data2) > 0)) { goto Sy1qB; } goto I5GV5; qiKhc: $ids = []; goto JVxv2; I5GV5: foreach ($data2 as $s) { $ids[] = $s->id_siswa; B2VyS: } goto UuSbt; NdXjN: Sy1qB: goto z50If; xsJvf: } public function copyFromSmt1() { goto lZpLm; YXm0s: $this->db->insert("\155\141\x73\164\x65\x72\137\153\145\154\141\163", $data); goto ZKR3n; aGpaV: foreach ($arrSiswa as $value) { goto gMsje; tJ2mh: if (!($id_siswa != null)) { goto Vu_gk; } goto CZI_j; Rb2Ui: Xe3zI: goto IgVUK; SfLi2: $res[] = $this->db->replace("\x6b\x65\154\x61\x73\x5f\163\x69\163\167\141", $insert); goto CxnmQ; CxnmQ: Vu_gk: goto Rb2Ui; gMsje: $id_siswa = $value["\x69\x64"]; goto tJ2mh; CZI_j: $insert = ["\151\144\x5f\x6b\x65\x6c\x61\x73\x5f\163\151\163\167\141" => $tp->id_tp . $smt->id_smt . $id_siswa, "\151\144\137\x74\x70" => $tp->id_tp, "\151\144\x5f\163\x6d\x74" => $smt->id_smt, "\151\144\x5f\153\x65\154\x61\x73" => $idk, "\x69\144\x5f\163\x69\x73\x77\x61" => $id_siswa]; goto SfLi2; IgVUK: } goto syt5h; N_2e3: $smt = $this->dashboard->getSemesterActive(); goto aDvGZ; Xo3qW: $this->output_json($res); goto oDPDC; ZKR3n: $idk = $this->db->insert_id(); goto ZGJxT; haAg8: $kelas = $this->kelas->get_one($kelas1, $tp->id_tp, "\61"); goto L3xO8; aDvGZ: $kelas1 = $this->input->post("\x6b\145\154\x61\x73\x5f\154\x61\x6d\141", true); goto PUzme; PUzme: $kelas2 = $this->input->post("\x6b\145\x6c\141\x73\137\142\141\162\x75", true); goto haAg8; lZpLm: $tp = $this->dashboard->getTahunActive(); goto N_2e3; hMoR9: $arrSiswa = unserialize($kelas->jumlah_siswa); goto aGpaV; syt5h: C_jAe: goto Xo3qW; L3xO8: $data = array("\x6e\141\155\141\137\153\145\154\141\163" => $kelas2, "\x6b\157\144\x65\137\153\145\154\141\x73" => $kelas->kode_kelas, "\x6a\165\x72\165\x73\x61\156\137\x69\x64" => $kelas->jurusan_id, "\151\144\137\x74\x70" => $tp->id_tp, "\x69\144\137\163\x6d\164" => $smt->id_smt, "\x6c\x65\x76\x65\x6c\x5f\151\144" => $kelas->level_id, "\147\165\162\165\x5f\151\144" => $kelas->guru_id, "\163\151\x73\167\x61\x5f\x69\x64" => $kelas->siswa_id, "\x6a\x75\155\154\141\150\x5f\x73\151\163\167\x61" => $kelas->jumlah_siswa); goto YXm0s; ZGJxT: $res = []; goto hMoR9; oDPDC: } public function copySiswaFromSmt1() { goto XgcoP; BcUCF: $posts = json_decode($this->input->post("\x6b\x65\x6c\141\x73", true)); goto DdZ1B; kz9Ot: foreach ($idkelases as $ik) { goto Kjfee; tKpGO: ABX5a: goto KgSff; uUkUO: $idk = $this->db->insert_id(); goto unusu; ZlF4a: $kelas = $this->kelas->get_one($ik, $tp->id_tp, "\x31"); goto njAq7; QhASw: $this->db->insert("\155\x61\163\x74\145\x72\x5f\x6b\145\x6c\x61\163", $data); goto uUkUO; Kjfee: if (!($ik != '')) { goto ABX5a; } goto ZlF4a; unusu: foreach ($siswakelas[$ik] as $s) { goto FZofL; S0HWM: b4Zqe: goto RET_b; FZofL: $insert = ["\x69\x64\x5f\x6b\x65\x6c\x61\x73\137\x73\x69\x73\167\141" => $tp->id_tp . $smt->id_smt . $s["\151\144"], "\151\x64\x5f\164\x70" => $tp->id_tp, "\151\x64\x5f\163\155\164" => $smt->id_smt, "\x69\x64\137\153\x65\x6c\141\163" => $idk, "\151\x64\137\163\151\163\x77\141" => $s["\x69\144"]]; goto RIjLt; RIjLt: $res[] = $this->db->replace("\x6b\x65\154\141\163\137\163\x69\163\167\141", $insert); goto S0HWM; RET_b: } goto PMQLM; KgSff: l1b_2: goto zCTme; njAq7: $jumlah = serialize($siswakelas[$ik]); goto Z8o8Y; PMQLM: VEo3F: goto tKpGO; Z8o8Y: $data = array("\x6e\141\155\x61\137\x6b\145\154\x61\163" => $kelas->nama_kelas, "\153\x6f\144\145\137\x6b\145\x6c\x61\x73" => $kelas->kode_kelas, "\x6a\165\162\165\x73\141\x6e\137\x69\144" => $kelas->jurusan_id, "\x69\x64\x5f\164\x70" => $tp->id_tp, "\151\x64\137\163\155\x74" => $smt->id_smt, "\x6c\x65\x76\x65\154\x5f\151\x64" => $kelas->level_id, "\x67\x75\x72\165\137\x69\144" => $kelas->guru_id, "\x73\x69\x73\167\141\137\151\144" => $kelas->siswa_id, "\x6a\x75\x6d\154\x61\x68\x5f\x73\151\x73\167\141" => $jumlah); goto QhASw; zCTme: } goto H_BfX; cp2bV: foreach ($posts as $d) { goto HBYNu; O238n: $siswakelas[$d->id_kelas][] = ["\151\x64" => $d->id_siswa]; goto ki4JQ; ki4JQ: H_Hke: goto eICSt; HBYNu: $idkelases[] = $d->id_kelas; goto O238n; eICSt: } goto FCKFh; FCKFh: BSz1W: goto DHM7A; JyIXA: $this->output_json($res); goto xcrrk; Dgxn1: $siswakelas = []; goto cp2bV; LDYYH: $res = []; goto kz9Ot; nBsh8: $smt = $this->dashboard->getSemesterActive(); goto BcUCF; XgcoP: $tp = $this->dashboard->getTahunActive(); goto nBsh8; DHM7A: $idkelases = array_unique($idkelases); goto LDYYH; H_BfX: lCaKF: goto JyIXA; DdZ1B: $idkelases = []; goto Dgxn1; xcrrk: } public function kenaikan() { goto AxzAq; Axo9k: $lvlKls = $this->kelas->get_one($kelas, $tp->id_tp - 1, "\x32"); goto rSJ4A; pFm0T: if (!($kelas != null)) { goto LsFzD; } goto wtuPb; KXIgO: $smt = $this->dashboard->getSemesterActive(); goto DeYPo; XNkaD: $data["\x70\162\x6f\146\151\x6c\145"] = $this->dashboard->getProfileAdmin($user->id); goto eIKjs; wtuPb: $data["\163\x69\x73\x77\141\x5f\x6b\145\x6c\141\x73\137\x62\141\x72\x75"] = $this->master->getSiswaKelasBaru($tp->id_tp, $smt->id_smt); goto hw3Ch; Cp3MD: $tp = $this->dashboard->getTahunActive(); goto KXIgO; liYy2: $data["\163\155\164\x5f\x61\x63\164\x69\x76\x65"] = $smt; goto XNkaD; jGBjI: $user = $this->ion_auth->user()->row(); goto dvWHx; DeYPo: $data["\x74\x70"] = $this->dashboard->getTahun(); goto Ap1a1; hw3Ch: $data["\x73\151\163\x77\141\163"] = $this->rapor->getKenaikanSiswa($kelas, $tp->id_tp - 1, "\62"); goto LEEbH; ihFXh: $this->load->view("\x5f\164\x65\155\x70\154\141\164\x65\x73\x2f\x64\141\x73\150\x62\x6f\x61\162\144\57\137\146\157\157\164\x65\x72"); goto w4HGT; mFH6f: $data["\x6b\x65\154\141\163\137\x62\x61\x72\165"] = $this->dropdown->getAllKelas($tp->id_tp, "\61"); goto pFm0T; yNSq2: $data = ["\x75\x73\145\x72" => $user, "\x6a\165\144\165\x6c" => "\113\x65\156\x61\x69\153\x6b\x61\x6e\40\x4b\145\154\141\163", "\x73\x75\142\x6a\x75\x64\x75\x6c" => "\x4e\x61\151\153\x20\x4b\x65\x6c\141\x73\40\123\151\163\167\x61", "\x73\x65\164\x74\x69\x6e\147" => $setting]; goto Cp3MD; G_w8z: LsFzD: goto U7Q68; rSJ4A: $data["\153\x65\154\x61\x73\x65\163"] = $this->dropdown->getAllKelas($tp->id_tp - 1, "\62", "\x3d" . ($lvlKls->level_id + 1)); goto G_w8z; dvWHx: $setting = $this->dashboard->getSetting(); goto yNSq2; LEEbH: $data["\153\x65\x6c\141\163\137\x73\x65\154\145\x63\x74\145\144"] = $kelas; goto Axo9k; eIKjs: $level = $setting->jenjang == "\61" ? "\66" : ($setting->jenjang == "\62" ? "\71" : ($setting->jenjang == "\61" ? "\63" : "\x31\62")); goto gZpf3; AxzAq: $kelas = $this->input->get("\153\145\154\x61\x73", true); goto jGBjI; U7Q68: $this->load->view("\x5f\164\145\x6d\160\x6c\x61\164\145\163\x2f\x64\x61\x73\150\142\157\x61\x72\x64\x2f\137\x68\x65\x61\144\145\162", $data); goto fQXN3; Ap1a1: $data["\164\160\137\x61\143\x74\x69\166\x65"] = $tp; goto yKVSp; fQXN3: $this->load->view("\155\x61\163\164\145\x72\57\x6b\145\154\x61\163\57\156\141\x69\x6b\x6b\x65\154\x61\x73"); goto ihFXh; yKVSp: $data["\163\x6d\164"] = $this->dashboard->getSemester(); goto liYy2; gZpf3: $data["\153\145\154\141\163\137\x6c\141\x6d\141"] = $this->dropdown->getAllKelas($tp->id_tp - 1, "\x32", "\x21\75" . $level); goto mFH6f; w4HGT: } public function naikKelas() { goto yw8oD; ZGbRM: $smt = $this->dashboard->getSemesterActive(); goto y2_g1; y2_g1: $posts = json_decode($this->input->post("\153\x65\x6c\x61\163", true)); goto OgQ2U; XTBqY: $idkelases = []; goto Zdq3z; KhzJY: $res = []; goto zlYnw; Xg5oE: yxpTd: goto BEsYW; Zdq3z: $siswakelas = []; goto nwNQg; sBdiW: $this->output_json($data); goto ukciN; OgQ2U: $mode = $this->input->post("\x6d\157\144\145", true); goto XTBqY; Hl4CM: gNoyu: goto n52Pq; VIenf: foreach ($idkelases as $ik) { goto uPDta; pP8kV: foreach ($idks as $idk) { goto OaFke; ZbLb_: ectp7: goto RUxmp; OaFke: foreach ($siswakelas[$ik] as $s) { goto b4Hmv; U4bpU: $res[] = $this->db->replace("\x6b\x65\x6c\141\x73\x5f\x73\x69\x73\x77\x61", $insert); goto Tj_rx; Tj_rx: Mg_WW: goto RiC9D; b4Hmv: $insert = ["\x69\x64\137\153\x65\154\141\163\137\163\151\163\x77\141" => $tp->id_tp . $smt->id_smt . $s["\x69\144"], "\151\144\137\164\x70" => $tp->id_tp, "\151\144\x5f\163\155\x74" => $smt->id_smt, "\151\144\x5f\153\145\154\x61\163" => $idk, "\x69\144\137\x73\x69\x73\167\x61" => $s["\x69\x64"]]; goto U4bpU; RiC9D: } goto m_jrF; m_jrF: OAigM: goto ZbLb_; RUxmp: } goto Vpk4i; xvND1: goto LX_9X; goto ZLYjQ; I09Rs: goto MIY9e; goto CFJu3; ZMEF7: $jumlah = serialize($siswakelas[$ik]); goto Ehqvi; C2fy6: $this->db->where("\x69\x64\137\153\145\154\x61\163", $kelas_baru->id_kelas); goto JOrLx; ha_5G: if ($mode == "\x70\x65\162\163\151\x73\x77\x61") { goto C_Hfb; } goto jePC6; n9cRJ: MIY9e: goto pP8kV; J0kPm: LX_9X: goto iwMAy; uPDta: $kelas = $this->kelas->get_one($ik, $tp->id_tp - 1, "\62"); goto N_QeC; ZLYjQ: C_Hfb: goto QgAXp; Vpk4i: uVD0T: goto phW80; Ehqvi: $data = array("\x6e\141\x6d\x61\x5f\153\145\154\x61\x73" => $kelas->nama_kelas, "\x6b\157\144\x65\x5f\153\x65\154\x61\163" => $kelas->kode_kelas, "\152\165\x72\165\x73\x61\156\137\x69\144" => $kelas->jurusan_id, "\151\x64\x5f\164\160" => $tp->id_tp, "\x69\x64\137\163\x6d\x74" => $smt->id_smt, "\154\x65\x76\x65\154\x5f\151\x64" => $kelas->level_id, "\x67\165\x72\165\x5f\151\144" => $kelas->guru_id, "\x73\x69\163\x77\x61\x5f\x69\144" => $kelas->siswa_id, "\x6a\x75\155\x6c\141\x68\137\163\151\x73\167\141" => $jumlah); goto ZQmfp; l3eM0: array_push($idks, $kelas_baru->id_kelas); goto xvND1; jePC6: $jumlah = serialize($siswakelas[$ik]); goto l3eM0; QgAXp: $jmlLama = unserialize($kelas_baru->jumlah_siswa); goto Gdaar; phW80: d7kit: goto WJ06_; JOrLx: $this->db->update("\155\x61\x73\x74\145\x72\137\153\145\x6c\x61\x73", $data); goto I09Rs; dj6fo: me4c6: goto gzjLX; ZQmfp: $this->db->insert("\x6d\x61\163\164\145\162\x5f\x6b\145\154\x61\163", $data); goto G9YvM; CFJu3: qBNe4: goto ZMEF7; Eg8uh: if ($kelas_baru == null) { goto qBNe4; } goto ha_5G; iwMAy: $data = array("\156\x61\155\x61\x5f\153\x65\x6c\x61\163" => $kelas->nama_kelas, "\x6b\x6f\x64\145\x5f\x6b\145\154\141\x73" => $kelas->kode_kelas, "\152\x75\x72\x75\x73\x61\x6e\137\x69\x64" => $kelas->jurusan_id, "\151\144\x5f\x74\x70" => $tp->id_tp, "\151\144\137\x73\155\x74" => $smt->id_smt, "\154\x65\x76\145\154\137\151\x64" => $kelas->level_id, "\x67\x75\x72\165\x5f\x69\144" => $kelas->guru_id, "\x73\x69\163\x77\141\137\x69\144" => $kelas->siswa_id, "\x6a\x75\x6d\x6c\141\x68\137\x73\x69\x73\167\141" => $jumlah); goto C2fy6; Gdaar: foreach ($siswakelas[$ik] as $s) { goto d5xqy; d5xqy: foreach ($jmlLama as $lama) { goto ilWce; jXYWf: pWq2v: goto pYI66; U7iKD: OXg6O: goto jXYWf; ISmdV: array_push($jmlLama, ["\151\144" => $s["\x69\x64"]]); goto r2Ai7; ilWce: if (!($lama["\151\144"] != $s["\x69\144"])) { goto OXg6O; } goto ISmdV; r2Ai7: array_push($idks, $kelas_baru->id_kelas); goto U7iKD; pYI66: } goto AbMmN; AbMmN: eM0lO: goto XR3aH; XR3aH: oRfH8: goto Z3C6C; Z3C6C: } goto dj6fo; gzjLX: $jumlah = serialize($jmlLama); goto J0kPm; N_QeC: $kelas_baru = $this->kelas->getKelasByNama($kelas->nama_kelas, $tp->id_tp, $smt->id_smt); goto Eg8uh; G9YvM: array_push($idks, $this->db->insert_id()); goto n9cRJ; WJ06_: } goto Hl4CM; n52Pq: $data["\x72\145\x73"] = $siswakelas; goto sBdiW; nwNQg: foreach ($posts as $d) { goto xXKDn; xXKDn: $idkelases[] = $d->kelas_baru; goto WVoca; DzdrH: Z4mWR: goto hVnI4; WVoca: $siswakelas[$d->kelas_baru][] = ["\x69\x64" => $d->id_siswa]; goto DzdrH; hVnI4: } goto Xg5oE; yw8oD: $tp = $this->dashboard->getTahunActive(); goto ZGbRM; BEsYW: $idkelases = array_unique($idkelases); goto KhzJY; zlYnw: $idks = []; goto VIenf; ukciN: } public function hapus($id_kelas) { goto JD_cK; d0SHV: $delete["\153\145\x6c\141\x73"] = $this->master->delete("\x6d\x61\x73\x74\x65\162\137\x6b\x65\154\141\x73", $id_kelas, "\151\x64\x5f\x6b\145\154\x61\x73"); goto OQfKL; JD_cK: $delete["\x73\151\163\x77\x61"] = $this->master->delete("\x6b\x65\154\x61\163\137\x73\x69\163\167\x61", $id_kelas, "\151\x64\x5f\x6b\x65\154\141\163"); goto d0SHV; OQfKL: $this->output_json($delete); goto SnUkD; SnUkD: } }
