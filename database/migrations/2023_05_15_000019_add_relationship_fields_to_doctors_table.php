<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDoctorsTable extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->foreign('clinic_id', 'clinic_fk_8467033')->references('id')->on('clinics');
        });
    }
}
