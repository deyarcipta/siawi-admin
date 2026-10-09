@extends($layout)

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-book-reader text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Jurnal Mengajar</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Catatan harian aktivitas guru mengajar, materi pembahasan, dan absensi</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item active">Jurnal Mengajar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <!-- Card Filter -->
        <div class="card mb-3">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Periode Jurnal
            </h3>
          </div>
          <div class="card-body">
            <form method="GET" action="{{ route('admin.jurnal.index') }}" class="row align-items-end">
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="tanggal_awal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
                <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
              </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Data</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Card Daftar Jurnal -->
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Daftar Jurnal Mengajar
            </h3>
            <a href="{{ route('admin.jurnal.downloadPdf', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir')
                ]) }}" 
                class="btn btn-danger btn-sm ml-auto" target="_blank">
                <i class="fas fa-file-pdf mr-1"></i> Download PDF
            </a>
            <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#tambahJurnalModal">
              <i class="fas fa-plus mr-1"></i> Tambah Jurnal
            </button>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Guru</th>
                  <th>Kelas</th>
                  <th>Mata Pelajaran</th>
                  <th>Jam</th>
                  <th>Materi</th>
                  <th>Foto Kelas</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jurnals as $data)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->tanggal }}</td>
                    <td>{{ $data->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $data->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $data->jadwal->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $data->jam_awal }} s/d {{ $data->jam_akhir }}</td>
                    <td>{{ $data->materi }}</td>
                    <td>
                      @if($data->foto_kelas)
                        <img src="{{ asset('storage/'.$data->foto_kelas) }}" class="img-thumbnail" style="max-height:80px;">
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center">
                      <form action="{{ route('admin.jurnal.destroy', $data->id_jurnal) }}" method="POST" class="d-inline-block mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#editJurnal{{ $data->id_jurnal }}" title="Edit Jurnal">
                          <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus Jurnal">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{-- {{ $jurnals->links() }} --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Jurnal -->
