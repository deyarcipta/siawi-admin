@extends($layout)
@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-exclamation-circle text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Rekap Kelalaian Input Absensi</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Monitoring kelas dan petugas piket yang belum menuntaskan absensi harian</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Absensi Siswa</a></li>
            <li class="breadcrumb-item active">Rekap Kelalaian</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          
          <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
              <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Rekap Kelalaian Input Absensi
              </h3>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.rekapBelumAbsen.index') }}" method="GET" class="row align-items-end">
                <div class="form-group col-md-4 mb-0">
                  <label for="tanggal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Tanggal</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $date }}">
                </div>
                <div class="form-group col-md-2 mb-0">
                  <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button>
                </div>
              </form>
            </div>
          </div>

          <div class="card mt-3">
            <div class="card-header d-flex align-items-center flex-wrap" style="gap: 8px;">
              <h3 class="card-title text-dark font-weight-bold mb-0 mr-auto">
                <i class="fas fa-table text-primary mr-2"></i> Rekap Kelalaian Input - {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }} ({{ $dayInd ?? '' }})
              </h3>
              @if($criteriaMet && count($kelasBelumAbsen) > 0)
                <div class="d-flex align-items-center" style="gap: 8px;">
                  <button type="button" class="btn btn-outline-success btn-sm font-weight-bold" onclick="kirimWaSemuaKelas()">
                    <i class="fab fa-whatsapp mr-1"></i> Kirim WA ke Semua Walas
                  </button>
                  <a href="{{ route('admin.rekapBelumAbsen.export', ['tanggal' => $date]) }}" class="btn btn-success btn-sm font-weight-bold">
                    <i class="fa fa-file-excel mr-1"></i> Export Excel
                  </a>
                </div>
              @endif
            </div>

            <div class="card-body">
              @if(!$criteriaMet)
                <div class="alert alert-warning text-center shadow-sm">
                  <h5 class="font-weight-bold mb-2"><i class="fa fa-exclamation-triangle mr-1"></i> Kriteria Tidak Terpenuhi!</h5>
                  Data kelalaian input absensi hanya dapat ditampilkan jika minimal terdapat 2 kelas yang seluruh siswanya telah mengisi/diisi absensinya. <br>
                  (Saat ini baru <strong>{{ $fullClassesCount }}</strong> kelas yang sudah full mengisi absensi).
                </div>
              @else
                
                @if(count($guruPiket) > 0)
                  <div class="alert alert-info shadow-sm">
                    <h6 class="font-weight-bold mb-2"><i class="fa fa-user-clock mr-1"></i> Guru Piket Bertugas Hari Ini:</h6>
                    <ul class="mb-0 pl-3">
                      @foreach($guruPiket as $gp)
                        <li>{{ $gp->guru?->nama_guru ?? 'Guru Telah Dihapus' }} ({{ $gp->waktu_awal }} - {{ $gp->waktu_akhir }})</li>
                      @endforeach
                    </ul>
                  </div>
                @else
                  <div class="alert alert-secondary shadow-sm">
                    <i class="fa fa-info-circle mr-1"></i> Tidak ada jadwal Guru Piket yang diatur untuk hari {{ $dayInd }}.
                  </div>
                @endif

                @if(count($kelasBelumAbsen) > 0)
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                      <thead class="bg-primary text-white">
                        <tr>
                          <th style="width: 10px">No</th>
                          <th>Nama Kelas</th>
                          <th>Wali Kelas</th>
                          <th>Total Siswa</th>
                          <th>Jumlah Belum Absen</th>
                          <th class="text-center" style="width: 260px;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($kelasBelumAbsen as $item)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong class="text-primary">{{ $item['kelas']->nama_kelas }}</strong></td>
                            <td>
                              @if($item['waliKelas'])
                                <div class="d-flex flex-column">
                                  <strong>{{ $item['waliKelas']->nama_guru }}</strong>
                                  @if(!empty($item['waliNoHp']))
                                    <small class="text-success"><i class="fab fa-whatsapp mr-1"></i> {{ $item['waliNoHp'] }}</small>
                                  @else
                                    <small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i> No WA Kosong</small>
                                  @endif
                                </div>
                              @else
                                <span class="badge badge-secondary">Belum Diatur</span>
                              @endif
                            </td>
                            <td>{{ $item['totalSiswa'] }} Siswa</td>
                            <td>
                              <span class="badge badge-danger px-2 py-1" style="font-size: 13px;">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $item['jumlahBelumAbsen'] }} Belum Absen
                              </span>
                            </td>
                            <td class="text-center">
                              <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                <!-- Button Detail & Input Absen -->
                                <button type="button" class="btn btn-info btn-sm font-weight-bold" data-toggle="modal" data-target="#modalDetail{{ $item['kelas']->id_kelas }}" title="Lihat detail siswa yang belum diinput">
                                  <i class="fa fa-eye mr-1"></i> Detail
                                </button>

                                <!-- Button WhatsApp Notification to Wali Kelas -->
                                @if(!empty($item['waUrl']))
                                  <div class="btn-group">
                                    <a href="{{ $item['waUrl'] }}" target="_blank" class="btn btn-success btn-sm font-weight-bold" title="Kirim data nama siswa yang belum absen ke nomor WhatsApp Wali Kelas">
                                      <i class="fab fa-whatsapp mr-1"></i> Kirim WA
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilihan Pengiriman">
                                      <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                                      <a class="dropdown-item py-2" href="{{ $item['waUrl'] }}" target="_blank">
                                        <i class="fab fa-whatsapp text-success mr-2"></i> Buka WhatsApp Chat
                                      </a>
                                      <a class="dropdown-item py-2" href="javascript:void(0)" onclick="kirimWaGateway('{{ $item['kelas']->id_kelas }}', '{{ $item['kelas']->nama_kelas }}', '{{ $item['waliKelas']?->nama_guru ?? '' }}')">
                                        <i class="fas fa-paper-plane text-primary mr-2"></i> Kirim via WA Gateway
                                      </a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item py-2" href="javascript:void(0)" onclick="salinTeksPesan('{{ $item['kelas']->id_kelas }}')">
                                        <i class="fas fa-copy text-secondary mr-2"></i> Salin Teks Pesan
                                      </a>
                                    </div>
                                  </div>
                                @else
                                  <button type="button" class="btn btn-secondary btn-sm" onclick="alertNoWa('{{ $item['kelas']->nama_kelas }}', '{{ $item['waliKelas']?->nama_guru ?? '' }}')" title="Nomor WhatsApp Wali Kelas belum diisi">
                                    <i class="fab fa-whatsapp mr-1"></i> Kirim WA
                                  </button>
                                @endif
                              </div>

                              <!-- Hidden Textarea for copying formatted WA message -->
                              <textarea id="rawPesanWa{{ $item['kelas']->id_kelas }}" style="display: none;">{{ $item['pesanWa'] }}</textarea>

                              <!-- Modal Detail Siswa Belum Absen -->
                              <div class="modal fade text-left" id="modalDetail{{ $item['kelas']->id_kelas }}" role="dialog" aria-labelledby="modalLabel{{ $item['kelas']->id_kelas }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <form action="{{ route('admin.rekapBelumAbsen.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tanggal" value="{{ $date }}">
                                    <input type="hidden" name="id_kelas" value="{{ $item['kelas']->id_kelas }}">
                                    
                                    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                                      <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title font-weight-bold" id="modalLabel{{ $item['kelas']->id_kelas }}">
                                          <i class="fa fa-users mr-1"></i> Data Belum Absen: {{ $item['kelas']->nama_kelas }}
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3 p-3">
                                          <div>
                                            <div class="font-weight-bold text-dark mb-1">
                                              Wali Kelas: <span class="text-primary">{{ $item['waliKelas']?->nama_guru ?? 'Belum Diatur' }}</span>
                                            </div>
                                            <div class="small text-muted">
                                              Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }} | Belum Terinput: <span class="text-danger font-weight-bold">{{ $item['jumlahBelumAbsen'] }} Siswa</span>
                                            </div>
                                          </div>
                                          @if(!empty($item['waUrl']))
                                            <a href="{{ $item['waUrl'] }}" target="_blank" class="btn btn-sm btn-success font-weight-bold shadow-sm">
                                              <i class="fab fa-whatsapp mr-1"></i> Kirim ke Wali Kelas
                                            </a>
                                          @endif
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                          <p class="mb-0 text-secondary font-weight-bold" style="font-size: 0.88rem;">Daftar Siswa Belum Absen:</p>
                                          <button type="button" class="btn btn-xs btn-primary btn-pilih-semua-hadir">
                                            <i class="fa fa-check-double mr-1"></i> Set Semua Hadir
                                          </button>
                                        </div>
                                        
                                        <div class="table-responsive">
                                          <table class="table table-bordered table-striped table-hover">
                                            <thead class="bg-secondary text-white">
                                              <tr>
                                                <th style="width: 10px">No</th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th style="width: 180px">Status Kehadiran</th>
                                                <th>Keterangan</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              @foreach($item['siswaBelumAbsen'] as $siswa)
                                                <tr>
                                                  <td>{{ $loop->iteration }}</td>
                                                  <td>{{ $siswa->nis ?? '-' }}</td>
                                                  <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                                                  <td>
                                                    <select name="siswa[{{ $siswa->id_siswa }}][kehadiran]" class="form-control form-control-sm select-kehadiran">
                                                      <option value="">-- Pilih Kehadiran --</option>
                                                      <option value="hadir">Hadir</option>
                                                      <option value="sakit">Sakit</option>
                                                      <option value="izin">Izin</option>
                                                      <option value="alfa">Alfa</option>
                                                    </select>
                                                  </td>
                                                  <td>
                                                    <input type="text" name="siswa[{{ $siswa->id_siswa }}][keterangan]" class="form-control form-control-sm" placeholder="Keterangan (opsional)">
                                                  </td>
                                                </tr>
                                              @endforeach
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="modal-footer bg-light d-flex justify-content-between">
                                        <div class="small">
                                          <strong>Total Siswa:</strong> {{ $item['totalSiswa'] }} | 
                                          <strong>Belum Absen:</strong> {{ $item['jumlahBelumAbsen'] }}
                                        </div>
                                        <div style="gap: 6px;" class="d-flex">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Simpan Kehadiran</button>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  <div class="alert alert-success text-center shadow-sm">
                    <h5 class="font-weight-bold mb-2"><i class="fa fa-check-circle mr-1"></i> Absensi Selesai!</h5>
                    Seluruh siswa di semua kelas telah terisi kehadirannya untuk tanggal ini.
                  </div>
                @endif

              @endif
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('.btn-pilih-semua-hadir').on('click', function(e) {
      e.preventDefault();
      var targetModal = $(this).closest('.modal');
      targetModal.find('.select-kehadiran').val('hadir');
    });
  });

  // Salin Teks Pesan WhatsApp ke Clipboard
  function salinTeksPesan(idKelas) {
    var textEl = document.getElementById('rawPesanWa' + idKelas);
    if (textEl) {
      navigator.clipboard.writeText(textEl.value).then(function() {
        Swal.fire({
          icon: 'success',
          title: 'Tersalin!',
          text: 'Teks pesan pengingat beserta daftar nama siswa telah disalin ke clipboard.',
          timer: 2000,
          showConfirmButton: false
        });
      }).catch(function(err) {
        Swal.fire({
          icon: 'info',
          title: 'Teks Pesan:',
          html: '<textarea class="form-control" rows="10" readonly>' + textEl.value + '</textarea>'
        });
      });
    }
  }

  // Peringatan jika No WA belum ada
  function alertNoWa(namaKelas, namaWali) {
    var walasText = namaWali ? 'Bapak/Ibu ' + namaWali : 'Wali Kelas ' + namaKelas;
    Swal.fire({
      icon: 'warning',
      title: 'Nomor WhatsApp Belum Terdaftar',
      text: walasText + ' belum memiliki nomor WhatsApp yang terdaftar di Data Guru. Silakan lengkapi nomor telepon guru terlebih dahulu di menu Data Guru.',
      confirmButtonText: 'Mengerti',
      confirmButtonColor: '#3085d6'
    });
  }

  // Kirim Pesan via WhatsApp Gateway (Background Queue)
  function kirimWaGateway(idKelas, namaKelas, namaWali) {
    Swal.fire({
      title: 'Kirim via WhatsApp Gateway?',
      text: 'Data kelalaian absen kelas ' + namaKelas + ' akan dikirimkan otomatis ke WhatsApp ' + (namaWali ? namaWali : 'Wali Kelas') + ' melalui sistem gateway.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      cancelButtonColor: '#94a3b8',
      confirmButtonText: '<i class="fab fa-whatsapp mr-1"></i> Ya, Kirim Sekarang',
      cancelButtonText: 'Batal',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return $.ajax({
          url: '/admin/rekap-belum-absen/kirim-wa/' + idKelas,
          type: 'POST',
          data: {
            tanggal: '{{ $date }}',
            _token: '{{ csrf_token() }}'
          }
        }).catch(error => {
          var msg = (error.responseJSON && error.responseJSON.message) ? error.responseJSON.message : 'Gagal mengirim pesan ke WhatsApp.';
          Swal.showValidationMessage(msg);
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: result.value.message,
          confirmButtonColor: '#1d72fe'
        });
      }
    });
  }

  // Kirim WhatsApp ke Semua Wali Kelas Sekaligus
  function kirimWaSemuaKelas() {
    Swal.fire({
      title: 'Kirim WA ke Semua Wali Kelas?',
      text: 'Pemberitahuan kelalaian input absensi beserta daftar siswa yang belum absen akan dikirimkan ke seluruh Wali Kelas terkait.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      cancelButtonColor: '#94a3b8',
      confirmButtonText: '<i class="fab fa-whatsapp mr-1"></i> Ya, Kirim ke Semua',
      cancelButtonText: 'Batal',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return $.ajax({
          url: '/admin/rekap-belum-absen/kirim-wa-semua',
          type: 'POST',
          data: {
            tanggal: '{{ $date }}',
            _token: '{{ csrf_token() }}'
          }
        }).catch(error => {
          var msg = (error.responseJSON && error.responseJSON.message) ? error.responseJSON.message : 'Gagal memproses pengiriman massal.';
          Swal.showValidationMessage(msg);
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        Swal.fire({
          icon: 'success',
          title: 'Pengiriman Diproses!',
          text: result.value.message,
          confirmButtonColor: '#1d72fe'
        });
      }
    });
  }
</script>
@endpush

