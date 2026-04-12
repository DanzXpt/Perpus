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
            $table->integer('dibayar')->default(0);
            $table->integer('sisa_denda')->default(0);
            $table->string('status_denda')->default('nunggak');
        });
    }

    public function down()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn([
                'dibayar',
                'sisa_denda',
                'status_denda'
            ]);
        });
    }
};
