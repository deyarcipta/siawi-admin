<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Reader Input</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">RFID Reader</h1>
        <div class="mt-4 text-center">
            <h4>RFID Data:</h4>
            <p id="rfidData" class="fw-bold text-primary"></p>
        </div>
    </div>

    <script>
        let rfidBuffer = ''; // Buffer untuk menyimpan data RFID sementara
        const rfidDataElement = document.getElementById('rfidData');

        // Event listener untuk menangkap data RFID
        document.addEventListener('keydown', function (event) {
            const char = event.key; // Karakter yang diketik oleh RFID reader
            
            // RFID biasanya berakhir dengan karakter newline (Enter), cek dan proses datanya
            if (char === 'Enter') {
                // Tampilkan data di halaman
                rfidDataElement.textContent = rfidBuffer;

                // Kirim data ke server menggunakan AJAX (axios)
                axios.post('/rfid/store', { rfid: rfidBuffer })
                    .then(function (response) {
                        console.log('Data berhasil dikirim:', response.data);
                    })
                    .catch(function (error) {
                        console.error('Error saat mengirim data:', error);
                    });

                // Reset buffer
                rfidBuffer = '';
            } else {
                // Tambahkan karakter ke buffer
                rfidBuffer += char;
            }
        });

        // Auto refresh halaman setiap 5 detik
        setInterval(function () {
            location.reload();
        }, 5000); // 5000 ms = 5 detik
    </script>
</body>
</html>
