@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
                    <i class="fas fa-file-import"></i>
                </div>
                <div>
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Import Data Master</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Unggah berkas spreadsheet Excel untuk impor massal data master</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary font-weight-500">Data Master</a></li>
                    <li class="breadcrumb-item active">Import Data</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
                        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-file-excel text-success mr-2"></i> Form Unggah Berkas Import
                        </h5>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-3 mb-0">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-3 mb-0">{{ session('error') }}</div>
                    @endif

                    <form action="/admin/import" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="file" class="font-weight-600 text-dark">Pilih Berkas Excel (.xls / .xlsx)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" required accept=".xls,.xlsx">
                                    <label class="custom-file-label" id="file-label" for="file">Pilih berkas template...</label>
                                    <script>
                                        document.getElementById('file').addEventListener('change', function(e) {
                                            var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas template...';
                                            var label = document.getElementById('file-label');
                                            label.textContent = fileName;
                                        });
                                    </script>
                                </div>
                            </div>

                            <div class="mt-3 mb-4">
                                <a href="{{ asset('template_import_master.xlsx') }}" class="btn btn-success btn-sm px-3 shadow-sm" style="border-radius: 6px;" download>
                                    <i class="fas fa-download mr-1"></i> Unduh Template Excel
                                </a>
                            </div>

                            <div class="p-3 bg-light rounded-lg border">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-info-circle text-info mr-2"></i>
                                    <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">Petunjuk Import Data Master</span>
                                </div>
                                <p class="text-muted mb-2" style="font-size: 0.85rem; line-height: 1.5;">
                                    Menu ini berfungsi untuk melakukan import data Master secara massal meliputi:
                                    <strong class="text-dark">Data Siswa, Jurusan, Level, dan Kelas</strong>.
                                </p>
                                <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.5;">
                                    Pastikan berkas berformat <strong>Ms. Excel (.xls / .xlsx)</strong> dan susunan kolom data harus sesuai dengan template resmi yang telah disediakan.
                                </p>
                            </div>
                        </div>

                        <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
                            <a href="/admin/dashboard" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-cloud-upload-alt mr-1"></i> Mulai Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
