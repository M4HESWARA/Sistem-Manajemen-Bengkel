<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password'); // password_hash
            $table->string('full_name', 150);
            $table->enum('role', ['admin', 'mekanik']);
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
