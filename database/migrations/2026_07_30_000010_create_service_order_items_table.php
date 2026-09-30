<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('sparepart_id')->constrained('spareparts')->restrictOnDelete();
            $table->rawColumn('quantity', 'integer not null CHECK (quantity > 0)');
            $table->decimal('harga_satuan', 14, 2); // snapshot harga jual saat transaksi
            $table->foreignId('input_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();

            // subtotal dihitung otomatis oleh database (kolom generated).
            // Pakai storedAs() agar portable lintas driver, bukan ALTER TABLE.
            $table->decimal('subtotal', 14, 2)->storedAs('quantity * harga_satuan');

            $table->index('service_order_id');
            $table->index('sparepart_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_order_items');
    }
};
