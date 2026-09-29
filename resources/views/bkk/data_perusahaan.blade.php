@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-building text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Mitra Perusahaan (BKK / DU-DI)</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kelola kemitraan dunia usaha & dunia industri untuk PKL dan lowongan kerja</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">BKK</a></li>
          <li class="breadcrumb-item active">Data Perusahaan</li>
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
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Data Mitra Perusahaan
            </h3>
            <button class="btn btn-success btn-sm ml-auto" data-toggle="modal" data-target="#modalTambahPerusahaan">
              <i class="fas fa-plus mr-1"></i> Tambah Perusahaan
            </button>
          </div>

          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th style="width: 10px">No</th>
                  <th>Nama Perusahaan</th>
                  <th>Alamat</th>
                  <th>Penanggung Jawab</th>
                  <th>Jumlah Siswa PKL</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($perusahaan as $data)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $data->nama_perusahaan }}</td>
                  <td>{{ $data->alamat_perusahaan }}</td>
                  <td>{{ $data->penanggung_jawab }}</td>
                  <td>{{ $data->siswa_aktif_count }} Siswa</td>
                  <td>
                    <form action="{{ route('admin.perusahaan.destroy', $data->id_perusahaan) }}" method="POST">
                      <!-- Tombol Edit Modal -->
                      <button 
                        type="button" 
                        class="btn btn-success btn-edit-perusahaan"
                        data-toggle="modal"
                        data-target="#modalEditPerusahaan"
                        data-id="{{ $data->id_perusahaan }}"
                        data-nama="{{ $data->nama_perusahaan }}"
                        data-alamat="{{ $data->alamat_perusahaan }}"
                        data-pj="{{ $data->penanggung_jawab }}"
                      >
                        <i class="fa fa-edit"></i>
                      </button>
                      @csrf
                      @method('DELETE')
                      <!-- Tombol Hapus dengan Swal -->
                      <button 
                        type="button" 
                        class="btn btn-danger btn-delete-swal" 
                        data-id="{{ $data->id_perusahaan }}"
                        data-action="{{ route('admin.perusahaan.destroy', $data->id_perusahaan) }}">
                        <i class="fa fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div> 
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Perusahaan -->
<div class="modal fade" id="modalTambahPerusahaan" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.perusahaan.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah Perusahaan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_perusahaan">Nama Perusahaan</label>
            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required>
          </div>
          <div class="form-group">
            <label for="alamat_perusahaan">Alamat Perusahaan</label>
            <input type="text" class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" required>
          </div>
          <div class="form-group">
            <label for="penanggung_jawab">Penanggung Jawab</label>
            <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit Perusahaan -->
<div class="modal fade" id="modalEditPerusahaan" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formEditPerusahaan" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Perusahaan</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="edit_nama_perusahaan">Nama Perusahaan</label>
            <input type="text" class="form-control" id="edit_nama_perusahaan" name="nama_perusahaan" required>
          </div>
          <div class="form-group">
            <label for="edit_alamat_perusahaan">Alamat Perusahaan</label>
            <input type="text" class="form-control" id="edit_alamat_perusahaan" name="alamat_perusahaan" required>
          </div>
          <div class="form-group">
            <label for="edit_penanggung_jawab">Penanggung Jawab</label>
            <input type="text" class="form-control" id="edit_penanggung_jawab" name="penanggung_jawab" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
  $('.btn-edit-perusahaan').on('click', function () {
    let id = $(this).data('id');
    let nama = $(this).data('nama');
    let alamat = $(this).data('alamat');
    let pj = $(this).data('pj');

    // Isi form di modal
    $('#edit_nama_perusahaan').val(nama);
    $('#edit_alamat_perusahaan').val(alamat);
    $('#edit_penanggung_jawab').val(pj);

    // Set action form edit
    $('#formEditPerusahaan').attr('action', '/admin/perusahaan/' + id);
  });
});
// Script Delete With Swal
$(document).ready(function () {
    $('.btn-delete-swal').click(function (e) {
      e.preventDefault();
      let actionUrl = $(this).data('action');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data perusahaan dan siswa PKL terkait akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Buat form dinamis dan submit
          let form = $('<form>', {
            method: 'POST',
            action: actionUrl
          });

          let token = '{{ csrf_token() }}';
          let method = $('<input>', {
            type: 'hidden',
            name: '_method',
            value: 'DELETE'
          });

          form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: token
          })).append(method);

          $('body').append(form);
          form.submit();
        }
      });
    });
  });
</script>
@endpush

