<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 class Cbtcetak extends CI_Controller { public function __construct() { goto vSIDk; QhiAz: show_error("\110\141\156\x79\x61\40\101\144\x6d\151\x6e\151\x73\164\x72\141\164\157\x72\x20\171\141\x6e\x67\x20\144\151\x62\145\x72\x69\x20\x68\x61\x6b\x20\x75\156\x74\x75\x6b\40\x6d\145\x6e\x67\x61\x6b\x73\145\163\40\150\x61\x6c\141\x6d\141\156\40\x69\156\x69\x2c\x20\74\x61\40\150\162\x65\146\x3d\42" . base_url("\x64\141\163\150\142\x6f\141\162\144") . "\42\x3e\113\145\x6d\142\141\x6c\151\x20\153\x65\40\x6d\x65\156\x75\x20\x61\167\141\x6c\74\57\x61\76", 403, "\x41\153\x73\145\163\x20\x54\145\162\x6c\141\x72\141\156\147"); goto R3Oyn; TUwk7: $this->form_validation->set_error_delimiters('', ''); goto Ux46x; aRSrZ: $this->load->model("\x44\x72\x6f\160\x64\157\x77\x6e\x5f\x6d\157\144\145\154", "\x64\x72\x6f\x70\x64\x6f\167\x6e"); goto TUwk7; HiPgB: $this->load->model("\115\141\x73\x74\x65\x72\x5f\x6d\x6f\x64\145\x6c", "\x6d\141\x73\x74\x65\x72"); goto yAODq; Vn8rL: redirect("\141\x75\164\x68"); goto fAt3t; A9nlJ: $this->load->model("\104\x61\x73\x68\x62\x6f\x61\x72\x64\137\155\157\144\x65\x6c", "\x64\141\x73\x68\142\x6f\x61\162\144"); goto FtJVE; WsHIt: if (!$this->ion_auth->logged_in()) { goto FoAFS; } goto C0qEv; QJ19R: $this->load->library("\165\x70\154\157\141\144"); goto HiPgB; FtJVE: $this->load->model("\x43\x62\164\137\x6d\157\x64\145\x6c", "\143\x62\164"); goto aRSrZ; fAt3t: iAADf: goto b25xH; yAODq: $this->load->model("\113\x65\154\141\x73\137\155\x6f\x64\145\154", "\x6b\x65\154\x61\x73"); goto A9nlJ; vSIDk: parent::__construct(); goto WsHIt; b25xH: $this->load->library(["\x64\x61\164\141\164\x61\142\x6c\145\x73", "\x66\157\162\155\x5f\166\141\154\x69\144\x61\164\x69\x6f\x6e"]); goto QJ19R; kwcfs: goto iAADf; goto LK9ln; C0qEv: if ($this->ion_auth->is_admin()) { goto Q5vk5; } goto QhiAz; R3Oyn: Q5vk5: goto kwcfs; LK9ln: FoAFS: goto Vn8rL; Ux46x: } public function output_json($data, $encode = true) { goto PScYY; PScYY: if (!$encode) { goto PGllT; } goto kjm8E; kjm8E: $data = json_encode($data); goto UAVoN; UAVoN: PGllT: goto cdAo3; cdAo3: $this->output->set_content_type("\141\x70\160\x6c\151\x63\x61\164\x69\157\x6e\57\x6a\163\157\x6e")->set_output($data); goto Wille; Wille: } public function index() { goto GBgk5; crxMW: $data["\x73\155\164\137\141\143\164\x69\166\x65"] = $this->dashboard->getSemesterActive(); goto ZSCnq; AvG0u: $data = ["\165\x73\145\x72" => $user, "\152\x75\144\x75\x6c" => "\x43\x65\164\x61\x6b\40\104\141\164\x61\x20\x50\x65\x6e\151\x6c\141\151\141\156", "\163\x75\x62\x6a\x75\144\x75\x6c" => "\103\x65\164\141\153", "\160\x72\x6f\x66\151\x6c\145" => $this->dashboard->getProfileAdmin($user->id), "\163\x65\164\x74\151\x6e\x67" => $this->dashboard->getSetting()]; goto xIIuo; NMz2p: $this->load->view("\x5f\164\x65\155\x70\x6c\x61\164\145\163\x2f\144\141\163\150\x62\157\x61\162\144\57\137\x66\157\157\x74\145\x72"); goto UDm5y; GBgk5: $user = $this->ion_auth->user()->row(); goto AvG0u; O6quI: $this->load->view("\x63\x62\164\x2f\143\145\164\x61\153\57\144\141\164\141"); goto NMz2p; ZSCnq: $data["\153\x6f\x70"] = $this->cbt->getSettingKopAbsensi(); goto bICmD; U0NV4: $data["\164\x70\x5f\141\x63\164\151\166\x65"] = $this->dashboard->getTahunActive(); goto Fc1gx; xIIuo: $data["\164\x70"] = $this->dashboard->getTahun(); goto U0NV4; Fc1gx: $data["\x73\155\164"] = $this->dashboard->getSemester(); goto crxMW; bICmD: $this->load->view("\x5f\x74\145\155\160\x6c\x61\x74\x65\163\57\144\x61\163\150\142\x6f\141\162\x64\57\x5f\150\x65\x61\x64\x65\x72", $data); goto O6quI; UDm5y: } public function data() { $this->output_json($this->cbt->getJenis(), false); } public function kartuPeserta() { goto rpgu6; rku9J: $data["\x6b\141\162\x74\165"] = $this->cbt->getSettingKartu(); goto qAj0B; fPbQj: $tp = $this->dashboard->getTahunActive(); goto LkV75; LkV75: $smt = $this->dashboard->getSemesterActive(); goto K3OK5; j0WV6: $data["\163\x6d\x74"] = $this->dashboard->getSemester(); goto E_BXR; yeybl: $this->load->view("\x5f\164\145\x6d\x70\x6c\141\164\145\x73\57\x64\x61\163\150\142\x6f\141\x72\x64\x2f\137\146\157\x6f\164\x65\x72"); goto hS0LA; lmJg7: $this->load->view("\137\164\145\155\x70\x6c\141\164\x65\163\x2f\144\141\163\x68\x62\x6f\141\162\x64\57\x5f\150\145\141\x64\145\x72", $data); goto ZZkWx; a1Zeu: $data["\x74\x70\x5f\x61\143\x74\151\166\x65"] = $tp; goto j0WV6; qAj0B: $data["\x6b\145\x6c\141\163"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt); goto Sa5Sg; GSkQB: $data = ["\165\163\145\162" => $user, "\x6a\165\x64\165\x6c" => "\x43\145\x74\x61\x6b\x20\x4b\x61\162\164\x75\40\120\x65\x73\145\162\x74\x61", "\163\x75\x62\x6a\x75\144\x75\154" => "\103\x65\164\x61\153", "\x70\x72\x6f\146\151\x6c\145" => $this->dashboard->getProfileAdmin($user->id), "\x73\145\164\164\151\x6e\x67" => $this->dashboard->getSetting()]; goto fPbQj; E_BXR: $data["\163\x6d\164\137\141\x63\x74\x69\166\x65"] = $smt; goto rku9J; ZZkWx: $this->load->view("\143\x62\x74\57\x63\145\164\141\x6b\x2f\153\141\x72\x74\165"); goto yeybl; rpgu6: $user = $this->ion_auth->user()->row(); goto GSkQB; K3OK5: $data["\x74\x70"] = $this->dashboard->getTahun(); goto a1Zeu; Sa5Sg: $data["\x72\x75\141\156\147"] = $this->dropdown->getAllRuang(); goto lmJg7; hS0LA: } function uploadFile($logo) { goto mOb9f; iFAVt: $data["\163\x72\143"] = $this->upload->display_errors(); goto ePupe; agC1q: goto h43o_; goto VqBpW; kBi6s: $data["\163\x74\x61\164\165\x73"] = false; goto iFAVt; ASwnZ: $data["\163\x74\x61\164\x75\x73"] = true; goto agC1q; ZourT: VKNR6: goto iGNHB; jA0mH: if (!$this->upload->do_upload("\154\x6f\x67\157")) { goto Yeth0; } goto BBr0p; b1jAV: $data["\164\x79\160\x65"] = $_FILES["\x6c\157\147\x6f"]["\164\x79\x70\x65"]; goto J2B4X; VqBpW: Yeth0: goto kBi6s; J2B4X: $data["\x73\151\x7a\145"] = $_FILES["\x6c\157\147\157"]["\x73\151\x7a\145"]; goto ZourT; qdJdP: $data["\146\x69\154\x65\x6e\x61\155\x65"] = pathinfo($result["\x66\151\x6c\145\x5f\156\141\155\145"], PATHINFO_FILENAME); goto ASwnZ; pPkcW: goto VKNR6; goto Y3hmG; wVFFn: $data["\x73\x72\143"] = ''; goto pPkcW; TEZcJ: $config["\146\x69\x6c\x65\x5f\x6e\141\155\145"] = $logo; goto LyFOs; BBr0p: $result = $this->upload->data(); goto WPcch; k5riX: $config["\x61\154\x6c\157\167\145\x64\137\x74\x79\x70\x65\x73"] = "\147\151\146\x7c\152\x70\147\x7c\x70\156\x67\x7c\x6a\160\x65\147\x7c\x4a\x50\x45\107\174\112\120\107\x7c\120\x4e\x47\x7c\107\x49\x46"; goto ZB66v; ZB66v: $config["\x6f\166\145\162\167\x72\x69\164\145"] = true; goto TEZcJ; iGNHB: $this->output_json($data); goto wriEz; LyFOs: $this->upload->initialize($config); goto jA0mH; WPcch: $data["\x73\162\x63"] = base_url() . "\165\x70\x6c\x6f\141\x64\x73\x2f\x73\x65\164\164\151\156\x67\x73\57" . $result["\146\x69\154\x65\137\x6e\x61\155\145"]; goto qdJdP; dMZx3: $config["\165\160\154\x6f\x61\x64\x5f\x70\x61\164\150"] = "\56\x2f\x75\x70\154\x6f\141\144\x73\x2f\x73\145\x74\164\x69\156\147\163\x2f"; goto k5riX; mOb9f: if (isset($_FILES["\x6c\x6f\x67\x6f"]["\x6e\x61\x6d\x65"])) { goto oeZmv; } goto wVFFn; ePupe: h43o_: goto b1jAV; Y3hmG: oeZmv: goto dMZx3; wriEz: } function deleteFile() { goto Eynn2; Eynn2: $src = $this->input->post("\163\x72\x63"); goto scCFf; scCFf: $file_name = str_replace(base_url(), '', $src); goto AL12D; AL12D: if (!unlink($file_name)) { goto NJfDw; } goto S7SNW; uk5Nh: NJfDw: goto ecn42; S7SNW: echo "\x46\151\x6c\x65\x20\x44\x65\154\x65\x74\x65\40\123\x75\x63\x63\145\x73\x73\x66\165\154\x6c\x79"; goto uk5Nh; ecn42: } public function saveKartu() { goto GfoG6; Wjwiv: $tanggal = $this->input->post("\x74\141\x6e\147\x67\141\154", true); goto DipGX; cNZQD: $header_4 = $this->input->post("\x68\145\x61\144\145\162\137\64", true); goto Wjwiv; iD42D: $update = $this->db->replace("\143\142\164\x5f\153\157\160\137\x6b\141\x72\164\x75", $insert); goto eY3BF; cCw4H: $header_2 = $this->input->post("\150\145\x61\144\145\162\x5f\x32", true); goto QxKww; QxKww: $header_3 = $this->input->post("\150\x65\141\144\145\x72\137\63", true); goto cNZQD; eY3BF: $this->output_json($update); goto ixjdi; DipGX: $insert = ["\151\x64\x5f\163\145\x74\137\153\141\x72\x74\165" => 123456, "\150\145\x61\x64\x65\x72\137\x31" => $header_1, "\x68\x65\141\144\x65\x72\137\62" => $header_2, "\150\x65\141\x64\x65\162\137\x33" => $header_3, "\x68\x65\141\144\145\162\137\64" => $header_4, "\164\141\156\x67\147\x61\x6c" => $tanggal]; goto iD42D; GfoG6: $header_1 = $this->input->post("\150\x65\x61\144\x65\162\x5f\x31", true); goto cCw4H; ixjdi: } public function getSiswaKelas() { goto EgWSg; mOA2w: $smt = $this->dashboard->getSemesterActive(); goto Ica4Q; SxBrz: $data["\151\x6e\x66\157"] = ["\153\145\x6c\141\163" => $ikelas, "\163\145\x73\151" => $isesi, "\152\x61\x64\x77\x61\x6c" => $ijadwal, "\x70\x65\156\147\x61\167\x61\x73" => $pengawas]; goto Sxm1J; IR8qR: $data["\x73\x69\163\x77\141"] = []; goto YDKe4; f3Lfy: Of3Fj: goto Qrtx_; CSYJO: $smt = $this->dashboard->getSemesterActive(); goto QIcYQ; ZlY35: $tp = $this->dashboard->getTahunActive(); goto CSYJO; Ica4Q: $pengawass = $this->cbt->getPengawasByJadwal($tp->id_tp, $smt->id_smt, $jadwal, $sesi); goto MT95p; KUZm4: ef0BS: goto Q8rv7; EzyAA: VbEy1: goto PtuNO; GNzet: $pengawas = []; goto A_iFU; EgWSg: $sesi = $this->input->get("\x73\x65\x73\x69"); goto KiWy0; G8pMn: if (!($s != null)) { goto Of3Fj; } goto dQc3R; gu9Wx: $isesi = null; goto G8pMn; Sxm1J: $this->output_json($data); goto Q3QBh; nNArP: goto VbEy1; goto KUZm4; frpHz: if ($kelas == "\141\x6c\x6c") { goto ef0BS; } goto UjD2I; Zou8a: Trt6X: goto F9rF9; dQc3R: $isesi = $this->cbt->getSesiById($s); goto f3Lfy; mRGie: $tp = $this->dashboard->getTahunActive(); goto mOA2w; Qrtx_: $ijadwal = null; goto GNzet; UjD2I: $ikelas = $this->master->getKelasById($kelas); goto nNArP; A_iFU: if (!($jadwal != null && $jadwal != "\x6e\165\154\154")) { goto NINjR; } goto mRGie; QIcYQ: $kelas = $this->input->get("\153\x65\x6c\141\x73"); goto frpHz; F9rF9: $ijadwal = $this->cbt->getJadwalById($jadwal, $s); goto gMXfN; YDKe4: $siswas = $this->cbt->getRuangSiswaByKelas($tp->id_tp, $smt->id_smt, $kelas, $s); goto b5YSE; MT95p: $pengawas = []; goto kltqp; KiWy0: $jadwal = $this->input->get("\152\x61\x64\167\141\x6c"); goto ZlY35; b5YSE: foreach ($siswas as $siswa) { array_push($data["\x73\x69\x73\x77\141"], $siswa); uh32p: } goto IGOyI; PtuNO: $s = !$sesi ? null : $sesi; goto gu9Wx; IGOyI: lmXHh: goto SxBrz; gMXfN: NINjR: goto IR8qR; HOwuk: $kelas = $ikelas; goto EzyAA; kltqp: foreach ($pengawass as $p) { goto VaO3y; v0r56: loVtv: goto CLa83; Jj8qe: array_push($pengawas, $this->master->getGuruByArrId(explode("\54", $p->id_guru))); goto A1zrN; A1zrN: duhRH: goto v0r56; VaO3y: if (!(count(explode("\54", $p->id_guru)) > 0)) { goto duhRH; } goto Jj8qe; CLa83: } goto Zou8a; Q8rv7: $ikelas = $this->kelas->getIdKelas($tp->id_tp, $smt->id_smt); goto HOwuk; Q3QBh: } public function getSiswaRuang() { goto GqNGm; d97z3: if (!($jadwal != null && $jadwal != "\156\165\154\154")) { goto gKxc3; } goto BciNh; k2k4K: $isesi = null; goto BImZ2; cuiIW: if (!($pengawass != null && count(explode("\54", $pengawass->id_guru)) > 0)) { goto eAwWL; } goto BfpjN; mDYrf: $pengawas = []; goto cuiIW; xdnV7: gKxc3: goto z9wqA; BImZ2: if (!($s != null)) { goto vCDc1; } goto UBDyT; lVkxK: $data["\x73\151\x73\167\x61"] = $this->cbt->getSiswaByRuang($tp->id_tp, $smt->id_smt, $ruang, $s); goto WNGET; SpTKT: $smt = $this->dashboard->getSemesterActive(); goto LeyEJ; MqHfh: vCDc1: goto Fajng; WNGET: $data["\151\156\146\x6f"] = ["\162\x75\x61\x6e\x67" => $iruang, "\x73\145\x73\151" => $isesi, "\152\141\144\x77\141\154" => $ijadwal, "\x70\145\x6e\147\x61\x77\141\x73" => $pengawas]; goto aKtmj; z9wqA: $pengawass = $this->cbt->getPengawas($tp->id_tp . $smt->id_smt . $jadwal . $ruang . $sesi); goto mDYrf; xWf8g: $tp = $this->dashboard->getTahunActive(); goto SpTKT; XB8ah: $s = $sesi == "\x6e\165\154\154" ? null : $sesi; goto k2k4K; LeyEJ: $iruang = $this->cbt->getRuangById($ruang); goto XB8ah; Fajng: $ijadwal = null; goto d97z3; GqNGm: $ruang = $this->input->get("\162\x75\141\156\147"); goto ByJmu; UBDyT: $isesi = $this->cbt->getSesiById($s); goto MqHfh; EloB7: eAwWL: goto lVkxK; SrotW: $jadwal = $this->input->get("\152\141\x64\x77\x61\154"); goto xWf8g; ByJmu: $sesi = $this->input->get("\x73\145\x73\x69"); goto SrotW; BciNh: $ijadwal = $this->cbt->getJadwalById($jadwal, $s); goto xdnV7; aKtmj: $this->output_json($data); goto Rw6sD; BfpjN: $pengawas = $this->master->getGuruByArrId(explode("\x2c", $pengawass->id_guru)); goto EloB7; Rw6sD: } public function saveKop() { goto RKLg3; cjgjJ: $pengawas_2 = $this->input->post("\x70\145\x6e\x67\x61\x77\x61\x73\137\62", true); goto d8o9c; RkLND: $this->output_json($update); goto lQs_x; d8o9c: $insert = ["\x69\x64\137\x6b\157\160" => 123456, "\x68\145\x61\x64\x65\162\137\x31" => $header_1, "\150\x65\x61\x64\145\162\x5f\62" => $header_2, "\x68\x65\141\x64\145\162\x5f\x33" => $header_3, "\x68\x65\x61\x64\145\x72\137\x34" => $header_4, "\160\162\x6f\153\164\x6f\162" => $proktor, "\x70\145\156\x67\x61\167\141\163\x5f\61" => $pengawas_1, "\160\145\156\x67\141\167\141\x73\137\62" => $pengawas_2]; goto DWvbf; jiGeO: $proktor = $this->input->post("\160\162\x6f\153\x74\x6f\162", true); goto mmVx1; C0WgK: $header_3 = $this->input->post("\x68\x65\x61\x64\145\162\x5f\63", true); goto vDe5q; vDe5q: $header_4 = $this->input->post("\x68\x65\141\144\145\x72\137\x34", true); goto jiGeO; mmVx1: $pengawas_1 = $this->input->post("\x70\x65\156\x67\141\167\x61\163\x5f\61", true); goto cjgjJ; DWvbf: $update = $this->db->replace("\x63\x62\x74\x5f\x6b\x6f\160\x5f\141\x62\163\x65\x6e\x73\151", $insert); goto RkLND; QRs_C: $header_2 = $this->input->post("\150\145\141\144\145\x72\137\62", true); goto C0WgK; RKLg3: $header_1 = $this->input->post("\150\145\141\144\145\x72\x5f\x31", true); goto QRs_C; lQs_x: } public function absenPeserta() { goto J56rs; ZNYfa: $tp = $this->dashboard->getTahunActive(); goto TXupl; J56rs: $user = $this->ion_auth->user()->row(); goto YKVG3; VOhTt: $data["\x73\145\x73\x69"] = $this->dropdown->getAllSesi(); goto k_B3p; PfWMP: $data["\163\x6d\x74"] = $this->dashboard->getSemester(); goto Om0eq; Xz42j: $data["\x6a\x61\144\x77\x61\x6c"] = $this->dropdown->getAllJadwal($tp->id_tp, $smt->id_smt); goto hhJL3; hhJL3: $data["\x6b\x65\x6c\x61\x73"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt); goto HsyIQ; HsyIQ: $data["\162\165\141\x6e\x67"] = $this->dropdown->getAllRuang(); goto VOhTt; Om0eq: $data["\x73\155\164\137\141\x63\x74\x69\x76\145"] = $smt; goto Xz42j; U663A: $this->load->view("\x5f\164\145\155\160\x6c\141\164\145\163\57\144\x61\x73\x68\x62\157\x61\162\x64\57\137\x68\145\141\144\x65\162", $data); goto cLEih; YKVG3: $data = ["\165\x73\x65\x72" => $user, "\x6a\x75\x64\165\154" => "\103\x65\x74\141\x6b\x20\106\x6f\x72\155\141\164\x20\101\142\x73\145\156\x73\x69", "\x73\x75\142\152\x75\x64\x75\x6c" => "\x43\145\x74\141\x6b", "\x70\162\157\146\x69\x6c\x65" => $this->dashboard->getProfileAdmin($user->id), "\x73\x65\164\164\151\x6e\147" => $this->dashboard->getSetting()]; goto ZNYfa; AuByw: $this->load->view("\x5f\164\x65\155\160\154\141\164\x65\x73\x2f\x64\x61\x73\150\x62\x6f\x61\x72\144\57\x5f\x66\x6f\157\164\x65\x72"); goto kDRIk; KrPYU: $data["\x74\160\137\x61\x63\164\x69\x76\x65"] = $tp; goto PfWMP; cLEih: $this->load->view("\x63\x62\164\57\x63\145\x74\x61\153\57\141\142\x73\145\x6e"); goto AuByw; k_B3p: $data["\x6b\157\x70"] = $this->cbt->getSettingKopAbsensi(); goto U663A; YFr6w: $data["\x74\x70"] = $this->dashboard->getTahun(); goto KrPYU; TXupl: $smt = $this->dashboard->getSemesterActive(); goto YFr6w; kDRIk: } public function beritaAcara() { goto Enbtt; A9AqN: $data["\163\x6d\x74"] = $this->dashboard->getSemester(); goto SMXPn; dzCmv: $data["\153\x6f\x70"] = $this->cbt->getSettingKopBeritaAcara(); goto rsMkp; z7oRQ: $data["\x74\160\x5f\x61\x63\x74\151\166\x65"] = $tp; goto A9AqN; Enbtt: $user = $this->ion_auth->user()->row(); goto aVZHe; aVZHe: $data = ["\x75\163\x65\x72" => $user, "\152\x75\x64\165\x6c" => "\x43\x65\x74\141\153\x20\102\145\x72\x69\164\x61\x20\x41\x63\x61\162\141", "\163\165\142\152\x75\144\165\154" => "\x43\x65\164\141\153", "\x70\162\x6f\x66\151\154\145" => $this->dashboard->getProfileAdmin($user->id), "\163\x65\164\164\x69\x6e\147" => $this->dashboard->getSetting()]; goto BHguG; ZBs0F: $smt = $this->dashboard->getSemesterActive(); goto adNop; adNop: $data["\164\x70"] = $this->dashboard->getTahun(); goto z7oRQ; hPP4I: $data["\x6a\141\144\x77\x61\154"] = $this->dropdown->getAllJadwal($tp->id_tp, $smt->id_smt); goto oG19O; cH5DZ: $data["\162\165\x61\x6e\147"] = $this->dropdown->getAllRuang(); goto oQDQl; SMXPn: $data["\163\155\164\x5f\x61\143\x74\x69\166\145"] = $smt; goto hPP4I; rsMkp: $this->load->view("\137\x74\x65\155\160\154\x61\x74\145\x73\57\144\x61\x73\x68\142\x6f\x61\162\144\57\x5f\150\145\x61\x64\x65\162", $data); goto LBsKY; LBsKY: $this->load->view("\x63\x62\x74\57\x63\x65\164\x61\x6b\x2f\142\145\162\x69\164\141\141\x63\141\x72\x61"); goto il7av; BHguG: $tp = $this->dashboard->getTahunActive(); goto ZBs0F; oQDQl: $data["\163\145\163\x69"] = $this->dropdown->getAllSesi(); goto dzCmv; il7av: $this->load->view("\x5f\164\x65\x6d\x70\x6c\141\164\x65\163\57\144\x61\x73\x68\142\157\141\162\x64\x2f\x5f\146\x6f\157\164\145\162"); goto LBDfV; oG19O: $data["\153\145\154\x61\x73"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt); goto cH5DZ; LBDfV: } public function saveKopBerita() { goto Sy48W; viKaN: $update = $this->db->replace("\143\142\164\137\x6b\157\160\137\142\145\x72\151\x74\x61", $insert); goto wa7GN; d5lyL: $insert = ["\x69\x64\137\153\157\x70" => 123456, "\x68\145\x61\144\145\x72\137\61" => $header_1, "\x68\145\x61\144\145\x72\x5f\62" => $header_2, "\150\x65\x61\x64\x65\x72\137\63" => $header_3, "\x68\145\x61\144\x65\162\137\x34" => $header_4]; goto viKaN; m4weL: $header_2 = $this->input->post("\150\x65\141\144\145\x72\x5f\62", true); goto zL6yr; zL6yr: $header_3 = $this->input->post("\150\x65\141\x64\x65\x72\x5f\63", true); goto Barry; wa7GN: $this->output_json($update); goto afdDl; Barry: $header_4 = $this->input->post("\x68\x65\x61\144\x65\162\137\x34", true); goto d5lyL; Sy48W: $header_1 = $this->input->post("\150\145\x61\144\145\162\137\x31", true); goto m4weL; afdDl: } public function pesertaUjian($mode = null) { goto iC5qa; I5xon: $data["\x74\x70"] = $this->dashboard->getTahun(); goto dlh79; j_gD9: $data["\153\157\160"] = $this->dashboard->getSetting(); goto Ex6cp; bZCeJ: $data = ["\165\163\145\162" => $user, "\152\165\144\x75\x6c" => "\103\x65\164\141\153\x20\104\x61\x66\x74\x61\162\40\120\145\163\x65\x72\164\x61", "\163\165\142\152\x75\x64\165\x6c" => "\103\x65\164\x61\x6b", "\160\162\x6f\146\x69\x6c\145" => $this->dashboard->getProfileAdmin($user->id), "\163\145\164\164\151\156\147" => $this->dashboard->getSetting()]; goto Nyki6; iC5qa: $user = $this->ion_auth->user()->row(); goto bZCeJ; dlh79: $data["\x74\x70\137\141\x63\x74\x69\x76\x65"] = $tp; goto ilyE8; Ur_us: $data["\x73\145\x73\x69\163"] = $this->cbt->getAllKodeSesi(); goto j_gD9; Nyki6: $tp = $this->dashboard->getTahunActive(); goto nAXHK; Ap2rv: $data["\163\151\x73\167\x61"] = $this->cbt->getAllPesertaByKelas($tp->id_tp, $smt->id_smt); goto D0_Ub; Q_TPo: $data["\153\x65\154\141\x73\163"] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt); goto ev3J5; ev3J5: $data["\x72\x75\141\156\x67\163"] = $this->dropdown->getAllRuang(); goto Ur_us; L8IHT: $data["\x6d\157\144\145"] = $mode; goto jj9Pa; ilyE8: $data["\x73\x6d\164"] = $this->dashboard->getSemester(); goto iPA48; Ex6cp: $data["\165\x6a\x69\141\156"] = $this->dropdown->getAllJenisUjian(); goto L8IHT; nAXHK: $smt = $this->dashboard->getSemesterActive(); goto I5xon; iPA48: $data["\163\155\164\137\x61\143\164\151\166\145"] = $smt; goto Q_TPo; tuoqO: $data["\x73\151\163\167\x61"] = $this->cbt->getAllPesertaByRuang($tp->id_tp, $smt->id_smt); goto hlXsm; hlXsm: dnA7G: goto OCDgq; sCUjl: $this->load->view("\143\x62\x74\x2f\x63\145\x74\141\153\57\x70\x65\x73\x65\x72\164\x61\x75\x6a\151\141\x6e"); goto Q31j2; D0_Ub: goto dnA7G; goto OxxG8; OxxG8: tkTOl: goto tuoqO; OCDgq: $this->load->view("\x5f\164\x65\155\160\x6c\x61\x74\x65\163\x2f\x64\x61\163\150\x62\157\141\x72\x64\57\x5f\x68\145\141\144\x65\x72", $data); goto sCUjl; Q31j2: $this->load->view("\x5f\x74\x65\x6d\x70\x6c\141\x74\x65\163\x2f\144\141\x73\150\x62\157\141\x72\x64\x2f\x5f\146\157\157\164\145\x72"); goto u6z8B; jj9Pa: if ($mode == "\x31" || $mode == null) { goto tkTOl; } goto Ap2rv; u6z8B: } public function pengawas() { goto rOSYh; G43A1: $data["\152\145\156\x69\163\137\163\x65\x6c\x65\x63\164\x65\x64"] = $jenis_selected; goto skeHF; AyxQG: $ids = []; goto F3l7K; VLOtk: $data["\x64\x61\x72\151\137\163\145\154\145\x63\164\145\x64"] = $dari_selected; goto WGPCk; BzD15: $data["\163\x65\163\x69"] = $this->dropdown->getAllSesi(); goto TvFnY; B0Z0t: $kelas_level = $this->cbt->getDistinctKelasLevel($tp->id_tp, $smt->id_smt, $arrLevel); goto vIaZ3; aOazy: $data["\x66\151\154\x74\145\162\x5f\163\x65\x6c\x65\x63\164\145\x64"] = $filter_selected; goto VLOtk; CcT5m: $jadwals = []; goto uqnmy; I1gZi: $data["\152\x65\x6e\x69\x73"] = ['' => "\x62\145\x6c\165\155\40\141\x64\x61\40\x6a\141\144\167\x61\x6c\40\165\152\151\x61\x6e"]; goto bov2Y; TgbEn: $data["\x73\155\164"] = $this->dashboard->getSemester(); goto X4DcP; GSg0E: $this->load->view("\x5f\164\145\155\x70\154\x61\164\145\x73\x2f\x64\x61\x73\x68\x62\x6f\x61\x72\144\x2f\x5f\146\157\157\x74\x65\x72"); goto WVT3f; fsSEq: if (!($jenis_selected != null)) { goto nE180; } goto c1Byi; X6EAa: foreach ($ruangs as $id_ruang => $ruang) { goto C7vfD; KvYM0: PH75c: goto EWLGx; EWLGx: WUNCl: goto liq9N; C7vfD: foreach ($ruang as $id_sesi => $sesi) { goto RBjGs; RBjGs: foreach ($kelas_level as $kl) { goto BXiWH; BXiWH: foreach ($jadwals as $jadwal) { goto xPniR; AKCfL: kLlyv: goto ebaJt; xPniR: if (!($jadwal->bank_level == $kl->level_id)) { goto piUfm; } goto WyFBQ; hMpG4: piUfm: goto AKCfL; WyFBQ: $jadwal_pengawas[$jadwal->tgl_mulai][$id_ruang][$id_sesi][$jadwal->kode] = $jadwal; goto hMpG4; ebaJt: } goto UNuJ0; UNuJ0: NcCsj: goto SaKvp; SaKvp: imino: goto L20CW; L20CW: } goto e0Pnw; e0Pnw: f3M89: goto cUk2U; cUk2U: BDX6G: goto jspvu; jspvu: } goto KvYM0; liq9N: } goto MoMFQ; AZ2jz: $data["\160\x65\156\x67\141\167\141\163"] = $pengawas; goto GldGP; GGqgx: if (!(count($arrLevel) > 0)) { goto CRq58; } goto B0Z0t; MoMFQ: fccZC: goto c51Y4; rOSYh: $user = $this->ion_auth->user()->row(); goto cGWIb; F3l7K: if (!(count($id_jenis) > 0)) { goto nH2Un; } goto O_Qzb; X4DcP: $data["\x73\155\164\137\x61\143\164\x69\x76\x65"] = $smt; goto vTD9v; ocJ4j: zQOxQ: goto Bsf56; cGWIb: $setting = $this->dashboard->getSetting(); goto J_Xqo; b9_hb: CRq58: goto EUhDn; s5lGK: $data["\152\141\144\x77\x61\x6c\x73\x5f\x72\165\141\156\x67"] = $perRuang; goto d3PJt; ru_5q: $data = ["\x75\163\145\162" => $user, "\x6a\165\144\165\x6c" => "\x4a\141\144\x77\141\x6c\40\120\x65\x6e\x67\141\167\141\163", "\x73\165\x62\152\165\144\165\154" => "\103\x65\164\141\153\x20\112\x61\144\x77\x61\x6c\x20\x50\x65\156\x67\x61\167\141\x73", "\163\x65\164\164\151\156\x67" => $setting]; goto l0OeL; GldGP: $gurus = $this->dropdown->getAllGuru(); goto CcT5m; uqnmy: if (!($jenis_selected != null)) { goto zpEFY; } goto CMydI; e4cQP: if (!(count($arrKls) > 0)) { goto TK9pD; } goto u3PC2; V1m9E: L14o8: goto T3nvS; r8PJe: $this->load->view("\x63\142\x74\57\x63\145\164\x61\153\x2f\160\x65\x6e\x67\x61\167\141\x73"); goto GSg0E; fwnOA: zpEFY: goto xJjH2; ZeB2e: nE180: goto AZ2jz; dYjuQ: foreach ($jadwals as $jadwal) { goto lW1rt; LoUFv: JibAb: goto JckDt; GssFI: array_push($arrLevel, $jadwal->bank_level); goto LoUFv; lW1rt: if (in_array($jadwal->bank_level, $arrLevel)) { goto JibAb; } goto GssFI; JckDt: WZ71a: goto LZS2j; LZS2j: } goto V1m9E; Bsf56: $jadwal_pengawas = []; goto e4cQP; anr88: foreach ($kelas_level as $kl) { array_push($arrKls, $kl->id_kelas); kWoOg: } goto ocJ4j; ZjXt7: $result = []; goto EVIHg; c1Byi: $pengawas = $this->cbt->getAllPengawas($tp->id_tp, $smt->id_smt); goto ZeB2e; CMydI: $jadwals = $this->cbt->getJadwalByJenis($jenis_selected, "\x30", $dari_selected, $sampai_selected); goto fwnOA; eRO_g: $data["\164\x70\x5f\141\143\x74\x69\166\x65"] = $tp; goto TgbEn; xJjH2: $arrLevel = []; goto dYjuQ; rvrpN: $pengawas = []; goto fsSEq; bov2Y: goto H17bk; goto g6r3W; J_Xqo: $jenis_selected = $this->input->get("\x6a\x65\156\151\x73", true); goto c8_Zu; z_GLt: $dari_selected = $this->input->get("\144\141\162\151", true); goto x2s_h; JLDBD: $data["\164\160"] = $this->dashboard->getTahun(); goto eRO_g; c51Y4: TK9pD: goto F06vF; lUnAO: $data["\162\165\x61\156\x67"] = $ruangs; goto X6EAa; LpbdO: H17bk: goto Fsw_T; WGPCk: $data["\x73\141\155\160\x61\x69\137\x73\145\154\x65\143\164\145\x64"] = $sampai_selected; goto rvrpN; Mttbr: $data["\146\151\154\x74\145\162"] = ["\x30" => "\123\x65\155\x75\x61", "\x31" => "\124\x61\x6e\147\147\141\x6c"]; goto G43A1; vTD9v: $id_jenis = $this->cbt->getDistinctJenisJadwal($tp->id_tp, $smt->id_smt); goto AyxQG; Fsw_T: $filter_selected = $this->input->get("\x66\x69\154\164\x65\162", true); goto z_GLt; GxQ21: if (count($ids) > 0) { goto mMoEn; } goto I1gZi; d3PJt: $data["\160\162\157\146\151\x6c\145"] = $this->dashboard->getProfileAdmin($user->id); goto Wuq7c; c8_Zu: $jenis_ujian = $this->cbt->getJenisById($jenis_selected); goto ru_5q; T3nvS: $kelas_level = []; goto GGqgx; t2DIZ: $data["\152\141\144\x77\x61\x6c\163"] = $result; goto s5lGK; x2s_h: $sampai_selected = $this->input->get("\163\141\155\x70\x61\151", true); goto Mttbr; PayNt: bLMrc: goto t2DIZ; l0OeL: $tp = $this->dashboard->getTahunActive(); goto sLVNb; Wuq7c: $data["\162\x75\x61\156\x67\137\163\x65\163\151"] = $this->cbt->getRuangSesi($tp->id_tp, $smt->id_smt); goto BzD15; cwltO: HbadL: goto rLLC_; vIaZ3: $data["\x6b\145\154\x61\163\x5f\154\x65\166\x65\154"] = $kelas_level; goto b9_hb; O_Qzb: foreach ($id_jenis as $jenis) { array_push($ids, $jenis->id_jenis); dhhhn: } goto cwltO; s_p_5: $data["\152\145\x6e\x69\163"] = $this->cbt->getAllJenisUjianByArrJenis($ids); goto LpbdO; sLVNb: $smt = $this->dashboard->getSemesterActive(); goto JLDBD; F06vF: $perRuang = []; goto ZjXt7; g6r3W: mMoEn: goto s_p_5; EVIHg: foreach ($jadwal_pengawas as $jadwal_pengawa) { goto kyXbT; HfSlZ: LPXlR: goto BwcA5; kyXbT: foreach ($jadwal_pengawa as $r => $jp) { goto JxXlx; JxXlx: foreach ($jp as $s => $j) { goto uCOaV; uCOaV: foreach ($j as $m => $km) { goto E3MIj; ubBxI: $pw = ''; goto P70CD; Q2Oni: $perRuang[$forAdd->ruang] = []; goto G1qiD; xKOGo: $siswas = $this->cbt->getSiswaByRuang($tp->id_tp, $smt->id_smt, $ir, $is); goto iVQG0; yQAVC: $jpp = count($sel); goto ubBxI; yR5lj: $jp = 0; goto yQAVC; UhpBJ: BXgzU: goto dGw9A; PQc6c: ESLAk: goto yRRMj; G1qiD: array_push($perRuang[$forAdd->ruang], $forAdd); goto leg2o; yRRMj: array_push($perRuang[$forAdd->ruang], $forAdd); goto UhpBJ; KpjW1: $ir = $ruangs[$r][$s]->ruang_id; goto Yhll2; Yl3ub: $sel = isset($pengawas[$km->id_jadwal]) && isset($pengawas[$km->id_jadwal][$ir]) && isset($pengawas[$km->id_jadwal][$ir][$is]) ? explode("\x2c", $pengawas[$km->id_jadwal][$ir][$is]->id_guru) : []; goto yR5lj; P70CD: foreach ($sel as $p) { goto QazqG; R4HvI: euKmf: goto Nctxm; QazqG: if (!isset($gurus[$p])) { goto UV6yz; } goto KR88U; KWs_8: gWMHk: goto p5IJV; yFlNx: $pw .= "\x3c\142\x72\76"; goto R4HvI; Nctxm: UV6yz: goto KWs_8; YzWzM: if (!($jp < $jpp)) { goto euKmf; } goto yFlNx; KR88U: $pw .= $gurus[$p]; goto RnlsY; RnlsY: $jp += 1; goto YzWzM; p5IJV: } goto xbYqQ; xbYqQ: UdkGO: goto xKOGo; RqnrH: $ns = $ruangs[$r][$s]->nama_sesi; goto KpjW1; iVQG0: $forAdd = json_decode(json_encode(["\x6a\x6d\154\137\163\x69\163\x77\x61" => count($siswas), "\x74\x61\x6e\147\x67\x61\154" => $km->tgl_mulai, "\x72\x75\x61\156\x67" => $nr, "\x73\145\163\x69" => $ns, "\x6d\141\x70\145\154" => $km->nama_mapel, "\x77\x61\x6b\x74\165" => $km->jam_ke, "\x70\145\x6e\147\141\x77\141\x73" => $pw])); goto koLPM; DgKdA: if (isset($perRuang[$forAdd->ruang])) { goto ESLAk; } goto Q2Oni; koLPM: array_push($result, $forAdd); goto DgKdA; leg2o: goto BXgzU; goto PQc6c; E3MIj: $nr = $ruangs[$r][$s]->nama_ruang; goto RqnrH; Yhll2: $is = $ruangs[$r][$s]->sesi_id; goto Yl3ub; dGw9A: j47fO: goto ijFHU; ijFHU: } goto Ilomk; TZ52G: Nkpwr: goto xCiKD; Ilomk: Gbk1I: goto TZ52G; xCiKD: } goto YUwhG; YUwhG: BF_Sr: goto Q5hN1; Q5hN1: Y6Vk4: goto CpUA4; CpUA4: } goto IktKO; IktKO: dq7YU: goto HfSlZ; BwcA5: } goto PayNt; rLLC_: nH2Un: goto GxQ21; u3PC2: $ruangs = $this->cbt->getDistinctRuang($tp->id_tp, $smt->id_smt, $arrKls); goto lUnAO; skeHF: $data["\152\145\156\151\x73\137\x75\x6a\151\141\x6e"] = $jenis_ujian; goto aOazy; TvFnY: $this->load->view("\x5f\164\145\x6d\160\x6c\141\164\x65\x73\x2f\x64\x61\163\150\142\157\141\162\144\x2f\x5f\x68\x65\x61\144\x65\x72", $data); goto r8PJe; EUhDn: $arrKls = []; goto anr88; WVT3f: } }
