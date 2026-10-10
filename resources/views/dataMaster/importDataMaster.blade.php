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
        <!-- Notifikasi Status -->
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
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-file-excel text-success mr-2"></i> 1. Import Data Master (Excel)
                        </h5>
                        <span class="badge badge-success px-2 py-1 font-weight-normal" style="font-size: 0.78rem;">Spreadsheet</span>
                    </div>

                    <form action="/admin/import" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1">
                        @csrf
                        <div class="card-body flex-grow-1">
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
                                    Template telah dilengkapi kolom biodata lengkap & kontak prioritas notifikasi WhatsApp.
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

                        <div class="card-footer bg-light py-3 px-4 border-top mt-auto d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Format: .xls / .xlsx</span>
                            <button type="submit" class="btn btn-primary px-4 py-2 font-weight-600 shadow-sm" style="border-radius: 8px; min-height: 44px;">
                                <i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Import Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. KARTU IMPORT FOTO SISWA MASSAL (ZIP) -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-images text-warning mr-2"></i> 2. Import Foto Siswa Massal (.zip)
                        </h5>
                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-normal" style="font-size: 0.78rem;">Arsip ZIP</span>
                    </div>

                    <form action="{{ route('admin.import-foto-zip') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1">
                        @csrf
                        <div class="card-body flex-grow-1">
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

                        <div class="card-footer bg-light py-3 px-4 border-top mt-auto d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Format: .zip (Maks. 100MB)</span>
                            <button type="submit" class="btn btn-warning text-dark px-4 py-2 font-weight-600 shadow-sm" style="border-radius: 8px; min-height: 44px;">
                                <i class="fas fa-file-upload mr-1"></i> Mulai Import Foto ZIP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="row">
            <div class="col-12 mb-4">
                <a href="/admin/dashboard" class="btn btn-outline-secondary px-3 py-2 font-weight-500" style="border-radius: 8px; min-height: 44px; display: inline-flex; align-items: center;">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update label berkas Excel
        var excelInput = document.getElementById('file_excel');
        var excelLabel = document.getElementById('file_excel_label');
        if (excelInput && excelLabel) {
            excelInput.addEventListener('change', function (e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas Excel...';
                excelLabel.textContent = fileName;
            });
        }

        // Update label berkas ZIP
        var zipInput = document.getElementById('file_zip');
        var zipLabel = document.getElementById('file_zip_label');
        if (zipInput && zipLabel) {
            zipInput.addEventListener('change', function (e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas arsip .zip...';
                zipLabel.textContent = fileName;
            });
        }
    });
</script>
@endsection
