<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset("storage/gambar/$setting->logo") }}" type="image/x-icon">
    <title>{{ config('app.name', 'Sistem Informasi Akademik Wisata Indonesia ') }}</title>
	 <style>
		.custom-search {
		  text-align: right;
		}

		.custom-search label {
		  width: 100%;
		}

		.custom-search input {
		  width: auto;
		  display: inline-block;
		}
	  </style>
    <script>
      // Fungsi untuk membuat judul bergerak
      function animateTitle() {
          var title = "{{ config('app.name') }} | Sistem Informasi Akademik SMK Wisata Indonesia | ";
          var speed = 200; // kecepatan pergerakan (ms)
          var charIndex = 0;
          setInterval(function() {
              document.title = title.substring(charIndex) + title.substring(0, charIndex);
              charIndex = (charIndex + 1) % title.length;
          }, speed);
      }

      // Panggil fungsi untuk memulai animasi judul saat dokumen selesai dimuat
      window.onload = animateTitle;
  </script>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
  <style>
    .dataTables_wrapper .dataTables_paginate {
      float: right;
    }

    .dataTables_wrapper .dataTables_paginate .pagination {
      margin: 0;
    }

    /* Global Modern Table Style */
    table.table,
    .table {
      border-collapse: separate !important;
      border-spacing: 0 !important;
      width: 100% !important;
      margin-top: 5px !important;
      border: none !important;
    }

    table.table thead th,
    .table thead th,
    .table thead tr th {
      background-color: #f8fafc !important;
      color: #4a5568 !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      font-size: 0.75rem !important;
      letter-spacing: 0.05em !important;
      border-bottom: 2px solid #edf2f7 !important;
      border-top: none !important;
      border-left: none !important;
      border-right: none !important;
      padding: 12px 16px !important;
    }

    table.table tbody td,
    .table tbody td {
      padding: 12px 16px !important;
      vertical-align: middle !important;
      border-bottom: 1px solid #edf2f7 !important;
      border-top: none !important;
      border-left: none !important;
      border-right: none !important;
      color: #4a5568 !important;
      font-size: 0.88rem !important;
      background-color: transparent !important;
    }

    /* Clear default thick borders from table-bordered */
    .table-bordered {
      border: none !important;
    }
    .table-bordered td, 
    .table-bordered th {
      border: none !important;
      border-bottom: 1px solid #edf2f7 !important;
    }
    .table-bordered thead th {
      border-bottom: 2px solid #edf2f7 !important;
    }

    .table tbody tr:last-child td {
      border-bottom: none !important;
    }

    .table tbody tr:hover td {
      background-color: #f7fafc !important;
    }

    /* Global Pagination Premium Style */
    .pagination .page-item .page-link {
      border: none !important;
      color: #718096 !important;
      font-weight: 600 !important;
      padding: 8px 16px !important;
      margin: 0 3px !important;
      border-radius: 6px !important;
      font-size: 0.85rem !important;
      background-color: transparent !important;
      transition: all 0.2s ease;
      box-shadow: none !important;
    }

    .pagination .page-item.active .page-link {
      background-color: #ebf8ff !important;
      color: #3182ce !important;
    }

    .pagination .page-item .page-link:hover:not(.active) {
      background-color: #f7fafc !important;
      color: #4a5568 !important;
    }

    .pagination .page-item.disabled .page-link {
      color: #cbd5e0 !important;
      background-color: transparent !important;
    }

    /* Table responsive wrapper */
    .table-responsive {
      border: none !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    /* Global Soft Badges */
    .badge-soft-danger {
      background-color: #fed7d7 !important;
      color: #9b2c2c !important;
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
    }
    .badge-soft-warning {
      background-color: #feebc8 !important;
      color: #c05621 !important;
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
    }
    .badge-soft-success {
      background-color: #c6f6d5 !important;
      color: #22543d !important;
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
    }
    .badge-soft-info {
      background-color: #e2e8f0 !important;
      color: #4a5568 !important;
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
    }
    .badge-soft-purple {
      background-color: #e9d8fd !important;
      color: #553c9a !important;
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
    }
  </style>
</head>
  <body class="hold-transition sidebar-mini">
  <div class="wrapper">

    {{-- navbar --}}
    @include('partials.navbar',['setting' => $setting])

    {{-- sidebar --}}
    @include('partials.sidebar', ['setting' => $setting])

    <div class="content-wrapper">
      @yield('content')

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
          <h5>Title</h5>
          <p>Sidebar content</p>
        </div>
      </aside>
      <!-- /.control-sidebar -->
    </div>
    
    <!-- Main Footer -->
    @include('partials.footer',['setting' => $setting])


  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('lte/plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('lte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('lte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- Summernote -->
  <script src="{{ asset('lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('lte/dist/js/pages/dashboard.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- Include SweetAlert2 JS -->
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
	</script>
    <script>
    $(function () {
	  $('#example2').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"dom": '<"top"f>rt<"bottom"lp><"clear">',
		"initComplete": function () {
		  $('#example2_filter').addClass('custom-search');
		}
	  });
	});
  </script>
  @stack('scripts')
  </body>
</html>

