<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceOrderActionController extends Controller
{
    /**
     * Assign mekanik ke sebuah order servis langsung dari dashboard,
     * dan otomatis pindahkan status ke 'proses_diagnosa'.
     */
    public function assignMechanic(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        $request->validate([
            'mechanic_id' => ['required', 'exists:users,id'],
        ]);

        $serviceOrder->update([
            'mechanic_id' => $request->mechanic_id,
            'status' => 'proses_diagnosa',
        ]);

        return back()->with('success', 'Mekanik berhasil di-assign ke order ' . $serviceOrder->order_number);
    }
}
