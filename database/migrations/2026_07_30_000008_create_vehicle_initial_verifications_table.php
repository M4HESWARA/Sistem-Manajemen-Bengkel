<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_initial_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->unique()
                ->constrained('service_orders')->cascadeOnDelete();
            $table->text('odometer_photo_url');
            $table->integer('odometer_reading');
            $table->text('fuel_photo_url')->nullable();
            $table->enum('fuel_level', ['empty', 'quarter', 'half', 'three_quarter', 'full']);
            $table->boolean('lampu_utama_ok')->default(true);
            $table->boolean('lampu_sein_ok')->default(true);
            $table->boolean('check_engine_ok')->default(true);
            $table->boolean('lampu_abs_ok')->default(true);
            $table->boolean('lampu_oli_ok')->default(true);
            $table->text('catatan_kondisi')->nullable();
            $table->foreignId('verified_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('verified_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_initial_verifications');
    }
};
