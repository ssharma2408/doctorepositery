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
        Schema::create('dependent', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name');
			$table->integer('gender')->nullable();
			$table->string('dob')->nullable();
			$table->unsignedBigInteger('family_id')->nullable();
            $table->foreign('family_id', 'family_fk_8602717')->references('id')->on('family');
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependent');
    }
};
