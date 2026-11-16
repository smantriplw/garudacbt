<?php
$jenisSoal = [
    1 => ["label" => "Pilihan Ganda", "short" => "Ganda", "color" => "#3b82f6"],
    2 => ["label" => "PG Kompleks", "short" => "Kompleks", "color" => "#8b5cf6"],
    3 => ["label" => "Menjodohkan", "short" => "Jodohkan", "color" => "#14b8a6"],
    4 => ["label" => "Isian Singkat", "short" => "Isian", "color" => "#f59e0b"],
    5 => ["label" => "Uraian", "short" => "Uraian", "color" => "#ef4444"],
];
$totalSoal = array_sum($cbt_soal_jenis);
$totalPeserta = max(0, (int) $cbt_peserta_total);
$dikerjakan = (int) $cbt_dikerjakan;
$persenKerja = $totalPeserta > 0 ? min(100, (int) round($dikerjakan / $totalPeserta * 100)) : 0;
$jumlahUjian = 0;
foreach ($cbt_hari_ini as $byMapel) {
    $jumlahUjian += count($byMapel);
}
$ruangTotal = isset($ujian_box[0]) ? (int) $ujian_box[0]->total : 0;
$sesiTotal = isset($ujian_box[1]) ? (int) $ujian_box[1]->total : 0;
$bankTotal = isset($ujian_box[2]) ? (int) $ujian_box[2]->total : 0;
$bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$hariNama = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
$tanggalTeks = $hariNama[(int) date("w")] . ", " . date("j") . " " . $bulan[(int) date("n") - 1] . " " . date("Y");
$chartMapel = [];
foreach ($cbt_bank_mapel as $row) {
    $chartMapel[] = [
        "label" => $row->nama_mapel !== null && trim((string) $row->nama_mapel) !== "" ? $row->nama_mapel : "Tanpa mapel",
        "value" => (int) $row->jumlah,
    ];
}
$kpi = [
    ["label" => "Ujian Hari Ini", "value" => number_format($jumlahUjian), "sub" => "jadwal CBT", "icon" => "desktop", "color" => "primary", "url" => "cbtjadwal"],
    ["label" => "Peserta Hari Ini", "value" => number_format($totalPeserta), "sub" => "siswa terdaftar", "icon" => "users", "color" => "info", "url" => "cbtruang"],
    ["label" => "Sudah Mengerjakan", "value" => number_format($dikerjakan), "sub" => $persenKerja . "% dari peserta", "icon" => "check-circle", "color" => "success", "url" => "cbtnilai"],
    ["label" => "Bank Soal", "value" => number_format($bankTotal), "sub" => "paket ujian", "icon" => "folder", "color" => "warning", "url" => "cbtbanksoal"],
    ["label" => "Butir Soal", "value" => number_format($totalSoal), "sub" => "5 jenis soal", "icon" => "file-alt", "color" => "purple", "url" => "cbtbanksoal"],
    ["label" => "Ruang / Sesi", "value" => number_format($ruangTotal) . " / " . number_format($sesiTotal), "sub" => "ruang ujian", "icon" => "building", "color" => "pink", "url" => "cbtruang"],
];
?>
<style>
    .exec-card { border: 0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, .06); }
    .exec-card .card-header { background: #fff; border-bottom: 1px solid #f1f3f5; }
    .exec-card .card-title { font-size: .95rem; font-weight: 600; }
    .kpi-card { border: 0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, .06); }
    .kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex: 0 0 auto; }
    .kpi-value { font-size: 1.45rem; font-weight: 700; line-height: 1.1; }
    .kpi-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; }
    .kpi-link { display: block; padding: .45rem 1.1rem; border-top: 1px solid #f1f3f5; font-size: .78rem; color: #6c757d; }
    .kpi-link:hover { color: #1a73e8; text-decoration: none; background: #f8f9fa; }
    .bg-soft-primary { background: #e8f0fe; color: #1a73e8; }
    .bg-soft-info { background: #e0f4f9; color: #0c8599; }
    .bg-soft-success { background: #e6f4ea; color: #1e8e3e; }
    .bg-soft-warning { background: #fef3e2; color: #b06000; }
    .bg-soft-purple { background: #f1eafe; color: #6f42c1; }
    .bg-soft-pink { background: #fde8ef; color: #c2185b; }
    .bg-soft-danger { background: #fde8e8; color: #c81e1e; }
    .mini-stat { border: 1px solid #eef1f5; border-radius: 10px; padding: .5rem .6rem; }
    .progress-thin { height: 6px; border-radius: 4px; background: #eef1f5; }
    .scroll-y { max-height: 360px; overflow-y: auto; }
</style>

<div class="content-wrapper bg-light pt-3">
    <section class="content-header pb-1">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h1 class="h4 mb-0">Beranda</h1>
                    <small class="text-muted">Monitoring CBT</small>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge badge-light border mr-1">
                        <i class="fa fa-calendar mr-1"></i><?= html_escape($tanggalTeks) ?>
                    </span>
                    <span class="badge badge-primary">
                        <i class="fa fa-graduation-cap mr-1"></i>TA <?= html_escape($tp_active->tahun) ?>
                        &middot; <?= html_escape($smt_active->nama_smt) ?>
                    </span>
                    <a href="<?= base_url('kelasjadwal') ?>" class="btn btn-sm btn-outline-secondary ml-1">
                        <i class="fa fa-calendar-alt mr-1"></i>Jadwal Pelajaran
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php foreach ($kpi as $item): ?>
                    <div class="col-xl-2 col-md-4 col-6 mb-3 d-flex">
                        <div class="card kpi-card w-100">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <span class="kpi-icon bg-soft-<?= $item["color"] ?>">
                                        <i class="fa fa-<?= $item["icon"] ?>"></i>
                                    </span>
                                    <div class="ml-3">
                                        <div class="kpi-value"><?= $item["value"] ?></div>
                                        <div class="kpi-label"><?= html_escape($item["label"]) ?></div>
                                    </div>
                                </div>
                                <div class="small text-muted mt-2"><?= html_escape($item["sub"]) ?></div>
                            </div>
                            <a class="kpi-link" href="<?= base_url($item["url"]) ?>">
                                Lihat detail <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-3 d-flex">
                    <div class="card exec-card w-100">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-desktop text-primary mr-2"></i>Monitoring Ujian Hari Ini
                            </h3>
                            <div class="card-tools ml-auto">
                                <a href="<?= base_url('cbtjadwal') ?>" class="btn btn-sm btn-outline-secondary">
                                    Jadwal CBT
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center border rounded p-2 mb-3">
                                <span class="kpi-icon bg-soft-warning mr-2"><i class="fa fa-key"></i></span>
                                <div>
                                    <div class="kpi-label">Token Ujian</div>
                                    <div class="h5 mb-0" id="token-view">
                                        <?= $token->token != null ? html_escape($token->token) : "- - - - - -" ?>
                                    </div>
                                </div>
                                <small class="text-muted ml-3 d-none" id="interval">-- : -- : --</small>
                                <a href="<?= base_url('cbttoken') ?>" class="btn btn-sm btn-outline-primary ml-auto">
                                    Kelola Token
                                </a>
                            </div>

                            <?php if (count($cbt_hari_ini) === 0): ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fa fa-calendar-times fa-2x mb-2"></i>
                                    <?php if ((int) $cbt_total_jadwal === 0): ?>
                                        <div>Belum ada jadwal CBT untuk tahun ajaran ini.</div>
                                        <a href="<?= base_url('cbtjadwal') ?>" class="small">Buat jadwal CBT</a>
                                    <?php else: ?>
                                        <div>Tidak ada ujian hari ini.</div>
                                        <a href="<?= base_url('cbtjadwal') ?>" class="small">Lihat jadwal CBT</a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table id="tbl-penilaian" class="table table-sm table-hover mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Mapel</th>
                                            <th>Jenis</th>
                                            <th>Level</th>
                                            <th>Pengawas</th>
                                            <th class="text-right">Peserta</th>
                                            <th style="min-width:150px">Pengerjaan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($cbt_hari_ini as $byMapel): ?>
                                            <?php foreach ($byMapel as $jdw): ?>
                                                <?php
                                                $idJadwal = (int) $jdw->id_jadwal;
                                                $kerja = (int) ($cbt_progress[$idJadwal] ?? 0);
                                                $peserta = max((int) ($cbt_peserta[$idJadwal] ?? 0), $kerja);
                                                $pct = $peserta > 0 ? min(100, (int) round($kerja / $peserta * 100)) : 0;
                                                $namaPengawas = [];
                                                if (isset($pengawas[$idJadwal])) {
                                                    foreach ($pengawas[$idJadwal] as $perRuang) {
                                                        foreach ($perRuang as $pengawasSesi) {
                                                            foreach (explode(",", (string) $pengawasSesi->id_guru) as $idGuru) {
                                                                $idGuru = trim($idGuru);
                                                                if ($idGuru !== "" && isset($gurus[$idGuru])) {
                                                                    $namaPengawas[$idGuru] = $gurus[$idGuru];
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <b><?= html_escape($jdw->kode) ?></b>
                                                        <div class="small text-muted"><?= html_escape($jdw->nama_mapel) ?></div>
                                                    </td>
                                                    <td><?= html_escape($jdw->kode_jenis) ?></td>
                                                    <td><?= html_escape($jdw->bank_level) ?></td>
                                                    <td class="small">
                                                        <?= count($namaPengawas) > 0
                                                            ? html_escape(implode(", ", $namaPengawas))
                                                            : '<span class="text-muted">-</span>' ?>
                                                    </td>
                                                    <td class="text-right"><?= number_format($peserta) ?></td>
                                                    <td>
                                                        <div class="progress progress-thin">
                                                            <div class="progress-bar bg-success" style="width:<?= $pct ?>%"></div>
                                                        </div>
                                                        <small class="text-muted">
                                                            <?= number_format($kerja) ?>/<?= number_format($peserta) ?> (<?= $pct ?>%)
                                                        </small>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3 d-flex">
                    <div class="card exec-card w-100">
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-folder-open text-success mr-2"></i>Komposisi Butir Soal
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($jenisSoal as $key => $meta): ?>
                                <?php
                                $jumlah = (int) ($cbt_soal_jenis[$key] ?? 0);
                                $persen = $totalSoal > 0 ? (int) round($jumlah / $totalSoal * 100) : 0;
                                $lebar = $totalSoal > 0 ? max($jumlah > 0 ? 2 : 0, (int) round($jumlah / $totalSoal * 100)) : 0;
                                ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>
                                            <i class="fa fa-circle mr-1"
                                               style="color:<?= $meta["color"] ?>;font-size:9px"></i>
                                            <?= html_escape($meta["label"]) ?>
                                        </span>
                                        <span>
                                            <b><?= number_format($jumlah) ?></b>
                                            <span class="text-muted">(<?= $persen ?>%)</span>
                                        </span>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar" style="width:<?= $lebar ?>%;background-color:<?= $meta["color"] ?>"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between small border-top pt-2 mt-2">
                                <span>Total butir soal</span>
                                <b><?= number_format($totalSoal) ?></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-3 d-flex">
                    <div class="card exec-card w-100">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-chart-line text-primary mr-2"></i>Traffic 7 Hari Terakhir
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php
                            $trafficHariIni = $traffic["hari_ini"] ?? [];
                            $trafficKpi = [
                                ["Login", $trafficHariIni["login"] ?? 0, "primary", "sign-in-alt"],
                                ["Logout", $trafficHariIni["logout"] ?? 0, "warning", "sign-out-alt"],
                                ["Keluar paksa", $trafficHariIni["paksa"] ?? 0, "danger", "user-slash"],
                                ["Mulai ujian", $trafficHariIni["mulai"] ?? 0, "purple", "play-circle"],
                                ["Selesai ujian", $trafficHariIni["selesai"] ?? 0, "success", "check-circle"],
                            ];
                            ?>
                            <div class="row mb-3">
                                <?php foreach ($trafficKpi as $tk): ?>
                                    <div class="col-6 col-md-4 col-xl mb-2">
                                        <div class="mini-stat">
                                            <div class="d-flex align-items-center">
                                                <span class="kpi-icon bg-soft-<?= $tk[2] ?>"
                                                      style="width:34px;height:34px;font-size:14px">
                                                    <i class="fa fa-<?= $tk[3] ?>"></i>
                                                </span>
                                                <div class="ml-2">
                                                    <div class="font-weight-bold"><?= number_format($tk[1]) ?></div>
                                                    <div class="kpi-label" style="font-size:.65rem"><?= html_escape($tk[0]) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div style="position:relative;height:260px">
                                <canvas id="chartTraffic"></canvas>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Angka di atas adalah hari ini; grafik menjumlahkan event per hari selama 7 hari terakhir.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3 d-flex">
                    <div class="card exec-card w-100">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-history text-info mr-2"></i>Aktivitas Terbaru
                            </h3>
                            <div class="card-tools ml-auto">
                                <button type="button" onclick="hapusLogAktivitas()"
                                        class="btn btn-sm btn-outline-danger" title="Hapus log">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2 scroll-y" id="log-list"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-3 d-flex">
                    <div class="card exec-card w-100">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-chart-bar text-primary mr-2"></i>Bank Soal per Mapel
                            </h3>
                            <div class="card-tools ml-auto">
                                <a href="<?= base_url('cbtbanksoal') ?>" class="btn btn-sm btn-outline-secondary">
                                    Semua bank soal
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (count($chartMapel) === 0): ?>
                                <div class="text-muted small">Belum ada bank soal.</div>
                            <?php else: ?>
                                <div style="position:relative;height:320px">
                                    <canvas id="chartBankMapel"></canvas>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card exec-card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-school text-secondary mr-2"></i>Data Sekolah
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach (array_slice($info_box, 0, 4) as $info): ?>
                                    <div class="col-6 mb-3">
                                        <div class="kpi-label"><?= html_escape($info->title) ?></div>
                                        <div class="h5 mb-0"><?= number_format($info->total) ?></div>
                                        <a href="<?= base_url($info->url) ?>" class="small">Detail</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card exec-card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fa fa-bullhorn text-warning mr-2"></i>Pengumuman
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="konten-pengumuman">
                                <div id="pengumuman"></div>
                                <p id="loading-post" class="text-center d-none">
                                    <br/><i class="fa fa-spinner fa-spin"></i> Loading....
                                </p>
                                <div id="loadmore-post" onclick="getPosts()"
                                     class="text-center mt-4 loadmore d-none">
                                    <div class="btn btn-default">Muat Pengumuman lainnya ...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="komentarModal" tabindex="-1" role="dialog" aria-labelledby="komentarLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="komentarLabel">Tulis Komentar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-fluid img-circle img-sm" src="<?= base_url('assets/img/siswa.png') ?>" alt="Alt Text">
                <div class="img-push">
                    <?= form_open('create', array('id' => 'komentar')) ?>
                    <input type="hidden" id="id-post" name="id_post" value="">
                    <div class="input-group">
                        <input type="text" name="text" placeholder="Tulis komentar ..."
                               class="form-control form-control-sm" required>
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-success btn-sm">Komentari</button>
                        </span>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="balasanModal" tabindex="-1" role="dialog" aria-labelledby="balasanLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="balasanLabel">Tulis Balasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-fluid img-circle img-sm" src="<?= base_url('assets/img/siswa.png') ?>" alt="Alt Text">
                <div class="img-push">
                    <?= form_open('create', array('id' => 'balasan')) ?>
                    <input type="hidden" id="id-comment" name="id_comment" value="">
                    <div class="input-group">
                        <input type="text" name="text" placeholder="Tulis balasan ...."
                               class="form-control form-control-sm" required>
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-success btn-sm">Balas</button>
                        </span>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    adaJadwalUjian = '<?= count($ada_ujian) ?>';
    localStorage.setItem('ada_jadwal_ujian', adaJadwalUjian);
</script>
<script src="<?= base_url() ?>/assets/app/js/jquery.rowspanizer.js"></script>
<script src="<?= base_url() ?>/assets/app/js/dashboard.js"></script>
<script>
    $(function () {
        var mapelData = <?= json_encode($chartMapel) ?>;

        var elMapel = document.getElementById('chartBankMapel');
        if (elMapel && mapelData.length) {
            new Chart(elMapel, {
                type: 'horizontalBar',
                data: {
                    labels: mapelData.map(function (item) { return item.label; }),
                    datasets: [{
                        data: mapelData.map(function (item) { return item.value; }),
                        backgroundColor: '#3b82f6',
                        barThickness: 14
                    }]
                },
                options: {
                    legend: { display: false },
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: { beginAtZero: true, precision: 0 },
                            gridLines: { color: '#eef1f5' }
                        }],
                        yAxes: [{
                            gridLines: { display: false },
                            ticks: { fontSize: 11 }
                        }]
                    }
                }
            });
        }

        var trafficData = <?= json_encode($traffic) ?>;
        var trafficMeta = [
            { key: 'login', label: 'Login', color: '#3b82f6' },
            { key: 'logout', label: 'Logout', color: '#f59e0b' },
            { key: 'paksa', label: 'Keluar paksa', color: '#ef4444' },
            { key: 'mulai', label: 'Mulai ujian', color: '#8b5cf6' },
            { key: 'selesai', label: 'Selesai ujian', color: '#10b981' }
        ];

        var elTraffic = document.getElementById('chartTraffic');
        if (elTraffic && trafficData && trafficData.labels) {
            new Chart(elTraffic, {
                type: 'line',
                data: {
                    labels: trafficData.labels,
                    datasets: trafficMeta.map(function (meta) {
                        return {
                            label: meta.label,
                            data: trafficData[meta.key] || [],
                            borderColor: meta.color,
                            backgroundColor: meta.color,
                            borderWidth: 2,
                            pointRadius: 2,
                            pointHoverRadius: 4,
                            fill: false,
                            tension: 0.3
                        };
                    })
                },
                options: {
                    maintainAspectRatio: false,
                    legend: { position: 'bottom', labels: { boxWidth: 10, fontSize: 11 } },
                    tooltips: { mode: 'index', intersect: false },
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true, precision: 0 },
                            gridLines: { color: '#eef1f5' }
                        }],
                        xAxes: [{ gridLines: { display: false } }]
                    }
                }
            });
        }
    });
</script>
