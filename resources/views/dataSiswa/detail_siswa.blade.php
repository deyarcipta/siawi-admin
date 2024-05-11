@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detail Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Siswa</a></li>
            <li class="breadcrumb-item active">Detail Siswa</li>
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
              <h3 class="card-title">Detail Data Siswa</h3>
            </div>
            <div class="card-body">
              {{-- <span>Data Diri Siswa</span> --}}
              <table class="table table-bordered table-striped">
                <tr>
                  <th class="text-center" width="200px">FOTO SISWA</th>
                  <th class="text-center" colspan="2" >DATA PRIBADI SISWA</th>
                </tr>
                <tr>
                  <td rowspan="5" class="align-middle text-center" ><img src="{{ asset("storage/foto-siswa/$detail->foto") }}" alt="Foto Siswa" style="width: 4cm; height: 6cm;"></td>
                  <td width="250px">NIS</td>
                  <td>{{$detail->nis}}</td>
                </tr>
                <tr>
                  <td>NISN</td>
                  <td>{{$detail->nisn}}</td>
                </tr>
                <tr>
                  <td>Nama Siswa</td>
                  <td>{{$detail->nama_siswa}}</td>
                </tr>
                <tr>
                  <td>Kelas</td>
                  <td>{{$detail->kelas->nama_kelas}}</td>
                </tr>
                <tr>
                  <td>Kompetensi Keahlian</td>
                  <td>
                    @if ($detail->jurusan == "PH")
                      <p>Perhotelan</p>
                    @elseif ($detail->jurusan == "KUL")
                      <p>Kuliner</p>
                    @elseif ($detail->jurusan == "TJKT")
                      <p>Teknik Jaringan Komputer dan Telekomunikasi</p>
                    @else
                      <p>Tidak Ada</p>
                    @endif
                  </td>
                </tr>
              </table>
              <table class="table table-bordered table-striped mt-3">
                <tr>
                  <td width="25%">Tempat, Tanggal Lahir</td>
                  <td width="27%">{{$detail->tmpt_lahir}}, {{$detail->tgl_lahir}}</td>
                  <td width="25%">Jenis Kelamin</td>
                  <td width="27%">{{$detail->jenis_kelamin}}</td>
                </tr>
                <tr>
                  <td width="25%">Agama</td>
                  <td width="27%">{{$detail->agama}}</td>
                  <td width="25%">Nomor Telepone</td>
                  <td width="27%">{{$detail->no_tlpn}}</td>
                </tr>
                <tr>
                  <td width="25%">Nomor HP</td>
                  <td width="27%">{{$detail->no_hp}}</td>
                  <td width="25%">Email</td>
                  <td width="27%">{{$detail->email}}</td>
                </tr>
                <tr>
                  <td width="25%">Alamat</td>
                  <td colspan="3">{{$detail->alamat}}</td>
                  {{-- <td width="25%">Email</td>
                  <td width="27%">{{$detail->email}}</td> --}}
                </tr>
                <tr>
                  <td width="25%">Rt/Rw</td>
                  <td width="27%">{{$detail->rt}}/{{$detail->rw}}</td>
                  <td width="25%">No Rumah</td>
                  <td width="27%">{{$detail->no_rumah}}</td>
                </tr>
                <tr>
                  <td width="25%">Kelurahan</td>
                  <td width="27%">{{$detail->kel}}</td>
                  <td width="25%">Kecamatan</td>
                  <td width="27%">{{$detail->kec}}</td>
                </tr>
                <tr>
                  <td width="25%">Kota</td>
                  <td width="27%">{{$detail->kota}}</td>
                  <td width="25%">Provinsi</td>
                  <td width="27%">{{$detail->prov}}</td>
                </tr>
              </table>
              <table class="table table-bordered table-striped mt-3">
                <tr class="text-center">
                  <th width="25%">Data Orang Tua</th>
                  <th width="25%">Data Ayah</th>
                  <th width="25%">Data Ibu</th>
                  <th width="25%">Data Wali</th>
                </tr>
                <tr>
                  <td>NIK</td>
                  <td>{{$detail->nik_ayah}}</td>
                  <td>{{$detail->nik_ibu}}</td>
                  <td>{{$detail->nik_wali}}</td>
                </tr>
                <tr>
                  <td>Nama Lengkap</td>
                  <td>{{$detail->nama_ayah}}</td>
                  <td>{{$detail->nama_ibu}}</td>
                  <td>{{$detail->nama_wali}}</td>
                </tr>
                <tr>
                  <td>Tempat, Tanggal Lahir</td>
                  <td>{{$detail->tmpt_lahir_ayah}}, {{$detail->tgl_lahir_ayah}}</td>
                  <td>{{$detail->tmpt_lahir_ibu}}, {{$detail->tgl_lahir_ibu}}</td>
                  <td>{{$detail->tmpt_lahir_wali}}, {{$detail->tgl_lahir_wali}}</td>
                </tr>
                <tr>
                  <td>Pendidikan</td>
                  <td>{{$detail->pendidikan_ayah}}</td>
                  <td>{{$detail->pendidikan_ibu}}</td>
                  <td>{{$detail->pendidikan_wali}}</td>
                </tr>
                <tr>
                  <td>Pekerjaan</td>
                  <td>{{$detail->pekerjaan_ayah}}</td>
                  <td>{{$detail->pekerjaan_ibu}}</td>
                  <td>{{$detail->pekerjaan_wali}}</td>
                </tr>
                <tr>
                  <td>Penghasilan</td>
                  <td>{{$detail->penghasilan_ayah}}</td>
                  <td>{{$detail->penghasilan_ibu}}</td>
                  <td>{{$detail->penghasilan_wali}}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection