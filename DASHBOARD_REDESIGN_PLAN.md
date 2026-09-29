# 📋 Rencana Implementasi Redesign Dashboard & Sistem SIAWI (SMK Wisata Indonesia)

Dokumen ini memuat arsitektur teknis, rencana desain antarmuka (UI/UX), perombakan backend/database query, serta tahapan eksekusi menyeluruh untuk mengimplementasikan tampilan dashboard baru yang modern, estetik, dan berbasis aksi cepat (maksimal 2 klik) sesuai mockup desain dan diagram alur kerja sistem.

---

## 🎯 1. Ringkasan & Sasaran Utama

Berdasarkan desain antarmuka dan diagram alur pengguna (*user flow*):
1. **Prinsip Utama UX**: *"Masalah yang perlu tindakan tampil di atas, data pendukung di bawah, semua aksi maksimal 2 klik."*
2. **Modern & Premium Look**:
   - Sidebar bernuansa **Deep Navy (`#0a192f` / `#0b2246`)** dengan active-item pill warna biru elektrik (`#1d72fe` / `#2563eb`).
   - Topbar bersih (*clean white*) dengan pencarian cepat (*quick search bar*), widget tanggal Bahasa Indonesia dinamis, notifikasi, dan profil admin.
   - Kartu metrik (*Stat Cards*) dengan palet warna pastel modern (Soft Blue, Soft Green, Soft Amber, Soft Red, Soft Purple).
   - Kartu Donut Chart kehadiran siswa yang informatif.
   - Tabel dan list dengan typography modern (**Plus Jakarta Sans** / **Inter**), avatar inisial berwarna, badge pill status, dan tombol aksi terarah.

---

## 🏗️ 2. Analisis Komponen & Kebutuhan Data

### A. Header Sambutan & Action Cepat
- **Sapaan Dinamis**: *"Selamat pagi/siang/sore/malam, [Nama User]"* dengan deskripsi *"Ringkasan kehadiran dan kegiatan sekolah hari ini."*
- **Action Buttons**:
  - `Ekspor laporan`: Dropdown/modal ekspor rekap harian/bulanan (Excel/PDF).
  - `Input absensi`: Tombol aksi langsung ke halaman input presensi cepat.

### B. 5 Kartu Ringkasan Metrik (Top Bar Cards)
| Kartu | Indikator | Data Sumber / Query |
| :--- | :--- | :--- |
| **1. Total Siswa** | Angka total & Subtext: *"Aktif tahun ajaran [TA]"* | `Siswa::where('status', 'aktif')->count()` |
| **2. Siswa Hadir** | Angka hadir & Subtext: *"[x]% dari [total] siswa"* | `Absensi::where('tanggal', $today)->where('kehadiran', 'hadir')->count()` |
| **3. Terlambat** | Angka siswa telat & Subtext: *"Belum ada / [x] siswa hari ini"* | `Absensi::where('tanggal', $today)->where('keterangan', 'like', '%terlambat%')->count()` |
| **4. Tidak Hadir** | Angka alpa/izin/sakit & Subtext: *"Sakit, izin, atau alpa"* | `Absensi::where('tanggal', $today)->whereIn('kehadiran', ['sakit','izin','alfa'])->count()` |
| **5. Guru Hadir** | Rasio hadir (misal `30/35`) & Subtext: *"[x] guru belum hadir"* | `AbsensiGuru::where('tanggal', $today)->count()` vs `Guru::count()` |

---

### C. Kolom Kiri: Area Aksi & Masalah Utama (Priority Section)
1. **Widget Kelas yang Belum Absen**:
   - **Header**: Menampilkan jumlah kelas yang belum absen (e.g. *"9 kelas perlu dicek hari ini"*) + Tombol `Ingatkan semua` (Kirim blast pengingat WhatsApp/notifikasi ke Wali Kelas).
   - **Tabel Ringkas**:
     - Kolom: `Kelas` (badge tingkat X/XI/XII), `Jumlah Siswa`, `Status` (Badge soft orange *"Belum diabsen"*), dan `Aksi` (*Lihat detail* modal / quick view).
   - **Interaksi**: Klik `Lihat detail` membuka modal daftar siswa di kelas tersebut yang belum diabsen.

2. **Widget Radar Siswa Kritis (Pelanggaran Tertinggi)**:
   - **Tujuan**: Menangani kasus siswa bermasalah secara dini.
   - **Tampilan**: List siswa dengan avatar inisial warna-warni, Nama Siswa, Kelas, Total Poin Pelanggaran, dan Status SP (Badge soft red/amber: `SP 1 (Orang tua)`, `SP 1`, dll.).
   - **Aksi Cepat**: Direct link untuk cetak SP, kirim pesan ke BK, atau hubungi orang tua murid.

---

### D. Kolom Kanan: Koordinasi & Statistik Harian (Support Section)
1. **Guru Piket Hari Ini**:
   - Menampilkan daftar guru piket pada hari berjalan beserta pembagian jam tugas (contoh: `06.30 - 10.00`, `10.00 - 12.30`, `12.30 - 15.00`).
   - Dilengkapi tombol/indikator untuk mengecek guru yang berhalangan hadir dan menugaskan guru pengganti.

