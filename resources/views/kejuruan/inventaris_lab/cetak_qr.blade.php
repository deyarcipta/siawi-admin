<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Label Stiker QR Code Inventaris Lab - {{ $setting->nama_sekolah ?? 'SIAWI' }}</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }
    body {
      background-color: #f1f5f9;
      color: #0f172a;
      padding: 20px;
    }
    .no-print {
      max-width: 900px;
      margin: 0 auto 20px auto;
      background: #ffffff;
      padding: 14px 20px;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .btn {
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      border: none;
      font-size: 13px;
      text-decoration: none;
    }
    .btn-primary {
      background-color: #2563eb;
      color: #ffffff;
    }
    .btn-secondary {
      background-color: #e2e8f0;
      color: #334155;
    }
    .sticker-container {
      max-width: 900px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
    }
    .sticker-card {
      background: #ffffff;
      border: 1.5px dashed #94a3b8;
      border-radius: 8px;
      padding: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      page-break-inside: avoid;
    }
    .sticker-header {
      width: 100%;
      border-bottom: 1px solid #cbd5e1;
      padding-bottom: 4px;
      margin-bottom: 8px;
    }
    .school-title {
      font-size: 9px;
      text-transform: uppercase;
      font-weight: 800;
      color: #1e293b;
      letter-spacing: 0.5px;
    }
    .lab-title {
      font-size: 8px;
      color: #64748b;
      font-weight: 600;
    }
    .qr-wrapper {
      padding: 4px;
      background: #ffffff;
      margin-bottom: 6px;
    }
    .qr-wrapper svg {
      width: 100px;
      height: 100px;
    }
    .asset-code {
      font-size: 11px;
      font-weight: 900;
      color: #0f172a;
      letter-spacing: 0.5px;
      font-family: 'Courier New', Courier, monospace;
      margin-bottom: 3px;
    }
    .asset-name {
      font-size: 10px;
      font-weight: 700;
      color: #334155;
      max-width: 100%;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 2px;
    }
    .asset-detail {
      font-size: 8px;
      color: #64748b;
    }
    @media print {
      body {
        background: transparent;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
      .sticker-container {
        max-width: 100%;
        gap: 10px;
      }
      .sticker-card {
        border: 1px solid #000000;
      }
    }
  </style>
</head>
<body>

  <div class="no-print">
    <div>
      <strong>Cetak Label Stiker QR Code Inventaris</strong>
      <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
        Jumlah aset siap cetak: {{ $asets->count() }} label
      </div>
    </div>
    <div>
      <a href="javascript:window.history.back()" class="btn btn-secondary" style="margin-right: 8px;">Kembali</a>
      <button onclick="window.print()" class="btn btn-primary">Cetak Stiker</button>
    </div>
  </div>

  <div class="sticker-container">
    @foreach($asets as $aset)
      <div class="sticker-card">
        <div class="sticker-header">
          <div class="school-title">{{ $setting->nama_sekolah ?? 'SMK NEGERI' }}</div>
          <div class="lab-title">{{ $aset->lab->nama_lab ?? 'Laboratorium Kejuruan' }}</div>
        </div>

        <div class="qr-wrapper">
          {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(url('/admin/kejuruan/inventaris-lab/aset/' . $aset->id_aset)) !!}
        </div>

        <div class="asset-code">{{ $aset->kode_aset }}</div>
        <div class="asset-name" title="{{ $aset->nama_aset }}">{{ $aset->nama_aset }}</div>
        <div class="asset-detail">
          Tahun: {{ $aset->tahun_perolehan }} | Kategori: {{ ucfirst(str_replace('_', ' ', $aset->kategori)) }}
        </div>
      </div>
    @endforeach
  </div>

</body>
</html>
