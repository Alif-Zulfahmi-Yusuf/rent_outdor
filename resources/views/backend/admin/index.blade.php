@extends('backend.layouts.app')

@section('title', 'Admin')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Admin</h4>
                    <p class="mb-0">Semua daftar admin yang ada di {{ config('app.name') }}</p>
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
                    <form action="" method="get" class="position-relative">
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
                                <a href="{{ route('panel.admins.create') }}"
                                    class="btn btn-primary float-start float-md-end h-100">Tambah</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table w-100">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('panel.admins.edit', $item->id) }}"
                                                class="btn btn-sm btn-icon text-primary shadow-sm"><i
                                                    class="fas fa-edit edit-box-icon"></i></a>
                                            <form action="{{ route('panel.admins.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon text-danger shadow-sm"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash trash-box-icon"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="4">Tidak ada data yang tersedia.</td>
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
@endsection
