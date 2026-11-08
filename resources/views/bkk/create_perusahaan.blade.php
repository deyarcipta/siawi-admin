@extends($layout)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<style>
  .leaflet-container {
    font-family: inherit;
    width: 100%;
    height: 100%;
  }
  .form-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
  }
  .card-action-bar::after,
  .card-action-bar .card-body::after {
    display: none !important;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.4rem;">
          <i class="fas fa-plus-circle text-primary mr-2"></i> Tambah Mitra Perusahaan
        </h1>
        <small class="text-muted">Daftarkan mitra DU/DI baru beserta titik radius presensi siswa PKL</small>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0" style="font-size: 0.85rem;">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.perusahaan.index') }}">Mitra Perusahaan</a></li>
          <li class="breadcrumb-item active">Tambah Mitra</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
        <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Mohon periksa kembali form berikut:</div>
        <ul class="mb-0 pl-3 small">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="{{ route('admin.perusahaan.store') }}" method="POST" enctype="multipart/form-data" id="formCreatePerusahaan">
      @csrf

      <div class="row">
        <!-- Kolom Kiri: Profil & Kontak Perusahaan -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-header bg-white py-3 border-0">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                <i class="fas fa-building text-primary mr-2"></i> Profil &amp; Kemitraan DU/DI
              </h5>
            </div>
            <div class="card-body pt-0 px-4 pb-4">
              
              <!-- Nama Perusahaan -->
              <div class="form-group">
                <label for="nama_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Nama Mitra Perusahaan / DU/DI <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-building text-muted"></i></span>
                  </div>
                  <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" placeholder="Contoh: PT Telkom Indonesia Tbk" required>
                </div>
              </div>

              <!-- Alamat Lengkap -->
              <div class="form-group">
                <label for="alamat_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alamat Lengkap Perusahaan <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" placeholder="Ketik alamat lengkap kantor/workshop, nama jalan, kelurahan, kecamatan, dan kota..." required>{{ old('alamat_perusahaan') }}</textarea>
                <small class="text-muted">Alamat ini akan digunakan untuk pencarian otomatis titik koordinat peta di samping.</small>
              </div>

              <div class="form-section-title mt-4">
                <i class="fas fa-user-shield text-info mr-2"></i> Pembimbing &amp; Penanggung Jawab
              </div>

              <!-- Guru Pembimbing Sekolah -->
              <div class="form-group">
                <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Guru Pembimbing Sekolah
                </label>
                <select name="id_guru" id="id_guru" class="form-control select2" style="width: 100%;">
                  <option value="">-- Pilih Guru Pembimbing (Opsional) --</option>
                  @foreach($guruList as $guru)
                    <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                      {{ $guru->nama_guru }} {{ $guru->nip ? '('.$guru->nip.')' : '' }}
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Guru yang ditugaskan memantau &amp; membimbing siswa di mitra ini.</small>
              </div>

              <!-- Penanggung Jawab Industri -->
              <div class="form-group">
                <label for="penanggung_jawab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pimpinan / Penanggung Jawab Industri
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-user-tie text-muted"></i></span>
                  </div>
                  <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" placeholder="Contoh: Bpk. Ahmad Dahlan, S.T. (Direktur/HRD)">
                </div>
              </div>

              <div class="row">
                <!-- PIC Lapangan -->
                <div class="col-md-6 form-group">
                  <label for="pic" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Nama PIC Lapangan
                  </label>
                  <input type="text" class="form-control @error('pic') is-invalid @enderror" id="pic" name="pic" value="{{ old('pic') }}" placeholder="Nama pembimbing teknis">
                </div>
                <!-- Kontak PIC -->
                <div class="col-md-6 form-group">
                  <label for="kontak_pic" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    No. WhatsApp PIC Lapangan
                  </label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light border-right-0"><i class="fab fa-whatsapp text-success"></i></span>
                    </div>
                    <input type="text" class="form-control @error('kontak_pic') is-invalid @enderror" id="kontak_pic" name="kontak_pic" value="{{ old('kontak_pic') }}" placeholder="08xxxxxxxxxx">
                  </div>
                </div>
              </div>

              <!-- Dokumen MoU -->
              <div class="form-group mb-0">
                <label for="file_mou" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Dokumen Kerjasama MoU (PDF)
                </label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input @error('file_mou') is-invalid @enderror" id="file_mou" name="file_mou" accept=".pdf">
                  <label class="custom-file-label" for="file_mou">Pilih berkas PDF...</label>
                </div>
                <small class="text-muted">Format PDF, maksimal ukuran berkas 5 MB.</small>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Peta Geofencing & Koordinat Radius -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                <i class="fas fa-crosshairs text-success mr-2"></i> Titik Lokasi Presensi (Geofencing)
              </h5>
              <span class="badge badge-light border text-muted px-2 py-1 small">
                <i class="fas fa-map-marked-alt text-primary mr-1"></i> Leaflet Maps
              </span>
            </div>
            <div class="card-body pt-0 px-4 pb-4">
              
              <p class="text-muted small mb-3">
                Tentukan koordinat pusat kantor mitra dan radius toleransi presensi siswa. Siswa wajib berada di dalam lingkaran radius saat melakukan presensi masuk &amp; pulang di aplikasi <code>siawi-app</code>.
              </p>

              <!-- Quick Action Toolbar -->
              <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 8px;">
                <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold btn-geocode-address" style="border-radius: 8px;" title="Pindai otomatis dari alamat dengan pembersih cerdas">
                  <i class="fas fa-magic mr-1"></i> Pindai dari Alamat
                </button>
                <button type="button" class="btn btn-sm btn-outline-info font-weight-bold btn-custom-search" style="border-radius: 8px;" title="Ketik nama jalan, gedung, atau patokan secara langsung">
                  <i class="fas fa-keyboard mr-1"></i> Cari Kata Kunci Bebas
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold btn-gps-current" style="border-radius: 8px;" title="Gunakan GPS perangkat Anda">
                  <i class="fas fa-location-arrow mr-1"></i> Lokasi Saya Saat Ini
                </button>
              </div>

              <!-- Map Container -->
              <div id="mapCreatePerusahaan" style="height: 330px; width: 100%; border-radius: 12px; border: 2px solid #e2e8f0; z-index: 1;" class="shadow-sm mb-3"></div>
              
              <div class="small text-muted mb-3 d-flex align-items-center">
                <i class="fas fa-info-circle text-info mr-2"></i>
                <span>Klik atau geser pin merah pada peta untuk menyetel koordinat lokasi secara presisi.</span>
              </div>

              <div class="row">
                <!-- Latitude -->
                <div class="col-md-4 form-group">
                  <label for="latitude" class="font-weight-bold text-dark" style="font-size: 0.8rem;">
                    Latitude
                  </label>
                  <input type="text" class="form-control form-control-sm @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="-6.2088" style="border-radius: 6px;">
                </div>
                <!-- Longitude -->
                <div class="col-md-4 form-group">
                  <label for="longitude" class="font-weight-bold text-dark" style="font-size: 0.8rem;">
                    Longitude
                  </label>
                  <input type="text" class="form-control form-control-sm @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="106.8456" style="border-radius: 6px;">
                </div>
                <!-- Radius Meter -->
                <div class="col-md-4 form-group">
                  <label for="radius_meter" class="font-weight-bold text-dark" style="font-size: 0.8rem;">
                    Radius (Meter)
                  </label>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control form-control-sm @error('radius_meter') is-invalid @enderror" id="radius_meter" name="radius_meter" value="{{ old('radius_meter', 50) }}" min="10" max="10000" style="border-radius: 6px 0 0 6px;">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light" style="border-radius: 0 6px 6px 0;">m</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="alert alert-light border p-3 mb-0" style="border-radius: 10px; background-color: #f8fafc;">
                <div class="d-flex align-items-start">
                  <i class="fas fa-lightbulb text-warning mr-2 mt-1"></i>
                  <div class="small text-muted">
                    <strong>Catatan Presensi Fleksibel / Shift:</strong> Siswa PKL dapat melakukan presensi kehadiran sesuai jadwal shift masing-masing tanpa ada batasan jam terlambat. Cukup pastikan posisi siswa berada dalam radius kantor di atas.
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons Card -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm mb-0 w-100 card-action-bar" style="border-radius: 14px;">
            <div class="card-body py-3 px-4" style="overflow: hidden;">
              <div class="d-flex justify-content-between align-items-center w-100" style="display: flex !important; justify-content: space-between !important; width: 100% !important;">
                <a href="{{ route('admin.perusahaan.index') }}" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 8px;">
                  <i class="fas fa-arrow-left mr-1"></i> Batal &amp; Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 8px; margin-left: auto !important;">
                  <i class="fas fa-save mr-1"></i> Simpan Mitra Perusahaan
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script src="{{ asset('lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
$(document).ready(function () {
  // Inisialisasi Select2
  if ($.fn.select2) {
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '100%'
    });
  }

  // Custom File Input label update secara aman
  if (typeof bsCustomFileInput !== 'undefined') {
    bsCustomFileInput.init();
  }

  // Setup Leaflet Map Picker
  const defaultLat = {{ old('latitude') ?: -6.2088 }};
  const defaultLng = {{ old('longitude') ?: 106.8456 }};
  const initialZoom = {{ old('latitude') ? 16 : 13 }};

  const map = L.map('mapCreatePerusahaan').setView([defaultLat, defaultLng], initialZoom);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19
  }).addTo(map);

  setTimeout(function () {
    map.invalidateSize();
  }, 250);

  let marker = null;
  let circle = null;

  function updateMapPosition(lat, lng, radius) {
    if (isNaN(lat) || isNaN(lng)) return;
    radius = parseInt(radius) || 50;

    $('#latitude').val(lat.toFixed(8));
    $('#longitude').val(lng.toFixed(8));

    if (!marker) {
      marker = L.marker([lat, lng], { draggable: true }).addTo(map);
      marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        updateMapPosition(pos.lat, pos.lng, $('#radius_meter').val());
      });
    } else {
      marker.setLatLng([lat, lng]);
    }

    if (!circle) {
      circle = L.circle([lat, lng], {
        radius: radius,
        color: '#0284c7',
        fillColor: '#38bdf8',
        fillOpacity: 0.25,
        weight: 2
      }).addTo(map);
    } else {
      circle.setLatLng([lat, lng]);
      circle.setRadius(radius);
    }

    map.panTo([lat, lng]);
  }

  // Jika ada input koordinat sebelumnya (misal dari old())
  @if(old('latitude') && old('longitude'))
    updateMapPosition({{ old('latitude') }}, {{ old('longitude') }}, {{ old('radius_meter', 50) }});
  @endif

  // Klik langsung pada peta
  map.on('click', function (e) {
    updateMapPosition(e.latlng.lat, e.latlng.lng, $('#radius_meter').val());
  });

  // Perubahan manual input latitude / longitude / radius
  $('#latitude, #longitude').on('change', function () {
    const lat = parseFloat($('#latitude').val());
    const lng = parseFloat($('#longitude').val());
    if (!isNaN(lat) && !isNaN(lng)) {
      updateMapPosition(lat, lng, $('#radius_meter').val());
    }
  });

  $('#radius_meter').on('input change', function () {
    const rad = parseInt($(this).val()) || 50;
    if (circle) circle.setRadius(rad);
  });

  // Tombol Lokasi Saat Ini (GPS Browser)
  $('.btn-gps-current').on('click', function () {
    if (!navigator.geolocation) {
      Swal.fire({ icon: 'error', title: 'GPS Tidak Didukung', text: 'Browser tidak mendukung deteksi lokasi.' });
      return;
    }

    const btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mendeteksi...');

    navigator.geolocation.getCurrentPosition(
      function (pos) {
        btn.prop('disabled', false).html('<i class="fas fa-location-arrow mr-1"></i> Lokasi Saya Saat Ini');
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        updateMapPosition(lat, lng, $('#radius_meter').val());
        map.setView([lat, lng], 17);
        Swal.fire({
          icon: 'success',
          title: 'Lokasi Ditemukan',
          text: `Koordinat berhasil disetel: ${lat.toFixed(6)}, ${lng.toFixed(6)}`,
          timer: 2000,
          showConfirmButton: false
        });
      },
      function (err) {
        btn.prop('disabled', false).html('<i class="fas fa-location-arrow mr-1"></i> Lokasi Saya Saat Ini');
        Swal.fire({
          icon: 'warning',
          title: 'Gagal Akses GPS',
          text: 'Pastikan izin lokasi pada browser diaktifkan (kode: ' + err.message + ').'
        });
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  });

  // Helper: Eksekusi satu query ke OpenStreetMap Nominatim
  function fetchNominatim(q) {
    return fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=' + encodeURIComponent(q), {
      headers: { 'Accept-Language': 'id' }
    })
    .then(res => res.json())
    .then(data => {
      if (data && data.length > 0) {
        return {
          query: q,
          lat: parseFloat(data[0].lat),
          lng: parseFloat(data[0].lon),
          displayName: data[0].display_name
        };
      }
      return null;
    })
    .catch(() => null);
  }

  // Helper: Generator variasi query cerdas untuk alamat Indonesia
  function generateSearchQueries(rawAddress) {
    if (!rawAddress) return [];
    let text = rawAddress.trim();

    let cleaned = text
      .replace(/kode\s*pos\s*:?\s*\d+/gi, '')
      .replace(/r[tw]\.?\s*\d+(\s*[\/\-]\s*r[tw]\.?\s*\d+)?/gi, '')
      .replace(/\bno\.?\s*\d+[a-z0-9\/\-]*\b/gi, '')
      .replace(/\b\d{5}\b/g, '')
      .replace(/\bdaerah\s+khusus\s+ibukota\b/gi, '')
      .replace(/\bwest\s+java\b/gi, 'Jawa Barat')
      .replace(/\bgg\.?\b/gi, 'Gang')
      .replace(/\bjl\.?\b/gi, 'Jalan')
      .replace(/\bps\.?\b/gi, 'Pasar')
      .replace(/\bkel\.?\b/gi, 'Kelurahan')
      .replace(/\bkec\.?\b/gi, 'Kecamatan')
      .replace(/\s+/g, ' ')
      .trim();

    let gangMatch = rawAddress.match(/\b(?:gg\.?|gang)\s+([a-z0-9\s]+?)(?=,|\bno\.|\brt|\brw|$)/i);
    let streetMatch = rawAddress.match(/\b(?:jl\.?|jalan)\s+([a-z0-9\s]+?)(?=\bgg\.?|\bgang|,|\bno\.|\brt|\brw|$)/i);
    let cityMatch = rawAddress.match(/(Jakarta\s+[A-Za-z]+|Bekasi|Depok|Bogor|Tangerang\s+[A-Za-z]+|Tangerang|Bandung|Surabaya|Semarang|Yogyakarta|[A-Za-z\s]+(?:Selatan|Utara|Barat|Timur|Pusat))/i);
    let city = cityMatch ? cityMatch[0].trim() : '';

    let segments = cleaned.split(',').map(s => s.trim().replace(/^,\s*|,\s*$/g, '')).filter(s => s.length > 2);
    let queries = [];

    // Prioritas 1: Gang + Area + Kota (paling presisi)
    if (gangMatch) {
      let gangName = 'Gang ' + gangMatch[1].trim();
      if (segments.length > 2 && city) {
        queries.push(`${gangName}, ${segments[1]}, ${city}`);
      }
      if (city) {
        queries.push(`${gangName}, ${city}`);
      }
    }

    // Prioritas 2: Jalan Utama + Kota
    if (streetMatch && city) {
      let stName = 'Jalan ' + streetMatch[1].trim();
      if (segments.length > 2) {
        queries.push(`${stName}, ${segments[1]}, ${city}`);
      }
      queries.push(`${stName}, ${city}`);
    }

    // Prioritas 3: Jalan Utama saja yang ada di segmen awal + kota
    if (segments.length >= 2) {
      let firstSeg = segments[0].replace(/\bGang\s+.*$/i, '').trim();
      if (firstSeg.length > 3 && city) {
        queries.push(`${firstSeg}, ${city}`);
      }
    }

    // Prioritas 4: Alamat bersih penuh (tanpa RT/RW/No)
    queries.push(cleaned);

    // Prioritas 5: Segmen awal + segmen akhir
    if (segments.length >= 2) {
      queries.push(`${segments[0]}, ${segments[segments.length - 1]}`);
    }

    return [...new Set(queries.map(q => q.replace(/,\s*,+/g, ',').trim()).filter(q => q.length > 3))];
  }

  // Tombol Pindai dari Alamat (Smart Cascading Geocoding)
  $('.btn-geocode-address').on('click', async function () {
    const rawAddress = ($('#alamat_perusahaan').val() || '').trim();

    if (!rawAddress) {
      Swal.fire({
        icon: 'warning',
        title: 'Alamat Masih Kosong',
        text: 'Silakan ketik alamat lengkap perusahaan terlebih dahulu.'
      });
      return;
    }

    const btn = $(this);
    const originalHtml = btn.html();
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memindai...');

    const queries = generateSearchQueries(rawAddress);
    let found = null;

    for (let i = 0; i < queries.length; i++) {
      let q = queries[i];
      btn.html(`<i class="fas fa-spinner fa-spin mr-1"></i> Cek (${i + 1}/${queries.length})...`);
      found = await fetchNominatim(q);
      if (found) break;
      await new Promise(r => setTimeout(r, 450));
    }

    btn.prop('disabled', false).html(originalHtml);

    if (found) {
      updateMapPosition(found.lat, found.lng, $('#radius_meter').val());
      map.setView([found.lat, found.lng], 17);
      Swal.fire({
        icon: 'success',
        title: 'Lokasi Berhasil Ditemukan!',
        html: `Koordinat disetel:<br><code>${found.lat.toFixed(6)}, ${found.lng.toFixed(6)}</code><br><br><small class="text-muted d-block text-left bg-light p-2 rounded border"><strong>Titik Terdeteksi:</strong><br>${found.displayName}<br><br><span class="text-primary font-italic">Kata kunci cocok: "${found.query}"</span></small>`,
        confirmButtonColor: '#0284c7',
        confirmButtonText: 'Terapkan'
      });
    } else {
      Swal.fire({
        icon: 'info',
        title: 'Tidak Ditemukan Langsung',
        html: 'Server peta memerlukan kata kunci yang lebih ringkas.<br><br>Gunakan tombol <strong>Cari Kata Kunci Bebas</strong> untuk mengetik nama gang/jalan secara langsung, atau klik pin di peta.',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        confirmButtonText: '<i class="fas fa-keyboard mr-1"></i> Ketik Kata Kunci',
        cancelButtonText: 'Tutup'
      }).then((result) => {
        if (result.isConfirmed) {
          promptCustomSearch();
        }
      });
    }
  });

  // Tombol Cari Kata Kunci Bebas
  $('.btn-custom-search').on('click', function () {
    promptCustomSearch();
  });

  function promptCustomSearch() {
    Swal.fire({
      title: 'Cari Lokasi / Kata Kunci',
      text: 'Ketik nama jalan, gedung perkantoran, gang, atau patokan lokasi:',
      input: 'text',
      inputPlaceholder: 'Misal: Gang Langgar Kebagusan, atau Gedung Cyber',
      showCancelButton: true,
      confirmButtonColor: '#0284c7',
      confirmButtonText: '<i class="fas fa-search mr-1"></i> Cari di Peta',
      cancelButtonText: 'Batal',
      inputValidator: (val) => {
        if (!val || !val.trim()) {
          return 'Ketik kata kunci pencarian terlebih dahulu!';
        }
      }
    }).then(async (result) => {
      if (result.isConfirmed && result.value) {
        Swal.fire({
          title: 'Mencari di Peta...',
          text: `Mencari: "${result.value}"`,
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); }
        });

        const found = await fetchNominatim(result.value.trim());

        if (found) {
          updateMapPosition(found.lat, found.lng, $('#radius_meter').val());
          map.setView([found.lat, found.lng], 17);
          Swal.fire({
            icon: 'success',
            title: 'Lokasi Ditemukan!',
            html: `Koordinat disetel:<br><code>${found.lat.toFixed(6)}, ${found.lng.toFixed(6)}</code><br><br><small class="text-muted d-block text-left bg-light p-2 rounded border">${found.displayName}</small>`,
            confirmButtonColor: '#0284c7'
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Lokasi Belum Ditemukan',
            text: `Tidak ada hasil untuk kata kunci "${result.value}". Coba tambahkan nama kota (contoh: "${result.value} Jakarta").`,
            confirmButtonColor: '#0284c7'
          });
        }
      }
    });
  }
});
</script>
@endpush

