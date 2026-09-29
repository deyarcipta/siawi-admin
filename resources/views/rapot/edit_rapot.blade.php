@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-6 d-flex align-items-center">
        <i class="fas fa-edit text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Edit E-Rapot Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.85rem;">Perbarui data nilai dan berkas rapot siswa</p>
        </div>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/rapot">Data Rapot</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
              <i class="fas fa-file-alt text-primary mr-2"></i> Edit Berkas Rapot: 
              <span class="text-primary">{{ $edit->siswa->nama_siswa ?? 'Siswa' }}</span>
            </h3>
            <div class="ml-auto">
              <a href="/admin/rapot/{{ $edit->id_siswa }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
            </div>
          </div>
          
          <form action="/admin/rapot/{{$edit->id_rapot}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body p-4">
              <div class="row">
                <div class="form-group col-md-6 mb-3">
                  <label for="nama_siswa" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Siswa</label>
                  <input type="text" class="form-control" name="nama_siswa" id="nama_siswa" value="{{ $edit->siswa->nama_siswa }}" disabled style="border-radius: 8px; height: 42px; background: #f1f5f9;">
                  <input type="hidden" name="id_siswa" id="id_siswa" value="{{ $edit->id_siswa }}">
                  <input type="hidden" name="id_kelas" id="id_kelas" value="{{ $edit->id_kelas }}">
                </div>

                <div class="form-group col-md-3 mb-3">
                  <label for="semester" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Semester <span class="text-danger">*</span></label>
                  <select class="form-control" name="semester" id="semester" required style="border-radius: 8px; height: 42px;">
                    <option value="">-- Pilih Semester --</option>
                    <option value="1" {{ $edit->semester == 1 ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ $edit->semester == 2 ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    <option value="3" {{ $edit->semester == 3 ? 'selected' : '' }}>Semester 3 (Ganjil)</option>
                    <option value="4" {{ $edit->semester == 4 ? 'selected' : '' }}>Semester 4 (Genap)</option>
                    <option value="5" {{ $edit->semester == 5 ? 'selected' : '' }}>Semester 5 (Ganjil)</option>
                    <option value="6" {{ $edit->semester == 6 ? 'selected' : '' }}>Semester 6 (Genap)</option>
                  </select>
                  @error('semester')
                    <small class="text-danger font-weight-500">{{ $message }}</small>
                  @enderror
                </div>

                <div class="form-group col-md-3 mb-3">
                  <label for="rata_rata" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nilai Rata-Rata <span class="text-danger">*</span></label>
                  <input class="form-control" type="number" step="0.01" name="rata_rata" id="rata_rata" value="{{ old('rata_rata', $edit->rata_rata) }}" placeholder="Contoh: 85.50" required style="border-radius: 8px; height: 42px;">
                  @error('rata_rata')
                    <small class="text-danger font-weight-500">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-0">
                <label for="file_rapot" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Ganti File Rapot (PDF)</label>
                <input type="file" name="file_rapot" id="file_rapot" class="form-control-file p-2 border" accept=".pdf" style="border-radius: 8px; background: #f8fafc;">
                @if($edit->file_rapot)
                  <small class="text-muted mt-2 d-block">
                    <i class="fas fa-file-pdf text-danger mr-1"></i> File saat ini: 
                    <a href="{{ asset('storage/file_rapot/' . $edit->file_rapot) }}" target="_blank" class="text-primary font-weight-bold">{{ $edit->file_rapot }}</a>
                    (Kosongkan jika tidak ingin mengubah file)
                  </small>
                @endif
                @error('file_rapot')
                  <small class="text-danger font-weight-500">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light py-3 d-flex align-items-center">
              <a href="/admin/rapot/{{ $edit->id_siswa }}" class="btn btn-secondary px-3" style="border-radius: 8px;">Batal</a>
              <button type="submit" class="btn btn-primary ml-auto px-4" style="border-radius: 8px;"><i class="fas fa-save mr-1"></i> Perbarui Rapot</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection