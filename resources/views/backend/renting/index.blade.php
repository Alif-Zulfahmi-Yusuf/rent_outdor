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
                                <th>Prediksi Keterlambatan</th>
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
                                            class="badge badge-phoenix-{{ $item->actual_return_date ? ($item->actual_return_date > $item->return_date ? 'warning' : 'success') : 'danger' }}">
                                            {{ $item->actual_return_date ? $item->actual_return_date->format('d-m-Y') : 'Belum Dikembalikan' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->lost_books }} barang</td>
                                    <td>Rp {{ number_format($item->pinalty, 0, ',', '.') }}</td>
                                    <td>
                                        @if (isset($predictions[$item->user_id]) && $predictions[$item->user_id] !== null)
                                            {{ $predictions[$item->user_id] }} hari
                                        @else
                                            <span class="text-muted">Belum ada data</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('panel.rentings.show', $item->id) }}"
                                                class="btn btn-sm btn-icon text-success shadow-sm"><i
                                                    class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon text-danger shadow-sm"
                                                onclick="confirmDelete({{ $item->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form action="{{ route('panel.rentings.destroy', $item->id) }}" method="post"
                                                id="delete-form-{{ $item->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="10">Tidak ada data yang tersedia.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus Data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
