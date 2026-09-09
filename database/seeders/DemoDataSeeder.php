<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\SparepartCategory;
use App\Models\Sparepart;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $mekanik = User::where('role', 'mekanik')->first();

        if (! $admin || ! $mekanik) {
            $this->command->warn('Jalankan UserSeeder dulu sebelum DemoDataSeeder.');
            return;
        }

        // --- Kategori & Sparepart (termasuk yang stoknya sengaja tipis) ---
        $kategoriPelumas = SparepartCategory::firstOrCreate(['name' => 'Pelumas']);
        $kategoriSparepart = SparepartCategory::firstOrCreate(['name' => 'Sparepart']);
        $kategoriKelistrikan = SparepartCategory::firstOrCreate(['name' => 'Kelistrikan']);

        $oli = Sparepart::firstOrCreate(
            ['sku' => 'OLI-YMLB'],
            ['name' => 'Oli Yamalube Matic', 'category_id' => $kategoriPelumas->id,
             'harga_beli' => 35000, 'harga_jual' => 50000, 'stok' => 2, 'stok_minimum' => 5, 'satuan' => 'botol']
        );
        $kampas = Sparepart::firstOrCreate(
            ['sku' => 'KMP-VARIO'],
            ['name' => 'Kampas Rem Depan Vario', 'category_id' => $kategoriSparepart->id,
             'harga_beli' => 25000, 'harga_jual' => 40000, 'stok' => 1, 'stok_minimum' => 3, 'satuan' => 'set']
        );
        $busi = Sparepart::firstOrCreate(
            ['sku' => 'BUS-C7HSA'],
            ['name' => 'Busi NGK C7HSA', 'category_id' => $kategoriKelistrikan->id,
             'harga_beli' => 12000, 'harga_jual' => 20000, 'stok' => 5, 'stok_minimum' => 8, 'satuan' => 'pcs']
        );
        Sparepart::firstOrCreate(
            ['sku' => 'OLI-FTS'],
            ['name' => 'Oli Federal Sport', 'category_id' => $kategoriPelumas->id,
             'harga_beli' => 30000, 'harga_jual' => 45000, 'stok' => 20, 'stok_minimum' => 5, 'satuan' => 'botol']
        );

        // --- Pelanggan & Kendaraan ---
        $c1 = Customer::firstOrCreate(['phone' => '081111111111'], ['full_name' => 'Andi Wijaya']);
        $v1 = Vehicle::firstOrCreate(['plat_nomor' => 'B1234ABC'], ['customer_id' => $c1->id, 'brand' => 'Yamaha', 'model' => 'NMAX']);

        $c2 = Customer::firstOrCreate(['phone' => '082222222222'], ['full_name' => 'Siti Rahma']);
        $v2 = Vehicle::firstOrCreate(['plat_nomor' => 'D5678DEF'], ['customer_id' => $c2->id, 'brand' => 'Honda', 'model' => 'Vario']);

        $c3 = Customer::firstOrCreate(['phone' => '083333333333'], ['full_name' => 'Rudi Hartono']);
        $v3 = Vehicle::firstOrCreate(['plat_nomor' => 'F9012GHI'], ['customer_id' => $c3->id, 'brand' => 'Suzuki', 'model' => 'Aerox']);

        $c4 = Customer::firstOrCreate(['phone' => '084444444444'], ['full_name' => 'Dewi Lestari']);
        $v4 = Vehicle::firstOrCreate(['plat_nomor' => 'L4455JKL'], ['customer_id' => $c4->id, 'brand' => 'Yamaha', 'model' => 'Mio']);

        // --- Order 1: menunggu mekanik ---
        ServiceOrder::firstOrCreate(
            ['order_number' => 'SO-DEMO-0001'],
            [
                'vehicle_id' => $v1->id, 'customer_id' => $c1->id, 'admin_id' => $admin->id,
                'keluhan_awal' => 'Ganti Oli, Servis Rutin', 'status' => 'menunggu',
                'tanggal_masuk' => today()->setTime(8, 30),
            ]
        );

        // --- Order 2: menunggu mekanik ---
        ServiceOrder::firstOrCreate(
            ['order_number' => 'SO-DEMO-0002'],
            [
                'vehicle_id' => $v2->id, 'customer_id' => $c2->id, 'admin_id' => $admin->id,
                'keluhan_awal' => 'Rem Bunyi, Ganti Kampas', 'status' => 'menunggu',
                'tanggal_masuk' => today()->setTime(9, 0),
            ]
        );

        // --- Order 3: sedang diperbaiki oleh mekanik ---
        ServiceOrder::firstOrCreate(
            ['order_number' => 'SO-DEMO-0003'],
            [
                'vehicle_id' => $v4->id, 'customer_id' => $c4->id, 'admin_id' => $admin->id,
                'mechanic_id' => $mekanik->id,
                'keluhan_awal' => 'Servis CVT', 'status' => 'sedang_diperbaiki',
                'tanggal_masuk' => today()->setTime(8, 0),
            ]
        );

        // --- Order 4: selesai & siap bayar ---
        $order4 = ServiceOrder::firstOrCreate(
            ['order_number' => 'SO-DEMO-0004'],
            [
                'vehicle_id' => $v3->id, 'customer_id' => $c3->id, 'admin_id' => $admin->id,
                'mechanic_id' => $mekanik->id,
                'keluhan_awal' => 'Servis rutin + ganti busi', 'status' => 'selesai',
                'odometer_masuk' => 12500, 'catatan_solusi' => 'Servis rutin selesai, busi diganti.',
                'tanggal_masuk' => today()->setTime(7, 30),
                'tanggal_selesai' => today()->setTime(9, 45),
            ]
        );

        if ($order4->jasaItems()->count() === 0) {
            $order4->jasaItems()->create([
                'deskripsi' => 'Jasa Servis Rutin', 'biaya' => 50000, 'input_by' => $admin->id,
            ]);
        }
        if ($order4->items()->count() === 0) {
            $order4->items()->create([
                'sparepart_id' => $busi->id, 'quantity' => 1,
                'harga_satuan' => $busi->harga_jual, 'input_by' => $mekanik->id,
            ]);
        }

        // --- Order 5: selesai & SUDAH dibayar (jadi tidak akan muncul di tab "Siap Bayar") ---
        $order5 = ServiceOrder::firstOrCreate(
            ['order_number' => 'SO-DEMO-0005'],
            [
                'vehicle_id' => $v2->id, 'customer_id' => $c2->id, 'admin_id' => $admin->id,
                'mechanic_id' => $mekanik->id,
                'keluhan_awal' => 'Ganti oli rutin', 'status' => 'selesai',
                'tanggal_masuk' => today()->setTime(7, 0),
                'tanggal_selesai' => today()->setTime(7, 40),
            ]
        );
        Invoice::firstOrCreate(
            ['service_order_id' => $order5->id],
            [
                'invoice_number' => 'INV-DEMO-0001', 'total_jasa' => 25000, 'total_sparepart' => 50000,
                'status' => 'paid', 'payment_method' => 'cash', 'paid_at' => today()->setTime(7, 45),
                'created_by' => $admin->id,
            ]
        );

        $this->command->info('Data demo dashboard berhasil dibuat.');
    }
}