2. **Donut Chart Kehadiran Siswa**:
   - Chart.js Donut interaktif dengan persentase kehadiran di tengah (contoh: `94,7%`).
   - Legend data berwarna di samping:
     - 🟢 Hadir
     - 🔴 Tidak Hadir (Sakit/Izin/Alpa)
     - 🟡 Terlambat
     - ⚪ Belum Absen

3. **Widget Datang Paling Awal (Early Birds)**:
   - Tab switcher interaktif: `[Siswa]` | `[Guru]`.
   - Menampilkan 4-5 peringkat pertama yang check-in paling awal hari ini dengan badge nomor urut hijau, nama, kelas/jabatan, dan jam masuk (contoh: `06.02`).

---

## 💻 3. Rencana Perubahan Teknis & File Terkait

```
├── app/Http/Controllers/
│   └── DashboardController.php      <-- Optimasi data aggregasi, hitung statistik, handling AJAX switch & reminder
├── resources/views/
│   ├── layout/
│   │   └── app.blade.php            <-- Update font Google (Plus Jakarta Sans), stylesheet modern, CSS custom tema
│   ├── partials/
│   │   ├── sidebar.blade.php        <-- Styling ulang full sidebar sesuai mockup (Deep Navy + Pill active)
│   │   ├── navbar.blade.php         <-- Styling ulang topbar (search bar, date widget, pill admin)
│   │   └── footer.blade.php         <-- Clean subtle footer
│   └── dashboard.blade.php          <-- Redesign penuh dashboard Blade sesuai mockup 1:1
```

---

## 🚀 4. Tahapan Eksekusi Bertahap (Execution Phases)

### 🔹 Fase 1: Perombakan Fondasi Tampilan & Layout Global
- Menambahkan Google Font `Plus Jakarta Sans` di [`app.blade.php`](file:///c:/laragon/www/siawi-admin/resources/views/layout/app.blade.php).
- Menata ulang stylesheet global: variabel warna (`--primary-blue: #1d72fe`, `--sidebar-bg: #0b1f3a`, `--card-bg: #ffffff`, `--bg-app: #f4f7fb`), border-radius, smooth transition, dan utilitas badge pastel.
- Mendesain ulang [`sidebar.blade.php`](file:///c:/laragon/www/siawi-admin/resources/views/partials/sidebar.blade.php) agar persis dengan mockup (Logo SIAWI SMK Wisata Indonesia, item menu pill, profile badge di bawah sidebar).
- Mendesain ulang [`navbar.blade.php`](file:///c:/laragon/www/siawi-admin/resources/views/partials/navbar.blade.php) dengan input search rounded, format tanggal Indonesia, dan quick profile button.

### 🔹 Fase 2: Peningkatan Logika Backend & Data Query
- Menyempurnakan query pada [`DashboardController.php`](file:///c:/laragon/www/siawi-admin/app/Http/Controllers/DashboardController.php):
  - Perhitungan persentase kehadiran siswa hari ini.
  - Query data keterlambatan dan yang belum absen per kelas.
  - Query guru piket dan alokasi jam tugas piket.
  - Query radar siswa kritis (poin tertinggi + status SP terbit).
  - Query data kehadiran tercepat (Siswa & Guru) untuk tab switcher.

### 🔹 Fase 3: Rekonstruksi Halaman Dashboard (`dashboard.blade.php`)
- Menyusun struktur grid 2 kolom (`col-lg-8` dan `col-lg-4` atau custom CSS grid) yang responsif dan presisi:
  - **Top Bar**: Salam dinamis + Tombol Aksi Cepat (*Ekspor laporan*, *Input absensi*).
  - **Top Cards**: 5 Stat Cards dengan ikon background pastel, angka tebal, dan label informatif.
  - **Left Section**: Tabel *Kelas yang belum absen* & Card *Radar siswa kritis*.
  - **Right Section**: Card *Guru piket hari ini*, Donut Chart *Kehadiran siswa*, dan Card *Datang paling awal* (dengan tab Siswa/Guru yang dapat di-toggle).

### 🔹 Fase 4: Integrasi Interaktivitas & Alur 2-Klik
- Menghubungkan Chart.js untuk Donut Chart Kehadiran Siswa dengan animasi halus dan rendering label tengah (*Center Text Plugin*).
- Menambahkan modal/fitur cepat:
  - Modal/Aksi *"Ingatkan semua"* untuk kelas yang belum diabsen.
  - Modal *"Lihat detail"* per kelas yang belum diabsen.
  - Interaksi tab switcher Siswa vs Guru pada widget *Datang paling awal*.
- Memastikan navigasi tombol aksi pada *Radar siswa kritis* terhubung langsung ke profil siswa / halaman BK / SP.

### 🔹 Fase 5: Validasi & Pengujian Responsivitas
- Pengujian visual pada resolusi desktop, tablet, dan mobile.
- Memastikan tidak ada tabrakan style dengan AdminLTE di halaman sub-modul lainnya.
- Verifikasi konsistensi data yang tampil dengan database riil SIAWI.

---

## ❓ 5. Langkah Selanjutnya

Silakan tinjau file rencana implementasi ini. Jika sudah sesuai, Anda dapat mengonfirmasi agar saya langsung mengeksekusi perombakan kode mulai dari **Fase 1**.
