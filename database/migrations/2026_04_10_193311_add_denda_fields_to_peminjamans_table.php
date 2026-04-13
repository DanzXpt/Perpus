<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('peminjamans', function (Blueprint $table) {

            if (!Schema::hasColumn('peminjamans', 'sisa_denda')) {
                $table->integer('sisa_denda')->default(0);
            }

            // status_denda SUDAH ADA → jangan ditambah lagi
        });
    }

    public function down()
    {
        Schema::table('peminjamans', function (Blueprint $table) {

            if (Schema::hasColumn('peminjamans', 'sisa_denda')) {
                $table->dropColumn('sisa_denda');
            }

        });
    }
};
