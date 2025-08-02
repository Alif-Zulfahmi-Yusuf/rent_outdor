@extends('backend.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('title', 'Edit Barang')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Barang</h4>
                    <p class="mb-0">Edit spesifikasi dan detail barang</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('panel.barangs.index') }}" class="btn btn-label-primary">Kembali</a>
                    <button type="submit" class="btn btn-primary" form="formUpdateBarang">Update</button>
                </div>
            </div>

            <form action="{{ route('panel.barangs.update', $barang->id) }}" method="post" enctype="multipart/form-data"
                id="formUpdateBarang">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title">Detail Barang</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Kode Barang</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        name="code" value="{{ $barang->code ?? old('code') }}">
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Judul Barang</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ $barang->title ?? old('title') }}">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Stok Barang</label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                            name="stock" value="{{ $barang->stock ?? old('stock') }}">
                                        @error('stock')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="10">{{ $barang->description ?? old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title">Kategori Barang</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select @error('category') is-invalid @enderror" name="category[]"
                                    multiple data-choices="data-choices">
                                    @foreach ($categories as $item)
                                        <option
                                            {{ $barang->barangCategories->where('category_id', $item->id)->first() ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card mb-6">
                            <div class="card-header">
                                <h5 class="card-title">Image Barang</h5>
                            </div>
                            <div class="card-body">
                                <img src="{{ asset('storage/' . $barang->image) }}" width="100%" alt=""
                                    class="mb-4">
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                    placeholder: 'Pilih kategori',
                    dropdownParent: $this.parent(),
                    minimumResultsForSearch: Infinity,
                    search: function() {
                        var term = $(this).val();
                        if (term.length < 2) {
                            return false;
                        }
                    }
                });
            });
        }
    </script>
@endpush