<div class="modal fade" id="tambahJurnalModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form action="{{ route('admin.jurnal.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="modalLabel">
            <i class="fas fa-book-open mr-2"></i> Tambah Jurnal Mengajar
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          @if($user->role === 'admin')
            <div class="form-group mb-3">
              <label for="tanggal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tanggal <span class="text-danger">*</span></label>
              <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 8px; height: 42px;">
            </div>

            <div class="form-group mb-3">
              <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Guru <span class="text-danger">*</span></label>
              <select id="id_guru" name="id_guru" class="form-control" required style="border-radius: 8px; height: 42px;">
                <option value="">-- Pilih Guru --</option>
                @foreach($guru as $g)
                  <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                @endforeach
              </select>
            </div>
          @else
            <input type="hidden" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
            <input type="hidden" id="id_guru" name="id_guru" value="{{ $user->id_guru }}">
          @endif

          <div class="form-group mb-3">
            <label for="id_jadwal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jadwal Mapel <span class="text-danger">*</span></label>
            <select id="id_jadwal" name="id_jadwal" class="form-control" required style="border-radius: 8px; height: 42px;">
              <option value="">-- Pilih Jadwal --</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="jam_awal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Awal</label>
                <input type="text" name="jam_awal" id="jam_awal" class="form-control" readonly style="border-radius: 8px; height: 42px;">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="jam_akhir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Akhir</label>
                <input type="text" name="jam_akhir" id="jam_akhir" class="form-control" readonly style="border-radius: 8px; height: 42px;">
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="materi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Materi <span class="text-danger">*</span></label>
            <input type="text" name="materi" class="form-control" required placeholder="Pokok bahasan / materi pembelajaran" style="border-radius: 8px; height: 42px;">
          </div>

          <div class="form-group mb-0">
            <label for="foto_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Foto Kelas</label>
            @error('foto_kelas')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            <input type="file" name="foto_kelas" 
                  class="form-control @error('foto_kelas') is-invalid @enderror" 
                  accept="image/*"  
                  onchange="previewFoto(event, 'previewTambah')" style="border-radius: 8px;">
            <small class="text-muted">Upload foto kegiatan belajar mengajar</small>
            <div class="mt-2">
              <img id="previewTambah" src="{{ asset('images/no-image.png') }}" 
                  class="img-thumbnail" style="max-height:120px; display:none; border-radius: 8px;">
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Jurnal
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit Jurnal --}}
@foreach ($jurnals as $data)
<div class="modal fade" id="editJurnal{{ $data->id_jurnal }}" tabindex="-1" role="dialog" aria-labelledby="editLabel{{ $data->id_jurnal }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form action="{{ route('admin.jurnal.update', $data->id_jurnal) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="editLabel{{ $data->id_jurnal }}">
            <i class="fas fa-edit mr-2"></i> Edit Jurnal Mengajar
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          @if($user->role === 'admin')
            <div class="form-group mb-3">
              <label for="tanggal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tanggal <span class="text-danger">*</span></label>
              <input type="date" name="tanggal" class="form-control" value="{{ $data->tanggal }}" required style="border-radius: 8px; height: 42px;">
            </div>

            <div class="form-group mb-3">
              <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Guru <span class="text-danger">*</span></label>
              <select name="id_guru" class="form-control" required style="border-radius: 8px; height: 42px;">
                <option value="">-- Pilih Guru --</option>
                @foreach($guru as $g)
                  <option value="{{ $g->id_guru }}" {{ $data->id_guru == $g->id_guru ? 'selected' : '' }}>
                    {{ $g->nama_guru }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="form-group mb-3">
            <label for="id_jadwal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jadwal Mapel <span class="text-danger">*</span></label>
            <select name="id_jadwal" class="form-control" required style="border-radius: 8px; height: 42px;">
              @foreach($jadwal as $j)
                <option value="{{ $j->id_jadwal }}" {{ $data->id_jadwal == $j->id_jadwal ? 'selected' : '' }}>
                  {{ $j->mapel->nama_mapel ?? '-' }} - {{ $j->kelas->nama_kelas ?? '-' }}
                  ({{ $j->waktu_awal }} - {{ $j->waktu_akhir }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="jam_awal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Awal</label>
                <input type="text" name="jam_awal" class="form-control" value="{{ $data->jam_awal }}" readonly style="border-radius: 8px; height: 42px;">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="jam_akhir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Akhir</label>
                <input type="text" name="jam_akhir" class="form-control" value="{{ $data->jam_akhir }}" readonly style="border-radius: 8px; height: 42px;">
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="materi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Materi <span class="text-danger">*</span></label>
            <input type="text" name="materi" class="form-control" value="{{ $data->materi }}" required style="border-radius: 8px; height: 42px;">
          </div>

          <div class="form-group mb-0">
            <label for="foto_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Foto Kelas</label><br>
            @if($data->foto_kelas)
              <img src="{{ asset('storage/'.$data->foto_kelas) }}" class="img-thumbnail mb-2" style="max-height:120px; border-radius: 8px;">
            @endif
            <input type="file" name="foto_kelas" class="form-control" accept="image/*" style="border-radius: 8px;">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Update Jurnal
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ✅ Delegasi event untuk tombol hapus (aman desktop & mobile)
  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const form = $(this).closest('form');

    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data yang dihapus tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });

  // ✅ Fungsi fetch jadwal
  function fetchJadwal() {
    let guru = $('#id_guru').val()?.trim();
    let tanggal = $('#tanggal').val()?.trim();
    if(!guru || !tanggal) return;

    $.ajax({
      url: "{{ route('admin.jurnal.getJadwal') }}",
      type: "GET",
      data: { id_guru: guru, tanggal: tanggal },
      success: function(res) {
        const $select = $('#id_jadwal');
        $select.empty().append('<option value="">-- Pilih Jadwal --</option>');

        if(res && res.count > 0 && Array.isArray(res.data)) {
          // Urutkan berdasarkan waktu_awal
          res.data.sort((a,b) => {
            let timeA = a.waktu_awal || '';
            let timeB = b.waktu_awal || '';
            return timeA.localeCompare(timeB);
          });

          res.data.forEach(j => {
            let mapel = j.mapel?.nama_mapel ?? '-';
            let kelas = j.kelas?.nama_kelas ?? '-';
            let guru = j.guru?.nama_guru ?? '-';
            let awal = j.waktu_awal ?? '';
            let akhir = j.waktu_akhir ?? '';
            let jam_awal = j.jam_awal ?? '';
            let jam_akhir = j.jam_akhir ?? '';

            $select.append(
              `<option value="${j.id_jadwal}" 
                data-jam_awal="${jam_awal}" 
                data-jam_akhir="${jam_akhir}">
                 ${mapel} - ${kelas} ([${jam_awal} = ${awal}] - [${jam_akhir} = ${akhir}]) | ${guru}
              </option>`
            );
          });
        }
      },
      error: function(err) {
        console.error("AJAX error:", err);
      }
    });
  }

  // ✅ Event change jadwal → isi jam otomatis
  $(document).on('change', '#id_jadwal', function(){
    let selected = $(this).find('option:selected');
    let jam_awal = selected.data('jam_awal') || '';
    let jam_akhir = selected.data('jam_akhir') || '';
    $('#jam_awal').val(jam_awal);
    $('#jam_akhir').val(jam_akhir);
  });

  // ✅ Event change untuk admin
  @if($user->role === 'admin')
    $('#id_guru, #tanggal').on('change', fetchJadwal);
  @endif

  // ✅ Panggil otomatis untuk guru
  $('#tambahJurnalModal').on('shown.bs.modal', function () {
    @if($user->role !== 'admin')
      fetchJadwal();
    @endif
  });
});

// ✅ Preview Foto
function previewFoto(event, targetId) {
  const [file] = event.target.files;
  if(file) {
    const preview = document.getElementById(targetId);
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  }
}
</script>

@if ($errors->any())
<script>
  $(document).ready(function () {
    $('#tambahJurnalModal').modal('show');
  });
</script>
@endif

@if(session('error'))
<script>
  Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: "{{ session('error') }}",
    confirmButtonColor: '#d33'
  });
</script>
@endif
@endpush
