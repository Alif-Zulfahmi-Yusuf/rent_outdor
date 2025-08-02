@extends('backend.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('title', 'Peminjam')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Peminjam</h4>
                    <p class="mb-0">Semua daftar peminjam yang ada di {{ config('app.name') }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {!! session('success') !!}
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
                                <select name="status" class="select2 form-select">
                                    <option value="">Pilih status</option>
                                    <option {{ request('status') == '1' ? 'selected' : '' }} value="1">Aktif</option>
                                    <option {{ request('status') == '0' ? 'selected' : '' }} value="0">Nonaktif
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <button class="btn btn-primary h-100" type="submit"><i
                                        class="fas fa-search search-box-icon"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>WhatsApp</th>
                                <th>Alamat</th>
                                <th class="text-center">Status</th>
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
                                    <td>{{ $item->phone ?? '-' }}</td>
                                    <td>{{ Str::limit($item->address, 20) ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-label-{{ $item->is_active == true ? 'success' : 'danger' }}">
                                            {{ $item->is_active == 1 ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('panel.renters.show', $item->id) }}"
                                                class="btn btn-sm btn-icon text-success shadow-sm"><i
                                                    class="ti ti-eye"></i></a>
                                            <form action="{{ route('panel.renters.update', $item->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-icon text-primary shadow-sm"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah status data ini?')">
                                                    <i
                                                        class="ti ti-{{ $item->is_active == true ? 'user-x' : 'user-check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('panel.renters.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon text-danger shadow-sm"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="7">Tidak ada data yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $data->appends(['search' => request('search'), 'status' => request('status')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        const select2 = $('.select2')
        if (select2.length) {
            select2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Pilih status",
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }
    </script>
@endpush
