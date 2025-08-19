<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengingat Pengembalian Barang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef1f5;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .email-header {
            background-color: rgb(230, 207, 7);
            padding: 20px;
            text-align: center;
        }

        .email-header img {
            max-width: 80px;
            heigt: 50px
        }

        .email-header h1 {
            color: #ffffff;
            font-size: 20px;
            margin: 0;
        }

        .email-body {
            padding: 30px;
            color: #333333;
        }

        .email-body h3 {
            margin-top: 0;
            color: #f1e206;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.6;
        }

        .email-footer {
            padding: 15px 30px;
            background-color: #f3f4f6;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Logo & Header -->
            <div class="email-header">
                <img src="{{ asset('assets/img/icons/logo.png') }}" alt="rent">
                <h1>Rent Outdor</h1>
            </div>
            <!-- Body -->
            <div class="email-body">
                <h3>Halo {{ $renting->user->name }},</h3>
                <p>
                    Ini adalah pengingat bahwa Anda memiliki pinjaman Barang yang belum dikembalikan.
                </p>
                <p>
                    Anda meminjam <strong>{{ $renting->rentItems->count() }}</strong> barang dalam transaksi ini.
                </p>
                @php
                    use Carbon\Carbon;

                    $now = Carbon::now();
                    $returnDate = Carbon::parse($renting->return_date)->startOfDay(); // hilangkan jam
                    $daysLeft = $now->startOfDay()->diffInDays($returnDate, false); // false agar bisa minus
                @endphp

                @if ($daysLeft > 0)
                    <p><strong>Waktu peminjaman Anda tinggal {{ $daysLeft }} hari lagi.</strong></p>
                @elseif ($daysLeft === 0)
                    <p><strong>Hari ini adalah batas terakhir pengembalian barang Anda.</strong></p>
                @else
                    <p><strong>Anda sudah melewati batas waktu pengembalian {{ abs($daysLeft) }} hari yang
                            lalu.</strong></p>
                @endif


                <p>Jika Anda telah mengembalikan barang, silakan abaikan pesan ini atau konfirmasi ulang kepada kami.
                </p>
                <p>Terima kasih atas perhatian dan kerja samanya.</p>
            </div>
            <!-- Footer -->
            <div class="email-footer">
                &copy; {{ now()->year }} Rent Outdor. Semua hak dilindungi.
            </div>
        </div>
    </div>
</body>

</html>
