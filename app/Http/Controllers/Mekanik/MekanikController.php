<?php

namespace App\Http\Controllers\Mekanik;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\Sparepart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MekanikController extends Controller
{
    /**
     * Halaman workspace mekanik: daftar tugas (kiri) + detail tugas terpilih (kanan),
     * sekaligus dalam satu halaman (mirip pola halaman Kasir).
     */
    public function dashboard(Request $request)
    {
        $mekanikId = Auth::id();

        $tugasAktif = ServiceOrder::with(['vehicle', 'customer'])
            ->where('mechanic_id', $mekanikId)
            ->whereIn('status', ['proses_diagnosa', 'sedang_diperbaiki'])
            ->orderBy('tanggal_masuk')
            ->get();

        $tugasSelesaiHariIni = ServiceOrder::where('mechanic_id', $mekanikId)
            ->where('status', 'selesai')
            ->whereDate('tanggal_selesai', today())
            ->count();

        // Order yang sedang ditampilkan detailnya di panel kanan
        $selectedOrder = null;
        if ($request->filled('order')) {
            $selectedOrder = $tugasAktif->firstWhere('id', (int) $request->order);
        }
        if (! $selectedOrder) {
            $selectedOrder = $tugasAktif->first();
        }

        $riwayat = collect();
        $spareparts = collect();

        if ($selectedOrder) {
            $selectedOrder->load(['verification', 'items.sparepart', 'jasaItems']);

            $riwayat = ServiceOrder::where('vehicle_id', $selectedOrder->vehicle_id)
                ->where('id', '!=', $selectedOrder->id)
                ->where('status', 'selesai')
                ->orderByDesc('tanggal_selesai')
                ->limit(5)
                ->get();

            $spareparts = Sparepart::where('is_active', true)->orderBy('name')->get();
        }

        // Riwayat singkat per kendaraan di daftar kiri (untuk tombol "Lihat Riwayat" tiap kartu)
        $riwayatPerOrder = $tugasAktif->mapWithKeys(function (ServiceOrder $order) {
            return [$order->id => ServiceOrder::where('vehicle_id', $order->vehicle_id)
                ->where('id', '!=', $order->id)
                ->where('status', 'selesai')
                ->orderByDesc('tanggal_selesai')
                ->limit(5)
                ->get()];
        });

        return view('mekanik.dashboard', compact(
            'tugasAktif', 'tugasSelesaiHariIni', 'selectedOrder', 'riwayat', 'spareparts', 'riwayatPerOrder'
        ));
    }

    /**
     * Update status: menunggu -> proses_diagnosa -> sedang_diperbaiki.
     */
    public function updateStatus(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $this->authorizeOwnership($serviceOrder);

        $request->validate([
            'status' => ['required', 'in:proses_diagnosa,sedang_diperbaiki'],
        ]);

        $serviceOrder->update(['status' => $request->status]);

        return back()->with('success', 'Status servis berhasil diperbarui.');
    }

    /**
     * Tambah pemakaian sparepart - otomatis potong stok & validasi stok cukup.
     */
    public function tambahSparepart(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $this->authorizeOwnership($serviceOrder);

        $request->validate([
            'sparepart_id' => ['required', 'exists:spareparts,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $serviceOrder->items()->create([
                'sparepart_id' => $request->sparepart_id,
                'quantity' => $request->quantity,
                'input_by' => Auth::id(),
            ]);
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['sparepart_id' => $e->getMessage()]);
        }

        return back()->with('success', 'Sparepart berhasil ditambahkan ke servis ini.');
    }

    /**
     * Hapus pemakaian sparepart (mis. salah input) - stok otomatis dikembalikan.
     */
    public function hapusSparepart(ServiceOrder $serviceOrder, ServiceOrderItem $item): RedirectResponse
    {
        $this->authorizeOwnership($serviceOrder);
        abort_if($item->service_order_id !== $serviceOrder->id, 404);

        $item->delete();

        return back()->with('success', 'Sparepart berhasil dihapus, stok dikembalikan.');
    }

    /**
     * Tambah rincian biaya jasa.
     */
    public function tambahJasa(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $this->authorizeOwnership($serviceOrder);

        $request->validate([
            'deskripsi' => ['required', 'string', 'max:255'],
            'biaya' => ['required', 'numeric', 'min:0'],
        ]);

        $serviceOrder->jasaItems()->create([
            'deskripsi' => $request->deskripsi,
            'biaya' => $request->biaya,
            'input_by' => Auth::id(),
        ]);

        return back()->with('success', 'Biaya jasa berhasil ditambahkan.');
    }

    /**
     * Tandai servis selesai, catat solusi.
     * Wajib sudah ada minimal 1 rincian jasa atau sparepart, supaya Kasir
     * punya dasar tagihan yang jelas (mencegah servis "kosong" tanpa rincian).
     */
    public function selesaikan(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $this->authorizeOwnership($serviceOrder);

        if ($serviceOrder->jasaItems()->count() === 0 && $serviceOrder->items()->count() === 0) {
            return back()->withErrors([
                'catatan_solusi' => 'Servis belum bisa diselesaikan. Tambahkan minimal 1 biaya jasa atau sparepart terlebih dahulu.',
            ]);
        }

        $request->validate([
            'catatan_solusi' => ['required', 'string'],
        ]);

        $serviceOrder->update([
            'status' => 'selesai',
            'catatan_solusi' => $request->catatan_solusi,
        ]);

        return redirect()->route('mekanik.dashboard')
            ->with('success', "Servis {$serviceOrder->order_number} berhasil diselesaikan.");
    }

    /**
     * Pastikan mekanik cuma bisa akses order yang jadi tugasnya sendiri.
     * Sesuai PRD: mekanik tidak boleh mengakses data yang bukan miliknya.
     */
    private function authorizeOwnership(ServiceOrder $serviceOrder): void
    {
        abort_if($serviceOrder->mechanic_id !== Auth::id(), 403, 'Order ini bukan tugas Anda.');
    }
}
