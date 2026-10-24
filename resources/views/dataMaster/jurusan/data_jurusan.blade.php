@extends($layout)
@section('content')
  <div class="content-header pb-2">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div>
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Data Program Keahlian (Jurusan)</h1>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Daftar kompetensi keahlian kejuruan dan kepala program keahlian</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Jurusan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {!! session('success') !!}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-exclamation-circle mr-1"></i> {!! session('error') !!}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="row">
        <div class="col-lg-12">
          <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 d-flex align-items-center">
              <h5 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1.05rem;">
                <i class="fas fa-table text-primary mr-2"></i> Daftar Jurusan
              </h5>
              @if($user->hasRole('admin'))
                <a href="/admin/jurusan/create" class="btn btn-primary btn-sm ml-auto px-3 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                  <i class="fas fa-plus mr-1"></i> Tambah Jurusan
                </a>
              @endif
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped">
                  <thead class="thead-light">
                    <tr>
                      <th style="width: 50px" class="text-center">No</th>
                      <th style="width: 140px">Kode Jurusan</th>
                      <th>Nama Program Keahlian</th>
                      <th>Kepala Program (Kaprog)</th>
                      <th style="width: 200px">Modul Kejuruan</th>
                      <th style="width: 100px" class="text-center">Status</th>
                      <th style="width: 120px" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($jurusan as $jur)
                      <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">
                          <span class="badge badge-light border px-2 py-1 font-weight-bold" style="font-size: 0.85rem;">
                            {{ $jur->kode_jurusan }}
                          </span>
                        </td>
                        <td class="align-middle font-weight-600 text-dark">{{ $jur->nama_jurusan }}</td>
                        <td class="align-middle">
                          @if($jur->kaprog->isNotEmpty())
                            @foreach($jur->kaprog as $kp)
                              <span class="d-inline-block text-dark font-weight-500">
                                <i class="fas fa-user-tie text-secondary mr-1"></i> {{ $kp->nama_guru }}
                              </span>
                            @endforeach
                          @else
                            <span class="text-muted font-italic" style="font-size: 0.85rem;">Belum ditugaskan</span>
                          @endif
                        </td>
                        <td class="align-middle">
                          @php $mods = $jur->modul_kejuruan; @endphp
                          <div class="d-flex flex-wrap" style="gap: 4px;">
                            @if(!empty($mods['tkk'])) <span class="badge badge-info" style="font-size: 0.72rem;">TKK</span> @endif
                            @if(!empty($mods['ukk'])) <span class="badge badge-primary" style="font-size: 0.72rem;">UKK</span> @endif
                            @if(!empty($mods['praktik'])) <span class="badge badge-success" style="font-size: 0.72rem;">Praktik</span> @endif
                            @if(!empty($mods['portofolio'])) <span class="badge badge-warning text-dark" style="font-size: 0.72rem;">Portofolio</span> @endif
                            @if(!empty($mods['lab'])) <span class="badge badge-secondary" style="font-size: 0.72rem;">Lab</span> @endif
                          </div>
                        </td>
                        <td class="text-center align-middle">
                          @if($jur->aktif)
                            <span class="badge badge-success px-2 py-1">Aktif</span>
                          @else
                            <span class="badge badge-secondary px-2 py-1">Nonaktif</span>
                          @endif
                        </td>
                        <td class="text-center align-middle">
                          <div class="btn-group" role="group">
                            <a href="/admin/jurusan/{{ $jur->id_jurusan }}/edit" class="btn btn-sm btn-outline-primary" title="Ubah Data" style="border-radius: 6px 0 0 6px;">
                              <i class="fas fa-edit"></i>
                            </a>
                            @if($user->hasRole('admin'))
                              <form action="/admin/jurusan/{{ $jur->id_jurusan }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data" style="border-radius: 0 6px 6px 0; border-left: none;">
                                  <i class="fas fa-trash-alt"></i>
                                </button>
                              </form>
                            @endif
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                          <i class="fas fa-info-circle mr-1"></i> Belum ada data jurusan yang tersedia.
                        </td>
                      </tr>
                    @endforelse
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