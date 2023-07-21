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
        Schema::create('family_log', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('patient_id')->nullable();
			$table->unsignedBigInteger('old_family_id')->nullable();
			$table->unsignedBigInteger('new_family_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_log');
    }
};
