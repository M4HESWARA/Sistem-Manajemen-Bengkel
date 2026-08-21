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
            $table->integer('quantity');
            $table->decimal('harga_satuan', 14, 2); // snapshot harga jual saat transaksi
            $table->foreignId('input_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('service_order_id');
            $table->index('sparepart_id');
        });

        // subtotal dihitung otomatis oleh database (kolom generated)
        DB::statement('ALTER TABLE service_order_items ADD COLUMN subtotal NUMERIC(14,2) GENERATED ALWAYS AS (quantity * harga_satuan) STORED');
        DB::statement('ALTER TABLE service_order_items ADD CONSTRAINT chk_item_qty_positive CHECK (quantity > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('service_order_items');
    }
};
