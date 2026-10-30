@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-edit"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Matriks Penilaian Praktik Kelas</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Input nilai per indikator untuk seluruh siswa satu kelas secara simultan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/penilaian-praktik" class="text-primary font-weight-500">Penilaian Praktik</a></li>
          <li class="breadcrumb-item active">Input Nilai</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('warning'))
      <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('info'))
      <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Info Banner Header -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-md-3 mb-2 mb-md-0 border-right">
            <span class="text-muted small d-block">Kelas & Tanggal:</span>
            <span class="font-weight-bold text-dark" style="font-size: 1.05rem;">
              {{ $penilaian->kelas->nama_kelas ?? '-' }}
            </span>
            <span class="text-muted small d-block">
              {{ \Carbon\Carbon::parse($penilaian->tanggal_penilaian)->translatedFormat('d F Y') }}
            </span>
          </div>

          <div class="col-md-5 mb-2 mb-md-0 border-right">
            <span class="text-muted small d-block">Modul Praktikum:</span>
            <span class="font-weight-bold text-dark d-block text-truncate" title="{{ $penilaian->modul->judul ?? '-' }}">
              {{ $penilaian->modul->judul ?? '-' }}
            </span>
            <small class="text-primary font-weight-bold">
              [{{ $penilaian->kompetensi->kode_kompetensi ?? '-' }}] {{ $penilaian->kompetensi->nama_kompetensi ?? '-' }}
            </small>
          </div>

          <div class="col-md-2 mb-2 mb-md-0 border-right text-center">
            <span class="text-muted small d-block">Batas KKTP / KKM:</span>
            <span class="badge badge-primary px-3 py-1 font-weight-bold" style="font-size: 1rem;">
              {{ floatval($penilaian->kkm_saat_penilaian) }}
            </span>
          </div>

          <div class="col-md-2 text-center">
            <span class="text-muted small d-block">Status Sesi:</span>
            @if($penilaian->status === 'final')
              <span class="badge badge-success px-2 py-1 font-weight-bold">
                <i class="fas fa-check-double mr-1"></i> Final (Rilis)
              </span>
            @else
              <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                <i class="fas fa-pencil-alt mr-1"></i> Draft Guru
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Notifikasi Siswa Belum Masuk -->
    @if($missingSiswas->isNotEmpty())
      <div class="alert alert-warning border-0 shadow-sm mb-3 d-flex align-items-center justify-content-between flex-wrap" style="border-radius: 10px; background-color: #fff9db; color: #856404; border-left: 4px solid #f59f00 !important;">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <i class="fas fa-exclamation-triangle mr-3 text-warning" style="font-size: 1.35rem;"></i>
          <div>
            <strong style="font-size: 0.95rem;">Terdapat {{ $missingSiswas->count() }} siswa di kelas {{ $penilaian->kelas->nama_kelas ?? '-' }} yang belum terdaftar pada sesi penilaian ini.</strong>
            <div class="small text-muted mt-0">Siswa belum masuk karena ditambahkan setelah sesi dibuat atau data awal dibatasi. Klik tombol di kanan untuk langsung memasukkan mereka ke tabel nilai.</div>
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-warning btn-sm font-weight-bold px-3 shadow-sm text-dark" style="border-radius: 6px;" onclick="confirmSyncSiswa({{ $missingSiswas->count() }})">
            <i class="fas fa-sync-alt mr-1"></i> Sinkronkan {{ $missingSiswas->count() }} Siswa Sekarang
          </button>
        </div>
      </div>
    @endif

    <!-- Formulir Matriks Kelas -->
    <form action="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/scores" method="POST" id="formPenilaian">
      @csrf

      <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap">
          <div class="d-flex align-items-center mb-2 mb-sm-0">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
              <i class="fas fa-table text-primary mr-2"></i> Tabel Penilaian Siswa ({{ $nilaiSiswas->count() }} Siswa)
            </h5>
          </div>

          <div class="d-flex align-items-center flex-wrap">
            <button type="button" class="btn btn-outline-info btn-sm px-3 mr-2 mb-1 mb-md-0 shadow-sm" style="border-radius: 6px; font-weight: 600;" onclick="confirmSyncSiswa({{ $missingSiswas->count() }})" title="Sinkronkan seluruh siswa kelas {{ $penilaian->kelas->nama_kelas ?? '' }} ke sesi ini">
              <i class="fas fa-sync-alt mr-1"></i> Sinkronkan Siswa Kelas
              @if($missingSiswas->count() > 0)
                <span class="badge badge-warning ml-1">{{ $missingSiswas->count() }}</span>
              @endif
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm px-3 mr-2 mb-1 mb-md-0 shadow-sm" style="border-radius: 6px; font-weight: 600;" data-toggle="modal" data-target="#modalTambahSiswa">
              <i class="fas fa-user-plus mr-1"></i> Tambah Siswa
            </button>
            <a href="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}" class="btn btn-outline-secondary btn-sm px-3 mr-2 mb-1 mb-md-0" style="border-radius: 6px;">
              <i class="fas fa-eye mr-1"></i> Ringkasan
            </a>
            <button type="submit" name="action" value="draft" class="btn btn-outline-primary btn-sm px-3 mr-2 mb-1 mb-md-0 shadow-sm" style="border-radius: 6px; font-weight: 600;">
              <i class="fas fa-save mr-1"></i> Simpan Draft
            </button>
            <button type="submit" name="action" value="finalkan" class="btn btn-success btn-sm px-3 mb-1 mb-md-0 shadow-sm" style="border-radius: 6px; font-weight: 600;" onclick="return confirm('Finalkan nilai sekarang? Nilai akan otomatis mengalir ke Portofolio Capaian Siswa dan tampil ke siswa.')">
              <i class="fas fa-check-double mr-1"></i> Finalkan & Rilis Nilai
            </button>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" id="matrixTable" style="font-size: 0.88rem;">
              <thead class="bg-light text-center">
                <tr>
                  <th style="width: 45px; vertical-align: middle;" rowspan="2">No</th>
                  <th style="width: 110px; vertical-align: middle;" rowspan="2">NIS</th>
                  <th style="min-width: 180px; vertical-align: middle; text-align: left;" rowspan="2">Nama Siswa</th>
                  
                  <!-- Kolom Rubrik Indikator -->
                  @if($rubriks->count() > 0)
                    <th colspan="{{ $rubriks->count() }}" style="background-color: #f1f5f9;">
                      Aspek Penilaian Praktikum
                    </th>
                  @endif

                  <th style="width: 90px; vertical-align: middle; background-color: #e2e8f0;" rowspan="2">Nilai Awal</th>
                  <th style="width: 120px; vertical-align: middle;" rowspan="2">Status</th>
                  <th style="width: 100px; vertical-align: middle;" rowspan="2">Remedial</th>
                  <th style="width: 90px; vertical-align: middle; background-color: #e2e8f0;" rowspan="2">Nilai Akhir</th>
                  <th style="min-width: 160px; vertical-align: middle;" rowspan="2">Catatan Guru</th>
                  <th style="width: 50px; vertical-align: middle;" rowspan="2">Aksi</th>
                </tr>
                @if($rubriks->count() > 0)
                  <tr style="background-color: #f8fafc;">
                    @foreach($rubriks as $r)
                      <th style="min-width: 110px; font-size: 0.8rem; font-weight: 600;">
                        {{ $r->nama_indikator }}
                        <span class="d-block text-primary small">({{ floatval($r->bobot) }}%)</span>
                      </th>
                    @endforeach
                  </tr>
                @endif
              </thead>
              <tbody>
                @foreach($nilaiSiswas as $idx => $ns)
                  @php
                    $siswaId = $ns->id_siswa;
                    $indikatorVals = $ns->nilai_per_indikator ?? [];
                    $kkm = floatval($penilaian->kkm_saat_penilaian);
                    $isKompeten = ($ns->nilai_akhir >= $kkm);
                  @endphp
                  <tr id="row-{{ $siswaId }}" class="student-row" data-siswa-id="{{ $siswaId }}">
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $ns->siswa->nis ?? $ns->siswa->nisn ?? '-' }}</td>
                    <td class="font-weight-600 text-dark">
                      {{ $ns->siswa->nama_siswa ?? 'Siswa' }}
                    </td>

                    <!-- Input Nilai Per Indikator -->
                    @foreach($rubriks as $r)
                      @php
                        $valIndikator = $indikatorVals[$r->id_rubrik] ?? ($rubriks->count() === 1 ? $ns->nilai_awal : '');
                      @endphp
                      <td class="p-1">
                        <input type="number" 
                          name="scores[{{ $siswaId }}][indikator][{{ $r->id_rubrik }}]" 
                          class="form-control form-control-sm text-center rubrik-input font-weight-600" 
                          data-bobot="{{ $r->bobot }}" 
                          value="{{ $valIndikator }}" 
                          min="0" max="100" step="0.5" 
                          placeholder="0-100" 
                          style="border-radius: 4px;">
                      </td>
                    @endforeach

                    <!-- Nilai Awal (Terkalkulasi) -->
                    <td class="text-center p-1 font-weight-bold text-dark bg-light" style="vertical-align: middle;">
                      <span class="nilai-awal-text">{{ floatval($ns->nilai_awal) }}</span>
                      <input type="hidden" name="scores[{{ $siswaId }}][nilai_awal]" class="nilai-awal-input" value="{{ $ns->nilai_awal }}">
                    </td>

                    <!-- Status Kompetensi Badge -->
                    <td class="text-center status-col" style="vertical-align: middle;">
                      <span class="badge {{ $isKompeten ? 'badge-success' : 'badge-danger' }} px-2 py-1 font-weight-bold status-badge">
                        {{ $isKompeten ? 'Kompeten' : 'Belum Kompeten' }}
                      </span>
                    </td>

                    <!-- Input Remedial (jika < KKM) -->
                    <td class="p-1 text-center" style="vertical-align: middle;">
                      <input type="number" 
                        name="scores[{{ $siswaId }}][nilai_remedial]" 
                        class="form-control form-control-sm text-center remedial-input font-weight-600 text-primary" 
                        value="{{ $ns->nilai_remedial !== null ? floatval($ns->nilai_remedial) : '' }}" 
                        min="0" max="100" step="0.5" 
                        placeholder="Nilai Rem." 
                        style="border-radius: 4px; {{ $isKompeten && $ns->nilai_remedial === null ? 'display: none;' : '' }}">
                      @if($isKompeten && $ns->nilai_remedial === null)
                        <span class="text-muted small not-needed-text">-</span>
                      @endif
                    </td>

                    <!-- Nilai Akhir -->
                    <td class="text-center p-1 font-weight-bold bg-light" style="vertical-align: middle;">
                      <span class="nilai-akhir-text {{ $isKompeten ? 'text-success' : 'text-danger' }}">
                        {{ floatval($ns->nilai_akhir) }}
                      </span>
                    </td>

                    <!-- Catatan Guru -->
                    <td class="p-1">
                      <input type="text" 
                        name="scores[{{ $siswaId }}][catatan]" 
                        class="form-control form-control-sm" 
                        value="{{ $ns->catatan_guru }}" 
                        placeholder="Catatan..." 
                        style="border-radius: 4px;">
                    </td>

                    <!-- Aksi Hapus Siswa -->
                    <td class="text-center p-1" style="vertical-align: middle;">
                      <button type="button" class="btn btn-xs btn-outline-danger" title="Keluarkan siswa dari penilaian ini" onclick="hapusSiswa('{{ $penilaian->id_penilaian_praktik }}', '{{ $siswaId }}', '{{ addslashes($ns->siswa->nama_siswa ?? 'Siswa') }}')">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer bg-light py-3 px-4 d-flex align-items-center justify-content-between">
          <div class="text-muted small">
            <i class="fas fa-info-circle mr-1 text-primary"></i> 
            Kalkulasi nilai otomatis berdasarkan bobot rubrik. Status <strong>Kompeten</strong> diberikan otomatis bila Nilai Akhir &ge; {{ floatval($penilaian->kkm_saat_penilaian) }}.
          </div>
          <div>
            <button type="submit" name="action" value="draft" class="btn btn-outline-primary btn-sm px-3 mr-2" style="border-radius: 6px; font-weight: 600;">
              <i class="fas fa-save mr-1"></i> Simpan Draft
            </button>
            <button type="submit" name="action" value="finalkan" class="btn btn-success btn-sm px-4 shadow-sm" style="border-radius: 6px; font-weight: 600;" onclick="return confirm('Finalkan nilai sekarang? Nilai akan resmi tercatat ke Portofolio Siswa.')">
              <i class="fas fa-check-double mr-1"></i> Finalkan & Rilis
            </button>
          </div>
        </div>
      </div>
    </form>

    <!-- Form Hidden untuk Sinkronisasi Siswa Kelas -->
    <form id="formSyncSiswa" action="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/sync-siswa" method="POST" style="display: none;">
      @csrf
    </form>

    <!-- Form Hidden untuk Hapus Satu Siswa -->
    <form id="formDeleteSiswa" method="POST" style="display: none;">
      @csrf
      @method('DELETE')
    </form>

    <!-- Modal Tambah Siswa Susulan / Pindahan -->
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
          <form action="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/add-siswa" method="POST">
            @csrf
            <div class="modal-header bg-light py-3 border-bottom">
              <h5 class="modal-title font-weight-bold text-dark" id="modalTambahSiswaTitle" style="font-size: 1.05rem;">
                <i class="fas fa-user-plus text-primary mr-2"></i> Tambah Siswa ke Penilaian
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body p-4">
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">Pilih Siswa yang Akan Ditambahkan:</label>
                <select name="id_siswa" class="form-control" required style="border-radius: 6px;">
                  <option value="">-- Pilih Siswa --</option>
                  @if($missingSiswas->isNotEmpty())
                    <optgroup label="Siswa Kelas {{ $penilaian->kelas->nama_kelas ?? '-' }} (Belum Masuk)">
                      @foreach($missingSiswas as $ms)
                        <option value="{{ $ms->id_siswa }}">
                          {{ $ms->nama_siswa }} (NIS: {{ $ms->nis ?? '-' }})
                        </option>
                      @endforeach
                    </optgroup>
                  @endif
                  @php
                    $otherSiswas = $availableSiswas->where('id_kelas', '!=', $penilaian->id_kelas);
                  @endphp
                  @if($otherSiswas->isNotEmpty())
                    <optgroup label="Siswa Kelas Lain (Susulan / Pindahan)">
                      @foreach($otherSiswas as $os)
                        <option value="{{ $os->id_siswa }}">
                          {{ $os->nama_siswa }} (Kelas: {{ $os->kelas->nama_kelas ?? '-' }})
                        </option>
                      @endforeach
                    </optgroup>
                  @endif
                </select>
                <small class="text-muted d-block mt-2">
                  Pilih siswa kelas {{ $penilaian->kelas->nama_kelas ?? '-' }} yang belum terdaftar, atau siswa kelas lain yang mengikuti praktik susulan.
                </small>
              </div>
            </div>
            <div class="modal-footer bg-light py-2 px-3 border-top">
              <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
              <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold shadow-sm" style="border-radius: 6px;">
                <i class="fas fa-check mr-1"></i> Tambahkan ke Penilaian
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  function confirmSyncSiswa(count) {
    const msg = count > 0 
      ? 'Masukkan ' + count + ' siswa kelas yang belum terdaftar ke dalam sesi penilaian ini?' 
      : 'Sinkronkan daftar siswa kelas ke dalam sesi penilaian ini?';
    if (confirm(msg)) {
      document.getElementById('formSyncSiswa').submit();
    }
  }

  function hapusSiswa(idPenilaian, idSiswa, namaSiswa) {
    if (confirm('Keluarkan ' + namaSiswa + ' dari sesi penilaian ini? Data nilai siswa pada sesi ini akan dihapus.')) {
      const form = document.getElementById('formDeleteSiswa');
      form.action = '/admin/kejuruan/penilaian-praktik/' + idPenilaian + '/siswa/' + idSiswa;
      form.submit();
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const kkm = parseFloat("{{ $penilaian->kkm_saat_penilaian }}");
    const matrixTable = document.getElementById('matrixTable');

    function calculateRow(row) {
      const rubrikInputs = row.querySelectorAll('.rubrik-input');
      let totalWeighted = 0;
      let totalWeight = 0;
      let anyFilled = false;

      rubrikInputs.forEach(input => {
        const val = parseFloat(input.value);
        const bobot = parseFloat(input.getAttribute('data-bobot')) || 100;
        if (!isNaN(val)) {
          totalWeighted += (val * bobot);
          totalWeight += bobot;
          anyFilled = true;
        }
      });

      let nilaiAwal = 0;
      if (anyFilled && totalWeight > 0) {
        nilaiAwal = Math.round((totalWeighted / totalWeight) * 100) / 100;
      }

      // Update text & hidden input nilai awal
      const awalText = row.querySelector('.nilai-awal-text');
      const awalInput = row.querySelector('.nilai-awal-input');
      if (awalText) awalText.textContent = nilaiAwal;
      if (awalInput) awalInput.value = nilaiAwal;

      // Cek remedial
      const remedialInput = row.querySelector('.remedial-input');
      const notNeededText = row.querySelector('.not-needed-text');
      let nilaiAkhir = nilaiAwal;

      if (remedialInput && remedialInput.value.trim() !== '' && !isNaN(parseFloat(remedialInput.value))) {
        nilaiAkhir = parseFloat(remedialInput.value);
      }

      // Update status badge
      const isKompeten = (nilaiAkhir >= kkm);
      const statusBadge = row.querySelector('.status-badge');
      if (statusBadge) {
        statusBadge.textContent = isKompeten ? 'Kompeten' : 'Belum Kompeten';
        statusBadge.className = 'badge ' + (isKompeten ? 'badge-success' : 'badge-danger') + ' px-2 py-1 font-weight-bold status-badge';
      }

      // Show/hide remedial field
      if (remedialInput) {
        if (!isKompeten || remedialInput.value.trim() !== '') {
          remedialInput.style.display = 'inline-block';
          if (notNeededText) notNeededText.style.display = 'none';
        } else {
          remedialInput.style.display = 'none';
          if (notNeededText) notNeededText.style.display = 'inline';
        }
      }

      // Update nilai akhir text
      const akhirText = row.querySelector('.nilai-akhir-text');
      if (akhirText) {
        akhirText.textContent = nilaiAkhir;
        akhirText.className = 'nilai-akhir-text ' + (isKompeten ? 'text-success' : 'text-danger');
      }
    }

    // Event listener for inputs
    matrixTable.addEventListener('input', function (e) {
      if (e.target.classList.contains('rubrik-input') || e.target.classList.contains('remedial-input')) {
        const row = e.target.closest('.student-row');
        if (row) {
          calculateRow(row);
        }
      }
    });
  });
</script>
@endsection
