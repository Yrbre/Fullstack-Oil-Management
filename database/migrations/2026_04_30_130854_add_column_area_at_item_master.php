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
            $table->string('orgn_code')->nullable()->after('item_desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ic_item_mst', function (Blueprint $table) {
            $table->dropColumn('orgn_code');
        });
    }
};
