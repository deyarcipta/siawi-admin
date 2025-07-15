@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Siswa PKL</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Data Siswa PKL</a></li>
          <li class="breadcrumb-item active">Data Siswa PKL</li>
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
            <h3 class="card-title">Data Siswa PKL</h3>
            <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#modalTambahSiswaPkl">
              Tambah Siswa PKL
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
                @foreach ($data_siswa_pkl as $data)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $data->siswa->nama_siswa }}</td>
                  <td>{{ $data->kelas->nama_kelas }}</td>
                  <td>{{ $data->perusahaan->nama_perusahaan }}</td>
                  <td>{{ $data->tanggal_mulai }}</td>
                  <td>
                    <form action="{{ route('admin.siswaPkl.destroy', $data->id_siswa_pkl) }}" method="POST">
                      <!-- Tombol Edit dengan Modal -->
                      <button 
                        type="button" 
                        class="btn btn-success btn-edit" 
                        data-toggle="modal" 
                        data-target="#modalEditSiswaPkl"
                        data-id="{{ $data->id_siswa_pkl }}"
                        data-id_kelas="{{ $data->kelas->id_kelas }}"
                        data-id_siswa="{{ $data->siswa->id_siswa }}"
                        data-id_perusahaan="{{ $data->perusahaan->id_perusahaan }}"
                        data-tanggal_mulai="{{ $data->tanggal_mulai }}"
                        data-tanggal_selesai="{{ $data->tanggal_selesai }}"
                      >
                        <i class="fa fa-edit"></i>
                      </button>
                      @csrf
                      @method('DELETE')
                      <button 
                        type="button" 
                        class="btn btn-danger btn-delete-swal" 
                        data-id="{{ $data->id_siswa_pkl }}"
                        data-action="{{ route('admin.siswaPkl.destroy', $data->id_siswa_pkl) }}">
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

<!-- Modal Tambah Siswa PKL -->
<div class="modal fade" id="modalTambahSiswaPkl" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.siswaPkl.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah Siswa PKL</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="id_kelas">Kelas</label>
            <select name="id_kelas" id="id_kelas" class="form-control">
              <option value="">Pilih Kelas</option>
              @foreach($kelasList as $kelas)
              <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="id_siswa">Nama Siswa</label>
              <select name="id_siswa" id="id_siswa" class="form-control">
                <option value="">Pilih siswa</option>
                @foreach($siswaList as $siswa)
                <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_siswa }}</option>
                @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="id_perusahaan">Perusahaan</label>
            <select name="id_perusahaan" class="form-control" required>
              <option value="">-- Pilih Perusahaan --</option>
              @foreach($perusahaan as $item)
                <option value="{{ $item->id_perusahaan }}">{{ $item->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" class="form-control" name="tanggal_mulai" required>
          </div>

          <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" class="form-control" name="tanggal_selesai" required>
          </div>
        </div>
        <input type="text" name="status" value="PKL" hidden>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit Siswa PKL -->
<div class="modal fade" id="modalEditSiswaPkl" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formEditSiswaPkl" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Siswa PKL</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Kelas -->
          <div class="form-group">
            <label for="edit_id_kelas">Kelas</label>
            <select name="id_kelas" id="edit_id_kelas" class="form-control">
              <option value="">Pilih Kelas</option>
              @foreach($kelasList as $kelas)
              <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <!-- Nama Siswa -->
          <div class="form-group">
            <label for="edit_id_siswa">Nama Siswa</label>
            <select name="id_siswa" id="edit_id_siswa" class="form-control">
              <option value="">Pilih siswa</option>
              @foreach($siswaList as $siswa)
              <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_siswa }}</option>
              @endforeach
            </select>
          </div>
          <!-- Perusahaan -->
          <div class="form-group">
            <label for="edit_id_perusahaan">Perusahaan</label>
            <select name="id_perusahaan" id="edit_id_perusahaan" class="form-control">
              <option value="">-- Pilih Perusahaan --</option>
              @foreach($perusahaan as $item)
              <option value="{{ $item->id_perusahaan }}">{{ $item->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>
          <!-- Tanggal -->
          <div class="form-group">
            <label for="edit_tanggal_mulai">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control">
          </div>
          <div class="form-group">
            <label for="edit_tanggal_selesai">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control">
          </div>
        </div>
        <input type="text" name="status" value="PKL" hidden>
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
    $(document).ready(function() {
      $('#id_kelas').on('change', function() {
          var idKelas = $(this).val();
          if (idKelas) {
              $.ajax({
                  url: '/admin/get-siswa-by-kelas/' + idKelas,
                  type: "GET",
                  dataType: "json",
                  success: function(data) {
                      $('#id_siswa').empty();
                      $('#id_siswa').append('<option value="">Pilih Siswa</option>');
                      // Urutkan berdasarkan nama_siswa secara ascending
                      data.sort(function(a, b) {
                          return a.nama_siswa.localeCompare(b.nama_siswa);
                      });
                      $.each(data, function(key, siswa) {
                          $('#id_siswa').append('<option value="'+ siswa.id_siswa +'">'+ siswa.nama_siswa +'</option>');
                      });
                  }
              });
          } else {
              $('#id_siswa').empty();
              $('#id_siswa').append('<option value="">Pilih Siswa</option>');
          }
      });
  });

  $(document).ready(function () {
    $('.btn-edit').on('click', function () {
      var id = $(this).data('id');
      var idKelas = $(this).data('id_kelas');
      var idSiswa = $(this).data('id_siswa');
      var idPerusahaan = $(this).data('id_perusahaan');
      var tanggalMulai = $(this).data('tanggal_mulai');
      var tanggalSelesai = $(this).data('tanggal_selesai');

      $('#edit_id_kelas').val(idKelas).trigger('change');
      $('#edit_id_siswa').val(idSiswa);
      $('#edit_id_perusahaan').val(idPerusahaan);
      $('#edit_tanggal_mulai').val(tanggalMulai);
      $('#edit_tanggal_selesai').val(tanggalSelesai);

      $('#formEditSiswaPkl').attr('action', '/admin/siswaPkl/' + id);
    });

    // Ajax untuk update siswa berdasarkan kelas di modal edit
    $('#edit_id_kelas').val(idKelas).trigger('change');

    // Delay set siswa agar nunggu AJAX selesai
    setTimeout(function () {
        $.ajax({
            url: '/admin/get-siswa-by-kelas/' + idKelas,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#edit_id_siswa').empty().append('<option value="">Pilih Siswa</option>');
                data.sort(function(a, b) {
                    return a.nama_siswa.localeCompare(b.nama_siswa);
                });
                $.each(data, function(key, siswa) {
                    $('#edit_id_siswa').append('<option value="'+ siswa.id_siswa +'">'+ siswa.nama_siswa +'</option>');
                });

                // Set selected value setelah option dimuat
                $('#edit_id_siswa').val(idSiswa);
            }
        });
    }, 300);
  });

  // Script Delete With Swal
$(document).ready(function () {
    $('.btn-delete-swal').click(function (e) {
      e.preventDefault();
      let actionUrl = $(this).data('action');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data siswa PKL terkait akan dihapus!",
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