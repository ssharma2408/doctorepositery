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
        Schema::table('tokens', function (Blueprint $table) {
            Schema::table('tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('timing_id')->nullable();
            $table->foreign('timing_id', 'timing_fk_8602717')->references('id')->on('timings');
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {
            //
        });
    }
};
