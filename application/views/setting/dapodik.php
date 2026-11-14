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
                    <div class="card-title">
                        Pengaturan Koneksi Dapodik
                    </div>
                </div>
                <div class="card-body">
                    <form id="formDapodik">
                        <div class="form-group">
                            <label for="dapodik_url">URL Webservice</label>
                            <input type="text" class="form-control" id="dapodik_url" name="dapodik_url"
                                   value="<?= html_escape($dapodik['url']) ?>"
                                   placeholder="https://dapodik.sekolah.sch.id" autocomplete="off">
                            <small class="text-muted">Alamat webservice Dapodik, tanpa garis miring di akhir.</small>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
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
                        <?php if (!empty($dapodik['last_sync'])): ?>
                            <p class="text-muted mb-3">Sinkronisasi terakhir: <?= html_escape($dapodik['last_sync']) ?></p>
                        <?php endif; ?>
                        <?php if (!$dapodik_ready): ?>
                            <div class="alert alert-warning py-2">
                                Lengkapi URL dan token di atas, lalu simpan, sebelum menarik data.
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-outline-secondary" id="testKoneksi">Tes Koneksi</button>
                    </form>
                </div>
            </div>

            <div class="card my-shadow">
                <div class="card-header">
                    <div class="card-title">
                        <?= $subjudul ?>
                    </div>
                    <div class="card-tools">
                        <a href="#" id="previewSiswa" class="btn btn-outline-primary <?= !$dapodik_ready ? 'disabled' : '' ?>">
                            Pratinjau Siswa
                        </a>
                        <a href="#" id="previewGtk" class="btn btn-outline-secondary <?= !$dapodik_ready ? 'disabled' : '' ?>">
                            Pratinjau GTK
                        </a>
                        <a href="#" id="dapodikTarikSiswa" class="btn btn-primary <?= !$dapodik_ready ? 'disabled' : '' ?>">
                            Tarik Siswa
                        </a>
                        <a href="#" id="dapodikTarikGtk" class="btn btn-secondary <?= !$dapodik_ready ? 'disabled' : '' ?>">
                            Tarik GTK
                        </a>
                    </div>
                </div>
            </div>
        </div>
