@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Backup & Pemulihan Sistem</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Pencadangan database MySQL dan berkas storage serta pemulihan sistem</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/setting" class="text-primary font-weight-500">Pengaturan</a></li>
                    <li class="breadcrumb-item active">Backup & Restore</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('failed'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('failed') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- 2 Kolom Panel Utama: Buat Cadangan & Pulihkan Cadangan -->
        <div class="row mb-4">
            <!-- Panel 1: Buat Cadangan Baru -->
            <div class="col-lg-6 mb-3 mb-lg-0">
                <div class="card border-0 shadow-sm h-100 mb-0 d-flex flex-column" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-download text-primary mr-2" style="font-size: 1.1rem;"></i>
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">1. Buat Cadangan Baru</h5>
                            </div>
                            <span class="badge badge-primary px-2.5 py-1 text-xs font-weight-bold">Snapshot</span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                                Pilih jenis pencadangan yang ingin dibuat. Berkas cadangan akan disimpan di server dan dapat langsung diunduh ke komputer Anda.
                            </p>

                            <div class="p-3 bg-light rounded-lg border mb-3">
                                <ul class="list-unstyled mb-0 text-muted" style="font-size: 0.83rem; line-height: 1.6;">
                                    <li class="mb-1.5">
                                        <i class="fas fa-check-circle text-primary mr-2"></i> 
                                        <strong class="text-dark">Database Saja (.sql)</strong>: Cadangan basis data MySQL (siswa, guru, presensi, jurnal, tagihan, dll).
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-success mr-2"></i> 
                                        <strong class="text-dark">Lengkap (.zip)</strong>: Database SQL + seluruh foto profil, dokumen siswa, dan modul di storage.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-auto pt-2">
                            <div class="row">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <form action="{{ route('admin.backup.create') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="database">
                                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 8px;">
                                            <i class="fas fa-database mr-1.5"></i> Database (.sql)
                                        </button>
                                    </form>
                                </div>
                                <div class="col-sm-6">
                                    <form action="{{ route('admin.backup.create') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="full">
                                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 8px;">
                                            <i class="fas fa-file-archive mr-1.5"></i> Lengkap (.zip)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Pulihkan (Restore) dari File Luar -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 mb-0 d-flex flex-column" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-upload text-warning mr-2" style="font-size: 1.1rem;"></i>
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">2. Pulihkan dari File Luar</h5>
                            </div>
                            <span class="badge badge-warning text-dark px-2.5 py-1 text-xs font-weight-bold">.sql &amp; .zip</span>
                        </div>
                    </div>

                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="alert alert-warning border-0 py-2 px-3 mb-3 text-dark" style="background-color: #fef9c3; border-radius: 8px; font-size: 0.83rem; line-height: 1.5;">
                                <i class="fas fa-exclamation-triangle mr-1 text-warning"></i>
                                <strong>Perhatian:</strong> Proses restore akan menimpa data database saat ini dengan data dari file yang Anda unggah.
                            </div>
                            <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                                Unggah berkas cadangan (<strong>.sql</strong> atau <strong>.zip</strong>) dari komputer Anda untuk mengembalikan sistem ke kondisi file tersebut.
                            </p>
                        </div>

                        <div class="mt-auto pt-2">
                            <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data" id="formUploadRestore">
                                @csrf
                                <div class="form-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="backup_file" name="backup_file" accept=".sql, .zip" required>
                                        <label class="custom-file-label text-truncate" for="backup_file" id="backup_file_label" style="border-radius: 8px;">Pilih file backup (.sql / .zip)...</label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning btn-block font-weight-bold py-2 shadow-sm text-dark" style="border-radius: 8px;" onclick="confirmUploadRestore()">
                                    <i class="fas fa-history mr-1.5"></i> Pulihkan Sistem Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel 3: Tabel Arsip Cadangan di Server -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-archive text-info mr-2" style="font-size: 1.1rem;"></i>
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">Arsip Cadangan di Server</h5>
                            </div>
                            <span class="badge badge-light border text-muted px-2.5 py-1 text-xs">
                                Total: {{ count($backups) }} File Tersimpan
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if(count($backups) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">No</th>
                                            <th>Nama File Backup</th>
                                            <th>Tipe Cadangan</th>
                                            <th>Ukuran</th>
                                            <th>Waktu Pembuatan</th>
                                            <th style="width: 200px;" class="text-center">Aksi Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($backups as $index => $item)
                                            <tr>
                                                <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item['is_full'])
                                                            <i class="fas fa-file-archive text-success fa-lg mr-2"></i>
                                                        @else
                                                            <i class="fas fa-file-code text-primary fa-lg mr-2"></i>
                                                        @endif
                                                        <span class="font-weight-bold text-dark">{{ $item['filename'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item['is_full'])
                                                        <span class="badge badge-success px-2.5 py-1 text-xs font-weight-bold">
                                                            <i class="fas fa-box-open mr-1"></i> Lengkap (DB + Storage)
                                                        </span>
                                                    @else
                                                        <span class="badge badge-primary px-2.5 py-1 text-xs font-weight-bold">
                                                            <i class="fas fa-database mr-1"></i> Database Saja
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-light border text-muted px-2.5 py-1 text-xs font-weight-normal">
                                                        {{ $item['size'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark small font-weight-500">{{ $item['created_at'] }}</span>
                                                    <span class="text-muted text-xs d-block">({{ $item['relative_time'] }})</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                                        <!-- Tombol Unduh -->
                                                        <a href="{{ route('admin.backup.download', $item['filename']) }}" 
                                                           class="btn btn-sm btn-info text-white shadow-sm" 
                                                           style="border-radius: 6px; font-weight: 500;"
                                                           title="Unduh File Cadangan">
                                                            <i class="fas fa-download mr-1"></i> Unduh
                                                        </a>

                                                        <!-- Tombol Restore Snapshot -->
                                                        <button type="button" 
                                                                class="btn btn-sm btn-warning text-dark font-weight-bold shadow-sm" 
                                                                style="border-radius: 6px;"
                                                                onclick="confirmRestoreSnapshot('{{ $item['filename'] }}', {{ $item['is_full'] ? 'true' : 'false' }})"
                                                                title="Pulihkan Sistem ke Titik Cadangan Ini">
                                                            <i class="fas fa-history mr-1"></i> Restore
                                                        </button>

                                                        <!-- Tombol Hapus -->
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger shadow-sm" 
                                                                style="border-radius: 6px;"
                                                                onclick="confirmDeleteBackup('{{ $item['filename'] }}')"
                                                                title="Hapus File Cadangan dari Server">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Form Tersembunyi Restore Snapshot -->
                                                    <form id="form-restore-{{ md5($item['filename']) }}" 
                                                          action="{{ route('admin.backup.restoreExisting', $item['filename']) }}" 
                                                          method="POST" 
                                                          style="display: none;">
                                                        @csrf
                                                    </form>

                                                    <!-- Form Tersembunyi Hapus Backup -->
                                                    <form id="form-delete-{{ md5($item['filename']) }}" 
                                                          action="{{ route('admin.backup.destroy', $item['filename']) }}" 
                                                          method="POST" 
                                                          style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                                <h6 class="font-weight-bold text-dark">Belum Ada File Cadangan Tersimpan</h6>
                                <p class="text-muted small mb-0">Klik tombol "Database (.sql)" atau "Lengkap (.zip)" di atas untuk membuat cadangan pertama Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SweetAlert2 Scripts -->
<script>
    // Update custom file input label when file selected
    document.getElementById('backup_file').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file backup (.sql / .zip)...';
        document.getElementById('backup_file_label').innerText = fileName;
    });

    // Konfirmasi Restore dari File Upload
    function confirmUploadRestore() {
        var fileInput = document.getElementById('backup_file');
        if (!fileInput.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih File Terlebih Dahulu',
                text: 'Silakan pilih file backup berekstensi .sql atau .zip dari komputer Anda.',
                confirmButtonColor: '#1d72fe'
            });
            return;
        }

        var fileName = fileInput.files[0].name;
        var ext = fileName.split('.').pop().toLowerCase();
        var extraText = (ext === 'zip') 
            ? 'File ZIP terdeteksi. Sistem akan memulihkan DATABASE dan BERKAS STORAGE (foto profil, dokumen siswa, dan modul).' 
            : 'File SQL terdeteksi. Sistem akan memulihkan DATABASE.';

        Swal.fire({
            title: 'Konfirmasi Pulihkan Sistem?',
            text: extraText + ' Seluruh data saat ini akan ditimpa. Lanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Pulihkan Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pemulihan Data...',
                    text: 'Sedang mengeksekusi restore database dan berkas storage. Mohon jangan menutup halaman...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('formUploadRestore').submit();
            }
        });
    }

    // Konfirmasi Restore dari Snapshot Server
    function confirmRestoreSnapshot(filename, isFull) {
        var md5Id = md5(filename);
        var desc = isFull 
            ? 'Sistem akan memulihkan DATABASE dan seluruh BERKAS STORAGE ke titik snapshot ' + filename + '.' 
            : 'Sistem akan memulihkan DATABASE ke titik snapshot ' + filename + '.';

        Swal.fire({
            title: 'Pulihkan ke Cadangan Ini?',
            text: desc + ' Data yang dibuat setelah tanggal cadangan tersebut akan tertimpa. Lanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Pulihkan Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memulihkan Sistem...',
                    text: 'Sedang mengeksekusi restore data dan membersihkan cache sistem...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('form-restore-' + md5Id).submit();
            }
        });
    }

    // Konfirmasi Hapus Backup
    function confirmDeleteBackup(filename) {
        var md5Id = md5(filename);
        Swal.fire({
            title: 'Hapus File Cadangan?',
            text: 'File ' + filename + ' akan dihapus permanen dari server.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus File',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + md5Id).submit();
            }
        });
    }

    // Simple MD5 implementation for element IDs
    function md5(string) {
        function RotateLeft(lValue, iShiftBits) {
            return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
        }
        function AddUnsigned(lX,lY) {
            var lX4,lY4,lX8,lY8,lResult;
            lX8 = (lX & 0x80000000);
            lY8 = (lY & 0x80000000);
            lX4 = (lX & 0x40000000);
            lY4 = (lY & 0x40000000);
            lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
            if (lX4 & lY4) return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
            if (lX4 | lY4) {
                if (lResult & 0x40000000) return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
                else return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            } else return (lResult ^ lX8 ^ lY8);
        }
        function F(x,y,z) { return (x & y) | ((~x) & z); }
        function G(x,y,z) { return (x & z) | (y & (~z)); }
        function H(x,y,z) { return (x ^ y ^ z); }
        function I(x,y,z) { return (y ^ (x | (~z))); }
        function FF(a,b,c,d,x,s,ac) {
            a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
            return AddUnsigned(RotateLeft(a, s), b);
        };
        function GG(a,b,c,d,x,s,ac) {
            a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
            return AddUnsigned(RotateLeft(a, s), b);
        };
        function HH(a,b,c,d,x,s,ac) {
            a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
            return AddUnsigned(RotateLeft(a, s), b);
        };
        function II(a,b,c,d,x,s,ac) {
            a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
            return AddUnsigned(RotateLeft(a, s), b);
        };
        function ConvertToWordArray(string) {
            var lWordCount;
            var lMessageLength = string.length;
            var lNumberOfWords_temp1=lMessageLength + 8;
            var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
            var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
            var lWordArray=Array(lNumberOfWords-1);
            var lBytePosition = 0;
            var lByteCount = 0;
            while ( lByteCount < lMessageLength ) {
                lWordCount = (lByteCount-(lByteCount % 4))/4;
                lBytePosition = (lByteCount % 4)*8;
                lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount)<<lBytePosition));
                lByteCount++;
            }
            lWordCount = (lByteCount-(lByteCount % 4))/4;
            lBytePosition = (lByteCount % 4)*8;
            lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
            lWordArray[lNumberOfWords-2] = lMessageLength<<3;
            lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
            return lWordArray;
        };
        function WordToHex(lValue) {
            var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
            for (lCount = 0;lCount<=3;lCount++) {
                lByte = (lValue>>>(lCount*8)) & 255;
                WordToHexValue_temp = "0" + lByte.toString(16);
                WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
            }
            return WordToHexValue;
        };
        var x=Array();
        var k,AA,BB,CC,DD,a,b,c,d;
        var S11=7, S12=12, S13=17, S14=22;
        var S21=5, S22=9 , S23=14, S24=20;
        var S31=4, S32=11, S33=16, S34=23;
        var S41=6, S42=10, S43=15, S44=21;
        x = ConvertToWordArray(string);
        a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
        for (k=0;k<x.length;k+=16) {
            AA=a; BB=b; CC=c; DD=d;
            a=FF(a,b,c,d,x[k+0], S11,0xD76AA478); d=FF(d,a,b,c,x[k+1], S12,0xE8C7B756); c=FF(c,d,a,b,x[k+2], S13,0x242070DB); b=FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
            a=FF(a,b,c,d,x[k+4], S11,0xF57C0FAF); d=FF(d,a,b,c,x[k+5], S12,0x4787C62A); c=FF(c,d,a,b,x[k+6], S13,0xA8304613); b=FF(b,c,d,a,x[k+7], S14,0xFD469501);
            a=FF(a,b,c,d,x[k+8], S11,0x698098D8); d=FF(d,a,b,c,x[k+9], S12,0x8B44F7AF); c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1); b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
            a=FF(a,b,c,d,x[k+12],S11,0x6B901122); d=FF(d,a,b,c,x[k+13],S12,0xFD987193); c=FF(c,d,a,b,x[k+14],S13,0xA679438E); b=FF(b,c,d,a,x[k+15],S14,0x49B40821);
            a=GG(a,b,c,d,x[k+1], S21,0xF61E2562); d=GG(d,a,b,c,x[k+6], S22,0xC040B340); c=GG(c,d,a,b,x[k+11],S23,0x265E5A51); b=GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
            a=GG(a,b,c,d,x[k+5], S21,0xD62F105D); d=GG(d,a,b,c,x[k+10],S22,0x2441453);  c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681); b=GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
            a=GG(a,b,c,d,x[k+9], S21,0x21E1CDE6); d=GG(d,a,b,c,x[k+14],S22,0xC33707D6); c=GG(c,d,a,b,x[k+3], S23,0xF4D50D87); b=GG(b,c,d,a,x[k+8], S24,0x455A14ED);
            a=GG(a,b,c,d,x[k+13],S21,0xA9E3E905); d=GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8); c=GG(c,d,a,b,x[k+7], S23,0x676F02D9); b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
            a=HH(a,b,c,d,x[k+5], S31,0xFFFA3942); d=HH(d,a,b,c,x[k+8], S32,0x8771F681); c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122); b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
            a=HH(a,b,c,d,x[k+1], S31,0xA4BEEA44); d=HH(d,a,b,c,x[k+4], S32,0x4BDECFA9); c=HH(c,d,a,b,x[k+7], S33,0xF6BB4B60); b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
            a=HH(a,b,c,d,x[k+13],S31,0x289B7EC6); d=HH(d,a,b,c,x[k+0], S32,0xEAA127FA); c=HH(c,d,a,b,x[k+3], S33,0xD4EF3085); b=HH(b,c,d,a,x[k+6], S34,0x4881D05);
            a=HH(a,b,c,d,x[k+9], S31,0xD9D4D039); d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5); c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8); b=HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
            a=II(a,b,c,d,x[k+0], S41,0xF4292244); d=II(d,a,b,c,x[k+7], S42,0x432AFF97); c=II(c,d,a,b,x[k+14],S43,0xAB9423A7); b=II(b,c,d,a,x[k+5], S44,0xFC93A039);
            a=II(a,b,c,d,x[k+12],S41,0x655B59C3); d=II(d,a,b,c,x[k+3], S42,0x8F0CCC92); c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D); b=II(b,c,d,a,x[k+1], S44,0x85845DD1);
            a=II(a,b,c,d,x[k+8], S41,0x6FA87E4F); d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0); c=II(c,d,a,b,x[k+6], S43,0xA3014314); b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);
            a=II(a,b,c,d,x[k+4], S41,0xF7537E82); d=II(d,a,b,c,x[k+11],S42,0xBD3AF235); c=II(c,d,a,b,x[k+2], S43,0x2AD7D2BB); b=II(b,c,d,a,x[k+9], S44,0xEB86D391);
            a=AddUnsigned(a,AA); b=AddUnsigned(b,BB); c=AddUnsigned(c,CC); d=AddUnsigned(d,DD);
        }
        return (WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d)).toLowerCase();
    }
</script>
@endsection
