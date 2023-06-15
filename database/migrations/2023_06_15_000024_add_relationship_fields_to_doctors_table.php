<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDoctorsTable extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id', 'doctor_fk_8486999')->references('id')->on('users');
        });
    }
}
