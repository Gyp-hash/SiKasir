<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Penjualan</title>
<style>
    body { font-family: sans-serif; font-size: 11px; color: #333; }
    h1   { font-size: 15px; text-align: center; margin-bottom: 2px; }
    .sub { text-align: center; color: #666; margin-bottom: 14px; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th    { background: #222; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
    td    { padding: 5px 8px; border-bottom: 1px solid #e0e0e0; }
    tr:nth-child(even) td { background: #f7f7f7; }
    .text-right { text-align: right; }
    .summary { margin-top: 14px; text-align: right; font-size: 11px; }
    .summary strong { font-size: 13px; }
    .footer { margin-top: 20px; font-size: 9px; color: #aaa; text-align: center; }
</style>
</head>
<body>
<h1>SiKasir Angkringan</h1>
<p class="sub">Laporan Penjualan &bull; {{ $dateFrom }} s/d {{ $dateTo }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th class="text-right">Total (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $i => $trx)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $trx->code }}</td>
            <td>{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
            <td>{{ $trx->user->name ?? '-' }}</td>
            <td class="text-right">{{ number_format($trx->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="summary">
    Jumlah Transaksi: <strong>{{ $summary['count'] }}</strong> &nbsp;|&nbsp;
    Total Penjualan: <strong>Rp {{ number_format($summary['total'], 0, ',', '.') }}</strong>
</div>

<p class="footer">Dicetak pada {{ now()->format('d M Y, H:i') }}</p>
</body>
</html>
