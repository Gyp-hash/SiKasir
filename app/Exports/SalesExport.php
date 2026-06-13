<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnFormatting
{
    public function __construct(
        private readonly string $dateFrom,
        private readonly string $dateTo,
    ) {}

    public function collection()
    {
        return Transaction::with('user:id,name')
            ->where('status', Transaction::STATUS_PAID)
            ->whereDate('transaction_date', '>=', $this->dateFrom)
            ->whereDate('transaction_date', '<=', $this->dateTo)
            ->latest('transaction_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Kasir',
            'Subtotal (Rp)',
            'Diskon (Rp)',
            'Total (Rp)',
        ];
    }

    private int $no = 0;

    public function map($row): array
    {
        $this->no++;

        return [
            $this->no,
            $row->code,
            $row->transaction_date->format('d/m/Y H:i'),
            $row->user->name ?? '-',
            (float) $row->subtotal,
            (float) $row->discount,
            (float) $row->total,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp" #,##0',
            'F' => '"Rp" #,##0',
            'G' => '"Rp" #,##0',
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
        return 'Laporan Penjualan';
    }
}
