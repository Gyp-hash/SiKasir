<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    // Kategori pengeluaran umum angkringan
    public const CATEGORIES = [
        'Bahan Baku',
        'Operasional',
        'Gaji Karyawan',
        'Sewa Tempat',
        'Listrik & Air',
        'Transportasi',
        'Peralatan',
        'Lain-lain',
    ];

    protected $fillable = [
        'expense_date',
        'category',
        'description',
        'amount',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount'       => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
