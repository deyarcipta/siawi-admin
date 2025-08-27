<!-- Main Sidebar Container -->
{{-- <aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="{{ asset('lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="#" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Import Data Master</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Jurusan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Level</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Kelas</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/dataSiswa" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Data Siswa
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/dataSiswa" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Data Alumni
            </p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Absensi Siswa
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Jadwal Mata Pelajaran
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Informasi Sekolah
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Kalender Sekolah
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dokumen
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Tagihan
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Point Siswa
            </p>
          </a>
        </li>
      </ul>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Modul Siswa
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Berita
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Data Guru
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Pengaturan
          </p>
        </a>
      </li>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside> --}}

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
        <li class="nav-item has-treeview {{ Request::is('admin/jurusan*') || Request::is('admin/importDataMaster*') || Request::is('admin/level*') || Request::is('admin/kelas*') || Request::is('admin/mapel*') || Request::is('admin/dataMaster/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Request::is('admin/jurusan*') || Request::is('admin/importDataMaster*') || Request::is('admin/level*') || Request::is('admin/kelas*') || Request::is('admin/mapel*') || Request::is('admin/dataMaster/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/importDataMaster" class="nav-link {{ Request::is('admin/importDataMaster') ? 'active' : '' }}">
                <i class="fa fa-upload nav-icon" style="font-size: 14px"></i>
                <p>Import Data Master</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/jurusan" class="nav-link {{ Request::is('admin/jurusan') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon " style="color: lightgreen; font-size:14px"></i>
                <p>Data Jurusan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/level" class="nav-link {{ Request::is('admin/level') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>Data Level</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/kelas" class="nav-link {{ Request::is('admin/kelas') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon"  style="color: lightgreen; font-size:14px"></i>
                <p>Data Kelas</p>
              </a>
            </li>
            <li class="nav-item" style="font-size: 14px">
              <a href="/admin/mapel" class="nav-link {{ Request::is('admin/mapel') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon " style="color: lightgreen; font-size:14px"></i>
                <p>Data Mata Pelajaran</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Data Siswa
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin/dataAlumni" class="nav-link {{ Request::is('admin/dataAlumni') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-graduate"></i>
            <p>
              Data Alumni
            </p>
          </a>
        </li>
        
        <li class="nav-item has-treeview {{ 
          Request::is('admin/absensi') ||
          Request::is('admin/rekapAbsen') || 
          Request::is('admin/rekapAbsenSiswa') || 
          Request::is('admin/dataAbsen') ? 'menu-open' : '' }}">
          
            <a href="#" class="nav-link {{ 
                Request::is('admin/absensi') || 
                Request::is('admin/rekapAbsen') ||
                Request::is('admin/rekapAbsenSiswa') || 
                Request::is('admin/dataAbsen') ? 'active' : '' }}">
                
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                    Data Abseni Siswa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>
                  Absensi Harian Siswa
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Data Absensi Kelas
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style=" font-size:14px"></i>
                <p>
                  Rekap Absensi Siswa
                </p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="/admin/viewRfidAbsen" class="nav-link {{ Request::is('admin/viewRfidAbsen') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Rfid Absensi Siswa
                </p>
              </a>
            </li> --}}
          </ul>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/absensi_guru') || 
          Request::is('admin/rekapAbsenGuru') ? 'menu-open' : '' }}">
          
            <a href="#" class="nav-link {{ 
                Request::is('admin/absensi_guru') || 
                Request::is('admin/rekapAbsenGuru') ? 'active' : '' }}">
                
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                    Data Abseni Guru
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/absensi_guru" class="nav-link {{ Request::is('admin/absensi_guru') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>
                  Absensi Harian Guru
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsenGuru" class="nav-link {{ Request::is('admin/rekapAbsenGuru') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Rekap Absensi Guru
                </p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="/admin/viewRfidAbsen" class="nav-link {{ Request::is('admin/viewRfidAbsen') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Rfid Absensi Guru
                </p>
              </a>
            </li> --}}
          </ul>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/informasi') || Request::is('admin/kalender') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ 
              Request::is('admin/informasi') || Request::is('admin/kalender') ? 'active' : '' }}">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Pusat Informasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi') ? 'active' : '' }}">
                  <i class="fa fa-check nav-icon" style="font-size:14px"></i>
                  <p>
                    Informasi Sekolah
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/kalender" class="nav-link {{ Request::is('admin/kalender') ? 'active' : '' }}">
                  <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>
                    Kalender Sekolah
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/berita" class="nav-link {{ Request::is('admin/berita') ? 'active' : '' }}">
                  <i class="fa fa-check nav-icon" style="font-size:14px"></i>
                  <p>
                    Berita
                  </p>
                </a>
              </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/jadwal" class="nav-link {{ Request::is('admin/jadwal') ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>
              Jadwal Mata Pelajaran
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/perusahaan') || 
          Request::is('admin/siswaPkl') ? 'menu-open' : '' }}">
          
            <a href="#" class="nav-link {{ 
                Request::is('admin/perusahaan') || 
                Request::is('admin/siswaPkl') ? 'active' : '' }}">
                
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                    BKK
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/perusahaan" class="nav-link {{ Request::is('admin/perusahaan') ? 'active' : '' }}">
                  <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                  <p>
                    Data Perusahaan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/siswaPkl" class="nav-link {{ Request::is('admin/siswaPkl') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>
                    Data Siswa PKL
                  </p>
                </a>
              </li>
            </ul>
        </li>
        {{-- <li class="nav-header">EXAMPLES</li> --}}
        <li class="nav-item has-treeview {{ 
          Request::is('admin/rapot') || 
          Request::is('admin/modul') ||
          Request::is('admin/dokumen') ? 'menu-open' : '' }}">
          
            <a href="#" class="nav-link {{ 
                Request::is('admin/rapot') || 
                Request::is('admin/modul') ||
                Request::is('admin/dokumen') ? 'active' : '' }}">
                
                <i class="nav-icon far fa-image"></i>
                <p>
                    Dokumen
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/dokumen" class="nav-link {{ Request::is('admin/dokumen') ? 'active' : '' }}">
                  <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                  <p>
                    Dokumen Siswa
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                  <p>
                    Rapot Siswa
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul') ? 'active' : '' }}">
                 <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                  <p>
                    Modul Siswa
                  </p>
                </a>
              </li>
            </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/tagihan" class="nav-link {{ Request::is('admin/tagihan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Tagihan
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/point') || 
          Request::is('admin/pointSiswa') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ 
            Request::is('admin/point') || 
            Request::is('admin/pointSiswa') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Point Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>Data Point</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon"  style="color: lightgreen; font-size:14px"></i>
                <p>Data Point Siswa</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/guru" class="nav-link {{ Request::is('admin/guru') ? 'active' : '' }}">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Data Guru
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin/setting" class="nav-link {{ Request::is('admin/setting') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              Setting
            </p>
          </a>
        </li>
        {{-- <li class="nav-item">
          <a href="/admin/rfid" class="nav-link {{ Request::is('admin/rfid') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              rfid
            </p>
          </a>
        </li> --}}
        @elseif($user->role == 'kurikulum')
        <li class="nav-item">
          <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Data Siswa
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('absensi*') || Request::is('admin/dataAbsen/*') || Request::is('admin/absensi/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ 
              Request::is('absensi*') || Request::is('admin/dataAbsen/*') || Request::is('admin/absensi/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Data Abseni
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>
                  Absensi Harian Siswa
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/dataAbsen" class="nav-link {{ Request::is('admin/dataAbsen') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Data Absensi Siswa
                </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/informasi/*') || Request::is('admin/kalender/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ 
              Request::is('admin/informasi/*') || Request::is('admin/kalender/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Pusat Informasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-info-circle"></i>
                  <p>
                    Informasi Sekolah
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/kalender" class="nav-link {{ Request::is('admin/kalender') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>
                    Kalender Sekolah
                  </p>
                </a>
              </li>
            </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/jadwal" class="nav-link {{ Request::is('admin/jadwal') ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>
              Jadwal Mata Pelajaran
            </p>
          </a>
        </li>
        {{-- <li class="nav-header">EXAMPLES</li> --}}
        <li class="nav-item">
          <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot') ? 'active' : '' }}">
            <i class="nav-icon far fa-image"></i>
            <p>
              E - Rapot
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin/tagihan" class="nav-link {{ Request::is('admin/tagihan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Tagihan
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
        Request::is('point*') || Request::is('admin/pointSiswa/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ 
            Request::is('point*') || Request::is('admin/pointSiswa/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Point Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>Data Point</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon"  style="color: lightgreen; font-size:14px"></i>
                <p>Data Point Siswa</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Modul Siswa
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin/guru" class="nav-link {{ Request::is('admin/guru') ? 'active' : '' }}">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Data Guru
            </p>
          </a>
        </li>
        @elseif($user->role == 'kesiswaan')
        <li class="nav-item">
          <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Data Siswa
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/absensi') ||
          Request::is('admin/rekapAbsen') || 
          Request::is('admin/rekapAbsenSiswa') || 
          Request::is('admin/dataAbsen') ? 'menu-open' : '' }}">
          
            <a href="#" class="nav-link {{ 
                Request::is('admin/absensi') || 
                Request::is('admin/rekapAbsen') ||
                Request::is('admin/rekapAbsenSiswa') || 
                Request::is('admin/dataAbsen') ? 'active' : '' }}">
                
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                    Data Abseni Siswa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>
                  Absensi Harian Siswa
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Data Absensi Kelas
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style=" font-size:14px"></i>
                <p>
                  Rekap Absensi Siswa
                </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/informasi" class="nav-link {{ Request::is('admin/informasi') ? 'active' : '' }}">
            <i class="nav-icon fas fa-info-circle"></i>
            <p>
              Informasi Sekolah
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot') ? 'active' : '' }}">
            <i class="nav-icon far fa-image"></i>
            <p>
              E - Rapot
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
        Request::is('point*') || Request::is('admin/pointSiswa/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ 
            Request::is('point*') || Request::is('admin/pointSiswa/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Point Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>Data Point</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon"  style="color: lightgreen; font-size:14px"></i>
                <p>Data Point Siswa</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/berita" class="nav-link {{ Request::is('admin/berita') ? 'active' : '' }}">
            <i class="nav-icon far fa-plus-square"></i>
            <p>
              Berita
            </p>
          </a>
        </li>
        @elseif($user->role == 'guru')
        <li class="nav-item">
          <a href="/admin/siswa" class="nav-link {{ Request::is('admin/siswa') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Data Siswa
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
          Request::is('admin/absensi') ||
          Request::is('admin/rekapAbsen') || 
          Request::is('admin/rekapAbsenSiswa') || 
          Request::is('admin/dataAbsen') ? 'menu-open' : '' }}">
          
            <a href="#" class="nav-link {{ 
                Request::is('admin/absensi') || 
                Request::is('admin/rekapAbsen') ||
                Request::is('admin/rekapAbsenSiswa') || 
                Request::is('admin/dataAbsen') ? 'active' : '' }}">
                
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                    Data Abseni Siswa
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>
                  Absensi Harian Siswa
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsen" class="nav-link {{ Request::is('admin/rekapAbsen') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style="color: lightgreen; font-size:14px"></i>
                <p>
                  Data Absensi Kelas
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/rekapAbsenSiswa" class="nav-link {{ Request::is('admin/rekapAbsenSiswa') ? 'active' : '' }}">
                <i class="fa fa-check nav-icon" style=" font-size:14px"></i>
                <p>
                  Rekap Absensi Siswa
                </p>
              </a>
            </li>
          </ul>
        </li>
        <!-- <li class="nav-item">
          <a href="/admin/absensi" class="nav-link {{ Request::is('admin/absensi') ? 'active' : '' }}">
            <i class="nav-icon fas fa-edit"></i>
            <p>
              Absensi Siswa
            </p>
          </a>
        </li> -->
        <li class="nav-item">
          <a href="/admin/rapot" class="nav-link {{ Request::is('admin/rapot') ? 'active' : '' }}">
            <i class="nav-icon far fa-image"></i>
            <p>
              E - Rapot
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ 
        Request::is('point*') || Request::is('admin/pointSiswa/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ 
            Request::is('point*') || Request::is('admin/pointSiswa/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Point Siswa
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/point" class="nav-link {{ Request::is('admin/point') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon" style="font-size: 14px"></i>
                <p>Data Point</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/pointSiswa" class="nav-link {{ Request::is('admin/pointSiswa') ? 'active' : '' }}" style="font-size: 14px">
                <i class="fa fa-check nav-icon"  style="color: lightgreen; font-size:14px"></i>
                <p>Data Point Siswa</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/admin/modul" class="nav-link {{ Request::is('admin/modul') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Modul Siswa
            </p>
          </a>
        </li>
        @endif
      </ul>
    </nav>
    

    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
