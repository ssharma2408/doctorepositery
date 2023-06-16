<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('clinic_user', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_id');
            $table->foreign('clinic_id', 'clinic_id_fk_8634612')->references('id')->on('clinics')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_8634612')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
