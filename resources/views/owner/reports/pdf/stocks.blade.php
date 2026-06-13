<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Stok</title>
<style>
    body { font-family: sans-serif; font-size: 10px; color: #333; }
    h1   { font-size: 15px; text-align: center; margin-bottom: 2px; }
    .sub { text-align: center; color: #666; margin-bottom: 14px; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th    { background: #222; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; }
    td    { padding: 5px 8px; border-bottom: 1px solid #e0e0e0; }
    tr:nth-child(even) td { background: #f7f7f7; }
    .text-center { text-align: center; }
    .badge-in   { background:#198754; color:#fff; padding:2px 6px; border-radius:4px; font-size:9px; }
    .badge-out  { background:#dc3545; color:#fff; padding:2px 6px; border-radius:4px; font-size:9px; }
    .badge-adj  { background:#ffc107; color:#333; padding:2px 6px; border-radius:4px; font-size:9px; }
    .footer { margin-top: 20px; font-size: 9px; color: #aaa; text-align: center; }
</style>
</head>
<body>
<h1>Sikasir Angkringan</h1>
<p class="sub">Laporan Stok &bull; {{ $dateFrom }} s/d {{ $dateTo }}{{ $type ? " &bull; Jenis: {$type}" : '' }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th class="text-center">Jenis</th>
            <th class="text-center">Qty</th>
            <th class="text-center">Sblm</th>
            <th class="text-center">Ssdh</th>
            <th>User</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movements as $i => $mv)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $mv->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $mv->product->name ?? '-' }}</td>
            <td class="text-center">
                @if ($mv->type === 'IN')
                    <span class="badge-in">Masuk</span>
                @elseif ($mv->type === 'OUT')
                    <span class="badge-out">Keluar</span>
                @else
                    <span class="badge-adj">Penyesuaian</span>
                @endif
            </td>
            <td class="text-center">{{ $mv->quantity }}</td>
            <td class="text-center">{{ $mv->stock_before }}</td>
            <td class="text-center">{{ $mv->stock_after }}</td>
            <td>{{ $mv->creator->name ?? '-' }}</td>
            <td>{{ $mv->notes ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p class="footer">Dicetak pada {{ now()->format('d M Y, H:i') }}</p>
</body>
</html>
