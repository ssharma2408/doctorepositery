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
         Schema::table('family', function (Blueprint $table) {
			$table->unsignedBigInteger('owner_id')->nullable()->change();
			$table->foreign('owner_id', 'patient_fk_8602717')->references('id')->on('patients');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
