<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : today();

        // --- Kartu statistik ---
        $totalMotorSelesai = ServiceOrder::whereDate('tanggal_selesai', $date)
            ->where('status', 'selesai')
            ->count();

        $pendapatanHariIni = Invoice::where('status', 'paid')
            ->whereDate('paid_at', $date)
            ->sum('total_tagihan');

        $totalMasukHariIni = ServiceOrder::whereDate('tanggal_masuk', $date)->count();
        $totalSelesaiDariMasukHariIni = ServiceOrder::whereDate('tanggal_masuk', $date)
            ->where('status', 'selesai')
            ->count();

        // Skor efisiensi: persentase order yang masuk hari ini dan sudah selesai hari ini juga.
        // Kalau belum ada order masuk hari ini, ditampilkan 100% (belum ada beban tertunda).
        $skorEfisiensi = $totalMasukHariIni > 0
            ? (int) round(($totalSelesaiDariMasukHariIni / $totalMasukHariIni) * 100)
            : 100;

        // --- Status antrean servis ---
        $antrianMenunggu = ServiceOrder::with(['vehicle', 'customer'])
            ->where('status', 'menunggu')
            ->orderBy('priority')
            ->orderBy('tanggal_masuk')
            ->get();

        $antrianProses = ServiceOrder::with(['vehicle', 'customer', 'mechanic'])
            ->whereIn('status', ['proses_diagnosa', 'sedang_diperbaiki'])
            ->orderBy('tanggal_masuk')
            ->get();

        $antrianSiapBayar = ServiceOrder::with(['vehicle', 'customer', 'invoice'])
            ->where('status', 'selesai')
            ->where(function ($q) {
                $q->whereDoesntHave('invoice')
                    ->orWhereHas('invoice', fn ($iq) => $iq->where('status', '!=', 'paid'));
            })
            ->orderBy('tanggal_selesai')
            ->get();

        // --- Peringatan stok tipis ---
        $lowStockItems = Sparepart::lowStock()->orderBy('stok')->limit(5)->get();

        // --- Daftar mekanik aktif, untuk form assign cepat ---
        $mekanikList = \App\Models\User::where('role', 'mekanik')
            ->where('is_active', true)
            ->orderBy('full_name')
            ->get();

        return view('admin.dashboard', compact(
            'date',
            'totalMotorSelesai',
            'pendapatanHariIni',
            'skorEfisiensi',
            'antrianMenunggu',
            'antrianProses',
            'antrianSiapBayar',
            'lowStockItems',
            'mekanikList',
        ));
    }
}
