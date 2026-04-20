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
        Schema::table('ic_item_mst', function (Blueprint $table) {
            $table->id()->first();
            $table->double('current_stock')->nullable()->after('item_usedby');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ic_item_mst', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('current_stock');
        });
    }
};
