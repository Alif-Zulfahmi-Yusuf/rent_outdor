@extends('frontend.layouts.app')

@section('title', $barang->title)

@section('content')
    <div class="">
        <section class="landing-hero">
            <div class="container">
                <nav aria-label="breadcrumb" class="mb-6 d-none d-lg-block">
                    <ol class="breadcrumb bg-transparent mb-0 py-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barangs') }}">Barang</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $barang->title }}</li>
                    </ol>
                </nav>
                <div class="row">
                    <div class="col-lg-4 py-image mb-6">
                        <img src="{{ asset('storage/' . $barang->image) }}" alt="" class="w-100">
                    </div>
                    <div class="col-lg-8 mb-6">
                        <h1 class="fw-bold mb-6 fs-2 lh-base">{{ $barang->title }}</h1>
                        <div class="mb-6">
                            <div class="h5 mb-3 fw-semibold">Ketersediaan</div>
                            <div class="d-flex gap-2">
                                <div class="text-danger d-flex align-items-center gap-1">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    <span>{{ $barang->rented }}</span>
                                </div>
                                <div class="text-success d-flex align-items-center  gap-1">
                                    <i class="fas fa-arrow-circle-down"></i>
                                    <span>{{ $barang->current_stock }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="h5 mb-3 fw-semibold">Kategori</div>
                            <div class="d-flex gap-2">
                                @foreach ($barang->barangCategories as $item)
                                    <span
                                        class="badge badge-phoenix badge-phoenix-primary">{{ $item->category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="h5 mb-3 fw-semibold">Deskripsi</div>
                            <p class="mb-0">{!! Str::limit($barang->description, 300) !!} <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalDeskripsi">Baca selengkapnya</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-py">
            <div class="container">
                <div class="mb-6">
                    <h4 class="mb-0">Buku
                        <span class="position-relative fw-extrabold z-1">Terkait
                            <img src="{{ asset('assets/img/favicons/favicon-32x32.png') }}" alt="laptop charging"
                                class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                        </span>
                    </h4>
                </div>
                <div class="row">
                    @foreach ($relatedBarangs as $item)
                        <div class="col-lg-2 col-md-3 col-6 mb-6">
                            <a href="{{ route('barangs.show', $item->slug) }}" class="card p-4 h-100">
                                <div class="ratio" style="--bs-aspect-ratio: calc(4 / 3 * 100%);">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt=""
                                        class="w-100 rounded object-fit-cover">
                                </div>
                                <div class="mt-4">
                                    <small style="font-size: .7rem">{{ $item->author }}</small>
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
        <div class="container position-fixed d-none d-lg-block" style="bottom: 30px; left: 0px; right: 0px; z-index: 999">
            <div class="card shadow-lg">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $barang->cover) }}" alt="" width="60"
                                class="rounded">
                            <div class="ms-4">
                                <small class="mb-1">{{ $barang->author }}</small>
                                <h1 class="fw-semibold fs-5 lh-base mb-2">{{ $barang->title }}</h1>
                                <div class="d-flex gap-2">
                                    <div class="text-danger d-flex align-items-center gap-1">
                                        <i class="ti ti-book-upload"></i>
                                        <span>{{ $barang->rented }}</span>
                                    </div>
                                    <div class="text-success d-flex align-items-center  gap-1">
                                        <i class="ti ti-book-2"></i>
                                        <span>{{ $barang->current_stock }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <form action="{{ route('bags.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2 ms-n1"></i>Kantongi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="position-fixed d-block d-lg-none bg-white p-4 bottom-0 right-0 left-0 w-100 shadow-lg"
            style="z-index: 999">
            <form action="{{ route('bags.store') }}" method="post">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-shopping-bag me-2 ms-n1"></i>Kantongi
                </button>
            </form>
        </div>
        <div class="modal fade" id="modalDeskripsi" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content pb-4">
                    <div class="modal-header pb-4">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Deskripsi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0" style="max-height: 400px">
                        {!! Str::markdown($barang->description) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
