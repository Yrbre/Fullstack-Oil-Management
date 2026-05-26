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
        Schema::create('ic_item_mst', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->nullable();
            $table->string('item_no', 30)->nullable();
            $table->string('item_desc', 100)->nullable();
            $table->string('orgn_code', 10)->nullable();
            $table->string('item_uom', 10)->nullable();
            $table->integer('inactive_ind')->nullable();
            $table->string('item_glclass', 20)->nullable();
            $table->string('item_usedby', 20)->nullable();
            $table->double('current_stock')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ic_item_mst');
    }
};
