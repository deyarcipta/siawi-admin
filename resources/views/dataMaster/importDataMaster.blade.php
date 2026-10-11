@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 48px; height: 48px; font-size: 1.25rem;">
                    <i class="fas fa-file-import"></i>
                </div>
                <div>
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Import Data Master & Foto Siswa</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.85rem;">Unggah berkas spreadsheet Excel data master atau arsip ZIP foto siswa secara massal</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-secondary font-weight-500">Data Master</a></li>
                    <li class="breadcrumb-item active text-dark font-weight-600">Import Data</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Notifikasi Status Standar -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-lg mr-2 text-success"></i>
                    <div>
                        <strong class="font-weight-600">Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-lg mr-2 text-danger"></i>
                    <div>
                        <strong class="font-weight-600">Perhatian!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-circle fa-lg mr-2 text-danger mt-1"></i>
                    <div>
                        <strong class="font-weight-600">Validasi Gagal:</strong>
                        <ul class="mb-0 pl-3 mt-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- 1. KARTU IMPORT DATA MASTER (EXCEL) -->
            <div class="col-lg-6 mb-4 d-flex align-items-stretch">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column w-100" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between w-100">
                        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-file-excel text-success mr-2"></i> 1. Import Data Master (Excel)
                        </h5>
                        <span class="badge badge-success px-2 py-1 font-weight-normal" style="font-size: 0.78rem;">Spreadsheet</span>
                    </div>

                    <form id="formImportExcel" action="/admin/import" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1 w-100" style="width: 100%;">
                        @csrf
                        <div class="card-body flex-grow-1 w-100" style="width: 100%;">
                            <p class="text-muted mb-3" style="font-size: 0.88rem; line-height: 1.5;">
                                Unggah berkas Excel berisi data Siswa, Jurusan, Level, Kelas, Biodata, serta Kontak Orang Tua (Ibu, Ayah, Wali).
                            </p>

                            <div class="form-group mb-3">
                                <label for="file_excel" class="font-weight-600 text-dark" style="font-size: 0.9rem;">
                                    Pilih Berkas Excel (.xls / .xlsx) <span class="text-danger">*</span>
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_excel" name="file" required accept=".xls,.xlsx">
                                    <label class="custom-file-label text-truncate" id="file_excel_label" for="file_excel">Pilih berkas Excel...</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <a href="{{ asset('template_import_master.xlsx') }}" class="btn btn-outline-success btn-sm px-3 py-2 font-weight-600" style="border-radius: 8px;" download>
                                    <i class="fas fa-download mr-1"></i> Unduh Template Excel Resmi
                                </a>
                                <small class="d-block text-muted mt-1" style="font-size: 0.8rem;">
                                    Template telah dilengkapi 30 kolom biodata lengkap & kontak prioritas notifikasi WhatsApp.
                                </small>
                            </div>

                            <div class="p-3 bg-light rounded border" style="font-size: 0.84rem; line-height: 1.5;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-info-circle text-primary mr-2"></i>
                                    <span class="font-weight-bold text-dark">Fitur Otomatisasi Import Excel:</span>
                                </div>
                                <ul class="pl-3 mb-0 text-secondary">
                                    <li class="mb-1"><strong>Akun Orang Tua:</strong> Akun login orang tua (<code class="text-dark font-weight-600">ortu_{nis}</code>) otomatis dibuat & ditautkan secara langsung.</li>
                                    <li class="mb-1"><strong>Deteksi Saudara:</strong> Jika No. HP orang tua sama dengan siswa lain, akun orang tua otomatis disatukan (multi-anak).</li>
                                    <li class="mb-1"><strong>Prioritas Kontak Notifikasi:</strong> Mendukung No HP Ibu (Prioritas 1), Ayah (Prioritas 2), Wali (Prioritas 3), dan Siswa.</li>
                                    <li><strong>Proteksi Foto:</strong> Jika siswa sudah memiliki foto profil di sistem, fotonya tidak akan tertimpa.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-footer bg-light py-3 px-3 px-sm-4 border-top mt-auto d-flex align-items-center justify-content-between w-100" style="width: 100%;">
                            <span class="text-muted small mb-0 mr-2">Format: .xls / .xlsx</span>
                            <button type="submit" id="btnImportExcel" class="btn btn-primary ml-auto px-4 py-2 font-weight-600 shadow-sm" style="border-radius: 8px; min-height: 44px; white-space: nowrap;">
                                <i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Import Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. KARTU IMPORT FOTO SISWA MASSAL (ZIP) -->
            <div class="col-lg-6 mb-4 d-flex align-items-stretch">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column w-100" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between w-100">
                        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-images text-warning mr-2"></i> 2. Import Foto Siswa Massal (.zip)
                        </h5>
                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-normal" style="font-size: 0.78rem;">Arsip ZIP</span>
                    </div>

                    <form id="formImportZip" action="{{ route('admin.import-foto-zip') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1 w-100" style="width: 100%;">
                        @csrf
                        <div class="card-body flex-grow-1 w-100" style="width: 100%;">
                            <p class="text-muted mb-3" style="font-size: 0.88rem; line-height: 1.5;">
                                Unggah berkas ZIP berisi kumpulan foto siswa untuk memperbarui foto profil siswa sekaligus berdasarkan NIS.
                            </p>

                            <div class="form-group mb-3">
                                <label for="file_zip" class="font-weight-600 text-dark" style="font-size: 0.9rem;">
                                    Pilih Berkas ZIP Foto (.zip) <span class="text-danger">*</span>
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_zip" name="file_zip" required accept=".zip">
                                    <label class="custom-file-label text-truncate" id="file_zip_label" for="file_zip">Pilih berkas arsip .zip...</label>
                                </div>
                            </div>

                            <div class="mb-4 p-3 bg-light rounded border" style="font-size: 0.84rem; line-height: 1.5;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-lightbulb text-warning mr-2"></i>
                                    <span class="font-weight-bold text-dark">Aturan Penamaan File di Dalam ZIP:</span>
                                </div>
                                <ul class="pl-3 mb-0 text-secondary">
                                    <li class="mb-1">Nama file foto <strong>wajib berupa NIS siswa</strong>. Contoh: <code class="text-dark font-weight-600">233001.jpg</code>, <code class="text-dark font-weight-600">233002.png</code>, atau <code class="text-dark font-weight-600">233003.webp</code>.</li>
                                    <li class="mb-1">Format foto yang didukung: <strong>JPG, JPEG, PNG, WEBP</strong> (Maks. 100 MB per file ZIP).</li>
                                    <li class="mb-1">Foto dapat ditaruh langsung di root zip atau di dalam subfolder.</li>
                                    <li><strong>Keamanan Sistem:</strong> Foto yang cocok akan otomatis disimpan dengan nama acak unik standar sistem dan foto lama digantikan secara bersih.</li>
                                </ul>
                            </div>

                            <div class="d-flex align-items-center p-2 rounded" style="background-color: #FEF3C7; color: #92400E; font-size: 0.82rem;">
                                <i class="fas fa-shield-alt mr-2 fa-lg text-warning"></i>
                                <span>Pastikan data master siswa (NIS) sudah diimport terlebih dahulu sebelum mengunggah foto.</span>
                            </div>
                        </div>

                        <div class="card-footer bg-light py-3 px-3 px-sm-4 border-top mt-auto d-flex align-items-center justify-content-between w-100" style="width: 100%;">
                            <span class="text-muted small mb-0 mr-2">Format: .zip (Maks. 100MB)</span>
                            <button type="submit" id="btnImportZip" class="btn btn-warning text-dark ml-auto px-4 py-2 font-weight-600 shadow-sm" style="border-radius: 8px; min-height: 44px; white-space: nowrap;">
                                <i class="fas fa-file-upload mr-1"></i> Mulai Import Foto ZIP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Update label berkas Excel saat dipilih
        var excelInput = document.getElementById('file_excel');
        var excelLabel = document.getElementById('file_excel_label');
        if (excelInput && excelLabel) {
            excelInput.addEventListener('change', function (e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas Excel...';
                excelLabel.textContent = fileName;
            });
        }

        // 2. Update label berkas ZIP saat dipilih
        var zipInput = document.getElementById('file_zip');
        var zipLabel = document.getElementById('file_zip_label');
        if (zipInput && zipLabel) {
            zipInput.addEventListener('change', function (e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas arsip .zip...';
                zipLabel.textContent = fileName;
            });
        }

        // 3. Helper format ukuran berkas byte ke KB / MB
        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(0) + ' KB';
            }
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // 4. Handler Pengunggahan Berkas dengan Progress Bar Interaktif (XHR + SweetAlert2)
        function initUploadProgress(formId, inputId, config) {
            var form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function (e) {
                var fileInput = document.getElementById(inputId);
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    return; // Biarkan validasi HTML5 bawaan browser aktif jika belum ada berkas
                }

                // Cek ketersediaan SweetAlert2
                if (typeof Swal === 'undefined') {
                    return; // Fallback ke submit form biasa jika Swal tidak tersedia
                }

                e.preventDefault();

                var activeFile = fileInput.files[0];
                var uploadFileName = activeFile.name;
                var totalSizeFormatted = formatBytes(activeFile.size);
                var formData = new FormData(form);

                // Tampilkan Modal SweetAlert2 dengan Progress Bar interaktif
                Swal.fire({
                    title: config.modalTitle,
                    html: `
                        <div class="text-left mt-2 px-1">
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span class="text-truncate font-weight-bold" style="max-width: 230px;" title="${uploadFileName}">${uploadFileName}</span>
                                <span class="badge badge-light border px-2 font-weight-bold">${totalSizeFormatted}</span>
                            </div>
                            <div class="progress" style="height: 22px; border-radius: 11px; background-color: #e9ecef; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);">
                                <div id="swalUploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated ${config.progressColor} font-weight-bold" role="progressbar" style="width: 0%; font-size: 11px; line-height: 22px; transition: width 0.2s ease;">0%</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 small">
                                <span id="swalUploadStatus" class="text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Sedang mengunggah berkas...</span>
                                <span id="swalUploadBytes" class="font-weight-bold ${config.textColor}">0 KB / ${totalSizeFormatted}</span>
                            </div>
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });

                var xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                // Pemantauan Progres Pengunggahan Byte per Detik
                xhr.upload.onprogress = function (event) {
                    if (event.lengthComputable) {
                        var percent = Math.round((event.loaded / event.total) * 100);
                        var loadedFormatted = formatBytes(event.loaded);
                        var totalFormatted = formatBytes(event.total);

                        var bar = document.getElementById('swalUploadProgressBar');
                        var status = document.getElementById('swalUploadStatus');
                        var bytes = document.getElementById('swalUploadBytes');

                        if (bar) {
                            bar.style.width = percent + '%';
                            bar.textContent = percent + '%';
                        }
                        if (bytes) {
                            bytes.textContent = loadedFormatted + ' / ' + totalFormatted;
                        }

                        if (percent >= 100) {
                            if (bar) {
                                bar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-success font-weight-bold';
                            }
                            if (status) {
                                status.innerHTML = '<i class="fas fa-cog fa-spin text-success mr-1"></i> ' + config.processingText;
                            }
                        } else {
                            if (status) {
                                status.innerHTML = '<i class="fas fa-arrow-up ' + config.textColor + ' mr-1"></i> Mengunggah: ' + percent + '%';
                            }
                        }
                    }
                };

                // Handler Respon Selesai dari Server
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        var res = {};
                        try {
                            res = JSON.parse(xhr.responseText);
                        } catch (err) {
                            res = { success: true, message: 'Berkas berhasil diimpor & diproses.' };
                        }

                        if (res.success !== false) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                html: res.message || 'Proses impor berhasil diselesaikan.',
                                confirmButtonColor: '#007bff',
                                confirmButtonText: 'OK, Selesai'
                            }).then(function () {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memproses Data',
                                html: res.message || 'Terjadi kesalahan saat memproses data pada berkas.',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Tutup'
                            });
                        }
                    } else {
                        var errorMsg = 'Gagal memproses berkas.';
                        if (xhr.status === 413) {
                            errorMsg = '<strong>Ukuran berkas terlalu besar untuk server (HTTP 413)</strong><br><br><div class="text-left small text-muted">Berkas yang diunggah melebihi batas <code>upload_max_filesize</code> / <code>post_max_size</code> PHP server hosting Anda.<br><br><strong>Solusi:</strong><br>1. Naikkan batas upload di php.ini / cPanel (atur ke 128M).<br>2. Atau kompres arsip foto/excel menjadi ukuran yang lebih kecil.</div>';
                        } else if (xhr.status === 419) {
                            errorMsg = '<strong>Sesi Anda telah kedaluwarsa (CSRF Token Expired)</strong><br><small class="text-muted">Halaman sudah terbuka terlalu lama. Silakan muat ulang (refresh) halaman lalu coba lagi.</small>';
                        } else {
                            try {
                                var res = JSON.parse(xhr.responseText);
                                if (res.errors) {
                                    errorMsg = Object.values(res.errors).flat().join('<br>');
                                } else if (res.message) {
                                    errorMsg = res.message;
                                }
                            } catch (e) {
                                if (xhr.responseText && xhr.responseText.length < 300) {
                                    errorMsg = xhr.responseText;
                                } else if (xhr.statusText) {
                                    errorMsg = 'Server merespon dengan status: ' + xhr.status + ' (' + xhr.statusText + ')';
                                }
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengunggah Berkas',
                            html: errorMsg,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Tutup'
                        });
                    }
                };

                // Handler Masalah Jaringan / Timeout
                xhr.onerror = function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Terputus',
                        text: 'Gagal menghubungi server saat mengunggah berkas. Periksa koneksi internet atau batas timeout server.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Tutup'
                    });
                };

                xhr.send(formData);
            });
        }

        // Aktifkan progress bar pada Form Import Data Excel
        initUploadProgress('formImportExcel', 'file_excel', {
            modalTitle: '<span style="font-size: 1.15rem; font-weight: 700;"><i class="fas fa-file-excel text-success mr-2"></i> Mengunggah & Memproses Data Excel</span>',
            progressColor: 'bg-primary',
            textColor: 'text-primary',
            processingText: 'Sedang membaca spreadsheet, validasi data, & sinkronisasi akun orang tua...'
        });

        // Aktifkan progress bar pada Form Import Foto Siswa ZIP
        initUploadProgress('formImportZip', 'file_zip', {
            modalTitle: '<span style="font-size: 1.15rem; font-weight: 700;"><i class="fas fa-file-archive text-warning mr-2"></i> Mengunggah & Memproses Foto ZIP</span>',
            progressColor: 'bg-warning text-dark',
            textColor: 'text-warning',
            processingText: 'Sedang mengekstrak berkas ZIP, mencocokkan NIS siswa, & menyimpan foto...'
        });
    });
</script>
@endsection
