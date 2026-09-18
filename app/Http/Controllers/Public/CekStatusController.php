<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CekStatusController extends Controller
{
    /**
     * Halaman publik: cari riwayat & status servis kendaraan berdasarkan Nomor Polisi.
     * Tanpa login, sesuai PRD (Portal Pelanggan).
     */
    public function index(Request $request)
    {
        $vehicle = null;
        $serviceOrders = collect();
        $searched = $request->filled('plat_nomor');

        if ($searched) {
            $platNomor = strtoupper(trim($request->plat_nomor));

            $vehicle = Vehicle::with('customer')->where('plat_nomor', $platNomor)->first();

            if ($vehicle) {
                $serviceOrders = ServiceOrder::where('vehicle_id', $vehicle->id)
                    ->with(['mechanic', 'invoice'])
                    ->orderByDesc('tanggal_masuk')
                    ->get();
            }
        }

        return view('public.cek-status', compact('vehicle', 'serviceOrders', 'searched'));
    }
}
