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
        Schema::create('clinic_patient', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_id');
            $table->foreign('clinic_id', 'clinic_id_fk_8634612')->references('id')->on('clinics')->onDelete('cascade');
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id', 'patient_id_fk_8634612')->references('id')->on('patients')->onDelete('cascade');
        });
    }
    
};
