<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToClinicsTable extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable();
            $table->foreign('package_id', 'package_fk_8467031')->references('id')->on('packages');
            $table->unsignedBigInteger('clinic_admin_id')->nullable();
            $table->foreign('clinic_admin_id', 'clinic_admin_fk_8467032')->references('id')->on('users');
            $table->unsignedBigInteger('domain_id')->nullable();
            $table->foreign('domain_id', 'domain_fk_8589002')->references('id')->on('domains');
        });
    }
}
