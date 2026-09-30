<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sparepart_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_id')->constrained('spareparts')->restrictOnDelete();
            $table->enum('movement_type', ['masuk', 'keluar', 'penyesuaian']);
            // CHECK inline: 'ALTER TABLE ... ADD CONSTRAINT' hanya didukung PostgreSQL 18+.
            $table->rawColumn('quantity', 'integer not null CHECK (quantity > 0)');
            $table->foreignId('service_order_id')->nullable()
                ->constrained('service_orders')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('sparepart_id');
            $table->index('service_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sparepart_stock_movements');
    }
};
