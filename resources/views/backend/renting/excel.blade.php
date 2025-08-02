@if ($from && $to)
<table>
    <tr>
        <td>Dari</td>
        <td>: {{ $from->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td>Sampai</td>
        <td>: {{ $to->format('d-m-Y') }}</td>
    </tr>
</table>
@endif
<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kode Unik</th>
            <th rowspan="2">Nama Peminjam</th>
            <th rowspan="2">Pinjam</th>
            <th rowspan="2">Kembali</th>
            <th rowspan="2">Dikembalikan</th>
            <th colspan="2">Daftar Buku</th> 
            <th rowspan="2">Denda</th>
        </tr>
        <tr>
            <th>Judul</th>
            <th>Hilang</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $renting)
            <tr>
                <td rowspan="{{ $renting->rentItems->count() }}">{{ $loop->iteration }}</td>
                <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->code }}</td>
                <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->user->name }}</td>
                <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->rent_date->format('d-m-Y') }}</td>
                <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->return_date->format('d-m-Y') }}</td>
                <td rowspan="{{ $renting->rentItems->count() }}">{{ $renting->actual_return_date ? $renting->actual_return_date->format('d-m-Y') : 'Belum dikembalikan' }}</td>
                <td>{{ $renting->rentItems[0]->book->title }}</td>
                <td>{{ $renting->rentItems[0]->is_lost ? 'Ya' : 'Tidak' }}</td>
                <td rowspan="{{ $renting->rentItems->count() }}">Rp {{ number_format($renting->pinalty, 0, ',', '.') }}</td>
            </tr>
            @foreach ($renting->rentItems->skip(1) as $rentItem)
                <tr>
                    <td>{{ $rentItem->book->title }}</td>
                    <td>{{ $rentItem->is_lost ? 'Ya' : 'Tidak' }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8">Total</th>
            <th>Rp {{ number_format($data->sum('pinalty'), 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>