<?php
$lastSync = !empty($dapodik["last_sync"]) ? date("d/m/Y H:i", strtotime($dapodik["last_sync"])) : null;
?>
<style>
    .dapodik-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex: 0 0 auto; }
    .dapodik-icon-primary { background: #e8f0fe; color: #1a73e8; }
    .dapodik-icon-secondary { background: #eef1f5; color: #5a6b7b; }
    .dapodik-stat-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #f4f6f9; flex: 0 0 auto; }
    .dapodik-table-wrap { max-height: 45vh; overflow: auto; }
    .dapodik-table-wrap thead th { position: sticky; top: 0; background: #f4f6f9; z-index: 2; white-space: nowrap; }
    .dapodik-fail-list { max-height: 260px; overflow: auto; }
    .dapodik-fail { display: flex; justify-content: space-between; gap: 12px; padding: 6px 10px; border-bottom: 1px solid #eee; font-size: 13px; }
    .dapodik-fail:last-child { border-bottom: 0; }
    .dapodik-fail small { text-align: right; color: #6c757d; }
    .dapodik-fail-main { display: flex; flex-direction: column; gap: 2px; }
    .dapodik-fail-name { font-weight: 600; }
    .dapodik-fail-meta .badge { font-weight: 400; font-size: 11px; margin: 1px 3px 0 0; }
    .dapodik-toolbar .form-control, .dapodik-toolbar .custom-select { height: 34px; }
    details > summary { cursor: pointer; }
</style>

<div class="content-wrapper bg-white pt-4">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $judul ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card my-shadow">
                <div class="card-header">
                    <h3 class="card-title mb-0">Pengaturan Koneksi Dapodik</h3>
                    <div class="card-tools">
                        <?php if ($dapodik_ready): ?>
                            <span class="badge badge-success"><i class="fa fa-check mr-1"></i> Siap</span>
                        <?php else: ?>
                            <span class="badge badge-danger"><i class="fa fa-exclamation-triangle mr-1"></i> Belum lengkap</span>
                        <?php endif; ?>
                        <?php if ($lastSync !== null): ?>
                            <span class="badge badge-light ml-1">
                                <i class="fa fa-clock mr-1"></i> Sinkron terakhir <?= html_escape($lastSync) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formDapodik">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dapodik_url">URL Webservice</label>
                                <input type="text" class="form-control" id="dapodik_url" name="dapodik_url"
                                       value="<?= html_escape($dapodik['url']) ?>"
                                       placeholder="https://dapodik.sekolah.sch.id" autocomplete="off">
                                <small class="text-muted">Alamat webservice Dapodik, tanpa garis miring di akhir.</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dapodik_token">Token</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="dapodik_token" name="dapodik_token"
                                           value="<?= html_escape($dapodik['token']) ?>" autocomplete="new-password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="toggleToken"
                                                title="Tampilkan / sembunyikan token">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Token webservice dari Dapodik, dikirim sebagai Bearer token.</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="dapodik_npsn">NPSN</label>
                                <input type="text" class="form-control" id="dapodik_npsn"
                                       value="<?= html_escape($dapodik['npsn']) ?>" readonly>
                                <small class="text-muted">
                                    Diambil dari <a href="<?= base_url('settings') ?>">Profile Sekolah</a>.
                                    <?php if ($dapodik['npsn'] === ''): ?>
                                        NPSN masih kosong, lengkapi dulu agar penarikan data bisa dilakukan.
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                        <?php if (!$dapodik_ready): ?>
                            <div class="alert alert-warning py-2">
                                Lengkapi URL dan token di atas, lalu simpan, sebelum menarik data.
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i> Simpan</button>
                        <button type="button" class="btn btn-outline-secondary" id="testKoneksi">
                            <i class="fa fa-plug mr-1"></i> Tes Koneksi
                        </button>
                    </form>
                </div>
            </div>

            <h5 class="mb-3"><?= html_escape($subjudul) ?></h5>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card my-shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="dapodik-icon dapodik-icon-primary"><i class="fa fa-users"></i></span>
                                <div class="ml-3">
                                    <h5 class="mb-0">Data Siswa</h5>
                                    <small class="text-muted">Peserta didik &amp; rombel</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">
                                Pratinjau menampilkan kelas yang akan dibuat, siswa baru, dan data yang tidak bisa
                                diimpor sebelum penarikan dijalankan.
                            </p>
                            <div class="mt-auto">
                                <button type="button" id="previewSiswa" class="btn btn-primary"
                                        <?= !$dapodik_ready ? 'disabled' : '' ?>>
                                    <i class="fa fa-eye mr-1"></i> Pratinjau Siswa
                                </button>
                                <button type="button" id="dapodikTarikSiswa" class="btn btn-outline-primary"
                                        <?= !$dapodik_ready ? 'disabled' : '' ?>>
                                    <i class="fa fa-download mr-1"></i> Tarik Siswa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <div class="card my-shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="dapodik-icon dapodik-icon-secondary"><i class="fa fa-id-badge"></i></span>
                                <div class="ml-3">
                                    <h5 class="mb-0">Data GTK</h5>
                                    <small class="text-muted">Guru &amp; tenaga kependidikan</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">
                                Pratinjau menampilkan guru baru, data yang akan diperbarui, dan akun login yang
                                akan dibuat sebelum penarikan dijalankan.
                            </p>
                            <div class="mt-auto">
                                <button type="button" id="previewGtk" class="btn btn-secondary"
                                        <?= !$dapodik_ready ? 'disabled' : '' ?>>
                                    <i class="fa fa-eye mr-1"></i> Pratinjau GTK
                                </button>
                                <button type="button" id="dapodikTarikGtk" class="btn btn-outline-secondary"
                                        <?= !$dapodik_ready ? 'disabled' : '' ?>>
                                    <i class="fa fa-download mr-1"></i> Tarik GTK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="previewTitle">Pratinjau</h5>
                        <small class="text-muted" id="previewSubtitle"></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="previewBody"></div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="previewHint"></span>
                    <div>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-success d-none" id="previewPull">
                            <i class="fa fa-download mr-1"></i> Tarik Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        ajaxcsrf();

        const dapodikReady = <?= $dapodik_ready ? 'true' : 'false' ?>;

        function esc(value) {
            return $('<div>').text(value === null || value === undefined ? '' : value).html();
        }

        function badge(text, cls) {
            return '<span class="badge badge-' + cls + '">' + esc(text) + '</span>';
        }

        function stat(value, label, iconName, color, cols) {
            cols = cols || 'col-6 col-lg-4 col-xl';
            return '<div class="' + cols + ' mb-2"><div class="border rounded h-100 p-2 d-flex align-items-center">' +
                '<span class="dapodik-stat-icon text-' + color + '"><i class="fa fa-' + iconName + '"></i></span>' +
                '<div class="ml-2"><div class="h5 mb-0">' + esc(value === undefined ? 0 : value) + '</div>' +
                '<small class="text-muted">' + esc(label) + '</small></div></div></div>';
        }

        function summaryRow(items) {
            return '<div class="row">' + items.join('') + '</div>';
        }

        function failRow(row) {
            if (typeof row === 'string') {
                return '<div class="dapodik-fail"><span>' + esc(row) + '</span></div>';
            }
            const identity = [];
            if (row.nisn) {
                identity.push('NISN: ' + row.nisn);
            }
            if (row.nis) {
                identity.push('NIS: ' + row.nis);
            }
            if (row.nip) {
                identity.push('NIP: ' + row.nip);
            }
            if (row.rombel) {
                identity.push('Rombel: ' + row.rombel);
            }
            let html = '<div class="dapodik-fail"><div class="dapodik-fail-main">' +
                '<span class="dapodik-fail-name">' + esc(row.nama || '-') + '</span>';
            if (identity.length) {
                html += '<span class="dapodik-fail-meta">' + identity.map(function(item) {
                    return '<span class="badge badge-light border">' + esc(item) + '</span>';
                }).join(' ') + '</span>';
            }
            html += '</div><small class="text-muted">' + esc(row.reason || '') + '</small></div>';
            return html;
        }

        function invalidBlock(invalid) {
            if (!invalid || !invalid.length) {
                return '';
            }
            return '<details class="mt-3"><summary class="text-danger font-weight-bold">' +
                invalid.length + ' data tidak bisa diimpor</summary>' +
                '<div class="dapodik-fail-list border rounded mt-2">' + invalid.map(failRow).join('') + '</div></details>';
        }

        function notReady() {
            swal.fire({
                title: "Belum dikonfigurasi",
                text: "Lengkapi URL dan token Dapodik lalu simpan sebelum menarik data.",
                icon: "warning"
            });
        }

        function loadingBody(text) {
            return '<div class="text-center py-5 text-muted">' +
                '<i class="fa fa-spinner fa-spin fa-2x mb-3"></i>' +
                '<div>Mengambil ' + esc(text) + ' dari webservice Dapodik…</div>' +
                '<small>Proses ini bisa memakan waktu hingga beberapa puluh detik.</small>' +
                '</div>';
        }

        function renderSiswa(res) {
            const s = res.summary || {};
            const classes = res.classes || [];
            const rows = res.rows || [];
            let html = summaryRow([
                stat(s.total, 'Data Dapodik', 'database', 'primary'),
                stat(s.insert, 'Siswa baru', 'user-plus', 'success'),
                stat(s.update, 'Diperbarui', 'sync', 'info'),
                stat(s.classes, 'Kelas', 'graduation-cap', 'warning'),
                stat(s.invalid, 'Tidak valid', 'exclamation-triangle', 'danger'),
            ]);

            html += '<div class="d-flex align-items-center mt-2 mb-1">' +
                '<h6 class="mb-0 mr-auto">Ringkasan per kelas</h6>' +
                '<div class="custom-control custom-switch">' +
                '<input type="checkbox" class="custom-control-input" id="onlyWarn">' +
                '<label class="custom-control-label small text-muted" for="onlyWarn">Hanya kelas dengan catatan</label>' +
                '</div></div>';
            html += '<div class="table-responsive"><table class="table table-sm table-bordered mb-2">' +
                '<thead><tr><th>Rombel</th><th>Level</th><th>Jurusan</th><th>Kelas</th>' +
                '<th class="text-right">Siswa</th><th class="text-right">Baru / Update</th><th>Catatan</th></tr></thead>' +
                '<tbody id="kelasRows">';
            classes.forEach(function(k) {
                const warnings = k.warnings || [];
                html += '<tr data-warn="' + (warnings.length ? '1' : '0') + '">' +
                    '<td>' + esc(k.nama_rombel) + '<div class="small text-muted"><code>' + esc(k.kode) + '</code></div></td>' +
                    '<td>' + esc(k.level) + '</td>' +
                    '<td>' + esc(k.jurusan_nama) + (k.jurusan_baru ? ' ' + badge('baru', 'secondary') : '') + '</td>' +
                    '<td>' + (k.kelas_ada ? badge('ada', 'info') : badge('dibuat', 'success')) + '</td>' +
                    '<td class="text-right">' + esc(k.siswa) + '</td>' +
                    '<td class="text-right small text-muted">' + esc(k.baru) + ' / ' + esc(k.update) + '</td>' +
                    '<td>' + (warnings.length
                        ? '<span class="text-warning">' + warnings.map(esc).join('<br>') + '</span>'
                        : '<span class="text-muted">-</span>') + '</td></tr>';
            });
            html += '</tbody></table></div>';

            html += '<div class="d-flex align-items-center mt-3 mb-2">' +
                '<h6 class="mb-0 mr-auto">Daftar siswa</h6>' +
                '<small class="text-muted" id="siswaShown"></small></div>';
            html += '<div class="form-row dapodik-toolbar mb-2">' +
                '<div class="col-md-5 mb-2"><input type="search" id="siswaSearch" class="form-control" ' +
                'placeholder="Cari nama, NIS, atau NISN…"></div>' +
                '<div class="col-md-4 mb-2"><select id="siswaKelasFilter" class="custom-select">' +
                '<option value="">Semua kelas</option>' +
                classes.map(function(k) {
                    return '<option value="' + esc(k.kode) + '">' + esc(k.nama_rombel) + '</option>';
                }).join('') + '</select></div>' +
                '<div class="col-md-3 mb-2"><select id="siswaStatusFilter" class="custom-select">' +
                '<option value="">Semua status</option><option value="baru">Siswa baru</option>' +
                '<option value="update">Diperbarui</option></select></div></div>';

            html += '<div class="dapodik-table-wrap border rounded"><table class="table table-sm table-hover mb-0">' +
                '<thead><tr><th>NISN</th><th>NIS</th><th>Nama</th><th>Rombel</th><th>Status</th></tr></thead>' +
                '<tbody id="siswaRows">';
            rows.forEach(function(row) {
                html += '<tr data-kode="' + esc(row.kode) + '" ' +
                    'data-status="' + (row.is_new ? 'baru' : 'update') + '" ' +
                    'data-search="' + esc((row.nama + ' ' + row.nisn + ' ' + row.nis).toLowerCase()) + '">' +
                    '<td>' + esc(row.nisn) + '</td><td>' + esc(row.nis) + '</td>' +
                    '<td>' + esc(row.nama) + '</td><td>' + esc(row.rombel) + '</td>' +
                    '<td>' + (row.is_new ? badge('Baru', 'success') : badge('Update', 'info')) + '</td></tr>';
            });
            html += '</tbody></table></div>';
            if (res.truncated) {
                html += '<div class="small text-muted mt-1">Menampilkan ' + esc(res.shown) +
                    ' dari ' + esc(s.total) + ' baris. Penarikan tetap memproses semua data.</div>';
            }
            html += invalidBlock(res.invalid);
            return html;
        }

        function renderGtk(res) {
            const s = res.summary || {};
            const rows = res.rows || [];
            let html = summaryRow([
                stat(s.total, 'Data Dapodik', 'database', 'primary'),
                stat(s.insert, 'GTK baru', 'user-plus', 'success'),
                stat(s.update, 'Diperbarui', 'sync', 'info'),
                stat(s.accounts, 'Akun login baru', 'key', 'secondary'),
                stat(s.invalid, 'Tidak valid', 'exclamation-triangle', 'danger'),
            ]);

            html += '<div class="form-row dapodik-toolbar mb-2">' +
                '<div class="col-md-8 mb-2"><input type="search" id="gtkSearch" class="form-control" ' +
                'placeholder="Cari nama atau NIP…"></div>' +
                '<div class="col-md-4 mb-2"><select id="gtkStatusFilter" class="custom-select">' +
                '<option value="">Semua status</option><option value="insert">Akan dibuat</option>' +
                '<option value="update">Akan diupdate</option></select></div></div>';
            html += '<div class="d-flex align-items-center mb-1">' +
                '<h6 class="mb-0 mr-auto">Daftar GTK</h6><small class="text-muted" id="gtkShown"></small></div>';

            html += '<div class="dapodik-table-wrap border rounded"><table class="table table-sm table-hover mb-0">' +
                '<thead><tr><th>NIP</th><th>Nama</th><th>JK</th><th>Agama</th><th>Aksi</th><th>Akun</th></tr></thead>' +
                '<tbody id="gtkRows">';
            rows.forEach(function(row) {
                html += '<tr data-status="' + esc(row.action) + '" ' +
                    'data-search="' + esc((row.nama + ' ' + row.nip).toLowerCase()) + '">' +
                    '<td>' + esc(row.nip) + '</td><td>' + esc(row.nama) + '</td>' +
                    '<td>' + esc(row.jenis_kelamin) + '</td><td>' + esc(row.agama) + '</td>' +
                    '<td>' + badge(row.action === 'insert' ? 'Dibuat' : 'Diupdate',
                        row.action === 'insert' ? 'success' : 'info') + '</td>' +
                    '<td>' + badge(row.user, row.user === 'baru' ? 'secondary' : 'light') + '</td></tr>';
            });
            html += '</tbody></table></div>';
            if (res.truncated) {
                html += '<div class="small text-muted mt-1">Menampilkan ' + esc(res.shown) +
                    ' dari ' + esc(s.total) + ' baris. Penarikan tetap memproses semua data.</div>';
            }
            html += invalidBlock(res.invalid);
            return html;
        }

        function filterRows(tbody, search, status, info) {
            const q = ($(search).val() || '').toLowerCase().trim();
            const state = $(status).val();
            let shown = 0;
            const total = $(tbody + ' tr').length;
            $(tbody + ' tr').each(function() {
                const $row = $(this);
                const match = (!q || String($row.attr('data-search') || '').indexOf(q) !== -1) &&
                    (!state || $row.attr('data-status') === state);
                $row.toggleClass('d-none', !match);
                if (match) {
                    shown++;
                }
            });
            $(info).text('Menampilkan ' + shown + ' dari ' + total + ' baris');
        }

        function wireSiswa() {
            function apply() {
                filterRows('#siswaRows', '#siswaSearch', '#siswaStatusFilter', '#siswaShown');
                const kode = $('#siswaKelasFilter').val();
                if (!kode) {
                    return;
                }
                $('#siswaRows tr').each(function() {
                    const $row = $(this);
                    if ($row.attr('data-kode') !== kode) {
                        $row.addClass('d-none');
                    }
                });
                const shown = $('#siswaRows tr:not(.d-none)').length;
                $('#siswaShown').text('Menampilkan ' + shown + ' dari ' + $('#siswaRows tr').length + ' baris');
            }
            $('#siswaSearch, #siswaKelasFilter, #siswaStatusFilter').on('input change', apply);
            $('#onlyWarn').on('change', function() {
                const only = $(this).is(':checked');
                $('#kelasRows tr').each(function() {
                    const warn = $(this).attr('data-warn') === '1';
                    $(this).toggleClass('d-none', only && !warn);
                });
            });
            apply();
        }

        function wireGtk() {
            const apply = function() {
                filterRows('#gtkRows', '#gtkSearch', '#gtkStatusFilter', '#gtkShown');
            };
            $('#gtkSearch, #gtkStatusFilter').on('input change', apply);
            apply();
        }

        function loadPreview(kind) {
            const isSiswa = kind === 'siswa';
            const url = base_url + 'dapodik/' + (isSiswa ? 'previewSiswa' : 'previewGtk');
            $('#previewTitle').text(isSiswa ? 'Pratinjau Data Siswa' : 'Pratinjau Data GTK');
            $('#previewSubtitle').text(isSiswa ? 'Peserta didik & rombel' : 'Guru & tenaga kependidikan');
            $('#previewHint').text('Mengambil data dari webservice…');
            $('#previewPull').addClass('d-none').removeData('kind');
            $('#previewBody').html(loadingBody(isSiswa ? 'data siswa' : 'data GTK'));
            $('#previewModal').modal('show');

            $.get(url, function(res) {
                if (!res || res.status !== true) {
                    $('#previewTitle').text('Pratinjau gagal');
                    $('#previewHint').text('');
                    $('#previewBody').html('<div class="alert alert-danger mb-0">' +
                        '<i class="fa fa-exclamation-circle mr-1"></i>' +
                        esc((res && res.message) || 'Gagal mengambil pratinjau.') + '</div>');
                    return;
                }
                $('#previewBody').html(isSiswa ? renderSiswa(res) : renderGtk(res));
                if (isSiswa) {
                    wireSiswa();
                } else {
                    wireGtk();
                }
                $('#previewPull').removeClass('d-none').data('kind', kind);
                $('#previewHint').text('Pratinjau tidak mengubah data. Penarikan memproses seluruh data Dapodik.');
            }, 'json').fail(function(xhr) {
                $('#previewTitle').text('Pratinjau gagal');
                $('#previewHint').text('');
                $('#previewBody').html('<div class="alert alert-danger mb-0">' +
                    '<i class="fa fa-exclamation-circle mr-1"></i> Permintaan gagal (' + xhr.status + ').</div>');
            });
        }

        function showResult(kind, res) {
            const s = res.summary || {};
            const fails = Array.isArray(res.fails) ? res.fails : [];
            const warnings = Array.isArray(res.warnings) ? res.warnings : [];
            const prefix = kind === 'siswa' ? 'siswa' : 'GTK';

            let html = summaryRow([
                stat(s.success, 'Berhasil', 'check-circle', 'success', 'col-6'),
                stat(s.failed, 'Gagal', 'times-circle', 'danger', 'col-6'),
                stat(s.insert, 'Data baru', 'plus-circle', 'primary', 'col-6'),
                stat(s.update, 'Diperbarui', 'sync', 'info', 'col-6'),
            ]);
            html += '<div class="small text-muted mb-1">Total data Dapodik: ' + esc(s.total || 0) + '</div>';
            if (s.kelas_dibuat !== undefined || s.kelas_diupdate !== undefined) {
                html += '<div class="small text-muted mb-2">Kelas: ' + esc(s.kelas_dibuat || 0) +
                    ' dibuat, ' + esc(s.kelas_diupdate || 0) + ' diperbarui.</div>';
            }
            if (fails.length) {
                html += '<div class="text-left mt-2"><div class="font-weight-bold mb-1 text-danger">' +
                    'Gagal diimpor (' + fails.length + ')</div>' +
                    '<div class="dapodik-fail-list border rounded">' + fails.map(failRow).join('') + '</div></div>';
            }
            if (warnings.length) {
                html += '<div class="text-left mt-3"><div class="font-weight-bold mb-1 text-warning">' +
                    'Perhatian (' + warnings.length + ')</div>' +
                    '<div class="dapodik-fail-list border rounded">' + warnings.map(failRow).join('') + '</div></div>';
            }

            swal.fire({
                title: 'Hasil tarik ' + prefix,
                html: html,
                width: 640,
                icon: fails.length ? 'warning' : 'success',
                confirmButtonText: 'Selesai'
            }).then(function() {
                window.location.reload();
            });
        }

        function pull(kind) {
            const isSiswa = kind === 'siswa';
            const startedAt = Date.now();
            let timer = null;
            swal.fire({
                title: 'Menarik data ' + (isSiswa ? 'siswa' : 'GTK') + '…',
                html: '<div class="text-muted">Jangan tutup halaman ini.</div>' +
                    '<div class="mt-2"><b id="pullElapsed">0</b> detik</div>' +
                    '<div class="progress mt-2" style="height:6px">' +
                    '<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div></div>' +
                    '<div class="small text-muted mt-2">Proses bisa memakan waktu hingga beberapa menit.</div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onOpen: function() {
                    timer = setInterval(function() {
                        const el = document.getElementById('pullElapsed');
                        if (el) {
                            el.textContent = Math.floor((Date.now() - startedAt) / 1000);
                        }
                    }, 1000);
                }
            });

            $.ajax({
                url: base_url + 'dapodik/' + (isSiswa ? 'tarikDataSiswa' : 'tarikDataGtk'),
                type: 'GET',
                dataType: 'json'
            }).done(function(res) {
                clearInterval(timer);
                if (!res || res.status === false || res.error) {
                    swal.fire({
                        title: 'Gagal',
                        text: (res && (res.error || res.message)) || 'Permintaan gagal.',
                        icon: 'error'
                    });
                    return;
                }
                showResult(kind, res);
            }).fail(function(xhr) {
                clearInterval(timer);
                swal.fire({
                    title: 'Gagal',
                    text: 'Permintaan gagal (' + xhr.status + ').',
                    icon: 'error'
                });
            });
        }

        function confirmPull(kind) {
            if (!dapodikReady) {
                return notReady();
            }
            const isSiswa = kind === 'siswa';
            swal.fire({
                title: 'Tarik data ' + (isSiswa ? 'siswa' : 'GTK') + '?',
                html: 'Data akan diambil langsung dari webservice Dapodik.<br>' +
                    '<small class="text-muted">Sebaiknya gunakan <b>Pratinjau</b> dulu untuk melihat kelas ' +
                    'dan data yang akan dibuat.</small>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Tarik sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then(function(result) {
                if (result.value) {
                    pull(kind);
                }
            });
        }

        $('#previewSiswa').on('click', function() {
            if (!dapodikReady) {
                return notReady();
            }
            loadPreview('siswa');
        });

        $('#previewGtk').on('click', function() {
            if (!dapodikReady) {
                return notReady();
            }
            loadPreview('gtk');
        });

        $('#previewPull').on('click', function() {
            const kind = $(this).data('kind');
            if (!kind) {
                return;
            }
            $('#previewModal').modal('hide');
            setTimeout(function() {
                pull(kind);
            }, 250);
        });

        $('#dapodikTarikSiswa').on('click', function() {
            confirmPull('siswa');
        });

        $('#dapodikTarikGtk').on('click', function() {
            confirmPull('gtk');
        });

        $('#toggleToken').on('click', function() {
            const input = $('#dapodik_token');
            const show = input.attr('type') === 'password';
            input.attr('type', show ? 'text' : 'password');
            $(this).find('i').toggleClass('fa-eye', !show).toggleClass('fa-eye-slash', show);
        });

        $('#formDapodik').on('submit', function(e) {
            e.preventDefault();
            const url = $.trim($('#dapodik_url').val());
            const token = $.trim($('#dapodik_token').val());

            if (url === '' || !/^https?:\/\/.+/i.test(url)) {
                swal.fire({ title: "Error", text: "URL webservice tidak valid.", icon: "error" });
                return;
            }
            if (token === '') {
                swal.fire({ title: "Error", text: "Token webservice wajib diisi.", icon: "error" });
                return;
            }

            $.post(base_url + 'dapodik/saveSettings', { dapodik_url: url, dapodik_token: token }, function(res) {
                if (res.status) {
                    swal.fire({ title: "Sukses", text: res.message, icon: "success" })
                        .then(function() { window.location.reload(); });
                } else {
                    swal.fire({ title: "Error", text: res.message, icon: "error" });
                }
            }, 'json').fail(function(xhr) {
                swal.fire({ title: "Error", text: "Gagal menyimpan (" + xhr.status + ").", icon: "error" });
            });
        });

        $('#testKoneksi').on('click', function() {
            swal.fire({
                text: "Menguji koneksi ke webservice...",
                button: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: function() { swal.showLoading(); }
            });
            $.get(base_url + 'dapodik/testConnection', function(res) {
                swal.fire({
                    title: res.status ? "Berhasil" : "Gagal",
                    text: res.message,
                    icon: res.status ? "success" : "error"
                });
            }, 'json').fail(function(xhr) {
                swal.fire({ title: "Gagal", text: "Permintaan gagal (" + xhr.status + ").", icon: "error" });
            });
        });
    });
</script>
