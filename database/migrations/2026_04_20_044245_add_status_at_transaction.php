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
            $table->string('status')->default('null')->after('item_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ic_trans_inv', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
