<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Cbtanalisis extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        } elseif (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('guru')) {
            show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
        }
        $this->load->library(['datatables', 'form_validation']);
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) {
            $data = json_encode($data);
        }
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index() {
        goto F2zZe;
        rUk3l:
        $all_jawaban = $this->cbt->getJawabanByBank($info->id_bank);
        goto xmUHh;
        ux9J1:
        $data['info'] = $info;
        goto V9eh8;
        Llo1B:
        $data['tp_selected'] = $thn_sel;
        goto N4SMG;
        h6l5F:
        $ids = [];
        goto mk2jq;
        XSdsR:
        $data['tp'] = $this->dashboard->getTahun();
        goto pAzGP;
        KUtsE:
        $this->load->view('cbt/analisis/data');
        goto sAbA3;
        N4ll5:
        $data = ['user'=>$user, 'judul'=>'Analisa Soal', 'subjudul'=>'Analisa Soal Ujian', 'setting'=>$this->dashboard->getSetting()];
        goto gA_Gz;
        N4SMG:
        $data['smt_selected'] = $smt_sel;
        goto EXduC;
        hdA1m:
        $this->load->view('members/guru/templates/header', $data);
        goto KUtsE;
        c8D3O:
        goto w4Nnl;
        D8BTb:
        $data['profile'] = $this->dashboard->getProfileAdmin($user->id);
        goto QYNsv;
        Q12eo:
        Ujm8M:
        goto ygd1V;
        sDVfD:
        $this->load->view('cbt/analisis/data');
        goto tTErM;
        h0WtZ:
        yBw95:
        goto RFX9E;
        sURSU:
        $data['kodejadwal'] = $this->dropdown->getAllJadwalGuru($thn_sel, $smt_sel, $guru->id_guru);
        goto hdA1m;
        V9eh8:
        $data['soals'] = $all_soals;
        goto QQ_e7;
        O8PvA:
        if (isset($all_soals[1])) {
            goto ZrwR1;
            Au5HN:
            $jadwal = $this->input->get('jadwal');
            goto juYxM;
            Ls_S2:
            $guru = $this->dashboard->getDataGuruByUserId($user->id, $thn_sel, $smt_sel);
            goto fwF4I;
            kh3J2:
            goto Ujm8M;
            xa59J:
            goto D8BTb;
            sAbA3:
            $this->load->view('members/guru/templates/footer');
            goto kh3J2;
            pAzGP:
            $data['tp_active'] = $tp;
            goto vEwab;
            l_ws2:
            $this->load->model('Cbt_model', 'cbt');
            goto R7GE_;
            F2zZe:
            $this->load->model('Dashboard_model', 'dashboard');
            goto l_ws2;
            xmUHh:
            $jawabans_siswa = [];
            goto h6l5F;
            keCFb:
            $smt_sel = $this->input->get('smt');
            goto y9xUo;
            uneY0:
            $this->load->view('_templates/dashboard/_header', $data);
            goto sDVfD;
            xXiKk:
            $data['smt_active'] = $smt;
            goto Au5HN;
            ZrwR1:
            foreach ($all_soals[1] as $no=>$soal) {
                goto UC0ge;
                ktkdL:
                ceFkQ:
                goto jNZsC;
                wR9Hc:
                $soal->jumlah_salah = 0;
                goto w2qEQ;
                DgCwG:
                $soal->status_daya = 'Jelek';
                goto QLOxP;
                hJjci:
                EGEwT:
                goto X6Uli;
                dCh6f:
                if (!($kesukaran >= 0.3)) {
                    goto GplUM;
                    Imj75:
                    RUZmS:
                    goto LkA9W;
                    ccAJm:
                    $status_soal = 'mudah';
                    goto hJjci;
                    mTDsM:
                    HZlrA:
                    goto s3o_F;
                    mZH4Z:
                    if ($daya_pembeda >= 0.7) {
                        goto wn1Zu;
                    }
                    goto EZbfS;
                    ve7FR:
                    $daya_pembeda = $yng_benar_golonganatas / $bagi_daya - $yng_benar_golonganbawah / $bagi_daya;
                    goto dsFbH;
                    QLOxP:
                    goto MeKPM;
                    goto fg84f;
                    lImcp:
                    foreach ($jwbn_siswa as $id=>$jawab_siswa) {
                        goto vwtSi;
                        zw9MV:
                        n_J0Q:
                        goto TKJAK;
                        Gv8_K:
                        $soal->jumlah_salah++;
                        goto Mj0fp;
                        PZmB9:
                        ayXK2:
                        goto SVBKx;
                        rLfIA:
                        $soal->jawaban_siswa['jawab_e'][$id] = $jawab_siswa;
                        goto cEvR8;
                        vwtSi:
                        $total_siswa++;
                        goto eUc0l;
                        dHquI:
                        if (!($jawab_siswa == 'E')) {
                            goto qXInP;
                            YN3CT:
                            goto oj7zn;
                            goto qXw91;
                            eUc0l:
                            if ($jawab_siswa == $soal->jawaban) {
                                goto n_J0Q;
                            }
                            goto Gv8_K;
                            wDEUl:
                            array_push($x, 1);
                            goto MVWVj;
                            yt5dr:
                            pa8Q0:
                            goto D7KJ0;
                            maSrf:
                            $soal->jawaban_siswa['jawab_a'][$id] = $jawab_siswa;
                            goto pT40z;
                            MVWVj:
                            TQrBm:
                            goto Ijf4B;
                            TKJAK:
                            $soal->jumlah_benar++;
                            goto wDEUl;
                            qXw91:
                        }
                        goto rLfIA;
                        q5Mqc:
                        goto oj7zn;
                        goto ZBNig;
                        pT40z:
                        goto oj7zn;
                        goto PZmB9;
                        Ijf4B:
                        if (!($jawab_siswa == 'A')) {
                            goto nQadE;
                            cWNVR:
                            goto ejecJ;
                            qXInP:
                            goto oj7zn;
                            goto Grhu_;
                            cEvR8:
                            oj7zn:
                            goto cWNVR;
                            Grhu_:
                        }
                        goto maSrf;
                        Mj0fp:
                        array_push($x, 0);
                        goto rQ0lS;
                        zqaRY:
                        if (!($jawab_siswa == 'D')) {
                            goto dHquI;
                            SVBKx:
                            $soal->jawaban_siswa['jawab_b'][$id] = $jawab_siswa;
                            goto fTZn_;
                            nQadE:
                            if ($jawab_siswa == 'B') {
                                goto ayXK2;
                            }
                            if ($jawab_siswa == 'C') {
                                goto pa8Q0;
                            }
                            goto zqaRY;
                            ZBNig:
                        }
                        $soal->jawaban_siswa['jawab_d'][$id] = $jawab_siswa;
                        goto YN3CT;
                        D7KJ0:
                        $soal->jawaban_siswa['jawab_c'][$id] = $jawab_siswa;
                        goto q5Mqc;
                        fTZn_:
                        goto oj7zn;
                        goto yt5dr;
                        rQ0lS:
                        goto TQrBm;
                        goto zw9MV;
                        ejecJ:
                    }
                    goto qyXvY;
                    NHZv2:
                    if ($cek == 1) {
                        goto hhmOz;
                        R9ha0:
                        wn1Zu:
                        goto P137j;
                        dsFbH:
                        goto RUZmS;
                        goto gV2er;
                        hGTvQ:
                        $daya_pembeda = 0 - $yng_benar_golonganbawah / $bagi_daya;
                        goto cUOMh;
                        X6Uli:
                        EKxQC:
                        goto snIBF;
                        koz09:
                        if ($yng_benar_golonganatas == 0 && $yng_benar_golonganbawah == 0) {
                            goto sYPpI;
                        }
                        goto ve7FR;
                        HqWVz:
                        $daya_pembeda = 0;
                        goto Imj75;
                        yKL9N:
                        goto yGTWN;
                        wtbVA:
                        $pos_b = $bagi;
                        goto CRksJ;
                        jNZsC:
                        goto oaIqD;
                        goto NOhnS;
                        SZ3gT:
                        $soal->daya_pembeda = $daya_pembeda;
                        goto mZH4Z;
                        pMuqK:
                        if (!($yng_benar_golonganatas == 0 && $yng_benar_golonganbawah != 0)) {
                            goto alADG;
                            l893x:
                            KFiQC:
                            goto BxRGt;
                            w2qEQ:
                            $total_siswa = 0;
                            goto uaJ_P;
                            L0qDf:
                            $jwbn_siswa = isset($jawabans_siswa[1][$no]) && isset($jawabans_siswa[1][$no]) ? $jawabans_siswa[1][$no] : [];
                            goto lImcp;
                            snIBF:
                            $soal->tingkat_kesukaran = $kesukaran;
                            goto t1Pgh;
                            l1zsK:
                            $yng_benar_golonganbawah = 0;
                            goto nXwrV;
                            KEVz2:
                            $salah = $soal->jumlah_salah;
                            goto mp0Pz;
                            jothp:
                            $pearson = $this->pearson($x, $y);
                            goto hjBj2;
                            alADG:
                            if ($yng_benar_golonganatas != 0 && $yng_benar_golonganbawah == 0) {
                                goto F_b1m;
                            }
                            goto koz09;
                            Fa_3n:
                            $soal->status_valid = $validitas;
                            goto ozV0O;
                            Ljc0u:
                            $validitas = $this->nilaiSignifikansi($total_siswa) <= $pearson ? 'Valid' : 'Tidak valid';
                            goto Fa_3n;
                            FDmra:
                            $soal->skor_siswa = [];
                            goto FTmv2;
                            CRksJ:
                            $y = [];
                            goto YG1da;
                            NOhnS:
                        }
                        goto hGTvQ;
                        j70_6:
                        if (!($jml_siswa > 0)) {
                            goto EKxQC;
                        }
                        goto wfPjg;
                        FTmv2:
                        $soal->jumlah_benar = 0;
                        goto wR9Hc;
                        YG1da:
                        $yng_benar_golonganatas = 0;
                        goto l1zsK;
                        LkA9W:
                        goto ceFkQ;
                        goto ac07y;
                        f6jRp:
                        MeKPM:
                        goto TLpPY;
                        e22Yz:
                        goto EGEwT;
                        goto xv9KC;
                        fg84f:
                        xfxbs:
                        goto a0MhC;
                        s3o_F:
                        goto buNo0;
                        zuYCo:
                        $benar = $soal->jumlah_benar;
                        goto KEVz2;
                        mVIeE:
                        CPUOx:
                        goto hGAyG;
                        BxRGt:
                        $soal->status_daya = 'Baik';
                        goto mVIeE;
                        P137j:
                        $soal->status_daya = 'Baik Sekali';
                        goto mTDsM;
                        H_eL9:
                        $cek = $jml_siswa % 2;
                        goto NHZv2;
                        FOaYy:
                        foreach ($nilai_pg as $id=>$nilai) {
                            goto S7QoU;
                            xHx10:
                            if (!($no <= $bagi)) {
                                goto o6B0x;
                                WmHIQ:
                                TL7m7:
                                goto NvG_P;
                                dt1dC:
                                $no++;
                                goto fBvoy;
                                hnrME:
                                $siswa_menjawab = $jwbn_siswa[$id];
                                goto xHx10;
                                YmxDy:
                                fIdVp:
                                goto Fia4p;
                                RaFZZ:
                            }
                            goto MF10K;
                            sgjkk:
                            $yng_benar_golonganbawah++;
                            goto WmHIQ;
                            Fia4p:
                            Z6mwX:
                            goto L5CRh;
                            jSPSe:
                            if (isset($jwbn_siswa[$id])) {
                                goto hnrME;
                                L5CRh:
                            }
                            goto dt1dC;
                            NvG_P:
                            goto Z6mwX;
                            goto RaFZZ;
                            DsXl7:
                            $yng_benar_golonganatas++;
                            goto YmxDy;
                            o6B0x:
                            if (!($siswa_menjawab == $soal->jawaban)) {
                                goto TL7m7;
                            }
                            goto sgjkk;
                            S7QoU:
                            array_push($y, $nilai->pg_benar);
                            goto jSPSe;
                            fBvoy:
                            goto KP9jU;
                            MF10K:
                            if (!($siswa_menjawab == $soal->jawaban)) {
                                goto fIdVp;
                            }
                            goto DsXl7;
                            KP9jU:
                        }
                        goto yKL9N;
                        qyXvY:
                        goto zuYCo;
                        pV21i:
                        goto o9u3J;
                        goto nCCyQ;
                        a0MhC:
                        $soal->status_daya = 'Cukup';
                        goto f6jRp;
                        yGTWN:
                        $soal->total_siswa = $total_siswa;
                        goto Ith9T;
                        GplUM:
                        $status_soal = 'sukar';
                        goto pV21i;
                        UKIYc:
                        $pos_a = 0;
                        goto wtbVA;
                        ac07y:
                        F_b1m:
                        goto EZF4V;
                        ozV0O:
                        $bagi_daya = $bagi > 0 ? $bagi : 1;
                        goto pMuqK;
                        Ith9T:
                        $soal->benar_atas = $yng_benar_golonganatas;
                        goto wtbbl;
                        TLpPY:
                        goto CPUOx;
                        goto l893x;
                        mp0Pz:
                        $jml_siswa = $total_siswa;
                        goto obXMa;
                        hjBj2:
                        $soal->nilai_valid = $pearson;
                        goto dSEGa;
                        wfPjg:
                        $kesukaran = round($benar / $jml_siswa, 2);
                        goto u32bS;
                        hhmOz:
                        $jml_siswa--;
                        goto RyNRI;
                        x4CBJ:
                        o9u3J:
                        goto e22Yz;
                        EZbfS:
                        if ($daya_pembeda >= 0.4) {
                            goto KFiQC;
                        }
                        goto Dyqrf;
                        UC0ge:
                        $soal->jawaban_siswa = [];
                        goto FDmra;
                        cUOMh:
                        oaIqD:
                        goto SZ3gT;
                        RyNRI:
                    }
                    goto IHXeH;
                    nXwrV:
                    $no = 1;
                    goto FOaYy;
                    t1Pgh:
                    $soal->status_kesukaran = $status_soal;
                    goto H_eL9;
                    uaJ_P:
                    $x = [];
                    goto L0qDf;
                    dSEGa:
                    $soal->table_r = $this->nilaiSignifikansi($total_siswa);
                    goto Ljc0u;
                    Dyqrf:
                    if ($daya_pembeda >= 0.2) {
                        goto xfxbs;
                    }
                    goto DgCwG;
                    wtbbl:
                    $soal->benar_bawah = $yng_benar_golonganbawah;
                    goto jothp;
                    EZF4V:
                    $daya_pembeda = $yng_benar_golonganatas / $bagi_daya;
                    goto ktkdL;
                    u32bS:
                    if (!($kesukaran >= 0.7)) {
                        goto dCh6f;
                        IHXeH:
                        $bagi = $jml_siswa / 2;
                        goto UKIYc;
                        obXMa:
                        $kesukaran = 0;
                        goto C8Xlm;
                        NCvpx:
                        $status_soal = 'sedang';
                        goto x4CBJ;
                        gV2er:
                        sYPpI:
                        goto HqWVz;
                        xv9KC:
                    }
                    goto ccAJm;
                    nCCyQ:
                }
                goto NCvpx;
                hGAyG:
                goto HZlrA;
                goto R9ha0;
                C8Xlm:
                $status_soal = '';
                goto j70_6;
                buNo0:
            }
            goto mlJMB;
            gA_Gz:
            $tp = $this->dashboard->getTahunActive();
            goto FhVg2;
            A3mOg:
            $info = $this->cbt->getJadwalById($jadwal);
            goto rUk3l;
            QYNsv:
            $data['kodejadwal'] = $this->dropdown->getAllJadwal($thn_sel, $smt_sel);
            goto uneY0;
            R7GE_:
            $this->load->model('Dropdown_model', 'dropdown');
            goto Lc7ua;
            FhVg2:
            $smt = $this->dashboard->getSemesterActive();
            goto XSdsR;
            y9xUo:
            $thn_sel = $thn_sel == null ? $tp->id_tp : $thn_sel;
            goto o3x4I;
            Lc7ua:
            $user = $this->ion_auth->user()->row();
            goto N4ll5;
            L62XI:
            $data['guru'] = $guru;
            goto sURSU;
            w4Nnl:
            $nilai_pg = $this->cbt->getAllNilaiSiswa($jadwal);
            goto pynhN;
            M9OW2:
            if (!($jadwal != null)) {
                goto yBw95;
            }
            goto A3mOg;
            mk2jq:
            foreach ($all_jawaban as $jawaban_siswa) {
                goto NVMBM;
                IT9zt:
                goto dxLut;
                ZhZTF:
                $jawabans_siswa[$jawaban_siswa->jenis_soal][$jawaban_siswa->nomor_soal][$jawaban_siswa->id_siswa] = $jawaban_siswa->jawaban_siswa;
                goto IT9zt;
                NVMBM:
                array_push($ids, $jawaban_siswa->id_siswa);
                goto ZhZTF;
                dxLut:
            }
            goto c8D3O;
            juYxM:
            $thn_sel = $this->input->get('thn');
            goto keCFb;
            tTErM:
            $this->load->view('_templates/dashboard/_footer');
            goto Q12eo;
            fwF4I:
            $nguru[$guru->id_guru] = $guru->nama_guru;
            goto L62XI;
            GuhqQ:
        }
        goto ux9J1;
        RFX9E:
        if ($this->ion_auth->is_admin()) {
            goto xa59J;
        }
        goto Ls_S2;
        EXduC:
        $data['jadwal_selected'] = $jadwal;
        goto M9OW2;
        QQ_e7:
        $data['nilai'] = $nilai_pg;
        goto h0WtZ;
        pynhN:
        $all_soals = $this->cbt->getSoalByBank($info->id_bank);
        goto O8PvA;
        vEwab:
        $data['smt'] = $this->dashboard->getSemester();
        goto xXiKk;
        mlJMB:
        goto GuhqQ;
        o3x4I:
        $smt_sel = $smt_sel == null ? $smt->id_smt : $smt_sel;
        goto Llo1B;
        ygd1V:
    }
    private function pearson($x, $y)
    {
        $x = array_values($x);
        $y = array_values($y);
        $cx = count($x);
        $cy = count($y);
        if ($cx === 0 || $cy === 0) {
            return -1;
        }
        if ($cx !== $cy) {
            return -1;
        }
        $xs = array_sum($x) / $cx;
        $ys = array_sum($y) / $cy;
        $a = 0;
        $bx = 0;
        $by = 0;
        for ($i = 0; $i < $cx; $i++) {
            $xr = $x[$i] - $xs;
            $yr = $y[$i] - $ys;
            $a += $xr * $yr;
            $bx += pow($xr, 2);
            $by += pow($yr, 2);
        }
        $b = sqrt($bx * $by);
        if ($b <= 0) {
            return -1;
        }
        return $a / $b;
    }

    public function getNilaiKelas()
    {
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Cbt_model', 'cbt');
        $kelas = $this->input->get('kelas');
        $sesi = $this->input->get('sesi');
        $jadwal = $this->input->get('jadwal');
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $info = $this->cbt->getJadwalById($jadwal, $sesi);
        $siswas = $this->cbt->getSiswaByKelas($tp->id_tp, $smt->id_smt, $kelas);
        $arrDur = [];
        foreach ($siswas as $siswa) {
            $arrJawab_pg = [];
            $arrJawab_essai = [];
            for ($i = 0; $i < $info->tampil_pg; $i++) {
                $arrJawab_pg[$siswa->id_siswa][] = $this->cbt->getJawabanSiswa($siswa->id_siswa . "0" . $jadwal . $info->id_bank . 1 . ($i + 1));
            }
            for ($j = 0; $j < $info->tampil_esai; $j++) {
                $jawaban = $this->cbt->getJawabanSiswa($siswa->id_siswa . "0" . $jadwal . $info->id_bank . 2 . ($j + 1));
                if ($jawaban != null) {
                    $arrJawab_essai[$siswa->id_siswa][] = $jawaban;
                } else {
                    $arrJawab_essai[$siswa->id_siswa][] = ['id_jawaban' => 0, 'jawaban' => '', 'jawaban_benar' => '', 'koreksi' => 0];
                }
            }
            $jawab_essai = isset($arrJawab_essai[$siswa->id_siswa]) ? $arrJawab_essai[$siswa->id_siswa] : [];
            $arrDur[$siswa->id_siswa] = ['dur' => $this->cbt->getDurasiSiswa($siswa->id_siswa . '0' . $jadwal), 'jawab_pg' => $arrJawab_pg[$siswa->id_siswa], 'jawab_essai' => $jawab_essai, 'log' => $this->cbt->getLogUjian($siswa->id_siswa, $jadwal)];
        }
        $data['siswa'] = $siswas;
        $data['jawaban'] = $arrDur;
        $data['info'] = $info;
        $this->output_json($data);
    }

    public function getJadwalUjianByJadwal()
    {
        $this->load->model('Cbt_model', 'cbt');
        $this->load->model('Dropdown_model', 'dropdown');
        $jadwal = $this->input->get('jadwal');
        $tp = $this->input->get('thn');
        $smt = $this->input->get('smt');
        $info = $this->cbt->getJadwalById($jadwal);
        $kelas = unserialize($info->bank_kelas);
        $kelases = [];
        foreach ($kelas as $key => $value) {
            $kelases[$value['kelas_id']] = $this->dropdown->getNamaKelasById($info->id_tp, $info->id_smt, $value['kelas_id']);
        }
        $this->output_json($kelases);
    }

    public function kalkulasi() {
        $jadwal = $this->input->get('jadwal');
        $update = $this->generateNilaiUjian($jadwal);
        $this->output_json($update);
    }
    public function generateNilaiUjian($jadwal) {
        goto QNHPG;
        c1L4v:
        foreach ($siswas as $siswa) {
            goto cep4g;
            z5zhb:
            $insert['essai_nilai'] = round($skor_es, 2);
            goto qIPD3;
            CoO7M:
            $otomatis_is = 0;
            goto ywN4F;
            YV2f1:
            $input_is = 0;
            goto iwpn1;
            qNIz0:
            $insert['id_jadwal'] = $jadwal;
            goto OHvso;
            azelQ:
            yBlmy:
            goto dyXfC;
            eACc8:
            B36cT:
            goto eck8r;
            OHvso:
            $insert['pg_benar'] = $benar_pg;
            goto yVct6;
            SNO0j:
            goto H8h3z;
            q8czS:
            $otomatis_jod = 0;
            goto n9bGF;
            nIQJo:
            LEvjg:
            fTdwb:
            goto K6ryM;
            vIrEE:
            $input_jod = $nilai_input->jodohkan_nilai;
            goto AqMpg;
            KGRKK:
            $input_jod = 0;
            goto M6vNI;
            eck8r:
            $s_is = $bagi_isian == 0 ? 0 : $benar_is / $bagi_isian * $bobot_isian;
            goto YV2f1;
            qU85Q:
            foreach ($jawaban_pg as $jwb_pg) {
                goto LXzmo;
                Zn4jz:
                SSG_K:
                goto OLCvm;
                bpngJ:
                goto GBAnY;
                vb1YH:
                goto m5wbq;
                goto Zn4jz;
                kBKzF:
                if (strtoupper($jwb_pg->jawaban_siswa) == strtoupper($jwb_pg->jawaban_benar)) {
                    goto SSG_K;
                }
                goto siS7E;
                hqRvt:
                m5wbq:
                goto fkH3o;
                OLCvm:
                $benar_pg += 1;
                goto hqRvt;
                fkH3o:
                uZnp6:
                goto bpngJ;
                LXzmo:
                if (!($jwb_pg != null && $jwb_pg->jawaban_siswa != null)) {
                    goto uZnp6;
                }
                goto kBKzF;
                siS7E:
                $salah_pg += 1;
                goto vb1YH;
                GBAnY:
            }
            goto qWStD;
            mYn7J:
            if (count($jawaban_jodoh) > 0) {
                goto ZDBvy;
                AE76x:
                $jawaban_jodoh = $ada_jawaban_jodoh ? $jawabans_siswa[$siswa->id_siswa]["\63"] : [];
                goto N9VwL;
                E6bZ3:
                $insert['jodohkan_nilai'] = round($skor_jod, 2);
                goto Bt3xV;
                Nr4iw:
                $ada_jawaban_pg2 = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\62"]);
                goto DPJNH;
                AqMpg:
                xURJ6:
                goto f5bPq;
                q1URv:
            }
            goto frWUR;
            Lwprc:
            w2i3_:
            goto azelQ;
            f5bPq:
            $skor_jod = $input_jod != 0 ? $input_jod : ($otomatis_jod == 0 ? $s_jod : $skor_koreksi_jod);
            goto wHP3P;
            WAYKr:
            $benar_es = 0;
            goto Ri8ip;
            n9bGF:
            if ($info->tampil_jodohkan > 0) {
                goto mYn7J;
                qIPD3:
                array_push($insets, $insert);
                goto AOCDO;
                frWUR:
            }
            goto QY4tv;
            SpE2m:
            $skor_es = $input_es != 0 ? $input_es : ($otomatis_es == 0 ? $s_es : $skor_koreksi_es);
            goto N6u3I;
            ywN4F:
            if (!($info->tampil_isian > 0)) {
                goto B36cT;
            }
            goto WGr_J;
            Br44F:
            $benar_is = 0;
            $skor_koreksi_is = 0.0;
            goto CoO7M;
            g4y_w:
            goto q1URv;
            hI8RR:
            $skor_koreksi_jod = 0.0;
            goto q8czS;
            SFNec:
            $skor_is = $input_is != 0 ? $input_is : ($otomatis_is == 0 ? $s_is : $skor_koreksi_is);
            goto Ttuot;
            TtWSj:
            $input_is = $nilai_input->isian_nilai;
            goto C7pfz;
            WaQim:
            $input_pg2 = $nilai_input->kompleks_nilai;
            goto BVGEf;
            eXBCP:
            $otomatis_pg2 = 0;
            goto tYKKe;
            gpKq8:
            if (!($nilai_input != null && $nilai_input->isian_nilai != null)) {
                goto CtQYj;
            }
            goto YyWqn;
            Ttuot:
            $jawaban_es = $ada_jawaban_essai ? $jawabans_siswa[$siswa->id_siswa]['5'] : [];
            goto WAYKr;
            AOCDO:
            goto BroSa;
            qWStD:
            goto Lwprc;
            YoiqS:
            $ada_jawaban_essai = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['5']);
            goto C_0RZ;
            Ta1vl:
            goto av8lH;
            RekA3:
            $benar_pg = 0;
            goto EQvJ6;
            yVct6:
            $insert['pg_nilai'] = round($skor_pg, 2);
            goto gQalG;
            C7pfz:
            IO5vr:
            goto SFNec;
            EfzvF:
            foreach ($jawaban_es as $num=>$jawab_es) {
                $skor_koreksi_es += $jawab_es->nilai_koreksi;
                $benar = $jawab_es != null && strtolower($jawab_es->jawaban_siswa) == strtolower($jawab_es->jawaban_benar);
                goto Db0PW;
                xbLqe:
                $benar_es++;
                lfuAL:
                goto vMO7A;
                Db0PW:
                if (!$benar) {
                    goto lfuAL;
                }
                goto xbLqe;
                ts74t:
                goto HYmI2;
                vMO7A:
                $otomatis_es = $jawab_es->nilai_otomatis;
                goto ts74t;
                HYmI2:
            }
            goto WQIgP;
            QY4tv:
            $s_jod = $bagi_jodoh == 0 ? 0 : $benar_jod / $bagi_jodoh * $bobot_jodoh;
            goto KGRKK;
            vlEUk:
            $insert['id_siswa'] = $siswa->id_siswa;
            goto qNIz0;
            Xv1AR:
            $skor_pg2 = $input_pg2 != 0 ? $input_pg2 : ($otomatis_pg2 == 0 ? $s_pg2 : $skor_koreksi_pg2);
            goto AE76x;
            ZDBvy:
            foreach ($jawaban_jodoh as $num=>$jawab_jod) {
                goto BNmbD;
                JuO_S:
                $benar_jod += 1 / $items * $item_benar;
                goto Suizm;
                qQb5i:
                $arrSoal = $jawab_jod->jawaban_benar->jawaban;
                goto HJRdk;
                pBW7_:
                $arrJwbSoal = [];
                goto Q9WvH;
                NB2eb:
                goto k1Xow;
                t6rMk:
                $arrJwbJawab = [];
                goto EzOu8;
                PmWo0:
                $item_salah = 0;
                goto nM6nO;
                KeHZS:
                foreach ($arrSoal as $kolSoal) {
                    goto oOqHT;
                    a2tHj:
                    array_push($arrJwbSoal, $jwb);
                    goto Yyb6y;
                    STkxo:
                    $jwb->title = array_shift($kolSoal);
                    goto a2tHj;
                    mUUFJ:
                    foreach ($kolSoal as $pos=>$kol) {
                        goto WUD2C;
                        z0Zd5:
                        rwbMY:
                        goto lZ4LR;
                        WUD2C:
                        if (!($kol == '1')) {
                            goto rwbMY;
                        }
                        goto KO7Gq;
                        WAW8N:
                        $items++;
                        goto z0Zd5;
                        KO7Gq:
                        $jwb->subtitle[] = $headSoal[$pos];
                        goto WAW8N;
                        lZ4LR:
                    }
                    goto tvHCH;
                    oOqHT:
                    $jwb = new stdClass();
                    goto mUUFJ;
                    tvHCH:
                    goto STkxo;
                    Yyb6y:
                }
                goto c0IWH;
                Suizm:
                $otomatis_jod = $jawab_jod->nilai_otomatis;
                goto F1j8X;
                EzOu8:
                foreach ($arrJawab as $kolJawab) {
                    goto L3lBT;
                    TaZGw:
                    array_push($arrJwbJawab, $jwbs);
                    goto pbGRC;
                    eeE15:
                    goto TaZGw;
                    xs9gl:
                    foreach ($kolJawab as $po=>$kol) {
                        if ($kol == "\61") {
                            $sub = $headJawab[$po];
                            goto bt7fK;
                            xxWIz:
                        }
                        goto R3GKu;
                        bt7fK:
                        $jwbs->subtitle[] = $sub;
                        goto xxWIz;
                        R3GKu:
                    }
                    goto eeE15;
                    pbGRC:
                    goto hNKbs;
                    L3lBT:
                    $jwbs = new stdClass();
                    goto xs9gl;
                    hNKbs:
                }
                goto NB2eb;
                umrvx:
                $arrJawab = $jawab_jod->jawaban_siswa->jawaban;
                goto X61bc;
                HJRdk:
                $headSoal = array_shift($arrSoal);
                goto pBW7_;
                F1j8X:
                goto WZVQs;
                X61bc:
                $headJawab = array_shift($arrJawab);
                goto t6rMk;
                nM6nO:
                foreach ($arrJwbJawab as $p=>$ajjs) {
                    goto Amtw7;
                    zE18k:
                    goto hT1ld;
                    Amtw7:
                    foreach ($ajjs->subtitle as $pp=>$ajs) {
                        goto THQRI;
                        X54G1:
                        goto FcpsD;
                        x85rH:
                        Z6N1d:
                        goto X54G1;
                        xdyp1:
                        $item_benar++;
                        goto x85rH;
                        TSCZE:
                        eZMQB:
                        goto xdyp1;
                        GHTGy:
                        $item_salah++;
                        goto Z6N1d;
                        goto TSCZE;
                        THQRI:
                        if (in_array($ajs, $arrJwbSoal[$p]->subtitle)) {
                            goto eZMQB;
                        }
                        goto GHTGy;
                        FcpsD:
                    }
                    goto zE18k;
                    hT1ld:
                }
                goto aRvAo;
                Q9WvH:
                $items = 0;
                goto KeHZS;
                c0IWH:
                goto umrvx;
                BNmbD:
                $skor_koreksi_jod += $jawab_jod->nilai_koreksi;
                goto qQb5i;
                aRvAo:
                goto JuO_S;
                k1Xow:
                $item_benar = 0;
                goto PmWo0;
                WZVQs:
            }
            goto g4y_w;
            Bt3xV:
            $insert['isian_nilai'] = round($skor_is, 2);
            goto z5zhb;
            WGr_J:
            if (count($jawaban_is) > 0) {
                goto PgKaK;
                DPJNH:
                $ada_jawaban_jodoh = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['3']);
                goto O3x0E;
                N6u3I:
                $insert['id_nilai'] = $siswa->id_siswa . "\60" . $jadwal;
                goto vlEUk;
                B18ik:
                if (!($info->tampil_pg > 0)) {
                    goto yBlmy;
                }
                goto YAvEa;
                YyWqn:
                $input_es = $nilai_input->essai_nilai;
                goto YEzqS;
                EQvJ6:
                $salah_pg = 0;
                goto B18ik;
                YAvEa:
                if (!(count($jawaban_pg) > 0)) {
                    goto w2i3_;
                }
                goto qU85Q;
                cep4g:
                $ada_jawaban = isset($jawabans_siswa[$siswa->id_siswa]);
                goto JUeg5;
                O3x0E:
                $ada_jawaban_isian = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\64"]);
                goto YoiqS;
                JUeg5:
                $ada_jawaban_pg = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['1']);
                goto Nr4iw;
                C_0RZ:
                $nilai_input = $this->cbt->getNilaiSiswaByJadwal($jadwal, $siswa->id_siswa);
                goto We80d;
                fahvI:
                if (!($info->tampil_esai > 0)) {
                    goto fTdwb;
                }
                goto HkGvG;
                Ri8ip:
                $skor_koreksi_es = 0.0;
                goto qB5z0;
                We80d:
                $jawaban_pg = $ada_jawaban_pg ? $jawabans_siswa[$siswa->id_siswa]['1'] : [];
                goto RekA3;
                BVGEf:
                fWv2M:
                goto Xv1AR;
                uDY_0:
                pKAAG:
                goto AXa42;
                MxDqN:
                $benar_pg2 = 0;
                goto oPsaF;
                YEzqS:
                CtQYj:
                goto SpE2m;
                iW0PP:
                $input_es = 0;
                goto gpKq8;
                M6vNI:
                if (!($nilai_input != null && $nilai_input->jodohkan_nilai != null)) {
                    goto xURJ6;
                }
                goto vIrEE;
                PgKaK:
                foreach ($jawaban_is as $num=>$jawab_is) {
                    goto H6hNB;
                    yDrve:
                    fMFkb:
                    goto iqLae;
                    H6hNB:
                    $skor_koreksi_is += $jawab_is->nilai_koreksi;
                    goto qC_Mo;
                    RUEL5:
                    $benar_is++;
                    goto yDrve;
                    Dhvty:
                    if (!$benar) {
                        goto fMFkb;
                    }
                    goto RUEL5;
                    iqLae:
                    $otomatis_is = $jawab_is->nilai_otomatis;
                    goto NNODY;
                    qC_Mo:
                    $benar = $jawab_is != null && strtolower($jawab_is->jawaban_siswa) == strtolower($jawab_is->jawaban_benar);
                    goto Dhvty;
                    NNODY:
                }
                goto Ta1vl;
                dyXfC:
                $skor_pg = $bagi_pg == 0 ? 0 : $benar_pg / $bagi_pg * $bobot_pg;
                goto dcCZw;
                H8h3z:
                AKaZ5:
                goto uDY_0;
                cSyUQ:
                if (!(count($jawaban_pg2) > 0)) {
                    goto AKaZ5;
                }
                foreach ($jawaban_pg2 as $num=>$jawab_pg2) {
                    $otomatis_pg2 = $jawab_pg2->nilai_otomatis;
                    $skor_koreksi_pg2 += $jawab_pg2->nilai_koreksi;
                    goto LlvE6;
                    JkOsm:
                    $benar_pg2 += 1 / count($jawab_pg2->jawaban_benar) * count($arr_benar);
                    goto eu6qn;
                    Xjrey:
                    foreach ($jawab_pg2->jawaban_siswa as $js) {
                        goto zEOEB;
                        dRVD6:
                        array_push($arr_benar, true);
                        goto IlL4a;
                        zEOEB:
                        if (in_array($js, $jawab_pg2->jawaban_benar)) {
                            goto dRVD6;
                            IlL4a:
                        }
                    }
                    goto JkOsm;
                    LlvE6:
                    $arr_benar = [];
                    goto Xjrey;
                    eu6qn:
                }
                goto SNO0j;
                tYKKe:
                if (!($info->tampil_kompleks > 0)) {
                    goto pKAAG;
                }
                goto cSyUQ;
                qB5z0:
                $otomatis_es = 0;
                goto fahvI;
                TJIlr:
                if (!($nilai_input != null && $nilai_input->kompleks_nilai != null)) {
                    goto fWv2M;
                }
                goto WaQim;
                Hy6f3:
                $input_pg2 = 0;
                goto TJIlr;
                wHP3P:
                $jawaban_is = $ada_jawaban_isian ? $jawabans_siswa[$siswa->id_siswa]['4'] : [];
                goto Br44F;
                HkGvG:
                if (!(count($jawaban_es) > 0)) {
                    goto LEvjg;
                }
                goto EfzvF;
                N9VwL:
                $benar_jod = 0;
                goto hI8RR;
                WQIgP:
                goto nIQJo;
                oPsaF:
                $skor_koreksi_pg2 = 0.0;
                goto eXBCP;
                AXa42:
                $s_pg2 = $bagi_pg2 == 0 ? 0 : $benar_pg2 / $bagi_pg2 * $bobot_pg2;
                goto Hy6f3;
                av8lH:
            }
            goto eACc8;
            gQalG:
            $insert['kompleks_nilai'] = round($skor_pg2, 2);
            goto E6bZ3;
            iwpn1:
            if (!($nilai_input != null && $nilai_input->isian_nilai != null)) {
                goto IO5vr;
            }
            goto TtWSj;
            dcCZw:
            $jawaban_pg2 = $ada_jawaban_pg2 ? $jawabans_siswa[$siswa->id_siswa]["\62"] : [];
            goto MxDqN;
            K6ryM:
            $s_es = $bagi_essai == 0 ? 0 : $benar_es / $bagi_essai * $bobot_essai;
            goto iW0PP;
            BroSa:
        }
        goto gD0gf;
        zVG8N:
        $kelases = [];
        goto T5R2J;
        teXaP:
        $bobot_jodoh = $info->bobot_jodohkan / 100;
        goto U8gQZ;
        R00P2:
        $update = $this->db->update_batch('cbt_nilai', $insets, 'id_nilai');
        return $update;
        sRX8A:
        $jawabans = $this->cbt->getJawabanByBank($info->id_bank);
        goto FAXr4;
        Upn4Y:
        $ids = [];
        goto RSyKB;
        AKCRu:
        $kelas_bank = unserialize($info->bank_kelas);
        goto zVG8N;
        NLu5w:
        $bagi_jodoh = $info->tampil_jodohkan / 100;
        goto teXaP;
        jc3FT:
        $bobot_pg = $info->bobot_pg / 100;
        goto nL68y;
        O_6WI:
        $siswas = $this->cbt->getSiswaByKelas($info->id_tp, $info->id_smt, $kelases);
        goto Upn4Y;
        return $update;
        goto BTLDq;
        Fq9os:
        $bobot_isian = $info->bobot_isian / 100;
        goto xwz0v;
        T5R2J:
        foreach ($kelas_bank as $key=>$value) {
            array_push($kelases, $value['kelas_id']);
        }
        goto O_6WI;
        grtvg:
        $jawabans_siswa = [];
        goto OtM6z;
        BshbU:
        $bagi_pg = $info->tampil_pg / 100;
        goto jc3FT;
        U8gQZ:
        $bagi_isian = $info->tampil_isian / 100;
        goto Fq9os;
        gD0gf:
        goto R00P2;
        eKnJ6:
        $insets = [];
        goto c1L4v;
        QNHPG:
        $this->load->model('Cbt_model', 'cbt');
        goto x21iK;
        RSyKB:
        foreach ($siswas as $key=>$value) {
            array_push($ids, $value->id_siswa);
        }
        goto ytjit;
        xwz0v:
        $bagi_essai = $info->tampil_esai / 100;
        goto OgRgd;
        gd6SV:
        goto eKnJ6;
        onaeu:
        $bobot_pg2 = $info->bobot_kompleks / 100;
        goto NLu5w;
        nL68y:
        $bagi_pg2 = $info->tampil_kompleks / 100;
        goto onaeu;
        OtM6z:
        foreach ($jawabans as $jawaban_siswa) {
            goto eJgRv;
            Rwti8:
            $jawaban_siswa->jawaban_benar = array_filter($jawaban_siswa->jawaban_benar, 'strlen');
            ArPJE:
            goto Z2kjL;
            Vd1ec:
            goto w4bmD;
            UXkQL:
            $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
            goto jChOH;
            Z2kjL:
            if ($jawaban_siswa->jenis_soal == '3') {
                goto GTqJv;
                eJgRv:
                if (!($jawaban_siswa->jenis_soal == "\62")) {
                    goto ArPJE;
                }
                goto D_yUV;
                wfbBM:
                $soal[$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
                goto Vd1ec;
                J0YWA:
                $jawaban_siswa->jawaban_benar = array_map('strtoupper', $jawaban_siswa->jawaban_benar);
                goto Rwti8;
                jChOH:
                $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
                goto J0YWA;
                GTqJv:
                $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
                $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
                goto nHmNn;
                rJS_R:
                $jawaban_siswa->jawaban_benar = json_decode(json_encode($jawaban_siswa->jawaban_benar));
                goto vxpKv;
                nHmNn:
                $jawaban_siswa->jawaban_siswa = json_decode(json_encode($jawaban_siswa->jawaban_siswa));
                goto rJS_R;
                vxpKv:
            }
            $jawabans_siswa[$jawaban_siswa->id_siswa][$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
            goto wfbBM;
            D_yUV:
            $jawaban_siswa->opsi_a = @unserialize($jawaban_siswa->opsi_a);
            goto UXkQL;
            w4bmD:
        }
        goto gd6SV;
        FAXr4:
        $soal = [];
        goto grtvg;
        OgRgd:
        $bobot_essai = $info->bobot_esai / 100;
        goto AKCRu;
        ytjit:
        goto sRX8A;
        x21iK:
        $info = $this->cbt->getJadwalById($jadwal);
        goto BshbU;
        BTLDq:
    }
    private function nilaiSignifikansi($jml)
    {
        $list = [3=>[5=>0.997], [1=>0.999], 4=>[5=>0.95], [1=>0.99], 5=>[5=>0.878], [1=>0.959], 6=>[5=>0.8110000000000001], [1=>0.917], 7=>[5=>0.754], [1=>0.874], 8=>[5=>0.707], [1=>0.834], 9=>[5=>0.666], [1=>0.798], 10=>[5=>0.632], [1=>0.765], 11=>[5=>0.602], [1=>0.735], 12=>[5=>0.576], [1=>0.708], 13=>[5=>0.553], [1=>0.6840000000000001], 14=>[5=>0.532], [1=>0.661], 15=>[5=>0.514], [1=>0.641], 16=>[5=>0.497], [1=>0.623], 17=>[5=>0.482], [1=>0.606], 18=>[5=>0.468], [1=>0.59], 19=>[5=>0.456], [1=>0.575], 20=>[5=>0.444], [1=>0.5610000000000001], 21=>[5=>0.433], [1=>0.549], 22=>[5=>0.423], [1=>0.537], 23=>[5=>0.413], [1=>0.526], 24=>[5=>0.404], [1=>0.515], 25=>[5=>0.396], [1=>0.505], 26=>[5=>0.388], [1=>0.496], 27=>[5=>0.381], [1=>0.487], 28=>[5=>0.374], [1=>0.478], 29=>[5=>0.367], [1=>0.47], 30=>[5=>0.361], [1=>0.463], 31=>[5=>0.355], [1=>0.456], 32=>[5=>0.349], [1=>0.449], 33=>[5=>0.344], [1=>0.442], 34=>[5=>0.339], [1=>0.436], 35=>[5=>0.334], [1=>0.43], 36=>[5=>0.329], [1=>0.424], 37=>[5=>0.325], [1=>0.418], 38=>[5=>0.32], [1=>0.413], 39=>[5=>0.316], [1=>0.408], 40=>[5=>0.312], [1=>0.403], 41=>[5=>0.308], [1=>0.398], 42=>[5=>0.304], [1=>0.393], 43=>[5=>0.301], [1=>0.389], 44=>[5=>0.297], [1=>0.384], 45=>[5=>0.294], [1=>0.38], 46=>[5=>0.291], [1=>0.376], 47=>[5=>0.288], [1=>0.372], 48=>[5=>0.284], [1=>0.368], 49=>[5=>0.281], [1=>0.364], 50=>[5=>0.279], [1=>0.361], 55=>[5=>0.266], [1=>0.345], 60=>[5=>0.254], [1=>0.33], 65=>[5=>0.244], [1=>0.317], 70=>[5=>0.235], [1=>0.306], 75=>[5=>0.227], [1=>0.296], 80=>[5=>0.22], [1=>0.286], 85=>[5=>0.213], [1=>0.278], 90=>[5=>0.207], [1=>0.27], 95=>[5=>0.202], [1=>0.263], 100=>[5=>0.195], [1=>0.256], 125=>[5=>0.176], [1=>0.23], 150=>[5=>0.159], [1=>0.21], 175=>[5=>0.149], [1=>0.194], 200=>[5=>0.138], [1=>0.191], 300=>[5=>0.113], [1=>0.181], 400=>[5=>0.098], [1=>0.148], 500=>[5=>0.08799999999999999], [1=>0.128], 600=>[5=>0.08], [1=>0.115], 700=>[5=>0.074], [1=>0.105], 800=>[5=>0.07000000000000001], [1=>0.091], 900=>[5=>0.065], [1=>0.08599999999999999], 1000=>[5=>0.062], [1=>0.081]];
        if (!isset($list[$jml])) {
            $keys = $this->getClosest($jml, array_keys($list));
            if ($keys < 4) {
                $keys = 4;
            }
            if (isset($list[$keys][5])) {
                return $list[$keys][5];
            }
            return $list[$keys][1];
        }
        if (isset($list[$jml][5])) {
            return $list[$jml][5];
        }
        return $list[$jml][1];
    }

    function getClosest($search, $arr)
    {
        $closest = null;
        foreach ($arr as $item) {
            if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                $closest = $item;
            }
        }
        return $closest;
    }
}
