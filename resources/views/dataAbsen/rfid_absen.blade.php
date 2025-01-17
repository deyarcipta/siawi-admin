@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Absensi Siswa RFID</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Absensi Siswa RFID</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-center">
                      <div class="row">
                        <div class="col-lg-6">
                        <h3>Scan Kartu RFID</h3>

                        <!-- Form dengan input tersembunyi untuk menyimpan RFID -->
                        <form id="rfidForm" method="POST" action="{{ route('admin.absensi.rfid') }}">
                            @csrf
                            <!-- Gambar untuk tombol scan -->
                            <button type="button" id="scanButton" class="btn btn-link p-0">
                                <img src="{{ asset('storage/gambar/scan-icon.png') }}" alt="Scan Icon" style="width: 100px; height: 100px;">
                            </button>
                            <!-- Input tersembunyi untuk RFID yang terisi setelah pemindaian -->
                            <input type="text" id="rfid" name="rfid" class="form-control" style="opacity: 0; position: absolute; top: -9999px;" autofocus autocomplete="off">
                        </form>
                        

                        <!-- Hasil scan atau error -->
                        <div id="result" class="mt-3">
                            @if(isset($siswa))
                                <div class="alert alert-success">
                                    <strong>Berhasil!</strong> Nama: {{ $siswa->nama_siswa }}, Kelas: {{ $siswa->kelas->nama_kelas }}.
                                </div>
                            @elseif(isset($message))
                                <div class="alert alert-danger">
                                    <strong>Gagal!</strong> {{ $message }}
                                </div>
                            @endif
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <table id="" class="table table-bordered table-hover mt-2">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataSiswa as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->siswa->nama_siswa }}</td>
                                        <td>{{ $siswa->kelas->nama_kelas }}</td>
                                        <td>{{ $siswa->jam_masuk }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const scanButton = document.getElementById("scanButton");
    const rfidInput = document.getElementById("rfid");
    const resultDiv = document.getElementById("result");

    // Fokus otomatis pada input RFID
    rfidInput.focus();

    // Menangani klik pada tombol gambar
    scanButton.addEventListener("click", function () {
        // Fokus pada input RFID untuk memulai pemindaian
        rfidInput.focus();
    });

    // Menangani input pada input RFID
    rfidInput.addEventListener("input", function () {
        const rfidValue = rfidInput.value.trim();

        // Jika ada nilai yang dimasukkan (setelah pemindaian RFID)
        if (rfidValue.length > 0) {
            // Kirim data ke backend menggunakan AJAX
            fetch('{{ route("admin.absensi.rfid") }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ rfid: rfidValue })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `<div class="alert alert-success">
                        <strong>Berhasil!</strong> Siswa: ${data.nama_siswa}, Kelas: ${data.kelas}.
                    </div>`;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">
                        <strong>Gagal!</strong> ${data.message}.
                    </div>`;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `<div class="alert alert-danger">
                    <strong>Error!</strong> Terjadi kesalahan saat memproses data.
                </div>`;
                console.error(error);
            });

            // Reset input setelah scan
            rfidInput.value = '';
        }
    });
});
</script>
@endsection
