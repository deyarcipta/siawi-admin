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
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Pengisian Jurnal Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Catat kegiatan pembelajaran dan progres jobsheet laboratorium siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jurnal-praktik" class="text-primary font-weight-500">Jurnal Praktik</a></li>
          <li class="breadcrumb-item active">Isi Jurnal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-1"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-clipboard-check text-primary mr-2"></i> Formulir Jurnal Pertemuan
            </h5>
          </div>

          <form action="/admin/kejuruan/jurnal-praktik" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body p-4 pt-2">
              <div class="row">
                <div class="col-md-8 form-group mb-3">
                  <label for="id_jadwal_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Sesi Jadwal Praktik <span class="text-danger">*</span></label>
                  <select name="id_jadwal_praktik" id="id_jadwal_praktik" class="form-control select2bs4 @error('id_jadwal_praktik') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Jadwal Praktik</option>
                    @foreach($jadwalList as $jl)
                      <option value="{{ $jl->id_jadwal_praktik }}" {{ (old('id_jadwal_praktik', $jadwal->id_jadwal_praktik ?? null) == $jl->id_jadwal_praktik) ? 'selected' : '' }}>
                        {{ $jl->hari }} ({{ substr($jl->jam_mulai, 0, 5) }}-{{ substr($jl->jam_selesai, 0, 5) }}) - {{ $jl->kelas->nama_kelas ?? '-' }} - {{ $jl->mapel->nama_mapel ?? '-' }} [{{ $jl->lab->nama_lab ?? '-' }}]
                      </option>
                    @endforeach
                  </select>
                  @error('id_jadwal_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="tanggal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tanggal Praktik <span class="text-danger">*</span></label>
                  <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $tanggal) }}" required style="border-radius: 8px; height: 42px;">
                  @error('tanggal')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="modul_praktik_id" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pilih Modul Praktik (Bank Modul) <span class="badge badge-light border text-primary ml-1">Opsional</span>
                </label>
                <select name="modul_praktik_id" id="modul_praktik_id" class="form-control select2bs4 @error('modul_praktik_id') is-invalid @enderror" style="border-radius: 8px; height: 42px;">
                  <option value="" data-judul="" data-alat="">-- Bebas / Tanpa Modul Khusus --</option>
                  @foreach($modulList as $m)
                    <option value="{{ $m->id_modul_praktik }}" 
                      data-judul="{{ $m->judul }}" 
                      data-alat="{{ $m->alat_bahan }}"
                      {{ old('modul_praktik_id') == $m->id_modul_praktik ? 'selected' : '' }}>
                      [{{ $m->kode_modul }}] {{ $m->judul }} (v{{ $m->versi }}) - {{ $m->kompetensi->nama_kompetensi ?? '' }}
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Memilih modul akan otomatis menyarankan materi dan daftar alat/bahan di bawah.</small>
                @error('modul_praktik_id')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="materi" class="font-weight-bold text-dark d-flex align-items-center justify-content-between" style="font-size: 0.85rem;">
                  <span>Materi Praktik / Topik Jobsheet <span class="text-danger">*</span></span>
                  <small class="text-muted font-weight-normal"><i class="fas fa-magic text-primary mr-1"></i> Editor Dokumen</small>
                </label>
                <textarea class="form-control summernote-kejuruan @error('materi') is-invalid @enderror" id="materi" name="materi" rows="4" placeholder="Tuliskan materi praktik, kompetensi yang dipraktikkan, atau rangkuman jobsheet...">{{ old('materi') }}</textarea>
                @error('materi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="jumlah_hadir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Jumlah Siswa Hadir <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <input type="number" class="form-control @error('jumlah_hadir') is-invalid @enderror" id="jumlah_hadir" name="jumlah_hadir" value="{{ old('jumlah_hadir', $defaultHadir) }}" min="0" required style="border-radius: 8px 0 0 8px; height: 42px;">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light text-muted" style="border-radius: 0 8px 8px 0;">Siswa</span>
                    </div>
                  </div>
                  <small class="text-muted">Nilai otomatis terhitung dari daftar absensi siswa di bawah.</small>
                  @error('jumlah_hadir')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="foto_praktik" class="font-weight-bold text-dark d-flex align-items-center justify-content-between" style="font-size: 0.85rem;">
                    <span><i class="fas fa-camera text-primary mr-1"></i> Dokumentasi Foto Praktik</span>
                    <span class="badge badge-light border text-muted" style="font-size: 0.75rem;">Maks. 512 KB</span>
                  </label>
                  <div class="custom-file" style="height: 42px;">
                    <input type="file" class="custom-file-input @error('foto_praktik') is-invalid @enderror" id="foto_praktik" name="foto_praktik" accept="image/jpeg,image/png,image/webp" style="cursor: pointer; height: 42px;">
                    <label class="custom-file-label d-flex align-items-center text-truncate" for="foto_praktik" id="foto_praktik_label" style="height: 42px; border-radius: 8px; font-size: 0.85rem; color: #64748b; padding-right: 90px;">
                      Pilih file foto dokumentasi...
                    </label>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-1">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Format: JPG, PNG, WebP (Maks. 512 KB)</small>
                    <small id="foto_size_warning" class="text-danger font-weight-bold d-none"></small>
                  </div>
                  <div id="foto_preview_container" class="mt-2 d-none align-items-center p-2 rounded border bg-light" style="border-radius: 8px;">
                    <img id="foto_preview_img" src="#" alt="Preview" class="rounded mr-2 border shadow-sm" style="width: 44px; height: 44px; object-fit: cover;">
                    <div class="flex-grow-1 text-truncate">
                      <span id="foto_preview_name" class="font-weight-bold text-dark d-block text-truncate small"></span>
                      <small id="foto_preview_size" class="text-muted"></small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="btn_remove_foto" title="Hapus foto" style="border-radius: 6px; padding: 2px 8px;">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  @error('foto_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <!-- Absensi Praktik Siswa -->
              <div class="card border border-light shadow-sm mb-4" style="border-radius: 12px; overflow: hidden; background: #fafbfc;">
                <div class="card-header bg-white py-2 px-3 border-bottom">
                  <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center my-1 mr-2">
                      <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded mr-2" style="width: 28px; height: 28px; font-size: 0.85rem; flex-shrink: 0;">
                        <i class="fas fa-user-check"></i>
                      </div>
                      <div>
                        <strong class="text-dark d-block d-sm-inline" style="font-size: 0.95rem;">Absensi Praktik Siswa</strong>
                        <div class="d-inline-flex flex-wrap align-items-center mt-1 mt-sm-0" style="gap: 4px;">
                          <span class="badge badge-primary" id="badgeTotalSiswa">{{ count($siswaList ?? []) }} Siswa di Rombel</span>
                          <span class="badge badge-success" id="badgeRingkasanTerpilih">0 Terpilih (0 Hadir)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row no-gutters w-100 mt-2 align-items-center">
                    <div class="col-12 col-md-5 mb-2 mb-md-0 pr-md-2">
                      <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                          <span class="input-group-text bg-white text-muted border-right-0" style="border-radius: 6px 0 0 6px;">
                            <i class="fas fa-search" style="font-size: 0.75rem;"></i>
                          </span>
                        </div>
                        <input type="text" id="inputCariSiswa" class="form-control border-left-0" placeholder="Cari nama siswa..." style="border-radius: 0 6px 6px 0; font-size: 0.82rem; height: 36px;">
                      </div>
                    </div>
                    <div class="col-12 col-md-7">
                      <div class="btn-group btn-group-sm w-100 d-flex" role="group">
                        <button type="button" class="btn btn-outline-primary font-weight-bold flex-fill px-1 py-1 d-flex align-items-center justify-content-center" id="btnBukaModalRandom" title="Pilih sejumlah siswa secara acak" style="border-radius: 6px 0 0 6px; height: 36px; min-width: 0;">
                          <i class="fas fa-random mr-1"></i> <span>Acak</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary flex-fill px-1 py-1 d-flex align-items-center justify-content-center" id="btnPilihSemua" title="Centang semua siswa" style="height: 36px; min-width: 0;">
                          <span>Semua</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary flex-fill px-1 py-1 d-flex align-items-center justify-content-center" id="btnKosongkanPilihan" title="Kosongkan centang semua siswa" style="height: 36px; min-width: 0;">
                          <span>Kosong</span>
                        </button>
                        <button type="button" class="btn btn-outline-success font-weight-bold flex-fill px-1 py-1 d-flex align-items-center justify-content-center" id="btnSemuaHadir" title="Set semua siswa terpilih menjadi Hadir" style="border-radius: 0 6px 6px 0; height: 36px; min-width: 0;">
                          <i class="fas fa-check-double mr-1"></i> <span>Hadirkan</span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="d-md-none bg-light px-3 py-1 border-bottom text-muted small d-flex align-items-center justify-content-between">
                    <span style="font-size: 0.74rem;"><i class="fas fa-arrows-alt-h mr-1 text-primary"></i> Geser tabel untuk melihat catatan</span>
                    <i class="fas fa-chevron-right text-muted" style="font-size: 0.7rem;"></i>
                  </div>
                  <div class="table-responsive" style="max-height: 420px; -webkit-overflow-scrolling: touch;">
                    <table class="table table-hover table-sm mb-0" id="tablePresensiSiswa" style="font-size: 0.86rem; min-width: 580px;">
                      <thead class="bg-light text-muted">
                        <tr>
                          <th style="width: 48px; min-width: 48px;" class="text-center" title="Status Keikutsertaan Praktik">Ikut?</th>
                          <th style="width: 36px; min-width: 36px;" class="text-center">No</th>
                          <th style="min-width: 150px;">Nama Siswa</th>
                          <th style="width: 190px; min-width: 190px;" class="text-center">Kehadiran</th>
                          <th style="min-width: 150px;">Catatan (Opsional)</th>
                        </tr>
                      </thead>
                      <tbody id="tbodyPresensi">
                        @forelse($siswaList as $idx => $s)
                          @php
                            $isIkut = old('presensi.'.$s->id_siswa.'.terpilih', 1) ? true : false;
                            $statusVal = old('presensi.'.$s->id_siswa.'.status', 'hadir');
                          @endphp
                          <tr class="row-siswa {{ $isIkut ? 'is-selected' : 'is-unselected' }}" data-id="{{ $s->id_siswa }}" data-nama="{{ strtolower($s->nama_siswa) }}">
                            <td class="text-center align-middle">
                              <div class="custom-control custom-checkbox d-inline-block">
                                <input type="checkbox" class="custom-control-input check-ikut-praktik" id="ikut_{{ $s->id_siswa }}" name="presensi[{{ $s->id_siswa }}][terpilih]" value="1" {{ $isIkut ? 'checked' : '' }}>
                                <label class="custom-control-label" for="ikut_{{ $s->id_siswa }}" style="cursor: pointer;"></label>
                              </div>
                            </td>
                            <td class="text-center align-middle font-weight-500 row-number">{{ $idx + 1 }}</td>
                            <td class="align-middle col-nama">
                              <span class="font-weight-bold text-dark d-block nama-text">{{ $s->nama_siswa }}</span>
                              <small class="text-muted d-block">NISN: {{ $s->nisn ?? ($s->nis ?? '-') }}</small>
                            </td>
                            <td class="align-middle text-center col-status">
                              <div class="btn-group btn-group-toggle group-kehadiran" data-toggle="buttons">
                                <label class="btn btn-outline-success btn-sm btn-status {{ ($statusVal == 'hadir') ? 'active' : '' }}" style="font-size: 0.76rem; padding: 3px 8px;">
                                  <input type="radio" name="presensi[{{ $s->id_siswa }}][status]" value="hadir" {{ ($statusVal == 'hadir') ? 'checked' : '' }} class="radio-status" {{ !$isIkut ? 'disabled' : '' }}> H
                                </label>
                                <label class="btn btn-outline-warning btn-sm btn-status {{ ($statusVal == 'sakit') ? 'active' : '' }}" style="font-size: 0.76rem; padding: 3px 8px;">
                                  <input type="radio" name="presensi[{{ $s->id_siswa }}][status]" value="sakit" {{ ($statusVal == 'sakit') ? 'checked' : '' }} class="radio-status" {{ !$isIkut ? 'disabled' : '' }}> S
                                </label>
                                <label class="btn btn-outline-info btn-sm btn-status {{ ($statusVal == 'izin') ? 'active' : '' }}" style="font-size: 0.76rem; padding: 3px 8px;">
                                  <input type="radio" name="presensi[{{ $s->id_siswa }}][status]" value="izin" {{ ($statusVal == 'izin') ? 'checked' : '' }} class="radio-status" {{ !$isIkut ? 'disabled' : '' }}> I
                                </label>
                                <label class="btn btn-outline-danger btn-sm btn-status {{ ($statusVal == 'alpa') ? 'active' : '' }}" style="font-size: 0.76rem; padding: 3px 8px;">
                                  <input type="radio" name="presensi[{{ $s->id_siswa }}][status]" value="alpa" {{ ($statusVal == 'alpa') ? 'checked' : '' }} class="radio-status" {{ !$isIkut ? 'disabled' : '' }}> A
                                </label>
                              </div>
                            </td>
                            <td class="align-middle col-catatan">
                              <input type="text" name="presensi[{{ $s->id_siswa }}][catatan]" value="{{ old('presensi.'.$s->id_siswa.'.catatan') }}" class="form-control form-control-sm input-catatan" placeholder="Keterangan..." style="border-radius: 6px; font-size: 0.8rem;" {{ !$isIkut ? 'disabled' : '' }}>
                            </td>
                          </tr>
                        @empty
                          <tr id="rowEmpty">
                            <td colspan="5" class="text-center py-4 text-muted">
                              <i class="fas fa-info-circle mr-1"></i> Silakan pilih jadwal praktik di atas untuk memuat daftar siswa rombel.
                            </td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  <div class="bg-light px-3 py-2 border-top d-flex align-items-center justify-content-between small text-muted">
                    <span><i class="fas fa-info-circle mr-1"></i> Centang kotak untuk menentukan siswa yang ikut praktik pada sesi ini. Siswa tanpa centang tidak akan disimpan ke jurnal.</span>
                  </div>
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="alat_dipakai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat & Software Laboratorium yang Digunakan
                </label>
                <input type="text" class="form-control @error('alat_dipakai') is-invalid @enderror" id="alat_dipakai" name="alat_dipakai" placeholder="Contoh: 18 Unit PC Client, 9 Unit Mikrotik RB750, Winbox v3.37, Kabel UTP Cat6" value="{{ old('alat_dipakai') }}" style="border-radius: 8px; height: 42px;">
                @error('alat_dipakai')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="kendala" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Kendala Praktik (Jika Ada)
                  </label>
                  <textarea class="form-control @error('kendala') is-invalid @enderror" id="kendala" name="kendala" rows="2" placeholder="Contoh: 1 unit adaptor router rusak, koneksi LAN switch 2 longgar" style="border-radius: 8px;">{{ old('kendala') }}</textarea>
                  @error('kendala')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="catatan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Catatan Tambahan / Tugas Lanjutan
                  </label>
                  <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2" placeholder="Contoh: Jobsheet nomor 4 dilanjutkan pada pertemuan minggu depan" style="border-radius: 8px;">{{ old('catatan') }}</textarea>
                  @error('catatan')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/jurnal-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Jurnal Praktik
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background-color: #f8fafc;">
          <div class="card-body p-3">
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-info-circle text-primary mr-1"></i> Ketentuan Jurnal
            </h6>
            <ul class="text-muted small pl-3 mb-0" style="line-height: 1.6;">
              <li>Guru mengisi satu jurnal untuk setiap pertemuan praktik yang terlaksana.</li>
              <li>Data absensi diambil otomatis dari sistem presensi kelas harian.</li>
              <li>Jurnal yang disimpan akan berstatus <strong>Menunggu Persetujuan</strong> dari Kepala Program Keahlian (Kaprog).</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Pilih Siswa Acak (Random Picker) -->
<div class="modal fade" id="modalRandomSiswa" tabindex="-1" role="dialog" aria-labelledby="modalRandomSiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
      <div class="modal-header py-3 px-3 bg-light border-bottom">
        <h6 class="modal-title font-weight-bold text-dark" id="modalRandomSiswaLabel" style="font-size: 0.95rem;">
          <i class="fas fa-random text-primary mr-1"></i> Pilih Siswa Acak (Random)
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3">
        <p class="small text-muted mb-2">
          Sistem akan mengacak siswa di rombel ini dan mencentang sejumlah siswa yang Anda tentukan secara otomatis.
        </p>
        <div class="form-group mb-2">
          <label class="font-weight-bold text-dark small mb-1">Jumlah Siswa Praktik</label>
          <div class="input-group">
            <input type="number" id="inputJumlahRandom" class="form-control" min="1" max="50" value="10" style="border-radius: 8px 0 0 8px; height: 38px; font-weight: bold;">
            <div class="input-group-append">
              <span class="input-group-text bg-light text-muted small" style="border-radius: 0 8px 8px 0;">Siswa</span>
            </div>
          </div>
          <small class="text-muted d-block mt-1">Total di rombel: <strong id="lblTotalRombelModal">0</strong> siswa</small>
        </div>
        <div class="mb-2">
          <label class="font-weight-bold text-dark small mb-1 d-block">Pilihan Cepat:</label>
          <div class="btn-group btn-group-sm w-100">
            <button type="button" class="btn btn-outline-secondary btn-preset-random" data-pct="50">50% Rombel</button>
            <button type="button" class="btn btn-outline-secondary btn-preset-random" data-count="10">10 Siswa</button>
            <button type="button" class="btn btn-outline-secondary btn-preset-random" data-count="12">12 Siswa</button>
          </div>
        </div>
      </div>
      <div class="modal-footer py-2 px-3 bg-light border-top">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
        <button type="button" class="btn btn-primary btn-sm px-3 font-weight-bold" id="btnEksekusiRandom" style="border-radius: 6px;">
          <i class="fas fa-check mr-1"></i> Acak & Pilih
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  .note-editor.note-frame {
    border: 1px solid #cbd5e1 !important;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
  }
  .note-editor.note-frame .note-toolbar {
    background-color: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 6px 8px !important;
  }
  .note-editor.note-frame .note-btn {
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #334155 !important;
    border-radius: 6px !important;
    padding: 3px 8px !important;
    font-size: 0.82rem !important;
  }
  .note-editor.note-frame .note-btn:hover {
    background: #e2e8f0 !important;
    color: #0f172a !important;
  }
  .note-editor.note-frame .note-btn.active {
    background: #0284c7 !important;
    color: #ffffff !important;
    border-color: #0284c7 !important;
  }
  .note-editor.note-frame .note-statusbar {
    background-color: #f8fafc !important;
    border-top: 1px solid #e2e8f0 !important;
  }
  .row-siswa.is-unselected {
    opacity: 0.45;
    background-color: #f8fafc;
    transition: opacity 0.2s ease, background-color 0.2s ease;
  }
  .row-siswa.is-selected {
    opacity: 1;
    background-color: #ffffff;
    transition: opacity 0.2s ease, background-color 0.2s ease;
  }
  .row-siswa:hover {
    background-color: #f1f5f9 !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Summernote dengan toolbar mirip CBT / Dokumen Pembelajaran
    if (window.jQuery && $.fn.summernote) {
      $('#materi').summernote({
        placeholder: 'Tuliskan materi praktik, topik jobsheet, atau poin-poin kegiatan praktikum di sini...',
        tabsize: 2,
        height: 180,
        tooltip: false,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['insert', ['picture', 'video', 'link']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['misc', ['codeview', 'clear']]
        ]
      });
    }

    const modulSelect = document.getElementById('modul_praktik_id');
    const alatInput = document.getElementById('alat_dipakai');

    if (modulSelect) {
      modulSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const judul = selected.getAttribute('data-judul');
        const alat = selected.getAttribute('data-alat');

        if (judul) {
          if (window.jQuery && $.fn.summernote) {
            $('#materi').summernote('code', judul);
          } else {
            const materiInput = document.getElementById('materi');
            if (materiInput) materiInput.value = judul;
          }
        }
        if (alat && alatInput && !alatInput.value.trim()) {
          alatInput.value = alat;
        }
      });
    }

    // Helper: update tampilan aktif / nonaktif baris siswa
    function applyRowState($row, isChecked) {
      if (isChecked) {
        $row.removeClass('is-unselected').addClass('is-selected');
        $row.find('.group-kehadiran input').prop('disabled', false);
        $row.find('.group-kehadiran label').css('pointer-events', 'auto');
        $row.find('.input-catatan').prop('disabled', false);
      } else {
        $row.removeClass('is-selected').addClass('is-unselected');
        $row.find('.group-kehadiran input').prop('disabled', true);
        $row.find('.group-kehadiran label').css('pointer-events', 'none');
        $row.find('.input-catatan').prop('disabled', true);
      }
    }

    // Helper: hitung dan perbarui ringkasan kehadiran & jumlah_hadir
    function updateAbsensiCounter() {
      const $rows = $('#tbodyPresensi tr.row-siswa');
      const totalSiswa = $rows.length;
      let terpilihCount = 0;
      let hadirCount = 0;

      $rows.each(function () {
        const isChecked = $(this).find('.check-ikut-praktik').is(':checked');
        if (isChecked) {
          terpilihCount++;
          const val = $(this).find('input.radio-status:checked').val();
          if (val === 'hadir') hadirCount++;
        }
      });

      $('#jumlah_hadir').val(hadirCount);
      $('#badgeTotalSiswa').text(totalSiswa + ' Siswa di Rombel');
      $('#lblTotalRombelModal').text(totalSiswa);

      if (terpilihCount === 0) {
        $('#badgeRingkasanTerpilih')
          .removeClass('badge-success badge-info')
          .addClass('badge-warning text-dark')
          .text('0 Terpilih (Pilih siswa praktik)');
      } else {
        $('#badgeRingkasanTerpilih')
          .removeClass('badge-warning text-dark')
          .addClass('badge-success')
          .text(terpilihCount + ' Terpilih (' + hadirCount + ' Hadir)');
      }
    }

    // Listener checkbox ikut praktik
    $(document).on('change', '.check-ikut-praktik', function () {
      const isChecked = $(this).is(':checked');
      const $row = $(this).closest('tr.row-siswa');
      applyRowState($row, isChecked);
      updateAbsensiCounter();
    });

    // Listener radio kehadiran
    $(document).on('change', '#tbodyPresensi input.radio-status', function () {
      updateAbsensiCounter();
    });

    // Tombol Pilih Semua Siswa
    $('#btnPilihSemua').on('click', function () {
      $('#tbodyPresensi tr.row-siswa').each(function () {
        $(this).find('.check-ikut-praktik').prop('checked', true);
        applyRowState($(this), true);
      });
      updateAbsensiCounter();
    });

    // Tombol Kosongkan Pilihan
    $('#btnKosongkanPilihan').on('click', function () {
      $('#tbodyPresensi tr.row-siswa').each(function () {
        $(this).find('.check-ikut-praktik').prop('checked', false);
        applyRowState($(this), false);
      });
      updateAbsensiCounter();
    });

    // Tombol Hadirkan (hanya siswa yang dicentang ikut praktik)
    $('#btnSemuaHadir').on('click', function () {
      $('#tbodyPresensi tr.row-siswa').each(function () {
        if ($(this).find('.check-ikut-praktik').is(':checked')) {
          const hLabel = $(this).find('input[value="hadir"]').closest('label');
          $(this).find('.btn-group label').removeClass('active');
          $(this).find('input.radio-status').prop('checked', false);
          hLabel.addClass('active');
          hLabel.find('input').prop('checked', true);
        }
      });
      updateAbsensiCounter();
    });

    // Live search / filter nama siswa
    $('#inputCariSiswa').on('input', function () {
      const q = $(this).val().toLowerCase().trim();
      $('#tbodyPresensi tr.row-siswa').each(function () {
        const nama = $(this).data('nama') || '';
        if (!q || nama.indexOf(q) !== -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    // Modal Random Picker
    $('#btnBukaModalRandom').on('click', function () {
      const total = $('#tbodyPresensi tr.row-siswa').length;
      if (total === 0) {
        alert('Daftar siswa belum dimuat. Silakan pilih sesi jadwal praktik terlebih dahulu.');
        return;
      }
      $('#lblTotalRombelModal').text(total);
      $('#modalRandomSiswa').modal('show');
    });

    // Preset tombol di modal acak
    $('.btn-preset-random').on('click', function () {
      const total = $('#tbodyPresensi tr.row-siswa').length;
      const pct = $(this).data('pct');
      const count = $(this).data('count');
      if (pct) {
        const val = Math.max(1, Math.round((total * pct) / 100));
        $('#inputJumlahRandom').val(val);
      } else if (count) {
        $('#inputJumlahRandom').val(Math.min(count, total));
      }
    });

    // Eksekusi Acak (Fisher-Yates Shuffle)
    $('#btnEksekusiRandom').on('click', function () {
      const $allRows = $('#tbodyPresensi tr.row-siswa');
      const total = $allRows.length;

      if (total === 0) {
        alert('Daftar siswa belum dimuat.');
        return;
      }

      const targetCount = parseInt($('#inputJumlahRandom').val(), 10);
      if (isNaN(targetCount) || targetCount < 1) {
        alert('Masukkan jumlah siswa yang valid (minimal 1).');
        return;
      }

      const countToPick = Math.min(targetCount, total);

      // Uncheck semua baris terlebih dahulu
      $allRows.each(function () {
        $(this).find('.check-ikut-praktik').prop('checked', false);
        applyRowState($(this), false);
      });

      // Buat array indeks lalu acak dengan algoritma Fisher-Yates
      const indices = Array.from({ length: total }, (_, i) => i);
      for (let i = indices.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [indices[i], indices[j]] = [indices[j], indices[i]];
      }

      // Pilih sejumlah N siswa pertama hasil acakan
      for (let k = 0; k < countToPick; k++) {
        const row = $allRows.eq(indices[k]);
        row.find('.check-ikut-praktik').prop('checked', true);
        applyRowState(row, true);

        // Otomatis tandai Hadir
        const hLabel = row.find('input[value="hadir"]').closest('label');
        row.find('.btn-group label').removeClass('active');
        row.find('input.radio-status').prop('checked', false);
        hLabel.addClass('active');
        hLabel.find('input').prop('checked', true);
      }

      updateAbsensiCounter();
      $('#modalRandomSiswa').modal('hide');
    });

    // Pemuatan dinamis daftar siswa ketika jadwal dipilih
    $('#id_jadwal_praktik').on('change', function () {
      const idJadwal = $(this).val();
      if (!idJadwal) {
        $('#tbodyPresensi').html('<tr id="rowEmpty"><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-info-circle mr-1"></i> Silakan pilih jadwal praktik di atas untuk memuat daftar siswa rombel.</td></tr>');
        $('#badgeTotalSiswa').text('0 Siswa di Rombel');
        $('#badgeRingkasanTerpilih').removeClass('badge-success').addClass('badge-warning text-dark').text('0 Terpilih (0 Hadir)');
        $('#jumlah_hadir').val(0);
        return;
      }

      $('#tbodyPresensi').html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat daftar siswa rombel...</td></tr>');

      $.getJSON('/admin/kejuruan/jurnal-praktik/siswa-by-jadwal/' + idJadwal, function (res) {
        if (res.success && res.siswas && res.siswas.length > 0) {
          let rowsHtml = '';
          res.siswas.forEach(function (s, idx) {
            const nis = s.nisn || s.nis || '-';
            const namaLower = (s.nama_siswa || '').toLowerCase();
            rowsHtml += `
              <tr class="row-siswa is-selected" data-id="${s.id_siswa}" data-nama="${namaLower}">
                <td class="text-center align-middle">
                  <div class="custom-control custom-checkbox d-inline-block">
                    <input type="checkbox" class="custom-control-input check-ikut-praktik" id="ikut_${s.id_siswa}" name="presensi[${s.id_siswa}][terpilih]" value="1" checked>
                    <label class="custom-control-label" for="ikut_${s.id_siswa}" style="cursor: pointer;"></label>
                  </div>
                </td>
                <td class="text-center align-middle font-weight-500 row-number">${idx + 1}</td>
                <td class="align-middle col-nama">
                  <span class="font-weight-bold text-dark d-block nama-text">${s.nama_siswa}</span>
                  <small class="text-muted d-block">NISN: ${nis}</small>
                </td>
                <td class="align-middle text-center col-status">
                  <div class="btn-group btn-group-toggle group-kehadiran" data-toggle="buttons">
                    <label class="btn btn-outline-success btn-sm btn-status active" style="font-size: 0.76rem; padding: 3px 8px;">
                      <input type="radio" name="presensi[${s.id_siswa}][status]" value="hadir" checked class="radio-status"> H
                    </label>
                    <label class="btn btn-outline-warning btn-sm btn-status" style="font-size: 0.76rem; padding: 3px 8px;">
                      <input type="radio" name="presensi[${s.id_siswa}][status]" value="sakit" class="radio-status"> S
                    </label>
                    <label class="btn btn-outline-info btn-sm btn-status" style="font-size: 0.76rem; padding: 3px 8px;">
                      <input type="radio" name="presensi[${s.id_siswa}][status]" value="izin" class="radio-status"> I
                    </label>
                    <label class="btn btn-outline-danger btn-sm btn-status" style="font-size: 0.76rem; padding: 3px 8px;">
                      <input type="radio" name="presensi[${s.id_siswa}][status]" value="alpa" class="radio-status"> A
                    </label>
                  </div>
                </td>
                <td class="align-middle col-catatan">
                  <input type="text" name="presensi[${s.id_siswa}][catatan]" class="form-control form-control-sm input-catatan" placeholder="Keterangan..." style="border-radius: 6px; font-size: 0.8rem;">
                </td>
              </tr>
            `;
          });
          $('#tbodyPresensi').html(rowsHtml);
          $('#badgeTotalSiswa').text(res.siswas.length + ' Siswa di Rombel');
          $('#lblTotalRombelModal').text(res.siswas.length);
          updateAbsensiCounter();
        } else {
          $('#tbodyPresensi').html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle mr-1"></i> Tidak ada data siswa untuk kelas pada jadwal ini.</td></tr>');
          $('#badgeTotalSiswa').text('0 Siswa di Rombel');
          $('#lblTotalRombelModal').text(0);
          updateAbsensiCounter();
        }
      }).fail(function () {
        $('#tbodyPresensi').html('<tr><td colspan="5" class="text-center py-4 text-danger"><i class="fas fa-times mr-1"></i> Gagal memuat data siswa.</td></tr>');
      });
    });

    // Initial counter update on load
    updateAbsensiCounter();

    // Interaktivitas Foto Dokumentasi Praktik & Validasi 512 KB
    $('#foto_praktik').on('change', function(e) {
      const file = this.files && this.files[0];
      const maxBytes = 512 * 1024; // 512 KB
      const $label = $('#foto_praktik_label');
      const $warning = $('#foto_size_warning');
      const $previewBox = $('#foto_preview_container');
      const $previewImg = $('#foto_preview_img');
      const $previewName = $('#foto_preview_name');
      const $previewSize = $('#foto_preview_size');

      if (!file) {
        $label.text('Pilih file foto dokumentasi...');
        $previewBox.addClass('d-none').removeClass('d-flex');
        $warning.addClass('d-none');
        return;
      }

      const sizeKb = (file.size / 1024).toFixed(1);

      if (file.size > maxBytes) {
        $warning.text('Ukuran file (' + sizeKb + ' KB) melebihi batas 512 KB!').removeClass('d-none');
        alert('Ukuran foto terlalu besar (' + sizeKb + ' KB). Batas maksimal foto dokumentasi adalah 512 KB. Silakan kompres foto terlebih dahulu.');
        $(this).val('');
        $label.text('Pilih file foto dokumentasi...');
        $previewBox.addClass('d-none').removeClass('d-flex');
        return;
      }

      $warning.addClass('d-none');
      $label.text(file.name);
      $previewName.text(file.name);
      $previewSize.text(sizeKb + ' KB');

      const reader = new FileReader();
      reader.onload = function(ev) {
        $previewImg.attr('src', ev.target.result);
        $previewBox.removeClass('d-none').addClass('d-flex');
      };
      reader.readAsDataURL(file);
    });

    $('#btn_remove_foto').on('click', function() {
      $('#foto_praktik').val('');
      $('#foto_praktik_label').text('Pilih file foto dokumentasi...');
      $('#foto_preview_container').addClass('d-none').removeClass('d-flex');
      $('#foto_size_warning').addClass('d-none');
    });
  });
</script>
@endsection
