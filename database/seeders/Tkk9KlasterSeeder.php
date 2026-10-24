<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Kompetensi;
use App\Models\JenisTkk;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\SiswaTkk;
use App\Models\CapaianSiswa;

class Tkk9KlasterSeeder extends Seeder
{
    public function run()
    {
        echo "=== Menjalankan Seeder 9 Klaster TKK TJKT ===\n";

        $tjkt = Jurusan::where('kode_jurusan', 'TJKT')->first();
        if (!$tjkt) {
            echo "Jurusan TJKT tidak ditemukan.\n";
            return;
        }

        $adminGuru = Guru::first();

        // 1. Data Unit Kompetensi Lengkap untuk 9 Klaster TJKT
        $unitKompetensiData = [
            // Klaster 1 (Kelas X Sem 1)
            'TJKT-K01-01' => ['nama' => 'Mengidentifikasi Komponen Perangkat Keras Komputer', 'tingkat' => 'X', 'sem' => 1, 'kkm' => 75],
            'TJKT-K01-02' => ['nama' => 'Merakit dan Menguji Fungsi Komputer Workstation', 'tingkat' => 'X', 'sem' => 1, 'kkm' => 75],

            // Klaster 2 (Kelas X Sem 2)
            'TJKT-K02-01' => ['nama' => 'Memasang Kabel UTP dan Terminasi Konektor RJ-45', 'tingkat' => 'X', 'sem' => 2, 'kkm' => 75],
            'TJKT-K02-02' => ['nama' => 'Mengonfigurasi dan Menguji Pengkabelan Jaringan Lokal (LAN)', 'tingkat' => 'X', 'sem' => 2, 'kkm' => 75],

            // Klaster 3 (Kelas XI Sem 1)
            'TJKT-K03-01' => ['nama' => 'Menginstalasi Perangkat Jaringan Nirkabel (WLAN / Access Point)', 'tingkat' => 'XI', 'sem' => 1, 'kkm' => 78],
            'TJKT-K03-02' => ['nama' => 'Mengonfigurasi Keamanan Hotspot Gateway & Captive Portal', 'tingkat' => 'XI', 'sem' => 1, 'kkm' => 78],

            // Klaster 4 (Kelas XI Sem 2)
            'TJKT-K04-01' => ['nama' => 'Mengonfigurasi VLAN dan Inter-VLAN Routing pada Switch Manageable', 'tingkat' => 'XI', 'sem' => 2, 'kkm' => 80],
            'TJKT-K04-02' => ['nama' => 'Mengonfigurasi Routing Dinamis OSPF Multi-Area & Failover', 'tingkat' => 'XI', 'sem' => 2, 'kkm' => 80],

            // Klaster 5 (Kelas XI Sem 2)
            'TJKT-K05-01' => ['nama' => 'Melakukan Penyambungan Kabel Fiber Optic (Fusion Splicer)', 'tingkat' => 'XI', 'sem' => 2, 'kkm' => 78],
            'TJKT-K05-02' => ['nama' => 'Melakukan Pengukuran Redaman Serat Optik (OTDR & OPM)', 'tingkat' => 'XI', 'sem' => 2, 'kkm' => 78],

            // Klaster 6 (Kelas XII Sem 1)
            'TJKT-K06-01' => ['nama' => 'Menginstalasi dan Mengadministrasi Sistem Operasi Server Linux', 'tingkat' => 'XII', 'sem' => 1, 'kkm' => 78],
            'TJKT-K06-02' => ['nama' => 'Mengonfigurasi Layanan Jaringan (DNS, Web Server, dan Database)', 'tingkat' => 'XII', 'sem' => 1, 'kkm' => 80],

            // Klaster 7 (Kelas XII Sem 1)
            'TJKT-K07-01' => ['nama' => 'Menerapkan Firewall Filtering, NAT, dan Mangle Traffic', 'tingkat' => 'XII', 'sem' => 1, 'kkm' => 80],
            'TJKT-K07-02' => ['nama' => 'Menerapkan VPN Tunneling dan Sistem Pencegahan Intrusi Jaringan', 'tingkat' => 'XII', 'sem' => 1, 'kkm' => 80],

            // Klaster 8 (Kelas XII Sem 2)
            'TJKT-K08-01' => ['nama' => 'Membangun Infrastruktur Virtualisasi Server Berbasis Hypervisor', 'tingkat' => 'XII', 'sem' => 2, 'kkm' => 80],
            'TJKT-K08-02' => ['nama' => 'Mengelola Komputasi Awan (Cloud Storage & Container Deployment)', 'tingkat' => 'XII', 'sem' => 2, 'kkm' => 80],

            // Klaster 9 (Kelas XII Sem 2)
            'TJKT-K09-01' => ['nama' => 'Mendiagnosis dan Mengatasi Permasalahan Jaringan Enterprise', 'tingkat' => 'XII', 'sem' => 2, 'kkm' => 80],
            'TJKT-K09-02' => ['nama' => 'Melakukan Manajemen Bandwidth dan Quality of Service (QoS)', 'tingkat' => 'XII', 'sem' => 2, 'kkm' => 80],
        ];

        $kompetensiModels = [];
        foreach ($unitKompetensiData as $kode => $d) {
            $comp = Kompetensi::updateOrCreate([
                'kode_kompetensi' => $kode,
            ], [
                'nama_kompetensi' => $d['nama'],
                'id_jurusan' => $tjkt->id_jurusan,
                'tingkat' => $d['tingkat'],
                'semester' => is_numeric($d['sem']) ? $d['sem'] : 1,
                'kkm' => $d['kkm'],
            ]);
            $kompetensiModels[$kode] = $comp;
        }

        // 2. Daftar 9 Klaster TKK TJKT
        $klasters = [
            1 => [
                'kode' => 'TKK-TJKT-01',
                'nama' => 'Perakitan & Perawatan PC',
                'tingkat' => 'dasar',
                'deskripsi' => 'Kecakapan perakitan komponen PC, instalasi BIOS, sistem operasi, dan perawatan perangkat keras.',
                'units' => ['TJKT-K01-01', 'TJKT-K01-02'],
            ],
            2 => [
                'kode' => 'TKK-TJKT-02',
                'nama' => 'Pengkabelan & Jaringan Lokal (LAN)',
                'tingkat' => 'dasar',
                'deskripsi' => 'Kecakapan crimping kabel UTP Cat6, terminasi patch panel, fluking test, dan setup topologi LAN.',
                'units' => ['TJKT-K02-01', 'TJKT-K02-02'],
            ],
            3 => [
                'kode' => 'TKK-TJKT-03',
                'nama' => 'Jaringan Nirkabel & Hotspot (WLAN)',
                'tingkat' => 'dasar',
                'deskripsi' => 'Kecakapan instalasi Access Point indoor/outdoor, bridging wireless, dan manajemen hotspot voucher.',
                'units' => ['TJKT-K03-01', 'TJKT-K03-02'],
            ],
            4 => [
                'kode' => 'TKK-TJKT-04',
                'nama' => 'Routing & Switching Enterprise',
                'tingkat' => 'menengah',
                'deskripsi' => 'Kecakapan konfigurasi VLAN, trunking 802.1Q, routing dinamis OSPF, dan redundansi link jaringan.',
                'units' => ['TJKT-K04-01', 'TJKT-K04-02'],
            ],
            5 => [
                'kode' => 'TKK-TJKT-05',
                'nama' => 'Teknisi Fiber Optic (Splicing & OTDR)',
                'tingkat' => 'menengah',
                'deskripsi' => 'Kecakapan pengupasan serat kaca, fusion splicing core FO, terminasi OTB/ODP, dan analisis loss OTDR.',
                'units' => ['TJKT-K05-01', 'TJKT-K05-02'],
            ],
            6 => [
                'kode' => 'TKK-TJKT-06',
                'nama' => 'Administrasi Server Jaringan (Linux)',
                'tingkat' => 'menengah',
                'deskripsi' => 'Kecakapan instalasi distro Linux Server, konfigurasi BIND DNS, Nginx/Apache Web Server, dan MySQL.',
                'units' => ['TJKT-K06-01', 'TJKT-K06-02'],
            ],
            7 => [
                'kode' => 'TKK-TJKT-07',
                'nama' => 'Network Security & Firewall Filtering',
                'tingkat' => 'utama',
                'deskripsi' => 'Kecakapan implementasi security rules, port forwarding, blocking malware, dan enkripsi VPN WireGuard/IPsec.',
                'units' => ['TJKT-K07-01', 'TJKT-K07-02'],
            ],
            8 => [
                'kode' => 'TKK-TJKT-08',
                'nama' => 'Cloud Infrastructure & Virtualisasi',
                'tingkat' => 'utama',
                'deskripsi' => 'Kecakapan deployment hypervisor virtualisasi, pengelolaan cluster VM, dan container docker.',
                'units' => ['TJKT-K08-01', 'TJKT-K08-02'],
            ],
            9 => [
                'kode' => 'TKK-TJKT-09',
                'nama' => 'Troubleshooting & Maintenance Jaringan',
                'tingkat' => 'utama',
                'deskripsi' => 'Kecakapan audit performa throughput jaringan, packet analysis Wireshark, dan mitigasi downtime.',
                'units' => ['TJKT-K09-01', 'TJKT-K09-02'],
            ],
        ];

        $tkkModels = [];
        foreach ($klasters as $nomor => $kData) {
            $tkk = JenisTkk::updateOrCreate([
                'id_jurusan' => $tjkt->id_jurusan,
                'nomor_klaster' => $nomor,
            ], [
                'kode_tkk' => $kData['kode'],
                'nama_tkk' => $kData['nama'],
                'tingkat' => $kData['tingkat'],
                'deskripsi' => $kData['deskripsi'],
                'ikon' => 'fas fa-medal',
            ]);

            // Sync unit kompetensi prasyarat klaster
            $compIds = [];
            foreach ($kData['units'] as $uKode) {
                if (isset($kompetensiModels[$uKode])) {
                    $compIds[] = $kompetensiModels[$uKode]->id_kompetensi;
                }
            }
            $tkk->syarats()->sync($compIds);
            $tkkModels[$nomor] = $tkk;
            echo "Klaster #{$nomor}: {$tkk->nama_tkk} berhasil disiapkan (" . count($compIds) . " Unit Syarat).\n";
        }

        // 3. Simulasi Capaian Siswa Kelas XII-TKJ
        $kelasTkj = Kelas::where('nama_kelas', 'like', '%TKJ%')->first() ?? Kelas::first();
        if ($kelasTkj) {
            $siswas = Siswa::where('id_kelas', $kelasTkj->id_kelas)->take(8)->get();

            foreach ($siswas as $idx => $s) {
                // Semua siswa lulus Klaster 1 & 2
                foreach (['TJKT-K01-01', 'TJKT-K01-02', 'TJKT-K02-01', 'TJKT-K02-02'] as $uK) {
                    if (isset($kompetensiModels[$uK])) {
                        CapaianSiswa::updateOrCreate([
                            'id_siswa' => $s->id_siswa,
                            'id_kompetensi' => $kompetensiModels[$uK]->id_kompetensi,
                            'jenis_capaian' => 'praktik',
                        ], [
                            'nilai' => 88.00 + ($idx % 5),
                            'kkm' => 75.00,
                            'status' => 'kompeten',
                            'tanggal' => date('Y-m-d', strtotime('-60 days')),
                            'id_penilai' => $adminGuru ? $adminGuru->id_guru : 1,
                            'catatan' => 'Lulus Praktikum KBM Mandiri',
                        ]);
                    }
                }

                // Sahkan TKK Klaster 1
                $noPiagam1 = "NO.TKK/TJKT/KL-1/" . date('Y') . "/" . str_pad($s->id_siswa, 4, '0', STR_PAD_LEFT);
                SiswaTkk::updateOrCreate([
                    'id_siswa' => $s->id_siswa,
                    'id_jenis_tkk' => $tkkModels[1]->id_jenis_tkk,
                ], [
                    'status' => 'diberikan',
                    'tanggal_perolehan' => date('Y-m-d', strtotime('-40 days')),
                    'id_pemberi' => $adminGuru ? $adminGuru->id_guru : 1,
                    'nomor_piagam' => $noPiagam1,
                    'catatan' => 'Memenuhi seluruh unit kompetensi Klaster 1.',
                ]);

                // Sahkan TKK Klaster 2
                $noPiagam2 = "NO.TKK/TJKT/KL-2/" . date('Y') . "/" . str_pad($s->id_siswa, 4, '0', STR_PAD_LEFT);
                SiswaTkk::updateOrCreate([
                    'id_siswa' => $s->id_siswa,
                    'id_jenis_tkk' => $tkkModels[2]->id_jenis_tkk,
                ], [
                    'status' => 'diberikan',
                    'tanggal_perolehan' => date('Y-m-d', strtotime('-30 days')),
                    'id_pemberi' => $adminGuru ? $adminGuru->id_guru : 1,
                    'nomor_piagam' => $noPiagam2,
                    'catatan' => 'Memenuhi seluruh unit kompetensi Klaster 2.',
                ]);

                // Sebagian siswa lulus Klaster 3 & 4 (sehingga berstatus Memenuhi Syarat untuk disahkan)
                if ($idx < 4) {
                    foreach (['TJKT-K03-01', 'TJKT-K03-02', 'TJKT-K04-01', 'TJKT-K04-02'] as $uK) {
                        if (isset($kompetensiModels[$uK])) {
                            CapaianSiswa::updateOrCreate([
                                'id_siswa' => $s->id_siswa,
                                'id_kompetensi' => $kompetensiModels[$uK]->id_kompetensi,
                                'jenis_capaian' => 'praktik',
                            ], [
                                'nilai' => 85.00 + ($idx % 5),
                                'kkm' => 78.00,
                                'status' => 'kompeten',
                                'tanggal' => date('Y-m-d', strtotime('-15 days')),
                                'id_penilai' => $adminGuru ? $adminGuru->id_guru : 1,
                                'catatan' => 'Lulus Praktikum Lanjutan',
                            ]);
                        }
                    }
                } else {
                    // Hanya lulus 1 unit dari Klaster 3 (sehingga berstatus Dalam Proses)
                    if (isset($kompetensiModels['TJKT-K03-01'])) {
                        CapaianSiswa::updateOrCreate([
                            'id_siswa' => $s->id_siswa,
                            'id_kompetensi' => $kompetensiModels['TJKT-K03-01']->id_kompetensi,
                            'jenis_capaian' => 'praktik',
                        ], [
                            'nilai' => 80.00,
                            'kkm' => 78.00,
                            'status' => 'kompeten',
                            'tanggal' => date('Y-m-d', strtotime('-10 days')),
                            'id_penilai' => $adminGuru ? $adminGuru->id_guru : 1,
                            'catatan' => 'Lulus Unit Dasar Wireless',
                        ]);
                    }
                }
            }
        }

        echo "=== 9 Klaster TKK TJKT Berhasil Digenerate & Terintegrasi ===\n";
    }
}
