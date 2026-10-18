@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-id-card text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Detail Data Mutasi</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Arsip lengkap data identitas dan informasi mutasi siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/siswaMutasi" class="text-primary font-weight-500">Data Mutasi</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-user-graduate text-primary mr-2"></i> Biodata Siswa: {{ $detail->nama_siswa }}
            </h3>
            <div class="d-flex align-items-center ml-auto mt-2 mt-md-0">
              <a href="/admin/siswaMutasi" class="btn btn-secondary btn-sm mr-2 shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              @if($user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum', 'kesiswaan']))
              <a href="/admin/siswaMutasi/{{ $detail->id_mutasi }}/edit" class="btn btn-warning btn-sm text-white font-weight-600 shadow-sm mr-2">
                <i class="fas fa-edit mr-1"></i> Edit Mutasi
              </a>
              <form action="{{ route('admin.siswaMutasi.restore', $detail->id_mutasi) }}" method="POST" class="d-inline form-restore mr-2">
                @csrf
                <button type="button" class="btn btn-info btn-sm btn-action-restore font-weight-600 shadow-sm" data-nama="{{ $detail->nama_siswa }}">
                  <i class="fas fa-undo mr-1"></i> Kembalikan ke Siswa Aktif
                </button>
              </form>
              @endif
            </div>
          </div>

          <div class="card-body">
            <!-- Informasi Mutasi Alert Box -->
            <div class="card card-outline card-danger bg-light mb-4 shadow-none border">
              <div class="card-body py-3">
                <div class="row align-items-center">
                  <div class="col-md-3 border-right mb-2 mb-md-0">
                    <span class="text-muted small d-block">Status & Jenis Mutasi:</span>
                    <span class="badge {{ $detail->badge_class }} px-2 py-1 mt-1 font-weight-bold" style="font-size: 0.9rem;">
                      {{ $detail->jenis_mutasi }}
                    </span>
                  </div>
                  <div class="col-md-3 border-right mb-2 mb-md-0">
                    <span class="text-muted small d-block">Tanggal Mutasi:</span>
                    <span class="font-weight-bold text-dark" style="font-size: 1rem;">
                      {{ $detail->tanggal_mutasi ? $detail->tanggal_mutasi->isoFormat('D MMMM Y') : '-' }}
                    </span>
                  </div>
                  <div class="col-md-3 border-right mb-2 mb-md-0">
                    <span class="text-muted small d-block">Sekolah / Institusi Tujuan:</span>
                    <span class="font-weight-600 text-dark">
                      {{ $detail->sekolah_tujuan ?: 'Tidak ada / bukan pindah sekolah' }}
                    </span>
                  </div>
                  <div class="col-md-3">
                    <span class="text-muted small d-block">Dicatat Oleh:</span>
                    <span class="font-weight-600 text-dark">
                      {{ $detail->dicatat_oleh ?: 'Admin' }}
                    </span>
                  </div>
                </div>
                @if($detail->alasan_mutasi)
                <div class="row mt-3 pt-2 border-top">
                  <div class="col-12">
                    <span class="text-muted small d-block font-weight-600">Catatan / Alasan Mutasi:</span>
                    <div class="text-dark mt-1 p-2 bg-white rounded border small">
                      {{ $detail->alasan_mutasi }}
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </div>

            <!-- Tabel Data Siswa -->
            <div class="table-responsive mb-4">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr class="bg-light">
                    <th class="text-center" style="width: 200px;">FOTO SISWA</th>
                    <th colspan="2">IDENTITAS & DATA AKADEMIK TERAKHIR</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td rowspan="6" class="align-middle text-center p-3 bg-light">
                      @php
                        $avatarList = ['avatar.jpg', 'avatar.png', 'avatar_male.jpg', 'avatar_male.png', 'avatar_female.jpg', 'avatar_female.png'];
                      @endphp
                      @if($detail->foto && !in_array($detail->foto, $avatarList))
                        <img src="{{ asset('storage/foto-siswa/' . $detail->foto) }}" alt="Foto Siswa" class="img-thumbnail rounded shadow-sm" style="max-width: 150px; max-height: 200px; object-fit: cover;">
                      @else
                        <div class="bg-white border rounded p-4 text-center d-inline-block shadow-sm">
                          <i class="fas fa-user-graduate text-secondary fa-4x"></i>
                          <p class="text-muted small mb-0 mt-2">Tidak ada foto</p>
                        </div>
                      @endif
                    </td>
                    <th style="width: 220px;" class="bg-light">Nama Lengkap</th>
                    <td class="font-weight-bold text-dark">{{ $detail->nama_siswa }}</td>
                  </tr>
                  <tr>
                    <th class="bg-light">NIS / NISN</th>
                    <td>
                      <code>{{ $detail->nis ?? '-' }}</code> / <code>{{ $detail->nisn ?? '-' }}</code>
                    </td>
                  </tr>
                  <tr>
                    <th class="bg-light">Kelas Terakhir</th>
                    <td>
                      <span class="badge badge-primary px-2 py-1 font-weight-normal" style="font-size: 0.85rem;">
                        {{ $detail->kelas_terakhir ?? ($detail->kelas->nama_kelas ?? '-') }}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <th class="bg-light">Jurusan Terakhir</th>
                    <td>{{ $detail->jurusan_terakhir ?? ($detail->jurusan->nama_jurusan ?? '-') }}</td>
                  </tr>
                  <tr>
                    <th class="bg-light">Tempat, Tanggal Lahir</th>
                    <td>{{ $detail->tmpt_lahir ?? '-' }}, {{ $detail->tgl_lahir ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th class="bg-light">Jenis Kelamin / Agama</th>
                    <td>
                      {{ $detail->jenis_kelamin == 'L' ? 'Laki-laki' : ($detail->jenis_kelamin == 'P' ? 'Perempuan' : ($detail->jenis_kelamin ?? '-')) }} / {{ $detail->agama ?? '-' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Kontak & Alamat -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="card h-100 border">
                  <div class="card-header bg-light py-2">
                    <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-address-book text-primary mr-2"></i> Kontak & Alamat Siswa</h6>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                      <tr>
                        <th style="width: 140px;" class="pl-3">No. HP / WA</th>
                        <td>{{ $detail->no_hp ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">No. Telepon</th>
                        <td>{{ $detail->no_tlpn ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">Email</th>
                        <td>{{ $detail->email ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">Alamat Lengkap</th>
                        <td>{{ $detail->alamat ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">RT / RW</th>
                        <td>{{ $detail->rt ?? '-' }} / {{ $detail->rw ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">Kelurahan / Kecamatan</th>
                        <td>{{ $detail->kel ?? '-' }} / {{ $detail->kec ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">Kota / Provinsi</th>
                        <td>{{ $detail->kota ?? '-' }} / {{ $detail->prov ?? '-' }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Data Orang Tua -->
              <div class="col-md-6 mb-3">
                <div class="card h-100 border">
                  <div class="card-header bg-light py-2">
                    <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-friends text-primary mr-2"></i> Data Orang Tua / Wali</h6>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                      <tr>
                        <th style="width: 140px;" class="pl-3">Nama Ayah</th>
                        <td>{{ $detail->nama_ayah ?? '-' }} ({{ $detail->pekerjaan_ayah ?? '-' }})</td>
                      </tr>
                      <tr>
                        <th class="pl-3">No. HP Ayah</th>
                        <td>{{ $detail->no_hp_ayah ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">Nama Ibu</th>
                        <td>{{ $detail->nama_ibu ?? '-' }} ({{ $detail->pekerjaan_ibu ?? '-' }})</td>
                      </tr>
                      <tr>
                        <th class="pl-3">No. HP Ibu</th>
                        <td>{{ $detail->no_hp_ibu ?? '-' }}</td>
                      </tr>
                      <tr>
                        <th class="pl-3">Nama Wali</th>
                        <td>{{ $detail->nama_wali ?? '-' }} ({{ $detail->pekerjaan_wali ?? '-' }})</td>
                      </tr>
                      <tr>
                        <th class="pl-3">No. HP Wali</th>
                        <td>{{ $detail->no_hp_wali ?? '-' }}</td>
                      </tr>
                      @if($detail->orangTua)
                      <tr>
                        <th class="pl-3">Akun Wali Portal</th>
                        <td>
                          <code>{{ $detail->orangTua->username }}</code>
                          @if($detail->orangTua->status_aktif)
                            <span class="badge badge-success ml-1">Aktif</span>
                          @else
                            <span class="badge badge-danger ml-1">Nonaktif</span>
                          @endif
                        </td>
                      </tr>
                      @endif
                    </table>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const restoreButtons = document.querySelectorAll('.btn-action-restore');
  restoreButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const form = this.closest('form');
      const nama = this.getAttribute('data-nama') || 'siswa ini';

      Swal.fire({
        title: 'Kembalikan Siswa?',
        text: `Data ${nama} akan dipulihkan kembali ke daftar Siswa Aktif.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
</script>
@endpush
