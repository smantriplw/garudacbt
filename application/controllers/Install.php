<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 defined("\102\x41\x53\x45\x50\101\x54\x48") or exit("\116\x6f\x20\x64\151\x72\145\143\164\x20\x73\x63\x72\151\160\x74\40\x61\x63\x63\x65\x73\163\40\x61\154\x6c\x6f\x77\x65\x64"); class Install extends CI_Controller { function __construct() { goto uUCKR; uUCKR: parent::__construct(); goto Fj8QB; SlSWM: $this->load->model("\x44\141\163\150\x62\157\x61\x72\x64\x5f\155\x6f\x64\145\154", "\x64\141\x73\150\x62\x6f\x61\x72\x64"); goto Qa3We; Fj8QB: include APPPATH . "\x63\x6f\x6e\146\151\147\57\x64\141\164\x61\142\x61\x73\145\x2e\x70\x68\160"; goto QTOWD; BwcIo: ovJ7I: goto ivoiw; Y32hO: $this->load->dbforge(); goto BwcIo; QTOWD: if (!($db["\x64\x65\146\141\165\154\164"]["\144\141\164\x61\142\141\163\x65"] != '')) { goto ovJ7I; } goto d6SKE; d6SKE: $this->load->database(); goto Y32hO; ivoiw: $this->load->model("\x49\156\163\x74\x61\154\154\x5f\155\157\144\x65\x6c", "\x69\x6e\x73\x74\141\x6c\154"); goto SlSWM; Qa3We: } public function output_json($data, $encode = true) { goto lxT5D; vIWqR: GA6m4: goto agqRg; T59aI: $data = json_encode($data); goto vIWqR; agqRg: $this->output->set_content_type("\x61\160\x70\154\151\143\x61\164\151\x6f\156\57\152\163\157\x6e")->set_output($data); goto PHDZi; lxT5D: if (!$encode) { goto GA6m4; } goto T59aI; PHDZi: } public function index() { goto ULthB; t3rVI: yyUcc: goto olyPF; i0IJG: btAVm: goto A0NY5; XNucG: kNUKJ: goto kuuC1; UBrzL: $this->load->view("\x69\x6e\163\164\x61\x6c\x6c\x2f\x68\x65\x61\144\145\162", ["\x64\x61\164\141" => $data]); goto WsnpI; Ii4_A: goto yyUcc; goto XNucG; WsnpI: $this->load->view("\x69\156\163\x74\x61\x6c\154\57\163\x74\145\x70"); goto wA4Pp; YJsqH: if ($res == "\x33") { goto V1LvK; } goto ltxxZ; BSiRN: $data["\x6d\x73\147"] = "\x62\145\x6c\165\x6d\40\141\x64\141\x20\x61\144\x6d\x69\156\151\163\164\162\x61\164\157\x72"; goto t3rVI; wA4Pp: $this->load->view("\x69\156\163\x74\141\154\154\57\146\x6f\x6f\164\145\162"); goto d5x12; qlPez: redirect("\x75\160\144\x61\164\x65"); goto i0IJG; QHTXK: if ($res == "\60") { goto XQ3Nx; } goto Bvtti; kuuC1: $data["\155\x73\x67"] = "\163\145\142\141\x67\151\141\x6e\x20\x74\141\142\x65\154\40\142\145\x6c\165\x6d\40\144\x69\x62\x75\x61\x74"; goto VJmqD; d5x12: goto btAVm; goto LWWo_; Bvtti: if ($res == "\62") { goto kNUKJ; } goto YJsqH; ULthB: $res = $this->install->check_installer(); goto QHTXK; HlMxZ: $data->error = $res; goto UBrzL; VJmqD: goto yyUcc; goto ddVYj; ltxxZ: $data["\x6d\163\x67"] = "\x62\145\154\165\155\x20\141\x64\x61\40\x64\x61\x74\x61\x20\163\x65\x6b\x6f\x6c\x61\x68"; goto Ii4_A; olyPF: $data = $this->getSaved(); goto HlMxZ; LWWo_: XQ3Nx: goto qlPez; ddVYj: V1LvK: goto BSiRN; A0NY5: } function getSaved() { goto noGRe; aZn1e: $data["\x70\x72\x6f\x76"] = $setting->provinsi; goto QhPx3; g0rWR: $data["\152\x65\x6e\x6a\141\156\147"] = ''; goto qtptq; MdZer: $data["\x64\x61\x74\x61\x62\141\163\x65"] = $database; goto bicrW; aMlPT: $data["\165\x73\x65\162\137\x61\x64\x6d\151\156"] = $admin->username; goto yaotw; tEwUA: if ($this->db->table_exists("\165\x73\145\162\163")) { goto VmNXR; } goto i48d0; dPQvq: $data["\x68\157\x73\x74\x6e\141\x6d\x65"] = $db["\x64\x65\x66\x61\165\154\164"]["\150\x6f\163\164\x6e\x61\x6d\x65"]; goto EVY_n; hHcrt: $data["\x61\154\x61\155\x61\164"] = ''; goto RMLpu; qtptq: $data["\163\x61\164\165\141\x6e"] = ''; goto kU6b7; zQ9Ys: $data["\143\x75\x72\x72\x65\156\x74\x5f\x70\x61\x67\145"] = $current_page; goto XNmFa; jLdoZ: $current_page = $admin == null ? 2 : ($setting == null ? 3 : 4); goto Y23bh; sKJD1: $data["\x6a\145\156\152\141\x6e\147"] = $setting->jenjang; goto eJtLO; jBb8P: $data["\141\160\x6c\x69\153\141\x73\x69"] = ''; goto VzEgX; EavGI: $data["\x6b\145\x70\163\145\x6b"] = $setting->kepsek; goto Drr6t; xxwQH: $data["\x70\141\163\x73\167\157\x72\x64"] = $db["\x64\x65\146\x61\x75\154\164"]["\160\x61\x73\x73\167\157\x72\144"]; goto MdZer; hEkWQ: $data["\153\x65\143"] = $setting->kecamatan; goto LtxiL; E3HhK: $data["\x6e\x61\155\141\137\x61\144\x6d\151\156"] = $admin->first_name . "\40" . $admin->last_name; goto aMlPT; VQV1o: $data["\165\163\x65\x72\x5f\141\x64\x6d\x69\156"] = ''; goto trhIq; Y23bh: CX2m3: goto zQ9Ys; XNmFa: return json_decode(json_encode($data)); goto CLEWp; LtxiL: $data["\153\x6f\x74\x61"] = $setting->kota; goto aZn1e; noGRe: include APPPATH . "\x63\x6f\x6e\x66\151\147\57\x64\141\164\x61\142\141\x73\x65\56\160\150\160"; goto e5Mgw; k2xZs: goto CX2m3; goto jXxok; kU6b7: $data["\153\x65\160\x73\x65\153"] = ''; goto hHcrt; uzEtb: $data["\x6b\145\x63"] = ''; goto hFxZA; yaotw: $data["\x70\x61\163\163\x5f\x61\144\155\x69\x6e"] = $admin->password; goto W_Rmy; PaPER: $current_page = 2; goto tEwUA; knpgU: $setting = $this->dashboard->getSetting(); goto hBrxr; trhIq: $data["\160\141\163\x73\137\141\x64\155\x69\156"] = ''; goto jBb8P; xuitl: $data["\x61\x70\154\x69\153\141\x73\x69"] = $setting->nama_aplikasi; goto vv4R2; hFxZA: $data["\153\x6f\x74\141"] = ''; goto Yr2ui; VzEgX: $data["\x73\145\x6b\157\154\x61\150"] = ''; goto g0rWR; QhPx3: cvdoX: goto jLdoZ; EVY_n: $data["\x75\x73\145\162\156\x61\x6d\x65"] = $db["\x64\x65\146\141\x75\154\164"]["\x75\163\x65\x72\156\141\x6d\x65"]; goto xxwQH; hBrxr: if (!($setting != null)) { goto cvdoX; } goto xuitl; vv4R2: $data["\163\145\x6b\x6f\154\x61\x68"] = $setting->sekolah; goto sKJD1; jXxok: VmNXR: goto N9FHD; W_Rmy: NsQm0: goto knpgU; RMLpu: $data["\144\145\163\141"] = ''; goto uzEtb; eJtLO: $data["\x73\x61\x74\x75\141\x6e"] = $setting->satuan_pendidikan; goto EavGI; RPVx9: $data["\144\x65\163\x61"] = $setting->desa; goto hEkWQ; wmfg0: if (!($admin != null)) { goto NsQm0; } goto E3HhK; Yr2ui: $data["\160\x72\157\x76"] = ''; goto PaPER; Drr6t: $data["\x61\x6c\141\155\141\164"] = $setting->alamat; goto RPVx9; N9FHD: $admin = $this->db->get("\165\x73\145\x72\163")->row(); goto wmfg0; i48d0: $current_page = 2; goto bYCQ4; bicrW: $data["\156\141\x6d\141\137\x61\x64\155\151\x6e"] = ''; goto VQV1o; e5Mgw: $database = $db["\144\145\146\141\x75\154\164"]["\x64\141\164\141\x62\141\x73\x65"]; goto dPQvq; bYCQ4: $data["\x6d\163\147"] = "\x54\141\x62\154\x65\40\x60\x75\163\x65\162\x73\140\40\142\x65\x6c\x75\155\x20\x64\x69\x62\165\141\x74"; goto k2xZs; CLEWp: } public function steps() { goto Xx4lN; Xx4lN: $data = $this->getSaved(); goto TUYuP; rYmBq: $this->load->view("\151\156\x73\x74\141\154\x6c\57\x73\x74\145\160"); goto V3Yaa; V3Yaa: $this->load->view("\x69\x6e\163\164\141\x6c\x6c\57\x66\157\x6f\164\145\x72"); goto UpooA; TUYuP: $this->load->view("\x69\156\163\x74\x61\x6c\x6c\x2f\150\145\x61\144\145\162", ["\144\x61\164\x61" => $data]); goto rYmBq; UpooA: } public function checkDatabase() { goto C_SWf; jx3w0: $data["\150\x6f\x73\164"] = true; goto UIHGg; nGXMZ: xn9Sd: goto k2QTW; X2bzq: $new = str_replace("\x25\104\x41\124\x41\x42\101\x53\x45\45", $database, $new); goto vpVYn; QT323: $data["\150\x6f\x73\x74\137\x6d\x73\x67"] = "\164\151\x64\x61\x6b\40\x61\144\141\x20\x61\153\x73\x65\x73\40\x6b\145\40\146\x69\x6c\x65\x20\144\141\x74\x61\142\x61\163\x65\56\x70\x68\160\x2c\40\160\141\x73\164\151\153\141\x6e\x20\x70\x65\x72\x6d\151\163\x73\151\x6f\x6e\40\x73\x75\x64\141\150\x20\144\151\x7a\151\156\x6b\x61\156"; goto hJf3Y; hJf3Y: goto lEySm; goto nGXMZ; HQasV: $data["\150\157\x73\164\x5f\x6d\163\147"] = "\164\151\144\141\x6b\x20\x62\x6f\x6c\145\x68\x20\141\144\141\x20\171\141\156\x67\x20\153\157\163\157\x6e\147"; goto T87HN; Jyl3n: $output_path = APPPATH . "\x63\x6f\x6e\x66\x69\147\x2f\x64\x61\164\141\x62\x61\163\x65\56\x70\150\160"; goto M6drN; C1LZ9: $data["\x68\x6f\163\164"] = true; goto LLZKb; oYUo4: $data["\164\x61\142\x6c\x65"] = $this->create_tables($hostname, $hostuser, $hostpass, $database); goto jx3w0; Zar6B: CYqmQ: goto WlHw_; RNtbN: $new = str_replace("\x25\x50\x41\123\x53\127\x4f\x52\104\x25", $hostpass, $new); goto X2bzq; OvTFt: $hostpass = $this->input->post("\x68\157\163\164\x70\141\163\x73", true); goto OxH_9; coQZS: $data["\150\x6f\163\164\x5f\x6d\163\147"] = "\147\x61\x67\141\154\40\x6d\x65\155\142\x75\141\164\x20\156\141\x6d\141\40\x64\141\x74\x61\x62\141\x73\x65"; goto pMPmq; LLZKb: $data["\x68\157\x73\164\137\155\163\x67"] = "\x62\145\x68\141\x73\x69\x6c"; goto SbpKd; p6pWY: $data["\x64\x61\164\141\x62\x61\163\145"] = true; goto JQb4M; AsSyS: $data["\150\x6f\x73\x74"] = false; goto coQZS; M6drN: $database_file = file_get_contents($template_path); goto VSAaF; gnQVC: lEySm: goto W913F; OxH_9: $database = $this->input->post("\x64\x61\164\x61\142\x61\163\145", true); goto cyNvv; SbpKd: $data["\x64\141\164\141\142\x61\x73\x65"] = $this->create_database($hostname, $hostuser, $hostpass, $database); goto oYUo4; WlHw_: $template_path = "\x2e\57\141\163\163\x65\x74\x73\57\141\x70\160\57\x64\x62\57\144\141\x74\141\x62\x61\x73\x65\x2e\x70\150\160"; goto Jyl3n; JQb4M: fvnmh: goto gnQVC; vpVYn: $handle = fopen($output_path, "\167\x2b"); goto rZwvs; XhXvD: $data["\x68\x6f\x73\164"] = false; goto QT323; mYazR: $data["\x68\x6f\x73\164"] = false; goto HQasV; UNH1H: $new = str_replace("\45\x55\123\x45\122\x4e\x41\115\105\x25", $hostuser, $new); goto RNtbN; k2QTW: if (fwrite($handle, $new)) { goto Gyd0X; } goto AsSyS; W913F: A4v3l: goto JSeqn; UIHGg: $data["\x68\157\163\164\x5f\x6d\163\147"] = "\x73\x75\153\163\x65\x73"; goto p6pWY; cyNvv: if ($this->validate_host($hostname, $hostuser, $database)) { goto CYqmQ; } goto mYazR; T87HN: goto A4v3l; goto Zar6B; JSeqn: $this->output_json($data); goto npEkV; DkPP0: Gyd0X: goto C1LZ9; pMPmq: goto fvnmh; goto DkPP0; C_SWf: $hostname = $this->input->post("\150\157\163\x74\156\x61\155\x65", true); goto jWsGy; jWsGy: $hostuser = $this->input->post("\150\x6f\163\x74\165\163\145\162", true); goto OvTFt; rZwvs: @chmod($output_path, 0777); goto gGpHQ; gGpHQ: if (is_writable($output_path)) { goto xn9Sd; } goto XhXvD; VSAaF: $new = str_replace("\45\x48\117\x53\124\116\x41\x4d\105\45", $hostname, $database_file); goto UNH1H; npEkV: } public function createDb() { goto l2H5B; ZTw7U: goto OXrLe; goto s1dAo; HkRZt: $hostpass = $this->input->post("\x68\157\x73\164\x70\141\163\163", true); goto RqTKt; m7OzJ: if ($page == "\x30") { goto FxcnW; } goto sNN1q; SwVI3: $data["\164\x61\x62\x6c\x65"] = $this->create_tables($hostname, $hostuser, $hostpass, $database); goto oj2aU; l2H5B: $page = $this->input->post("\x70\141\x67\x65", true); goto m7OzJ; sNN1q: $data["\150\x6f\x73\x74"] = true; goto zr8xr; UyNJ8: $data["\144\141\164\x61\142\x61\x73\145"] = true; goto bAN8g; s1dAo: FxcnW: goto OrVKz; bAN8g: OXrLe: goto kuk1K; RqTKt: $database = $this->input->post("\x64\x61\x74\141\x62\x61\163\145", true); goto SwVI3; kuk1K: $this->output_json($data); goto K63Xx; zr8xr: $data["\x68\157\x73\164\x5f\x6d\163\147"] = "\163\x74\145\x70\40\163\x61\154\141\x68"; goto Wx58I; Vb_a3: $data["\164\x61\142\154\145"] = false; goto ZTw7U; y34XV: $data["\150\x6f\163\x74\x5f\155\x73\147"] = "\163\165\153\163\145\163"; goto UyNJ8; Wx58I: $data["\144\141\164\x61\x62\x61\163\145"] = false; goto Vb_a3; ax4sU: $hostuser = $this->input->post("\x68\157\163\164\165\163\145\x72", true); goto HkRZt; OrVKz: $hostname = $this->input->post("\150\x6f\x73\164\156\x61\155\145", true); goto ax4sU; oj2aU: $data["\150\157\163\x74"] = true; goto y34XV; K63Xx: } function validate_host($host, $usr, $db) { return !empty($host) && !empty($usr) && !empty($db); } function create_database($hostname, $hostuser, $hostpass, $database) { goto YjUSK; WodWd: return true; goto Eq39i; Mq4TS: return false; goto yROpS; N4NBi: $mysqli->close(); goto WodWd; uY9Cw: if (!mysqli_connect_errno()) { goto AVTSg; } goto Mq4TS; OfTJc: $mysqli->query("\x43\x52\105\101\x54\x45\x20\x44\101\x54\x41\102\101\123\105\40\111\106\40\x4e\x4f\124\40\105\x58\x49\123\124\123\40" . $database); goto N4NBi; YjUSK: $mysqli = new mysqli($hostname, $hostuser, $hostpass, ''); goto uY9Cw; yROpS: AVTSg: goto OfTJc; Eq39i: } function create_tables($hostname, $hostuser, $hostpass, $database) { goto KKDgt; KKDgt: $mysqli = new mysqli($hostname, $hostuser, $hostpass, $database); goto LQ_a6; UqVob: t_Umz: goto Dc2bU; CHp7Q: $mysqli->close(); goto eX6pY; Dc2bU: $query = file_get_contents("\56\x2f\x61\163\x73\145\x74\x73\x2f\141\x70\160\x2f\x64\142\57\155\x61\163\164\145\x72\x2e\x73\161\x6c"); goto O5S4b; eX6pY: return true; goto ye0k8; tJ7TL: return false; goto UqVob; O5S4b: $mysqli->multi_query($query); goto CHp7Q; LQ_a6: if (!mysqli_connect_errno()) { goto t_Umz; } goto tJ7TL; ye0k8: } public function createSetting() { goto fjiJu; ypqV3: $desa = $this->input->post("\144\x65\x73\x61", true); goto hxC8V; fjiJu: $nama_aplikasi = $this->input->post("\156\141\x6d\141\137\x61\x70\x6c\151\153\x61\163\151", true); goto xbh4b; MvD1i: $data["\163\x61\x76\145\144"] = $this->getSaved(); goto ks1_U; EdiVQ: $kec = $this->input->post("\x6b\145\x63", true); goto ypqV3; bajxu: $alamat = $this->input->post("\141\154\x61\x6d\141\164", true); goto nByba; xzcT1: $kepsek = $this->input->post("\153\x65\160\x73\145\153", true); goto bajxu; F77L6: $jenjang = $this->input->post("\152\x65\x6e\x6a\141\x6e\x67", true); goto O35t1; xbh4b: $sekolah = $this->input->post("\x6e\x61\155\141\137\163\x65\153\x6f\x6c\141\150", true); goto F77L6; CanqI: $insert = ["\151\x64\x5f\163\145\164\x74\x69\x6e\x67" => 1, "\163\145\x6b\157\x6c\x61\x68" => $sekolah, "\152\x65\x6e\x6a\x61\x6e\147" => $jenjang, "\163\141\164\165\x61\156\x5f\x70\x65\x6e\x64\151\144\151\153\141\x6e" => $satuan_pendidikan, "\141\154\x61\x6d\141\164" => $alamat, "\144\x65\163\141" => $desa, "\153\157\164\141" => $kota, "\153\145\143\141\x6d\x61\164\141\156" => $kec, "\x74\145\154\x70" => $tlp, "\x6b\x65\160\163\145\153" => $kepsek, "\156\141\155\x61\x5f\x61\x70\154\x69\153\x61\x73\x69" => $nama_aplikasi]; goto Q3Qd5; nByba: $kota = $this->input->post("\x6b\x6f\x74\x61", true); goto EdiVQ; O35t1: $satuan_pendidikan = $this->input->post("\163\x61\164\x75\x61\156\137\160\x65\156\144\151\x64\151\x6b\x61\x6e", true); goto xzcT1; ks1_U: $this->output_json($data); goto wB1sq; Q3Qd5: $data["\151\156\x73\x65\162\164"] = $this->db->insert("\163\145\x74\164\x69\156\147", $insert); goto MvD1i; hxC8V: $tlp = $this->input->post("\x74\x6c\x70", true); goto CanqI; wB1sq: } public function createAdmin() { goto uGslO; uGslO: $nama = $this->input->post("\x6e\141\x6d\x61\137\154\145\x6e\x67\x6b\141\x70", true); goto nTatp; Lb1pF: $first_name = $namaAdmin[0]; goto EBf3P; F9w9n: $group = array("\61"); goto wKbXQ; EBf3P: $last_name = end($namaAdmin); goto nGIOJ; vNsZ1: $password = $this->input->post("\160\x61\x73\163\167\x6f\x72\x64", true); goto y0hrg; o_YsS: $data["\x61\144\x6d\151\x6e"] = $create; goto bC9Tm; vCuqn: $create = $this->ion_auth->register($username, $password, $email, $additional_data, $group); goto o_YsS; wKbXQ: $email = strtolower($nama) . "\100\141\x64\155\151\x6e\56\x63\157\155"; goto vCuqn; y0hrg: $namaAdmin = explode("\x20", $nama); goto Lb1pF; bC9Tm: $this->output_json($data); goto DAlfN; nGIOJ: $additional_data = ["\146\x69\162\163\164\137\x6e\141\x6d\145" => $first_name, "\x6c\x61\163\x74\x5f\156\x61\155\145" => $last_name]; goto F9w9n; nTatp: $username = $this->input->post("\x75\163\x65\x72\156\141\x6d\145", true); goto vNsZ1; DAlfN: } public function createApp() { goto P6gF9; DdEvX: $this->output_json($data); goto kRgD_; OKWkg: $alamat = $this->input->post("\x61\154\x61\155\141\164", true); goto G0NyE; ObNSv: $username = $this->input->post("\165\163\x65\x72\156\x61\x6d\x65", true); goto JXDnI; psXQR: $create = $this->ion_auth->register($username, $password, $email, $additional_data, $group); goto DrwOj; XHLMj: $desa = $this->input->post("\x64\x65\163\141", true); goto eFBXm; JXDnI: $password = $this->input->post("\160\141\x73\x73\x77\157\162\x64", true); goto ljFhz; OPnn5: $jenjang = $this->input->post("\x6a\x65\156\x6a\141\x6e\x67", true); goto Vw1Ka; ljFhz: $nama_aplikasi = $this->input->post("\156\141\155\x61\137\x61\160\x6c\x69\153\x61\x73\151", true); goto l8fNY; uzCzd: $email = strtolower($nama) . "\x40\141\x64\x6d\x69\156\56\143\157\155"; goto psXQR; P6gF9: $nama = $this->input->post("\156\141\155\141\x5f\x6c\x65\156\147\x6b\x61\x70", true); goto ObNSv; xG2IR: $kec = $this->input->post("\x6b\145\x63", true); goto XHLMj; Du1Ih: $kepsek = $this->input->post("\153\x65\160\163\145\153", true); goto OKWkg; eFBXm: $prov = $this->input->post("\x70\162\x6f\x76", true); goto jCEHE; Vw1Ka: $satuan_pendidikan = $this->input->post("\x73\141\x74\165\x61\156", true); goto Du1Ih; l8fNY: $sekolah = $this->input->post("\156\141\155\141\137\x73\x65\153\x6f\154\x61\150", true); goto OPnn5; OVVKZ: $additional_data = ["\x66\151\x72\x73\164\137\156\141\155\145" => $first_name, "\154\141\163\164\x5f\x6e\x61\x6d\x65" => $last_name]; goto qb6G4; Npv7n: $last_name = end($namaAdmin); goto OVVKZ; qb6G4: $group = array("\x31"); goto uzCzd; DOzDf: $namaAdmin = explode("\x20", $nama); goto zIWO6; zIWO6: $first_name = $namaAdmin[0]; goto Npv7n; DrwOj: $data["\151\x6e\x73\x65\x72\164"] = $this->db->insert("\163\145\x74\164\151\x6e\x67", $insert); goto lUZuN; jCEHE: $insert = ["\x69\x64\x5f\163\145\x74\x74\151\x6e\x67" => 1, "\x73\x65\153\157\x6c\x61\x68" => $sekolah, "\152\145\156\x6a\x61\156\x67" => $jenjang, "\163\x61\164\x75\141\156\x5f\160\x65\x6e\144\151\x64\x69\x6b\141\x6e" => $satuan_pendidikan, "\x61\x6c\141\155\x61\x74" => $alamat, "\x64\145\x73\x61" => $desa, "\153\157\x74\141" => $kota, "\153\x65\143\x61\155\141\x74\x61\x6e" => $kec, "\x70\x72\x6f\166\151\156\x73\x69" => $prov, "\x6b\x65\x70\x73\145\x6b" => $kepsek, "\156\x61\155\x61\x5f\141\160\154\151\x6b\x61\x73\151" => $nama_aplikasi]; goto DOzDf; lUZuN: $data["\x61\x64\155\x69\x6e"] = $create; goto DdEvX; G0NyE: $kota = $this->input->post("\153\157\164\x61", true); goto xG2IR; kRgD_: } }
