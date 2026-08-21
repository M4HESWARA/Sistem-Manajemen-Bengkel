<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_jasa_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->string('deskripsi', 255); // mis. "Ganti Oli", "Servis Rem"
            $table->decimal('biaya', 14, 2);
            $table->foreignId('input_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('service_order_id');
        });

        DB::statement('ALTER TABLE service_jasa_items ADD CONSTRAINT chk_biaya_non_negative CHECK (biaya >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('service_jasa_items');
    }
};
