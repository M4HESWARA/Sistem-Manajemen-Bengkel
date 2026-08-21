<?php

namespace App\Observers;

use App\Models\ServiceOrder;
use App\Models\ServiceStatusHistory;
use Illuminate\Support\Facades\Auth;

/**
 * Menggantikan trigger database utk mencatat riwayat perubahan status,
 * dan otomatis mengisi tanggal_selesai saat status menjadi 'selesai'.
 */
class ServiceOrderObserver
{
    public function created(ServiceOrder $order): void
    {
        $this->logStatus($order, $order->status);
    }

    public function updating(ServiceOrder $order): void
    {
        if ($order->isDirty('status')) {
            if ($order->status === 'selesai' && ! $order->tanggal_selesai) {
                $order->tanggal_selesai = now();
            }
        }
    }

    public function updated(ServiceOrder $order): void
    {
        if ($order->wasChanged('status')) {
            $this->logStatus($order, $order->status);
        }
    }

    private function logStatus(ServiceOrder $order, ?string $status): void
    {
        ServiceStatusHistory::create([
            'service_order_id' => $order->id,
            'status' => $status ?? 'menunggu',
            'changed_by' => Auth::id() ?? $order->mechanic_id ?? $order->admin_id,
            'changed_at' => now(),
        ]);
    }
}