@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    <div data-bs-spy="scroll" class="scrollspy-example">

        <!-- Real customers reviews: Start -->
        <section id="landingReviews" class="section-py landing-reviews pb-0">

            <div class="container-medium text-center mb-11 position-relative">

                <h3 class="mb-2 text-body-emphasis">Barang Terpopuler</h3>
                <p class="text-body-tertiary mb-0">Lihat barang populer di {{ config('app.name') }}<br
                        class="d-none d-xl-block" />
                    dan pinjam secepat kilat</p>
            </div>
            <!-- What people say slider: Start -->
            <div class="container-fluid px-sm-0">
                <div class="swiper-theme-container swiper-slide-nav-top">
                    <div class="swiper-nav">
                        <div class="swiper-button-next"><span class="fas fa-chevron-right text-primary"
                                data-fa-transform="shrink-3"></span></div>
                        <div class="swiper-button-prev"><span class="fas fa-chevron-left text-primary"
                                data-fa-transform="shrink-3"></span></div>
                    </div>
                    <div class="swiper theme-slider"
                        data-swiper='{"loop":true,"centeredSlides":true,"autoplay":true,"centeredSlidesBounds":true,"spaceBetween":16,"slidesPerView":1,"speed":1500,"breakpoints":{"576":{"slidesPerView":"auto"}}}'>
                        <div class="swiper-wrapper">
                            @foreach ($popularBarangs as $barang)
                                <div class="swiper-slide w-sm-auto">
                                    <a class="position-relative rounded-3 overflow-hidden d-block"
                                        href="{{ route('barangs.show', $barang->slug) }}">
                                        <img class="w-100 w-sm-auto object-fit-cover"
                                            src="{{ asset('storage/' . $barang->image) }}" alt="" height="220" />
                                        <div class="img-backdrop-faded">
                                            <div class="image-reveal-content mb-3">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h6 class="mb-0 text-secondary-lighter fw-semibold">
                                                        {{ Str::limit($barang->title, 50) }}</h6>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
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
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" role="progressbar"
                                                    aria-valuenow="{{ $barang->stock_percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100" style="height: 10px; width: 100%">
                                                    <div class="progress-bar"
                                                        style="width: {{ $barang->stock_percentage }}%"></div>
                                                </div>
                                                <h4 class="mb-0 text-white">{{ $barang->stock_percentage }}%</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- What people say slider: End -->
        </section>
        <!-- Real customers reviews: End -->

        <!-- FAQ: Start -->
        <section id="landingFAQ" class="section-py bg-body landing-faq">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-6">
                    <h4 class="mb-0">
                        Barang
                        <span class="position-relative fw-extrabold z-1">Terbaru
                            <span class="fas fa-angle-double-right position-absolute"></span>
                        </span>
                    </h4>
                    <a href="{{ route('barangs') }}">Lainnya <i class="ti ti-arrow-right"></i></a>
                </div>
                <div class="row">
                    @foreach ($barangs as $barang)
                        <div class="col-lg-2 col-md-3 col-6 mb-6">
                            <a href="{{ route('barangs.show', $barang->slug) }}" class="card p-4 h-100">
                                <div class="card-img-top" style="--bs-aspect-ratio: calc(4 / 3 * 100%);">
                                    <img src="{{ asset('storage/' . $barang->image) }}" alt=""
                                        class="w-100 rounded object-fit-cover">
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
                                        <div class="text-danger d-flex align-items-center gap-1" style="font-size: .7rem">
                                            <i class="fas fa-arrow-circle-down" style="font-size: .9rem"></i>
                                            <span>{{ $barang->rented }}</span>
                                        </div>
                                        <div class="text-success d-flex align-items-center  gap-1" style="font-size: .7rem">
                                            <i class="fas fa-arrow-circle-down" style="font-size: .9rem"></i>
                                            <span>{{ $barang->current_stock }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- FAQ: End -->
    </div>
@endsection
