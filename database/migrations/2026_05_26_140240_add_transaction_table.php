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
        Schema::create('ic_trans_inv', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->nullable();
            $table->string('status', 20)->nullable();
            $table->string('item_no', 30)->nullable();
            $table->string('item_desc', 100)->nullable();
            $table->string('item_uom', 20)->nullable();
            $table->string('orgn_code', 20)->nullable();
            $table->string('whse_code', 20)->nullable();
            $table->string('whse_loc', 20)->nullable();
            $table->string('doc_type', 20)->nullable();
            $table->string('adj_type', 20)->nullable();
            $table->string('reason_code', 20)->nullable();
            $table->dateTime('creation_date')->nullable();
            $table->date('trans_date')->nullable();
            $table->string('tgl', 2)->nullable();
            $table->string('bln', 2)->nullable();
            $table->string('thn', 4)->nullable();
            $table->string('periode', 20)->nullable();
            $table->double('trans_qty')->nullable();
            $table->string('catatan', 200)->nullable();
            $table->double('bb_qty')->nullable();
            $table->double('in_qty')->nullable();
            $table->double('out_qty')->nullable();
            $table->double('eb_qty')->nullable();
            $table->string('created_by', 20)->nullable();
            $table->dateTime('update_date')->nullable();
            $table->string('update_by', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ic_trans_inv');
    }
};
