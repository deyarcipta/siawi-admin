@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Siswa</a></li>
            <li class="breadcrumb-item active">Tambah Siswa</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Tambah Siswa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/siswa" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <span style="font-size: 16; font-weight:bold;">Data Diri Siswa</span>
                <div class="row mt-2">
                  <div class="form-group col-6">
                    <label for="nis">NIS</label>
                    <input type="text" class="form-control" id="nis" placeholder="Enter Nis" name="nis" value="{{old('nis')}}">
                    @error('nis')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nisn">NISN</label>
                    <input type="text" class="form-control" id="nisn" placeholder="Enter Nisn" name="nisn" value="{{old('nisn')}}">
                    @error('nisn')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="nama_siswa">Nama Siswa</label>
                    <input type="text" class="form-control" id="nama_siswa" placeholder="Enter Nama Siswa" name="nama_siswa" value="{{old('nama_siswa')}}">
                    @error('nama_siswa')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" id="password" placeholder="Enter password" name="password" value="{{old('password')}}">
                    @error('password')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="kode_level">Pilih Level</label>
                    <select class="form-control" name="kode_level" id="kode_level">
                      <option value="{{old('kode_level')}}">Pilih Level</option>
                      @foreach ($level as $lvl)
                        <option value="{{$lvl->id_level}}">{{$lvl->kode_level}}</option>
                      @endforeach
                    </select>
                    @error('kode_level')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="kode_kelas">Pilih Kelas</label>
                    <select class="form-control" name="kode_kelas" id="kode_kelas">
                      <option value="{{old('kode_kelas')}}">Pilih Kelas</option>
                      @foreach ($kelas as $lvl)
                        <option value="{{$lvl->id_kelas}}">{{$lvl->kode_kelas}}</option>
                      @endforeach
                    </select>
                    @error('kode_kelas')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="kode_jurusan">Pilih Jurusan</label>
                    <select class="form-control" name="kode_jurusan" id="kode_jurusan">
                      <option value="{{old('kode_jurusan')}}">Pilih Jurusan</option>
                      @foreach ($jurusan as $jur)
                        <option value="{{$jur->id_jurusan}}">{{$jur->kode_jurusan}}</option>
                      @endforeach
                    </select>
                    @error('kode_jurusan')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-3">
                    <label for="tmpt_lahir">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tmpt_lahir" placeholder="Enter Tempat Lahir" name="tmpt_lahir" value="{{old('tmpt_lahir')}}">
                    @error('tmpt_lahir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" placeholder="Enter tgl_lahir" name="tgl_lahir" value="{{old('tgl_lahir')}}">
                    @error('tgl_lahir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="agama">Agama</label>
                    <select class="form-control" name="agama" id="agama">
                      <option value="{{old('agama')}}">Pilih Agama</option>
                      <option value="islam">Islam</option>
                      <option value="katolik">Katolik</option>
                      <option value="protestan">Protestan</option>
                      <option value="hindu">Hindu</option>
                      <option value="budha">Budha</option>
                    </select>
                    @error('agama')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                      <option value="{{old('jenis_kelamin')}}">Pilih Jenis Kelamin</option>
                      <option value="L">Laki-laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-3">
                    <label for="no_hp">No HP</label>
                    <input type="text" class="form-control" id="no_hp" placeholder="Enter No Hp" name="no_hp" value="{{old('no_hp')}}">
                    @error('no_hp')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="no_tlpn">No. Telp</label>
                    <input type="text" class="form-control" id="no_tlpn" placeholder="Enter No. Telp" name="no_tlpn" value="{{old('no_tlpn')}}">
                    @error('no_tlpn')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email" value="{{old('email')}}">
                    @error('email')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="foto">Foto Siswa</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" id='foto-label' for="foto">Choose file</label>
                        <script>
                          document.getElementById('foto').addEventListener('change', function(e) {
                              var fileName = e.target.files[0].name;
                              var label = document.getElementById('foto-label');
                              label.textContent = fileName;
                          });
                      </script>
                      </div>
                      {{-- <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div> --}}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-12">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" placeholder="Enter Alamat" name="alamat" value="{{old('alamat')}}"></textarea>
                    @error('alamat')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-2">
                    <label for="rt">RT</label>
                    <input type="text" class="form-control" id="rt" placeholder="Enter RT" name="rt" value="{{old('rt')}}">
                    @error('rt')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-2">
                    <label for="rw">RW</label>
                    <input type="text" class="form-control" id="rw" placeholder="Enter RW" name="rw" value="{{old('rw')}}">
                    @error('rw')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-2">
                    <label for="no_rumah">No. Rumah</label>
                    <input type="text" class="form-control" id="no_rumah" placeholder="Enter No Rumah" name="no_rumah" value="{{old('no_rumah')}}">
                    @error('no_rumah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="kel">Kelurahan</label>
                    <input type="text" class="form-control" id="kel" placeholder="Enter Kelurahan" name="kel" value="{{old('kel')}}">
                    @error('kel')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="form-group col-4">
                    <label for="kec">Kecamatan</label>
                    <input type="text" class="form-control" id="kec" placeholder="Enter Kecamatan" name="kec" value="{{old('kec')}}">
                    @error('kec')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="kota">Kota</label>
                    <input type="text" class="form-control" id="kota" placeholder="Enter Kota" name="kota" value="{{old('kota')}}">
                    @error('kota')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="prov">Provinsi</label>
                    <input type="text" class="form-control" id="prov" placeholder="Enter Provinsi" name="prov" value="{{old('prov')}}">
                    @error('prov')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <span style="font-size: 16; font-weight:bold;">Data Orang Tua ( ayah )</span>
                <div class="row mt-2">
                  <div class="form-group col-6">
                    <label for="nik_ayah">NIK Ayah</label>
                    <input type="text" class="form-control" id="nik_ayah" placeholder="Enter NIK Ayah" name="nik_ayah" value="{{old('nik_ayah')}}">
                    @error('nik_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nama_ayah">Nama Ayah</label>
                    <input type="text" class="form-control" id="nama_ayah" placeholder="Enter Nama Ayah" name="nama_ayah" value="{{old('nama_ayah')}}">
                    @error('nama_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="tmpt_lahir_ayah">Tempat Lahir Ayah</label>
                    <input type="text" class="form-control" id="tmpt_lahir_ayah" placeholder="Enter Tempat Lahir Ayah" name="tmpt_lahir_ayah" value="{{old('tmpt_lahir_ayah')}}">
                    @error('tmpt_lahir_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="tgl_lahir_ayah">Tanggal Lahir Ayah</label>
                    <input type="date" class="form-control" id="tgl_lahir_ayah" name="tgl_lahir_ayah" value="{{old('tgl_lahir_ayah')}}">
                    @error('tgl_lahir_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="pendidikan_ayah">Pendidikan Ayah</label>
                    <select class="form-control" name="pendidikan_ayah" id="pendidikan_ayah">
                      <option value="{{old('pendidikan_ayah')}}">Pilih Pendidikan Ayah</option>
                      <option value="sd">SD</option>
                      <option value="smp">SMP</option>
                      <option value="sma">SMA/SMK</option>
                      <option value="s1">S1</option>
                      <option value="s2">S2</option>
                      <option value="s3">S3</option>
                    </select>
                    @error('pendidikan_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="form-group col-6">
                    <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                    <select class="form-control" name="pekerjaan_ayah" id="pekerjaan_ayah">
                      <option value="{{old('pekerjaan_ayah')}}">Pilih Pendidikan Ayah</option>
                      <option value="karyawan">Karyawan</option>
                      <option value="pns">PNS</option>
                      <option value="tni/polisi">TNI/POLISI</option>
                      <option value="wiraswasta">Wiraswasta</option>
                      <option value="guru">Guru</option>
                      <option value="lainnya">Lainnya</option>
                    </select>
                    @error('pekerjaan_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="penghasilan_ayah">Penghasilan Ayah</label>
                    <select class="form-control" name="penghasilan_ayah" id="penghasilan_ayah">
                      <option value="{{old('penghasilan_ayah')}}">Pilih Penghasilan Ayah</option>
                      <option value="-">-</option>
                      <option value="Rp.500.000 - Rp 2.000.000">Rp.500.000 - Rp 2.000.000</option>
                      <option value="Rp.2.100.000 - Rp 3.000.000">Rp.2.100.000 - Rp 3.000.000</option>
                      <option value="Rp.3.100.000 - Rp 4.000.000">Rp.3.100.000 - Rp 4.000.000</option>
                      <option value="Rp.4.100.000 - Rp 5.000.000">Rp.4.100.000 - Rp 5.000.000</option>
                      <option value=">Rp.5.000.000">>Rp.5.000.000</option>
                    </select>
                    @error('penghasilan_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <span style="font-size: 16; font-weight:bold;">Data Orang Tua ( ibu )</span>
                <div class="row mt-2">
                  <div class="form-group col-6">
                    <label for="nik_ibu">NIK Ibu</label>
                    <input type="text" class="form-control" id="nik_ibu" placeholder="Enter NIK ibu" name="nik_ibu" value="{{old('nik_ibu')}}">
                    @error('nik_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nama_ibu">Nama Ibu</label>
                    <input type="text" class="form-control" id="nama_ibu" placeholder="Enter Nama ibu" name="nama_ibu" value="{{old('nama_ibu')}}">
                    @error('nama_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="tmpt_lahir_ibu">Tempat Lahir Ibu</label>
                    <input type="text" class="form-control" id="tmpt_lahir_ibu" placeholder="Enter Tempat Lahir ibu" name="tmpt_lahir_ibu" value="{{old('tmpt_lahir_ibu')}}">
                    @error('tmpt_lahir_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="tgl_lahir_ibu">Tanggal Lahir Ibu</label>
                    <input type="date" class="form-control" id="tgl_lahir_ibu" name="tgl_lahir_ibu" value="{{old('tgl_lahir_ibu')}}">
                    @error('tgl_lahir_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="pendidikan_ibu">Pendidikan Ibu</label>
                    <select class="form-control" name="pendidikan_ibu" id="pendidikan_ibu">
                      <option value="{{old('pendidikan_ibu')}}">Pilih Pendidikan ibu</option>
                      <option value="sd">SD</option>
                      <option value="smp">SMP</option>
                      <option value="sma">SMA/SMK</option>
                      <option value="s1">S1</option>
                      <option value="s2">S2</option>
                      <option value="s3">S3</option>
                    </select>
                    @error('pendidikan_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="form-group col-6">
                    <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                    <select class="form-control" name="pekerjaan_ibu" id="pekerjaan_ibu">
                      <option value="{{old('pekerjaan_ibu')}}">Pilih Pendidikan ibu</option>
                      <option value="karyawan">Karyawan</option>
                      <option value="pns">PNS</option>
                      <option value="tni/polisi">TNI/POLISI</option>
                      <option value="wiraswasta">Wiraswasta</option>
                      <option value="guru">Guru</option>
                      <option value="ibu rumah tangga">Ibu Rumah Tangga</option>
                      <option value="lainnya">Lainnya</option>
                    </select>
                    @error('pekerjaan_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="penghasilan_ibu">Penghasilan Ibu</label>
                    <select class="form-control" name="penghasilan_ibu" id="penghasilan_ibu">
                      <option value="{{old('penghasilan_ibu')}}">Pilih Penghasilan ibu</option>
                      <option value="-">-</option>
                      <option value="Rp.500.000 - Rp 2.000.000">Rp.500.000 - Rp 2.000.000</option>
                      <option value="Rp.2.100.000 - Rp 3.000.000">Rp.2.100.000 - Rp 3.000.000</option>
                      <option value="Rp.3.100.000 - Rp 4.000.000">Rp.3.100.000 - Rp 4.000.000</option>
                      <option value="Rp.4.100.000 - Rp 5.000.000">Rp.4.100.000 - Rp 5.000.000</option>
                      <option value=">Rp.5.000.000">>Rp.5.000.000</option>
                    </select>
                    @error('penghasilan_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <span style="font-size: 16; font-weight:bold;">Data Orang Tua ( wali )</span>
                <div class="row mt-2">
                  <div class="form-group col-6">
                    <label for="nik_wali">NIK Wali</label>
                    <input type="text" class="form-control" id="nik_wali" placeholder="Enter NIK wali" name="nik_wali" value="{{old('nik_wali')}}">
                    @error('nik_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nama_wali">Nama Wali</label>
                    <input type="text" class="form-control" id="nama_wali" placeholder="Enter Nama wali" name="nama_wali" value="{{old('nama_wali')}}">
                    @error('nama_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="tmpt_lahir_wali">Tempat Lahir Wali</label>
                    <input type="text" class="form-control" id="tmpt_lahir_wali" placeholder="Enter Tempat Lahir wali" name="tmpt_lahir_wali" value="{{old('tmpt_lahir_wali')}}">
                    @error('tmpt_lahir_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="tgl_lahir_wali">Tanggal Lahir Wali</label>
                    <input type="date" class="form-control" id="tgl_lahir_wali" name="tgl_lahir_wali" value="{{old('tgl_lahir_wali')}}">
                    @error('tgl_lahir_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="pendidikan_wali">Pendidikan Wali</label>
                    <select class="form-control" name="pendidikan_wali" id="pendidikan_wali">
                      <option value="{{old('pendidikan_wali')}}">Pilih Pendidikan wali</option>
                      <option value="sd">SD</option>
                      <option value="smp">SMP</option>
                      <option value="sma">SMA/SMK</option>
                      <option value="s1">S1</option>
                      <option value="s2">S2</option>
                      <option value="s3">S3</option>
                    </select>
                    @error('pendidikan_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="form-group col-6">
                    <label for="pekerjaan_wali">Pekerjaan Wali</label>
                    <select class="form-control" name="pekerjaan_wali" id="pekerjaan_wali">
                      <option value="{{old('pekerjaan_wali')}}">Pilih Pendidikan wali</option>
                      <option value="karyawan">Karyawan</option>
                      <option value="pns">PNS</option>
                      <option value="tni/polisi">TNI/POLISI</option>
                      <option value="wiraswasta">Wiraswasta</option>
                      <option value="guru">Guru</option>
                      <option value="ibu rumah tangga">Ibu Rumah Tangga</option>
                      <option value="lainnya">Lainnya</option>
                    </select>
                    @error('pekerjaan_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="penghasilan_wali">Penghasilan Wali</label>
                    <select class="form-control" name="penghasilan_wali" id="penghasilan_wali">
                      <option value="{{old('penghasilan_wali')}}">Pilih Penghasilan wali</option>
                      <option value="-">-</option>
                      <option value="Rp.500.000 - Rp 2.000.000">Rp.500.000 - Rp 2.000.000</option>
                      <option value="Rp.2.100.000 - Rp 3.000.000">Rp.2.100.000 - Rp 3.000.000</option>
                      <option value="Rp.3.100.000 - Rp 4.000.000">Rp.3.100.000 - Rp 4.000.000</option>
                      <option value="Rp.4.100.000 - Rp 5.000.000">Rp.4.100.000 - Rp 5.000.000</option>
                      <option value=">Rp.5.000.000">>Rp.5.000.000</option>
                    </select>
                    @error('penghasilan_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
@endsection