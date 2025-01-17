@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Siswa</a></li>
            <li class="breadcrumb-item active">Edit Siswa</li>
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
              <h3 class="card-title">Form Edit Siswa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/siswa/{{$edit->id_siswa}}" method="POST" enctype="multipart/form-data">
              @method('PUT')
              @csrf
              <div class="card-body">
                <span style="font-size: 16; font-weight:bold;">Data Diri Siswa</span>
                <div class="row mt-2">
                  <div class="form-group col-4">
                    <label for="nis">NIS</label>
                    <input type="text" readonly class="form-control" id="nis" placeholder="Enter Nis" name="nis" value="{{$edit->nis}}">
                    @error('nis')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="nisn">NISN</label>
                    <input type="text" class="form-control" id="nisn" placeholder="Enter Nisn" name="nisn" value="{{$edit->nisn}}">
                    @error('nisn')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="rfid">RFID</label>
                    <input type="text" class="form-control" id="rfid" placeholder="Enter rfid" name="rfid" value="{{$edit->rfid}}" autocomplete="off">
                    @error('rfid')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-12">
                    <label for="nama_siswa">Nama Siswa</label>
                    <input type="text" class="form-control" id="nama_siswa" placeholder="Enter Nama Siswa" name="nama_siswa" value="{{$edit->nama_siswa}}">
                    @error('nama_siswa')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  {{-- <div class="form-group col-6">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" id="password" placeholder="Enter password" name="password" hidden>
                    @error('password')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div> --}}
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="kode_level">Pilih Level</label>
                    <select class="form-control" name="kode_level" id="kode_level">
                        
                      @foreach ($level as $lvl)
                        <option value="{{ $lvl->id_level }}" {{ $edit->id_level == $lvl->id_level ? 'selected' : '' }}> {{ $lvl->nama_level }}</option>
                      @endforeach
                    </select>
                    @error('kode_level')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="kode_kelas">Pilih Kelas</label>
                    <select class="form-control" name="kode_kelas" id="kode_kelas">
                      @foreach ($kelas as $kls)
                      <option value="{{ $kls->id_kelas }}" {{ $edit->id_kelas == $kls->id_kelas ? 'selected' : '' }}> {{ $kls->nama_kelas }}</option>
                    @endforeach
                    </select>
                    @error('kode_kelas')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="kode_jurusan">Pilih Jurusan</label>
                    <select class="form-control" name="kode_jurusan" id="kode_jurusan">
                      <option value="{{$edit->jurusan}}">{{$edit->jurusan}}</option>
                      @foreach ($jurusan as $jur)
                        <option value="{{ $jur->id_jurusan }}" {{ $edit->id_jurusan == $jur->id_jurusan ? 'selected' : '' }}> {{ $jur->nama_jurusan }}</option>
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
                    <input type="text" class="form-control" id="tmpt_lahir" placeholder="Enter Tempat Lahir" name="tmpt_lahir" value="{{$edit->tmpt_lahir}}">
                    @error('tmpt_lahir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" placeholder="Enter tgl_lahir" name="tgl_lahir" value="{{$edit->tgl_lahir}}">
                    @error('tgl_lahir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="agama">Agama</label>
                    <select class="form-control" name="agama" id="agama">
                      <option value="{{$edit->agama}}">{{$edit->agama}}</option>
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
                      <option value="{{$edit->jenis_kelamin}}">
                        @if ($edit->jenis_kelamin == "L")
                          Laki - Laki
                        @else
                          Perempuan
                        @endif
                      </option>
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
                    <input type="text" class="form-control" id="no_hp" placeholder="Enter No Hp" name="no_hp" value="{{$edit->no_hp}}">
                    @error('no_hp')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="no_tlpn">No. Telp</label>
                    <input type="text" class="form-control" id="no_tlpn" placeholder="Enter No. Telp" name="no_tlpn" value="{{$edit->no_tlpn}}">
                    @error('no_tlpn')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email" value="{{$edit->email}}">
                    @error('email')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-3">
                    <label for="foto">Foto Siswa</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" id="foto-label" for="foto">Choose file</label>
                        <script>
                          document.getElementById('foto').addEventListener('change', function(e) {
                              var fileName = e.target.files[0].name;
                              var label = document.getElementById('foto-label');
                              label.textContent = fileName;
                          });
                      </script>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-12">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" placeholder="Enter Alamat" name="alamat" value="{{$edit->alamat}}">{{$edit->alamat}}</textarea>
                    @error('alamat')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-2">
                    <label for="rt">RT</label>
                    <input type="text" class="form-control" id="rt" placeholder="Enter RT" name="rt" value="{{$edit->rt}}">
                    @error('rt')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-2">
                    <label for="rw">RW</label>
                    <input type="text" class="form-control" id="rw" placeholder="Enter RW" name="rw" value="{{$edit->rw}}">
                    @error('rw')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-2">
                    <label for="no_rumah">No. Rumah</label>
                    <input type="text" class="form-control" id="no_rumah" placeholder="Enter No Rumah" name="no_rumah" value="{{$edit->no_rumah}}">
                    @error('no_rumah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="kel">Kelurahan</label>
                    <input type="text" class="form-control" id="kel" placeholder="Enter Kelurahan" name="kel" value="{{$edit->kel}}">
                    @error('kel')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="form-group col-4">
                    <label for="kec">Kecamatan</label>
                    <input type="text" class="form-control" id="kec" placeholder="Enter Kecamatan" name="kec" value="{{$edit->kec}}">
                    @error('kec')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="kota">Kota</label>
                    <input type="text" class="form-control" id="kota" placeholder="Enter Kota" name="kota" value="{{$edit->kota}}">
                    @error('kota')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="prov">Provinsi</label>
                    <input type="text" class="form-control" id="prov" placeholder="Enter Provinsi" name="prov" value="{{$edit->prov}}">
                    @error('prov')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <span style="font-size: 16; font-weight:bold;">Data Orang Tua ( ayah )</span>
                <div class="row mt-2">
                  <div class="form-group col-6">
                    <label for="nik_ayah">NIK Ayah</label>
                    <input type="text" class="form-control" id="nik_ayah" placeholder="Enter NIK Ayah" name="nik_ayah" value="{{$edit->nik_ayah}}">
                    @error('nik_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nama_ayah">Nama Ayah</label>
                    <input type="text" class="form-control" id="nama_ayah" placeholder="Enter Nama Ayah" name="nama_ayah" value="{{$edit->nama_ayah}}">
                    @error('nama_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="tmpt_lahir_ayah">Tempat Lahir Ayah</label>
                    <input type="text" class="form-control" id="tmpt_lahir_ayah" placeholder="Enter Tempat Lahir Ayah" name="tmpt_lahir_ayah" value="{{$edit->tmpt_lahir_ayah}}">
                    @error('tmpt_lahir_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="tgl_lahir_ayah">Tanggal Lahir Ayah</label>
                    <input type="date" class="form-control" id="tgl_lahir_ayah" name="tgl_lahir_ayah" value="{{$edit->tgl_lahir_ayah}}">
                    @error('tgl_lahir_ayah')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="pendidikan_ayah">Pendidikan Ayah</label>
                    <select class="form-control" name="pendidikan_ayah" id="pendidikan_ayah">
                      <option value="{{$edit->pendidikan_ayah}}">{{$edit->pendidikan_ayah}}</option>
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
                      <option value="{{$edit->pekerjaan_ayah}}">{{$edit->pekerjaan_ayah}}</option>
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
                      <option value="{{$edit->penghasilan_ayah}}">{{$edit->penghasilan_ayah}}</option>
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
                    <input type="text" class="form-control" id="nik_ibu" placeholder="Enter NIK ibu" name="nik_ibu" value="{{$edit->nik_ibu}}">
                    @error('nik_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nama_ibu">Nama Ibu</label>
                    <input type="text" class="form-control" id="nama_ibu" placeholder="Enter Nama ibu" name="nama_ibu" value="{{$edit->nama_ibu}}">
                    @error('nama_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="tmpt_lahir_ibu">Tempat Lahir Ibu</label>
                    <input type="text" class="form-control" id="tmpt_lahir_ibu" placeholder="Enter Tempat Lahir ibu" name="tmpt_lahir_ibu" value="{{$edit->tmpt_lahir_ibu}}">
                    @error('tmpt_lahir_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="tgl_lahir_ibu">Tanggal Lahir Ibu</label>
                    <input type="date" class="form-control" id="tgl_lahir_ibu" name="tgl_lahir_ibu" value="{{$edit->tgl_lahir_ibu}}">
                    @error('tgl_lahir_ibu')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="pendidikan_ibu">Pendidikan Ibu</label>
                    <select class="form-control" name="pendidikan_ibu" id="pendidikan_ibu">
                      <option value="{{$edit->pendidikan_ibu}}">{{$edit->pendidikan_ibu}}</option>
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
                      <option value="{{$edit->pekerjaan_ibu}}">{{$edit->pekerjaan_ibu}}</option>
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
                      <option value="{{$edit->penghasilan_ibu}}">{{$edit->penghasilan_ibu}}</option>
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
                    <input type="text" class="form-control" id="nik_wali" placeholder="Enter NIK wali" name="nik_wali" value="{{$edit->nik_wali}}">
                    @error('nik_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nama_wali">Nama Wali</label>
                    <input type="text" class="form-control" id="nama_wali" placeholder="Enter Nama wali" name="nama_wali" value="{{$edit->nama_wali}}">
                    @error('nama_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="tmpt_lahir_wali">Tempat Lahir Wali</label>
                    <input type="text" class="form-control" id="tmpt_lahir_wali" placeholder="Enter Tempat Lahir wali" name="tmpt_lahir_wali" value="{{$edit->tmpt_lahir_wali}}">
                    @error('tmpt_lahir_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="tgl_lahir_wali">Tanggal Lahir Wali</label>
                    <input type="date" class="form-control" id="tgl_lahir_wali" name="tgl_lahir_wali" value="{{$edit->tgl_lahir_wali}}">
                    @error('tgl_lahir_wali')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="pendidikan_wali">Pendidikan Wali</label>
                    <select class="form-control" name="pendidikan_wali" id="pendidikan_wali">
                      <option value="{{$edit->pendidikan_wali}}">{{$edit->pendidikan_wali}}</option>
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
                      <option value="{{$edit->pekerjaan_wali}}">{{$edit->pekerjaan_wali}}</option>
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
                      <option value="{{$edit->penghasilan_wali}}">{{$edit->penghasilan_wali}}</option>
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
  <script>
    document.addEventListener("DOMContentLoaded", function () {
  // Fokuskan input RFID saat halaman dimuat
  const rfidInput = document.getElementById("rfid");
  rfidInput.focus(); // Fokus pada input RFID otomatis

  // Menambahkan event listener untuk submit form
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function (event) {
      // Validasi input RFID sebelum submit
      const rfidValue = rfidInput.value.trim();

      if (!rfidValue) {
        alert("RFID belum dipindai atau kosong.");
        event.preventDefault(); // Mencegah pengiriman jika input kosong
      }
    });
  }

  // Event listener untuk input RFID
  rfidInput.addEventListener("input", function () {
    const rfidValue = rfidInput.value.trim();

    // Log nilai RFID jika ada perubahan
    console.log("Nilai RFID: ", rfidValue);
  });

  // Opsional: Tambahkan validasi saat input kehilangan fokus
  rfidInput.addEventListener("blur", function () {
    if (!rfidInput.value.trim()) {
      alert("RFID belum dipindai");
    }
  });
});

  </script>
@endsection