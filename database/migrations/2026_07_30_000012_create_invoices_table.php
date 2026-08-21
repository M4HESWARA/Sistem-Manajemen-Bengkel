<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->unique(); // mis. INV-20260730-0001
            $table->foreignId('service_order_id')->unique()
                ->constrained('service_orders')->restrictOnDelete();
            $table->decimal('total_jasa', 14, 2)->default(0);
            $table->decimal('total_sparepart', 14, 2)->default(0);
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'debit', 'credit', 'qris', 'transfer'])->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('status');
        });

        // total_tagihan dihitung otomatis oleh database
        DB::statement('ALTER TABLE invoices ADD COLUMN total_tagihan NUMERIC(14,2) GENERATED ALWAYS AS (total_jasa + total_sparepart) STORED');
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
