@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Berita</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Berita</a></li>
            <li class="breadcrumb-item active">Edit Berita</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Edit Berita</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/berita/{{$edit->id_berita}}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="judul_berita">Judul Berita</label>
                  <input type="text" class="form-control" id="judul_berita" placeholder="Enter Judul Berita" name="judul_berita" value="{{$edit->judul_berita}}">
                  @error('judul_berita')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="isi_berita">Isi Berita</label>
                  <textarea type="text" class="form-control" id="isi_berita" placeholder="Enter Isi Berita" name="isi_berita" value="{{$edit->isi_berita}}">{{$edit->isi_berita}}</textarea>
                  @error('isi_berita')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="pembuat">Pembuat Berita</label>
                    <select class="form-control" name="pembuat" id="pembuat">
                    @foreach ($guru as $gru)
                      <option value="{{ $gru->id_guru }}" {{ $edit->id_guru == $gru->id_guru ? 'selected' : '' }}> {{ $gru->nama_guru }}</option>
                    @endforeach
                    </select>
                    @error('pembuat')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="tanggal">Tanggal Berita</label>
                    <input class="form-control" type="datetime-local" name="tanggal" id="tanggal" value="{{$carbonDate}}">
                    </select>
                    @error('tanggal')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group">
                  <label for="cover">Cover Berita</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="cover" id="cover">
                      <label class="custom-file-label" id="cover-label" for="cover">Choose file</label>
                      <script>
                        document.getElementById('cover').addEventListener('change', function(e) {
                            var fileName = e.target.files[0].name;
                            var label = document.getElementById('cover-label');
                            label.textContent = fileName;
                        });
                    </script>
                    </div>
                  </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection