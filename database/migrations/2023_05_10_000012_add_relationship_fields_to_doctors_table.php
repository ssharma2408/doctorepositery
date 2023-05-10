<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDoctorsTable extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_ids_id')->nullable();
            $table->foreign('clinic_ids_id', 'clinic_ids_fk_8460746')->references('id')->on('clinics');
        });
    }
}
