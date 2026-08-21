<?php

namespace App\Exceptions;

use Exception;

/**
 * Dilempar saat mekanik/admin mencoba memakai sparepart melebihi stok yang tersedia.
 * Sesuai PRD: "Sistem harus menolak transaksi jika stok sparepart yang diinput
 * mekanik tidak mencukupi (atau memberikan notifikasi)."
 */
class InsufficientStockException extends Exception
{
    public function __construct(
        public readonly string $sparepartName,
        public readonly int $available,
        public readonly int $requested,
    ) {
        parent::__construct(
            "Stok '{$sparepartName}' tidak mencukupi. Tersedia: {$available}, diminta: {$requested}."
        );
    }
}