</section>
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Pratinjau</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        ajaxcsrf();

        const dapodikReady = <?= $dapodik_ready ? 'true' : 'false' ?>;

        function notReady() {
            swal.fire({
                title: "Belum dikonfigurasi",
                text: "Lengkapi URL dan token Dapodik lalu simpan sebelum menarik data.",
                icon: "warning"
            });
        }

        function esc(value) {
            return $('<div>').text(value === null || value === undefined ? '' : value).html();
        }

        function badge(text, cls) {
            return '<span class="badge badge-' + cls + '">' + esc(text) + '</span>';
        }

        function previewError(message) {
            $('#previewBody').html('<div class="alert alert-danger mb-0">' + esc(message) + '</div>');
            $('#previewTitle').text('Pratinjau gagal');
            $('#previewModal').modal('show');
        }

        function loadPreview(kind) {
            const url = base_url + 'dapodik/' + (kind === 'siswa' ? 'previewSiswa' : 'previewGtk');
            $('#previewTitle').text(kind === 'siswa' ? 'Pratinjau Data Siswa' : 'Pratinjau Data GTK');
            $('#previewBody').html('<p class="text-muted mb-0">Mengambil data dari webservice...</p>');
            $('#previewModal').modal('show');

            $.get(url, function(res) {
                if (!res.status) {
                    return previewError(res.message || 'Gagal mengambil pratinjau.');
                }
                $('#previewBody').html(kind === 'siswa' ? renderSiswa(res) : renderGtk(res));
            }, 'json').fail(function(xhr) {
                previewError('Permintaan gagal (' + xhr.status + ').');
            });
        }

        function summaryCards(items) {
            return '<div class="row mb-3">' + items.map(function(it) {
                return '<div class="col"><div class="border rounded p-2 text-center">' +
                    '<div class="h5 mb-0">' + esc(it.value) + '</div>' +
                    '<small class="text-muted">' + esc(it.label) + '</small>' +
                    '</div></div>';
            }).join('') + '</div>';
        }

        function renderGtk(res) {
            let html = summaryCards([
                { label: 'Data Dapodik', value: res.summary.total },
                { label: 'Akan dibuat', value: res.summary.insert },
                { label: 'Akan diupdate', value: res.summary.update },
                { label: 'Tidak valid', value: res.summary.invalid },
            ]);
            html += '<div class="table-responsive"><table class="table table-sm table-bordered mb-0">' +
                '<thead><tr><th>NIP</th><th>Nama</th><th>Jenis Kelamin</th><th>Agama</th><th>Aksi</th><th>Akun</th></tr></thead><tbody>';
            res.rows.forEach(function(row) {
                html += '<tr><td>' + esc(row.nip) + '</td><td>' + esc(row.nama) + '</td><td>' + esc(row.jenis_kelamin) +
                    '</td><td>' + esc(row.agama) + '</td><td>' +
                    badge(row.action === 'insert' ? 'Dibuat' : 'Diupdate', row.action === 'insert' ? 'success' : 'info') +
                    '</td><td>' + badge(row.user, row.user === 'baru' ? 'secondary' : 'light') + '</td></tr>';
            });
            html += '</tbody></table></div>';
            if (res.invalid.length) {
                html += '<h6 class="mt-3">Tidak bisa diimport</h6><ul class="mb-0">' +
                    res.invalid.map(function(row) { return '<li>' + esc(row.nama) + ' — ' + esc(row.reason) + '</li>'; }).join('') +
                    '</ul>';
            }
            return html;
        }

        function renderSiswa(res) {
            let html = summaryCards([
                { label: 'Data Dapodik', value: res.summary.total },
                { label: 'Siswa baru', value: res.summary.insert },
                { label: 'Akan diupdate', value: res.summary.update },
                { label: 'Kelas terdeteksi', value: res.summary.classes },
                { label: 'Tidak valid', value: res.summary.invalid },
            ]);
            html += '<h6>Per kelas</h6><div class="table-responsive"><table class="table table-sm table-bordered mb-3">' +
                '<thead><tr><th>Rombel</th><th>Kode</th><th>Level</th><th>Jurusan</th><th>Kelas</th><th>Siswa</th><th>Catatan</th></tr></thead><tbody>';
            res.classes.forEach(function(k) {
                html += '<tr><td>' + esc(k.nama_rombel) + '</td><td><code>' + esc(k.kode) + '</code></td><td>' + esc(k.level) +
                    '</td><td>' + esc(k.jurusan_nama) + (k.jurusan_baru ? ' ' + badge('baru', 'secondary') : '') +
                    '</td><td>' + (k.kelas_ada ? badge('ada', 'info') : badge('dibuat', 'success')) +
                    '</td><td>' + esc(k.siswa) + ' <small class="text-muted">(' + esc(k.baru) + ' baru, ' + esc(k.update) + ' update)</small>' +
                    '</td><td>' + (k.warnings.length ? '<span class="text-warning">' + k.warnings.map(esc).join('<br />') + '</span>' : '-') +
                    '</td></tr>';
            });
            html += '</tbody></table></div>';
            if (res.invalid.length) {
                html += '<h6>Tidak bisa diimport</h6><ul class="mb-0">' +
                    res.invalid.map(function(row) { return '<li>' + esc(row.nama) + ' — ' + esc(row.reason) + '</li>'; }).join('') +
                    '</ul>';
            }
            return html;
        }

        $('#previewSiswa').on('click', function(e) {
            e.preventDefault();
            if (!dapodikReady) { return notReady(); }
            loadPreview('siswa');
        });

        $('#previewGtk').on('click', function(e) {
            e.preventDefault();
            if (!dapodikReady) { return notReady(); }
            loadPreview('gtk');
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
                        .then(() => window.location.reload());
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
                onOpen: () => { swal.showLoading(); }
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

        $('#dapodikTarikGtk').on('click', () => {
            if (!dapodikReady) {
                notReady();
                return;
            }
            swal.fire({
                title: "Anda yakin?",
                html: "Anda akan melakukan penarikan data GTK dari Dapodik",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Tarik!"
            }).then(result => {
                if (result.value) {
                    swal.fire({
                        text: "Silahkan tunggu....",
                        button: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        onOpen: () => {
                            swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: base_url + 'dapodik/tarikDataGtk',
                        type: 'GET',
                        success: function (response) {
                            if (response.message) {
                                const fails = Array.isArray(response.fails) ? response.fails : [];
                                swal.fire({
                                    title: "Sukses",
                                    html: `${response.message}<br />Yang gagal diimport:<br />${fails.map(x => `<li>${x}</li>`).join('<br/>')}`,
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                }).then(result => {
                                    if (result.value) {
                                        window.location.href = base_url + 'dapodik';
                                    }
                                });
                            }

                            if (response.error) {
                                swal.fire({
                                    title: "Error",
                                    text: response.error,
                                    icon: "error"
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            swal.fire({
                                title: "Error",
                                text: error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });

        $('#dapodikTarikSiswa').on('click', function() {
            if (!dapodikReady) {
                notReady();
                return;
            }
            swal.fire({
                title: "Anda yakin?",
                html: "Anda akan melakukan penarikan data dari Dapodik",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Tarik!"
            }).then(result => {
                if (result.value) {
                    swal.fire({
                        text: "Silahkan tunggu....",
                        button: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        onOpen: () => {
                            swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: base_url + 'dapodik/tarikDataSiswa',
                        type: 'GET',
                        success: function (respon) {
                            if (respon.message) {
                                const fails = Array.isArray(respon.fails) ? respon.fails : [];
                                swal.fire({
                                    title: "Sukses",
                                    html: `${respon.message}<br />Nama siswa yang gagal diimport:<br />${fails.map(x => `<li>${x}</li>`).join('<br/>')}`,
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                }).then(result => {
                                    if (result.value) {
                                        window.location.href = base_url + 'dapodik';
                                    }
                                });
                            }

                            if (respon.error) {
                                swal.fire({
                                    title: "Error",
                                    text: respon.error,
                                    icon: "error"
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            swal.fire({
                                title: "Error",
                                text: error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
