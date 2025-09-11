@extends($layout)

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Jurnal Mengajar</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
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
          <div class="card-header">
            <h3 class="card-title">Filter Data Jurnal</h3>
          </div>
          <div class="card-body">
            <form method="GET" action="{{ route('admin.jurnal.index') }}" class="row g-3">
              <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
              </div>
              <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Tampilkan Data</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Card Daftar Jurnal -->
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Daftar Jurnal Mengajar</h3>
            <a href="{{ route('admin.jurnal.downloadPdf', [
                    'tanggal_awal' => request('tanggal_awal'),
                    'tanggal_akhir' => request('tanggal_akhir')
                ]) }}" 
                class="btn btn-danger ml-auto" target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#tambahJurnalModal">
              Tambah Jurnal
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
                  <th>Aksi</th>
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
                    <td>
                      <form action="{{ route('admin.jurnal.destroy', $data->id_jurnal) }}" method="POST">
                        <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#editJurnal{{ $data->id_jurnal }}">
                          <i class="fa fa-edit text-white"></i>
                        </button>
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-delete">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{ $jurnals->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Jurnal -->
<div class="modal fade" id="tambahJurnalModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Tambah Jurnal Mengajar</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.jurnal.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          
          @if($user->role === 'admin')
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
              <label for="id_guru">Guru</label>
              <select id="id_guru" name="id_guru" class="form-control" required>
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

          <div class="form-group">
            <label for="id_jadwal">Jadwal Mapel</label>
            <select id="id_jadwal" name="id_jadwal" class="form-control" required>
              <option value="">-- Pilih Jadwal --</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="jam_awal">Jam Awal</label>
                <input type="text" name="jam_awal" id="jam_awal" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="jam_akhir">Jam Akhir</label>
                <input type="text" name="jam_akhir" id="jam_akhir" class="form-control" readonly>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="materi">Materi</label>
            <input type="text" name="materi" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="foto_kelas">Foto Kelas</label>
            @error('foto_kelas')
                <div class="text-danger small">{{ $message }}</div>
            @enderror

            <input type="file" name="foto_kelas" 
                  class="form-control @error('foto_kelas') is-invalid @enderror" 
                  accept="image/*" capture="camera" 
                  onchange="previewFoto(event, 'previewTambah')">

            <div class="mt-2">
              <img id="previewTambah" src="{{ asset('images/no-image.png') }}" 
                  class="img-thumbnail" style="max-height:120px; display:none;">
            </div>
          </div>


          <button type="submit" class="btn btn-success">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit Jurnal --}}
@foreach ($jurnals as $data)
<div class="modal fade" id="editJurnal{{ $data->id_jurnal }}" tabindex="-1" aria-labelledby="editLabel{{ $data->id_jurnal }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLabel{{ $data->id_jurnal }}">Edit Jurnal Mengajar</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.jurnal.update', $data->id_jurnal) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          @if($user->role === 'admin')
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" value="{{ $data->tanggal }}" required>
            </div>

            <div class="form-group">
              <label for="id_guru">Guru</label>
              <select name="id_guru" class="form-control" required>
                <option value="">-- Pilih Guru --</option>
                @foreach($guru as $g)
                  <option value="{{ $g->id_guru }}" {{ $data->id_guru == $g->id_guru ? 'selected' : '' }}>
                    {{ $g->nama_guru }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="form-group">
            <label for="id_jadwal">Jadwal Mapel</label>
            <select name="id_jadwal" class="form-control" required>
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
              <div class="form-group">
                <label for="jam_awal">Jam Awal</label>
                <input type="text" name="jam_awal" class="form-control" value="{{ $data->jam_awal }}" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="jam_akhir">Jam Akhir</label>
                <input type="text" name="jam_akhir" class="form-control" value="{{ $data->jam_akhir }}" readonly>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="materi">Materi</label>
            <input type="text" name="materi" class="form-control" value="{{ $data->materi }}" required>
          </div>

          <div class="form-group">
            <label for="foto_kelas">Foto Kelas</label><br>
            @if($data->foto_kelas)
              <img src="{{ asset('storage/'.$data->foto_kelas) }}" class="img-thumbnail mb-2" style="max-height:120px;">
            @endif
            <input type="file" name="foto_kelas" class="form-control" accept="image/*" capture="camera">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
          </div>

          <button type="submit" class="btn btn-success">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // SweetAlert Hapus
  const deleteButtons = document.querySelectorAll('.btn-delete');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const form = this.closest('form');
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then(result => { if(result.isConfirmed) form.submit(); });
    });
  });

  // Fungsi fetch jadwal
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

  // Event change jadwal → isi jam otomatis
  $(document).on('change', '#id_jadwal', function(){
    let selected = $(this).find('option:selected');
    let jam_awal = selected.data('jam_awal') || '';
    let jam_akhir = selected.data('jam_akhir') || '';
    $('#jam_awal').val(jam_awal);
    $('#jam_akhir').val(jam_akhir);
  });

  // Event change untuk admin
  @if($user->role === 'admin')
    $('#id_guru, #tanggal').on('change', fetchJadwal);
  @endif

  // Panggil otomatis untuk guru
  $('#tambahJurnalModal').on('shown.bs.modal', function () {
    @if($user->role !== 'admin')
      fetchJadwal();
    @endif
  });
});

// Preview Foto
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
