@extends('backend.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <!-- Card Border Shadow -->
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                                    <span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-success-light"
                                        data-fa-transform="down-4 rotate--10 left-4"></span>
                                    <span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-success"
                                        data-fa-transform="up-4 right-3 grow-2"></span><span
                                        class="fa-stack-1x fa-solid fa-star text-success "
                                        data-fa-transform="shrink-2 up-8 right-6"></span>
                                </span>

                            </div>
                            <h4 class="mb-0">{{ $totalBarangs }}</h4>
                        </div>
                        <p class="mb-1">Total Barang</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                                    <span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-primary-light"
                                        data-fa-transform="down-4 rotate--10 left-4"></span>
                                    <span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-primary"
                                        data-fa-transform="up-4 right-3 grow-2"></span><span
                                        class="fa-stack-1x fa-solid fa-user text-primary "
                                        data-fa-transform="shrink-2 up-8 right-6"></span>
                                </span>
                            </div>
                            <h4 class="mb-0">{{ $totalRenter }}</h4>
                        </div>
                        <p class="mb-1">Total User</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="fa-stack" style="min-height: 46px;min-width: 46px;">
                                    <span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-warning-light"
                                        data-fa-transform="down-4 rotate--10 left-4"></span>
                                    <span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-warning"
                                        data-fa-transform="up-4 right-3 grow-2"></span><span
                                        class="fa-stack-1x fa-solid fa-share text-warning "
                                        data-fa-transform="shrink-2 up-8 right-6"></span>
                                </span>
                            </div>
                            <h4 class="mb-0">{{ $barangRenteds }}</h4>
                        </div>
                        <p class="mb-1">Barang Sedang Dipinjam</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span
                                        class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-danger-light"
                                        data-fa-transform="down-4 rotate--10 left-4"></span><span
                                        class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-danger"
                                        data-fa-transform="up-4 right-3 grow-2"></span><span
                                        class="fa-stack-1x fa-solid fa-xmark text-danger "
                                        data-fa-transform="shrink-2 up-8 right-6"></span></span>

                            </div>
                            <h4 class="mb-0">{{ $lostBarangs }}</h4>
                        </div>
                        <p class="mb-1">Barang Hilang</p>
                    </div>
                </div>
            </div>
            <!--/ Card Border Shadow -->

            <!-- On route vehicles Table -->
            <div class="col-12 order-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Peminjaman Terbaru</h5>
                        </div>
                        <a href="{{ route('panel.rentings.index') }}" class="btn btn-primary">Lainnya<i
                                class="ti ti-arrow-right ms-2 me-n1"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm fs-9 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Kode Unik</th>
                                    <th>Nama Peminjam</th>
                                    <th>Jumlah Barang</th>
                                    <th>Pinjam</th>
                                    <th>Kembali</th>
                                    <th>Dikembalikan</th>
                                    <th>Hilang</th>
                                    <th>Denda</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->rentItems->count() }} barang</td>
                                        <td>{{ $item->rent_date->format('d-m-Y') }}</td>
                                        <td>{{ $item->return_date->format('d-m-Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-label-{{ $item->actual_return_date ? ($item->actual_return_date > $item->return_date ? 'warning' : 'success') : 'danger' }}">
                                                {{ $item->actual_return_date ? $item->actual_return_date->format('d-m-Y') : 'Belum Dikembalikan' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->lost_barans }} barang</td>
                                        <td>Rp {{ number_format($item->pinalty, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('panel.rentings.show', $item->id) }}"
                                                    class="btn btn-sm btn-icon text-success shadow-sm"><i
                                                        class="ti ti-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="9">Tidak ada data yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <!--/ On route vehicles Table -->
        </div>
    </div>

@endsection
