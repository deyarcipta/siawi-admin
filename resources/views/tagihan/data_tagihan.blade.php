@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-file-invoice-dollar text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Tagihan Siswa</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kelola link invoice tagihan dan rincian pembayaran SPP siswa</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tagihan Siswa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h3 class="card-title text-dark font-weight-bold mb-0">
                            <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Tagihan Per Kelas
                        </h3>
                        @if($user && in_array($user->role, ['admin', 'keuangan']))
                        <a href="/admin/tagihan/1/edit" class="btn btn-secondary btn-sm ml-auto">
                            <i class="fas fa-edit mr-1"></i> Edit Link Template
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="/admin/tagihan" method="GET">
                            <div class="row align-items-end">
                                <div class="form-group col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="kelas" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Kelas</label>
                                    <select class="form-control" id="kelas" name="kelas" onchange="this.form.submit()">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2 col-12 mb-0">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        @if(isset($siswa))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h3 class="card-title text-dark font-weight-bold mb-0">
                            <i class="fas fa-table text-primary mr-2"></i> Data Tagihan Siswa
                        </h3>
                        <span class="badge badge-light border text-muted px-3 py-1 font-weight-600 ml-auto mt-2 mt-md-0" style="border-radius: 20px; font-size: 0.78rem;">
                            {{ $siswa->count() }} siswa ditemukan
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 10px" class="text-center">No</th>
                                        <th style="width: 120px" class="text-center">NIS</th>
                                        <th>Nama Siswa</th>
                                        <th style="width: 140px" class="text-center">Kelas</th>
                                        <th style="width: 220px" class="text-center">Aksi Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswa as $data)
                                    @php
                                        $fullLink = ($tagihan && $tagihan->link && $data->nis) ? $tagihan->link . $data->nis : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                        <td class="text-center font-weight-600 text-dark">{{ $data->nis ?? '-' }}</td>
                                        <td class="font-weight-bold text-dark">{{ $data->nama_siswa }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-soft-primary font-weight-bold px-2 py-1">
                                                {{ $data->kelas->nama_kelas ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($fullLink)
                                                <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1" onclick="copyInvoiceLink('{{ $fullLink }}', '{{ addslashes($data->nama_siswa) }}')" title="Salin Link Invoice" style="font-size: 0.78rem;">
                                                        <i class="fas fa-copy text-primary mr-1"></i> Salin
                                                    </button>
                                                    <a href="{{ $fullLink }}" target="_blank" class="btn btn-primary btn-sm px-2 py-1" title="Buka Invoice di Tab Baru" style="font-size: 0.78rem;">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Buka
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-muted small">NIS belum diatur</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle mr-1"></i> Tidak ada data siswa untuk kelas yang dipilih.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyInvoiceLink(link, namaSiswa) {
    if (!link) return;
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(link).then(function() {
            showToastSuccess(namaSiswa);
        }).catch(function() {
            fallbackCopy(link, namaSiswa);
        });
    } else {
        fallbackCopy(link, namaSiswa);
    }
}

function fallbackCopy(text, namaSiswa) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showToastSuccess(namaSiswa);
    } catch (err) {
        alert("Link gagal disalin otomatis: " + text);
    }
    document.body.removeChild(textArea);
}

function showToastSuccess(namaSiswa) {
    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: 'success',
            title: 'Link tagihan ' + (namaSiswa ? namaSiswa : '') + ' berhasil disalin!'
        });
    } else {
        alert("Link tagihan berhasil disalin!");
    }
}
</script>
@endpush
