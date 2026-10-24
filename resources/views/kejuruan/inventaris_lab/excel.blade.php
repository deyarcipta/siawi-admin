<table>
    <thead>
        <tr>
            <th colspan="10" style="font-weight: bold; font-size: 14pt; text-align: center;">
                LAPORAN INVENTARIS LABORATORIUM - {{ strtoupper($setting->nama_sekolah ?? 'SMK NEGERI') }}
            </th>
        </tr>
        <tr>
            <th colspan="10" style="font-size: 11pt; text-align: center;">
                Laboratorium: {{ $lab->nama_lab }} | Program Keahlian: {{ $lab->jurusan->nama_jurusan ?? 'Semua Jurusan' }} | Lokasi: {{ $lab->lokasi ?: '-' }}
            </th>
        </tr>
        <tr>
            <th colspan="10" style="font-size: 9pt; text-align: center;">
                Dicetak pada: {{ date('d/m/Y H:i') }} oleh {{ $user->nama ?? $user->name ?? 'Admin/Kaprog' }}
            </th>
        </tr>
        <tr>
            <th colspan="10"></th>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Kode Aset</th>
            <th style="font-weight: bold; border: 1px solid #000000;">Nama Barang / Alat / Bahan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Kategori</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Jumlah Total</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Stok Tersedia</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Satuan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Kondisi</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Tahun Perolehan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Harga Satuan (Rp)</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Total Nilai (Rp)</th>
            <th style="font-weight: bold; border: 1px solid #000000;">Spesifikasi / Sumber Dana</th>
        </tr>
    </thead>
    <tbody>
        @php $grandTotal = 0; @endphp
        @forelse($asets as $index => $a)
            @php 
                $subtotal = $a->jumlah * $a->harga_satuan;
                $grandTotal += $subtotal;
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; font-weight: bold;">{{ $a->kode_aset }}</td>
                <td style="border: 1px solid #000000;">{{ $a->nama_aset }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $a->kategori === 'alat' ? 'Alat' : 'Bahan Habis Pakai' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $a->jumlah }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $a->stok_tersedia }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $a->satuan }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ ucfirst(str_replace('_', ' ', $a->kondisi)) }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $a->tahun_perolehan }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $a->harga_satuan }}</td>
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">{{ $subtotal }}</td>
                <td style="border: 1px solid #000000;">{{ $a->spesifikasi ?: $a->sumber_dana }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12" style="border: 1px solid #000000; text-align: center;">Tidak ada data aset.</td>
            </tr>
        @endforelse
        @if($asets->isNotEmpty())
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="10" style="border: 1px solid #000000; text-align: right;">TOTAL ESTIMASI NILAI ASET:</td>
                <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">{{ $grandTotal }}</td>
                <td style="border: 1px solid #000000;"></td>
            </tr>
        @endif
    </tbody>
</table>
