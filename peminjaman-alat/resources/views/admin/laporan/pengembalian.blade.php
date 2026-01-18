<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengembalian</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .header-info { margin-bottom: 20px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Pengembalian Alat</h1>
    <div class="header-info">
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Dikembalikan</th>
                <th>Alat</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDenda = 0; @endphp
            @forelse($pengembalians as $pengembalian)
                @php $totalDenda += $pengembalian->denda; @endphp
                <tr>
                    <td>{{ $pengembalian->id }}</td>
                    <td>{{ $pengembalian->peminjaman->user->name }}</td>
                    <td>{{ $pengembalian->tanggal_dikembalikan->format('d/m/Y') }}</td>
                    <td>
                        @foreach($pengembalian->peminjaman->detailPeminjaman as $detail)
                            {{ $detail->alat->nama_alat }} ({{ $detail->jumlah }})<br>
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Denda:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

