<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceOrder;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan form pendaftaran servis baru.
     */
    public function create()
    {
        return view('admin.pendaftaran.create');
    }

    /**
     * Endpoint AJAX: cari kendaraan berdasarkan nomor polisi.
     * Dipakai untuk auto-isi data pelanggan/kendaraan jika sudah pernah servis.
     */
    public function cariKendaraan(Request $request): JsonResponse
    {
        $platNomor = strtoupper(trim((string) $request->query('plat_nomor')));

        $vehicle = Vehicle::with('customer')->where('plat_nomor', $platNomor)->first();

        if (! $vehicle) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'vehicle' => [
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'color' => $vehicle->color,
            ],
            'customer' => [
                'full_name' => $vehicle->customer->full_name,
                'phone' => $vehicle->customer->phone,
                'address' => $vehicle->customer->address,
            ],
            'riwayat_count' => $vehicle->serviceOrders()->count(),
        ]);
    }

    /**
     * Simpan pendaftaran servis baru: pelanggan, kendaraan, order servis,
     * dan verifikasi fisik awal, sekaligus.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15'],
            'brand' => ['nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],

            'nama_pelanggan' => ['required', 'string', 'max:150'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],

            'keluhan_awal' => ['required', 'string'],

            'odometer_photo' => ['required', 'image', 'max:4096'],
            'odometer_reading' => ['required', 'integer', 'min:0'],
            'fuel_photo' => ['nullable', 'image', 'max:4096'],
            'fuel_level' => ['required', Rule::in(['empty', 'quarter', 'half', 'three_quarter', 'full'])],
            'catatan_kondisi' => ['nullable', 'string'],
        ]);

        // Cari atau buat pelanggan berdasarkan nomor HP
        $customer = Customer::firstOrCreate(
            ['phone' => $data['no_hp']],
            ['full_name' => $data['nama_pelanggan'], 'address' => $data['alamat'] ?? null]
        );
        $customer->update([
            'full_name' => $data['nama_pelanggan'],
            'address' => $data['alamat'] ?? $customer->address,
        ]);

        // Cari atau buat kendaraan berdasarkan plat nomor
        $vehicle = Vehicle::updateOrCreate(
            ['plat_nomor' => strtoupper($data['plat_nomor'])],
            [
                'customer_id' => $customer->id,
                'brand' => $data['brand'] ?? null,
                'model' => $data['model'] ?? null,
                'year' => $data['year'] ?? null,
                'color' => $data['color'] ?? null,
            ]
        );

        // Buat order servis baru, masuk ke antrian "menunggu"
        $order = ServiceOrder::create([
            'order_number' => ServiceOrder::generateOrderNumber(),
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'admin_id' => auth()->id(),
            'keluhan_awal' => $data['keluhan_awal'],
            'status' => 'menunggu',
            'odometer_masuk' => $data['odometer_reading'],
            'tanggal_masuk' => now(),
        ]);

        // Simpan foto verifikasi fisik awal.
        // Disk 'public' bisa tidak writable (mis. hosting serverless/ephemeral),
        // dan disk itu dikonfigurasi throw=false sehingga store() tidak melempar
        // exception, hanya mengembalikan false. Nilai false akan ter-binding
        // sebagai integer 0 dan ditolak kolom bertipe text, jadi harus
        // dinormalkan ke null sebelum masuk database.
        $odometerPath = $this->storeVerificationPhoto($request->file('odometer_photo'));
        $fuelPath = $this->storeVerificationPhoto(
            $request->file('fuel_photo')
        );

        if ($odometerPath === null) {
            report(new \RuntimeException(
                "Foto odometer gagal disimpan pada disk 'public' untuk order {$order->order_number}."
            ));
        }

        $order->verification()->create([
            'odometer_photo_url' => $odometerPath ?? '',
            'odometer_reading' => $data['odometer_reading'],
            'fuel_photo_url' => $fuelPath,
            'fuel_level' => $data['fuel_level'],
            'lampu_utama_ok' => $request->boolean('lampu_utama_ok'),
            'lampu_sein_ok' => $request->boolean('lampu_sein_ok'),
            'check_engine_ok' => $request->boolean('check_engine_ok'),
            'lampu_abs_ok' => $request->boolean('lampu_abs_ok'),
            'lampu_oli_ok' => $request->boolean('lampu_oli_ok'),
            'catatan_kondisi' => $data['catatan_kondisi'] ?? null,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $message = "Servis {$order->order_number} untuk {$vehicle->plat_nomor} berhasil didaftarkan.";

        if ($odometerPath === null) {
            $message .= ' Peringatan: foto odometer gagal disimpan, mohon catat manual pada arsip fisik.';
        }

        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    /**
     * Simpan satu file foto verifikasi ke disk 'public'.
     *
     * Mengembalikan null bila file tidak ada atau gagal ditulis. Kegagalan
     * tidak boleh menggagalkan pendaftaran order, jadi sengaja tidak dilempar.
     */
    private function storeVerificationPhoto(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        try {
            $path = $file->store('verifikasi', 'public');
        } catch (\Throwable $e) {
            report($e);

            return null;
        }

        // store() mengembalikan false bila disk gagal menulis (throw=false).
        if (! is_string($path) || $path === '') {
            return null;
        }

        return $path;
    }
}
