<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/admin/dashboard" class="brand-link">
    <img src="{{ asset("storage/gambar/$setting->logo") }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-2" style="opacity: .8 box-shadow: 0px 0px 5px rgba(255, 255, 255, 0.8);">
    <span class="brand-text font-weight-bold">{{$setting->nama_app}}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-1 d-flex">
      <div class="image">
        <img src="{{ asset('lte/dist/img/default.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{$user->nama_guru}}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-1">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="/admin/dashboard" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        @if ($user->role == 'admin')
        <!-- 1. Data Master -->
        <li class="nav-item has-treeview {{ Request::is('admin/jurusan*') || Request::is('admin/importDataMaster*') || Request::is('admin/level*') || Request::is('admin/kelas*') || Request::is('admin/mapel*') || Request::is('admin/siswa') || Request::is('admin/siswa/*') || Request::is('admin/dataAlumni*') || Request::is('admin/guru') || Request::is('admin/guru/*') || Request::is('admin/dataMaster/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/jurusan*') || Request::is('admin/importDataMaster*') || Request::is('admin/level*') || Request::is('admin/kelas*') || Request::is('admin/mapel*') || Request::is('admin/siswa') || Request::is('admin/siswa/*') || Request::is('admin/dataAlumni*') || Request::is('admin/guru') || Request::is('admin/guru/*') || Request::is('admin/dataMaster/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/importDataMaster" class="nav-link {{ Request::is('admin/importDataMaster') ? 'active' : '' }}">
                <i class="fas fa-upload nav-icon" style="font-size: 14px"></i>
                <p>Import Data Master</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/jurusan" class="nav-link {{ Request::is('admin/jurusan*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>Data Jurusan</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/level" class="nav-link {{ Request::is('admin/level*') ? 'active' : '' }}">
                <i class="fas fa-layer-group nav-icon" style="font-size: 14px"></i>
                <p>Data Level</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/kelas" class="nav-link {{ Request::is('admin/kelas*') ? 'active' : '' }}">
                <i class="fas fa-school nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>Data Kelas</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/mapel" class="nav-link {{ Request::is('admin/mapel*') ? 'active' : '' }}">
                <i class="fas fa-book nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>Data Mata Pelajaran</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') ? 'active' : '' }}">
                <i class="fas fa-user-friends nav-icon" style="font-size: 14px"></i>
                <p>Data Siswa</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/dataAlumni" class="nav-link {{ Request::is('admin/dataAlumni*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>Data Alumni</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/guru" class="nav-link {{ Request::is('admin/guru') || Request::is('admin/guru/*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher nav-icon" style="font-size: 14px"></i>
                <p>Data Guru</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 2. Pembelajaran -->
        <li class="nav-item has-treeview {{ Request::is('admin/jadwal*') || Request::is('admin/jurnal*') || Request::is('admin/rekap-kehadiran-guru*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/jadwal*') || Request::is('admin/jurnal*') || Request::is('admin/rekap-kehadiran-guru*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Pembelajaran
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size:14px">
                <a href="/admin/jadwal" class="nav-link {{ Request::is('admin/jadwal*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="font-size:14px"></i>
                  <p>Jadwal Mata Pelajaran</p>
                </a>
              </li>
              <li class="nav-item" style="font-size:14px">
                <a href="/admin/jurnal" class="nav-link {{ Request::is('admin/jurnal*') ? 'active' : '' }}">
                  <i class="fas fa-book nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Jurnal Mengajar</p>
                </a>
              </li>
              <li class="nav-item" style="font-size:14px">
                <a href="/admin/rekap-kehadiran-guru" class="nav-link {{ Request::is('admin/rekap-kehadiran-guru*') ? 'active' : '' }}">
                  <i class="fas fa-chart-line nav-icon" style="font-size:14px"></i>
                  <p>Monitoring Kehadiran</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 3. Absensi Siswa -->
        <li class="nav-item has-treeview {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/rekap-belum-absen*') || Request::is('admin/dataAbsen*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/rekap-belum-absen*') || Request::is('admin/dataAbsen*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                    Absensi Siswa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-check nav-icon" style="font-size: 14px"></i>
                  <p>Absensi Harian Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Data Absensi Kelas</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa*') ? 'active' : '' }}">
                  <i class="fas fa-clipboard-list nav-icon" style="font-size:14px"></i>
                  <p>Rekap Absensi Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekap-belum-absen" class="nav-link {{ Request::is('admin/rekap-belum-absen*') ? 'active' : '' }}">
                  <i class="fa fa-exclamation-triangle nav-icon" style="color: #ffc107; font-size:14px"></i>
                  <p>Rekap Kelalaian Absen</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 4. Absensi Guru -->
        <li class="nav-item has-treeview {{ Request::is('admin/absensi_guru*') || Request::is('admin/rekapAbsenGuru*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/absensi_guru*') || Request::is('admin/rekapAbsenGuru*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                    Absensi Guru
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/absensi_guru" class="nav-link {{ Request::is('admin/absensi_guru*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-check nav-icon" style="font-size: 14px"></i>
                  <p>Absensi Harian Guru</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsenGuru" class="nav-link {{ Request::is('admin/rekapAbsenGuru*') ? 'active' : '' }}">
                  <i class="fas fa-clipboard-list nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Rekap Absensi Guru</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 5. Guru Piket -->
        <li class="nav-item has-treeview {{ Request::is('admin/guruPiket*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/guruPiket*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-clock"></i>
            <p>
              Guru Piket
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/guruPiket/panel" class="nav-link {{ Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
                <i class="fas fa-clock nav-icon" style="font-size: 14px"></i>
                <p>Panel Guru Piket</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/guruPiket" class="nav-link {{ Request::is('admin/guruPiket') || Request::is('admin/guruPiket/*') && !Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check nav-icon" style="color: lightgreen; font-size: 14px"></i>
                <p>Jadwal Guru Piket</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 6. Point Siswa -->
        <li class="nav-item has-treeview {{ Request::is('admin/point') || Request::is('admin/point/*') || Request::is('admin/pointSiswa*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/point') || Request::is('admin/point/*') || Request::is('admin/pointSiswa*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-circle"></i>
            <p>
              Point Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') || Request::is('admin/point/*') ? 'active' : '' }}">
                <i class="fas fa-list-ol nav-icon" style="font-size: 14px"></i>
                <p>Data Point</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>Data Point Siswa</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 7. E-Dokumen & Akademik -->
        <li class="nav-item has-treeview {{ Request::is('admin/rapot*') || Request::is('admin/modul*') || Request::is('admin/dokumen*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/rapot*') || Request::is('admin/modul*') || Request::is('admin/dokumen*') ? 'active' : '' }}">
                <i class="nav-icon far fa-folder-open"></i>
                <p>
                    Dokumen & Akademik
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/dokumen" class="nav-link {{ Request::is('admin/dokumen*') ? 'active' : '' }}">
                  <i class="fas fa-file-alt nav-icon" style="font-size: 14px"></i>
                  <p>Dokumen Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot*') ? 'active' : '' }}">
                  <i class="fas fa-file-invoice nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Rapot Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul*') ? 'active' : '' }}">
                  <i class="fas fa-book-reader nav-icon" style="font-size: 14px"></i>
                  <p>Modul Siswa</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 8. Hubungan Industri (BKK) -->
        <li class="nav-item has-treeview {{ Request::is('admin/perusahaan*') || Request::is('admin/siswaPkl*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/perusahaan*') || Request::is('admin/siswaPkl*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                    BKK
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/perusahaan" class="nav-link {{ Request::is('admin/perusahaan*') ? 'active' : '' }}">
                  <i class="fas fa-building nav-icon" style="font-size: 14px"></i>
                  <p>Data Perusahaan</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/siswaPkl" class="nav-link {{ Request::is('admin/siswaPkl*') ? 'active' : '' }}">
                  <i class="fas fa-user-tie nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Data Siswa PKL</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 9. Tagihan Keuangan -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/tagihan" class="nav-link {{ Request::is('admin/tagihan*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Tagihan</p>
          </a>
        </li>

        <!-- 10. Pusat Informasi -->
        <li class="nav-item has-treeview {{ Request::is('admin/informasi*') || Request::is('admin/kalender*') || Request::is('admin/berita*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/informasi*') || Request::is('admin/kalender*') || Request::is('admin/berita*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Pusat Informasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi*') ? 'active' : '' }}">
                  <i class="fas fa-info-circle nav-icon" style="font-size:14px"></i>
                  <p>Informasi Sekolah</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/kalender" class="nav-link {{ Request::is('admin/kalender*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Kalender Sekolah</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/berita" class="nav-link {{ Request::is('admin/berita*') ? 'active' : '' }}">
                  <i class="fas fa-newspaper nav-icon" style="font-size:14px"></i>
                  <p>Berita</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 11. Pengaturan Aplikasi -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/setting" class="nav-link {{ Request::is('admin/setting*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cogs"></i>
            <p>Setting</p>
          </a>
        </li>
        @elseif($user->role == 'kurikulum')
        <!-- 1. Data Master -->
        <li class="nav-item has-treeview {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') || Request::is('admin/guru') || Request::is('admin/guru/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') || Request::is('admin/guru') || Request::is('admin/guru/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') ? 'active' : '' }}">
                <i class="fas fa-user-friends nav-icon" style="font-size: 14px"></i>
                <p>Data Siswa</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/guru" class="nav-link {{ Request::is('admin/guru') || Request::is('admin/guru/*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher nav-icon" style="font-size: 14px"></i>
                <p>Data Guru</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 2. Pembelajaran -->
        <li class="nav-item has-treeview {{ Request::is('admin/jadwal*') || Request::is('admin/jurnal*') || Request::is('admin/rekap-kehadiran-guru*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/jadwal*') || Request::is('admin/jurnal*') || Request::is('admin/rekap-kehadiran-guru*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Pembelajaran
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size:14px">
                <a href="/admin/jadwal" class="nav-link {{ Request::is('admin/jadwal*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="font-size:14px"></i>
                  <p>Jadwal Mata Pelajaran</p>
                </a>
              </li>
              <li class="nav-item" style="font-size:14px">
                <a href="/admin/jurnal" class="nav-link {{ Request::is('admin/jurnal*') ? 'active' : '' }}">
                  <i class="fas fa-book nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Jurnal Mengajar</p>
                </a>
              </li>
              <li class="nav-item" style="font-size:14px">
                <a href="/admin/rekap-kehadiran-guru" class="nav-link {{ Request::is('admin/rekap-kehadiran-guru*') ? 'active' : '' }}">
                  <i class="fas fa-chart-line nav-icon" style="font-size:14px"></i>
                  <p>Monitoring Kehadiran</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 3. Absensi Siswa -->
        <li class="nav-item has-treeview {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/dataAbsen*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/dataAbsen*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>
                Absensi Siswa
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-check nav-icon" style="font-size: 14px"></i>
                  <p>Absensi Harian Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/dataAbsen" class="nav-link {{ Request::is('admin/dataAbsen*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Data Absensi Siswa</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 4. Guru Piket -->
        <li class="nav-item has-treeview {{ Request::is('admin/guruPiket*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/guruPiket*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-clock"></i>
            <p>
              Guru Piket
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/guruPiket/panel" class="nav-link {{ Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
                <i class="fas fa-clock nav-icon" style="font-size: 14px"></i>
                <p>Panel Guru Piket</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/guruPiket" class="nav-link {{ Request::is('admin/guruPiket') || Request::is('admin/guruPiket/*') && !Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check nav-icon" style="color: lightgreen; font-size: 14px"></i>
                <p>Jadwal Guru Piket</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 5. Point Siswa -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-circle"></i>
            <p>Point Siswa</p>
          </a>
        </li>

        <!-- 6. E-Rapot -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>E - Rapot</p>
          </a>
        </li>

        <!-- 7. Modul Siswa -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book-reader"></i>
            <p>Modul Siswa</p>
          </a>
        </li>

        <!-- 8. Tagihan Keuangan -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/tagihan" class="nav-link {{ Request::is('admin/tagihan*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Tagihan</p>
          </a>
        </li>

        <!-- 9. Pusat Informasi -->
        <li class="nav-item has-treeview {{ Request::is('admin/informasi*') || Request::is('admin/kalender*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/informasi*') || Request::is('admin/kalender*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Pusat Informasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi*') ? 'active' : '' }}">
                  <i class="fas fa-info-circle nav-icon" style="font-size:14px"></i>
                  <p>Informasi Sekolah</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/kalender" class="nav-link {{ Request::is('admin/kalender*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Kalender Sekolah</p>
                </a>
              </li>
            </ul>
        </li>

        @elseif($user->role == 'kesiswaan')
        <!-- 1. Data Siswa -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>Data Siswa</p>
          </a>
        </li>

        <!-- 2. Jurnal Mengajar -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/jurnal" class="nav-link {{ Request::is('admin/jurnal*') ? 'active' : '' }}">
            <i class="fas fa-book nav-icon"></i>
            <p>Jurnal Mengajar</p>
          </a>
        </li>

        <!-- 3. Absensi Siswa -->
        <li class="nav-item has-treeview {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/rekap-belum-absen*') || Request::is('admin/dataAbsen*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/rekap-belum-absen*') || Request::is('admin/dataAbsen*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                    Absensi Siswa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-check nav-icon" style="font-size: 14px"></i>
                  <p>Absensi Harian Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Data Absensi Kelas</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa*') ? 'active' : '' }}">
                  <i class="fas fa-clipboard-list nav-icon" style="font-size:14px"></i>
                  <p>Rekap Absensi Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekap-belum-absen" class="nav-link {{ Request::is('admin/rekap-belum-absen*') ? 'active' : '' }}">
                  <i class="fa fa-exclamation-triangle nav-icon" style="color: #ffc107; font-size:14px"></i>
                  <p>Rekap Kelalaian Absen</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 4. Panel Guru Piket -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/guruPiket/panel" class="nav-link {{ Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-clock"></i>
            <p>Panel Guru Piket</p>
          </a>
        </li>

        <!-- 5. Point Siswa -->
        <li class="nav-item has-treeview {{ Request::is('admin/point') || Request::is('admin/point/*') || Request::is('admin/pointSiswa*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/point') || Request::is('admin/point/*') || Request::is('admin/pointSiswa*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-circle"></i>
            <p>
              Point Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') || Request::is('admin/point/*') ? 'active' : '' }}">
                <i class="fas fa-list-ol nav-icon" style="font-size: 14px"></i>
                <p>Data Point</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>Data Point Siswa</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- 6. E-Rapot -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>E - Rapot</p>
          </a>
        </li>

        <!-- 7. Informasi Sekolah -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-info-circle"></i>
            <p>Informasi Sekolah</p>
          </a>
        </li>

        <!-- 8. Berita -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/berita" class="nav-link {{ Request::is('admin/berita*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-newspaper"></i>
            <p>Berita</p>
          </a>
        </li>

        @elseif($user->role == 'guru')
        <!-- 1. Data Siswa -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') || Request::is('admin/siswa/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>Data Siswa</p>
          </a>
        </li>

        <!-- 2. Jurnal Mengajar -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/jurnal" class="nav-link {{ Request::is('admin/jurnal*') ? 'active' : '' }}">
            <i class="fas fa-book nav-icon"></i>
            <p>Jurnal Mengajar</p>
          </a>
        </li>

        <!-- 3. Absensi Siswa -->
        <li class="nav-item has-treeview {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/dataAbsen*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') || Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') || Request::is('admin/rekapAbsenSiswa*') || Request::is('admin/dataAbsen*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                    Absensi Siswa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') || Request::is('admin/absensi/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-check nav-icon" style="font-size: 14px"></i>
                  <p>Absensi Harian Siswa</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') || Request::is('admin/rekapAbsen/*') ? 'active' : '' }}">
                  <i class="fas fa-calendar-alt nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>Data Absensi Kelas</p>
                </a>
              </li>
              <li class="nav-item" style="font-size: 14px">
                <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa*') ? 'active' : '' }}">
                  <i class="fas fa-clipboard-list nav-icon" style="font-size:14px"></i>
                  <p>Rekap Absensi Siswa</p>
                </a>
              </li>
            </ul>
        </li>

        <!-- 4. Panel Guru Piket -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/guruPiket/panel" class="nav-link {{ Request::is('admin/guruPiket/panel*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-clock"></i>
            <p>Panel Guru Piket</p>
          </a>
        </li>

        <!-- 5. Point Siswa -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-circle"></i>
            <p>Point Siswa</p>
          </a>
        </li>

        <!-- 6. E-Rapot -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>E - Rapot</p>
          </a>
        </li>

        <!-- 7. Modul Siswa -->
        <li class="nav-item" style="font-size: 14px">
          <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book-reader"></i>
            <p>Modul Siswa</p>
          </a>
        </li>
        @endif
      </ul>
    </nav>
    

    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
