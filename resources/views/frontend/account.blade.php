@extends('frontend.layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="">
        <section class="landing-hero">
            <div class="container">
                <h4 class="fw-extrabold mb-6">Akun Saya</h4>

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
                    <div class="col-lg-3 mb-6">
                        <div class="card p-4 shadow-none border position-relative">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalAkun"
                                class="btn btn-sm btn-icon btn-label-primary position-absolute top-0 end-0"><i
                                    class="fas fa-edit"></i></button>
                            <div class="text-center">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded my-4"
                                    width="100px" />
                                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                            <div class="d-flex justify-content-around flex-wrap mb-6 gap-0 gap-md-3 gap-lg-4">
                                <div class="d-flex align-items-center gap-4 me-5">
                                    <div class="avatar">
                                        <div class="avatar-initial rounded badge-phoenix-primary">
                                            <i class="fas fa-box ti-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ Auth::user()->totalBarangs() }}</h5>
                                        <span>Barang</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="avatar">
                                        <div class="avatar-initial rounded badge-phoenix-primary">
                                            <i class="fas fa-dollar-sign ti-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Rp {{ number_format(Auth::user()->total_pinalty, 0, ',', '.') }}
                                        </h5>
                                        <span>Denda</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-container">
                                <h5 class="pb-4 border-bottom text-capitalize mt-6 mb-4">Detail</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="h6 me-1">WhatsApp:</span>
                                        <span>{{ Auth::user()->phone ?? '-' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="h6 me-1">Alamat:</span>
                                        <span>{{ Auth::user()->address ?? '-' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="h6 me-1">Status:</span>
                                        <span
                                            class="badge badge-phoenix-{{ Auth::user()->is_active ? 'success' : 'danger' }}">{{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </li>
                                    <li class="">
                                        <span class="h6 me-1">Password:</span>
                                        <span><a href="#" data-bs-toggle="modal" data-bs-target="#modalPassword"><i
                                                    class="ti ti-edit"></i></a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 mb-6">
                        <div class="card">
                            <h5 class="card-header fw-bold">Riwayat Peminjaman</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm fs-9 mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Jumlah Barang</th>
                                            <th>Pinjam</th>
                                            <th>Kembali</th>
                                            <th>Dikembalikan</th>
                                            <th>Denda</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rents = Auth::user()->rents()->latest()->paginate(10);
                                        @endphp
                                        @forelse ($rents as $rent)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($rents->currentPage() - 1) * $rents->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $rent->rentItems()->count() }} Barang</td>
                                                <td>{{ $rent->rent_date->format('d-m-Y') }}</td>
                                                <td>{{ $rent->return_date->format('d-m-Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-phoenix-{{ $rent->actual_return_date ? ($rent->actual_return_date > $rent->return_date ? 'warning' : 'success') : 'danger' }}">
                                                        {{ $rent->actual_return_date ? $rent->actual_return_date->format('d-m-Y') : 'Belum Dikembalikan' }}
                                                    </span>
                                                </td>
                                                <td>Rp {{ number_format($rent->pinalty, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#modalDetail{{ $rent->id }}"
                                                        class="btn btn-sm btn-icon shadow-sm text-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modalDetail{{ $rent->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                                                                Detail Peminjaman</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column gap-2">
                                                                <div class="d-flex gap-2">
                                                                    <div class="fw-medium">Kode Unik</div>
                                                                    <div class="ms-auto fw-medium">#{{ $rent->code }}
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <div class="fw-medium">Nama Peminjam</div>
                                                                    <div class="ms-auto fw-medium">{{ $rent->user->name }}
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <div class="fw-medium">Tanggal Pinjam</div>
                                                                    <div class="ms-auto fw-medium">
                                                                        {{ $rent->rent_date->format('d-m-Y') }}</div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <div class="fw-medium">Tanggal Kembali</div>
                                                                    <div class="ms-auto fw-medium">
                                                                        {{ $rent->return_date->format('d-m-Y') }}</div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <div class="fw-medium">Dikembalikan</div>
                                                                    <div class="ms-auto fw-medium">
                                                                        {{ $rent->actual_return_date ? $rent->actual_return_date->format('d-m-Y') : '-' }}
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex flex-column gap-2">
                                                                    <div class="d-flex gap-2">
                                                                        <div class="fw-medium">Total Denda</div>
                                                                        <div class="ms-auto fw-medium">Rp
                                                                            {{ number_format($rent->pinalty, 0, ',', '.') }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="d-flex flex-column gap-2">
                                                                <div class="fw-semibold mb-2">Daftar Barang</div>
                                                                @foreach ($rent->rentItems as $index => $rentItem)
                                                                    {!! $index > 0 ? '<hr class="my-1">' : '' !!}
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="{{ asset('storage/' . $rentItem->barang->image) }}"
                                                                            class="rounded" width="40">
                                                                        <div class="ms-3 text-truncate">
                                                                            <div class="fw-medium mb-1 text-truncate">
                                                                                {{ $rentItem->barang->title }}</div>
                                                                            <span
                                                                                class="badge fs-tiny bg-label-{{ $rent->actual_return_date
                                                                                    ? ($rentItem->is_lost
                                                                                        ? 'danger'
                                                                                        : ($rent->actual_return_date > $rent->return_date
                                                                                            ? 'warning'
                                                                                            : 'success'))
                                                                                    : 'danger' }}">
                                                                                {{ $rent->actual_return_date
                                                                                    ? ($rentItem->is_lost
                                                                                        ? 'Hilang'
                                                                                        : ($rent->actual_return_date > $rent->return_date
                                                                                            ? 'Telat'
                                                                                            : 'Dikembalikan'))
                                                                                    : 'Belum Dikembalikan' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <hr>
                                                            <p class="text-warning mb-0">
                                                                Perlu diingat bahwa kamu harus mengembalikan buku tepat
                                                                waktu untuk menghindari denda.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Kamu belum meminjam buku apapun
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                {{ $rents->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-py"></section>
        <div class="modal fade" id="modalAkun" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                            Edit Profil
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('account.update', Auth::user()->id) }}" method="post"
                            id="formUpdateAkun">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Nama</label><span class="text-danger">*</span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ Auth::user()->name }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">WhatsApp</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">+62</span>
                                    <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" placeholder="812-1234-1234" value="{{ Auth::user()->phone }}">
                                </div>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" rows="5" class="form-control @error('address') is-invalid @enderror">{{ Auth::user()->address }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" form="formUpdateAkun" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">
                            Ubah Password
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('account.updatePassword', Auth::user()->id) }}" method="post"
                            id="formUpdatePassword">
                            @csrf
                            @method('PUT')
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label">Password Lama</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <input type="password"
                                        class="form-control @error('old_password') is-invalid @enderror"
                                        name="old_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label">Password Baru</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label">Konfirmasi Password</label><span class="text-danger">*</span>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" form="formUpdatePassword" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
