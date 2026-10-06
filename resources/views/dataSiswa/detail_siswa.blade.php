@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-id-card text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Detail Data Siswa</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Biodata lengkap, kontak, dan informasi data orang tua / wali siswa</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/siswa">Data Siswa</a></li>
            <li class="breadcrumb-item active">Detail Siswa</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
              <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-user-graduate text-primary mr-2"></i> Biodata Siswa: {{ $detail->nama_siswa }}
              </h3>
              <div class="d-flex align-items-center ml-auto mt-2 mt-md-0">
                <a href="/admin/siswa" class="btn btn-secondary btn-sm mr-2 shadow-sm">
                  <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <a href="/admin/siswa/{{ $detail->id_siswa }}/edit" class="btn btn-warning btn-sm text-white font-weight-600 shadow-sm">
                  <i class="fas fa-edit mr-1"></i> Edit Data Siswa
                </a>
              </div>
            </div>
            <div class="card-body">
              <!-- Bagian 1: Foto & Profil Utama -->
              <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                  <thead>
                    <tr class="bg-light">
                      <th class="text-center" style="width: 220px;">FOTO SISWA</th>
                      <th colspan="2">DATA PRIBADI SISWA</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td rowspan="5" class="align-middle text-center p-3" style="background: #f8fafc;">
                        @if($detail->foto && $detail->foto !== 'avatar.jpg' && (file_exists(public_path('storage/foto-siswa/' . $detail->foto)) || file_exists(storage_path('app/public/foto-siswa/' . $detail->foto))))
                          <img src="{{ asset('storage/foto-siswa/' . $detail->foto) }}" alt="Foto Siswa" class="img-thumbnail rounded shadow-sm" style="width: 140px; height: 190px; object-fit: cover;">
                        @else
                          <img src="{{ asset('lte/dist/img/avatar.png') }}" alt="Avatar Default" class="img-thumbnail rounded shadow-sm" style="width: 140px; height: 190px; object-fit: cover;">
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
                      <td class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $detail->nama_siswa }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Kelas</td>
                      <td>
                        <span class="badge badge-soft-primary px-3 py-1 font-weight-bold" style="font-size: 0.84rem;">
                          {{ $detail->kelas->nama_kelas ?? '-' }}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Kompetensi Keahlian</td>
                      <td class="font-weight-500 text-dark">
                        @if ($detail->id_jurusan == "2")
                          Perhotelan
                        @elseif ($detail->id_jurusan == "5")
                          Kuliner
                        @elseif ($detail->id_jurusan == "3")
                          Teknik Jaringan Komputer dan Telekomunikasi
                        @else
                          {{ $detail->jurusan->nama_jurusan ?? '-' }}
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Bagian 2: Alamat & Kontak -->
              <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                  <thead>
                    <tr class="bg-light">
                      <th colspan="4"><i class="fas fa-map-marker-alt text-primary mr-1"></i> INFORMASI ALAMAT & KONTAK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="font-weight-600 text-secondary" style="width: 20%;">Tempat, Tanggal Lahir</td>
                      <td class="text-dark" style="width: 30%;">{{ $detail->tmpt_lahir ?? '-' }}, {{ $detail->tgl_lahir ?? '-' }}</td>
                      <td class="font-weight-600 text-secondary" style="width: 20%;">Jenis Kelamin</td>
                      <td class="text-dark" style="width: 30%;">{{ $detail->jenis_kelamin == 'L' ? 'Laki-laki' : ($detail->jenis_kelamin == 'P' ? 'Perempuan' : ($detail->jenis_kelamin ?? '-')) }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Agama</td>
                      <td class="text-dark">{{ $detail->agama ?? '-' }}</td>
                      <td class="font-weight-600 text-secondary">Nomor Telepon</td>
                      <td class="text-dark">{{ $detail->no_tlpn ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Nomor HP</td>
                      <td class="text-dark">{{ $detail->no_hp ?? '-' }}</td>
                      <td class="font-weight-600 text-secondary">Email</td>
                      <td class="text-dark">{{ $detail->email ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Alamat</td>
                      <td colspan="3" class="text-dark">{{ $detail->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">RT / RW</td>
                      <td class="text-dark">{{ $detail->rt ?? '-' }} / {{ $detail->rw ?? '-' }}</td>
                      <td class="font-weight-600 text-secondary">Nomor Rumah</td>
                      <td class="text-dark">{{ $detail->no_rumah ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Kelurahan / Desa</td>
                      <td class="text-dark">{{ $detail->kel ?? '-' }}</td>
                      <td class="font-weight-600 text-secondary">Kecamatan</td>
                      <td class="text-dark">{{ $detail->kec ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Kota / Kabupaten</td>
                      <td class="text-dark">{{ $detail->kota ?? '-' }}</td>
                      <td class="font-weight-600 text-secondary">Provinsi</td>
                      <td class="text-dark">{{ $detail->prov ?? '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Bagian 3: Data Orang Tua / Wali -->
              <div class="table-responsive">
                <table class="table table-bordered align-middle">
                  <thead>
                    <tr class="bg-light">
                      <th style="width: 25%;">DATA ORANG TUA</th>
                      <th style="width: 25%;">DATA AYAH</th>
                      <th style="width: 25%;">DATA IBU</th>
                      <th style="width: 25%;">DATA WALI</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="font-weight-600 text-secondary">NIK</td>
                      <td class="text-dark">{{ $detail->nik_ayah ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->nik_ibu ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->nik_wali ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Nama Lengkap</td>
                      <td class="text-dark font-weight-500">{{ $detail->nama_ayah ?? '-' }}</td>
                      <td class="text-dark font-weight-500">{{ $detail->nama_ibu ?? '-' }}</td>
                      <td class="text-dark font-weight-500">{{ $detail->nama_wali ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Tempat, Tanggal Lahir</td>
                      <td class="text-dark">{{ $detail->tmpt_lahir_ayah ?? '-' }}, {{ $detail->tgl_lahir_ayah ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->tmpt_lahir_ibu ?? '-' }}, {{ $detail->tgl_lahir_ibu ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->tmpt_lahir_wali ?? '-' }}, {{ $detail->tgl_lahir_wali ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Pendidikan Terakhir</td>
                      <td class="text-dark">{{ $detail->pendidikan_ayah ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->pendidikan_ibu ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->pendidikan_wali ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Pekerjaan</td>
                      <td class="text-dark">{{ $detail->pekerjaan_ayah ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->pekerjaan_ibu ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->pekerjaan_wali ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-600 text-secondary">Penghasilan Bulanan</td>
                      <td class="text-dark">{{ $detail->penghasilan_ayah ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->penghasilan_ibu ?? '-' }}</td>
                      <td class="text-dark">{{ $detail->penghasilan_wali ?? '-' }}</td>
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