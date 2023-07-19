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
        Schema::table('family_log', function (Blueprint $table) {            
            $table->foreign('patient_id', 'patients_fk_8467031')->references('id')->on('patients');        
        });
    }    
};
