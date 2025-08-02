@extends('backend.layouts.app')

@push('css')
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
@endpush

@section('title', 'Pengembalian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Pengembalian</h4>
                    <p class="mb-0">Lakukan pengembalian barang di sini</p>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 offset-lg-4">
                            <form action="" method="get">
                                <div class="input-group input-group-merge input-group-lg">
                                    <input type="search" class="form-control" placeholder="Kode Unik" name="code"
                                        value="{{ request('code') }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {!! session('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if ($renting)
                            <div class="col-md-4">
                                <div class="card border shadow-none mb-6">
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
                                                <div class="ms-auto fw-medium">{{ $renting->rent_date->format('d-m-Y') }}
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <div class="fw-medium">Tanggal Kembali</div>
                                                <div class="ms-auto fw-medium">{{ $renting->return_date->format('d-m-Y') }}
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <div class="fw-medium">Dikembalikan</div>
                                                <div class="ms-auto fw-medium">
                                                    {{ $renting->actual_return_date ? $renting->actual_return_date->format('d-m-Y') : '-' }}
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <form action="{{ route('panel.returns.update', $renting->code) }}"
                                                method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label class="form-label">Denda</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" name="pinalty" class="form-control"
                                                            value="{{ $renting->pinalty }}">
                                                    </div>
                                                </div>
                                                <div class="mb-6">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="0"
                                                            {{ $renting->actual_return_date == null ? 'selected' : '' }}>
                                                            Belum Dikembalikan</option>
                                                        <option value="1"
                                                            {{ $renting->actual_return_date != null ? 'selected' : '' }}>
                                                            Dikembalikan</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100"
                                                    onclick="return confirm('Apakah anda yakin?')">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card border shadow-none mb-6">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Daftar Buku</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table w-100 text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Cover</th>
                                                    <th>Judul Buku</th>
                                                    <th>Penulis</th>
                                                    <th>Hilang</th>
                                                    <th class="text-center">Aksi Hilang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($renting->rentItems as $item)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ asset('storage/' . $item->book->cover) }}"
                                                                alt="" width="50px" class="rounded">
                                                        </td>
                                                        <td class="text-wrap">{{ $item->book->title }}</td>
                                                        <td>{{ $item->book->author }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-label-{{ $item->is_lost ? 'danger' : 'success' }}">
                                                                {{ $item->is_lost ? 'Hilang' : 'Tidak' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('panel.returns.update-lost', $item->id) }}"
                                                                method="post" id="formUpdateLost{{ $item->id }}"
                                                                style="width: 130px">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="is_lost" class="form-select"
                                                                    onchange="document.getElementById('formUpdateLost{{ $item->id }}').submit()">
                                                                    <option value="0"
                                                                        {{ !$item->is_lost ? 'selected' : '' }}>Tidak
                                                                    </option>
                                                                    <option value="1"
                                                                        {{ $item->is_lost ? 'selected' : '' }}>Hilang
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-4 offset-lg-4">
                                <div><dotlottie-player
                                        src="https://lottie.host/5c9c6639-fdec-4c7c-b7a9-ea3f2a2f6fb7/zZTCvdeIgu.lottie"
                                        background="transparent" speed="1" style="width: 100%;" loop
                                        autoplay></dotlottie-player></div>
                                <p class="text-center">Masukkan kode unik peminjaman untuk melihatnya</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
