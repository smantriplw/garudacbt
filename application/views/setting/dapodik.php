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
