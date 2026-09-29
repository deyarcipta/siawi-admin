@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-newspaper"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Berita & Artikel</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui publikasi berita sekolah, cover thumbnail, dan konten</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/berita" class="text-primary font-weight-500">Data Berita</a></li>
          <li class="breadcrumb-item active">Edit Berita</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<style>
  /* Modern WYSIWYG Editor Styling */
  .note-editor.note-frame {
    border: 1px solid #d1d5db !important;
    border-radius: 10px !important;
    overflow: hidden !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
    background-color: #ffffff !important;
  }
  .note-editor.note-frame .note-toolbar {
    background-color: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 8px 10px !important;
  }
  .note-editor.note-frame .note-btn {
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #475569 !important;
    border-radius: 6px !important;
    padding: 5px 10px !important;
    font-size: 0.85rem !important;
    margin-right: 3px !important;
    margin-bottom: 2px !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
    transition: all 0.15s ease;
  }
  .note-editor.note-frame .note-btn:hover,
  .note-editor.note-frame .note-btn.active {
    background-color: #f1f5f9 !important;
    color: #1e293b !important;
    border-color: #cbd5e1 !important;
  }
  .note-editor.note-frame .note-editable {
    background-color: #ffffff !important;
    font-family: inherit !important;
    font-size: 0.95rem !important;
    color: #334155 !important;
    padding: 16px !important;
    min-height: 250px;
  }
  .note-editor.note-frame.focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
  }
  .note-editor .note-statusbar {
    background-color: #f8fafc !important;
    border-top: 1px solid #e2e8f0 !important;
  }
</style>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Berita
            </h5>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="/admin/berita/{{$edit->id_berita}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="form-group">
                <label for="judul_berita">Judul Berita</label>
                <input type="text" class="form-control" id="judul_berita" placeholder="Masukkan Judul Berita" name="judul_berita" value="{{$edit->judul_berita}}">
                @error('judul_berita')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="isi_berita">Isi Berita</label>
                <textarea class="form-control summernote" id="isi_berita" placeholder="Tulis isi berita..." name="isi_berita">{!! $edit->isi_berita !!}</textarea>
                @error('isi_berita')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="row">
                <div class="form-group col-md-6">
                  <label for="pembuat">Pembuat Berita</label>
                  <select class="form-control" name="pembuat" id="pembuat">
                  @foreach ($guru as $gru)
                    <option value="{{ $gru->id_guru }}" {{ $edit->id_guru == $gru->id_guru ? 'selected' : '' }}> {{ $gru->nama_guru }}</option>
                  @endforeach
                  </select>
                  @error('pembuat')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6">
                  <label for="tanggal">Tanggal Berita</label>
                  <input class="form-control" type="datetime-local" name="tanggal" id="tanggal" value="{{$carbonDate}}">
                  @error('tanggal')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-0">
                <label for="cover">Cover Berita</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="cover" id="cover">
                  <label class="custom-file-label" id="cover-label" for="cover">Pilih berkas cover...</label>
                  <script>
                    document.getElementById('cover').addEventListener('change', function(e) {
                        var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas cover...';
                        var label = document.getElementById('cover-label');
                        label.textContent = fileName;
                    });
                  </script>
                </div>
                @if($edit->cover)
                  <small class="text-muted d-block mt-2">
                    <i class="fas fa-image mr-1"></i> File saat ini: <strong>{{ $edit->cover }}</strong>
                  </small>
                @endif
                @error('cover')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/berita" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    if ($.fn.summernote) {
      $('#isi_berita').summernote({
        placeholder: 'Tulis isi berita atau artikel di sini...',
        tabsize: 2,
        height: 320,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['insert', ['picture', 'video', 'link']],
          ['para', ['ol', 'ul', 'paragraph']],
          ['misc', ['codeview', 'clear']]
        ]
      });
    }
  });
</script>
@endpush