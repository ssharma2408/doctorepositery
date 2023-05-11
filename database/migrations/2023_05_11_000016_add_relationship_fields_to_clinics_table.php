<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToClinicsTable extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->unsignedBigInteger('package_ids_id')->nullable();
            $table->foreign('package_ids_id', 'package_ids_fk_8460740')->references('id')->on('packages');
            $table->unsignedBigInteger('clinic_adminid_id')->nullable();
            $table->foreign('clinic_adminid_id', 'clinic_adminid_fk_8466815')->references('id')->on('users');
        });
    }
}
