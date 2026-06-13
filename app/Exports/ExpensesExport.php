<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpensesExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnFormatting
{
    public function __construct(
        private readonly string $dateFrom,
        private readonly string $dateTo,
        private readonly ?string $category = null,
    ) {}

    public function collection()
    {
        return Expense::with('creator:id,name')
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->whereDate('expense_date', '>=', $this->dateFrom)
            ->whereDate('expense_date', '<=', $this->dateTo)
            ->latest('expense_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kategori',
            'Keterangan',
            'Nominal (Rp)',
            'Dicatat Oleh',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->expense_date->format('d/m/Y'),
            $row->category,
            $row->description,
            (float) $row->amount,
            $row->creator->name ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp" #,##0',
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
        return 'Laporan Pengeluaran';
    }
}
