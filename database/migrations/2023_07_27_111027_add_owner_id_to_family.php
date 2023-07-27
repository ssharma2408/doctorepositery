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
            $table->unsignedBigInteger('owner_id');
            $table->longText('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family', function (Blueprint $table) {
            $table->dropColumn('owner_id');
            $table->dropColumn('address');
        });
    }
};
