<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->string('plat_nomor', 15)->unique();
            $table->string('brand', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->smallInteger('year')->nullable();
            $table->string('color', 30)->nullable();
            $table->timestamps();

            $table->index('plat_nomor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
