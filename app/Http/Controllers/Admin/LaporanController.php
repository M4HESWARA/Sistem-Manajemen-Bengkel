<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->month)
            : now();

        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        $prevMonthStart = $monthStart->copy()->subMonth()->startOfMonth();
        $prevMonthEnd = $monthStart->copy()->subMonth()->endOfMonth();

        // --- Kartu Ringkasan ---
        $totalPendapatan = Invoice::where('status', 'paid')
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('total_tagihan');

        $totalPendapatanBulanLalu = Invoice::where('status', 'paid')
            ->whereBetween('paid_at', [$prevMonthStart, $prevMonthEnd])
            ->sum('total_tagihan');

        $persenPerubahanPendapatan = $totalPendapatanBulanLalu > 0
            ? round((($totalPendapatan - $totalPendapatanBulanLalu) / $totalPendapatanBulanLalu) * 100)
            : ($totalPendapatan > 0 ? 100 : 0);

        $totalMotorServis = ServiceOrder::where('status', 'selesai')
            ->whereBetween('tanggal_selesai', [$monthStart, $monthEnd])
            ->count();

        $rataRataPerHari = $monthEnd->day > 0 ? round($totalMotorServis / $monthEnd->day, 1) : 0;

        $totalMasukBulanIni = ServiceOrder::whereBetween('tanggal_masuk', [$monthStart, $monthEnd])->count();
        $totalSelesaiDariMasukBulanIni = ServiceOrder::whereBetween('tanggal_masuk', [$monthStart, $monthEnd])
            ->where('status', 'selesai')
            ->count();
        $efisiensiRataRata = $totalMasukBulanIni > 0
            ? (int) round(($totalSelesaiDariMasukBulanIni / $totalMasukBulanIni) * 100)
            : 100;

        // --- Grafik Pendapatan 6 Bulan Terakhir ---
        $grafikLabels = [];
        $grafikValues = [];
        $grafikHighlightIndex = 5;

        for ($i = 5; $i >= 0; $i--) {
            $bulan = $monthStart->copy()->subMonths($i);
            $grafikLabels[] = $bulan->translatedFormat('M');
            $grafikValues[] = (float) Invoice::where('status', 'paid')
                ->whereBetween('paid_at', [$bulan->copy()->startOfMonth(), $bulan->copy()->endOfMonth()])
                ->sum('total_tagihan');
        }

        // --- Analitik Kinerja Mekanik ---
        $mekanikList = User::where('role', 'mekanik')->where('is_active', true)->get();

        $kinerjaMekanik = $mekanikList->map(function (User $mekanik) use ($monthStart, $monthEnd) {
            $ordersSelesai = ServiceOrder::where('mechanic_id', $mekanik->id)
                ->where('status', 'selesai')
                ->whereBetween('tanggal_selesai', [$monthStart, $monthEnd])
                ->get(['tanggal_masuk', 'tanggal_selesai']);

            $totalServis = $ordersSelesai->count();

            $rataRataMenit = $totalServis > 0
                ? (int) round($ordersSelesai->avg(fn ($o) => $o->tanggal_masuk->diffInMinutes($o->tanggal_selesai)))
                : 0;

            $totalAssigned = ServiceOrder::where('mechanic_id', $mekanik->id)
                ->whereBetween('tanggal_masuk', [$monthStart, $monthEnd])
                ->count();

            $skorEfisiensi = $totalAssigned > 0
                ? (int) round(($totalServis / $totalAssigned) * 100)
                : 0;

            return [
                'nama' => $mekanik->full_name,
                'total_servis' => $totalServis,
                'rata_rata_menit' => $rataRataMenit,
                'skor_efisiensi' => $skorEfisiensi,
            ];
        })->sortByDesc('total_servis')->values();

        return view('admin.laporan.index', compact(
            'month', 'totalPendapatan', 'persenPerubahanPendapatan',
            'totalMotorServis', 'rataRataPerHari', 'efisiensiRataRata',
            'grafikLabels', 'grafikValues', 'grafikHighlightIndex',
            'kinerjaMekanik'
        ));
    }
}
