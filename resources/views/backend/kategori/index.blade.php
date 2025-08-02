@extends('backend.layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Kategori</h4>
                    <p class="mb-0">Semua kategori buku yang ada di {{ config('app.name') }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-outline-success d-flex align-items-center" role="alert">
                    <span class="fas fa-check-circle text-success fs-5 me-3"></span>
                    <p class="mb-0 flex-1">{!! session('success') !!}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-6">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="input-group input-group-merge h-100">
                                    <input type="search" class="form-control search-input search form-control-sm"
                                        placeholder="Cari..." name="search" value="{{ request('search') }}"
                                        aria-label="Cari..." aria-describedby="basic-addon-search31">
                                </div>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <button class="btn btn-primary h-100" type="submit"><i
                                        class="fas fa-search search-box-icon"></i></button>
                            </div>
                            <div class="col-md-7">
                                <button type="button" class="btn btn-primary float-start float-md-end"
                                    data-bs-toggle="modal" data-bs-target="#tambahKategori">Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th class="text-center">Total Barang</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td class="text-center">{{ $item->barangCategories->count() }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#editKategori-{{ $item->id }}"
                                                class="btn btn-sm btn-icon text-primary shadow-sm"><i
                                                    class="fas fa-edit edit-box-icon"></i></button>
                                            <button type="submit" form="delete-{{ $item->id }}"
                                                class="btn btn-sm btn-icon text-danger shadow-sm"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i
                                                    class="fas fa-trash trash-box-icon"></i></button>
                                        </div>
                                        <form action="{{ route('panel.categories.destroy', $item->id) }}" method="post"
                                            id="delete-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editKategori-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kategori</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('panel.categories.update', $item->id) }}"
                                                    method="post" id="formUpdateKategori-{{ $item->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Kategori</label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ $item->name ?? old('name') }}">
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" form="formUpdateKategori-{{ $item->id }}"
                                                    class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">Tidak ada data yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $data->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambahKategori" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('panel.categories.store') }}" method="post" id="formTambahKategori">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="formTambahKategori" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
