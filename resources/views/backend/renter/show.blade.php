@extends('backend.layouts.app')

@section('title', 'Detail Peminjam')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Detail Peminjam</h4>
                    <p class="mb-0">Lihat detail data peminjam</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('panel.renters.index') }}" class="btn btn-label-primary">Kembali</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-6">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="https://ui-avatars.com/api/?name={{ $renter->name }}" class="rounded my-4"
                                    width="100px" />
                                <h5 class="mb-1">{{ $renter->name }}</h5>
                                <p>{{ $renter->email }}</p>
                            </div>
                            <div class="d-flex justify-content-around flex-wrap mb-6 gap-0 gap-md-3 gap-lg-4">
                                <div class="d-flex align-items-center gap-4 me-5">
                                    <div class="avatar">
                                        <div class="avatar-initial rounded bg-label-primary">
                                            <i class="ti ti-books ti-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $renter->totalBooks() }}</h5>
                                        <span>Buku</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="avatar">
                                        <div class="avatar-initial rounded bg-label-primary">
                                            <i class="ti ti-currency-dollar ti-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Rp {{ number_format($renter->total_pinalty, 0, ',', '.') }}</h5>
                                        <span>Denda</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-container">
                                <h5 class="pb-4 border-bottom text-capitalize mt-6 mb-4">Detail</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="h6 me-1">WhatsApp:</span>
                                        <span>{{ $renter->phone ?? '-' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="h6 me-1">Alamat:</span>
                                        <span>{{ $renter->address ?? '-' }}</span>
                                    </li>
                                    <li class="">
                                        <span class="h6 me-1">Status:</span>
                                        <span
                                            class="badge bg-label-{{ $renter->is_active ? 'success' : 'danger' }}">{{ $renter->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Log Peminjaman</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table w-100 text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Jumlah Buku</th>
                                        <th>Pinjam</th>
                                        <th>Kembali</th>
                                        <th>Dikembalikan</th>
                                        <th>Denda</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rents = $renter->rents()->latest()->paginate(10);
                                    @endphp
                                    @forelse ($rents as $rent)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($rents->currentPage() - 1) * $rents->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $rent->rentItems()->count() }} Buku</td>
                                            <td>{{ $rent->rent_date->format('d-m-Y') }}</td>
                                            <td>{{ $rent->return_date->format('d-m-Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-label-{{ $rent->actual_return_date ? ($rent->actual_return_date > $rent->return_date ? 'warning' : 'success') : 'danger' }}">
                                                    {{ $rent->actual_return_date ? $rent->actual_return_date->format('d-m-Y') : 'Belum Dikembalikan' }}
                                                </span>
                                            </td>
                                            <td>Rp {{ number_format($rent->pinalty, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#modalDetail{{ $rent->id }}"
                                                    class="btn btn-sm btn-icon shadow-sm text-primary">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalDetail{{ $rent->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Detail
                                                            Peminjaman</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-column gap-2">
                                                            <div class="d-flex gap-2">
                                                                <div class="fw-medium">Kode Unik</div>
                                                                <div class="ms-auto fw-medium">#{{ $rent->code }}</div>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <div class="fw-medium">Nama Peminjam</div>
                                                                <div class="ms-auto fw-medium">{{ $rent->user->name }}
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <div class="fw-medium">Tanggal Pinjam</div>
                                                                <div class="ms-auto fw-medium">
                                                                    {{ $rent->rent_date->format('d-m-Y') }}</div>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <div class="fw-medium">Tanggal Kembali</div>
                                                                <div class="ms-auto fw-medium">
                                                                    {{ $rent->return_date->format('d-m-Y') }}</div>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <div class="fw-medium">Dikembalikan</div>
                                                                <div class="ms-auto fw-medium">
                                                                    {{ $rent->actual_return_date ? $rent->actual_return_date->format('d-m-Y') : '-' }}
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column gap-2">
                                                                <div class="d-flex gap-2">
                                                                    <div class="fw-medium">Total Denda</div>
                                                                    <div class="ms-auto fw-medium">Rp
                                                                        {{ number_format($rent->pinalty, 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex flex-column gap-2">
                                                            <div class="fw-semibold mb-2">Daftar Buku</div>
                                                            @foreach ($rent->rentItems as $index => $rentItem)
                                                                {!! $index > 0 ? '<hr class="my-1">' : '' !!}
                                                                <div class="d-flex align-items-center">
                                                                    <img src="{{ asset('storage/' . $rentItem->book->cover) }}"
                                                                        class="rounded" width="40">
                                                                    <div class="ms-3 text-truncate">
                                                                        <div class="fw-medium mb-1 text-truncate">
                                                                            {{ $rentItem->book->title }}</div>
                                                                        <span
                                                                            class="badge fs-tiny bg-label-{{ $rent->actual_return_date
                                                                                ? ($rentItem->is_lost
                                                                                    ? 'danger'
                                                                                    : ($rent->actual_return_date > $rent->return_date
                                                                                        ? 'warning'
                                                                                        : 'success'))
                                                                                : 'danger' }}">
                                                                            {{ $rent->actual_return_date
                                                                                ? ($rentItem->is_lost
                                                                                    ? 'Hilang'
                                                                                    : ($rent->actual_return_date > $rent->return_date
                                                                                        ? 'Telat'
                                                                                        : 'Dikembalikan'))
                                                                                : 'Belum Dikembalikan' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <hr>
                                                        <p class="text-warning mb-0">
                                                            Perlu diingat bahwa kamu harus mengembalikan buku tepat waktu
                                                            untuk menghindari denda.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Kamu belum meminjam buku apapun</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $rents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
