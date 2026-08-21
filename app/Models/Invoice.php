<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'service_order_id',
        'total_jasa',
        'total_sparepart',
        'status',
        'payment_method',
        'paid_at',
        'printed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'total_jasa' => 'decimal:2',
            'total_sparepart' => 'decimal:2',
            'total_tagihan' => 'decimal:2',
            'paid_at' => 'datetime',
            'printed_at' => 'datetime',
        ];
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function markAsPaid(string $paymentMethod): void
    {
        $this->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ]);
    }

    // Generate nomor invoice berformat INV-YYYYMMDD-XXXX
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $lastNumber = static::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $sequence = $lastNumber ? ((int) substr($lastNumber, -4)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
