@extends('backend.layouts.app')

@section('title', 'Tambah Admin')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Tambah Admin</h4>
                    <p class="mb-0">Tambahkan admin yang dapat mengakses panel</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <a href="{{ route('panel.admins.index') }}" class="btn btn-label-primary">Kembali</a>
                    <button type="submit" class="btn btn-primary" form="formTambahAdmin">Simpan</button>
                </div>
            </div>

            <form action="{{ route('panel.admins.store') }}" method="post" id="formTambahAdmin">
                @csrf
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label><span class="text-danger">*</span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label><span class="text-danger">*</span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Konfirmasi Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection