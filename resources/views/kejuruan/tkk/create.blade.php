@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-plus-circle text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Tambah Jenis TKK Baru</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Definisikan nama lencana keahlian, tingkat, dan unit kompetensi yang disyaratkan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/tkk" class="text-primary font-weight-500">Master TKK</a></li>
          <li class="breadcrumb-item active">Tambah TKK</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(isset($errors) && $errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="/admin/kejuruan/tkk" method="POST">
      @csrf

      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-bottom">
          <h5 class="card-title text-dark font-weight-bold mb-0">
            <i class="fas fa-award text-primary mr-2"></i> Identitas Tanda Kecakapan Khusus (TKK)
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-3 form-group mb-3">
              <label class="font-weight-bold text-dark small">Kode TKK (Opsional)</label>
              <input type="text" name="kode_tkk" class="form-control" placeholder="Contoh: TKK-TJKT-01" value="{{ old('kode_tkk') }}">
            </div>

            <div class="col-md-5 form-group mb-3">
              <label class="font-weight-bold text-dark small">Nama TKK <span class="text-danger">*</span></label>
              <input type="text" name="nama_tkk" class="form-control" placeholder="Contoh: TKK Perakitan & Perawatan PC" value="{{ old('nama_tkk') }}" required>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Nomor Klaster Skema (1-9)</label>
              <select name="nomor_klaster" class="form-control">
                <option value="">-- Tanpa Klaster (TKK Umum) --</option>
                @for($k = 1; $k <= 9; $k++)
                  <option value="{{ $k }}" {{ old('nomor_klaster') == $k ? 'selected' : '' }}>
                    Klaster #{{ $k }}
                  </option>
                @endfor
              </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label class="font-weight-bold text-dark small">Program Keahlian (Jurusan) <span class="text-danger">*</span></label>
              <select name="id_jurusan" id="select_id_jurusan" class="form-control select2bs4" required>
                @foreach($jurusans as $j)
                  <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label class="font-weight-bold text-dark small">Tingkat Kemahiran <span class="text-danger">*</span></label>
              <select name="tingkat" class="form-control" required>
                <option value="dasar" {{ old('tingkat') === 'dasar' ? 'selected' : '' }}>Tingkat Dasar (Purwa)</option>
                <option value="menengah" {{ old('tingkat') === 'menengah' ? 'selected' : '' }}>Tingkat Menengah (Madya)</option>
                <option value="utama" {{ old('tingkat') === 'utama' ? 'selected' : '' }}>Tingkat Utama</option>
              </select>
            </div>

            <div class="col-12 form-group mb-0">
              <label class="font-weight-bold text-dark small">Deskripsi Keahlian & Standar Pencapaian</label>
              <textarea name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan kompetensi kerja atau keterampilan praktis yang dibuktikan oleh TKK ini">{{ old('deskripsi') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Syarat Kompetensi Berdasarkan Jurusan -->
      <style>
        .komp-card-grid {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 12px;
        }
        @media (max-width: 767.98px) {
          .komp-card-grid {
            grid-template-columns: 1fr;
          }
        }
        .komp-card-box {
          background: #f8fafc;
          border: 1.5px solid #e2e8f0;
          border-radius: 10px;
          padding: 10px 14px 10px 42px;
          height: 100%;
          min-height: 72px;
          display: flex;
          align-items: center;
          transition: all 0.2s ease;
          box-sizing: border-box;
          margin: 0 !important;
          cursor: pointer;
          position: relative;
        }
        .komp-card-box:hover {
          border-color: #cbd5e1;
          background: #f1f5f9;
        }
        .komp-card-box.is-selected {
          border-color: #3b82f6 !important;
          background: #eff6ff !important;
        }
        .komp-card-box .custom-control-label::before,
        .komp-card-box .custom-control-label::after {
          top: 50% !important;
          transform: translateY(-50%) !important;
          left: -30px !important;
          width: 20px !important;
          height: 20px !important;
          border-radius: 6px !important;
        }
        .komp-card-box .custom-control-label::before {
          border: 2px solid #cbd5e1 !important;
          background-color: #ffffff;
        }
        .komp-card-box .custom-control-input:checked ~ .custom-control-label::before {
          background-color: #007bff !important;
          border-color: #007bff !important;
        }
        .komp-title-text {
          display: -webkit-box;
          -webkit-line-clamp: 2;
          -webkit-box-orient: vertical;
          overflow: hidden;
          text-overflow: ellipsis;
          word-break: break-word;
          line-height: 1.32;
          font-size: 0.86rem;
          max-height: 2.7em;
        }
      </style>

      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap">
          <div>
            <h5 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-check-double text-primary mr-2"></i> Syarat Unit Kompetensi yang Harus Tercapai <span class="text-danger">*</span>
            </h5>
            <p class="text-muted small mb-0 mt-1">Centang unit kompetensi yang wajib berstatus "Kompeten" di Buku Induk Capaian Siswa agar berhak memperoleh TKK ini.</p>
          </div>
          <div class="mt-2 mt-sm-0 d-flex align-items-center" style="gap: 6px;">
            <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" onclick="selectFilteredKomp(true)">
              <i class="fas fa-check-square mr-1"></i> Pilih Semua
            </button>
            <button type="button" class="btn btn-xs btn-outline-secondary font-weight-bold px-2 py-1" onclick="selectFilteredKomp(false)">
              <i class="fas fa-square mr-1"></i> Hapus Pilihan
            </button>
          </div>
        </div>

        <div class="card-body p-4">
          <!-- Filter Tabs / Pills per Jurusan -->
          <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom pb-3 mb-3" style="gap: 8px;">
            <ul class="nav nav-pills mb-0" id="filterJurusanPills" style="gap: 6px;">
              <li class="nav-item">
                <a class="nav-link active font-weight-bold btn-sm py-1 px-3 filter-jurusan-btn" href="javascript:void(0)" data-filter="all" style="border-radius: 8px; font-size: 0.84rem;">
                  <i class="fas fa-layer-group mr-1"></i> Semua Jurusan
                  <span class="badge badge-light border ml-1 count-badge" id="badge_count_all">0</span>
                </a>
              </li>
              @foreach($jurusans as $j)
                @php
                  $jKompCount = $kompetensis->where('id_jurusan', $j->id_jurusan)->count();
                @endphp
                @if($jKompCount > 0)
                  <li class="nav-item">
                    <a class="nav-link font-weight-bold btn-sm py-1 px-3 filter-jurusan-btn" href="javascript:void(0)" data-filter="{{ $j->id_jurusan }}" id="tab_btn_jurusan_{{ $j->id_jurusan }}" style="border-radius: 8px; font-size: 0.84rem;">
                      {{ $j->kode_jurusan ?? $j->nama_jurusan }}
                      <span class="badge badge-light border ml-1 count-badge" id="badge_count_{{ $j->id_jurusan }}">0</span>
                    </a>
                  </li>
                @endif
              @endforeach
            </ul>

            <div class="small text-muted font-weight-bold" id="selectedSummaryText">
              0 unit kompetensi dipilih
            </div>
          </div>

          <!-- Grid Kartu Unit Kompetensi -->
          <div class="komp-card-grid" id="kompCardContainer">
            @forelse($kompetensis as $k)
              @php
                $isChecked = is_array(old('syarat_kompetensi')) && in_array($k->id_kompetensi, old('syarat_kompetensi'));
              @endphp
              <div class="komp-item-wrapper" data-jurusan-id="{{ $k->id_jurusan }}">
                <div class="custom-control custom-checkbox komp-card-box {{ $isChecked ? 'is-selected' : '' }}" id="card_box_{{ $k->id_kompetensi }}">
                  <input type="checkbox" 
                         class="custom-control-input komp-check" 
                         id="komp_{{ $k->id_kompetensi }}" 
                         name="syarat_kompetensi[]" 
                         value="{{ $k->id_kompetensi }}" 
                         data-jurusan-id="{{ $k->id_jurusan }}" 
                         {{ $isChecked ? 'checked' : '' }} 
                         onchange="onKompChange(this)">
                  <label class="custom-control-label font-weight-bold text-dark w-100 mb-0" for="komp_{{ $k->id_kompetensi }}" style="cursor: pointer;">
                    <div class="komp-title-text text-dark" title="[{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }}">
                      <span class="text-primary font-weight-bold mr-1">[{{ $k->kode_kompetensi }}]</span>{{ $k->nama_kompetensi }}
                    </div>
                    <small class="d-block text-muted font-weight-normal mt-1" style="font-size: 0.74rem; line-height: 1.2;">
                      Tingkat {{ $k->tingkat }} • Semester {{ $k->semester }} • KKM: {{ floatval($k->kkm) }}
                      @if($k->jurusan)
                        <span class="badge badge-light border ml-1">{{ $k->jurusan->kode_jurusan ?? $k->jurusan->nama_jurusan }}</span>
                      @endif
                    </small>
                  </label>
                </div>
              </div>
            @empty
              <div class="col-12 text-muted py-3">Belum ada standar kompetensi terdaftar.</div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between mb-5">
        <a href="/admin/kejuruan/tkk" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-arrow-left mr-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-save mr-1"></i> Simpan Jenis TKK
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  let currentJurusanFilter = 'all';

  function filterJurusan(jurusanId) {
    currentJurusanFilter = jurusanId;
    document.querySelectorAll('.filter-jurusan-btn').forEach(btn => {
      btn.classList.toggle('active', btn.getAttribute('data-filter') == jurusanId);
    });

    const items = document.querySelectorAll('.komp-item-wrapper');
    items.forEach(item => {
      if (jurusanId === 'all' || item.getAttribute('data-jurusan-id') == jurusanId) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }

  function onKompChange(input) {
    const card = document.getElementById('card_box_' + input.value);
    if (card) {
      card.classList.toggle('is-selected', input.checked);
    }
    updateCountBadges();
  }

  function updateCountBadges() {
    const allChecked = document.querySelectorAll('.komp-check:checked');
    const badgeAll = document.getElementById('badge_count_all');
    if (badgeAll) badgeAll.textContent = allChecked.length;

    const summaryText = document.getElementById('selectedSummaryText');
    if (summaryText) {
      summaryText.textContent = allChecked.length + ' unit kompetensi dipilih';
    }

    document.querySelectorAll('.filter-jurusan-btn').forEach(btn => {
      const jId = btn.getAttribute('data-filter');
      if (jId !== 'all') {
        const count = document.querySelectorAll('.komp-check[data-jurusan-id="' + jId + '"]:checked').length;
        const b = document.getElementById('badge_count_' + jId);
        if (b) b.textContent = count;
      }
    });
  }

  function selectFilteredKomp(check) {
    const items = document.querySelectorAll('.komp-item-wrapper');
    items.forEach(item => {
      if (item.style.display !== 'none') {
        const input = item.querySelector('.komp-check');
        if (input) {
          input.checked = check;
          const card = item.querySelector('.komp-card-box');
          if (card) card.classList.toggle('is-selected', check);
        }
      }
    });
    updateCountBadges();
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Event listener click filter buttons
    document.querySelectorAll('.filter-jurusan-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        filterJurusan(this.getAttribute('data-filter'));
      });
    });

    // Auto filter when dropdown jurusan changes
    const selectJurusan = document.getElementById('select_id_jurusan');
    if (selectJurusan) {
      $(selectJurusan).on('change', function() {
        if (this.value) {
          filterJurusan(this.value);
        }
      });
      if (selectJurusan.value) {
        filterJurusan(selectJurusan.value);
      }
    }

    updateCountBadges();
  });
</script>
@endsection
