<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 defined("\102\x41\123\x45\x50\101\124\x48") or exit("\116\x6f\x20\144\151\162\x65\x63\x74\x20\x73\143\162\x69\160\x74\40\141\x63\x63\x65\163\163\40\141\x6c\x6c\157\167\x65\144"); class HasilUjian extends CI_Controller { public function __construct() { goto EElkW; C5_4v: fafFe: goto gKQeb; Ok730: if ($this->ion_auth->logged_in()) { goto fafFe; } goto qZzsc; EElkW: parent::__construct(); goto Ok730; FpwWO: $this->load->model("\x4d\141\163\x74\145\x72\x5f\155\157\144\x65\x6c", "\x6d\141\163\164\145\162"); goto Wf3iT; Wf3iT: $this->load->model("\125\152\151\x61\x6e\x5f\x6d\157\144\x65\x6c", "\x75\152\x69\x61\x6e"); goto G4ILR; gKQeb: $this->load->library(["\144\141\x74\x61\x74\141\142\154\145\x73"]); goto FpwWO; qZzsc: redirect("\141\x75\x74\150"); goto C5_4v; G4ILR: $this->user = $this->ion_auth->user()->row(); goto VyXIL; VyXIL: } public function output_json($data, $encode = true) { goto gVgy5; Tn_YS: $this->output->set_content_type("\141\160\x70\154\151\x63\141\x74\x69\157\x6e\x2f\152\163\x6f\x6e")->set_output($data); goto TG5et; VUuyd: rUzck: goto Tn_YS; gVgy5: if (!$encode) { goto rUzck; } goto CjfDO; CjfDO: $data = json_encode($data); goto VUuyd; TG5et: } public function data() { goto nDkYf; qcz5H: hpor4: goto CHH2O; nDkYf: $nip_guru = null; goto LCEbv; NWBYC: $nip_guru = $this->user->username; goto qcz5H; CHH2O: $this->output_json($this->ujian->getHasilUjian($nip_guru), false); goto N0weN; LCEbv: if (!$this->ion_auth->in_group("\147\x75\x72\x75")) { goto hpor4; } goto NWBYC; N0weN: } public function NilaiMhs($id) { $this->output_json($this->ujian->HslUjianById($id, true), false); } public function index() { goto rBh2s; rBh2s: $data = ["\x75\163\x65\x72" => $this->user, "\x6a\165\144\x75\154" => "\x55\x6a\x69\141\x6e", "\163\x75\142\152\165\144\165\x6c" => "\110\141\163\x69\154\40\x55\152\x69\x61\x6e"]; goto jJNRN; Yy04m: $this->load->view("\137\x74\x65\155\x70\x6c\x61\164\145\x73\x2f\x64\141\163\150\142\157\141\162\x64\57\x5f\x66\157\157\164\145\x72\56\x70\150\x70"); goto byhvK; jJNRN: $this->load->view("\137\164\x65\155\x70\x6c\141\x74\145\x73\57\x64\x61\163\x68\142\x6f\141\x72\144\57\137\150\145\x61\x64\145\x72\56\x70\150\x70", $data); goto fpjIV; fpjIV: $this->load->view("\165\x6a\x69\141\156\x2f\x68\x61\x73\x69\154"); goto Yy04m; byhvK: } public function detail($id) { goto jYAl3; I1xSr: $data = ["\165\163\145\x72" => $this->user, "\152\165\144\x75\x6c" => "\x55\152\x69\141\156", "\163\x75\x62\x6a\165\x64\165\154" => "\x44\x65\164\x61\x69\x6c\x20\x48\x61\163\x69\x6c\40\125\152\x69\x61\156", "\x75\152\151\x61\x6e" => $ujian, "\x6e\x69\x6c\x61\151" => $nilai]; goto Nr9s9; O1nkp: $this->load->view("\x5f\164\x65\155\160\154\x61\164\145\163\x2f\144\141\163\x68\142\157\141\x72\x64\57\x5f\146\157\x6f\x74\x65\162\x2e\x70\150\x70"); goto t7l1S; obSaj: $this->load->view("\165\152\151\x61\156\x2f\x64\145\x74\141\x69\154\137\x68\x61\163\151\154"); goto O1nkp; jYAl3: $ujian = $this->ujian->getUjianById($id); goto pAqKZ; Nr9s9: $this->load->view("\x5f\164\145\155\160\x6c\141\x74\145\x73\x2f\144\x61\x73\x68\142\x6f\x61\x72\x64\57\x5f\x68\x65\x61\x64\145\x72\56\160\150\x70", $data); goto obSaj; pAqKZ: $nilai = $this->ujian->bandingNilai($id); goto I1xSr; t7l1S: } public function cetak($id) { goto E6u3O; E6u3O: $mhs = $this->ujian->getIdMahasiswa($this->user->username); goto YtzLi; h6cL5: $data = ["\165\152\x69\141\x6e" => $ujian, "\x68\x61\163\151\x6c" => $hasil, "\x6d\x68\x73" => $mhs]; goto rrRdG; YtzLi: $hasil = $this->ujian->HslUjian($id, $mhs->id_siswa)->row(); goto CSjmF; rrRdG: $this->load->view("\x75\x6a\151\141\x6e\57\x63\x65\164\141\x6b", $data); goto tR6DL; CSjmF: $ujian = $this->ujian->getUjianById($id); goto h6cL5; tR6DL: } public function cetak_detail($id) { goto dobZP; dobZP: $ujian = $this->ujian->getUjianById($id); goto Z12lA; l14B2: $this->load->view("\165\152\x69\141\x6e\x2f\x63\x65\x74\141\x6b\137\x64\x65\x74\x61\151\x6c", $data); goto RtWRk; IMqtq: $data = ["\165\152\x69\141\x6e" => $ujian, "\156\x69\154\x61\x69" => $nilai, "\x68\141\x73\151\154" => $hasil]; goto l14B2; Z12lA: $nilai = $this->ujian->bandingNilai($id); goto deEOU; deEOU: $hasil = $this->ujian->HslUjianById($id)->result(); goto IMqtq; RtWRk: } }
