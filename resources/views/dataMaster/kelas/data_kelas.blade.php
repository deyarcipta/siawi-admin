@extends($layout)
@section('content')

<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Kelas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Data Master</a></li>
          <li class="breadcrumb-item active">Data Kelas</li>
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
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Data Kelas</h3>
            <a href="/admin/kelas/create" class="btn btn-success ml-auto">Tambah Kelas</a>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th style="width: 10px">No</th>
                  <th>Kode Kelas</th>
                  <th>Kode Level</th>
                  <th>Nama Kelas</th>
                  <th>Kode Jurusan</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($kelas as $kls)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $kls->kode_kelas }}</td>
                  <td>{{ $kls->kode_level }}</td>
                  <td>{{ $kls->nama_kelas }}</td>
                  <td>{{ $kls->kode_jurusan }}</td>
                  <td>
                    <form action="/admin/kelas/{{ $kls->id_kelas }}" method="POST">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalSiswa-{{ $kls->id_kelas }}">
                        <i class="fa fa-user"></i>
                      </button>
                      <a href="/admin/kelas/{{ $kls->id_kelas }}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div> <!-- /.card-body -->
        </div> <!-- /.card -->
      </div>
    </div>
  </div>
</div>

<!-- MODALS -->
@foreach ($kelas as $kls)
<div class="modal fade" id="modalSiswa-{{ $kls->id_kelas }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $kls->id_kelas }}" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        <h5 class="modal-title mb-0">Data Siswa - {{ $kls->nama_kelas }}</h5>
        <div>
          @if(strtoupper($kls->kode_level) === 'XII')
          {{-- Tombol Pindah ke Alumni --}}
          <form action="{{ url('/admin/kelas/'.$kls->id_kelas.'/pindah-semua-alumni') }}" method="POST" onsubmit="return confirm('Yakin ingin memindahkan semua siswa ke alumni?')">
            @csrf
            <input type="hidden" name="tahun_lulus" value="{{ date('Y') }}">
            <button type="submit" class="btn btn-danger btn-sm">
              <i class="fa fa-share-square"></i> Pindahkan Semua ke Alumni
            </button>
          </form>
          @else
          {{-- Tombol Naik Kelas --}}
          <form action="{{ url('/admin/kelas/'.$kls->id_kelas.'/naik-kelas') }}" method="POST" onsubmit="return confirm('Yakin ingin menaikkan semua siswa ke kelas berikutnya?')">
            @csrf
            <button type="submit" class="btn btn-info btn-sm">
              <i class="fa fa-level-up"></i> Naik Kelas
            </button>
          </form>
          @endif
        </div>
      </div>

      <div class="modal-body">
        @php
          $siswaKelas = \App\Models\Siswa::where('id_kelas', $kls->id_kelas)->get();
        @endphp

        @if($siswaKelas->count())
        <form action="{{ url('/admin/kelas/proses-individual') }}" method="POST">
          @csrf
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Email</th>
                <th>Aksi</th>
                <th>Pilih (jika ingin proses massal)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($siswaKelas as $sw)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sw->nama_siswa }}</td>
                <td>{{ $sw->nis }}</td>
                <td>{{ $sw->email }}</td>
                <td>
                  @if(strtoupper($kls->kode_level) === 'XII')
                  <form action="{{ url('/admin/siswa/'.$sw->id_siswa.'/alumni') }}" method="POST" style="display:inline-block">
                    @csrf
                    <input type="hidden" name="tahun_lulus" value="{{ date('Y') }}">
                    <button type="submit" class="btn btn-warning btn-sm">
                      <i class="fa fa-share"></i> Alumni
                    </button>
                  </form>
                  @else
                  <select name="tujuan_kelas[{{ $sw->id_siswa }}]" class="form-control form-control-sm">
                    @php
                      $kelasTujuan = \App\Models\Kelas::where('kode_level', strtoupper($kls->kode_level) === 'X' ? 'XI' : 'XII')
                          ->where('kode_jurusan', $kls->kode_jurusan)->get();
                    @endphp
                    @foreach($kelasTujuan as $kt)
                      <option value="{{ $kt->id_kelas }}">{{ $kt->nama_kelas }}</option>
                    @endforeach
                  </select>
                  @endif
                </td>
                <td>
                  <input type="checkbox" name="selected_siswa[]" value="{{ $sw->id_siswa }}">
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          @if(strtoupper($kls->kode_level) === 'XII')
            <input type="hidden" name="action" value="alumni">
            <input type="hidden" name="tahun_lulus" value="{{ date('Y') }}">
          @else
            <input type="hidden" name="action" value="naik">
          @endif

          <button type="submit" class="btn btn-primary mt-3">
            Proses Siswa Terpilih
          </button>
        </form>
        @else
          <div class="alert alert-info">Tidak ada siswa dalam kelas ini.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endforeach

<!-- END MODALS -->

@endsection
