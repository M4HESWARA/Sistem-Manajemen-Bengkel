<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique(); // mis. SO-20260730-0001
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('admin_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('mechanic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('keluhan_awal');
            $table->enum('status', [
                'menunggu',
                'proses_diagnosa',
                'sedang_diperbaiki',
                'selesai',
                'dibatalkan',
            ])->default('menunggu');
            $table->smallInteger('priority')->default(0);
            $table->integer('queue_number')->nullable();
            $table->integer('odometer_masuk')->nullable();
            $table->text('catatan_solusi')->nullable();
            $table->timestamp('tanggal_masuk')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_masuk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
