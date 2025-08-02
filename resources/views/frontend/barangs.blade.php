@extends('frontend.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
@endpush

@section('title', 'Barang')

@section('content')
    <div class="">
        <section class="landing-hero">
            <div class="container">
                <h4 class="fw-extrabold">Semua Barang</h4>
                <form action="" method="get" class="pb-6 border-bottom">
                    <div class="row">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="input-group input-group-merge h-100">
                                <input type="search" class="form-control" placeholder="Cari Barang.." name="search"
                                    value="{{ request('search') }}" aria-label="Cari..."
                                    aria-describedby="basic-addon-search31">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <select name="category" class="select2 form-select">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->slug }}"
                                        {{ request('category') == $item->slug ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <button class="btn btn-primary h-100 w-md-100" type="submit"><i
                                    class="fas fa-search"></i></button>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </form>
                <div class="mb-5 pt-6">
                    @if (request('search') || request('category'))
                        <h6 class="mb-6">
                            {!! request('search') ? 'Menampilkan barang  <strong>"' . request('search') . '"</strong>.' : '' !!}
                            {!! request('category')
                                ? 'Kategori <strong>"' . $categories->where('slug', request('category'))->first()->name . '"</strong>.'
                                : '' !!}
                        </h6>
                    @endif
                    <div class="row">
                        @forelse ($barangs as $barang)
                            <div class="col-lg-2 col-md-3 col-6 mb-6">
                                <a href="{{ route('barangs.show', $barang->slug) }}" class="card p-4 h-100">
                                    <div class="card-img-top">
                                        <img src="{{ asset('storage/' . $barang->image) }}" alt=""
                                            class="img-fluid h-100 rounded-start">
                                    </div>
                                    <div class="mt-4">
                                        <small style="font-size: .7rem">{{ $barang->author }}</small>
                                        <h6 style="font-size: .8rem" class="lh-base mb-2">
                                            {{ Str::limit($barang->title, 50) }}
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress" role="progressbar"
                                                aria-valuenow="{{ $barang->stock_percentage }}" aria-valuemin="0"
                                                aria-valuemax="100" style="height: 10px; width: 100%">
                                                <div class="progress-bar" style="width: {{ $barang->stock_percentage }}%">
                                                </div>
                                            </div>
                                            <div class="text-danger d-flex align-items-center gap-1"
                                                style="font-size: .7rem">
                                                <i class="fas fa-arrow-circle-up" style="font-size: .9rem"></i>
                                                <span>{{ $barang->rented }}</span>
                                            </div>
                                            <div class="text-success d-flex align-items-center  gap-1"
                                                style="font-size: .7rem">
                                                <i class="fas fa-arrow-circle-down" style="font-size: .9rem"></i>
                                                <span>{{ $barang->current_stock }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-lg-4 offset-lg-4 mt-6">
                                <dotlottie-player
                                    src="https://lottie.host/5c9c6639-fdec-4c7c-b7a9-ea3f2a2f6fb7/zZTCvdeIgu.lottie"
                                    background="transparent" speed="1" style="width: 100%;" loop
                                    autoplay></dotlottie-player>
                                <p class="mb-0 text-center">Maaf, barang yang kamu cari tidak ditemukan</p>
                            </div>
                        @endforelse
                    </div>
                    {{ $barangs->links() }}
                </div>
            </div>
        </section>
        <section class="section-py"></section>
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
                    placeholder: "Pilih kategori",
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }
    </script>
@endpush
