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
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settingJamPelajaran">Setting Jam Pelajaran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settingSp">Setting SP</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settingWhatsapp">Setting WhatsApp</a>
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

                            <!-- Form Setting Jam Pelajaran -->
                            <div class="tab-pane fade" id="settingJamPelajaran">
                                <form action="/admin/setting/{{$setting->id}}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="col-md-6 mb-3">
                                                <div class="card p-3 border shadow-sm">
                                                    <h5 class="font-weight-bold">Jam Ke-{{ $i }}</h5>
                                                    <div class="row">
                                                        <div class="form-group col-6 mb-0">
                                                            <label for="jam_pelajaran_{{ $i }}_mulai">Waktu Mulai</label>
                                                            <input type="time" class="form-control" id="jam_pelajaran_{{ $i }}_mulai" name="jam_pelajaran[{{ $i }}][mulai]" value="{{ isset($setting->jam_pelajaran[$i]['mulai']) ? $setting->jam_pelajaran[$i]['mulai'] : '' }}">
                                                        </div>
                                                        <div class="form-group col-6 mb-0">
                                                            <label for="jam_pelajaran_{{ $i }}_selesai">Waktu Selesai</label>
                                                            <input type="time" class="form-control" id="jam_pelajaran_{{ $i }}_selesai" name="jam_pelajaran[{{ $i }}][selesai]" value="{{ isset($setting->jam_pelajaran[$i]['selesai']) ? $setting->jam_pelajaran[$i]['selesai'] : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Simpan Setting Jam Pelajaran</button>
                                </form>
                            </div>

                            <!-- Form Setting SP -->
                            <div class="tab-pane fade" id="settingSp">
                                <form action="/admin/setting/{{$setting->id}}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group col-md-6 px-0">
                                        <label for="jumlah_sp">Jumlah SP yang Ditampilkan</label>
                                        <select class="form-control" id="jumlah_sp" name="sp_settings[jumlah_sp]" onchange="renderSpInputs()">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ (isset($setting->sp_settings['jumlah_sp']) && $setting->sp_settings['jumlah_sp'] == $i) ? 'selected' : '' }}>{{ $i }} SP</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div id="sp_inputs_container" class="row">
                                        <!-- Kolom input dinamis akan dirender di sini via JavaScript -->
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Simpan Setting SP</button>
                                </form>
                            </div>

                            <!-- Form Setting WhatsApp -->
                            <div class="tab-pane fade" id="settingWhatsapp">
                                <form action="/admin/setting/{{$setting->id}}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="wa_settings" value="1">
                                    
                                    <div class="form-group">
                                        <label for="wa_status">Status Notifikasi WhatsApp</label>
                                        <select class="form-control" id="wa_status" name="wa_status">
                                            <option value="1" {{ $setting->wa_status ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ !$setting->wa_status ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="wa_api_url">OpenWA API URL</label>
                                        <input type="text" class="form-control" id="wa_api_url" name="wa_api_url" 
                                               value="{{ $setting->wa_api_url ?? env('OPEN_WA_API_URL', 'http://localhost:2785/api') }}" 
                                               placeholder="http://localhost:2785/api">
                                        <small class="form-text text-muted">URL Gateway OpenWA API.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="wa_api_key">OpenWA API Key</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="wa_api_key" name="wa_api_key" 
                                                   value="{{ $setting->wa_api_key ?? env('OPEN_WA_API_KEY') }}"
                                                   placeholder="Masukkan API Key">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="toggleApiKey">
                                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                         <label for="wa_session_id">OpenWA Session ID (UUID)</label>
                                         <input type="text" class="form-control" id="wa_session_id" name="wa_session_id" 
                                                value="{{ $setting->wa_session_id ?? env('OPEN_WA_SESSION_ID', 'default') }}"
                                                placeholder="79355abc-2aed-4d98-a394-0ea79ddf9f49">
                                     </div>

                                     <div class="form-group">
                                         <label for="wa_rate_limit">Batas Kirim Pesan (Pesan per Menit)</label>
                                         <input type="number" class="form-control" id="wa_rate_limit" name="wa_rate_limit" 
                                                value="{{ $setting->wa_rate_limit ?? 10 }}"
                                                min="1" placeholder="10">
                                         <small class="form-text text-muted">Batas maksimal pesan WhatsApp yang dikirim per menit untuk menghindari pemblokiran nomor.</small>
                                     </div>

                                     <button type="submit" class="btn btn-primary mb-3">Simpan Setting WhatsApp</button>
                                 </form>

                                 <hr class="my-4">
                                 
                                 <div class="card card-outline card-info shadow-sm">
                                     <div class="card-header">
                                         <h4 class="card-title font-weight-bold text-info">
                                             <i class="fab fa-whatsapp mr-1"></i> Status Koneksi WhatsApp Gateway
                                         </h4>
                                     </div>
                                     <div class="card-body text-center">
                                         <div id="wa_status_loading" class="py-3">
                                             <div class="spinner-border text-info" role="status">
                                                 <span class="sr-only">Loading...</span>
                                             </div>
                                             <p class="mt-2 text-muted">Mengecek status koneksi ke server...</p>
                                         </div>

                                         <div id="wa_status_wrapper" class="d-none">
                                             <div class="mb-3">
                                                 <span id="wa_status_badge" class="badge p-2 px-3 text-sm">Unknown</span>
                                             </div>
                                             
                                             <div id="wa_status_details" class="alert alert-light border p-3 rounded d-inline-block text-left mb-3" style="min-width: 300px;">
                                                 <p class="mb-1"><strong>Pesan:</strong> <span id="wa_status_message">-</span></p>
                                                 <p class="mb-0"><strong>Status Sesi:</strong> <code id="wa_status_raw_state">-</code></p>
                                             </div>
                                             
                                             <div id="wa_qr_container" class="my-4 d-none">
                                                 <p class="text-warning font-weight-bold mb-2">
                                                     <i class="fas fa-qrcode mr-1"></i> Scan QR Code berikut dengan WhatsApp Anda:
                                                 </p>
                                                 <div class="bg-white p-3 d-inline-block rounded border shadow-sm">
                                                     <img id="wa_qr_image" src="" alt="WhatsApp QR Code" class="img-fluid" style="width: 250px; height: 250px;">
                                                 </div>
                                                 <p class="small text-muted mt-2">QR Code akan diperbarui secara otomatis setiap beberapa detik.</p>
                                             </div>

                                             <div class="mt-2">
                                                 <button type="button" id="btn_check_wa" class="btn btn-outline-info mr-2">
                                                     <i class="fas fa-sync mr-1"></i> Cek Koneksi
                                                 </button>
                                                 <button type="button" id="btn_start_wa" class="btn btn-primary d-none">
                                                     <i class="fas fa-play mr-1"></i> Mulai Sesi Baru
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
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

    function renderSpInputs() {
        const jumlahSp = document.getElementById('jumlah_sp').value;
        const container = document.getElementById('sp_inputs_container');
        const existingRules = @json($setting->sp_settings['sp_rules'] ?? []);
        
        container.innerHTML = '';
        
        for (let i = 1; i <= jumlahSp; i++) {
            const val = existingRules[i] !== undefined ? existingRules[i] : (i * 25);
            const div = document.createElement('div');
            div.className = 'col-md-6 mb-3';
            div.innerHTML = `
                <div class="card p-3 border shadow-sm">
                    <h5 class="font-weight-bold">Surat Peringatan ${i} (SP ${i})</h5>
                    <div class="form-group mb-0">
                        <label for="sp_rules_${i}">Poin Minimal Pemicu</label>
                        <input type="number" class="form-control" id="sp_rules_${i}" name="sp_settings[sp_rules][${i}]" value="${val}" required min="1">
                    </div>
                </div>
            `;
            container.appendChild(div);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderSpInputs();
        
        // Toggle view/hide API Key
        const toggleBtn = document.getElementById('toggleApiKey');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const apiKeyInput = document.getElementById('wa_api_key');
                const icon = document.getElementById('toggleIcon');
                if (apiKeyInput.type === 'password') {
                    apiKeyInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    apiKeyInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        // Pemantau Sesi dan Status Koneksi WhatsApp AJAX
        const waStatusTab = document.querySelector('a[href="#settingWhatsapp"]');
        let waPollInterval = null;

        function checkWhatsAppStatus() {
            const loadingEl = document.getElementById('wa_status_loading');
            const wrapperEl = document.getElementById('wa_status_wrapper');
            const badgeEl = document.getElementById('wa_status_badge');
            const messageEl = document.getElementById('wa_status_message');
            const stateEl = document.getElementById('wa_status_raw_state');
            const qrContainer = document.getElementById('wa_qr_container');
            const qrImage = document.getElementById('wa_qr_image');
            const btnStart = document.getElementById('btn_start_wa');

            if (!loadingEl || !wrapperEl) return;

            fetch('/admin/setting-whatsapp-status')
                .then(response => response.json())
                .then(data => {
                    loadingEl.classList.add('d-none');
                    wrapperEl.classList.remove('d-none');

                    if (data.success) {
                        messageEl.textContent = data.message;
                        stateEl.textContent = data.status;

                        // Reset badge classes
                        badgeEl.className = 'badge p-2 px-3 text-sm';
                        
                        if (data.connected) {
                            badgeEl.classList.add('badge-success');
                            badgeEl.textContent = 'Terhubung';
                            qrContainer.classList.add('d-none');
                            btnStart.classList.add('d-none');
                            
                            // Hentikan polling jika sudah terhubung
                            if (waPollInterval) {
                                clearInterval(waPollInterval);
                                waPollInterval = null;
                            }
                        } else {
                            if (data.status === 'NOT_STARTED') {
                                badgeEl.classList.add('badge-danger');
                                badgeEl.textContent = 'Offline';
                                qrContainer.classList.add('d-none');
                                btnStart.classList.remove('d-none');
                                
                                if (waPollInterval) {
                                    clearInterval(waPollInterval);
                                    waPollInterval = null;
                                }
                            } else {
                                badgeEl.classList.add('badge-warning');
                                badgeEl.textContent = 'Butuh Scan QR';
                                btnStart.classList.add('d-none');

                                if (data.qrCode) {
                                    qrContainer.classList.remove('d-none');
                                    const qrSrc = data.qrCode.startsWith('data:') ? data.qrCode : 'data:image/png;base64,' + data.qrCode;
                                    qrImage.src = qrSrc;
                                } else {
                                    qrContainer.classList.add('d-none');
                                }

                                // Jalankan polling jika statusnya butuh scan QR
                                if (!waPollInterval) {
                                    waPollInterval = setInterval(checkWhatsAppStatus, 5000);
                                }
                            }
                        }
                    } else {
                        badgeEl.className = 'badge p-2 px-3 text-sm badge-danger';
                        badgeEl.textContent = 'Error Gateway';
                        messageEl.textContent = data.message;
                        stateEl.textContent = 'ERROR';
                        qrContainer.classList.add('d-none');
                        btnStart.classList.add('d-none');
                        
                        if (waPollInterval) {
                            clearInterval(waPollInterval);
                            waPollInterval = null;
                        }
                    }
                })
                .catch(error => {
                    loadingEl.classList.add('d-none');
                    wrapperEl.classList.remove('d-none');
                    badgeEl.className = 'badge p-2 px-3 text-sm badge-danger';
                    badgeEl.textContent = 'Server Error';
                    messageEl.textContent = 'Gagal menghubungi server aplikasi.';
                    stateEl.textContent = 'SERVER_ERROR';
                    qrContainer.classList.add('d-none');
                    btnStart.classList.add('d-none');
                    
                    if (waPollInterval) {
                        clearInterval(waPollInterval);
                        waPollInterval = null;
                    }
                });
        }

        // Cek status saat tab WhatsApp diklik (dukungan jQuery dan Vanilla JS)
        if (typeof $ !== 'undefined') {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if (e.target.hash === '#settingWhatsapp') {
                    const loading = document.getElementById('wa_status_loading');
                    const wrapper = document.getElementById('wa_status_wrapper');
                    if (loading && wrapper) {
                        loading.classList.remove('d-none');
                        wrapper.classList.add('d-none');
                    }
                    checkWhatsAppStatus();
                }
            });
            $('a[data-toggle="tab"]').on('hidden.bs.tab', function (e) {
                if (e.target.hash === '#settingWhatsapp' && waPollInterval) {
                    clearInterval(waPollInterval);
                    waPollInterval = null;
                }
            });
        } else if (waStatusTab) {
            waStatusTab.addEventListener('shown.bs.tab', function () {
                const loading = document.getElementById('wa_status_loading');
                const wrapper = document.getElementById('wa_status_wrapper');
                if (loading && wrapper) {
                    loading.classList.remove('d-none');
                    wrapper.classList.add('d-none');
                }
                checkWhatsAppStatus();
            });
        }

        // Cek status manual dengan tombol
        const btnCheck = document.getElementById('btn_check_wa');
        if (btnCheck) {
            btnCheck.addEventListener('click', function() {
                const loading = document.getElementById('wa_status_loading');
                const wrapper = document.getElementById('wa_status_wrapper');
                if (loading && wrapper) {
                    loading.classList.remove('d-none');
                    wrapper.classList.add('d-none');
                }
                checkWhatsAppStatus();
            });
        }

        // Mulai sesi baru via tombol
        const btnStartSession = document.getElementById('btn_start_wa');
        if (btnStartSession) {
            btnStartSession.addEventListener('click', function() {
                btnStartSession.disabled = true;
                btnStartSession.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Memproses Sesi...';

                fetch('/admin/setting-whatsapp-start', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    btnStartSession.disabled = false;
                    btnStartSession.innerHTML = '<i class="fas fa-play mr-1"></i> Mulai Sesi Baru';
                    checkWhatsAppStatus();
                })
                .catch(error => {
                    alert('Gagal menghubungi server untuk memulai sesi.');
                    btnStartSession.disabled = false;
                    btnStartSession.innerHTML = '<i class="fas fa-play mr-1"></i> Mulai Sesi Baru';
                });
            });
        }
    });
</script>

@endsection
