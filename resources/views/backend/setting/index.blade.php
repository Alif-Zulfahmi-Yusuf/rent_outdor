@extends('backend.layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Pengaturan</h4>
                    <p class="mb-0">Sesuaikan konfigurasi peminjaman</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <button type="submit" class="btn btn-primary" form="formUpdate">Simpan</button>
                </div>
            </div>

            @if (session('success'))
                <!-- Container untuk toast -->
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                    <!-- Toast -->
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <strong class="me-auto">
                                <i class="fas fa-check-circle text-success"></i>
                            </strong>
                            <small class="text-muted">{{ now()->diffForHumans(session('success_time')) }}</small>
                            <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('panel.settings.store') }}" method="post" id="formUpdate">
                @csrf
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Denda Telat Perhari</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">Rp.</span>
                                    <input type="number"
                                        class="form-control @error('late_fee_per_day') is-invalid @enderror"
                                        name="late_fee_per_day" value="{{ $settings['late_fee_per_day'] }}">
                                </div>
                                @error('late_fee_per_day')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Denda Hilang</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">Rp.</span>
                                    <input type="number" class="form-control @error('lost_fee') is-invalid @enderror"
                                        name="lost_fee" value="{{ $settings['lost_fee'] }}">
                                </div>
                                @error('lost_fee')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Maksimal Meminjam Barang</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <input type="number"
                                        class="form-control @error('max_book_per_rent') is-invalid @enderror"
                                        name="max_book_per_rent" value="{{ $settings['max_book_per_rent'] }}">
                                    <span class="input-group-text">Barang</span>
                                </div>
                                @error('max_book_per_rent')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Maksimal Barang Dipinjam</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <input type="number" class="form-control @error('max_rent_day') is-invalid @enderror"
                                        name="max_rent_day" value="{{ $settings['max_rent_day'] }}">
                                    <span class="input-group-text">Hari</span>
                                </div>
                                @error('max_rent_day')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
