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
        Schema::create('transferrecords', function (Blueprint $table) {
            $table->id();
            $table->string('drug_name');
            $table->string('clinic_from');
            $table->string('sender');
            $table->string('drug_amount');
            $table->string('clinic_to');
            $table->string('reciever');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferrecords');
    }
};
