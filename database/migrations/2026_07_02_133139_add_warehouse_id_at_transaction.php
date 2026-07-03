<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ic_trans_inv', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id')->nullable()->after('whse_loc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ic_trans_inv', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
        });
    }
};
