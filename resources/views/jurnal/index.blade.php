@extends($layout)

@section('content')
  <!-- Content Header -->
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

  <!-- Content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="card mb-3">
              <div class="card-header">
                  <h3 class="card-title">Filter Data Jurnal</h3>
              </div>
              <div class="card-body">
                  <form method="GET" action="{{ route('admin.jurnal.index') }}" class="row g-3">
                      <div class="col-md-4">
                          <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                          <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control"
                                value="{{ request('tanggal_awal') }}">
                      </div>
                      <div class="col-md-4">
                          <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                          <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control"
                                value="{{ request('tanggal_akhir') }}">
                      </div>
                      <div class="col-md-4 d-flex align-items-end">
                          <button type="submit" class="btn btn-primary w-100">Tampilkan Data</button>
                      </div>
                  </form>
              </div>
          </div>
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
                    <th style="width: 10px">No</th>
                    <th>Tanggal</th>
                    <th>Guru</th>
                    <th>Kelas</th>
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
                    <td>{{ $data->kelas->nama_kelas }}</td>
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

                  <!-- Modal Edit Jurnal -->
                  <div class="modal fade" id="editJurnal{{ $data->id_jurnal }}" tabindex="-1" aria-labelledby="editModalLabel{{ $data->id_jurnal }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editModalLabel{{ $data->id_jurnal }}">Edit Jurnal Mengajar</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form action="{{ route('admin.jurnal.update', $data->id_jurnal) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Jika admin bisa pilih guru --}}
                            @if($user->role === 'admin')
                            <div class="form-group">
                              <label for="id_guru">Guru</label>
                              <select name="id_guru" class="form-control" required>
                                @foreach($guru as $g)
                                  <option value="{{ $g->id_guru }}" {{ $data->id_guru == $g->id_guru ? 'selected' : '' }}>
                                    {{ $g->nama_guru }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                            @endif

                            <div class="form-group">
                              <label for="id_kelas">Kelas</label>
                              <select name="id_kelas" class="form-control" required>
                                @foreach($kelas as $k)
                                  <option value="{{ $k->id_kelas }}" {{ $data->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                  </option>
                                @endforeach
                              </select>
                            </div>

                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="jam_awal">Jam Awal</label>
                                  <select name="jam_awal" class="form-control">
                                    @for($i=1;$i<=10;$i++)
                                      <option value="ke-{{ $i }}" {{ $data->jam_awal == 'ke-'.$i ? 'selected' : '' }}>ke-{{ $i }}</option>
                                    @endfor
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="jam_akhir">Jam Akhir</label>
                                  <select name="jam_akhir" class="form-control">
                                    @for($i=1;$i<=10;$i++)
                                      <option value="ke-{{ $i }}" {{ $data->jam_akhir == 'ke-'.$i ? 'selected' : '' }}>ke-{{ $i }}</option>
                                    @endfor
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="materi">Materi</label>
                              <input type="text" name="materi" class="form-control" value="{{ $data->materi }}">
                            </div>

                            {{-- Foto dengan preview --}}
                            <div class="form-group">
                              <label for="foto_kelas">Foto Kelas</label>
                              <input type="file" name="foto_kelas" class="form-control" accept="image/*" onchange="previewFoto(event, 'previewEdit{{ $data->id_jurnal }}')">
                              <div class="mt-2">
                                <img id="previewEdit{{ $data->id_jurnal }}" src="{{ $data->foto_kelas ? asset('storage/'.$data->foto_kelas) : asset('images/no-image.png') }}" class="img-thumbnail" style="max-height:120px;">
                              </div>
                            </div>

                            {{-- Jika admin bisa edit tanggal --}}
                            @if($user->role === 'admin')
                            <div class="form-group">
                              <label for="tanggal">Tanggal</label>
                              <input type="date" name="tanggal" class="form-control" value="{{ $data->tanggal }}">
                            </div>
                            @endif

                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
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
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.jurnal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Jika admin bisa pilih guru --}}
            @if($user->role === 'admin')
            <div class="form-group">
              <label for="id_guru">Guru</label>
              <select name="id_guru" class="form-control" required>
                <option value="">-- Pilih Guru --</option>
                @foreach($guru as $g)
                  <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                @endforeach
              </select>
            </div>
            @endif

            <div class="form-group">
              <label for="id_kelas">Kelas</label>
              <select name="id_kelas" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                  <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                @endforeach
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="jam_awal">Jam Awal</label>
                  <select name="jam_awal" class="form-control">
                    @for($i=1;$i<=10;$i++)
                      <option value="ke-{{ $i }}">ke-{{ $i }}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="jam_akhir">Jam Akhir</label>
                  <select name="jam_akhir" class="form-control">
                    @for($i=1;$i<=10;$i++)
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

            {{-- Foto dengan preview --}}
            <div class="form-group">
              <label for="foto_kelas">Foto Kelas</label>
              <input type="file" name="foto_kelas" class="form-control" accept="image/*" capture="camera" onchange="previewFoto(event, 'previewTambah')">
              <div class="mt-2">
                <img id="previewTambah" src="{{ asset('images/no-image.png') }}" class="img-thumbnail" style="max-height:120px; display:none;">
              </div>
            </div>

            {{-- Jika admin bisa isi tanggal --}}
            @if($user->role === 'admin')
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            @endif

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
  const deleteButtons = document.querySelectorAll('.btn-delete');

  deleteButtons.forEach(function (button) {
    button.addEventListener('click', function (e) {
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
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});

function previewFoto(event, targetId) {
  const [file] = event.target.files;
  if (file) {
    const preview = document.getElementById(targetId);
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  }
}
</script>
@endpush
