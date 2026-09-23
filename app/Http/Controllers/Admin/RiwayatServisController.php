<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class RiwayatServisController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::with(['vehicle', 'customer', 'mechanic', 'invoice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle', fn ($vq) => $vq->where('plat_nomor', 'like', "%{$search}%"))
                    ->orWhereHas('customer', fn ($cq) => $cq->where('full_name', 'like', "%{$search}%"))
                    ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('tanggal_masuk', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('tanggal_masuk', '<=', $request->to);
        }

        $riwayat = $query->orderByDesc('tanggal_masuk')->paginate(15)->withQueryString();

        return view('admin.riwayat.index', compact('riwayat'));
    }
}
