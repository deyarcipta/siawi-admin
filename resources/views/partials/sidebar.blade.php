@php
  $setting = $setting ?? \App\Models\Setting::find(1);
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/admin/dashboard" class="brand-link">
    @if($setting && $setting->logo && file_exists(public_path('storage/gambar/' . $setting->logo)))
      <img src="{{ asset('storage/gambar/' . $setting->logo) }}" alt="{{ $setting->nama_sekolah ?? 'Logo Sekolah' }}" class="brand-logo-img" style="width: 38px; height: 38px; object-fit: contain; flex-shrink: 0; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.25));">
    @else
      <div class="brand-logo-icon">
        S
      </div>
    @endif
    <div class="brand-text-wrapper">
      <span class="brand-title">{{ $setting->nama_app ?? 'SIAWI' }}</span>
      <span class="brand-subtitle">{{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }}</span>
    </div>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
        
        <!-- ============================================== -->
        <!-- 1. DASHBOARD & LIVE PANEL (Semua Pengguna)     -->
        <!-- ============================================== -->
        <li class="nav-item">
          <a href="/admin/dashboard" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th-large"></i>
            <p>Dashboard</p>
          </a>
        </li>

        @if($user->hasAnyRole(['admin', 'kurikulum', 'kesiswaan', 'wali_kelas', 'guru', 'tata_usaha']))
        <li class="nav-item">
          <a href="/admin/live-panel" target="_blank" class="nav-link {{ Request::is('admin/live-panel*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tv"></i>
            <p>
              Live Panel TV
              <span class="right badge badge-info" style="font-size: 0.65rem; font-weight: 700;">LIVE</span>
            </p>
          </a>
        </li>
        @endif

        <!-- ============================================== -->
        <!-- 2. PRESENSI & OPERASIONAL HARIAN               -->
        <!-- ============================================== -->
        @if($user->hasAnyRole(['admin', 'kurikulum', 'kesiswaan', 'wali_kelas', 'guru', 'tata_usaha']))
        <li class="nav-header">
          Presensi Harian
        </li>

        <!-- Absensi Siswa -->
        @if($user->hasAnyRole(['admin', 'kurikulum', 'kesiswaan', 'wali_kelas', 'guru', 'tata_usaha']))
        <li class="nav-item has-treeview {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/siswa-tidak-hadir*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/showRekapAbsen*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/rekap-belum-absen*') || Request::is('admin/laporan-kedisiplinan-siswa*') || Request::is('admin/laporan-bulanan-wa*') || Request::is('admin/laporan-mingguan-wa*') || Request::is('admin/dataAbsen*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/siswa-tidak-hadir*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/showRekapAbsen*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/rekap-belum-absen*') || Request::is('admin/laporan-kedisiplinan-siswa*') || Request::is('admin/laporan-bulanan-wa*') || Request::is('admin/laporan-mingguan-wa*') || Request::is('admin/dataAbsen*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-check"></i>
            <p>
              Absensi Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum', 'guru']))
            <li class="nav-item">
              <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Absensi Harian Siswa</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'tata_usaha', 'kurikulum']))
            <li class="nav-item">
              <a href="/admin/siswa-tidak-hadir?today=1" class="nav-link {{ Request::is('admin/siswa-tidak-hadir*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Siswa Tidak Hadir</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/showRekapAbsen*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Absensi Kelas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Rekap Absensi Siswa</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas']))
            <li class="nav-item">
              <a href="/admin/laporan-kedisiplinan-siswa" class="nav-link {{ Request::is('admin/laporan-kedisiplinan-siswa*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Kedisiplinan Absensi Mesin</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kesiswaan']))
            <li class="nav-item">
              <a href="/admin/rekap-belum-absen" class="nav-link {{ Request::is('admin/rekap-belum-absen*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Rekap Kelalaian Absen</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum']))
            <li class="nav-item">
              <a href="/admin/laporan-bulanan-wa" class="nav-link {{ Request::is('admin/laporan-bulanan-wa*') || Request::is('admin/laporan-mingguan-wa*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Rekap Bulanan & WA</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        <!-- Absensi Guru -->
        @if($user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum']))
        <li class="nav-item has-treeview {{ Request::is('admin/absensi_guru*') || Request::is('admin/rekapAbsenGuru*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/absensi_guru*') || Request::is('admin/rekapAbsenGuru*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>
              Absensi Guru
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/absensi_guru" class="nav-link {{ Request::is('admin/absensi_guru*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Absensi Harian Guru</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsenGuru" class="nav-link {{ Request::is('admin/rekapAbsenGuru*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Rekap Absensi Guru</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        <!-- Guru Piket -->
        @if($user->hasAnyRole(['admin', 'guru', 'wali_kelas', 'kurikulum', 'kesiswaan', 'tata_usaha']))
        <li class="nav-item has-treeview {{ Request::is('admin/guruPiket*') || Request::is('admin/piketPembiasaanPagi*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/guruPiket*') || Request::is('admin/piketPembiasaanPagi*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-clock"></i>
            <p>
              Guru Piket
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/guruPiket/panel" class="nav-link {{ Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Panel Guru Piket</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/guruPiket" class="nav-link {{ Request::is('admin/guruPiket') || Request::is('admin/guruPiket/*') && !Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Jadwal Guru Piket</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/piketPembiasaanPagi" class="nav-link {{ Request::is('admin/piketPembiasaanPagi*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Jadwal Pembiasaan Pagi</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @endif

        <!-- ============================================== -->
        <!-- 3. AKADEMIK & PEMBELAJARAN                     -->
        <!-- ============================================== -->
        @if($user->hasAnyRole(['admin', 'kurikulum', 'wali_kelas', 'guru', 'tata_usaha']))
        <li class="nav-header">
          Akademik & Belajar
        </li>

        <!-- Pembelajaran -->
        @if($user->hasAnyRole(['admin', 'kurikulum', 'wali_kelas', 'guru']))
        <li class="nav-item has-treeview {{ Request::is('admin/jadwal*') || Request::is('admin/jurnal*') || Request::is('admin/rekap-kehadiran-guru*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/jadwal*') || Request::is('admin/jurnal*') || Request::is('admin/rekap-kehadiran-guru*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book-open"></i>
            <p>
              Pembelajaran
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($user->hasAnyRole(['admin', 'kurikulum']))
            <li class="nav-item">
              <a href="/admin/jadwal" class="nav-link {{ Request::is('admin/jadwal*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Jadwal Mata Pelajaran</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kurikulum', 'wali_kelas', 'guru']))
            <li class="nav-item">
              <a href="/admin/jurnal" class="nav-link {{ Request::is('admin/jurnal*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Jurnal Mengajar</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kurikulum', 'tata_usaha']))
            <li class="nav-item">
              <a href="/admin/rekap-kehadiran-guru" class="nav-link {{ Request::is('admin/rekap-kehadiran-guru*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Monitoring Kehadiran</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        <!-- Dokumen, Rapot & Modul -->
        @if($user->hasAnyRole(['admin', 'kurikulum', 'wali_kelas', 'guru', 'tata_usaha']))
        <li class="nav-item has-treeview {{ Request::is('admin/rapot*') || Request::is('admin/modul*') || Request::is('admin/dokumen*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/rapot*') || Request::is('admin/modul*') || Request::is('admin/dokumen*') ? 'active' : '' }}">
            <i class="nav-icon far fa-file-alt"></i>
            <p>
              Dokumen & Rapot
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($user->hasAnyRole(['admin', 'tata_usaha']))
            <li class="nav-item">
              <a href="/admin/dokumen" class="nav-link {{ Request::is('admin/dokumen*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Dokumen Siswa</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kurikulum', 'wali_kelas']))
            <li class="nav-item">
              <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Rapot Siswa</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kurikulum', 'wali_kelas', 'guru']))
            <li class="nav-item">
              <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Modul Siswa</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @endif

        <!-- ============================================== -->
        <!-- 4. KESISWAAN & KEDISIPLINAN                    -->
        <!-- ============================================== -->
        @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum', 'guru', 'tata_usaha']))
        <li class="nav-header">
          Kesiswaan & Industri
        </li>

        <!-- Point & Pelanggaran Siswa -->
        @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum', 'guru']))
        <li class="nav-item has-treeview {{ Request::is('admin/point') || Request::is('admin/point/*') || Request::is('admin/pointSiswa*') || Request::is('admin/laporan-pelanggaran*') || Request::is('admin/surat-peringatan*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/point') || Request::is('admin/point/*') || Request::is('admin/pointSiswa*') || Request::is('admin/laporan-pelanggaran*') || Request::is('admin/surat-peringatan*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p>
              Poin & Tata Tertib
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($user->hasAnyRole(['admin', 'kesiswaan']))
            <li class="nav-item">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') || Request::is('admin/point/*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Poin</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum', 'guru']))
            <li class="nav-item">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Pencatatan Poin Siswa</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum']))
            <li class="nav-item">
              <a href="/admin/laporan-pelanggaran" class="nav-link {{ Request::is('admin/laporan-pelanggaran*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Laporan Pelanggaran</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/surat-peringatan" class="nav-link {{ Request::is('admin/surat-peringatan*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Surat Peringatan (SP)</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        <!-- Hubungan Industri (BKK & PKL) -->
        @if($user->hasAnyRole(['admin', 'kesiswaan', 'tata_usaha']))
        <li class="nav-item has-treeview {{ Request::is('admin/perusahaan*') || Request::is('admin/siswaPkl*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/perusahaan*') || Request::is('admin/siswaPkl*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-briefcase"></i>
            <p>
              BKK & PKL
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/perusahaan" class="nav-link {{ Request::is('admin/perusahaan*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Perusahaan Mitra</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/siswaPkl" class="nav-link {{ Request::is('admin/siswaPkl*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Siswa PKL</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @endif

        <!-- ============================================== -->
        <!-- 5. KEUANGAN & INFORMASI                        -->
        <!-- ============================================== -->
        @if($user->hasAnyRole(['admin', 'keuangan', 'wali_kelas', 'tata_usaha', 'kurikulum']))
        <li class="nav-header">
          Keuangan & Informasi
        </li>

        <!-- Tagihan Keuangan -->
        @if($user->hasAnyRole(['admin', 'keuangan', 'wali_kelas']))
        <li class="nav-item">
          <a href="/admin/tagihan" class="nav-link {{ Request::is('admin/tagihan*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Tagihan Siswa</p>
          </a>
        </li>
        @endif

        <!-- Pusat Informasi -->
        @if($user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum', 'keuangan']))
        <li class="nav-item has-treeview {{ Request::is('admin/informasi*') || Request::is('admin/kalender*') || Request::is('admin/berita*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/informasi*') || Request::is('admin/kalender*') || Request::is('admin/berita*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-bullhorn"></i>
            <p>
              Pusat Informasi
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Informasi Sekolah</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/kalender" class="nav-link {{ Request::is('admin/kalender*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Kalender Akademik</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/berita" class="nav-link {{ Request::is('admin/berita*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Berita & Artikel</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        <!-- Agenda Surat -->
        @if($user->hasAnyRole(['admin', 'tata_usaha']))
        <li class="nav-item has-treeview {{ Request::is('admin/surat-keluar*') || Request::is('admin/surat-masuk*') || Request::is('admin/klasifikasi-surat*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/surat-keluar*') || Request::is('admin/surat-masuk*') || Request::is('admin/klasifikasi-surat*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-mail-bulk"></i>
            <p>
              Agenda Surat
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/surat-keluar" class="nav-link {{ Request::is('admin/surat-keluar*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Surat Keluar (Penomoran)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/surat-masuk" class="nav-link {{ Request::is('admin/surat-masuk*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Surat Masuk (Agenda)</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/klasifikasi-surat" class="nav-link {{ Request::is('admin/klasifikasi-surat*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Klasifikasi Surat</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @endif

        <!-- ============================================== -->
        <!-- 6. SISTEM & PENGATURAN                         -->
        <!-- ============================================== -->
        @if($user->hasAnyRole(['admin', 'tata_usaha', 'kesiswaan', 'wali_kelas', 'kurikulum', 'guru', 'keuangan']))
        <li class="nav-header">
          Sistem & Pengaturan
        </li>

        <!-- Data Master -->
        <li class="nav-item has-treeview {{ Request::is('admin/jurusan*') || Request::is('admin/importDataMaster*') || Request::is('admin/level*') || Request::is('admin/kelas*') || Request::is('admin/mapel*') || Request::is('admin/siswa') || Request::is('admin/siswa/*') || Request::is('admin/dataAlumni*') || Request::is('admin/alumni*') || Request::is('admin/guru') || Request::is('admin/guru/*') || Request::is('admin/dataMaster/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/jurusan*') || Request::is('admin/importDataMaster*') || Request::is('admin/level*') || Request::is('admin/kelas*') || Request::is('admin/mapel*') || Request::is('admin/siswa') || Request::is('admin/siswa/*') || Request::is('admin/dataAlumni*') || Request::is('admin/alumni*') || Request::is('admin/guru') || Request::is('admin/guru/*') || Request::is('admin/dataMaster/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($user->hasRole('admin'))
            <li class="nav-item">
              <a href="/admin/importDataMaster" class="nav-link {{ Request::is('admin/importDataMaster') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Import Data Master</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'tata_usaha']))
            <li class="nav-item">
              <a href="/admin/jurusan" class="nav-link {{ Request::is('admin/jurusan*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Jurusan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/level" class="nav-link {{ Request::is('admin/level*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Level</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/kelas" class="nav-link {{ Request::is('admin/kelas*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Kelas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/mapel" class="nav-link {{ Request::is('admin/mapel*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Mata Pelajaran</p>
              </a>
            </li>
            @endif

            <!-- Data Siswa & Alumni -->
            @if($user->hasAnyRole(['admin', 'tata_usaha', 'kesiswaan', 'wali_kelas', 'kurikulum', 'guru', 'keuangan']))
            <li class="nav-item">
              <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Siswa</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/dataAlumni" class="nav-link {{ Request::is('admin/dataAlumni*') || Request::is('admin/alumni*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Alumni</p>
              </a>
            </li>
            @endif

            @if($user->hasAnyRole(['admin', 'tata_usaha']))
            <li class="nav-item">
              <a href="/admin/guru" class="nav-link {{ Request::is('admin/guru') || Request::is('admin/guru/*') ? 'active' : '' }}">
                <i class="fas fa-circle nav-icon" style="font-size: 6px;"></i>
                <p>Data Guru</p>
              </a>
            </li>
            @endif
          </ul>
        </li>

        @if($user->hasRole('admin'))
        <!-- Pengaturan Aplikasi -->
        <li class="nav-item">
          <a href="/admin/setting" class="nav-link {{ Request::is('admin/setting*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>Setting Aplikasi</p>
          </a>
        </li>

        <!-- Backup & Restore Database -->
        <li class="nav-item">
          <a href="/admin/backup" class="nav-link {{ Request::is('admin/backup*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-shield-alt"></i>
            <p>Backup & Restore</p>
          </a>
        </li>
        @endif
        @endif

      </ul>
    </nav>
  </div>

  <!-- Docked Bottom User Card in Sidebar -->
  <div class="sidebar-user-dock">
    @if($user && $user->foto && file_exists(storage_path('app/public/foto_guru/' . $user->foto)))
      <img src="{{ asset('storage/foto_guru/' . $user->foto) }}" alt="{{ $user->nama_guru }}" class="sidebar-user-avatar rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
    @else
      <div class="sidebar-user-avatar">
        {{ strtoupper(substr($user->nama_guru ?? 'A', 0, 1)) }}
      </div>
    @endif
    <div class="sidebar-user-info">
      <div class="sidebar-user-name" title="{{ $user->nama_guru }}">{{ $user->nama_guru }}</div>
      <div class="sidebar-user-role" title="{{ implode(' • ', array_map(function($r) { return ucfirst(str_replace('_', ' ', $r)); }, $user->roles_list ?? [$user->role])) }}">
        @php
          $roleLabels = [
            'admin' => 'Admin',
            'wali_kelas' => 'Wali Kelas',
            'kesiswaan' => 'Kesiswaan',
            'kurikulum' => 'Kurikulum',
            'tata_usaha' => 'Tata Usaha',
            'keuangan' => 'Keuangan',
            'guru' => 'Guru',
          ];
          $userDisplayRoles = array_map(function($r) use ($roleLabels) {
            return $roleLabels[$r] ?? ucfirst(str_replace('_', ' ', $r));
          }, $user->roles_list ?? [$user->role]);
        @endphp
        {{ implode(' • ', $userDisplayRoles) }}
      </div>
    </div>
  </div>
</aside>
