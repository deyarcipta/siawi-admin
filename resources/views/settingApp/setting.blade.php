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
                                            <option value="0" {{ $setting->wa_status == 0 ? 'selected' : '' }}>Nonaktif</option>
                                            <option value="1" {{ $setting->wa_status == 1 ? 'selected' : '' }}>Aktif untuk Keduanya (Guru & Siswa)</option>
                                            <option value="2" {{ $setting->wa_status == 2 ? 'selected' : '' }}>Aktif untuk Guru Saja</option>
                                            <option value="3" {{ $setting->wa_status == 3 ? 'selected' : '' }}>Aktif untuk Siswa Saja</option>
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
                                        <label for="wa_load_balancing">Metode Pengiriman Sesi</label>
                                        <select class="form-control" id="wa_load_balancing" name="wa_load_balancing">
                                            <option value="1" {{ ($setting->wa_load_balancing ?? 1) == 1 ? 'selected' : '' }}>Load Balancing (Gunakan Semua Sesi Aktif secara Acak)</option>
                                            <option value="0" {{ ($setting->wa_load_balancing ?? 1) == 0 ? 'selected' : '' }}>Single Session (Gunakan Sesi Utama Saja)</option>
                                        </select>
                                        <small class="form-text text-muted">Jika menggunakan Single Session, pesan hanya akan dikirim melalui Sesi Utama pilihan Anda.</small>
                                    </div>

                                    <div class="form-group" id="primary_session_container" style="display: none;">
                                        <label for="wa_session_id">Sesi Utama (Single Session)</label>
                                        <select class="form-control" id="wa_session_id" name="wa_session_id">
                                            <option value="">-- Pilih Sesi Utama --</option>
                                            @foreach($waSessions as $s)
                                                <option value="{{ $s->session_id }}" {{ ($setting->wa_session_id == $s->session_id) ? 'selected' : '' }}>
                                                    {{ $s->label }} ({{ $s->phone_number ? '+' . $s->phone_number : 'Belum Terhubung' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Pilih nomor gateway utama yang akan digunakan untuk mengirim seluruh notifikasi WA.</small>
                                    </div>

                                      <button type="submit" class="btn btn-primary mb-3">Simpan Setting WhatsApp</button>
                                  </form>

                                  <hr class="my-4">
                                  
                                  <!-- Widget Status Server Gateway -->
                                  <div class="card card-outline card-primary shadow-sm mb-4">
                                      <div class="card-header py-2">
                                          <h4 class="card-title font-weight-bold text-primary mb-0">
                                              <i class="fas fa-server mr-1"></i> Status & Kesehatan Server Gateway
                                          </h4>
                                      </div>
                                      <div class="card-body py-3">
                                          <div class="row align-items-center text-center text-md-left">
                                              <div class="col-md-3 mb-2 mb-md-0 border-right">
                                                  <div class="text-xs text-muted font-weight-bold text-uppercase">Koneksi Server</div>
                                                  <div class="mt-1 d-flex align-items-center justify-content-center justify-content-md-start">
                                                      <span id="server_status_badge" class="badge badge-secondary p-2 text-sm">Memeriksa...</span>
                                                  </div>
                                              </div>
                                              <div class="col-md-2 mb-2 mb-md-0 border-right">
                                                  <div class="text-xs text-muted font-weight-bold text-uppercase">Latensi (Ping)</div>
                                                  <div class="mt-1 font-weight-bold text-lg" id="server_latency_val">-</div>
                                              </div>
                                              <div class="col-md-2 mb-2 mb-md-0 border-right">
                                                  <div class="text-xs text-muted font-weight-bold text-uppercase">Penggunaan RAM</div>
                                                  <div class="mt-1 font-weight-bold text-lg text-info" id="server_ram_val">-</div>
                                              </div>
                                              <div class="col-md-2 mb-2 mb-md-0 border-right">
                                                  <div class="text-xs text-muted font-weight-bold text-uppercase">Engine</div>
                                                  <div class="mt-1 font-weight-bold text-lg text-secondary" id="server_engine_val">-</div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="text-xs text-muted font-weight-bold text-uppercase">Versi Server</div>
                                                  <div class="mt-1 font-weight-bold text-sm text-truncate" id="server_version_val" title="-">-</div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="card card-outline card-info shadow-sm">
                                      <div class="card-header d-flex align-items-center justify-content-between py-2">
                                          <h4 class="card-title font-weight-bold text-info mb-0">
                                              <i class="fab fa-whatsapp mr-1"></i> Sesi & Nomor WhatsApp Gateway (Multi-Session)
                                          </h4>
                                          <button type="button" class="btn btn-sm btn-info ml-auto" data-toggle="modal" data-target="#modalAddWaSession">
                                              <i class="fas fa-plus mr-1"></i> Tambah Nomor Baru
                                          </button>
                                      </div>
                                      <div class="card-body">
                                          @if($waSessions->isEmpty())
                                              <div class="alert alert-light border text-center py-4 mb-0">
                                                  <p class="text-muted mb-0">Belum ada nomor WhatsApp yang ditambahkan. Silakan klik tombol <strong>Tambah Nomor Baru</strong> di atas.</p>
                                              </div>
                                          @else
                                              <div class="row" id="wa_sessions_container">
                                                  @foreach($waSessions as $waSession)
                                                      <div class="col-md-6 mb-4 session-card-wrapper" data-id="{{ $waSession->id }}">
                                                          <div class="card h-100 border shadow-sm mb-0">
                                                              <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
                                                                  <strong class="text-secondary">{{ $waSession->label }}</strong>
                                                                  <div class="custom-control custom-switch ml-auto mr-3">
                                                                      <input type="checkbox" class="custom-control-input toggle-session-switch" 
                                                                             id="toggle_switch_{{ $waSession->id }}" data-id="{{ $waSession->id }}"
                                                                             {{ $waSession->is_active ? 'checked' : '' }}>
                                                                      <label class="custom-control-label small text-muted font-weight-normal" for="toggle_switch_{{ $waSession->id }}">Aktif</label>
                                                                  </div>
                                                                  <button type="button" class="btn btn-xs btn-outline-danger delete-session-btn" data-id="{{ $waSession->id }}" title="Hapus Sesi">
                                                                      <i class="fas fa-trash"></i>
                                                                  </button>
                                                              </div>
                                                              <div class="card-body text-center p-3">
                                                                  <div id="loading_{{ $waSession->id }}" class="py-3">
                                                                      <div class="spinner-border text-info spinner-border-sm" role="status">
                                                                          <span class="sr-only">Loading...</span>
                                                                      </div>
                                                                      <span class="ml-2 text-muted text-sm">Memeriksa status...</span>
                                                                  </div>

                                                                  <div id="content_{{ $waSession->id }}" class="d-none">
                                                                      <div class="mb-2">
                                                                          <span id="badge_{{ $waSession->id }}" class="badge p-2 px-3 text-sm">Unknown</span>
                                                                      </div>
                                                                      <div class="small text-muted mb-2">
                                                                          <div><strong>Nomor:</strong> <span id="phone_{{ $waSession->id }}">{{ $waSession->phone_number ?? '-' }}</span></div>
                                                                          <div><strong>Status Sesi:</strong> <code id="raw_state_{{ $waSession->id }}">-</code></div>
                                                                      </div>

                                                                      <div id="qr_container_{{ $waSession->id }}" class="my-3 d-none">
                                                                          <p class="text-warning font-weight-bold mb-2 text-xs">
                                                                              <i class="fas fa-qrcode mr-1"></i> Scan QR Code berikut dengan WhatsApp Anda:
                                                                          </p>
                                                                          <div class="bg-white p-2 d-inline-block rounded border shadow-sm">
                                                                              <img id="qr_image_{{ $waSession->id }}" src="" alt="WhatsApp QR Code" class="img-fluid" style="width: 180px; height: 180px;">
                                                                          </div>
                                                                      </div>

                                                                      <div class="mt-2">
                                                                          <button type="button" class="btn btn-xs btn-outline-info check-session-btn mr-1" data-id="{{ $waSession->id }}">
                                                                              <i class="fas fa-sync mr-1"></i> Cek Koneksi
                                                                          </button>
                                                                          <button type="button" class="btn btn-xs btn-primary start-session-btn d-none" id="btn_start_{{ $waSession->id }}" data-id="{{ $waSession->id }}">
                                                                              <i class="fas fa-play mr-1"></i> Mulai Sesi
                                                                          </button>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  @endforeach
                                              </div>
                                          @endif
                                      </div>
                                  </div>

                                  <!-- Modal Tambah Sesi -->
                                  <div class="modal fade" id="modalAddWaSession" tabindex="-1" role="dialog" aria-labelledby="modalAddWaSessionLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content text-left">
                                              <div class="modal-header">
                                                  <h5 class="modal-title font-weight-bold" id="modalAddWaSessionLabel">Tambah Sesi WhatsApp Baru</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <form id="form_add_wa_session">
                                                  @csrf
                                                  <div class="modal-body">
                                                      <div class="form-group">
                                                          <label for="new_session_label">Label Pengenal Nomor / Sesi</label>
                                                          <input type="text" class="form-control" id="new_session_label" name="label" required 
                                                                 placeholder="Contoh: Nomor Utama Sekolah, Nomor Cadangan 1">
                                                          <small class="form-text text-muted">Label ini membantu Anda mengidentifikasi nomor WhatsApp yang terhubung.</small>
                                                      </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                      <button type="submit" class="btn btn-primary" id="btn_submit_add_session">
                                                          <i class="fas fa-save mr-1"></i> Simpan Sesi
                                                      </button>
                                                  </div>
                                              </form>
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

        // Toggle visibilitas Sesi Utama berdasarkan Load Balancing
        const loadBalancingSelect = document.getElementById('wa_load_balancing');
        const primarySessionContainer = document.getElementById('primary_session_container');
        if (loadBalancingSelect && primarySessionContainer) {
            function togglePrimarySessionVisibility() {
                if (loadBalancingSelect.value === '0') {
                    primarySessionContainer.style.display = 'block';
                } else {
                    primarySessionContainer.style.display = 'none';
                }
            }
            loadBalancingSelect.addEventListener('change', togglePrimarySessionVisibility);
            togglePrimarySessionVisibility(); // Jalankan sekali saat load
        }

        // Pemantau Sesi dan Status Koneksi WhatsApp AJAX (Multi-Session)
        const waStatusTab = document.querySelector('a[href="#settingWhatsapp"]');
        const activePolls = {}; // Menyimpan interval ID polling per sesi

        function checkSessionStatus(sessionId, forceShowLoading = false) {
            const loadingEl = document.getElementById(`loading_${sessionId}`);
            const contentEl = document.getElementById(`content_${sessionId}`);
            const badgeEl = document.getElementById(`badge_${sessionId}`);
            const phoneEl = document.getElementById(`phone_${sessionId}`);
            const stateEl = document.getElementById(`raw_state_${sessionId}`);
            const qrContainer = document.getElementById(`qr_container_${sessionId}`);
            const qrImage = document.getElementById(`qr_image_${sessionId}`);
            const btnStart = document.getElementById(`btn_start_${sessionId}`);

            if (!loadingEl || !contentEl) return;

            if (forceShowLoading) {
                loadingEl.classList.remove('d-none');
                contentEl.classList.add('d-none');
            }

            fetch(`/admin/whatsapp-sessions/${sessionId}/status`)
                .then(response => response.json())
                .then(data => {
                    loadingEl.classList.add('d-none');
                    contentEl.classList.remove('d-none');

                    if (data.success) {
                        stateEl.textContent = data.status;
                        phoneEl.textContent = data.phone_number || '-';

                        // Reset badge classes
                        badgeEl.className = 'badge p-2 px-3 text-sm';
                        
                        if (data.connected) {
                            badgeEl.classList.add('badge-success');
                            badgeEl.textContent = 'Terhubung';
                            qrContainer.classList.add('d-none');
                            btnStart.classList.add('d-none');
                            
                            // Hentikan polling jika sudah terhubung
                            stopPolling(sessionId);
                        } else {
                            if (data.status === 'NOT_STARTED') {
                                badgeEl.classList.add('badge-danger');
                                badgeEl.textContent = 'Offline';
                                qrContainer.classList.add('d-none');
                                btnStart.classList.remove('d-none');
                                stopPolling(sessionId);
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

                                // Jalankan polling untuk mendapatkan update QR Code atau status koneksi terbaru
                                startPolling(sessionId);
                            }
                        }
                    } else {
                        badgeEl.className = 'badge p-2 px-3 text-sm badge-danger';
                        badgeEl.textContent = 'Error Gateway';
                        stateEl.textContent = 'ERROR';
                        qrContainer.classList.add('d-none');
                        btnStart.classList.add('d-none');
                        stopPolling(sessionId);
                    }
                })
                .catch(error => {
                    loadingEl.classList.add('d-none');
                    contentEl.classList.remove('d-none');
                    badgeEl.className = 'badge p-2 px-3 text-sm badge-danger';
                    badgeEl.textContent = 'Server Error';
                    stateEl.textContent = 'SERVER_ERROR';
                    qrContainer.classList.add('d-none');
                    btnStart.classList.add('d-none');
                    stopPolling(sessionId);
                });
        }

        function startPolling(sessionId) {
            if (!activePolls[sessionId]) {
                activePolls[sessionId] = setInterval(() => checkSessionStatus(sessionId), 5000);
            }
        }

        function stopPolling(sessionId) {
            if (activePolls[sessionId]) {
                clearInterval(activePolls[sessionId]);
                delete activePolls[sessionId];
            }
        }

        // Cek status server gateway
        function checkWhatsAppServerStatus() {
            const badgeEl = document.getElementById('server_status_badge');
            const latencyEl = document.getElementById('server_latency_val');
            const ramEl = document.getElementById('server_ram_val');
            const engineEl = document.getElementById('server_engine_val');
            const versionEl = document.getElementById('server_version_val');

            if (!badgeEl) return;

            // Reset ke keadaan memeriksa
            badgeEl.className = 'badge badge-secondary p-2 text-sm';
            badgeEl.textContent = 'Memeriksa...';

            fetch('/admin/whatsapp-server/status')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.connected) {
                        badgeEl.className = 'badge badge-success p-2 text-sm';
                        badgeEl.textContent = 'Terhubung';
                        latencyEl.textContent = data.latency;
                        ramEl.textContent = data.ram;
                        engineEl.textContent = data.engine;
                        versionEl.textContent = data.version;
                        versionEl.setAttribute('title', data.version);
                    } else {
                        badgeEl.className = 'badge badge-danger p-2 text-sm';
                        badgeEl.textContent = 'Terputus (Offline)';
                        latencyEl.textContent = 'Offline';
                        ramEl.textContent = 'N/A';
                        engineEl.textContent = 'N/A';
                        versionEl.textContent = 'N/A';
                        versionEl.setAttribute('title', 'Offline');
                    }
                })
                .catch(error => {
                    badgeEl.className = 'badge badge-danger p-2 text-sm';
                    badgeEl.textContent = 'Error Koneksi';
                    latencyEl.textContent = 'Error';
                    ramEl.textContent = 'N/A';
                    engineEl.textContent = 'N/A';
                    versionEl.textContent = 'N/A';
                    versionEl.setAttribute('title', 'Error');
                });
        }

        // Cek semua sesi
        function checkAllSessions() {
            checkWhatsAppServerStatus();
            document.querySelectorAll('.session-card-wrapper').forEach(card => {
                const sessionId = card.getAttribute('data-id');
                checkSessionStatus(sessionId);
            });
        }

        // Hentikan semua polling
        function stopAllPolls() {
            Object.keys(activePolls).forEach(sessionId => {
                stopPolling(sessionId);
            });
        }

        // Cek status saat tab WhatsApp diklik (dukungan jQuery dan Vanilla JS)
        if (typeof $ !== 'undefined') {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if (e.target.hash === '#settingWhatsapp') {
                    checkAllSessions();
                }
            });
            $('a[data-toggle="tab"]').on('hidden.bs.tab', function (e) {
                if (e.target.hash === '#settingWhatsapp') {
                    stopAllPolls();
                }
            });
        } else if (waStatusTab) {
            waStatusTab.addEventListener('shown.bs.tab', function () {
                checkAllSessions();
            });
        }

        // Cek status manual dengan tombol
        $(document).on('click', '.check-session-btn', function() {
            const sessionId = $(this).data('id');
            checkSessionStatus(sessionId, true);
        });

        // Mulai sesi baru via tombol
        $(document).on('click', '.start-session-btn', function() {
            const sessionId = $(this).data('id');
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Memproses...');

            fetch(`/admin/whatsapp-sessions/${sessionId}/start`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                btn.prop('disabled', false).html('<i class="fas fa-play mr-1"></i> Mulai Sesi');
                checkSessionStatus(sessionId, true);
            })
            .catch(error => {
                alert('Gagal menghubungi server untuk memulai sesi.');
                btn.prop('disabled', false).html('<i class="fas fa-play mr-1"></i> Mulai Sesi');
            });
        });

        // Mengubah status aktif sesi dengan toggle switch
        $(document).on('change', '.toggle-session-switch', function() {
            const sessionId = $(this).data('id');
            const switchEl = $(this);

            fetch(`/admin/whatsapp-sessions/${sessionId}/toggle`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    switchEl.prop('checked', !switchEl.prop('checked'));
                    alert('Gagal mengubah status sesi.');
                }
            })
            .catch(error => {
                switchEl.prop('checked', !switchEl.prop('checked'));
                alert('Gagal menghubungi server.');
            });
        });

        // Menghapus sesi
        $(document).on('click', '.delete-session-btn', function() {
            const sessionId = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus sesi WhatsApp ini? Sesi juga akan ditutup dari server OpenWA.')) {
                fetch(`/admin/whatsapp-sessions/${sessionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        stopPolling(sessionId);
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal menghapus sesi.');
                    }
                })
                .catch(error => {
                    alert('Gagal menghubungi server.');
                });
            }
        });

        // Submit form tambah sesi baru
        const formAddSession = document.getElementById('form_add_wa_session');
        if (formAddSession) {
            formAddSession.addEventListener('submit', function(e) {
                e.preventDefault();
                const btnSubmit = document.getElementById('btn_submit_add_session');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...';

                const formData = new FormData(formAddSession);

                fetch('/admin/whatsapp-sessions', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Sesi';
                    if (data.success) {
                        $('#modalAddWaSession').modal('hide');
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal menambahkan sesi.');
                    }
                })
                .catch(error => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Sesi';
                    alert('Gagal menghubungi server.');
                });
            });
        }

        // Cek status semua sesi secara otomatis saat halaman termuat jika tab aktif
        if (window.location.hash === '#settingWhatsapp' || (waStatusTab && waStatusTab.classList.contains('active'))) {
            checkAllSessions();
        }
    });
</script>

@endsection
