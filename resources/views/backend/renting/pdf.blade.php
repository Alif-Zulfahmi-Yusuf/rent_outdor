<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} - Download Log Peminjaman</title>
    <style>
        td.text-left,
        th.text-left {
            text-align: left !important
        }

        td {
            text-align: center
        }

        h2 {
            margin-bottom: 0;
        }

        h3 {
            margin: 0 auto;
        }

        hr {
            margin: 20px auto
        }

        table {
            margin-bottom: 20px
        }
    </style>
</head>

<body>
    <div>
        <h2>Log Peminjaman Buku</h2>
        <h3>{{ config('app.name') }}</h3>
    </div>
    <hr>
    @if ($from && $to)
        <table>
            <tr>
                <td class="text-left">Dari</td>
                <td class="text-left">: {{ $from->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="text-left">Sampai</td>
                <td class="text-left">: {{ $to->format('d-m-Y') }}</td>
            </tr>
        </table>
    @endif
    <table style="width: 100%" border="1" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">No</th>
                <th rowspan="2">Kode Unik</th>
                <th rowspan="2">Nama Peminjam</th>
                <th rowspan="2">Pinjam</th>
                <th rowspan="2">Kembali</th>
                <th rowspan="2">Dikembalikan</th>
                <th colspan="2">Daftar Barang</th>
                <th rowspan="2">Denda</th>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <th>Hilang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $renting)
                <tr>
                    <td rowspan="{{ $renting->rentItems->count() }}" class="text-center">{{ $loop->iteration }}</td>
                    <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->code }}</td>
                    <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->user->name }}</td>
                    <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->rent_date->format('d-m-Y') }}</td>
                    <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->return_date->format('d-m-Y') }}</td>
                    <td rowspan="{{ $renting->rentItems->count() }}">
                        {{ $renting->actual_return_date ? $renting->actual_return_date->format('d-m-Y') : 'Belum dikembalikan' }}
                    </td>
                    <td class="text-left">{{ $renting->rentItems[0]->book->title }}</td>
                    <td>{{ $renting->rentItems[0]->is_lost ? 'Ya' : 'Tidak' }}</td>
                    <td class="text-left" rowspan="{{ $renting->rentItems->count() }}">Rp
                        {{ number_format($renting->pinalty, 0, ',', '.') }}</td>
                </tr>
                @foreach ($renting->rentItems->skip(1) as $rentItem)
                    <tr>
                        <td class="text-left">{{ $rentItem->book->title }}</td>
                        <td>{{ $rentItem->is_lost ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8">Total</th>
                <th class="text-left">Rp {{ number_format($data->sum('pinalty'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.history.back();
            };
        };
    </script>
</body>

</html>
