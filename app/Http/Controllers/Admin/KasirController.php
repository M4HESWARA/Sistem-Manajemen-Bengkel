<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KasirController extends Controller
{
    /**
     * Halaman Kasir & Pembayaran: daftar antrian (kiri) + ringkasan tagihan
     * order yang dipilih (kanan), sekaligus dalam satu halaman.
     */
    public function index(Request $request)
    {
        $antrianSiapBayar = ServiceOrder::with(['vehicle', 'customer', 'invoice'])
            ->where('status', 'selesai')
            ->where(function ($q) {
                $q->whereDoesntHave('invoice')
                    ->orWhereHas('invoice', fn ($iq) => $iq->where('status', '!=', 'paid'));
            })
            ->orderBy('tanggal_selesai')
            ->get();

        // Daftar informatif: kendaraan yang masih dikerjakan (belum siap dibayar)
        $antrianProses = ServiceOrder::with(['vehicle', 'customer'])
            ->whereIn('status', ['proses_diagnosa', 'sedang_diperbaiki'])
            ->orderBy('tanggal_masuk')
            ->get();

        // Order yang sedang ditampilkan ringkasannya di panel kanan
        $selectedOrder = null;
        if ($request->filled('order')) {
            $selectedOrder = $antrianSiapBayar->firstWhere('id', (int) $request->order);
        }
        if (! $selectedOrder) {
            $selectedOrder = $antrianSiapBayar->first();
        }
        if ($selectedOrder) {
            $selectedOrder->load(['items.sparepart', 'jasaItems', 'mechanic']);
        }

        return view('admin.kasir.index', compact('antrianSiapBayar', 'antrianProses', 'selectedOrder'));
    }

    /**
     * Konfirmasi pembayaran: hitung total otomatis, buat/lunaskan invoice.
     */
    public function bayar(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', Rule::in(['cash', 'debit', 'credit', 'qris', 'transfer'])],
        ]);

        if ($serviceOrder->status !== 'selesai') {
            return back()->withErrors(['payment_method' => 'Servis ini belum berstatus selesai, belum bisa dibayar.']);
        }

        $invoice = Invoice::firstOrNew(['service_order_id' => $serviceOrder->id]);
        $invoice->invoice_number = $invoice->invoice_number ?: Invoice::generateInvoiceNumber();
        $invoice->total_jasa = $serviceOrder->totalJasa();
        $invoice->total_sparepart = $serviceOrder->totalSparepart();
        $invoice->status = 'paid';
        $invoice->payment_method = $request->payment_method;
        $invoice->paid_at = now();
        $invoice->created_by = auth()->id();
        $invoice->save();

        return redirect()->route('admin.kasir.nota', $serviceOrder)
            ->with('success', "Pembayaran {$serviceOrder->order_number} berhasil dikonfirmasi.");
    }

    /**
     * Nota/struk yang siap dicetak.
     */
    public function nota(ServiceOrder $serviceOrder)
    {
        $serviceOrder->load(['vehicle', 'customer', 'mechanic', 'items.sparepart', 'jasaItems', 'invoice']);

        abort_if(! $serviceOrder->invoice, 404, 'Belum ada pembayaran untuk order ini.');

        if (! $serviceOrder->invoice->printed_at) {
            $serviceOrder->invoice->update(['printed_at' => now()]);
        }

        return view('admin.kasir.nota', compact('serviceOrder'));
    }
}
