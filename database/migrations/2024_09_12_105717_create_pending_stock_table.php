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
        Schema::create('pending_stock', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->integer('item_quantity');
            $table->string('item_number');
            $table->string('procurer');
            $table->string('status');
            $table->string('reciever')->nullable();
            $table->string('recieved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_stock');
    }
};
