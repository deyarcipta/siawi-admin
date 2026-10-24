@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-edit text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Lembar Penilaian UKK Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">
            Peserta: <strong>{{ $peserta->siswa->nama_siswa ?? 'Siswa' }}</strong> (NIS: {{ $peserta->siswa->nis ?? '-' }} | Kelas: {{ $peserta->siswa->kelas->nama_kelas ?? '-' }})
          </p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}" class="text-primary font-weight-500">Detail UKK</a></li>
          <li class="breadcrumb-item active">Input Nilai</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-circle mr-2"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}/simpan/{{ $peserta->id_ukk_peserta }}" method="POST">
      @csrf

      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h5 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-tasks text-primary mr-2"></i> Asesmen Unit Kompetensi Kejuruan
            </h5>
            <small class="text-muted d-block mt-1">Kegiatan: {{ $kegiatan->nama_kegiatan }}</small>
          </div>
          <span class="badge badge-light border text-primary px-3 py-2 font-weight-bold">
            Total: {{ $kegiatan->units->count() }} Unit Kompetensi
          </span>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
              <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem;">
                <tr>
                  <th style="width: 50px" class="text-center">No</th>
                  <th>Unit Kompetensi</th>
                  <th style="width: 80px" class="text-center">KKM</th>
                  <th style="width: 140px">Nilai Angka (0-100)</th>
                  <th style="width: 200px">Penguji / Asesor</th>
                  <th>Catatan Asesor</th>
                  <th style="width: 180px">Jadwal Remedial (Jika < KKM)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kegiatan->units as $u)
                  @php
                    $exist = $nilaiMap[$u->id_ukk_unit] ?? null;
                    $valNilai = old('nilai_' . $u->id_ukk_unit, $exist ? $exist->nilai : '');
                    $valPenguji = old('penguji_' . $u->id_ukk_unit, $exist ? $exist->id_ukk_penguji : '');
                    $valCatatan = old('catatan_' . $u->id_ukk_unit, $exist ? $exist->catatan_penguji : '');
                    $valRemedial = old('remedial_' . $u->id_ukk_unit, ($exist && $exist->jadwal_remedial) ? $exist->jadwal_remedial->format('Y-m-d') : '');
                  @endphp
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td class="align-middle">
                      <span class="badge badge-light border text-primary font-weight-bold px-2 py-1 mb-1">
                        {{ $u->kompetensi->kode_kompetensi ?? '-' }}
                      </span>
                      <div class="font-weight-600 text-dark">
                        {{ $u->kompetensi->nama_kompetensi ?? '-' }}
                      </div>
                    </td>
                    <td class="text-center align-middle font-weight-bold text-muted">
                      {{ floatval($u->kkm) }}
                    </td>
                    <td class="align-middle">
                      <input type="number" step="0.01" min="0" max="100" name="nilai_{{ $u->id_ukk_unit }}" class="form-control form-control-sm font-weight-bold text-dark" value="{{ $valNilai }}" placeholder="0.00" required>
                    </td>
                    <td class="align-middle">
                      <select name="penguji_{{ $u->id_ukk_unit }}" class="form-control form-control-sm" required>
                        <option value="">-- Pilih Penguji --</option>
                        @foreach($kegiatan->pengujis as $png)
                          <option value="{{ $png->id_ukk_penguji }}" {{ $valPenguji == $png->id_ukk_penguji ? 'selected' : '' }}>
                            {{ $png->nama_tampil }}
                          </option>
                        @endforeach
                      </select>
                    </td>
                    <td class="align-middle">
                      <input type="text" name="catatan_{{ $u->id_ukk_unit }}" class="form-control form-control-sm" value="{{ $valCatatan }}" placeholder="Catatan teknis...">
                    </td>
                    <td class="align-middle">
                      <input type="date" name="remedial_{{ $u->id_ukk_unit }}" class="form-control form-control-sm" value="{{ $valRemedial }}">
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer bg-light p-3">
          <div class="form-group mb-0">
            <label class="font-weight-bold text-dark small mb-1">Catatan Evaluasi Umum Asesor / Kaprog (Opsional)</label>
            <textarea name="catatan_umum" class="form-control" rows="2" placeholder="Catatan rekapitulasi penilaian atau rekomendasi industri...">{{ old('catatan_umum', $peserta->catatan_umum) }}</textarea>
          </div>
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between mb-5">
        <a href="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-save mr-1"></i> Simpan Nilai & Alirkan ke Capaian Siswa
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
