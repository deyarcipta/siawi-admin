<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Jurusan;
use App\Models\Lab;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kompetensi;
use App\Models\ModulPraktik;
use App\Models\RubrikPenilaian;
use App\Models\JadwalPraktik;
use App\Models\JurnalPraktik;
use App\Models\PenilaianPraktik;
use App\Models\NilaiPraktikSiswa;
use App\Models\AsetLab;
use App\Models\PeminjamanAset;
use App\Models\PemakaianBahanLab;
use App\Models\PerawatanAsetLab;
use App\Models\UkkKegiatan;
use App\Models\UkkPenguji;
use App\Models\UkkUnitKompetensi;
use App\Models\UkkPeserta;
use App\Models\UkkNilaiSiswa;
use App\Models\JenisTkk;
use App\Models\TkkSyaratKompetensi;
use App\Models\SiswaTkk;
use App\Models\PortofolioSiswa;
use App\Models\CapaianSiswa;

class KejuruanDummyDataSeeder extends Seeder
{
    public function run()
    {
        echo "=== Memulai Pembuatan Seluruh Data Dummy Kejuruan ===\n";

        $gurus = Guru::all();
        $adminGuru = Guru::first();
        if (!$adminGuru) {
            echo "Guru tidak ditemukan di sistem.\n";
            return;
        }

        $jurusans = Jurusan::all();
        if ($jurusans->isEmpty()) {
            echo "Jurusan tidak ditemukan di sistem.\n";
            return;
        }

        // =========================================================================
        // 1. LABORATORIUM PER JURUSAN
        // =========================================================================
        echo "1. Memvalidasi & Membuat Laboratorium...\n";
        $labList = [
            // TJKT (id_jurusan: 3 atau cari berdasarkan kode TJKT)
            ['kode' => 'TJKT', 'nama' => 'Lab Jaringan Komputer & Fiber Optic', 'lokasi' => 'Gedung B Lt. 2', 'kapasitas' => 36],
            ['kode' => 'TJKT', 'nama' => 'Lab Server & Komputasi Awan', 'lokasi' => 'Gedung B Lt. 2', 'kapasitas' => 32],
            // Perhotelan (PH)
            ['kode' => 'PH', 'nama' => 'Mockup Hotel Suite & Front Office', 'lokasi' => 'Gedung Prakerin Lt. 1', 'kapasitas' => 30],
            ['kode' => 'PH', 'nama' => 'Lab Housekeeping & Laundry Industri', 'lokasi' => 'Gedung Prakerin Lt. 1', 'kapasitas' => 28],
            // Kuliner (KUL)
            ['kode' => 'KUL', 'nama' => 'Lab Kitchen Patiseri & Bakery', 'lokasi' => 'Gedung C Boga Lt. 1', 'kapasitas' => 32],
            ['kode' => 'KUL', 'nama' => 'Lab Kitchen Kontinental & Resto Service', 'lokasi' => 'Gedung C Boga Lt. 2', 'kapasitas' => 36],
        ];

        $labsCreated = [];
        foreach ($labList as $item) {
            $j = Jurusan::where('kode_jurusan', $item['kode'])->first() ?? $jurusans->first();
            static $labCounter = 1;
            $lab = Lab::firstOrCreate([
                'nama_lab' => $item['nama'],
                'id_jurusan' => $j->id_jurusan,
            ], [
                'kode_lab' => 'LAB-' . $item['kode'] . '-' . str_pad($labCounter++, 2, '0', STR_PAD_LEFT),
                'lokasi' => $item['lokasi'],
                'kapasitas' => $item['kapasitas'],
            ]);
            $labsCreated[$item['kode']][] = $lab;
        }

        // =========================================================================
        // 2. STANDAR KOMPETENSI KEJURUAN
        // =========================================================================
        echo "2. Membuat Standar Kompetensi...\n";
        $kompetensiSeed = [
            'TJKT' => [
                ['kode' => 'TJKT-KD-001', 'nama' => 'Pengalamatan IP Address & Subnetting IPv4/IPv6', 'tingkat' => 'XII', 'semester' => 1, 'kkm' => 78.00],
                ['kode' => 'TJKT-KD-002', 'nama' => 'Konfigurasi Routing Dinamis OSPF & Multi-Area', 'tingkat' => 'XII', 'semester' => 1, 'kkm' => 80.00],
                ['kode' => 'TJKT-KD-003', 'nama' => 'Penyambungan Kabel Fiber Optic (Splicing & OTDR)', 'tingkat' => 'XII', 'semester' => 2, 'kkm' => 78.00],
                ['kode' => 'TJKT-KD-004', 'nama' => 'Manajemen Keamanan Jaringan & Firewall Filtering', 'tingkat' => 'XI', 'semester' => 2, 'kkm' => 75.00],
            ],
            'PH' => [
                ['kode' => 'PH-KD-001', 'nama' => 'Operasional Front Office & Sistem Reservasi Hotel', 'tingkat' => 'XII', 'semester' => 1, 'kkm' => 78.00],
                ['kode' => 'PH-KD-002', 'nama' => 'Penataan Kamar Tamu Suite Room (Making Bed & Dusting)', 'tingkat' => 'XII', 'semester' => 1, 'kkm' => 80.00],
                ['kode' => 'PH-KD-003', 'nama' => 'Penanganan Layanan Laundry & Dry Cleaning Tamu', 'tingkat' => 'XI', 'semester' => 2, 'kkm' => 76.00],
                ['kode' => 'PH-KD-004', 'nama' => 'Pelayanan Tamu VIP & Handling Complain Profesional', 'tingkat' => 'XII', 'semester' => 2, 'kkm' => 80.00],
            ],
            'KUL' => [
                ['kode' => 'KUL-KD-001', 'nama' => 'Pembuatan Roti Manis & Artisan Sourdough Bakery', 'tingkat' => 'XII', 'semester' => 1, 'kkm' => 78.00],
                ['kode' => 'KUL-KD-002', 'nama' => 'Teknik Laminasi Pembuatan Croissant & Danish Pastry', 'tingkat' => 'XII', 'semester' => 1, 'kkm' => 80.00],
                ['kode' => 'KUL-KD-003', 'nama' => 'Pengolahan Hidangan Pembuka Kontinental (Appetizer)', 'tingkat' => 'XI', 'semester' => 2, 'kkm' => 75.00],
                ['kode' => 'KUL-KD-004', 'nama' => 'Penerapan Sanitasi Hygiene & HACCP Dapur Restoran', 'tingkat' => 'XI', 'semester' => 1, 'kkm' => 78.00],
            ],
        ];

        $kompetensisCreated = [];
        foreach ($kompetensiSeed as $kodeJur => $items) {
            $j = Jurusan::where('kode_jurusan', $kodeJur)->first() ?? $jurusans->first();
            foreach ($items as $kItem) {
                $comp = Kompetensi::firstOrCreate([
                    'kode_kompetensi' => $kItem['kode'],
                ], [
                    'nama_kompetensi' => $kItem['nama'],
                    'id_jurusan' => $j->id_jurusan,
                    'tingkat' => $kItem['tingkat'],
                    'semester' => $kItem['semester'],
                    'kkm' => $kItem['kkm'],
                ]);
                $kompetensisCreated[$kodeJur][] = $comp;
            }
        }

        // =========================================================================
        // 3. INVENTARIS LABORATORIUM (ASET ALAT & BAHAN HABIS PAKAI)
        // =========================================================================
        echo "3. Membuat Inventaris Laboratorium (Alat & Bahan)...\n";
        $asetSeed = [
            'TJKT' => [
                ['kode' => 'AST-TJKT-001', 'nama' => 'Routerboard MikroTik RB750Gr3 hEX', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 25, 'min' => 5, 'sat' => 'Unit', 'hrg' => 850000],
                ['kode' => 'AST-TJKT-002', 'nama' => 'Managed Switch Cisco Catalyst 2960 24-Port', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 10, 'min' => 2, 'sat' => 'Unit', 'hrg' => 3200000],
                ['kode' => 'AST-TJKT-003', 'nama' => 'Fusion Splicer Fiber Optic Sumitomo T-72C', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 4, 'min' => 1, 'sat' => 'Unit', 'hrg' => 45000000],
                ['kode' => 'AST-TJKT-004', 'nama' => 'Optical Time Domain Reflectometer (OTDR)', 'kat' => 'alat', 'kondisi' => 'rusak_ringan', 'jml' => 3, 'min' => 1, 'sat' => 'Unit', 'hrg' => 18000000],
                ['kode' => 'AST-TJKT-005', 'nama' => 'Kabel UTP Cat6 Belden Original 305M', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 12, 'min' => 3, 'sat' => 'Roll', 'hrg' => 1850000],
                ['kode' => 'AST-TJKT-006', 'nama' => 'Konektor RJ45 Cat6 Commscope Isi 50 Pcs', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 45, 'min' => 10, 'sat' => 'Kotak', 'hrg' => 165000],
                ['kode' => 'AST-TJKT-007', 'nama' => 'Pigtail & Patch Cord Fiber Optic SC-UPC 3M', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 120, 'min' => 25, 'sat' => 'Pcs', 'hrg' => 35000],
            ],
            'PH' => [
                ['kode' => 'AST-PH-001', 'nama' => 'Trolley Housekeeping Hotel Stainless Steel', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 6, 'min' => 2, 'sat' => 'Unit', 'hrg' => 4500000],
                ['kode' => 'AST-PH-002', 'nama' => 'Mesin Polisher Lantai Wet & Dry Karpet', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 4, 'min' => 1, 'sat' => 'Unit', 'hrg' => 8500000],
                ['kode' => 'AST-PH-003', 'nama' => 'Set Komputer Terminal Front Office PMS', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 8, 'min' => 2, 'sat' => 'Set', 'hrg' => 7500000],
                ['kode' => 'AST-PH-004', 'nama' => 'Bed Sheet Cotton King Size Putih Polos', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 50, 'min' => 15, 'sat' => 'Lembar', 'hrg' => 180000],
                ['kode' => 'AST-PH-005', 'nama' => 'Cairan Chemical Floor Cleaner Ecolab 5L', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 20, 'min' => 5, 'sat' => 'Jerigen', 'hrg' => 240000],
                ['kode' => 'AST-PH-006', 'nama' => 'Guest Amenities Hotel Box (Soap & Dental Kit)', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 250, 'min' => 50, 'sat' => 'Set', 'hrg' => 12500],
            ],
            'KUL' => [
                ['kode' => 'AST-KUL-001', 'nama' => 'Heavy Duty Planetary Mixer 20 Liter Sinmag', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 6, 'min' => 2, 'sat' => 'Unit', 'hrg' => 22000000],
                ['kode' => 'AST-KUL-002', 'nama' => 'Gas Deck Oven 2 Deck 4 Tray Digital', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 4, 'min' => 1, 'sat' => 'Unit', 'hrg' => 34000000],
                ['kode' => 'AST-KUL-003', 'nama' => 'Mesin Espresso Komersial 2-Group Nuova Simonelli', 'kat' => 'alat', 'kondisi' => 'baik', 'jml' => 3, 'min' => 1, 'sat' => 'Unit', 'hrg' => 55000000],
                ['kode' => 'AST-KUL-004', 'nama' => 'Tepung Terigu Protein Tinggi Cakra Kembar 25Kg', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 30, 'min' => 5, 'sat' => 'Sak', 'hrg' => 285000],
                ['kode' => 'AST-KUL-005', 'nama' => 'Mentega Korsvet / Pastry Fat Sheet Anchor 10Kg', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 15, 'min' => 4, 'sat' => 'Karton', 'hrg' => 650000],
                ['kode' => 'AST-KUL-006', 'nama' => 'Biji Kopi Arabika Specialty Single Origin 1Kg', 'kat' => 'bahan_habis_pakai', 'kondisi' => 'baik', 'jml' => 40, 'min' => 8, 'sat' => 'Pouch', 'hrg' => 195000],
            ],
        ];

        $asetsCreated = [];
        foreach ($asetSeed as $kodeJur => $items) {
            $labTarget = $labsCreated[$kodeJur][0] ?? Lab::first();
            foreach ($items as $aItem) {
                $aset = AsetLab::firstOrCreate([
                    'kode_aset' => $aItem['kode'],
                ], [
                    'id_lab' => $labTarget->id_lab,
                    'nama_aset' => $aItem['nama'],
                    'kategori' => $aItem['kat'],
                    'spesifikasi' => 'Standar industri bersertifikasi SNI / ISO untuk sertifikasi kejuruan',
                    'jumlah' => $aItem['jml'],
                    'stok_tersedia' => $aItem['jml'],
                    'stok_minimum' => $aItem['min'],
                    'satuan' => $aItem['sat'],
                    'kondisi' => $aItem['kondisi'],
                    'tahun_perolehan' => 2024,
                    'sumber_dana' => 'BOS Kinerja & DAK Fisik SMK',
                    'harga_satuan' => $aItem['hrg'],
                    'keterangan' => 'Inventaris resmi laboratorium sarpras sekolah.',
                ]);
                $asetsCreated[] = $aset;
            }
        }

        // 3b. Transaksi Peminjaman Alat, Pemakaian Bahan, & Perawatan
        $sampleSiswa = Siswa::first();
        $sampleAsetAlat = AsetLab::where('kategori', 'alat')->first();
        if ($sampleAsetAlat && $sampleSiswa) {
            // Peminjaman
            PeminjamanAset::firstOrCreate([
                'kode_peminjaman' => 'PINJAM-202610-001',
            ], [
                'id_aset' => $sampleAsetAlat->id_aset,
                'tipe_peminjam' => 'siswa',
                'id_peminjam_siswa' => $sampleSiswa->id_siswa,
                'id_peminjam_guru' => null,
                'jumlah' => 1,
                'tanggal_pinjam' => date('Y-m-d', strtotime('-3 days')),
                'rencana_kembali' => date('Y-m-d', strtotime('+2 days')),
                'status' => 'dipinjam',
                'id_petugas' => $adminGuru->id_guru,
                'keperluan' => 'Praktik mandiri konfigurasi routing kejuruan',
                'kondisi_sebelum' => 'baik',
            ]);

            PeminjamanAset::firstOrCreate([
                'kode_peminjaman' => 'PINJAM-202610-002',
            ], [
                'id_aset' => $sampleAsetAlat->id_aset,
                'tipe_peminjam' => 'guru',
                'id_peminjam_guru' => $adminGuru->id_guru,
                'jumlah' => 2,
                'tanggal_pinjam' => date('Y-m-d', strtotime('-10 days')),
                'rencana_kembali' => date('Y-m-d', strtotime('-8 days')),
                'tanggal_kembali' => date('Y-m-d', strtotime('-8 days')),
                'status' => 'kembali',
                'id_petugas' => $adminGuru->id_guru,
                'keperluan' => 'Simulasi demo ujian praktik kompetensi',
                'kondisi_sebelum' => 'baik',
                'kondisi_sesudah' => 'baik',
            ]);

            // Pemakaian Bahan
            $sampleBahan = AsetLab::where('kategori', 'bahan_habis_pakai')->first();
            if ($sampleBahan) {
                PemakaianBahanLab::firstOrCreate([
                    'id_aset' => $sampleBahan->id_aset,
                    'tanggal_pakai' => date('Y-m-d', strtotime('-2 days')),
                    'keperluan' => 'Praktikum pembuatan kabel jaringan kelas XII',
                ], [
                    'jumlah_pakai' => 2,
                    'sisa_stok_saat_itu' => $sampleBahan->stok_tersedia - 2,
                    'id_pengguna_guru' => $adminGuru->id_guru,
                    'id_petugas' => $adminGuru->id_guru,
                    'catatan' => 'Dipakai untuk 36 siswa praktikum crimping kabel LAN.',
                ]);
            }

            // Perawatan Aset
            PerawatanAsetLab::firstOrCreate([
                'id_aset' => $sampleAsetAlat->id_aset,
                'deskripsi_kendala' => 'Pembersihan debu berkala dan pembaruan firmware sistem',
            ], [
                'jenis' => 'perawatan_rutin',
                'tanggal_jadwal' => date('Y-m-d'),
                'tanggal_selesai' => date('Y-m-d'),
                'tindakan_perbaikan' => 'Pembersihan cooling fan & upgrade RouterOS ke versi stabil terbaru',
                'biaya' => 150000,
                'status' => 'selesai',
                'kondisi_akhir' => 'baik',
                'teknisi' => 'Teknisi Lab Internal',
                'id_pelapor' => $adminGuru->id_guru,
            ]);
        }

        // =========================================================================
        // 4. BANK MODUL PRAKTIK & RUBRIK PENILAIAN
        // =========================================================================
        echo "4. Membuat Modul Praktik & Rubrik...\n";
        $modulSeed = [
            'TJKT' => [
                'kode' => 'MOD-TJKT-002',
                'judul' => 'Konfigurasi Routing Dinamis OSPF Multi-Area',
                'tujuan' => 'Siswa mampu merancang dan mengonfigurasi routing dinamis OSPF pada topologi multi-router',
                'alat' => 'Routerboard MikroTik, Kabel UTP Cat6, PC Workstation, Winbox',
                'rubrik' => [
                    ['aspek' => 'Perancangan Topologi & Subnetting IP', 'bobot' => 25],
                    ['aspek' => 'Konfigurasi Interface & Routing OSPF Area 0', 'bobot' => 35],
                    ['aspek' => 'Pengujian Konektivitas & Traceroute Failover', 'bobot' => 25],
                    ['aspek' => 'Kerapian Dokumentasi & Waktu Pengerjaan', 'bobot' => 15],
                ],
            ],
            'PH' => [
                'kode' => 'MOD-PH-001',
                'judul' => 'Operasional Check-In Tamu VIP & Sistem PMS Hotel',
                'tujuan' => 'Siswa menguasai alur penyambutan tamu hotel, verifikasi data reservasi di sistem PMS, dan escorting tamu',
                'alat' => 'Terminal PMS Front Office, Keycard Encoder, Guest Registration Card, VOUCHER',
                'rubrik' => [
                    ['aspek' => 'Standard Grooming & Greeting Ramah Tamu', 'bobot' => 20],
                    ['aspek' => 'Ketelitian Verifikasi Data Tamu di PMS', 'bobot' => 30],
                    ['aspek' => 'Penjelasan Fasilitas Hotel & Penyerahan Kunci', 'bobot' => 30],
                    ['aspek' => 'Komunikasi Bahasa Inggris & Etika Pelayanan', 'bobot' => 20],
                ],
            ],
            'KUL' => [
                'kode' => 'MOD-KUL-001',
                'judul' => 'Pembuatan Croissant & Danish Pastry Klasik',
                'tujuan' => 'Siswa terampil membuat adonan pastry beragi dengan teknik laminasi lipatan single dan double fold',
                'alat' => 'Planetary Mixer, Rolling Pin, Pastry Brush, Gas Deck Oven, Baking Sheet',
                'rubrik' => [
                    ['aspek' => 'Mise en Place & Ketepatan Timbangan Bahan', 'bobot' => 20],
                    ['aspek' => 'Teknik Lipatan Laminasi Korsvet', 'bobot' => 35],
                    ['aspek' => 'Hasil Pemanggangan, Lapisan Honeycomb & Warna', 'bobot' => 30],
                    ['aspek' => 'Penerapan Sanitasi Hygiene Tempat Kerja', 'bobot' => 15],
                ],
            ],
        ];

        $modulsCreated = [];
        foreach ($modulSeed as $kodeJur => $mData) {
            $komp = $kompetensisCreated[$kodeJur][0] ?? Kompetensi::first();
            $modul = ModulPraktik::firstOrCreate([
                'kode_modul' => $mData['kode'],
            ], [
                'id_kompetensi' => $komp->id_kompetensi,
                'id_guru' => $adminGuru->id_guru,
                'judul' => $mData['judul'],
                'tujuan' => $mData['tujuan'],
                'alat_bahan' => $mData['alat'],
                'langkah_kerja' => "1. Persiapkan alat dan bahan yang dibutuhkan.\n2. Lakukan instruksi kerja sesuai SOP industri.\n3. Lakukan pengujian hasil kerja bersama guru penguji.\n4. Rapikan kembali ruang kerja praktik.",
                'versi' => '1.0',
                'status' => 'terbit',
            ]);

            // Rubrik
            foreach ($mData['rubrik'] as $urutan => $rItem) {
                RubrikPenilaian::firstOrCreate([
                    'id_modul_praktik' => $modul->id_modul_praktik,
                    'nama_indikator' => $rItem['aspek'],
                ], [
                    'id_kompetensi' => $komp->id_kompetensi,
                    'bobot' => $rItem['bobot'],
                    'deskripsi' => 'Kriteria evaluasi pencapaian rubrik modul',
                    'urutan' => $urutan + 1,
                ]);
            }
            $modulsCreated[$kodeJur] = $modul;
        }

        // =========================================================================
        // 5. JADWAL PRAKTIK & JURNAL PRAKTIK
        // =========================================================================
        echo "5. Membuat Jadwal & Jurnal Praktik...\n";
        $kelasAll = Kelas::all();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($jurusans as $idx => $jur) {
            $kodeJur = $jur->kode_jurusan;
            $kelasTarget = Kelas::where('kode_jurusan', $kodeJur)->first() ?? $kelasAll->get($idx % max(1, $kelasAll->count()));
            $labTarget = $labsCreated[$kodeJur][0] ?? Lab::first();
            $modulTarget = $modulsCreated[$kodeJur] ?? ModulPraktik::first();

            if ($kelasTarget && $labTarget) {
                $jadwal = JadwalPraktik::firstOrCreate([
                    'id_kelas' => $kelasTarget->id_kelas,
                    'id_lab' => $labTarget->id_lab,
                    'hari' => $hariList[$idx % 5],
                    'jam_mulai' => '07:30:00',
                ], [
                    'jam_selesai' => '11:30:00',
                    'id_guru' => $adminGuru->id_guru,
                    'id_mapel' => \App\Models\Mapel::first()->id_mapel ?? null,
                    'tahun_ajaran' => '2026/2027',
                    'semester' => 'ganjil',
                    'alat_bahan' => 'Peralatan standar lab dan modul praktikum',
                ]);

                // Jurnal Praktik
                JurnalPraktik::firstOrCreate([
                    'id_jadwal_praktik' => $jadwal->id_jadwal_praktik,
                    'tanggal' => date('Y-m-d', strtotime('-' . ($idx + 1) . ' days')),
                ], [
                    'modul_praktik_id' => $modulTarget ? $modulTarget->id_modul_praktik : null,
                    'materi' => 'Praktikum Sesi Pertemuan ke-' . ($idx + 1) . ': ' . ($modulTarget ? $modulTarget->judul : 'Praktik Kejuruan'),
                    'jumlah_hadir' => 34,
                    'kendala' => 'Semua peralatan berfungsi normal dan berjalan kondusif.',
                    'alat_dipakai' => 'Peralatan lab lengkap sesuai modul praktik.',
                    'catatan' => 'Siswa mengikuti instruksi kerja dengan baik.',
                    'status_approval' => 'disetujui',
                    'approved_by' => $adminGuru->id_guru,
                    'approved_at' => now(),
                    'catatan_kaprog' => 'Laporan jurnal disetujui.',
                ]);
            }
        }

        // =========================================================================
        // 6. SESI PENILAIAN PRAKTIK KELAS & REKAP NILAI SISWA
        // =========================================================================
        echo "6. Membuat Sesi Penilaian Praktik & Nilai Siswa...\n";
        foreach ($jurusans as $idx => $jur) {
            $kodeJur = $jur->kode_jurusan;
            $kelasTarget = Kelas::where('kode_jurusan', $kodeJur)->first() ?? $kelasAll->get($idx % max(1, $kelasAll->count()));
            $modulTarget = $modulsCreated[$kodeJur] ?? ModulPraktik::first();

            if ($kelasTarget && $modulTarget) {
                $penilaian = PenilaianPraktik::firstOrCreate([
                    'id_kelas' => $kelasTarget->id_kelas,
                    'id_modul_praktik' => $modulTarget->id_modul_praktik,
                    'tanggal_penilaian' => date('Y-m-d', strtotime('-' . ($idx * 2 + 1) . ' days')),
                ], [
                    'id_kompetensi' => $modulTarget->id_kompetensi,
                    'id_guru' => $adminGuru->id_guru,
                    'kkm_saat_penilaian' => 75.00,
                    'status' => 'final',
                    'catatan' => 'Sesi penilaian terstruktur evaluasi capaian kompetensi praktikum.',
                ]);

                // Nilai siswa di kelas tersebut
                $siswasKelas = Siswa::where('id_kelas', $kelasTarget->id_kelas)->take(10)->get();
                foreach ($siswasKelas as $sIdx => $s) {
                    $nilaiAwal = 78.00 + ($sIdx % 5) * 4;
                    if ($nilaiAwal > 96) $nilaiAwal = 92;

                    NilaiPraktikSiswa::updateOrCreate([
                        'id_penilaian_praktik' => $penilaian->id_penilaian_praktik,
                        'id_siswa' => $s->id_siswa,
                    ], [
                        'nilai_per_indikator' => [
                            ['aspek' => 'Persiapan & Teori', 'skor' => $nilaiAwal],
                            ['aspek' => 'Pelaksanaan & SOP', 'skor' => $nilaiAwal + 2],
                            ['aspek' => 'Hasil Kerja & Pengujian', 'skor' => $nilaiAwal - 2],
                        ],
                        'nilai_awal' => $nilaiAwal,
                        'nilai_akhir' => $nilaiAwal,
                        'status_kompetensi' => 'kompeten',
                        'is_remedial' => false,
                        'catatan_guru' => 'Kompeten dengan penguasaan materi yang baik.',
                    ]);

                    // Alirkan ke capaian_siswa
                    CapaianSiswa::updateOrCreate([
                        'id_siswa' => $s->id_siswa,
                        'id_kompetensi' => $modulTarget->id_kompetensi,
                        'jenis_capaian' => 'praktik',
                    ], [
                        'id_referensi' => $penilaian->id_penilaian_praktik,
                        'nilai' => $nilaiAwal,
                        'kkm' => 75.00,
                        'status' => 'kompeten',
                        'tanggal' => $penilaian->tanggal_penilaian,
                        'id_penilai' => $adminGuru->id_guru,
                        'catatan' => 'Penilaian Praktik Modul: ' . $modulTarget->judul,
                    ]);
                }
            }
        }

        // =========================================================================
        // 7. UJI KOMPETENSI KEAHLIAN (UKK)
        // =========================================================================
        echo "7. Membuat Kegiatan Uji Kompetensi (UKK)...\n";
        $ukkSeed = [
            'TJKT' => [
                'nama' => 'Uji Kompetensi Keahlian TJKT 2026/2027',
                'lab' => $labsCreated['TJKT'][0]->id_lab ?? null,
                'mitra' => 'APJII Wilayah Jawa Barat',
                'penguji' => 'Bambang Irawan, M.T. (Asesor LSP P3 Telekomunikasi)',
            ],
            'PH' => [
                'nama' => 'Uji Kompetensi Keahlian Perhotelan 2026/2027',
                'lab' => $labsCreated['PH'][0]->id_lab ?? null,
                'mitra' => 'Hotel Santika Premiere',
                'penguji' => 'Dra. Ratna Handayani (General Manager Hotel Santika)',
            ],
            'KUL' => [
                'nama' => 'Uji Kompetensi Keahlian Kuliner 2026/2027',
                'lab' => $labsCreated['KUL'][0]->id_lab ?? null,
                'mitra' => 'Indonesian Chef Association (ICA)',
                'penguji' => 'Chef Haryo Kusumo (Executive Chef Grand Mercure)',
            ],
        ];

        foreach ($ukkSeed as $kodeJur => $uData) {
            $jur = Jurusan::where('kode_jurusan', $kodeJur)->first() ?? $jurusans->first();
            $ukkKegiatan = UkkKegiatan::firstOrCreate([
                'nama_kegiatan' => $uData['nama'],
                'id_jurusan' => $jur->id_jurusan,
            ], [
                'id_lab' => $uData['lab'],
                'tanggal_mulai' => date('Y-m-d', strtotime('-5 days')),
                'tanggal_selesai' => date('Y-m-d', strtotime('+5 days')),
                'tahun_ajaran' => '2026/2027',
                'status' => 'berlangsung',
                'nomor_sk' => 'SK-UKK/SMK-WI/421.5/' . date('Y') . '/' . $kodeJur,
                'deskripsi' => 'Pelaksanaan Uji Kompetensi Keahlian Mandiri bekerja sama dengan DU-DI / LSP mitra.',
                'created_by' => $adminGuru->id_guru,
            ]);

            // Penguji Internal & Eksternal
            UkkPenguji::firstOrCreate([
                'id_ukk_kegiatan' => $ukkKegiatan->id_ukk_kegiatan,
                'jenis' => 'internal',
                'id_guru' => $adminGuru->id_guru,
            ], [
                'jabatan_penguji' => 'Ketua Penguji Internal',
            ]);

            UkkPenguji::firstOrCreate([
                'id_ukk_kegiatan' => $ukkKegiatan->id_ukk_kegiatan,
                'jenis' => 'eksternal',
                'nama_penguji' => $uData['penguji'],
            ], [
                'instansi' => $uData['mitra'],
                'jabatan_penguji' => 'Penguji Eksternal / Asesor',
            ]);

            // Unit Uji Kompetensi
            $unitsKomp = $kompetensisCreated[$kodeJur] ?? Kompetensi::take(2)->get();
            $unitIds = [];
            foreach ($unitsKomp as $uKomp) {
                $unit = UkkUnitKompetensi::firstOrCreate([
                    'id_ukk_kegiatan' => $ukkKegiatan->id_ukk_kegiatan,
                    'id_kompetensi' => $uKomp->id_kompetensi,
                ], [
                    'bobot' => 100.00,
                    'kkm' => $uKomp->kkm ?: 75.00,
                ]);
                $unitIds[] = $unit;
            }

            // Peserta Siswa
            $kelasJur = Kelas::where('kode_jurusan', $kodeJur)->first();
            $pesertasSiswa = Siswa::where('id_kelas', $kelasJur ? $kelasJur->id_kelas : 1)->take(8)->get();

            foreach ($pesertasSiswa as $pIdx => $pSiswa) {
                $nilaiAkhir = 82.50 + ($pIdx % 4) * 3.5;
                $statusLulus = $nilaiAkhir >= 75.00 ? 'kompeten' : 'belum_kompeten';

                $ukkPeserta = UkkPeserta::updateOrCreate([
                    'id_ukk_kegiatan' => $ukkKegiatan->id_ukk_kegiatan,
                    'id_siswa' => $pSiswa->id_siswa,
                ], [
                    'nomor_peserta' => 'UKK-' . $kodeJur . '-' . str_pad($pSiswa->id_siswa, 3, '0', STR_PAD_LEFT),
                    'status_kehadiran' => 'hadir',
                    'nilai_akhir' => $nilaiAkhir,
                    'status_kelulusan' => $statusLulus,
                    'catatan_umum' => 'Menguasai prosedur standar kerja industri dengan baik.',
                ]);

                // Nilai per unit
                foreach ($unitIds as $uUnit) {
                    UkkNilaiSiswa::updateOrCreate([
                        'id_ukk_peserta' => $ukkPeserta->id_ukk_peserta,
                        'id_ukk_unit' => $uUnit->id_ukk_unit,
                    ], [
                        'nilai' => $nilaiAkhir,
                        'status' => $statusLulus,
                        'jadwal_remedial' => $statusLulus === 'belum_kompeten' ? date('Y-m-d', strtotime('+3 days')) : null,
                        'catatan_penguji' => 'Sesuai rubrik standar kompetensi.',
                    ]);
                }

                // Alirkan ke capaian_siswa
                CapaianSiswa::updateOrCreate([
                    'id_siswa' => $pSiswa->id_siswa,
                    'jenis_capaian' => 'ukk',
                    'id_referensi' => $ukkKegiatan->id_ukk_kegiatan,
                ], [
                    'id_kompetensi' => $unitIds[0]->id_kompetensi ?? null,
                    'nilai' => $nilaiAkhir,
                    'kkm' => 75.00,
                    'status' => $statusLulus,
                    'tanggal' => date('Y-m-d'),
                    'id_penilai' => $adminGuru->id_guru,
                    'catatan' => 'Hasil ' . $ukkKegiatan->nama_kegiatan . ' (Nilai: ' . $nilaiAkhir . ')',
                ]);
            }
        }

        // =========================================================================
        // 8. TANDA KECAKAPAN KHUSUS (TKK)
        // =========================================================================
        echo "8. Membuat Master TKK & Penganugerahan Piagam Siswa...\n";
        $tkkSeed = [
            'TJKT' => [
                ['kode' => 'TKK-TJKT-001', 'nama' => 'Junior Network Administrator', 'tingkat' => 'dasar'],
                ['kode' => 'TKK-TJKT-002', 'nama' => 'Fiber Optic Splicing Technician', 'tingkat' => 'menengah'],
                ['kode' => 'TKK-TJKT-003', 'nama' => 'Enterprise Routing & Security Specialist', 'tingkat' => 'utama'],
            ],
            'PH' => [
                ['kode' => 'TKK-PH-001', 'nama' => 'Front Desk Reception Specialist', 'tingkat' => 'dasar'],
                ['kode' => 'TKK-PH-002', 'nama' => 'Professional Room Attendant Suite', 'tingkat' => 'menengah'],
                ['kode' => 'TKK-PH-003', 'nama' => 'Hospitality Guest Relation Executive', 'tingkat' => 'utama'],
            ],
            'KUL' => [
                ['kode' => 'TKK-KUL-001', 'nama' => 'Junior Pastry & Bakery Artisan', 'tingkat' => 'dasar'],
                ['kode' => 'TKK-KUL-002', 'nama' => 'Continental Garde Manger Specialist', 'tingkat' => 'menengah'],
                ['kode' => 'TKK-KUL-003', 'nama' => 'Kitchen Executive & Food Safety Auditor', 'tingkat' => 'utama'],
            ],
        ];

        foreach ($tkkSeed as $kodeJur => $tItems) {
            $jur = Jurusan::where('kode_jurusan', $kodeJur)->first() ?? $jurusans->first();
            $comps = $kompetensisCreated[$kodeJur] ?? Kompetensi::take(2)->get();

            foreach ($tItems as $tIdx => $tInfo) {
                $jTkk = JenisTkk::firstOrCreate([
                    'nama_tkk' => $tInfo['nama'],
                    'id_jurusan' => $jur->id_jurusan,
                ], [
                    'tingkat' => $tInfo['tingkat'],
                    'deskripsi' => 'Lencana TKK keahlian siswa berstandar KKNI level II dan asosiasi profesi.',
                    'ikon' => 'fas fa-medal',
                ]);

                // Tautkan Syarat Kompetensi
                foreach ($comps as $c) {
                    $jTkk->syarats()->syncWithoutDetaching([$c->id_kompetensi]);
                }

                // Berikan ke beberapa siswa sampel
                $kelasJur = Kelas::where('kode_jurusan', $kodeJur)->first();
                $siswasTkk = Siswa::where('id_kelas', $kelasJur ? $kelasJur->id_kelas : 1)->take(4)->get();

                foreach ($siswasTkk as $sIdx => $stk) {
                    $statusTkk = ($sIdx % 2 == 0) ? 'diberikan' : 'proses';
                    $noPiagam = ($statusTkk === 'diberikan') ? ('NO.TKK/' . $kodeJur . '/' . date('Y') . '/' . str_pad($stk->id_siswa, 4, '0', STR_PAD_LEFT)) : null;

                    SiswaTkk::updateOrCreate([
                        'id_siswa' => $stk->id_siswa,
                        'id_jenis_tkk' => $jTkk->id_jenis_tkk,
                    ], [
                        'status' => $statusTkk,
                        'tanggal_perolehan' => ($statusTkk === 'diberikan') ? date('Y-m-d', strtotime('-' . ($sIdx + 1) * 3 . ' days')) : null,
                        'id_pemberi' => ($statusTkk === 'diberikan') ? $adminGuru->id_guru : null,
                        'nomor_piagam' => $noPiagam,
                        'catatan' => 'Telah memenuhi seluruh unit kompetensi prasyarat skema ' . $jTkk->nama_tkk,
                    ]);

                    if ($statusTkk === 'diberikan') {
                        CapaianSiswa::updateOrCreate([
                            'id_siswa' => $stk->id_siswa,
                            'jenis_capaian' => 'tkk',
                            'id_referensi' => $jTkk->id_jenis_tkk,
                        ], [
                            'id_kompetensi' => null,
                            'nilai' => 100.00,
                            'kkm' => 75.00,
                            'status' => 'kompeten',
                            'tanggal' => date('Y-m-d', strtotime('-' . ($sIdx + 1) * 3 . ' days')),
                            'id_penilai' => $adminGuru->id_guru,
                            'catatan' => 'Penganugerahan TKK: ' . $jTkk->nama_tkk . ' (' . $noPiagam . ')',
                        ]);
                    }
                }
            }
        }

        // =========================================================================
        // 9. PORTOFOLIO & SERTIFIKASI SISWA
        // =========================================================================
        echo "9. Membuat Portofolio & Sertifikat Siswa...\n";
        $portoSeed = [
            [
                'kategori' => 'sertifikat',
                'judul' => 'Sertifikat Kompetensi BNSP Junior Network Administrator',
                'penyelenggara' => 'LSP Telekomunikasi Indonesia / BNSP',
                'tautan' => 'https://lsptelematika.or.id/verify',
                'deskripsi' => 'Uji kompetensi nasional bersertifikasi negara untuk skema teknisi jaringan komputer.',
                'status' => 'disetujui',
            ],
            [
                'kategori' => 'proyek_karya',
                'judul' => 'Rancang Bangun Sistem Hotspot Mikrotik Voucher Berbasis IoT',
                'penyelenggara' => 'SMK Wisata Indonesia Innovation Lab',
                'tautan' => 'https://github.com/smkwi-tjkt/hotspot-billing',
                'deskripsi' => 'Pengembangan portal captive login terintegrasi cetak voucher termal otomatis.',
                'status' => 'disetujui',
            ],
            [
                'kategori' => 'prestasi_lomba',
                'judul' => 'Juara 1 Lomba Kompetensi Siswa (LKS) Tingkat Provinsi Bidang Restaurant Service',
                'penyelenggara' => 'Dinas Pendidikan Provinsi Jawa Barat',
                'tautan' => null,
                'deskripsi' => 'Mewakili kontingen sekolah pada ajang bergengsi LKS kejuruan perhotelan dan restoran.',
                'status' => 'disetujui',
            ],
            [
                'kategori' => 'sertifikat',
                'judul' => 'Sertifikasi Keahlian Barista & Coffee Sensory Specialist',
                'penyelenggara' => 'Specialty Coffee Association of Indonesia (SCAI)',
                'tautan' => null,
                'deskripsi' => 'Pelatihan kompetensi ekstraksi espresso, latte art, dan kalibrasi grinder.',
                'status' => 'menunggu',
            ],
            [
                'kategori' => 'proyek_karya',
                'judul' => 'Buku Resep & Produksi Artisan Sourdough Bakery Komersial',
                'penyelenggara' => 'Teaching Factory Kuliner SMK',
                'tautan' => 'https://drive.google.com/open?id=demo_portofolio_bakery',
                'deskripsi' => 'Produksi mandiri roti ragi alami dengan omzet pemasaran pada event car free day.',
                'status' => 'disetujui',
            ],
        ];

        $sampleSiswasAll = Siswa::take(10)->get();
        foreach ($portoSeed as $pIdx => $pData) {
            $siswaTarget = $sampleSiswasAll->get($pIdx % max(1, $sampleSiswasAll->count()));
            $kompTarget = Kompetensi::first();

            $porto = PortofolioSiswa::firstOrCreate([
                'id_siswa' => $siswaTarget->id_siswa,
                'judul' => $pData['judul'],
            ], [
                'id_kompetensi' => $kompTarget ? $kompTarget->id_kompetensi : null,
                'kategori' => $pData['kategori'],
                'penyelenggara' => $pData['penyelenggara'],
                'tanggal_kegiatan' => date('Y-m-d', strtotime('-' . ($pIdx * 5 + 2) . ' days')),
                'tautan_karya' => $pData['tautan'],
                'deskripsi' => $pData['deskripsi'],
                'status_verifikasi' => $pData['status'],
                'id_verifikator' => ($pData['status'] === 'disetujui') ? $adminGuru->id_guru : null,
                'catatan_verifikasi' => ($pData['status'] === 'disetujui') ? 'Portofolio memenuhi kriteria validasi dan layak dicatat di Buku Induk Capaian.' : null,
            ]);

            if ($pData['status'] === 'disetujui') {
                CapaianSiswa::updateOrCreate([
                    'id_siswa' => $siswaTarget->id_siswa,
                    'jenis_capaian' => 'sertifikat',
                    'id_referensi' => $porto->id_portofolio,
                ], [
                    'id_kompetensi' => $kompTarget ? $kompTarget->id_kompetensi : null,
                    'nilai' => 100.00,
                    'kkm' => 75.00,
                    'status' => 'kompeten',
                    'tanggal' => $porto->tanggal_kegiatan,
                    'id_penilai' => $adminGuru->id_guru,
                    'catatan' => ucfirst($porto->kategori) . ': ' . $porto->judul,
                ]);
            }
        }

        echo "=== Pembuatan Seluruh Data Dummy Kejuruan Berhasil Selesai ===\n";
    }
}
