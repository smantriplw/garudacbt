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

    public function index() {
        goto HAq9W;
        CwYO3:
        $this->load->model('Dropdown_model', 'dropdown');
        goto NaJrb;
        UjBc3:
        $data['ruang'] = $this->dropdown->getAllRuang();
        goto Xrl7D;
        HAq9W:
        $this->load->model('Dashboard_model', 'dashboard');
        goto LlP6d;
        r3MXb:
        $this->load->view('cbt/nilai/data');
        goto MD8kY;
        Oeahq:
        $this->db->trans_complete();
        goto oTXZ8;
        Ule0E:
        $data['tp'] = $this->dashboard->getTahun();
        goto yuENm;
        mPTuf:
        $xb = $this->input->get('xb');
        goto AYdFY;
        abbbN:
        $data['jadwal'] = [];
        goto GLHqz;
        PezEr:
        foreach ($jawabans as $jawaban_siswa) {
            goto Y0LLx;
            iL3sn:
            kYAod:
            goto uizBI;
            VvrKY:
            $jawaban_siswa->jawaban = json_decode(json_encode($jawaban_siswa->jawaban));
            goto kCPUu;
            KeUf2:
            $jawaban_siswa->jawaban_benar = array_filter($jawaban_siswa->jawaban_benar, 'strlen');
            goto pU_1F;
            uizBI:
            if ($jawaban_siswa->jenis_soal == '3') {
                goto ASIva;
                Y0LLx:
                if (!($jawaban_siswa->jenis_soal == "\62")) {
                    goto kYAod;
                }
                goto K1lnE;
                OVE3R:
                $jawabans_siswa[$jawaban_siswa->id_siswa][$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
                goto L7DNz;
                kCPUu:
            }
            goto OVE3R;
            paAT_:
            $jawaban_siswa->jawaban_siswa = json_decode(json_encode($jawaban_siswa->jawaban_siswa));
            goto bttyr;
            L7DNz:
            $soal[$jawaban_siswa->jenis_soal][] = $jawaban_siswa;
            goto WLohU;
            pU_1F:
            $jawaban_siswa->jawaban = array_filter($jawaban_siswa->jawaban, 'strlen');
            goto iL3sn;
            tQ20G:
            $jawaban_siswa->jawaban = array_map('strtoupper', $jawaban_siswa->jawaban);
            goto KeUf2;
            WA91w:
            $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
            goto y2EKq;
            K1lnE:
            $jawaban_siswa->opsi_a = @unserialize($jawaban_siswa->opsi_a);
            goto WA91w;
            Qeqlh:
            $jawaban_siswa->jawaban_benar = array_map('strtoupper', $jawaban_siswa->jawaban_benar);
            goto tQ20G;
            y2EKq:
            $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
            goto hz1tq;
            A0l8R:
            $jawaban_siswa->jawaban = @unserialize($jawaban_siswa->jawaban);
            goto paAT_;
            ynXo6:
            $jawaban_siswa->jawaban_benar = @unserialize($jawaban_siswa->jawaban_benar);
            goto A0l8R;
            ASIva:
            $jawaban_siswa->jawaban_siswa = @unserialize($jawaban_siswa->jawaban_siswa);
            goto ynXo6;
            bttyr:
            $jawaban_siswa->jawaban_benar = json_decode(json_encode($jawaban_siswa->jawaban_benar));
            goto VvrKY;
            WLohU:
            goto bqiPl;
            hz1tq:
            $jawaban_siswa->jawaban = @unserialize($jawaban_siswa->jawaban);
            goto Qeqlh;
            bqiPl:
        }
        goto onFbU;
        NaJrb:
        $this->load->model('Kelas_model', 'kelas');
        goto MxFFb;
        A3gNh:
        $bagi_pg = $info->tampil_pg / 100;
        goto hC2UZ;
        mglFA:
        $smt = $this->dashboard->getSemesterActive();
        goto Ule0E;
        JFKpc:
        $jadwal_selected = $this->input->get('jadwal');
        goto uqYd8;
        gVNWH:
        $bagi_jodoh = $info->tampil_jodohkan / 100;
        goto Fgo_C;
        R1Rpg:
        ENW23:
        goto Oeahq;
        iKpzl:
        uT6Vy:
        goto gLDxI;
        Vi8f2:
        $data['profile'] = $this->dashboard->getProfileAdmin($user->id);
        goto V84OD;
        oTXZ8:
        if (!($this->ion_auth->is_admin())) {
            goto HnlDx;
            TkhNr:
            $logs = $this->cbt->getLogUjianByJadwal($jadwal_selected);
            goto A1w8r;
            KyydF:
        }
        goto Vi8f2;
        cC26o:
        $data['kelas'] = $arrKelas;
        goto v0O_y;
        OOoST:
        $data['siswas'] = $siswas;
        goto i9wGe;
        lPVw8:
        $this->load->view('_templates/dashboard/_header', $data);
        goto nVI_0;
        dZ9R0:
        goto vhbgJ;
        goto KyydF;
        dxnIl:
        QaDqQ:
        goto MB3rz;
        HDl9Y:
        $data['guru'] = $guru;
        goto ei_bG;
        S2bd_:
        goto Q0bat;
        S3vD8:
        goto TRNz4;
        MD8kY:
        $this->load->view('members/guru/templates/footer');
        goto dZ9R0;
        JTuye:
        GUblq:
        goto cC26o;
        f9H4y:
        foreach ($mapel as $m) {
            goto e7gPF;
            wJVLI:
            goto V_Eq0;
            e7gPF:
            $arrMapel[$m->id_mapel] = $m->nama_mapel;
            foreach ($m->kelas_mapel as $kls) {
                goto NsXbB;
                UotNt:
                $arrKelas[$kls->kelas] = $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $kls->kelas);
                goto ZyXfl;
                iaJIU:
                goto arovb;
                ZyXfl:
                men0a:
                goto iaJIU;
                NsXbB:
                if (!$kls->kelas) {
                    goto men0a;
                }
                goto UotNt;
                arovb:
            }
            goto wJVLI;
            V_Eq0:
        }
        goto IW4oH;
        hC2UZ:
        $bobot_pg = $info->bobot_pg / 100;
        goto ZZ3u2;
        i9wGe:
        if ($ya != null) {
            goto XxSGZ;
            bWE01:
            $ya = $this->input->get('ya');
            goto S1dxX;
            egAIH:
            $data['smt'] = $this->dashboard->getSemester();
            goto V0aja;
            RFQgE:
            goto QaDqQ;
            goto b2aGV;
            vtsC7:
            $data['jadwal'] = $jdwl;
            goto R1Rpg;
            vDj8R:
            $durasies = $this->cbt->getDurasiSiswaByJadwal($jadwal_selected);
            goto TkhNr;
            nVI_0:
            $this->load->view('cbt/nilai/data');
            goto oC_gV;
            S1dxX:
            $yb = $this->input->get('yb');
            goto FUCLl;
            V84OD:
            $data['jadwal_selected'] = $jadwal_selected;
            goto gqMMv;
            DV7bG:
            $data['info'] = $info;
            goto OOoST;
            y54s5:
            $bobot_pg2 = $info->bobot_kompleks / 100;
            goto gVNWH;
            TRNz4:
            $jadwals = $this->cbt->getAllJadwal($tp->id_tp, $smt->id_smt, $id_guru);
            goto aMDfe;
            DASRZ:
            goto ENW23;
            goto iKpzl;
            MxFFb:
            $user = $this->ion_auth->user()->row();
            goto xa2uG;
            uPnDX:
            $kelas_bank = unserialize($info->bank_kelas);
            goto jpA91;
            WTEbP:
            $data['jadwal_selected'] = $jadwal_selected;
            goto tNHOV;
            WD4Ya:
            goto vtsC7;
            v0O_y:
            $this->load->view('members/guru/templates/header', $data);
            goto r3MXb;
            Q0bat:
            $jawabans = $this->cbt->getJawabanSiswaByJadwal($jadwal_selected, $ids);
            goto CQj9B;
            yuENm:
            $data['tp_active'] = $tp;
            goto egAIH;
            vhMl_:
            $mapel = json_decode(json_encode(unserialize($mapel_guru->mapel_kelas)));
            goto WTEbP;
            lwJNL:
            $siswas = $this->cbt->getSiswaByKelas($tp->id_tp, $smt->id_smt, $kelas_selected);
            goto jkbqv;
            NJgR_:
            foreach ($siswas as $key=>$value) {
                array_push($ids, $value->id_siswa);
            }
            goto S2bd_;
            onFbU:
            goto vDj8R;
            IW4oH:
            goto JTuye;
            ei_bG:
            $id_guru = $guru->id_guru;
            goto dxnIl;
            VOwrd:
            $guru = $this->dashboard->getDataGuruByUserId($user->id, $tp->id_tp, $smt->id_smt);
            goto HDl9Y;
            oC_gV:
            $this->load->view('_templates/dashboard/_footer');
            goto EDYef;
            V0aja:
            $data['smt_active'] = $smt;
            goto UjBc3;
            tNHOV:
            $arrKelas = [];
            goto d1fiH;
            gqMMv:
            $data['kelas'] = $this->dropdown->getAllKelas($tp->id_tp, $smt->id_smt);
            goto lPVw8;
            ogX0c:
            foreach ($kelas_bank as $key=>$value) {
                if ($value['kelas_id'] != '') {
                    goto tfQ5E;
                    S2WRO:
                }
                goto sZ4dg;
                tfQ5E:
                $kelases[$value['kelas_id']] = $this->dropdown->getNamaKelasById($tp->id_tp, $smt->id_smt, $value['kelas_id']);
                goto S2WRO;
                sZ4dg:
            }
            goto S3vD8;
            eP7jV:
            foreach ($jadwals as $jadwal) {
                goto Wb18v;
                QuTgy:
                goto ldA2_;
                Wb18v:
                $kls = unserialize($jadwal->bank_kelas);
                goto KukYO;
                NMUGc:
                goto QuTgy;
                KukYO:
                foreach ($kls as $kl) {
                    goto NHDFf;
                    cL0eb:
                    goto siphY;
                    fP0Bi:
                    SFWyZ:
                    goto cL0eb;
                    NHDFf:
                    if (!($kl['kelas_id'] == $kelas_selected)) {
                        goto SFWyZ;
                    }
                    $jdwl[$jadwal->id_jadwal] = $jadwal->bank_kode;
                    goto fP0Bi;
                    siphY:
                }
                goto NMUGc;
                ldA2_:
            }
            goto WD4Ya;
            GLHqz:
            $data['siswas'] = [];
            goto DASRZ;
            HnlDx:
            $mapel_guru = $this->kelas->getGuruMapelKelas($id_guru, $tp->id_tp, $smt->id_smt);
            goto vhMl_;
            MB3rz:
            if ($jadwal_selected != null) {
                goto uT6Vy;
            }
            goto abbbN;
            XxSGZ:
            $convert = ['ya'=>$ya, 'yb'=>$yb, 'xa'=>$xa, 'xb'=>$xb];
            goto gO6sm;
            aMDfe:
            $jdwl = [];
            goto eP7jV;
            EDYef:
            vhbgJ:
            goto pJdp3;
            LlP6d:
            $this->load->model('Cbt_model', 'cbt');
            goto CwYO3;
            AYdFY:
            if (!($this->ion_auth->in_group('guru'))) {
                goto aHoFc;
                vYcjO:
                $bobot_isian = $info->bobot_isian / 100;
                goto sYmP6;
                gLDxI:
                $info = $this->cbt->getJadwalById($jadwal_selected);
                goto A3gNh;
                aHoFc:
                $id_guru = null;
                goto RFQgE;
                A1w8r:
                foreach ($siswas as $siswa) {
                    goto fI9eG;
                    RU8YQ:
                    kB3yW:
                    goto NimjG;
                    BtT92:
                    $otomatis_jod = 0;
                    goto OIaPb;
                    tDsaK:
                    foreach ($logs as $log) {
                        goto RclP4;
                        iVmVZ:
                        $selesai = date('H:i', strtotime($log->log_time));
                        $sudahSelesai = true;
                        goto q5z_Q;
                        k9oEB:
                        paQH3:
                        goto vnpSF;
                        W76XL:
                        goto KMLP_;
                        goto k9oEB;
                        J5YO1:
                        $sudahMulai = false;
                        goto XiriM;
                        z3bKk:
                        if ($log->log_type == "\61") {
                            goto paQH3;
                        }
                        goto WePWh;
                        WGVoh:
                        $loading = $sudahSelesai ? '<i class="fa fa-check"></i> ' : ($sudahMulai ? '<i class="fa fa-spinner fa-spin"></i> ' : '');
                        goto kPF33;
                        OuYQZ:
                        $mulai = date('H:i', strtotime($log->log_time));
                        goto dISPE;
                        q5z_Q:
                        CXg2H:
                        goto W76XL;
                        dISPE:
                        $sudahMulai = true;
                        goto hYpmg;
                        vnpSF:
                        if ($log != null) {
                            goto OuYQZ;
                            hYpmg:
                        }
                        goto vxLm6;
                        kPF33:
                        yaQSA:
                        goto Jcc1R;
                        RclP4:
                        if (!($log->id_siswa == $siswa->id_siswa)) {
                            goto yaQSA;
                        }
                        goto J5YO1;
                        vxLm6:
                        KMLP_:
                        goto WGVoh;
                        WePWh:
                        if (!($log != null)) {
                            goto CXg2H;
                        }
                        goto iVmVZ;
                        XiriM:
                        $sudahSelesai = false;
                        goto z3bKk;
                        Jcc1R:
                    }
                    goto wJ_Ek;
                    FjwP4:
                    $n++;
                    goto o2Twf;
                    OIaPb:
                    $benar_jod = 0;
                    goto x56B7;
                    akBsM:
                    h1FrH:
                    goto S4iOB;
                    x56B7:
                    if (!($info->tampil_jodohkan > 0)) {
                        goto v7ZL7;
                    }
                    goto nHWhe;
                    E0Zzu:
                    $benar_pg2 = 0;
                    goto Govjk;
                    LJUWN:
                    if ($nilai_input != null && $nilai_input->jodohkan_nilai != null) {
                        goto eLFLy;
                        eda5L:
                        if (!($info->tampil_esai > 0)) {
                            goto ezab5;
                        }
                        goto Fwv7t;
                        tVN4M:
                        $s_pg2 = $bagi_pg2 == 0 ? 0 : $benar_pg2 / $bagi_pg2 * $bobot_pg2;
                        goto AtStC;
                        iiNCU:
                        $siswa->jawaban_pg = $arrJawabanPg;
                        goto Cucbg;
                        i4icw:
                        Ifnes:
                        goto KuqEO;
                        S4iOB:
                        if (!($total < $xb)) {
                            goto Awpcc;
                        }
                        goto UojEf;
                        gAD_x:
                        $benar_es = 0;
                        goto eda5L;
                        sydWK:
                        $siswa->skor_kompleks = round($skor_pg2, 2);
                        goto sowvz;
                        cVPLN:
                        $s_is = $bagi_isian == 0 ? 0 : $benar_is / $bagi_isian * $bobot_isian;
                        goto sT4Xc;
                        P84fz:
                        $skor_koreksi_es = 0.0;
                        goto Po2TL;
                        CB_j7:
                        $arrJawabanPg = [];
                        goto J0fyG;
                        iVecl:
                        $input_jod = 0;
                        goto LJUWN;
                        QpRRM:
                    }
                    goto ursDV;
                    iO_dy:
                    if (!($total > $xa)) {
                        goto h1FrH;
                    }
                    goto VhJYC;
                    lubOm:
                    $selesai = '- -  :  - -';
                    goto tDsaK;
                    Po2TL:
                    $otomatis_es = 0;
                    goto gAD_x;
                    cQtxt:
                    goto bAh4m;
                    KSEMQ:
                    $skor_es = $input_es != 0 ? $input_es : ($otomatis_es == 0 ? $s_es : $skor_koreksi_es);
                    goto thvza;
                    uRblB:
                    icCkt:
                    goto KSEMQ;
                    yFPLB:
                    yTawM:
                    goto iiNCU;
                    gAMrr:
                    sX9nn:
                    goto qjx5Z;
                    ghXyD:
                    $benar_is = 0;
                    goto u67lU;
                    YIIc9:
                    if (!($info->tampil_pg > 0)) {
                        goto yTawM;
                    }
                    goto NhpvV;
                    sowvz:
                    $jawaban_jodoh = $ada_jawaban_jodoh ? $jawabans_siswa[$siswa->id_siswa]["\63"] : [];
                    goto w0ABQ;
                    e7W7V:
                    $siswa->skor_isian = round($skor_is, 2);
                    goto UDeR0;
                    Ty98W:
                    $benar_pg = 0;
                    goto uDVs_;
                    TrJon:
                    $lamanya = '';
                    goto X1BDa;
                    wIgsv:
                    if ($nilai_input != null && $nilai_input->isian_nilai != null) {
                        goto XWwxL;
                        iQ4wT:
                        foreach ($jawaban_pg as $num=>$jwb_pg) {
                            goto qX4IZ;
                            kR1Qq:
                            $benar = true;
                            goto YRDVu;
                            hrHok:
                            if (!(strtoupper($jwb_pg->jawaban_siswa) == strtoupper($jwb_pg->jawaban))) {
                                goto h9jVQ;
                                KCpAQ:
                                goto XRbcu;
                                qZhZ1:
                                $arrJawabanPg[$num] = ['jawaban'=>strtoupper($jwb_pg->jawaban_siswa), 'benar'=>$benar];
                                goto KCpAQ;
                                iY3Dw:
                            }
                            goto woQWP;
                            qX4IZ:
                            $benar = false;
                            goto t9xEJ;
                            jIDsE:
                            Bdu1N:
                            goto qZhZ1;
                            YRDVu:
                            RVYjF:
                            goto jIDsE;
                            GSUm1:
                            goto RVYjF;
                            goto iY3Dw;
                            t9xEJ:
                            if (!($jwb_pg != null && $jwb_pg->jawaban_siswa != null)) {
                                goto Bdu1N;
                            }
                            goto hrHok;
                            h9jVQ:
                            $benar = false;
                            goto GSUm1;
                            woQWP:
                            $benar_pg += 1;
                            goto kR1Qq;
                            XRbcu:
                        }
                        goto Wy6gY;
                        wxB4p:
                        $skor_pg2 = $input_pg2 != 0 ? $input_pg2 : ($otomatis_pg2 == 0 ? $s_pg2 : $skor_koreksi_pg2);
                        goto sydWK;
                        CVMij:
                        goto FjwP4;
                        c5T7k:
                        goto cVj9O;
                        nHWhe:
                        foreach ($jawaban_jodoh as $num=>$jawab_jod) {
                            $skor_koreksi_jod += $jawab_jod->nilai_koreksi;
                            goto X_evA;
                            thFXn:
                            $headJawab = array_shift($arrJawab);
                            goto tZtni;
                            yYDvr:
                            goto nKnBt;
                            FgUQu:
                            $soal[3][$ks]->point = $point;
                            goto s8yQ5;
                            tGyy1:
                            $soal[3][$ks]->tabel_benar = $arrBenar;
                            goto cyTQf;
                            nKnBt:
                            $benar_jod += 1 / $items * $item_benar;
                            goto ERWZa;
                            ikawe:
                            $soal[3][$ks]->tabel_jawab = $arrJwbJawab;
                            goto SP8oX;
                            X_evA:
                            $typeSoal = $jawab_jod->jawaban->type;
                            goto xPkFy;
                            mWHbh:
                            goto tGyy1;
                            ERWZa:
                            $point_benar = $info->bobot_jodohkan > 0 ? round($info->bobot_jodohkan / $info->tampil_jodohkan, 2) : 0;
                            goto pcvSv;
                            L_FYm:
                            goto ikawe;
                            s8yQ5:
                            XkWhd:
                            goto adt7m;
                            vjPu7:
                            $item_lebih = 0;
                            goto RtwbJ;
                            CgESy:
                            $point = round($point_soal, 2);
                            goto hFMyz;
                            adt7m:
                            $soal[3][$ks]->point_koreksi = $jawab_jod->nilai_koreksi;
                            goto yiclt;
                            SP8oX:
                            $arrBenar = [];
                            goto jQ4QF;
                            lVCPA:
                            $headSoal = array_shift($arrSoal);
                            goto csbhx;
                            g1JaC:
                            foreach ($arrJwbSoal as $ps=>$ajj) {
                                if (isset($ajj->subtitle)) {
                                    goto ITWR8;
                                    ioDH2:
                                    $kk = 0;
                                    foreach ($ajj->subtitle as $pps=>$aj) {
                                        goto uOFaF;
                                        wluK2:
                                        $item_kurang++;
                                        goto F7jMT;
                                        uOFaF:
                                        if (isset($arrJwbJawab[$ps]) && !isset($arrJwbJawab[$ps]->subtitle[$pps])) {
                                            goto f93xw;
                                            MzZPV:
                                            $arrBenar[$ps]->kurang = $kk;
                                            goto wluK2;
                                            f93xw:
                                            $kk++;
                                            goto MzZPV;
                                            F7jMT:
                                        }
                                    }
                                    goto Mn8rG;
                                    Bj03h:
                                    bMG4K:
                                    goto ioDH2;
                                    ITWR8:
                                    $point_subitem = $point_item / count((array)$ajj->subtitle);
                                    goto wX2aO;
                                    HUgsQ:
                                    $point_soal += $point_subitem * $arrBenar[$ps]->benar;
                                    goto Bj03h;
                                    KS8nT:
                                    goto lct14;
                                    Mn8rG:
                                    goto RkME2;
                                    wX2aO:
                                    if (!isset($arrBenar[$ps]->benar)) {
                                        goto bMG4K;
                                    }
                                    goto HUgsQ;
                                    RkME2:
                                }
                                goto KS8nT;
                                lct14:
                            }
                            goto mWHbh;
                            jQ4QF:
                            $item_benar = 0;
                            goto actrA;
                            yiclt:
                            $soal[3][$ks]->point_otomatis = $point;
                            goto lHWDt;
                            KkJA6:
                            goto PdlAc;
                            iqv7p:
                            goto XkWhd;
                            goto qOdcE;
                            RtwbJ:
                            foreach ($arrJwbJawab as $p=>$ajjs) {
                                goto diXwu;
                                XP_8i:
                                $bb = 0;
                                goto sKOw3;
                                awOki:
                                goto Ifakd;
                                Q0on3:
                                if (isset($ajjs->subtitle)) {
                                    goto kdIEq;
                                    nlFgt:
                                }
                                goto awOki;
                                kdIEq:
                                foreach ($ajjs->subtitle as $pp=>$ajs) {
                                    goto GrODa;
                                    uwyUY:
                                    $item_benar++;
                                    goto uS30q;
                                    GrODa:
                                    if (isset($arrJwbSoal[$p]) && !isset($arrJwbSoal[$p]->subtitle[$pp])) {
                                        goto W5SuL;
                                        leig7:
                                        glcat:
                                        goto AAwK1;
                                        lzUSu:
                                        $item_salah++;
                                        goto PPJvL;
                                        gzYD6:
                                    }
                                    goto QzOBT;
                                    uS30q:
                                    FLBLy:
                                    goto dLKGe;
                                    HkDG4:
                                    if (in_array($ajs, $arrJwbSoal[$p]->subtitle)) {
                                        goto glcat;
                                    }
                                    goto iCrp9;
                                    TuztU:
                                    $item_lebih++;
                                    goto gzYD6;
                                    HK41l:
                                    $arrBenar[$p]->salah = $ss;
                                    goto lzUSu;
                                    iCrp9:
                                    $ss++;
                                    goto HK41l;
                                    QzOBT:
                                    if (isset($arrJwbSoal[$p]) && isset($arrJwbSoal[$p]->subtitle)) {
                                        goto HkDG4;
                                        AAwK1:
                                        $bb++;
                                        goto qcfpx;
                                        Iee8f:
                                        $arrBenar[$p]->lebih = $ll;
                                        goto TuztU;
                                        W5SuL:
                                        $ll++;
                                        goto Iee8f;
                                        KJfkc:
                                        goto ai1Xw;
                                        qcfpx:
                                        $arrBenar[$p]->benar = $bb;
                                        goto uwyUY;
                                        PPJvL:
                                        goto FLBLy;
                                        goto leig7;
                                        dLKGe:
                                    }
                                    goto KJfkc;
                                    ai1Xw:
                                }
                                goto p8rJ0;
                                yXCN2:
                                $arrBenar[$p] = new stdClass();
                                goto Q0on3;
                                p8rJ0:
                                goto nlFgt;
                                sKOw3:
                                $ss = 0;
                                goto yXCN2;
                                diXwu:
                                $ll = 0;
                                goto XP_8i;
                                Ifakd:
                            }
                            goto yYDvr;
                            actrA:
                            $item_salah = 0;
                            goto vjPu7;
                            Fbeyd:
                            $arrJawab = [];
                            goto rheHr;
                            pVFe9:
                            $soal[3][$ks]->tabel_soal = $arrJwbSoal;
                            goto Fbeyd;
                            qOdcE:
                            WsA5P:
                            goto FgUQu;
                            csbhx:
                            $arrJwbSoal = [];
                            goto m2WSC;
                            tZtni:
                            kjwaO:
                            goto kTGuL;
                            abZgc:
                            $point_soal = 0;
                            goto g1JaC;
                            xPkFy:
                            $arrSoal = $jawab_jod->jawaban->jawaban;
                            goto lVCPA;
                            rheHr:
                            if (!isset($jawab_jod->jawaban_siswa->jawaban)) {
                                goto kjwaO;
                            }
                            goto gwWGs;
                            Vjd0Q:
                            foreach ($arrJawab as $kolJawab) {
                                goto ij2Ee;
                                kJdMx:
                                array_push($arrJwbJawab, $jwbs);
                                goto D6Jwt;
                                TxfS4:
                                foreach ($kolJawab as $po=>$kol) {
                                    goto x02IQ;
                                    dv4Qn:
                                    $jwbs->subtitle[] = $sub;
                                    goto BpvpU;
                                    Qy1iE:
                                    goto lTn9d;
                                    BpvpU:
                                    U0s2R:
                                    goto Qy1iE;
                                    x02IQ:
                                    if (!($kol == "\61")) {
                                        goto U0s2R;
                                    }
                                    $sub = $headJawab[$po];
                                    goto dv4Qn;
                                    lTn9d:
                                }
                                goto ALi1t;
                                D6Jwt:
                                goto RYDFS;
                                ij2Ee:
                                $jwbs = new stdClass();
                                goto TxfS4;
                                ALi1t:
                                $jwbs->title = array_shift($kolJawab);
                                goto kJdMx;
                                RYDFS:
                            }
                            goto L_FYm;
                            cyTQf:
                            $soal[3][$ks]->point_soal = $point_soal;
                            goto CgESy;
                            hmx2p:
                            $soal[3][$ks]->type_soal = $typeSoal;
                            goto pVFe9;
                            PdlAc:
                            $ks = array_search($jawab_jod->nomor_soal, array_column($soal[3], 'nomor_soal'));
                            goto hmx2p;
                            AiD88:
                            goto T9fzU;
                            gNSTf:
                            foreach ($arrSoal as $kolSoal) {
                                goto n7nhM;
                                QplaK:
                                array_push($arrJwbSoal, $jwb);
                                goto qN_RE;
                                aCYzV:
                                $jwb->title = array_shift($kolSoal);
                                goto QplaK;
                                qN_RE:
                                goto RVRdY;
                                NkfNd:
                                goto aCYzV;
                                n7nhM:
                                $jwb = new stdClass();
                                foreach ($kolSoal as $pos=>$kol) {
                                    if ($kol == '1') {
                                        goto vd03e;
                                        BALWq:
                                        $items++;
                                    }
                                    goto cI7xj;
                                    vd03e:
                                    $jwb->subtitle[] = $headSoal[$pos];
                                    goto BALWq;
                                    cI7xj:
                                }
                                goto NkfNd;
                                RVRdY:
                            }
                            goto KkJA6;
                            RZZAn:
                            $soal[3][$ks]->point = $jawab_jod->nilai_koreksi;
                            goto iqv7p;
                            pcvSv:
                            $point_item = $point_benar / count($arrSoal);
                            goto ao91u;
                            hFMyz:
                            if ($jawab_jod->nilai_otomatis == "\60") {
                                goto WsA5P;
                            }
                            goto RZZAn;
                            kTGuL:
                            $arrJwbJawab = [];
                            goto Vjd0Q;
                            lHWDt:
                            $otomatis_jod = $jawab_jod->nilai_otomatis;
                            goto AiD88;
                            gwWGs:
                            $arrJawab = $jawab_jod->jawaban_siswa->jawaban;
                            goto thFXn;
                            ao91u:
                            $item_kurang = 0;
                            goto abZgc;
                            m2WSC:
                            $items = 0;
                            goto gNSTf;
                            T9fzU:
                        }
                        goto kWSn6;
                        UojEf:
                        $xb = $total;
                        goto Logn5;
                        vkkYR:
                        $arrJawabanPg[$n + 1] = ['jawaban'=>'', 'benar'=>false];
                        goto CVMij;
                        ix21g:
                        dbqXf:
                        goto zKi79;
                        thvza:
                        $siswa->skor_essai = round($skor_es, 2);
                        goto N0vi0;
                        Cucbg:
                        $siswa->skor_pg = round($skor_pg, 2);
                        goto Laam2;
                        o2Twf:
                        goto dbqXf;
                        goto RU8YQ;
                        zKi79:
                        if (!($n < $info->tampil_pg)) {
                            goto kB3yW;
                        }
                        goto vkkYR;
                        Fwv7t:
                        foreach ($jawaban_es as $num=>$jawab_es) {
                            goto rOYIG;
                            JsFuB:
                            IOCH8:
                            goto StaTA;
                            iVvty:
                            FgBqB:
                            $ks = array_search($jawab_es->nomor_soal, array_column($soal[5], 'nomor_soal'));
                            goto LWfk6;
                            rOYIG:
                            $skor_koreksi_es += (int)$jawab_es->nilai_koreksi;
                            goto e38Vh;
                            Ouapu:
                            $soal[5][$ks]->point_koreksi = $jawab_es->nilai_koreksi;
                            goto GMsIH;
                            P7j8E:
                            if (!$benar) {
                                goto FgBqB;
                            }
                            goto iDYFp;
                            AJJd3:
                            $soal[5][$ks]->point = $jawab_es->nilai_koreksi;
                            goto FHpJ0;
                            LWfk6:
                            $point = !$benar ? 0 : ($info->bobot_esai > 0 ? round($info->bobot_esai / $info->tampil_esai, 2) : 0);
                            goto WiPQ3;
                            StaTA:
                            $soal[5][$ks]->point = $point;
                            goto CPwbC;
                            GMsIH:
                            $soal[5][$ks]->point_otomatis = $point;
                            goto AKBlE;
                            CPwbC:
                            KWNn2:
                            goto Ouapu;
                            iDYFp:
                            $benar_es++;
                            goto iVvty;
                            WiPQ3:
                            if ($jawab_es->nilai_otomatis == "\60") {
                                goto IOCH8;
                            }
                            goto AJJd3;
                            FHpJ0:
                            goto KWNn2;
                            goto JsFuB;
                            R0X1L:
                            goto ZshTo;
                            AKBlE:
                            $otomatis_es = $jawab_es->nilai_otomatis;
                            goto R0X1L;
                            e38Vh:
                            $benar = $jawab_es != null && strtolower($jawab_es->jawaban_siswa) == strtolower($jawab_es->jawaban);
                            goto P7j8E;
                            ZshTo:
                        }
                        goto cQtxt;
                        LpGlC:
                        $ada_jawaban_jodoh = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\63"]);
                        goto HmoCl;
                        cL_vu:
                        goto nDAH8;
                        goto sNCNM;
                        N0vi0:
                        $total = $skor_pg + $skor_pg2 + $skor_jod + $skor_is + $skor_es;
                        goto s80Qv;
                        kWSn6:
                        goto y_tC8;
                        Gac7j:
                        $skor_is = $input_is != 0 ? $input_is : ($otomatis_is == 0 ? $s_is : $skor_koreksi_is);
                        goto e7W7V;
                        vyjBo:
                        hKPCw:
                        goto tVN4M;
                        XWwxL:
                        $input_is = $nilai_input->isian_nilai;
                        goto R7hIm;
                        dQ_mg:
                        foreach ($jawaban_pg2 as $num=>$jawab_pg2) {
                            goto RZcy4;
                            Dr8iJ:
                            $benar_pg2 += 1 / count($jawab_pg2->jawaban) * count($arr_benar);
                            goto FZptl;
                            sHA2g:
                            $point_item = count($jawab_pg2->jawaban) > 0 ? $point_benar / count($jawab_pg2->jawaban) : 0;
                            goto sxxTz;
                            PMz6v:
                            $ks = array_search($jawab_pg2->nomor_soal, array_column($soal[2], 'nomor_soal'));
                            goto uee64;
                            v6Pex:
                            $soal[2][$ks]->point = $point;
                            goto VHnUU;
                            y33UO:
                            if (!($jawab_pg2->nilai_otomatis == "\60")) {
                                goto blyhe;
                                ydWTD:
                                j81j2:
                                if (!(count($jawab_pg2->jawaban) > 0)) {
                                    goto KAdug;
                                }
                                goto Dr8iJ;
                                VHnUU:
                                V0rqu:
                                goto xxhqt;
                                t5ayk:
                                $soal[2][$ks]->point_otomatis = $point;
                                goto D3a4k;
                                PGaF3:
                                goto ydWTD;
                                blyhe:
                                $soal[2][$ks]->point = $jawab_pg2->nilai_koreksi;
                                goto QD5KN;
                                RZcy4:
                                $skor_koreksi_pg2 += $jawab_pg2->nilai_koreksi;
                                goto ab5CA;
                                xxhqt:
                                $soal[2][$ks]->point_koreksi = $jawab_pg2->nilai_koreksi;
                                goto t5ayk;
                                D3a4k:
                                $otomatis_pg2 = $jawab_pg2->nilai_otomatis;
                                goto aigu8;
                                DmTVr:
                                $point_benar = $info->bobot_kompleks > 0 ? round($info->bobot_kompleks / $info->tampil_kompleks, 2) : 0;
                                goto sHA2g;
                                UqBI3:
                            }
                            goto v6Pex;
                            QD5KN:
                            goto V0rqu;
                            goto UqBI3;
                            sxxTz:
                            $pk = $point_item * count($arr_benar);
                            goto PMz6v;
                            ZYbIe:
                            if (!$jawab_pg2->jawaban_siswa) {
                                goto j81j2;
                            }
                            goto Fqw60;
                            uee64:
                            $point = round($pk, 2);
                            goto y33UO;
                            Fqw60:
                            foreach ($jawab_pg2->jawaban_siswa as $js) {
                                goto XTkYf;
                                CaORs:
                                array_push($arr_benar, true);
                                goto AjjoM;
                                rv3ps:
                                goto wABr6;
                                XTkYf:
                                if (in_array($js, $jawab_pg2->jawaban)) {
                                    goto CaORs;
                                    AjjoM:
                                }
                                goto rv3ps;
                                wABr6:
                            }
                            goto PGaF3;
                            aigu8:
                            goto Vbfli;
                            FZptl:
                            KAdug:
                            goto DmTVr;
                            ab5CA:
                            $arr_benar = [];
                            goto ZYbIe;
                            Vbfli:
                        }
                        goto RK9C5;
                        awMEg:
                        $ada_jawaban = isset($jawabans_siswa[$siswa->id_siswa]);
                        goto vlVH3;
                        RK9C5:
                        goto vyjBo;
                        sNCNM:
                        Z2t2i:
                        goto iO_dy;
                        qjx5Z:
                        $skor_pg = $benar_pg / $bagi_pg * $bobot_pg;
                        goto yFPLB;
                        Kod2t:
                        nDAH8:
                        goto c5T7k;
                        tFT3T:
                        $siswa->lama_ujian = $loading . $dur_siswa;
                        goto vUknl;
                        mVobK:
                        goto EkCqu;
                        nuNzp:
                        $s_es = $bagi_essai == 0 ? 0 : $benar_es / $bagi_essai * $bobot_essai;
                        goto tK3cg;
                        jjXH6:
                        $siswa->mulai_ujian = $mulai;
                        goto CTHLE;
                        PzrX7:
                        $skor_koreksi_is = 0.0;
                        goto oWQN7;
                        Govjk:
                        $skor_koreksi_pg2 = 0.0;
                        goto A73gz;
                        zHYdM:
                        AZOjo:
                        goto wxB4p;
                        I87dE:
                        $input_pg2 = $nilai_input->kompleks_nilai;
                        goto zHYdM;
                        Wy6gY:
                        goto gAMrr;
                        Logn5:
                        Awpcc:
                        $siswa->skor_katrol = round(($ya - $yb) / 100 * $total + $yb, 2);
                        goto Kod2t;
                        vcdcy:
                        $siswa->skor_jodohkan = round($skor_jod, 2);
                        goto aN0wc;
                        Laam2:
                        $nilai_input = $this->cbt->getNilaiSiswaByJadwal($jadwal_selected, $siswa->id_siswa);
                        goto vs8PF;
                        wJ_Ek:
                        goto jjXH6;
                        y_tC8:
                        v7ZL7:
                        goto Z0XaG;
                        qpvPi:
                        R1WEC:
                        goto iQ4wT;
                        NimjG:
                        goto sX9nn;
                        goto qpvPi;
                        KuqEO:
                        $jawaban_pg2 = $ada_jawaban_pg2 ? $jawabans_siswa[$siswa->id_siswa]["\62"] : [];
                        goto E0Zzu;
                        YMoVl:
                        $siswa->skor_katrol = '';
                        goto cL_vu;
                        vs8PF:
                        if (!($nilai_input != null)) {
                            goto Ifnes;
                        }
                        goto H1F1T;
                        J0fyG:
                        $jawaban_pg = $ada_jawaban_pg ? $jawabans_siswa[$siswa->id_siswa]["\61"] : [];
                        goto Ty98W;
                        CTHLE:
                        $siswa->selesai_ujian = $selesai;
                        goto tFT3T;
                        EkCqu:
                        $loading = '';
                        goto cYLXO;
                        h0omj:
                        foreach ($jawaban_is as $num=>$jawab_is) {
                            goto kocZZ;
                            Am0FK:
                            $point = !$benar ? 0 : ($info->bobot_isian > 0 ? round($info->bobot_isian / $info->tampil_isian, 2) : 0);
                            goto f2eoY;
                            hAgDc:
                            $benar = $jawab_is != null && strtolower($jawab_is->jawaban_siswa) == strtolower($jawab_is->jawaban);
                            goto y19jG;
                            WJDYY:
                            $soal[4][$ks]->point_koreksi = $jawab_is->nilai_koreksi;
                            goto tI5Y2;
                            uzSNJ:
                            goto x1l1d;
                            goto klPma;
                            iDPhr:
                            x1l1d:
                            goto WJDYY;
                            h4LEc:
                            $otomatis_is = $jawab_is->nilai_otomatis;
                            goto HdiHV;
                            YwvxW:
                            Kijl9:
                            goto lL812;
                            tI5Y2:
                            $soal[4][$ks]->point_otomatis = $point;
                            goto h4LEc;
                            y19jG:
                            if (!$benar) {
                                goto Kijl9;
                            }
                            $benar_is++;
                            goto YwvxW;
                            f2eoY:
                            if (!($jawab_is->nilai_otomatis == '0')) {
                                goto hjqlv;
                                CEkVi:
                                $soal[4][$ks]->point = $point;
                                goto iDPhr;
                                klPma:
                            }
                            goto CEkVi;
                            HdiHV:
                            goto iXUpz;
                            hjqlv:
                            $soal[4][$ks]->point = $jawab_is->nilai_koreksi;
                            goto uzSNJ;
                            lL812:
                            $ks = array_search($jawab_is->nomor_soal, array_column($soal[4], 'nomor_soal'));
                            goto Am0FK;
                            kocZZ:
                            $skor_koreksi_is += $jawab_is->nilai_koreksi;
                            goto hAgDc;
                            iXUpz:
                        }
                        goto momq2;
                        Ym2k8:
                        $ada_jawaban_pg2 = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\62"]);
                        goto LpGlC;
                        BcPeY:
                        if (!($info->tampil_kompleks > 0)) {
                            goto hKPCw;
                        }
                        goto dQ_mg;
                        m6iKW:
                        $ada_jawaban_essai = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['5']);
                        goto CB_j7;
                        s2i5g:
                        GiKxl:
                        goto cVPLN;
                        eLFLy:
                        $input_jod = $nilai_input->jodohkan_nilai;
                        goto QpRRM;
                        F4h9w:
                        $input_es = $nilai_input->essai_nilai;
                        goto uRblB;
                        VhJYC:
                        $xa = $total;
                        goto akBsM;
                        aN0wc:
                        $jawaban_is = $ada_jawaban_isian ? $jawabans_siswa[$siswa->id_siswa]['4'] : [];
                        goto PzrX7;
                        AtStC:
                        $input_pg2 = 0;
                        if (!($nilai_input != null && $nilai_input->kompleks_nilai != null)) {
                            goto AZOjo;
                        }
                        goto I87dE;
                        uDVs_:
                        $skor_pg = 0;
                        goto YIIc9;
                        HmoCl:
                        $ada_jawaban_isian = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\64"]);
                        goto m6iKW;
                        UDeR0:
                        $jawaban_es = $ada_jawaban_essai ? $jawabans_siswa[$siswa->id_siswa]['5'] : [];
                        goto P84fz;
                        R7hIm:
                    }
                    goto Gac7j;
                    w0ABQ:
                    $skor_koreksi_jod = 0.0;
                    goto BtT92;
                    fI9eG:
                    $dur_siswa = '';
                    goto TrJon;
                    s80Qv:
                    $siswa->skor_total = round($total, 2);
                    goto iiNl2;
                    vlVH3:
                    $ada_jawaban_pg = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]['1']);
                    goto Ym2k8;
                    cYLXO:
                    $mulai = '- -  :  - -';
                    goto lubOm;
                    Z0XaG:
                    $s_jod = $bagi_jodoh == 0 ? 0 : $benar_jod / $bagi_jodoh * $bobot_jodoh;
                    goto iVecl;
                    A73gz:
                    $otomatis_pg2 = 0;
                    goto BcPeY;
                    iV2s9:
                    $n = 0;
                    goto ix21g;
                    bAh4m:
                    ezab5:
                    goto nuNzp;
                    vUknl:
                    $siswa->durasi_ujian = $lamanya;
                    goto awMEg;
                    ursDV:
                    $skor_jod = $input_jod != 0 ? $input_jod : ($otomatis_jod == 0 ? $s_jod : $skor_koreksi_jod);
                    goto vcdcy;
                    NhpvV:
                    if (count($jawaban_pg) > 0) {
                        goto R1WEC;
                    }
                    goto iV2s9;
                    H1F1T:
                    $siswa->dikoreksi = $nilai_input->dikoreksi;
                    goto i4icw;
                    momq2:
                    goto s2i5g;
                    iiNl2:
                    if ($ya != null) {
                        goto Z2t2i;
                    }
                    goto YMoVl;
                    X1BDa:
                    foreach ($durasies as $durasi) {
                        goto raCvd;
                        hA04H:
                        $em = $elap[1] == '00' ? '' : intval($elap[1]) + $ed . 'm';
                        $dd = $ej . $em;
                        goto UCicM;
                        QQBIb:
                        $dur_siswa = round($mins, 2) . ' m';
                        goto c8dlT;
                        diPn9:
                        goto rxTNN;
                        SJTFu:
                        mlYLo:
                        goto gFw_M;
                        MBtPw:
                        jgq2R:
                        goto enkcC;
                        dzGuc:
                        $lamanya = $durasi->lama_ujian;
                        goto uuTwY;
                        raCvd:
                        if ($durasi->id_siswa == $siswa->id_siswa) {
                            goto RCDU9;
                            gFw_M:
                            $mins = (strtotime($durasi->selesai) - strtotime($durasi->mulai)) / 60;
                            goto QQBIb;
                            RCDU9:
                            if ($durasi->lama_ujian == null) {
                                goto mlYLo;
                            }
                            goto dzGuc;
                            vhM1k:
                            $ej = $elap[0] == "\60\60" ? '' : intval($elap[0]) . 'j ';
                            goto hA04H;
                            RgInN:
                            $ed = $elap[2] == '00' ? 0 : 1;
                            goto vhM1k;
                            UCicM:
                            $dur_siswa = $dd == '' ? '0 m' : $dd;
                            goto Lrkks;
                            P6uqs:
                            $dur_siswa = $durasi->mulai . ' m';
                            goto LtwzF;
                            Lrkks:
                            Kju5V:
                            goto clDtW;
                            LtwzF:
                            goto Kju5V;
                            goto MBtPw;
                            clDtW:
                            goto kHfWx;
                            goto SJTFu;
                            uTn13:
                        }
                        goto diPn9;
                        uuTwY:
                        if (strpos($lamanya, "\72") !== false) {
                            goto jgq2R;
                        }
                        goto P6uqs;
                        enkcC:
                        $elap = explode("\72", $lamanya);
                        goto RgInN;
                        c8dlT:
                        kHfWx:
                        goto uTn13;
                        rxTNN:
                    }
                    goto mVobK;
                    oWQN7:
                    $otomatis_is = 0;
                    goto ghXyD;
                    tK3cg:
                    $input_es = 0;
                    goto F01sP;
                    sT4Xc:
                    $input_is = 0;
                    goto wIgsv;
                    u67lU:
                    if (!($info->tampil_isian > 0)) {
                        goto GiKxl;
                    }
                    goto h0omj;
                    F01sP:
                    if (!($nilai_input != null && $nilai_input->isian_nilai != null)) {
                        goto icCkt;
                    }
                    goto F4h9w;
                    cVj9O:
                }
                goto VWWUT;
                jkbqv:
                $ids = [];
                goto NJgR_;
                b2aGV:
            }
            goto VOwrd;
            jpA91:
            $kelases = [];
            goto ogX0c;
            CQj9B:
            $soal = [];
            goto bbqxq;
            sYmP6:
            $bagi_essai = $info->tampil_esai / 100;
            goto j5_P2;
            LTO9_:
            $tp = $this->dashboard->getTahunActive();
            goto mglFA;
            j5_P2:
            $bobot_essai = $info->bobot_esai / 100;
            goto lwJNL;
            ZZ3u2:
            $bagi_pg2 = $info->tampil_kompleks / 100;
            goto y54s5;
            xa2uG:
            $this->db->trans_start();
            goto Drcts;
            uqYd8:
            $data['kelas_selected'] = $kelas_selected;
            goto bWE01;
            UKYrM:
        }
        goto uPnDX;
        Drcts:
        $data = ['user'=>$user, 'judul'=>'Hasil Ujian Siswa', 'subjudul'=>'Nilai Siswa', 'setting'=>$this->dashboard->getSetting()];
        goto LTO9_;
        gO6sm:
        $data['convert'] = $convert;
        goto UKYrM;
        d1fiH:
        if (!($mapel != null)) {
            goto GUblq;
        }
        goto f9H4y;
        kFQs3:
        $kelas_selected = $this->input->get('kelas');
        goto JFKpc;
        VWWUT:
        goto DV7bG;
        FUCLl:
        $xa = $this->input->get('xa');
        goto mPTuf;
        Fgo_C:
        $bobot_jodoh = $info->bobot_jodohkan / 100;
        $bagi_isian = $info->tampil_isian / 100;
        goto vYcjO;
        bbqxq:
        $jawabans_siswa = [];
        goto PezEr;
        Xrl7D:
        $data['sesi'] = $this->dropdown->getAllSesi();
        goto kFQs3;
        pJdp3:
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
        $jawaban_jodoh = $ada_jawaban_jodoh ? $jawabans_siswa[$siswa->id_siswa]["\63"] : [];
        goto YJ5Qw;
        Nmj74:
        $jawaban_pg2 = $ada_jawaban_pg2 ? $jawabans_siswa[$siswa->id_siswa]["\62"] : [];
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
            if ($jawaban_siswa->jenis_soal == "\63") {
                goto pZiFX;
                GHcwt:
                $jawaban_siswa->jawaban = array_filter($jawaban_siswa->jawaban, 'strlen');
                goto GLFCf;
                R0K2X:
                $jawaban_siswa->jawaban_benar = array_filter($jawaban_siswa->jawaban_benar, 'strlen');
                goto tEfXZ;
                Gmdp1:
                if (!($jawaban_siswa->jenis_soal == "\62")) {
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
            $ada_jawaban_essai = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\65"]);
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
            $jawaban_es = $ada_jawaban_essai ? $jawabans_siswa[$siswa->id_siswa]["\65"] : [];
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
                $ada_jawaban_pg2 = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\62"]);
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
                        if ($jawab_es->nilai_otomatis == "\60") {
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
                    $jawaban_pg = $ada_jawaban_pg ? $jawabans_siswa[$siswa->id_siswa]["\61"] : [];
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
        $ada_jawaban_pg = $ada_jawaban && isset($jawabans_siswa[$siswa->id_siswa]["\61"]);
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
                if ($jawab_is->nilai_otomatis == "\60") {
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
