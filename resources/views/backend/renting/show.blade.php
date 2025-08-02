@extends('backend.layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Detail Peminjaman</h4>
                    <p class="mb-0">Lihat detail data peminjaman</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('panel.rentings.index') }}" class="btn btn-label-primary">Kembali</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Detail</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">Kode Unik</div>
                                    <div class="ms-auto fw-medium">#{{ $renting->code }}</div>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">Nama Peminjam</div>
                                    <div class="ms-auto fw-medium">{{ $renting->user->name }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">WhatsApp</div>
                                    <div class="ms-auto fw-medium">{{ $renting->user->phone }}</div>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">Tanggal Pinjam</div>
                                    <div class="ms-auto fw-medium">{{ $renting->rent_date->format('d-m-Y') }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">Tanggal Kembali</div>
                                    <div class="ms-auto fw-medium">{{ $renting->return_date->format('d-m-Y') }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">Dikembalikan</div>
                                    <div class="ms-auto fw-medium">{{ $renting->actual_return_date ? $renting->actual_return_date->format('d-m-Y') : '-' }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="fw-medium">Total Denda</div>
                                    <div class="ms-auto fw-medium">Rp {{ number_format($renting->pinalty, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Daftar Buku</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table w-100 text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Cover</th>
                                        <th>Judul Buku</th>
                                        <th>Penulis</th>
                                        <th class="text-center">Hilang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($renting->rentItems as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $item->book->cover) }}" alt="" width="50px" class="rounded">
                                            </td>
                                            <td>{{ $item->book->title }}</td>
                                            <td>{{ $item->book->author }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-label-{{ $item->is_lost ? 'danger' : 'success' }}">
                                                    {{ $item->is_lost ? 'Hilang' : 'Tidak' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection