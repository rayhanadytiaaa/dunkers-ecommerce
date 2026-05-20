<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:6px; }
        th { background:#eee; }
        tr {
            text-align: center;
        }
        tbody{
            text-align: center;
        }
    </style>
</head>
<body>

<h3>Riwayat Transaksi Dunkers</h3>

@if($r->from && $r->to)
<p>Periode: {{ $r->from }} - {{ $r->to }}</p>
@endif

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->user->name ?? '-' }}</td>
            <td>{{ $item->tanggal->format('d/m/Y') }}</td>
            <td>
                @foreach($item->detail as $d)
                    {{ $d->produk->nama ?? '-' }}<br>
                @endforeach
            </td>
            <td>
                @foreach($item->detail as $d)
                    {{ $d->jumlah }}<br>
                @endforeach
            </td>
            <td>
                Rp {{ number_format($item->total,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
