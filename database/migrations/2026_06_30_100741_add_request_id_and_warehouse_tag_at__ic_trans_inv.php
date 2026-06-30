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
            $table->foreignId('request_id')->nullable()->after('id')->constrained('transfer_requests');
            $table->string('warehouse_tag')->nullable()->after('whse_loc');
            $table->string('trans_code')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ic_trans_inv', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn('request_id');
            $table->dropColumn('warehouse_tag');
            $table->dropColumn('trans_code');
        });
    }
};
