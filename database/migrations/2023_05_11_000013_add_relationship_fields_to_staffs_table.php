<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToStaffsTable extends Migration
{
    public function up()
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->unsignedBigInteger('clinic_ids_id')->nullable();
            $table->foreign('clinic_ids_id', 'clinic_ids_fk_8465752')->references('id')->on('clinics');
        });
    }
}
