@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-id-card text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Detail Data Alumni</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Biodata lengkap, kontak, status penelusuran tamatan alumni</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/dataAlumni" class="text-primary font-weight-500">Data Alumni</a></li>
          <li class="breadcrumb-item active">Detail Alumni</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-user-graduate text-primary mr-2"></i> Biodata Alumni: {{ $detail->nama }}
            </h3>
            <div class="d-flex align-items-center ml-auto mt-2 mt-md-0">
              <a href="/admin/dataAlumni" class="btn btn-secondary btn-sm mr-2 shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <a href="/admin/alumni/{{ $detail->id_alumni }}/edit" class="btn btn-warning btn-sm text-white font-weight-600 shadow-sm">
                <i class="fas fa-edit mr-1"></i> Edit Data Alumni
              </a>
            </div>
          </div>
          <div class="card-body">
            <!-- Bagian 1: Foto & Profil Utama -->
            <div class="table-responsive mb-3">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr class="bg-light">
                    <th class="text-center" style="width: 220px;">FOTO ALUMNI</th>
                    <th colspan="2">DATA AKADEMIK & KELULUSAN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td rowspan="5" class="align-middle text-center p-3" style="background: #f8fafc;">
                      @if($detail->foto && $detail->foto != 'avatar.jpg')
                        <img src="{{ asset('storage/foto-siswa/' . $detail->foto) }}" alt="Foto Alumni" class="img-thumbnail rounded shadow-sm" style="width: 140px; height: 190px; object-fit: cover;">
                      @else
                        <div class="avatar-placeholder rounded bg-light d-inline-flex align-items-center justify-content-center text-secondary font-weight-bold shadow-sm" style="width: 140px; height: 190px; font-size: 2.5rem;">
                          {{ strtoupper(substr($detail->nama ?? 'A', 0, 1)) }}
                        </div>
                      @endif
                    </td>
                    <td class="font-weight-600 text-secondary" style="width: 220px;">NIS</td>
                    <td class="font-weight-bold text-dark">{{ $detail->nis ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-600 text-secondary">NISN</td>
                    <td class="font-weight-500 text-dark">{{ $detail->nisn ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-600 text-secondary">Nama Lengkap</td>
                    <td class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $detail->nama }}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-600 text-secondary">Tahun Lulus</td>
                    <td>
                      <span class="badge badge-soft-info px-3 py-1 font-weight-bold" style="font-size: 0.84rem;">
                        Tahun {{ $detail->tahun_lulus ?? '-' }}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-600 text-secondary">Kompetensi Keahlian</td>
                    <td class="font-weight-500 text-dark">
                      {{ $detail->jurusan->nama_jurusan ?? '-' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Bagian 2: Biodata Pribadi & Status -->
            <div class="table-responsive mb-3">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr class="bg-light">
                    <th colspan="4"><i class="fas fa-info-circle text-primary mr-1"></i> BIODATA PRIBADI & STATUS TAMATAN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="font-weight-600 text-secondary" style="width: 20%;">Tempat, Tanggal Lahir</td>
                    <td class="text-dark" style="width: 30%;">
                      {{ $detail->tempat_lahir ?? '-' }}, {{ $detail->tanggal_lahir ? date('d-m-Y', strtotime($detail->tanggal_lahir)) : '-' }}
                    </td>
                    <td class="font-weight-600 text-secondary" style="width: 20%;">Jenis Kelamin</td>
                    <td class="text-dark" style="width: 30%;">
                      {{ $detail->jenis_kelamin == 'L' ? 'Laki-laki' : ($detail->jenis_kelamin == 'P' ? 'Perempuan' : ($detail->jenis_kelamin ?? '-')) }}
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-600 text-secondary">Agama</td>
                    <td class="text-dark">{{ $detail->agama ?? '-' }}</td>
                    <td class="font-weight-600 text-secondary">Status Tamatan</td>
                    <td class="text-dark">
                      @if($detail->status && $detail->status != '-')
                        <span class="badge badge-soft-success px-3 py-1 font-weight-bold">{{ $detail->status }}</span>
                      @else
                        <span class="badge badge-soft-secondary px-3 py-1">-</span>
                      @endif
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Bagian 3: Alamat & Kontak -->
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr class="bg-light">
                    <th colspan="4"><i class="fas fa-map-marker-alt text-primary mr-1"></i> INFORMASI ALAMAT & KONTAK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="font-weight-600 text-secondary" style="width: 20%;">Nomor WhatsApp / HP</td>
                    <td class="text-dark" style="width: 30%;">{{ $detail->no_hp ?? '-' }}</td>
                    <td class="font-weight-600 text-secondary" style="width: 20%;">Email</td>
                    <td class="text-dark" style="width: 30%;">{{ $detail->email ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-600 text-secondary">Alamat Lengkap</td>
                    <td colspan="3" class="text-dark">{{ $detail->alamat ?? '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
