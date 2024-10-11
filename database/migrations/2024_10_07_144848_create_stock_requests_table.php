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
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->integer('item_quantity');
            $table->string('item_number');
            $table->string('clinic');
            $table->string('requester');
            $table->string('status');
            $table->string('approver')->nullable();
            $table->dateTime('Date Requested');
            $table->dateTime('Date approved')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_requests');
    }
};
