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
        Schema::create('transfer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->nullable()->constrained('ic_item_mst')->onDelete('cascade');
            $table->double('requested_qty')->nullable();
            $table->foreignId('source_warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->date('requested_date')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_requests');
    }
};
