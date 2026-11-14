<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtnilai extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if ($this->ion_auth->logged_in()) {
            if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('guru')) {
                show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
            }
        } else {
            redirect('auth');
        }
        $this->load->library(['datatables', 'form_validation']);
        $this->load->library('upload');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) {
            $data = json_encode($data);
        }
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
    {
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Cbt_model', 'cbt');
        $this->load->model('Dropdown_model', 'dropdown');
        $this->load->model('Kelas_model', 'kelas');
        $user = $this->ion_auth->user()->row();
        $this->db->trans_start();
        $data = ['user' => $user, 'judul' => 'Hasil Ujian Siswa', 'subjudul' => 'Nilai Siswa', 'setting' => $this->dashboard->getSetting()];
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $data['tp'] = $this->dashboard->getTahun();
        $data['tp_active'] = $tp;
        $data['smt'] = $this->dashboard->getSemester();
        $data['smt_active'] = $smt;
        $data['ruang'] = $this->dropdown->getAllRuang();
        $data['sesi'] = $this->dropdown->getAllSesi();
        $kelas_selected = $this->input->get('kelas');
        $jadwal_selected = $this->input->get('jadwal');
        $data['kelas_selected'] = $kelas_selected;
        $ya = $this->input->get('ya');
        $yb = $this->input->get('yb');
        $xa = $this->input->get('xa');
        $xb = $this->input->get('xb');

        if (!$this->ion_auth->in_group('guru')) {
            $id_guru = null;
        } else {
            $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
            $data['guru'] = $guru;
            $id_guru = $guru->id_guru;
        }

        if ($jadwal_selected != null) {
            $info = $this->cbt->getJadwalById($jadwal_selected);
            $bagi_pg = $info->tampil_pg / 100;
            $bobot_pg = $info->bobot_pg / 100;
            $bagi_pg2 = $info->tampil_kompleks / 100;
            $bobot_pg2 = $info->bobot_kompleks / 100;
            $bagi_jodoh = $info->tampil_jodohkan / 100;
            $bobot_jodoh = $info->bobot_jodohkan / 100;
            $bagi_isian = $info->tampil_isian / 100;
            $bobot_isian = $info->bobot_isian / 100;
            $bagi_essai = $info->tampil_esai / 100;
            $bobot_essai = $info->bobot_esai / 100;

            $siswas = $this->cbt->getSiswaByKelas($tp->id_tp, $smt->id_smt, $kelas_selected);
            $ids = [];
            foreach ($siswas as $key => $value) {
                array_push($ids, $value->id_siswa);
            }
            $jawabans = $this->cbt->getJawabanSiswaByJadwal($jadwal_selected, $ids);
            $soal = [];
            $jawabans_siswa = [];
            foreach ($jawabans as $jawaban_siswa) {
                if ($jawaban_siswa->jenis_soal == '2') {
                    $jawaban_siswa->opsi_a = @unserialize($jawaban_siswa->opsi_a);
                    $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
                    $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
                    $jawaban_siswa->jawaban = @unserialize($jawaban_siswa->jawaban);
                    $jawaban_siswa->jawaban_benar = array_map('strtoupper', $jawaban_siswa->jawaban_benar);
                    $jawaban_siswa->jawaban = array_map('strtoupper', $jawaban_siswa->jawaban);
                    $jawaban_siswa->jawaban_benar = array_filter($jawaban_siswa->jawaban_benar, 'strlen');
                    $jawaban_siswa->jawaban = array_filter($jawaban_siswa->jawaban, 'strlen');
                } elseif ($jawaban_siswa->jenis_soal == '3') {
                    $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
                    $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
                    $jawaban_siswa->jawaban = @unserialize($jawaban_siswa->jawaban);
                    $jawaban_siswa->jawaban_siswa = json_decode(json_encode($jawaban_siswa->jawaban_siswa));
                    $jawaban_siswa->jawaban_benar = json_decode(json_encode($jawaban_siswa->jawaban_benar));
                    $jawaban_siswa->jawaban = json_decode(json_encode($jawaban_siswa->jawaban));
                }
                $jawabans_siswa[$jawaban_siswa->id_siswa][$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
                $soal[$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
            }

            $durasies = $this->cbt->getDurasiSiswaByJadwal($jadwal_selected);
            $logs = $this->cbt->getLogUjianByJadwal($jadwal_selected);

            foreach ($siswas as $siswa) {
                $dur_siswa = '';
                $lamanya = '';
                foreach ($durasies as $durasi) {
                    if ($durasi->id_siswa != $siswa->id_siswa) {
                        continue;
                    }
                    if ($durasi->lama_ujian == null) {
                        $mins = (strtotime($durasi->selesai) - strtotime($durasi->mulai)) / 60;
                        $dur_siswa = round($mins, 2) . ' m';
                    } else {
                        $lamanya = $durasi->lama_ujian;
                        if (strpos($lamanya, ':') !== false) {
                            $elap = explode(':', $lamanya);
                            $ed = $elap[2] == '00' ? 0 : 1;
                            $ej = $elap[0] == '00' ? '' : intval($elap[0]) . 'j ';
                            $em = $elap[1] == '00' ? '' : intval($elap[1]) + $ed . 'm';
                            $dd = $ej . $em;
                            $dur_siswa = $dd == '' ? '0 m' : $dd;
                        } else {
                            $dur_siswa = $durasi->mulai . ' m';
                        }
                    }
                }

                $loading = '';
                $mulai = '- -  :  - -';
                $selesai = '- -  :  - -';
                foreach ($logs as $log) {
                    if ($log->id_siswa != $siswa->id_siswa) {
                        continue;
                    }
                    $sudahMulai = false;
                    $sudahSelesai = false;
                    if ($log->log_type == '1') {
                        if ($log != null) {
                            $mulai = date('H:i', strtotime($log->log_time));
                            $sudahMulai = true;
                        }
                    } else if ($log != null) {
                        $selesai = date('H:i', strtotime($log->log_time));
                        $sudahSelesai = true;
                    }
                    $loading = $sudahSelesai ? '<i class="fa fa-check"></i> ' : ($sudahMulai ? '<i class="fa fa-spinner fa-spin"></i> ' : '');
                }

                $siswa->mulai_ujian = $mulai;
                $siswa->selesai_ujian = $selesai;
                $siswa->lama_ujian = $loading . $dur_siswa;
                $siswa->durasi_ujian = $lamanya;

                $ada_jawaban = isset($jawabans_siswa[$siswa->id_siswa]);
                $ada_jawaban_pg = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['1']);
                $ada_jawaban_pg2 = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['2']);
                $ada_jawaban_jodoh = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['3']);
                $ada_jawaban_isian = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['4']);
                $ada_jawaban_essai = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['5']);

                $arrJawabanPg = [];
                $jawaban_pg = $ada_jawaban_pg ? $jawabans_siswa[$siswa->id_siswa]['1'] : [];
                $benar_pg = 0;
                $skor_pg = 0;
                if ($info->tampil_pg > 0) {
                    if (count($jawaban_pg) > 0) {
                        foreach ($jawaban_pg as $num => $jwb_pg) {
                            $benar = false;
                            if ($jwb_pg != null && $jwb_pg->jawaban_siswa != null) {
                                if (strtoupper($jwb_pg->jawaban_siswa) == strtoupper($jwb_pg->jawaban)) {
                                    $benar = true;
                                    $benar_pg += 1;
                                }
                            }
                            $arrJawabanPg[$num] = ['jawaban' => strtoupper($jwb_pg->jawaban_siswa), 'benar' => $benar];
                        }
                    } else {
                        $n = 0;
                        while ($n < $info->tampil_pg) {
                            $arrJawabanPg[$n + 1] = ['jawaban' => '', 'benar' => false];
                            $n++;
                        }
                    }
                    $skor_pg = $benar_pg / $bagi_pg * $bobot_pg;
                }
                $siswa->jawaban_pg = $arrJawabanPg;
                $siswa->skor_pg = round($skor_pg, 2);

                $nilai_input = $this->cbt->getNilaiSiswaByJadwal($jadwal_selected, $siswa->id_siswa);
                if ($nilai_input != null) {
                    $siswa->dikoreksi = $nilai_input->dikoreksi;
                }

                // PG2
                $jawaban_pg2 = $ada_jawaban_pg2 ? $jawabans_siswa[$siswa->id_siswa]['2'] : [];
                $benar_pg2 = 0;
                $skor_koreksi_pg2 = 0.0;
                $otomatis_pg2 = 0;
                if ($info->tampil_kompleks > 0 && count($jawaban_pg2) > 0) {
                    foreach ($jawaban_pg2 as $num => $jawab_pg2) {
                        $skor_koreksi_pg2 += $jawab_pg2->nilai_koreksi;
                        $arr_benar = [];
                        if ($jawab_pg2->jawaban_siswa) {
                            foreach ($jawab_pg2->jawaban_siswa as $js) {
                                if (in_array($js, $jawab_pg2->jawaban)) {
                                    array_push($arr_benar, true);
                                }
                            }
                        }
                        $point_benar = $info->bobot_kompleks > 0 ? round($info->bobot_kompleks / $info->tampil_kompleks, 2) : 0;
                        $point_item = count($jawab_pg2->jawaban) > 0 ? $point_benar / count($jawab_pg2->jawaban) : 0;
                        $pk = $point_item * count($arr_benar);
                        $point = round($pk, 2);
                        $ks = array_search($jawab_pg2->nomor_soal, array_column($soal[2], 'nomor_soal'));
                        if ($jawab_pg2->nilai_otomatis == '0') {
                            $soal[2][$ks]->point = $point;
                        } else {
                            $soal[2][$ks]->point = $jawab_pg2->nilai_koreksi;
                        }
                        $soal[2][$ks]->point_otomatis = $point;
                        $soal[2][$ks]->point_koreksi = $jawab_pg2->nilai_koreksi;
                        $otomatis_pg2 = $jawab_pg2->nilai_otomatis;
                        if (count($jawab_pg2->jawaban) > 0) {
                            $benar_pg2 += 1 / count($jawab_pg2->jawaban) * count($arr_benar);
                        }
                    }
                }
                $s_pg2 = $bagi_pg2 == 0 ? 0 : $benar_pg2 / $bagi_pg2 * $bobot_pg2;
                $input_pg2 = 0;
                if ($nilai_input != null && $nilai_input->kompleks_nilai != null) {
                    $input_pg2 = $nilai_input->kompleks_nilai;
                }
                $skor_pg2 = $input_pg2 != 0 ? $input_pg2 : ($otomatis_pg2 == 0 ? $s_pg2 : $skor_koreksi_pg2);
                $siswa->skor_kompleks = round($skor_pg2, 2);

                // Jodohkan
                $jawaban_jodoh = $ada_jawaban_jodoh ? $jawabans_siswa[$siswa->id_siswa]['3'] : [];
                $benar_jod = 0;
                $skor_koreksi_jod = 0.0;
                $otomatis_jod = 0;
                if ($info->tampil_jodohkan > 0 && count($jawaban_jodoh) > 0) {
                    foreach ($jawaban_jodoh as $num => $jawab_jod) {
                        $skor_koreksi_jod += $jawab_jod->nilai_koreksi;
                        $typeSoal = $jawab_jod->jawaban->type;
                        $arrSoal = $jawab_jod->jawaban->jawaban;
                        $headSoal = array_shift($arrSoal);
                        $arrJwbSoal = [];
                        $items = 0;
                        foreach ($arrSoal as $kolSoal) {
                            $jwb = new stdClass();
                            foreach ($kolSoal as $pos => $kol) {
                                if ($kol == '1') {
                                    $jwb->subtitle[] = $headSoal[$pos];
                                    $items++;
                                }
                            }
                            $jwb->title = array_shift($kolSoal);
                            array_push($arrJwbSoal, $jwb);
                        }

                        $ks = array_search($jawab_jod->nomor_soal, array_column($soal[3], 'nomor_soal'));
                        $soal[3][$ks]->type_soal = $typeSoal;
                        $soal[3][$ks]->tabel_soal = $arrJwbSoal;

                        $arrJawab = [];
                        if (isset($jawab_jod->jawaban_siswa->jawaban)) {
                            $arrJawab = $jawab_jod->jawaban_siswa->jawaban;
                            $headJawab = array_shift($arrJawab);
                        }
                        $arrJwbJawab = [];
                        foreach ($arrJawab as $kolJawab) {
                            $jwbs = new stdClass();
                            foreach ($kolJawab as $po => $kol) {
                                if ($kol == '1') {
                                    $jwbs->subtitle[] = $headJawab[$po];
                                }
                            }
                            $jwbs->title = array_shift($kolJawab);
                            array_push($arrJwbJawab, $jwbs);
                        }
                        $soal[3][$ks]->tabel_jawab = $arrJwbJawab;

                        $arrBenar = [];
                        $item_benar = 0;
                        $item_salah = 0;
                        $item_lebih = 0;
                        foreach ($arrJwbJawab as $p => $ajjs) {
                            $ll = 0;
                            $bb = 0;
                            $ss = 0;
                            $arrBenar[$p] = new stdClass();
                            if (!isset($ajjs->subtitle)) {
                                continue;
                            }
                            foreach ($ajjs->subtitle as $pp => $ajs) {
                                if (isset($arrJwbSoal[$p]) && !isset($arrJwbSoal[$p]->subtitle[$pp])) {
                                    $ll++;
                                    $arrBenar[$p]->lebih = $ll;
                                    $item_lebih++;
                                } else if (isset($arrJwbSoal[$p]) && isset($arrJwbSoal[$p]->subtitle)) {
                                    if (in_array($ajs, $arrJwbSoal[$p]->subtitle)) {
                                        $bb++;
                                        $arrBenar[$p]->benar = $bb;
                                        $item_benar++;
                                    } else {
                                        $ss++;
                                        $arrBenar[$p]->salah = $ss;
                                        $item_salah++;
                                    }
                                }
                            }
                        }

                        $point_benar = $info->bobot_jodohkan > 0 ? round($info->bobot_jodohkan / $info->tampil_jodohkan, 2) : 0;
                        $point_item = $point_benar / count($arrSoal);
                        $item_kurang = 0;
                        $point_soal = 0;
                        foreach ($arrJwbSoal as $ps => $ajj) {
                            if (!isset($ajj->subtitle)) {
                                continue;
                            }
                            $point_subitem = $point_item / count((array) $ajj->subtitle);
                            if (isset($arrBenar[$ps]->benar)) {
                                $point_soal += $point_subitem * $arrBenar[$ps]->benar;
                            } else {
                                $kk = 0;
                                foreach ($ajj->subtitle as $pps => $aj) {
                                    if (isset($arrJwbJawab[$ps]) && !isset($arrJwbJawab[$ps]->subtitle[$pps])) {
                                        $kk++;
                                    } else {
                                        $arrBenar[$ps]->kurang = $kk;
                                        $item_kurang++;
                                    }
                                }
                            }
                        }
                        $soal[3][$ks]->tabel_benar = $arrBenar;
                        $soal[3][$ks]->point_soal = $point_soal;

                        $point = round($point_soal, 2);
                        if ($jawab_jod->nilai_otomatis == '0') {
                            $soal[3][$ks]->point = $point;
                        } else {
                            $soal[3][$ks]->point = $jawab_jod->nilai_koreksi;
                        }
                        $soal[3][$ks]->point_otomatis = $point;
                        $otomatis_jod = $jawab_jod->nilai_otomatis;
                        $soal[3][$ks]->point_koreksi = $jawab_jod->nilai_koreksi;
                        $benar_jod += 1 / $items * $item_benar;
                    }
                }
                $s_jod = $bagi_jodoh == 0 ? 0 : $benar_jod / $bagi_jodoh * $bobot_jodoh;
                $input_jod = 0;
                if ($nilai_input != null && $nilai_input->jodohkan_nilai != null) {
                    $input_jod = $nilai_input->jodohkan_nilai;
                }
                $skor_jod = $input_jod != 0 ? $input_jod : ($otomatis_jod == 0 ? $s_jod : $skor_koreksi_jod);
                $siswa->skor_jodohkan = round($skor_jod, 2);

                // Isian
                $jawaban_is = $ada_jawaban_isian ? $jawabans_siswa[$siswa->id_siswa]['4'] : [];
                $benar_is = 0;
                $skor_koreksi_is = 0.0;
                $otomatis_is = 0;
                if ($info->tampil_isian > 0 && count($jawaban_is) > 0) {
                    foreach ($jawaban_is as $num => $jawab_is) {
                        $skor_koreksi_is += $jawab_is->nilai_koreksi;
                        $benar = $jawab_is != null && strtolower($jawab_is->jawaban_siswa) == strtolower($jawab_is->jawaban);
                        if ($benar) {
                            $benar_is++;
                        }
                        $ks = array_search($jawab_is->nomor_soal, array_column($soal[4], 'nomor_soal'));
                        $point = !$benar ? 0 : ($info->bobot_isian > 0 ? round($info->bobot_isian / $info->tampil_isian, 2) : 0);
                        if ($jawab_is->nilai_otomatis == '0') {
                            $soal[4][$ks]->point = $point;
                        } else {
                            $soal[4][$ks]->point = $jawab_is->nilai_koreksi;
                        }
                        $soal[4][$ks]->point_otomatis = $point;
                        $soal[4][$ks]->point_koreksi = $jawab_is->nilai_koreksi;
                        $otomatis_is = $jawab_is->nilai_otomatis;
                    }
                }
                $s_is = $bagi_isian == 0 ? 0 : $benar_is / $bagi_isian * $bobot_isian;
                $input_is = 0;
                if ($nilai_input != null && $nilai_input->isian_nilai != null) {
                    $input_is = $nilai_input->isian_nilai;
                }
                $skor_is = $input_is != 0 ? $input_is : ($otomatis_is == 0 ? $s_is : $skor_koreksi_is);
                $siswa->skor_isian = round($skor_is, 2);

                // Essai
                $jawaban_es = $ada_jawaban_essai ? $jawabans_siswa[$siswa->id_siswa]['5'] : [];
                $benar_es = 0;
                $skor_koreksi_es = 0.0;
                $otomatis_es = 0;
                if ($info->tampil_esai > 0 && count($jawaban_es) > 0) {
                    foreach ($jawaban_es as $num => $jawab_es) {
                        $skor_koreksi_es += (int) $jawab_es->nilai_koreksi;
                        $benar = $jawab_es != null && strtolower($jawab_es->jawaban_siswa) == strtolower($jawab_es->jawaban);
                        if ($benar) {
                            $benar_es++;
                        }
                        $ks = array_search($jawab_es->nomor_soal, array_column($soal[5], 'nomor_soal'));
                        $point = !$benar ? 0 : ($info->bobot_esai > 0 ? round($info->bobot_esai / $info->tampil_esai, 2) : 0);
                        if ($jawab_es->nilai_otomatis == '0') {
                            $soal[5][$ks]->point = $point;
                        } else {
                            $soal[5][$ks]->point = $jawab_es->nilai_koreksi;
                        }
                        $soal[5][$ks]->point_otomatis = $point;
                        $soal[5][$ks]->point_koreksi = $jawab_es->nilai_koreksi;
                        $otomatis_es = $jawab_es->nilai_otomatis;
                    }
                }
                $s_es = $bagi_essai == 0 ? 0 : $benar_es / $bagi_essai * $bobot_essai;
                $input_es = 0;
                if ($nilai_input != null && $nilai_input->isian_nilai != null) {
                    $input_es = $nilai_input->essai_nilai;
                }
                $skor_es = $input_es != 0 ? $input_es : ($otomatis_es == 0 ? $s_es : $skor_koreksi_es);
                $siswa->skor_essai = round($skor_es, 2);

                $total = $skor_pg + $skor_pg2 + $skor_jod + $skor_is + $skor_es;
                $siswa->skor_total = round($total, 2);
                if ($ya != null) {
                    if ($total > $xa) {
                        $xa = $total;
                    }
                    if ($total < $xb) {
                        $xb = $total;
                    }
                    $siswa->skor_katrol = round(($ya - $yb) / 100 * $total + $yb, 2);
                } else {
                    $siswa->skor_katrol = '';
                }
            }

            $data['info'] = $info;
            $data['siswas'] = $siswas;
            if ($ya != null) {
                $data['convert'] = ['ya' => $ya, 'yb' => $yb, 'xa' => $xa, 'xb' => $xb];
            }
            $kelas_bank = unserialize($info->bank_kelas);
            $kelases = [];
            foreach ($kelas_bank as $key => $value) {
                if ($value['kelas_id'] != '') {
                    $kelases[$value['kelas_id']] = $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $value['kelas_id']);
                }
            }
            $jadwals = $this->cbt->getAllJadwal($tp->id_tp, $smt->id_smt, $id_guru);
            $jdwl = [];
            foreach ($jadwals as $jadwal) {
                $kls = unserialize($jadwal->bank_kelas);
                foreach ($kls as $kl) {
                    if ($kl['kelas_id'] == $kelas_selected) {
                        $jdwl[$jadwal->id_jadwal] = $jadwal->bank_kode;
                    }
                }
            }
            $data['jadwal'] = $jdwl;
        } else {
            $data['jadwal'] = [];
            $data['siswas'] = [];
        }

        $this->db->trans_complete();

        if (!$this->ion_auth->is_admin()) {
            $mapel_guru = $this->kelas->getGuruMapelKelas($id_guru, $tp->id_tp, $smt->id_smt);
            $mapel = json_decode(json_encode(unserialize($mapel_guru->mapel_kelas)));
            $data['jadwal_selected'] = $jadwal_selected;
            $arrKelas = [];
            if ($mapel != null) {
                foreach ($mapel as $m) {
                    $arrMapel[$m->id_mapel] = $m->nama_mapel;
                    foreach ($m->kelas_mapel as $kls) {
                        if (!$kls->kelas) {
                            continue;
                        }
                        $arrKelas[$kls->kelas] = $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $kls->kelas);
                    }
                }
            }
            $data['kelas'] = $arrKelas;
            $this->load->view('members/guru/templates/header', $data);
            $this->load->view('cbt/nilai/data');
            $this->load->view('members/guru/templates/footer');
        } else {
            $data['profile'] = $this->dashboard->getProfileAdmin($user->id);
            $data['jadwal_selected'] = $jadwal_selected;
            $data['kelas'] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
            $this->load->view('_templates/dashboard/_header', $data);
            $this->load->view('cbt/nilai/data');
            $this->load->view('_templates/dashboard/_footer');
        }
    }
    public function detail() {
        goto bQu8h;
        D0gxj:
        TRtlg:
        goto dd70t;
        sclkD:
        $otomatis_jod = 0;
        goto kqfVe;
        sBzyb:
        $input_es = $nilai_input->essai_nilai;
        goto MrC1E;
        XQMHV:
        $bagi_essai = $info->tampil_esai / 100;
        goto LfVCC;
        MrC1E:
        GTBMm:
        goto C7One;
        Wc63o:
        foreach ($logs as $log) {
            if ($log->id_siswa == $siswa->id_siswa) {
                goto OqTfu;
                MHKHc:
                goto LlA77;
                OqTfu:
                array_push($log_siswa, $log);
            }
            goto MHKHc;
            LlA77:
        }
        goto zWPoG;
        OSRmj:
        goto PlqnX;
        Ry6pB:
        nnXY2:
        goto FGAGf;
        WRUId:
        goto dbCw3;
        bm9iZ:
        $log_siswa = [];
        goto Wc63o;
        Rb9H6:
        $input_jod = 0;
        goto Cr6qd;
        t02mw:
        goto u0_fE;
        goto D0gxj;
        BTddI:
        AMmeu:
        goto Qy1g8;
        Ocwvg:
        $data['soal'] = $soal;
        goto R8xNa;
        FMPmo:
        v03Mq:
        goto BTddI;
        w9NPQ:
        $skor->skor_isian = $skor_is;
        goto ZrSEP;
        I9oW3:
        $jawaban_jodoh = $ada_jawaban_jodoh ? $jawabans_siswa[$siswa->id_siswa]['3'] : [];
        goto YJ5Qw;
        Nmj74:
        $jawaban_pg2 = $ada_jawaban_pg2 ? $jawabans_siswa[$siswa->id_siswa]['2'] : [];
        goto b3l92;
        Qy1g8:
        $s_jod = $bagi_jodoh == 0 ? 0 : $benar_jod / $bagi_jodoh * $bobot_jodoh;
        goto Rb9H6;
        rqih5:
        ofmJN:
        goto KF4UU;
        yGx9h:
        $info = $this->cbt->getJadwalById($jadwal);
        goto Enxyr;
        Bx_Ah:
        $skor->dikoreksi = $nilai_input->dikoreksi;
        goto sa1mZ;
        T0A4B:
        $skor_koreksi_is = 0.0;
        goto TXBvg;
        qQIOa:
        $ada_jawaban_isian = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['4']);
        goto n7ceq;
        ooUr0:
        $input_is = 0;
        goto soxrg;
        yo0zD:
        $data['siswa'] = $siswa;
        goto Ocwvg;
        FGAGf:
        mYdLV:
        goto Fqij1;
        KF4UU:
        BFddT:
        goto xOVs5;
        mmi0w:
        $s_pg2 = $bagi_pg2 == 0 ? 0 : $benar_pg2 / $bagi_pg2 * $bobot_pg2;
        goto ui75N;
        b3l92:
        $benar_pg2 = 0;
        goto uz_zG;
        oxUQf:
        Kny4M:
        goto e_1eb;
        SeDap:
        $nilai_input = $this->cbt->getNilaiSiswaByJadwal($jadwal, $siswa->id_siswa);
        goto H4X6T;
        TCGKR:
        foreach ($jawabans as $jawaban_siswa) {
            goto Gmdp1;
            o8U06:
            $jawaban_siswa->jawaban_siswa = json_decode(json_encode($jawaban_siswa->jawaban_siswa));
            goto uNuzP;
            rovzM:
            $jawaban_siswa->jawaban = @unserialize($jawaban_siswa->jawaban);
            $jawaban_siswa->jawaban_benar = array_map('strtoupper', $jawaban_siswa->jawaban_benar);
            goto R0K2X;
            QRZG_:
            $jawaban_siswa->jawaban = json_decode(json_encode($jawaban_siswa->jawaban));
            goto jgIZq;
            r_QrP:
            $jawaban_siswa->jawaban = @unserialize($jawaban_siswa->jawaban);
            goto o8U06;
            NEI_W:
            $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
            goto r_QrP;
            iU3S3:
            $jawabans_siswa[$jawaban_siswa->id_siswa][$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
            goto Vwibj;
            tEfXZ:
            $jawaban_siswa->jawaban = array_map('strtoupper', $jawaban_siswa->jawaban);
            goto GHcwt;
            GLFCf:
            n2x2h:
            goto EehpV;
            uNuzP:
            $jawaban_siswa->jawaban_benar = json_decode(json_encode($jawaban_siswa->jawaban_benar));
            goto QRZG_;
            naOZt:
            $jawaban_siswa->opsi_a = @unserialize($jawaban_siswa->opsi_a);
            goto KLFWk;
            pZiFX:
            $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
            goto NEI_W;
            Vwibj:
            $soal[$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
            goto Pt5eA;
            C9HzU:
            $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
            goto rovzM;
            Pt5eA:
            goto XCsLL;
            KLFWk:
            $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
            goto C9HzU;
            EehpV:
            if ($jawaban_siswa->jenis_soal == '3') {
                goto pZiFX;
                GHcwt:
                $jawaban_siswa->jawaban = array_filter($jawaban_siswa->jawaban, 'strlen');
                goto GLFCf;
                R0K2X:
                $jawaban_siswa->jawaban_benar = array_filter($jawaban_siswa->jawaban_benar, 'strlen');
                goto tEfXZ;
                Gmdp1:
                if (!($jawaban_siswa->jenis_soal == '2')) {
                    goto n2x2h;
                }
                goto naOZt;
                jgIZq:
            }
            goto iU3S3;
            XCsLL:
        }
        goto OSRmj;
        Cr6qd:
        if ($nilai_input != null && $nilai_input->jodohkan_nilai != null) {
            goto b4tEG;
            yjp0r:
            $input_is = $nilai_input->isian_nilai;
            goto oxUQf;
            MvX0D:
            if (!(count($jawaban_es) > 0)) {
                goto ldtoR;
            }
            goto z5bsN;
            PgFiP:
            $bagi_isian = $info->tampil_isian / 100;
            goto UPItr;
            M62qP:
            $this->load->model('Dashboard_model', 'dashboard');
            goto kQNps;
            lPiaX:
            $bobot_pg = $info->bobot_pg / 100;
            goto bQqBl;
            pA4Iu:
            $data['info'] = $info;
            goto yo0zD;
            C7One:
            $skor_es = $input_es != 0 ? $input_es : ($otomatis_es == 0 ? $s_es : $skor_koreksi_es);
            goto VJQZi;
            PlqnX:
            $ada_jawaban = isset($jawabans_siswa[$siswa->id_siswa]);
            goto EwvMH;
            kufkG:
            goto wuVSj;
            qrXps:
            $data['ada_nilai'] = $nilai_siswa != null;
            goto pg0D_;
            q1R1d:
            $logs = $this->cbt->getLogUjianByJadwal($jadwal);
            goto CvCS6;
            Ia4ZU:
            $bobot_jodoh = $info->bobot_jodohkan / 100;
            goto PgFiP;
            n7ceq:
            $ada_jawaban_essai = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['5']);
            goto E2hJb;
            Lv1In:
            $data['tp_active'] = $tp;
            goto djhOu;
            Z3LhA:
            $data = ['user'=>$user, 'judul'=>'Koreksi Hasil Siswa', 'subjudul'=>'Hasil Siswa', 'setting'=>$this->dashboard->getSetting(), 'durasi'=>$dur_siswa, 'log'=>$log_siswa];
            goto p6vdM;
            djhOu:
            $data['smt'] = $this->dashboard->getSemester();
            goto L5YjQ;
            SPY7E:
            $this->load->view('cbt/nilai/detail');
            goto mNFTr;
            ZrSEP:
            $jawaban_es = $ada_jawaban_essai ? $jawabans_siswa[$siswa->id_siswa]['5'] : [];
            goto vpbtM;
            ZNApE:
            u0_fE:
            goto SLJ70;
            mMbqO:
            if (count($jawaban_pg2) > 0) {
                goto HMx2K;
                KLEFO:
                if (!(count($jawaban_pg) > 0)) {
                    goto ofmJN;
                }
                goto YY24f;
                XHiw5:
                foreach ($durasies as $durasi) {
                    goto zRFor;
                    UIS3j:
                    M5pMO:
                    goto I4Zhr;
                    AQb7h:
                    $dur_siswa = $durasi;
                    goto UIS3j;
                    zRFor:
                    if (!($durasi->id_siswa == $siswa->id_siswa)) {
                        goto M5pMO;
                    }
                    goto AQb7h;
                    I4Zhr:
                }
                goto aWRLy;
                WHsTc:
                $input_pg2 = $nilai_input->kompleks_nilai;
                goto WG1Fm;
                ryh38:
                $smt = $this->dashboard->getSemesterActive();
                goto yZ1T1;
                sa1mZ:
                SQLbZ:
                goto Nd1aF;
                srxEh:
                $ada_jawaban_pg2 = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['2']);
                goto TbsNm;
                N2IOw:
                foreach ($jawaban_jodoh as $num=>$jawab_jod) {
                    goto ygAym;
                    SRLLo:
                    $point_item = $point_benar / count($arrSoal);
                    goto FlL1S;
                    m_AQO:
                    foreach ($arrSoal as $kolSoal) {
                        goto kgTRR;
                        sSuAm:
                        goto T3s1Z;
                        OX553:
                        $jwb->title = array_shift($kolSoal);
                        goto krqNu;
                        kgTRR:
                        $jwb = new stdClass();
                        foreach ($kolSoal as $pos=>$kol) {
                            goto aeLTR;
                            eaymp:
                            xT45j:
                            goto y_Y3C;
                            LjXt4:
                            $jwb->subtitle[] = $headSoal[$pos];
                            goto kU40P;
                            aeLTR:
                            if (!($kol == '1')) {
                                goto xT45j;
                            }
                            goto LjXt4;
                            kU40P:
                            $items++;
                            goto eaymp;
                            y_Y3C:
                        }
                        goto OX553;
                        krqNu:
                        array_push($arrJwbSoal, $jwb);
                        goto sSuAm;
                        T3s1Z:
                    }
                    goto Myp8D;
                    P_iCi:
                    $soal[3][$ks]->point = $jawab_jod->nilai_koreksi;
                    goto gnsXc;
                    tpxdf:
                    $arrSoal = $jawab_jod->jawaban->jawaban;
                    goto nw55_;
                    yib_w:
                    $soal[3][$ks]->point_koreksi = $jawab_jod->nilai_koreksi;
                    goto X_eTA;
                    gnsXc:
                    goto XocGI;
                    goto WyxFn;
                    HwKiB:
                    $otomatis_jod = $jawab_jod->nilai_otomatis;
                    goto Rt3YY;
                    nw55_:
                    $headSoal = array_shift($arrSoal);
                    goto EMSAr;
                    ItGMc:
                    $soal[3][$ks]->tabel_soal = $arrJwbSoal;
                    goto aEiRo;
                    YEiPX:
                    $soal[3][$ks]->point_soal = $point_soal;
                    goto YyDp3;
                    ajGv_:
                    iuTmp:
                    goto YoxMt;
                    QUHDE:
                    goto XtaKu;
                    goto ajGv_;
                    PP1AA:
                    foreach ($arrJwbJawab as $p=>$ajjs) {
                        goto a5GtH;
                        Qe2NB:
                        $arrBenar[$p] = new stdClass();
                        goto rOuCY;
                        GzHW2:
                        $bb = 0;
                        goto aW3XN;
                        mito0:
                        guZxS:
                        goto Canf6;
                        aW3XN:
                        $ss = 0;
                        goto Qe2NB;
                        uKs5a:
                        goto mito0;
                        rOuCY:
                        if (!isset($ajjs->subtitle)) {
                            goto guZxS;
                        }
                        foreach ($ajjs->subtitle as $pp=>$ajs) {
                            goto haZie;
                            LdaRL:
                            $bb++;
                            goto ZGstZ;
                            VE7xY:
                            $item_salah++;
                            goto r9AsF;
                            BQcDb:
                            $item_lebih++;
                            goto L7vXH;
                            K2xS4:
                            if (!(in_array($ajs, $arrJwbSoal[$p]->subtitle))) {
                                goto Cvypk;
                                ZGstZ:
                                $arrBenar[$p]->benar = $bb;
                                goto adorc;
                                Cvypk:
                                $ss++;
                                goto WsKSC;
                                ceZSX:
                                gCxGR:
                                goto EFUo7;
                                ri87L:
                            }
                            goto LdaRL;
                            UbXuV:
                            if (!(isset($arrJwbSoal[$p]) && isset($arrJwbSoal[$p]->subtitle))) {
                                goto gCxGR;
                            }
                            goto K2xS4;
                            haZie:
                            if (isset($arrJwbSoal[$p]) && !isset($arrJwbSoal[$p]->subtitle[$pp])) {
                                goto LA200;
                                CSJch:
                                $arrBenar[$p]->lebih = $ll;
                                goto BQcDb;
                                r9AsF:
                                goto mUD_i;
                                goto ri87L;
                                xpYyx:
                                mUD_i:
                                goto ceZSX;
                                L7vXH:
                            }
                            goto UbXuV;
                            adorc:
                            $item_benar++;
                            goto xpYyx;
                            LA200:
                            $ll++;
                            goto CSJch;
                            WsKSC:
                            $arrBenar[$p]->salah = $ss;
                            goto VE7xY;
                            EFUo7:
                        }
                        goto uKs5a;
                        a5GtH:
                        $ll = 0;
                        goto GzHW2;
                        Canf6:
                    }
                    goto Singg;
                    jyOCO:
                    XocGI:
                    goto yib_w;
                    Q8GYR:
                    $analisa = '<i class="fa fa-times-circle text-yellow text-lg"></i>';
                    goto QUHDE;
                    aEiRo:
                    $arrJawab = [];
                    goto UYWh4;
                    YoxMt:
                    $analisa = '<i class="fa fa-check-circle text-green text-lg"></i>';
                    goto OpqbK;
                    YIIAt:
                    $arrJawab = $jawab_jod->jawaban_siswa->jawaban;
                    goto IgmN_;
                    ygAym:
                    $skor_koreksi_jod += $jawab_jod->nilai_koreksi;
                    goto MLVZL;
                    X_eTA:
                    $soal[3][$ks]->point_otomatis = $point;
                    goto uwvkV;
                    FlL1S:
                    $item_kurang = 0;
                    goto Qlv6G;
                    m9Axy:
                    goto iJfAx;
                    Bf5Nb:
                    $item_lebih = 0;
                    goto PP1AA;
                    hwqln:
                    if (!($jawab_jod->nilai_otomatis == '0')) {
                        goto P_iCi;
                        HVamv:
                        $analisa = '<i class="fa fa-times-circle text-red text-lg"></i>';
                        goto PIgNQ;
                        L4moF:
                        $item_salah = 0;
                        goto Bf5Nb;
                        uwvkV:
                        if ($item_benar == $items && $item_salah == 0 && $item_lebih == 0 && $item_kurang == 0) {
                            goto iuTmp;
                        }
                        goto wHtYj;
                        OC0TL:
                        $arrBenar = [];
                        goto xulrT;
                        Singg:
                        goto vm2xZ;
                        mNOx8:
                        $items = 0;
                        goto m_AQO;
                        iJfAx:
                        $soal[3][$ks]->tabel_jawab = $arrJwbJawab;
                        goto OC0TL;
                        ApP_r:
                        $soal[3][$ks]->analisa = $analisa;
                        goto HwKiB;
                        wHtYj:
                        if (!($item_benar == 0)) {
                            goto Q8GYR;
                            vm2xZ:
                            $benar_jod += 1 / $items * $item_benar;
                            goto UwnrZ;
                            PIgNQ:
                            XtaKu:
                            goto ApP_r;
                            YyDp3:
                            $point = round($point_soal, 2);
                            goto hwqln;
                            mFvP1:
                            foreach ($arrJawab as $kolJawab) {
                                goto M25_m;
                                aaCB1:
                                goto D5mhs;
                                warSg:
                                goto R8pjZ;
                                BhT43:
                                array_push($arrJwbJawab, $jwbs);
                                goto warSg;
                                D5mhs:
                                $jwbs->title = array_shift($kolJawab);
                                goto BhT43;
                                M25_m:
                                $jwbs = new stdClass();
                                foreach ($kolJawab as $po=>$kol) {
                                    goto GEsO_;
                                    zJPc9:
                                    MbHQb:
                                    goto mU7up;
                                    IEV1b:
                                    $sub = $headJawab[$po];
                                    goto jxpBr;
                                    GEsO_:
                                    if (!($kol == '1')) {
                                        goto MbHQb;
                                    }
                                    goto IEV1b;
                                    mU7up:
                                    goto c18lu;
                                    jxpBr:
                                    $jwbs->subtitle[] = $sub;
                                    goto zJPc9;
                                    c18lu:
                                }
                                goto aaCB1;
                                R8pjZ:
                            }
                            goto m9Axy;
                            MLVZL:
                            $typeSoal = $jawab_jod->jawaban->type;
                            goto tpxdf;
                            Rt3YY:
                            goto rttwZ;
                            Mu7NO:
                            $ks = array_search($jawab_jod->nomor_soal, array_column($soal[3], 'nomor_soal'));
                            goto v0a76;
                            F5Dsr:
                            foreach ($arrJwbSoal as $ps=>$ajj) {
                                goto Mzw3w;
                                xQf_y:
                                goto nV_3M;
                                i_73b:
                                zDBzy:
                                goto f9r_d;
                                UsxQc:
                                $point_soal += $point_subitem * $arrBenar[$ps]->benar;
                                goto i_73b;
                                EVv6V:
                                goto XJjON;
                                UV2G6:
                                foreach ($ajj->subtitle as $pps=>$aj) {
                                    goto U78GK;
                                    si9Om:
                                    $kk++;
                                    goto d3gDl;
                                    ijzGs:
                                    $item_kurang++;
                                    goto ehj6f;
                                    U78GK:
                                    if (isset($arrJwbJawab[$ps]) && !isset($arrJwbJawab[$ps]->subtitle[$pps])) {
                                        goto si9Om;
                                        glF6T:
                                        goto mAd3d;
                                        ehj6f:
                                    }
                                    goto glF6T;
                                    d3gDl:
                                    $arrBenar[$ps]->kurang = $kk;
                                    goto ijzGs;
                                    mAd3d:
                                }
                                goto EVv6V;
                                ahGVZ:
                                if (!isset($arrBenar[$ps]->benar)) {
                                    goto zDBzy;
                                }
                                goto UsxQc;
                                f9r_d:
                                $kk = 0;
                                goto UV2G6;
                                ULM2a:
                                $point_subitem = $point_item / count((array)$ajj->subtitle);
                                goto ahGVZ;
                                Mzw3w:
                                if (isset($ajj->subtitle)) {
                                    goto ULM2a;
                                    XJjON:
                                }
                                goto xQf_y;
                                nV_3M:
                            }
                            goto UA2Cs;
                            xMuyH:
                            $arrJwbJawab = [];
                            goto mFvP1;
                            Bjdan:
                        }
                        goto HVamv;
                        OpqbK:
                        goto XtaKu;
                        goto Bjdan;
                        UA2Cs:
                        goto ZSyJw;
                        UwnrZ:
                        $point_benar = $info->bobot_jodohkan > 0 ? round($info->bobot_jodohkan / $info->tampil_jodohkan, 2) : 0;
                        goto SRLLo;
                        WyxFn:
                    }
                    goto SHvS0;
                    Qlv6G:
                    $point_soal = 0;
                    goto F5Dsr;
                    EMSAr:
                    $arrJwbSoal = [];
                    goto mNOx8;
                    Myp8D:
                    goto Mu7NO;
                    xulrT:
                    $item_benar = 0;
                    goto L4moF;
                    v0a76:
                    $soal[3][$ks]->type_soal = $typeSoal;
                    goto ItGMc;
                    ZSyJw:
                    $soal[3][$ks]->tabel_benar = $arrBenar;
                    goto YEiPX;
                    IgmN_:
                    $headJawab = array_shift($arrJawab);
                    goto tvq4e;
                    UYWh4:
                    if (isset($jawab_jod->jawaban_siswa->jawaban)) {
                        goto YIIAt;
                        tvq4e:
                    }
                    goto xMuyH;
                    SHvS0:
                    $soal[3][$ks]->point = $point;
                    goto jyOCO;
                    rttwZ:
                }
                goto Txagr;
                TzN2D:
                $data['guru'] = $guru;
                goto FpV9T;
                kqfVe:
                if (!($info->tampil_jodohkan > 0)) {
                    goto AMmeu;
                }
                goto ncnW5;
                TXBvg:
                $otomatis_is = 0;
                goto qDFOa;
                FEzyT:
                Qbt_e:
                goto mmi0w;
                WC6wH:
                $skor_koreksi_jod = 0.0;
                goto sclkD;
                kQNps:
                $tp = $this->dashboard->getTahunActive();
                goto ryh38;
                bQu8h:
                $this->load->model('Cbt_model', 'cbt');
                goto M62qP;
                Enxyr:
                $bagi_pg = $info->tampil_pg / 100;
                goto lPiaX;
                VJQZi:
                $skor->skor_essai = $skor_es;
                goto yNBKS;
                pHggg:
                $user = $this->ion_auth->user()->row();
                goto Z3LhA;
                YY24f:
                foreach ($jawaban_pg as $num=>$jwb_pg) {
                    goto pHOeR;
                    D27sM:
                    goto wPTnP;
                    goto vrj13;
                    H9GrM:
                    if ($jwb_pg != null && $jwb_pg->jawaban_siswa != null) {
                        goto XzW2p;
                        TFQw4:
                        $soal[1][$ks]->point = !$benar ? 0 : ($info->bobot_pg > 0 ? round($info->bobot_pg / $info->tampil_pg, 2) : 0);
                        goto allqD;
                        Rz0vF:
                        $benar = false;
                        goto D27sM;
                        QDAyh:
                        $salah_pg += 1;
                        goto Rz0vF;
                        allqD:
                        $analisa = $benar ? '<i class="fa fa-check-circle text-green text-lg"></i>' : '<i class="fa fa-times-circle text-red text-lg"></i>';
                        goto ywhz6;
                        vrj13:
                        t4Kit:
                        goto XKGcj;
                        RGHKg:
                        $ks = array_search($jwb_pg->nomor_soal, array_column($soal[1], 'nomor_soal'));
                        goto TFQw4;
                        XKGcj:
                        $benar_pg += 1;
                        goto gVUr1;
                        ywhz6:
                        $soal[1][$ks]->analisa = $analisa;
                        goto UThuw;
                        pHOeR:
                        $benar = false;
                        goto H9GrM;
                        gVUr1:
                        $benar = true;
                        goto oD8PB;
                        UThuw:
                        goto kwKKJ;
                        ezGkF:
                    }
                    goto RGHKg;
                    oD8PB:
                    wPTnP:
                    goto ezGkF;
                    XzW2p:
                    if (strtoupper($jwb_pg->jawaban_siswa) == strtoupper($jwb_pg->jawaban)) {
                        goto t4Kit;
                    }
                    goto QDAyh;
                    kwKKJ:
                }
                goto xge6H;
                R8xNa:
                $data['skor'] = $skor;
                goto T2YRD;
                fiAg2:
                $skor_pg2 = $input_pg2 != 0 ? $input_pg2 : ($otomatis_pg2 == 0 ? $s_pg2 : $skor_koreksi_pg2);
                goto B1ktV;
                ncnW5:
                if (!(count($jawaban_jodoh) > 0)) {
                    goto v03Mq;
                }
                goto N2IOw;
                mNFTr:
                $this->load->view('_templates/dashboard/_footer');
                goto ZNApE;
                soxrg:
                if (!($nilai_input != null && $nilai_input->isian_nilai != null)) {
                    goto Kny4M;
                }
                goto yjp0r;
                qdR88:
                $skor_jod = $input_jod != 0 ? $input_jod : ($otomatis_jod == 0 ? $s_jod : $skor_koreksi_jod);
                goto r90JZ;
                V0zxP:
                $jadwal = $this->input->get('jadwal');
                goto yGx9h;
                xj7ct:
                $s_es = $bagi_essai == 0 ? 0 : $benar_es / $bagi_essai * $bobot_essai;
                goto cT2wY;
                nVhBx:
                $durasies = $this->cbt->getDurasiSiswaByJadwal($jadwal);
                goto q1R1d;
                aWRLy:
                goto bm9iZ;
                vpV8N:
                if (!($nilai_input != null && $nilai_input->isian_nilai != null)) {
                    goto GTBMm;
                }
                goto sBzyb;
                fa1TT:
                $jawabans_siswa = [];
                goto TCGKR;
                UPItr:
                $bobot_isian = $info->bobot_isian / 100;
                goto XQMHV;
                HMMaP:
                if ($info->tampil_esai > 0) {
                    goto MvX0D;
                    HJvT5:
                    $this->load->view('_templates/dashboard/_header', $data);
                    goto SPY7E;
                    yNBKS:
                    $total = $skor_pg + $skor_pg2 + $skor_jod + $skor_is + $skor_es;
                    goto Sj_fR;
                    hqed1:
                    $skor_koreksi_es = 0.0;
                    goto kWhKd;
                    Sj_fR:
                    $skor->skor_total = $total;
                    goto nVhBx;
                    uz_zG:
                    $skor_koreksi_pg2 = 0.0;
                    goto KOaII;
                    Qwdoa:
                    $jawabans = $this->cbt->getJawabanSiswaByJadwal($jadwal, $siswa->id_siswa);
                    goto UKoV_;
                    E2hJb:
                    $skor = new stdClass();
                    goto SeDap;
                    UKoV_:
                    $soal = [];
                    goto fa1TT;
                    BAF0v:
                    $this->load->view('members/guru/templates/footer');
                    goto t02mw;
                    b4tEG:
                    $input_jod = $nilai_input->jodohkan_nilai;
                    goto s1_0c;
                    e_1eb:
                    $skor_is = $input_is != 0 ? $input_is : ($otomatis_is == 0 ? $s_is : $skor_koreksi_is);
                    goto w9NPQ;
                    XgX6S:
                    $salah_pg = 0;
                    goto Ogfl0;
                    a8XFk:
                    $benar_pg = 0;
                    goto XgX6S;
                    Fqij1:
                    $s_is = $bagi_isian == 0 ? 0 : $benar_is / $bagi_isian * $bobot_isian;
                    goto ooUr0;
                    f5FlJ:
                    if (!($nilai_input != null && $nilai_input->kompleks_nilai != null)) {
                        goto ZL8i0;
                    }
                    goto WHsTc;
                    z5bsN:
                    foreach ($jawaban_es as $num=>$jawab_es) {
                        goto O7nrU;
                        PXPlg:
                        XsNdZ:
                        goto Pnnwb;
                        sxYwT:
                        $analisa = '<i class="fa fa-check-circle text-green text-lg"></i>';
                        goto PXPlg;
                        Pnnwb:
                        $soal[5][$ks]->analisa = $analisa;
                        goto MK16K;
                        AXjSz:
                        goto BjvIH;
                        MK16K:
                        $otomatis_es = $jawab_es->nilai_otomatis;
                        goto AXjSz;
                        tME8O:
                        V1WdR:
                        goto YFYtt;
                        opCeX:
                        d5IrZ:
                        goto e1vZ2;
                        Up1kc:
                        goto V1WdR;
                        goto opCeX;
                        gl53Z:
                        $analisa = '<i class="fa fa-times-circle text-yellow text-lg"></i>';
                        goto Uxzuv;
                        qRlk_:
                        $point = !$benar ? 0 : ($info->bobot_esai > 0 ? round($info->bobot_esai / $info->tampil_esai, 2) : 0);
                        goto Ko7as;
                        f_BSD:
                        $benar = $jawab_es != null && strtolower($jawab_es->jawaban_siswa) == strtolower($jawab_es->jawaban);
                        if ($benar) {
                            goto s1GH9;
                            O7nrU:
                            $skor_koreksi_es += $jawab_es->nilai_koreksi;
                            goto f_BSD;
                            vB2k0:
                            $ks = array_search($jawab_es->nomor_soal, array_column($soal[5], 'nomor_soal'));
                            goto qRlk_;
                            VIKJo:
                        }
                        goto vB2k0;
                        Ko7as:
                        if ($jawab_es->nilai_otomatis == '0') {
                            goto d5IrZ;
                        }
                        $soal[5][$ks]->point = $jawab_es->nilai_koreksi;
                        goto Up1kc;
                        dBwJn:
                        $soal[5][$ks]->point_otomatis = $point;
                        goto haEtF;
                        YFYtt:
                        $soal[5][$ks]->point_koreksi = $jawab_es->nilai_koreksi;
                        goto dBwJn;
                        haEtF:
                        if (!($benar)) {
                            goto gl53Z;
                            s1GH9:
                            $benar_es++;
                            goto VIKJo;
                            Uxzuv:
                            goto XsNdZ;
                        }
                        goto sxYwT;
                        e1vZ2:
                        $soal[5][$ks]->point = $point;
                        goto tME8O;
                        BjvIH:
                    }
                    goto kufkG;
                    YJ5Qw:
                    $benar_jod = 0;
                    goto WC6wH;
                    KvbO9:
                    $benar_is = 0;
                    goto T0A4B;
                    zWPoG:
                    goto pHggg;
                    T2YRD:
                    $nilai_siswa = $this->cbt->getNilaiSiswaByJadwal($jadwal, $siswa->id_siswa);
                    goto qrXps;
                    xge6H:
                    goto rqih5;
                    vpbtM:
                    $benar_es = 0;
                    goto hqed1;
                    Ogfl0:
                    if (!($info->tampil_pg > 0)) {
                        goto BFddT;
                    }
                    goto KLEFO;
                    dd70t:
                    $data['profile'] = $this->dashboard->getProfileAdmin($user->id);
                    goto HJvT5;
                    qDFOa:
                    if (!($info->tampil_isian > 0)) {
                        goto mYdLV;
                    }
                    goto bgUHY;
                    cT2wY:
                    $input_es = 0;
                    goto vpV8N;
                    Nd1aF:
                    $jawaban_pg = $ada_jawaban_pg ? $jawabans_siswa[$siswa->id_siswa]['1'] : [];
                    goto a8XFk;
                    nE7Ud:
                }
                goto xj7ct;
                LfVCC:
                $bobot_essai = $info->bobot_esai / 100;
                goto Qwdoa;
                H4X6T:
                if (!($nilai_input != null)) {
                    goto SQLbZ;
                }
                goto Bx_Ah;
                xOVs5:
                $skor->skor_pg = $skor_pg = $bagi_pg == 0 ? 0 : $benar_pg / $bagi_pg * $bobot_pg;
                goto Nmj74;
                HMx2K:
                foreach ($jawaban_pg2 as $num=>$jawab_pg2) {
                    goto J5mMv;
                    fm6ZM:
                    if ($jawab_pg2->nilai_otomatis == '0') {
                        goto Q_Oc8;
                    }
                    goto f036k;
                    wqRwj:
                    if ($jawab_pg2->jawaban_siswa) {
                        goto NILf4;
                        N1ILI:
                        AzeiO:
                        goto FBt_6;
                        HlT31:
                        goto D_OSX;
                        pJqe_:
                        goto s01P3;
                        goto nmEF6;
                        NILf4:
                        foreach ($jawab_pg2->jawaban_siswa as $js) {
                            goto VgkCQ;
                            l33qs:
                            sQbg2:
                            goto dozBj;
                            VgkCQ:
                            if (!in_array($js, $jawab_pg2->jawaban)) {
                                goto sQbg2;
                            }
                            array_push($arr_benar, true);
                            goto l33qs;
                            dozBj:
                        }
                        goto BFpP5;
                        xmXoK:
                        $jml_benar = count($arr_benar);
                        goto YvCc2;
                        MzLKS:
                        $benar_pg2 += 1 / count($jawab_pg2->jawaban) * count($arr_benar);
                        goto N1ILI;
                        nmEF6:
                        Q_Oc8:
                        goto uNRMD;
                        BUxqB:
                        $point = round($pk, 2);
                        goto whbww;
                        nqc9M:
                        pjRVT:
                        goto gBIwx;
                        nF3LA:
                        $soal[2][$ks]->point_otomatis = $point;
                        goto Ay38I;
                        Y_Alz:
                        $analisa = '<i class="fa fa-check-circle text-green text-lg"></i>';
                        goto zy1RC;
                        iDeAy:
                        $point_item = count($jawab_pg2->jawaban) > 0 ? $point_benar / count($jawab_pg2->jawaban) : 0;
                        goto l5i0A;
                        Rfwnn:
                        goto pjRVT;
                        goto DCXjm;
                        QpMOq:
                        $soal[2][$ks]->point_koreksi = $jawab_pg2->nilai_koreksi;
                        goto nF3LA;
                        BFpP5:
                        goto fenH9;
                        I2m9M:
                        if (!(count($jawab_pg2->jawaban) > 0)) {
                            goto AzeiO;
                        }
                        goto MzLKS;
                        fenH9:
                    }
                    goto I2m9M;
                    uNRMD:
                    $soal[2][$ks]->point = $point;
                    goto t3euY;
                    YvCc2:
                    if (!($jml_benar == count($jawab_pg2->jawaban))) {
                        if (!($jml_benar > 0 && $jml_benar < count($jawab_pg2->jawaban))) {
                            goto KnE8K;
                            zy1RC:
                            goto pjRVT;
                            goto l97lt;
                            whbww:
                            $soal[2][$ks]->analisa = $analisa;
                            goto fm6ZM;
                            J5mMv:
                            $skor_koreksi_pg2 += $jawab_pg2->nilai_koreksi;
                            goto ogGML;
                            KnE8K:
                            $analisa = '<i class="fa fa-times-circle text-red text-lg"></i>';
                            goto Rfwnn;
                            f036k:
                            $soal[2][$ks]->point = $jawab_pg2->nilai_koreksi;
                            goto pJqe_;
                            l97lt:
                        }
                        goto sAFxT;
                        ogGML:
                        $arr_benar = [];
                        goto wqRwj;
                        t3euY:
                        s01P3:
                        goto QpMOq;
                        Ay38I:
                        $otomatis_pg2 = $jawab_pg2->nilai_otomatis;
                        goto HlT31;
                        FBt_6:
                        $point_benar = $info->bobot_kompleks > 0 ? round($info->bobot_kompleks / $info->tampil_kompleks, 2) : 0;
                        goto iDeAy;
                        l5i0A:
                        $pk = $point_item * count($arr_benar);
                        goto xmXoK;
                        sAFxT:
                        $analisa = '<i class="fa fa-times-circle text-yellow text-lg"></i>';
                        goto nqc9M;
                        DCXjm:
                    }
                    goto Y_Alz;
                    gBIwx:
                    $ks = array_search($jawab_pg2->nomor_soal, array_column($soal[2], 'nomor_soal'));
                    goto BUxqB;
                    D_OSX:
                }
                goto WRUId;
                bQqBl:
                $bagi_pg2 = $info->tampil_kompleks / 100;
                goto Q0E1E;
                r90JZ:
                $skor->skor_jodohkan = $skor_jod;
                goto BXduG;
                yZ1T1:
                $siswa = $this->cbt->getSiswaById($tp->id_tp, $smt->id_smt, $this->input->get('siswa'));
                goto V0zxP;
                B1ktV:
                $skor->skor_kompleks = $skor_pg2;
                goto I9oW3;
                p6vdM:
                $data['tp'] = $this->dashboard->getTahun();
                goto Lv1In;
                wuVSj:
                ldtoR:
                goto nE7Ud;
                kWhKd:
                $otomatis_es = 0;
                goto HMMaP;
                dbCw3:
            }
            goto FEzyT;
            s1_0c:
        }
        goto qdR88;
        Txagr:
        goto FMPmo;
        KFFPb:
        $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
        goto TzN2D;
        bgUHY:
        if (!(count($jawaban_is) > 0)) {
            goto nnXY2;
        }
        goto Skr0V;
        JGMfX:
        $bagi_jodoh = $info->tampil_jodohkan / 100;
        goto Ia4ZU;
        Ex1A6:
        goto Ry6pB;
        BXduG:
        $jawaban_is = $ada_jawaban_isian ? $jawabans_siswa[$siswa->id_siswa]['4'] : [];
        goto KvbO9;
        TbsNm:
        $ada_jawaban_jodoh = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['3']);
        goto qQIOa;
        pg0D_:
        if ($this->ion_auth->is_admin()) {
            goto TRtlg;
        }
        goto KFFPb;
        ui75N:
        $input_pg2 = 0;
        goto f5FlJ;
        KOaII:
        $otomatis_pg2 = 0;
        goto nZShM;
        WG1Fm:
        ZL8i0:
        goto fiAg2;
        L5YjQ:
        $data['smt_active'] = $smt;
        goto pA4Iu;
        EwvMH:
        $ada_jawaban_pg = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['1']);
        goto srxEh;
        s2tIr:
        $this->load->view('cbt/nilai/detail');
        goto BAF0v;
        nZShM:
        if (!($info->tampil_kompleks > 0)) {
            goto Qbt_e;
        }
        goto mMbqO;
        FpV9T:
        $this->load->view('members/guru/templates/header', $data);
        goto s2tIr;
        CvCS6:
        $dur_siswa = null;
        goto XHiw5;
        Q0E1E:
        $bobot_pg2 = $info->bobot_kompleks / 100;
        goto JGMfX;
        Skr0V:
        foreach ($jawaban_is as $num=>$jawab_is) {
            goto b5EgL;
            B2o3R:
            if ($benar) {
                $benar_is++;
                goto tQBlp;
                BkFAN:
                $ks = array_search($jawab_is->nomor_soal, array_column($soal[4], 'nomor_soal'));
                goto Vgw96;
                RAPhu:
                $soal[4][$ks]->point_otomatis = $point;
                goto ZnNgN;
                JeMIf:
                p90lL:
                goto y_Lhm;
                Vgw96:
                $point = !$benar ? 0 : ($info->bobot_isian > 0 ? round($info->bobot_isian / $info->tampil_isian, 2) : 0);
                goto i8CvK;
                UU4Q3:
                $benar = $jawab_is != null && strtolower($jawab_is->jawaban_siswa) == strtolower($jawab_is->jawaban);
                goto B2o3R;
                i8CvK:
                if ($jawab_is->nilai_otomatis == '0') {
                    goto p90lL;
                }
                goto appAE;
                tQBlp:
            }
            goto BkFAN;
            appAE:
            $soal[4][$ks]->point = $jawab_is->nilai_koreksi;
            goto q2cFr;
            BqPmX:
            goto r239X;
            MLipY:
            wnw2B:
            $soal[4][$ks]->analisa = $analisa;
            goto XwTae;
            AL_Yn:
            goto wnw2B;
            goto cflFQ;
            q2cFr:
            goto fZR8K;
            goto JeMIf;
            XwTae:
            $otomatis_is = $jawab_is->nilai_otomatis;
            goto BqPmX;
            Kot1P:
            $soal[4][$ks]->point_koreksi = $jawab_is->nilai_koreksi;
            goto RAPhu;
            tw9S6:
            fZR8K:
            goto Kot1P;
            ZnNgN:
            if (!($benar)) {
                $analisa = '<i class="fa fa-times-circle text-yellow text-lg"></i>';
                goto AL_Yn;
                b5EgL:
                $skor_koreksi_is += $jawab_is->nilai_koreksi;
                goto UU4Q3;
                cflFQ:
            }
            goto sVDdv;
            y_Lhm:
            $soal[4][$ks]->point = $point;
            goto tw9S6;
            sVDdv:
            $analisa = '<i class="fa fa-check-circle text-green text-lg"></i>';
            goto MLipY;
            r239X:
        }
        goto Ex1A6;
        SLJ70:
    }
    public function simpanKoreksi()
    {
        $siswa = $this->input->post('siswa', true);
        $jadwal = $this->input->post('jadwal', true);
        $jenis = $this->input->post('jenis', true);
        $nilais = json_decode($this->input->post('nilai', true));
        $updated = [];
        $ids = [];
        $jml = 0;
        foreach ($nilais as $nilai) {
            array_push($ids, $nilai->id_soal);
            $jml += $nilai->koreksi;
            $updated[] = ['id_soal_siswa' => $nilai->id_soal, 'nilai_koreksi' => $nilai->koreksi, 'nilai_otomatis' => 1];
        }
        $updated = $this->db->update_batch('cbt_soal_siswa', $updated, 'id_soal_siswa');
        if ($updated) {
            $this->db->set($jenis, $jml);
            $this->db->where('id_nilai', $siswa . '0' . $jadwal);
            $this->db->update('cbt_nilai');
        }
        $data['success'] = $updated;
        $this->output_json($data);
    }

    public function tandaiKoreksi()
    {
        $siswa = $this->input->post('siswa', true);
        $jadwal = $this->input->post('jadwal', true);
        $this->db->set('dikoreksi', 1);
        $this->db->where('id_nilai', $siswa . '0' . $jadwal);
        $updated = $this->db->update('cbt_nilai');
        $data['success'] = $updated;
        $this->output_json($data);
    }

    public function tandaisemua()
    {
        $this->load->model('Cbt_model', 'cbt');
        $id_jadwal = $this->input->post('id_jadwal', true);
        $siswas = $this->input->post('ids', true);
        $updated = 0;
        $test_data = [];
        foreach ($siswas as $id_siswa => $memulai) {
            $info = $this->cbt->getJadwalById($id_jadwal);
            $jawabans = $this->cbt->getJawabanByBank($info->id_bank, $id_siswa);
            $jawabans_siswa = [];
            foreach ($jawabans as $jawaban_siswa) {
                if ($jawaban_siswa->jenis_soal == '2') {
                    $jawaban_siswa->opsi_a = @unserialize($jawaban_siswa->opsi_a);
                    $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
                    $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
                    $jawaban_siswa->jawaban_benar = array_map('strtoupper', $jawaban_siswa->jawaban_benar);
                    $jawaban_siswa->jawaban_benar = array_filter($jawaban_siswa->jawaban_benar, 'strlen');
                } elseif ($jawaban_siswa->jenis_soal == '3') {
                    $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
                    $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
                    $jawaban_siswa->jawaban_siswa = json_decode(json_encode($jawaban_siswa->jawaban_siswa));
                    $jawaban_siswa->jawaban_benar = json_decode(json_encode($jawaban_siswa->jawaban_benar));
                }
                $jawabans_siswa[$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
            }

            $ada_jawaban_isian = isset($jawabans_siswa['4']);
            $ada_jawaban_essai = isset($jawabans_siswa['5']);

            $bagi_pg = $info->tampil_pg / 100;
            $bobot_pg = $info->bobot_pg / 100;
            $bagi_pg2 = $info->tampil_kompleks / 100;
            $bobot_pg2 = $info->bobot_kompleks / 100;
            $bagi_jodoh = $info->tampil_jodohkan / 100;
            $bobot_jodoh = $info->bobot_jodohkan / 100;
            $bagi_isian = $info->tampil_isian / 100;
            $bobot_isian = $info->bobot_isian / 100;
            $bagi_essai = $info->tampil_esai / 100;
            $bobot_essai = $info->bobot_esai / 100;

            $jawaban_pg = isset($jawabans_siswa['1']) ? $jawabans_siswa['1'] : [];
            $benar_pg = 0;
            if ($info->tampil_pg > 0 && count($jawaban_pg) > 0) {
                foreach ($jawaban_pg as $jwb_pg) {
                    if (!($jwb_pg != null && $jwb_pg->jawaban_siswa != null)) {
                        continue;
                    }
                    if (strtoupper($jwb_pg->jawaban_siswa) == strtoupper($jwb_pg->jawaban_benar)) {
                        $benar_pg += 1;
                    }
                }
            }
            $skor_pg = $bagi_pg == 0 ? 0 : $benar_pg / $bagi_pg * $bobot_pg;

            $jawaban_pg2 = isset($jawabans_siswa["2"]) ? $jawabans_siswa["2"] : [];
            $benar_pg2 = 0;
            $skor_koreksi_pg2 = 0.0;
            $otomatis_pg2 = 0;
            if ($info->tampil_kompleks > 0 && count($jawaban_pg2) > 0) {
                foreach ($jawaban_pg2 as $num => $jawab_pg2) {
                    $otomatis_pg2 = $jawab_pg2->nilai_otomatis;
                    $skor_koreksi_pg2 += $jawab_pg2->nilai_koreksi;
                    $arr_benar = [];
                    foreach ($jawab_pg2->jawaban_siswa as $js) {
                        if (in_array($js, $jawab_pg2->jawaban_benar)) {
                            array_push($arr_benar, true);
                        }
                    }
                    if (count($jawab_pg2->jawaban_benar) > 0) {
                        $benar_pg2 += 1 / count($jawab_pg2->jawaban_benar) * count($arr_benar);
                    }
                }
            }
            $s_pg2 = $bagi_pg2 == 0 ? 0 : $benar_pg2 / $bagi_pg2 * $bobot_pg2;
            $skor_pg2 = $otomatis_pg2 == 0 ? $s_pg2 : $skor_koreksi_pg2;

            $jawaban_jodoh = isset($jawabans_siswa["3"]) ? $jawabans_siswa["3"] : [];
            $benar_jod = 0;
            $skor_koreksi_jod = 0.0;
            $otomatis_jod = 0;
            if ($info->tampil_jodohkan > 0 && count($jawaban_jodoh) > 0) {
                foreach ($jawaban_jodoh as $num => $jawab_jod) {
                    $skor_koreksi_jod += $jawab_jod->nilai_koreksi;

                    $arrSoal = $jawab_jod->jawaban_benar->jawaban;
                    $headSoal = array_shift($arrSoal);
                    $arrJwbSoal = [];
                    $items = 0;
                    foreach ($arrSoal as $kolSoal) {
                        $jwb = new stdClass();
                        foreach ($kolSoal as $pos => $kol) {
                            if ($kol == '1') {
                                $jwb->subtitle[] = $headSoal[$pos];
                                $items++;
                            }
                        }
                        $jwb->title = array_shift($kolSoal);
                        array_push($arrJwbSoal, $jwb);
                    }

                    $arrJawab = $jawab_jod->jawaban_siswa->jawaban;
                    $headJawab = array_shift($arrJawab);
                    $arrJwbJawab = [];
                    foreach ($arrJawab as $kolJawab) {
                        $jwbs = new stdClass();
                        foreach ($kolJawab as $po => $kol) {
                            if ($kol == '1') {
                                $jwbs->subtitle[] = $headJawab[$po];
                            }
                        }
                        array_push($arrJwbJawab, $jwbs);
                    }

                    $item_benar = 0;
                    $item_salah = 0;
                    foreach ($arrJwbJawab as $p => $ajjs) {
                        if (isset($ajjs->subtitle)) {
                            foreach ($ajjs->subtitle as $pp => $ajs) {
                                if (in_array($ajs, $arrJwbSoal[$p]->subtitle)) {
                                    $item_benar++;
                                } else {
                                    $item_salah++;
                                }
                            }
                        }
                    }
                    $benar_jod += 1 / $items * $item_benar;
                    $otomatis_jod = $jawab_jod->nilai_otomatis;
                }
            }
            $s_jod = $bagi_jodoh == 0 ? 0 : $benar_jod / $bagi_jodoh * $bobot_jodoh;
            $skor_jod = $otomatis_jod == 0 ? $s_jod : $skor_koreksi_jod;

            $jawaban_is = $ada_jawaban_isian ? $jawabans_siswa['4'] : [];
            $benar_is = 0;
            $skor_koreksi_is = 0.0;
            $otomatis_is = 0;
            if ($info->tampil_isian > 0 && count($jawaban_is) > 0) {
                foreach ($jawaban_is as $num => $jawab_is) {
                    $skor_koreksi_is += $jawab_is->nilai_koreksi;
                    $benar = $jawab_is != null && strtolower($jawab_is->jawaban_siswa) == strtolower($jawab_is->jawaban_benar);
                    if ($benar) {
                        $benar_is++;
                    }
                    $otomatis_is = $jawab_is->nilai_otomatis;
                }
            }
            $s_is = $bagi_isian == 0 ? 0 : $benar_is / $bagi_isian * $bobot_isian;
            $skor_is = $otomatis_is == 0 ? $s_is : $skor_koreksi_is;

            $jawaban_es = $ada_jawaban_essai ? $jawabans_siswa["5"] : [];
            $benar_es = 0;
            $skor_koreksi_es = 0.0;
            $otomatis_es = 0;
            if ($info->tampil_esai > 0 && count($jawaban_es) > 0) {
                foreach ($jawaban_es as $num => $jawab_es) {
                    $skor_koreksi_es += $jawab_es->nilai_koreksi;
                    $benar = $jawab_es != null && strtolower($jawab_es->jawaban_siswa) == strtolower($jawab_es->jawaban_benar);
                    if ($benar) {
                        $benar_es++;
                    }
                    $otomatis_es = $jawab_es->nilai_otomatis;
                }
            }
            $s_es = $bagi_essai == 0 ? 0 : $benar_es / $bagi_essai * $bobot_essai;
            $skor_es = $otomatis_es == 0 ? $s_es : $skor_koreksi_es;

            $insert = ['id_nilai' => $id_siswa . "0" . $id_jadwal, 'id_siswa' => $id_siswa, 'id_jadwal' => $id_jadwal, 'pg_benar' => $benar_pg, 'pg_nilai' => round($skor_pg, 2), 'kompleks_nilai' => round($skor_pg2, 2), 'jodohkan_nilai' => round($skor_jod, 2), 'isian_nilai' => round($skor_is, 2), 'essai_nilai' => round($skor_es, 2), 'dikoreksi' => $memulai === '2' ? '0' : '1'];
            $test_data[] = $insert;
            $upd = $this->db->replace('cbt_nilai', $insert);
            if ($upd) {
                $updated++;
            }
        }
        $data['success'] = $updated;
        $data['siswa'] = $siswas;
        $this->output_json($data);
    }
    public function inputEssai()
    {
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Cbt_model', 'cbt');
        $this->load->model('Dropdown_model', 'dropdown');
        $kelas_selected = $this->input->get('kelas');
        $jadwal_selected = $this->input->get('jadwal');
        $info = $this->cbt->getJadwalById($jadwal_selected);
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $siswas = $this->cbt->getSiswaByKelas($tp->id_tp, $smt->id_smt, $kelas_selected);
        $ids = [];
        foreach ($siswas as $key => $val) {
            array_push($ids, $val->id_siswa);
        }
        $nilai = $this->cbt->getNilaiAllSiswa([$jadwal_selected], $ids);
        foreach ($siswas as $siswa) {
            $siswa->skor_pg = isset($nilai[$siswa->id_siswa]) ? $nilai[$siswa->id_siswa]->pg_nilai : '0';
            $siswa->skor_pg2 = isset($nilai[$siswa->id_siswa]) ? $nilai[$siswa->id_siswa]->kompleks_nilai : '0';
            $siswa->skor_jod = isset($nilai[$siswa->id_siswa]) ? $nilai[$siswa->id_siswa]->jodohkan_nilai : '0';
            $siswa->skor_isian = isset($nilai[$siswa->id_siswa]) ? $nilai[$siswa->id_siswa]->isian_nilai : '0';
            $siswa->skor_essai = isset($nilai[$siswa->id_siswa]) ? $nilai[$siswa->id_siswa]->essai_nilai : '0';
        }
        $user = $this->ion_auth->user()->row();
        $data = ['user' => $user, 'judul' => 'Input Nilai Manual', 'subjudul' => '', 'profile' => $this->dashboard->getProfileAdmin($user->id), 'setting' => $this->dashboard->getSetting()];
        $data['tp'] = $this->dashboard->getTahun();
        $data['smt'] = $this->dashboard->getSemester();
        $data['tp_active'] = $tp;
        $data['smt_active'] = $smt;
        $data['nama_kelas'] = $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $kelas_selected);
        $data['kelas_selected'] = $kelas_selected;
        $data['jadwal_selected'] = $jadwal_selected;
        $data['jadwal'] = $info;
        $data['siswas'] = $siswas;
        if (!$this->ion_auth->is_admin()) {
            $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
            $data['guru'] = $guru;
            $this->load->view('members/guru/templates/header', $data);
            $this->load->view('cbt/nilai/nilai_essai');
            $this->load->view('members/guru/templates/footer');
        } else {
            $this->load->view('_templates/dashboard/_header', $data);
            $this->load->view('cbt/nilai/nilai_essai');
            $this->load->view('_templates/dashboard/_footer');
        }
    }

    public function simpanKoreksiEssai()
    {
        $this->load->model('Cbt_model', 'cbt');
        $jadwal = $this->input->post('jadwal', true);
        $nilais = json_decode($this->input->post('nilai', true));
        $update = 0;
        $blm_selesai = [];
        foreach ($nilais as $nilai) {
            $nilai_siswa = $this->cbt->getNilaiSiswaByJadwal($jadwal, $nilai->id_siswa);
            if ($nilai_siswa == null) {
                array_push($blm_selesai, $nilai->id_siswa);
                continue;
            }
            $replace = ['id_nilai' => $nilai_siswa->id_nilai, 'id_siswa' => $nilai_siswa->id_siswa, 'id_jadwal' => $nilai_siswa->id_jadwal, 'pg_benar' => $nilai_siswa->pg_benar, 'pg_nilai' => $nilai_siswa->pg_nilai, 'kompleks_nilai' => isset($nilai->kompleks_nilai) && $nilai->kompleks_nilai != null ? $nilai->kompleks_nilai : '0', 'jodohkan_nilai' => isset($nilai->jodohkan_nilai) && $nilai->jodohkan_nilai != null ? $nilai->jodohkan_nilai : '0', 'isian_nilai' => isset($nilai->isian_nilai) && $nilai->isian_nilai != null ? $nilai->isian_nilai : '0', 'essai_nilai' => isset($nilai->essai_nilai) && $nilai->essai_nilai != null ? $nilai->essai_nilai : '0', 'dikoreksi' => '1'];
            $up = $this->db->replace('cbt_nilai', $replace);
            if ($up) {
                $update++;
            }
        }
        $data['success'] = $update;
        $data['data'] = $nilais;
        $data['blm_selesai'] = count($blm_selesai);
        $this->output_json($data);
    }
}
