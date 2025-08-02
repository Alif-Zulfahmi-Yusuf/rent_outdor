@extends('backend.layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Detail Barang</h4>
                    <p class="mb-0">Lihat spesifikasi dan detail Barang</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('panel.barangs.index') }}" class="btn btn-label-primary">Kembali</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-6">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $barang->image) }}" alt=""
                                    class="rounded-start h-100 w-100">
                            </div>
                            <div class="card-body d-flex col-md-8">
                                <table class="w-100 align-middle">
                                    <tr>
                                        <th class="pb-2">Kode Barang</th>
                                        <td class="pb-2">: #{{ $barang->code }}</td>
                                    </tr>
                                    <tr>
                                        <th class="pb-2">Nama Barang</th>
                                        <td class="pb-2">: {{ $barang->title }}</td>
                                    </tr>
                                    <tr>
                                        <th class="pb-2">Stok Barang</th>
                                        <td class="pb-2">: <span class="text-primary">{{ $barang->stock }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="pb-2">Stok Tersedia</th>
                                        <td class="pb-2">: <span class="text-success">{{ $barang->current_stock }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="pb-2">Kategori</th>
                                        <td class="pb-2">:
                                            @foreach ($barang->barangCategories as $item)
                                                <span class="badge badge-phoenix badge-phoenix-primary">
                                                    {{ $item->category->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-6">
                        <div class="card-body">
                            <table>
                                <tr>
                                    <th class="pb-2">Deskripsi</th>
                                    <td class="pb-2"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="overflow-hidden mb-1" style="max-height: 100px" id="description">
                                            {!! Str::markdown($barang->description) !!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <a href="#" id="read-more" onclick="toggleReadMore()">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h5 class="card-title mb-0">Buku Dipinjam</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $barangRented = $barang->barangRented()->paginate(10);
                            @endphp
                            @forelse ($barangRented as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ ($barangRented->currentPage() - 1) * $barangRented->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->rent->user->name }}</td>
                                    <td>{{ $item->rent->rent_date->format('d-m-Y') }}</td>
                                    <td>{{ $item->rent->return_date->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Buku tidak ada yang meminjam</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $barangRented->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function toggleReadMore() {
            const description = document.getElementById(`description`);
            const button = document.getElementById(`read-more`);

            if (description.style.maxHeight === '100px') {
                description.style.maxHeight = 'none';
                button.textContent = 'Lebih Sedikit';
            } else {
                description.style.maxHeight = '100px';
                button.textContent = 'Selengkapnya';
            }
        }
    </script>
@endpush
