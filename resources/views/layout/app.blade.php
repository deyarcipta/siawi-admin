<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset("storage/gambar/$setting->logo") }}" type="image/x-icon">
  <title>{{ config('app.name', 'SIAWI | SMK Wisata Indonesia') }}</title>

  <!-- Google Font: Inter (Tinggi Keterbacaan & Nyaman untuk Segala Usia) & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  
  <style>
    :root {
      --font-family-base: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      --navy-sidebar-bg: #0b1f3a;
      --navy-sidebar-dark: #07162c;
      --navy-sidebar-hover: rgba(255, 255, 255, 0.06);
      --navy-active-pill: #1d72fe;
      --app-bg: #f4f7fb;
      --card-radius: 16px;
      --primary-blue: #1d72fe;
      --text-dark: #0f172a;
      --text-muted: #64748b;
    }

    body {
      font-family: var(--font-family-base) !important;
      background-color: var(--app-bg) !important;
      color: var(--text-dark);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .wrapper {
      background-color: var(--app-bg) !important;
      position: relative;
    }

    .content-wrapper {
      background-color: var(--app-bg) !important;
      min-height: calc(100vh - 64px) !important;
      margin-top: 0 !important;
      padding-top: 0 !important;
    }

    /* === MODERN DEEP NAVY SIDEBAR STYLING === */
    :root {
      --sidebar-width: 255px;
    }

    .main-sidebar {
      background-color: var(--navy-sidebar-bg) !important;
      box-shadow: 4px 0 25px rgba(0, 0, 0, 0.12) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.04) !important;
      position: fixed !important;
      top: 0 !important;
      bottom: 0 !important;
      left: 0 !important;
      height: 100vh !important;
      z-index: 1038 !important;
      overflow: hidden !important;
      width: var(--sidebar-width) !important;
      transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
    }

    .main-sidebar::before {
      width: var(--sidebar-width) !important;
    }

    @media (min-width: 768px) {
      .main-sidebar,
      .main-sidebar::before {
        width: var(--sidebar-width) !important;
      }
      body:not(.sidebar-collapse) .content-wrapper,
      body:not(.sidebar-collapse) .main-footer,
      body:not(.sidebar-collapse) .main-header {
        margin-left: var(--sidebar-width) !important;
      }
      body.sidebar-collapse .main-sidebar,
      body.sidebar-collapse .main-sidebar::before {
        margin-left: calc(var(--sidebar-width) * -1) !important;
      }
      body.sidebar-collapse .content-wrapper,
      body.sidebar-collapse .main-footer,
      body.sidebar-collapse .main-header {
        margin-left: 0 !important;
      }
    }

    @media (max-width: 767.98px) {
      .main-sidebar,
      .main-sidebar::before {
        box-shadow: none !important;
        margin-left: calc(var(--sidebar-width) * -1) !important;
      }
      body.sidebar-open .main-sidebar,
      body.sidebar-open .main-sidebar::before {
        margin-left: 0 !important;
        box-shadow: 4px 0 25px rgba(0, 0, 0, 0.25) !important;
      }
      body:not(.sidebar-open) .main-sidebar {
        pointer-events: none;
      }
      body.sidebar-open .main-sidebar {
        pointer-events: auto;
      }
      #sidebar-overlay {
        background-color: rgba(15, 23, 42, 0.6) !important;
        backdrop-filter: blur(2px) !important;
        -webkit-backdrop-filter: blur(2px) !important;
        z-index: 1037 !important;
      }
      .content-wrapper,
      .main-footer,
      .main-header {
        margin-left: 0 !important;
      }
    }

    .main-sidebar .sidebar {
      height: calc(100vh - 70px - 65px) !important;
      overflow-y: auto !important;
      padding-bottom: 20px !important;
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    /* Sleek Scrollbar */
    .main-sidebar .sidebar::-webkit-scrollbar {
      width: 4px;
    }
    .main-sidebar .sidebar::-webkit-scrollbar-track {
      background: transparent;
    }
    .main-sidebar .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 10px;
      transition: background 0.2s ease;
    }
    .main-sidebar .sidebar:hover::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.22);
    }

    /* Brand Header */
    .brand-link {
      background-color: var(--navy-sidebar-bg) !important;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
      padding: 16px 18px !important;
      height: 70px !important;
      display: flex !important;
      align-items: center;
      gap: 12px;
      text-decoration: none !important;
      width: var(--sidebar-width) !important;
    }

    .brand-logo-icon {
      width: 38px;
      height: 38px;
      background: linear-gradient(135deg, #1d72fe 0%, #0052cc 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(29, 114, 254, 0.4);
      flex-shrink: 0;
    }

    .brand-text-wrapper {
      display: flex;
      flex-direction: column;
      line-height: 1.25;
    }

    .brand-title {
      font-size: 1.05rem;
      font-weight: 800;
      color: #ffffff !important;
      letter-spacing: 0.5px;
    }

    .brand-subtitle {
      font-size: 0.72rem;
      color: #8da2c0;
      font-weight: 500;
      letter-spacing: 0.2px;
      margin-top: 1px;
    }

    /* Fix AdminLTE Card Header flexbox pseudo-element */
    .card-header.d-flex::after,
    .card-header > .d-flex::after {
      display: none !important;
      content: none !important;
    }

    /* Menu Hierarchy (Berjenjang) */
    .nav-sidebar {
      padding: 10px 8px !important;
      width: 100% !important;
    }

    .nav-sidebar > .nav-item {
      margin-bottom: 3px;
      width: 100%;
    }

    /* Main Menu Parent Link */
    .nav-sidebar > .nav-item > .nav-link {
      border-radius: 8px !important;
      padding: 8.5px 12px !important;
      font-size: 0.86rem !important;
      font-weight: 600 !important;
      color: #94a3b8 !important;
      display: flex;
      align-items: center;
      position: relative !important;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-sidebar > .nav-item > .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.05) !important;
      color: #f1f5f9 !important;
      transform: translateX(2px);
    }

    /* Open Treeview Parent Item */
    .nav-sidebar > .nav-item.menu-open > .nav-link:not(.active) {
      background-color: rgba(255, 255, 255, 0.04) !important;
      color: #f8fafc !important;
    }

    /* Active Main Pill */
    .nav-sidebar > .nav-item > .nav-link.active {
      background: linear-gradient(135deg, #1d72fe 0%, #155ecc 100%) !important;
      color: #ffffff !important;
      box-shadow: 0 4px 14px rgba(29, 114, 254, 0.35) !important;
      font-weight: 700 !important;
    }

    .nav-sidebar > .nav-item > .nav-link .nav-icon {
      font-size: 0.95rem !important;
      margin-right: 12px !important;
      width: 18px;
      text-align: center;
      color: inherit;
      flex-shrink: 0;
      opacity: 0.85;
    }

    .nav-sidebar > .nav-item > .nav-link.active .nav-icon {
      opacity: 1;
    }

    /* Sidebar Section Divider Headers (Alur Kerja) */
    .nav-sidebar .nav-header {
      padding: 6px 10px 2px 8px !important;
      font-size: 0.65rem !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.08em !important;
      color: #94a3b8 !important;
      display: flex !important;
      align-items: center !important;
      gap: 7px !important;
      background: transparent !important;
      margin-top: 4px !important;
      margin-bottom: 2px !important;
      line-height: 1.2 !important;
    }

    .nav-sidebar .nav-header:first-of-type {
      margin-top: 2px !important;
      padding-top: 3px !important;
    }

    .nav-sidebar .nav-header::before {
      content: "";
      display: inline-block;
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: #38bdf8;
      box-shadow: 0 0 6px rgba(56, 189, 248, 0.7);
      flex-shrink: 0;
    }

    .nav-sidebar .nav-header::after {
      content: "";
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 100%);
      margin-left: 6px;
    }

    .nav-sidebar > .nav-item > .nav-link p {
      font-size: 0.86rem !important;
      margin-bottom: 0 !important;
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      white-space: nowrap !important;
      padding-right: 18px;
    }

    .nav-sidebar > .nav-item > .nav-link p .right,
    .nav-sidebar > .nav-item > .nav-link > .right {
      position: absolute !important;
      right: 12px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      font-size: 0.72rem !important;
      transition: transform 0.25s ease-in-out !important;
      opacity: 0.75;
    }

    .nav-sidebar > .nav-item.menu-open > .nav-link p .right,
    .nav-sidebar > .nav-item.menu-open > .nav-link > .right {
      transform: translateY(-50%) rotate(-90deg) !important;
    }

    /* Sub-Menu Hierarchy Container (Berjenjang) */
    .nav-sidebar .nav-treeview {
      padding-left: 8px !important;
      margin-left: 12px !important;
      margin-top: 3px !important;
      margin-bottom: 5px !important;
      border-left: 1.5px solid rgba(255, 255, 255, 0.08) !important;
    }

    .nav-sidebar .nav-treeview .nav-item {
      margin-bottom: 2px;
      position: relative;
    }

    .nav-sidebar .nav-treeview .nav-link {
      border-radius: 6px !important;
      padding: 6.5px 10px !important;
      font-size: 0.80rem !important;
      font-weight: 500 !important;
      color: #8da2c0 !important;
      transition: all 0.18s ease;
      display: flex;
      align-items: center;
      white-space: nowrap !important;
    }

    .nav-sidebar .nav-treeview .nav-link:hover {
      color: #ffffff !important;
      background-color: rgba(255, 255, 255, 0.05) !important;
      padding-left: 13px !important;
    }

    .nav-sidebar .nav-treeview .nav-link.active {
      background-color: rgba(29, 114, 254, 0.16) !important;
      color: #60a5fa !important;
      font-weight: 600 !important;
    }

    .nav-sidebar .nav-treeview .nav-link .nav-icon {
      font-size: 5px !important;
      margin-right: 8px !important;
      width: 8px !important;
      opacity: 0.65;
      flex-shrink: 0 !important;
      transition: all 0.2s ease;
    }

    .nav-sidebar .nav-treeview .nav-link:hover .nav-icon,
    .nav-sidebar .nav-treeview .nav-link.active .nav-icon {
      opacity: 1;
      color: #38bdf8 !important;
      transform: scale(1.3);
    }

    .nav-sidebar .nav-treeview .nav-link p {
      font-size: 0.80rem !important;
      letter-spacing: 0.1px;
      margin: 0 !important;
      white-space: nowrap !important;
      overflow: visible !important;
      text-overflow: clip !important;
      display: inline-block !important;
    }

    /* Docked Bottom User Card in Sidebar */
    .sidebar-user-dock {
      position: absolute !important;
      bottom: 0 !important;
      left: 0 !important;
      right: 0 !important;
      width: var(--sidebar-width) !important;
      height: 65px !important;
      padding: 12px 16px !important;
      background-color: #071324 !important;
      border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
      display: flex !important;
      align-items: center;
      gap: 12px;
      z-index: 1040 !important;
    }

    .sidebar-user-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg, #1d72fe 0%, #0052cc 100%);
      border: 2px solid rgba(255, 255, 255, 0.12);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .sidebar-user-info {
      display: flex;
      flex-direction: column;
      line-height: 1.25;
      overflow: hidden;
    }

    .sidebar-user-name {
      color: #f1f5f9;
      font-weight: 600;
      font-size: 0.84rem;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }

    .sidebar-user-role {
      color: #8da2c0;
      font-size: 0.72rem;
      font-weight: 500;
    }

    /* === MODERN TOPBAR NAVBAR === */
    .main-header.navbar {
      background-color: #ffffff !important;
      border-bottom: 1px solid #eef2f6 !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
      padding: 10px 24px !important;
      min-height: 64px;
      position: sticky !important;
      top: 0 !important;
      z-index: 1030 !important;
    }

    .navbar-search-pill {
      background: #f1f5f9;
      border: 1px solid #e2e8f0;
      border-radius: 24px;
      padding: 8px 18px;
      display: flex;
      align-items: center;
      gap: 10px;
      width: 320px;
      transition: all 0.2s ease;
    }

    .navbar-search-pill:focus-within {
      background: #ffffff;
      border-color: #1d72fe;
      box-shadow: 0 0 0 3px rgba(29, 114, 254, 0.12);
    }

    .navbar-search-pill input {
      border: none;
      background: transparent;
      outline: none;
      font-size: 0.85rem;
      color: #334155;
      width: 100%;
    }

    .navbar-search-pill input::placeholder {
      color: #94a3b8;
    }

    .navbar-date-badge {
      color: #64748b;
      font-size: 0.85rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-right: 18px;
    }

    .navbar-admin-pill {
      background: #1d72fe;
      color: #ffffff !important;
      border-radius: 20px;
      padding: 6px 16px;
      font-weight: 600;
      font-size: 0.84rem;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none !important;
      box-shadow: 0 2px 8px rgba(29, 114, 254, 0.3);
      transition: all 0.2s ease;
    }

    .navbar-admin-pill:hover {
      background: #155ecc;
      transform: translateY(-1px);
    }

    .navbar-circle-btn {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: #f1f5f9;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      margin-right: 10px;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .navbar-circle-btn:hover {
      background: #e2e8f0;
      color: #1e293b;
    }

    /* Modern Soft Badges */
    .badge, .badge-pill {
      border-radius: 20px !important;
      padding: 5px 12px !important;
      font-weight: 600 !important;
      font-size: 0.75rem !important;
      letter-spacing: 0.02em;
    }
    .badge-soft-danger, .badge-danger, .sp-danger {
      background-color: #fee2e2 !important;
      color: #dc2626 !important;
    }
    .badge-soft-warning, .badge-warning, .sp-warning {
      background-color: #fef3c7 !important;
      color: #d97706 !important;
    }
    .badge-soft-amber, .sp-amber {
      background-color: #ffedd5 !important;
      color: #c2410c !important;
    }
    .badge-soft-success, .badge-success {
      background-color: #dcfce7 !important;
      color: #15803d !important;
    }
    .badge-soft-info, .badge-info, .sp-info {
      background-color: #e0f2fe !important;
      color: #0369a1 !important;
    }
    .badge-soft-purple, .badge-purple {
      background-color: #f3e8ff !important;
      color: #7e22ce !important;
    }
    .badge-soft-primary, .badge-primary {
      background-color: #eff6ff !important;
      color: #1d72fe !important;
    }
    .badge-soft-secondary, .badge-secondary {
      background-color: #f1f5f9 !important;
      color: #64748b !important;
    }

    /* === GLOBAL MODERN DESIGN SYSTEM (Matching Laporan Kedisiplinan) === */
    .content-header {
      padding: 24px 30px 14px 30px !important;
    }
    .content {
      padding: 0 30px 30px 30px !important;
    }
    .content-header .container-fluid,
    .content .container-fluid {
      padding-left: 0 !important;
      padding-right: 0 !important;
      max-width: 100% !important;
    }

    @media (max-width: 767.98px) {
      .content-header {
        padding: 16px 16px 8px 16px !important;
      }
      .content {
        padding: 0 16px 20px 16px !important;
      }
      /* Sembunyikan breadcrumb pada tampilan mobile agar rapi, fokus, dan hemat ruang vertikal */
      .content-header .breadcrumb,
      .content-header ol.breadcrumb,
      .breadcrumb {
        display: none !important;
      }
      /* Optimasi ukuran judul & subjudul header di layar mobile */
      .content-header h1 {
        font-size: 1.18rem !important;
        line-height: 1.25 !important;
      }
      .content-header p,
      .content-header .text-muted {
        font-size: 0.78rem !important;
      }
      .content-header i.fas,
      .content-header i.fa,
      .content-header i.far {
        font-size: 1.6rem !important;
      }
    }

    .content-header h1 {
      font-size: 1.45rem !important;
      font-weight: 800 !important;
      color: #0f172a !important;
      letter-spacing: -0.02em !important;
    }
    .content-header p, .content-header .text-muted, .content-header .text-sm {
      color: #64748b !important;
      font-size: 0.85rem !important;
    }
    .breadcrumb {
      background: transparent !important;
      padding: 0 !important;
      margin: 0 !important;
      font-size: 0.80rem !important;
      font-weight: 500 !important;
    }
    .breadcrumb-item a {
      color: #64748b !important;
      text-decoration: none !important;
    }
    .breadcrumb-item.active {
      color: #94a3b8 !important;
    }

    /* Modern Card Container */
    .card, .card-default, .card-primary, .card-info, .card-success, .card-danger {
      background: #ffffff !important;
      border-radius: 16px !important;
      border: 1px solid #f1f5f9 !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
      margin-bottom: 16px !important;
      overflow: hidden !important;
    }
    .content .row + .row {
      margin-top: 0 !important;
    }
    .content .row.mt-4, .content .row.mt-3, .content .row.mt-2 {
      margin-top: 0 !important;
    }
    .content + .content {
      padding-top: 0 !important;
    }
    .card-header {
      background-color: #ffffff !important;
      border-bottom: 1px solid #f1f5f9 !important;
      padding: 14px 20px !important;
    }
    .card-title {
      font-size: 1.02rem !important;
      font-weight: 700 !important;
      color: #0f172a !important;
      margin-bottom: 0 !important;
    }
    .card-body {
      padding: 18px 20px !important;
    }

    /* Tables */
    .table {
      width: 100% !important;
      margin-bottom: 0 !important;
      border-collapse: collapse !important;
      table-layout: auto !important;
    }
    .table th, .table thead th {
      font-size: 0.74rem !important;
      text-transform: uppercase !important;
      color: #64748b !important;
      font-weight: 700 !important;
      letter-spacing: 0.05em !important;
      padding: 12px 14px !important;
      border-top: none !important;
      border-bottom: 1px solid #eef2f6 !important;
      background: #ffffff !important;
      vertical-align: middle !important;
    }
    .table th.sorting, .table th.sorting_asc, .table th.sorting_desc {
      padding-right: 24px !important;
      position: relative !important;
    }
    .table th.no-sort, .table th.no-sort::before, .table th.no-sort::after {
      background-image: none !important;
      cursor: default !important;
      padding-right: 12px !important;
    }
    .table th.no-sort::before, .table th.no-sort::after {
      display: none !important;
      content: "" !important;
    }
    .table td, .table tbody td {
      padding: 10px 12px !important;
      vertical-align: middle !important;
      border-top: none !important;
      border-bottom: 1px solid #f8fafc !important;
      font-size: 0.84rem !important;
      color: #334155 !important;
    }
    .table td .btn {
      white-space: nowrap !important;
    }
    .table tr:hover td, .table tbody tr:hover td {
      background-color: #fafbfd !important;
    }
    .table-bordered, .table-bordered td, .table-bordered th {
      border: 1px solid #f1f5f9 !important;
    }

    .table-responsive {
      width: 100% !important;
      margin-bottom: 0 !important;
    }

    .table th.col-no,
    .table td.col-no,
    .table th.w-1,
    .table td.w-1 {
      width: 1% !important;
      min-width: 40px !important;
      max-width: 50px !important;
      text-align: center !important;
      white-space: nowrap !important;
      padding-left: 8px !important;
      padding-right: 8px !important;
    }

    .table th.text-center,
    .table td.text-center {
      text-align: center !important;
    }

    /* === GLOBAL MODAL CENTERING & STYLING (Full iOS Safari Support) === */
    .modal {
      z-index: 1060 !important;
      padding-right: 0 !important;
      -webkit-overflow-scrolling: touch !important;
      overflow-y: auto !important;
    }
    .modal-backdrop {
      z-index: 1055 !important;
      background-color: rgba(15, 23, 42, 0.6) !important;
      backdrop-filter: blur(3px);
      -webkit-backdrop-filter: blur(3px);
    }
    .modal-dialog {
      margin: 1.75rem auto !important;
      position: relative;
      width: auto;
      pointer-events: auto;
      -webkit-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
    }
    .modal-dialog-centered {
      display: flex !important;
      align-items: center !important;
      min-height: calc(100% - 3.5rem) !important;
      justify-content: center !important;
      margin: 1.75rem auto !important;
    }
    .modal-content {
      border: none !important;
      border-radius: 14px !important;
      box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.25) !important;
      width: 100% !important;
      -webkit-overflow-scrolling: touch !important;
    }

    /* iOS Tap Delegation Fix for Modals */
    [data-toggle="modal"],
    [data-dismiss="modal"],
    [data-bs-toggle="modal"],
    [data-bs-dismiss="modal"] {
      cursor: pointer !important;
      -webkit-tap-highlight-color: transparent !important;
    }

    /* Modern Action Button Squircles (Matching Screenshot: Eye, Key, Edit, Trash) */
    .btn-action,
    .btn-action-view,
    .btn-action-edit,
    .btn-action-delete,
    .btn-action-reset,
    .btn-action-pdf,
    .table td .btn-action,
    .table td .btn-action-view,
    .table td .btn-action-edit,
    .table td .btn-action-delete,
    .table td .btn-action-reset,
    .table td .btn-action-pdf {
      width: 34px !important;
      height: 34px !important;
      min-width: 34px !important;
      max-width: 34px !important;
      border-radius: 9px !important;
      padding: 0 !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      border: none !important;
      font-size: 0.86rem !important;
      line-height: 1 !important;
      color: #ffffff !important;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
      cursor: pointer !important;
      flex-shrink: 0 !important;
    }

    .btn-action:hover,
    .btn-action-view:hover,
    .btn-action-edit:hover,
    .btn-action-delete:hover,
    .btn-action-reset:hover,
    .btn-action-pdf:hover {
      transform: translateY(-2px) scale(1.05) !important;
      color: #ffffff !important;
    }

    .btn-action-view {
      background: #10b981 !important; /* Emerald Green */
      box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35) !important;
    }
    .btn-action-view:hover {
      background: #059669 !important;
    }

    .btn-action-reset {
      background: #1d72fe !important; /* Vibrant Blue */
      box-shadow: 0 2px 6px rgba(29, 114, 254, 0.35) !important;
    }
    .btn-action-reset:hover {
      background: #155ecc !important;
    }

    .btn-action-edit {
      background: #f59e0b !important; /* Vibrant Amber/Orange */
      box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35) !important;
    }
    .btn-action-edit:hover {
      background: #d97706 !important;
    }

    .btn-action-delete, .btn-action-pdf {
      background: #ef4444 !important; /* Vibrant Red */
      box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35) !important;
    }
    .btn-action-delete:hover, .btn-action-pdf:hover {
      background: #dc2626 !important;
    }

    .btn-action i,
    .btn-action-view i,
    .btn-action-edit i,
    .btn-action-delete i,
    .btn-action-reset i,
    .btn-action-pdf i {
      color: #ffffff !important;
      font-size: 0.85rem !important;
      margin: 0 !important;
    }

    /* Form Controls & Inputs */
    .form-control, select.form-control, input[type="text"].form-control, input[type="date"].form-control, input[type="search"].form-control, input[type="password"].form-control, input[type="email"].form-control {
      background: #f8fafc !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 10px !important;
      font-size: 0.84rem !important;
      color: #1e293b !important;
      font-weight: 500 !important;
      padding: 8px 14px !important;
      height: 40px !important;
      transition: all 0.2s ease !important;
    }
    .form-control:focus {
      background: #ffffff !important;
      border-color: #1d72fe !important;
      outline: none !important;
      box-shadow: 0 0 0 3px rgba(29, 114, 254, 0.12) !important;
    }
    label {
      font-size: 0.78rem !important;
      font-weight: 600 !important;
      color: #64748b !important;
      margin-bottom: 6px !important;
    }

    /* Buttons */
    .btn {
      border-radius: 10px !important;
      font-weight: 600 !important;
      font-size: 0.84rem !important;
      padding: 8px 18px !important;
      transition: all 0.2s ease !important;
    }
    .btn-sm {
      padding: 5px 12px !important;
      font-size: 0.78rem !important;
      border-radius: 8px !important;
    }
    .btn-xs {
      padding: 3px 8px !important;
      font-size: 0.72rem !important;
      border-radius: 6px !important;
    }
    .table td .btn {
      padding: 5px 10px !important;
      font-size: 0.76rem !important;
      border-radius: 7px !important;
      white-space: nowrap !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      line-height: 1.2 !important;
    }
    .table td .btn-xs {
      padding: 3px 8px !important;
      font-size: 0.72rem !important;
      border-radius: 6px !important;
    }
    .table td .btn-sm {
      padding: 5px 11px !important;
      font-size: 0.76rem !important;
      border-radius: 7px !important;
    }
    .btn-primary {
      background: #1d72fe !important;
      border-color: #1d72fe !important;
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(29, 114, 254, 0.25) !important;
    }
    .btn-primary:hover {
      background: #155ecc !important;
      border-color: #155ecc !important;
      color: #ffffff !important;
      box-shadow: 0 4px 12px rgba(29, 114, 254, 0.35) !important;
      transform: translateY(-1px);
    }
    .btn-success {
      background: #10b981 !important;
      border-color: #10b981 !important;
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25) !important;
    }
    .btn-success:hover {
      background: #059669 !important;
      border-color: #059669 !important;
      color: #ffffff !important;
      transform: translateY(-1px);
    }
    .btn-warning {
      background: #f59e0b !important;
      border-color: #f59e0b !important;
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25) !important;
    }
    .btn-warning:hover {
      background: #d97706 !important;
      border-color: #d97706 !important;
      color: #ffffff !important;
      transform: translateY(-1px);
    }
    .btn-danger {
      background: #ef4444 !important;
      border-color: #ef4444 !important;
      color: #ffffff !important;
    }
    .btn-danger:hover {
      background: #dc2626 !important;
      border-color: #dc2626 !important;
      color: #ffffff !important;
      transform: translateY(-1px);
    }
    .btn-info {
      background: #0ea5e9 !important;
      border-color: #0ea5e9 !important;
      color: #ffffff !important;
    }
    .btn-info:hover {
      background: #0284c7 !important;
      border-color: #0284c7 !important;
      color: #ffffff !important;
      transform: translateY(-1px);
    }
    .btn-secondary, .btn-light {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      color: #0f172a !important;
    }
    .btn-secondary:hover, .btn-light:hover {
      background: #f8fafc !important;
      border-color: #cbd5e1 !important;
      color: #0f172a !important;
    }

    /* === CONSISTENT TABLE ACTION BUTTONS === */
    .table th:last-child,
    .table td:last-child,
    .table th.action-column,
    .table td.action-column {
      white-space: nowrap !important;
    }

    .table td form,
    .table td .btn-group,
    .table td .action-btns,
    .table td .actions-wrapper {
      display: inline-flex !important;
      flex-direction: row !important;
      align-items: center !important;
      flex-wrap: nowrap !important;
      gap: 5px !important;
      margin: 0 !important;
      padding: 0 !important;
      white-space: nowrap !important;
      vertical-align: middle !important;
    }

    .table td .btn,
    .table td form .btn,
    .table td .btn-group .btn,
    .table td > a.btn,
    .table td > button.btn {
      width: auto !important;
      min-width: auto !important;
      max-width: none !important;
      height: auto !important;
      min-height: 28px !important;
      padding: 5px 12px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 5px !important;
      border-radius: 8px !important;
      font-size: 0.78rem !important;
      font-weight: 600 !important;
      line-height: 1.2 !important;
      margin: 0 !important;
      flex-shrink: 0 !important;
      white-space: nowrap !important;
      box-shadow: none !important;
    }

    .table td .btn-xs,
    .table td form .btn-xs {
      padding: 4px 8px !important;
      font-size: 0.74rem !important;
      min-height: 24px !important;
    }

    .table td .btn i,
    .table td form .btn i,
    .table td .btn-group .btn i,
    .table td > a.btn i,
    .table td > button.btn i {
      font-size: 0.80rem !important;
      line-height: 1 !important;
    }

    /* Square icon-only buttons */
    .table td .btn-icon,
    .table td .btn-square {
      width: 32px !important;
      height: 32px !important;
      min-width: 32px !important;
      max-width: 32px !important;
      padding: 0 !important;
    }

    .table td > .btn + form,
    .table td > form + .btn,
    .table td > .btn + .btn {
      margin-left: 5px !important;
    }

    /* === MODERN SOFT-PILL PAGINATION (Exact Match to Design Mockup) === */
    .pagination,
    .dataTables_wrapper .dataTables_paginate .pagination {
      display: flex !important;
      align-items: center !important;
      justify-content: flex-end !important;
      margin: 0 !important;
      padding: 0 !important;
      gap: 4px !important;
      list-style: none !important;
    }

    .pagination .page-item,
    .dataTables_wrapper .dataTables_paginate .page-item {
      margin: 0 !important;
    }

    .pagination .page-item .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      border: none !important;
      background: transparent !important;
      color: #475569 !important;
      font-size: 0.88rem !important;
      font-weight: 500 !important;
      padding: 0 8px !important;
      min-width: 34px !important;
      height: 34px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      border-radius: 8px !important;
      transition: all 0.15s ease !important;
      text-decoration: none !important;
      box-shadow: none !important;
    }

    .pagination .page-item:not(.active):not(.disabled) .page-link:hover,
    .dataTables_wrapper .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
      background: #f1f5f9 !important;
      color: #1d72fe !important;
      border-radius: 8px !important;
    }

    /* Active Page: Soft Blue Squircle with Vibrant Blue Text (Matching Screenshot) */
    .pagination .page-item.active .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
      background: #eff6ff !important;
      color: #1d72fe !important;
      font-weight: 700 !important;
      border-radius: 8px !important;
      border: none !important;
      box-shadow: none !important;
    }

    /* Previous & Next Arrow Buttons */
    .pagination .page-item.previous .page-link,
    .pagination .page-item.next .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.previous .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.next .page-link {
      font-weight: 700 !important;
      font-size: 1.15rem !important;
      padding: 0 8px !important;
      background: transparent !important;
      min-width: 30px !important;
      line-height: 1 !important;
    }

    .pagination .page-item:not(.disabled).previous .page-link,
    .pagination .page-item:not(.disabled).next .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item:not(.disabled).previous .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item:not(.disabled).next .page-link {
      color: #1d72fe !important;
    }

    .pagination .page-item.disabled .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
      color: #cbd5e1 !important;
      background: transparent !important;
      cursor: not-allowed !important;
    }

    /* DataTables Modern Styling */
    .dataTables_wrapper {
      padding: 8px 0 !important;
      font-size: 0.86rem;
      width: 100% !important;
      display: block !important;
    }
    .dataTables_wrapper::before,
    .dataTables_wrapper::after {
      content: "" !important;
      display: table !important;
      clear: both !important;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
      margin-bottom: 14px !important;
    }
    .dataTables_wrapper .dataTables_length {
      float: left !important;
      text-align: left !important;
    }
    .dataTables_wrapper .dataTables_filter {
      float: right !important;
      text-align: right !important;
    }
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
      display: inline-flex !important;
      align-items: center !important;
      margin-bottom: 0 !important;
      font-weight: 500 !important;
      color: #475569 !important;
      font-size: 0.86rem !important;
    }
    .dataTables_wrapper .dataTables_length select {
      appearance: none !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      background-color: #ffffff !important;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m3 6 5 5 5-5'/%3e%3c/svg%3e") !important;
      background-repeat: no-repeat !important;
      background-position: right 8px center !important;
      background-size: 10px 10px !important;
      border: 1px solid #cbd5e1 !important;
      border-radius: 8px !important;
      padding: 4px 22px 4px 10px !important;
      font-size: 0.84rem !important;
      font-weight: 600 !important;
      color: #1e293b !important;
      margin: 0 6px !important;
      height: 36px !important;
      min-width: 55px !important;
      cursor: pointer !important;
      box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
      outline: none !important;
    }
    .dataTables_wrapper .dataTables_length select:hover {
      border-color: #94a3b8 !important;
      background-color: #f8fafc !important;
    }
    .dataTables_wrapper .dataTables_length select:focus {
      border-color: #1d72fe !important;
      box-shadow: 0 0 0 3px rgba(29, 114, 254, 0.12) !important;
    }
    .dataTables_wrapper .dataTables_filter input {
      background: #ffffff !important;
      border: 1px solid #cbd5e1 !important;
      border-radius: 8px !important;
      padding: 6px 12px !important;
      font-size: 0.84rem !important;
      margin-left: 8px !important;
      height: 36px !important;
      width: 220px !important;
      max-width: 100% !important;
      box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
      transition: all 0.2s ease !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
      background: #ffffff !important;
      border-color: #1d72fe !important;
      outline: none !important;
      box-shadow: 0 0 0 3px rgba(29, 114, 254, 0.12) !important;
      width: 260px !important;
    }

    /* Mencegah tabrakan kontrol DataTables di kolom sempit (seperti panel piket col-lg-5, col-lg-4, col-md-6) */
    .col-lg-3 .dataTables_wrapper .dataTables_length,
    .col-lg-4 .dataTables_wrapper .dataTables_length,
    .col-lg-5 .dataTables_wrapper .dataTables_length,
    .col-lg-6 .dataTables_wrapper .dataTables_length,
    .col-md-4 .dataTables_wrapper .dataTables_length,
    .col-md-5 .dataTables_wrapper .dataTables_length,
    .col-md-6 .dataTables_wrapper .dataTables_length {
      float: none !important;
      width: 100% !important;
      margin-bottom: 8px !important;
      text-align: left !important;
    }
    .col-lg-3 .dataTables_wrapper .dataTables_filter,
    .col-lg-4 .dataTables_wrapper .dataTables_filter,
    .col-lg-5 .dataTables_wrapper .dataTables_filter,
    .col-lg-6 .dataTables_wrapper .dataTables_filter,
    .col-md-4 .dataTables_wrapper .dataTables_filter,
    .col-md-5 .dataTables_wrapper .dataTables_filter,
    .col-md-6 .dataTables_wrapper .dataTables_filter {
      float: none !important;
      width: 100% !important;
      text-align: left !important;
      margin-bottom: 12px !important;
    }
    .col-lg-3 .dataTables_wrapper .dataTables_filter label,
    .col-lg-4 .dataTables_wrapper .dataTables_filter label,
    .col-lg-5 .dataTables_wrapper .dataTables_filter label,
    .col-lg-6 .dataTables_wrapper .dataTables_filter label,
    .col-md-4 .dataTables_wrapper .dataTables_filter label,
    .col-md-5 .dataTables_wrapper .dataTables_filter label,
    .col-md-6 .dataTables_wrapper .dataTables_filter label {
      display: flex !important;
      flex-direction: column !important;
      align-items: stretch !important;
      width: 100% !important;
      gap: 4px !important;
    }
    .col-lg-3 .dataTables_wrapper .dataTables_filter input,
    .col-lg-4 .dataTables_wrapper .dataTables_filter input,
    .col-lg-5 .dataTables_wrapper .dataTables_filter input,
    .col-lg-6 .dataTables_wrapper .dataTables_filter input,
    .col-md-4 .dataTables_wrapper .dataTables_filter input,
    .col-md-5 .dataTables_wrapper .dataTables_filter input,
    .col-md-6 .dataTables_wrapper .dataTables_filter input {
      width: 100% !important;
      margin-left: 0 !important;
    }

    .dataTables_wrapper .dataTables_info {
      padding-top: 12px !important;
      font-size: 0.86rem !important;
      color: #64748b !important;
      font-weight: 500 !important;
    }
    .dataTables_wrapper .dataTables_info strong,
    .dataTables_wrapper .dataTables_info b {
      color: #0f172a !important;
      font-weight: 700 !important;
    }
    .dataTables_wrapper .dataTables_paginate {
      padding-top: 8px !important;
    }

    /* ==========================================================================
       === COMPREHENSIVE RESPONSIVE DESIGN SYSTEM (MOBILE, TABLET, DESKTOP) ===
       ========================================================================== */
    
    /* Global Container Overflow Protection */
    html, body {
      max-width: 100% !important;
      overflow-x: hidden !important;
    }
    
    .wrapper {
      max-width: 100% !important;
      overflow-x: hidden !important;
    }
    
    /* Table Responsive Wrapper & Touch Scroll */
    .table-responsive {
      -webkit-overflow-scrolling: touch !important;
      scrollbar-width: thin;
      margin-bottom: 0.5rem;
      width: 100% !important;
    }
    
    .table-responsive::-webkit-scrollbar {
      height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    
    /* --- TABLETS & SMALL LAPTOPS (<= 991.98px) --- */
    @media (max-width: 991.98px) {
      .main-header.navbar {
        padding: 8px 16px !important;
      }
      .navbar-search-pill {
        width: 220px !important;
      }
      .content-header {
        padding: 12px 14px 4px !important;
      }
      .content {
        padding: 0 14px 16px !important;
      }
      .dashboard-container {
        padding: 16px 14px !important;
      }
    }

    /* --- MOBILE PHONES (<= 767.98px) --- */
    @media (max-width: 767.98px) {
      /* Navbar on Mobile */
      .main-header.navbar {
        padding: 6px 12px !important;
        min-height: 56px !important;
      }
      .navbar-search-pill {
        display: none !important;
      }
      .navbar-admin-pill {
        padding: 5px 12px !important;
        font-size: 0.78rem !important;
      }
      .navbar-circle-btn {
        width: 34px !important;
        height: 34px !important;
        margin-right: 6px !important;
      }

      /* Content Header on Mobile */
      .content-header {
        padding: 10px 10px 4px !important;
      }
      .content-header h1 {
        font-size: 1.15rem !important;
      }
      .content-header p {
        font-size: 0.76rem !important;
      }
      .content-header .breadcrumb {
        margin-top: 8px !important;
        float: none !important;
        font-size: 0.76rem !important;
        flex-wrap: wrap !important;
      }
      .content {
        padding: 0 10px 14px !important;
      }

      /* Form Cards on Mobile */
      .card {
        border-radius: 12px !important;
        margin-bottom: 16px !important;
      }
      .card-header {
        padding: 12px 14px !important;
      }
      .card-title {
        font-size: 0.95rem !important;
      }
      .card-body {
        padding: 14px !important;
      }
      .card-footer {
        padding: 10px 14px !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
      }
      .card-footer .btn {
        flex: 1 1 auto !important;
        text-align: center !important;
        justify-content: center !important;
        padding: 8px 12px !important;
        font-size: 0.82rem !important;
      }
      .card-footer .ml-auto {
        margin-left: 0 !important;
      }

      /* Form Elements */
      .form-group {
        margin-bottom: 14px !important;
      }
      .form-group.col-6 {
        flex: 0 0 100% !important;
        max-width: 100% !important;
      }
      .form-control, .custom-select {
        font-size: 0.88rem !important;
        height: 42px !important;
      }
      .custom-file, .custom-file-label {
        height: 42px !important;
        line-height: 28px !important;
        font-size: 0.82rem !important;
      }
      .custom-file-label::after {
        height: 40px !important;
        line-height: 26px !important;
      }

      /* Nav Tabs Responsive Scroll */
      .nav-tabs {
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        white-space: nowrap !important;
        scrollbar-width: none !important;
      }
      .nav-tabs::-webkit-scrollbar {
        display: none !important;
      }
      .nav-tabs .nav-link {
        padding: 8px 14px !important;
        font-size: 0.82rem !important;
      }

      /* DataTables Stacking & Styling on Mobile */
      .dataTables_wrapper .dataTables_length {
        float: none !important;
        text-align: left !important;
        width: 100% !important;
        margin-bottom: 10px !important;
      }
      .dataTables_wrapper .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 6px !important;
        width: auto !important;
        font-size: 0.84rem !important;
        color: #64748b !important;
        margin-bottom: 0 !important;
      }
      .dataTables_wrapper .dataTables_length select {
        margin: 0 4px !important;
        height: 34px !important;
        border-radius: 8px !important;
      }
      
      .dataTables_wrapper .dataTables_filter {
        float: none !important;
        text-align: left !important;
        width: 100% !important;
        margin-bottom: 12px !important;
      }
      .dataTables_wrapper .dataTables_filter label {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
        gap: 4px !important;
        font-size: 0.82rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        margin-bottom: 0 !important;
      }
      .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
        margin-left: 0 !important;
        height: 38px !important;
        border-radius: 8px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
      }

      /* Universal Polished Pagination on Mobile (DataTables & Laravel Blade links) */
      .pagination-info,
      .dataTables_wrapper .dataTables_info {
        float: none !important;
        text-align: center !important;
        width: 100% !important;
        padding-top: 14px !important;
        margin-bottom: 8px !important;
        font-size: 0.84rem !important;
        color: #64748b !important;
        display: block !important;
      }
      .pagination-info strong,
      .pagination-info b,
      .dataTables_wrapper .dataTables_info strong,
      .dataTables_wrapper .dataTables_info b {
        color: #0f172a !important;
        font-weight: 700 !important;
        margin: 0 3px !important;
      }
      .dataTables_wrapper .dataTables_paginate,
      .pagination-container > div:last-child {
        float: none !important;
        text-align: center !important;
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
        padding: 4px 0 12px 0 !important;
      }
      .pagination,
      .dataTables_wrapper .dataTables_paginate .pagination {
        justify-content: center !important;
        flex-wrap: wrap !important;
        gap: 3px !important;
        margin: 0 auto !important;
      }
      .pagination .page-item .page-link,
      .dataTables_wrapper .dataTables_paginate .page-item .page-link {
        min-width: 32px !important;
        height: 34px !important;
        font-size: 0.84rem !important;
        border-radius: 8px !important;
        padding: 0 6px !important;
      }
      .pagination .page-item.active .page-link,
      .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
        background: #eff6ff !important;
        color: #1d72fe !important;
        font-weight: 700 !important;
        border-radius: 8px !important;
      }
      .pagination .page-item.previous .page-link,
      .pagination .page-item.next .page-link,
      .dataTables_wrapper .dataTables_paginate .page-item.previous .page-link,
      .dataTables_wrapper .dataTables_paginate .page-item.next .page-link {
        font-size: 1.15rem !important;
        min-width: 30px !important;
        height: 34px !important;
      }
      
      /* Dashboard Responsive on Mobile */
      .dashboard-container {
        padding: 12px 10px !important;
      }
      .dashboard-header-title {
        font-size: 1.25rem !important;
      }
      .stat-card {
        padding: 14px !important;
        border-radius: 12px !important;
      }
      
      /* Action Buttons inside Table Cells */
      .btn-action {
        width: 30px !important;
        height: 30px !important;
        font-size: 0.75rem !important;
      }
      .btn-group-action {
        gap: 4px !important;
      }

      /* Card Header and Tools Responsiveness on Mobile */
      .card-header {
        padding: 14px 16px !important;
      }
      .card-header.d-flex,
      .card-header.justify-content-between {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 12px !important;
      }
      .card-header .card-title {
        font-size: 0.96rem !important;
        line-height: 1.35 !important;
        margin-bottom: 0 !important;
      }
      .card-header .d-flex.align-items-center.ml-auto,
      .card-header .card-tools,
      .card-header > .btn-group,
      .card-header .ml-auto,
      .w-100-mobile {
        margin-left: 0 !important;
        width: 100% !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
        justify-content: stretch !important;
      }
      .card-header form.form-inline {
        width: 100% !important;
        display: flex !important;
        flex-wrap: nowrap !important;
        gap: 6px !important;
        margin-right: 0 !important;
        margin-bottom: 4px !important;
      }
      .card-header form.form-inline .form-group {
        flex: 1 1 auto !important;
        margin-right: 0 !important;
        margin-bottom: 0 !important;
      }
      .card-header form.form-inline input[type="date"],
      .card-header form.form-inline .form-control {
        width: 100% !important;
        height: 36px !important;
        border-radius: 8px !important;
      }
      .card-header form.form-inline button,
      .card-header form.form-inline .btn {
        height: 36px !important;
        border-radius: 8px !important;
        white-space: nowrap !important;
        padding: 0 14px !important;
      }
      .card-header .d-flex.align-items-center.ml-auto > .btn,
      .card-header .d-flex.align-items-center.ml-auto > a.btn,
      .card-header .card-tools > .btn,
      .w-100-mobile .btn {
        flex: 1 1 calc(50% - 4px) !important;
        min-width: 120px !important;
        height: 36px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        font-size: 0.80rem !important;
        font-weight: 600 !important;
        margin: 0 !important;
      }

      /* KPI / Metric Summary Cards on Mobile */
      .card.d-flex.flex-row.align-items-center.justify-content-between,
      .card.p-3.d-flex.flex-row {
        padding: 10px 12px !important;
      }
      .card.d-flex.flex-row .rounded-circle {
        width: 34px !important;
        height: 34px !important;
        font-size: 0.9rem !important;
        flex-shrink: 0 !important;
      }
      .card.d-flex.flex-row .font-weight-bold.text-dark {
        font-size: 1.25rem !important;
      }
      .card.d-flex.flex-row .text-muted.small {
        font-size: 0.68rem !important;
      }

      /* Modals */
      .modal-dialog {
        margin: 10px !important;
        max-width: calc(100% - 20px) !important;
      }
    }

    /* --- EXTRA SMALL SCREENS (<= 480px) --- */
    @media (max-width: 480px) {
      .content-header .row > div:first-child {
        display: flex !important;
        align-items: flex-start !important;
      }
      .content-header .rounded-circle {
        width: 38px !important;
        height: 38px !important;
        font-size: 1rem !important;
        margin-right: 10px !important;
        flex-shrink: 0 !important;
      }
      .btn {
        font-size: 0.80rem !important;
      }
    }

    /* --- UNIVERSAL TABLE & CONTAINER RESPONSIVENESS --- */
    .table-responsive,
    .dataTables_wrapper {
      width: 100% !important;
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch !important;
    }
    .dataTables_wrapper > .row {
      margin-left: 0 !important;
      margin-right: 0 !important;
      width: 100% !important;
    }
    @media (max-width: 991.98px) {
      .card-body > table:not(.table-responsive),
      .card-body > .table {
        display: block !important;
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  {{-- Navbar --}}
  @include('partials.navbar', ['setting' => $setting])

  {{-- Sidebar --}}
  @include('partials.sidebar', ['setting' => $setting])

  {{-- Content Wrapper --}}
  <div class="content-wrapper">
    @yield('content')
  </div>
  
  {{-- Main Footer --}}
  @include('partials.footer', ['setting' => $setting])

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
<!-- DataTables & Plugins -->
<script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      html: '{!! session('success') !!}'
    });
  @endif

  @if (session('failed'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      html: '{!! session('failed') !!}'
    });
  @endif

  // Inisialisasi Smooth Treeview Accordion & Global DataTables Pagination
  $(document).ready(function() {
    $('[data-widget="treeview"]').Treeview({
      accordion: true,
      animationSpeed: 280
    });

    // =========================================================================
    // PERSISTENT SIDEBAR POSITION (Seamless SPA Feel - Tanpa Lompat/Flicker)
    // =========================================================================
    var $sidebar = $('.main-sidebar .sidebar');
    var $activeLink = $('.nav-sidebar .nav-link.active').first();

    if ($sidebar.length) {
      var savedScroll = sessionStorage.getItem('siawi_sidebar_scroll');

      if (savedScroll !== null) {
        // Kembalikan posisi scroll tepat seperti saat user mengklik menu
        $sidebar.scrollTop(parseInt(savedScroll, 10));
      } else if ($activeLink.length) {
        // Fallback kunjungan pertama: posisikan menu aktif langsung di viewport
        var sidebarTop = $sidebar.offset().top;
        var activeTop = $activeLink.offset().top;
        var sidebarHeight = $sidebar.height();
        var targetScroll = (activeTop - sidebarTop) - (sidebarHeight / 3);
        $sidebar.scrollTop(Math.max(0, targetScroll));
      }

      // Simpan posisi scroll secara real-time
      $sidebar.on('scroll', function() {
        sessionStorage.setItem('siawi_sidebar_scroll', $sidebar.scrollTop());
      });

      $('.nav-sidebar a').on('click', function() {
        sessionStorage.setItem('siawi_sidebar_scroll', $sidebar.scrollTop());
      });
    }

    // =========================================================================
    // iOS SAFARI / WEBKIT MODAL COMPATIBILITY FIX
    // =========================================================================
    $(document).on('show.bs.modal', '.modal', function () {
      // Pastikan modal menempel langsung pada <body> agar tidak terperangkap Stacking Context di iOS Safari
      if (!$(this).parent().is('body')) {
        $(this).appendTo('body');
      }
    });

    // Inisialisasi otomatis DataTables dengan Pagination, Search & Dropdown Jumlah Data di semua halaman
    if ($.fn.DataTable) {
      $.fn.dataTable.ext.errMode = 'none';
      var tableSelectors = '#example1, #example2, #example3, #spTable, #siswaTable, #siswaPiketTable, #terlambatHariIniTable, .datatable-auto, .datatable, .card-body > table:not(.no-datatable):not(.table-dashboard):not(.table-sm), .card-body > .table-responsive > table:not(.no-datatable):not(.table-dashboard):not(.table-sm)';
      $(tableSelectors).each(function() {
        if (!$.fn.DataTable.isDataTable(this)) {
          // Pastikan table memiliki thead dan tbody sebelum init
          if ($(this).find('thead').length > 0 && $(this).find('tbody').length > 0) {
            $(this).DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": false,
              "info": true,
              "autoWidth": false,
              "responsive": false,
              "pageLength": 10,
              "lengthMenu": [10, 20, 25, 50, 100],
              "language": {
                "search": "Cari data:",
                "searchPlaceholder": "Ketik kata kunci...",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan <strong>_START_ - _END_</strong> dari <strong>_TOTAL_</strong> data",
                "infoEmpty": "Menampilkan <strong>0</strong> data",
                "infoFiltered": "(disaring dari <strong>_MAX_</strong> total data)",
                "zeroRecords": "Tidak ada data yang cocok",
                "paginate": {
                  "first": "«",
                  "last": "»",
                  "previous": "‹",
                  "next": "›"
                }
              }
            });
          }
        }
      });
    }

    // Inisialisasi otomatis Summernote WYSIWYG Editor
    if ($.fn.summernote) {
      $('.summernote, #isi_berita').summernote({
        placeholder: 'Tulis isi berita atau artikel di sini...',
        tabsize: 2,
        height: 320,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['insert', ['picture', 'video', 'link']],
          ['para', ['ol', 'ul', 'paragraph']],
          ['misc', ['codeview', 'clear']]
        ]
      });
    }
  });
</script>

@stack('scripts')
</body>
</html>
