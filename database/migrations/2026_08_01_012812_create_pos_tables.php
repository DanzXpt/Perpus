<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Buat tabel categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Buat tabel products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 3. Buat tabel transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_code')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->decimal('total', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->decimal('cash', 15, 2);
            $table->decimal('change_amount', 15, 2);
            $table->timestamps();
        });

        // 4. Buat tabel transaction_details
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->decimal('price', 15, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 15, 2);
        });
    }

    public function down(): void {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};