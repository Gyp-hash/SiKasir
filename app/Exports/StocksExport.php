<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StocksExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        private readonly string $dateFrom,
        private readonly string $dateTo,
        private readonly ?string $productId = null,
        private readonly ?string $type      = null,
    ) {}

    public function collection()
    {
        return StockMovement::with(['product:id,name', 'creator:id,name'])
            ->when($this->productId, fn ($q) => $q->where('product_id', $this->productId))
            ->when($this->type,      fn ($q) => $q->where('type', $this->type))
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Produk',
            'Jenis',
            'Qty',
            'Stok Sebelum',
            'Stok Sesudah',
            'Dicatat Oleh',
            'Catatan',
        ];
    }

    private int $no = 0;

    public function map($row): array
    {
        $this->no++;

        return [
            $this->no,
            $row->created_at->format('d/m/Y H:i'),
            $row->product->name  ?? '-',
            $row->type,
            $row->quantity,
            $row->stock_before,
            $row->stock_after,
            $row->creator->name ?? '-',
            $row->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Stok';
    }
}
