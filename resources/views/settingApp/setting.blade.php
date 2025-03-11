@extends($layout)
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Setting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Setting</a></li>
                    <li class="breadcrumb-item active">Setting</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Setting</h3>
                    </div>
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="settingTabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#settingDasar">Setting Dasar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settingAplikasi">Setting Aplikasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settingVersiAplikasi">Versi Aplikasi</a>
                        </li>
                    </ul>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Form Setting Dasar -->
                            <div class="tab-pane fade show active" id="settingDasar">
                                <form action="/admin/setting/{{$setting->id}}" method="POST" enctype="multipart/form-data">
                                  @method('PUT')
                                  @csrf
                                    <div class="form-group">
                                      <label for="nama_app">Nama Aplikasi</label>
                                      <input type="text" class="form-control" id="nama_app" placeholder="Enter Nama Aplikasi" name="nama_app" value="{{$setting->nama_app}}">
                                      @error('nama_app')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                      @enderror
                                    </div>
                                    <div class="form-group">
                                      <label for="nama_sekolah">Nama Sekolah</label>
                                      <input type="text" class="form-control" id="nama_sekolah" placeholder="Enter Nama Sekolah" name="nama_sekolah" value="{{$setting->nama_sekolah}}">
                                      @error('nama_sekolah')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                      @enderror
                                    </div>
                                    <div class="row">
                                      <div class="form-group col-6">
                                        <label for="nama_kepsek">Nama Kepsek</label>
                                        <input type="text" class="form-control" id="nama_kepsek" placeholder="Enter Nama Kepsek" name="nama_kepsek" value="{{$setting->nama_kepsek}}">
                                        @error('nama_kepsek')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                      <div class="form-group col-6">
                                        <label for="nip_kepsek">Nip Kepsek</label>
                                        <input type="text" class="form-control" id="nip_kepsek" placeholder="Enter Nip Kepsek" name="nip_kepsek" value="{{$setting->nip_kepsek}}">
                                        @error('nip_kepsek')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label for="alamat">Alamat Sekolah</label>
                                      <textarea type="text" class="form-control" id="alamat" placeholder="Enter Alamat Sekolah" name="alamat" value="{{$setting->alamat}}">{{$setting->alamat}}</textarea>
                                      @error('alamat')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                      @enderror
                                    </div>
                                    <div class="row">
                                      <div class="form-group col-6">
                                        <label for="kel">Kelurahaan</label>
                                        <input type="text" class="form-control" id="kel" placeholder="Enter Kelurahaan" name="kel" value="{{$setting->kel}}">
                                        @error('kel')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                      <div class="form-group col-6">
                                        <label for="kec">Kecamatan</label>
                                        <input type="text" class="form-control" id="kec" placeholder="Enter Kecamatan" name="kec" value="{{$setting->kec}}">
                                        @error('kec')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="form-group col-6">
                                        <label for="kota">Kota</label>
                                        <input type="text" class="form-control" id="kota" placeholder="Enter Kota" name="kota" value="{{$setting->kota}}">
                                        @error('kota')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                      <div class="form-group col-6">
                                        <label for="prov">Provinsi</label>
                                        <input type="text" class="form-control" id="prov" placeholder="Enter Provinsi" name="prov" value="{{$setting->prov}}">
                                        @error('prov')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="form-group col-6">
                                        <label for="logo">Logo</label>
                                        <div class="input-group">
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="logo" id="logo">
                                            <label class="custom-file-label" id="logo-label" for="logo">Choose file</label>
                                            <script>
                                              document.getElementById('logo').addEventListener('change', function(e) {
                                                  var fileName = e.target.files[0].name;
                                                  var label = document.getElementById('logo-label');
                                                  label.textContent = fileName;
                                              });
                                          </script>
                                          </div>
                                        @error('logo')
                                          <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                      </div>
                                    </div>
                                    <div class="col-6 form-group">
                                      <img src="{{ asset("storage/gambar/$setting->logo") }}" alt="Logo Sekolah" width="90px">
                                    </div>
                                  </div>
                                    <button type="submit" class="btn btn-primary">Simpan Settingan Dasar</button>
                                </form>
                            </div>

                            <!-- Form Setting Aplikasi -->
                            <div class="tab-pane fade" id="settingAplikasi">
                                <form action="/admin/setting/{{$setting->id}}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <label for="nama_app">Nama Aplikasi</label>
                                        <input type="text" class="form-control" id="nama_app" name="nama_app" value="{{$setting->nama_app}}">
                                        @error('nama_app')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="primary_color">Primary Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="primary_color" name="primary_color" 
                                                  value="{{ '#' . substr($setting->primary_color, 4) }}" 
                                                  onchange="updateColorPicker('primary_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_primary_color" 
                                                  name="primary_color" value="{{ '#' . substr($setting->primary_color, 4) }}" 
                                                  oninput="updateTextInput('primary_color')">
                                        </div>
                                        @error('primary_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="secondary_color">Secondary Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="secondary_color" name="secondary_color" 
                                                  value="{{ '#' . substr($setting->secondary_color, 4) }}" 
                                                  onchange="updateColorPicker('secondary_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_secondary_color" 
                                                  name="secondary_color" value="{{ '#' . substr($setting->secondary_color, 4) }}" 
                                                  oninput="updateTextInput('secondary_color')">
                                        </div>
                                        @error('secondary_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="third_color">Third Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="third_color" name="third_color" 
                                                  value="{{ '#' . substr($setting->third_color, 4) }}" 
                                                  onchange="updateColorPicker('third_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_third_color" 
                                                  name="third_color" value="{{ '#' . substr($setting->third_color, 4) }}" 
                                                  oninput="updateTextInput('third_color')">
                                        </div>
                                        @error('third_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="four_color">Four Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="four_color" name="four_color" 
                                                  value="{{ '#' . substr($setting->four_color, 4) }}" 
                                                  onchange="updateColorPicker('four_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_four_color" 
                                                  name="four_color" value="{{ '#' . substr($setting->four_color, 4) }}" 
                                                  oninput="updateTextInput('four_color')">
                                        </div>
                                        @error('four_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="five_color">Five Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="five_color" name="five_color" 
                                                  value="{{ '#' . substr($setting->five_color, 4) }}" 
                                                  onchange="updateColorPicker('five_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_five_color" 
                                                  name="five_color" value="{{ '#' . substr($setting->five_color, 4) }}" 
                                                  oninput="updateTextInput('five_color')">
                                        </div>
                                        @error('five_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="six_color">Six Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="six_color" name="six_color" 
                                                  value="{{ '#' . substr($setting->six_color, 4) }}" 
                                                  onchange="updateColorPicker('six_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_six_color" 
                                                  name="six_color" value="{{ '#' . substr($setting->six_color, 4) }}" 
                                                  oninput="updateTextInput('six_color')">
                                        </div>
                                        @error('six_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="text_color">Text Color</label>
                                        <div class="input-group">
                                            <!-- Input untuk memilih warna -->
                                            <input type="color" class="form-control" id="text_color" name="text_color" 
                                                  value="{{ '#' . substr($setting->text_color, 4) }}" 
                                                  onchange="updateColorPicker('text_color')">
                                            
                                            <!-- Input untuk memasukkan kode warna -->
                                            <input type="text" class="form-control ml-2" id="kode_warna_text_color" 
                                                  name="text_color" value="{{ '#' . substr($setting->text_color, 4) }}" 
                                                  oninput="updateTextInput('text_color')">
                                        </div>
                                        @error('text_color')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit"  class="btn btn-primary">Simpan Setting Aplikasi</button>
                                </form>
                            </div>
                            <!-- Form Setting Version Aplikasi -->
                            <div class="tab-pane fade" id="settingVersiAplikasi">
                                <form action="/admin/setting/{{$appVersi->id_versi}}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <label for="versi_app">Versi Aplikasi</label>
                                        <input type="text" class="form-control" id="versi_app" name="versi_app" value="{{$appVersi->versi}}">
                                        @error('versi_app')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="link_app">Link Aplikasi</label>
                                        <input type="text" class="form-control" id="link_app" name="link_app" value="{{$appVersi->download_url}}">
                                        @error('link_app')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Versi Aplikasi</button>
                                </form>
                            </div>
                        </div>
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</div>

<script>
    function updateColorPicker(colorId) {
        let colorPicker = document.getElementById(colorId);
        let colorCode = document.getElementById('kode_warna_' + colorId);
        colorCode.value = colorPicker.value.toUpperCase(); // Update input teks dengan warna yang dipilih
    }

    function updateTextInput(colorId) {
        let colorPicker = document.getElementById(colorId);
        let colorCode = document.getElementById('kode_warna_' + colorId);
        
        // Pastikan format yang dimasukkan valid (#RRGGBB)
        let colorValue = colorCode.value.trim();
        if (/^#([0-9A-F]{3}){1,2}$/i.test(colorValue)) {
            colorPicker.value = colorValue; // Update color picker dengan warna yang diketik
        }
    }
</script>

@endsection
