@extends('backend.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('title', 'Log Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Log Peminjaman</h4>
                    <p class="mb-0">Semua daftar peminjaman yang ada di {{ config('app.name') }}</p>
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
                                    <input type="search" class="form-control" placeholder="Kode Unik atau Nama Peminjam..."
                                        name="search" value="{{ request('search') }}" aria-label="Kode Unik..."
                                        aria-describedby="basic-addon-search31">
                                </div>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <select name="return" class="select2 form-select">
                                    <option value="">Pilih status</option>
                                    <option {{ request('return') == 'true' ? 'selected' : '' }} value="true">Belum
                                        Dikembalikan</option>
                                    <option {{ request('return') == 'false' ? 'selected' : '' }} value="false">Dikembalikan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary h-100" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('panel.rentings.kirim-pengingat') }}"
                                    class="btn btn-outline-warning h-100"
                                    onclick="return confirm('Yakin ingin mengirim pengingat ke semua peminjam?')">
                                    <i class="fas fa-paper-plane"></i>
                                </a>
                            </div>
                            <div class="col-md-5">
                                <button type="button" class="btn btn-primary float-start float-md-end"
                                    data-bs-toggle="modal" data-bs-target="#filterDownload"><i
                                        class="uil-cloud-download me-2 ms-n1"></i>Download</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm fs-9 mb-0">
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
                                    <td class="text-center">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
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
                                    <td>{{ $item->lost_books }} barang</td>
                                    <td>Rp {{ number_format($item->pinalty, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('panel.rentings.show', $item->id) }}"
                                                class="btn btn-sm btn-icon text-success shadow-sm"><i
                                                    class="ti ti-eye"></i></a>
                                            <form action="{{ route('panel.rentings.destroy', $item->id) }}" method="post">
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
                                    <td class="text-center" colspan="9">Tidak ada data yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $data->appends(['search' => request('search'), 'return' => request('return')])->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="filterDownload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Download</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('panel.rentings.download') }}" method="post" id="formDownload">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Dari</label>
                            <input type="date" name="from" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sampai</label>
                            <input type="date" name="to" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Format</label>
                            <select name="format" class="form-select select2-icons">
                                <option value="pdf" data-icon="ti ti-file-type-pdf">PDF</option>
                                <option value="excel" data-icon="ti ti-file-spreadsheet">Excel</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="formDownload" class="btn btn-primary">Download</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        const select2 = $('.select2')
        const select2Icons = $('.select2-icons')

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
        if (select2Icons.length) {
            function renderIcons(option) {
                if (!option.id) {
                    return option.text;
                }
                var $icon = "<i class='" + $(option.element).data('icon') + " me-2'></i>" + option.text;

                return $icon;
            }
            select2Icons.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: select2Icons.parent(),
                templateResult: renderIcons,
                templateSelection: renderIcons,
                escapeMarkup: function(es) {
                    return es;
                }
            });
        }
    </script>
@endpush
