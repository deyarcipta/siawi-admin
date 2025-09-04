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
                <select name="jam_awal" class="form-control">
                  @for($i=1; $i<=10; $i++)
                    <option value="ke-{{ $i }}">ke-{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="jam_akhir">Jam Akhir</label>
                <select name="jam_akhir" class="form-control">
                  @for($i=1; $i<=10; $i++)
                    <option value="ke-{{ $i }}">ke-{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="materi">Materi</label>
            <input type="text" name="materi" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="foto_kelas">Foto Kelas</label>
            <input type="file" name="foto_kelas" class="form-control" accept="image/*" capture="camera" onchange="previewFoto(event, 'previewTambah')">
            <div class="mt-2">
              <img id="previewTambah" src="{{ asset('images/no-image.png') }}" class="img-thumbnail" style="max-height:120px; display:none;">
            </div>
          </div>

          <button type="submit" class="btn btn-success">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

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

            $select.append(
              `<option value="${j.id_jadwal}">
                 ${mapel} - ${kelas} (${awal} - ${akhir}) | ${guru}
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
@endpush
