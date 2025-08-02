@extends('frontend.layouts.app')

@push('css')
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
@endpush

@section('title', 'Kantong')

@section('content')
    <div class="">
        <section class="landing-hero">
            <div class="container">
                <h4 class="fw-extrabold mb-6">Kantong</h4>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8 mb-6">
                        <div class="card p-4 shadow-sm ">
                            @forelse ($bags as $index => $bag)
                                {!! $index == 0 ? '' : '<hr>' !!}
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $bag->barang->image) }}" alt=""
                                            width="50" class="rounded">
                                        <div class="ms-4">
                                            <h1 class="fw-semibold fs-6 lh-base mb-2">{{ $bag->barang->title }}</h1>
                                            <div class="d-flex gap-2">
                                                <div class="text-danger d-flex align-items-center gap-1">
                                                    <i class="ti ti-book-upload fs-6"></i>
                                                    <span class="fs-6">{{ $bag->barang->rented }}</span>
                                                </div>
                                                <div class="text-success d-flex align-items-center gap-1">
                                                    <i class="ti ti-book-2 fs-6"></i>
                                                    <span class="fs-6">{{ $bag->barang->current_stock }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('bags.destroy', $bag->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-label-danger"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus buku ini dari kantong?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <dotlottie-player
                                    src="https://lottie.host/5c9c6639-fdec-4c7c-b7a9-ea3f2a2f6fb7/zZTCvdeIgu.lottie"
                                    background="transparent" speed="1" style="width: 100%; height: 300px;" loop
                                    autoplay></dotlottie-player>
                                <p class="mb-0 text-center">Yahh, kantong mu masih kosong :(</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-lg-4 mb-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="fw-extrabold">Keterangan Pinjam</div>
                                <div class="mt-3 d-flex flex-column gap-2">
                                    <div class="d-flex gap-2">
                                        <div class="fw-medium">Total Barang</div>
                                        <div class="ms-auto fw-medium">{{ $bags->count() }} Barang</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="fw-medium">Durasi Pinjam</div>
                                        <div class="ms-auto fw-medium">Maksimal {{ $settings['max_rent_day'] }} hari</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="fw-medium">Kembalikan Pada</div>
                                        <div class="ms-auto fw-medium">{{ $return_date }}</div>
                                    </div>
                                </div>
                                <hr>
                                <button {{ $bags->count() == 0 ? 'disabled' : '' }} class="btn btn-primary w-100"
                                    data-bs-toggle="modal" data-bs-target="#modalPinjam">
                                    <i class="ti ti-book-upload me-2 ms-n1"></i>
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-py">
            <div class="container">
                <div class="mb-6">
                    <h4 class="mb-0">Barang
                        <span class="position-relative fw-extrabold z-1">Populer

                        </span>
                    </h4>
                </div>
                <div class="row">
                    @foreach ($popularBarangs as $item)
                        <div class="col-lg-2 col-md-3 col-6 mb-6">
                            <a href="{{ route('barangs.show', $item->slug) }}" class="card p-4 h-100">
                                <div class="ratio" style="--bs-aspect-ratio: calc(4 / 3 * 100%);">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt=""
                                        class="w-100 rounded object-fit-cover">
                                </div>
                                <div class="mt-4">
                                    <h6 style="font-size: .8rem" class="lh-base mb-2">{{ Str::limit($item->title, 50) }}
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress" role="progressbar"
                                            aria-valuenow="{{ $item->stock_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100" style="height: 10px; width: 100%">
                                            <div class="progress-bar" style="width: {{ $item->stock_percentage }}%"></div>
                                        </div>
                                        <div class="text-danger d-flex align-items-center gap-1" style="font-size: .7rem">
                                            <i class="ti ti-book-upload" style="font-size: .9rem"></i>
                                            <span>{{ $item->rented }}</span>
                                        </div>
                                        <div class="text-success d-flex align-items-center  gap-1" style="font-size: .7rem">
                                            <i class="ti ti-book-2" style="font-size: .9rem"></i>
                                            <span>{{ $item->current_stock }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <div class="modal fade" id="modalPinjam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold text-danger" id="exampleModalLabel">
                            <i class="uil-exclamation-octagon me-2"></i>Peraturan Peminjaman
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-4">Peraturan yang berlaku di {{ config('app.name') }} ini adalah sebagai
                            berikut:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex gap-2 py-3">
                                <i class="uil-exclamation-triangle text-warning fs-5"></i>
                                <div>
                                    <p class="mb-0">Denda telat perhari Rp.
                                        {{ number_format($settings['late_fee_per_day'], 0, ',', '.') }},- jika telat
                                        mengembalikan.</p>
                                    <p class="mb-0">Denda barang hilang Rp.
                                        {{ number_format($settings['lost_fee'], 0, ',', '.') }},-.</p>
                                </div>
                            </li>
                            <li class="list-group-item d-flex gap-2 py-3">
                                <i class="uil-clock-five text-primary fs-5"></i>
                                <div>
                                    <p class="mb-0">Jika meminjam hari ini, maka estimasi pengembalian adalah
                                        {{ $settings['max_rent_day'] }} hari dari sekarang. Yaitu pada tanggal
                                        {{ $return_date }}</p>
                                </div>
                            </li>
                            <li class="list-group-item d-flex gap-2 py-3">
                                <i class="uil-clipboard-alt text-primary fs-5"></i>
                                <div>
                                    <p class="mb-0">Maksimal jumlah peminjaman barang adalah
                                        {{ $settings['max_book_per_rent'] }} barang per orang.</p>
                                </div>
                            </li>
                        </ul>
                        <p class="mt-4">Silahkan perhatikan peraturan peminjaman di atas sebelum meminjam barang. Terima
                            kasih.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('rents.store') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary">Pinjam Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
