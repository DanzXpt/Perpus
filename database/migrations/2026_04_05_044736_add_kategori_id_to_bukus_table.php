<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            // Tambahkan kolom kategori_id setelah kolom judul (atau di mana saja)
            // constrained() otomatis bikin foreign key ke tabel kategoris
            $table->foreignId('kategori_id')->nullable()->after('judul')->constrained('kategoris')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};
