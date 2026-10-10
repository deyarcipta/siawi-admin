@extends('errors.layout')

@section('title', '500 Terjadi Kesalahan Server')

@section('icon')
  <div class="error-icon-wrapper" style="background-color: #fef2f2; color: #e11d48; border: 1px solid #fee2e2;">
    <i class="fas fa-server"></i>
  </div>
@endsection

@section('badge')
  <div class="error-code-badge" style="background-color: #ffe4e6; color: #9f1239;">
    Status 500 &bull; Kesalahan Server
  </div>
@endsection

@section('heading', 'Terjadi Kesalahan Server')

@section('message')
  <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;">
    {{ !empty($exception->getMessage()) && !app()->isProduction() ? $exception->getMessage() : 'Terjadi kendala internal pada server saat memproses permintaan Anda.' }}
  </div>
  <div style="font-size: 0.82rem; color: #64748b; font-weight: 400;">
    Tim teknis telah mencatat kejadian ini. Silakan coba kembali beberapa saat lagi atau hubungi administrator sistem.
  </div>
@endsection

@section('actions')
  <button type="button" onclick="window.location.reload();" class="error-btn error-btn-primary">
    <i class="fas fa-redo"></i> Coba Muat Ulang
  </button>
  <a href="{{ url('/admin/dashboard') }}" class="error-btn error-btn-secondary">
    <i class="fas fa-th-large"></i> Ke Dashboard Utama
  </a>
@endsection
