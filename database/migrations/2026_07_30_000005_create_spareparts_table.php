<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 50)->unique();
            $table->string('name', 150);
            $table->foreignId('category_id')->nullable()
                ->constrained('sparepart_categories')->nullOnDelete();
            $table->decimal('harga_beli', 14, 2)->default(0);
            $table->decimal('harga_jual', 14, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->string('satuan', 20)->default('pcs');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sku');
        });

        // Cegah stok negatif langsung di level database
        DB::statement('ALTER TABLE spareparts ADD CONSTRAINT chk_stok_non_negative CHECK (stok >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
